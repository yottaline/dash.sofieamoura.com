<?php

namespace App\Http\Controllers;

use App\Mail\orderConfirmation;
use App\Mail\OrderCreated;
use App\Mail\OrderInvoice;
use App\Mail\OrderProforma;
use App\Models\Currency;
use App\Models\Location;
use App\Models\Retailer;
use App\Models\Retailer_address;
use App\Models\Season;
use App\Models\Ws_order;
use App\Models\Ws_orders_product;
use App\Models\Ws_product;
use App\Models\Ws_products_size;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendProformaInvoice;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;

class WsOrderController extends Controller
{
    protected $telegramService;

    function __construct(TelegramService $telegramService)
    {
        $this->middleware('auth');
        $this->telegramService = $telegramService;
    }

    function index()
    {
        $retailers   = Retailer::fetch(0, [['retailer_blocked', 1]]);
        $seasons     = Season::fetch(0, [['season_visible', 1]]);
        $currencies  = Currency::fetch(0, [['currency_visible', 1]]);
        $locations   = Location::fetch(0, [['location_visible', 1]]);
        return view('contents.wsOrders.index', compact('retailers', 'seasons', 'currencies', 'locations'));
    }

    function create()
    {
        $countries = Location::fetch(null, [['location_visible', '1']]);
        $retailers = Retailer::fetch(0);
        return view('contents.wsOrders.create', compact('countries', 'retailers'));
    }

    function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;
        if ($request->date)   $param[] = ['order_created', 'like', '%' . $request->date . '%'];
        if ($request->r_name) $param[] = ['retailer_fullName', 'like', '%' . $request->r_name . '%'];

