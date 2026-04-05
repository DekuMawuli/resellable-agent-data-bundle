<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired &mdash; GloVans</title>
    <link rel="shortcut icon" href="{{ asset('admin_assets/images/favicon.ico') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }
        p {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }
        .btn {
            display: inline-block;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: opacity 0.15s;
        }
        .btn:hover { opacity: 0.85; }
        .btn-primary {
            background: #4f46e5;
            color: #fff;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">&#x23F1;</div>
        <h1>Session Expired</h1>
        <p>Your session timed out for security reasons. Please go back and try your action again — your work may still be there.</p>
        <div>
            <a href="javascript:history.back()" class="btn btn-primary">Go Back &amp; Retry</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">Home</a>
        </div>
    </div>
</body>
</html>
