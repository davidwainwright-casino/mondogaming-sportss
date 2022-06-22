<?php include_once('../db.php');include_once("__odds_switcher.php");
$event_id = $_POST['evid'];
$spid = $_POST['spid'];
$usid=$_POST['usid'];
$tkn=mysqli_fetch_assoc(mysqli_query($conn,"SELECT serial FROM `www_token`"));$tk = $tkn['serial'];?>

<div class="refevent" id="refevent">
<input id="cevid" value="<?php echo $event_id;?>" hidden/>
<input id="cspid" value="<?php echo $spid;?>" hidden/>

<!-- cashout -->
<?php 
$slip = "SELECT * FROM sh_sf_tickets_records WHERE user_id=$usid AND status='awaiting' AND event_id = $event_id AND bet_info IS NULL AND type = 'sbook'";
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
			$okk = $shsl['bet_option_name']. ' ' .$shsl['bet_option_id'];
			$avaiting_odd_list_back[$okk]+=$winnings;
			$c_back[$okk]=$shsl['stake'];
			$c_odd[$okk]= $shsl['odd'];
			$s_back[$okk]= $shsl['slip_id'];
		}


//GET EVENTS

function getEventData($event_id,$conn){
	function maxi_bet($conn){
		$query="SELECT deadline FROM risk_management";
		$mxbt=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($mxbt);
		}
	$mbet = maxi_bet($conn);
	$dline = $mbet['deadline'];
		$query="SELECT * from af_pre_bet_events join af_pre_bet_events_cats on af_pre_bet_events.bet_event_id=af_pre_bet_events_cats.bet_event_id join af_pre_bet_options on af_pre_bet_events_cats.bet_event_cat_id=af_pre_bet_options.bet_event_cat_id WHERE af_pre_bet_events.bet_event_id=$event_id AND af_pre_bet_events.is_active=1 AND UNIX_TIMESTAMP() < (af_pre_bet_events.deadline - $dline)";
		$event_data=mysqli_query($conn,$query);
		$structured_data=array();
		$temp_cat_id='';
		$event_name='';
		while($row=mysqli_fetch_assoc($event_data)){
			if($event_name==''){
				$event_name=$row['bet_event_name'];
				$spid=$row['spid'];
				$deadline=$row['deadline'];
			}
			if($temp_cat_id==$row['bet_event_cat_id']){
				$option_name=$row['bet_option_name'];
				$option_id=$row['bet_option_id'];
				$odd=$row['bet_option_odd'];
				$o_sort=$row['o_sort'];
				$bet_option=['bet_option_name'=>$option_name,'bet_option_id'=>$option_id,'odd'=>$odd,'o_sort'=>$o_sort];
				array_push($structured_data[strval($temp_cat_id)]['bet_options'],$bet_option);
			}else{
				$temp_cat_id=$row['bet_event_cat_id'];
				$cat_name=$row['bet_event_cat_name'];
				$cat_id=$row['bet_event_cat_id'];
				$spid=$row['spid'];
				$option_name=$row['bet_option_name'];
				$option_id=$row['bet_option_id'];
				$odd=$row['bet_option_odd'];
				$c_sort=$row['c_sort'];
				$o_sort=$row['o_sort'];
				$bet_option=['bet_option_name'=>$option_name,'bet_option_id'=>$option_id,'odd'=>$odd,'o_sort'=>$o_sort];	
				$structured_data[strval($temp_cat_id)]=['cat_name'=>$row['bet_event_cat_name'],'cat_id'=>$row['bet_event_cat_id'],'c_sort'=>$c_sort,'spid'=>$spid,'bet_options'=>[$bet_option]];
			}
		}


		return array('event_id'=>$event_id,'event_name'=>$event_name,'deadline'=>$deadline,'spid'=>$spid,'categories'=>$structured_data);
	}


	$bet_event_data=getEventData($event_id,$conn);
	
	if (empty($bet_event_data['categories'])) {
	echo '<div class="opno"><h3>Temporarily Suspended</h3> <i id="trrps" class="icon trophy"></i></div>';
	}
	
	
	
	
	
	
	
	//FOR CATEGORY
	$betEventName = $bet_event_data['event_name'];
	$spid = $bet_event_data['spid'];
	$etype = 'pre';
	$i = 1;
	foreach ($bet_event_data['categories'] as $key => $category) {
			$cat_name = $category['cat_name'];
            $cat_id=$category['cat_id'];
            echo "<div id='".$cat_id."' class='".$cat_name."'>";
            echo '<div class="catfid" id="d'.$cat_id.'"> '.$cat_name.' <i id="catcol" class="icon angle down"></i></div>';
			echo '<div class="catheader hid'.$i++.'" id="c'.$cat_id.'">';
		//FOR OPTIONS
		foreach ($category['bet_options'] as $key_options => $option) {
								  $boi = $option['bet_option_id'];
                                  $bon = $option['bet_option_name'];
								  $bod = $option['odd'];
								  $bodk = bgetOdd($bod);
								  $okid = $bon. ' ' .$boi;
								  $cstake = $c_back[$okid];
								  $kmid = $boi. '' .$bon;
								  $smid=str_replace(' ', '', $kmid);
								  $ult = $avaiting_odd_list_back[$okid]/$bod; $nt = $ult * 20/100; $ut = $ult - $nt;
									 echo '<div id="btheader"><div class="b_option_odd evn-'.$bon.' hsu'.(int)($bodk).'" id="bet__option__btn__'.$event_id.'__'.$betEventName.'__'.$cat_id.'__'.$cat_name.'__'.$boi.'__'.$bon.'__'.$spid.'__'.$bodk.'__'.$bod.'__'.$etype.'">';?>
										<span class="bodname"><?php echo $bon;?><?php if(!empty($cstake)):?><span class="shwinnings"><?php echo $winnings;?></span><span class="casout dod<?php echo $bod;?>" id="<?php echo $s_back[$okid];?>">Cash <?php echo round($ut, 2);?></span><?php endif;?></span>
										<?php echo '<span class="bback" id="cor-'.$boi.'">' . $bodk .'</span>
										</div></div>';

			
			
			
		}
		echo '</div></div>';
		
	}
	echo '</div></br></br>'; 









