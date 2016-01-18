<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/16/2016
# Last Modified: 1/17/2016
# 
# Login_user.php
#	This script is designed to be a wrapper around the verify user script
# to handle extra details and close the database connection.


try
{
	#Runs the script that sets up the paramaters based on either the commandline arguments
	#or the HTTP arguments
	require 'Setup_credential_params.php';
	
	#Includes the code for verifying the user
	require 'Verify_user.php';
	
	#Retrieves a json object
	$verification_json = verify_user($username, $password);
	
	#Encodes the object and returns it as a json object.	
	echo json_encode($verification_json);
	
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
