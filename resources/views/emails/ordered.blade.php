<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ordered</title>

    <style type="text/css">
        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            width: 100% !important;
            height: 100%;
            line-height: 1.6em;
            background-color: #f6f6f6;
            box-sizing: border-box;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .content {
            max-width: 600px;
            display: block;
            margin: 0 auto;
            padding: 20px;
        }

        main {
            border: 1px solid #e9e9e9;
            border-radius: 3px;
            background-color: #fff;
            padding: 20px;
        }

        header {
            font-size: 24px;
            font-weight: 400;
            text-align: center;
            margin-top: 40px;
            padding-bottom: 20px;
        }

        footer {
            color: #999;
            font-size: 12px;
            padding: 20px;
            padding-bottom: 40px;
            text-align: center;
        }

        .bt {
            border-top: 1px solid black;
            padding: 5px 0;
        }

        @media only screen and (max-width: 640px) {
            .content {
                padding: 0 !important;
            }

            main {
                padding: 10px !important;
            }

            header {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
                font-size: 18px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <div class="content">
        <main>
            <header>Thanks for Ordering</header>
            <div class="invoice" style="text-align: left; width: 80%; margin: 40px auto">
                <div style="padding: 5px 0">
                    {{ ucfirst($name) ?? '' }}<br />Order Code :{{ $ordercode }}<br />{{ $date->format('d-m-Y') }}
                </div>
                <table style="width: 100%; border-spacing: 0">
                    <tr>
                        <td class="bt">Product</td>
                        <td class="bt">Qty</td>
                        <td class="bt" style="text-align: right">Price</td>
                    </tr>
                    @foreach ($items as $i)
                        <tr>
                            <td class="bt">{{ $i->product->name }}</td>
                            <td class="bt">{{ $i->count }}</td>
                            @php
                                $discount = $i->product->price->discount;
                                $base = $i->product->price->base;
                                $price = $discount ? $discount * $i->count : $base * $i->count;
                            @endphp
                            <td class="bt" style="text-align: right">$ {{ number_format($price, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bt">Total</td>
                        <td class="bt"></td>
                        <td class="bt" style="text-align: right">$ {{ $total }}</td>
                    </tr>
                </table>
            </div>
        </main>
        <footer>
            Questions? Email
            <a href="#" style="color: #999">support@example.com</a>
        </footer>
    </div>
</body>

</html>
