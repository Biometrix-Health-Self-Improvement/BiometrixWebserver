<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/25/2016
# Last Modified: 4/17/2016 - Changed to function for sync purposes
# 
# insert_update_values.php
#     Inserts values into a table by calling the stored procedure for the table
#     or updates values for the passed in web key and userid combination
#     the user's id should be stored in $userid, the params as an array in
#     $params, the table name in $table, and the operation in $operation

if (!function_exists('insert_or_update'))
{
function insert_or_update($userid, $operation, $params, $table)
{
try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	switch($table)
	{
	case "Exercise":
		$prep_string = "Exec Exercise$operation";
		$prep_string = $prep_string . " @UserID = :UserID";
		$prep_string = $prep_string . ", @LocalExerciseID = :LocalExerciseID";
		$prep_string = $prep_string . ", @Title = :Title";
		$prep_string = $prep_string . ", @Type = :Type";
		$prep_string = $prep_string . ", @Minutes = :Minutes";
		$prep_string = $prep_string . ", @Inty = :Inty";
		$prep_string = $prep_string . ", @Notes = :Notes";
		$prep_string = $prep_string . ", @DateEx = :DateEx";
		$prep_string = $prep_string . ", @TimeEx = :TimeEx";

		if ($operation == "Update")
		{
			$prep_string = $prep_string . ", @WebExerciseID = :WebExerciseID";
		}

		$stmt_handle = $db_connection->Prepare($prep_string);

		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalExerciseID";
		$cols["3"] = "Title";
		$cols["4"] = "Type";
		$cols["5"] = "Minutes";
		$cols["6"] = "Inty";
		$cols["7"] = "Notes";
		$cols["8"] = "DateEx";
		$cols["9"] = "TimeEx";

		if ($operation == "Update")
		{
			$cols["10"] = "WebExerciseID";
		}
		break;
	case "Sleep":
		$prep_string = "Exec Sleep$operation";
		$prep_string = $prep_string . " @UserID = :UserID";
		$prep_string = $prep_string . ", @LocalSleepID = :LocalSleepID";
		$prep_string = $prep_string . ", @Date = :Date";
		$prep_string = $prep_string . ", @Time = :Time";
		$prep_string = $prep_string . ", @Duration = :Duration";
		$prep_string = $prep_string . ", @Quality = :Quality";
		$prep_string = $prep_string . ", @Notes = :Notes";

		if ($operation == "Update")
		{
			$prep_string = $prep_string . ", @WebSleepID = :WebSleepID";
		}

		$stmt_handle = $db_connection->Prepare($prep_string);

		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalSleepID";
		$cols["3"] = "Date";
		$cols["4"] = "Time";
		$cols["5"] = "Duration";
		$cols["6"] = "Quality";
		$cols["7"] = "Notes";

		if ($operation == "Update")
		{
			$cols["8"] = "WebSleepID";
		}
		break;

	case "Mood":
		$prep_string = "Exec Mood$operation";
		$prep_string = $prep_string . " @UserID = :UserID";
		$prep_string = $prep_string . ", @LocalMoodID = :LocalMoodID";
		$prep_string = $prep_string . ", @Date = :Date";
		$prep_string = $prep_string . ", @Time = :Time";
		$prep_string = $prep_string . ", @Depression = :Depression";
		$prep_string = $prep_string . ", @Elevated = :Elevated";
		$prep_string = $prep_string . ", @Irritable = :Irritable";
		$prep_string = $prep_string . ", @Anxiety = :Anxiety";
		$prep_string = $prep_string . ", @Notes = :Notes";

		if ($operation == "Update")
		{
			$prep_string = $prep_string . ", @WebMoodID = :WebMoodID";
		}

		$stmt_handle = $db_connection->Prepare($prep_string);

		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalMoodID";
		$cols["3"] = "Date";
		$cols["4"] = "Time";
		$cols["5"] = "Depression";
		$cols["6"] = "Elevated";
		$cols["7"] = "Irritable";
		$cols["8"] = "Anxiety";
		$cols["9"] = "Notes";

		if ($operation == "Update")
		{
			$cols["10"] = "WebMoodID";
		}
		break;
	case "Diet":
		$prep_string = "Exec Diet$operation";
		$prep_string = $prep_string . " @UserID = :UserID";
		$prep_string = $prep_string . ", @LocalDietID = :LocalDietID";
		$prep_string = $prep_string . ", @Date = :Date";
		$prep_string = $prep_string . ", @FoodType = :FoodType";
		$prep_string = $prep_string . ", @Meal = :Meal";
		$prep_string = $prep_string . ", @ServingSize = :ServingSize";
		$prep_string = $prep_string . ", @Calories = :Calories";
		$prep_string = $prep_string . ", @TotalFat = :TotalFat";
		$prep_string = $prep_string . ", @SaturatedFat = :SaturatedFat";
		$prep_string = $prep_string . ", @TransFat = :TransFat";
		$prep_string = $prep_string . ", @Cholesterol = :Cholesterol";
		$prep_string = $prep_string . ", @Sodium = :Sodium";
		$prep_string = $prep_string . ", @TotalCarbs = :TotalCarbs";
		$prep_string = $prep_string . ", @DietaryFiber = :DietaryFiber";
		$prep_string = $prep_string . ", @Sugars = :Sugars";
		$prep_string = $prep_string . ", @Protein = :Protein";
		$prep_string = $prep_string . ", @VitaminA = :VitaminA";
		$prep_string = $prep_string . ", @VitaminB = :VitaminB";
		$prep_string = $prep_string . ", @Calcium = :Calcium";
		$prep_string = $prep_string . ", @Iron = :Iron";
		$prep_string = $prep_string . ", @Notes = :Notes";

		if ($operation == "Update")
		{
			$prep_string = $prep_string . ", @WebDietID = :WebDietID";
		}

		$stmt_handle = $db_connection->Prepare($prep_string);

		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalDietID";
		$cols["3"] = "Date";
		$cols["4"] = "FoodType";
		$cols["5"] = "Meal";
		$cols["6"] = "ServingSize";
		$cols["7"] = "Calories";
		$cols["8"] = "TotalFat";
		$cols["9"] = "SaturatedFat";
		$cols["10"] = "TransFat";
		$cols["11"] = "Cholesterol";
		$cols["12"] = "Sodium";
		$cols["13"] = "TotalCarbs";
		$cols["14"] = "DietaryFiber";
		$cols["15"] = "Sugars";
		$cols["16"] = "Protein";
		$cols["17"] = "VitaminA";
		$cols["18"] = "VitaminB";
		$cols["19"] = "Calcium";
		$cols["20"] = "Iron";
		$cols["21"] = "Notes";

		if ($operation == "Update")
		{
			$cols["22"] = "WebDietID";
		}
		break;
	case "Medication":
		$prep_string = "Exec Medication$operation";
		$prep_string = $prep_string . " @UserID = :UserID";
		$prep_string = $prep_string . ", @LocalMedicationID = :LocalMedicationID";
		$prep_string = $prep_string . ", @Date = :Date";
		$prep_string = $prep_string . ", @Time = :Time";
		$prep_string = $prep_string . ", @BrandName = :BrandName";
		$prep_string = $prep_string . ", @Prescriber = :Prescriber";
		$prep_string = $prep_string . ", @Dose = :Dose";
		$prep_string = $prep_string . ", @Instructions = :Instructions";
		$prep_string = $prep_string . ", @Warnings = :Warnings";
		$prep_string = $prep_string . ", @Notes = :Notes";

		if ($operation == "Update")
		{
			$prep_string = $prep_string . ", @WebMedicationID = :WebMedicationID";
		}

		$stmt_handle = $db_connection->Prepare($prep_string);

		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalMedicationID";
		$cols["3"] = "Date";
		$cols["4"] = "Time";
		$cols["5"] = "BrandName";
		$cols["6"] = "Prescriber";
		$cols["7"] = "Dose";
		$cols["8"] = "Instructions";
		$cols["9"] = "Warnings";
		$cols["10"] = "Notes";

		if ($operation == "Update")
		{
			$cols["11"] = "WebMedicationID";
		}
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

		if ($operation == "Insert")
		{
			if($row = $stmt_handle->fetch() )
			{
				$json_verified['Verified'] = true;
				
				$updated_pair = array();
				$updated_pair['Local'] = $row[0];
				$updated_pair['Web'] = $row[1];

				$json_verified['Row'] = $updated_pair;
			}
			else
			{
				$json_verified['Verified'] = false;
				$json_verified['Error'] = "Database insert failed";
			}
		}
		else if($operation == "Update")
		{
			if($row = $stmt_handle->fetch() )
			{
				$num_rows = $row[0];
				
				if ($num_rows == 1)
				{
					$json_verified['Verified'] = true;
					#Grabs the web key from the first argument
					$json_verified['WebKey'] = $params[$cols["1"]];
				}
				else if ($num_rows > 1)
				{
					$json_verified['Verified'] = false;
					$json_verified['Error'] = "Database updated multiple fields";
				}
				else
				{
					$json_verified['Verified'] = false;
					$json_verified['Error'] = "Failed to update row on Webdatabase";
				}
			}
			else
			{
				$json_verified['Verified'] = false;
				$json_verified['Error'] = "Database update failed";
			}
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
