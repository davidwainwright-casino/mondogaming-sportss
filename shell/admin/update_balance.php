<?php include('../db.php');

 $user_id = $_POST['usid'];
 $chips = $_POST['chips'];
 $afbal = $_POST['afbal'];
 $sabal = $_POST['sabal'];
 $cpromo = $_POST['cpromo'];
 $txn = substr(str_shuffle("TROMUXQWFG48URPLEE945Y"), 0, 20); 
 if(empty($afbal) && !empty($sabal)){
	 $afbal = 0;
 }
 if(empty($sabal) && !empty($afbal)){
	 $sabal = 0;
 }
 if(empty($cpromo)){
	$cpromo = 0;
 }
 
 $chips_amount = $_POST['chipsput'];
 $afbal_amount=$_POST['afbalput'];
 $sabal_amount=$_POST['sabalput'];
 $promo_amount = $_POST['promoput'];
 
$record = mysqli_fetch_assoc(mysqli_query($conn, "SELECT chips, promo,afbal, sabal, afid, said FROM users WHERE id = ".$user_id.""));
	$ichips = $record['chips'];
	$ipromo = $record['promo'];
	$afbals = $record['afbal'];
	$sabals = $record['sabal'];
	$aidd = $record['afid'];
	if(!empty($aidd)){
		$afid = $record['afid'];
	}else{
		$afid ='0';
	}
	$dt = time();
	
	if(!empty($chips_amount)){
	mysqli_query($conn,"INSERT INTO sh_sf_points_log (user_id, comment_id, txn_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'custom credit', '$txn', '$afid', '$chips_amount', '$dt', 'chips', '$ichips', '$chips')");
	}
	if(!empty($afbal_amount)){
	mysqli_query($conn,"INSERT INTO sh_sf_points_log (user_id, comment_id, txn_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'custom credit', '$txn', '$afid', '$afbal_amount', '$dt', 'agentcash', '$afbals', '$afbal')");
	}
	if(!empty($sabal_amount)){
	$i_record = mysqli_query($conn,"INSERT INTO sh_sf_points_log (user_id, comment_id, txn_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'custom credit', '$txn', '$afid', '$sabal_amount', '$dt', 'SAcash', '$sabals', '$sabal')");
	}
	if(!empty($promo_amount)){
	$p_record = mysqli_query($conn,"INSERT INTO sh_sf_points_log (user_id, comment_id, txn_id, aff_id, amount, date, type, bf, af) VALUES ('$user_id', 'promo credit', '$txn', '$afid', '$promo_amount', '$dt', 'promo', '$ipromo', '$cpromo')");
		
	}








 $update_bal = mysqli_query($conn,"UPDATE users SET chips = '$chips', promo = '$cpromo', afbal = '$afbal', sabal = '$sabal' WHERE id= $user_id");
  if ($update_bal) {
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
			echo $conn->error;
     }

