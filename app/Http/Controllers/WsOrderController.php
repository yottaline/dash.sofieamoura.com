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
        $retailer_address = Retailer_address::fetch(0, [['address_retailer', $request->retailer_id]]);
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
            'order_retailer'        => $request->retailer_id,
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
            'order_bill_country'  => $request->location,
            'order_bill_province' => $request->bill_province,
            'order_bill_city'     => $request->city,
            'order_bill_zip'      => $request->zip,
            'order_bill_line1'    => $request->line1,
            'order_bill_line2'    => $request->line2,
            'order_bill_phone'    => $request->phone,
            'order_ship_country'  => $request->location,
            'order_ship_province' => $request->bill_province,
            'order_ship_city'     => $request->city,
            'order_ship_zip'      => $request->zip,
            'order_ship_line1'    => $request->line1,
            'order_ship_line2'    => $request->line2,
            'order_ship_phone'    => $request->phone,

        ];
        }else {

            $orderParam = [
                ...$orderParam,
                'order_bill_country'  => $retailer_address[0]->address_country,
                'order_bill_province' => $retailer_address[0]->address_province,
                'order_bill_city'     => $retailer_address[0]->address_city,
                'order_bill_zip'      => $retailer_address[0]->address_zip,
                'order_bill_line1'    => $retailer_address[0]->address_line1,
                'order_bill_line2'    => $retailer_address[0]->address_line2,
                'order_bill_phone'    => $retailer_address[0]->address_phone,
                'order_ship_country'  => $retailer_address[0]->address_country,
                'order_ship_province' => $retailer_address[0]->address_province,
                'order_ship_city'     => $retailer_address[0]->address_city,
                'order_ship_zip'      => $retailer_address[0]->address_zip,
                'order_ship_line1'    => $retailer_address[0]->address_line1,
                'order_ship_line2'    => $retailer_address[0]->address_line2,
                'order_ship_phone'    => $retailer_address[0]->address_phone,

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
        $orderData = Ws_orders_product::fetch(0,[['ordprod_order', $id]]);

        return view('contents.wsOrders.view', compact('order', 'retailer', 'orderData'));
    }
}
