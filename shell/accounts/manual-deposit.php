<?php include_once('../db.php');
if(isset($_POST['method']) && $_POST['method'] == 'sherr'){
 $usid = $_POST['usid'];
 $paytype = $_POST['paytype'];
 $amount = $_POST['amount'];
 $gpnumber = $_POST['gpnumber'];
 $gpname = $_POST['gpname'];
 $ref = $_POST['ref'];
 $gpremarks = $_POST['gpremarks'];
 $date = time();
 
 $query = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid AND status = 'Pending'";
  $result = mysqli_query($conn, $query);
   if ($result->num_rows > 0){
	   echo 'Err!! You already have Pending request';
	   die();	   
   } else {
	$qinsert = "INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `ac_name`, `account_no`, `remarks`, `date`, `type`, `status`) VALUES ($usid,'$ref','$amount', '$gpname', '$gpnumber', '$gpremarks', '$date','$paytype','Pending')";
			if(mysqli_query($conn,$qinsert)){
				echo '<i class="icon check all"></i> Successfully Submitted.Check History';
			}
   }
}




if(isset($_POST['method']) && $_POST['method'] == 'mandeposit'){
	$usid = $_POST['usid'];
?>

<div class="_mandeposit"><span class="payclose">X</span>

<label for="paytype">Deposit Method*</label>
 <div class="dcontent">
  <select id="paymenttype">
   <option value="Skrill">Skrill</option>
   <option value="PayPal">PayPal</option>
   <option value="Google Pay">Google Pay</option>
   <option value="PayTM" selected>PayTM</option>
   <option value="UPI" selected>UPI</option>
   <option value="Bank Transfer" selected>Bank Transfer</option>
  </select>
 </div>

 <label for="paytype">Deposit Amount* ( <span style="font-size:10px">50 USD min.</span> )</label>
 <div class="dcontent">
  <input type="number" placeholder="Amount" id="mamount" required>
 </div>
 
 <label for="paytype">Transaction Reference (Optional)</label>
 <div class="dcontent">
  <input type="text" placeholder="Transaction ID" id="tref">
 </div>
 <div class="sherr"></div>
 <div class="paysubmit">Submit Request</div>



</div>


<?php } ?>