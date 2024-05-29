<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Season;
use App\Models\Ws_product;
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

}