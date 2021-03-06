<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/14/2016
# Last Modified: 1/23/2016
# 
# add_user
#	This php script creates a new user with the username and password that
# are passed in on the commandline.

try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	
	
	#Creates the json object that is passed back to the application
	$json_verified = array();
	$json_verified['Verified'] = false;
	$json_verified['Operation'] = "Add";

	#Prepares a select statement to check if the username already exists
	$username_check_handle = $db_connection->prepare('Select Username From Biometrix.dbo.LoginTable Where Username = :name');
	
	#Binds the username to the where clause above
	$username_check_handle->bindValue(':name', $username, PDO::PARAM_STR);	
	$username_check_handle->execute();

	#Checks if a row was returned
	if ($username_check_handle->fetch() != false)
	{
		$db_connection = null;
		$json_verified['Error'] = "Username already in use";
		echo json_encode($json_verified);
		$db_connection=null;	
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
		$json_verified['Error'] = "Email address already in use";
		echo json_encode($json_verified);
		$db_connection=null;	
		exit;
	}

	#$db_connection->exec('SET ANSI_NULL_DFLT_ON ON GO');
	
	#Creates the prepared statement to insert the new username, password, 
	#and email into the table
	$stmt_handle = $db_connection->prepare('Insert Into Biometrix.dbo.LoginTable (Username, [Password], Email) Values (:name, :pass, :email)');

	#Hashes the user's password
	$inserted_pass = password_hash($password, PASSWORD_DEFAULT); 

	#Binds the username and hashed password to the prepared statement
	$stmt_handle->bindValue(':name', $username, PDO::PARAM_STR);
	$stmt_handle->bindValue(':pass', $inserted_pass, PDO::PARAM_STR);
	$stmt_handle->bindValue(':email', $email, PDO::PARAM_STR);

	#Executes the prepared statement
	$stmt_handle->execute();

	#Creates another prepared statement to retrieve the newly added user's ID
	$stmt_handle = $db_connection->prepare('Select UserID From Biometrix.dbo.LoginTable WHERE Username = :name AND Email = :email');

	#Binds and executes the statement
	$stmt_handle->bindValue(':name', $username, PDO::PARAM_STR);
	$stmt_handle->bindValue(':email', $email, PDO::PARAM_STR);
	
	$stmt_handle->execute();

	$userid = 0;

	if ($row = $stmt_handle->fetch() )
	{
		$userid = $row[0];
	}


	$json_verified['Verified'] = true;
	
	#require '../dbconnection/Sign_jwt.php';
	
	require '/var/www/dbconnection/New_user_verify.php';
	#$json_verified['Token'] = JWTSign::sign_token($userid);
	
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
