<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDER #{{ $order->order_code }}</title>
</head>

<body>
    <p><img src="assets/img/logo_clean.png" alt="S MODE" width="100"></p>
    <br>
    <div style="font-size: 80%">
        <p>
            <b>Invoice</b><br>
            <br>
            Invoice #: {{ $order->order_invoice }} <br>
            Date: {{ $order->order_invoicetime }} <br>
            Order #:{{ $order->order_code }}<br>
            Season: {{ $order->season_name }}<br>
            Customer name: {{ $retailer->retailer_fullName }}<br>
            Customer ID: {{ $retailer->retailer_code }}<br>
            Placed: {{ $order->order_placed }}<br>
            Order type: {{ $order->order_type == 1 ? 'Immediate' : 'Pre-order' }}
        </p>
        <br>
        @if ($order->order_note)
            <p><b>Notes</b><br>{{ $order->order_note }}</p>
        @endif
    </div>
    <?php $qty = 0; ?>
    @foreach ($products as $product)
        <?php $total = 0;
        $productQty = 0; ?>
        <div style="border: 1px solid #ccc; margin-bottom: 20px; padding:15px; font-size: 80%; width: 100%;">
            <table style="border-collapse: collapse; width: 100%">
                <tr>
                    <td style="vertical-align: top; padding: 5px">
                        <a href="#">
                            <img src="{{ public_path('/media/product/' . $product->product_id . '/' . $product->media_file) }}"
                                alt="photo" width='100'>
                        </a>
                    </td>
                    <td style="vertical-align: top">
                        <p style="margin: 0"><b>{{ $product->product_name }} #{{ $product->product_code }}</b></p>

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
                                {{ $total += $product->ordprod_total,
                                    $productQty += $product->ordprod_request_qty,
                                    $qty += $product->ordprod_request_qty }}
                                <tr style="text-align: center">
                                    <td width="110" style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ $product->prodcolor_name }}
                                    </td>
                                    <td width="110" style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ $product->size_name }}
                                    </td>
                                    <td width="70" style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ $product->prodsize_wsp }}</td>
                                    <td width="50" style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ $product->ordprod_request_qty }}
                                    </td>
                                    <td width="80" style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ number_format($product->ordprod_request_qty * $product->prodsize_wsp, 2, '.', '') }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr style="text-align: center">
                                    <td colspan="3" style="border-top: 1px solid #ccc; padding: 5px"></td>
                                    <td style="border-top: 1px solid #ccc; padding: 5px"><?= $productQty ?></td>
                                    <td style="border-top: 1px solid #ccc; padding: 5px">
                                        {{ $order->currency_code }}
                                        {{ $product->ordprod_total }}</td>
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
            @if ($order->order_discount > 0)
                <b>Subtotal: {{ $order->currency_code }} {{ $order->order_subtotal }}</b><br>
                <b>Discount: {{ $order->currency_code }} -{{ $order->order_discount }}</b><br>
            @endif
            <b>Total: {{ $order->currency_code }} {{ $order->order_subtotal }}</b><br>
            <b>Advance Payment: {{ $order->currency_code }} {{ $retailer->retailer_adv_payment }}</b>
        </p>
        <br>
        <p style="text-align: center"><i>Thank you for your business!</i></p>
        <br>
    </div>
</body>

</html>
