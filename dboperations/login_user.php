<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/16/2016
# Last Modified: 1/23/2016
# 
# login_user
#	This script is designed to be a wrapper around the verify user script
# to handle extra details and close the database connection.


try
{
	#Includes the code for verifying the user
	require '/var/www/dboperations/verify_user.php';
	
	#Retrieves a json object with the token for the user
	$verification_json = verify_user($username, $password, true);
	$verification_json['Operation'] = "Login";

	#Encodes the object and returns it as a json object.	
	echo json_encode($verification_json);
	#echo var_dump($verification_json);


	$db_connection=null;	
}
catch(PDOException $except)
{
	echo $except->getMessage() . "\n";
}
catch(InvalidArgumentException $arg_except)
{
	echo $arg_except->getMessage() . "\n";
}

?>
