<?php
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/7/2016
# Last Modified: 2/7/2016
#
# Sign_jwt.php-
#	A class to sign the JWT based on the user's username (or email in case
#	of a google account). 
#
	
#Ensures the function is not defined multiple times. Similar to using
# #ifndef's in C++	
if (!class_exists('JWTSign') )
{

require '/var/www/dbconnection/jwt_helper.php';
#require 'google_client.php';

#A class that contains the methods for signing the JWT, and for verifying a
#token given from a google account
class JWTSign
{
	const DEBUG = False;
	
	#Signs and returns a key for the passed in userid
	public static function sign_token($userid)
	{
		$token = array();
		$token['userid'] = $userid;
		return JWT::encode($token, JWTSign::get_key() );
	}

	#retrieves the secret key for the server
	private static function get_key()
	{
		$key_file = fopen('/var/www/dbconnection/jwt_secret.txt', 'r') or die ("Key Unavailable");
		$key = fread($key_file, filesize('/var/www/dbconnection/jwt_secret.txt'));
		$key = trim($key);
		return $key;
	}

	
	#Decodes the passed in token and returns the userid
	public static function decode_token($token)
	{
		return JWT::decode($token, JWTSign::get_key() )->userid;
	}

	#Decodes the passed in google token and returns the array 
	public static function decode_google_token($token)
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
}
?>
