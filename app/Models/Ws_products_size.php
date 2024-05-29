<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_products_size extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'prodsize_product',
        'prodsize_size',
        'prodsize_colorid',
        'prodsize_color',
        'prodsize_cost',
        'prodsize_wsp',
        'prodsize_rrp',
        'prodsize_qty',
        'prodsize_stock',
        'prodsize_visible'
    ];


    public static function fetch($id = 0, $params = null)
    {
        $ws_products_sizes = self::join('ws_products', 'prodsize_product', 'product_id')
                                ->join('sizes', 'prodsize_size', 'size_id');
        if($params) $ws_products_sizes->where($params);
        if($id) $ws_products_sizes->where('prodsize_id', $id);

        return $id ? $ws_products_sizes->first() : $ws_products_sizes->get();
    }
}