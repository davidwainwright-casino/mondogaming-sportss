<?php include_once("../db.php"); //error_reporting(0);
include_once('../../init.php');
$user_id=$_POST['usid'];
//function to get balance in form of (promo,chips)
	function get_exchange($curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
//for min & max deposit
$qrr="SELECT min_deposit,max_deposit FROM risk_management";
$mxbt=mysqli_query($conn,$qrr);
$mng = mysqli_fetch_assoc($mxbt);
$min_dp = $mng['min_deposit'];
$max_dp = $mng['max_deposit'];

//for getting user currency
$query="SELECT username, stripe_cus FROM users WHERE id='$user_id'";
$curr=mysqli_query($conn,$query);
$gbal = mysqli_fetch_assoc($curr);

//currency symbol
$cur_name = $gbal['stripe_cus'];
$usernamety = $gbal['username'];
$cur_value=get_exchange($cur_name,$conn);
$rate = $cur_value['rate'];

$deposit_min = round($min_dp, 2);
$deposit_max = round($max_dp, 2);


//international payments
 if(isset($_POST['method']) && $_POST['method'] == 'intPayments') {?>
 
 	<div class="depositform">
	All int. transactions need delecration at the time of transfer that payment is non-refundable. Withdrawals will be used on the same method.
	<h3>Your Money is 100% guarantted & Protected</h3>
	<b style="color:#f00">Note: </b> All payments will be billed as services and cannot be refunded but you can always withdraw the balance. Non-refundable clause is made to prevent possible fraud. Any such attempt will lead to permanent deletion of account.  <a href="#">Read more in the support page</a>
	</div>



<a id="pfmoney" href="/bt_accounts/?pg105=pf_money">Perfect Money Deposit here</a>


<div style="padding:10px">

<div class="intpayments">..</div>
	</br>
<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>
</br></br>
<h4>Contact the below given cashier to deposit</h4>
</div>
	<?php
   $usid = $_POST['usid'];	 
   $query = "SELECT fname,lname,email,city,notes,stripe_cus,chips FROM users WHERE id=100";
   $result = mysqli_query($conn, $query);?>
    <div style="overflow-x:auto;" id="seetick">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">Name</th>
        <th data-sort="int">WhatsAPP/Phone</th>
        <th data-sort="int">E-Mail ID</th>
		<th data-sort="int">Cashier Limit</th>
      </tr>
    </thead>
   
   <?php
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {?>
	<tr>
     <td><?php echo $row['fname'];?> <?php echo $row['lname'];?></td>
	 <td><?php echo $row['notes'];?></td>
	 <td><a><?php echo $row['email'];?></a></td>
	 <td><b style="color:#f00"><?php echo $row['chips'];?> <?php echo $row['stripe_cus'];?></b></td>
	 </tr>
	 </table>
	 </div>
	<?php }
   }else {
	  echo 'No Availabe agents found in your region. Please contact support.';
   }
 
 }
 
 //reedem voucher
 else if(isset($_POST['method']) && $_POST['method'] == 'redeemVoucher') {?>
 <div class="dptext">Cashback/redeem Balance</div>
 <div class="getfrhere"> Get Cashback code from here <a target="_blank" href="https://websiteclubs.com/api_interface_auth/?refauth=<?php echo $usernamety;?>"> Go here..</a>
 <div class="depositform">
	<h6>What, how or where do you find cashback code?</h6>
	
	Cashback coupon are available from the stores operated by the company. All available stores are listed below. You need to just click on the link and purchase product of your deposit size to generate the code. Remember, these cashback coupon stores are operated just to make deposits simple and legally feasible from everywhere</br>
	
	<b style="color:#f00">All the cashback balance you get from our affiliate stores are 100% withdrawl able as those are operated to make deposit of funds easier for our players</b></br>
	
	<button id="myBtn">more info here..</button>
	</div>
	
	<div class="storeslink">
	No Available stores at the moment. You will be notified once it's available.
	</div>
	
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
 
	 
 <?php }
 
 
 //crypto currency
 else if(isset($_POST['method']) && $_POST['method'] == 'cryptoCurrency') {
	 $usid = $_POST['usid'];
	 $ref = substr(str_shuffle("TROM9737XQWDG48URPLEE945Y"), 0, 20);
	 $date = time();
	 $gpnumber = 'null';
	 $gpname = 'null';
	 $gpremarks = 'null';
	 //first deposit bonus
	 $fdbset = mysqli_fetch_assoc(mysqli_query($conn,"SELECT fdb FROM risk_management"));
	 $promo_fdb = $fdbset['fdb'];
	 //exchange conversion
	 $ucur=mysqli_fetch_assoc(mysqli_query($conn,"SELECT stripe_cus FROM users WHERE id=$usid"));
	 $hecurrency = $ucur['stripe_cus'];
	 $rate=mysqli_fetch_assoc(mysqli_query($conn,"SELECT rate FROM currencies WHERE name='$hecurrency'"));
	 $urate = $rate['rate'];
	 $amount = $_COOKIE['crr'];
	 $amt = $amount * $urate;
	 $promo = $amt * $promo_fdb/100;
	 
	 mysqli_query($conn, "UPDATE users SET chips = chips + $amt WHERE id=$usid");
			if (mysqli_affected_rows($conn) > 0){
				mysqli_query($conn,"INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `ac_name`, `account_no`, `remarks`, `date`, `type`, `status`) VALUES ($usid,'$ref','$amt', NULL, NULL, NULL, '$date','CryptoCurrency','Received')");
				if (mysqli_affected_rows($conn) > 0){
					echo 'Your deposit is successfull. You can close the browser now';
					//for first deposit bonus
					$result = mysqli_query($conn, "SELECT * FROM sh_sf_deposits WHERE user_id=$usid AND status = 'Received'");
					if ($result->num_rows > 1){
						}else{
							mysqli_query($conn, "UPDATE users SET promo = promo + $promo WHERE id=$usid");
						}
					}else{
					mysqli_query($conn, "UPDATE users SET chips = chips - $amt WHERE id=$usid");
					echo 'Sorry, unable to deposit. Please try again.';
				}
			}else {
			echo 'Sorry, unable to deposit. Please try again.';	
			}
 
	 
	 
 }
 
 
 //crypto currency
 else if(isset($_POST['method']) && $_POST['method'] == 'history') {?>
	 
 <h5 class="dphist"><?= Lang::$word->ACC_P2_DEPOSIT_HISTORY; ?></h5>
  <?php
   $usid = $_POST['usid'];	 
   $query = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid ORDER BY date DESC LIMIT 50";
   $result = mysqli_query($conn, $query);
   echo '<input type="hidden" class="cfvalue" value="50">';
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
     $id = $row['id'];
	 $amount = $row['amount'];
     $ref = $row['transaction_id'];
     $date = date ("m-d-y H:i", $row['date']);
     $type = $row['type'];
	 $status = $row['status'];
	 ?> 
 
    <ul class="deptshow">
	 <li class="idto-<?php echo $id;?>">
	  <div class="ccredit"> <?php if($status == 'Received') { echo '<a>Received</a>';} else { echo '<a style="color:#f00">Pending</a>'; echo '<span class="cpending" id="'.$id.'" title="cancel request">X</span>'; };?> <span class="deptright"><?php echo $amount;?></span></div>
	  <div class="ccredit"> Deposit: <span class="deptright"><?php echo $type;?></span></div>
	  <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> Date: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  } else {
	 echo '<div style="padding:10px">No Active Records Found</div>';
  }
  
  if ($result->num_rows > 1) {
	echo '<span id="lrem" class="dload">Load More...</span>';
   }
 }
 
 
 
 //local pay selector
 //crypto currency
 else if(isset($_POST['method']) && $_POST['method'] == 'localpay') {
	$code = $_POST['code'];?>
	<div class="depositform">
	<b>How you do it?</b></br>
	All the below listed users are exchange brokers registered with us. They are authorized to take money from players in your region/country and credit equal amount in the betting account. Contact em via WhatsApp or email and transfer amount to their local bank/wallet/cash etc. They will credit your betting account within 3 hours. If you do not receive balance after 3 hours of transfer, please contact our support directly with broker email/name.
	<h3>Your Money is 100% guarantted & Protected</h3>
	<b style="color:#f00">Warning: </b> You should never transfer amount more than the TRANSFER LIMIT given in his account below. You can divide in to multiple payments, if required. Company is not responsible for over limit transfer loss(if any). You should also check the currency symbol whether it's USD or Local Currency. <a href="#">Read more in the support page</a>
	</div>
	<?php
   $usid = $_POST['usid'];	 
   $query = "SELECT fname,lname,email,city,notes,stripe_cus,chips FROM users WHERE username='V4u8xTKC' AND type='Sagent'";
   $result = mysqli_query($conn, $query);?>
    <div style="overflow-x:auto;" id="seetick">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">Name</th>
        <th data-sort="int">WhatsAPP/Phone</th>
        <th data-sort="int">E-Mail ID</th>
		<th data-sort="int">Transfer Limit</th>
      </tr>
    </thead>
   
   <?php
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {?>
	<tr>
     <td><?php echo $row['fname'];?> <?php echo $row['lname'];?></td>
	 <td><?php echo $row['notes'];?></td>
	 <td><a><?php echo $row['email'];?></a></td>
	 <td><b style="color:#f00"><?php echo $row['chips'];?> USD<?php //echo $row['stripe_cus'];?></b></td>
 
	 </tr>
	<?php }
   }else {
	  echo 'No Availabe agents found in your region. Please contact support.';
   }
	 ?> 
	 </table>
	 </div>
	 
	 
	 
	 
 <?php }
 
 //Indian payment tab
  else if(isset($_POST['method']) && $_POST['method'] == 'ggpay') {
	 $ifck = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_deposits WHERE user_id=$user_id AND status IN('Pending', 'Processing')"));
	 $amt = $ifck['amount'];
	 $status = $ifck['status'];
	  
	  ?>
	 
<div class="_mandeposit">
<?php if(!empty($amt)):?>
<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>

<div class="depositinfo">
	You already have Pending deposit request of  <b><?php echo $cur_name;echo ' '; echo $amt;?></b>
	<span class="dltdpt"><i class="icon delete"></i></span>
</div>


<?php else:?>






<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>

<div class="depositinfo">
	<span class="paywarn"><i class="icon question sign"></i> IMPORTANT!!</span>
	<b style="color:#f00">Before submitting request, please make a transfer to the below <b>bKash</b> account number. After successful transfer only submit this form to get credit in your account.
	</b><hr>

	<div class="ggdetails">
	<b>Bank name:</b> bKash</br>
	<b>Number:</b>  01318959360
	</div>
</div>

 <div class="dcontent">
  <select id="paymenttype" hidden>
   <option value="bKash">bKash</option>
  </select>
 </div>




 <label for="paytype">Deposit Amount* ( <span style="font-size:10px">Min. <?php echo $deposit_min;?> <?php echo $cur_name;?></span> )</label>
 
 <div class="dcontent">
 <input id="mindpd" value="<?php echo $deposit_min;?>" hidden>
 <input id="maxdpd" value="<?php echo $deposit_max;?>" hidden>

  <input type="number" placeholder="Amount" id="mamount" required>
 </div>
 
 
 <label for="paytype">Your bKash number</label>
 <div class="dcontent">
  <input type="text" placeholder="bKash number" id="gpnumber">
 </div>
 
 <!-- <label for="paytype">Your bKash Name</label>
 <div class="dcontent">
  <input type="text" placeholder="bKash name" id="gpname">
 </div> ---->

 <label for="paytype">Transaction ID/Ref. ID, if any (optional):</label>
 <div class="dcontent">
  <input type="text" placeholder="Transaction ID" id="tref">
 </div>
 
 
 <label for="paytype">Remarks (optional)</label>
 <div class="dcontent">
  <input type="textarea" placeholder="Put if you have anyting to say.." id="gpremarks">
 </div>
 <div class="sherr"></div>
 <div class="paysubmit">Submit Request</div>

<p> * Please contact support if you didn't get credit after 3 hours from submission of this form.</p>
<?php endif;?>
</div>
	 
	 
	 
 <?php }
 else if(isset($_POST['method']) && $_POST['method'] == 'gphonepe') {
	 
	 $ifck = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_deposits WHERE user_id=$user_id AND status IN('Pending', 'Processing')"));
	 $amt = $ifck['amount'];
	 $status = $ifck['status'];
	  
	  ?>
	 
<div class="_mandeposit">
<?php if(!empty($amt)):?>
<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>

<div class="depositinfo">
	You already have Pending deposit request of  <b><?php echo $cur_name;echo ' '; echo $amt;?></b>
	<span class="dltdpt"><i class="icon delete"></i></span>
</div>


<?php else:?>






<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>

<div class="depositinfo">
	<span class="paywarn"><i class="icon question sign"></i> IMPORTANT!!</span>
	<b style="color:#f00">Before submitting request, please make a transfer to the below <b>nagad</b> account number. After successful transfer only submit this form to get credit in your account.
	</b><hr>

	<div class="ggdetails">
	<b>Bank name:</b> NAGAD</br>
	<b>Number:</b>  01645174054
	</div>
</div>

 <div class="dcontent">
  <select id="paymenttype" hidden>
   <option value="nagad">nagad</option>
  </select>
 </div>




 <label for="paytype">Deposit Amount* ( <span style="font-size:10px">Min. <?php echo $deposit_min;?> <?php echo $cur_name;?></span> )</label>
 
 <div class="dcontent">
 <input id="mindpd" value="<?php echo $deposit_min;?>" hidden>
 <input id="maxdpd" value="<?php echo $deposit_max;?>" hidden>

  <input type="number" placeholder="Amount" id="mamount" required>
 </div>
 
 
 <label for="paytype">Your NAGAD number</label>
 <div class="dcontent">
  <input type="text" placeholder="Nagad number" id="gpnumber">
 </div>
 
 <!-- <label for="paytype">Your bKash Name</label>
 <div class="dcontent">
  <input type="text" placeholder="bKash name" id="gpname">
 </div> ---->

 <label for="paytype">Transaction ID/Ref. ID, if any (optional):</label>
 <div class="dcontent">
  <input type="text" placeholder="Transaction ID" id="tref">
 </div>
 
 
 <label for="paytype">Remarks (optional)</label>
 <div class="dcontent">
  <input type="textarea" placeholder="Put if you have anyting to say.." id="gpremarks">
 </div>
 <div class="sherr"></div>
 <div class="paysubmit">Submit Request</div>

<p> * Please contact support if you didn't get credit after 3 hours from submission of this form.</p>
<?php endif;?>
</div>
	 
	 
	 
	 
 <?php }
 
 else if(isset($_POST['method']) && $_POST['method'] == 'gpaytm') {
	 $ifck = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_deposits WHERE user_id=$user_id AND status IN('Pending', 'Processing')"));
	 $amt = $ifck['amount'];
	 $status = $ifck['status'];
	  
	  ?>
	 
<div class="_mandeposit">
<?php if(!empty($amt)):?>
<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>

<div class="depositinfo">
	You already have Pending deposit request of  <b><?php echo $cur_name;echo ' '; echo $amt;?></b>
	<span class="dltdpt"><i class="icon delete"></i></span>
</div>


<?php else:?>






<div class="mimzdep">
Minimum : <span class="ofmindep"><?php echo $deposit_min;?> <?php echo $cur_name;?></span>
Maximum : <span class="ofmindep"><?php echo $deposit_max;?> <?php echo $cur_name;?></span></br>
</div>

<div class="depositinfo">
	<span class="paywarn"><i class="icon question sign"></i> IMPORTANT!!</span>
	<b style="color:#f00">Before submitting request, please make a transfer to the below <b>Rocket</b> account number. After successful transfer only submit this form to get credit in your account.
	</b><hr>

	<div class="ggdetails">
	<b>Bank name:</b> Rocket</br>
	<b>Number:</b>   013034905586
	</div>
</div>

 <div class="dcontent">
  <select id="paymenttype" hidden>
   <option value="rocket">rocket</option>
  </select>
 </div>




 <label for="paytype">Deposit Amount* ( <span style="font-size:10px">Min. <?php echo $deposit_min;?> <?php echo $cur_name;?></span> )</label>
 
 <div class="dcontent">
 <input id="mindpd" value="<?php echo $deposit_min;?>" hidden>
 <input id="maxdpd" value="<?php echo $deposit_max;?>" hidden>

  <input type="number" placeholder="Amount" id="mamount" required>
 </div>
 
 
 <label for="paytype">Your Rocket number</label>
 <div class="dcontent">
  <input type="text" placeholder="Rocket number" id="gpnumber">
 </div>
 
 <!-- <label for="paytype">Your bKash Name</label>
 <div class="dcontent">
  <input type="text" placeholder="bKash name" id="gpname">
 </div> ---->

 <label for="paytype">Transaction ID/Ref. ID, if any (optional):</label>
 <div class="dcontent">
  <input type="text" placeholder="Transaction ID" id="tref">
 </div>
 
 
 <label for="paytype">Remarks (optional)</label>
 <div class="dcontent">
  <input type="textarea" placeholder="Put if you have anyting to say.." id="gpremarks">
 </div>
 <div class="sherr"></div>
 <div class="paysubmit">Submit Request</div>

<p> * Please contact support if you didn't get credit after 3 hours from submission of this form.</p>
<?php endif;?>
</div>
	 
	 
 <?php }
 
 //cancel deppost
  else if(isset($_POST['method']) && $_POST['method'] == 'canceldeposit') {
	  $usid = $_POST['usid'];
	  mysqli_query($conn,"UPDATE sh_sf_deposits SET status='Canceled' WHERE user_id=$usid AND status='Pending'");
	  if(mysqli_affected_rows($conn) > 0){
		  echo 'Successfully Canceled';
	  }else{
		 echo 'Couldn\'t Cancel as it\'s already Processing..'; 
	  }
  }
  
  
  
  
  
  
  
  
  
  