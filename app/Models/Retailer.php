<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'retailer_code',
        'retailer_fullName',
        'retailer_email',
        'retailer_password',
        'retailer_phone',
        'retailer_company',
        'retailer_logo',
        'retailer_desc',
        'retailer_website',
        'retailer_country',
        'retailer_province',
        'retailer_city',
        'retailer_address',
        'retailer_shipAdd',
        'retailer_billAdd',
        'retailer_currency',
        'retailer_adv_payment',
        'retailer_approved',
        'retailer_approved_by',
        'retailer_blocked',
        'retailer_modified',
        'retailer_created'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $retailers = self::join('locations', 'retailer_country', 'location_id')
        ->join('currencies', 'retailer_currency', 'currency_id')->orderBy('retailer_created', 'DESC')->limit($limit);

        if($lastId) $retailers->where('retailer_id', '<', $lastId);

        if($params) $retailers->where($params);

        if($id) $retailers->where('retailer_id', $id);

        return $id ? $retailers->first() : $retailers->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('retailer_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}
