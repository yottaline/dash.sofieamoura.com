<?php

namespace App\Http\Controllers;

use App\Models\Ws_products_size;
use Illuminate\Http\Request;

class WsProductsSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $param[] = ['prodsize_product', $request->product_id];

        echo json_encode(Ws_products_size::fetch(0,$param));
    }

    public function submit(Request $request)
    {
        $id = $request->id;
        $param = [
            'prodsize_product' => $request->p_id,
            'prodsize_size'    => $request->size,
            'prodsize_colorid' => $request->code,
            'prodsize_color'   => $request->name,
            'prodsize_cost'    => $request->cost,
            'prodsize_wsp'     => $request->wholesale,
            'prodsize_rrp'     => $request->rrp,
            'prodsize_qty'     => $request->qty,
            'prodsize_stock'   => $request->stock,
            'prodsize_visible' => $request->visible ?? 1,
        ];

        $result = Ws_products_size::submit($param, $id);
        echo json_encode([
            'status'  => boolval($result),
            'data'    => $result ? Ws_products_size::fetch($result, [['prodsize_product', $request->p_id]]) : []
        ]);
    }
}