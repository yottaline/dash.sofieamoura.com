<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'currency_name',
        'currency_code',
        'currency_symbol',
        'currency_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $currencies = self::limit($limit);

        if($lastId) $currencies->where('currency_id', '<', $lastId);

        if($params) $currencies->where($params);

        if($id) $currencies->where('currency_id', $id);

        return $id ? $currencies->first() : $currencies->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('currency_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}
