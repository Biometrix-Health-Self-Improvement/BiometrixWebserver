<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/11/2016
# Last Modified: 2/1333/2016
# 
# add_or_check_google_user.php
#	This php script checks if a google user with the id already exists. If 
# they do, the existing userid is returned. If they do not, a new user account
# is created for them.

try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	#Creates the json object that is passed back to the application
	$json_verified = array();
	$json_verified['Verified'] = false;

	#Prepares a select statement to check if the username already exists
	$username_check_handle = $db_connection->prepare('Select UserID, Username From Biometrix.dbo.LoginTable Where Username = :name AND Verified = 1');
	
	#Binds the username to the where clause above
	$username_check_handle->bindValue(':name', $username, PDO::PARAM_STR);	
	$username_check_handle->execute();

	$userid = 0;

	$row = $username_check_handle->fetch();	
	
	#Checks if a row was returned
	if ($row != false)
	{
		$db_connection = null;
		$userid = $row[0];		
	}
	else
	{
		#Creates the prepared statement to insert the new google user 
		#and email into the table
		$stmt_handle = $db_connection->prepare('Insert Into Biometrix.dbo.LoginTable (Username, Email, IsGoogle, Verified) Values (:name, :email, 1, 1)');
		#Binds the username and email to the statement
		$stmt_handle->bindValue(':name', $username, PDO::PARAM_STR);
		$stmt_handle->bindValue(':email', $email, PDO::PARAM_STR);
		
		#Executes the prepared statement
		$stmt_handle->execute();

		#Creates another prepared statement to retrieve the newly added 
		#user's ID
		$stmt_handle = $db_connection->prepare('Select UserID From Biometrix.dbo.LoginTable WHERE Username = :name AND Verified = 1');

		#Binds and executes the statement
		$stmt_handle->bindValue(':name', $username, PDO::PARAM_STR);
		$stmt_handle->execute();


		if ($row = $stmt_handle->fetch() )
		{
			$userid = $row[0];
		}
	}

	if ($userid != 0)
	{
		
		require '/var/www/dbconnection/Sign_jwt.php';
		$json_verified['Verified'] = true;
		$json_verified['Google'] = true;
		$json_verified['Operation'] = "GoogleLogin";
		$json_verified['Token'] = JWTSign::sign_token($userid);
		
		echo json_encode($json_verified);
	}
	else
	{
		$json_verified['Error'] = "Unexpected error";
	}
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
