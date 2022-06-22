<?php include_once('../db.php');

	//function to add single bet
	function saveSingleBet($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$usid,$conn){
			$query="INSERT INTO `sh_sf_slips_prematch` (`user_id`, `status`, `stake`, `winnings`, `date`, `odd`, `event_id`, `event_name`, `cat_name`, `cat_id`, `bet_option_id`, `bet_option_name`, `type`) VALUES ($usid,'unsubmitted',0,0,'14464','$odd','$event_id','$event_name','$cat_name','$cat_id','$oid','$oname','$btype')";
			if(mysqli_query($conn,$query)){
				return true;
			}else{
				return false;
			}
	}

	//function to get unsubmitted slip of user (each can have max one unsubmitted slip)
	function getUnSubmittedSlip($user_id,$conn){
		$query="SELECT * FROM sh_sf_slips_prematch WHERE user_id='$user_id' AND status='unsubmitted'";
		$unsubmitted_slip=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($unsubmitted_slip);
	}

	//function to update slip which have only one saved bet and same event and same category
	function updateSameEventCategorySlip($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$slip_id,$conn){
		$query="UPDATE `sh_sf_slips_prematch` SET `stake`=0, `winnings`= 0, `date`='14464', `odd`='$odd', `event_id`='$event_id', `event_name`='$event_name', `cat_name`='$cat_name', `cat_id`='$cat_id', `bet_option_id`='$oid', `bet_option_name`='$oname', `type`='$btype' WHERE slip_id='$slip_id'";
		if(mysqli_query($conn,$query)){
			return true;
		}else{
			return false;
		}
	}

	//function to save bet when one bet already exist in slip and new bet have different category (max one bet save per category)
	function saveMultipleBetCaseOne($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$slip_id,$old_bet,$conn){
		$existing_bet=['stake'=>0, 'winnings'=>0, 'date'=>$old_bet['date'], 'odd'=>$old_bet['odd'], 'event_id'=>$old_bet['event_id'], 'event_name'=>$old_bet['event_name'], 'cat_name'=>$old_bet['cat_name'], 'cat_id'=>$old_bet['cat_id'], 'bet_option_id'=>$old_bet['bet_option_id'], 'bet_option_name'=>$old_bet['bet_option_name'], 'type'=>$old_bet['type']];
		$new_bet=['stake'=>0, 'winnings'=>0, 'date'=>'1545214', 'odd'=>$odd, 'event_id'=>$event_id, 'event_name'=>$event_name, 'cat_name'=>$cat_name, 'cat_id'=>$cat_id, 'bet_option_id'=>$oid, 'bet_option_name'=>$oname, 'type'=>$btype];
		$bets=serialize(array($existing_bet,$new_bet));
		$query="UPDATE `sh_sf_slips_prematch` SET bet_info='$bets' WHERE slip_id='$slip_id'";
		if(mysqli_query($conn,$query)){
			return true;
		}else{
			return false;
		}
	}

	//function to save bet when bet_info column is used before.(CASE 2 triggers when bet_info column used once)
	function saveMultipleBetCaseTwo($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$slip_id,$old_bet,$conn){
		//TODO 	
	}

	$event_name=$_POST['event_name'];
	$cat_name=$_POST['cat_name'];
	$event_id=$_POST['event_id'];
	$cat_id=$_POST['cat_id'];
	$oid = $_POST['oid'];
	$oname = $_POST['oname'];
	$btype = $_POST['btype'];
	$usid = $_POST['usid'];
	$odd=$_POST['odd'];
	$btype=$_POST['btype'];
	if($usid == '999999999'){
		echo 'Please login to add';
		die;
	}else{
		$unsubmitted_slip=getUnSubmittedSlip($usid,$conn);
		if($unsubmitted_slip){ //CASE 2
			if($unsubmitted_slip['bet_info']){
				//CASE 2 TODO
				echo "CASE 2 TO DO";
			}else{
				if($unsubmitted_slip['event_id']==$event_id && $unsubmitted_slip['cat_id']==$cat_id){ 
					$isUpdated=updateSameEventCategorySlip($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$unsubmitted_slip['slip_id'],$conn);
					if($isUpdated){
						echo "Updated!";
					}else{
						echo "Failed to Update!";
					}
				}else{ 
					//CASE ONE TODO
					$isUpdated=saveMultipleBetCaseOne($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$unsubmitted_slip['slip_id'],$unsubmitted_slip,$conn);
					if($isUpdated){
						echo "Multiple Bet Updated!";
					}else{
						echo "Failed to Update Multiple Bet!";
					}
				}
			}

		}else if(saveSingleBet($event_name,$event_id,$cat_name,$cat_id,$oname,$oid,$odd,$btype,$usid,$conn)){ //CASE 1
			echo "Bet Saved!";
		}else{
			echo "Failed to save!";
		}

		

	//first step
	//existing unsubmitted slips verify with event id and option

	// $sql_pre = "SELECT e.deadline, e.spid, e.event_name, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
	//             FROM af_pre_bet_events e
	//             JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
	//             JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
	//             $filter
	//             ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort LIMIT 26";
				
				//first step
	//existing unsubmitted slips verify with event id and option
	
}
?>