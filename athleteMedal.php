<?php

require("include/include.php");

$athletes = getAthleteData();
foreach ($athletes as &$athlete) {
	if ($athlete["name"] == $_GET["name"]) {
		if (!empty($athlete[$_GET["type"]])) {
			$num = $athlete[$_GET["type"]];
		} else {
			$num = 0;
		}
	}
}

$im = ImageCreateFromPNG("img/" . $_GET['type'] . ".png");	

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
		$x = 110;
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