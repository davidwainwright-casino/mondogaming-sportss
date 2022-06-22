<?php include('../../db.php');

//for deposits
if(isset($_POST['method']) && $_POST['method'] == 'players_performance'){
$usid = $_POST['usid'];

//today
$pre = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS cps, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS cwin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS close, SUM(CASE WHEN status = 'winning' OR status = 'losing' THEN stake END) AS cstake,  SUM(CASE WHEN status = 'winning' THEN winnings END) AS cnet FROM sh_sf_slips_history WHERE status<>'awaiting' AND user_id=$usid AND FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE()"));

$inp = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS cps, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS cwin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS close, SUM(CASE WHEN status = 'winning' OR status = 'losing' THEN stake END) AS cstake, SUM(CASE WHEN status = 'winning' THEN winnings END) AS cnet FROM sh_sf_tickets_history WHERE status<>'awaiting' AND user_id=$usid AND FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE()"));

//this month

$mpre = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS cps, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS cwin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS close, SUM(CASE WHEN status = 'winning' OR status = 'losing' THEN stake END) AS cstake,  SUM(CASE WHEN status = 'winning' THEN winnings END) AS cnet FROM sh_sf_slips_history WHERE status<>'awaiting' AND user_id=$usid AND FROM_UNIXTIME(date,'%Y-%m-%d') > CURDATE() - INTERVAL 7 DAY"));

$minp = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS cps, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS cwin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS close, SUM(CASE WHEN status = 'winning' OR status = 'losing' THEN stake END) AS cstake, SUM(CASE WHEN status = 'winning' THEN winnings END) AS cnet FROM sh_sf_tickets_history WHERE status<>'awaiting' AND user_id=$usid AND FROM_UNIXTIME(date,'%Y-%m-%d') > CURDATE() - INTERVAL 7 DAY"));


//all time
$apre = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS cps, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS cwin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS close, SUM(CASE WHEN status = 'winning' OR status = 'losing' THEN stake END) AS cstake,  SUM(CASE WHEN status = 'winning' THEN winnings END) AS cnet FROM sh_sf_slips_history WHERE status<>'awaiting' AND user_id=$usid"));

$ainp = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS cps, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS cwin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS close, SUM(CASE WHEN status = 'winning' OR status = 'losing' THEN stake END) AS cstake, SUM(CASE WHEN status = 'winning' THEN winnings END) AS cnet FROM sh_sf_tickets_history WHERE status<>'awaiting' AND user_id=$usid"));



$nwin = $pre['cnet']+$inp['cnet'];
$nstake = $pre['cstake']+$inp['cstake'];
$net_win = $nwin - $nstake;

$mnwin = $mpre['cnet']+$minp['cnet'];
$mnstake = $mpre['cstake']+$minp['cstake'];
$mnet_win = $mnwin - $mnstake;

$anwin = $apre['cnet']+$ainp['cnet'];
$anstake = $apre['cstake']+$ainp['cstake'];
$anet_win = $anwin - $anstake;



//$ho = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN 1 END) AS today, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() THEN amount END) AS todayearn, COUNT(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') = CURDATE() - INTERVAL 1 DAY THEN amount END) AS yesterdayearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN 1 END) AS week, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY) THEN amount END) AS weekearn, COUNT(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN 1 END) AS month, SUM(CASE WHEN FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) THEN amount END) AS monthearn, COUNT(id) AS total, sum(amount) AS amountearn FROM sh_sf_transfers WHERE status<>'canceled' AND user_id=$usid"));
?>
<div class="statsdata">
<h4>Today's Stats</h4>
TotalSlips: <input type="text" class="ttcount" value="<?php echo round($pre['cps']+$inp['cps'],2);?>" disabled>
Win/Loss: <input type="text" class="ttcount" value="<?php echo round($pre['cwin']+$inp['cwin'],2);?>/<?php echo round($pre['close']+$inp['close'],2);?>" disabled>
TotalStake: <input type="text" class="ttcount" value="<?php echo round($pre['cstake']+$inp['cstake'],2);?>" disabled>
NetWin: <input type="text" class="ttcount" value="<?php echo $net_win;?>" disabled>
WinRate: <input type="text" class="ttcount" value="<?php $wc = $pre['cwin']+$inp['cwin'];$lc = $pre['close']+$inp['close'];$tt = $wc+$lc;$ts = $pre['cps']+$inp['cps']; $wpc = round($wc*100/$tt,2);if($wpc > 0){ echo $wpc;}else{echo '0';};?>%" disabled>

<h4>This Month's Stats</h4>
TotalSlips: <input type="text" class="ttcount" value="<?php echo round($mpre['cps']+$minp['cps'],2);?>" disabled>
Win/Loss: <input type="text" class="ttcount" value="<?php echo round($mpre['cwin']+$minp['cwin'],2);?>/<?php echo round($mpre['close']+$minp['close'],2);?>" disabled>
TotalStake: <input type="text" class="ttcount" value="<?php echo round($mpre['cstake']+$minp['cstake'],2);?>" disabled>
NetWin: <input type="text" class="ttcount" value="<?php echo $mnet_win;?>" disabled>
WinRate: <input type="text" class="ttcount" value="<?php $wc = $mpre['cwin']+$minp['cwin'];$lc = $mpre['close']+$minp['close'];$tt = $wc+$lc;$ts = $mpre['cps']+$minp['cps']; $wpc = round($wc*100/$tt,2);if($wpc > 0){ echo $wpc;}else{echo '0';};?>%" disabled>

<h4>All Time Stats</h4>
TotalSlips: <input type="text" class="ttcount" value="<?php echo round($apre['cps']+$ainp['cps'],2);?>" disabled>
Win/Loss: <input type="text" class="ttcount" value="<?php echo round($apre['cwin']+$ainp['cwin'],2);?>/<?php echo round($apre['close']+$ainp['close'],2);?>" disabled>
TotalStake: <input type="text" class="ttcount" value="<?php echo round($apre['cstake']+$ainp['cstake'],2);?>" disabled>
NetWin: <input type="text" class="ttcount" value="<?php echo $anet_win;?>" disabled>
WinRate: <input type="text" class="ttcount" value="<?php $wc = $apre['cwin']+$ainp['cwin'];$lc = $apre['close']+$ainp['close'];$tt = $wc+$lc;$ts = $apre['cps']+$ainp['cps']; $wpc = round($wc*100/$tt,2);if($wpc > 0){ echo $wpc;}else{echo '0';};?>%" disabled>
<?php if($apre['cps']+$ainp['cps'] > 20 && $wpc > 70):?><div class="animate-flicker">Warning...</div><?php endif;?>
<div class="noticesp">Note* High total WinRate is not good for the sportsbook, especiall WinRate of more than 70%. Try to limit max bet amount or ban such users solely for profitible business purposes</div>
</div>

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