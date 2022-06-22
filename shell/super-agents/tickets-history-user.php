<?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];
 //tickets history
if(isset($_POST['method']) && $_POST['method'] == 'downlinetickets') {
	$sql = "SELECT * FROM sh_sf_tickets_history WHERE aid=$usid UNION SELECT * FROM sh_sf_slips_history WHERE aid=$usid ORDER BY date DESC LIMIT 50";
	$prec = "SELECT slip_id FROM sh_sf_tickets_history WHERE aid=$usid UNION SELECT slip_id FROM sh_sf_slips_history WHERE aid=$usid";
	$gfname = mysqli_query($conn, "SELECT fname FROM users WHERE id=$usid");
	$fname = $gfname->fetch_assoc();
    ?>
	  <div class="backmeifx"><i class="icon chevron right"></i> Go Back</div>  
<div class="viewindi">You are viewing downline tickets of <span style="color:#f00"><?php echo $fname['fname'];?></span></div>
</br></br>
	<?php	
	} 
  //for tickets history load more
  
  else if($_POST['method'] == 'aidmore'){
	$rc = $_POST['rc'];
	$sql = "SELECT * FROM sh_sf_tickets_history WHERE aid = $usid UNION SELECT * FROM sh_sf_slips_history WHERE aid=$usid ORDER BY date DESC LIMIT $rc, 50";
	$prec = "SELECT slip_id FROM sh_sf_tickets_history WHERE aid = $usid UNION SELECT slip_id FROM sh_sf_slips_history WHERE aid=$usid";
   }
	$result = $conn->query($sql);
	$netcc = mysqli_query($conn, $prec);?>
	
	  
</br></br>

<div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper"><?php echo $rc+50;?></span> OF <span class="foncounter"><?php echo $netcc->num_rows;?></span></div>

</br>
     <input type="hidden" class="suser" value="<?php echo $usid;?>">
     <input type="hidden" class="cfvalue" value="50">
	 <div style="overflow-x:auto;">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        
        <th data-sort="string">UserID</th>
        <th data-sort="int">Status</th>
        <th data-sort="int">Stake</th>
		<th data-sort="int">Win</th>
		<th data-sort="int">Date</th>
		<th data-sort="int">Odd</th>
		<th data-sort="int">Bet Event Name</th>
		<th data-sort="int">Cat Name</th>
		<th data-sort="int">Selection</th>
		<th data-sort="int">Type</th>
		<th data-sort="int">Debit</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="50">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		 $data=unserialize($row['bet_info']);?>
		 
	  <?php if($row['bet_info'] == null){?> 
	  <tr class="singbt" id="tr_<?php echo $row['slip_id'];?>">
	  
      <td><?php echo $row['user_id']+19691000;?></td>
      <td><?php echo $row['status'];?></td>
      <td><?php echo $row['stake'];?></td>
      <td><?php echo $row['winnings'];?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>
      <td><?php echo $row['odd'];?></td>
	  <td><?php echo $row['event_name'];?></td>
	  <td><?php echo $row['cat_name'];?></td>
	  <td><?php echo $row['bet_option_name'];?></td>
	  <td><?php echo $row['user_id'];?></td>
	  <td><?php echo $row['type'];?></td>
	  <td><?php echo $row['debit'];?></td>
	  </tr>
	  
	  <?php } else {?>
	  
	  <tr class="multibs" id="tr_<?php echo $row['slip_id'];?>">
      <td><?php echo $row['user_id']+19691000;?></td>
      <td><?php echo $row['status'];?></td>
      <td><?php echo $row['stake'];?></td>
      <td><?php echo $row['winnings'];?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>	  
	  
	  <td>
	  <ol class="ollist">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['odd'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	   <div class="shadowme" id="<?php echo $row['slip_id'];?>">Expand..</div>
	  <ol class="ollist" style="display:none" id="show<?php echo $row['slip_id'];?>">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['event_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	 <div class="shadowmex" id="<?php echo $row['slip_id'];?>">Expand..</div> 
	  <ol class="ollist" style="display:none" id="showx<?php echo $row['slip_id'];?>">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['cat_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	   <div class="shadowmey" id="<?php echo $row['slip_id'];?>">Expand..</div>  
	  <ol class="ollist" style="display:none" id="showy<?php echo $row['slip_id'];?>">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['bet_option_name'].'</li>';
	  };?>
	  </ol>
	  </td>

	  <td><?php echo $row['type'];?></td>
	  <td><?php echo $row['debit'];?></td>
	  
	  </tr>
	  
	  <?php } ?>

	 <?php
	 }
 } else {
	  echo '<div style="padding:10px">No active Tickets Found</div>';
	  die();
  }?> 
  </table>
  </div>
  <div id="tickmore">
  <?php if ($result->num_rows > 1) {
	echo '<div class="loadmothx">Load More..</div>';
  }?>
  </div>
	
	
	
   
   
   
   
   
   
   
   
   
   
   
   