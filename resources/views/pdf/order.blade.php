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
    <title>COMMANDE #{{ $order->order_code }}</title>
</head>

<body>
    <p><img src="{{ public_path('/assets/img/logo.png') }}" alt="Sofie Amoura" width="120"></p>
    <p>
        9 RUE DE MARIGNAN<br>
        75008 PARIS-FRANCE<br>
        +33 6 14 63 80 55<br>
        N° TVA FR87928114164
    </p>
    <br>
    <div style="font-size: 80%">
        <p>
            N° de commande: #{{ $order->order_code }}<br>
            Date de commande: {{ $order->order_placed }}<br>

            {{-- Season: {{ $order->season_name }}<br>
            Customer name: {{ $retailer->retailer_fullName }}<br>
            Customer ID: {{ $retailer->retailer_code }}<br>
            Order type: {{ $order->order_type == 1 ? 'Immediate' : 'Pre-order' }} --}}
        </p>
        <br>
        <table style="border-collapse: collapse; width: 100%">
            <tbody>
                <tr>
                    <td style="padding: 5px; width: 50%"><b>Adresse de facturation</b></td>
                    <td style="padding: 5px; width: 50%"><b>Adresse de livraison</b></td>
                </tr>
                <tr>
                    <td style="padding: 0 5px; width: 50%">
                        {{ $billAddress->address_line1 }}<br>
                        {{ $billAddress->address_line2 }}
                    </td>
                    <td style="padding: 0 5px; width: 50%">
                        {{ $shipAddress->address_line1 }}<br>
                        {{ $shipAddress->address_line2 }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 5px; width: 50%">{{ $billAddress->address_city }},
                        {{ $billAddress->address_country }}</td>
                    <td style="padding: 0 5px; width: 50%">{{ $shipAddress->address_city }},
                        {{ $shipAddress->address_country }}</td>
                </tr>
                <tr>
                    <td style="padding: 0 5px; width: 50%">{{ $billAddress->address_note }}</td>
                    <td style="padding: 0 5px; width: 50%">{{ $shipAddress->address_note }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        @if ($order->order_note)
            <p>{{ $order->order_note }}</p>
        @endif
    </div>
    <?php $qty = 0; ?>
    @foreach ($parsedProducts as $product)
        <?php $total = 0;
        $productQty = 0; ?>
        <div style="margin-bottom: 20px; padding:15px; font-size: 80%; width: 100%;">
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
                                    <th style="padding: 5px">Couleur</th>
                                    <th style="padding: 5px">Taille</th>
                                    <th style="padding: 5px">Prix</th>
                                    <th style="padding: 5px">Quantité</th>
                                    <th style="padding: 5px">Total</th>
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
        <p><b>QUANTITÉ: {{ $qty }}</b><br>
            @if ($order->order_discount > 0)
                <b>SUUSTOTAL: {{ $order->currency_code }} {{ $order->order_subtotal }}</b><br>
                <b>REMISE: {{ $order->currency_code }} {{ $order->order_discount }}</b><br>
            @endif
            <b>TOTAL MONTANT HT: {{ $order->currency_code }} {{ $order->order_total }}</b><br>
            <b>TOTAL MONTANT TVA: {{ $order->currency_code }} {{ number_format($order->order_total, 2) }}</b><br>
            <b>MONTANT DE LA FACTURE: {{ $order->currency_code }} {{ number_format($order->order_total, 2) }}</b><br>
            {{-- <b>Advance Payment: {{ $order->currency_code }} {{ $retailer->retailer_adv_payment }}</b> --}}
        </p>
        <br>
        <p style="text-align: center"><i>Merci pour votre achat chez Sofie Amoura</i></p>
        <br>
    </div>
</body>

</html>
