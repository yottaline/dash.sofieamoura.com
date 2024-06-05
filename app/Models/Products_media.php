<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_media extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'media_product',
        'media_color',
        'media_file',
        'media_type',
        'media_order',
        'media_visible'
    ];

    public static function fetch($id = 0, $params = null)
    {
        $product_medias = self::join('ws_products', 'media_product', 'product_id')->orderBy('media_order');

        if($params) $product_medias->where($params);
        if($id) $product_medias->where('media_id', $id);

        return $id ? $product_medias->first() : $product_medias->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('media_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}