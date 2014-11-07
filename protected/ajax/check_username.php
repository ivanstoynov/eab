<?php
	
	include_once(LIBRARY_DIR.'funcs'.DS.'database_funcs'.DS.'user.sql.funcs.php');

	$username = $_REQUEST['username'];
	$is_free = !is_username_exists($username);
if( $username == 'asdasd' ) $is_free = false;
	echo json_encode( array("success"=>true, "error"=>"", "data"=>$is_free) );
?>