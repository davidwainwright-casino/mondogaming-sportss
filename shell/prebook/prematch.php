<?php include_once('../db.php');
$xr = "SELECT serial FROM `www_token` ";
$result = mysqli_query($conn, $xr);
$c_row = $result->fetch_assoc();
$tk = $c_row['serial'];
$tnow = time();
//mysqli_query($conn,"DELETE FROM af_pre_bet_events WHERE is_active = '1' AND feat = '0'");
//mysqli_query($conn,"DELETE FROM af_pre_bet_events WHERE $tnow > deadline");
//mysqli_query($conn,"DELETE FROM af_pre_bet_events_cats WHERE yn IS NULL");
//mysqli_query($conn,"DELETE FROM af_pre_bet_options WHERE bet_event_cat_id > 1000000");



//events insert soccer page1

$url = 'https://api.betsapi.com/v1/bwin/prematch?token='.$tk.'&page=1';
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
foreach($obj['results'] as $result){
    $output = $result['Id'];
    $url='https://api.betsapi.com/v1/bwin/event?token='.$tk.'&event_id='.$output;
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
    $data = utf8_encode($data);
    $json = $data;	
    $a = explode('string', $json);
    for($k =0; $k<count($a); $k++){
        $w = trim($a[$k]);
        if(count($a) > 1){
            $w = strstr($w, '"');
        }
        $json = trim($w, '"');
        $obj = json_decode($json, true);
        $b = $obj['results'];
        for($i =0; $i<count($b); $i++){
			$bet_event_id = $b[$i]['Id'];
			$bradar = $b[$i]['BetRadarId'];
            if(!empty($b[$i]['Id']) && !empty($bradar)){
             $sport_id = $b[$i]['SportId'];
			 $sname = $b[$i]['SportName'];			 
             $event_id = $b[$i]['LeagueId'];
			 $cc = $b[$i]['RegionName'];
			 
             $empt = $b[$i]['HomeTeam']; 
             $event_name = mysqli_real_escape_string($conn, $b[$i]['LeagueName']);
			 //$event_name = addslashes($lvname);
			 
             $e_name =  $b[$i]['HomeTeam'].' - '. $b[$i]['AwayTeam'];
			 $ev_name = mysqli_real_escape_string($conn, $e_name); 
             //$ev_name = addslashes($vname );
             if (!empty($empt)){
             $bet_event_name = $ev_name;
             } else{
             $bet_event_name = addslashes($b[$i]['LeagueName']);      
             }
			 $deadline = strtotime($b[$i]['Date']);
			 $date = new DateTime(); 
			 $now = $date->getTimestamp();
			 $descr = mysqli_real_escape_string($conn, $data);
			 
			 
			 ///////////////////INSERT STARTS//////////////////////
			 //INSERT TO FULL ODDS
			 $full_af_bet_events = "INSERT INTO `af_pre_bet_events_json` (bet_event_id, full_odds) VALUES('$bet_event_id', '$descr') ON DUPLICATE KEY UPDATE full_odds='$descr'";
			 
			 if ($conn->query($full_af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $full_af_bet_events . "<br>" . $conn->error;		 
			 }
			 
			 //insert sf_events
			 $bet_events = 'af_pre_bet_events';
			 $af_bet_events = "INSERT IGNORE INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bradar', '$bet_event_name', '$deadline', '1', '$event_id', '$event_name', '$sport_id', '$cc', '$sname')";
			 
			 if ($conn->query($af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_bet_events . "<br>" . $conn->error;		 
			 }

			 //INSERT BET EVENTS CATS
			  
			  $c = $b[$i]['Markets'];
              for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $c[$j]['id'];
				  $bet_event_cat_name = addslashes($c[$j]['name']['value']);
				  $visible = $c[$j]['visibility'];
				  
				 
			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
			if($visible == 'Visible'){				
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '$sport_id')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
			 
			 //INSERT BET OPTIONS
			 
			 $d = $c[$j]['results'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $oname = $e['name']['value'];
                   $bet_option_name = addslashes($oname );
                   $bet_option_odd = $e['odds'];
				   $ovisible = $e['visibility'];
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			if($ovisible == 'Visible'){
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }

			    } //if ovisible
			   } //bet options markets
			  } // if visible..
		    
			break;
			  } //cat markets
			 
			}
		}
	}
}



