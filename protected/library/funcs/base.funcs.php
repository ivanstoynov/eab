<?php
	/**
	 * Този файл се ползва за да се реализират някой функции които 
	 * ще се ползват в сайта.
	 */
	 
	 
	 /**
	  * Показва данните пеформатирани. Ползва се главно
	  * за дебъгване.
	  */
	 function show($data)
	 {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	 }
	 
	 /**
	  * Тази функция пренасочва браузъра към даденото url
	  */
	 function redirect($url, $code=302)
	 {
		 header( 'Location: '.$url, true, $code);
		 exit();
	 }
	 
	 /**
	  * Намира разликата в секунди от две времена зададени чрез microtime().
	  * 
	  * @param microtime $endTime
	  * @param microtime $startTime
	  * @param microtime $roundPlaces
	  * @return double
	  */
	 function microtime_difference($endTime, $startTime, $roundPlaces=5)
	 {
        list($usec1, $sec1) = explode(' ', $startTime);
        list($usec2, $sec2) = explode(' ', $endTime);
        $diff = round($sec2-$sec1+$usec2-$usec1, $roundPlaces);
		return $diff;
	 }
	 
	 function create_sql_date($day,$month,$year)
	 {
	 	$date = $year.'-';
	 	$date.= (($month<10) ? '0'.$month : $month).'-';
	 	$date.= ($day<10) ? '0'.$day : $day;
	 	return $date;
	 }
	 
	 function is_valid_email($email)
	 {
	 	if( function_exists('filter_var') ){
	 		return filter_var($email, FILTER_VALIDATE_EMAIL);
	 	}
	 	
		$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
		return preg_match($pattern, $email) === 1;
	 }


?>