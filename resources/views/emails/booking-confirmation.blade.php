<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        :root {
            --primary-color: #000000;
            --text-color: #fff;
            --error-color: #bf0603;
            --success-color: #21c063;
            --hover-color: rgba(0, 0, 0, 0.81);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .email-header {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 20px;
            text-align: center;
        }

        .email-header .logo img{
            width: 100%;
            border-radius: 20px;
        }

        .email-header h2 svg {
            margin: 5px 10px;
        }

        .email-header h2 svg path.success {
            fill: var(--success-color);
        }

        .email-header h2 svg path.error {
            fill: var(--error-color);
        }

        .email-body {
            padding: 30px 25px;
        }

        .email-body h3 {
            margin-top: 0;
            font-size: 20px;
        }

        .email-body h5 {
            font-weight: normal;
            color: #555;
        }

        .details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-left: 4px solid var(--primary-color);
        }

        .details p {
            margin: 8px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: var(--primary-color);
            color: var(--text-color);
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: var(--hover-color);
            color: var(--text-color);
            box-shadow: 0 0 5px var(--success-color);
        }

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            padding: 15px;
        }

        .email-footer .logo img{
            height: 100px;
        }

        .status {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .status img, .status svg{
            height: 150px;
        }

        .status svg path.success {
            fill: var(--success-color);
        }

        .status svg path.error {
            fill: var(--error-color);
        }

        .status svg path.warning {
            fill: var(--error-color);
        }

        .status.error img{
            filter: drop-shadow(0 0 10px var(--error-color));
        }

    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h2 class="d-flex align-items-center justify-content-center text-uppercase">Booking Confirmed</h2>
    </div>
    <div class="email-body">
        <div class="status success">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path class="success" d="M170.718 216.482L141.6 245.6l93.6 93.6 208-208-29.118-29.118L235.2 279.918l-64.482-63.436zM422.4 256c0 91.518-74.883 166.4-166.4 166.4S89.6 347.518 89.6 256 164.482 89.6 256 89.6c15.6 0 31.2 2.082 45.764 6.241L334 63.6C310.082 53.2 284.082 48 256 48 141.6 48 48 141.6 48 256s93.6 208 208 208 208-93.6 208-208h-41.6z"></path></svg>
        </div>
        <h3>Hi {{ $data->user_name }},</h3>
        <h5>Your booking for <strong>{{ $data->package_name }}</strong> on <strong>{{ \Illuminate\Support\Carbon::parse($data->date)->format('d M y') }}</strong> has been successfully confirmed.</h5>

        <div class="details">
            <p><strong>Booking ID:</strong> {{ $data->booking_id }}</p>
            <p><strong>Customer:</strong> {{ $data->customer }}</p>
            <p><strong>Amount Paid:</strong> {{ $data->amount_paid }}</p>
        </div>

{{--        <a href="{{ $data->view_booking_on }}" class="btn"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"></path></svg> View Booking</a>--}}
    </div>
    <div class="email-footer">
        <div class="logo">
            <img src="{{ rtrim(config('app.url'), '/').'/assets/application/images/harmonylogo.png' }}" alt="">
        </div>
        © {{ \Illuminate\Support\Carbon::now()->year }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>
</body>
</html>
