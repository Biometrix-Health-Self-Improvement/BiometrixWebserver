<!-- 
#
# Author: Troy Riblett, troy.riblett@oit.edu
# Created: 2/13/2016
# Last Modified: 2/13/2016
# 
# verify_user.php
#	Displays the main page for setting up a new user
-->
<form action="perform_verify.php" method="post">
  Username:<br>
  <input type="text" name="Username" value="" required><br>
  Token:<br>
  <input type="text" name="Token" value="<?php echo $_GET['Token'] ?>" required><br><br>
  
  <input type="submit" value="Submit">
</form> 


