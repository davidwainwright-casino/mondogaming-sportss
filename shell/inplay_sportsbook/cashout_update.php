<?php include('../db.php');error_reporting(0);

	$usid=$_POST['usid'];
	if(empty($usid)){
		die();
	}
	
   function getOdd($boi, $bon, $conn){  
	$query="SELECT bet_option_odd FROM af_inplay_bet_options WHERE bet_option_id = $boi AND bet_option_name='$bon'";
	$edata=mysqli_query($conn,$query);
	$ob = mysqli_fetch_assoc($edata);
	$odo = $ob['bet_option_odd'];
	return $odo;
	}

$query="SELECT * FROM sh_sf_tickets_records WHERE user_id=$usid AND status='awaiting' AND bet_info IS NULL AND type='sbook'";
		$event_data=mysqli_query($conn,$query);
		$data = array();
		while($row=mysqli_fetch_assoc($event_data)){
			$winnings = $row['winnings'];
			$boi = $row['bet_option_id'];
			$bon = $row['bet_option_name'];
			$opodd = getOdd($boi,$bon, $conn);
	
			$gcash = $winnings/$opodd; $gcal = $gcash * 20/100; $gtotal = $gcash - $gcal;
			$win = round($gtotal,2);
			$data[$row['slip_id']] = $win;
		}

		$cash_data= $data;
		echo json_encode(["cash_data"=>$cash_data]);
		
	

?>