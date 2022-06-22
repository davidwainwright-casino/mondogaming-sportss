<?php error_reporting(0); error_reporting(E_ALL ^ E_NOTICE);
	include_once('../db.php');

$tk = "94840-yL6oCP0lnJl8xM";

	function pull_all_event_ids($spid,$conn){
		$query="SELECT bet_event_id FROM af_pre_bet_events";
		$event_ids=mysqli_query($conn,$query);
		$event_id_array=array();
		while($row=mysqli_fetch_assoc($event_ids)){
			$event_id_array[]=$row['bet_event_id'];
		}
		return array_chunk($event_id_array,10);
	} 
	
	function insertEvents($event_ids,$spid,$tk,$conn){
		//mysqli_query($conn,"DELETE FROM sh_sf_events_scores WHERE spid<>3");
		mysqli_autocommit($conn,FALSE); //transaction begin

		echo '<pre>';
		print_r($event_ids);
		echo '</pre>';

		foreach ($event_ids as $event_id_key => $event_ids_set) {
			$imp = implode(",",$event_ids_set);
			
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

			$homeurl=file_get_contents('https://assets.b365api.com/images/team/m/'.$img_home.'.png');
			$h_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_home.'.png';echo '</br>';
			$h_upload =file_put_contents($h_put_file, $homeurl);
			
			$awayurl=file_get_contents('https://assets.b365api.com/images/team/m/'.$img_away.'.png');
			$a_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_away.'.png';
			$a_upload =file_put_contents($a_put_file, $awayurl);

			  
			     $bwin_scores = "UPDATE af_pre_bet_events SET img_h_id=$img_home, img_a_id=$img_away WHERE bet_event_id=$sf_inplay_id";
				 mysqli_query($conn,$bwin_scores);
				 
				 //echo $conn->error;
				 }
            } 

			if(mysqli_commit($conn)){
				//echo "Updated yes";
			}else{
				//echo mysqli_error($conn);
				echo "Failed to udpatebwin!";
				//echo $conn->error;
			}
		}
	}
	

	insertEvents(pull_all_event_ids(10,$conn),10,$tk,$conn);
	
	exit;
 ?>

