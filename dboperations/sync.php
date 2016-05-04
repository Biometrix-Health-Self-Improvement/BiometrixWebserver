<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 4/17/2016 
# Last Modified: 4/17/2016
# 
# sync.php
# 	Performs a sync with the database based on the set params

try
{
	#includes the script for getting the db connection
	require '/var/www/dbconnection/Get_db_connection.php';
	
	#Grabs the functions for inserting or deleting data
	require '/var/www/dboperations/insert_update_values.php';
	require '/var/www/dboperations/delete_values.php';	
	require '/var/www/dboperations/pull_values.php';	

	#Makes a call to a the get_db_connection that sets up the PDO connection
	$db_connection = DbConnection::get_instance()->get_db_connection();	

	$json_verified = array();
	$cur_index = 0;
	$json_verified['NumElements'] = $cur_index;

	foreach($params as $table_name => &$table_op)
	{

		foreach( $table_op as $op_name => &$op_index)
		{
			if ($op_name == 'PullData')
			{
				$json_verified[$cur_index] = array();
				$json_verified[$cur_index]["Table"] = $table_name;
				$json_verified[$cur_index]["Operation"] = $op_name;

				$temp_handle = $db_connection->Prepare("create table #TempID ( ID int not null)");
				$temp_handle->execute();

				$insert_handle = $db_connection->Prepare("insert into #TempID Values(?)");
				$key_value = 0;
				$insert_handle->bindParam(1, $key_value, PDO::PARAM_INT);

				foreach( $op_index as &$func_params)
				{	
					foreach ( $func_params as $key)
					{
						$key_value = $key;
						$insert_handle->execute();
					}
				}
				
				$json_verified[$cur_index]["Results"] = pull_values($userid, $table_name);
				$cur_index = $cur_index + 1;

				$delete_handle = $db_connection->Prepare("drop table #TempID");
				$delete_handle->execute();
			}
			else
			{		
				foreach( $op_index as &$func_params)
				{
					$json_verified[$cur_index] = array();
					$json_verified[$cur_index]["Table"] = $table_name;
					$json_verified[$cur_index]["Operation"] = $op_name;
						
					if ($op_name == 'Delete')
					{
						$json_verified[$cur_index]["Results"] = delete_values($userid, $func_params, $table_name);
					}	
					else if ($op_name == 'Insert' || $op_name == 'Update')
					{
						$json_verified[$cur_index]["Results"] = insert_or_update($userid, $op_name, $func_params, $table_name);
					}
					$cur_index = $cur_index + 1;
				}
			}
		}
	}	

	$json_verified['NumElements'] = $cur_index;
	
	$db_connection=null;	
	//Auto sets to true, each operation can fail or succeed on
	//its own.
	$json_verified['Verified'] = true;
	echo json_encode($json_verified);
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
