<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $oder_products = self::join('ws_orders', 'ordprod_order', 'order_id')->join('ws_products', 'ordprod_product', 'product_id')
                            ->join('ws_products_sizes', 'ordprod_size', 'prodsize_id')->join('sizes', 'ws_products_sizes.prodsize_size', 'sizes.size_id')
                            ->join('ws_products_colors', 'ws_products_colors.prodcolor_ref', 'ws_products_sizes.prodsize_color');

        if($params) $oder_products->where($params);

        if($id) $oder_products->where('ordprod_id', $id);

        return $id ? $oder_products->first() : $oder_products->get();
    }

}