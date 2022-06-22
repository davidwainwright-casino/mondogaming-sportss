<?php include('../../db.php');

//for deposits
if(isset($_POST['method']) && $_POST['method'] == 'deposits_data'){
$usid = $_POST['usid'];
if(!empty($usid)){	
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN 1 END) AS today, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN amount END) AS todayearn, COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN amount END) AS yesterdayearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN 1 END) AS week, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN amount END) AS weekearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN 1 END) AS month, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN amount END) AS monthearn, COUNT(id) AS total, sum(amount) AS amountearn, COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS pend, SUM(CASE WHEN status = 'Pending' THEN amount END) AS pendearn, COUNT(CASE WHEN status = 'Processing' THEN 1 END) AS pro, SUM(CASE WHEN status = 'Processing' THEN amount END) AS proearn FROM sh_sf_deposits WHERE status<>'Canceled' AND user_id=$usid"));
} else {
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN 1 END) AS today, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN amount END) AS todayearn, COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN amount END) AS yesterdayearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN 1 END) AS week, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN amount END) AS weekearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN 1 END) AS month, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN amount END) AS monthearn, COUNT(id) AS total, sum(amount) AS amountearn, COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS pend, SUM(CASE WHEN status = 'Pending' THEN amount END) AS pendearn, COUNT(CASE WHEN status = 'Processing' THEN 1 END) AS pro, SUM(CASE WHEN status = 'Processing' THEN amount END) AS proearn FROM sh_sf_deposits WHERE status<>'Canceled'"));		
}
	
?>
Today: <input type="text" class="ttcount" value="<?php echo round($ccs['todayearn'],2);?>/<?php echo $ccs['today'];?>" disabled>
Yesterday: <input type="text" class="ttcount" value="<?php echo round($ccs['yesterdayearn'],2);?>/<?php echo $ccs['yesterday'];?>" disabled>
This week: <input type="text" class="ttcount" value="<?php echo round($ccs['weekearn'],2);?>/<?php echo $ccs['week'];?>" disabled>
This Month: <input type="text" class="ttcount" value="<?php echo round($ccs['monthearn'],2);?>/<?php echo $ccs['month'];?>" disabled>
Total: <input type="text" class="ttcount" value="<?php echo round($ccs['amountearn'],2);?>/<?php echo $ccs['total'];?>" disabled>
Pending: <input type="text" class="ttcount" value="<?php echo round($ccs['pendearn'],2);?>/<?php echo $ccs['pend'];?>" disabled>
Processing: <input type="text" class="ttcount" value="<?php echo round($ccs['proearn'],2);?>/<?php echo $ccs['pro'];?>" disabled>

<?php } 

//for processing
else if(isset($_POST['method']) && $_POST['method'] == 'processtx'){
$pid = $_POST['pid'];
$conn -> query("UPDATE sh_sf_deposits SET status = 'Processing' WHERE id=$pid");	
}
//for cancel
else if(isset($_POST['method']) && $_POST['method'] == 'canceltx'){
$pid = $_POST['pid'];
$conn -> query("UPDATE sh_sf_deposits SET status = 'Canceled' WHERE id=$pid");	
}
//for received
else if(isset($_POST['method']) && $_POST['method'] == 'markrec'){
$pid = $_POST['pid'];
$usid = $_POST['usid'];
$sql = "SELECT user_id, amount FROM sh_sf_deposits WHERE id=$pid";
 $result = $conn->query($sql);
 while($row = $result->fetch_assoc()) {
  $user_id = $row['user_id'];
  $amount = $row['amount'];

  $ofyes = $conn -> query("UPDATE sh_sf_deposits SET status = 'Received' WHERE id=$pid");
  
  if (mysqli_affected_rows($conn) > 0){
   $conn -> query("UPDATE users SET chips = chips + $amount WHERE id=$user_id");
    if (mysqli_affected_rows($conn) > 0){
    echo 'Done! Credited '.$amount.'';
     }
    }
	
  }
}












?>