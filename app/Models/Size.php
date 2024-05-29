<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'size_name',
        'size_order',
        'size_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $sizes = self::orderBy('size_order', 'ASC')->limit($limit);

        if($lastId) $sizes->where('size_id', '<', $lastId);

        if($params) $sizes->where($params);

        if($id) $sizes->where('size_id', $id);

        return $id ? $sizes->first() : $sizes->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('size_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }

}