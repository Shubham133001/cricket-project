<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        .holder {
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
            padding: 0;
            padding-bottom: 20px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #000;
            background: #f8f8f8;
        }

        .holder h3 {
            text-align: center;
        }

        .header {
            width: 100%;
            margin: 0;
            padding: 10px 0;
            /* background: #2da8e0; */
            text-align: center;
        }

        .header .logo {
            width: 100%;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .header .logo img {
            width: 100px;
            margin: 0;
            padding: 0;
        }

        .header .title {
            width: 100%;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .header .title h1 {
            font-size: 20px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .info {
            width: 100%;
            margin: 0;
            padding: 0;
            text-align: center;
            border-bottom: solid 2px #a8a8a8;

        }

        .info p {
            margin: 0;
            padding: 5px 10px;
            font-size: 14px;
            line-height: 20px;
        }

        .info p strong {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="holder">
        <div class="header">
            <div class="logo">
                @isset($store->logo)
                    <img src="{{ $store->logo }}" alt="logo" width="100px">
                @else
                    <h1>{{$store->name}}</h1>
                @endif
            </div>
            <div class="title">
                <h1>#{{$appointment->id}} Appointment Booked Successfully</h1>
            </div>
        </div>
        <div class="info">
            <p style="border-bottom: solid 2px #a8a8a8; font-weight: 600" class="pb-1">Your Booking Details are as below.</p>
            <p><strong>Appointment Date:</strong> {{ $appointment->booking_date }} {{ $appointment->time_slot }}</p>
            <p><strong>Doctor Name:</strong> {{ $appointment->doctor->name }}</p>
            <p><strong>Patient Name:</strong> {{ $appointment->patient->name }}</p>
            <p><strong>Patient Phone Number:</strong> {{ $appointment->patient->phonenumber }}</p>
            <p><strong>Your Booking Status:</strong>
                @if($appointment->status == 0)
                Not Confirmed
                @elseif($appointment->status == 1)
                Confirmed
                @elseif($appointment->status == 2)
                Engaged
                @elseif($appointment->status == 3)
                Checkedout
                @elseif($appointment->status == 4)
                Cancelled
                @endif
            </p>
            <p><strong>Your Payment Status:</strong> @if($appointment->invoice->payment != null) Paid @else Unpaid @endif</p>

            <p><strong>Payment ID:</strong> @if($appointment->invoice->payment != null){{ $appointment->invoice->payment->payment_id }}@endif</p>
        </div>
        <h3>Thank you for your Booking </h3>
    </div>
</body>

</html>