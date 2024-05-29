<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_media extends Model
{
    use HasFactory;
    public $timestamps = false;

    // $table->bigInteger('media_id', true, true);
    protected $fillable = [
        'media_product',
        'media_color',
        'media_file',
        'media_type',
        'media_order',
        'media_visible'
    ];
}