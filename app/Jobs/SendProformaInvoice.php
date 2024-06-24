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
        $address = Retailer_address::fetch(0, [['address_retailer', $retailer->retailer_id]]);
        $orderData = Ws_orders_product::fetch(0, [['ordprod_order', $this->orderId]]);

        $data = [
            'order' => $order,
            'retailer' => $retailer,
            'orderData' => $orderData,
            'address'   => $address[0]
        ];

        $pdf = Pdf::loadView('pdf.profroma', ['data' => $data]);

        $pdfPath = 'proforma/' . $order->order_code . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Mail::to('b2b@sofieamoura.com')->send(new OrderProforma($retailer->retailer_fullName, $order->order_code, $pdfPath));
    }
}