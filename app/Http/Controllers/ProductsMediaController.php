<?php

namespace App\Http\Controllers;

use App\Models\Products_media;
use Illuminate\Http\Request;

class ProductsMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function load(Request $request)
    {
        $param[] = ['media_product', $request->product_id];
        echo json_encode(Products_media::fetch(0, $param));
    }

    public function submit(Request $request)
    {
        $id = $request->id;

        $param = [
            'media_product'  => $request->product_id,
            'media_color'    => $request->color ?? '',
            'media_order'    => $request->order,
        ];

        $media = $request->file('media');
        if ($media && count($media) > 0) {
            foreach ($media as $image) {
                $fileName = uniqidReal(8) . '.' . $image->getClientOriginalExtension();
                $image->move('media/product/'. $request->product_id .'/' , $fileName);
                $param['media_file'] = $fileName;
                $result = Products_media::submit($param, $id);
            }
        }


        echo json_encode([
            'status' => boolval($result),
            'data' => $result ? Products_media::fetch($result, [['media_product', $request->product_id]]) : []
        ]);


    }
}
