<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Ws_product extends Model
{
    use HasFactory;
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'product_ref',
        'product_code',
        'product_name',
        'product_desc',
        'product_media',
        'product_season',
        'product_type',
        'product_gender',
        'product_category',
        'product_ordertype',
        'product_minqty',
        'product_maxqty',
        'product_mincolorqty',
        'product_minorder',
        'product_discount',
        'product_freeshipping',
        'product_delivery',
        'product_views',
        'product_order',
        'product_related',
        'product_published',
        'product_modified_by',
        'product_modified',
        'product_created_by',
        'product_created'
    ];

    static function fetch($id = 0, $params = null, $limit = 24, $offset = 0)
    {
        $ws_products = self::join('seasons', 'product_season', '=', 'season_id')
            ->join('categories', 'product_category', '=', 'category_id')
            ->orderBy('product_order', 'ASC')->limit($limit)->offset($offset);


        if (isset($params['q'])) {
            $ws_products->where(function (Builder $query) use ($params) {
                $query->where('product_code', $params['q'])
                    ->orWhere('product_ref', $params['q'])
                    ->orWhere('product_desc', 'like', "%{$params['q']}%")
                    ->orWhere('product_name', 'like', "%{$params['q']}%")
                    ->orWhere('season_name', $params['q'])
                    ->orWhere('category_name', $params['q']);
            });
            unset($params['q']);
        }

        if ($params) $ws_products->where($params);
        if ($id) $ws_products->where('product_id', $id);

        // if($ids) $ws_products->whereIn('product_id', $ids);

        return $id ? $ws_products->first() : $ws_products->get();
    }

    static function submit($param, $id)
    {
        if ($id) return self::where('product_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->product_id : false;
    }

}
