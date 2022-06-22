<?php include_once('../db.php');
$usid = $_POST['usid'];

include_once('../../init.php');

//function to get balance in form of (promo,chips)
	function get_exchange($curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	
$getusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT country,info,stripe_cus,chips FROM users WHERE id=$usid"));

$user_balance = $getusr['chips'];
$curr = $getusr['stripe_cus'];
$bank_details = $getusr['info'];
$country = $getusr['country'];

$cur_value=get_exchange($curr,$conn);


$minw = mysqli_fetch_assoc(mysqli_query($conn,"SELECT min_withdraw FROM risk_management"));
$min_withdraw = $minw['min_withdraw'];

$net_minimum = $min_withdraw;


echo '<input id="minwit" value="'.$net_minimum.'" hidden>';

if(isset($_POST['method']) && $_POST['method'] == 'werr'){
 $usid = $_POST['usid'];
 $paytype = $_POST['paytype'];
 $amount = $_POST['amount'];
 $notes = $_POST['ref'];
 $wcc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT wallet_address FROM sh_sf_users_bank WHERE user_id= ".$usid.""));
 $account_from = $wcc['wallet_address'];
 $ref = substr(str_shuffle("QR8123MUXQWLG48URPLEE945Y"), 0, 20); 
 $date = time();
 

 if($user_balance < $amount){
	 echo Lang::$word->WITHDRAWAL_FORM_ERROR.$user_balance.'';
	   die();	   
 }
 if($amount < $net_minimum){
	 echo Lang::$word->WITHDRAWAL_FORM_MIN.$net_minimum. Lang::$word->WITHDRAWAL_FORM_MIN2;
	   die();	   
 }
 
 
 $query = "SELECT * FROM sh_sf_withdraws WHERE user_id=$usid AND status = 'Processing'";
  $result = mysqli_query($conn, $query);
   if ($result->num_rows > 0){
	   echo Lang::$word->WITHDRAWAL_FORM_ERROR_PENDING;
	   die();	   
   } else {
	   mysqli_query($conn,"UPDATE users SET chips=chips - $amount WHERE id=$usid");
		  if(mysqli_affected_rows($conn) > 0){
	$qinsert = "INSERT INTO `sh_sf_withdraws` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `withdrawal_details`, `status`, `send_from`, `notes`, `cc`) VALUES ($usid,'$ref','$amount','$date','$paytype','$bank_details','Pending', '$account_from', '$notes', '$country')";
			  
			if(mysqli_query($conn,$qinsert)){
				echo Lang::$word->WITHDRAWAL_FORM_SUCCESS;
			}
		  }else{
			  echo Lang::$word->WITHDRAWAL_FORM_UNBABEL;
			  
		  }
   }
}


 if(isset($_POST['method']) && $_POST['method'] == 'wpending') {
	 $idto = $_POST['idto'];
	 $usid = $_POST['usid'];
	$query="DELETE FROM sh_sf_withdraws WHERE id = '$idto' AND user_id='$usid' AND status = 'Processing' ";
	$isDeleted=mysqli_query($conn,$query);
		
}


if(isset($_POST['method']) && $_POST['method'] == 'manwithdraw'){
	$usid = $_POST['usid'];
?>

<div class="_mandeposit"><span class="payclose">X</span>

<label for="paytype"><?= Lang::$word->WITHDRAWAL_FORM_METHOD; ?></label>
 <div class="dcontent">
  <select id="paymenttype">
  <option value="bKash" selected>bKash</option>
  <option value="Nagad">Nagad</option>
  <option value="Skrill">Rocket</option>
   <option value="Skrill">Skrill</option>
   <option value="PayPal">PayPal</option>
   <option value="Google Pay">Google Pay</option>
   <option value="PayTM">PayTM</option>
   <option value="UPI" >PhonePe</option>
   <option value="Bank Transfer"><?= Lang::$word->WITHDRAWAL_FORM_METHOD_MONEY; ?></option>
  </select>
 </div>

 <label for="paytype"><?= Lang::$word->WITHDRAWAL_FORM_AMOUNT; ?> ( <span style="font-size:10px"><?php echo $net_minimum;?> <?= Lang::$word->WITHDRAWAL_FORM_AMOUNT_min; ?></span> )</label>
 <div class="dcontent">
  <input type="number" placeholder="<?= Lang::$word->WITHDRAWAL_FORM_AMOUNT; ?>" id="mamount" required>
 </div>
 
 <label for="paytype"><?= Lang::$word->WITHDRAWAL_FORM_NOTES; ?></label>
 <div class="dcontent">
  <input type="text" id="tref">
 </div>
 <div class="sherr"></div>
 <div class="wrequest"><?= Lang::$word->WITHDRAWAL_FORM_SUBMIT_REQUEST; ?></div>

</div>


<?php } ?>