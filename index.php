<?php

	include_once('protected/eab/eab.core.php');
	include_once('protected/eab/eab.database.php');
	
	
	
	include_once('protected/eab/ClassLoading/class.EabClassLoader.php');
	
	try {
		
		$loader = new EabClassLoader();
		
		$path = dirname(__FILE__).'protected/eab/';
		$loader->registerAutoloadPath($path);
		
		


	function f($class)
	{
		echo 'Load f : '.$class.'<br />';
	}
	function f1($class)
	{
		echo 'Load f1 : '.$class.'<br />';
	}
	
	spl_autoload_register('f');
	spl_autoload_register('f1');
	$s = new UnknownClass();

	
		$app=new Eab();
		$app->run();
		
		// test from office
		
		/*
		$db=EabDb::GetInstance();
		$db->applySettings(
			array(
				'host'=>'127.0.0.1',
				'username'=>'root',
				'password'=>'',
				'database'=>'schoolrate'
		));
		
		Eab::debug($db->fetchOne("select from schools"));
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