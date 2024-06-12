<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Location;
use App\Models\Retailer;
use App\Models\Retailer_address;
use App\Models\Season;
use App\Models\Ws_order;
use App\Models\Ws_orders_product;
use App\Models\Ws_product;
use App\Models\Ws_products_size;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class WsOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $retailers   = Retailer::fetch(0,[['retailer_blocked', 1]]);
        $seasons     = Season::fetch(0, [['season_visible', 1]]);
        $currencies  = Currency::fetch(0, [['currency_visible', 1]]);
        $locations   = Location::fetch(0,[['location_visible', 1]]);
        return view('contents.wsOrders.index', compact('retailers', 'seasons', 'currencies', 'locations'));
    }

    public function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;
        if($request->date)   $param[] = ['order_created', 'like', '%' . $request->date . '%'];
        if($request->r_name) $param[] = ['retailer_fullName', 'like', '%' . $request->r_name . '%'];

        echo json_encode(Ws_order::fetch(0, $param, $limit, $lastId));
    }

    public function getProduct(Request $request)
    {
        $product_name = $request->product;
        $param =  [['product_name', 'like', '%' . $product_name . '%']];
        echo json_encode(Ws_products_size::fetch(0, $param));
    }

    public function submit(Request $request)
    {
        $ids = explode(',', $request->id);
        $qty = explode(',', $request->qty);
        $disc = explode(',', $request->disc);
        $amount = explode(',', $request->amount);

        // create and check retailer data
        $retailer = Retailer::fetch(0, [['retailer_email', $request->email]]);
        if(!count($retailer))
        {
            $retailerParam = [
                'retailer_code' => uniqidReal(8),
                'retailer_fullName' => $request->name ?? 'default',
                'retailer_email'    => $request->email,
                'retailer_password' => Hash::make('1234'),
                'retailer_phone'    => $request->r_phone ?? 'default',
                'retailer_country'  => 1,
                'retailer_city'     => $request->r_city ?? 'default',
                'retailer_address'  => $request->address ?? 'default',
                'retailer_company'  => 'Company',
                'retailer_created'  => Carbon::now(),
                'retailer_province' => $request->r_city  ?? 'default',
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
                $subtotal = $amount[$indx] * $p->prodsize_wsp;
                $total    = $subtotal * $disc[$indx] / 100;
                $orderProductParam[] = [
                    'ordprod_product'       => $p->product_id,
                    'ordprod_size'          => $p->prodsize_id,
                    'ordprod_price'         => $p->prodsize_wsp,
                    'ordprod_request_qty'   => $amount[$indx],
                    'ordprod_subtotal'      => $subtotal,
                    'ordprod_total'         => $total,
                    'ordprod_discount'      => $p->prodcolor_discount,
                    'ordprod_served_qty'    => $qty[$indx]
                ];
                $ordSubtotal    += $subtotal;
                $orderTotalDisc += $total - $subtotal;
                $ordTotal       += $total;
            }
        }

        $orderParam = [
            'order_code'            => uniqidReal(10),
            'order_season'          => $request->season,
            'order_retailer'        => $retailer[0]->retailer_id,
            'order_shipping'        => $request->cost,
            'order_subtotal'        => $ordSubtotal,
            'order_discount'        => $request->orderdisc ?? 0,
            'order_total'           => $ordTotal,
            'order_currency'        => $request->currencies,
            'order_type'            => $request->order_type,
            'order_note'            => $request->note,
            'order_created'         => Carbon::now(),
            'order_proforma'        => uniqidReal(20),
            'order_proformatime'    => Carbon::now(),
            'order_invoice'         => uniqidReal(30),
            'order_invoicetime'     => Carbon::now(),
            'order_status'          => 0
        ];
        if($request->checkbox == 'false')
        {
             $orderParam = [
            ...$orderParam,
            'order_bill_country'  => $retailer[0]->retailer_country ?? 1,
            'order_bill_province' => $retailer[0]->retailer_province ??'default',
            'order_bill_city'     => $retailer[0]->retailer_city ??'default',
            'order_bill_zip'      => $retailer[0]->retailer_country ??'default',
            'order_bill_line1'    => $retailer[0]->retailer_city ??'default',
            'order_bill_line2'    => $retailer[0]->retailer_city ??'default',
            'order_bill_phone'    => $retailer[0]->retailer_phone ??'default',
            'order_ship_country'  => $retailer[0]->retailer_country ??'default',
            'order_ship_province' => $retailer[0]->retailer_province ??'default',
            'order_ship_city'     => $retailer[0]->retailer_city ??'default',
            'order_ship_zip'      => $retailer[0]->retailer_country ?? 1,
            'order_ship_line1'    => $retailer[0]->retailer_city ??'default',
            'order_ship_line2'    => $retailer[0]->retailer_city ??'default',
            'order_ship_phone'    => $retailer[0]->retailer_phone ??'default',

        ];
        }else {

            $orderParam = [
                ...$orderParam,
                'order_bill_country'  => $retailer[0]->retailer_country ?? 1,
                'order_bill_province' => $retailer[0]->retailer_province ??'default',
                'order_bill_city'     => $retailer[0]->retailer_city ??'default',
                'order_bill_zip'      => $retailer[0]->retailer_country ??'default',
                'order_bill_line1'    => $retailer[0]->retailer_city ??'default',
                'order_bill_line2'    => $retailer[0]->retailer_city ??'default',
                'order_bill_phone'    => $retailer[0]->retailer_phone ??'default',
                'order_ship_country'  => $retailer[0]->retailer_country ??'default',
                'order_ship_province' => $retailer[0]->retailer_province ??'default',
                'order_ship_city'     => $retailer[0]->retailer_city ??'default',
                'order_ship_zip'      => $retailer[0]->retailer_country ?? 1,
                'order_ship_line1'    => $retailer[0]->retailer_city ??'default',
                'order_ship_line2'    => $retailer[0]->retailer_city ??'default',
                'order_ship_phone'    => $retailer[0]->retailer_phone ??'default',

            ];
        }

        $result = Ws_order::submit(0, $orderParam, $orderProductParam);

        if($result['status']) $result['data'] = Ws_order::fetch($result['id']);

        echo json_encode($result);
    }


    public function updateStatus(Request $request)
    {
        $param = [
            'order_status' => $request->status,
        ];
        if($request->status == 2) $param['order_placed'] = Carbon::now();
        $result =  Ws_order::submit($request->id, $param);
        echo json_encode([
            'status'  => boolval($result),
        ]);
    }


    public function view($id)
    {
        $order = Ws_order::fetch($id);
        $retailer = Retailer::fetch($order->order_retailer);
        // return $order;
        $orderData = Ws_orders_product::fetch(0,[['ordprod_order', $id]]);

        return view('contents.wsOrders.view', compact('order', 'retailer', 'orderData'));
    }
}
