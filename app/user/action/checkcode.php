<?php
defined('IN_TS') or die('Access Denied.');

// Set the content-type
header('Content-Type: image/png');

$randCode = '';
$chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ23456789';

for ( $i = 0; $i < 4; $i++ ){
	$randCode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
}

$_SESSION['authcode'] = strtoupper($randCode);

$text = $randCode;

// Create the image
$im = imagecreatetruecolor(100, 30);

$text_color = imagecolorallocate($im, mt_rand(30, 180), mt_rand(10, 100), mt_rand(40, 250));

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
imagefilledrectangle($im, 0, 0, 399, 29, $white);

// Replace path by your own font path
$font = 'public/font/t2.ttf';

// Add some shadow to the text
imagettftext($im, 20, 0, 11, 21, $text_color, $font, $text);

imagepng($im);
imagedestroy($im);