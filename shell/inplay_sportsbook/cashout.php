<?php include('../db.php');


$slid = $_POST['slid'];
$rc = explode('-', $slid);
$slipid = $rc[1];


//function to validate from bwin api before submission
	function isValidOddCricket($stake, $bet_event_id,$winnings,$_option_id,$_option_name,$_odd,$b_type,$aid,$conn){
		$tdata=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `www_token`"));
		$tk= $tdata['serial'];
		$url = 'https://api.betsapi.com/v1/bet365/event?token=2645-9zwZs0KT0m3e5L&FI=' . $bet_event_id.'';
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch) or die(curl_error($ch));
        if ($data === false) {
            $info = curl_getinfo($ch);
            curl_close($ch);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($ch);
        $result = json_decode($data, true);
		//make sure it's updated no less than 1 minute..
		$tmt = time() - 90;
		$rek = $result['stats']['update_at'];
		$last_update = $rek;
		if($tmt > $rek){
		return false;
		}
		
        if (isset($result['results'])){
            foreach ($result['results'][0] as $j) {
                if ($j['type'] == 'PA' || $j['type'] == 'MA') {
						if ($j['type'] == 'MA') {
							$okk = $j['NA'];
							$jna = $j['HA'];
						}
						 if ($j['type'] == 'PA' && $j['HA'] == '') {
							$jna = $j['NA'];
							$okhd = $j['HD'];
						}
						else if($j['type'] == 'PA' && $j['HA'] !== '' && !empty($j['NA'])){
							$jna = $j['NA'];
							 
						}else{
							$jna = $j['HA'].' '.$j['NA'];
						}
						if(!empty( $j['ID'])){
							if($j['SU']=='0'){
								$option_name = trim($okk.' '.$jna.' '.$okhd);  
								$odd = explode('/', $j['OD']);
	                                    $odd = round(($odd[0] / $odd[1]), 2) + 1;
										$apiodd = round($odd,2);
										if($j['ID'] == $_option_id){
									    //echo $option_name; echo '--'; echo $_option_name;
										if($option_name==$_option_name){
											if($apiodd==$_odd){
												return true;
												}
											}
										  }
										}
									}
								}
                
				/* if ($j['type'] == 'PA') {
                    if ($j['ID'] != 0) {
                        if ($j['SU'] == '0') {
                            if (isset($j['NA'])) {
                                if (!empty($j['NA'])) {
                                    $odd = explode('/', $j['OD']);
                                    $odd = round(($odd[0] / $odd[1]), 2) + 1;
                                    if($j['ID'] == $_option_id){
										if($j['NA']==$_option_name){
											if($odd == $_odd){
												return true;
											}
										}
									}
  									// 'su' => $j['SU']
                                }
                            }
                        } 
                    }
                }*/
                
            }
        }
        return false;
	}

//function to validate from bwin api before submission
	function isValidOdd($stake, $bet_event_id,$winnings,$_option_id,$_option_name,$_odd,$b_type,$sport_id,$aid,$conn){
		if($sport_id==3){
			return isValidOddCricket($stake,$bet_event_id,$winnings,$_option_id,$_option_name,$_odd,$b_type,$aid,$conn);
		}
		$tdata=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `www_token`"));
		$tk= $tdata['serial'];
		$url = "https://api.betsapi.com/v1/bwin/event?token=" . $tk . "&event_id=" . $bet_event_id;
		$ch  = curl_init($url);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch) or die(curl_error($ch));
		if ($data === false) {
		    return false;
		}
		$json=json_decode($data, true);
		curl_close($ch);
		$event_market=$json['results'][0]['Markets'];
		foreach ($event_market as $cat_key => $category) {
			foreach ($category['results'] as $option_key => $option_value) {
				if($option_value['id'] == $_option_id){
					$oname=$option_value['name']['value'];
		            $bet_option_name = str_replace("'", "''", $oname);
					if($bet_option_name==$_option_name){
						$option_value['odds']." : ".$_odd;
						$bet_option_name." : ".$_option_name;
						$option_value['id']." : ".$_option_id;
						if($option_value['odds'] == $_odd){
							if($option_value['visibility']=="Visible"){
							return true;
							}
						}
					}
				}
			}
		}
		return false;
	}
	

