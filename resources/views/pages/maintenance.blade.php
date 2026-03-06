<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance | GloVans</title>
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') }}">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
            background: radial-gradient(circle at top right, #202d42, #0f1725 55%);
            color: #e8eef9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .maintenance-card {
            width: min(680px, 100%);
            border-radius: 18px;
            padding: 32px 28px;
            background: rgba(17, 25, 38, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.32);
            text-align: center;
        }

        .maintenance-logo {
            height: 34px;
            width: auto;
            margin-bottom: 16px;
        }

        .maintenance-title {
            margin: 0 0 10px;
            font-size: clamp(1.45rem, 3vw, 2rem);
            font-weight: 700;
            color: #fff;
        }

        .maintenance-copy {
            margin: 0;
            font-size: 1rem;
            line-height: 1.7;
            color: #c8d3e7;
        }
    </style>
</head>
<body>
<div class="maintenance-card">
    <img src="{{ asset('admin_assets/images/glovans-logo.svg') }}" alt="GloVans logo" class="maintenance-logo">
    <h1 class="maintenance-title">We are undergoing maintenance</h1>
    <p class="maintenance-copy">{{ $message ?? "Please check back shortly." }}</p>
</div>
</body>
</html>
