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
            'Order Coder',
            'Placed',
            'Retailer Name',
            'Retailer Email',
            'Product Code',
            'Product Name',
            'Season Name',
            'Size Name',
            'Request Qty',
            'Total Price',
        ];
    }
}
