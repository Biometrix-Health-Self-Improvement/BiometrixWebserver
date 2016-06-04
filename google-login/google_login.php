<?php
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/11/2016
# 
# google_login.php
#    verifies the webtoken passed set in do_operation, and then either creates
#    a new userid in the database, or returns the user's existing ID.

require '/var/www/google-login/verify_gjwt.php';

#google_token should be set in the do_operation.php script in html
$google_account = decode_google_token($google_token);

if (isset($google_account["error_description"]) )
{
	echo "Google validation failed";
	exit;
}

$aud = $google_account["aud"];

require '/var/www/google-login/client_id.php';
if ($aud == Get_Client_ID() )
{
	$username = $google_account["sub"];
	$email = $google_account["email"];
	require '/var/www/dboperations/add_or_check_google_user.php';
}

?>
