<?php error_reporting(0);
	include_once('../db.php');
	
    $epostid = $_POST['event_id'];
	$user_id = $_POST['user_id'];
	if(empty($epostid)){
		//die();
	}

	//function format update slip back
	function gformat($brow){	 
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	  $decimal_odd = $brow;
	  if (2 > $decimal_odd) {
                $plus_minus = '-';
                $result = 100 / ($decimal_odd - 1);  
            } else {              
                $plus_minus = '+';
                $result = ($decimal_odd - 1) * 100;
            }       
            return ($plus_minus . round($result, 2));
     }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $brow;
	  if (2 == $decimal_odd) {
                return '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    return ($dividend . '/' . $divisor);
                }
            }           
            return ($dividend . '/' . $divisor);
	  
  }else{ 
  return round($brow,2);
  }
};

//function format update slip back
	function nbgetOdd($xbod){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	  $decimal_odd = $xbod;
	  if (2 > $decimal_odd) {
                $plus_minus = '-';
                $result = 100 / ($decimal_odd - 1);  
            } else {              
                $plus_minus = '+';
                $result = ($decimal_odd - 1) * 100;
            }       
            return ($plus_minus . round($result, 2));
     }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $xbod;
	  if (2 == $decimal_odd) {
                return '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    return ($dividend . '/' . $divisor);
                }
            }           
            return ($dividend . '/' . $divisor);
	  
  }else{ 
  return $xbod;
  }
};
	 