//PAGE2
$url = 'https://api.betsapi.com/v1/bwin/prematch?token='.$tk.'&page=2';
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
foreach($obj['results'] as $result){
    $output = $result['Id'];
    $url='https://api.betsapi.com/v1/bwin/event?token='.$tk.'&event_id='.$output;
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
    $data = utf8_encode($data);
    $json = $data;	
    $a = explode('string', $json);
    for($k =0; $k<count($a); $k++){
        $w = trim($a[$k]);
        if(count($a) > 1){
            $w = strstr($w, '"');
        }
        $json = trim($w, '"');
        $obj = json_decode($json, true);
        $b = $obj['results'];
        for($i =0; $i<count($b); $i++){
			$bet_event_id = $b[$i]['Id'];
			$bradar = $b[$i]['BetRadarId'];
            if(!empty($b[$i]['Id']) && !empty($bradar)){
             $sport_id = $b[$i]['SportId'];
			 $sname = $b[$i]['SportName'];			 
             $event_id = $b[$i]['LeagueId'];
			 $cc = $b[$i]['RegionName'];
			 
             $empt = $b[$i]['HomeTeam']; 
             $event_name = mysqli_real_escape_string($conn, $b[$i]['LeagueName']);
			 //$event_name = addslashes($lvname);
			 
             $e_name =  $b[$i]['HomeTeam'].' - '. $b[$i]['AwayTeam'];
			 $ev_name = mysqli_real_escape_string($conn, $e_name); 
             //$ev_name = addslashes($vname );
             if (!empty($empt)){
             $bet_event_name = $ev_name;
             } else{
             $bet_event_name = addslashes($b[$i]['LeagueName']);      
             }
			 $deadline = strtotime($b[$i]['Date']);
			 $date = new DateTime(); 
			 $now = $date->getTimestamp();
			 $descr = mysqli_real_escape_string($conn, $data);
			 
			 
			 ///////////////////INSERT STARTS//////////////////////
			 //INSERT TO FULL ODDS
			 $full_af_bet_events = "INSERT INTO `af_pre_bet_events_json` (bet_event_id, full_odds) VALUES('$bet_event_id', '$descr') ON DUPLICATE KEY UPDATE full_odds='$descr'";
			 
			 if ($conn->query($full_af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $full_af_bet_events . "<br>" . $conn->error;		 
			 }
			 
			 //insert sf_events
			 $bet_events = 'af_pre_bet_events';
			 $af_bet_events = "INSERT IGNORE INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bradar', '$bet_event_name', '$deadline', '1', '$event_id', '$event_name', '$sport_id', '$cc', '$sname')";
			 
			 if ($conn->query($af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_bet_events . "<br>" . $conn->error;		 
			 }

			 //INSERT BET EVENTS CATS
			  
			  $c = $b[$i]['Markets'];
              for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $c[$j]['id'];
				  $bet_event_cat_name = addslashes($c[$j]['name']['value']);
				  $visible = $c[$j]['visibility'];
				  
				 
			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
			if($visible == 'Visible'){				
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '$sport_id')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
			 
			 //INSERT BET OPTIONS
			 
			 $d = $c[$j]['results'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $oname = $e['name']['value'];
                   $bet_option_name = addslashes($oname );
                   $bet_option_odd = $e['odds'];
				   $ovisible = $e['visibility'];
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			if($ovisible == 'Visible'){
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }

			    } //if ovisible
			   } //bet options markets
			  } // if visible..
		    
			break;
			  } //cat markets
			 
			}
		}
	}
}





//PAGE3

