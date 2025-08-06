<!DOCTYPE html>
<html>
<body>
    <form method="POST" action="validate_captcha.php">
        <img src="generate_captcha.php?t=<?php echo time(); ?>" alt="Captcha challenge" id="captcha-image" style="border: 1px solid #666; margin: 10px 0; display: block; margin-left: auto; margin-right: auto;">
        <br>
        <input type="text" name="captcha_input" required>
        <input type="submit" value="Submit">
        <br>
        <a href="index.html">New CAPTCHA</a>
    </form>
</body>
</html>
