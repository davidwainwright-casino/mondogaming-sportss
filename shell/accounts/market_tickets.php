<?php include_once('../db.php');error_reporting(0);
 $usid = $_POST['usid'];
 

 //for forex
 if(isset($_POST['method']) && $_POST['method'] == 'topfx'){?>
   No active records found...
 <?php }
 
 
 //for stocks
 else if(isset($_POST['method']) && $_POST['method'] == 'topstock'){	 
 echo 'No active records found...';
 } 
 
 
 //for crypto
 else if(isset($_POST['method']) && $_POST['method'] == 'topcrypto'){
	 echo 'No active records found...';
 }
 
 //for commodities
 else if(isset($_POST['method']) && $_POST['method'] == 'topcommodity'){
 echo 'No active records found...';
 }

 
 //for other markets
 else if(isset($_POST['method']) && $_POST['method'] == 'topmktothers'){
 echo 'No active records found...';
 }


?>
 