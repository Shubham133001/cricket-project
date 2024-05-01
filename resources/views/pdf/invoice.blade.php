<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
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
        /* .invoicestatus_paid, .invoicestatus_unpaid {
            
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
            text-align:left;
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
                @if($invoice->status == 1)
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
                #{{ $invoice->id }}
            </td>
            <td >
                {{ $invoice->created_at }}
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
                {{ $invoice->patient->name }}
            </td>
            <td >
                {{ $invoice->patient->phonenumber }}
            </td>
            <td >
                {{ $invoice->patient->age }}
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
            <tr v-for="item in invoice.invoice_items">
                <td>{{ $invoice->description }}</td>
                <td>{{ $invoice->amount }}</td>
            </tr>
        </tbody>
    </table>
    <h2>Payment Details</h2>
    @if(!is_null($invoice->payment))
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Payment Amount</th>
            <th>Payment Date</th>
            <th>Payment Status</th>
        </tr>
        <tr>
            <td>{{ $invoice->payment->payment_id }}</td>
            <td>{{ $invoice->payment->amount }}</td>
            <td>{{ $invoice->payment->created_at }}</td>
            <td>{{ $invoice->payment->status }}</td>
        </tr>
    </table>
    @else
    <p>No payment has been made for this invoice.</p>
    @endif
</body>
</html>