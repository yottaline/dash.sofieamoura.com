<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer_address extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'address_retailer',
        'address_type',
        'address_country',
        'address_province',
        'address_city',
        'address_zip',
        'address_line1',
        'address_line2',
        'address_phone',
        'address_note'
    ];

    public static function fetch($id = 0, $params = null)
    {
        $retailer_addresses = self::join('retailers', 'address_retailer', 'retailer_id')->join('locations', 'address_country', 'location_id');

        if($params) $retailer_addresses->where($params);

        if($id) $retailer_addresses->where('address_id', $id);

        return $id ? $retailer_addresses->first() : $retailer_addresses->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('address_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}