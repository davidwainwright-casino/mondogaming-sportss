<?php include('../db.php');


$slipid = $_POST['slip_id'];
$spid = $_POST['spid'];
$usid = $_POST['usid'];
$odd=trim($_POST['odd']);  
$tkn=mysqli_fetch_assoc(mysqli_query($conn,"SELECT serial FROM `www_token`"));$token = $tkn['serial'];


//get USD value for agents
function getExchange($currency, $cxch, $conn){
	$gv=mysqli_fetch_assoc(mysqli_query($conn,"SELECT rate FROM `currencies` WHERE name='$currency'"));
	$grate = $gv['rate'];
	return $frate = $cxch/$grate;
	
}

	//bet365 validation api
	
		//function to check validity of cricket odd
	function isValidOddCricket($event_id,$option_id,$option_name,$odd,$spid,$token,$conn){
        $url = 'https://api.betsapi.com/v1/bet365/event?token='.$token.'&FI='.$event_id.'';
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
								$coption_name = trim($okk.' '.$jna.' '.$okhd);  
								$odd = explode('/', $j['OD']);
	                                    $odd = round(($odd[0] / $odd[1]), 2) + 1;
										$apiodd = round($odd,2);
										if($j['ID'] == $option_id){
									    //echo $option_name;echo '--'; echo $coption_name;
										if($option_name==$coption_name){
											if($apiodd==$odd){
												return true;
												}
											}
										  }
										}
									}
								}
				
				/*
                if ($j['type'] == 'PA') {
                    if ($j['ID'] != 0) {
                        if ($j['SU'] == '0') {
                            //if (isset($j['NA'])) {
                                //if (!empty($j['NA'])) {
                                    $odd = explode('/', $j['OD']);
                                    $odd = $odd[0] / $odd[1] + 1;
									$apiodd = round($odd,2);
                                    if($j['ID'] == $_option_id){
										var_dump($apiodd); echo '|||'; var_dump($_odd);
										//if($j['NA']==$_option_name){
											if($apiodd==$_odd){
												return true;
											}
										//}
									}
  									// 'su' => $j['SU']
                                //}
                            //}
                        } 
                    }
                }
				*/
                
            }
        }
        return false;
    }
	

//function to validate from bwin api before submission
//validate bwin api
	
	function isValidOdd($event_id,$option_id,$option_name,$odd,$spid,$token,$conn){
		if($spid==3){
			return isValidOddCricket($event_id,$option_id,$option_name,$odd,$spid,$token,$conn);
		} 
        $url="https://api.b365api.com/v1/bwin/event?token=".$token."&event_id=".$event_id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch) or die(curl_error($ch));
        if ($data === false) {
            echo 'Event Suspended';
        }
        curl_close($ch);
        $json1 = $data; 
        $a = explode('string', $json1);
        for($k =0; $k<count($a); $k++){
            $bet_event_cat_sort_order = 1;
            $bet_option_sort_order = 1;
            $w = trim($a[$k]);
            if(count($a) > 1){
             $w = strstr($w, '"');
            }
            $json = trim($w, '"');
            $obj = json_decode($json, true);
            $b = $obj['results'];
            for($i =0; $i<count($b); $i++){
                $sport_id = $b[$i]['SportId'];
                $event_id = $b[$i]['LeagueId'];
                $cc = $b[$i]['RegionName'];;
                $deadline = $b[$i]['updated_at'];
                $event_name = $b[$i]['HomeTeam']." - ".$b[$i]['AwayTeam'];      
                $c = $b[$i]['Markets'];
                for($j =0; $j<count($c); $j++){
                    $bet_option_sort_order = 1;
                    $bet_event_cat_name = $c[$j]['name']['value'];
                      $bet_event_cat_id = $c[$j]['id']; 
                      $cat_id=$c[$j]['id'];
                      $d = $c[$j]['results'];
                      $bet_option_order = 1;
					  $cvisibility=$c[$j]['visibility'];
					  if($cvisibility !='Suspended'){
                      foreach ($d as $e) {
                        if($e['id']==$option_id){
                          if($e['odds']==$odd){
                          	if($e['visibility']=="Visible"){
                          		return true;
                          	}
                          }
                        }
                 }
				}
            
          };
      } 
    };

    return false;
}
	

//first get other details from database for validation
$shsl = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_tickets_records WHERE slip_id = $slipid AND status = 'awaiting' AND bet_info IS NULL AND type = 'sbook'"));
$slip_id = $shsl['slip_id'];
$user_id = $shsl['user_id'];
$stake = $shsl['stake'];
$winnings = $shsl['winnings'];
$dds = $shsl['date'];
$event_id = $shsl['event_id'];
$event_name = $shsl['event_name'];
$cat_id = $shsl['cat_id'];
$cat_name = $shsl['cat_name'];
$option_id = trim($shsl['bet_option_id']);
$option_name = trim($shsl['bet_option_name']);
$aid = $shsl['aid'];
$b_type = $shsl['type'];
$spid = $shsl['sp'];
$debit = $shsl['debit'];

