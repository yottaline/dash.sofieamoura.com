<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    public $timestamps = false;
    // $table->integer('', true, true);
    protected $fillable = [
        'size_name',
        'size_order',
        'size_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $sizes = self::orderBy('size_order')->limit($limit);

        if($lastId) $sizes->where('size_id', '<', $lastId);

        if($params) $sizes->where($params);

        if($id) $sizes->where('size_id', $id);

        return $id ? $sizes->first() : $sizes->get();
    }

    public function submit($param, $id)
    {

    }

}