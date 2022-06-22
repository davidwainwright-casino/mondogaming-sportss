<?php include_once('../db.php');

	//function to add single bet
	function saveSingleBet($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$usid, $aid, $sp, $conn){
			$time=time();
			$query="INSERT INTO `sh_sf_slips` (`user_id`, `status`, `stake`, `winnings`, `date`, `odd`,`sodd`, `event_id`, `event_name`, `cat_name`, `cat_id`, `bet_option_id`, `bet_option_name`, `aid`, `type`, `sp`) VALUES ($usid,'unsubmitted',0,0,'$time','$odd','$sodd','$event_id','$event_name','$cat_name','$cat_id','$oid','$oname', '$aid', '$btype', '$sp')";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				return false;
			}
	}

	//function to get unsubmitted slip of user (each can have max one unsubmitted slip)
	function getUnSubmittedSlip($user_id,$conn){
		$query="SELECT * FROM sh_sf_slips WHERE user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'";
		$unsubmitted_slip=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($unsubmitted_slip);
	}

	//function to update slip which have only one saved bet and same event and same category
	function updateSameEventCategorySlip($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$slip_id,$conn){
		$time=time();
		$query="UPDATE `sh_sf_slips` SET `stake`=0, `winnings`= 0, `date`='$time', `odd`='$odd',`sodd`='$sodd', `event_id`='$event_id', `event_name`='$event_name', `cat_name`='$cat_name', `cat_id`='$cat_id', `bet_option_id`='$oid', `bet_option_name`='$oname', `type`='$btype', `sp`='$sp' WHERE slip_id='$slip_id'";
		if(mysqli_query($conn,$query)){
			return true;
		}else{
			return false;
		}
	}

	//function to save bet when one bet already exist in slip and new bet have different category (max one bet save per category)
	function saveMultipleBetCaseOne($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$slip_id,$old_bet,  $conn){
		$time=time();
		if($old_bet['type']==$btype){
			$existing_bet=['stake'=>0, 'winnings'=>0, 'date'=>$old_bet['date'], 'odd'=>$old_bet['odd'],'sodd'=>$old_bet['sodd'], 'event_id'=>$old_bet['event_id'],'event_name'=>$old_bet['event_name'], 'cat_name'=>$old_bet['cat_name'], 'cat_id'=>$old_bet['cat_id'], 'bet_option_id'=>$old_bet['bet_option_id'], 'bet_option_name'=>$old_bet['bet_option_name'], 'type'=>$old_bet['type'], 'sp'=>$old_bet['sp']];

			$new_bet=['stake'=>0, 'winnings'=>0, 'date'=>$time, 'odd'=>$odd,'sodd'=>$sodd, 'event_id'=>$event_id, 'event_name'=>$event_name, 'cat_name'=>$cat_name, 'cat_id'=>$cat_id, 'bet_option_id'=>$oid, 'bet_option_name'=>$oname, 'type'=>$btype, 'sp'=>$sp];

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
		if($btype==$existing_bets_array[0]['type'] || !isset($existing_bets_array[0]['type'])){
			foreach($existing_bets_array as $key=>$existing_bet){
				if($existing_bet['event_id']==$event_id){
					$existing_index=$key;
				}	
			}

			$new_bet=['stake'=>0, 'winnings'=>0, 'date'=>$time, 'odd'=>$odd, 'sodd'=>$sodd, 'event_id'=>$event_id, 'event_name'=>$event_name, 'cat_name'=>$cat_name, 'cat_id'=>$cat_id, 'bet_option_id'=>$oid, 'bet_option_name'=>$oname, 'aid'=>$aid, 'type'=>$btype, 'sp'=>$sp];

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
	function getUnSubmittedSlipJson($user_id,$conn){
		$query="SELECT * FROM sh_sf_slips WHERE user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'";
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
		$query="DELETE FROM sh_sf_slips WHERE user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'";
		$isDeleted=mysqli_query($conn,$query);
		if($isDeleted){
			return true;
		}else{
			return false;
		}
	}

	//function to delere single bet from unsubmitted slip
	function deleteSingleBet($user_id,$bet_option_id,$conn){
		$query="SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND status='unsubmitted' AND type = 'sbook'";
		$unsubmitted_slip=mysqli_fetch_assoc(mysqli_query($conn,$query));
		if(!$unsubmitted_slip['bet_info']){ //when only single bet exist in slip
			$delete_query="DELETE FROM sh_sf_slips WHERE bet_option_id='$bet_option_id' AND user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'";
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
			$update_query="UPDATE sh_sf_slips SET bet_info='$updated_serialized_slip' WHERE user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'";
			
			if(mysqli_query($conn,$update_query)){
				
			$us=mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_info FROM sh_sf_slips WHERE user_id=$user_id AND status='unsubmitted' AND type = 'sbook'"));
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
        $io = mysqli_query($conn,"UPDATE sh_sf_slips SET bet_info= NULL,odd='$od',sodd='$sod',event_id=$ei,event_name='$en',cat_name='$cn',cat_id=$ci,bet_option_id=$boi,bet_option_name='$bon' WHERE user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'");
	   if($io){
		   return true;
	   }else{
		   mysqli_query($conn,"DELETE FROM sh_sf_slips WHERE user_id='$user_id' AND status='unsubmitted' AND type = 'sbook'");
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

	//function to validate odd values
	function validateOdd($bet_option_id,$bet_option_name,$odd,$type,$conn){
		if($type=='sbook'){
		mysqli_query($conn,"SELECT bet_option_odd FROM af_pre_bet_options WHERE bet_option_id=$bet_option_id AND bet_option_name='$bet_option_name' AND bet_option_odd=$odd");
		if (mysqli_affected_rows($conn) > 0){
			return true;
		}else{
			return false;
		}
	} else if($type=='lay'){
		$od = $odd - 0.02;
		mysqli_query($conn,"SELECT bet_option_odd FROM af_pre_bet_options WHERE bet_option_id=$bet_option_id AND bet_option_name='$bet_option_name' AND bet_option_odd=$od");
		if (mysqli_affected_rows($conn) > 0){
			return true;
		}else{
			return false;
		}
	  }
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
		$dline = $mbet['deadline'];
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
		
		$wings = round($stake*$total_odd,2); //max winable lay betting
		if($wings > $max_win_rate){
			$lwinnings = $max_win_rate;
		}else{
			$lwinnings = $wings;
		}
		
		

		/////////////////////submit bet using promo//////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////
		if(!empty($promo) && $chips < $min_bet){ // submit bet using promo
		
				$new_promo=$promo-$stake;
				$lay_promo=$promo-$to_stake;
				$unsubmitted_slip=getUnSubmittedSlip($user_id,$conn);
				
				if($unsubmitted_slip['bet_info']){ // multiple bet
				    $data=unserialize($unsubmitted_slip['bet_info']);
				    if(sizeof($data) < 3){
						echo 'Multibet Need Min. 3 selection';
						die();
					}
					$is_valid=true;
					$have_bets=false;
					$unserialized_slip_multiple=unserialize($unsubmitted_slip['bet_info']);
					if(sizeof($unserialized_slip_multiple) < 3){
						echo 'Multibet Need Min. 3 selection';
						die();
					}
					$total_odds=0;
					foreach($unserialized_slip_multiple as $key=>$bet){
					$event_id = $bet['event_id']; 
		            $cat_id = $bet['cat_id']; 
		          //check if passed deadline
		         $ds = mysqli_query($conn, "SELECT bet_event_id, deadline, is_active FROM af_pre_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$ddl = $sd['deadline'];
		$isactive = $sd['is_active'];
		$tmt = time();
		$nettime = $ddl - $dline;
		
		if ($tmt > $nettime) {
			echo 'Deadline passed for this event';
			die();	
		};
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
		
		           //check if category is disabled
		          $hh = "SELECT bet_event_cat_id FROM af_pre_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	              $hc = $conn->query($hh);
		          $hk = $hc->fetch_assoc();
		          if (!empty($hk)) {
			         echo 'This option is disabled temporarily';
			         die();	
		            };
						$have_bets=true;
						$total_odds=$total_odds+$bet['odd'];
						if(!validateOdd($bet['bet_option_id'],$bet['bet_option_name'],$bet['odd'],$bet['type'],$conn)){
							$is_valid=false;
							//return ['success'=>false,'msg'=>"Check Zeek",'promo'=>$promo,'chips'=>$chips];
						}
					}
					if($have_bets && $is_valid){
						$slip_id=$unsubmitted_slip['slip_id'];
						
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
							   mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='promo' WHERE slip_id='$slip_id'");
							   $raup = rand(99999999, 999999999);	
							   mysqli_query($conn,"UPDATE `sh_sf_slips` SET slip_id = $raup WHERE slip_id = '$slip_id'");
							   mysqli_query($conn, "INSERT INTO sh_sf_slips_history SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET promo=$new_promo WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
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
								mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE slip_id=$cslip_id");
								mysqli_query($conn,"DELETE FROM sh_sf_slips WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET promo=promo + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE slip_id=$ssl_id");
						    mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
							
							
				}else{
					return ['success'=>false,'msg'=>"Failed to submit, Odds Suspended!",'promo'=>$promo,'chips'=>$chips];
				}


                //for promo single bet
				}else {
					if(validateOdd($unsubmitted_slip['bet_option_id'],$unsubmitted_slip['bet_option_name'],$unsubmitted_slip['odd'],$unsubmitted_slip['type'],$conn)){
						
		        $event_id = $unsubmitted_slip['event_id']; 
		        $cat_id = $unsubmitted_slip['cat_id']; 
		         //check if passed deadline
		         $ds = mysqli_query($conn, "SELECT bet_event_id, deadline, is_active FROM af_pre_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$ddl = $sd['deadline'];
		$isactive = $sd['is_active'];
		$tmt = time();
		$nettime = $ddl - $dline;
		
		if ($tmt > $nettime) {
			echo 'Deadline passed for this event';
			die();	
		};
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
		
		         //check if category is disabled
		        $hh = "SELECT bet_event_cat_id FROM af_pre_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	             $hc = $conn->query($hh);
		         $hk = $hc->fetch_assoc();
		         if (!empty($hk)) {
			        echo 'This option is disabled temporarily';
			        die();	
		            };
		
						$slip_id=$unsubmitted_slip['slip_id'];
						
						
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
								
						 mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='promo' WHERE slip_id='$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_slips_history(user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit) SELECT user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit FROM sh_sf_slips WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 ");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET promo=$new_promo WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
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
							mysqli_query($mydb,"INSERT INTO sh_sf_slipsxp (status, date, event_id, event_name, cat_id, cat_name, bet_option_id, bet_option_name, ic, sp) VALUES('awaiting', $cdate, $event_id, '$event_name', $cat_id, '$cat_name', $option_id, '$option_name', $ic, $sp) ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Backed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type = 'sbook'");
								mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET promo=promo + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,bet_option_id,bet_option_name FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$option_name = $kes['bet_option_name'];
							$option_id = $kes['bet_option_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type = 'sbook'");
						    mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}
						
						
					}else{
						return ['success'=>false,'msg'=>"Failed to submit, odds suspended!",'promo'=>$promo,'chips'=>$chips];
					}
				}












         /////////////////////submit bet using CHIPS //////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////

		}else{
			
				$new_chips=$chips-$stake;
				$lay_chips=$chips-$to_stake; 
				
				$unsubmitted_slip=getUnSubmittedSlip($user_id,$conn);
				
				// multiple bet
				if($unsubmitted_slip['bet_info']){ 
					$is_valid=true;
					$have_bets=false;
					$unserialized_slip_multiple=unserialize($unsubmitted_slip['bet_info']);
					if(sizeof($unserialized_slip_multiple) < 3){
						echo 'Multibet Need Min. 3 selection';
						die();
					}
					foreach($unserialized_slip_multiple as $key=>$bet){
		         $event_id = $bet['event_id']; 
				 $cat_id = $bet['cat_id']; 
		         //check if passed deadline
		         $ds = mysqli_query($conn, "SELECT bet_event_id, deadline, is_active FROM af_pre_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$ddl = $sd['deadline'];
		$isactive = $sd['is_active'];
		$tmt = time();
		$nettime = $ddl - $dline;
		
		if ($tmt > $nettime) {
			echo 'Deadline passed for this event';
			die();	
		};
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
		
		        //check if category is disabled
		         $hh = "SELECT bet_event_cat_id FROM af_pre_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	             $hc = $conn->query($hh);
		         $hk = $hc->fetch_assoc();
		          if (!empty($hk)) {
			         echo 'This option is disabled temporarily';
			         die();	
		            };
		
					  $have_bets=true;
						if(!validateOdd($bet['bet_option_id'],$bet['bet_option_name'],$bet['odd'],$bet['type'],$conn)){
							$is_valid=false;
							return ['success'=>false,'msg'=>"One of the Odds Suspended!",'promo'=>$promo,'chips'=>$chips];
						}
					}
					if($have_bets && $is_valid){
						$slip_id=$unsubmitted_slip['slip_id'];

							/////////////////////Chips balance back multibet//////////////////////////////////////
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
							   mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='chips' WHERE slip_id='$slip_id'");
							   $raup = rand(9000000, 99999999);	
							   mysqli_query($conn,"UPDATE `sh_sf_slips` SET slip_id = $raup WHERE slip_id = '$slip_id'");
							   mysqli_query($conn, "INSERT INTO sh_sf_slips_history SELECT * FROM sh_sf_slips WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET chips=$new_chips WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_slips` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
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
								mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE slip_id=$cslip_id");
								mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET chips=chips + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,event_id,bet_option_id FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$event_id = $kes['event_id'];
							$option_id = $kes['bet_option_id'];
						    mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE slip_id=$ssl_id");
						    mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}

				}else{
					return ['success'=>false,'msg'=>"Failed to submit, Odds Suspended..!",'promo'=>$promo,'chips'=>$chips];
				}

				}else { 
				
				
				
				
				//single bet
				
		$event_id = $unsubmitted_slip['event_id']; 
		$cat_id = $unsubmitted_slip['cat_id'];	
		
		//check if passed deadline
		         $ds = mysqli_query($conn, "SELECT bet_event_id, deadline, is_active FROM af_pre_bet_events WHERE bet_event_id = $event_id");
		$sd=mysqli_fetch_assoc($ds);
		$ddl = $sd['deadline'];
		$isactive = $sd['is_active'];
		$tmt = time();
		$nettime = $ddl - $dline;
		
		if ($tmt > $nettime) {
			echo 'Deadline passed for this event';
			die();	
		};
		if ($isactive ==0) {
			echo 'This event is temporarily disabled';
			die();	
		};
		//check if category is disabled
		$hh = "SELECT bet_event_cat_id FROM af_pre_bet_events_cats WHERE bet_event_cat_id = '$cat_id' AND yn IS NOT NULL";
	    $hc = $conn->query($hh);
		$hk = $hc->fetch_assoc();
		if (!empty($hk)) {
			echo 'This option is disabled temporarily';
			die();	
		};
		
			if(validateOdd($unsubmitted_slip['bet_option_id'],$unsubmitted_slip['bet_option_name'],$unsubmitted_slip['odd'],$unsubmitted_slip['type'],$conn)){
						
						$slip_id=$unsubmitted_slip['slip_id'];
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
						   
						    mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='awaiting',`date`='$time',`winnings`='$winnings', `stake`='$stake', `debit`='chips' WHERE slip_id='$slip_id'");
							mysqli_query($conn, "INSERT INTO sh_sf_slips_history(user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit) SELECT user_id,status,stake,winnings,date,bet_info,odd,sodd,event_id,event_name,cat_name,cat_id,bet_option_id,bet_option_name,aid,type,sp,debit FROM sh_sf_slips WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 ");

						if (mysqli_affected_rows($conn) > 0){
							//debit balance
							 mysqli_query($conn,"UPDATE users SET chips=$new_chips WHERE id='$user_id'");
							 
						  if (mysqli_affected_rows($conn) > 0){
							 //insert for settlement
							$tes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
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
							mysqli_query($mydb,"INSERT INTO sh_sf_slipsxp (status, date, event_id, event_name, cat_id, cat_name, bet_option_id, bet_option_name, ic, sp) VALUES('awaiting', $cdate, $event_id, '$event_name', $cat_id, '$cat_name', $option_id, '$option_name', $ic, $sp) ON DUPLICATE KEY UPDATE date = '$cdate'");
							//success message
							if (mysqli_affected_rows($mydb) > 0){
								return ['success'=>true,'msg'=>"<i id='ckck' class='icon check'></i> Success! Backed...",'promo'=>$promo,'chips'=>$chips];
							} else {
								//if insert to settlemnt not success delete history and refunds
								mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type = 'sbook'");
								mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$cslip_id");
							    mysqli_query($conn,"UPDATE users SET chips=chips + $stake WHERE id='$user_id'");
								return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
							 }
								
							}else{
							 //delete records if payment fails
							 $kes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT slip_id,bet_option_id,bet_option_name FROM `sh_sf_slips_history` WHERE user_id=$user_id AND status = 'awaiting' AND type = 'sbook' ORDER BY date DESC LIMIT 0, 1 "));
							$ssl_id = $kes['slip_id'];
							$option_name = $kes['bet_option_name'];
							$option_id = $kes['bet_option_id'];
						   mysqli_query($conn,"UPDATE `sh_sf_slips` SET `status`='unsubmitted' WHERE `user_id`='$user_id' AND `bet_option_id`='$option_id' AND `bet_option_name`='$option_name' AND type = 'sbook'");
						    mysqli_query($conn,"DELETE FROM sh_sf_slips_history WHERE slip_id=$ssl_id");
							return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();	
							}
						}else{
						return ['success'=>false,'msg'=>"Couldn't Match Your Bid, Try again.",'promo'=>$promo,'chips'=>$chips];
								die();
						}		

						
					}else{
						return ['success'=>false,'msg'=>"Failed to submit, Odds Suspended!",'promo'=>$promo,'chips'=>$chips];
					}
				}
				echo 'Nothing doing!';

		}
	}


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
		
		
		
		if($usid == '999999999'){
			echo 'Please login to add';
			die;
		}else{
			$unsubmitted_slip=getUnSubmittedSlip($usid,$conn);
			if($unsubmitted_slip){ //CASE 2
				if($unsubmitted_slip['bet_info']){
					$is_updated=saveMultipleBetCaseTwo($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$sp,$unsubmitted_slip['slip_id'],$unsubmitted_slip,$conn);
					if($is_updated){
							echo "Multiple Bet Updated!";
						}else{
							echo "Cannot mix back & lay bet";
						}
				}else{
					if($unsubmitted_slip['event_id']==$event_id){ 
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

			}else if(saveSingleBet($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$sodd,$btype,$usid,$aid, $sp,$conn)){ //CASE 1
				echo "Option Saved!";
			}else{
				echo "Failed to save!";
			}
		
		};
	
	}

	if(isset($_POST['get_unsubmitted_slip'])){
		$data=getUnSubmittedSlipJson($_POST['user_id'],$conn);
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