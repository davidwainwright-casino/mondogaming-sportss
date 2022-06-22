<?php
include_once('db.php');error_reporting(0);

$query="TRUNCATE TABLE currencies ";
$isDeleted=mysqli_query($conn,$query);

$url = 'https://currencyapi.net/api/v1/rates?key=fZnIkKy9mDoLMQjFAbPbEs8083f7THvmPDf5';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
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
$obj = json_decode($data, true); 
//var_dump($obj['rates']); 
foreach($obj['rates'] as $key=>$ob){
     $symbol = $key;
	 $rate = round($ob,2);
	 echo '<option value="'.$symbol.'">'.$symbol.'</option>';
	 //$curr =  mysqli_query($conn, "UPDATE users SET fb_link=$rate WHERE stripe_cus='$symbol'");
	 $curr =  mysqli_query($conn, "INSERT INTO `currencies` (`name`, `rate`) VALUES ('$symbol',$rate)");
			 if (mysqli_affected_rows($conn) > 0){
				//echo 'updated';
			 } else {
				 //echo "Error: " . $curr . "<br>" . $conn->error;		 
			 }
	
	
	}
//var_dump($data);;























?>