<?php

	$city_id = $_REQUEST['city_id'];
	$sql = "SELECT `id`, `school_name` FROM `schools` WHERE `city_id` = ".(int)$city_id;
	
	$schools = db_fetch_all($sql);
	
	echo json_encode( array("success"=>true, "error"=>"", "data"=>$schools) );
?>