<?php include_once('../db.php');

if(isset($_POST['method']) && $_POST['method'] == 'stats') {
	$agid = $_POST['agid'];
	//$esk = htmlentities($_POST['msg']);
	/*if($ag > 0){
	$agid = $ag;	
	}else{
	 $agid = 'NULL';	
	}
	
	$sa = $_POST['saaid'];
	if($sa > 0){
	$saaid = $sa;	
	}else{
	$saaid = 'NULL';	
	};*/
	$saaid = $_POST['saaid'];
	$uip = $_POST['uip'];
	
	//$res = $conn -> query("SELECT fname, lname FROM stats WHERE ip=$uip");
	//$row = $res->fetch_assoc();
	//$fnn = $row['fname'].' '.$row['lname'];
	$conn->query("INSERT INTO stats (pageviews, uniquevisitors, ip, agid, said) VALUES(1, 1, '$uip', '$agid', '$saaid') ON DUPLICATE KEY UPDATE pageviews = pageviews + 1");
	 if(mysqli_affected_rows($conn) > 0){
		 echo 'Done';
	 } else {
		echo $conn->error; 
	 }
	 
}?>