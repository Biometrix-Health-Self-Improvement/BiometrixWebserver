<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/13/2016
# Last Modified: 2/13/2016
# 
# New_user_verify.php
# 	Sends the user an email with a verification link to force the user to
#	confirm their account. The same token is then stored in the file system
#	so that identity can be confirmed and so that it will expire after 24 
#	hours

#Includes the random.php library. This allows the use of random_int
require "/var/www/dbconnection/random_compat-1.1.5/lib/random.php";

#This is taken from example code given in
#http://stackoverflow.com/a/31107425/2373138
$user_token = "";
$keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$max = strlen($keyspace);
$num_chars = random_int(40, 60);
for ($i = 0; $i < $num_chars; ++$i)
{
	$user_token .= $keyspace[random_int(0, $max)];
}

#Gets the current time in UTC. Since this is just used to determine when a
#verification is valid, this may as well be in universal time
date_default_timezone_set("UTC");
$cur_date = getdate();

#Escapes each of the arguments to the shell script to enable easier passing
$esc_day = escapeshellarg($cur_date["wday"]);
$esc_hour = escapeshellarg($cur_date["hours"]);
$esc_min = escapeshellarg($cur_date["minutes"]);
$esc_token = escapeshellarg($user_token);
$esc_username = escapeshellarg($username);
$esc_email = escapeshellarg($email);


#Executes a shell script that adds the verification token to the file
$verify_status = shell_exec ("/var/www/dbconnection/add_new_token $esc_day $esc_hour $esc_min $esc_token $esc_username $esc_email");

#echo $verify_status . "Please check your email for the reset link";

#The from address that should populate the email
$reply_address = "DoNotReply@biometrixapp.com";
$headers = "From: " . $reply_address;

#The link that will be sent to the user to reset their password
$reset_link = "https://www.biometrixapp.com/verify_user.php?Token=";
$reset_link .= $user_token;

$output = "Please go to the following link and confirm your username. ";
$output .= $reset_link;

#Sends the actual email
mail ($email, "Biometrix new account requested", $output, $headers);


?>
