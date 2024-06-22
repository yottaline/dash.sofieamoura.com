<?php

namespace App\Models;

use App\Exports\WsOrderExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Ws_order extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'order_code',
        'order_season',
        'order_retailer',
        'order_tax',
        'order_shipping',
        'order_subtotal',
        'order_discount',
        'order_total',
        'order_currency',
        'order_type',
        'order_status',
        'order_note',
        'order_bill_country',
        'order_bill_province',
        'order_bill_city',
        'order_bill_zip',
        'order_bill_line1',
        'order_bill_line2',
        'order_bill_phone',
        'order_ship_country',
        'order_ship_province',
        'order_ship_city',
        'order_ship_zip',
        'order_ship_line1',
        'order_ship_line2',
        'order_ship_phone',
        'order_proforma',
        'order_proformatime',
        'order_invoice',
        'order_invoicetime',
        'order_skip_adv',
        'order_modified',
        'order_placed',
        'order_created'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $ws_orders = self::join('seasons', 'order_season', 'season_id')
            ->join('retailers', 'order_retailer', 'retailer_id')
            ->join('currencies', 'order_currency', 'currency_id');

        if ($lastId) $ws_orders->where('order_id', '<', $lastId);

        if (isset($params['q'])) {
            $ws_orders->where(function (Builder $query) use ($params) {
                $query->where('order_code', 'like', '%' . $params['q'] . '%')
                    ->orWhere('order_discount', 'like', '%' . $params['q'] . '%')
                    ->orWhere('order_created', 'like', '%' . $params['q'] . '%');
            });

            unset($params['q']);
        }

        if ($params) $ws_orders->where($params);
        if ($id) $ws_orders->where('order_id', $id);

        return $id ? $ws_orders->first() : $ws_orders->get();
    }

    public static function submit(int $id, array $orderParam = null, array $orderItemParam = null)
    {
        try {
            DB::beginTransaction();
            $status = $id ? self::where('order_id', $id)->update($orderParam) : self::create($orderParam);
            $id = $id ? $id : $status->id;
            if (!empty($orderItemParam)) {
                for ($i = 0; $i < count($orderItemParam); $i++) {
                    $orderItemParam[$i]['ordprod_order'] = $id;
                }
                Ws_orders_product::insert($orderItemParam);
            }
            DB::commit();
            return ['status' => true, 'id' => $id];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
        }
    }
}
