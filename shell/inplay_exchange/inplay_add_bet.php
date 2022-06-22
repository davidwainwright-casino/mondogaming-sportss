<?php include_once('../db.php');

	//function to add single bet
	function saveSingleBet($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$aid,$btype,$usid,$conn){
			$time=time();
			$query="INSERT INTO `sh_sf_tickets_records` (`user_id`, `status`, `stake`, `winnings`, `date`, `odd`,`sodd`, `event_id`, `event_name`, `cat_name`, `cat_id`, `bet_option_id`, `bet_option_name`, `aid`, `type`,`sp`) VALUES ($usid,'unsubmitted',0,0,'$time','$odd','$sodd','$event_id','$event_name','$cat_name','$cat_id','$oid','$oname', '$aid', '$btype','$sport_id')";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				//echo mysqli_error($conn);
				return false;
			}
	}

	
	 
	 
	 //function format update slip back
	 function gformat($odd){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	  $decimal_odd = $odd;
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
	  $decimal_odd = $odd;
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
  return $odd;
  }
}
	 
//function format update slip laybet
	 function lformat($odd){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	   $decimal_oddlay = $odd+0.02;
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
	  $decimal_odd = $odd+0.02;
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
	  
  return $odd+0.02;
  }
}	


   //Fetch on new slip data refresh
  function updateuslip($user_id, $conn){
	  $query="SELECT * FROM sh_sf_tickets_records WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'";
	  $unsubmitted_slip=mysqli_query($conn,$query);
		$data=mysqli_fetch_assoc($unsubmitted_slip);
	  if($data['bet_info']){
			return unserialize($data['bet_info']);
		}else{
			return [$data];
		}
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
						$nodd= lformat($odd);
						$data['sodd'] = $nodd;
						$data['odd']= $odd;
						$lbsodd = $odd+0.02;
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
	
	
	

	//function to get unsubmitted slip of user (each can have max one unsubmitted slip)
	// function getUnSubmittedSlip($user_id,$conn){
	// 	$query="SELECT * FROM sh_sf_tickets_records WHERE user_id='$user_id' AND status='unsubmitted'";
	// 	$unsubmitted_slip=mysqli_query($conn,$query);
	// 	return mysqli_fetch_assoc($unsubmitted_slip);
	// }

	//function to update slip which have only one saved bet and same event and same category
	function updateSameEventCategorySlip($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$slip_id,$conn){
		$time=time();
		$query="UPDATE `sh_sf_tickets_records` SET `stake`=0, `winnings`= 0, `date`='$time', `odd`='$odd',`sodd`='$sodd', `event_id`='$event_id', `event_name`='$event_name', `cat_name`='$cat_name', `cat_id`='$cat_id', `bet_option_id`='$oid', `bet_option_name`='$oname', `type`='$btype',`sp`='$sport_id' WHERE slip_id='$slip_id'";
		if(mysqli_query($conn,$query)){
			return true;
		}else{
			return false;
		}
	}

	//function to save bet when one bet already exist in slip and new bet have different category (max one bet save per category)
	function saveMultipleBetCaseOne($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$slip_id,$old_bet,$conn){
		$time=time();
		if($old_bet['type']==$btype){
			if($btype=='back'){
			$existing_bet=['stake'=>0, 'winnings'=>0, 'date'=>$old_bet['date'], 'odd'=>$old_bet['odd'],'sodd'=>$old_bet['sodd'], 'event_id'=>$old_bet['event_id'],'event_name'=>$old_bet['event_name'],
			'cat_name'=>$old_bet['cat_name'], 'cat_id'=>$old_bet['cat_id'], 'bet_option_id'=>$old_bet['bet_option_id'],
			'bet_option_name'=>$old_bet['bet_option_name'], 'type'=>$old_bet['type'],'sp'=>$old_bet['sp']];

			$new_bet=['stake'=>0, 'winnings'=>0, 'date'=>$time, 'odd'=>$odd,'sodd'=>$sodd, 'event_id'=>$event_id, 'event_name'=>$event_name, 'cat_name'=>$cat_name,
			'cat_id'=>$cat_id, 'bet_option_id'=>$oid, 'bet_option_name'=>$oname, 'type'=>$btype,'sp'=>$sport_id];

			$bets=serialize(array($existing_bet,$new_bet));
			$query="UPDATE `sh_sf_tickets_records` SET bet_info='$bets' WHERE slip_id='$slip_id'";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				return false;
			}
		 }else{
			$is_updated=updateSameEventCategorySlip($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$slip_id,$conn);
			if($is_updated){
				return true;
			}else{
				return false;
			}
		 }

		}else{

			$is_updated=updateSameEventCategorySlip($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$slip_id,$conn);
			if($is_updated){
				return true;
			}else{
				return false;
			}
		}
		
	}

	//function to save bet when bet_info column is used before.(CASE 2 triggers when bet_info column used once)
	function saveMultipleBetCaseTwo($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$slip_id,$existing_bets,$conn){
		$time=time();
		$existing_bets_array=unserialize($existing_bets['bet_info']);
		$existing_index=-1;
		if($btype==$existing_bets_array[0]['type'] || !isset($existing_bets_array[0]['type'])){
			foreach($existing_bets_array as $key=>$existing_bet){
				if($existing_bet['event_id']==$event_id){
					$existing_index=$key;
				}	
			}

			$new_bet=['stake'=>0, 'winnings'=>0, 'date'=>$time, 'odd'=>$odd, 'sodd'=>$sodd, 'event_id'=>$event_id, 'event_name'=>$event_name, 'cat_name'=>$cat_name, 'cat_id'=>$cat_id, 'bet_option_id'=>$oid, 'bet_option_name'=>$oname, 'type'=>$btype,'sp'=>$sport_id];

			if($existing_index>-1){
				$existing_bets_array[$existing_index]=$new_bet;
			}else{
				array_push($existing_bets_array,$new_bet);
			}
			$updated_bets=serialize($existing_bets_array);
			$query="UPDATE `sh_sf_tickets_records` SET bet_info='$updated_bets' WHERE slip_id='$slip_id'";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	//function to get unsubmitted bets(to send data to front-end in json form)
	function getUnSubmittedSlipJson($user_id,$conn){
		// $query="SELECT * FROM sh_sf_tickets_records WHERE user_id='$user_id' AND status='unsubmitted'";
		// $unsubmitted_slip=mysqli_query($conn,$query);
		// $unsubmitted_slip_array=mysqli_fetch_assoc($unsubmitted_slip);
		$unsubmitted_slip_array=getUnSubmittedSlip($user_id,$conn);
		if($unsubmitted_slip_array['bet_info']){
			return unserialize($unsubmitted_slip_array['bet_info']);
		}else{
			return [$unsubmitted_slip_array];
		}
	}

	//funtion to delete unsubmitted bets whole slip
	function deleteUnsubmittedSlip($user_id,$conn){
		$query="DELETE FROM sh_sf_tickets_records WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'";
		$isDeleted=mysqli_query($conn,$query);
		if($isDeleted){
			return true;
		}else{
			return false;
		}
	}

	//function to delere single bet from unsubmitted slip
	function deleteSingleBet($user_id,$bet_option_id,$conn){
		$query="SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='unsubmitted' AND type <> 'sbook'";
		$unsubmitted_slip=mysqli_fetch_assoc(mysqli_query($conn,$query));
		if(!$unsubmitted_slip['bet_info']){ //when only single bet exist in slip
			$delete_query="DELETE FROM sh_sf_tickets_records WHERE bet_option_id='$bet_option_id' AND user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'";
			if(mysqli_query($conn,$delete_query)){
				return true;
			}else{
				return false;
			}
		}else{ //when multiple bets exist in slip or bet_info is not null
			$unserialized_slip=unserialize($unsubmitted_slip['bet_info']);
			
			$index_to_delete=-1;
			foreach($unserialized_slip as $key=>$bet){
				if($bet['bet_option_id']==$bet_option_id){
					$index_to_delete=$key;
				}
			}
			
			unset($unserialized_slip[$index_to_delete]);
			$updated_serialized_slip=serialize($unserialized_slip);
			$update_query="UPDATE sh_sf_tickets_records SET bet_info='$updated_serialized_slip' WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'";
			
			if(mysqli_query($conn,$update_query)){
				
			$us=mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_info FROM sh_sf_tickets_records WHERE user_id=$user_id AND status='unsubmitted' AND type <> 'sbook'"));
			$usr = unserialize($us['bet_info']);
		   if(sizeof($usr) == 1){
			 $sus=unserialize($us['bet_info']);
		  foreach($sus as $key=>$bo){
			$ei = $bo['event_id'];
			$en = $bo['event_name'];
			$cn = $bo['cat_name'];
			$ci = $bo['cat_id'];
			$boi = $bo['bet_option_id'];
			$bon = $bo['bet_option_name'];
			$od = $bo['odd'];
			$sod = $bo['sodd'];
			}			
        $io = mysqli_query($conn,"UPDATE sh_sf_tickets_records SET bet_info= NULL,odd='$od',sodd='$sod',event_id=$ei,event_name='$en',cat_name='$cn',cat_id=$ci,bet_option_id=$boi,bet_option_name='$bon' WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'");
	   if($io){
		   return true;
	   }else{
		   mysqli_query($conn,"DELETE FROM sh_sf_tickets_records WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'");
	   }
				//mysqli_query($conn,"DELETE FROM sh_sf_tickets_records WHERE user_id='$user_id' AND status='unsubmitted'");
				}else{
					return true;
				}
				
			}else{
				return false;
			}
		}
	}

	//function to get balance in form of (promo,chips)
	function get_balance($user_id,$conn){
		$query="SELECT stripe_cus, max_bet, promo, chips FROM users WHERE id='$user_id'";
		$balance=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($balance);
	}
	//function to get balance in form of (promo,chips)
	function get_exchange($curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	
	
	//function to get balance in form of (promo,chips)
	function maxi_bet($user_id,$conn){
		$query="SELECT * FROM risk_management";
		$mxbt=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($mxbt);
	}

	//function to check validity of cricket odd
	function isValidOddCricket($bet_event_id,$_option_id,$_option_name,$_odd,$b_type,$conn){
		/*if($b_type=="lay"){
			$_odd=$_odd-0.02;
		}*/
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
										if(trim($option_name)==trim($_option_name)){
											if(trim($apiodd)==trim($_odd)){
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



	//function to validate from api before submission
	function isValidOdd($bet_event_id,$_option_id,$_option_name,$_odd,$b_type,$sport_id,$conn){
		if($sport_id==3){
			return isValidOddCricket($bet_event_id,$_option_id,$_option_name,$_odd,$b_type,$conn);
		}
		/*if($b_type=="lay"){
			$_odd=$_odd-0.02;
		}
		*/
		$tk="2645-CC4Cv1IPRtpzbS";
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
						//echo $option_value['odds']." : ".$_odd;echo "odder";
						$bet_option_name." : ".$_option_name;
						$option_value['id']." : ".$_option_id;
						if($option_value['odds'] == $_odd){
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	

	//function to submit bet
	function submitBet($user_id,$stake,$total_odd,$conn){
		$mydb = mysqli_connect('localhost', 'aayan_user11', 'aayan@1967', 'aayan_1967');
		//risk management
		
		
		//get user balance
		$balance=get_balance($user_id,$conn);
		$excg=get_exchange($balance['stripe_cus'],$conn); // Currency of users
		$imaxBet = $excg['rate']; //exchange rate of base currency i.e USD 
		$u_maxbet = $balance['max_bet'] * $imaxBet; //users or individual max bet.. there is no min bet for individual
		$promo=$balance['promo']; //promobalance
		$chips=$balance['chips']; //chips balance
		$wn=round($stake*$total_odd,2); //winnings for back betting
		$time=time();
		
		
		$mbet = maxi_bet($user_id,$conn);
		$min_bet = $mbet['mn_b'] * $imaxBet; //main chips min bet
		$max_bet = $mbet['mx_b'] * $imaxBet; //main chinps max bet
		$pro_min = $mbet['pro_min'] * $imaxBet; //main promo min bet
		$pro_max = $mbet['pro_max'] * $imaxBet; //main promo max bet
		
		//max winable for back betting
		$max_win = $mbet['max_win']; //max user can win
		
		$max_win_rate = $max_win * $imaxBet; //convert USD to relative currency
		if($wn > $max_win_rate){
			$winnings = $max_win_rate;
		}else{
			$winnings = $wn;
		}
		
		
		
		
		
		
		
		//lay stake
		$lstake = $total_odd - 1;
		$to_stake = $lstake * $stake;
		 $lat = $to_stake * $total_odd;
		$wings = round($lat,2); //max winable lay betting
		if($wings > $max_win_rate){
			$lwinnings = $max_win_rate;
		}else{
			$lwinnings = $wings;
		}
		
         /////////////////////submit bet using promo//////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////
		if(!empty($promo) && $chips < $min_bet){ // submit bet using promo
				/*if(!empty($u_maxbet) && $stake > $u_maxbet){
					echo 'Maximum stake allowed '.$u_maxbet.'';
					die();
					}
			        if($stake < $pro_min){
					 echo 'Minimum stake allowed '.$pro_min.'';
					  die();
				   } else if($stake > $pro_max){
					echo 'Maximum stake allowed '.$pro_max.'';
					die();
				   };*/
				$new_promo=$promo-$stake;
				$lay_promo=$promo-$to_stake;
				$unsubmitted_slip=getUnSubmittedSlip($user_id,$conn);
				if($unsubmitted_slip['bet_info']){ // multiple bet
					$is_valid=true;
					$have_bets=false;
					$unserialized_slip_multiple=unserialize($unsubmitted_slip['bet_info']);
					if(sizeof($unserialized_slip_multiple) < 2){
						echo 'Multibet Need Min. 3 selection';
						die();
					 }	
					$total_odds=0;
					foreach($unserialized_slip_multiple as $key=>$bet){
					  $event_id = $bet['event_id']; 
		              $cat_id = $bet['cat_id'];
					  //check if category is disabled
		              $hh = "SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	                  $hc = $conn->query($hh);
		              $hk = $hc->fetch_assoc();
		              if (!empty($hk)) {
			            echo 'This option is temporarily disabled';
			            die();	
		              };
					  
					  //if sport or event is disabled
					  $ds = mysqli_query($conn, "SELECT is_active FROM af_inplay_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$isactive = $sd['is_active'];
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};

						$have_bets=true;
						$total_odds=$total_odds+$bet['odd'];
						if(!isValidOdd($bet['event_id'],$bet['bet_option_id'],$bet['bet_option_name'],$bet['odd'],$bet['type'],$bet['sp'],$conn)){
							$is_valid=false;
						}
					}
					if($have_bets && $is_valid){
						$slip_id=$unsubmitted_slip['slip_id'];

						if($unserialized_slip_multiple[0]['type']=='lay'){
							return ['success'=>false,'msg'=>"Multibet NOT allowed in Lay options.",'promo'=>$promo,'chips'=>$chips];
						}else{
							
							//Promo balance back multibet//////////////////////////////////////
							//check balance and min parameters
							if(!empty($u_maxbet) && $stake > $u_maxbet){
								echo 'Maximum stake allowed '.$u_maxbet.'';
								die();
								}
								if($stake < $pro_min){
									echo 'Minimum stake allowed '.$pro_min.'';
									die();
								} else if($stake > $pro_max){
									echo 'Maximum stake allowed '.$pro_max.'';
									die();
								}else if($stake > $promo){
									echo 'Low Credit. Avl Promo Balance '.$promo.'';
									die();
								}
							
							//Chips balance back multibet//////////////////////////////////////
							//multibet DEBIT/INSERT
							   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='promo' WHERE slip_id='$slip_id'");
							   $raup = rand(9000000, 99999999);	
							   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET slip_id = $raup WHERE slip_id = '$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_tickets_history SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET promo=$new_promo WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$cslip_id = $tes['slip_id'];
							$bet_info = $tes['bet_info'];
							$cstatus = $tes['status'];
							$cdate = time();
							$event_id = $tes['event_id'];
							$event_name = $tes['event_name'];
							$cat_id = $tes['cat_id'];
							$cat_name = $tes['cat_name'];
							$option_id = $tes['bet_option_id'];
							$option_name = $tes['bet_option_name'];
							$ic = '3';
							$sp = $tes['sp'];
							$otcs= '1';
							
							//insert
							mysqli_query($mydb,"INSERT INTO sh_sf_slips_multibetxp (slip_id, status, date, bet_info, me) VALUES('$cslip_id', 'awaiting', '$cdate', '$bet_info', 'inp') ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Backed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE slip_id=$cslip_id");
								mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET promo=promo + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE slip_id=$ssl_id");
						    mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
						
						
						
						
							
						}
				}else{
					return ['success'=>false,'msg'=>"Failed to submit, Odds suspended!",'promo'=>$promo,'chips'=>$chips];
				}
				
				
				
				
                //for promo single bet				
				}else {
					if(isValidOdd($unsubmitted_slip['event_id'],$unsubmitted_slip['bet_option_id'],$unsubmitted_slip['bet_option_name'],$unsubmitted_slip['odd'],$unsubmitted_slip['type'],$unsubmitted_slip['sp'],$conn)){				
						$event_id = $unsubmitted_slip['event_id']; 
						$cat_id = $unsubmitted_slip['cat_id'];
						//check if category is disabled
		             $hh = "SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	                 $hc = $conn->query($hh);
		             $hk = $hc->fetch_assoc();
		             if (!empty($hk)) {
			           echo 'This option is temporarily disabled';
			           die();	
		              };
					  //if sport or event is disabled
					  $ds = mysqli_query($conn, "SELECT is_active FROM af_inplay_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$isactive = $sd['is_active'];
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
										
						$slip_id=$unsubmitted_slip['slip_id'];
						
						
						
						//for single promo lay bet
						if($unsubmitted_slip['type']=="lay"){						
							//lay single chips bet///////////////////////////////////////
							$winnin=($unsubmitted_slip['odd']-1)*$stake;
							
							if(!empty($u_maxbet) && $winnin > $u_maxbet){
								echo 'Maximum risk allowed '.$u_maxbet.'';
								die();
								}
								if($winnin < $pro_min){
									echo 'Minimum risk allowed '.$pro_min.'';
									die();
								} else if($winnin > $pro_max){
									echo 'Maximum risk allowed '.$pro_max.'';
									die();
								} else if($promo < $winnings){
									echo 'Low Credit. Maximum you can risk '.$promo.'';
									die();
								}
							
						    //LAY DEBIT/INSERT PROMO
							   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='awaiting',`date`='$time',`winnings`='$lwinnings', `stake`='$winnin', `debit`='promo' WHERE slip_id='$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_tickets_history(user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit) SELECT user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 ");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET promo=$lay_promo WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$cslip_id = $tes['slip_id'];
							$bet_info = $tes['bet_info'];
							$cstatus = $tes['status'];
							$cdate = time();
							$event_id = $tes['event_id'];
							$event_name = $tes['event_name'];
							$cat_id = $tes['cat_id'];
							$cat_name = $tes['cat_name'];
							$option_id = $tes['bet_option_id'];
							$option_name = $tes['bet_option_name'];
							$ic = '3';
							$sp = $tes['sp'];
							$otcs= '1';
							
							//insert
							mysqli_query($mydb,"INSERT INTO sh_sf_inplayxp (status, date, event_id, event_name, cat_id, cat_name, bet_option_id, bet_option_name, ic, sp) VALUES('awaiting', $cdate, $event_id, '$event_name', $cat_id, '$cat_name', $option_id, '$option_name', $ic, $sp) ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Layed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
								mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET promo=promo + $winnings WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Offer, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,bet_option_id,bet_option_name FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							
							$option_id = $kes['bet_option_id'];
							$option_name = $kes['bet_option_name'];
						   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
						    mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Offer, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Offer, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
							
							
							
							
							
							
							
	
						}else{
							
						 //////////////////promo back single bet///////////////////////
						 
						 //check balance and min parameters
							if(!empty($u_maxbet) && $stake > $u_maxbet){
								echo 'Maximum stake allowed '.$u_maxbet.'';
								die();
								}
								if($stake < $pro_min){
									echo 'Minimum stake allowed '.$pro_min.'';
									die();
								} else if($stake > $pro_max){
									echo 'Maximum stake allowed '.$pro_max.'';
									die();
								}else if($stake > $promo){
									echo 'Low Credit. Avl Promo Balance '.$promo.'';
									die();
								}
								
						 mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='promo' WHERE slip_id='$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_tickets_history(user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit) SELECT user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 ");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET promo=$new_promo WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$cslip_id = $tes['slip_id'];
							$bet_info = $tes['bet_info'];
							$cstatus = $tes['status'];
							$cdate = time();
							$event_id = $tes['event_id'];
							$event_name = $tes['event_name'];
							$cat_id = $tes['cat_id'];
							$cat_name = $tes['cat_name'];
							$option_id = $tes['bet_option_id'];
							$option_name = $tes['bet_option_name'];
							$ic = '3';
							$sp = $tes['sp'];
							$otcs= '1';
							
							//insert
							mysqli_query($mydb,"INSERT INTO sh_sf_inplayxp (status, date, event_id, event_name, cat_id, cat_name, bet_option_id, bet_option_name, ic, sp) VALUES('awaiting', $cdate, $event_id, '$event_name', $cat_id, '$cat_name', $option_id, '$option_name', $ic, $sp) ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Backed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
								mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET promo=promo + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,bet_option_id,bet_option_name FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$option_name = $kes['bet_option_name'];
							$option_id = $kes['bet_option_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
						    mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}	 

						}
						
						
					}else{
						return ['success'=>false,'msg'=>"Couldn't match your bet. Try again.",'promo'=>$promo,'chips'=>$chips];
					}
				}
				
		




       /////////////////////submit bet using CHIPS //////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////// 		

		}else{
				
				$new_chips=$chips-$stake;
				$lay_chips=$chips-$to_stake; 
				
				$unsubmitted_slip=getUnSubmittedSlip($user_id,$conn);
				if($unsubmitted_slip['bet_info']){ 
					$is_valid=true;
					$have_bets=false;
					$unserialized_slip_multiple=unserialize($unsubmitted_slip['bet_info']);
					foreach($unserialized_slip_multiple as $key=>$bet){
					  $event_id = $bet['event_id']; 
		              $cat_id = $bet['cat_id'];
					  //check if category is disabled
		              $hh = "SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	                  $hc = $conn->query($hh);
		              $hk = $hc->fetch_assoc();
		              if (!empty($hk)) {
			            echo 'This option is temporarily disabled';
			            die();	
		              };
					  
					  //if sport or event is disabled
					  $ds = mysqli_query($conn, "SELECT is_active FROM af_inplay_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$isactive = $sd['is_active'];
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
					  
						$have_bets=true;
						if(!isValidOdd($bet['event_id'],$bet['bet_option_id'],$bet['bet_option_name'],$bet['odd'],$bet['type'],$bet['sp'],$conn)){
							$is_valid=false;
							return ['success'=>false,'msg'=>"Some of the odds have changed! T.A",'promo'=>$promo,'chips'=>$chips];
						}
					}
					if($have_bets && $is_valid){
						$slip_id=$unsubmitted_slip['slip_id'];
						if($unserialized_slip_multiple[0]['type']=='lay'){
							return ['success'=>false,'msg'=>"Multibet NOT allowed in lay options!",'promo'=>$promo,'chips'=>$chips];

						}else{
							
							//check balance and min parameters
							if(!empty($u_maxbet) && $stake > $u_maxbet){
								echo 'Maximum stake allowed '.$u_maxbet.'';
								die();
								}
								if($stake < $min_bet){
									echo 'Minimum stake allowed '.$min_bet.'';
									die();
								} else if($stake > $max_bet){
									echo 'Maximum stake allowed '.$max_bet.'';
									die();
								}else if($stake > $chips){
									echo 'Low Credit. Available Balance '.$chips.'';
									die();
								}
							
							//Chips balance back multibet//////////////////////////////////////
							//multibet DEBIT/INSERT
							   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='chips' WHERE slip_id='$slip_id'");
							   $raup = rand(9000000, 99999999);	
							   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET slip_id = $raup WHERE slip_id = '$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_tickets_history SELECT * FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET chips=$new_chips WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$cslip_id = $tes['slip_id'];
							$bet_info = $tes['bet_info'];
							$cstatus = $tes['status'];
							$cdate = time();
							$event_id = $tes['event_id'];
							$event_name = $tes['event_name'];
							$cat_id = $tes['cat_id'];
							$cat_name = $tes['cat_name'];
							$option_id = $tes['bet_option_id'];
							$option_name = $tes['bet_option_name'];
							$ic = '3';
							$sp = $tes['sp'];
							$otcs= '1';
							
							//insert
							mysqli_query($mydb,"INSERT INTO sh_sf_slips_multibetxp (slip_id, status, date, bet_info, me) VALUES('$cslip_id', 'awaiting', '$cdate', '$bet_info', 'inp') ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Backed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE slip_id=$cslip_id");
								mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET chips=chips + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,event_id,bet_option_id FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$event_id = $kes['event_id'];
							$option_id = $kes['bet_option_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE slip_id=$ssl_id");
						    mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
							
							
							
							
							
							
							
						}

				}else{
					return ['success'=>false,'msg'=>"Failed to submit, odds suspended",'promo'=>$promo,'chips'=>$chips];
				}

				}else { 
				
				
				
				    
				
					if(isValidOdd($unsubmitted_slip['event_id'],$unsubmitted_slip['bet_option_id'],$unsubmitted_slip['bet_option_name'],$unsubmitted_slip['odd'],$unsubmitted_slip['type'],$unsubmitted_slip['sp'],$conn)){
					 $event_id = $unsubmitted_slip['event_id']; 
					 $cat_id = $unsubmitted_slip['cat_id'];
					 //check if category is disabled
					 
					 $hh = "SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	                 $hc = $conn->query($hh);
		             $hk = $hc->fetch_assoc();
		             if (!empty($hk)) {
			           echo 'This option is temporarily disabled';
			           die();	
		              };
					  
					  //if sport or event is disabled
					  $ds = mysqli_query($conn, "SELECT is_active FROM af_inplay_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$isactive = $sd['is_active'];
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
	
						$slip_id=$unsubmitted_slip['slip_id'];
						if($unsubmitted_slip['type']=='lay'){
							
							//lay single chips bet///////////////////////////////////////
							
							$winnin=($unsubmitted_slip['odd']-1)*$stake;
							
							if(!empty($u_maxbet) && $winnin > $u_maxbet){
								echo 'Maximum risk allowed '.$u_maxbet.'';
								die();
								}
								if($winnin < $min_bet){
									echo 'Minimum risk allowed '.$min_bet.'';
									die();
								} else if($winnin > $max_bet){
									echo 'Maximum risk allowed '.$max_bet.'';
									die();
								} else if($chips < $winnin){
									echo 'Low Credit. Maximum you can risk '.$chips.'';
									die();
								}
							
						    //LAY DEBIT/INSERT
							   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='awaiting',`date`='$time',`winnings`='$lwinnings', `stake`='$winnin', `debit`='chips' WHERE slip_id='$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_tickets_history(user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit) SELECT user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 ");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET chips=$lay_chips WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$cslip_id = $tes['slip_id'];
							$bet_info = $tes['bet_info'];
							$cstatus = $tes['status'];
							$cdate = time();
							$event_id = $tes['event_id'];
							$event_name = $tes['event_name'];
							$cat_id = $tes['cat_id'];
							$cat_name = $tes['cat_name'];
							$option_id = $tes['bet_option_id'];
							$option_name = $tes['bet_option_name'];
							$ic = '3';
							$sp = $tes['sp'];
							$otcs= '1';
							
							//insert
							mysqli_query($mydb,"INSERT INTO sh_sf_inplayxp (status, date, event_id, event_name, cat_id, cat_name, bet_option_id, bet_option_name, ic, sp) VALUES('awaiting', $cdate, $event_id, '$event_name', $cat_id, '$cat_name', $option_id, '$option_name', $ic, $sp) ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Layed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
								mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET chips=chips + $winnings WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Offer, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,bet_option_id,bet_option_name FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$option_name = $kes['bet_option_name'];
							$option_id = $kes['bet_option_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
						    mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Offer, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Offer, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
							
							
							
							
							
							
							
							
							
							
						}else{
							
						   //back single chips bet
						   
						   //check balance and min parameters
							if(!empty($u_maxbet) && $stake > $u_maxbet){
								echo 'Maximum stake allowed '.$u_maxbet.'';
								die();
								}
								if($stake < $min_bet){
									echo 'Minimum stake allowed '.$min_bet.'';
									die();
								} else if($stake > $max_bet){
									echo 'Maximum stake allowed '.$max_bet.'';
									die();
								}else if($stake > $chips){
									echo 'Low Credit. Available Balance '.$chips.'';
									die();
								}
						   
						    mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='chips' WHERE slip_id='$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_tickets_history(user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit) SELECT user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit FROM sh_sf_tickets_records WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 ");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET chips=$new_chips WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$cslip_id = $tes['slip_id'];
							$bet_info = $tes['bet_info'];
							$cstatus = $tes['status'];
							$cdate = time();
							$event_id = $tes['event_id'];
							$event_name = $tes['event_name'];
							$cat_id = $tes['cat_id'];
							$cat_name = $tes['cat_name'];
							$option_id = $tes['bet_option_id'];
							$option_name = $tes['bet_option_name'];
							$ic = '3';
							$sp = $tes['sp'];
							$otcs= '1';
							
							//insert
							mysqli_query($mydb,"INSERT INTO sh_sf_inplayxp (status, date, event_id, event_name, cat_id, cat_name, bet_option_id, bet_option_name, ic, sp) VALUES('awaiting', $cdate, $event_id, '$event_name', $cat_id, '$cat_name', $option_id, '$option_name', $ic, $sp) ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Backed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
								mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET chips=chips + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,bet_option_id,bet_option_name FROM `sh_sf_tickets_history` WHERE user_id=$user_id AND status = 'awaiting' AND type <> 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$option_name = $kes['bet_option_name'];
							$option_id = $kes['bet_option_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_tickets_records` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type <> 'sbook'");
						    mysqli_query($conn,"DELETE FROM sh_sf_tickets_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						//Inside bet complete submission start from chips
						
					   }
					}else{
						return ['success'=>false,'msg'=>"Couldn't match your bet. Try again.",'promo'=>$promo,'chips'=>$chips];
						
					}
				}

		}
	}


	if(isset($_POST['save_bet'])){
		$event_name=$_POST['event_name'];
		$cat_name=$_POST['cat_name'];
		$event_id=$_POST['event_id'];
		$cat_id=$_POST['cat_id'];
		$oid = $_POST['oid'];
		$btype = $_POST['btype'];
		$usid = $_POST['usid'];
		$sodd=$_POST['sodd'];
		$ds = mysqli_query($conn, "SELECT bet_option_name,bet_option_odd FROM af_inplay_bet_options WHERE bet_option_id=$oid");
		$sd=mysqli_fetch_assoc($ds);
		$oname = $sd['bet_option_name'];
		$odd=round($sd['bet_option_odd'],2);
		$aid=$_POST['aid'];
		$btype=$_POST['btype'];
		$sport_id=$_POST['sport_id'];
		if($usid == '999999999'){
			echo 'Please login to add';
			die;
		}else{
			$unsubmitted_slip=getUnSubmittedSlip($usid,$conn);
			if($unsubmitted_slip){ //CASE 2
				if($unsubmitted_slip['bet_info']){
					$usert = unserialize($unsubmitted_slip['bet_info']);
					if(sizeof($usert) > 5){
						echo 'Cannot Select more than 6 events';
						die();
					}
					$is_updated=saveMultipleBetCaseTwo($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$unsubmitted_slip['slip_id'],$unsubmitted_slip,$conn);
					if($is_updated){
							echo "Selection Updated!";
						}else{
							echo "Mix multiple type not allowed!";
						}
				}else{
					if($unsubmitted_slip['event_id']==$event_id){ 
						$is_updated=updateSameEventCategorySlip($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$unsubmitted_slip['slip_id'],$conn);
						if($is_updated){
							echo "Updated!";
						}else{
							echo "Failed to Update!";
						}
					}else{ 
						//CASE ONE TODO
						$is_updated=saveMultipleBetCaseOne($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$unsubmitted_slip['slip_id'],$unsubmitted_slip,$conn);
						if($is_updated){
							if($btype=='lay'){
								echo 'Lay bet updated';
							}else{
							echo "Multiple Bet Updated!";
							}
						}else{
							echo "Failed to Update Multiple Bet!";
						}
					}
				}

			}else if(saveSingleBet($sport_id,$event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$aid,$btype,$usid,$conn)){ //CASE 1
				echo "Bet Saved!";
			}else{
				echo "Failed to save!";
			}
		}
	}
     
	 //show slips
	if(isset($_POST['get_unsubmitted_slip'])){
		$data=getUnSubmittedSlipJson($_POST['user_id'],$conn);
		header('Content-Type: application/json');
		print_r(json_encode($data));
	}
	//update slips
	if(isset($_POST['update_unsubmitted_slip'])){
		$data=updateuslip($_POST['user_id'],$conn);
		header('Content-Type: application/json');
		print_r(json_encode($data));
	}
	
	

	if(isset($_POST['delete_all_unsubmitted_bets'])){
		if(isset($_POST['usid'])){
			if(deleteUnsubmittedSlip($_POST['usid'],$conn)){
				echo "Slip deleted!";
			}else{
				echo "Failed to delete slip!";
			}
		}
	}

	if(isset($_POST['delete_single_bet'])){
		if(isset($_POST['usid'])){
			if(deleteSingleBet($_POST['usid'],$_POST['bet_id'],$conn)){
				echo "Bet deleted!";
			}else{
				echo "Failed to delete bet!";
			}
		}
	}

	if(isset($_POST['submit_bet'])){
		if(isset($_POST['usid'])){
			$user_id=$_POST['usid'];
			$stake=$_POST['stake'];
			$total_odd=$_POST['total_odd'];
			$result=submitBet($user_id,$stake,$total_odd,$conn);
			if($result['success']){
				echo $result['msg'];
			}else{
				echo $result['msg'];
			}

		}
	}


?>