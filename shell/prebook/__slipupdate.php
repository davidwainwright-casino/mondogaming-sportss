<?php include_once('../db.php');include_once("__odds_switcher.php");
$sdata = $_POST['sdata'];
$tkn=mysqli_fetch_assoc(mysqli_query($conn,"SELECT serial FROM `www_token`"));
$tk = $tkn['serial'];


//function update
function odd_updated_value($event_id, $bet_option_id,$bet_option_name,$conn){
		$cquery = mysqli_fetch_assoc(mysqli_query($conn,"SELECT bet_option_odd FROM af_pre_bet_options WHERE bet_option_id=$bet_option_id AND bet_option_name='$bet_option_name'"));
		return $cquery['bet_option_odd'];
	}

		
		
		$data=$sdata;
		$odata=array();
		foreach($data as $key => $value) {
			$obj=$value;
		$new_odd=odd_updated_value($value['event_id'],$value['bet_option_id'],$value['bet_option_name'],$conn);
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