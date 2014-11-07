<?php

	include_once(dirname(__FILE__).DS.'..'.DS.'base.funcs.php');

	 /**
	  * Създава връзката с базата данни, като се правят проверки за 
	  * ъзникнали грешки при осъществяване на някоя от връзките или 
	  * самата конекция към сървъра за БД
	  */
	 function db_coonect($host, $user, $pass, $database, $charset='utf8')
	 {
		// Прави връзка със съравара за БД
		$link = mysql_connect($host, $user, $pass);
		if( !$link ){
			throw new Exception( "Error mysql_connect(): ". mysql_error() );
		}

		// Задава работнатa база данни
		if( !mysql_select_db($database) ){
			throw new Exception( "Error mysql_select_db(): ". mysql_error() );
		}

		// Задава енкодинга на връзката със сървара за БД
		if( function_exists('mysql_set_charset') ){
			mysql_set_charset($charset);
		}
		else if( !mysql_query("SET NAMES '".$charset."'") ){
			throw new Exception( "Error mysql_query(): ". mysql_error() );
		}
	 }
	 
	 /**
	  * Изпълнява заявка към базата данни, като прихваща грешка
	  * при наличие на такава.
	  * Също така добавя всяка заявка към глобалния масив.
	  */
	 function db_query($sql)
	 {
		$startTime = microtime();
		
		$res = mysql_query($sql);
		if( !$res ){
			//if( defined('SITE_MODE') &&  'DEBUG' == SITE_MODE ){
				//$baacktrce = nl2br(print_r(debug_backtrace(),true));
			//}
			throw new Exception( "<b>Error mysql_query():</b> ". mysql_error()."<br/><b>Sql:</b> ".$sql );
		}

		// При DEBUG_MODE dобавя заявката към глобалния масив
		if( defined('SITE_MODE') &&  'DEBUG' == SITE_MODE ){
			$GLOBALS['SQL_QUERIES'][] = array(
				'sql' => $sql,
				'execution_time' => microtime_difference(microtime(), $startTime)
			);
		}
		
		return $res;
	 }
	 
	 /**
	  * Връща масив с редове
	  */
	 function db_fetch_all($sql)
	 {
	 	$rs = array();
	 	$r = db_query($sql);
	 	while ($row = mysql_fetch_assoc($r)){
	 		$rs[]=$row;
	 	}
	 	mysql_free_result($r);
	 	return $rs;
	 }

	/**
	 * Почиства низ (масив от низове) като добавя
	 * налконени черти с цел да не се получават sql инжекции.
	 */
	function db_escape($val, $quotes=false)
	{
		// Акое е масив, то той се escape като се обиколи рекурсивно
        if(is_array($val)) {
            foreach ($val as $k=>$v) {
                $value[$k] = db_escape($v,$quotes);
            }
            return $val;
        }
        else {
            
            if (is_bool($val)) {
                return true==$val ? 1 : 0;
            }
            elseif(is_string($val)) {
	            if ( get_magic_quotes_gpc() ) {
	                $val = stripslashes($val);
	            }
                $val = mysql_real_escape_string($val);
                if( true == $quotes){ $val = "'".$val."'"; }
                return $val;
            }
            elseif( is_numeric($val) ) {
                return $val;
            }
            else {
            	return '';
            }
        }
	}
	 
	 /**
	  * Извежда дебъг със sql заявките които са се изпълнили
	  */
	 function display_sql_debug_data()
	 {
		if( !empty($GLOBALS['SQL_QUERIES']) ){
		
			echo '<div class="debug_cont">';
			$i = 1;
			foreach($GLOBALS['SQL_QUERIES'] as $query_data){
				$style = $i++%2 == 0 ? 'background-color: #FDFFE3' : 'background-color: #E6F8FF';
				echo '<div style="padding:5px;'.$style.'">';
				echo '  <b>Sql: </b>'.$query_data['sql'].'<br />';
				echo '  <b>Execution time: </b>'.$query_data['execution_time'].' sec.';
				echo '</div>';
			}
			echo '</div>';
		}
	 }
 
	 function create_simple_sql_filter( $filter='' )
	 {
	 	if( empty($filter) ) return '1';
	 	if( is_string($filter) ) return $filter;
	 	
	 	$rows=array();
	 	foreach ($filter as $data){
	 		$rows[] = $data;
	 	}
	 	
	 	return implode(' AND ', $rows);
	 }
	 
	 function create_sql_set_expr($data)
	 {
	 	$expr='';
	 	foreach ($data as $k=>$v){
	 		$expr.="`".$k."`='".db_escape($v)."',";
	 	}
	 	$expr = substr($expr,0,-1);
	 	return $expr;
	 }

?>