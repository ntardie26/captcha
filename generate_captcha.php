<?php
session_start();

function generateRandomChars() {
    $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
    $length = rand(4, 5);
    $result = '';
    for($i = 0; $i < $length; $i++) {
        $result .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $result;
}

function generateMathCaptcha() {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $operations = ['+', '-', '*'];
    $operation = $operations[array_rand($operations)];
    
    switch($operation) {
        case '+':
            $answer = $num1 + $num2;
            break;
        case '-':
            if($num1 < $num2) {
                $temp = $num1;
                $num1 = $num2;
                $num2 = $temp;
            }
            $answer = $num1 - $num2;
            break;
        case '*':
            $num1 = rand(1, 5);
            $num2 = rand(1, 5);
            $answer = $num1 * $num2;
            break;
    }
    
    return [
        'question' => "$num1 $operation $num2 = ?",
        'answer' => $answer,
        'type' => 'math'
    ];
}

function generateTextCaptcha() {
    $text = generateRandomChars();
    return [
        'question' => $text,
        'answer' => $text,
        'type' => 'text'
    ];
}

if(rand(1, 100) <= 70) {
    $captcha = generateMathCaptcha();
} else {
    $captcha = generateTextCaptcha();
}

$_SESSION['captcha_answer'] = $captcha['answer'];
$_SESSION['captcha_time'] = time();
$_SESSION['captcha_type'] = $captcha['type'];

$width = 200;
$height = 60;
$image = imagecreate($width, $height);

$style = rand(1, 5);

switch($style) {
    case 1:
        $bg_color = imagecolorallocate($image, rand(0, 30), rand(0, 30), rand(0, 30));
        $text_color = imagecolorallocate($image, rand(220, 255), rand(220, 255), rand(220, 255));
        $shadow_color = imagecolorallocate($image, 255, 255, 255);
        break;
    case 2:
        $bg_r = rand(50, 150);
        $bg_g = rand(50, 150);
        $bg_b = rand(100, 200);
        $bg_color = imagecolorallocate($image, $bg_r, $bg_g, $bg_b);
        $text_color = imagecolorallocate($image, 255 - $bg_r, 255 - $bg_g, 255 - $bg_b);
        $shadow_color = imagecolorallocate($image, 0, 0, 0);
        break;
    case 3:
        $bg_color = imagecolorallocate($image, rand(180, 240), rand(180, 240), rand(180, 240));
        $text_color = imagecolorallocate($image, rand(0, 50), rand(0, 50), rand(0, 50));
        $shadow_color = imagecolorallocate($image, 255, 255, 255);
        break;
    case 4:
        $bg_color = imagecolorallocate($image, rand(80, 120), rand(80, 120), rand(80, 120));
        $text_color = imagecolorallocate($image, rand(220, 255), rand(220, 255), rand(220, 255));
        $shadow_color = imagecolorallocate($image, 0, 0, 0);
        break;
    default:
        $bg_color = imagecolorallocate($image, rand(0, 40), rand(0, 40), rand(0, 40));
        $text_color = imagecolorallocate($image, rand(230, 255), rand(230, 255), rand(230, 255));
        $shadow_color = imagecolorallocate($image, 255, 255, 255);
        break;
}

$effect_type = rand(1, 4);

if($effect_type == 1) {
    for($y = 0; $y < $height; $y += rand(2, 5)) {
        $line_color = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
        imageline($image, 0, $y, $width, $y, $line_color);
    }
} elseif($effect_type == 2) {
    for($i = 0; $i < 200; $i++) {
        $r = rand(50, 200);
        $g = rand(50, 200);
        $b = rand(50, 200);
        $pixel_color = imagecolorallocate($image, $r, $g, $b);
        imagesetpixel($image, rand(0, $width), rand(0, $height), $pixel_color);
    }
} elseif($effect_type == 3) {
    for($i = 0; $i < 10; $i++) {
        $line_color = imagecolorallocate($image, rand(80, 180), rand(80, 180), rand(80, 180));
        imageline($image, rand(0, $width), 0, rand(0, $width), $height, $line_color);
    }
} else {
    for($i = 0; $i < 6; $i++) {
        $x1 = rand(0, $width - 20);
        $y1 = rand(0, $height - 10);
        $x2 = $x1 + rand(5, 20);
        $y2 = $y1 + rand(3, 12);
        $r = rand(30, 180);
        $g = rand(30, 180);
        $b = rand(30, 180);
        $rect_color = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, $x1, $y1, $x2, $y2, $rect_color);
    }
}

