<?php error_reporting(0);include('../../db.php');

  //for prematch active slips
  $rc = $_POST['rc'];
  $usid = $_POST['usid'];
  if($_POST['method'] == 'creditrec'){
	$sql = "SELECT * FROM sh_sf_slips_history ORDER BY date DESC LIMIT 100";
	$prec = "SELECT slip_id FROM sh_sf_slips_history";
  } else if($_POST['method'] == 'usersmore'){
	$sql = "SELECT * FROM sh_sf_slips_history ORDER BY date DESC LIMIT $rc, 100";
	$prec = "SELECT slip_id FROM sh_sf_slips_history";
   }
   
   else if($_POST['method'] == 'singlehist'){
	$sql = "SELECT * FROM sh_sf_slips_history WHERE user_id = $usid ORDER BY date DESC LIMIT 100";
	$result = mysqli_query($conn, "SELECT fname,lname FROM users WHERE id = $usid"); $cr = $result->fetch_assoc();
	$prec = "SELECT slip_id FROM sh_sf_slips_history WHERE user_id = $usid";
	echo '<a id="ksow">Showing records OF '.$cr['fname'].' '.$cr['lname'].'</a>';
  } else if($_POST['method'] == 'singlemore'){
	$sql = "SELECT * FROM sh_sf_slips_history WHERE user_id = $usid ORDER BY date DESC LIMIT $rc, 100";
	$prec = "SELECT slip_id FROM sh_sf_slips_history WHERE user_id = $usid";
   } 
   
   //date query all
   else if($_POST['method'] == 'alldates'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_slips_history WHERE date >= $start AND date <= $end ORDER BY date DESC LIMIT 500";
 $prec = "SELECT slip_id FROM sh_sf_slips_history WHERE date >= $start AND date <= $end";  
   }
   //date query for user specific
    else if($_POST['method'] == 'userdates'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_slips_history WHERE user_id = $usid AND date >= $start AND date <= $end ORDER BY date DESC LIMIT 500";
  $prec = "SELECT slip_id FROM sh_sf_slips_history WHERE user_id = $usid AND date >= $start AND date <= $end";  
   }
   
   //search query
   else if($_POST['method'] == 'searchlist'){
	$es = $_POST['es'];
    $idt = $_POST['idt'];
    if (is_numeric($es)){
	 $sql = "SELECT * FROM sh_sf_slips_history WHERE slip_id = $es OR user_id = $es LIMIT 500";
	 $prec = "SELECT slip_id FROM sh_sf_slips_history WHERE slip_id = $es OR user_id = $es";
      } else {
	$sql = "SELECT * FROM sh_sf_slips_history WHERE bet_info LIKE '%".$es."%' OR event_name LIKE '%".$es."%' OR status = '".$es."' LIMIT 500";
	$prec = "SELECT slip_id FROM sh_sf_slips_history WHERE bet_info LIKE '%".$es."%' OR event_name LIKE '%".$es."%' OR status = '".$es."' ";
	}
  }
  //delete rows
  else if($_POST['method'] == 'binme'){
	$post_ids = $_POST['post_id'];
	foreach($post_ids as $id){ 
     $query = "DELETE FROM sh_sf_slips_history WHERE slip_id = $id"; 
     mysqli_query($conn,$query);
    }  
 } 

   $result = $conn->query($sql);
?>
 
<?php
  $netcc = mysqli_query($conn, $prec);
  $counter = $netcc->num_rows;?>
  <div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper">100</span> OF <span class="foncounter"><?php echo $counter;?></span></div>
 <div style="overflow-x:auto;">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><input type="checkbox" id="checkAll">SlipID</th>
        <th data-sort="string">UID</th>
        <th data-sort="int">Status</th>
        <th data-sort="int">Stake</th>
		<th data-sort="int">Win</th>
		<th data-sort="int">Date</th>
		<th data-sort="int">Odd</th>
		<th data-sort="int">Event Name</th>
		<th data-sort="int">Cat Name</th>
		<th data-sort="int">Option Name</th>
		<th data-sort="int">AID</th>
		<th data-sort="int">Type</th>
		<th data-sort="int">Debit</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="100">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		 $data=unserialize($row['bet_info']);?>
		 
	  <?php if($row['bet_info'] == null){?> 
	  <tr class="singbt" id="tr_<?php echo $row['slip_id'];?>">
	  <td><input type='checkbox' name="slipname" class="delop" id='del_<?php echo $row['slip_id'];?>'> <?php echo $row['slip_id'];?></td>
	  
      <td><a href="/admin/prematch-history/?usid=<?php echo $row['user_id'];?>"><i class="icon eye blocked"></i> <?php echo $row['user_id'];?></a></td>
      <td><?php echo $row['status'];?></td>
      <td><?php echo $row['stake'];?></td>
      <td><?php echo $row['winnings'];?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>
      <td><?php echo $row['odd'];?></td>
	  <td><?php echo $row['event_name'];?></td>
	  <td><?php echo $row['cat_name'];?></td>
	  <td><?php echo $row['bet_option_name'];?></td>
	  <td><?php echo $row['aid'];?></td>
	  <td><?php echo $row['type'];?></td>
	  <td><?php echo $row['debit'];?></td>
	  </tr>
	  
	  <?php } else {?>
	  
	  <tr class="multibs" id="tr_<?php echo $row['slip_id'];?>">
	  
	  
	  <td><input type='checkbox' name="slipname" class="delop" id='del_<?php echo $row['slip_id'];?>'>Multi</br><?php echo $row['slip_id'];?></td>
      <td><a href="/admin/prematch-history/?usid=<?php echo $row['user_id'];?>"><i class="icon eye blocked"></i> <?php echo $row['user_id'];?></a></td>
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

	  <td><?php echo $row['aid'];?></td>
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
  <?php if ($result->num_rows > 1) {
	echo '<div class="addload" id="triggerlodo"></div>';
	echo '</br><div id="'.$counter.'" class="lodo">Load More...</div>';
  }


?>