$shsl = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_tickets_records WHERE slip_id = $slipid AND status = 'awaiting' AND bet_info IS NULL AND type = 'sbook'"));
$slip_id = $shsl['slip_id'];
$user_id = $shsl['user_id'];
$stake = $shsl['stake'];
$winnings = $shsl['winnings'];
$dds = $shsl['date'];
$bet_event_id = $shsl['event_id'];
$event_name = $shsl['event_name'];
$cat_id = $shsl['cat_id'];
$cat_name = $shsl['cat_name'];
$_option_id = $shsl['bet_option_id'];
$_option_name = $shsl['bet_option_name'];
$_odd = $shsl['odd'];
$aid = $shsl['aid'];
$b_type = $shsl['type'];
$sport_id = $shsl['spid'];
$debit = $shsl['debit'];

$sql = "SELECT * from sh_sf_tickets_history WHERE user_id=$user_id AND bet_info IS NULL AND bet_option_id = $_option_id AND bet_option_name = '$_option_name' AND status = 'awaiting' AND type = 'sbook'";
	$result = $conn->query($sql);
	$rows = mysqli_fetch_all($result);
	//var_dump($rows);
//var_dump($rows);
foreach($rows as $shsl){
$slip_id = $shsl['0'];
$kslip_id = $shsl['0'];
$user_id = $shsl['1'];
$status = $shsl['2'];
$stake = $shsl['3'];
$winnings = $shsl['4'];
$bet_event_id = $shsl['9'];
$event_name = $shsl['10'];
$cat_id = $shsl['12'];
$cat_name = $shsl['11'];
$_option_id = $shsl['13'];
$_option_name = $shsl['14'];
$_odd = $shsl['7'];
$kkodd = $shsl['7'];
$aid = $shsl['15'];
$b_type = $shsl['16'];
$sport_id = $shsl['17'];
$debit = $shsl['18'];
		
		
//pack events details for serilaization
$info = array(
			"event_id" => $bet_event_id, "event_name" => $event_name, "cat_id" => $cat_id, "cat_name" => $cat_name, "bet_option_id" => $_option_id, "bet_option_name" => $_option_name
			);
		$bet_info = Serialize($info);

$query="SELECT bet_option_odd FROM af_inplay_bet_options WHERE bet_option_id = '$_option_id' AND bet_option_name = '$_option_name'";
$sqr = mysqli_query($conn,$query);
$casho = $sqr->fetch_assoc();
$_odd = $casho['bet_option_odd'];


	
	
if(isValidOdd($stake, $bet_event_id,$winnings,$_option_id,$_option_name,$_odd,$b_type,$sport_id,$aid,$conn)){	

if($b_type == 'sbook'){
	$stake = $stake;
$ult = $winnings/$_odd; $nt = $ult * 20/100; $gtotal = $ult - $nt;
//$gcash = $winnings/$_odd; $gcal = $gcash * 20/100; $gtotal = $gcash - $gcal;
$win = round($gtotal,2);


$dt = time();

//get balance of user
$usdt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT type, chips, promo, afid,said FROM users WHERE id = $user_id"));
  $utype = $usdt['type'];
   $aid = $usdt['afid'];
  $said = $usdt['said'];
 if($debit=='chips'){
  $ubal = $usdt['chips'];
  $af_bal = round($ubal + $win,2);
 }else{
  $upromo = $usdt['promo'];	 
  $af_bal = round($upromo + $win,2);
 }
 

$updat = mysqli_query($conn,"UPDATE sh_sf_tickets_history SET status = 'winning', winnings = '$win', odd='$_odd',sodd='$_odd', st='co' WHERE user_id=$user_id AND slip_id=$kslip_id AND status = 'awaiting'");

//for users credit records
if(mysqli_affected_rows($conn) > 0){
 $cc = mysqli_query($conn, "INSERT INTO `sh_users_credit_records` (`sl_id`, `u_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `aid`, `dt`, `st`) VALUES ('$kslip_id', '$user_id', '$bet_info', '$_odd', '$stake', '$ubal', '$win', '$af_bal', 'winning', '$b_type', '$debit', '$aid', '$dt', 'co')");
 //echo $conn->error;
 if(mysqli_affected_rows($conn) > 0){
	 if($debit == 'chips'){
	 //update chips balance
	 mysqli_query($conn,"UPDATE users SET chips = chips + ".$win." WHERE id=$user_id");
	 } else {
	 mysqli_query($conn,"UPDATE users SET promo = promo + ".$win." WHERE id=$user_id");
	 if(mysqli_affected_rows($conn) > 0){
		 echo '<i class="icon check all"></i> Done! '.$win.''; die();
	 }else{
		 mysqli_query($conn,"UPDATE sh_sf_tickets_history SET status = 'awaiting', winnings = '$winnings', odd='$kkodd',sodd='$kkodd', st='canco' WHERE slip_id=$kslip_id"); 
	 }
	}
	
	//for agents credit records
	if(mysqli_affected_rows($conn) > 0){

		//if not empty means he is not agent and is registered under agent which agent will need to be credited	
		if(!empty($aid)){
		$rk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM risk_management"));
		$excomi = $rk['ex_comi'];
		$spcomi = $rk['sp_comi'];
		$sacomi = $rk['ex_sagents'];
		$netw = $stake - $win;
		
		
	    //$netwin = $win - $stake;
		$commission = $netw * $excomi/100;
		$sa_commission = $commission * $sacomi/100;
			//get balance of agent
		$fasso = mysqli_fetch_assoc(mysqli_query($conn, "SELECT type, chips, promo, afid, afbal, said FROM users WHERE id = $aid"));
		$utype = $fasso['type'];
		$afbal = $fasso['afbal'];
		$ag_after = round($afbal + $commission,2);
		$said = $fasso['said'];
		if($commission < 0){
			$ccom = 'Debit';
		}else{
			$ccom = 'Credit';
		}
		mysqli_query($conn, "INSERT INTO `sh_agents_credit_records` (`sl_id`, `agent_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `affu_id`, `dt`, `st`) VALUES ('$slip_id', '$aid', '$bet_info', '$_odd', '$stake', '$afbal', '$commission', '$ag_after', '$ccom', '$b_type', '$debit', '$user_id', '$dt', 'so')");
		//echo $conn->error;
		
		//update balance of both agent and sa agent
		if(mysqli_affected_rows($conn) > 0){
			mysqli_query($conn,"UPDATE users SET afbal = afbal + ".$commission." WHERE id=$aid");
			if(!empty($said)){
				//get balance of super agent
				mysqli_query($conn,"UPDATE users SET sabal = sabal + ".$sa_commission." WHERE id=$said");
				//echo $sa_commission;
				//var_dump($okjanu);
				if(mysqli_affected_rows($conn) > 0){
				 echo '<i class="icon check all"></i> Done! '.$win.'';
				 mysqli_query($conn,"DELETE FROM sh_sf_tickets_records WHERE user_id=$user_id AND bet_option_id=$_option_id AND bet_option_name='$_option_name' AND type='sbook'"); 
				}else{
					echo 'Suspended';
					//echo $conn->error;
				}
				
				
			}else{
			echo '<i class="icon check all"></i> Done! '.$win.'';
			mysqli_query($conn,"DELETE FROM sh_sf_tickets_records WHERE user_id=$user_id AND bet_option_id=$_option_id AND bet_option_name='$_option_name' AND type='sbook'"); 
			
			}
		}else{
			echo 'Suspended';
			//echo $conn->error;
		}
	
	
	}else{ //no aid
	 echo '<i class="icon check all"></i> Done!'.$win.'';
	 mysqli_query($conn,"DELETE FROM sh_sf_tickets_records WHERE user_id=$user_id AND bet_option_id=$_option_id AND bet_option_name='$_option_name' AND type='sbook'"); 
	 die();
	}
	
 ///////}
 
 
	
}else{ //for agent credit records 
   //mysqli_query($conn,"DELETE FROM sh_users_credit_records WHERE sl_id=$kslip_id");
	echo 'suspended';
  }
  
 }else{ //second update
	 echo 'Suspended';
	 mysqli_query($conn,"UPDATE sh_sf_tickets_history SET status = 'awaiting', winnings = '0.00', odd='$_odd', st='canco' WHERE user_id=$user_id AND slip_id=$kslip_id");
 }	 
}else{ //for first update
echo 'suspended';	
//echo $conn->error;
 }
 
  }//if it's back type
 }else{
	 echo 'Suspended';
	 die();
 }	 //end odd validation
}
?>