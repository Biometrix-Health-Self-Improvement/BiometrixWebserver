<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/14/2016
# Last Modified: 1/17/2016
# 
# Add_db_user.php
#	This php script creates a new user with the username and password that
# are passed in on the commandline.

try
{
	#Calls the script that gets the params from commandline or HTTP	
	require 'Setup_credential_params.php';
	
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	
	#Prepares a select statement to check if the username already exists
	$username_check_handle = $db_connection->prepare('Select Username From Biometrix.dbo.LoginTable Where Username = :name');
	
	#Binds the username to the where clause above
	$username_check_handle->bindValue(':name', $username, PDO::PARAM_STR);	
	$username_check_handle->execute();

	#Checks if a row was returned
	if ($username_check_handle->fetch() != false)
	{
		$db_connection = null;
		exit("That username already exists\n");
	}
	
	#Prepares a select statement to check if the email address already
	#is in use
	$email_check_handle = $db_connection->prepare('Select Email From Biometrix.dbo.LoginTable Where Email = :email');
	
	#Binds the username to the where clause above
	$email_check_handle->bindValue(':email', $email, PDO::PARAM_STR);	
	$email_check_handle->execute();

	#Checks if a row was returned
	if ($email_check_handle->fetch() != false)
	{
		$db_connection = null;
		exit("That email address is already in use!\n");
	}
	
	#Creates the prepared statement to insert the new username, password, 
	#and email into the table
	$stmt_handle = $db_connection->prepare('Insert Into Biometrix.dbo.LoginTable (Username, Password, Email) Values (:name, :pass, :email)');

	#Hashes the user's password
	$inserted_pass = password_hash($password, PASSWORD_DEFAULT); 

	#Binds the username and hashed password to the prepared statement
	$stmt_handle->bindValue(':name', $username, PDO::PARAM_STR);
	$stmt_handle->bindValue(':pass', $inserted_pass, PDO::PARAM_STR);
	$stmt_handle->bindValue(':email', $email, PDO::PARAM_STR);

	$stmt_handle->execute();
	
	#Creates the json object that is passed back to the application
	$json_verified = array();
	$json_verified['Verified'] = True;
	echo json_encode($json_verified);
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
