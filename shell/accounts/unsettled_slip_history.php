<?php include('../db.php');?>
 <?php
 //for inplay
 if(isset($_POST['method']) && $_POST['method'] == 'inplay'){
 $usid = $_POST['usid'];	 
 $sql = "SELECT * FROM sh_sf_tickets_records WHERE user_id = $usid AND status = 'awaiting' ORDER BY date DESC LIMIT 20";
 }
 //for prematch 
 if(isset($_POST['method']) && $_POST['method'] == 'pre'){
 $usid = $_POST['usid'];	 
 $sql = "SELECT * FROM sh_sf_slips_history WHERE user_id = $usid AND status = 'awaiting' ORDER BY date LIMIT 20";
 } 
 //for games
 if(isset($_POST['method']) && $_POST['method'] == 'games'){
	 echo '<div style="padding:10px">No Active tickets found..</div>';
	 die();
 }
 
 //date filter inplay
 if(isset($_POST['method']) && $_POST['method'] == 'inplaydatefilter'){
 $usid = $_POST['usid'];
 $dt1 = $_POST['dt1'];
 $dt2 = $_POST['dt2'];
 $start = strtotime($dt1);
 $end = strtotime($dt2);
  echo '<div class="timefram">In-Play filter '.$dt1.' TO '.$dt2.'</div>';
 $sql = "SELECT * FROM sh_sf_tickets_history WHERE user_id = $usid AND date >=".$start." AND date <= ".$end." AND status = 'awaiting' ORDER BY date LIMIT 100";
 }
 
 //date filter prematch
 if(isset($_POST['method']) && $_POST['method'] == 'predatefilter'){
 $usid = $_POST['usid'];
 $dt1 = $_POST['dt1'];
 $dt2 = $_POST['dt2'];
 $start = strtotime($dt1);
 $end = strtotime($dt2);
  echo '<div class="timefram">Prematch filter '.$dt1.' TO '.$dt2.'</div>';
 $sql = "SELECT * FROM sh_sf_slips_history WHERE user_id = $usid AND date >=".$start." AND date <= ".$end." AND status = 'awaiting' ORDER BY date LIMIT 100";
 }
 
 //inplay loadmore
 if(isset($_POST['method']) && $_POST['method'] == 'lomore'){
	 $rc = $_POST['rc'];
	 $usid = $_POST['usid'];
	 $sql = "SELECT * FROM sh_sf_tickets_records WHERE user_id = $usid AND status = 'awaiting' ORDER BY date DESC LIMIT $rc, 20";
 }
 
 
 //preloadmore
 if(isset($_POST['method']) && $_POST['method'] == 'prelomore'){
	 $rc = $_POST['rc'];
	 $usid = $_POST['usid'];
	 $sql = "SELECT * FROM sh_sf_slips_history WHERE user_id = $usid AND status = 'awaiting' ORDER BY date LIMIT $rc, 50";
 }


 $result = mysqli_query($conn, $sql);
 echo '<input type="hidden" class="cfvalue" value="20">';  
 if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$slip_id = $row['slip_id'];
			$user_id = $row['user_id'];
			$status = $row['status'];
			$stake = $row['stake'];
			$winnings = $row['winnings'];
			$date = $row['date'];
			$bet_info = $row['bet_info'];
			$odd = $row['odd'];
			$event_id = $row['event_id'];
			$event_name = $row['event_name'];
			$cat_name = $row['cat_name'];
			$cat_id = $row['cat_id'];
			$bet_option_id = $row['bet_option_id'];
			$bet_option_name = $row['bet_option_name'];
			$aid = $row['aid'];
			$type = $row['type'];
			$sp = $row['sp'];
			$debit = $row['debit'];
			
			if(!empty($bet_info)):?>
			
			<ul class="aslipwrap">
			 <li style="background:#e0e0e0">
			  <div class="fwrap">
			  <span class="leftwrap" style="font-weight:bold;color:#f00"><?php echo 'Multi Bet';?></span>
			  <span class="rightwrap">stake <?php echo $stake;?> / win <?php echo $winnings;?></span></br>
	<div class="eventslip"><a class="showmulti" id="<?php echo $slip_id;?>">Show Bet Details</a> <span class="rightwrap type"><?php echo $type;?></span> </div>
	
	<div class="showmfull" id="showm-<?php echo $slip_id;?>" style="display:none">
	<?php $data=unserialize($bet_info);
	foreach($data as $key=>$bet){?>
			 <div class="fwrap">
			  <span class="leftwrap"><?php echo date ("m-d H:i", $bet['date']);?></span>
			  <span class="rightwrap"><?php echo $bet['cat_name'];?></span></br>
			  
			  <span class="leftwrap bold"><?php echo $bet['bet_option_name'];?></span>
			  <span class="rightwrap od"><?php echo $bet['odd'];?></span></br>
			  
			  <span class="leftwrap"></span>
			  <span class="rightwrap"></span>
	        <div class="eventslip">
	          <?php echo $bet['event_name'];?> <span class="rightwrap type"><?php echo $status;?></span> </div>
			</div>
		
		
		
		
	<?php } ?>
	</div>
			  </div>
			 </li>
			</ul>
			
			
			<?php else:?>
			
			<ul class="aslipwrap">
			 <li>
			  <div class="fwrap">
			  <span class="leftwrap"><?php echo date ("m-d H:i", $date);?></span>
			  <span class="rightwrap"><?php echo $cat_name;?></span></br>
			  
			  <span class="leftwrap bold"><?php echo $bet_option_name;?></span>
			  <span class="rightwrap od"><?php echo $odd;?></span></br>
			  
			  <span class="leftwrap">Stake <?php echo $stake;?> / win <?php echo $winnings;?></span>
			  <span class="rightwrap"><?php echo $status;?></span>
			  
			  <span class="leftwrap"></span>
			  <span class="rightwrap"></span>
	<div class="eventslip"><?php echo $event_name;?> <span class="rightwrap type"><?php echo $type;?></span> </div>
			  </div>
			 </li>
			</ul>
			<?php endif;?>
			
			
				

			
  <?php }
  } else {
	  echo '<div style="padding:10px">No active Tickets Found</div>';
  }
 if ($result->num_rows > 1) {
	echo '<div class="addload" id="triggerlo"></div>';
	echo '<div id="lrem" class="loadmo inplay">Load More...</div>';
  }	


?>
 