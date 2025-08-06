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
<style>body{font-family:monospace;text-align:center;padding:50px;}</style>
Correct! <a href='index.php'>Try another</a>
        <?php
    } else {
        ?>
<style>body{font-family:monospace;text-align:center;padding:50px;}</style>
Wrong. <a href='index.php'>Try again</a>
        <?php
    }
} else {
    header('Location: index.php');
}
?>
