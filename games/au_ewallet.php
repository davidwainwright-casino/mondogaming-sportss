<?php
header("Content-Type:application/json");
// /games/au_ewallet?opKey=DffdhzEPup2MMJLH&userId=1
if (isset($_GET['opKey']) && $_GET['userId']!="" && $_GET['opKey'] == "DffdhzEPup2MMJLH") {
 include('db.php');
 $userId = $_GET['userId'];
$stmt = $con->prepare("SELECT meta_value FROM `sh_usermeta` WHERE user_id=$userId AND meta_key = 'sf_points'");
$stmt->bind_param('s', $name); // 's' specifies the variable type => 'string'
$stmt->execute();
$result = $stmt->get_result();
 if(mysqli_num_rows($result)>0){
 $row = mysqli_fetch_array($result);
 $amount = $row['meta_value'];
 response($amount);
 mysqli_close($con);
 }else{
 response(NULL, NULL, 200,"No Record Found");
 }
}else{
 response(NULL, NULL, 400,"Invalid Request");
 }
 
function response($amount){
 $response['cu_balance'] = $amount;
 $json_response = json_encode($response);
 echo $json_response;
}
$con -> close();
?>