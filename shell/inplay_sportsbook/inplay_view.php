<?php error_reporting(0);
	include_once('../db.php');
	
	$bet_event_id = $_POST['evid'];
	$usid = $_POST['usid'];
	
	if(empty($usid)){
		die();
	}
	
	function checkEventExist($bet_event_id, $conn){
		//if b_sort=1 is already present then we don't insert again. This is onclick on list events first insert only
	    $query  = "SELECT bet_event_id FROM af_inplay_bet_events WHERE bet_event_id='$bet_event_id' AND b_sort = 1";
	    $result = mysqli_query($conn, $query);
	    if (mysqli_num_rows($result)>0){
	        return true;
	    } else {
	        return false;
	    }
	}

	function saveToDatabase($bet_event_id, $conn,$tk)
	{
		$url = "https://api.betsapi.com/v1/bwin/event?token=" . $tk . "&event_id=" . $bet_event_id;
		$ch  = curl_init($url);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch) or die(curl_error($ch));
		if ($data === false) {
		    echo 'Event Suspended';
		}
		curl_close($ch);
		$json1 = $data;
		$a     = explode('string', $json1);
		for ($k = 0; $k < count($a); $k++) {
		    $bet_event_cat_sort_order = 1;
		    $bet_option_sort_order    = 1;
		    $w                        = trim($a[$k]);
		    if (count($a) > 1) {
		        $w = strstr($w, '"');
		    }
		    $json = trim($w, '"');
		    $obj  = json_decode($json, true);
		    $b    = $obj['results'];
		    for ($i = 0; $i < count($b); $i++) {
		        //$bet_event_sort_order = 1;
		        $deadline       = $b[$i]['updated_at'];
		        $sport_id       = $b[$i]['SportId'];
		        $sport_name     = $b[$i]['SportName'];
		        $cc             = $b[$i]['RegionName'];
		        $event_id       = $b[$i]['LeagueId'];
		        $event_name     = $b[$i]['LeagueName'];
		        $bet_event_name = $b[$i]['HomeTeam'] . " - " . $b[$i]['AwayTeam'];
		        $betr       = $b[$i]['BetRadarId'];
			if($betr > 1){
				$betradar       = $b[$i]['BetRadarId'];
			} else {
				$betradar       = 1;
			}
		        //insert in the table af_inplay_bet_events
		        
		        $bet_events    = 'af_inplay_bet_events';
		        $af_bet_events = "UPDATE $bet_events SET b_sort = 1 WHERE bet_event_id = $bet_event_id";
		        if ($conn->query($af_bet_events) === TRUE) {
		            //echo 'inserted';
		        } else {
		            //echo "Error: " . $af_bet_events . "<br>" . $conn->error;
		            //echo "Temporarily Suspended";            
		        }
		        
		        $c = $b[$i]['Markets'];
		        $i = 0;
		        for ($j = 0; $j < count($c); $j++) {
		            if ($i == 0) {
		                $c_sort             = 1;
		                $bet_event_cat_name = $c[$j]['name']['value'];
		                $bet_event_cat_id   = $c[$j]['id'];
		                $cat_id             = $c[$j]['id'];
		                $d                  = $c[$j]['results'];
		                $visible            = $c[$j]['visibility'];
		                //insert in the table af_inplay_bet_events_cats    
		                $cat_events         = 'af_inplay_bet_events_cats';
		                $c_count            = "SELECT count(*) as count FROM $cat_events WHERE bet_event_id = '$bet_event_id'";
		                $c_result           = $conn->query($c_count);
		                if ($c_result->num_rows > 0) {
		                    $c_row  = $c_result->fetch_assoc();
		                    $c_sort = $c_row['count'] + 1;
		                } else {
		                    $c_sort = 1;
		                }



		                if($sport_id==7){

		                	if($bet_event_cat_name=="Moneyline" || $bet_option_name=="totals"){

		                		if ($visible == 'Visible') {
				                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
				                    
				                    if ($conn->query($af_cat_events) === TRUE) {
				                        //echo 'inserted';
				                    } else {
				                        //echo "Temporarily Suspended";
				                        
				                    }
				                    
				                    foreach ($d as $e) {
				                        $bet_option_id   = $e['id'];
				                        $visible         = $e['visibility'];
				                        $oname           = $e['name']['value'];
				                        $bet_option_name = str_replace("'", "''", $oname);
				                        $bet_option_odd  = $e['odds'];
				                        
				                        //insert in the table af_inplay_bet_options
				                        $opt_events = 'af_inplay_bet_options';
				                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
				                        $o_result   = $conn->query($o_count);
				                        if ($o_result->num_rows > 0) {
				                            $o_row  = $o_result->fetch_assoc();
				                            $o_sort = $o_row['count'] + 1;
				                        } else {
				                            $o_sort = 1;
				                        }
				                        if ($visible == 'Visible') {
				                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id')";
				                            
				                            if ($conn->query($af_opt_events) === TRUE) {
				                                //echo 'inserted';
				                            } else {
				                                //echo "Temporarily Suspended";        
				                            }
				                            
				                            
					                        } //if ovisible
				                    } //foreach options
			                    
				                } //if visible category
		                	}

		                }else if($sport_id==5){

		                	if($bet_event_cat_name=="Match Result" || $bet_option_name=="Set Betting"){

		                		if ($visible == 'Visible') {
				                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
				                    
				                    if ($conn->query($af_cat_events) === TRUE) {
				                        //echo 'inserted';
				                    } else {
				                        //echo "Temporarily Suspended";
				                        
				                    }
				                    
				                    foreach ($d as $e) {
				                        $bet_option_id   = $e['id'];
				                        $visible         = $e['visibility'];
				                        $oname           = $e['name']['value'];
				                        $bet_option_name = str_replace("'", "''", $oname);
				                        $bet_option_odd  = $e['odds'];
				                        
				                        //insert in the table af_inplay_bet_options
				                        $opt_events = 'af_inplay_bet_options';
				                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
				                        $o_result   = $conn->query($o_count);
				                        if ($o_result->num_rows > 0) {
				                            $o_row  = $o_result->fetch_assoc();
				                            $o_sort = $o_row['count'] + 1;
				                        } else {
				                            $o_sort = 1;
				                        }
				                        if ($visible == 'Visible') {
				                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id')";
				                            
				                            if ($conn->query($af_opt_events) === TRUE) {
				                                //echo 'inserted';
				                            } else {
				                                //echo "Temporarily Suspended";        
				                            }
				                            
				                            
					                        } //if ovisible
				                    } //foreach options
			                    
				                } //if visible category
		                	}

		                }else if($sport_id==12){

		                	if($bet_event_cat_name=="Moneyline" || $bet_option_name=="Over/Under"){

		                		if ($visible == 'Visible') {
				                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
				                    
				                    if ($conn->query($af_cat_events) === TRUE) {
				                        //echo 'inserted';
				                    } else {
				                        //echo "Temporarily Suspended";
				                        
				                    }
				                    
				                    foreach ($d as $e) {
				                        $bet_option_id   = $e['id'];
				                        $visible         = $e['visibility'];
				                        $oname           = $e['name']['value'];
				                        $bet_option_name = str_replace("'", "''", $oname);
				                        $bet_option_odd  = $e['odds'];
				                        
				                        //insert in the table af_inplay_bet_options
				                        $opt_events = 'af_inplay_bet_options';
				                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
				                        $o_result   = $conn->query($o_count);
				                        if ($o_result->num_rows > 0) {
				                            $o_row  = $o_result->fetch_assoc();
				                            $o_sort = $o_row['count'] + 1;
				                        } else {
				                            $o_sort = 1;
				                        }
				                        if ($visible == 'Visible') {
				                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id')";
				                            
				                            if ($conn->query($af_opt_events) === TRUE) {
				                                //echo 'inserted';
				                            } else {
				                                //echo "Temporarily Suspended";        
				                            }
				                            
				                            
					                        } //if ovisible
				                    } //foreach options
			                    
				                } //if visible category
		                	}

		                }else if($sport_id==23){

		                	if($bet_event_cat_name=="Moneyline" || $bet_option_name=="Maximum Balls"){

		                		if ($visible == 'Visible') {
				                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
				                    
				                    if ($conn->query($af_cat_events) === TRUE) {
				                        //echo 'inserted';
				                    } else {
				                        //echo "Temporarily Suspended";
				                        
				                    }
				                    
				                    foreach ($d as $e) {
				                        $bet_option_id   = $e['id'];
				                        $visible         = $e['visibility'];
				                        $oname           = $e['name']['value'];
				                        $bet_option_name = str_replace("'", "''", $oname);
				                        $bet_option_odd  = $e['odds'];
				                        
				                        //insert in the table af_inplay_bet_options
				                        $opt_events = 'af_inplay_bet_options';
				                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
				                        $o_result   = $conn->query($o_count);
				                        if ($o_result->num_rows > 0) {
				                            $o_row  = $o_result->fetch_assoc();
				                            $o_sort = $o_row['count'] + 1;
				                        } else {
				                            $o_sort = 1;
				                        }
				                        if ($visible == 'Visible') {
				                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id')";
				                            
				                            if ($conn->query($af_opt_events) === TRUE) {
				                                //echo 'inserted';
				                            } else {
				                                //echo "Temporarily Suspended";        
				                            }
				                            
				                            
					                        } //if ovisible
				                    } //foreach options
			                    
				                } //if visible category
		                	}

		                }else if($sport_id==11){

		                	if($bet_event_cat_name=="Moneyline" || $bet_option_name=="Totals Over"){

		                		if ($visible == 'Visible') {
				                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
				                    
				                    if ($conn->query($af_cat_events) === TRUE) {
				                        //echo 'inserted';
				                    } else {
				                        //echo "Temporarily Suspended";
				                        
				                    }
				                    
				                    foreach ($d as $e) {
				                        $bet_option_id   = $e['id'];
				                        $visible         = $e['visibility'];
				                        $oname           = $e['name']['value'];
				                        $bet_option_name = str_replace("'", "''", $oname);
				                        $bet_option_odd  = $e['odds'];
				                        
				                        //insert in the table af_inplay_bet_options
				                        $opt_events = 'af_inplay_bet_options';
				                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
				                        $o_result   = $conn->query($o_count);
				                        if ($o_result->num_rows > 0) {
				                            $o_row  = $o_result->fetch_assoc();
				                            $o_sort = $o_row['count'] + 1;
				                        } else {
				                            $o_sort = 1;
				                        }
				                        if ($visible == 'Visible') {
				                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id')";
				                            
				                            if ($conn->query($af_opt_events) === TRUE) {
				                                //echo 'inserted';
				                            } else {
				                                //echo "Temporarily Suspended";        
				                            }
				                            
				                            
					                        } //if ovisible
				                    } //foreach options
			                    
				                } //if visible category
		                	}

		                }else if($sport_id==4){

		                	$_categories=["Match Result", " Total Goals - Over/Under", "Double Chance", "Both Teams to Score", "Handicap 0:1", "Handicap 0:2", "Handicap 1:0", "Handicap 2:0", "Half Time result", "Half Time Double Chance", "1st Goal - 1st Half", "Total Goals O/U - 1st Half", "Total Goals O/U - 2nd Half", "Correct Score (Regular Time)", "1st Goal", "Team 1 to Score", "Team 2 to Score", "Both Teams to Score 1st Half", "Number of Corners (Regular Time)", "Red Card - Yes/No", "Total Goals - Exact"];
		                	if(in_array($bet_event_cat_name,$_categories)){

		                		if ($visible == 'Visible') {
				                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
				                    
				                    if ($conn->query($af_cat_events) === TRUE) {
				                        //echo 'inserted';
				                    } else {
				                        //echo "Temporarily Suspended";
				                        
				                    }
				                    
				                    foreach ($d as $e) {
				                        $bet_option_id   = $e['id'];
				                        $visible         = $e['visibility'];
				                        $oname           = $e['name']['value'];
				                        $bet_option_name = str_replace("'", "''", $oname);
				                        $bet_option_odd  = $e['odds'];
				                        
				                        //insert in the table af_inplay_bet_options
				                        $opt_events = 'af_inplay_bet_options';
				                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
				                        $o_result   = $conn->query($o_count);
				                        if ($o_result->num_rows > 0) {
				                            $o_row  = $o_result->fetch_assoc();
				                            $o_sort = $o_row['count'] + 1;
				                        } else {
				                            $o_sort = 1;
				                        }
				                        if ($visible == 'Visible') {
				                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid, dl) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id', '1') ON DUPLICATE KEY UPDATE dl = 1";
				                            
				                            if ($conn->query($af_opt_events) === TRUE) {
				                                //echo 'inserted';
				                            } else {
				                                //echo "Temporarily Suspended";        
				                            }
				                            
				                            
					                        } //if ovisible
				                    } //foreach options
			                    
				                } //if visible category
		                	}

		                }else{

	                		if ($visible == 'Visible') {
			                    $af_cat_events = "INSERT INTO $cat_events (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
			                    
			                    if ($conn->query($af_cat_events) === TRUE) {
			                        //echo 'inserted';
			                    } else {
			                        //echo "Temporarily Suspended";
			                        
			                    }
			                    
			                    foreach ($d as $e) {
			                        $bet_option_id   = $e['id'];
			                        $visible         = $e['visibility'];
			                        $oname           = $e['name']['value'];
			                        $bet_option_name = str_replace("'", "''", $oname);
			                        $bet_option_odd  = $e['odds'];
			                        
			                        //insert in the table af_inplay_bet_options
			                        $opt_events = 'af_inplay_bet_options';
			                        $o_count    = "SELECT count(*) as count FROM $opt_events WHERE bet_event_cat_id = '$bet_event_cat_id'";
			                        $o_result   = $conn->query($o_count);
			                        if ($o_result->num_rows > 0) {
			                            $o_row  = $o_result->fetch_assoc();
			                            $o_sort = $o_row['count'] + 1;
			                        } else {
			                            $o_sort = 1;
			                        }
			                        if ($visible == 'Visible') {
			                            $af_opt_events = "INSERT INTO $opt_events (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id')";
			                            
			                            if ($conn->query($af_opt_events) === TRUE) {
			                                //echo 'inserted';
			                            } else {
			                                //echo "Temporarily Suspended";        
			                            }
			                            
			                            
				                        } //if ovisible
			                    } //foreach options
			                    break;
		                    
			                } //if visible category

		                }
		                
		                
		            }
		            
		        }
		    }
		    
		}
	}
	
	
	if(!checkEventExist($bet_event_id,$conn)){
		saveToDatabase($bet_event_id,$conn,"2645-9zwZs0KT0m3e5L");
	}














	function getEventData($bet_event_id,$conn){
		$query="select * from af_inplay_bet_events join af_inplay_bet_events_cats on af_inplay_bet_events.bet_event_id=af_inplay_bet_events_cats.bet_event_id join af_inplay_bet_options on af_inplay_bet_events_cats.bet_event_cat_id=af_inplay_bet_options.bet_event_cat_id WHERE af_inplay_bet_events.bet_event_id=$bet_event_id ORDER BY c_sort ASC";
		$event_data=mysqli_query($conn,$query);
		$structured_data=array();
		$temp_cat_id='';
		$event_name='';
		while($row=mysqli_fetch_assoc($event_data)){
			if($event_name==''){
				$event_name=$row['bet_event_name'];
				$spid=$row['spid'];
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
				$structured_data[strval($temp_cat_id)]=['cat_name'=>$row['bet_event_cat_name'],'c_sort'=>$c_sort,'spid'=>$spid,'bet_options'=>[$bet_option]];
			}
		}


		return array('event_id'=>$bet_event_id,'event_name'=>$event_name,'spid'=>$spid,'categories'=>$structured_data);
	}
	
	$bet_event_data=getEventData($bet_event_id,$conn);
	
 