//for update to agent_credit records
$info = array("event_id" => $event_id, "event_name" => $event_name, "cat_id" => $cat_id, "cat_name" => $cat_name, "bet_option_id" => $option_id, "bet_option_name" => $option_name);
$bet_info = Serialize($info);

	
	
if(isValidOdd($event_id,$option_id,$option_name,$odd,$spid,$token,$conn)){
if($b_type == 'sbook'){
$stake = $stake;
$ult = $winnings/$odd; $nt = $ult * 20/100; $gtotal = $ult - $nt;
$win = round($gtotal,2);
$dt = time();

//get balance of user
$usdt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT type, stripe_cus, chips, promo, afid,said FROM users WHERE id = $user_id"));
  $utype = $usdt['type'];
  $currency = $usdt['stripe_cus'];
  $aid = $usdt['afid'];
  $said = $usdt['said'];
  //after or before balance records
 if($debit=='chips'){
  $ubal = $usdt['chips'];
  $af_bal = round($ubal + $win,2);
 }else{
  $upromo = $usdt['promo'];	 
  $af_bal = round($upromo + $win,2);
 }
 

$updat = mysqli_query($conn,"UPDATE sh_sf_tickets_records SET status = 'winning', date=$dt WHERE slip_id=$slip_id");

//for users credit records
if(mysqli_affected_rows($conn) > 0){
 mysqli_query($conn, "INSERT INTO sh_sf_tickets_history SELECT * FROM sh_sf_tickets_records WHERE slip_id=$slip_id");
 if(mysqli_affected_rows($conn) > 0){
	 if($debit == 'chips'){
	 //update chips balance
	 mysqli_query($conn,"UPDATE users SET chips = chips + ".$win." WHERE id=$user_id");
	 } else {
	 mysqli_query($conn,"UPDATE users SET promo = promo + ".$win." WHERE id=$user_id");
	 
	 if(mysqli_affected_rows($conn) > 0){
		 echo '<i id="ccssd" class="icon check all"></i> Done! '.$win.''; die();
	 }else{
		 mysqli_query($conn,"UPDATE sh_sf_tickets_records SET status = 'awaiting' WHERE slip_id=$slip_id"); 
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
		$bxch = $stake - $win;
		$netw = getExchange($currency, $bxch, $conn);
		//$netw = $stake - $win;
		
		
	    //$netwin = $win - $stake;
		$commission = $netw * $spcomi/100;
		$sa_commission = $commission * $spcomi/100;
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
		mysqli_query($conn, "INSERT INTO `sh_agents_credit_records` (`sl_id`, `agent_id`, `bet_info`, `odd`, `stake`, `ab`, `amt`, `af`, `status`, `type`, `debit`, `affu_id`, `dt`, `st`) VALUES ('$slip_id', '$aid', '$bet_info', '$odd', '$stake', '$afbal', '$commission', '$ag_after', '$ccom', '$b_type', '$debit', '$user_id', '$dt', 'so')");
		//echo $conn->error;
		
		//update balance of both agent and sa agent
		if($aid > 1){
			mysqli_query($conn,"UPDATE users SET afbal = afbal + ".$commission." WHERE id=$aid");
			if($said > 1){
			mysqli_query($conn,"UPDATE users SET sabal = sabal + ".$sa_commission." WHERE id=$said");
			}
			echo '<i id="ccssd" class="icon check all"></i> Done! '.$win.'';
		}else{
			echo '<i id="ccssd" class="icon check all"></i> Done! '.$win.'';
			//echo $conn->error;
		}
	
	
	}else{ //no aid
	 echo '<i id="ccssd" class="icon check all"></i> Done!'.$win.'';
	 die();
	}
	
 ///////}
 
 
	
}else{ //for agent credit records 
   //mysqli_query($conn,"DELETE FROM sh_users_credit_records WHERE sl_id=$kslip_id");
	echo 'suspended';
	//echo $conn->error;
  }
  
 }else{ //second update
	 echo 'Suspended';
	 mysqli_query($conn,"DELETE sh_sf_tickets_history WHERE slip_id=$slip_id");
	 //echo $conn->error;
 }

}else{ //for first update
echo 'suspended';	
mysqli_query($conn,"UPDATE sh_sf_tickets_records SET status='awaiting' WHERE slip_id=$slip_id");
//echo $conn->error;
 }
 
  }//if it's back type
 }else{
	 echo 'Suspended';
	 //isValidOddCricket($event_id,$option_id,$option_name,$odd,$spid,$token,$conn);
	 //echo $conn->error;
	 die();
 }	 //end odd validation

?>