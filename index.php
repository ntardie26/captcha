<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: monospace; 
            text-align: center; 
            background: #f0f0f0; 
            padding: 50px; 
        }
        img { border: 1px solid #000; margin: 20px; }
        input { font-family: monospace; margin: 5px; }
    </style>
</head>
<body>
    <h2>CAPTCHA VERIFICATION</h2>
    <form method="POST" action="validate_captcha.php">
        <img src="generate_captcha.php?t=<?php echo time(); ?>" alt="Captcha challenge">
        <br>
        <input type="text" name="captcha_input" required>
        <input type="submit" value="Submit">
        <br><br>
        <a href="index.php">New CAPTCHA</a>
    </form>
</body>
</html>
