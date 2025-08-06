<?php
session_start();

if ($_POST && isset($_POST['captcha_input'])) {
    $user_input = trim($_POST['captcha_input']);
    
    if (!isset($_SESSION['captcha_answer']) || !isset($_SESSION['captcha_time']) || !isset($_SESSION['captcha_type'])) {
        header('Location: index.php');
        exit;
    }
    
    if (time() - $_SESSION['captcha_time'] > 300) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time'], $_SESSION['captcha_type']);
        header('Location: index.php');
        exit;
    }
    
    $correct = $_SESSION['captcha_answer'];
    $type = $_SESSION['captcha_type'];
    
    $valid = ($type === 'math') ? (intval($user_input) === intval($correct)) : (strtolower($user_input) === strtolower($correct));
    
    if ($valid) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time'], $_SESSION['captcha_type']);
        ?>
<!DOCTYPE html>
<html>
<head>
    <title>Verification Complete</title>
    <style>
        body {
            font-family: monospace;
            background-color: #f0f0f0;
            margin: 0;
            padding: 50px 0;
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 2px solid #333333;
            padding: 30px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #006600;
        }
        a {
            color: #333333;
            text-decoration: none;
            font-family: monospace;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✓ Verification Successful</h1>
        <p>Access granted.</p>
        <br>
        <a href="index.php">Return to Verification</a>
    </div>
</body>
</html>
        <?php
    } else {
        ?>
<!DOCTYPE html>
<html>
<head>
    <title>Verification Failed</title>
    <style>
        body {
            font-family: monospace;
            background-color: #f0f0f0;
            margin: 0;
            padding: 50px 0;
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 2px solid #333333;
            padding: 30px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #cc0000;
        }
        a {
            color: #333333;
            text-decoration: none;
            font-family: monospace;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✗ Verification Failed</h1>
        <p>Incorrect code entered.</p>
        <br>
        <a href="index.php">Try Again</a>
    </div>
</body>
</html>
        <?php
    }
} else {
    header('Location: index.php');
}
?>
