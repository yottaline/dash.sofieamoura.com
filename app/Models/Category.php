<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'category_type',
        'category_gender',
        'category_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $categories = self::orderBy('category_id', 'DESC')->limit($limit);

        if (isset($params['q'])) {
            $categories->where(function (Builder $query) use ($params) {
                $query->where('category_name',  $params['q']);
            });

            unset($params['q']);
        }

        if($lastId) $categories->where('category_id', '<', $lastId);

        if($params) $categories->where($params);

        if($id) $categories->where('category_id', $id);

        return $id ? $categories->first() : $categories->get();
    }

    public static function submit($param, $id)
    {
        if($id) return self::where('category_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }

}