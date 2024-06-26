<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ws_products_color extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'prodcolor_ref',
        'prodcolor_slug',
        'prodcolor_name',
        'prodcolor_product',
        'prodcolor_mincolorqty',
        'prodcolor_minqty',
        'prodcolor_maxqty',
        'prodcolor_minorder',
        'prodcolor_media',
        'prodcolor_ordertype',
        'prodcolor_discount',
        'prodcolor_freeshipping',
        'prodcolor_related',
        'prodcolor_views',
        'prodcolor_order',
        'prodcolor_published',
        'prodcolor_modified_by',
        'prodcolor_modified',
        'prodcolor_created_by',
        'prodcolor_created'
    ];

    static function fetch($id = 0, $params = null)
    {
        $colors = self::join('ws_products', 'prodcolor_product', 'product_id')->orderBy('prodcolor_created', 'DESC');
        if ($params) $colors->where($params);
        if ($id) $colors->where('prodcolor_id', $id);

        return $id ? $colors->first() : $colors->get();
    }


    static function submit($id = 0, $params = null)
    {
        return self::where('prodcolor_id', $id)->update($params) ? $id : false;
    }

    static function createUpdateColorSize($size, $sizeParam, $color, $colorParam)
    {
        if ($size && $color) return self::where('prodcolor_id', $color)->update($colorParam) && Ws_products_size::where('prodsize_id', $size)->update($sizeParam) ? $size : false;
        try {
            DB::beginTransaction();

            if (self::insert($colorParam)) {
                $status = Ws_products_size::insert($sizeParam);
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => 'Failed to insert colors'];
            }

            DB::commit();
            return ['status' => boolval($status), 'id' => $status ? $status->id : false];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
        }
    }

    static function lastOrder($season)
    {
        return self::join('ws_products', 'prodcolor_product', 'product_id')
            ->where('product_season', $season)->max('prodcolor_order');
    }

    // static function updateSizeColor()
    // {
    //     return self::where('prodcolor_id', $color)->update($colorParam) && Ws_products_size::where('prodsize_id', $size)->update($sizeParam) ? $size : false;
    // }

}
