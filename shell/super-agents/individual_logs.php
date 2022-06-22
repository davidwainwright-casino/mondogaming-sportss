<?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];

 
  //view individual logs
  if($_POST['method'] == 'indlogs'){
	$usrd = $_POST['usid'];
	$sql = "SELECT * FROM sh_sf_points_log WHERE user_id = $usrd LIMIT 50";
	$prec = "SELECT id FROM sh_sf_points_log WHERE user_id = $usrd";
	$netcc = mysqli_query($conn, $prec);
	$kk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname,lname FROM users WHERE id=".$usrd.""));
	$fulname = $kk['fname'].' '.$kk['lname'];
	echo '<div class="cckmorelog" id="'.$usrd.'"></div>';
	?>
	<div class="backmeiftr"><i class="icon chevron right"></i> Go Back</div>
	<p>You are viewing individual transaction records of <?php echo $fulname;?></p>
	<div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper"><?php echo $rc+50;?></span> OF <span class="foncounter"><?php echo $netcc->num_rows;?></span></div>
	
  <?php }
  
  else if($_POST['method'] == 'logsindmore'){
	 $usidm = $_POST['usidm']; 
	$rc = $_POST['rc'];
	$sql = "SELECT * FROM sh_sf_points_log WHERE user_id=$usidm ORDER BY date DESC LIMIT $rc, 50";
	$prec = "SELECT id FROM sh_sf_points_log WHERE user_id=$usidm";
	$netcc = mysqli_query($conn, $prec);
	echo '<div class="cckmorelog" id="'.$usidm.'"></div>';
	
	?>
	<div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper"><?php echo $rc+50;?></span> OF <span class="foncounter"><?php echo $netcc->num_rows;?></span></div>
	
  <?php }
  
 
	$result = $conn->query($sql);?>
	
 
     <input type="hidden" class="cfvalue" value="50">
	 <div style="overflow-x:auto;" id="seetick">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        
        <th data-sort="string">User ID</th>
        <th data-sort="int">Comment</th>
        <th data-sort="int">amount</th>
		<th data-sort="int">date</th>
		<th data-sort="int">type</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="50">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	 <tr>
	 <td><a class="tkaglistlog" id="<?php echo $row['user_id'];?>"><i class="icon eye"></i> <?php echo $row['user_id'];?></a></td>
	 <td><?php echo $row['comment_id'];?></td>
	 <td><b style="color:#f00"><?php echo $row['amount'];?></b></td>
	 <td><?php echo date ("m-d-y H:i", $row['date']);?></td> 
	 <td><?php echo $row['type'];?></td>
	 </tr>
	 <?php }
 } else {
	  echo '<div style="padding:10px">No active records found</div>';
	  die();
  }?> 
  </table>
  </div>
  <?php if ($result->num_rows > 1) {
	echo '<div class="loadmothtrind" id="'.$usid.'">Load More..</div>';
  }
	