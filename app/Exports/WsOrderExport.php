<?php

namespace App\Exports;

use App\Models\Ws_order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WsOrderExport implements FromCollection, WithHeadings
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {


        return [
            'ORDER',
            'RETAILER CODE',
            'RETAILER COMPANY',
            'RETAILER NAME',
            'PRODUCT CODE',
            'PRODUCT NAME',
            'CATEGORY',
            'PRODUCT INFO',
            'SIZE',
            'COLOR',
            'WSP',
            'QTY',
            'TOTAL',
            'SHIP QTY',
            'SHIP TOTAL',
        ];
    }
}
// Season Name
// 'Retailer Email',
