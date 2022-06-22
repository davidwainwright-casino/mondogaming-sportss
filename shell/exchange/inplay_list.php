<?php error_reporting(0);
	//delete which didn't update for 5 mins ...
	mysqli_query($conn,"DELETE FROM af_inplay_bet_events WHERE deadline < (UNIX_TIMESTAMP() - 120)");
	//mysqli_query($conn,"DELETE FROM sh_sf_tickets_records WHERE status='unsubmitted' AND date < (UNIX_TIMESTAMP() - 300)");
	//mysqli_query($conn,"DELETE FROM sh_sf_slips WHERE status='unsubmitted' AND date < (UNIX_TIMESTAMP() - 300)");
	//$retval = $conn->query($drp);

	//events insert soccer bet365

	$url = 'https://api.betsapi.com/v1/bwin/inplay?token=' . $tk . '';
	$ch  = curl_init($url);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
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
	//mysqli_autocommit($conn,FALSE); //transaction begin
	foreach ($obj['results'] as $keyChil => $valueChild) {
	    $bet_event_id = $valueChild['Id'];
	    $betr     = $valueChild['BetRadarId'];
		if(!empty($betr)){
			$betradar     = $valueChild['BetRadarId'];
		} else {
			$betradar     = 1;
			
		}
	    $cc           = $valueChild['RegionName'];
	    $sports_id    = $valueChild['SportId'];
	    $sportname    = $valueChild['SportName'];
	    $leagueid     = $valueChild['LeagueId'];
	    $leaguename   = $valueChild['LeagueName'];
		$deadline =    $valueChild['updated_at'];
	    
	    $hometm = $valueChild['HomeTeam'];
	    $awaytm = $valueChild['AwayTeam'];
	    if (empty($hometm)) {
	        $bet_event_name = $leaguename;
	    } else {
	        $bet_event_name = $hometm . ' - ' . $awaytm;
	    }
	    
	    $ss = $valueChild['Scoreboard']['score'];
	    $tms = time();
	    //insert sf_events  
	
	    if (strpos($bet_event_name, 'Simulated') == false && $sports_id !=22) {
	        $bet_events    = 'af_inplay_bet_events';
	        $af_bet_events = "INSERT INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, ss, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$betradar', '$bet_event_name', '$deadline', '1', '$ss', '$leagueid', '$leaguename', '$sports_id', '$cc', '$sportname') ON DUPLICATE KEY UPDATE deadline = $tms";
	        mysqli_query($conn,$af_bet_events);
			echo $conn->error;
	        if ($conn->query($af_bet_events) === TRUE) {
	            
	         } else {
				 echo("Error description: " . $conn -> error);
	             echo "No Live events at this time"; 
	        }
	    }
	    
	}
	
	

	//Insert bet365 api
	
	$url = 'https://api.betsapi.com/v1/bet365/inplay_filter?token=' . $tk . '&sport_id=3';
	$ch  = curl_init($url);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
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
	foreach ($obj['results'] as $keyChil => $valueChild) {
	    $bet_event_id = $valueChild['id'];
	    $sports_id    = $valueChild['sport_id'];
	    $time_status  = $valueChild['time_status'];
	    $leagueid     = $valueChild['league']['id'];
	    $leaguename   = $valueChild['league']['name'];
	    $cc           = 'World';
	    $sportname    = 'Cricket';
	    $hometm       = $valueChild['home']['name'];
	    $awaytm       = $valueChild['away']['name'];
	    if (empty($hometm)) {
	        $bet_event_name = $leaguename;
	    } else {
	        $bet_event_name = $hometm . ' - ' . $awaytm;
	    }
	    
	    $ss       = $valueChild['ss'];
	    $deadline = $valueChild['updated_at'];
	    $betradar = $valueChild['id'];
	    $tms = time();
	    //insert sf_events        
	    if ($time_status == '1') {
	        $bet_events    = 'af_inplay_bet_events';
	        $af_bet_events = "INSERT INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, ss, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bet_event_id', '$bet_event_name', '$deadline', '1', '$ss', '$leagueid', '$leaguename', '$sports_id', '$cc', '$sportname') ON DUPLICATE KEY UPDATE deadline = '$tms', ss='$ss'";
	        mysqli_query($conn, $af_bet_events);
			echo $conn->error;
	        
	        // if ($conn->query($af_bet_events) === TRUE) {
	            
	        // } else {
	        //     //echo "Temporarily Unavailable";
	        //     //echo "Error: " . $af_bet_events . "<br>" . $conn->error;                
	        // }
	    }
	    
	}
	
//mysqli_close($conn);
?>