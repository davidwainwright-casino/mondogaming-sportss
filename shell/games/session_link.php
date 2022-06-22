<?php include('../db.php');
$usid =  $_POST['usid'];
$gameID = $_POST['gameId'];
$ngame = $_POST['ngame'];
$device = $_POST['device'];
$providerID = $_POST['providerID'];
$currency="USD";
$bal="34.50";

if(isset($_POST['method']) && $_POST['method'] == 'session_link'){
	
$login = 'gambabet';
$password = 'sDA5xUxG12XLsoiz';	
$surl = 'https://api.riseupgames.net/'.$providerID.'/session';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$surl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
$result = curl_exec($ch);
curl_close($ch);  
$res = json_decode($result, true); 
$sessionId = $res['data'];

	

$url="https://api.riseupgames.net/".$providerID."/session/new";
//$xch = curl_init($url);

$data = array("userID"=>"".$usid."","username"=>"gambabet","session"=>"".$sessionId."","currency"=>"".$currency."","device"=>"".$device."","gameID"=>"".$gameID."");
$data_json = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);
$aut = json_decode($response, true); 
//var_dump($response);
echo $urs = $aut['data']; 





};
?>