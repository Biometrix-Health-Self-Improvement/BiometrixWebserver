<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/17/2016
# Last Modified: 1/17/2016
# 
# Db_operation.php 
# 	The user's entry into the php scripts. This call the other db scripts based on the
# 	parameter that is based in.

$url_encoded = file_get_contents("php://input");
$json_params = urldecode($url_encoded);
$http_post = (json_decode($json_params, true));

$operation = $http_post["Operation"];
$username = $http_post["Username"];
$password = $http_post["Password"];


#Chooses the script to call based on the HTML POST
switch($operation )
{
case "Login":
	require 'Login_user.php';
	break;
case "Add":
	require 'Add_db_user.php';
	break;
case "Delete":
	require 'Del_db_user.php';
	break;
default:
	echo 'Invalid Operation "' . $operation . '"chosen';
	break;
}

?>
