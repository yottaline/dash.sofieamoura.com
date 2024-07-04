<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoice extends Mailable
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
        return $this->subject("Order #{$this->order_code} invoice | {$this->retailer}")
            ->view('email_templates.orders.InvoiceConfirmation')
            ->with([
                'retailer' => $this->retailer,
                'order_code' => $this->order_code
            ])
            ->attach(storage_path('app/public/' . $this->pdfPath), [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
