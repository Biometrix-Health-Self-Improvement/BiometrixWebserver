<?php
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/17/2016
# Last Modified: 1/17/2016
#
# Setup_credential_params
#	This script basically just encapsulates a bit of code to set the username and password 
#	based on either the commandline arguments or the HTTP requests. This should be called
# 	before verifying the login, and username and password should be checked that they are not ""



#argc values allow this to be called from the commandline for testing. $_POST refers to the "super global"
#HTTP variablse that will be passed with the HTTP request.
if ($argc != 3 && !(isset($_POST['username']) && isset($_POST['password'])) )
{
	throw new InvalidArgumentException('Arguments passed incorrectly. Closing.');
}

$username = "";
$password = "";
#If the HTTP post variables were set use those, otherwise expect commandline args
if (isset($_POST['username']) && isset($_POST['password']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];	
}
else
{
	$username = $argv[1];
	$password = $argv[2];
}

?>
