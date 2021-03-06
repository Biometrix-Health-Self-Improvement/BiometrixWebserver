<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/16/2016
# Last Modified: 1/23/2016
# 
# del_user
#	This php script removes the user from the database if their username
#	and password are successfully verified
try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	#includes the code that is used to verify the user
	require 'verify_user';

	#Gets the status of the attempt to verify the user	
	$verification_status = verify_user($username, $password);
	
	$json_delete_status = array();
	$json_delete_status['Operation'] = "Delete";
	
	#If the verify_user function suceeded, remove the user
	if ($verification_status['verified'] = true)
	{
		#Creates the prepared statement to delete the user from the table 
		$stmt_handle = $db_connection->prepare('Delete Biometrix.dbo.LoginTable Where Username = :name');

		#Binds the username to the prepared statement
		$stmt_handle->bindValue(':name', $argv[1], PDO::PARAM_STR);

		$stmt_handle->execute();
		
		$json_delete_status['Deleted'] = true;
		$json_delete_status['Verified'] = true;
	}
	else
	{
		$json_delete_status['Deleted'] = false;
		$json_delete_status['Verified'] = false;
		$json_delete_status['Error'] = "Account not found"; 
	}

	$db_connection=null;

	echo json_encode($json_delete_status);
}
catch(PDOException $except)
{
	echo $except->getMessage() . "\n";
	$db_connection=null;	
}

?>
