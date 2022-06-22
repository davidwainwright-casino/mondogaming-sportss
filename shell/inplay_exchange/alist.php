<?php error_reporting(0);
	include_once('../db.php');
	$xr     = "SELECT serial FROM `www_token` ";
	$result = mysqli_query($conn, $xr);
	$c_row  = $result->fetch_assoc();
	$tk     = $c_row['serial'];
    include_once('inplay_list.php');
	function get_all_event_ids($spid,$conn){
		$query="SELECT bet_event_id FROM af_inplay_bet_events WHERE spid <> 3";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		return array_chunk($event_id_array,10);
	}
	
	//SET 1 means delete, set 2 is to keep category 1
	mysqli_query($conn,"UPDATE af_inplay_bet_events_cats SET dl=1 WHERE c_sort=1");
	mysqli_query($conn,"UPDATE af_inplay_bet_options SET dl=1 WHERE dl=2");
	
	function updateEvents($event_ids,$spid,$tk,$conn){
		//mysqli_query($conn,"DELETE FROM af_inplay_list_cats WHERE spid <> 3");
		//mysqli_query($conn,"DELETE FROM af_inplay_list_options WHERE spid <> 3");
		//mysqli_autocommit($conn,FALSE); //transaction begin

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			$url = "https://api.betsapi.com/v1/bwin/event?token=" . $tk . "&event_id=" . implode(",",$event_ids_set);
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
		                		if($cat_visible=='Visible'){
				                	//category update
									$categories_query = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid,dl) VALUES('$bet_event_cat_id', '$c_sort', '$bet_event_cat_name', '$bet_event_id','$sport_id','2') ON DUPLICATE KEY UPDATE bet_event_cat_name = '$bet_event_cat_name', dl='2'";
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
												$bet_options_query="INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id, spid,dl) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id', '$sport_id', '2') ON DUPLICATE KEY UPDATE bet_option_odd = '$bet_option_odd', dl='2'";
												mysqli_query($conn,$bet_options_query);
												//echo("Error description: " . $conn -> error);
					                        }
											$o_sort++;
									}
									break;
				                }
		                
						$c_sort++;
					}
				}
			}

			
}
		
		

		function get_all_event_ids_cricket($spid,$conn){
		$query="SELECT bet_event_id FROM af_inplay_bet_events WHERE spid=3";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		return $event_id_array;
	}
	
	
	
	
	
	
	
	
	
	
	

	function updateEventsCricket($event_ids,$spid,$tk,$conn){
		//mysqli_query($conn,"DELETE FROM af_inplay_list_cats WHERE spid=3");
		//mysqli_query($conn,"DELETE FROM af_inplay_list_options WHERE spid=3");
		//mysqli_autocommit($conn,FALSE); //transaction begin

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			//echo "<h1>".$event_ids_set."</h1><br>";
			$url = 'https://api.betsapi.com/v1/bet365/event?token='.$tk.'&FI=' . $event_ids_set;
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
	                if ($j['type'] == 'PA') {
	                    if ($j['ID'] != 0) {
	                        if ($j['SU'] == '0') {
	                            if (isset($j['NA'])) {
	                                if (!empty($j['NA'])) {
	                                    
	                                    $odd = explode('/', $j['OD']);
	                                    $odd = round(($odd[0] / $odd[1]), 2) + 1;
	                                    array_push($newArray[sizeof($newArray) - 1], array(
	                                        'odd_name' => $j['NA'],
	                                        'odd_id' => $j['ID'],
	                                        'odd' => $odd,
	                                        'su' => $j['SU']
	                                    ));
	                                }
	                            }
	                            
	                        } else {
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

	        //mysqli_autocommit($conn,FALSE); //transaction begin

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
		                if (sizeof($value[1]) > 0 && empty(preg_match("/^Runs off (.*)/i", $ro)) && $ro !== 'Method of Dismissal 6-Way' && $ro !== 'Dismissal Method' && $ro !== 'Next Man Out' && empty(preg_match('/\b(?: - Runs Odd/Even)\b/i', $ro))) {
		                    //for insert in af_cric_bet_events_cats
		                    $bet_event_cat_id   = $value['cat_id']+rand(100000000, 999999999);
		                    $c_sort             = $c_sort;
		                    $bet_event_cat_name = $value['cat_name'];
		                    $o_sort = -1;

		                    //cat insert

		                    $CATEGORY_INSERT_QUERY="INSERT IGNORE INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id,spid,dl) VALUES('$bet_event_cat_id', '1', '$bet_event_cat_name', '$bet_event_id','$sport_id', '2') ON DUPLICATE KEY UPDATE bet_event_cat_name = '$bet_event_cat_name', dl='2'";
		                    //echo $CATEGORY_INSERT_QUERY;
		                    echo "<br>";
		                    mysqli_query($conn,$CATEGORY_INSERT_QUERY);

		                    foreach ($value as $key_child => $value_child) {
		                        if (is_array($value_child)) {
		                            //for insert in af_inplay_bet_options
		                            $bet_option_id   = $value_child['odd_id'];
		                            //$o_sort          = $o_sort;
		                            $bet_option_name = $value_child['odd_name'];
		                            $bet_option_odd  = $value_child['odd'];

		                            $OPTION_INSERT_QUERY="INSERT IGNORE INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, bet_event_cat_id,cat_name_in_option,spid,dl) VALUES('$bet_option_id', '$o_sort', '$bet_option_name', '$bet_option_odd', '$bet_event_cat_id','$bet_event_cat_name','$sport_id','2') ON DUPLICATE KEY UPDATE bet_option_name = '$bet_option_name', bet_option_odd = '$bet_option_odd', cat_name_in_option = '$bet_event_cat_name', dl='2'";
		                            //echo $OPTION_INSERT_QUERY;
		                            echo "<br>";
		                            mysqli_query($conn,$OPTION_INSERT_QUERY);
		                            //odd insert 
		                            
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

			// if(mysqli_commit($conn)){
			// 	echo "Updated Successfully";
			// }else{
			// 	echo mysqli_error($conn);
			// 	echo "Failed to udpate!";
			// }
	}
	
	

    updateEventsCricket(get_all_event_ids_cricket(10,$conn),10,$tk,$conn); 
	updateEvents(get_all_event_ids(10,$conn),10,$tk,$conn);
	
	include_once('events_scores.php');
	//delete passed categories with dl=1
	/*
	function httpGet($url){
	    $ch = curl_init();  
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    $output=curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	//echo httpGet("https://sp.sportsfier.com:4200/broadcast_changes");
	*/
	
	
	
	mysqli_close($conn);
	exit;
	

 ?>