for($i = 0; $i < rand(3, 8); $i++) {
    $r = rand(80, 180);
    $g = rand(80, 180);
    $b = rand(80, 180);
    $line_color = imagecolorallocate($image, $r, $g, $b);
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
}

for($i = 0; $i < rand(100, 300); $i++) {
    $grain_intensity = rand(0, 255);
    $grain_color = imagecolorallocate($image, $grain_intensity, $grain_intensity, $grain_intensity);
    imagesetpixel($image, rand(0, $width), rand(0, $height), $grain_color);
}

$data_fonts = glob('data/*.{ttf,otf,TTF,OTF}', GLOB_BRACE);
$font_fonts = glob('font/*.{ttf,otf,TTF,OTF}', GLOB_BRACE);
$font_paths = array_merge($data_fonts ?: [], $font_fonts ?: []);
$selected_font = null;
$available_fonts = [];

foreach($font_paths as $font_path) {
    if(file_exists($font_path)) {
        $available_fonts[] = $font_path;
    }
}

if(!empty($available_fonts)) {
    $selected_font = $available_fonts[array_rand($available_fonts)];
}

$text = $captcha['question'];

if($captcha['type'] === 'math' || !$selected_font || !function_exists('imagettftext')) {
    $font_size = 5;
    $text_width = strlen($text) * imagefontwidth($font_size);
    $text_height = imagefontheight($font_size);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    $x += rand(-5, 5);
    $y += rand(-3, 3);
    imagestring($image, $font_size, $x+1, $y+1, $text, $shadow_color);
    imagestring($image, $font_size, $x, $y, $text, $text_color);
} else {
    $font_size = rand(14, 22);
    $angle = rand(-15, 15);
    $bbox = imagettfbbox($font_size, 0, $selected_font, $text);
    $text_width = abs($bbox[4] - $bbox[0]);
    $text_height = abs($bbox[1] - $bbox[7]);
    $x = ($width - $text_width) / 2;
    $y = ($height + $text_height) / 2;
    $x += rand(-15, 15);
    $y += rand(-10, 10);
    
    for($sx = -1; $sx <= 1; $sx++) {
        for($sy = -1; $sy <= 1; $sy++) {
            if($sx != 0 || $sy != 0) {
                imagettftext($image, $font_size, $angle, $x+$sx, $y+$sy, $shadow_color, $selected_font, $text);
            }
        }
    }
    imagettftext($image, $font_size, $angle, $x, $y, $text_color, $selected_font, $text);
}

for($i = 0; $i < 3; $i++) {
    $x_pos = rand(0, $width);
    $distort_color = imagecolorallocate($image, rand(50, 150), rand(50, 150), rand(50, 150));
    imageline($image, $x_pos, 0, $x_pos, $height, $distort_color);
}

