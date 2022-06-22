<?php error_reporting(0);include('../db.php');

  //for prematch active slips
  $rc = $_POST['rc'];
  if($_POST['method'] == 'creditrec'){
	$sql = "SELECT * FROM sh_users_credit_records";
  } else if($_POST['method'] == 'usersmore'){
	$sql = "SELECT * FROM users WHERE type = 'member' ORDER BY chips DESC LIMIT $rc, 50";
	};

   $result = $conn->query($sql);
?>
 
<?php $prec = "SELECT * FROM users WHERE type = 'member'";
  $netcc = mysqli_query($conn, $prec);
  $counter = $netcc->num_rows;?>
  <div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper">50</span> OF <?php echo $counter;?></div>


<input type="hidden" class="ticketvalue" value="50">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		if(!empty($row['bet_info'])):
		 $data=unserialize($row['bet_info']);
		 echo '<tr class="multibs" id="tr_'.$row['slip_id'].'">';?>
		 
	 <td><?php echo $row['slip_id'];?></td>
      <td><?php echo $row['user_id'];?></td>
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