$usid = $_POST['usid'];
$slip = "SELECT * FROM sh_sf_tickets_records WHERE user_id=$usid AND status='awaiting' AND event_id = $bet_event_id AND bet_info IS NULL AND type = 'sbook'";
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
				  //for laybet
              
		};
	
	
	
	//function format update slip back
	function bgetOdd($bodk){	 
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	  $decimal_odd = $bodk;
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
	  $decimal_odd = $bodk;
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
  return $bodk;
  }
};
	 
//function format update slip laybet
	function lgetOdd($lodk){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	   $decimal_oddlay = $lodk;
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
	  $decimal_odd = $lodk;
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
	  
  return $lodk;
  }
};
	
	
	//event view
	echo "<div class='evidf' id=".$bet_event_data['event_id'].">".$bet_event_data['event_name']."</div>";
	
	
	
	echo '<div class="cricid" id="'.$bet_event_data['spid'].'"></div>';
	
    foreach ($bet_event_data['categories'] as $key => $category) {
		
	 if($category['c_sort'] == '1'):?>
       <div class="cs modelwrap ms">
		<div class="catTop1 xp"><i class="icon timeline"></i> <?php echo $category['cat_name'];?> <span class="sfright xp"><i class="icon checkbox checked alt" title="Cashout Available"></i> <i class="icon star full" title="Top Category"></i></span></div>
		
		<?php foreach ($category['bet_options'] as $key_options => $option) {
			//for odd format
			$bodk = $option['odd'];
			$lodk = $bodk + 0.02;
			$bod = bgetOdd($bodk);
			$lod = lgetOdd($lodk);
			
			//others
			$backrm = rand(1000,9000);
			$layrm = rand(10,100);
			$okid = $option['bet_option_name']. ' ' .$option['bet_option_id'];
			$kmid = $option['bet_option_id']. '' .$option['bet_option_name'];
			$smid=str_replace(' ', '', $kmid);
			$cstake = $c_back[$okid];
		    $curod = $option['odd'];	//current odd		  
			$codd = $c_odd[$okid];
			?>
        <div class="b_option_wrapper xp">
       <span class="b_option_name xp"><div class="onamebg">.</div> 
	   <span class="ocg-<?php echo $option['bet_option_id'];?> cksuspend" id="<?php echo $option['bet_option_id'] ?>"><?php echo $option['bet_option_name'] ?></span>
		  <span class="cashwrapper">
		  <a><?php echo $avaiting_odd_list_back[$okid];?></a> 
		   <?php if(!empty($cstake)):
		   $ult = $avaiting_odd_list_back[$okid]/$curod; $nt = $ult * 20/100; $ut = $ult - $nt;
		   echo '<span class="casout '.$smid.'" id="slip-'.$s_back[$okid].'">Cash '.round($ut, 2).'</span>'; endif;?>		  
		  <span class="lyod"><?php $ck = $avaiting_odd_list_lay[$okid]; if(!empty($ck)){echo '-'.$ck.'';};?></span>
		  </span>
	  </span>
		  
		  
		      <div class="b_option_odd evn-<?php echo $option['bet_option_name'];?>" id="bet__option__btn__<?php echo $option['bet_option_id'] ?>__<?php echo $bet_event_id;?>__<?php echo $key ?>__<?php echo $bet_event_data['event_name']; ?>__<?php echo $category['cat_name'];?>__<?php echo $bodk;?>__<?php echo $lodk;?>__<?php echo $bet_event_data['spid'];?>__<?php echo $bod;?>__<?php echo $lod;?>">
          
		  <span class="bback" id="cor-<?php echo $option['bet_option_id'];?>">
		    <?php echo $bod;?><ft class="bm"><?php echo $backrm;?></ft>
		   </span> 
		  

		  
           </div> 
         </div>
       <?php }?>		
	</div>
<?php endif;?>
	<?php } ?>	
		
		
		<?php if(!empty($category['c_sort'])):?><h3 class="otm"><i class="icon maple leaf"></i> Other Markets</h3> <div id="evrefresh"><i class="icon refresh"></i></div><?php else:?><div class="evsus"><i class="icon warning sign"></i> Event Suspended<div id="evrefresh" style="margin:0px"><i class="icon refresh"></i></div></div><?php endif;?>
		<?php if($category['spid'] == '4'):?>	
		<ul class="omarkers">
		 <li class="omk active" id="popularf">Popular</li>
		 <li class="omk" id="goals">Goals O/U</li>
		 <li class="omk" id="handicap">Handicap</li>
		 <li class="omk" id="halftime">HT</li>
		 <li class="omk" id="scoref">Score</li>
		 </ul>
		 <?php elseif($category['spid'] == '3'):?>	
		 <ul class="crimarkers">
		 <li class="comk pxxactive" id="Popularc">Popular</li>
		 <li class="comk" id="Runsc">Runs</li>
		 <li class="comk" id="Wicketsc">Bowler</li>
		 <li class="comk" id="Scoresc">Batsman</li>
		 <li class="comk" id="Oversc">Others</li>
		 </ul>
		 <?php endif;?>
		 
		 
	<div id="fetchcat"> 
	<div class="masterwrapper">
	<?php foreach ($bet_event_data['categories'] as $key => $category) {
	 if($category['c_sort'] !== '1' && $category['cat_name'] !== 'Correct Score (Regular Time)' && $category['cat_name'] !== 'Total Goals - Exact' && $category['cat_name'] !== 'Player of the Match' && $category['cat_name'] !== 'Player Performance' && $category['cat_name'] !== 'Player to Score Most Sixes' && $category['cat_name'] !== 'Player to Score Most Sixes'):?>	
		<div class="cs modelwrap xp">
		<div class="catTop1 xp"><i class="icon timeline"></i> <?php echo $category['cat_name'];?> <span class="sfright xp"><i class="icon checkbox checked alt" title="Cashout Available"></i> <i class="icon star full" title="Top Category"></i></span></div>
		
		<?php foreach ($category['bet_options'] as $key_options => $option) {
			//for odd format
			$bodk = $option['odd'];
			$lodk = $bodk + 0.02;
			$bod = bgetOdd($bodk);
			$lod = lgetOdd($lodk);
			
			$backrm = rand(10,100);
			$layrm = rand(10,100);
			$okid = $option['bet_option_name']. ' ' .$option['bet_option_id'];
			$kmid = $option['bet_option_id']. '' .$option['bet_option_name'];
			$smid=str_replace(' ', '', $kmid);
			$cstake = $c_back[$okid];
		    $curod = $option['odd'];	//current odd		  
			$codd = $c_odd[$okid];?>
        <div class="b_option_wrapper xp">		
        <span class="b_option_name xp">
		  <div class="onamebg">.</div> <span class="ocg-<?php echo $option['bet_option_id'];?> cksuspend" id="<?php echo $option['bet_option_id'] ?>"><?php echo $option['bet_option_name'] ?></span>
		  <span class="cashwrapper">
		  <a><?php echo $avaiting_odd_list_back[$okid];?></a> 
		   <?php if(!empty($cstake)):
		   $ult = $avaiting_odd_list_back[$okid]/$curod; $nt = $ult * 20/100; $ut = $ult - $nt; 
		   echo '<span class="casout '.$smid.'" id="slip-'.$s_back[$okid].'">Cash '.round($ut, 2).'</span>'; endif;?>		  
		  <span class="lyod"><?php $ck = $avaiting_odd_list_lay[$okid]; if(!empty($ck)){echo '-'.$ck.'';};?></span>
		  </span>  
	  </span>
		  <div class="b_option_odd evn-<?php echo $option['bet_option_name'];?>" id="bet__option__btn__<?php echo $option['bet_option_id'] ?>__<?php echo $bet_event_id;?>__<?php echo $key ;?>__<?php echo $bet_event_data['event_name']; ?>__<?php echo $category['cat_name'];?>__<?php echo $bodk;?>__<?php echo $lodk;?>__<?php echo $bet_event_data['spid'];?>__<?php echo $bod;?>__<?php echo $lod;?>">        
		  <span class="bback" id="cor-<?php echo $option['bet_option_id'];?>"><?php echo $bod;?><ft class="bm"><?php echo $backrm;?></ft></span>
           </div>   
        </div>		
        <?php }?>
	</div>
    
	 <?php endif;?>
	<?php } ?>
	</div>
	</div>
