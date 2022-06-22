<?php include('../db.php');

//for prematch active slips
if(isset($_POST['method']) && $_POST['method'] == 'pre_active_counts'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ttcount, COUNT(CASE WHEN type = 'back' THEN 1 END) AS tback, COUNT(CASE WHEN type = 'lay' THEN 1 END) AS tlay, COUNT(CASE WHEN type = 'sbook' THEN 1 END) AS tbook, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoday FROM sh_sf_slips_history WHERE status = 'awaiting'"));
	
?>
Today's Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttoday'];?>">
Total Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttcount'];?>">
Back: <input type="text" class="ttcount" value="<?php echo $ccs['tback'];?>">
Lay: <input type="text" class="ttcount" value="<?php echo $ccs['tlay'];?>">
SportsBook: <input type="text" class="ttcount" value="<?php echo $ccs['tbook'];?>">

<?php } 

//for prematch settled slips
else if(isset($_POST['method']) && $_POST['method'] == 'pre_settled_counts'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ttcount, COUNT(CASE WHEN type = 'back' THEN 1 END) AS tback, COUNT(CASE WHEN type = 'lay' THEN 1 END) AS tlay, COUNT(CASE WHEN type = 'sbook' THEN 1 END) AS tbook, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoday FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed')"));
$dds = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN status = 'winning' THEN 1 END) AS twin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS tlose, COUNT(CASE WHEN status = 'canceled' THEN 1 END) AS tcan, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoda FROM sh_sf_slips_history"));
?>
Today's Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttoday'];?>">
Total Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttcount'];?>">
Back: <input type="text" class="ttcount" value="<?php echo $ccs['tback'];?>">
Lay: <input type="text" class="ttcount" value="<?php echo $ccs['tlay'];?>">
SportsBook: <input type="text" class="ttcount" value="<?php echo $ccs['tbook'];?>">
<hr>
Winning Tickets: <input type="text" class="ttcount xp" value="<?php echo $dds['twin'];?>">
Losing Tickets: <input type="text" class="ttcount xp" value="<?php echo $dds['tlose'];?>">
Canceled Tickets: <input type="text" class="ttcount xp" value="<?php echo $dds['tcan'];?>">
<a href="/admin/tickets_stats">Check full stats here..</a>

<?php } 

//for inplay active slips
else if(isset($_POST['method']) && $_POST['method'] == 'in_active_counts'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ttcount, COUNT(CASE WHEN type = 'back' THEN 1 END) AS tback, COUNT(CASE WHEN type = 'lay' THEN 1 END) AS tlay, COUNT(CASE WHEN type = 'sbook' THEN 1 END) AS tbook, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoday FROM sh_sf_tickets_records WHERE status = 'awaiting'"));
	
?>
Today's Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttoday'];?>">
Total Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttcount'];?>">
Back: <input type="text" class="ttcount" value="<?php echo $ccs['tback'];?>">
Lay: <input type="text" class="ttcount" value="<?php echo $ccs['tlay'];?>">
SportsBook: <input type="text" class="ttcount" value="<?php echo $ccs['tbook'];?>">

<?php } //for inplay settled slips
else if(isset($_POST['method']) && $_POST['method'] == 'in_settled_counts'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ttcount, COUNT(CASE WHEN type = 'back' THEN 1 END) AS tback, COUNT(CASE WHEN type = 'lay' THEN 1 END) AS tlay, COUNT(CASE WHEN type = 'sbook' THEN 1 END) AS tbook, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoday FROM sh_sf_tickets_history WHERE status NOT IN('awaiting', 'trashed')"));

$dds = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN status = 'winning' THEN 1 END) AS twin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS tlose, COUNT(CASE WHEN status = 'canceled' THEN 1 END) AS tcan, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoda FROM sh_sf_tickets_history"));
?>
Today's Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttoday'];?>">
Total Tickets: <input type="text" class="ttcount" value="<?php echo $ccs['ttcount'];?>">
Back: <input type="text" class="ttcount" value="<?php echo $ccs['tback'];?>">
Lay: <input type="text" class="ttcount" value="<?php echo $ccs['tlay'];?>">
SportsBook: <input type="text" class="ttcount" value="<?php echo $ccs['tbook'];?>">
<hr>
Winning Tickets: <input type="text" class="ttcount xp" value="<?php echo $dds['twin'];?>">
Losing Tickets: <input type="text" class="ttcount xp" value="<?php echo $dds['tlose'];?>">
Canceled Tickets: <input type="text" class="ttcount xp" value="<?php echo $dds['tcan'];?>">
<a href="/admin/tickets_stats">Check full stats here..</a>

<?php } ?>