<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Season;
use App\Models\Ws_product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WsProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $seasons = Season::fetch(0, [['season_visible', 1]]);
        $categories = Category::fetch(0, [['category_visible', 1]]);
        return view('contents.wsProducts.index', compact('seasons', 'categories'));
    }

    public function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;

        echo json_encode(Ws_product::fetch(0, $params, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $id = $request->id;
        if($request->discount >= 100)
        {
            echo json_encode(['status' => false, 'message' => 'The discount must be less than or equal to 100']);
            return;
        }
        $param = [
            'product_ref'       => $request->reference,
            'product_code'      => uniqidReal(),
            'product_name'      => $request->name,
            'product_desc'      => $request->description,
            'product_season'    => $request->season,
            'product_category'  => $request->category,
            'product_ordertype' => $request->order_type,
            'product_minqty'    => $request->minqty,
            'product_maxqty'    => $request->maxqty,
            'product_minorder'  => $request->minorder,
            'product_discount'  => $request->discount,
            'product_freeshipping' => intval($request->freeshipping),
            'product_delivery'     => $request->delivery,
            'product_related'      => $request->related,
            'product_published'    => intval($request->status),
            'product_created_by'   => auth()->user()->id,
            'product_created'      => Carbon::now()
        ];

        if($id)
        {
            $param['product_modified_by']  = auth()->user()->id;
            $param['product_modified']     = Carbon::now();
        };

        $result = Ws_product::submit($param, $id);
        echo json_encode([
            'status'   => boolval($result),
            'data'     => $result ? Ws_product::fetch($result) : []
        ]);
    }

}