<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_products_size extends Model
{
    use HasFactory;

    public $timestamps = false;

    // $table->bigInteger('prodsize_id', true, true);

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
}