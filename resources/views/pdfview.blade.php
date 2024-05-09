<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>
    <style>
        @page {
            margin: 0;
            size: auto;
        }

        @media print {
            body {
                margin: 1.2cm;
                -webkit-print-color-adjust: exact;
            }
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Roboto', sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 30px;
            line-height: 45px;
            color: #333;
            text-transform: uppercase;
            font-weight: 300;
            font-family: 'Roboto', sans-serif;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.information span:nth-child(1) {
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }

        .invoice-box table tr.heading td {
            background: #0096c7;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            width: 33.33%;
            text-align: left;
            color: #fff;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
            text-align: left;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item:last-child td {
            border-bottom: none;
        }

        .invoice-box .invoice-summary {
            border-top: 2px solid #eee;
            text-align: right;
            display: inline-block;
            float: right;
            padding: 5pt;
            margin-top: 0px;
        }

        .invoice-box .invoice-summary .invoice-total {
            font-weight: bold;
        }

        .invoice-box .invoice-summary .invoice-final {
            font-weight: 300;
            padding-top: 8pt;
        }

        .invoice-box .invoice-summary .invoice-exchange {
            font-weight: 300;
            font-size: 12px;
        }

        .invoiceheading {
            font-size: 35px;
            color: #fff;
            font-weight: bold;
            background: #0096c7;
            width: 200px !important;
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 30px;
            float: left;
        }

        .status {
            font-size: 18px;
            font-weight: bold;
        }

        .status.success {
            color: #06d6a0;
        }

        .status.fail {
            color: #ef476f;
        }
    </style>
</head>

<body>
    <div class="invoice-box">

        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">

                    <table>
                        <tr>
                            <td class="title">
                                @if($storedetail['logo'] != '')
                                <img src="{{ $storedetail['logo'] }}" style="width:100%; max-width:200px;">
                                @else
                                <span class="t-invoice">{{ $storedetail['name'] }}</span>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="t-invoice"><b>Invoice#{{ $item->id }}</b></span>
                                <span class="invoice-id"></span> &nbsp;

                            </td>
                            <td>

                                @if($item->status == 1)
                                <span class="status success">PAID</span>
                                @elseif($item->status == 2)
                                <span class="status success">PARTIALLY PAID</span>
                                @else
                                <span class="status fail">UNPAID</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="information-company">
                                <span class="t-invoice-from" style="font-size: 16px;">Invoice From</span><br>
                                <span id="company-name">{{$storedetail['name']}}</span><br>
                                <span id="company-address">{!! nl2br(e($storedetail['address'])) !!}</span><br>
                                <span id="company-phone">{!! nl2br(e($storedetail['contact'])) !!}</span><br>
                            </td>

                            <td class="information-client">
                                <span class="t-invoice-to" style="font-size: 16px;">Invoice To</span><br>
                                <span id="client-name">{{ $item->user->name }} </span><br>


                                @if($item->user->address != '')
                                <span id="client-address">{!! nl2br(e( $item->user->address )) !!}</span><br>
                                @endif
                                @if($item->user->address2 != '')
                                <span id="client-address">{!! nl2br(e( $item->user->address2 )) !!}</span><br>
                                @endif


                                <span id="client-country"> @if($item->user->country != '')
                                    {{ $item->user->country->name }},
                                    @endif
                                    @if($item->user->zip != '')
                                    {{ $item->user->zip }}
                                    @endif
                                </span><br>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="transaction-items" cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td style="width: 5%;"><span class="t-item"># </span></td>
                <td style="width: 60%;"><span class="t-item">Item </span></td>
                <td><span class="t-price">Price</span></td>
            </tr>

            @foreach($item->items as $key => $product)
            <tr class="item">
                <td><span class="t-item">{{ $key+1 }} </span></td>
                <td style="text-align: left !important;"><span>{{ $product->description }} </span></td>
                <td><span class="t-price"> {{ number_format($product->price,2,'.') }} INR</span></td>
            </tr>
            @endforeach
        </table>




        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <br>


        <table class="transaction-items" cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td style="width: 20%;"><span class="t-item">Date </span></td>
                <td style="width: 30%;"><span class="t-item" style="text-align: left;">Payment Method </span></td>
                <td><span class="t-item">Transaction ID </span></td>
                <td><span class="t-price">Amount</span></td>
                <td><span class="t-item">Transacation Fee</span></td>
            </tr>

            @foreach($item->payment as $key => $transaction)
            <tr class="">
                <td><span class="t-item">{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }} </span></td>
                <td style="text-align: left;"><span class="t-item">{{ $transaction->method  }}</span></td>
                <td><span class="t-item">{{ $transaction->transactionid }} </span></td>
                <td><span class="t-price"> {{ number_format($transaction->amount,2,'.') }} INR</span></td>
                <td><span class="t-price"> {{ number_format($transaction->fee,2,'.') }} INR</span></td>
            </tr>
            @endforeach

        </table>

    </div>
</body>

</html>