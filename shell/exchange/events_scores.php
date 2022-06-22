<?php //error_reporting(0); error_reporting(E_ALL ^ E_NOTICE);
	include_once('../db.php');
   //error_reporting(0);
  $tkn=mysqli_fetch_assoc(mysqli_query($conn,"SELECT serial FROM `www_token`"));$tk = $tkn['serial']; 
	function pull_all_event_ids($spid,$conn){
		$query="SELECT bet_event_id FROM af_inplay_bet_events WHERE spid<>3";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		return array_chunk($event_id_array,10);
	}  
	
	function insertEvents($event_ids,$spid,$tk,$conn){
		mysqli_query($conn,"DELETE FROM sh_sf_events_scores WHERE status<>1");
		//mysqli_autocommit($conn,FALSE); //transaction begin

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			$imp = implode(",",$event_ids_set);
			//$br = mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_event_id,bradar, bet_event_name,event_name FROM af_inplay_bet_events WHERE bet_event_id='$imp'"));
			//$sf_inplay_id = $br['bet_event_id'];
			//$sf_betradar = $br['bradar'];
			//$sf_betradar = $b[$i]['id'];
			//$sf_name = $br['bet_event_name'];
			//$league_name = $br['event_name'];
			
			echo $conn->error;
		$url = "https://api.betsapi.com/v1/bwin/result?token=" . $tk . "&event_id=" . implode(",",$event_ids_set);
			//echo "<h1 style='color:green'>api callled</h1>";
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
			$a = explode('string', $data);
			
			
			
			
			
			
			   for($k = 0; $k<count($a); $k++){
            $w = trim($a[$k]);
            if(count($a) > 1){
                $w = strstr($w, '"');
            }
            $json = trim($w, '"');
            
            $obj = json_decode($json, true);
            $b = $obj['results'];
            for($i =0; $i<count($b); $i++){
			$sf_inplay_id = $b[$i]['bwin_id'];	
			$br = mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_event_id,bradar, bet_event_name,event_name FROM af_inplay_bet_events WHERE bet_event_id='$sf_inplay_id'"));
			//$sf_inplay_id = $br['bet_event_id'];
			$sf_betradar = $br['bradar'];	
				
			//$sf_betradar = $b[$i]['id'];
			$league_name = $b[$i]['league']['name'];
            $sf_sport_id = $b[$i]['sport_id'];
			$sf_name = $b[$i]['home']['name']. ' - ' .$b[$i]['away']['name'];
			
			$ss = $b[$i]['ss'];
			if( !empty($ss)){
                $sf_score = $b[$i]['ss'];
			}else{
				$sf_score = 0-0;
			}
			
			$timer = $b[$i]['timer']['tm'];			
			if( !empty($timer)){
                $sf_timer = $b[$i]['timer']['tm'];
			}else{
				$sf_timer = 0-0;
			}
	
			
			$hm = $b[$i]['home']['image_id'];
			if($hm < 1){
				$img_home = 'NULL';
			}else{
				$img_home = $b[$i]['home']['image_id'];
			}
			
			$aw = $b[$i]['away']['image_id'];
			if($aw < 1){
				$img_away = 'NULL';
			}else{
				$img_away = $b[$i]['away']['image_id'];
			}
			
			/*
			$homeurl=file_get_contents('https://assets.b365api.com/images/team/b/'.$img_home.'.png');
			$h_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_home.'.png';
			$h_upload =file_put_contents($h_put_file, $homeurl);
			
			$awayurl=file_get_contents('https://assets.b365api.com/images/team/b/'.$img_away.'.png');
			$a_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_away.'.png';
			$a_upload =file_put_contents($a_put_file, $awayurl);
			
			file_put_contents(c.php, c.php);
			*/
			//store image in database
			//$save_img = "INSERT INTO sh_sf_images(bet_event_id,img_home,img_away) VALUES($sf_inplay_id,$ihome,$iaway)";
			//mysqli_query($conn,$save_img);
			//echo $conn->error;
			
			
			
            $attacks = $b[$i]['stats']['attacks'][0]. '-' .$b[$i]['stats']['attacks'][1];
			if( !empty($attacks)){
                $sf_attacks = $b[$i]['stats']['attacks'][0]. '-' .$b[$i]['stats']['attacks'][1];
			}	
			
			$corners = $b[$i]['stats']['corners'][0]. '-' .$b[$i]['stats']['corners'][1];
			if (!empty($corners)){
				$sf_corners = $b[$i]['stats']['corners'][0]. '-' .$b[$i]['stats']['corners'][1];
			}
			
			$dang_attacks = $b[$i]['stats']['dangerous_attacks'][0]. '-' .$b[$i]['stats']['dangerous_attacks'][1];
			if (!empty($dang_attacks)){
				$sf_dang_attacks = $b[$i]['stats']['dangerous_attacks'][0]. '-' .$b[$i]['stats']['dangerous_attacks'][1];
			}
			
			$penalties = $b[$i]['stats']['penalties'][0]. '-' .$b[$i]['stats']['penalties'][1];
			if(!empty($penalties)){
				$sf_penalties = $b[$i]['stats']['penalties'][0]. '-' .$b[$i]['stats']['penalties'][1];
			}
			
			$on_target = $b[$i]['stats']['on_target'][0]. '-' .$b[$i]['stats']['on_target'][1];
			if(!empty($on_target)){
				$sf_on_target = $b[$i]['stats']['on_target'][0]. '-' .$b[$i]['stats']['on_target'][1];
			}
			
			$off_target = $b[$i]['stats']['off_target'][0]. '-' .$b[$i]['stats']['off_target'][1];
			if(!empty($off_target)){
				$sf_off_target = $b[$i]['stats']['off_target'][0]. '-' .$b[$i]['stats']['off_target'][1];
			}
			
			$possession = $b[$i]['stats']['possession_rt'][0]. '-' .$b[$i]['stats']['possession_rt'][1];
			if(!empty($possession)){
			$sf_possession = $b[$i]['stats']['possession_rt'][0]. '-' .$b[$i]['stats']['possession_rt'][1];
			}
			
			$red = $b[$i]['stats']['redcards'][0]. '-' .$b[$i]['stats']['redcards'][1];
			if(!empty($red)){
				$sf_red = $b[$i]['stats']['redcards'][0]. '-' .$b[$i]['stats']['redcards'][1];
			}
			
			$yellow = $b[$i]['stats']['yellowcards'][0]. '-' .$b[$i]['stats']['yellowcards'][1];
			if(!empty($yellow)){
				$sf_yellow = $b[$i]['stats']['yellowcards'][0]. '-' .$b[$i]['stats']['yellowcards'][1];
			}
			
			$sf_ho_shots = $b[$i]['stats']['shots'][0]. '-' .$b[$i]['stats']['shots'][1]; 
			$sf_status = $b[$i]['time_status']; 
			$commy = $b[$i]['events']; 
			$events = json_encode($commy);
		    $sf_comm = str_replace("'", "''", "$events");
			//print_r($commm); ; echo '</br></br>';			
            foreach ($commy as $row1){
                   $rm = $row1; 
				   //$sf_comm = str_replace("'", "''", "$rm");
				   }

			  
			     $bwin_scores = "INSERT INTO sh_sf_events_scores (bet_event_id, our_id, spid, league, e_name, score, timer, atts, dan_atts, cor, pen, on_tar, off_tar, poss, red, yelo, comm, status, img_h_id, img_a_id) VALUES ('$sf_inplay_id', '$sf_betradar ', '$sf_sport_id', '$league_name', '$sf_name', '$sf_score', '$sf_timer', '$sf_attacks', '$sf_dang_attacks', '$sf_corners', '$sf_penalties', '$sf_on_target', '$sf_off_target', '$sf_possession', '$sf_red', '$sf_yellow', '$sf_comm', '$sf_status', $img_home, $img_away) ON DUPLICATE KEY UPDATE score='$sf_score', timer='$sf_timer', atts='$sf_attacks', dan_atts='$sf_dang_attacks', cor='$sf_corners', pen='$sf_penalties', on_tar='$sf_on_target', off_tar='$sf_off_target', poss='$sf_possession', red='$sf_red', yelo='$sf_yellow', comm='$sf_comm', status='$sf_status' ";
				 mysqli_query($conn,$bwin_scores);
				 //echo $conn->error;
				 if (mysqli_affected_rows($conn) > 0){
	                echo "Update Successfully";
					}else{
					echo $conn->error;
					}
				 }
            }

		}
	}
	
	
	
	
	
	
	
	
	
	
	
	


