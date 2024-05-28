<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    public $timestamps = false;
    // $table->integer('season_id', true, true);
    protected $fillable = [
        'season_name',
        'season_code',
        'season_current',
        'season_adv_payment',
        'season_adv_context',
        'season_delivery_1',
        'season_delivery_2',
        'season_start',
        'season_end',
        'season_lookbook',
        'season_visible'
    ];
}
