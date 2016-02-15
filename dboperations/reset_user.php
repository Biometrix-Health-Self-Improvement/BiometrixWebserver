<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/23/2016
# Last Modified: 1/23/2016
# 
# reset_user
#	Verifies that the username and email match an existing user. If they do
#	an id is created and sent to the user's email address. If the user uses
#	the link

try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	$json_verified = array();	
	$json_verified['Verified'] = false;

	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	
	#Prepares a select statement to check if the username and email
	#combination exist already exists
	$correct_user_handle = $db_connection->prepare('Select Email From Biometrix.dbo.LoginTable Where Username = :name AND Email = :email');
	
	#Binds the username and email to the where clause above
	$correct_user_handle->bindValue(':name', $username, PDO::PARAM_STR);	
	$correct_user_handle->bindValue(':email', $email, PDO::PARAM_STR);	
	$correct_user_handle->execute();

	#Checks if a row was returned
	if ($correct_user_handle->fetch() == false)
	{
		$db_connection = null;
		$json_verified['Error'] = "Username and email combination does not exist";
		echo json_encode($json_verified);
		exit;
	}

	#calls the script that sends the email and creates the file on the
	#server corresponding to it.
	require '/var/www/dbconnection/Reset_password.php';

	$json_verified['Verified'] = true;
	$json_verified['EmailAddress'] = $email;
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