//cricket results starts
function pull_all_event_ids_cricket($spid,$conn){
		$query="SELECT bet_event_id FROM af_inplay_bet_events WHERE spid = 3";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		return $event_id_array;
	}
	
	function insertEventsCricket($event_ids,$spid,$tk,$conn){
		 mysqli_query($conn,"DELETE FROM sh_sf_events_scores WHERE spid=3");
		//mysqli_autocommit($conn,FALSE); //transaction begin

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			
			$url = 'http://api.betsapi.com/v1/bet365/event?token='.$tk.'&FI=' . $event_ids_set;
			$bet_event_id=$event_ids_set;
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
			
			
			
			$result=json_decode($data,true);

        $newArray=array();
        $event_details=array('event_name'=>'','event_id'=>$event_id,'team'=>'');
        if(isset($result['results'])){
            foreach($result['results'][0] as $j) {
				if($j['type']=='EV'){
					$summary = $j['EX'];
					$e_name = $j['CC'];
				}
            if($j['type']=='TE' && empty($j['S5']) && !empty($j['SC'])){ //fielding team
				$home = $j['NA'];
				$home_score = $j['SC'];//
				$field_score = $home.' - '.$j['SC'];
				$bowlers = $j['S8'];
				$es = explode('#', $bowlers);
				$over = ''.$es[0].''; //overs
				$bowler = ''.$es[1].'';//bowling
				
            } else if($j['type']=='TE' && !empty($j['S5'])){  //batting team
				$away = $j['NA'];
				$away_score = $j['SC'];
				$bat_score = $away.' - '.$j['SC'];
				 
                $batsmans = $j['S7'];
				$bs = explode('#', $batsmans);
				$batsman = ''.$bs[0].'';//on strike
				$onruns = ''.$j['S1'].'';//batsman run
				//echo $j['S8'];echo '</br>';
				
            } else if($j['type']=='ES'){
                $latestscore = $j['AD'];

            }
			

         }
	   }
	  $sf_name = $home. ' - ' .$away;
	  $sf_score = $home_score. ',' .$away_score;
	  $sf_sport_id = 3;
	  $sf_status = 1;
	
			  
			     $cric_scores = "INSERT INTO sh_sf_events_scores (bet_event_id, spid, league, e_name, score, timer, atts, dan_atts, cor, pen, on_tar, off_tar, poss, red, yelo, comm, status) VALUES ($bet_event_id, $sf_sport_id, '$e_name', '$sf_name', '$sf_score', '$summary', '$over', '$bowler', '$batsman', '$onruns', '$latestscore', '$bat_score', '$field_score', '$sf_red', '$sf_yellow', '$sf_comm', '$sf_status')  ON DUPLICATE KEY UPDATE score='$sf_score', timer='$summary', atts='$over', dan_atts='$bowler', cor='batsman', pen='onruns', on_tar='$latestscore', off_tar='$bat_score', poss='$field_score', red='$sf_red', yelo='$sf_yellow', comm='$sf_comm', status='$sf_status'";
				 mysqli_query($conn,$cric_scores); 
				 
				 if (mysqli_affected_rows($conn) > 0){
	                echo "Update Successfully";
					}else{
					echo $conn->error;
					}
	
			  }  			 
	      }

	        
	            



	insertEvents(pull_all_event_ids(5,$conn),5,$tk,$conn);
	insertEventsCricket(pull_all_event_ids_cricket(3,$conn),3,$tk,$conn);
 ?>

