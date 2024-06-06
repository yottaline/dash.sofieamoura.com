<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contents.categories.index');
    }

    public function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;

        if($request->status) $param[] = ['category_visible', $request->status - 1];
        echo json_encode(Category::fetch(0, $param, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'type'      => 'required|numeric',
            'gender'    => 'required|numeric'
        ]);

        $id = $request->id;

        $param = [
            'category_name'     => $request->name,
            'category_type'     => $request->type,
            'category_gender'   => $request->gender,
            'category_visible'  => intval($request->visible)
        ];

        $result = Category::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Category::fetch($result) : []
        ]);
    }
}