<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="margin: 0; padding: 0" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

    <title>Transfer Payment Invoice | Circl</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/brands.css"
        integrity="sha384-nT8r1Kzllf71iZl81CdFzObMsaLOhqBU1JD2+XoAALbdtWaXDOlWOZTR4v1ktjPE" crossorigin="anonymous">

    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0
        }

    </style>
</head>

<body style="margin:0; padding:0;">
    <table
        style="padding: 0px;border-spacing: 0px;mso-table-lspace:0pt; mso-table-rspace:0pt; margin: 0 auto; border-collapse:collapse;"
        width="100%" align="center">
        <tbody>
            <tr>
                <td valign="top">
                    <table style="padding: 0px;border-spacing: 0px;border: 1px solid #0000;" width="100%"
                        align="center">
                        <tbody style="border: 1px solid #0000;">
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td align="center" style="line-height: 0px;">
                                    <a href="#" style="display:block; margin: 0 auto;">
                                        <img src="{{ asset('public/assets/img/logo-2.png') }}" alt="img"
                                            style="display:block; line-height:0px; font-size:0px; border:0px;width: 300px;height: 150px">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td align="center"
                                    style="text-align: center;font-size:20px;  font-family:'Poppins' , 'arial'; font-weight:700 ; line-height: 13px; color: #31292A;">
                                    Transfer Payment Invoice
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tr>
                            <td style="vertical-align: top">
                                <table style="padding: 20px;" width="100%" align="left">
                                    <tbody>
                                        <tr>
                                            <!-- Start Title -->
                                            <td
                                                style="font-size:20px;  font-family:'Poppins' , 'arial'; font-weight:700 ; line-height: 13px; color: #31292A;">
                                                Banking Information Detail
                                            </td>
                                            <!-- End Title -->
                                        </tr>
                                        <tr>
                                            <td
                                                style="border: 1px solid #0000;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                Account Title:
                                                <b>{{ $withdrawal_data->account_title }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border: 1px solid #0000;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                Account Name:
                                                <b>{{ $withdrawal_data->account_name }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border: 1px solid #0000;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                Account Number:
                                                <b>{{ $withdrawal_data->account_number }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border: 1px solid #0000;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                Account IBAN:
                                                <b>{{ $withdrawal_data->iban_account_number }}</b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="vertical-align: top">
                                <table style="padding: 20px;" width="100%" align="left">
                                    <tbody>
                                        <tr>
                                            <td
                                                style=";text-align: left; font-size:20px;  font-family:'Poppins' , 'arial'; font-weight:700 ; line-height: 13px; color: #31292A;">
                                                Receipt Information
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                Invoice ID:
                                                <b>{{ $withdrawal_data->invoice_id ?? '' }}</b>
                                            </td>
                                        </tr>
                                        @if ($withdrawal_data->reciept_id)
                                            <tr>
                                                <td
                                                    style="font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                    Receipt ID:
                                                    <b>{{ $withdrawal_data->reciept_id ?? '' }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                    Receipt URL:
                                                    <b>
                                                        <a style="color: #000;font-weight: bold"
                                                            href="{{  'https://d222gg0z1b3vh.cloudfront.net/uploads/receipt/' . $withdrawal_data->reciept_url }}">
                                                            Open Receipt
                                                        </a>
                                                    </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:15px; text-align:left; color: #31292A;">
                                                    Receipt Date:
                                                    <b>{{ $withdrawal_data->receipt_date }}</b>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table border="0" style="padding: 0px 20px 20px 20px;" width="100%" align="center">
                        <tbody>
                            <tr>
                                <td width="100%" valign="top">
                                    <table border="0"
                                        style="padding: 0px; background: #31292A; background-color: #31292A;"
                                        width="100%" align="center">
                                        <tr>
                                            <td
                                                style="vertical-align: middle;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:left; color: #fff;padding-bottom: 8px">
                                                Earning Type
                                            </td>
                                            <td
                                                style="vertical-align: middle;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:center; color: #fff;padding-bottom: 8px">
                                                Status
                                            </td>
                                            <td
                                                style="vertical-align: middle;font-size:14px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:right; color: #fff;padding-bottom: 8px">
                                                Earn Amount
                                            </td>
                                        </tr>
                                    </table>
                                    <table border="0" style="padding: 0px;" width="100%" align="center">
                                        @php
                                            $total_earning = 0;
                                        @endphp
                                        @foreach ($withdrawal_data->withdrawalEarnings as $withdrawal_earning)
                                            @php
                                                $total_earning += $withdrawal_earning->earned_amount;
                                            @endphp
                                            <tr>
                                                <td
                                                    style="vertical-align: middle;font-size:12px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:left; color: #31292A;">
                                                    <strong>
                                                        @if ($withdrawal_earning->subscription_id != null)
                                                            {{ 'Subscription' }}
                                                        @elseif ($withdrawal_earning->class_booking_id != null)
                                                            {{ 'Class' }}
                                                        @elseif ($withdrawal_earning->appointment_id != null)
                                                            {{ 'Appointment' }}
                                                        @endif
                                                    </strong>
                                                </td>
                                                <td
                                                    style="vertical-align: middle;font-size:12px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:left; color: #31292A;">
                                                    {{ config('arrays.withdraw_statuses.' . $withdrawal_data['schedule_status'] . '.text') }}
                                                </td>
                                                <td
                                                    style="vertical-align: middle;font-size:12px; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:center; color: #31292A;">
                                                    {{ round($withdrawal_earning->earned_amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <table>
                                        <tr>
                                            <td style="border: 0px solid #ffff" height="10"></td>
                                        </tr>
                                    </table>
                                    <table border="0" align="right" width="100%" style="border-top: 1px solid #000">
                                        <tbody>
                                            <tr>
                                                <td
                                                    style="font-size:14px;letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:500 ; line-height:20px; text-align:right; color: #31292A; vertical-align: top;">
                                                    <b>Total</b>
                                                </td>
                                                <td
                                                    style="font-size:13px;padding-right: 40px; vertical-align: top; letter-spacing: .8px; font-family: 'Poppins' , arial; font-weight:600 ; line-height:20px; text-align:right; color: #31292A;">
                                                    {{ round($total_earning, 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
{{-- @dd("Here") --}}
