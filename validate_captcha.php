<?php
session_start();

if ($_POST && isset($_POST['captcha_input'])) {
    $user_input = trim($_POST['captcha_input']);
    
    if (!isset($_SESSION['captcha_answer']) || !isset($_SESSION['captcha_time']) || !isset($_SESSION['captcha_type'])) {
        echo "Session expired. <a href='index.html'>Try again</a>";
        exit;
    }
    
    if (time() - $_SESSION['captcha_time'] > 300) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time'], $_SESSION['captcha_type']);
        echo "Timeout. <a href='index.html'>Try again</a>";
        exit;
    }
    
    $correct = $_SESSION['captcha_answer'];
    $type = $_SESSION['captcha_type'];
    
    $valid = ($type === 'math') ? (intval($user_input) === intval($correct)) : (strtolower($user_input) === strtolower($correct));
    
    if ($valid) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time'], $_SESSION['captcha_type']);
        echo "Correct! <a href='index.html'>Try another</a>";
    } else {
        echo "Wrong. <a href='index.html'>Try again</a>";
    }
} else {
    header('Location: index.html');
}
?>
