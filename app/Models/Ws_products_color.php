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
        'prodcolor_code',
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

    // public static function fetch($id = 0, $params = null)
    // {

    // }

    public static function createUpdateColorSize($colorParam, $sizeParam)
    {
        try {
            DB::beginTransaction();
            if (self::insert($colorParam)) {
               $status = Ws_products_size::insert($sizeParam);
            }
            DB::commit();
            return ['status' => boolval($status),'id' => $status->id];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false,'message' => 'error: ' . $e->getMessage()];
        }
    }
}
