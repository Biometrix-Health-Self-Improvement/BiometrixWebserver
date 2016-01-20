<?php
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/17/2016
# Last Modified: 1/1999999999/2016
#
# Setup_credential_params
#  Pulls the username and password from the commandline if they were not
# already set. This is script is basically ignored from the Db_Operation.php
# script which is the main entrance from the web.

if (!isset($username) || !isset($password) )
{

if ($argc != 3 )
{
	throw new InvalidArgumentException('Arguments passed incorrectly. Closing.');
}

$username = $argv[1];
$password = $argv[2];
}
?>
