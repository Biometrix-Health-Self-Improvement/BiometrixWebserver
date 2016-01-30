<!-- 
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 1/23/2016
# Last Modified: 1/23/2016
# 
# forgot_password
#	Displays the main page for resetting the password
-->
<form action="perform_reset.php" method="post">
  Username:<br>
  <input type="text" name="Username" value="" required><br>
  New password:<br>
  <input type="password" name="Password" value="" required><br>
  Confirm New Password:<br>
  <input type="password" name="ConfirmPassword" value="" required><br>
  Token:<br>
  <input type="text" name="Token" value="<?php echo $_GET['Token'] ?>" required><br><br>
  
  <input type="submit" value="Submit">
</form> 


