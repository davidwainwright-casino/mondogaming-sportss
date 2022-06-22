<?php include('../../db.php');

//for deposits
if(isset($_POST['method']) && $_POST['method'] == 'transfers_data'){
$usid = $_POST['usid'];
if(!empty($usid)){	
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN 1 END) AS today, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN amount END) AS todayearn, COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN amount END) AS yesterdayearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN 1 END) AS week, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN amount END) AS weekearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN 1 END) AS month, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN amount END) AS monthearn, COUNT(id) AS total, sum(amount) AS amountearn FROM sh_sf_transfers WHERE status<>'Canceled' AND user_id=$usid"));
} else {
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN 1 END) AS today, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN amount END) AS todayearn, COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN amount END) AS yesterdayearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN 1 END) AS week, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN amount END) AS weekearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN 1 END) AS month, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN amount END) AS monthearn, COUNT(id) AS total, sum(amount) AS amountearn FROM sh_sf_transfers WHERE status<>'Canceled'"));		
}
	
?>
Today: <input type="text" class="ttcount" value="<?php echo round($ccs['todayearn'],2);?>/<?php echo $ccs['today'];?>" disabled>
Yesterday: <input type="text" class="ttcount" value="<?php echo round($ccs['yesterdayearn'],2);?>/<?php echo $ccs['yesterday'];?>" disabled>
This week: <input type="text" class="ttcount" value="<?php echo round($ccs['weekearn'],2);?>/<?php echo $ccs['week'];?>" disabled>
This Month: <input type="text" class="ttcount" value="<?php echo round($ccs['monthearn'],2);?>/<?php echo $ccs['month'];?>" disabled>
Total: <input type="text" class="ttcount" value="<?php echo round($ccs['amountearn'],2);?>/<?php echo $ccs['total'];?>" disabled>


<?php } 

//for processing
else if(isset($_POST['method']) && $_POST['method'] == 'processtx'){
$pid = $_POST['pid'];
$conn -> query("UPDATE sh_sf_withdraws SET status = 'Processing' WHERE id=$pid");	
}
//for cancel
else if(isset($_POST['method']) && $_POST['method'] == 'canceltx'){
$pid = $_POST['pid'];
$conn -> query("UPDATE sh_sf_withdraws SET status = 'Canceled' WHERE id=$pid");	
}


//Revert transfer
else if(isset($_POST['method']) && $_POST['method'] == 'revert_transfer'){
$pid = $_POST['pid'];
//$usid = $_POST['usid'];
$sql = "SELECT user_id, amount, send_to FROM sh_sf_transfers WHERE id=$pid";
 $result = $conn->query($sql);
 while($row = $result->fetch_assoc()) {
  $user_id = $row['user_id'];
  $amount = $row['amount'];
  $semail = $row['send_to'];

  $ofyes = $conn -> query("UPDATE sh_sf_transfers SET status = 'Canceled' WHERE id=$pid");
  
  if (mysqli_affected_rows($conn) > 0){
   $conn -> query("UPDATE users SET chips = chips - $amount WHERE email = '$semail'");
    if (mysqli_affected_rows($conn) > 0){
    $conn -> query("UPDATE users SET chips = chips + $amount WHERE id = $user_id");
	if (mysqli_affected_rows($conn) > 0){
		echo 'Done!';
	  }else{
	    $conn -> query("UPDATE users SET chips = chips + $amount WHERE email = '$semail'"); 
		echo 'Failed!';
	  }
     }
    }
	
  }
}


?>