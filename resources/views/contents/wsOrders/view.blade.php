<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
    <title>Order</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        label {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            height: 28px;
            text-align: right;
            font-size: 16px;
            font-family: sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }

        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }

        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }

        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }

        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
        }

        .no-border {
            border: 1px solid #fff !important;
        }

        .bg-blue {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>

<body>

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" colspan="2" class="text-start company-data">
                    <span> Order Serial Number : {{ $order->order_code }}</span> <br>
                    <span> Order Date : {{ $order->order_created }}</span> <br>
                </th>
                <th width="50%" colspan="2">
                    <h2 class="text-center">SOFIE AMOURA <i class="bi bi-brilliance"></i></h2>
                </th>

            </tr>
            <tr class="bg-blue">
                <th width="50%" class="text-center" colspan="2">Order details</th>
                <th width="50%" class="text-center" colspan="2">Retailer details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> Order number :</td>
                <td> {{ $order->order_id }}</td>

                <td>Retailer name </td>
                <td>{{ $retailer->retailer_fullName }}</td>
            </tr>
            <tr>
                <td>Order Date </td>
                <td> {{ $order->order_created }}</td>

                <td>Email </td>
                <td>{{ $retailer->retailer_email }}</td>
            </tr>
            <tr>
                <td>Order date placed</td>
                <td>{{ $order->order_placed }}</td>

                <td> Retailer Phone</td>
                <td>{{ $retailer->retailer_phone }}</td>
            </tr>
            <tr>
                <td>Order status</td>
                <td>
                    @if ($order->order_status == 0)
                        DRAFT
                    @endif
                    @if ($order->order_status == 1)
                        CANCELLED
                    @endif
                    @if ($order->order_status == 2)
                        PLACED
                    @endif
                    @if ($order->order_status == 3)
                        CONFIRMED
                    @endif
                    @if ($order->order_status == 4)
                        ADVANCE PAYMENT IS PENDING
                    @endif
                    @if ($order->order_status == 5)
                        BALANCE PAYMENT IS PENDING
                    @endif
                    @if ($order->order_status == 6)
                        SHIPPED
                    @endif
                </td>

                <td> Retailer Code </td>
                <td>{{ $retailer->retailer_code }}</td>
            </tr>

            <tr>
                <td>Bill Address</td>
                <td> {{ $order->order_bill_province }},{{ $order->order_bill_zip }} ,{{ $order->order_bill_city }},
                    {{ $order->order_bill_line1 }}, {{ $order->order_bill_line2 }}</td>
            </tr>
            <tr>
                <td>Sipping Address</td>
                <td> {{ $order->order_ship_province }},{{ $order->order_ship_zip }} ,{{ $order->order_ship_city }},
                    {{ $order->order_ship_line1 }}, {{ $order->order_ship_line2 }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="no-border text-end heading" colspan="5">
                    PRODUCTS
                </th>
            </tr>
            <tr class="bg-blue">
                <th>#</th>
                <th class="text-center">Product</th>
                <th class="text-center">Product Color</th>
                <th class="text-center">Product Size</th>
                <th class="text-center">Product Price</th>
                <th class="text-center">Product Discount</th>
                <th class="text-center">QTY</th>
                <th>Before discount</th>
                <th>After discount</th>
            </tr>
        </thead>
        <tbody>

            @forelse($orderData as $o)
                <tr>
                    <td width="10%">{{ $o->product_code }}</td>
                    <td class="text-center">
                        {{ $o->product_name }}
                    </td>
                    <td width="10%">{{ $o->prodcolor_name }}</td>
                    <td width="10%">{{ $o->size_name }}</td>
                    <td width="10%">{{ $o->prodsize_wsp }}</td>
                    <td width="10%">{{ $o->prodcolor_discount }}</td>
                    <td width="10%">{{ $o->ordprod_request_qty }}</td>
                    <td width="15%" class="fw-bold">{{ $o->ordprod_subtotal }}</td>
                    <td width="15%" class="fw-bold">{{ $o->ordprod_total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5"> <i class="bi bi-exclamation-circle display-3"></i></td>
                    <td>
                        <i class="bi bi-exclamation-circle display-3"></i>
                    </td>
                    <td> <i class="bi bi-exclamation-circle display-3"></i></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="8">Order Discount :</td>
                <td>{{ $order->order_discount }}</td>
            </tr>
            <tr>
                <td colspan="8">Shipping fees :</td>
                <td>{{ $order->order_shipping }}</td>
            </tr>

            <tr>
                <td colspan="8" class="total-heading">Total :</td>
                <td class="total-heading">
                    @if ($order->order_total !== '0.00')
                        {{ $order->order_total + $order->order_shipping }}
                    @else
                        {{ $order->order_subtotal + $order->order_shipping }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <br>
    <p class="text-center">
        Thanks for shopping <i class="bi bi-heart"></i>
    </p>

</body>

</html>
