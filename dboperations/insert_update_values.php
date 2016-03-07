<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/25/2016
# Last Modified: 2/25/2016
# 
# insert_update_values.php
#     Inserts values into a table by calling the stored procedure for the table
#     or updates values for the passed in web key and userid combination
#     the user's id should be stored in $userid, the params as an array in
#     $params, the table name in $table, and the operation in $operation

try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	switch($table)
	{
	case "Exercise":
		$stmt_handle = $db_connection->Prepare("Exec Exercise$operation @UserID = :UserID, @LocalExerciseID = :LocalExerciseID, @Title = :Title, @Type = :Type, @Minutes = :Minutes, @Reps = :Reps, @Laps = :Laps, @Weight = :Weight, @Inty = :Inty, @Notes = :Notes, @DateEx = :DateEx, @TimeEx = :TimeEx");
		
		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalExerciseID";
		$cols["3"] = "Title";
		$cols["4"] = "Type";
		$cols["5"] = "Reps";
		$cols["6"] = "Minutes";
		$cols["7"] = "Laps";
		$cols["8"] = "Weight";
		$cols["9"] = "Inty";
		$cols["10"] = "Notes";
		$cols["11"] = "DateEx";
		$cols["12"] = "TimeEx";

		if ($operation == "Update")
		{
			$cols["13"] = "WebExerciseID";
		}
		break;

	case "Sleep":
		$stmt_handle = $db_connection->Prepare("Exec Sleep$operation @UserID = :UserID, @LocalSleepID = :LocalSleepID, @Date = :Date, @Time = :Time, @Duration = :Duration, @Quality = :Quality, @Notes = :Notes, @Health = :Health");
		
		$cols = array();
		$cols["1"] = "UserID";
		$cols["2"] = "LocalSleepID";
		$cols["3"] = "Date";
		$cols["4"] = "Time";
		$cols["5"] = "Duration";
		$cols["6"] = "Quality";
		$cols["7"] = "Notes";
		$cols["8"] = "Health";

		if ($operation == "Update")
		{
			$cols["9"] = "WebSleepID";
		}
		break;
	case "Mood":
		$stmt_handle = $db_connection->Prepare("Exec Mood$operation @UserID = :UserID, @LocalMoodID = :LocalMoodID, @Date = :Date, @Time = :Time, @Depression = :Depression, @Elevated = :Elevated, @Irritable = :Irritable, @Anxiety = :Anxiety, @Notes = :Notes");
		
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
		$stmt_handle = $db_connection->Prepare("Exec Diet$operation @UserID = :UserID, @LocalDietID = :LocalDietID, @Date = :Date, @FoodType = :FoodType, @Meal = :Meal, @ServingSize = :ServingSize, @Calories = :Calories, @TotalFat = :TotalFat, @SaturatedFat = :SaturatedFat, @TransFat = :TransFat, @Cholesterol = :Cholesterol, @Sodium = :Sodium, @TotalCarbs = :TotalCarbs, @DietaryFiber = :DietaryFiber, @Sugars = :Sugars, @Protein = :Protein, @VitaminA = :VitaminA, @VitaminB = :VitaminB, @Calcium = :Calcium, @Iron = :Iron, @Notes = :Notes");
	
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
	default:
		echo "Unrecognized database table";
		break;
	}
	
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
			
			echo $bind_name . " " . $value;
			$stmt_handle->bindValue($bind_name, $value, PDO::PARAM_STR);		
		}
		echo var_dump($stmt_handle);
		$stmt_handle->execute();

		
		$stmt_handle = null;
	}
	$db_connection=null;	
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

?>
