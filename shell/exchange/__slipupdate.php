<?php include_once('../db.php');include_once("__odds_switcher.php");
$sdata = $_POST['sdata'];
$tkn=mysqli_fetch_assoc(mysqli_query($conn,"SELECT serial FROM `www_token`"));
$tk = $tkn['serial'];


//for prematch odd updates
function custom_odd_updated_value($event_id, $bet_option_id,$bet_option_name,$conn){
		$cquery = mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_option_odd FROM af_pre_bet_options WHERE bet_option_id=$bet_option_id AND bet_option_name='$bet_option_name'"));
		return $cquery['bet_option_odd'];
	}
	
//function update
function odd_updated_value($event_id,$bet_option_id,$bet_option_name,$sp,$tk){
		if($sp==3){
			return isValidOddCricket($event_id,$bet_option_id,$bet_option_name,$sp,$tk);
		} 
        $url="https://api.b365api.com/v1/bwin/event?token=".$tk."&event_id=".$event_id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch) or die(curl_error($ch));
        if ($data === false) {
            echo 'Event Suspended';
        }
        curl_close($ch);
        $json1 = $data; 
        $a = explode('string', $json1);
        for($k =0; $k<count($a); $k++){
            $bet_event_cat_sort_order = 1;
            $bet_option_sort_order = 1;
            $w = trim($a[$k]);
            if(count($a) > 1){
             $w = strstr($w, '"');
            }
            $json = trim($w, '"');
            $obj = json_decode($json, true);
            $b = $obj['results'];
            for($i =0; $i<count($b); $i++){
                $sport_id = $b[$i]['SportId'];
                $event_id = $b[$i]['LeagueId'];
                $cc = $b[$i]['RegionName'];;
                $deadline = $b[$i]['updated_at'];
                $event_name = $b[$i]['HomeTeam']." - ".$b[$i]['AwayTeam'];      
                $c = $b[$i]['Markets'];
                for($j =0; $j<count($c); $j++){
                    $bet_option_sort_order = 1;
                    $bet_event_cat_name = $c[$j]['name']['value'];
                    
                      $bet_event_cat_id = $c[$j]['id']; 
                      $cat_id=$c[$j]['id'];
                      $d = $c[$j]['results'];
                      $bet_option_order = 1;
					  $cvisibility=$c[$j]['visibility'];
					  if($cvisibility !='Suspended'){
                      foreach ($d as $e) {
                        if($e['id']==$bet_option_id){
                        	if($e['visibility']=="Visible"){
								$odd = $e['odds'];
                        		return $odd;
                        	}
                          
                        }
                }
			 }
            
          }
      } 
    }
}


//for bet365 api
function isValidOddCricket($event_id,$bet_option_id,$bet_option_name,$sp,$tk){
	$url = 'https://api.betsapi.com/v1/bet365/event?token='.$tk.'&FI=' . $event_id.'';
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
	$tmt = time() - 50;
	$rek = $result['stats']['update_at'];
	$last_update = $rek;
	if($tmt > $rek){
		return false;
	}
        $newArray=array();
        $event_details=array('event_name'=>'','event_id'=>$event_id,'team'=>'');
        if(isset($result['results'])){
            foreach($result['results'][0] as $j) {
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
								$option_name = trim($okk.' '.$jna.' '.$okhd);  
								$kodd = explode('/', $j['OD']);
	                                    $zodd = round(($kodd[0] / $kodd[1]), 2) + 1;
										$od = round($zodd,2);
										$apiodd = $od;
						
										if($j['ID'] == $bet_option_id){
										if($option_name==trim($bet_option_name)){
												return $apiodd;
											}
										  }
										}
									}
								}
        };
      }else{
      	return false;
      }
    return false;
	
	
	
	
}












		
		
		$data=$sdata;
		$odata=array();
		foreach($data as $key => $value) {
			$obj=$value;
			
			if($value['type']=='pre'){
			$new_odd=custom_odd_updated_value($value['event_id'],$value['bet_option_id'],$value['bet_option_name'],$conn);
			}else{
			$new_odd=odd_updated_value($value['event_id'],$value['bet_option_id'],$value['bet_option_name'],$value['sp'],$tk);	
			}
			if($new_odd){
				$obj['odd']=$new_odd;
				$obj['sodd']=bgetOdd($new_odd);
				
				//$odata[$value['bet_option_id']] = array($new_odd, bgetOdd($new_odd));
				array_push($odata, $obj);
				
				
			}else{
				$obj['odd']=0;
				$obj['sodd']='suspended';
				
				//$odata[$value['bet_option_id']] = array($new_odd, bgetOdd($new_odd));
				array_push($odata, $obj);
				
			}
			
		}
		echo json_encode($odata);