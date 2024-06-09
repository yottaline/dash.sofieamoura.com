<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contents.currencies.index');
    }

    public function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;

        if($request->status) $params[] = ['currency_visible', $request->status - 1];
        echo json_encode(Currency::fetch(0, $params, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $id = $request->currency_id;
        $param = [
            'currency_name'    => $request?->name,
            'currency_code'    => $request->code,
            'currency_symbol'  => $request->symbol,
            'currency_visible' => intval($request->active)
        ];

        $result = Currency::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Currency::fetch($result) : []
        ]);
    }
}