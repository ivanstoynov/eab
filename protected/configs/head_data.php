<?php

	function cfg_get_all_head_data()
	{
		return array(
		
			'page' => array(

				// Начална страница
				'index' => array(
					'title' => 'Оценс успеха',
					'description' => 'Страница за оценка на ученика',
					'keywords' => 'оценка, чуеник, успех, училище, номинация, оценки, ученици, номинации',
					//'styles'  => '',
					//'js' => ''
				),
	
				// Грешка - изключение (Exception)
				'errors.exception' => array(
					'title' => 'Грешка със зареждане на страницата',
					'description' => 'Грешка със зареждане на страницата',
					'styles'  => STYLES_PATH.'errors.css',
				),
	
				// Грешка 404
				'errors.error404' => array(
					'title' => 'Грешка 404',
					'description' => 'Грешка 404. Страница с дадения url адрес не може да бъде намерена',
					'styles'  => STYLES_PATH.'errors.css'
				),
				// Login страница
				'login' => array(
					'title' => 'Влизане в сайта',
					'description' => 'Влизане в сайта',
					'styles'  => STYLES_PATH.'login.css'
				),
				// Login страница
				'register' => array(
					'title' => 'Регистриране на потребител',
					'description' => 'Регистриране на нов потребител',
					'styles'  => STYLES_PATH.'forms.css'
				)
			),
			
			
			'ajax' => array(
			
			)
		);
	}

	function cfg_get_head_data( $key, $type='page')
	{
		$all_data = cfg_get_all_head_data();
		return !empty($all_data[$type][$key]) ? $all_data[$type][$key] : false;
	}
?>
