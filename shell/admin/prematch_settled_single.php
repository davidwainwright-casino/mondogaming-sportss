
<?php
include_once('../db.php'); ?>

 <?php 
 $ttype = $_POST['ttype'];
 $rc = $_POST['rc'];
 $lomo = $_POST['lomo'];
 $cats=array("winning","losing", "canceled");
 $nolist = join("','",$cats);
 if($ttype == 'allevents' || $lomo == 'allevents'){
 $prec = "SELECT slip_id FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed')";
 }else{
 $prec = "SELECT slip_id FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed') AND type = '$ttype' OR status NOT IN('awaiting', 'trashed') AND type = '$lomo'";	 
 }
  $netcc = mysqli_query($conn, $prec);
  $counter = $netcc->num_rows;
  $cctr = "SELECT slip_id FROM sh_sf_slips_history WHERE status = 'trashed'";
  $coc = mysqli_query($conn, $cctr);
  $trashc = $coc->num_rows;
  ?>
  <div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <input class="hoper" value="100"> OF <?php echo $counter;?> <div class="binmecov"> <span class="binme" title="Move selected slips to Trash"><i class="icon trash alt"> Selected</i></span>   <span class="trashal tdrk" id="rall title="Trash all available settled slips"><i class="icon trash"> All(<?php echo $counter;?>)</i> </span> <span class="emptyal tdrk" id="rbin" title="Permanently delete slips in Bin"><i class="icon delete"> Empty Bin(<?php echo $trashc;?>)</i></span><span class="restoreal tdrk" id="rrestore" title="Restore all available slips in Bin"><i class="icon redo"> Restore Bin(<?php echo $trashc;?>)</i></span> </div></div>
  <table class="yoyo sorting basic table" id="recordsTable">
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
    $sql = "SELECT * FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed') ORDER BY date DESC LIMIT 100";
  } else if($_POST['method'] == 'prelodo'){
    $sql = "SELECT * FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed') ORDER BY date DESC LIMIT $rc, 100";
	}
 }else {
   if($_POST['method'] == 'typesingle'){
    $sql = "SELECT * FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed') AND type = '$ttype' ORDER BY date DESC LIMIT 100";
  } else if($_POST['method'] == 'prelodo'){
    $sql = "SELECT * FROM sh_sf_slips_history WHERE status NOT IN('awaiting', 'trashed') AND type = '$lomo' ORDER BY date DESC LIMIT $rc, 100";
	}	 
 }

  $result = mysqli_query($conn, $sql);
  echo '<input type="hidden" class="ticketvalue" value="100">';
  echo '<div class="addload" id="'.$ttype.'"></div>';  
 if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		if(!empty($row['bet_info'])):
		 $data=unserialize($row['bet_info']);
		 echo '<tr class="multibs" id="tr_'.$row['slip_id'].'">';?>
		 
	 <td><input type='checkbox' class="delop" id='del_<?php echo $row['slip_id'];?>'> Multi</br><?php echo $row['slip_id'];?></td>
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
      <td>
	  <div class="pmsg-<?= $row['slip_id']; ?>">
	   <a class="winlosem sta-<?= $row['status']; ?>" id="<?= $row['slip_id']; ?>">Revert</a>
	  </div>
	  </td>
  
     </tr>		 	 
    <?php else:?>

	 
    <tr id="tr_<?php echo $row['slip_id'];?>">
      <td><input type='checkbox' class="delop" id='del_<?php echo $row['slip_id'];?>'> <?php echo $row['slip_id'];?></td>
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
  <?php if ($result->num_rows > 1) {
	echo '<div id="'.$counter.'" class="lodosingle">Load More...</div>';
  }

?>