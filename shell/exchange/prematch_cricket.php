<?php include_once('../db.php');
$xr = "SELECT serial FROM `www_token` ";
$result = mysqli_query($conn, $xr);
$c_row = $result->fetch_assoc();
$tk = $c_row['serial'];
$tnow = time();
//mysqli_query($conn,"DELETE FROM af_pre_bet_events WHERE is_active = '1' AND feat = '0'");
mysqli_query($conn,"DELETE FROM af_pre_bet_events WHERE $tnow > deadline");
mysqli_query($conn,"DELETE FROM af_pre_bet_events_cats WHERE spid=3 AND yn IS NULL");
mysqli_query($conn,"DELETE FROM af_pre_bet_options WHERE bet_event_cat_id > 1000000 AND spid=3");


//insert cricket bet365

$url = 'https://api.betsapi.com/v1/bet365/upcoming?token='.$tk.'&sport_id=3';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
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
$obj = json_decode($data, true);  

//echo $obj['results']['id'];
foreach($obj['results'] as $result){
    $bet_event_id = $result['id'];echo '</br>';
	$deadline = $result['time'];echo '</br>';
	$event_id = $result['league']['id'];echo '</br>';
	$event_name = $result['league']['name'];echo '</br>';
	$e_name =  $result['home']['name'].' - '. $result['away']['name'];
             $ev_name = addslashes($e_name);
             if (!empty($ev_name)){
            $bet_event_name = $ev_name;
             } else{
             $bet_event_name = addslashes($event_name);      
             };
	$bradar = 	$result['our_event_id'];
	$homeo = $result['home']['name'];
	$awayo = $result['away']['name'];
	 /*
			 if($sport_id == '22' && $event_name == 'Test Matches'){
             $deadline = strtotime($b[$i]['Date']) - 345600; //432000;
			 }else{
			 $deadline = strtotime($b[$i]['Date']);
			 }			 			 
			 
			 $date = new DateTime(); 
			 $now = $date->getTimestamp();
			 */
			 
			 ///////////////////INSERT STARTS//////////////////////
			 //insert sf_events
			 $bet_events = 'af_pre_bet_events';
			 $af_bet_events = "INSERT IGNORE INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bradar', '$bet_event_name', '$deadline', '1', '$event_id', '$event_name', '3', 'World', 'Cricket')";
			 
			 if ($conn->query($af_bet_events) === TRUE) {
				// echo 'inserted';
			 } else {
				 echo "Error: " . $af_bet_events . "<br>" . $conn->error;		 
			 }

			 
	
    $url='https://api.betsapi.com/v3/bet365/prematch?token='.$tk.'&FI='.$bet_event_id;
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
   // var_dump($data);
    $result = json_decode($data, true);
    foreach ($result['results'] as $j) {
		$b = $j['main']['sp'];
		$c = $j['others'];
        foreach($b as $ca){
			
			 //var_dump($ca);
			 //INSERT BET EVENTS CATS
			  
			  //echo $ca['name'];
              //for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $ca['id']+rand(100000000, 999999999);;
				  $bet_event_cat_name = addslashes($ca['name']);
				  $nodd = $ca['odds'];
				  
            if(!empty($nodd)){

			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
							
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '3')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				// echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
				  
 
			 
			 
			 //INSERT BET OPTIONS
			 
			   $d = $ca['odds'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $odd_header = addslashes($e['header']);
                   $oname = addslashes($e['name']);
				   $team_header = addslashes($e['team']);
				   if(!empty($odd_header && $team_header)){
				     $bet_option_name = $odd_header.' '.$oname.' - '.$team_header;
				    }else if(!empty($odd_header)){
					 $bet_option_name = $odd_header.' '.$oname;
					}
					else{
				    $bet_option_name = $oname;
				    }
                   $bet_option_odd = $e['odds'];
				   if($bet_option_name == 1){
						$bet_option_name = $homeo;
						
					}else if($bet_option_name == 2){
						$bet_option_name = $awayo;
					}
				   
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			//if($ovisible == 'Visible'){

				
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id, spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id', '3') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }
				   

			    //} //if ovisible
			   } //bet options markets
			   // if visible..
		      //} //if category is
			  //} //cat markets
		}
			 
		}
		
		
		
			
		//other categories
		
		foreach($c as $sa){
			foreach ($sa['sp'] as $ca){
			 //var_dump($ca);
			 //INSERT BET EVENTS CATS
			  
			  //echo $ca['name'];
              //for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $ca['id']+rand(100000000, 999999999);;
				  $bet_event_cat_name = addslashes($ca['name']);
				  $nodd = $ca['odds'];
				  
            if(!empty($nodd)){
				if($bet_event_cat_name !=='Match Handicap' && $bet_event_cat_name !='Batsman Match Runs'){

			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
							
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '3')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				// echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
				  
 
			 
			 
			 //INSERT BET OPTIONS
			 
			   $d = $ca['odds'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $odd_header = addslashes($e['header']);
                   $oname = addslashes($e['name']);
				   $team_header = addslashes($e['team']);
				   if(!empty($odd_header && $team_header)){
				     $bet_option_name = $oname.' - '.$team_header;
				    }else if(!empty($odd_header)){
					 $bet_option_name = $odd_header.' '.$oname;
					}
					else{
				    $bet_option_name = $oname;
				    }
                   $bet_option_odd = $e['odds'];
				   if($bet_option_name == 1){
						$bet_option_name = $homeo;
						
					}else if($bet_option_name == 2){
						$bet_option_name = $awayo;
					}
				   
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			//if($ovisible == 'Visible'){

				
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id, spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id', '3') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }
				   

			    //} //if ovisible
			   } //bet options markets
			   // if visible..
		      //} //if category is
			  //} //cat markets
			  }
			 
	    	}
		  }
		}
	}
};

exit;