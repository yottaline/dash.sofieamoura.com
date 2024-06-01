<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Season;
use App\Models\Size;
use App\Models\Ws_product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WsProductController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $seasons = Season::fetch(0, [['season_visible', 1]]);
        $categories = Category::fetch(0, [['category_visible', 1]]);
        return view('contents.wsProducts.index', compact('seasons', 'categories'));
    }

    function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $offset = $request->offset;

        echo json_encode(Ws_product::fetch(0, $params, $limit, $offset));
    }

    function submit(Request $request)
    {
        $id = $request->id;
        $param = [
            'product_code'      => $request->code,
            'product_name'      => $request->name,
            'product_season'    => $request->season,
            'product_category'  => $request->category,
            'product_type'      => $request->order_type,
        ];

        if (!$id) {
            $param = [
                ...$param,
                'product_ref' => uniqidReal(),
                'product_created_by' => auth()->user()->id,
                'product_created' => Carbon::now()
            ];
        } else {
            $param = [
                ...$param,
                'product_desc'      => $request->description,
                'product_delivery'     => $request->delivery,
                'product_modified_by' => auth()->user()->id,
                'product_modified' => Carbon::now()
            ];
        }

        $result = Ws_product::submit($param, $id);
        echo json_encode([
            'status'   => boolval($result),
            'data'     => $result ? Ws_product::fetch($result) : []
        ]);
    }

    function view(String $ref)
    {
        $seasons = Season::fetch(0, [['season_visible', 1]]);
        $sizes = Size::fetch(0, [['size_visible', 1]]);
        $categories = Category::fetch(0, [['category_visible', 1]]);
        $data = Ws_product::fetch(0, [['product_ref', $ref]], 1);
        return view('contents.wsProducts.view', compact('data', 'seasons', 'categories', 'sizes'));
    }

    function order(Request $request)
    {
        $ids    = $request->ids;
        $orders = $request->order;
        $products = Ws_product::fetch(0, null, null, null, $ids);
        foreach ($products as $p) {
            $i = array_search($p->product_id, $ids);
            $result = Ws_product::submit(['product_order' => $orders[$i]], $p->product_id);
        }
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Ws_product::fetch(0, null, null, null, $ids) : [],
        ]);
    }

}