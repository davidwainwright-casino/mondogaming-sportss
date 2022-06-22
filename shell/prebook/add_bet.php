<?php include_once('../db.php');

	//function to add single bet
	function saveSingleBet($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$usid,$conid,$aid, $sp, $conn){
			$time=time();
			$infor=['cid'=>$cat_id, 'cn'=>$cat_name, 'oid'=>$oid, 'bn'=>$oname, 'od'=>$odd, 'sp'=>$sp];
			$binfo = serialize(array($infor));
			$query="INSERT INTO `sh_sf_slips` (`user_id`, `status`, `date`, `bet_info`, `event_id`, `event_name`, `conid`) VALUES ($usid,'unsubmitted','$time', '$binfo', '$event_id','$event_name', $conid)";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				return false;
			}
	}

	//function to get unsubmitted slip of user (each can have max one unsubmitted slip)
	function getUnSubmittedSlip($user_id, $conid, $getSelection, $conn){
		$query="SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND conid=$conid AND status='unsubmitted'";
		$unsubmitted_slip=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($unsubmitted_slip);
	}

	//function to update slip which have only one saved bet and same event and same category
	function updateSameEventCategorySlip($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$slip_id,$conn){
		$time=time();
		$query="UPDATE `sh_sf_slips` SET `stake`=0, `date`='$time', `od`='$odd', `event_id`='$event_id', `event_name`='$event_name', `cn`='$cat_name', `cid`='$cat_id', `oid`='$oid', `bn`='$oname', `sp`=$sp WHERE slip_id='$slip_id'";
		if(mysqli_query($conn,$query)){
			return true;
		}else{
			return false;
		}
	}

	//function to save bet when one bet already exist in slip and new bet have different category (max one bet save per category)
	function saveMultipleBetCaseOne($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$slip_id,$old_bet,  $conn){
		$time=time();
		if($old_bet['event_id']==$event_id){
			$existing_bet=['cid'=>$old_bet['cid'], 'cn'=>$old_bet['cn'], 'oid'=>$old_bet['oid'], 'bn'=>$old_bet['bn'], 'od'=>$old_bet['od'], 'sp'=>$old_bet['sp']];

			$new_bet=['cid'=>$cat_id,'cn'=>$cat_name,'oid'=>$oid, 'bn'=>$oname, 'od'=>$odd, 'sp'=>$sp];

			$bets=serialize(array($existing_bet,$new_bet));
			$query="UPDATE `sh_sf_slips` SET bet_info='$bets' WHERE slip_id='$slip_id'";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				return false;
			}

		}else{

			$is_updated=updateSameEventCategorySlip($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$slip_id,$conn);
			if($is_updated){
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	
	
	
	
	
	
	

	//function to save bet when bet_info column is used before.(CASE 2 triggers when bet_info column used once)
	function saveMultipleBetCaseTwo($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$slip_id,$existing_bets,$conn){
		$time=time();
		$existing_bets_array=unserialize($existing_bets['bet_info']);
		
		$existing_index=-1;
		if($cat_id==$existing_bets_array['cid'] || !isset($existing_bets_array['cid'])){
			foreach($existing_bets_array as $key=>$existing_bet){
				if($existing_bet['cid']==$cat_id){
					$existing_index=$key;
				}	
			}

			$new_bet=['cid'=>$cat_id,'cn'=>$cat_name, 'oid'=>$oid, 'bn'=>$oname, 'od'=>$odd, 'sp'=>$sp];

			if($existing_index>-1){
				$existing_bets_array[$existing_index]=$new_bet;
			}else{
				array_push($existing_bets_array,$new_bet);
			}
			$updated_bets=serialize($existing_bets_array);
			$query="UPDATE `sh_sf_slips` SET bet_info='$updated_bets' WHERE slip_id='$slip_id'";
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
	function getUnSubmittedSlipJson($user_id,$conid,$conn){
		$query="SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND conid = $conid AND status='unsubmitted'";
		$unsubmitted_slip=mysqli_query($conn,$query);
		$unsubmitted_slip_array=mysqli_fetch_assoc($unsubmitted_slip);
		if($unsubmitted_slip_array['bet_info']){
			return unserialize($unsubmitted_slip_array['bet_info']);
		}else{
			return [$unsubmitted_slip_array];
		}
	}

	//funtion to delete unsubmitted bets whole slip
	function deleteUnsubmittedSlip($user_id,$conn){
		$query="DELETE FROM sh_sf_slips WHERE user_id=$user_id AND status='unsubmitted'";
		$isDeleted=mysqli_query($conn,$query);
		if($isDeleted){
			return true;
		}else{
			return false;
		}
	}

	//function to delere single bet from unsubmitted slip
	function deleteSingleBet($user_id,$bet_option_id,$conn){
		$query="SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND status='unsubmitted'";
		$unsubmitted_slip=mysqli_fetch_assoc(mysqli_query($conn,$query));
		if(!$unsubmitted_slip['bet_info']){ //when only single bet exist in slip
			$delete_query="DELETE FROM sh_sf_slips WHERE oid='$bet_option_id' AND user_id='$user_id' AND status='unsubmitted'";
			if(mysqli_query($conn,$delete_query)){
				return true;
			}else{
				return false;
			}
		}else{ //when multiple bets exist in slip or bet_info is not null
			$unserialized_slip=unserialize($unsubmitted_slip['bet_info']);
			
			$index_to_delete=-1;
			foreach($unserialized_slip as $key=>$bet){
				if($bet['oid']==$bet_option_id){
					$index_to_delete=$key;
				}
			}
			
			unset($unserialized_slip[$index_to_delete]);
			$updated_serialized_slip=serialize($unserialized_slip);
			$update_query="UPDATE sh_sf_slips SET bet_info='$updated_serialized_slip' WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'";
			
			if(mysqli_query($conn,$update_query)){
				
			$us=mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_info FROM sh_sf_slips WHERE user_id=$user_id AND status='unsubmitted' AND type <> 'sbook'"));
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
        $io = mysqli_query($conn,"UPDATE sh_sf_slips SET bet_info= NULL,odd='$od',sodd='$sod',event_id=$ei,event_name='$en',cat_name='$cn',cat_id=$ci,bet_option_id=$boi,bet_option_name='$bon' WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'");
	   if($io){
		   return true;
	   }else{
		   mysqli_query($conn,"DELETE FROM sh_sf_slips WHERE user_id='$user_id' AND status='unsubmitted' AND type <> 'sbook'");
	   }
				//mysqli_query($conn,"DELETE FROM sh_sf_slips WHERE user_id='$user_id' AND status='unsubmitted'");
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
		$query="SELECT stripe_cus, promo, chips, won FROM users WHERE id='$user_id'";
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

	//function to validate odd values
	function validateOdd($bet_option_id,$bet_option_name,$odd,$conn){
		$fodd = $odd/10;
		mysqli_query($conn,"SELECT bet_option_odd FROM af_pre_bet_options WHERE bet_option_id=$bet_option_id AND bet_option_odd=$fodd");
		if (mysqli_affected_rows($conn) > 0){
			return true;
		}else{
			return false;
			echo $conn->error;
		}
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
										if($j['ID'] == $option_id){
									    //echo $option_name; echo '--'; echo $_option_name;
										if($option_name==$option_name){
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	//function to submit bet
	function submitBet($user_id,$getSelection,$stake,$ifref,$winnings,$conn){
	   include_once('../ck.php');	
	   $time=time();
	   $rsk=mysqli_fetch_assoc(mysqli_query($conn,"SELECT mn_b, mx_b, max_win, deadline FROM risk_management"));
	   $dline=$rsk['deadline'];
	   $min_bet=$rsk['mn_b'];
	   $max_bet=$rsk['mx_b'];
	   $max_win=$rsk['max_win'];
	   
	   $ucr=mysqli_fetch_assoc(mysqli_query($conn,"SELECT chips, promo FROM users WHERE id=$user_id"));
	   $promo = $ucr['promo'];
	   echo $chips = $ucr['chips'];
	   $af = $chips - $stake;
		if($ifref=='chips'){
			if($stake > $chips){
				echo 'You have insufficient balance '.$chips.'';die();
			}
			$btype='chips';
			$final_stake = $stake;
			$key_type = 'chips';
		}else{
			if($stake > $promo){
				echo 'Your promo balance is too low '.$promo.'';die();
			}
			$btype='promo';
			$final_stake = $stake;
			$key_type='promo';
		}
			
		if($final_stake < $min_bet){
			echo 'Minimum stake allowed is '.$min_bet.'';die();
		}
		if($final_stake > $max_bet){
			echo 'Maximum stake allowed is '.$max_bet.'';die();
		}
		if($winnings > $max_win){
			$net_win = $max_win;
		}else{
			$net_win = $winnings;
			
		}
			
		//get token
		$tkn=mysqli_fetch_assoc(mysqli_query($conn,"SELECT serial FROM `www_token`"));
		$token = $tkn['serial'];
			if($getSelection){
				$is_valid=true;
				$have_bets=false;
				$unserialized_slip_multiple=$getSelection;
			   foreach($unserialized_slip_multiple as $key=>$bet){
				 $event_id = $bet['event_id'];
				 $event_name = $bet['event_name'];
				 $cat_id = $bet['cat_id'];
				 $cat_name = $bet['cat_name'];
				 $option_id = $bet['bet_option_id'];
				 $option_name = $bet['bet_option_name'];
				 $odd = $bet['odd'];
				 $format_odd = $bet['sodd'];
				 $spid = $bet['sp'];
				 $event_type = $bet['type']; //live or prematch
				 $aid = $bet['aid'];
		
		        //check if category is disabled
	             $cstatus = mysqli_fetch_assoc(mysqli_query("SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE bet_event_cat_id = $cat_id AND yn IS NOT NULL"));
		          if (!empty($cstatus)) {
			         echo 'This option is temporarily disabled.';die();	
		            }
				//check if full event is disabled
				$estatus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT bet_event_id FROM af_inplay_bet_events WHERE bet_event_id = $event_id AND is_active=0"));
				if (!empty($estatus)) {
			         echo 'This event is temporarily disabled.';die();	
		            }
				
		
					   $have_bets=true;
						if(!isValidOdd($event_id,$option_id,$option_name,$odd,$spid,$token,$conn)){
							$is_valid=false;
							return ['success'=>false,'msg'=>"One of the selection have been Suspended!",'chips'=>$chips];
						}
					}
					
					

					
					//INSERT TO SETTLEMENT
					if($have_bets && $is_valid){
						//for single bet
						if(sizeof($getSelection) == 1){
						mysqli_query($conn,"INSERT INTO `sh_sf_tickets_records` (`user_id`, `status`, `stake`, `winnings`, `date`, `odd`,`sodd`, `event_id`, `event_name`, `cat_name`, `cat_id`, `bet_option_id`, `bet_option_name`, `aid`, `type`,`sp`,`debit`, `st`) VALUES ($user_id,'awaiting','$final_stake','$winnings','$time','$odd','$format_odd','$event_id','$event_name','$cat_name','$cat_id','$option_id','$option_name', '$aid', 'sbook','$spid', '$btype', '$event_type')");
						
						//for settlement
						mysqli_query($sconn,"INSERT INTO `sh_sf_inplayxp` (`status`, `date`, `event_id`, `event_name`, `cat_id`, `cat_name`, `bet_option_id`, `bet_option_name`, `sp`) VALUES ('awaiting', '$time','$event_id', '$event_name', '$cat_id', '$cat_name', '$option_id', '$option_name', '$spid')");
						
						//for users history records
						mysqli_query($conn,"INSERT INTO `sh_sf_bet_history` (`user_id`, `status`, `stake`, `winnings`, `date`, `odd`,`sodd`, `event_id`, `event_name`, `cat_name`, `cat_id`, `bet_option_id`, `bet_option_name`, `aid`, `type`,`sp`,`debit`, `st`, `ab`, `af`) VALUES ($user_id,'awaiting','$final_stake','$winnings','$time','$odd','$format_odd','$event_id','$event_name','$cat_name','$cat_id','$option_id','$option_name', '$aid', 'sbook','$spid', '$btype', '$event_type', '$chips', '$af')");
						}
						//for multi bet
						else{
						$binfo = serialize($getSelection);
						mysqli_query($conn,"INSERT INTO `sh_sf_tickets_records` (`user_id`, `status`, `stake`, `winnings`, `date`, `bet_info`, `aid`, `type`, `debit`, `st`) VALUES ($user_id,'awaiting','$final_stake','$winnings','$time','$binfo', '$aid', 'sbook', '$btype', '$event_type')");
						
						//for settlement
						$laid=mysqli_fetch_assoc(mysqli_query($conn,"SELECT slip_id FROM sh_sf_tickets_records WHERE user_id=$user_id ORDER BY slip_id DESC LIMIT 1"));
						$gsid = $laid['slip_id'];
						
						mysqli_query($sconn,"INSERT INTO `sh_sf_slips_multibetxp` (`slip_id`, `status`, `date`, `bet_info`, `type`) VALUES ($gsid,'awaiting', '$time','$binfo', '$event_type')");
						}
						//for users history records
						mysqli_query($conn,"INSERT INTO `sh_sf_bet_history` (`user_id`, `status`, `stake`, `winnings`, `date`, `bet_info`, `aid`, `type`, `debit`, `st`, `ab`, `af`) VALUES ($user_id,'awaiting','$final_stake','$winnings','$time','$binfo', '$aid', 'sbook', '$btype', '$event_type', '$chips', '$af')");
						
						if (mysqli_affected_rows($conn) > 0){
							mysqli_query($conn,"UPDATE users SET $key_type=$key_type - $final_stake WHERE id=$user_id");
							echo 'Bet Successfully Accepted';

						}else{
							//echo $conn->error;
						return ['success'=>false,'msg'=>"Couldn't Join the contest.",'chips'=>$chips];
								die();
						}

				}else{
					return ['success'=>false,'msg'=>"Couldn't join the contest.!",'chips'=>$chips];
				}

				}

		
	}

























/*

	if(isset($_POST['save_bet'])){
		$event_name=$_POST['event_name'];
		$cat_name=$_POST['cat_name'];
		$event_id=$_POST['event_id'];
		$cat_id=$_POST['cat_id'];
		$oid = $_POST['oid'];
		$oname = $_POST['oname'];
		$btype = $_POST['btype'];
		$usid = $_POST['usid'];
		$aid = $_POST['aid'];
		$odd=$_POST['odd'];
		$sodd=$_POST['sodd'];
		$btype=$_POST['btype'];
		$conid = $_POST['conid'];
		$sp=$_POST['spid'];
		//check if passed deadline
		$mbet = maxi_bet($user_id,$conn);
		$dline = $mbet['deadline'];
		$pp = "SELECT bet_event_id, deadline FROM af_pre_bet_events WHERE bet_event_id = $event_id AND UNIX_TIMESTAMP() < (deadline - $dline)";
	    $cs = $conn->query($pp);
		$ck = $cs->fetch_assoc();
		if (empty($ck)) {
			echo 'Deadline passed for this event';
			die();	
		};
		
		$dead = $ck['deadline'];
		
		//check if category is disabled
		$hh = "SELECT bet_event_cat_id, bet_event_cat_name FROM af_pre_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	    $hc = $conn->query($hh);
		$hk = $hc->fetch_assoc();
		if (!empty($hk)) {
			echo 'This option is disabled temporarily';
			die();	
		};
	    $nettime = time() + 7200;
		//$catname = $hk['bet_event_cat_name'];
		
		if(($nettime > $dead) && ($cat_name == 'To Win the Toss')){
			echo 'This category has expired';
			die();
		}
		
		if($usid == '100'){
			echo 'Please login to add';
			die;
		}else{
			$unsubmitted_slip=getUnSubmittedSlip($usid,$conid, $conn);
			if(sizeof(unserialize($unsubmitted_slip['bet_info'])) == 11){
				die();
			}
			if($unsubmitted_slip){ //CASE 2
				if($unsubmitted_slip['bet_info']){
					$is_updated=saveMultipleBetCaseTwo($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$unsubmitted_slip['slip_id'],$unsubmitted_slip,$conn);
					if($is_updated){
							echo "Multiple Bet Updatedss!";
						}else{
							echo "Cannot mix selection type";
						}
				}else{
					if($unsubmitted_slip['cn']==$cat_name){
						$is_updated=updateSameEventCategorySlip($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$unsubmitted_slip['slip_id'],$conn);
						
						if($is_updated){
							echo "Selection Updated!";
						}else{
							echo "Failed to Update!";
						}
					}else{ 
						//CASE ONE TODO
						$is_updated=saveMultipleBetCaseOne($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype, $sp, $unsubmitted_slip['slip_id'],$unsubmitted_slip,$conn);
						if($is_updated){
							echo "On Multibet Selection";
						}else{
							echo "Failed to Update Multiple Bet!";
						}
					}
					
				}

			}else if(saveSingleBet($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$usid,$conid,$aid, $sp,$conn)){ //CASE 1
				echo "Option Saved!";
			}else{
				echo "Failed to save!";
				echo $conn->error;
				
			}
		
		};
	
	}

	if(isset($_POST['get_unsubmitted_slip'])){
		//$data=getUnSubmittedSlipJson($_POST['user_id'],$_POST['event_id'],$conn);
		$user_id = $_POST['user_id'];
		$event_id = $_POST['event_id'];
		$conid = $_POST['conid'];
		$unsubmitted_slip_array=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND conid = $conid AND status='unsubmitted'"));
		if($unsubmitted_slip_array['bet_info']){
			print_r(json_encode(unserialize($unsubmitted_slip_array['bet_info'])));
		}else{
			print_r(json_encode([$unsubmitted_slip_array]));
		}

		//print_r(json_encode($data));
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
	*/

	if(isset($_POST['submit_bet'])){
		if(isset($_POST['usid'])){
			$user_id=$_POST['usid'];
			$getSelection=$_POST['getStoredArr'];
			$stake=$_POST['stake'];
			$winnings = $_POST['winnings'];
			$ifref=$_POST['ifref'];
			$result=submitBet($user_id,$getSelection,$stake,$ifref,$winnings,$conn);
			if($result['success']){
				echo $result['msg'];
			}else{
				echo $result['msg'];
			}

		}
	}

?>