<?php
$parsedProducts = [];
foreach ($products as $p) {
    if (!isset($parsedProducts[$p->prodcolor_slug])) {
        $parsedProducts[$p->prodcolor_slug] = [
            'id' => $p->product_id,
            'media' => $p->media_file,
            'name' => $p->product_name,
            'code' => $p->product_code,
            'total' => $p->ordprod_total,
            'sizes' => [],
        ];
    }
    $parsedProducts[$p->prodcolor_slug]['sizes'][] = $p;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDER #{{ $data['order']->order_code }}</title>
</head>

<body>
    <p><img src="assets/img/logo_clean.png" alt="S MODE" width="100"></p>
    <br>
    <div style="font-size: 80%">
        <p>
            <b>Proforma Invoice</b><br>
            <br>
            Proforma Invoice #: {{ $data['order']->order_proforma }} <br>
            Date: {{ $data['order']->order_proformatime }} <br>
            Order #:{{ $data['order']->order_code }}<br>
            Season: {{ $data['order']->season_name }}<br>
            Customer name: {{ $data['retailer']->retailer_fullName }}<br>
            Customer ID: {{ $data['retailer']->retailer_code }}<br>
            Placed: {{ $data['order']->order_placed }}<br>
            Order type: {{ $data['order']->order_type == 1 ? 'Immediate' : 'Pre-order' }}
        </p>
        <br>
        <table style="border-collapse: collapse; width: 100%">
            <tbody>
                <tr>
                    <td style="padding: 5px; width: 50%"><b>Billing address</b></td>
                    <td style="padding: 5px; width: 50%"><b>Shipping address</b></td>
                </tr>
                <tr>
                    <td style="padding: 0 5px; width: 50%">
                        {{ $data['address']->address_line1 }}</td>
                    <td style="padding: 0 5px; width: 50%"> {{ $data['address']->address_line1 }}</td>
                </tr>
                <tr>
                    <td style="padding: 0 5px; width: 50%">{{ $data['address']->address_line2 }}</td>
                    <td style="padding: 0 5px; width: 50%"> {{ $data['address']->address_line2 }}</td>
                </tr>
                <tr>
                    <td style="padding: 0 5px; width: 50%">Postal code: {{ $data['address']->address_zip }}</td>
                    <td style="padding: 0 5px; width: 50%">Postal code: {{ $data['address']->address_zip }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        @if ($data['order']->order_note)
            <p><b>Notes</b><br>{{ $data['order']->order_note }}</p>
        @endif
    </div>
    <?php $qty = 0; ?>
    @foreach ($parsedProducts as $product)
        <?php $total = 0;
        $productQty = 0; ?>
        <div style="border: 1px solid #ccc; margin-bottom: 20px; padding:15px; font-size: 80%; width: 100%;">
            <table style="border-collapse: collapse; width: 100%">
                <tr>
                    <td style="vertical-align: top; padding: 5px">
                        <img src="{{ public_path('/media/product/' . $product['id'] . '/' . $product['media']) }}"
                            alt="photo" width='100'>
                    </td>
                    <td style="vertical-align: top">
                        <p style="margin: 0"><b>{{ $product['name'] }} #{{ $product['code'] }}</b></p>

                        <table style="font-size: 12px; margin-top: 15px; font-size: 90%; width: 100%">
                            <thead>
                                <tr style="text-align: center; font-weight: bold">
                                    <th style="padding: 5px">color</th>
                                    <th style="padding: 5px">size</th>
                                    <th style="padding: 5px">wsp</th>
                                    <th style="padding: 5px">qty</th>
                                    <th style="padding: 5px">total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product['sizes'] as $s)
                                    {{ $total += $s->ordprod_total, $productQty += $s->ordprod_request_qty, $qty += $s->ordprod_request_qty }}

                                    <tr style="text-align: center">
                                        <td style="border-top: 1px solid #ccc; padding: 5px">
                                            {{ $s->prodcolor_name }}
                                        </td>
                                        <td width="70" style="border-top: 1px solid #ccc; padding: 5px">
                                            {{ $s->size_name }}
                                        </td>
                                        <td width="70" style="border-top: 1px solid #ccc; padding: 5px">
                                            {{ $s->prodsize_wsp }}</td>
                                        <td width="50" style="border-top: 1px solid #ccc; padding: 5px">
                                            {{ $s->ordprod_request_qty }}
                                        </td>
                                        <td width="80" style="border-top: 1px solid #ccc; padding: 5px">
                                            {{ number_format($s->ordprod_request_qty * $s->prodsize_wsp, 2, '.', '') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="text-align: center">
                                    <td colspan="3" style="border-top: 1px solid #ccc; padding: 5px"></td>
                                    <td style="border-top: 1px solid #ccc; padding: 5px"><?= $productQty ?></td>
                                    <td style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ $order->currency_code }}
                                        {{ $product['total'] }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
    <div style="font-size: 80%">
        <p><b>Total qty: {{ $qty }}</b><br>
            @if ($data['order']->order_discount > 0)
                <b>Subtotal: {{ $data['order']->currency_code }} {{ $data['order']->order_subtotal }}</b><br>
                <b>Discount: {{ $data['order']->currency_code }} -{{ $data['order']->order_discount }}</b><br>
            @endif
            <b>Total: {{ $data['order']->currency_code }} {{ $data['order']->order_subtotal }}</b><br>
            <b>Advance Payment: {{ $data['order']->currency_code }} {{ $data['retailer']->retailer_adv_payment }}</b>
        </p>
        <br>
        <p style="text-align: center"><i>Thank you for your business!</i></p>
        <br>
    </div>
</body>

</html>
