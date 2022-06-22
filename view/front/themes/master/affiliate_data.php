<?php $db = $_SERVER['DOCUMENT_ROOT']."/shell/"; include_once($db."db.php");
$xr = "SELECT ex_comi, sp_comi FROM `risk_management` ";
$result = mysqli_query($conn, $xr);
$c_row = $result->fetch_assoc();
$exc = $c_row['ex_comi'];
$spcomi = $c_row['sp_comi'];

//today users calculation data
$ctoday = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE() THEN 1 END) AS rtoday, COUNT(CASE WHEN active = 'y' THEN 1 END) AS ractive, COUNT(CASE WHEN active <> 'y' THEN 1 END) AS rinactive FROM users WHERE afid = '$usid' AND id <> $usid"));

//For exchange today earning
/*
$eatoday = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings - stake) AS expre FROM sh_sf_slips_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND status = 'winning' AND type <> 'sbook' AND aid = '$usid' AND debit = 'chips'"));
$inplay_ex = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings - stake) AS inplayex FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND status = 'winning' AND type <> 'sbook' AND aid = '$usid' AND debit = 'chips'"));
$net_value = $eatoday['expre'] + $inplay_ex['inplayex'];
$exchange_prematch = $net_value * $exc/100;

//for sportsbook today earning
$sbook_pre = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'losing' THEN stake END) AS booklose, SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS bookwin FROM sh_sf_slips_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND type = 'sbook' AND aid = '$usid' AND debit = 'chips'"));
$inplay_sbook = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'losing' THEN stake END) AS inplaylose, SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS inplaywin FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND type = 'sbook' AND aid = '$usid' AND debit = 'chips'"));

$pre_sbooksum = $sbook_pre['booklose'] - $sbook_pre['bookwin'];
$inplay_sbooksum = $inplay_sbook['inplaylose'] - $inplay_sbook['inplaywin'];
$sum_sbook = $pre_sbooksum + $inplay_sbooksum;
$final_sbook = $sum_sbook * $spcomi/100;

//final commission calculation today earning
$net_commission = $exchange_prematch + $final_sbook;
*/

$earn = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN FROM_UNIXTIME(dt,'%Y-%m-%d') >= CURDATE()- INTERVAL 1 DAY THEN amt END) AS todayearn FROM sh_agents_credit_records WHERE agent_id = $usid"));

$net_commission =$earn['todayearn'];





//pending volume
$pending_prematch = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS prepends FROM sh_sf_slips_history WHERE user_id <> '$usid' AND status = 'awaiting' AND aid = '$usid' AND debit = 'chips'"));
$pending_inplay = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS inplaypend FROM sh_sf_tickets_history WHERE user_id <> '$usid' AND status = 'awaiting' AND aid = '$usid' AND debit = 'chips'"));

$pending_volume = $pending_prematch['prepends'] + $pending_inplay['inplaypend'];

$sg = mysqli_fetch_assoc(mysqli_query($conn,"SELECT agent_notice FROM sh_messages_board"));
$kge=$sg['agent_notice'];









