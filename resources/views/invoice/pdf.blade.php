<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            /* Monospace for thermal printer look */
        }

        .receipt {
            /* max-width: 320px; */
            border: 1px dashed #000;
            padding: 15px;
            margin: auto;
            background: #fff;
        }

        .header {
            text-align: center;
            font-weight: bold;
        }

        .table td,
        .table th {
            text-align: center;
            vertical-align: middle;
            font-size: 48px;
        }

        p,
        h5 {
            font-size: 50px;
        }

        .bold {
            font-weight: bold;
        }

        .amount-box {
            border: 1px solid #000;
            padding: 5px;
            font-size: 48px;
        }

        .amount-box td {
            text-align: center;
            font-size: 40px;
            padding: 2px;
        }

        .dotted-line {
            border-top: 1px dashed black;
            margin: 10px 0;
        }

        p {
            margin-bottom: 0px;
        }

    </style>
</head>

<body>

    <div class="receipt">
        <div class="text-center">
            <p class="mb-1">🕉️ Jai Shree Ganesh 🕉️<br>Jai Shree Krishna</p>
            <h5 class="fw-bold">STANNY'S</h5>
            <p>LE MONDE DU LUXE</p>
        </div>

        <div class="dotted-line"></div>
        <div class="d-flex justify-content-between align-items-start">
            <!-- Left Column (Personal Info) -->
            <div class="col-8">
                <p><strong>Mr/Mrs:</strong> {{ $invoice->customer->name }}</p>
                <p><strong>Rank:</strong> {{ $invoice->customer->employee_rank }}</p>
            </div>

            <!-- Right Column (Amount Details) -->
            <div class="col-4">
                <table class="table table-sm table-bordered amount-box">
                    <tr>
                        <td>Amount:</td>
                        <td class="fw-bold">{{ number_format($invoice->net_price, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Deposit:</td>
                        <td class="fw-bold">{{ number_format($invoice->net_price-$invoice->required_payment_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Balance:</td>
                        <td class="fw-bold">{{ number_format($invoice->required_payment_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <p><strong>Co/Ins Name:</strong> {{ $invoice->customer->company_name }}</p>
        <p><strong>Address:</strong> 123 Main Street</p>

        <div class="dotted-line"></div>

        <table class="table table-sm table-bordered mt-3">
            <thead>
                <tr>
                    <th>ITEM DESC</th>
                    <th>QTY</th>
                    <!-- <th>DISC</th> -->
                    <th>NET AMT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->items as $item)
                    <tr>
                    {{ number_format($invoice->net_price/$item->quantity, 2) }}
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <!-- <td>3960.00</td> -->
                        <td>{{ number_format( ($item->piece_price) *($item->quantity) , 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="dotted-line"></div>

        <table class="table table-sm">
            <tr>
                <td class="bold">SUBTOTAL</td>
                <td class="text-end">{{ number_format($invoice->net_price, 2) }}</td>
            </tr>
            <!-- <tr>
                <td class="bold">TOTAL</td>
                <td class="text-end">3240.00</td>
            </tr> -->
            <!-- <tr>
                <td class="bold">GVOWN</td>
                <td class="text-end">3000.00</td>
            </tr> -->
            <tr>
                <td class="bold">BALANCE DUE</td>
                <td class="text-end">{{ number_format($invoice->required_payment_amount, 2) }}</td>
            </tr>
        </table>

        <div class="dotted-line"></div>

        <p class="text-center">Your mobile number has been registered with <br>Big Bazaar.</p>

        <p class="bold">PIECES PURCHASED: {{count($invoice->order->items)}}</p>

        <div class="dotted-line"></div>

        <p class="text-center">Thank you for shopping with us! 😊</p>
    </div>

</body>

</html>
