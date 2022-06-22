<?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];
 //tickets history
if(isset($_POST['method']) && $_POST['method'] == 'txnlogs') {
	$que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}
	  $aids = implode (", ", $agent_ids);
	$sql = "SELECT * FROM sh_sf_points_log WHERE user_id IN($aids) ORDER BY date DESC LIMIT 50";
	$prec = "SELECT id FROM sh_sf_points_log WHERE user_id IN($aids)";
	$netcc = mysqli_query($conn, $prec);
    ?>
<h2>Transactions Log(<?php echo mysqli_query($conn, $prec)->num_rows;?>)</h2>

<div class="agdwn">All downline transactions records</div>
<div class="noteus"><b>Info :</b> Transactions log doesn't contain betting credit/debit. It includes transfers, deposits, withdrawals etc.</div>
</br></br>
<div class="topwwp">
<div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper"><?php echo $rc+50;?></span> OF <span class="foncounter"><?php echo $netcc->num_rows;?></span></div>

<div class="srid"><span class="searcktr"></span> <input type="text" placeholder="Search by User ID.." name="search" id="searchmetr"> <i class="find icon"></i></div>
</div>

</br>


	<?php	
	} 
  //for tickets history load more
  
  else if($_POST['method'] == 'logsmore'){
	$que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}
	$aids = implode (", ", $agent_ids);  
	$rc = $_POST['rc'];
	$sql = "SELECT * FROM sh_sf_points_log WHERE user_id IN($aids) ORDER BY date DESC LIMIT $rc, 50";
	$prec = "SELECT id FROM sh_sf_points_log WHERE user_id IN($aids)";
	$netcc = mysqli_query($conn, $prec);?>
	<div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper"><?php echo $rc+50;?></span> OF <span class="foncounter"><?php echo $netcc->num_rows;?></span></div>
	
  <?php }
  
  //search query
   else if($_POST['method'] == 'searchlogs'){
	$es = $_POST['es'];
 
	$sql = "SELECT * FROM sh_sf_points_log WHERE user_id = $es LIMIT 100";
	$prec = "SELECT id FROM sh_sf_points_log WHERE user_id = $es";
	$netcc = mysqli_query($conn, $prec);?>
	
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
	echo '<div class="loadmothtr">Load More..</div>';
  }
	
	
	
   
   
   
   
   
   
   
   
   
   
   
   