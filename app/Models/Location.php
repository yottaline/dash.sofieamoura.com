<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'location_name',
        'location_iso_2',
        'location_iso_3',
        'location_code',
        'location_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $locations = self::limit($limit)->orderBy('location_id', 'DESC');

        if($lastId) $locations->where('location_id', '<', $lastId);

        if($params) $locations->where($params);

        if($id) $locations->where('location_id', $id);

        return $id ? $locations->first() : $locations->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('location_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}