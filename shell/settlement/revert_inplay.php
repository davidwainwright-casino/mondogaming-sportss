<?php header('Access-Control-Allow-Origin: *'); error_reporting(0);
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
 
 
 //GET RESPONSE VALUES
 $option_id = $_POST['id'];
 $option_name = $_POST['afc'];
 $stt = $_POST['stt'];
 
 //REVERT WINNING
 if($stt == 'winning'){
	$sql = "SELECT * FROM sh_sf_tickets_records WHERE status <>'awaiting' AND bet_option_id = $option_id AND bet_option_name = '$option_name'";
	$result = $conn->query($sql);
	$rows = mysqli_fetch_all($result);
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
		
		//get current balance of agent
		$uinfo = mysqli_query($conn, "SELECT type, chips, promo, afid, afbal, said FROM users WHERE id = $aid");
		$fasso = $uinfo->fetch_assoc();
		$utype = $fasso['type'];
		$chips = $fasso['chips'];
		$promo = $fasso['promo'];
		$afid =  $fasso['afid'];
		$afbal = $fasso['afbal'];
		$said = $fasso['said'];
		
		//get users balance data	
		$rkinfo = mysqli_query($conn, "SELECT stripe_cus, chips, promo FROM users WHERE id = $user_id");
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
			$commission = $winnings * $excomi/100;;	
		} else {
			$commission = 0;
		}
		
		

		//for Lay bet
		
			if($type == 'lay'){
			//update awaiting to settled in lay
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'awaiting' WHERE slip_id = '$slip_id'");
			if (mysqli_affected_rows($conn) > 0){		
			//insert to users credit records
			mysqli_query($conn, "DELETE FROM sh_users_credit_records WHERE sl_id = '$slip_id'");
			if(mysqli_affected_rows($conn) > 0){
				echo 'done!';
			} else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'winning' WHERE slip_id = '$slip_id'");
			 die();
			 }
			 
			}

		/////////////for back/sbook//////////////////////////
		} else {
			
			$lab = $ab - $winnings;
			
		//update awaiting to settled in back or sbook
		$ca = mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'awaiting' WHERE slip_id = $slip_id");
		mysqli_query($conn, "DELETE FROM sh_sf_tickets_history WHERE slip_id = $slip_id");
			
			if (mysqli_affected_rows($conn) > 0){			
			//insert to users credit records
			mysqli_query($conn, "DELETE FROM sh_users_credit_records WHERE sl_id = $slip_id");
			
			if(mysqli_affected_rows($conn) > 0){
				if($debit == 'chips'){
			        //update chips balanc
			        mysqli_query($conn, "UPDATE users SET chips=chips - $winnings WHERE id=$user_id");
				} else {
					mysqli_query($conn, "UPDATE users SET promo=promo - $winnings WHERE id=$user_id");
				}
			 } else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'winning' WHERE slip_id = $slip_id");
			 die();
			}
			
			//insert to agent credit records AGENTS...........
		 if($utype == 'agent' && $debit == 'chips' && $type == 'back'){
			mysqli_query($conn, "DELETE FROM sh_agents_credit_records WHERE sl_id = '$slip_id' ");
			if(mysqli_affected_rows($conn) > 0){
			//update affiliate balance
			mysqli_query($conn, "UPDATE users SET afbal=afbal - $commission WHERE id=$aid AND type = 'agent'");
			mysqli_query($conn, "UPDATE users SET sabal=sabal - $sa_commission WHERE id=$said AND type = 'Sagent'");
			 } else {
			    echo 'Failed'; die();
			 }
			} 
			else if($utype == 'agent' && $debit == 'chips' && $type == 'sbook') {
			$aftr = $commission + $afbal;
			mysqli_query($conn, "DELETE FROM sh_agents_credit_records WHERE sl_id = '$slip_id'");
			if(mysqli_affected_rows($conn) > 0){
			//update affiliate balance
			mysqli_query($conn, "UPDATE users SET afbal=$aftr WHERE id=$aid AND type = 'agent'");
			mysqli_query($conn, "UPDATE users SET sabal=sabal + $sa_commission WHERE id=$said AND type = 'Sagent'");
			 } else {
			    echo 'Failed'; die();
			 }	
			}	
		  }	
		} //for lay/back
	} //foreach	
	
 } 
 
 
 
 //REVERT LOSING
 else if($stt == 'losing'){
	$sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'losing' AND bet_option_id = $option_id AND bet_option_name = '$option_name'";
	$result = $conn->query($sql);
	$rows = mysqli_fetch_all($result);
	foreach($rows as $row){
		echo $slip_id = $row[0];
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
		
		//get current balance of agent
		$uinfo = mysqli_query($conn, "SELECT type, chips, promo, afid, afbal, said FROM users WHERE id = $aid");
		$fasso = $uinfo->fetch_assoc();
		$utype = $fasso['type'];
		$chips = $fasso['chips'];
		$promo = $fasso['promo'];
		$afid =  $fasso['afid'];
		$afbal = $fasso['afbal'];
		$said = $fasso['said'];
		
		//get users balance data	
		$rkinfo = mysqli_query($conn, "SELECT stripe_cus, chips, promo FROM users WHERE id = $user_id");
		$usdt = $rkinfo->fetch_assoc();
		$uchips = $usdt['chips'];
		$upromo = $usdt['promo'];
		$curr = $usdt['stripe_cus'];
		
		//get currency value
		$agrate = getAcommission($curr, $conn);
		$agvalue = $agrate['rate'];
		$agcommission = $com; //$stake/$agvalue;
		
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
			$commission = $com * $excomi/100;;	
		} else {
			$commission = 0;
		}
		
		

		//for Lay bet
		
		if($type == 'lay'){
			//update awaiting
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'awaiting' WHERE slip_id = '$slip_id'");
			if (mysqli_affected_rows($conn) > 0){		
			//insert to users credit records
			mysqli_query($conn, "DELETE FROM sh_users_credit_records WHERE sl_id = '$slip_id'");
			if(mysqli_affected_rows($conn) > 0){
				if($debit == 'chips'){
			        //update chips balanc
			        mysqli_query($conn, "UPDATE users SET chips=chips - $winnings WHERE id='$user_id'");
				} else {
					mysqli_query($conn, "UPDATE users SET promo=promo - $winnings WHERE id='$user_id'");
				}
			} else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'losing' WHERE slip_id = '$slip_id'");
			 die();
			 }
			 
			//insert to agent credit records AGENTS...........
		  if($utype == 'agent' && $debit == 'chips'){
			mysqli_query($conn, "DELETE FROM sh_agents_credit_records WHERE sl_id = '$slip_id'");
			if(mysqli_affected_rows($conn) > 0){
			//update affiliate balance
			//mysqli_query($conn, "UPDATE users SET afbal=afbal - $commission WHERE id='$aid' AND type = 'agent'");
			 }
			}
		  } else {
			echo 'Failed';
			die();
		}	
		
/////////////for back/sbook//////////////////////////

	    } else {
			$lab = $ab - $winnings;
		//update awaiting to settled in back or sbook
		$ca = mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'awaiting' WHERE slip_id = $slip_id");
		mysqli_query($conn, "DELETE FROM sh_sf_tickets_history WHERE slip_id = $slip_id");
		echo $conn->error;
			if (mysqli_affected_rows($conn) > 0){			
			//insert to users credit records
			mysqli_query($conn, "DELETE FROM sh_users_credit_records WHERE sl_id = $slip_id");
			if(mysqli_affected_rows($conn) > 0){
				echo 'done!';
			 } else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'losing' WHERE slip_id = $slip_id");
			 die();
			}
			
			//insert to agent credit records AGENTS...........
		 if($utype == 'agent' && $debit == 'chips' && $type == 'back'){
			mysqli_query($conn, "DELETE FROM sh_agents_credit_records WHERE sl_id = $slip_id ");
			} 
			else if($utype == 'agent' && $debit == 'chips' && $type == 'sbook') {
			mysqli_query($conn, "DELETE FROM sh_agents_credit_records WHERE sl_id = $slip_id");
			if(mysqli_affected_rows($conn) > 0){
			//update affiliate balance
			mysqli_query($conn, "UPDATE users SET afbal=afbal - $commission WHERE id=$aid AND type = 'agent'");
			mysqli_query($conn, "UPDATE users SET sabal=sabal - $sa_commission WHERE id=$said AND type = 'Sagent'");
			 } else {
			    echo 'Failed'; die();
			 }	
			}	
		  }	
		} //for lay/back
	} //foreach	
	
 } 
 
 
 
 
 
 
 //REVERT CANCELED
 else if($stt == 'canceled'){
	 $sql = "SELECT * from sh_sf_tickets_records WHERE status = 'canceled' AND bet_option_id = $option_id AND bet_option_name = '$option_name'";
	$result = $conn->query($sql);
	$rows = mysqli_fetch_all($result);
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
		
		//get current balance of agent
		$uinfo = mysqli_query($conn, "SELECT type, chips, promo, afid, afbal FROM users WHERE id = '$aid'");
		$fasso = $uinfo->fetch_assoc();
		$utype = $fasso['type'];
		$chips = $fasso['chips'];
		$promo = $fasso['promo'];
		$afid =  $fasso['afid'];
		$afbal = $fasso['afbal'];
		
		//get users balance data	
		$rkinfo = mysqli_query($conn, "SELECT chips, promo FROM users WHERE id = '$user_id'");
		$usdt = $rkinfo->fetch_assoc();
		$uchips = $usdt['chips'];
		$upromo = $usdt['promo'];
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
			$commission = $com * $excomi/100;
		} else if(($type == 'sbook') && ($user_id != $afid)){
			$commission = $com * $spcomi/100;
		} else if (($type == 'lay') && ($user_id != $afid)){
			$commission = $com * $excomi/100;;	
		} else {
			$commission = 0;
		}
		
		

		//for Lay bet
		
		if($type == 'lay'){
			//update awaiting
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'awaiting' WHERE slip_id = $slip_id");
			if (mysqli_affected_rows($conn) > 0){		
			//insert to users credit records
			mysqli_query($conn, "DELETE FROM sh_users_credit_records WHERE sl_id = $slip_id");
			if(mysqli_affected_rows($conn) > 0){
				if($debit == 'chips'){
			        //update chips balanc
			        mysqli_query($conn, "UPDATE users SET chips=chips - $stake WHERE id='$user_id'");
				} else {
					mysqli_query($conn, "UPDATE users SET promo=promo - $stake WHERE id='$user_id'");
				}
			} else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_history SET status = 'canceled' WHERE slip_id = '$slip_id'");
			 die();
			 } 
		  } else {
			echo 'Failed'; die();
		}	
		
/////////////for back/sbook//////////////////////////
    } else {
		
		//update awaiting to settled in back or sbook
		$ca = mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'awaiting' WHERE slip_id = $slip_id");
		mysqli_query($conn, "DELETE FROM sh_sf_tickets_history WHERE slip_id = $slip_id");
			if (mysqli_affected_rows($conn) > 0){			
			//insert to users credit records
			mysqli_query($conn, "DELETE FROM sh_users_credit_records WHERE sl_id = $slip_id");
			if(mysqli_affected_rows($conn) > 0){
				if($debit == 'chips'){
			        //update chips balanc
			        mysqli_query($conn, "UPDATE users SET chips=chips - $stake WHERE id=$user_id");
				} else {
					mysqli_query($conn, "UPDATE users SET promo=promo - $stake WHERE id=$user_id");
				}
			 } else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'canceled' WHERE slip_id = $slip_id");
			 die();
			}
				
		  }	
		} //for lay/back
	} //foreach	
	
 }
 
 
	
} //if isset

