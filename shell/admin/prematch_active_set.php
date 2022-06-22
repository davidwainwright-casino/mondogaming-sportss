<?php //<link rel="stylesheet" href="https://sp.sportsfier.com/shell/admin/str.css" type="text/css">;?>
<?php
include_once('../db.php'); ?>

 <?php 
 $rc = $_POST['rc'];
 $prec = "SELECT DISTINCT bet_option_id FROM sh_sf_slips_history WHERE status = 'awaiting'";
  $netcc = mysqli_query($conn, $prec);
  $counter = $netcc->num_rows;?>
  <div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <input class="hoper" value="100"> OF <?php echo $counter;?></div>
  <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">Slip ID</th>
        <th data-sort="string">UID</th>
        <th data-sort="int">Status</th>
        <th data-sort="int">Stake</th>
        <th data-sort="int">Win</th>
		<th data-sort="int">Date</th>
		<th data-sort="int">Odd</th>
		<th data-sort="int">Event Name</th>
		<th data-sort="int">Cat Name</th>
		<th data-sort="int">Option Name</th>
		<th data-sort="int">Type</th>
		<th data-sort="int">SID</th>
		<th data-sort="int">Actions</th>
      </tr>
    </thead> 
	
  <?php 
  if($_POST['method'] == 'prematch_active'){
    $sql = "SELECT MIN(bet_option_id), slip_id, user_id,status, stake, winnings,date, bet_info, odd, event_id, event_name,cat_id,cat_name,bet_option_id, bet_option_name, aid, type,sp,debit FROM sh_sf_slips_history WHERE status = 'awaiting' GROUP BY bet_option_id ORDER BY date DESC LIMIT 200";
  } else if($_POST['method'] == 'prelodo'){
    $sql = "SELECT MIN(bet_option_id), slip_id, user_id,status, stake, winnings,date, bet_info,odd, event_id, event_name,cat_id,cat_name,bet_option_id, bet_option_name, aid, type,sp,debit FROM sh_sf_slips_history WHERE status = 'awaiting' GROUP BY bet_option_id ORDER BY date DESC LIMIT $rc, 200";
	};

  $result = mysqli_query($conn, $sql);
  echo '<input type="hidden" class="ticketvalue" value="100">';  
 if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		if(!empty($row['bet_info'])):
		 $data=unserialize($row['bet_info']);
		 echo '<tr class="multibs">';?>
		 
	 <td>Multi</br><?php echo $row['slip_id'];?></td>
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

	
      <td>
	  <ol class="ollist">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['odd'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	  <ol class="ollist">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['event_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	  <ol class="ollist">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['cat_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	  <ol class="ollist">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['bet_option_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
	  <td><?php $ter = $row['type']; echo '<span class="ter'.$ter.'">'.$ter.'</span>';?></td>
      <td><?php echo $row['sp'];?></td>
      <td class="tdset">
	  <div class="pmsg-<?= $row['slip_id']; ?>">
	   <a class="mwin pr-<?= $row['slip_id']; ?>" id="pwin-<?= $row['bet_option_id']; ?>">W</a>
	   <a class="mlose pr-<?= $row['slip_id']; ?>" id="plose-<?= $row['bet_option_id']; ?>">L</a>
	   <a class="mcan pr-<?= $row['slip_id']; ?>" id="pcan-<?= $row['bet_option_id']; ?>">C</a>
	  </div>
	  </td>
  
     </tr>		 	 
    <?php else:?>

	 
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
	  <td><?php $ter = $row['type']; echo '<span class="ter'.$ter.'">'.$ter.'</span>';?></td>
      <td><?php echo $row['sp'];?></td>
      <td class="tdset">
	  <div class="pmsg-<?= $row['bet_option_id']; ?>">
	  <a class="pwin pr-<?= $row['bet_option_name']; ?>" id="pwin-<?= $row['bet_option_id']; ?>">W</a>
	  <a class="plose pr-<?= $row['bet_option_name']; ?>" id="plose-<?= $row['bet_option_id']; ?>">L</a>
	  <a class="pcan pr-<?= $row['bet_option_name']; ?>" id="pcan-<?= $row['bet_option_id']; ?>">C</a>
	  </div>
	  </td>
    </tr>
	<?php endif;?>
	 <?php
	 }
 } else {
	  echo '<div style="padding:10px">No active Tickets Found</div>';
	  die();
  }?> 
  </table>
  <?php if ($result->num_rows > 1) {
	echo '<div class="addload" id="triggerlodo"></div>';
	echo '<div id="'.$counter.'" class="lodo">Load More...</div>';
  }

























?>