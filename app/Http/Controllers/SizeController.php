<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contents.sizes.index');
    }

    public function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;

        echo json_encode(Size::fetch(0, $params, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $id = $request->id;
        $param = [
            'size_name'    => $request?->name,
            'size_order'   => $request->order,
            'size_visible' => intval($request->visible),
        ];

        $result = Size::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Size::fetch($result) : []
        ]);
    }
}