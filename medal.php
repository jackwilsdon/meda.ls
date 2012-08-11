<?php

require("include/include.php");

$medals = getMedalData();

if (!file_exists("img/" . $_GET['type'] . ".png") || $medals[$_GET['c']] == NULL) {
	$num = "-";
} else {
	$num    = $medals[$_GET['c']][$_GET['type']];
	$im = ImageCreateFromPNG("img/" . $_GET['type'] . ".png");	
}
$bg = ImageColorAllocate($im, 255, 255, 255);

$width  = 300;
$height = 300;

$text = $num;

$textcolor = ImageColorAllocate($im, 0, 0, 0);

// Font stuff
$font  = 'arial.ttf';
$width = strlen($text);

// String length management
switch (strlen($text)) {
	case 1:
		$x = 100;
		break;
	case 2:
		$x = 80;
}

imagettftext($im, 150, 0, $x, 220, $textcolor, $font, $text);
$im = resize($im, 30, 30);
imagealphablending($im, false);
imagesavealpha($im, true);
header("Content-type: image/png");
ImagePNG($im);
?>