$num_icons = rand(2, 5);
for($i = 0; $i < $num_icons; $i++) {
    $icon_type = rand(1, 8);
    $icon_x = rand(5, $width - 20);
    $icon_y = rand(5, $height - 15);
    $icon_size = rand(3, 8);
    $alpha = rand(30, 120);
    $icon_r = rand(50, 200);
    $icon_g = rand(50, 200);
    $icon_b = rand(50, 200);
    
    if (function_exists('imagecolorallocatealpha')) {
        $icon_color = imagecolorallocatealpha($image, $icon_r, $icon_g, $icon_b, $alpha);
    } else {
        $icon_color = imagecolorallocate($image, $icon_r, $icon_g, $icon_b);
    }
    
    switch($icon_type) {
        case 1:
            imagefilledellipse($image, $icon_x, $icon_y, $icon_size, $icon_size, $icon_color);
            break;
        case 2:
            imagefilledrectangle($image, $icon_x, $icon_y, $icon_x + $icon_size, $icon_y + $icon_size, $icon_color);
            break;
        case 3:
            $points = array(
                $icon_x, $icon_y + $icon_size,
                $icon_x + $icon_size, $icon_y + $icon_size,
                $icon_x + ($icon_size/2), $icon_y
            );
            imagefilledpolygon($image, $points, 3, $icon_color);
            break;
        case 4:
            $points = array(
                $icon_x + ($icon_size/2), $icon_y,
                $icon_x + $icon_size, $icon_y + ($icon_size/2),
                $icon_x + ($icon_size/2), $icon_y + $icon_size,
                $icon_x, $icon_y + ($icon_size/2)
            );
            imagefilledpolygon($image, $points, 4, $icon_color);
            break;
        case 5:
            for($j = 0; $j < 5; $j++) {
                $angle = ($j * 72) * (M_PI / 180);
                $star_x = $icon_x + cos($angle) * $icon_size;
                $star_y = $icon_y + sin($angle) * $icon_size;
                imagefilledellipse($image, $star_x, $star_y, 2, 2, $icon_color);
            }
            break;
        case 6:
            imageline($image, $icon_x, $icon_y + ($icon_size/2), $icon_x + $icon_size, $icon_y + ($icon_size/2), $icon_color);
            imageline($image, $icon_x + ($icon_size/2), $icon_y, $icon_x + ($icon_size/2), $icon_y + $icon_size, $icon_color);
            break;
        case 7:
            imageline($image, $icon_x, $icon_y, $icon_x + $icon_size, $icon_y + $icon_size, $icon_color);
            imageline($image, $icon_x + $icon_size, $icon_y, $icon_x, $icon_y + $icon_size, $icon_color);
            break;
        case 8:
            $hex_points = array(
                $icon_x + ($icon_size * 0.25), $icon_y,
                $icon_x + ($icon_size * 0.75), $icon_y,
                $icon_x + $icon_size, $icon_y + ($icon_size * 0.5),
                $icon_x + ($icon_size * 0.75), $icon_y + $icon_size,
                $icon_x + ($icon_size * 0.25), $icon_y + $icon_size,
                $icon_x, $icon_y + ($icon_size * 0.5)
            );
            imagefilledpolygon($image, $hex_points, 6, $icon_color);
            break;
    }
}

$confusing_chars = ['◆', '▲', '●', '■', '★', '◇', '△', '○', '□', '☆', '◈', '▼', '◉', '◯', '▪', '▫', '∙', '∘'];
$num_symbols = rand(1, 3);

for($i = 0; $i < $num_symbols; $i++) {
    if(rand(1, 3) == 1) {
        $symbol = $confusing_chars[array_rand($confusing_chars)];
        $sym_x = rand(10, $width - 20);
        $sym_y = rand(15, $height - 10);
        $sym_size = rand(1, 3);
        $sym_color = imagecolorallocate($image, rand(80, 180), rand(80, 180), rand(80, 180));
        imagestring($image, $sym_size, $sym_x, $sym_y, $symbol, $sym_color);
    }
}

for($i = 0; $i < 20; $i++) {
    $dot_color = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
    imagefilledellipse($image, rand(0, $width), rand(0, $height), rand(1, 3), rand(1, 3), $dot_color);
}

if(function_exists('imagefilter')) {
    imagefilter($image, IMG_FILTER_CONTRAST, -10);
}

header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

imagepng($image);
imagedestroy($image);
?>
