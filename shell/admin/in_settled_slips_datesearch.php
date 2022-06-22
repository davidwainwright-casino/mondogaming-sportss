
<?php
include_once('../db.php'); ?>

 <?php 
 $dt1 = $_POST['dt1'];
 $dt2 = $_POST['dt2'];
 $start = strtotime($dt1);
 $end = strtotime($dt2);
  echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
 $sql = "SELECT * FROM sh_sf_tickets_history WHERE date >=".$start." AND date <= ".$end." AND status NOT IN('awaiting', 'trashed') ORDER BY date DESC";?>	 
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
  $result = mysqli_query($conn, $sql);
 if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		if(!empty($row['bet_info'])):
		 $data=unserialize($row['bet_info']);
		 echo '<tr class="multibs" id="tr_'.$row['slip_id'].'">';?>
		 
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
		  echo '<li><a target="_blank" href="http://scorestab.com/results/?event_id='.$childValue['event_id'].'">'.$childValue['event_name'].'</a></li>';
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
      <td>
	  <div class="pmsg-<?= $row['slip_id']; ?>">
	   <a class="winlosem sta-<?= $row['status']; ?>" id="<?= $row['slip_id']; ?>">Revert</a>
	  </div>
	  </td>
  
     </tr>		 	 
    <?php else:?>

	 
    <tr id="tr_<?php echo $row['slip_id'];?>">
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
      <td><a target="_blank" href="http://scorestab.com/results/?event_id=<?php echo $row['event_id'];?>"><?php echo $row['event_name'];?></a></td>
      <td><?php echo $row['cat_name'];?></td>
      <td><?php echo $row['bet_option_name'];?></td>
	  <td><?php $ter = $row['type']; echo '<span class="ter'.$ter.'">'.$ter.'</span>';?></td>
      <td><?php echo $row['sp'];?></td>
      <td class="tdset">
	  <div class="pmsg-<?= $row['bet_option_id']; ?>">
	  <a class="winlosei afc-<?= $row['bet_option_name']; ?>" id="winlose-<?= $row['bet_option_id']; ?>">
	 <span class="revert-<?= $row['bet_option_id']; ?>" id="<?= $row['status']; ?>">Revert</span></a>
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