//function format update slip laybet
	function lformat($lrow){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	   $decimal_oddlay = $lrow;//
	    if (2 > $decimal_oddlay) {
                $plus_minusl = '-';
                $resultl = 100 / ($decimal_oddlay - 1);  
            } else {              
                $plus_minusl = '+';
                $resultl = ($decimal_oddlay - 1) * 100;
            }       
            return ($plus_minusl . round($resultl, 2));
			
     }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $lrow;//
	  if (2 == $decimal_odd) {
                return '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    return ($dividend . '/' . $divisor);
                }
            }           
            return ($dividend . '/' . $divisor);
	  
  }else{
  return round($lrow,2);
  }
};
//function format update slip laybet
	function nlgetOdd($xlod){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	   $decimal_oddlay = $xlod;
	    if (2 > $decimal_oddlay) {
                $plus_minusl = '-';
                $resultl = 100 / ($decimal_oddlay - 1);  
            } else {              
                $plus_minusl = '+';
                $resultl = ($decimal_oddlay - 1) * 100;
            }       
            return ($plus_minusl . round($resultl, 2));
			
     }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $xlod;
	  if (2 == $decimal_odd) {
                return '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    return ($dividend . '/' . $divisor);
                }
            }           
            return ($dividend . '/' . $divisor);
	  
  }else{
	  
  return $xlod;
  }
}
		$query="select bet_option_id, bet_option_name, bet_option_odd FROM af_inplay_bet_options WHERE bet_event_cat_id IN(SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE bet_event_id = $epostid)";
		$event_data=mysqli_query($conn,$query);
		$data = array();
		$odata = array();
		while($row=mysqli_fetch_assoc($event_data)){
			$brow = $row['bet_option_odd'];
			if($brow < 1){
				$lrow = $row['bet_option_odd'];
			}else{
				$lrow = $row['bet_option_odd']+0.02;
			}
			
			$xbod = $brow - 0.01;
			$xlod = $lrow + 0.01;
			$data[$row['bet_option_id']] = array(round(gformat($brow),2), round(lformat($lrow),2));
			$xdata[$row['bet_option_id']] = array(round(nbgetOdd($xbod),2), round(nlgetOdd($xlod),2));
			$odata[$row['bet_option_id']] = $row['bet_option_name'];
			//$data[$row['bet_option_id']] = round($row['bet_option_odd']+0.02, 2);
		}









     	
	
 //function to get odd value of bet option
	function getOddFromDatabase($odd_id,$odd_name,$cat_name,$sp, $conn){
		if($sp=="3"){
		$qrs="SELECT bet_option_odd FROM af_inplay_bet_options WHERE bet_option_id=$odd_id AND bet_option_name='$odd_name' AND cat_name_in_option='$cat_name'";
		}else{
		$qrs="SELECT bet_option_odd FROM af_inplay_bet_options WHERE bet_option_id=$odd_id AND bet_option_name='$odd_name'";
		}
		$wc=mysqli_query($conn,$qrs);
		$ds=mysqli_fetch_assoc($wc);
		$odd = $ds['bet_option_odd'];
		return $odd;
	}	
	
	
	//for updating slip data
	//function to get unsubmitted slip of user (each can have max one unsubmitted slip)
	function getUnSubmittedSlip($user_id,$conn){
		$query="SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='unsubmitted' AND type <> 'sbook'";
		$unsubmitted_slip=mysqli_query($conn,$query);
		$data=mysqli_fetch_assoc($unsubmitted_slip);
		if(isset($data['odd'])){
			//for multibet
			if($data['bet_info']){
				$unserialized_data=unserialize($data['bet_info']);
				$temp_array=array();
				foreach ($unserialized_data as $key => $value) {
					$obj=$value;
					$odd = getOddFromDatabase($value['bet_option_id'],$value['bet_option_name'],$value['cat_name'],$value['sp'],$conn);
					if($odd){
							$nodd= gformat($odd);
							$unserialized_data[$key]['odd']=$odd;
							$unserialized_data[$key]['sodd']=$nodd;
							$obj['sodd']=$nodd;
							$obj['odd']=$odd;
							array_push($temp_array, $obj);

					}else{
		                    $fodd=0.00;
						    $sodd= 0.00;
							$unserialized_data[$key]['odd']=$fodd;
							$unserialized_data[$key]['sodd']=$sodd;
							$obj['sodd']=$sodd;
							$obj['odd']=$fodd;
							array_push($temp_array, $obj);;
						
					}
				}
				$data['bet_info']=serialize($unserialized_data);
				$d=Serialize($temp_array);
				mysqli_query($conn, "UPDATE sh_sf_tickets_records SET bet_info='$d' WHERE user_id=$user_id AND status = 'unsubmitted' AND type <> 'sbook'");
			
			}
			//for single bet
			else{
				$odd = getOddFromDatabase($data['bet_option_id'],$data['bet_option_name'],$data['cat_name'],$data['sp'], $conn);
				if($odd){
					if($data['type']=="lay"){
						$fodd = $odd + 0.02;
						$nodd= lformat($fodd);
						$data['sodd'] = $nodd;
						$data['odd']= $odd;
						$lbsodd = $fodd;
						mysqli_query($conn, "UPDATE sh_sf_tickets_records SET odd='$lbsodd', sodd='$nodd' WHERE user_id=$user_id AND status = 'unsubmitted' AND type <> 'sbook'");
						
					}else{
						$nodd= gformat($odd);
						$data['sodd'] = $nodd;
						$data['odd'] = $odd;
						mysqli_query($conn, "UPDATE sh_sf_tickets_records SET odd='$odd', sodd='$nodd' WHERE user_id=$user_id AND status = 'unsubmitted' AND type <> 'sbook'");
					}
				}else{
					mysqli_query($conn, "UPDATE sh_sf_tickets_records SET odd='0', sodd='0' WHERE user_id=$user_id AND status = 'unsubmitted' AND type <> 'sbook'");
					
					//mysqli_query($conn, "DELETE FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'unsubmitted'");
				}				
			}
		}
		return $data;
	}














	function getSubmittedSlipWinnings($user_id,$bet_event_id,$conn){
		$slip = "SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='awaiting' AND event_id = $bet_event_id AND bet_info IS NULL AND type <> 'sbook'";
		$shslips = mysqli_query($conn,$slip);
		$avaiting_odd_list_back=array();
		$avaiting_odd_list_lay=array();
		while($shsl = $shslips->fetch_assoc()){
            $winnings = $shsl['winnings'];
			$option_id = $shsl['bet_option_id'];
			$option_name = $shsl['bet_option_name'];
			$bet_info = $shsl['bet_info'];
			$event_id = $shsl['event_id'];
			$type = $shsl['type'];
			$okk = $shsl['bet_option_name'].$shsl['bet_option_id'];
              if($type == "back"){
                  $avaiting_odd_list_back[$okk]+=$winnings;
				  $c_back[$okk]=$shsl['stake'];
				  $c_odd[$okk]= $shsl['odd'];
				  //for laybet
              }else if($type == "lay"){
                  $avaiting_odd_list_lay[$okk]+=$winnings;
                  //$avaiting_odd_list_lay[$data[0]['bid_id'].'-count']=$data[0]['count']+1; for sort_order
              }
            
		};
		return ["lay"=>$avaiting_odd_list_lay,"back"=>$avaiting_odd_list_back];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	//function to get unsubmitted bets(to send data to front-end in json form)
	function UnsubmittedOdds($user_id,$conn){
		$query="SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='unsubmitted' AND type <> 'sbook'";
		$unsubmitted_slip=mysqli_query($conn,$query);
		$unsubmitted_slip_array=mysqli_fetch_assoc($unsubmitted_slip);
		if($unsubmitted_slip_array['bet_info']){
			return unserialize($unsubmitted_slip_array['bet_info']);
		}else{
			return [$unsubmitted_slip_array];
		}
	}


	
	//cashout
	function getOdd($boi, $bon, $conn){  
	$kk="SELECT bet_option_odd FROM af_inplay_bet_options WHERE bet_option_id = $boi AND bet_option_name='$bon'";
	$edata=mysqli_query($conn,$kk);
	$ob = mysqli_fetch_assoc($edata);
	$odo = $ob['bet_option_odd'];
	return $odo;
	}
	


    $qs="SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='awaiting' AND bet_info IS NULL AND type='back'";
		$shslips = mysqli_query($conn,$qs);
		$dats = array();
		while($row=$shslips->fetch_assoc()){
			$winnings = $row['winnings'];
			$boi = $row['bet_option_id'];
			$bon = $row['bet_option_name'];
			$slipid = $row['slip_id'];
			$opodd = getOdd($boi,$bon, $conn);
			$kmid = $row['bet_option_id']. '' .$row['bet_option_name'];
			$okk=str_replace(' ', '', $kmid);
			if(empty($opodd)){
				$rodd = '10';
			}else{
				$rodd = $opodd;
			}
	
			$gcash = $winnings/$rodd; $gcal = $gcash * 20/100; $gtotal = $gcash - $gcal;
			$win = round($gtotal,2);
			$dats[$okk] += $win;
			//$dats[$okk]= $slipid;
		}

//var_dump($dats);

//yecho 'helo';
//unsubmitted slip 

//Fetch on new slip data refresh
  function updateuslip($user_id, $conn){
	  $query="SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='unsubmitted' AND type <> 'sbook'";
	  $unsubmitted_slip=mysqli_query($conn,$query);
		$data=mysqli_fetch_assoc($unsubmitted_slip);
	  if($data['bet_info']){
			return unserialize($data['bet_info']);
		}else{
			return [$data];
		}
  }
  
 
	
	
	if(isset($_POST['get_all_events'])){
		$bet_event_data= $data;
		$odata= $odata;
		
		if(isset($_POST['user_id'])){
			getUnSubmittedSlip($_POST['user_id'],$conn);
			$unsubmitted_keys=[];
			$cash_data = $dats;
			//$submitted_data=getSubmittedSlipWinnings($_GET['user_id'],$_GET['event_id'],$conn);
			$unsubmitted_keys=updateuslip($user_id,$conn);
			//$updateOddtoSlip = getUnSubmittedSlipJson($_GET['user_id'],$conn);
		}
		echo json_encode(["event_data"=>$bet_event_data, "xdata"=>$xdata, "odata"=>$odata, "cash_data"=>$cash_data,'unsubmitted_keys'=>$unsubmitted_keys]);
	}

?>
