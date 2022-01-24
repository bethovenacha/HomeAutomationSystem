<?php
	require_once 'c.php';
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json);

 $con = mysql_connect($obj->{'host'},$obj->{'conusername'},$obj->{'conpassword'});
 
	if($con){
		mysql_select_db($obj->{'condatabase'},$con);
		header('Content-type: application/json');
		echo json_encode(array('conStatus'=>"true"));
	
	}else{	
		header('Content-type: application/json');
		echo json_encode(array('conStatus'=>"false"));
		
	} 
?>