<?php

$str = "Фантик Петя";

$set = array(
	array("icon-32", 32, 32, ''),
	array("icon-64", 64, 64),
	array("icon-96", 96, 96),
	array("icon-128", 128, 128),
	array("apple-touch-icon-80x80_in_root_dir", 80, 80), // это в корень приложения
	array("touch-icon-ipad", 152, 152),
	array("touch-icon-ipad-retina", 167, 167),
	array("touch-icon-iphone", 120, 120),
	array("touch-icon-iphone-retina", 180, 180),
	array("icon-168", 168, 168),
	array("icon-192", 192, 192),
	array("icon-256", 256, 256),
	array("icon-512", 512, 512),
	array("icon-1024", 1024, 1024),
	array("apple-launch-828x1792", 828, 1792)
);

$LX = -5; $LY = -5;
$RX = 5; $RY = 5;

// image from file that will be inserted in canvas
$filename = __DIR__ . '/pwa.png';

$info   = getimagesize($filename);
$width  = $info[0];
$height = $info[1];
$type   = $info[2];

switch ($type) { 
	case 1: 
		$img = imageCreateFromGif($filename);
		imageSaveAlpha($img, true);
		break;					
	case 2: 
		$img = imageCreateFromJpeg($filename);
		break;
	case 3: 
		$img = imageCreateFromPng($filename); 
		imageSaveAlpha($img, true);
		break;
}
// ***********************************************


foreach ($set as $val) {
	global $str;
	$nm = $val[0]; $W = $val[1]; $H = $val[2]; $thickness = 2;
	if( isset( $val[3] ) ) {
		$txt = $val[3];
	}
	else $txt = $str;
	//echo "$nm | $W x $H\n";
	buildIcon($nm, $txt);
}

function buildIcon($nm, $str) {
	global $W, $H, $thickness;
	$im = @imagecreatetruecolor($W, $H) or die('Невозможно инициализировать GD поток');
	$red = imagecolorallocate($im, 255, 0, 0);
	$realwhite = imagecolorallocate($im, 255, 255, 255);
	$white = imagecolorallocate($im, 0x87, 0xce, 0xfa);#87CEFA
	$black = imagecolorallocate($im, 0x19, 0x19, 0x70);//imagecolorallocate($im, 0, 0, 0);
	$gray = imagecolorallocate($im, 0x80, 0x80, 0x80);
	$blue = imagecolorallocate($im, 0x41, 0x69, 0xe1);

	imagesetthickness($im, $thickness);

	imagefill($im, 0, 0, $realwhite);
	//imagerectangle($im, 0, 0, $W-1, $H-1, $red);

	addImg($im);

	$size = -pY(8/10); $angle = 10; $x = X(-4); $y = Y(-4); $fontfile = __DIR__ . '/arial.ttf';
	if($H > $W) { $size = -pY(5/10); $angle = 30; }
	//echo "$size, $angle, $x, $y, $fontfile, $str";
	imagettftext ($im , $size, $angle, $x, $y, $black, $fontfile, $str);

	imagepng($im, "$nm.png"); imagedestroy($im);
	echo "$nm\n";
}

function X($x) {
	global $LX, $RX, $W;
	$x+=0.3;
	return (int) (($x - $LX) * $W / ($RX - $LX));
}

function Y($y) {
	global $LY, $RY, $H;

	return (int) (($RY - $y) * $H / ($RY - $LY));
}

function pX($d) { return X($d) - X(0.); }
function pY($d) { return Y($d) - Y(0.); }

function addImg($im) {
	global $W, $H,
	       $img, $width, $height, $type;

	//if(empty($w)) $w = ceil($h / ($height / $width));
	//if(empty($h)) $h = ceil($w / ($width / $height));

	$off = $W > $H ? intval($H/20) : intval($W/20);
	//echo "$off\n";
	$w = $W - 2*$off;

	$h = ceil($w / ($width / $height));

	/*$tmp = imageCreateTrueColor($w, $h);

	if($type == 1 || $type == 3) {
		imagealphablending($tmp, true); 
		imageSaveAlpha($tmp, true);
		$transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127); 
		imagefill($tmp, 0, 0, $transparent); 
		imagecolortransparent($tmp, $transparent);    
	}*/

	$tw = ceil($h / ($height / $width)); $th = ceil($w / ($width / $height));
	if($tw < $w) { echo ceil(($w - $tw) / 2), ' | ', $off, ' | '; imageCopyResampled($im, $img, ceil(($w - $tw) / 2), $off,                       0, 0, $tw, $h,  $width, $height);}
	else         { echo $off, ' | ', ceil(($h - $th) / 2), ' | '; imageCopyResampled($im, $img, $off,                    ceil(($h - $th) / 2)+2, 0, 0, $w,  $th, $width, $height); }

	imagedestroy($im);
}


//imagecopyresampled(GdImage $dst_image, GdImage $src_image, int $dst_x, int $dst_y, int $src_x, int $src_y, int $dst_width, int $dst_height, int $src_width, int $src_height)


?>