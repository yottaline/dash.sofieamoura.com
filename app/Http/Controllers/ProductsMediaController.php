<?php

namespace App\Http\Controllers;

use App\Models\Products_media;
use App\Models\Ws_products_color;
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

    public function getColor(Request $request)
    {
        $param[] = ['product_ref', $request->ref];
        echo json_encode(Ws_products_color::fetch(0, $param));
    }

    public function submit(Request $request)
    {
        $id = $request->id;

        $param = [
            'media_product'  => $request->product_id,
            'media_color'    => $request->color,
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

        if($result)
        {
            if(Ws_products_color::where('prodcolor_ref', $request->color)->where('prodcolor_media', null)->get()){
                Ws_products_color::where('prodcolor_ref', $request->color)->update(['prodcolor_media' => $result]);
            }
        }

        echo json_encode([
            'status' => boolval($result),
            'data' => $result ? Products_media::fetch(0, [['media_id', $result],['media_product', $request->product_id]]) : []
        ]);


    }
    public function updateOrder(Request $request)
    {
        $orders = $request->orders;
        foreach ($orders as $order => $id) {
           $result =  Products_media::submit(['media_order' => $order], $id);
        }
        echo json_encode(['status' => boolval($result)]);
    }

    public function imageDefault(Request $request)
    {
       $color = $request->c_id;
       $media = $request->m_id;
       $status = null;
        if(!$request->s) $status = $media;
       $result = Ws_products_color::submit($color, ['prodcolor_media' => $status]);
       echo json_encode([
        'status' => boolval($result)
       ]);
    }
}
