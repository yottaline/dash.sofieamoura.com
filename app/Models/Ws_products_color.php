<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
