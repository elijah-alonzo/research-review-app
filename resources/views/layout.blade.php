<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Graduate School') — SPUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-900: #0b3b26;
            --green-800: #0f4a30;
            --green-700: #16643f;
            --green-500: #2aa564;
            --gold-500: #f5c542;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            color: var(--white);
            background: #0a2b1d;
        }

        .page {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            background-image:
                linear-gradient(100deg, rgba(11, 59, 38, 0.98) 0%, rgba(15, 74, 48, 0.92) 50%, rgba(15, 74, 48, 0.35) 80%),
                url("/images/landing/graduate-campus.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .page::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 75% 20%, rgba(255, 255, 255, 0.12), transparent 55%);
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 36px 24px 64px;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .brand img {
            width: 20rem;
            height: auto;
            object-fit: contain;
        }

        .login-button {
            padding: 10px 18px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }

        .login-button:hover {
            border-color: var(--gold-500);
            color: var(--gold-500);
        }

        .hero {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 600px) 1fr;
            align-items: start;
            gap: 48px;
            padding: 64px 0 24px;
        }

        .hero .content {
            justify-self: start;
            text-align: left;
            max-width: 520px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
        }

        .eyebrow::before {
            content: "";
            width: 36px;
            height: 2px;
            background: var(--gold-500);
            display: inline-block;
        }

        h1 {
            font-size: clamp(2.4rem, 3.4vw, 3.8rem);
            line-height: 1.1;
            margin: 16px 0 12px;
        }

        p {
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.82);
            margin: 0 0 24px;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin: 24px 0 18px;
        }

        .step-card {
            background: rgba(7, 46, 27, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 16px;
            min-height: 120px;
        }

        .step-card span {
            display: block;
            font-size: 12px;
            letter-spacing: 0.18em;
            color: rgba(255, 255, 255, 0.65);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .step-card strong {
            display: block;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .step-card p {
            margin: 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 8px 0 20px;
        }

        .primary-button,
        .secondary-button {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .primary-button {
            background: var(--gold-500);
            color: #1f1400;
            border: none;
        }

        .secondary-button {
            background: rgba(255, 255, 255, 0.12);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .status-panel {
            background: rgba(9, 52, 31, 0.75);
            border-radius: 18px;
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: grid;
            gap: 14px;
        }

        .status-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .countdown {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .countdown-card {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 12px;
            text-align: center;
        }

        .countdown-card h3 {
            margin: 0;
            font-size: 22px;
        }

        .countdown-card span {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.75);
        }

        @media (max-width: 960px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .hero .content {
                justify-self: start;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .countdown {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="container">

            <header>
                <div class="brand">
                    <img src="/sys-logo.png" alt="SPUP Logo">
                </div>
            </header>

            @yield('content')

        </div>
    </div>
</body>
</html>