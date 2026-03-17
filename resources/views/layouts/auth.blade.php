<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Floraffeine Boutique - Auth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f4f1f7;
            color: #1f2933;
        }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
            padding: 2.5rem 2.25rem;
        }
        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .auth-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        .field {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.35rem;
        }
        input {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            border: none;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            color: #ffffff;
            margin-top: 0.25rem;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #111827;
        }
        .link-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            font-size: 0.8rem;
        }
        .link-row a {
            color: #8b5cf6;
            text-decoration: none;
        }
        .link-row a:hover {
            text-decoration: underline;
        }
        .errors {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 0.75rem;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 0.8rem;
        }
        .status {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 0.75rem;
            background: #ecfdf3;
            color: #166534;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-title">@yield('title')</div>
        @hasSection('subtitle')
            <div class="auth-subtitle">@yield('subtitle')</div>
        @endif

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>

