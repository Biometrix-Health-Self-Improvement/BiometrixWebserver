<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/16/2016
# Last Modified: 1/17/2016
# 
# Del_db_user.php
#	This php script removes the user from the database if their username
#	and password are successfully verified
try
{
	#Calls the code that stores the HTTP or the commandline args into $username and $password
	require 'Setup_credential_params.php';

	#includes the script for getting the db connection
	require 'Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	#includes the code that is used to verify the user
	require 'Verify_user.php';

	#Gets the status of the attempt to verify the user	
	$verification_status = verify_user($username, $password);
	
	$json_delete_status = array();
	
	#If the verify_user function suceeded, remove the user
	if ($verification_status['verified'] = true)
	{
		#Creates the prepared statement to delete the user from the table 
		$stmt_handle = $db_connection->prepare('Delete Biometrix.dbo.LoginTable Where Username = :name');

		#Binds the username to the prepared statement
		$stmt_handle->bindValue(':name', $argv[1], PDO::PARAM_STR);

		$stmt_handle->execute();
		
		$json_delete_status['deleted'] = true;
	}
	else
	{
		$json_delete_status['deleted'] = false;
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
