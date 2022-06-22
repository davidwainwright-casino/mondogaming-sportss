<?php

header("Content-Type:application/json");
// /games/au_ewallet?opKey=DffdhzEPup2MMJLH&userId=1
//if (isset($_GET['opKey']) && $_GET['userId']!="" && $_GET['opKey'] == "DffdhzEPup2MMJLH") {
include('../db.php');
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data["pid"];
function getAcommission($curr, $conn){
	 $query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur); 
 }
 
$ptype = $data['type'];
//$twin = $data['win'];
$gameid= $data['gameid'];
$game_name = $data['product'];
$refTID = $data['tid'];
if(!empty($refTID)){
 $refTID = $data['tid'];
 }else{
 $refTID = '0'; 
 }
$tmt = time();
//user balance
$ubal=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stripe_cus,chips,afid FROM users WHERE id=$userId"));
 $balc = $ubal['chips'];
 
 
 $pamt = $data['amount'];
 $curr = $ubal['stripe_cus'];
 $agrate = 1; //getAcommission($curr, $conn);
 $agvalue = $agrate['rate'];
 $wamount = round($pmt * $agvalue, 2);
 $bet_amount = round($data['bet'] * $agvalue, 2);
 $twin = round($data['win'] * $agvalue, 2);
 
 
 
 $balc_after = $balc - $bet_amount;
 if(!empty($ubal['afid'])){
 $afid = $ubal['afid'];
 }else{
 $afid = '0';	 
 }
//$comi=mysqli_fetch_assoc(mysqli_query($conn, "SELECT casino_comi, ex_sagents FROM risk_management"));
$comm = mysqli_query($conn, "SELECT casino_comi, ex_sagents FROM `risk_management` ");
$comi = $comm->fetch_assoc();
$c_rate = $comi['casino_comi']; //casino commission rate on losing bet
$sa_rate = $comi['ex_sagents'];
 
//check if it's start of play to deduct balance
if($ptype=='BET'){
 //if balance is greater than or equal to bet amount go ahead and deduct the balance
 if($balc >= $bet_amount){
		mysqli_query($conn,"UPDATE users SET chips=chips-$bet_amount WHERE id=$userId");
		
		mysqli_query($conn,"INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `txn_id`, `aff_id`, `amount`, `date`, `type`, `af`, `bf`) VALUES ($userId, 'cainoPlay debit', '$refTID', $afid, '-$bet_amount', $tmt, 'chips', '$balc_after', '$balc')");
		 //INSERT FOR PLAYERS FRONT END 
		mysqli_query($conn,"INSERT INTO `sh_slot_casino_dealers` (`user_id`, `agent_id`, `game_id`, `game_name`, `stake`, `user_win`, `ag_win`, `type`, `transaction_id`, `updated_at`) VALUES ($userId, $afid, '$gameid', '$game_name', '$bet_amount', '$twin', '0.00', 'chips', '$refTID', $tmt)");
		 
		 //send success response
		 $asuccess = 'true';
		 $bsuccess = $balc;
		 $csuccess = "".$refTID."";
		 $dsuccess = '';
		 response($asuccess, $bsuccess, $csuccess, $dsuccess);
	 
 }else{
	$aerror='false';
	$berror=$balc;
	$cerror="".$refTID."";
	$derror='Balance Too Low';
	response($aerror, $berror, $cerror, $derror);
 }
	
}

