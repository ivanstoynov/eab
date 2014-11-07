<?php

	include_once("db.funcs.php");

	function get_cities($filter=array(), $order="", $limit="", $count_only=false)
	{
		$order_expr = '';
		$limit_expr = '';
		$where_expr = create_simple_sql_filter($filter);
		if( $count_only ){
			$select_expr = " SELECT count(*) as `cnt` ";
		}
		else {
			if( !empty($limit) )  $limit_expr = ' LIMIT '.$limit;
			if( !empty($order) )  $order_expr = ' ORDER BY '.$order;
			$select_expr = " SELECT * ";
		}

		$sql = $select_expr." FROM `cities` WHERE ".$where_expr." ".$order_expr." ".$limit_expr;
		return db_fetch_all($sql);
	}
	
	function get_schools($filter=array(), $order="", $limit="", $count_only=false)
	{
		$order_expr = '';
		$limit_expr = '';
		$where_expr = create_simple_sql_filter($filter);
		if( $count_only ){
			$select_expr = " SELECT count(*) as `cnt` ";
		}
		else {
			if( !empty($limit) )  $limit_expr = ' LIMIT '.$limit;
			if( !empty($order) )  $order_expr = ' ORDER BY '.$order;
			$select_expr = " SELECT * ";
		}

		$sql = $select_expr." FROM `schools` WHERE ".$where_expr." ".$order_expr." ".$limit_expr;
		return db_fetch_all($sql);
	}	
	
	

?>