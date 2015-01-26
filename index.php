<?php

	//include_once('protected/eab/eab.core.php');
	//include_once('protected/eab/eab.database.php');
	
	include_once('protected/eab/ClassLoading/class.EabAutoLoader.php');
	
	try {
		
		$loader = EabAutoLoader::GetInstance();
		$loader->registerAutoloadPath(dirname(__FILE__).'/protected/eab/');
		
		Eab::app()->run();
/*		
		$db = new EabDbAdapter(array(
			'username' => 'root',
			'password' => '',
			'host' => 'localhost',
			'database' => 'books_library',
			'charset' => 'UTF8',
		));
		
		$data = $db->exec("UPDATE books SET price = 25 WHERE id = 1");
		Eab::debug($data);
*/		
			

/*	
		if( 'DEBUG' == SITE_MODE ){
			error_reporting(E_ERROR | E_PARSE);
		}
		else {
			error_reporting(E_ALL);
		}
*/
	}
	catch (Exception $e){
		Eab::debug("Exception:</br>");
		Eab::debug($e->getMessage());
		Eab::debug($e->getTraceAsString());
	}
	
	// Ако сме в DEBUG моде
	if( 'DEBUG' == SITE_MODE ){
		//display_sql_debug_data();
	}
?>