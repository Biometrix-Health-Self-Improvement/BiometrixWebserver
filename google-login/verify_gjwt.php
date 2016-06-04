<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/11/2016
#
# verify_gjwt.php
# This function is passed the google token and returns an array containing the
# user's information.

if (!function_exists("decode_google_token") )
{
#Decodes the passed in google token and returns the array 
function decode_google_token($token)
{
	$webfile = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=";
	$webfile = $webfile . $token;

	// Initialize session and set URL.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $webfile);

	// Set so curl_exec returns the result instead of outputting it.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Get the response and close the channel.
	$response = curl_exec($ch);
	curl_close($ch);

	return json_decode($response, true);;	
}
}
?>
