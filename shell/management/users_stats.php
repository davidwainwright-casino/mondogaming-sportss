<?php include('../db.php');

//for prematch active slips
if(isset($_POST['method']) && $_POST['method'] == 'players_data'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE() THEN 1 END) AS today, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 7 DAY THEN 1 END) AS week, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 30 DAY THEN 1 END) AS month, COUNT(id) AS total, COUNT(CASE WHEN active = 'y' THEN 1 END) AS active FROM users WHERE type = 'member'"));
	
?>
Today: <input type="text" class="ttcount" value="<?php echo $ccs['today'];?>">
Yesterday: <input type="text" class="ttcount" value="<?php echo $ccs['yesterday'];?>">
This week: <input type="text" class="ttcount" value="<?php echo $ccs['week'];?>">
This Month: <input type="text" class="ttcount" value="<?php echo $ccs['month'];?>">
Total: <input type="text" class="ttcount" value="<?php echo $ccs['total'];?>">
Active: <input type="text" class="ttcount" value="<?php echo $ccs['active'];?>">

<?php } else if(isset($_POST['method']) && $_POST['method'] == 'agents_data'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE() THEN 1 END) AS today, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 7 DAY THEN 1 END) AS week, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 30 DAY THEN 1 END) AS month, COUNT(id) AS total, COUNT(CASE WHEN active = 'y' THEN 1 END) AS active FROM users WHERE type = 'agent'"));
	
?>
Today: <input type="text" class="ttcount" value="<?php echo $ccs['today'];?>">
Yesterday: <input type="text" class="ttcount" value="<?php echo $ccs['yesterday'];?>">
This week: <input type="text" class="ttcount" value="<?php echo $ccs['week'];?>">
This Month: <input type="text" class="ttcount" value="<?php echo $ccs['month'];?>">
Total: <input type="text" class="ttcount" value="<?php echo $ccs['total'];?>">
Active: <input type="text" class="ttcount" value="<?php echo $ccs['active'];?>">

<?php } else if(isset($_POST['method']) && $_POST['method'] == 'super_data'){
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE() THEN 1 END) AS today, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 7 DAY THEN 1 END) AS week, COUNT(CASE WHEN created >= CURDATE() - INTERVAL 30 DAY THEN 1 END) AS month, COUNT(id) AS total, COUNT(CASE WHEN active = 'y' THEN 1 END) AS active FROM users WHERE type = 'Sagent'"));
	
?>
Today: <input type="text" class="ttcount" value="<?php echo $ccs['today'];?>">
Yesterday: <input type="text" class="ttcount" value="<?php echo $ccs['yesterday'];?>">
This week: <input type="text" class="ttcount" value="<?php echo $ccs['week'];?>">
This Month: <input type="text" class="ttcount" value="<?php echo $ccs['month'];?>">
Total: <input type="text" class="ttcount" value="<?php echo $ccs['total'];?>">
Active: <input type="text" class="ttcount" value="<?php echo $ccs['active'];?>">

<?php } ?>