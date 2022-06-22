<?php 
define("_YOYO", true);
  require_once("../../../init.php");
  
 $user_id = $_POST['usid'];
 $chips = $_POST['chips'];
 $afbal = $_POST['afbal'];
 $sabal = $_POST['sabal'];
 $cpromo = $_POST['cpromo'];
 $chips_amount = $_POST['chipsput'];
 $afbal_amount=$_POST['afbalput'];
 $sabal_amount=$_POST['sabalput'];
 $promo_amount = $_POST['promoput'];
 
$sql_pre = "SELECT chips, promo,afbal, sabal, afid, said FROM users WHERE id = ".$user_id."";
$result = Db::run()->pdoQuery($sql_pre);
foreach ($result->aResults as $record) {
	$ichips = $record->chips;
	$ipromo = $record->promo;
	$afbals = $record->afbal;
	$sabals = $record->sabal;
	$aidd = $record->afid;
	if(!empty($aidd)){
		$afid = $record->afid;
	}else{
		$afid ='0';
	}
	$dt = time();
	
	if(!empty($chips_amount)){
	$i_record = "INSERT INTO sh_sf_points_log (user_id, comment_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'custom credit', '$afid', '$chips_amount', '$dt', 'chips', '$ichips', '$chips')";
	$iresult = Db::run()->pdoQuery($i_record);
	}
	if(!empty($afbal_amount)){
	$i_record = "INSERT INTO sh_sf_points_log (user_id, comment_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'custom credit', '$afid', '$afbal_amount', '$dt', 'agentcash', '$afbals', '$afbal')";
	$iresult = Db::run()->pdoQuery($i_record);
	}
	if(!empty($sabal_amount)){
	$i_record = "INSERT INTO sh_sf_points_log (user_id, comment_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'custom credit', '$afid', '$sabal_amount', '$dt', 'SAcash', '$sabals', '$sabal')";
	$iresult = Db::run()->pdoQuery($i_record);
	}
	if(!empty($promo_amount)){
	$p_record = "INSERT INTO sh_sf_points_log (user_id, comment_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'promo credit', '$afid', '$promo_amount', '$dt', 'promo', '$ipromo', '$cpromo')";
	$presult = Db::run()->pdoQuery($p_record);	
		
	}

}

 $update_bal = "UPDATE users SET chips = '$chips', promo = '$cpromo', afbal = '$afbal', sabal = '$sabal' WHERE id= $user_id";
 $uresult = Db::run()->pdoQuery($update_bal);
 
  if ($uresult) {
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
     }


