<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <div style='text-align: center'>
        @if(isset($logo) && $logo != '')
        <img style="width: 200px;" src="{{ $logo }}" class="logo" alt="{{ $companyname }}">
        @else
        {{ $companyname }}
        @endif
        <h2>You are receiving this email because we received a password reset request for your account.</h2>
        <br /><a href="{{ $url}}" style='background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;'>Reset Password</a><br /><br />
        If you did not request a password reset, no further action is required.
        <p>© {{ $companyname }} 2024. All Rights Reserved</p>
    </div>

</body>

</html>