$url = 'https://api.betsapi.com/v1/bwin/prematch?token='.$tk.'&page=3';
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
foreach($obj['results'] as $result){
    $output = $result['Id'];
    $url='https://api.betsapi.com/v1/bwin/event?token='.$tk.'&event_id='.$output;
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
    $data = utf8_encode($data);
    $json = $data;	
    $a = explode('string', $json);
    for($k =0; $k<count($a); $k++){
        $w = trim($a[$k]);
        if(count($a) > 1){
            $w = strstr($w, '"');
        }
        $json = trim($w, '"');
        $obj = json_decode($json, true);
        $b = $obj['results'];
        for($i =0; $i<count($b); $i++){
			$bet_event_id = $b[$i]['Id'];
			$bradar = $b[$i]['BetRadarId'];
            if(!empty($b[$i]['Id']) && !empty($bradar)){
             $sport_id = $b[$i]['SportId'];
			 $sname = $b[$i]['SportName'];			 
             $event_id = $b[$i]['LeagueId'];
			 $cc = $b[$i]['RegionName'];
			 
             $empt = $b[$i]['HomeTeam']; 
             $event_name = mysqli_real_escape_string($conn, $b[$i]['LeagueName']);
			 //$event_name = addslashes($lvname);
			 
             $e_name =  $b[$i]['HomeTeam'].' - '. $b[$i]['AwayTeam'];
			 $ev_name = mysqli_real_escape_string($conn, $e_name); 
             //$ev_name = addslashes($vname );
             if (!empty($empt)){
             $bet_event_name = $ev_name;
             } else{
             $bet_event_name = addslashes($b[$i]['LeagueName']);      
             }
			 $deadline = strtotime($b[$i]['Date']);
			 $date = new DateTime(); 
			 $now = $date->getTimestamp();
			 $descr = mysqli_real_escape_string($conn, $data);
			 
			 
			 ///////////////////INSERT STARTS//////////////////////
			 //INSERT TO FULL ODDS
			 $full_af_bet_events = "INSERT INTO `af_pre_bet_events_json` (bet_event_id, full_odds) VALUES('$bet_event_id', '$descr') ON DUPLICATE KEY UPDATE full_odds='$descr'";
			 
			 if ($conn->query($full_af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $full_af_bet_events . "<br>" . $conn->error;		 
			 }
			 
			 //insert sf_events
			 $bet_events = 'af_pre_bet_events';
			 $af_bet_events = "INSERT IGNORE INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bradar', '$bet_event_name', '$deadline', '1', '$event_id', '$event_name', '$sport_id', '$cc', '$sname')";
			 
			 if ($conn->query($af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_bet_events . "<br>" . $conn->error;		 
			 }

			 //INSERT BET EVENTS CATS
			  
			  $c = $b[$i]['Markets'];
              for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $c[$j]['id'];
				  $bet_event_cat_name = addslashes($c[$j]['name']['value']);
				  $visible = $c[$j]['visibility'];
				  
				 
			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
			if($visible == 'Visible'){				
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '$sport_id')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
			 
			 //INSERT BET OPTIONS
			 
			 $d = $c[$j]['results'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $oname = $e['name']['value'];
                   $bet_option_name = addslashes($oname );
                   $bet_option_odd = $e['odds'];
				   $ovisible = $e['visibility'];
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			if($ovisible == 'Visible'){
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }

			    } //if ovisible
			   } //bet options markets
			  } // if visible..
		    
			break;
			  } //cat markets
			 
			}
		}
	}
}


