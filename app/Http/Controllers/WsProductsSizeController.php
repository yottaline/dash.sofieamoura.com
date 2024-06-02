<?php

namespace App\Http\Controllers;

use App\Models\Ws_products_color;
use App\Models\Ws_products_size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WsProductsSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $param[] = ['prodcolor_product', $request->product_id];

        echo json_encode(Ws_products_size::fetch(0,$param));
    }

    public function submit(Request $request)
    {
        $color_name = explode(',', $request->name);
        $sizes      = explode(',', $request->size);
        $orders     = explode(',', $request->order);

        $colorParam = [];
        $sizeParam  = [];

        // colors
        foreach($color_name as $color)
        {
            $i = 0;
            $color_ref = uniqidReal(14);
            $colorParam[] = [
                'prodcolor_ref'         => $color_ref,
                'prodcolor_code'        => $request->code,
                'prodcolor_name'        => $color[$i],
                'prodcolor_product'     => $request->p_id,
                'prodcolor_mincolorqty' => $request->mincolorqty,
                'prodcolor_minqty'      => $request->minqty,
                'prodcolor_maxqty'      => $request->maxqty,
                'prodcolor_minorder'    => $request->minorder,
                'prodcolor_ordertype'   => $request->order_type,
                'prodcolor_discount'    => $request->discount,
                'prodcolor_freeshipping' => intval($request->freeshipping),
                'prodcolor_related'      => $request->related ?? 'null',
                'prodcolor_order'        => $orders[$i],
                'prodcolor_published'    => intval($request->color_status),
                'prodcolor_created_by'   => auth()->user()->id,
                'prodcolor_created'      => Carbon::now()
            ];
        }
        // sizes
        foreach($sizes as $size)
        {
            $index = 0;
            $sizeParam[] = [
                'prodsize_product' => $request->p_id,
                'prodsize_size'    => $size[$index],
                'prodsize_color'   => $color_ref,
                'prodsize_cost'    => $request->cost,
                'prodsize_wsp'     => $request->wholesale,
                'prodsize_rrp'     => $request->rrp,
                'prodsize_qty'     => $request->qty,
                'prodsize_stock'   => $request->stock,
                'prodsize_visible' => $request->visible ?? 1,
                'prodsize_created_by' => auth()->user()->id,
                'prodsize_created'    => Carbon::now()
            ];
        };
        $id = $request->id;
        $result = Ws_products_color::createUpdateColorSize($colorParam, $sizeParam);
        echo json_encode([
            'status'  => boolval($result),
            'data'    => $result ? Ws_products_size::fetch($result, [['prodsize_product', $request->p_id]]) : []
        ]);
    }
}