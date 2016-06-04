<?php


require '/var/www/dbconnection/Sign_jwt.php';
try
{
	echo JWTSign::decode_token("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyc2VyaWQiOiIxIn0.fYhJXsbcaSfyc-h5l49utWTvD2TpRbffwOCOzyZdfo4");
}
catch(Exception $except)
{
	echo "Invalid Token";
}
exit;

date_default_timezone_set("UTC");
$cur_date = getdate();
echo var_dump($cur_date);
exit;
require '/var/www/dbconnection/Sign_jwt.php';

$userid = 1;
echo var_dump($userid);
$token = JWTSign::sign_token($userid);

var_dump($token);
#echo $token;
echo "\n";

echo JWTSign::decode_token($token);
echo "\n";

echo JWTSign::decode_token("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyaWQiOiIxIn0.fYhJXsbcaSfyc-h5l49utWTvD2TpRbffwOCOzyZdfo4");
?>
