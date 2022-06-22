<?php include_once('../db.php');
 $usid = $_POST['usid'];
 
 //show transfer amount
if(isset($_POST['method']) && $_POST['method'] == 'showvaltotr') {
	$trmoney=$_POST['trmoney'];
	//get agent currency value rate
	function agent_exchange($agent_curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$agent_curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	
	
	$agmin = mysqli_fetch_assoc(mysqli_query($conn,"SELECT ag_min_transfer FROM risk_management"));
	$min_allowed = $agmin['ag_min_transfer'];
	
	//if less than minimum, return
	if($trmoney < $min_allowed){
		echo 'Minimum transfer allowed '.$min_allowed.' '.$curry.'';
		die();
	}
	
	
	//get agent details
	$getusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,stripe_cus,afbal FROM users WHERE id=$usid"));
	$agent_curr = $getusr['stripe_cus']; //broker rate
	$agent_balance = $getusr['afbal'];
	if($trmoney > $agent_balance){
		echo 'You don\'t have sufficient balance. You have maximum transferable balance of '.$agent_balance. ' '.$curry.'';
		die();
	}
	
	
	$agent_value=agent_exchange($agent_curr,$conn);
	
	if($agent_curr =='USD'){
	$cut_net = $trmoney;
	//This is the amount to be transferred
	$net_transfer_amount = $cut_net - $get_net;
	}else{
	$cut_net =  $trmoney; //$agent_value['rate'] * $trmoney;	
	$get_net = $cut_net * 3.5/100;
	//This is the amount to be transferred
	$net_transfer_amount = $cut_net - $get_net;
	}
	
	echo "<div id='wraptramoutn'>You wills receive : <span style='color:#009688;font-weight:bold'> ".round($net_transfer_amount,2)." ".$agent_curr. "</span> in your betting account <div class='subtransferag'>Transfer Balance</div></div>";
	
	
	//get user details
	/*$rusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,country,stripe_cus,chips FROM users WHERE email='$tremail'"));
	$receiver_curr = $rusr['stripe_cus'];
	if(empty($receiver_curr)){
		echo 'No account found with this email';
		die();
	}
	$receiver_value = receiver_exchange($receiver_curr,$conn);
	
	
	if($broker_curr == $receiver_curr){
	$net_rate = $receiver_value['rate'] / $broker_value['rate'];
	$available_bal = round($net_rate * $trmoney, 2);
	}else{
	$net_rate = $receiver_value['rate'] / $broker_value['rate'];	
	$net_ded = $net_rate * 5/100;
	$net_value = $net_rate - $net_ded;
	
	$available_bal = round($net_value * $trmoney, 2);
	}
	
	echo "Recipient will get : <span style='color:#009688;font-weight:bold'> ".$available_bal. " ".$receiver_curr."</span><div class='subtransfer'>Send Money</div>";
	
	*/
}

///*

//submit transfer
else if(isset($_POST['method']) && $_POST['method'] == 'agpaynum') {
	$trmoney=$_POST['trmoney'];
	//get agent currency value rate
	function agent_exchange($agent_curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$agent_curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	
	
	$agmin = mysqli_fetch_assoc(mysqli_query($conn,"SELECT ag_min_transfer FROM risk_management"));
	$min_allowed = $agmin['ag_min_transfer'];
	
	//if less than minimum, return
	if($trmoney < $min_allowed){
		echo 'Minimum transfer allowed '.$min_allowed.' '.$curry.'';
		die();
	}
	
	
	//get agent details
	$getusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,stripe_cus,chips,afbal FROM users WHERE id=$usid"));
	$agent_curr = $getusr['stripe_cus']; //broker rate
	$agent_balance = $getusr['afbal'];
	$chips_balance = $getusr['chips'];
	if($trmoney > $agent_balance){
		echo 'You don\'t have sufficient balance. You have maximum transferable balance of '.$agent_balance. ' '.$curry.'';
		die();
	}
	
	$prevrec = $getusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM sh_sf_deposits WHERE user_id=$usid AND type='ag_commission' AND FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY)"));
	if(!empty($prevrec)){
	echo 'You already has transfers in the last 30 days. You are allowed to transfer once in a month/30 days. Please read terms for more info.. ';
	die();
	}
	
	
	
	$agent_value=agent_exchange($agent_curr,$conn);
	
	if($agent_curr =='USD'){
	$cut_net = $trmoney; //$agent_value['rate'] * $trmoney;
	//This is the amount to be transferred
	$net_transfer_amount = $cut_net - $get_net;
	}else{
	$cut_net = $trmoney; //$agent_value['rate'] * $trmoney;	
	$get_net = $cut_net * 3.5/100;
	//This is the amount to be transferred
	$net_transfer_amount = round($cut_net - $get_net,2);
	}
	$ref = substr(str_shuffle("QR8123MUXQWLG48URPLEE945Y"), 0, 20); 
	$tm = time();
	$af = $chips_balance + $net_transfer_amount;
	mysqli_query($conn, "UPDATE users SET chips = chips + $net_transfer_amount WHERE id=$usid");
	mysqli_query($conn, "UPDATE users SET afbal = afbal - $trmoney WHERE id=$usid");
	if(mysqli_affected_rows($conn)>0){
		//insert to deposits
		    mysqli_query($conn, "INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`) VALUES ($usid,'$ref','$net_transfer_amount','$tm','ag_commission','Received')");
			mysqli_query($conn, "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($usid,'ag_commission credit','$net_transfer_amount','$tm','chips','$chips_balance','$af')");
			if(mysqli_affected_rows($conn)>0){
				echo "<div id='succtr'>Successfully transferred : <span style='color:#009688;font-weight:bold'> ".$trmoney." ".$curry."</span> in your betting account. You need to logout and relogin to see balance credit. Check deposit history to confirm</div>";
			}else{
				mysqli_query($conn, "UPDATE users SET chips = chips - $net_transfer_amount WHERE id=$usid");
			}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 /*
 $amount = $_POST['amount'];
 $cq = "SELECT chips, afbal FROM users WHERE id = $usid";
 $cqres = mysqli_query($conn, $cq);
 $check = $cqres->fetch_assoc();
 
 $chips = $check['chips'];
 $afbal = $check['afbal'];
 $netafbal = $afbal - $amount;
 $netchips = $chips+$amount;
 if($afbal < $amount){
	 echo 'Err!! You don\'t have sufficient balance. Max. amount you can transfer is '.$afbal.'';
	   die();	   
 }
 if($afbal > $amount){
	 //SA2C = Super Agent to Chips
	 $date=time();
	$ddbal =  mysqli_query($conn, "UPDATE users SET afbal=afbal-$amount WHERE id=$usid");
	$debit = "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `aff_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($usid,'SA2C transfer Debit', '$usid', '-$amount','$date','SACredit','$afbal','$netafbal')";
		mysqli_query($conn, $debit);
		
	if (mysqli_affected_rows($conn) > 0){
	$addchips = mysqli_query($conn, "UPDATE users SET chips=chips+$amount WHERE id=$usid");
	$credit = "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `aff_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($usid,'SA2C transfer Credit', '$usid', '$amount','$date','Chips','$chips','$netchips')";
		mysqli_query($conn, $credit);
	} 
	if (mysqli_affected_rows($conn) > 0){
		echo "<div style='color:#007dc6'>Success! Remember, you need to logout & relogin to see the change.</div>";
		
	} else {
		echo $conn->error;
		echo "Could Not complete transfer. Contact support, if required";
	}
	   die();	   
 }
		*/
}

/*
if(isset($_POST['method']) && $_POST['method'] == 'werr'){
 $usid = $_POST['usid'];
 $paytype = $_POST['paytype'];
 $amount = $_POST['amount'];
 $acnum = $_POST['acnum'];
 $notes = $_POST['ref'];
 $ref = substr(str_shuffle("QR8123MUXQWLG48URPLEE945Y"), 0, 20); 
 $date = time();
 
 $cq = "SELECT afbal FROM users WHERE id ='$usid'";
 $cqres = mysqli_query($conn, $cq);
 $check = $cqres->fetch_assoc();
 $bal = $check['afbal'];
 if($bal < $amount){
	 echo 'Err!! You don\'t have sufficient balance. Max. amount you can withdraw is '.$bal.'';
	   die();	   
 }
 if($amount < 100){
	 echo 'Err!! Minimum 100 allowed..';
	   die();	   
 }
 
 $query = "SELECT * FROM sh_sf_agent_withdraws WHERE user_id=$usid AND status = 'Processing'";
  $result = mysqli_query($conn, $query);
   if ($result->num_rows > 0){
	   echo 'Err!! You already have Pending request';
	   die();	   
   } else {
	$qinsert = "INSERT INTO `sh_sf_agent_withdraws` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`, `send_from`, `acno`, `notes`) VALUES ($usid,'$ref','$amount','$date','$paytype','Processing', 'Agent', '$acnum', '$notes')";
			if(mysqli_query($conn,$qinsert)){
				echo '<i class="icon check all"></i> Successfully Submitted';
			}
   }
}


 if(isset($_POST['method']) && $_POST['method'] == 'wpending') {
	 $idto = $_POST['idto'];
	 $usid = $_POST['usid'];
	$query="DELETE FROM sh_sf_agent_withdraws WHERE id = '$idto' AND user_id='$usid' AND status = 'Processing' ";
	$isDeleted=mysqli_query($conn,$query);
		
}
*/

