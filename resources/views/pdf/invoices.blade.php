<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 00px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            text-align: left;
        }

        /* .invoicestatus_paid,
        .invoicestatus_unpaid {

            float: right;
            font-size: 30px;
        } */

        .invoicestatus_paid {
            color: green;
        }

        .invoicestatus_unpaid {
            color: red;
        }

        .storename {
            text-align: left;
            width: 50%;
            float: left;
        }
        .removeborder{
            border: none;
            font-size: 30px;
        }
    </style>
</head>

<body>
    <table class="removeborder">
        <tr>
            <th class="removeborder">
                {{$store->name}}
            </th>          
            <th class="removeborder">
                @if($maininvoice[0]->status == 1)
                    <h4 class="invoicestatus_paid">Paid</h4>
                @else
                    <h4 class="invoicestatus_unpaid">Unpaid</h4>
                @endif
            </th>
        </tr>
    </table>
    <h2>Invoice Details</h2>
    <table>
        <tr>
            <th>
                Invoice ID
            </th>          
            <th>
                Invoice Date
            </th>
         </tr>
         <tr>
            <td >
                #{{ $maininvoice[0]->id }}
            </td>
            <td >
                {{ $maininvoice[0]->created_at }}
            </td>
         </tr>
    </table>
   
    <h2>Patient Details</h2>
    <table>
        <tr>
            <th>
                <b>Patient Name</b>
            </th>          
            <th>
                <b>Patient Phone</b>
            </th>
            <th>
                Patient Age
            </th>
           
         </tr>
         <tr>
            <td>
                {{ $maininvoice[0]->patient->name }}
            </td>
            <td >
                {{ $maininvoice[0]->patient->phonenumber }}
            </td>
            <td >
                {{ $maininvoice[0]->patient->age }}
            </td>
         </tr>
    </table>
    <h2>Invoice Items</h2>
    <table>
        <thead>
            <tr>
                <th class="text-left">Service Description</th>
                <th class="text-left">Service Price</th>
            </tr>
        </thead>
        <tbody>

            @foreach($maininvoice[0]->invoiceitems as $item)
            <tr>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['amount'] }}</td>
            </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td><b>Sub Total: </b><b>{{ $maininvoice[0]->subtotal }}</b></td>
            </tr>
        </tbody>
    </table>
    <h2>Payment Details</h2>
    @if(!is_null($maininvoice[0]->payment))
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Payment Amount</th>
            <th>Payment Date</th>
            <th>Payment Status</th>
        </tr>
        @foreach($maininvoice[0]->invoiceitems as $item1)
        
        <tr>
            <td>{{ $item1['payment']->payment_id }}</td>
            <td>{{ $item1['payment']->amount }}</td>
            <td>{{ $item1['payment']->created_at }}</td>
            <td>{{ $item1['payment']->status }}</td>

        </tr>

        @endforeach
    </table>
    @else
    <p>No payment has been made for this invoice.</p>
    @endif
</body>

</html>