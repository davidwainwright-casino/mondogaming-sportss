<?php error_reporting(0);include('../../db.php');

  //for deposits
  $usid = $_POST['usid'];
  if($_POST['method'] == 'deposit_withdrawal'){
	$deposit = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(amount) AS amt FROM sh_sf_deposits WHERE user_id=$usid AND status = 'Received' "));
	$withdraw = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(amount) AS amt FROM sh_sf_withdraws WHERE user_id=$usid AND status = 'Processed' "));
  }

?>

Deposited:<span class="paydepot"><?php echo round($deposit['amt'],2);?></span> | Withdrawn:<span class="paydepot"><?php echo round($withdraw['amt'],2);?></span>