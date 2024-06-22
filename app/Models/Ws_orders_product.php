<?php

namespace App\Models;

use App\Exports\WsOrderExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class Ws_orders_product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'ordprod_order',
        'ordprod_product',
        'ordprod_size',
        'ordprod_request_qty',
        'ordprod_served_qty',
        'ordprod_total',
        'ordprod_price',
        'ordprod_subtotal',
        'ordprod_discount',
    ];

    public static function fetch($id = 0, $params = null)
    {
        // join('ws_orders', 'ordprod_order', 'order_id')
        $oder_products = self::join('ws_products', 'ordprod_product', 'product_id')
            ->join('ws_products_sizes', 'ordprod_size', 'prodsize_id')
            ->join('sizes', 'prodsize_size', 'sizes.size_id')
            ->join('ws_products_colors', 'prodcolor_ref', 'prodsize_color')
            // ->leftJoin('products_media', 'media_color', 'prodcolor_ref')
            ->join('products_media', 'prodcolor_media', 'media_id');

        if ($params) $oder_products->where($params);

        if ($id) $oder_products->where('ordprod_id', $id);

        return $id ? $oder_products->first() : $oder_products->get();
    }

    public static function excel($orderids = null)
    {
        $orders =   self::join('ws_orders AS o', 'ordprod_order', 'order_id')->join('ws_products AS wp1', 'ordprod_product', 'product_id')
            ->join('ws_products_sizes', 'ordprod_size', 'prodsize_id')->join('sizes', 'ws_products_sizes.prodsize_size', 'sizes.size_id')
            ->join('seasons', 'season_id', 'product_season')
            ->join('retailers', 'retailer_id', 'order_retailer')
            ->join('ws_products_colors', 'ws_products_colors.prodcolor_ref', 'ws_products_sizes.prodsize_color');

        if ($orderids) $orders->whereIn('o.order_id', $orderids);
        $data = $orders->get(['o.order_code', 'o.order_placed', 'retailer_fullName', 'retailer_email', 'wp1.product_code', 'wp1.product_name', 'season_name', 'size_name', 'ordprod_request_qty', 'o.order_total']);
        $date = Carbon::now();
        return Excel::download(new WsOrderExport($data), 'orders' . $date . '.xlsx');
    }
}
