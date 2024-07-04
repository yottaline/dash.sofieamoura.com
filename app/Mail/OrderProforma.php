<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderProforma extends Mailable
{
    use Queueable, SerializesModels;

    public $retailer;
    public $order_code;
    public $pdfPath;

    function __construct($retailer, $order_code, $pdfPath)
    {
        $this->retailer = $retailer;
        $this->order_code = $order_code;
        $this->pdfPath = $pdfPath;
    }

    function build()
    {
        return $this->subject("Proforma Invoice {$this->order_code} | {$this->retailer}")
            ->view('email_templates.orders.proformaConfirmation')
            ->with([
                'retailer' => $this->retailer,
                'order_code' => $this->order_code
            ])
            ->attach(storage_path('app/public/' . $this->pdfPath), [
                'as' => 'proforma_invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
