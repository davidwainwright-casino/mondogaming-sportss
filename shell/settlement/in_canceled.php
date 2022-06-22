<?php header('Access-Control-Allow-Origin: *'); error_reporting(0);
//if (isset($_GET['winid']) && $_GET['winid']!="") {
if(isset($_POST['method']) && $_POST['method'] == 'updateStatus'){
 include('../db.php');
 $comm = mysqli_query($conn, "SELECT ex_comi, sp_comi FROM `risk_management` ");
 $c_row = $comm->fetch_assoc();
 $excomi = $c_row['ex_comi'];
 $spcomi = $c_row['sp_comi'];
 
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
		$uinfo = mysqli_query($conn, "SELECT type, chips, promo, afid FROM users WHERE id = '$user_id'");
		$fasso = $uinfo->fetch_assoc();
		$utype = $fasso['type'];
		$chips = $fasso['chips'];
		$promo = $fasso['promo'];
		$afid =  $fasso['afid'];
		$afbal = $fasso['afbal'];
		
		
		if($debit == 'chips'){
		//amount before/after
		 $ab = $chips;
		 $af = $chips + $stake;
		} else {
		 $ab = $promo;
		 $af = $promo + $stake;	
		}
		
		
		

		     //for chips
			//update awaiting to settled in lay
			$ca = mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'canceled' WHERE slip_id = $slip_id AND status = 'awaiting'");
			
			if (mysqli_affected_rows($conn) > 0){
				//insert to history table
			mysqli_query($conn, "INSERT INTO sh_sf_tickets_history SELECT * FROM sh_sf_tickets_records WHERE slip_id=$slip_id");
				
			//insert to users credit records
			
			$cc = mysqli_query($conn, "INSERT INTO `sh_users_credit_records` (`sl_id`, `u_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `aid`, `dt`) VALUES ('$slip_id', '$user_id', '$bet_info', '$odd', '$stake', '$ab', '$stake', '$af', 'canceled', '$type', '$debit', '$aid', '$dt')");
			if(mysqli_affected_rows($conn) > 0){
				echo 'Done!';
				//mysqli_query($conn, "DELETE FROM sh_sf_tickets_records WHERE user_id=$user_id AND bet_info IS NULL AND bet_option_id = $option_id AND bet_option_name = '$option_name' AND status = 'awaiting'");
				if($debit == 'chips'){
			        //update chips balance
			        mysqli_query($conn, "UPDATE users SET chips=chips + $stake WHERE id=$user_id");
				} else {
					mysqli_query($conn, "UPDATE users SET promo=promo + $stake WHERE id=$user_id");
				}
			} else {
			mysqli_query($conn, "UPDATE sh_sf_tickets_records SET status = 'awaiting' WHERE slip_id = $slip_id");
			 echo 'Failed';
			 die();
			 }
	
		} else {
			echo 'Failed';
			die();
		}
		

		
		

			
	} //foreach		
} //if isset

