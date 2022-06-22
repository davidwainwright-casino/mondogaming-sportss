<?php include_once('../db.php');
if(isset($_POST['method']) && $_POST['method'] == 'sherr'){
 $usid = $_POST['usid'];
 $paytype = $_POST['paytype'];
 $amount = $_POST['amount'];
 $ref = $_POST['ref'];
 $date = time();
 
 $query = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid AND status = 'Pending'";
  $result = mysqli_query($conn, $query);
   if ($result->num_rows > 0){
	   echo 'Err!! You already have Pending request';
	   die();	   
   } else {
	$qinsert = "INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`) VALUES ($usid,'$ref','$amount','$date','$paytype','Pending')";
			if(mysqli_query($conn,$qinsert)){
				echo '<i class="icon check all"></i> Successfully Submitted';
			}
   }
}



if(isset($_POST['method']) && $_POST['method'] == 'subcash'){
	$usid = $_POST['usid'];
	$cashid = $_POST['cashid'];
	$cashcheck = mysqli_query($conn, "SELECT transaction_id FROM sh_sf_deposits WHERE transaction_id = '$cashid'");
	if ($cashcheck->num_rows >= 1) {
		echo 'This Voucher Code is Already Redeemed';
		die();
	}
	$url = "https://sportsfier.com/shell/cashback/token?cash_id=".$cashid."";
	$client = curl_init($url);
	curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
	$response = curl_exec($client);
	$result = json_decode($response);
	$amount = $result->amount;
	$valid = $result->valid;
	$cashid = $result->cash_id;
	$date = time();
	if($amount == null){
		echo 'Invalid Voucher Code or Validity Expired';
		die();
	}
 
	$redeem_update ="UPDATE users SET chips=chips + $amount WHERE id='$usid'";
	if ($conn->query($redeem_update) === TRUE) {
		$qrinsert = "INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`) VALUES ($usid,'$cashid','$amount','$date','Voucher Credit','Received')";
		if ($conn->query($qrinsert) === TRUE) {
			echo '<i class="icon check all"></i> Successfully Redemeed! Your account is credited with '.$amount.'';
			
		$curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,'https://sportsfier.com/shell/cashback/token_update?cash_id='.$cashid.'');
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        $res = curl_exec($curl_handle);
        curl_close($curl_handle);
        if ($res) {
            //echo "success message";
        }
		
		} else {
			echo 'Account Credited but unable to record payments';
		}
	} else {
    echo "Unable to Redeem it, Please try again.";
	}
	$conn->close();
}








if(isset($_POST['method']) && $_POST['method'] == 'cashback'){
	$usid = $_POST['usid'];
?>

<div class="_mandeposit"><span class="payclose">X</span>

 
 <label for="paytype">Cashback Code</label> 
 <div class="tooltip"><i class="icon question sign"></i>
  <span class="tooltiptext"> Cashback/redeem code can be found when you purchase product from our registered retailers. All cashback amount are withdrawable. Read more..</span>
</div>
 
 </br>
 <div class="dcontent">
  <input type="text" id="cashid" placeholder="Voucher Number">
 </div>
 <div class="sherr"></div>
 <div class="redeemit">Redeem it</div>



</div>


<?php } ?>