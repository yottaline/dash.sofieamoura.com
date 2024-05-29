<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contents.seasons.index');
    }

    public function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;

        echo json_encode(Season::fetch(0, $param, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $id = $request->id;

        $param = [
            'season_name'     => $request->name,
            'season_code'     => uniqidReal(8),
            'season_current'  => intval($request->current),
            'season_adv_payment' => $request?->adv_payment,
            'season_adv_context' => $request->description,
            'season_delivery_1'  => $request->in_stock,
            'season_delivery_2'  => $request->per_order,
            'season_start'       => $request->start,
            'season_end'         => Season::parse($request->start, 2),
            'season_lookbook'   => $request->Lookbook,
            'season_visible'    => intval($request->visible)
        ];

        $result = Season::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Season::fetch($result) : []
        ]);

    }

}
