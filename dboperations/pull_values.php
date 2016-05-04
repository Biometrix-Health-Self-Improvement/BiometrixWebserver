<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 4/18/2016
# Last Modified: 4/18/2016 - Changed to a function
# 
# pull_values.php
# 	Deletes the row that matches the passed in userID, WebID, and localID

if (!function_exists('pull_values'))
{
function pull_values($userid, $table)
{
try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	$json_verified = array();

	switch($table)
	{
	case "Mood":
		$prep_string = "Select ";
		$prep_string = $prep_string . "WebMoodID";
		$prep_string = $prep_string . ", UserID";
		$prep_string = $prep_string . ", LocalMoodID";
		$prep_string = $prep_string . ", Date";
		$prep_string = $prep_string . ", convert(varchar(5), Time, 114)";
		$prep_string = $prep_string . ", Depression";
		$prep_string = $prep_string . ", Elevated";
		$prep_string = $prep_string . ", Irritable";
		$prep_string = $prep_string . ", Anxiety";
		$prep_string = $prep_string . ", Notes";
		$prep_string = $prep_string . " From Biometrix.dbo.Mood";
		$prep_string = $prep_string . " LEFT JOIN #TempID ON ID = WebMoodID";
		$prep_string = $prep_string . " Where [UserId] = ? AND ID is null";

		$stmt_handle = $db_connection->Prepare($prep_string);

		$num_cols = 10;

		$cols = array();
		$cols[0] = "WebMoodID";
		$cols[1] = "UserID";
		$cols[2] = "LocalMoodID";
		$cols[3] = "Date";
		$cols[4] = "Time";
		$cols[5] = "Depression";
		$cols[6] = "Elevated";
		$cols[7] = "Irritable";
		$cols[8] = "Anxiety";
		$cols[9] = "Notes";
		break;
	case "Diet":
		$prep_string = "Select ";
		$prep_string = $prep_string . "WebDietID";
		$prep_string = $prep_string . ", UserID";
		$prep_string = $prep_string . ", LocalDietID";
		$prep_string = $prep_string . ", Date";
		$prep_string = $prep_string . ", FoodType";
		$prep_string = $prep_string . ", Meal";
		$prep_string = $prep_string . ", ServingSize";
		$prep_string = $prep_string . ", Calories";
		$prep_string = $prep_string . ", TotalFat";
		$prep_string = $prep_string . ", SaturatedFat";
		$prep_string = $prep_string . ", TransFat";
		$prep_string = $prep_string . ", Cholesterol";
		$prep_string = $prep_string . ", Sodium";
		$prep_string = $prep_string . ", TotalCarbs";
		$prep_string = $prep_string . ", DietaryFiber";
		$prep_string = $prep_string . ", Sugars";
		$prep_string = $prep_string . ", Protein";
		$prep_string = $prep_string . ", VitaminA";
		$prep_string = $prep_string . ", VitaminB";
		$prep_string = $prep_string . ", Calcium";
		$prep_string = $prep_string . ", Iron";
		$prep_string = $prep_string . ", Notes";
		$prep_string = $prep_string . " From Biometrix.dbo.Diet";
		$prep_string = $prep_string . " LEFT JOIN #TempID ON ID = WebDietID";
		$prep_string = $prep_string . " Where [UserId] = ? AND ID is null";

		$stmt_handle = $db_connection->Prepare($prep_string);

		$num_cols = 22;

		$cols = array();
		$cols[0] = "WebDietID";
		$cols[1] = "UserID";
		$cols[2] = "LocalDietID";
		$cols[3] = "Date";
		$cols[4] = "FoodType";
		$cols[5] = "Meal";
		$cols[6] = "ServingSize";
		$cols[7] = "Calories";
		$cols[8] = "TotalFat";
		$cols[9] = "SaturatedFat";
		$cols[10] = "TransFat";
		$cols[11] = "Cholesterol";
		$cols[12] = "Sodium";
		$cols[13] = "TotalCarbs";
		$cols[14] = "DietaryFiber";
		$cols[15] = "Sugars";
		$cols[16] = "Protein";
		$cols[17] = "VitaminA";
		$cols[18] = "VitaminB";
		$cols[19] = "Calcium";
		$cols[20] = "Iron";
		$cols[21] = "Notes";
		break;
	case "Exercise":
		$prep_string = "Select ";
		$prep_string = $prep_string . "WebExerciseID";
		$prep_string = $prep_string . ", UserID";
		$prep_string = $prep_string . ", LocalExerciseID";
		$prep_string = $prep_string . ", Title";
		$prep_string = $prep_string . ", Type";
		$prep_string = $prep_string . ", Minutes";
		$prep_string = $prep_string . ", Inty";
		$prep_string = $prep_string . ", Notes";
		$prep_string = $prep_string . ", DateEx";
		$prep_string = $prep_string . ", TimeEx";
		$prep_string = $prep_string . " From Biometrix.dbo.Exercise";
		$prep_string = $prep_string . " LEFT JOIN #TempID ON ID = WebExerciseID";
		$prep_string = $prep_string . " Where [UserId] = ? AND ID is null";

		$stmt_handle = $db_connection->Prepare($prep_string);

		$num_cols = 10;

		$cols = array();
		$cols[0] = "WebExerciseID";
		$cols[1] = "UserID";
		$cols[2] = "LocalExerciseID";
		$cols[3] = "Title";
		$cols[4] = "Type";
		$cols[5] = "Minutes";
		$cols[6] = "Inty";
		$cols[7] = "Notes";
		$cols[8] = "DateEx";
		$cols[9] = "TimeEx";
		break;
	case "Sleep":
		$prep_string = "Select ";
		$prep_string = $prep_string . "WebSleepID";
		$prep_string = $prep_string . ", UserID";
		$prep_string = $prep_string . ", LocalSleepID";
		$prep_string = $prep_string . ", Date";
		$prep_string = $prep_string . ", convert(varchar(5), Time, 114)";
		$prep_string = $prep_string . ", convert(varchar(5), Duration, 114)";
		$prep_string = $prep_string . ", Quality";
		$prep_string = $prep_string . ", Notes";
		$prep_string = $prep_string . " From Biometrix.dbo.Sleep";
		$prep_string = $prep_string . " LEFT JOIN #TempID ON ID = WebSleepID";
		$prep_string = $prep_string . " Where [UserId] = ? AND ID is null";

		$stmt_handle = $db_connection->Prepare($prep_string);

		$num_cols = 8;

		$cols = array();
		$cols[0] = "WebSleepID";
		$cols[1] = "UserID";
		$cols[2] = "LocalSleepID";
		$cols[3] = "Date";
		$cols[4] = "Time";
		$cols[5] = "Duration";
		$cols[6] = "Quality";
		$cols[7] = "Notes";
		break;
	case "Medication":
		$prep_string = "Select ";
		$prep_string = $prep_string . "WebMedicationID";
		$prep_string = $prep_string . ", UserID";
		$prep_string = $prep_string . ", LocalMedicationID";
		$prep_string = $prep_string . ", Date";
		$prep_string = $prep_string . ", convert(varchar(5), Time, 114)";
		$prep_string = $prep_string . ", BrandName";
		$prep_string = $prep_string . ", Prescriber";
		$prep_string = $prep_string . ", Dose";
		$prep_string = $prep_string . ", Instructions";
		$prep_string = $prep_string . ", Warnings";
		$prep_string = $prep_string . ", Notes";
		$prep_string = $prep_string . " From Biometrix.dbo.Medication";
		$prep_string = $prep_string . " LEFT JOIN #TempID ON ID = WebMedicationID";
		$prep_string = $prep_string . " Where [UserId] = ? AND ID is null";

		$stmt_handle = $db_connection->Prepare($prep_string);

		$num_cols = 11;

		$cols = array();
		$cols[0] = "WebMedicationID";
		$cols[1] = "UserID";
		$cols[2] = "LocalMedicationID";
		$cols[3] = "Date";
		$cols[4] = "Time";
		$cols[5] = "BrandName";
		$cols[6] = "Prescriber";
		$cols[7] = "Dose";
		$cols[8] = "Instructions";
		$cols[9] = "Warnings";
		$cols[10] = "Notes";
		break;
	default:
		echo "Unrecognized database table";
		break;
	}
	
	$stmt_handle->bindValue(1, $userid, PDO::PARAM_INT);
	$stmt_handle->execute();
	$cur_row_num = 0;
	while($row = $stmt_handle->fetch() )
	{
		$row_entry = array();
		for($i = 0; $i < $num_cols; $i++)
		{
			$col_entry = array();
			$col_entry["ColumnName"] = $cols[$i];
			$col_entry["Value"] = $row[$i];
			$row_entry[$i] = $col_entry;
		}
		
		#Hack to get php to stop treating this as a freakin list.
		$row_entry[-1] = -1;
		$json_verified[$cur_row_num] = $row_entry;
		$cur_row_num = $cur_row_num + 1;
	}
	$json_verified["NumRows"] = $cur_row_num;
	$json_verified["NumColumns"] = $num_cols;

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
