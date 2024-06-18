<?php

namespace App\Http\Controllers;

use App\Models\Ws_product;
use App\Models\Ws_products_color;
use App\Models\Ws_products_size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WsProductsSizeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function load(Request $request)
    {
        $param = [['prodsize_product', '=', $request->product_id]];
        echo json_encode(Ws_products_size::fetch(0, $param));
    }

    function submit(Request $request)
    {
        // return
        $color_name = explode(',', $request->name);
        $id = $request->id;
        $color = $request->color_id;
        $sizes      = $request->size;
        $user = auth()->user()->id;
        $time = Carbon::now();

        $colorParam = [];
        $sizeParam  = [];

        $color_name = array_values(array_filter($color_name, function ($e) {
            $e = trim($e);
            return !empty($e);
        }));

        $lastOrder = Ws_products_color::lastOrder(1);
        $color_ref = uniqidReal(rand(6, 24));
        if (count($color_name) > 1) {
            foreach ($color_name as $color) {
                $colorParam[] = [
                    'prodcolor_ref'         => $color_ref,
                    'prodcolor_name'        => $color,
                    'prodcolor_order'       => ++$lastOrder,
                    'prodcolor_created_by'   => $user,
                    'prodcolor_created'      => $time,
                    'prodcolor_product'     => $request->p_id,
                ];
                foreach ($sizes as $size) {
                    $sizeParam[] = [
                        'prodsize_size'    => $size,
                        'prodsize_color'   => $color_ref,
                        'prodsize_product' => $request->p_id,
                        'prodsize_wsp'     => $request->wholesale,
                        'prodsize_rrp'     => $request->rrp,
                        'prodsize_created_by' => $user,
                        'prodsize_created'    => $time
                    ];
                };
            }
        } else {
            if ($id && $color) {
                $sizeParam = [
                    'prodsize_product'     => $request->p_id,
                    'prodsize_cost'        => '0.00',
                    'prodsize_wsp'         => $request->wholesale,
                    'prodsize_stock'       => $request->qty,
                    'prodsize_visible'     => $request->visible ?? 1,
                    'prodsize_modified_by' => auth()->user()->id,
                    'prodsize_modified'    => Carbon::now()
                ];
                $colorParam = [
                    'prodcolor_published'   => intval($request->color_status),
                    'prodcolor_modified_by' => auth()->user()->id,
                    'prodcolor_modified'    => Carbon::now(),
                    'prodcolor_product'     => $request->p_id,
                    'prodcolor_mincolorqty' => $request->mincolorqty,
                    'prodcolor_minqty'      => $request->minqty,
                    'prodcolor_maxqty'      => $request->maxqty,
                    'prodcolor_minorder'    => $request->minorder,
                    'prodcolor_ordertype'   => $request->order_type,
                    'prodcolor_discount'    => $request->discount,
                    'prodcolor_freeshipping' => intval($request->freeshipping),
                ];
            } else {
                $colorParam[] = [
                    'prodcolor_ref'         => $color_ref,
                    'prodcolor_name'        => $request->name,
                    'prodcolor_order'        => +$lastOrder,
                    'prodcolor_created_by'   => $user,
                    'prodcolor_created'      => $time,
                    'prodcolor_product'     => $request->p_id,
                ];
                foreach ($sizes as $size) {
                    $sizeParam[] = [
                        'prodsize_size'    => $size,
                        'prodsize_color'   => $color_ref,
                        'prodsize_product' => $request->p_id,
                        'prodsize_wsp'     => $request->wholesale,
                        'prodsize_rrp'     => $request->rrp,
                        'prodsize_created_by' => $user,
                        'prodsize_created'    => $time
                    ];
                };
            }
        }

        $result = Ws_products_color::createUpdateColorSize($id, $sizeParam, $color, $colorParam);

        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ?  Ws_products_size::fetch($result, [['prodsize_product', $request->p_id]]) : []
        ]);
    }

    function editStatus(Request $request)
    {
        $i = 1;
        if ($request->status) $i = 0;
        $param = ['prodsize_visible' => $i];

        $result = Ws_products_size::submit($param, $request->id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ?  Ws_products_size::fetch($result) : []
        ]);
    }

    function update(Request $request)
    {
        // return $request;
        $id = $request->id;
        $param = [
            'prodsize_wsp' => $request->newPrice,
            'prodsize_rrp' => ($request->newPrice * 2.4)
        ];
        $result = Ws_products_size::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ?  Ws_products_size::fetch($result, [['prodsize_product', $request->pr_id]]) : []
        ]);
    }
}