        echo json_encode(Ws_order::fetch(0, $param, $limit, $lastId));
    }

    function getProduct(Request $request)
    {
        $product_name = $request->product;
        $param =  [['product_name', 'like', '%' . $product_name . '%']];
        echo json_encode(Ws_products_size::fetch(0, $param));
    }

    function submit(Request $request)
    {
        $ids = explode(',', $request->sizes);
        $qty = explode(',', $request->qty);
        // $disc = explode(',', $request->disc);
        // $amount = explode(',', $request->amount);

        // create and check retailer data
        $retailer = Retailer::fetch(0, [['retailer_email', $request->email]]);
        if (!count($retailer)) {
            $retailerParam = [
                'retailer_code' => uniqidReal(8),
                'retailer_email'    => $request->email,
                'retailer_password' => Hash::make('1234'),
                'retailer_fullName' => $request->name,
                'retailer_company'  => $request->biz,
                'retailer_phone'    => $request->phone ?? '',
                'retailer_country'  => $request->country,
                'retailer_province' => $request->province  ?? '',
                'retailer_city'     => $request->city ?? '',
                'retailer_address'  => $request->address ?? '',
                'retailer_created'  => Carbon::now(),
            ];

            $status = Retailer::submit($retailerParam, null);
            $retailer = Retailer::fetch(0, [['retailer_id', $status]]);
        }

        // $retailer_address = Retailer_address::fetch(0, [['address_retailer', $request->retailer_id]]);
        $ordSubtotal = $orderTotalDisc = $ordTotal = 0;
        $orderParam = [];
        $products  = Ws_products_size::fetch(0, null, $ids);
        foreach ($products as $p) {
            $indx = array_search($p->prodsize_id, $ids);
            if ($indx !== false) {
                $subtotal = $qty[$indx] * $p->prodsize_wsp;
                // $total    = $subtotal * $disc[$indx] / 100;
                $orderProductParam[] = [
                    'ordprod_product'       => $p->product_id,
                    'ordprod_size'          => $p->prodsize_id,
                    'ordprod_price'         => $p->prodsize_wsp,
                    'ordprod_request_qty'   => $qty[$indx],
                    'ordprod_subtotal'      => $subtotal,
                    'ordprod_total'         => $subtotal,
                    'ordprod_discount'      => 0,
                    'ordprod_served_qty'    => $qty[$indx]
                ];
                $ordSubtotal    += $subtotal;
                $ordTotal       += $subtotal;
            }
        }

        $orderParam = [
            'order_code'          => uniqidReal(12),
            'order_season'        => 1,
            'order_retailer'      => $retailer[0]->retailer_id,
            'order_shipping'      => 0,
            'order_subtotal'      => $ordSubtotal,
            'order_discount'      => 0,
            'order_total'         => $ordTotal,
            'order_currency'      => 1,
            'order_type'          => 2,
            'order_note'          => $request->note,
            'order_created'       => Carbon::now(),
            'order_proforma'      => uniqidReal(20),
            'order_proformatime'  => Carbon::now(),
            'order_invoice'       => uniqidReal(30),
            'order_invoicetime'   => Carbon::now(),
            'order_status'        => 2,
            'order_bill_country'  => $retailer[0]->retailer_country,
            'order_bill_province' => $retailer[0]->retailer_province,
            'order_bill_city'     => $retailer[0]->retailer_city,
            'order_bill_zip'      => '',
            'order_bill_line1'    => $retailer[0]->retailer_address,
            'order_bill_line2'    => '',
            'order_bill_phone'    => $retailer[0]->retailer_phone,
            'order_ship_country'  => $retailer[0]->retailer_country,
            'order_ship_province' => $retailer[0]->retailer_province,
            'order_ship_city'     => $retailer[0]->retailer_city,
            'order_ship_zip'      => '',
            'order_ship_line1'    => $retailer[0]->retailer_address,
            'order_ship_line2'    => '',
            'order_ship_phone'    => $retailer[0]->retailer_phone,
        ];

        // if ($request->checkbox == 'false') {
        //     $orderParam = [
        //         ...$orderParam,
        //         'order_bill_country'  => $retailer[0]->retailer_country ?? 1,
        //         'order_bill_province' => $retailer[0]->retailer_province ?? 'default',
        //         'order_bill_city'     => $retailer[0]->retailer_city ?? 'default',
        //         'order_bill_zip'      => $retailer[0]->retailer_country ?? 'default',
        //         'order_bill_line1'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_bill_line2'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_bill_phone'    => $retailer[0]->retailer_phone ?? 'default',
        //         'order_ship_country'  => $retailer[0]->retailer_country ?? 'default',
        //         'order_ship_province' => $retailer[0]->retailer_province ?? 'default',
        //         'order_ship_city'     => $retailer[0]->retailer_city ?? 'default',
        //         'order_ship_zip'      => $retailer[0]->retailer_country ?? 1,
        //         'order_ship_line1'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_ship_line2'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_ship_phone'    => $retailer[0]->retailer_phone ?? 'default',

        //     ];
        // } else {
        //     $orderParam = [
        //         ...$orderParam,
        //         'order_bill_country'  => $retailer[0]->retailer_country ?? 1,
        //         'order_bill_province' => $retailer[0]->retailer_province ?? 'default',
        //         'order_bill_city'     => $retailer[0]->retailer_city ?? 'default',
        //         'order_bill_zip'      => $retailer[0]->retailer_country ?? 'default',
        //         'order_bill_line1'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_bill_line2'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_bill_phone'    => $retailer[0]->retailer_phone ?? 'default',
        //         'order_ship_country'  => $retailer[0]->retailer_country ?? 'default',
        //         'order_ship_province' => $retailer[0]->retailer_province ?? 'default',
        //         'order_ship_city'     => $retailer[0]->retailer_city ?? 'default',
        //         'order_ship_zip'      => $retailer[0]->retailer_country ?? 1,
        //         'order_ship_line1'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_ship_line2'    => $retailer[0]->retailer_city ?? 'default',
        //         'order_ship_phone'    => $retailer[0]->retailer_phone ?? 'default',
        //     ];
        // }

        $result = Ws_order::submit(0, $orderParam, $orderProductParam);

        if ($result['status']) $result['data'] = Ws_order::fetch($result['id']);
        echo json_encode($result);
    }


    function updateStatus(Request $request)
    {
        $param = [
            'order_status' => $request->status,
        ];
        if ($request->status == 2) $param['order_placed'] = Carbon::now();

        $result =  Ws_order::submit($request->id, $param);
        echo json_encode([
            'status'  => boolval($result),
            'data'    => $result ? Ws_order::fetch($request->id) : []
        ]);
    }

    function view($id)
    {
        $order = Ws_order::fetch($id);
        $retailer = Retailer::fetch($order->order_retailer);
        $products = Ws_orders_product::fetch(0, [['ordprod_order', $id]]);
        // return $products;

        return view('contents.wsOrders.view', compact('order', 'retailer', 'products'));
    }

    function export(Request $request)
    {
        $order_id = $request->orderid;
        return $order_id ? Ws_orders_product::excel($order_id) : Ws_orders_product::excel();
    }

    function Confirmed($id)
    {
        $order = Ws_order::fetch($id);
        $retailer = Retailer::fetch($order->order_retailer);
        $address = Retailer_address::fetch(0, [['address_retailer', $retailer->retailer_id]]);
        $orderData = Ws_orders_product::fetch(0, [['ordprod_order', $id]]);

        $data = [
            'order' => $order,
            'retailer' => $retailer,
            'orderData' => $orderData,
            'address'   => $address[0]
        ];

        $pdf = Pdf::loadView('pdf.order', ['data' => $data]);

        $pdfPath = 'orders/' . $order->order_code . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        Mail::to('b2b@sofieamoura.com')->send(new OrderCreated($retailer->retailer_fullName, $order->order_code, $pdfPath));
        return back();
    }

    function Proforma($id)
    {
        set_time_limit(5000);
        SendProformaInvoice::dispatch($id);
        return back();
    }

    function invoice($id)
    {
        $order = Ws_order::fetch($id);
        $retailer = Retailer::fetch($order->order_retailer);
        $address = Retailer_address::fetch(0, [['address_retailer', $retailer->retailer_id]]);
        $orderData = Ws_orders_product::fetch(0, [['ordprod_order', $id]]);

        $data = [
            'order' => $order,
            'retailer' => $retailer,
            'orderData' => $orderData,
            'address'   => $address[0]
        ];
        $pdf = Pdf::loadView('pdf.invoice', ['data' => $data]);


        $pdfPath = 'invoice/' . $order->order_code . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Mail::to('b2b@sofieamoura.com')->send(new OrderInvoice($retailer->retailer_fullName, $order->order_code, $pdfPath));
        return back();
        // try {
        //     Mail::to('b2b@sofieamoura.com')->send(new orderConfirmation());
        //     echo 'Message Sent';
        // } catch (Exception $e) {
        //     echo sprintf('[%s],[%d] ERROR:[%s]', __METHOD__, __LINE__, json_encode($e->getMessage(), true));
        // }
    }

    function getRetailer($id)
    {
        echo json_encode(Retailer::fetch($id));
    }
}