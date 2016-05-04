<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/17/2016
# Last Modified: 4/10/2016
# 
#  do_operation
# 	The user's entry into the php scripts. This call the other db scripts based on the
# 	parameter that is passed in.

$url_encoded = file_get_contents("php://input");
$json_params = urldecode($url_encoded);
$http_post = (json_decode($json_params, true));

#Retrieves the json information that is common to all db operations
$operation = $http_post["Operation"];

#Chooses the script to call based on the HTML POST
switch($operation )
{
case "Login":
	#Retrieves the json information specific to logging in 
	$username = $http_post["Username"];
	$password = $http_post["Password"];
	require '../dboperations/login_user.php';
	break;
case "GoogleLogin":
	$google_token = $http_post["GoogleToken"];
	require '../google-login/google_login.php';
	break;
case "Add":
	#Retrieves the json information for adding a user 
	$username = $http_post["Username"];
	$password = $http_post["Password"];
	$email = $http_post["Email"];
	require '../dboperations/add_user.php';
	break;
case "DeleteUser":
	#Retrieves the json information for deleting a user 
	$username = $http_post["Username"];
	$password = $http_post["Password"];
	$email = $http_post["Email"];
	require '../dboperations/del_user.php';
	break;
case "Reset":
	#Retrieves the json information for resetting a user
	$username = $http_post["Username"];
	$email = $http_post["Email"];
	require '../dboperations/reset_user.php';
	break;
case "Insert";
case "Update";
	$userid = 0;
	require '/var/www/dbconnection/Sign_jwt.php';
	try
	{
		$userid = JWTSign::decode_token($http_post["Token"]);
		$params = json_decode($http_post["Params"], true);
		$table = $http_post["Table"];
		require '/var/www/dboperations/insert_update_values.php';
		echo json_encode(insert_or_update($userid, $operation, $params, $table));
	}
	catch(Exception $except)
	{
		echo "Invalid Token. Try logging out and in again.";
	}
	break;
case "Delete";
	$userid = 0;
	require '/var/www/dbconnection/Sign_jwt.php';
	try
	{
		$userid = JWTSign::decode_token($http_post["Token"]);
		$params = json_decode($http_post["Params"], true);
		$table = $http_post["Table"];
		require '/var/www/dboperations/delete_values.php';
		echo json_encode(delete_values($userid, $params, $table));
	}
	catch(Exception $except)
	{
		echo "Invalid Token. Try logging out and in again.";
	}
	break;
case "Sync";
	$userid = 0;
	require '/var/www/dbconnection/Sign_jwt.php';
	try
	{
		$userid = JWTSign::decode_token($http_post["Token"]);
		$params = json_decode($http_post["Params"], true);
		$table = $http_post["Table"];
		require '/var/www/dboperations/sync.php';
	}
	catch(Exception $except)
	{
		echo "Invalid Token. Try logging out and in again.";
	}
	break;
default:
	echo "Welcome to Biometrix!\nEither your chosen operation is not setup,\nor you are accessing this page directly from the web";
	break;
}

?>
