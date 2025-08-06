<!DOCTYPE html>
<html>
<head>
    <title>CAPTCHA Verification</title>
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
        }
        #captcha-image {
            border: 2px solid #333333;
            margin: 20px 0;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        input[type="text"] {
            font-family: monospace;
            font-size: 16px;
            padding: 8px;
            border: 2px solid #333333;
            margin: 10px 0;
            width: 150px;
        }
        input[type="submit"] {
            font-family: monospace;
            font-size: 14px;
            padding: 8px 20px;
            border: 2px solid #333333;
            background-color: #ffffff;
            cursor: pointer;
            text-transform: uppercase;
            margin: 10px;
        }
        input[type="submit"]:hover {
            background-color: #f0f0f0;
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
        <h1>Security Verification</h1>
        <form method="POST" action="validate_captcha.php">
            <img src="generate_captcha.php?t=<?php echo time(); ?>" alt="Captcha challenge" id="captcha-image">
            <br>
            <input type="text" name="captcha_input" required placeholder="Enter code">
            <br>
            <input type="submit" value="Verify">
            <br><br>
            <a href="index.php">Generate New Code</a>
        </form>
    </div>
</body>
</html>
