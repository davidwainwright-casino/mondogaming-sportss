<?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];


if(isset($_POST['method']) && $_POST['method'] == 'printreportssa') {
	 $que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}

	$aids = implode (", ", $agent_ids);
	$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN said=$usid THEN 1 END) AS tagent, COUNT(CASE WHEN afid IN($aids) THEN 1 END) AS tdown, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 30 DAY THEN 1 END) AS thismonth, COUNT(id) AS allsa FROM users"));
	$ttickets = mysqli_query($conn, "SELECT slip_id FROM sh_sf_tickets_history WHERE aid IN($aids) UNION SELECT slip_id FROM sh_sf_slips_history WHERE aid IN($aids)");
	$ltickets = mysqli_query($conn, "SELECT slip_id FROM sh_sf_tickets_history WHERE aid IN($aids) AND status='losing' UNION SELECT slip_id FROM sh_sf_slips_history WHERE aid IN($aids) AND status='losing'");
	$wtickets = mysqli_query($conn, "SELECT slip_id FROM sh_sf_tickets_history WHERE aid IN($aids) AND status='winning' UNION SELECT slip_id FROM sh_sf_slips_history WHERE aid IN($aids) AND status='winning'");
	$ctickets = mysqli_query($conn, "SELECT slip_id FROM sh_sf_tickets_history WHERE aid IN($aids) AND status='canceled' UNION SELECT slip_id FROM sh_sf_slips_history WHERE aid IN($aids) AND status='canceled'");
	
	
	
	$wvol = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings-stake) AS vwin FROM sh_sf_tickets_history WHERE status='winning' AND aid IN($aids)"));
	$wvolx = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings-stake) AS vwin FROM sh_sf_slips_history WHERE status='winning' AND aid IN($aids)"));
	$lvol = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS lwin FROM sh_sf_tickets_history WHERE status='losing' AND aid IN($aids)"));
	$lvolx = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS lwin FROM sh_sf_slips_history WHERE status='losing' AND aid IN($aids)"));
	$tvol = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings-stake) AS twin FROM sh_sf_tickets_history WHERE aid IN($aids)"));
	$tvolx = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings-stake) AS twin FROM sh_sf_slips_history WHERE aid IN($aids)"));
	
	$tstake = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS tstake FROM sh_sf_slips_history WHERE aid IN($aids)"));
	$tstakex = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS tstakex FROM sh_sf_tickets_history WHERE aid IN($aids)"));
	

 $cqres = mysqli_query($conn, "SELECT sabal FROM users WHERE id = $usid");
 $check = $cqres->fetch_assoc();
 $bal = $check['sabal'];
	
	//$earn = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(amt) AS allsa FROM sh_agents_credit_records WHERE agent_id IN($aids)"));
	
	$wins = $wvol['vwin']+$wvolx['vwin'];
	$loses = $lvol['lwin']+$lvolx['lwin'];
	$totalvol = $wins+$loses;
	$netearn = $loses - $wins;
	
	
	
?>
	
<h2>Print Reports</h2>

<div class="agdwn">Print your current statistics</div>
<div class="noteus"><b>Info :</b> You can print current statistics of your account. This is mainly for reference purposes. Latest data, at times, may be delayed up to 24 hours or until you are logout and relogin your account.. Gross earnings is not an indicative of your or agents earnings. It's users earnings/losing bet.</div>
</br></br>
	
<div id="printTable">
<div class="printwrap">
<div class="wrtp">
<span class="lefttp">Gambabet.com SA reports</span><span class="righttp"><?php echo date("Y/m/d");?></span>
</div>
 <ul class="printul">
  <li><span class="leftpr">Total Agents</span> <span class="rightpr"><?php echo $ccs['tagent'];?></span></li>
  <li><span class="leftpr">Total Downline</span> <span class="rightpr"><?php echo $ccs['tdown'];?></span></li>
  <li><span class="leftpr">Total Tickets</span> <span class="rightpr"><?php echo $ttickets->num_rows;?></span></li>
  <li><span class="leftpr">Losing Tickets</span> <span class="rightpr"><?php echo $ltickets->num_rows;?></span></li>
  <li><span class="leftpr">Winning Tickets</span> <span class="rightpr"><?php echo $wtickets->num_rows;?></span></li>
  <li><span class="leftpr">Canceled Tickets</span> <span class="rightpr"><?php echo $ctickets->num_rows;?></span></li>
  <li><span class="leftpr">Winning Volume</span> <span class="rightpr"><?php echo round($wins,2);?></span></li>
  <li><span class="leftpr">Losing Volume</span> <span class="rightpr"><?php echo round($loses,2);?></span></li>
  <li><span class="leftpr">Total Volume</span> <span class="rightpr"><?php echo round($totalvol,2);?></span></li>
  
  <li><span class="leftpr">Total Stake</span> <span class="rightpr"><?php echo round($tstake['tstake']+$tstakex['tstakex'],2);?></span></li>
  <li><span class="leftpr">Gross Earnings</span> <span class="rightpr"><?php echo round($netearn,2);?></span></li>
  <hr>
  <li class="ccbtm"><span class="leftpr">Current Blanace</span> <span class="rightpr"><?php echo $bal;?></span></li>
 
 









</ul>
</div>
</div>

<button id="prstats">Print stats</button>


















   <?php } ?>
   
   
   
   
   
   
   
   
   
   
   
   