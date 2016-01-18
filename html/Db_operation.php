<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/17/2016
# Last Modified: 1/17/2016
# 
# Db_operation.php 
# 	The user's entry into the php scripts. This call the other db scripts based on the
# 	parameter that is based in.


#If no operation was selected, echo the error and that is all.
if (!isset($_POST['operation'] ))
{
	echo "No database operation was selected";
}
else
{
	#Chooses the script to call based on the HTML POST
	switch($_POST['operation'] )
	{
	case "Login":
		require 'Login_user.php';
		break;
	case "Add":
		require 'Add_db_user.php';
		break;
	case "Delete":
		require 'Del_db_user.php';
		break;
	}
}

?>
