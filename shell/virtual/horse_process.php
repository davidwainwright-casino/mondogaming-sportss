<?php include('../db.php'); //error_reporting(0);
if(isset($_POST['method']) && $_POST['method'] == 'debit_balance'){
	$stake = $_POST['stake'];
	$usid = $_POST['usid'];
	$uc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $usid"));
	$chip_b = $uc['chips'];
	$promo_b = $uc['promo'];
	if($chip_b >= $stake){
		mysqli_query($conn,"UPDATE users SET chips = chips - $stake WHERE id=$usid");
		echo 'success chips';
		die();
	}else if($promo_b >= $stake){
		mysqli_query($conn,"UPDATE users SET promo = promo - $stake WHERE id=$usid");
		echo 'success promo';
		die();
	}else {
		echo 'Insufficient Balance';
		echo $conn->error;
		die();
	}
}
//for insert record or balance addition
else if(isset($_POST['method']) && $_POST['method'] == 'set_records'){
	$usid = $_POST['usid'];
	$uc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $usid"));
	$chips = $uc['chips'];
	$promo = $uc['promo'];
	$stake = $_POST['istake'];
	$winnings = $_POST['iMoney'];
	$currency_rate = $_POST['curr'];
	$afid = $_POST['afid'];
	$type = $_POST['type'];
	$updated_at = time();
	//get management %
	$rk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM risk_management"));
	$spcomi = $rk['sp_comi'];
	$sacomi = $rk['ex_sagents'];
	
	//get super agent id
	$sa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = ".$afid.""));
	$said = $sa['said'];
	
	echo $winnings; echo 'odd'; echo $stake;
	if($winnings > $stake){
		
		//for chips
		if($type == 'chips'){
			$netwin = round($winnings - $chips,2);
			$tkwin = round($netwin - $stake,2);
			echo $tkwin; echo 'heyoo';
			$agwin = $tkwin/$currency_rate;
			//real deduction from winning balance only for agent credit else keep as it's for user with no real win deduction
			$netagwin = round($agwin * $spcomi/100,2);
			$netsawin = round($netagwin * $sacomi/100,2);
			
			mysqli_query($conn,"UPDATE users SET chips = $winnings WHERE id=$usid");
			//if(mysqli_affected_rows($conn) > 0){
				mysqli_query($conn, "INSERT INTO `sh_virtual_games` (`user_id`, `agent_id`, `game_name`, `stake`, `user_win`, `ag_win`, `type`, `updated_at`) VALUES ($usid, '$afid', 'Virtual HorseRacing', $stake, $tkwin, $netagwin, '$type', $updated_at)");
			echo 'after insert';
			echo $conn->error;
			if(!empty($afid)){
				mysqli_query($conn,"UPDATE users SET afbal = afbal + $netagwin WHERE id=$afid");
			}
			if(!empty($said)){
				mysqli_query($conn,"UPDATE users SET sabal = sabal + $netsawin WHERE id=$said");
			}
			
			
				
			//}
			
		}
		//for promo balance.. no agent credit for promo
		else if($type == 'promo'){
			$netwin = $winnings - $promo;
			$tkwin = round($netwin - $stake,2);
			mysqli_query($conn,"UPDATE users SET promo = $winnings WHERE id=$usid");
			
			//if(mysqli_affected_rows($conn) > 0){
				mysqli_query($conn, "INSERT INTO `sh_virtual_games` (`user_id`, `agent_id`, `game_name`, `stake`, `user_win`, `ag_win`, `type`, `updated_at`) VALUES ($usid, '$afid', 'Virtual HorseRacing', '$stake', '$tkwin', '$agwin', '$type', $updated_at)");
				
			//}
			
		}

		
		
		
		
	}
	
	
}
exit;?>