//else if after play
else if($ptype=='WIN'){
		mysqli_query($conn,"UPDATE users SET chips=chips+$twin WHERE id=$userId");
		if($twin > 0){
		mysqli_query($conn,"UPDATE sh_slot_casino_dealers SET user_win=$twin WHERE user_id=$userId AND game_name='$game_name' ORDER BY id DESC LIMIT 1");	
		}
		
		//for agent credit
		if($twin <= 0){
		//get stake from
		$gstake=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stake FROM sh_slot_casino_dealers WHERE user_id=$userId AND game_name='$game_name' ORDER BY id DESC LIMIT 1"));
		$fstake = $gstake['stake'];
		
		$fcom = $fstake * $c_rate/100;
		$fcommi = round($fcom,3); //affiliate/agent commission
		$sab=mysqli_fetch_assoc(mysqli_query($conn, "SELECT said FROM users WHERE id=$afid"));
		$said = $sab['said'];
		$sacommy = $fcommi * $sa_rate/100;
		$fsac = round($sacommy,3); //final super agent commission
		
		mysqli_query($conn, "UPDATE users SET afbal=afbal + $fcommi WHERE id=$afid AND type = 'agent'");
		mysqli_query($conn, "UPDATE users SET sabal=sabal + $fsac WHERE id=$said AND type = 'Sagent'");
		
		mysqli_query($conn, "INSERT INTO `sh_agents_credit_records` (`sl_id`,`agent_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `affu_id`, `dt`) VALUES ('$gameid', '$afid', '$game_name', '0.00', '$fstake', '0.00', '$fcommi', '0.00', 'credit', 'casino_slot', 'chips', '$userId', '$tmt')");	
		}
		
		
		
		
		
		//mysqli_query($conn,"INSERT INTO `sh_slot_casino_dealers` (`user_id`, `agent_id`, `game_id`, `game_name`, `stake`, `user_win`, `ag_win`, `type`, `transaction_id`, `updated_at`) VALUES ($userId, $afid, '$gameid', '$game_name', '$bet_amount', '$twin', '0.00', 'chips', '$refTID', $tmt)");
	 
		 
		 //send success response
		 $asuccess = 'true';
		 $bsuccess = $balc;
		 $csuccess = "".$refTID."";
		 $dsuccess = '';
		 response($asuccess, $bsuccess, $csuccess, $dsuccess);
	
}



//else if after play FOR SLOTS
else if($ptype=='BETWIN'){
	     mysqli_query($conn,"UPDATE users SET chips=chips-$wamount WHERE id=$userId");
		 mysqli_query($conn,"INSERT INTO `sh_slot_casino_dealers` (`user_id`, `agent_id`, `game_id`, `game_name`, `stake`, `user_win`, `ag_win`, `type`, `transaction_id`, `updated_at`) VALUES ($userId, $afid, '$gameid', '$game_name', '$bet_amount', '$twin', '0.00', 'chips', '$refTID', $tmt)");
		 
		 mysqli_query($conn,"INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `txn_id`, `aff_id`, `amount`, `date`, `type`, `af`, `bf`) VALUES ($userId, 'cainoPlay debit', '$refTID', $afid, '-$bet_amount', $tmt, 'chips', '$balc_after', '$balc')");
		 //send success response
		 $asuccess = 'true';
		 $bsuccess = $balc;
		 $csuccess = "".$refTID."";
		 $dsuccess = '';
		 response($asuccess, $bsuccess, $csuccess, $dsuccess);
}

else if($ptype=='REFUND'){
	mysqli_query($conn,"UPDATE users SET chips=chips+$wamount WHERE id=$userId");
	mysqli_query($conn,"UPDATE sh_sf_points_log SET type='chips_refund' WHERE txn_id='$refTID'");
	mysqli_query($conn,"UPDATE sh_slot_casino_dealers SET type='chips_refund' WHERE transaction_id='$refTID'");
	
	
	
}


function response($statuses, $balnc, $reftd, $ermsg){
 $response['status'] = $statuses;	
 $response['balance'] = $balnc;
 $response['referenceTID'] = $reftd;
 $response['error'] = $ermsg;
 $json_response = json_encode($response);
 echo $json_response;
}




/*
$stmt = $conn->prepare("SELECT chips FROM `users` WHERE id=$userId");
$stmt->bind_param('s', $name); // 's' specifies the variable type => 'string'
$stmt->execute();
$result = $stmt->get_result();
 if(mysqli_num_rows($result)>0){
 $row = mysqli_fetch_array($result);
 $amount = $row['chips'];
 $status = 'true';
 $error = '';
 response($status, $amount, $error);
 mysqli_close($con);
 }else{
 $amount = '0';
 $status = 'false';
 $error = 'Internal Error';
 response($status, $amount, $error);
 }
//}else{
 //response(NULL, NULL, 400,"Invalid Request");
 //}
 
function response($status, $amount, $error){
 $response['status'] = $status;	
 $response['balance'] = $amount;
 $response['error'] = $error;
 $json_response = json_encode($response);
 echo $json_response;
}
*/
$conn -> close();
?>