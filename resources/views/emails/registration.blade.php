<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
        <h2 class="d-flex align-items-center justify-content-center text-uppercase">Registration</h2>
    </div>
    <div class="email-body">
        <div class="status success">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512"  xmlns="http://www.w3.org/2000/svg"><path d="M313.6 304c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-25.6c0-74.2-60.2-134.4-134.4-134.4zM400 464H48v-25.6c0-47.6 38.8-86.4 86.4-86.4 14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 47.6 0 86.4 38.8 86.4 86.4V464zM224 288c79.5 0 144-64.5 144-144S303.5 0 224 0 80 64.5 80 144s64.5 144 144 144zm0-240c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96z"></path></svg>
        </div>
        <h3>Hi {{ $data->user_name }},</h3>
        <h5>Thank you for registering as a {{ $data->role }} with {{ $data->agency_name }}.</h5>

        <h6 class="m-0 mt-2">You can now access your dashboard using your registered. <br/> Email: {{ $data->email }}</h6>
        @if($data->role == "Customer")
            <h6 class="m-0 mt-2">Your password is: {{ $data->password }}</h6>
            <h6 class="m-0 mt-2 text-danger">Please change your password after logging in.</h6>
        @endif

        <div class="my-5">
            <a href="{{ $data->login_on }}" class="btn"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"></path></svg> Login Now</a>
            <h6 class="m-0 mt-2 text-danger">If you didn’t create this account, please contact us immediately.</h6>
        </div>
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

