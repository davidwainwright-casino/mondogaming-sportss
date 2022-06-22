
<?php
include_once('../db.php'); ?>

 <?php 
 $ttype = $_POST['ttype'];
 $rc = $_POST['rc'];
 $lomo = $_POST['lomo'];
 
 if($ttype=='mb'){
	if($ttype == 'mb'){
 $prec = "SELECT slip_id FROM sh_sf_tickets_records WHERE status = 'awaiting' AND bet_info IS NOT null";
 }else{
 $prec = "SELECT slip_id FROM sh_sf_tickets_records WHERE status = 'awaiting' AND type = '$ttype' OR status = 'awaiting' AND type = '$lomo' AND bet_info IS NOT null";	 
 }
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
 if($ttype == 'mb') {
    $sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'awaiting' AND bet_info IS NOT null ORDER BY date DESC LIMIT 100";
  } else{
    $sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'awaiting' AND bet_info IS NOT null ORDER BY date DESC LIMIT $rc, 100";
	}
  $result = mysqli_query($conn, $sql);
  echo '<input type="hidden" class="ticketvalue" value="100">';
  echo '<div class="addload" id="'.$ttype.'"></div>';  
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
	   <a class="mwin pr-<?= $row['slip_id']; ?>" id="pwin-<?= $row['bet_option_id']; ?>">W</a>
	   <a class="mlose pr-<?= $row['slip_id']; ?>" id="plose-<?= $row['bet_option_id']; ?>">L</a>
	   <a class="mcan pr-<?= $row['slip_id']; ?>" id="pcan-<?= $row['bet_option_id']; ?>">C</a>
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
	echo '<div id="'.$counter.'" class="lodosingle">Load More...</div>';
  }
	 
	 
	 
	 
	 
	 
 }else{
 
 if($ttype == 'allevents' || $lomo == 'allevents'){
 $prec = "SELECT slip_id FROM sh_sf_tickets_records WHERE status = 'awaiting'";
 }else{
 $prec = "SELECT slip_id FROM sh_sf_tickets_records WHERE status = 'awaiting' AND type = '$ttype' OR status = 'awaiting' AND type = '$lomo'";	 
 }
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
 if($ttype == 'allevents' || $lomo == 'allevents'){  
  if($_POST['method'] == 'typesingle'){
    $sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'awaiting' ORDER BY date DESC LIMIT 100";
  } else if($_POST['method'] == 'prelodo'){
    $sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'awaiting' ORDER BY date DESC LIMIT $rc, 100";
	}
 }else {
   if($_POST['method'] == 'typesingle'){
    $sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'awaiting' AND type = '$ttype' ORDER BY date DESC LIMIT 100";
  } else if($_POST['method'] == 'prelodo'){
    $sql = "SELECT * FROM sh_sf_tickets_records WHERE status = 'awaiting' AND type = '$lomo' ORDER BY date DESC LIMIT $rc, 100";
	}	 
 }

  $result = mysqli_query($conn, $sql);
  echo '<input type="hidden" class="ticketvalue" value="100">';
  echo '<div class="addload" id="'.$ttype.'"></div>';  
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
      <td><a target="_blank" href="http://scorestab.com/results/?event_id=<?php echo $row['event_id'];?>"><?php echo $row['event_name'];?></a></td>
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
	echo '<div id="'.$counter.'" class="lodosingle">Load More...</div>';
  }


 }
?>