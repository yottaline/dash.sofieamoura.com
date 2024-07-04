<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ws_order;
use App\Models\Retailer;
use App\Models\Retailer_address;
use App\Models\Ws_orders_product;
use App\Mail\OrderProforma;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class SendProformaInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        $order = Ws_order::fetch($this->orderId);
        $retailer = Retailer::fetch($order->order_retailer);
        $billAddress = Retailer_address::fetch(0, [
            ['retailer_id', $order->order_retailer],
            ['address_type', '1'],
        ]);
        $shipAddress = Retailer_address::fetch(0, [
            ['retailer_id', $order->order_retailer],
            ['address_type', '2'],
        ]);
        $products = Ws_orders_product::fetch(0, [['ordprod_order', $this->orderId]]);

        $data = [
            'order' => $order,
            'retailer' => $retailer,
            'billAddress' => $billAddress[0],
            'shipAddress' => $shipAddress[0],
            'products' => $products,
        ];

        $pdf = Pdf::loadView('pdf.profroma', ['data' => $data]);

        $pdfPath = 'proforma/' . $order->order_code . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Mail::to('b2b@sofieamoura.com')->send(new OrderProforma($retailer->retailer_fullName, $order->order_code, 'public/' . $pdfPath));
    }
}