//PAGE 4
$url = 'https://api.betsapi.com/v1/bwin/prematch?token='.$tk.'&page=4';
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
foreach($obj['results'] as $result){
    $output = $result['Id'];
    $url='https://api.betsapi.com/v1/bwin/event?token='.$tk.'&event_id='.$output;
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
    $data = utf8_encode($data);
    $json = $data;	
    $a = explode('string', $json);
    for($k =0; $k<count($a); $k++){
        $w = trim($a[$k]);
        if(count($a) > 1){
            $w = strstr($w, '"');
        }
        $json = trim($w, '"');
        $obj = json_decode($json, true);
        $b = $obj['results'];
        for($i =0; $i<count($b); $i++){
			$bet_event_id = $b[$i]['Id'];
			$bradar = $b[$i]['BetRadarId'];
            if(!empty($b[$i]['Id']) && !empty($bradar)){
             $sport_id = $b[$i]['SportId'];
			 $sname = $b[$i]['SportName'];			 
             $event_id = $b[$i]['LeagueId'];
			 $cc = $b[$i]['RegionName'];
			 
             $empt = $b[$i]['HomeTeam']; 
             $event_name = mysqli_real_escape_string($conn, $b[$i]['LeagueName']);
			 //$event_name = addslashes($lvname);
			 
             $e_name =  $b[$i]['HomeTeam'].' - '. $b[$i]['AwayTeam'];
			 $ev_name = mysqli_real_escape_string($conn, $e_name); 
             //$ev_name = addslashes($vname );
             if (!empty($empt)){
             $bet_event_name = $ev_name;
             } else{
             $bet_event_name = addslashes($b[$i]['LeagueName']);      
             }
			 $deadline = strtotime($b[$i]['Date']);
			 $date = new DateTime(); 
			 $now = $date->getTimestamp();
			 $descr = mysqli_real_escape_string($conn, $data);
			 
			 
			 ///////////////////INSERT STARTS//////////////////////
			 //INSERT TO FULL ODDS
			 $full_af_bet_events = "INSERT INTO `af_pre_bet_events_json` (bet_event_id, full_odds) VALUES('$bet_event_id', '$descr') ON DUPLICATE KEY UPDATE full_odds='$descr'";
			 
			 if ($conn->query($full_af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $full_af_bet_events . "<br>" . $conn->error;		 
			 }
			 
			 //insert sf_events
			 $bet_events = 'af_pre_bet_events';
			 $af_bet_events = "INSERT IGNORE INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bradar', '$bet_event_name', '$deadline', '1', '$event_id', '$event_name', '$sport_id', '$cc', '$sname')";
			 
			 if ($conn->query($af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_bet_events . "<br>" . $conn->error;		 
			 }

			 //INSERT BET EVENTS CATS
			  
			  $c = $b[$i]['Markets'];
              for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $c[$j]['id'];
				  $bet_event_cat_name = addslashes($c[$j]['name']['value']);
				  $visible = $c[$j]['visibility'];
				  
				 
			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
			if($visible == 'Visible'){				
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '$sport_id')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
			 
			 //INSERT BET OPTIONS
			 
			 $d = $c[$j]['results'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $oname = $e['name']['value'];
                   $bet_option_name = addslashes($oname );
                   $bet_option_odd = $e['odds'];
				   $ovisible = $e['visibility'];
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			if($ovisible == 'Visible'){
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }

			    } //if ovisible
			   } //bet options markets
			  } // if visible..
		    
			break;
			  } //cat markets
			 
			}
		}
	}
}


//page5

