<?php include('../db.php'); //error_reporting(0);
if(isset($_POST['method']) && $_POST['method'] == 'debit_balance'){
	$stake = $_POST['stake'];
	echo $usid = $_POST['usid'];
	echo $uc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = ".$usid.""));
	echo $chip_b = $uc['chips'];
	$promo_b = $uc['promo'];
	var_dump($uc);
	echo $conn->error;
	if($chip_b > $stake){
		mysqli_query($conn,"UPDATE users SET chips = chips - $chip_b WHERE id=$usid");
		echo 'success';
		die();
	}else if($promo_b > $stake){
		mysqli_query($conn,"UPDATE users SET promo = promo - $promo_b WHERE id=$usid");
		echo 'success promo';
		die();
	}else {
		echo 'Insufficient Balance';
		echo $conn->error;
		die();
	}
}
exit;?>