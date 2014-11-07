<?php

	define('SITE_MODE', 'DEBUG', 1 );

	if(!defined('DS')){
		define('DS', '/', 1); // Directory separator - разделител на директории
	}
	if(!defined('PS')){ 
		define('PS', '/', 1); // Path separator - разделител ползващ се в url-то
	}

	define('DB_EXCEPTION', 201, 1 );
	define('FILE_EXCEPTION', 301, 1 );
	define('OTHER_EXCEPTION', 501, 1 );
	
	// Константи за базови пътища
	define('ROOT_DIR', dirname(__FILE__).DS, 1 );
	define('PAGES_DIR', ROOT_DIR.'protected'.DS.'pages'.DS, 1);
	define('AJAX_DIR', ROOT_DIR.'protected'.DS.'ajax'.DS, 1);
	define('TPLS_DIR', ROOT_DIR.'protected'.DS.'templates'.DS, 1);
	define('LIBRARY_DIR', ROOT_DIR.'protected'.DS.'library'.DS, 1);
	define('CONFIGS_DIR', ROOT_DIR.'protected'.DS.'configs'.DS, 1);

	// Константи за пътя до js,css и images
	define('SCRIPTS_PATH', 'public'.PS.'js'.PS, 1);
	define('STYLES_PATH', 'public'.PS.'style'.PS, 1);
	define('IMAGES_PATH', 'public'.PS.'images'.PS, 1);

	// Конфигурационни константи за БД
	define('DB_SERVER', 'localhost', 1);
	define('DB_USER', 'root', 1);
	define('DB_PASSWORD', 'root', 1);
	define('DB_WORK_DATABASE', 'schoolrate', 1);

?>