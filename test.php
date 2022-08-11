<?php
	header('Access-Control-Allow-Origin:*'); 
	header("Access-Control-Allow-Methods: *");
	header("Access-Control-Allow-Headers: Origin, Methods, Content-Type, Authorization");
	$gg = file_get_contents("php://input");
	echo $gg;
	
?>