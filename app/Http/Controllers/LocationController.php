<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Locale;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contents.locations.index');
    }

    public function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if($request->code) $param[] = ['location_code', $request->code];
        echo json_encode(Location::fetch(0, $param, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'code'       => 'required',
            'iso_code_2' => 'required',
            'iso_code_3' => 'required'
        ]);

        $id = $request->location_id;
        $param = [
            'location_name'  => $request->name,
            'location_iso_2' => $request->iso_code_2,
            'location_iso_3' => $request->iso_code_3,
            'location_code'  => $request->code,
            'location_visible'  => intval($request->active),
        ];

        $result = Location::submit($param, $id);
        echo json_encode([
            'status'  => boolval($result),
            'data'    => $result ? Location::fetch($result) : []
        ]);
    }
}