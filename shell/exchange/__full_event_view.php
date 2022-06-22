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
?>

<?php if($spid==3):?>


<?php $url='https://api.b365api.com/v1/bet365/event?token='.$tk.'&FI='.$event_id.'';
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
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
		$result=json_decode($data,true);
		//var_dump($result);
		//added
		if(empty($result['results'])){
			echo '<div id="rvsus">Event Suspended</div>';
		}

        $newArray=array();
        $event_details=array('event_name'=>'','event_id'=>$event_id,'team'=>'');
        if(isset($result['results'])){
            foreach($result['results'][0] as $j) {
            if($j['type']=='EV'){
                $event_details['event_name']=$j['NA'];
            }    
            if($j['type']=='MG'){
                array_push($newArray, array());
                $newArray[sizeof($newArray)-1]['cat_name']=$j['NA'];
                $newArray[sizeof($newArray)-1]['cat_id']=$j['ID'];

            }
			
			
			
			
			
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
	                                    $odd = explode('/', $j['OD']);
										$od = round(($odd[0] / $odd[1]), 2) + 1;
										$odd = round($od,2);
	                                    array_push($newArray[sizeof($newArray) - 1], array(
	                                        'odd_name' => $okk.' '.$jna.' '.$okhd,
	                                        'odd_id' => $j['ID'],
	                                        'odd' => $odd,
	                                        'su' => $j['SU']
	                                    ));
									  }else{
										$ms = 'Suspended';
	                            $od = '0.00';
	                            array_push($newArray[sizeof($newArray) - 1], array(
	                                'odd_name' => $ms,
	                                'odd_id' => $j['ID'],
	                                'odd' => $od,
	                                'su' => $j['SU']
	                            ));
										
									 }
									}
	              
	                    //}
	                }
			
         }
	   }
		
		if(empty($result['results'])){
				//echo '<div class="suspde">Event Suspended!</div>';
				$sus = '<div id="rvsus">Event Suspended</div>';
			}


        foreach ($newArray as $key => $value) {
          if(!isset($newArray[1])){
            unset($newArray[$key]);
          }
        }

        $betEventName=$event_details['event_name'];
		$type='live';

        echo '<div class="evt-name" id="evtid-'.$event_details['event_id'].'">'.$betEventName.'</div>';
		echo $sus;
		$i=1;
        foreach ($newArray as $key => $value) {
		  $ro = $value['cat_name'];
          if(isset($value[1])){
            if (sizeof($value[1]) > 0 && empty(preg_match("/^Runs off (.*)/i", $ro)) && empty(preg_match("/^Milestones (.*)/i", $ro)) && empty(preg_match("/^Wickets Lost for (.*)/i", $ro)) && empty(preg_match("/^Wickets Lost for 50 Runs (.*)/i", $ro)) && $ro !== 'Method of Dismissal 6-Way' && $ro !== 'To Score Most Runs' && $ro !== 'To Score Most Runs - Group' && $ro !== 'Dismissal Method' && $ro !== 'Next Man Out' && empty(preg_match('/\b(?: - Runs Odd/Even)\b/i', $ro)) && empty(preg_match("/^Runs in First (.*)/i", $ro)) && empty(preg_match('/\b(?: Runs off 1st Delivery, 1st Over)\b/i', $ro)) && empty(preg_match('/\b(?: Wickets Lost for)\b/i', $ro)) && empty(preg_match('/\b(?: Milestones)\b/i', $ro))) {
			 $cat_name=$value['cat_name'];
             $cat_id=$value['cat_id']; 	
             echo "<div id='".$value['cat_id']."'>";
            echo '<div class="catfid" id="d'.$event_id.''.$value['cat_id'].'"> '.$value['cat_name'].' <i id="catcol" class="icon angle down"></i></div>';
            echo '<div class="catheader hid'.$i++.'" id="c'.$event_id.''.$cat_id.'">';
			$bet_option_order = 1;
            foreach ($value as $key_child => $value_child) {
            if(is_array($value_child)){
				$bod = $value_child['odd'];
				$bodk = bgetOdd($value_child['odd']);
				$boi = $value_child['odd_id'];
				$bon = $value_child['odd_name'];
				$okid = $bon. ' ' .$boi;
				$cstake = $c_back[$okid];
				$kmid = $boi. '' .$bon;
				$smid=str_replace(' ', '', $kmid);
				$ult = $avaiting_odd_list_back[$okid]/$bod; $nt = $ult * 20/100; $ut = $ult - $nt;
				
				
				
				
				
                echo '<div id="btheader"><div class="b_option_odd evn-'.$bon.' hsu'.(int)($bodk).'" id="bet__option__btn__'.$event_id.'__'.$betEventName.'__'.$cat_id.'__'.$cat_name.'__'.$boi.'__'.$bon.'__'.$spid.'__'.$bodk.'__'.$bod.'__'.$type.'">';?>
										<span class="bodname"><?php echo $bon;?><?php if(!empty($cstake)):?><span class="shwinnings"><?php echo $winnings;?></span><span class="casout dod<?php echo $bod;?>" id="<?php echo $s_back[$okid];?>">Cash <?php echo round($ut, 2);?></span><?php endif;?></span>
										<?php echo '<span class="bback" id="cor-'.$boi.'">' . $bodk .'</span>
										</div></div>';
            }
			$bet_option_order++;	
            }
            echo "</div></div>";
            }
          }
        }
 ?>



























