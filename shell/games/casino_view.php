<?php include('../db.php');
$usid =  $_POST['usid'];
$gameid = $_POST['gameid'];
//$ngame = $_POST['ngame'];
$device = $_POST['devi'];
$currency="USD";

if(isset($_POST['method']) && $_POST['method'] == 'casino_view'){
	
$login = 'gambabet';
$password = 'sDA5xUxG12XLsoiz';	
$surl = "https://api.riseupgames.net/".$gameid."/session";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$surl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
$result = curl_exec($ch);
curl_close($ch);  
$res = json_decode($result, true); 
$sessionId = $res['data'];

	

$url="https://api.riseupgames.net/".$gameid."/session/new";
//$xch = curl_init($url);

$data = array("userID"=>"".$usid."","username"=>"gambabet","session"=>"".$sessionId."","currency"=>"".$currency."","device"=>"".$device."","gameID"=>"0");
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
//var_dump($response);
//echo '<a target="_blank" id="">'.$urs.'</a>';


//curl -X POST "http://api.riseupgames.net/habanero/session/new" -H "accept: */*" -H "Authorization: Basic U3BvcnRDYWZmVGVzdDpac3pHNEhqTWJqTmM2ckJx" -H "Content-Type: application/json" -d "{\"userID\":\"testuser\",\"session\":\"E5E7F0C4F04041DDF07A3BAB152740A8\",\"gameID\":\"AllForOne\"}"






};
?>