<?php

	if (!isset($_SESSION)) session_start();
	
	unset($_SESSION['_CAPCHA_CODE']);

	function generate_captcha_code($code_length) {
		$chars = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		for($i=0; $i<$code_length; $i++) 
			$code .= substr($chars, mt_rand(0, strlen($chars)-1), 1);

		return $code;
	}

	function generate_and_output_captcha($width='120',$height='40',$code_length='6') {
		
		$font_path = dirname(__FILE__) . '/fonts/';
		$fonts = array(
			$font_path.'monofont.ttf', 
			//$font_path.'times_new_yorker.ttf'
		);
		$font = $fonts[rand(0,count($fonts)-1)];
		
		$code = generate_captcha_code($code_length);
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['_CAPTCHA_CODE'] = $code;
	}

	$width = isset($_GET['w']) ? $_GET['w'] : '120';
	$height = isset($_GET['h']) ? $_GET['h'] : '40';
	$length = isset($_GET['len']) && $_GET['len'] > 2 ? $_GET['len'] : '6';
	
	generate_and_output_captcha($width, $height, $length);
?>