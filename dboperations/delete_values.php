<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 4/10/2016
# Last Modified: 4/17/2016 - Changed to a function
# 
# delete_values.php
# 	Deletes the row that matches the passed in userID, WebID, and localID

if (!function_exists('delete_values'))
{
function delete_values($userid, $params, $table)
{
try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	switch($table)
	{
	case "Mood":
		$prep_string = "Exec MoodDelete";
		$prep_string = $prep_string . " @WebMoodID = :WebMoodID";
		$prep_string = $prep_string . ", @UserID = :UserID";

		$stmt_handle = $db_connection->Prepare($prep_string);
		$cols = array();
		$cols["1"] = "WebMoodID";
		$cols["2"] = "UserID";
		break;
	case "Diet":
		$prep_string = "Exec DietDelete";
		$prep_string = $prep_string . " @WebDietID = :WebDietID";
		$prep_string = $prep_string . ", @UserID = :UserID";

		$stmt_handle = $db_connection->Prepare($prep_string);
		$cols = array();
		$cols["1"] = "WebDietID";
		$cols["2"] = "UserID";
		break;
	case "Exercise":
		$prep_string = "Exec ExerciseDelete";
		$prep_string = $prep_string . " @WebExerciseID = :WebExerciseID";
		$prep_string = $prep_string . ", @UserID = :UserID";

		$stmt_handle = $db_connection->Prepare($prep_string);
		$cols = array();
		$cols["1"] = "WebExerciseID";
		$cols["2"] = "UserID";
		break;
	case "Sleep":
		$prep_string = "Exec SleepDelete";
		$prep_string = $prep_string . " @WebSleepID = :WebSleepID";
		$prep_string = $prep_string . ", @UserID = :UserID";

		$stmt_handle = $db_connection->Prepare($prep_string);
		$cols = array();
		$cols["1"] = "WebSleepID";
		$cols["2"] = "UserID";
		break;
	case "Medication":
		$prep_string = "Exec MedicationDelete";
		$prep_string = $prep_string . " @WebMedicationID = :WebMedicationID";
		$prep_string = $prep_string . ", @UserID = :UserID";

		$stmt_handle = $db_connection->Prepare($prep_string);
		$cols = array();
		$cols["1"] = "WebMedicationID";
		$cols["2"] = "UserID";
		break;
	default:
		echo "Unrecognized database table";
		break;
	}
	
	$json_verified = array();

	#If these variables are set, a valid table was chosen
	#Bind the parameters and execute the stored procedure.
	if(isset($cols) && isset($stmt_handle) )
	{
		foreach ($cols as $col)
		{
			if ($col == "UserID")
			{
				$value = $userid;	
			}
			else
			{
				$value = $params[$col];
			}
		
			#Empty values passed in may include "" or "null"
			#Replace these with NULL	
			if ( strcasecmp($value,"null") == 0 || strcasecmp($value,"\"null\"") == 0)
			{
				$value = "";
			}
			$bind_name = ':' . $col;
			$stmt_handle->bindValue($bind_name, $value, PDO::PARAM_STR);		
		}
		$stmt_handle->execute();

		$row = $stmt_handle->fetch();
		$rows_affected = $row[0];

		if ($rows_affected == 1)
		{
			$json_verified['Verified'] = true;
			#Grabs the webkey from the first argument
			$json_verified['WebKey'] = $params[$cols["1"]];
		}
		else if ($rows_affected == 0)
		{
			$json_verified['Verified'] = false;
			$json_verified['Error'] = "Row was not deleted from the web database";
		}
		else if ($rows_affected > 1)
		{
			$json_verified['Verified'] = false;
			$json_verified['Error'] = 'Multiple rows deleted from the web database. Only 1 row should have been';
		}
		$stmt_handle = null;
	}
	else
	{
		$json_verified['Verified'] = false;
		$json_verified['Error'] = "Invalid database table chosen";
	}
	$db_connection=null;	
	return $json_verified;
}
catch(PDOException $except)
{
	echo $except->getMessage() . "\n";
	$err_arr = $stmt_handle->errorInfo();
	print_r($err_arr);

	$stmt_handle = null;
	$db_connection = null;
}
catch(InvalidArgumentException $arg_except)
{
	echo $arg_except->getMessage() . "\n";
}
}
}
?>
