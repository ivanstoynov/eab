<?php

	include_once("db.funcs.php");
	

	/**
	 * Намира id-тата на текущите кампании за номинации на ученици.
	 */
	function get_student_nominationcamps($filter=array(), $order="", $limit="", $count_only=false)
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

		$sql = $select_expr." FROM `student_nominations_campains` WHERE ".$where_expr." ".$order_expr." ".$limit_expr;
		return db_fetch_all($sql);
	}	
	
	/**
	 * Намира id-то на текущата кампаниия за номинации на ученици.
	 */
	function get_current_student_nominationcamps_id($school_id)
	{
		$sql = " SELECT `id` FROM `student_nominations_campains` 
		          WHERE `start_date` <= NOW() AND `end_date` >= NOW() AND active=1 AND `school_id` = ".(int)$school_id."
		          LIMIT 1 ";
		
		$result = db_query($sql);
		if( mysql_num_rows($result) == 0 ) return false;
		
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		return $row['id'];
	}
	
	/**
	 * Намира id-то на текущата кампаниия за номинации на ученици.
	 */
	function get_last_student_nominationcamps_id($school_id)
	{
		$sql = " SELECT `id` FROM `student_nominations_campains` 
		          WHERE `end_date` <= NOW() AND active=0 AND `school_id` = ".(int)$school_id." 
		          ORDER BY `end_date` DESC
		          LIMIT 1 ";
		
		$result = db_query($sql);
		if( mysql_num_rows($result) == 0 ) return false;
		
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		return $row['id'];
	}
	

	/**
	 * Намира номинациите на ученици 
	 */
	function get_camp_student_nominations($filter=array(), $order="", $limit="", $count_only=false)
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
			$select_expr = " SELECT  
					`usr`.`fname`,
					`usr`.`sname`,
					`usr`.`lname`,
					`usr`.`avatar`,
					`sinf`.`class`,
					`sinf`.`letter`,
					`nom`.`nomination_title`,
					`nom`.`votes`,
					`nom_cat`.`category_name`,
					`nom_cam`.`campain_title`
					";
		}
		
		// Взема данните за номинирните
		$sql = $select_expr."
	          FROM `student_nominations` as `nom`
	          JOIN `student_nominations_campains` as `nom_cam`
	          	ON `nom`.`nomination_campain_id` = `nom_cam`.`id`
	          JOIN `student_nomination_categories` as `nom_cat` 
	          	ON `nom`.`nomination_category_id` = `nom_cat`.`id`
	          JOIN `users` as `usr` 
	          	ON `nom`.`user_id` = `usr`.`id`
	          JOIN `student_infos` as `sinf` 
	          	ON `nom`.`user_id` = `sinf`.`user_id` 
	         WHERE ".$where_expr." ".
		             $order_expr." ".
		             $limit_exprt;

		return db_fetch_all($sql);
	}
	
	
	function get_student_nominationscamp_winners($filter=array(), $order="", $limit="", $count_only=false)
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
			$select_expr = " SELECT  
					`usr`.`fname`,
					`usr`.`sname`,
					`usr`.`lname`,
					`usr`.`avatar`,
					`sinf`.`class`,
					`sinf`.`letter`,
					`nom`.`nomination_title`,
					`nom`.`votes`,
					`nom_cat`.`category_name`,
					`nom_cam`.`campain_title`
					";
		}
		
		$sql = $select_expr."
	          FROM `student_nominations` as `nom`
	          JOIN `student_nominations_campains` as `nom_cam`
	          	ON `nom`.`id` = `nom_cam`.`win_nomination_id`
	          JOIN `student_nomination_categories` as `nom_cat` 
	          	ON `nom`.`nomination_category_id` = `nom_cat`.`id`
	          JOIN `users` as `usr` 
	          	ON `nom`.`user_id` = `usr`.`id`
	          JOIN `student_infos` as `sinf` 
	          	ON `nom`.`user_id` = `sinf`.`user_id` 
	         WHERE ".$where_expr." ".
		            $order_expr." ".
		            $limit_expr;
		
		return db_fetch_all($sql);
	}
?>