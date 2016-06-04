<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/15/2016
# Last Modified: 2/15/2016
# 
# delete_unverified
#	This php script removes the user from the database. Should only be
# 	called for unverified users.

try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	$stmt_handle = $db_connection->prepare('Delete Biometrix.dbo.LoginTable Where Username = :name');

	#Binds the username to the prepared statement
	$stmt_handle->bindValue(':name', $argv[1], PDO::PARAM_STR);

	$stmt_handle->execute();
		
	$db_connection=null;
}
catch(PDOException $except)
{
	echo $except->getMessage() . "\n";
	$db_connection=null;	
}

?>