<?php else:?>


<?php $url="https://api.betsapi.com/v1/bwin/event?token=".$tk."&event_id=".$event_id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch) or die(curl_error($ch));
        if ($data === false) {
            echo 'Event Suspended';
        }
        curl_close($ch);
        $json1 = $data;
        $a = explode('string', $json1);
        for($k =0; $k<count($a); $k++){
            $bet_option_sort_order = 1;
            $w = trim($a[$k]);
            if(count($a) > 1){
             $w = strstr($w, '"');
            }
            $json = trim($w, '"');
            $obj = json_decode($json, true);
            $b = $obj['results'];
			if(empty($b)){
				echo '<div id="rvsus">Event Suspended</div>';
			}
            for($i =0; $i<count($b); $i++){
               //$bet_event_sort_order = 1;
             $spid = $b[$i]['SportId'];
			 $sport_name = $b[$i]['SportName'];
			 $league_id = $b[$i]['LeagueId'];
			 $event_name = $b[$i]['LeagueName'];
			 $cc = $b[$i]['RegionName'];
			 $type='live';
			 echo '<div class="lgnms">'.$event_name. ' ('.$cc.')</div>';
         echo '<div class="evt-name" id="evtid-'.$event_id.'">'.$bet_event_name = $b[$i]['HomeTeam'].' - '. $b[$i]['AwayTeam'].' <a class="vbetradr" target="_blank" href="https://s5.sir.sportradar.com/bwin/en/match/'.$b[$i]['BetRadarId'].'"><i class="fa fa-bar-chart"></i></a></div>';?>
		 <?php 
         $deadline = $b[$i]['updated_at'];
         $betEventName = $b[$i]['HomeTeam']." - ".$b[$i]['AwayTeam'];      
             $c = $b[$i]['Markets'];
			 if(empty($c)){
				 echo '<div id="rvsus">Event Suspended</div>';
			 }
			 $i = 1;
           for($j =0; $j<count($c); $j++){
            $bet_option_sort_order = 1;
            $cat_name = $c[$j]['name']['value'];
            /*if($sport_id == "22" || $bet_event_cat_name == "2Way - Who will win? (Dead heat rules apply)" || $bet_event_cat_name == "Match Result" || $bet_event_cat_name == "Double Chance" || $bet_event_cat_name == "Both Teams to Score" || $bet_event_cat_name == "Draw no bet" || $bet_event_cat_name == "Half Time result" || $bet_event_cat_name == "Total Goals - Over/Under"){*/
            $cat_id=$c[$j]['id'];
            echo "<div id='".$cat_id."'>";
            echo '<div class="catfid" id="d'.$cat_id.'"> '.$c[$j]['name']['value'].' <i id="catcol" class="icon angle down"></i></div>';  
			$cvisibility=$c[$j]['visibility'];
			if($cvisibility !='Suspended'){
            $d = $c[$j]['results'];
                              $bet_option_order = 1;
							  echo '<div class="catheader hid'.$i++.'" id="c'.$cat_id.'">';
                              foreach ($d as $e) {
                                  $boi = $e['id'];
                                  $visible = $e['visibility'];
                                  $bon = $e['name']['value'];
								  $bod = $e['odds'];
								  $bodk = bgetOdd($e['odds']);
								  $okid = $bon. ' ' .$boi;
								  $cstake = $c_back[$okid];
								  $kmid = $boi. '' .$bon;
								  $smid=str_replace(' ', '', $kmid);
								  $ult = $avaiting_odd_list_back[$okid]/$bod; $nt = $ult * 20/100; $ut = $ult - $nt;
                                  
                                  if($visible == "Visible"){
									 echo '<div id="btheader"><div class="b_option_odd evn-'.$bon.' hsu'.(int)($bodk).'" id="bet__option__btn__'.$event_id.'__'.$betEventName.'__'.$cat_id.'__'.$cat_name.'__'.$boi.'__'.$bon.'__'.$spid.'__'.$bodk.'__'.$bod.'__'.$type.'">';?>
										<span class="bodname"><?php echo $bon;?><?php if(!empty($cstake)):?><span class="shwinnings"><?php echo $winnings;?></span><span class="casout dod<?php echo $bod;?>" id="<?php echo $s_back[$okid];?>">Cash <?php echo round($ut, 2);?></span><?php endif;?></span>
										<?php echo '<span class="bback" id="cor-'.$boi.'">' . $bodk .'</span>
										</div></div>';
								  
                  }else{
					  
					  
				  }
          //global $wpdb; UPDATE sh_sf_tickets WHERE user_id = '133'
                }
				echo '</div>';
			}else{?>
				<div class="suswraper"><span class="catsuspendeds">
				<?php $sus = str_replace("'","''",$bon );
				if(!empty($sus)){
					echo $sus;
				}else{
					echo 'Suspended';
				}?>
				</span>
				
				<?php
				echo '<a href="#" class="susodd"><i id="susplock" class="icon lock"></i></a></div>';
				
			}
                echo "</div>";
            //}
            
          }; echo '</br></br>'; 
         } 
       };?>
	   
	   
	<?php endif;?>   
	     
   </div>