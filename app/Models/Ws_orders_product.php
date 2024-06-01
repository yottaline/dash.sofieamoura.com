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
        'ordprod_price',
        'ordprod_subtotal',
        'ordprod_discount',
    ];

    public static function fetch($id = 0, $params = null)
    {
        $oder_products = self::join('ws_orders', 'ordprod_order', 'order_id')->join('ordprod_product', 'ordprod_order', 'product_id')
                            ->join('ws_products_sizes', 'ordprod_size', 'prodsize_id');

        if($params) $oder_products->where($params);

        if($id) $oder_products->where('ordprod_id', $id);

        return $id ? $oder_products->first() : $oder_products->get();
    }

}