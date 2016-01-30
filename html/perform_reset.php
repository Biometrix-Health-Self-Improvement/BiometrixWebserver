<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/23/2016
# Last Modified: 1/23/2016
#
# perform_reset.php
#       Checks if the username and token correspond to an entry in
#       reset_verifications.txt

#Grabs all needed variables from the POST
$username = $_POST["Username"];
$password = $_POST["Password"];
$password2 = $_POST["ConfirmPassword"];
$token = $_POST["Token"];

if ( $password != $password2 )
{
	echo "Passwords did not match, please go back and try again";
	exit;
}

$esc_token = escapeshellarg($token);
$esc_username = escapeshellarg($username);


#Executes a shell script that adds the verification token to the file
$verify_status = shell_exec ("/var/www/dbconnection/check_reset_token $esc_username $esc_token");

#If the username matches a valid token, reset the password
if ( $verify_status == 1 )
{

	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	#Prepares a select statement to check if the username already exists
	$modify_password_handle = $db_connection->prepare('Update Biometrix.dbo.LoginTable Set Password = :pass Where Username = :name');

	#Hashes the user's password
	$inserted_pass = password_hash($password, PASSWORD_DEFAULT); 

	#Binds the username and hashed password to the prepared statement
	$modify_password_handle->bindValue(':name', $username, PDO::PARAM_STR);
	$modify_password_handle->bindValue(':pass', $inserted_pass, PDO::PARAM_STR);
	$modify_password_handle->execute();

	shell_exec ("/var/www/dbconnection/delete_reset_token $esc_token");
	echo "Password has been changed!";
}
#If the username does not match, return the error
else
{
	echo "Password not changed. Check username and token and try again.";
}
?>
