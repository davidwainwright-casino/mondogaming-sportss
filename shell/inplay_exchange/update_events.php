<?php error_reporting(0);
	include_once('../db.php');error_reporting(0);
	$tk="2645-9zwZs0KT0m3e5L";

	function get_all_event_ids($spid,$conn){
		$query="SELECT bet_event_id FROM af_inplay_bet_events WHERE b_sort = 1 AND spid<>3";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		$aids = implode (",", $event_id_array);
		//var_dump($aids);
		//here delete only active matches and yn null means there is disabled events
		//mysqli_query($conn,"DELETE FROM af_inplay_bet_events_cats WHERE bet_event_id IN($aids) AND yn IS NULL");
		
		return array_chunk($event_id_array,10);
	}
	
	//set parameters to delete.. 6 delete, 7 update keep it.
	//mysqli_query($conn,"UPDATE af_inplay_bet_events_cats SET yn=6");
	//mysqli_query($conn,"UPDATE af_inplay_bet_options SET yn=6");
	
	
	function updateEvents($event_ids,$spid,$tk,$conn){
		  //delete passed categories with dl=1
		  mysqli_query($conn,"DELETE FROM af_inplay_bet_events_cats WHERE yn IS NULL AND dl=1 AND spid <> 3");
		  mysqli_query($conn,"DELETE FROM af_inplay_bet_options WHERE dl=1 AND spid <> 3");
		  
		     
		  //mysqli_query($conn,"DELETE FROM af_inplay_bet_events WHERE spid<>3");
		  //mysqli_query($conn,"DELETE FROM af_inplay_bet_options WHERE bet_event_cat_id NOT IN('SELECT bet_event_cat_id FROM af_inplay_bet_events_cats WHERE c_sort = 1)");
		  //echo $conn->error;
		mysqli_autocommit($conn,FALSE); //transaction begin

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			$url = "https://api.betsapi.com/v1/bwin/event?token=" . $tk . "&event_id=" . implode(",",$event_ids_set);
			echo "<h1 style='color:green'>api callled</h1>";
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
			$event_market=$json['results'][0]['Markets'];
			foreach($json['results'] as $eventKey=>$event){
				$bet_event_id	= $event['Id'];
				$deadline       = $event['updated_at'];
		        $sport_id       = $event['SportId'];
		        $sport_name     = $event['SportName'];
		        $cc             = $event['RegionName'];
		        $event_id       = $event['LeagueId'];
		        $event_name     = $event['LeagueName'];
		        $bet_event_name = $event['HomeTeam'] . " - " . $event['AwayTeam'];

                   $c_sort = 1;
				foreach ($event['Markets'] as $cat_key => $category) {
		                $bet_event_cat_name = $category['name']['value'];
		                $bet_event_cat_id   = $category['id'];
		                $cat_id             = $category['id'];
		                $d                  = $category['results'];
		                $cat_visible        = $category['visibility'];
		                if($sport_id==7){
		                	if($bet_event_cat_name=="Moneyline" || $bet_event_cat_name=="totals"){
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
												mysqli_query($conn,$bet_options_query);
					                        }
											$o_sort++;
									}
				                }
		                	}
		                }else if($sport_id==5){
		                	if($bet_event_cat_name=="Match Result" || $bet_event_cat_name=="Set Betting"){
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
												mysqli_query($conn,$bet_options_query);
					                        }
											$o_sort++;
									}
				                }
		                	}
		                }else if($sport_id==12){
		                	if($bet_event_cat_name=="Moneyline" || $bet_event_cat_name=="Over/Under"){
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
												mysqli_query($conn,$bet_options_query);
					                        }
											$o_sort++;
									}
				                }
		                	}
		                }else if($sport_id==23){
		                	if($bet_event_cat_name=="Moneyline" || $bet_event_cat_name=="Maximum Balls"){
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
												mysqli_query($conn,$bet_options_query);
					                        }
											$o_sort++;
									}
				                }
		                	}
		                }else if($sport_id==11){
		                	if($bet_event_cat_name=="Moneyline" || $bet_event_cat_name=="Totals Over"){
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
												mysqli_query($conn,$bet_options_query);
					                        }
											$o_sort++;
									}
				                }
		                	}
		                }else if($sport_id==4){
		                	$_categories=["Match Result", " Total Goals - Over/Under", "Double Chance", "Both Teams to Score", "Handicap 0:1", "Handicap 0:2", "Handicap 1:0", "Handicap 2:0", "Half Time result", "Half Time Double Chance", "1st Goal - 1st Half", "Total Goals O/U - 1st Half", "Total Goals O/U - 2nd Half", "Correct Score (Regular Time)", "1st Goal", "Team 1 to Score", "Team 2 to Score", "Both Teams to Score 1st Half", "Number of Corners (Regular Time)", "Red Card - Yes/No", "Total Goals - Exact", "Total Goals - Exact", "Away No Bet", "Home No Bet" ];
		                	if(in_array($bet_event_cat_name,$_categories)){
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd' ";
												mysqli_query($conn,$bet_options_query);
												echo $conn->error;
					                        }
											$o_sort++;
									}
				                }
		                	}
		                }else{
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id')";
									mysqli_query($conn,$categories_query);
									
									$o_sort=1;
									foreach ($category['results'] as $option_key => $option) {
											$bet_option_id   = $option['id'];
					                        $option_visible  = $option['visibility'];
					                        $oname           = $option['name']['value'];
					                        $bet_option_name = str_replace("'", "''", $oname);
					                        $bet_option_odd  = $option['odds'];			      
					                        if($option_visible='Visible'){
					                        	//options update
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status, bet_event_cat_id,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', 'awaiting', '$bet_event_cat_id','$sport_id') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd'";
												mysqli_query($conn,$bet_options_query);
					                        }
											$o_sort++;
									}
									break;
				                }
		                }
						$c_sort++;
					}
				}
			}

			if(mysqli_commit($conn)){
				echo "Updated Successfully";
			}else{
				echo mysqli_error($conn);
				echo "Failed to udpate!";
			}
		}

		function get_all_event_ids_cricket($spid,$conn){
		$query="SELECT bet_event_id FROM af_inplay_bet_events WHERE spid = 3";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		return $event_id_array;
	}
	
	
	
	
	
	
	
	
	
	
	function updateEventsCricket($event_ids,$spid,$tk,$conn){
		//mysqli_query($conn,"DELETE FROM af_inplay_bet_events WHERE spid=3");
		//mysqli_query($conn,"DELETE FROM af_inplay_bet_events_cats WHERE spid=3");
		//mysqli_query($conn,"DELETE FROM af_inplay_bet_options WHERE spid=3");
		
		mysqli_query($conn,"DELETE FROM af_inplay_bet_events_cats WHERE yn IS NULL AND spid=3");
		mysqli_query($conn,"DELETE FROM af_inplay_bet_options WHERE spid=3");
		
		mysqli_autocommit($conn,FALSE); //transaction begin

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			echo "<h1>".$event_ids_set."</h1><br>";
			$url="https://api.betsapi.com/v1/bet365/event?token=2645-9zwZs0KT0m3e5L&FI=$event_ids_set";
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
	        $result = json_decode($data, true);
	        $newArray      = array();
	        $event_details = array(
	            'event_name' => '',
	            'event_id' => $event_id,
	            'team' => ''
	        );
	        if (isset($result['results'])) {
	            foreach ($result['results'][0] as $j) {
	                if ($j['type'] == 'EV') {
	                    $event_details['event_name'] = $j['NA'];
	                }
	                if ($j['type'] == 'MG') {
	                    array_push($newArray, array());
	                    $newArray[sizeof($newArray) - 1]['cat_name'] = $j['NA'];
	                    $newArray[sizeof($newArray) - 1]['cat_id']   = $j['ID'];
	                    
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
	                                    $odd = round(($odd[0] / $odd[1]), 2) + 1;
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

	        if (empty($result['results'])) {
	            $sus = '<div class="suspendev">Event Suspended</div>';
	        }

	        foreach ($newArray as $key => $value) {
	            if (!isset($newArray[1])) {
	                unset($newArray[$key]);
	            }
	        }

	        //For insert in af_cric_bet_events

	        mysqli_autocommit($conn,FALSE); //transaction begin

	        // $bet_event_id   = $event_details['event_id'];
	        $bet_event_name = $event_details['event_name'];
	        $event_id       = $event_details['event_id'];
	        $event_name     = 'Cricket';
	        $sport_id       = '3';
	        $sport_name     = 'Cricket';
	        $deadline       = time();
	        $c_sort = 1;
	        //event insert

	        if($bet_event_id!=0){
		        foreach ($newArray as $key => $value) {
		            $ro = $value['cat_name'];
		            if (isset($value[1])) {
		                if (sizeof($value[1]) > 0 && empty(preg_match("/^Runs off (.*)/i", $ro)) && empty(preg_match("/^Runs in First (.*)/i", $ro)) && $ro !== 'Method of Dismissal 6-Way' && $ro !== 'Dismissal Method' && $ro !== 'Next Man Out' && empty(preg_match('/\b(?: - Runs Odd/Even)\b/i', $ro))) {
		                    //for insert in af_cric_bet_events_cats
		                    $bet_event_cat_id   = $value['cat_id']+rand(100000000, 999999999);
		                    $c_sort             = $c_sort;
		                    $bet_event_cat_name = $value['cat_name'];
		                    $o_sort = -1;

		                    //cat insert

		                    $CATEGORY_INSERT_QUERY="INSERT IGNORE INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id') ON DUPLICATE KEY UPDATE bet_event_cat_name = '$bet_event_cat_name'";
		                    
		                    mysqli_query($conn,$CATEGORY_INSERT_QUERY);

		                    foreach ($value as $key_child => $value_child) {
		                        if (is_array($value_child)) {
		                            //for insert in af_inplay_bet_options
		                            $bet_option_id   = $value_child['odd_id'];
		                            //$o_sort          = $o_sort;
		                            $bet_option_name = trim($value_child['odd_name']);
		                            $bet_option_odd  = $value_child['odd'];

		                            $OPTION_INSERT_QUERY="INSERT IGNORE INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,cat_name_in_option,spid) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$bet_event_cat_name','$sport_id') ON DUPLICATE KEY UPDATE bet_option_name = '$bet_option_name', bet_option_odd = '$bet_option_odd', cat_name_in_option = '$bet_event_cat_name'";
		                           
		                            mysqli_query($conn,$OPTION_INSERT_QUERY);
		                            //odd insert 
		                            
		                        }
		                        $o_sort++;
		                    }
							
		                }
		            }
		            $c_sort++;
		        }
		    }
	        if(mysqli_commit($conn)){
	                echo "Update Successfully";
	            }else{
	                echo mysqli_error($conn);
	                echo "Failed to udpate!";
	        	}
        }
	}
    
	updateEventsCricket(get_all_event_ids_cricket(5,$conn),5,'',$conn);
	updateEvents(get_all_event_ids(5,$conn),5,$tk,$conn);
	
 
	
	function httpGet($url){
	    $ch = curl_init();  
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    $output=curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	echo httpGet("https://cricmarkets.com:4200/broadcast_changes");
 ?>