$url = 'https://api.betsapi.com/v1/bwin/prematch?token='.$tk.'&page=5';
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
foreach($obj['results'] as $result){
    $output = $result['Id'];
    $url='https://api.betsapi.com/v1/bwin/event?token='.$tk.'&event_id='.$output;
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
    $data = utf8_encode($data);
    $json = $data;	
    $a = explode('string', $json);
    for($k =0; $k<count($a); $k++){
        $w = trim($a[$k]);
        if(count($a) > 1){
            $w = strstr($w, '"');
        }
        $json = trim($w, '"');
        $obj = json_decode($json, true);
        $b = $obj['results'];
        for($i =0; $i<count($b); $i++){
			$bet_event_id = $b[$i]['Id'];
			$bradar = $b[$i]['BetRadarId'];
            if(!empty($b[$i]['Id']) && !empty($bradar)){
             $sport_id = $b[$i]['SportId'];
			 $sname = $b[$i]['SportName'];			 
             $event_id = $b[$i]['LeagueId'];
			 $cc = $b[$i]['RegionName'];
			 
             $empt = $b[$i]['HomeTeam']; 
             $event_name = mysqli_real_escape_string($conn, $b[$i]['LeagueName']);
			 //$event_name = addslashes($lvname);
			 
             $e_name =  $b[$i]['HomeTeam'].' - '. $b[$i]['AwayTeam'];
			 $ev_name = mysqli_real_escape_string($conn, $e_name); 
             //$ev_name = addslashes($vname );
             if (!empty($empt)){
             $bet_event_name = $ev_name;
             } else{
             $bet_event_name = addslashes($b[$i]['LeagueName']);      
             }
			 $deadline = strtotime($b[$i]['Date']);
			 $date = new DateTime(); 
			 $now = $date->getTimestamp();
			 $descr = mysqli_real_escape_string($conn, $data);
			 
			 
			 ///////////////////INSERT STARTS//////////////////////
			 //INSERT TO FULL ODDS
			 $full_af_bet_events = "INSERT INTO `af_pre_bet_events_json` (bet_event_id, full_odds) VALUES('$bet_event_id', '$descr') ON DUPLICATE KEY UPDATE full_odds='$descr'";
			 
			 if ($conn->query($full_af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $full_af_bet_events . "<br>" . $conn->error;		 
			 }
			 
			 //insert sf_events
			 $bet_events = 'af_pre_bet_events';
			 $af_bet_events = "INSERT IGNORE INTO $bet_events (bet_event_id, bradar, bet_event_name, deadline, is_active, event_id, event_name, spid, cc, sname) VALUES('$bet_event_id', '$bradar', '$bet_event_name', '$deadline', '1', '$event_id', '$event_name', '$sport_id', '$cc', '$sname')";
			 
			 if ($conn->query($af_bet_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_bet_events . "<br>" . $conn->error;		 
			 }

			 //INSERT BET EVENTS CATS
			  
			  $c = $b[$i]['Markets'];
              for($j =0; $j<count($c); $j++){
				  $c_sort = 1;
				  $bet_event_cat_id = $c[$j]['id'];
				  $bet_event_cat_name = addslashes($c[$j]['name']['value']);
				  $visible = $c[$j]['visibility'];
				  
				 
			 $cat_events = 'af_pre_bet_events_cats';
			 $c_count = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
			if($visible == 'Visible'){				
			 $af_cat_events = "INSERT IGNORE INTO $cat_events (bet_event_cat_id, c_sort, bet_event_id, bet_event_cat_name, spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_id', '$bet_event_cat_name', '$sport_id')";
			 
			 if ($conn->query($af_cat_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_cat_events . "<br>" . $conn->error;		 
			 }
			 
			 //INSERT BET OPTIONS
			 
			 $d = $c[$j]['results'];
               foreach ($d as $e) {
				   $bet_option_id = $e['id'];
                   $oname = $e['name']['value'];
                   $bet_option_name = addslashes($oname );
                   $bet_option_odd = $e['odds'];
				   $ovisible = $e['visibility'];
			 $opt_events = 'af_pre_bet_options';
			 $o_count = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			 $o_result = $conn->query($o_count);
                            if ($o_result->num_rows > 0) {
                                $o_row = $o_result->fetch_assoc();
                                $o_sort = $o_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
			if($ovisible == 'Visible'){
			 $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
			 
			 if ($conn->query($af_opt_events) === TRUE) {
				 //echo 'inserted';
			 } else {
				 echo "Error: " . $af_opt_events . "<br>" . $conn->error;		 
			 }

			    } //if ovisible
			   } //bet options markets
			  } // if visible..
		    
			break;
			  } //cat markets
			 
			}
		}
	}
}


//include_once('prematch_others.php');
include_once('team_logo_insert.php');
exit;
?>