<?php error_reporting(0);
	include_once('../db.php');
	
   $ge = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM abc_event_refresh"));
   $kge = $ge['timer']; 
   $maint = $ge['maint']; 
   $tm = time();
   $net = $tm - 30;
   $mnet = $tm - 60;
   
   //for main list
	/*if($maint < $mnet){
	mysqli_query($conn,"UPDATE abc_event_refresh SET maint = $tm");	
	$sp = 'https://sp.sportsfier.com/shell/inplay_exchange/alist';
	$ch =  curl_init(''.$sp.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    $mainR = curl_exec($ch);
	}
	*/
     //for view page
	if($kge < $net){
	$tm = time();
	mysqli_query($conn,"UPDATE abc_event_refresh SET timer = $tm");
	$sp = 'https://sp.sportsfier.com/shell/inplay_exchange/update_events';
	$ch =  curl_init(''.$sp.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    $viewR = curl_exec($ch);
	}
	 
exit;
	