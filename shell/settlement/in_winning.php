<?php header('Access-Control-Allow-Origin: *'); //error_reporting(0);
//if (isset($_GET['winid']) && $_GET['winid']!="") {
if(isset($_POST['method']) && $_POST['method'] == 'updateStatus'){
 include('../db.php');
 $comm = mysqli_query($conn, "SELECT ex_comi, sp_comi, ex_sagents FROM `risk_management` ");
 $c_row = $comm->fetch_assoc();
 $excomi = $c_row['ex_comi'];
 $spcomi = $c_row['sp_comi'];
 $sa_commi = $c_row['ex_sagents'];
 
 //get currency value
 function getAcommission($curr, $conn){
	 $query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur); 
 }
 
 //GET PARAMETERS
 $option_id = $_POST['id'];
 $option_name = $_POST['afc'];
 $sql = "SELECT * from sh_sf_tickets_records WHERE bet_info IS NULL AND bet_option_id = $option_id AND bet_option_name = '$option_name' AND status = 'awaiting'";
	$result = $conn->query($sql);
	$rows = mysqli_fetch_all($result);
	//echo '<pre>'; print_r($rows); echo '</pre>';
	foreach($rows as $row){
		$slip_id = $row[0];
		$user_id = $row[1];
		$status = $row[2];
		$stake = $row[3];
		$winnings = $row[4];
		$dt = $row[5];
		$odd = $row[7];
		$sodd = $row[8];
		$event_id = $row[9];
		$event_name = $row[10];
		$cat_name = $row[11];
		$cat_id = $row[12];
		$option_id = $row[13];
		$option_name = $row[14];
		$aid = $row[15];
		$type = $row[16];
		$sp = $row[17];
		$debit = $row[18];
		$st = $row[19]; //whether cashout or settlement.. se means settlement/ co means cashout
		$com = $winnings - $stake;
		
		$info = array(
			"event_id" => $event_id, "event_name" => $event_name, "cat_id" => $cat_id, "cat_name" => $cat_name, "bet_option_id" => $option_id, "bet_option_name" => $option_name
			);
		$bet_info = Serialize($info);	
		
		//get current balance of user
		$uinfo = mysqli_query($conn, "SELECT type, chips, promo, afid, afbal, said FROM users WHERE id = '$aid'");
		$fasso = $uinfo->fetch_assoc();
		$utype = $fasso['type'];
		$chips = $fasso['chips'];
		$promo = $fasso['promo'];
		$afid =  $fasso['afid'];
		$afbal = $fasso['afbal'];
		$said = $fasso['said'];
		
		$rkinfo = mysqli_query($conn, "SELECT stripe_cus, chips, promo FROM users WHERE id = '$user_id'");
		$usdt = $rkinfo->fetch_assoc();
		$uchips = $usdt['chips'];
		$upromo = $usdt['promo'];
		$curr = $usdt['stripe_cus'];
		
		//get currency value
		$agrate = getAcommission($curr, $conn);
		$agvalue = $agrate['rate'];
		$agcommission = $com; //$com/$agvalue;
		
		
		
		if($debit == 'chips'){
		//amount before/after
		 $ab = $uchips;
		 $af = $uchips + $winnings;
		} else {
		 $ab = $upromo;
		 $af = $upromo + $winnings;	
		}
		
		//affiliates calculation
		if(($type == 'back') && ($user_id != $afid)){
			$commission = $agcommission * $excomi/100;
			$sa_commission = $commission * $sa_commi/100;
			
		} else if(($type == 'sbook') && ($user_id != $afid)){
			$commission = $agcommission * $spcomi/100;
			$sa_commission = $commission * $sa_commi/100;
		} else if (($type == 'lay') && ($user_id != $afid)){
			$commission = 0;	
		} else {
			$commission = 0;
		}
		

		//for chips
		
			if($type == 'lay'){
			//update awaiting to settled in lay
			$ca = mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'losing' WHERE slip_id = '$slip_id' AND status = 'awaiting'");
			
			if (mysqli_affected_rows($conn) > 0){		
			//insert to users credit records
			$cc = mysqli_query($conn, "INSERT INTO `sh_users_credit_records` (`sl_id`, `u_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `aid`, `dt`) VALUES ('$slip_id', '$user_id', '$bet_info', '$odd', '$stake', '$ab', '0.00', '$ab', 'losing', '$type', '$debit', '$aid', '$dt')");
			if(mysqli_affected_rows($conn) > 0){
				mysqli_query($conn, "DELETE FROM sh_sf_tickets_records WHERE user_id=$user_id AND bet_info IS NULL AND bet_option_id = $option_id AND bet_option_name = '$option_name' AND status = 'awaiting'");
				echo 'Done';
			} else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'awaiting' WHERE slip_id = '$slip_id'");
			 die();
			 }
	
		} else {
			echo 'Failed';
			die();
		}
		

		/////////////for back/sbook//////////////////////////
		} else {
		//update awaiting to settled in back or sbook
			$ca = mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'winning' WHERE slip_id = '$slip_id' AND status = 'awaiting'");
			
			if (mysqli_affected_rows($conn) > 0){
			//insert to history table
			mysqli_query($conn, "INSERT INTO sh_sf_tickets_history SELECT * FROM sh_sf_tickets_records WHERE slip_id=$slip_id");
			
			//insert to users credit records
			$cc = mysqli_query($conn, "INSERT INTO `sh_users_credit_records` (`sl_id`, `u_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `aid`, `dt`) VALUES ('$slip_id', '$user_id', '$bet_info', '$odd', '$stake', '$ab', '$winnings', '$af', 'winning', '$type', '$debit', '$aid', '$dt')");
			
			if(mysqli_affected_rows($conn) > 0){
				$tmt = time();
				mysqli_query($conn, "UPDATE sh_sf_tickets_records SET date=$tmt WHERE slip_id=$slip_id");
				if($debit == 'chips'){
			        //update chips balance
			        mysqli_query($conn, "UPDATE users SET chips=chips + $winnings WHERE id=$user_id");
				} else {
					mysqli_query($conn, "UPDATE users SET promo=promo + $winnings WHERE id=$user_id");
				}
			 } else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'awaiting' WHERE slip_id = $slip_id");
			 die();
			}
			 
			//insert to agent credit records AGENTS...........
			//FOR AGENT EXCHANGE
		  if($utype == 'agent' && $debit == 'chips' && $type == 'back'){
			$afb = $afbal + $commission;	
			mysqli_query($conn, "INSERT INTO `sh_agents_credit_records` (`sl_id`, `agent_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `affu_id`, `dt`) VALUES ('$slip_id', '$aid', '$bet_info', '$odd', '$stake', '$afbal', '$commission', '$afb', 'credit', '$type', '$debit', '$user_id', '$dt')");
			if(mysqli_affected_rows($conn) > 0){
			//update affiliate balance
			mysqli_query($conn, "UPDATE users SET afbal=afbal + $commission WHERE id=$aid AND type = 'agent'");
			mysqli_query($conn, "UPDATE users SET sabal=sabal + $sa_commission WHERE id=$said AND type = 'Sagent'");
			} else {
			  echo 'Failed!';
			 }
			} 
			//FOR AGENT SPORTSBOOK
			else if($utype == 'agent' && $debit == 'chips' && $type == 'sbook'){
			$afsb = $afbal - $commission;	
			mysqli_query($conn, "INSERT INTO `sh_agents_credit_records` (`sl_id`, `agent_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `affu_id`, `dt`) VALUES ('$slip_id', '$aid', '$bet_info', '$odd', '$stake', '$afbal', '$commission', '$afsb', 'debit', '$type', '$debit', '$user_id', '$dt')");
			if(mysqli_affected_rows($conn) > 0){
			//update affiliate balance
			mysqli_query($conn, "UPDATE users SET afbal=afbal - $commission WHERE id=$aid AND type = 'agent'");
			mysqli_query($conn, "UPDATE users SET sabal=sabal - $sa_commission WHERE id=$said AND type = 'Sagent'");
			} else {
			echo 'Failed!';
			 }
				
				
			}
				
		} else {
			die();
		}	
				
	}
 
		
		

			
	} //foreach	
	
} //if isset

