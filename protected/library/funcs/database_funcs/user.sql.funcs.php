<?php

	include_once("db.funcs.php");
	
	function is_username_exists($username)
	{
		$sql = " SELECT `id` FROM `users` WHERE `username` = '".db_escape($username)."' LIMIT 1;";
		$result = db_query($sql);
		$rs = mysql_num_rows($result) > 0 ? true : false;
		mysql_free_result($result);
		return $rs;
	}

	function auth_user($username, $password)
	{
		$_SESSION = array();
		$password = md5(db_escape($password));
		$sql = " SELECT `id` , `school_id`, `user_type`, `fname`, `sname`, `lname`, `birthday`, `avatar` FROM `users` 
		          WHERE `username` = '".db_escape($username)."' AND `password`='".$password."' AND `active`=1
		          LIMIT 1";

		$result = db_query($sql);
		if( mysql_num_rows($result) == 0 ){
			return false;
		}
		
		$row = mysql_fetch_assoc($result);
		$_SESSION['_auth'] = array(
			'user_id'	=> $row['id'],
			'school_id'	=> $row['school_id'],
			'user_type'	=> $row['user_type'],
			'fname'		=> $row['fname'],
			'sname'		=> $row['sname'],
			'lname'		=> $row['lname'],
			'birthday'	=> $row['birthday'], 
			'avatar'	=> $row['avatar']
		);

		return true;
	}
	
	function add_user($data=array())
	{
		$data = array_map("db_escape", $data);
		
		if( 'STUDENT' == $data['user_type']){
			$student_data['class']  = $data['class'];
			$student_data['letter'] = $data['letter'];
			$student_data['end_study_year'] = $data['end_study_year'];
			unset($data['class']);
			unset($data['letter']);
			unset($data['end_study_year']);
		}
		
		$data['password'] = md5($data['password']);
		$fields = array_keys($data);
		$sql = "INSERT INTO `users` (`".implode("`,`",$fields)."`,`actieve`,`create_datetime`) VALUES('".implode("','",$data)."',0,NOW()) ";
		
		db_query($sql);
		$user_id =  mysql_insert_id();
		
		if( 'STUDENT' == $data['user_type']){
			$sql = "INSERT INTO `student_infos` (`user_id`,`class`,`letter`,`end_study_year`,`create_datetime`) VALUE 
			        (".$user_id.",'".$student_data['class']."','".$student_data['letter']."','".$student_data['end_study_year']."', NOW() )";
			db_query($sql);
		}
	}
	
	function edit_user($data=array())
	{
		$set_expr = create_sql_set_expr($data);
		$sql = "UPDATE `users` SET ".$set_expr.", `update_datetime`=NOW()";
		return mysql_insert_id();
	}
	
	
	
	

?>