<?php
header("Content-Type:application/json");
// /games/au_ewallet?opKey=DffdhzEPup2MMJLH&userId=1
//if (isset($_GET['opKey']) && $_GET['userId']!="" && $_GET['opKey'] == "DffdhzEPup2MMJLH") {
include('../db.php');
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data["pid"];

function getAcommission($curr, $conn){
	 $query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur); 
 }
 
		
		
$ubal=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stripe_cus, chips FROM users WHERE id=$userId"));
$gamt = $ubal['chips'];
$curr = $ubal['stripe_cus'];
$agrate = getAcommission($curr, $conn);
$agvalue = $agrate['rate'];
$amount = $gamt;
 if(!empty($amount)){
 $status = 'true';
 $error = '';
 response($status, $amount, $error);
 mysqli_close($conn);
 }else{
 $amount = '0';
 $status = 'false';
 $error = 'Internal Error';
 response($status, $amount, $error);
 }
/*}else{
 response(NULL, NULL, 400,"Invalid Request");
 }
 */
function response($status, $amount, $error){
 $response['status'] = $status;	
 $response['balance'] = $amount;
 $response['error'] = $error;
 $json_response = json_encode($response);
 echo $json_response;
}
$conn -> close();

?>