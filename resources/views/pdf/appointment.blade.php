<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Appointment Receipt</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
        .holder {
            background: url(/storage/entleter.jpg) no-repeat;
            width: 791px;
            height: 1024px;
            /* border: solid 1px red; */
            font-family: Poppins;
            font-size: 11px;
        }

        .half {
            width: 475px;
            float: left;
            /* border: solid 1px red; */
            margin-left: 15px;
        }
        .half1 {
            width: 300px;
            float: right;
            text-align: left;
            /* border: solid 1px red; */
        }
        .info
        {
            margin-top: 190px;
        }

        .info p strong
        {
            font-weight: 600;
            color: #e42e26;
        }
        #appointment-type{
            background-color: #ede5db;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="holder">
        <div class="info">
            <div class="half">
                <p><strong>Name:</strong> {{ $appointment->patient->name }}</p>
                <p><strong>Age/Sex:</strong> {{ $appointment->patient->age }}/ @if($appointment->patient->gender == '1') Male @elseif($appointment->patient->gender == '0') Female @else Other @endif</p>
            </div>
            <div class="half1">
                <p><strong>Date:</strong> {{ $appointment->booking_date }} {{ $appointment->time_slot }}
                    <span id="appointment-type">
                        @isset($appointment->type)
                            @if($appointment->type === 'online')
                                 {{ Str::upper('appoint') }}
                            @elseif ($appointment->type === 'offline')
                                @if($appointment->walkin_category === "Another" && $appointment->another_appointment)
                                    {{ Str::upper($appointment->another_appointment->another_name) }}
                                
                                @elseif ($appointment->walkin_category !== "Another" )
                                    {{ Str::upper($appointment->walkin_category) }}
                                
                                @endif
                          
                            @endif
                        @endisset
                    </span>
                </p>
                <p>
                    <strong>Mobile No:</strong> {{ $appointment->patient->phonenumber }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>