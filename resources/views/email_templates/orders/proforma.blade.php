@extends('email_templates.master')
@section('message')
    <br>
    <h5>Hi {{ $retailer }},</h5>

    <p>Thank you for your order.<br>
        We will send you a proforma invoice confirming your order once we receive your advance payment.
        In the meanwhile, if you have any questions or need assistance, contact us at <a
            href="mailto:info@sofieamoura.com">info@sofieamoura.com</a></p>
    <br>
    <p><b>Please see your ORDER # {{ $order_code }} attached.</b></p>
    <p><a href="{{ asset('storage/orders/' . $order_code . '.pdf') }}">Download Order PDF</a></p>
    <br>
@endsection
