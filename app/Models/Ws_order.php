<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_order extends Model
{
    use HasFactory;
    public $timestamps = false;
    // $table->integer('order_id', true, true);
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

    // public function
}