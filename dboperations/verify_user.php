<?php
# 
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/14/2016
# Last modified: 1/23/2016
# 
# verify_user 
# 	A php script that is passed two arguments for username and password 
# respectively. It then authenticates against the database to ensure that
# the username and password are correct. The database connection is NOT
# automatically closed at the end since it is expected that this will be used
# within another script.
# 
#

if (!function_exists('verify_user'))
{
function verify_user($username, $password, $return_token = false)
{
	#Creates an object for the return of the json object.	
	$json_verified = array();
	$json_verified['Verified'] = false;
	
#The entire statement is enclosed in a try in case of a PDO exeption.
try
{
	#Includes the database connection file in this script
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Runs the get_db_connection function in the above file which returns
	#the PDO connection to the database
	$db_connection = DbConnection::get_instance()->get_db_connection();
	
	#Creates a prepared statement to select the username and password associated with the account
	$stmt_handle = $db_connection->prepare('Select UserID, Password, Verified From Biometrix.dbo.LoginTable WHERE Username = :name');

	#bands the value of :name in the above statement to the first value
	#passed in on the commandline
	$stmt_handle->bindValue(':name', $username, PDO::PARAM_STR);

	#Executes the prepared statement
	$stmt_handle->execute();

	$pass_correct = false;
	$userid = 0;

	#Fetches the first row, if null the username and password were wrong	
	if ($row = $stmt_handle->fetch() )
	{
		if ($row[2] == 0)
		{
			$json_verified['Verified'] = false;
			$json_verified['Error'] = "Please verify your email account";
		}
		#Uses built in bcrypt functionality to verify against the hash
 		else if ( password_verify($password, $row[1]) )
		{
			$pass_correct = true;
			$json_verified['Verified'] = true;

			#creates a return token for the user if one was
			#requested			
			if ($return_token = true)
			{
				$userid = intval($row[0]);
				#$userid = 1;
				require '/var/www/dbconnection/Sign_jwt.php';
				$json_verified['Token'] = JWTSign::sign_token($userid);
			}
		}
	}

}
catch(PDOException $except)
{
	echo $except->getMessage();
	$db_connection = null;
}

	#Returns the json object for success or failure of login
	return $json_verified;	
}
}
?>
