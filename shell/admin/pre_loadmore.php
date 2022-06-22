<?php //<link rel="stylesheet" href="https://sp.sportsfier.com/shell/admin/str.css" type="text/css">;?>
<?php
include_once('../db.php'); ?>

 <?php $prec = "SELECT * FROM sh_sf_slips_history WHERE status = 'awaiting'";
  $netcc = mysqli_query($conn, $prec);
  $counter = $netcc->num_rows;?>
  <div class="showmo">Showing 0 to <input class="hoper" value="10"> OF <?php echo $counter;?></div>
  <table class="yoyo sorting basic table">
	
  <?php 
	$rc = $_POST['rc'];
    $sql = "SELECT * FROM sh_sf_slips_history WHERE status = 'awaiting' ORDER BY date DESC LIMIT $rc, 10";

  $result = mysqli_query($conn, $sql);
  echo '<input type="hidden" class="ticketvalue" value="2">';  
 if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
    <tr id="">
      <td><?php echo $row['slip_id'];?></td>
      <td><?php echo $row['user_id'];?></td>
      <td><?php echo $row['status'];?></td>
      <td><?php echo $row['stake'];?></td>
      <td><?php echo $row['winnings'];?></td>
	  <td><?php $otime = $row['date'];
	 $diff = time() - $otime;
	 $min     = round($diff / 60 ); 
    // Convert time difference in hours 
    $hrs     = round($diff / 3600);   
    // Convert time difference in days 
    $days     = round($diff / 86400 ); 
	 
	 if($min <= 60) {?>
			<span style="color:#f00"><?php echo "$min mins ago";?></span> 
        <?php } 
		else if($hrs <= 24) {?>
			<span style="color:#f00"><?php echo "$hrs hours ago";?></span>
        <?php }else {
		echo date ("m-d H:i", $row['date']);
	};?></td>
      <td><?php echo $row['odd'];?></td>
      <td><?php echo $row['event_name'];?></td>
      <td><?php echo $row['cat_name'];?></td>
      <td><?php echo $row['bet_option_name'];?></td>
	  <td><?php echo $row['type'];?></td>
      <td><?php echo $row['sp'];?></td>
      <td>W/L/C</td>
    </tr>
	 <?php
	 }
 } else {
	  echo '<div style="padding:10px">No active Tickets Found</div>';
  }?> 
  </table>
  <?php if ($result->num_rows > 1) {
	echo '<div class="addload" id="triggerlodo"></div>';
	echo '<div id="'.$counter.'" class="lodo">Load More...</div>';
  }

























?>