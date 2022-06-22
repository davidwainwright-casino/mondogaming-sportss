<?php error_reporting(0);include('../../db.php');

  //for deposits
  $rc = $_POST['rc'];
  $usid = $_POST['usid'];
  if($_POST['method'] == 'creditrec'){
	$sql = "SELECT * FROM sh_sf_deposits ORDER BY date DESC, status LIMIT 100";
	$prec = "SELECT id FROM sh_sf_deposits";
  } else if($_POST['method'] == 'usersmore'){
	$sql = "SELECT * FROM sh_sf_deposits ORDER BY date DESC, status LIMIT $rc, 100";
	$prec = "SELECT id FROM sh_sf_deposits";
   }
   
   //date query all
   else if($_POST['method'] == 'alldates'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_deposits WHERE date >= $start AND date <= $end ORDER BY date DESC, status LIMIT 500";
  $prec = "SELECT id FROM sh_sf_deposits WHERE date >= $start AND date <= $end";  
   }
   
   
   //search query
   else if($_POST['method'] == 'searchlist'){
	$es = $_POST['es'];
    $idt = $_POST['idt'];
	 $sql = "SELECT * FROM sh_sf_deposits WHERE user_id = '$es' OR transaction_id = '$es' OR type = '$es' OR status = '$es'";
	 $prec = "SELECT id FROM sh_sf_deposits WHERE user_id = '$es' OR transaction_id = '$es' OR type = '$es' OR status = '$es'";
  }
  
  //for users first load fetch
 else if($_POST['method'] == 'xpcreditrec'){
	$sql = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid ORDER BY date DESC, status LIMIT 100";
	$prec = "SELECT id FROM sh_sf_deposits WHERE user_id=$usid";
  } else if($_POST['method'] == 'xpusersmore'){
	$sql = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid ORDER BY date DESC, status LIMIT $rc, 100";
	$prec = "SELECT id FROM sh_sf_deposits WHERE user_id=$usid";
   }
  
 //users date  
 else if($_POST['method'] == 'xpalldates'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid AND date >= $start AND date <= $end ORDER BY date DESC, status LIMIT 500";
  $prec = "SELECT id FROM sh_sf_deposits WHERE user_id=$usid AND date >= $start AND date <= $end";  
   }
   
 
   
   
  //delete rows
  else if($_POST['method'] == 'binme'){
	$post_ids = $_POST['post_id'];
	foreach($post_ids as $id){ 
     $query = "DELETE FROM sh_sf_deposits WHERE id = $id"; 
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
        <th data-sort="string"><input type="checkbox" id="checkAll">ID</th>
        <th data-sort="string">User_ID</th>
        <th data-sort="int">TXT ID</th>
        <th data-sort="int">Amount</th>
		<th data-sort="int">Date</th>
		<th data-sort="int">Type</th>
		<th data-sort="int">Status</th>
		<th data-sort="int">Actions</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="100">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	  <tr class="singbt" id="tr_<?php echo $row['id'];?>">
	  
	  <td>
	 <input type='checkbox' name="slipname" class="delop" id='del_<?php echo $row['id'];?>'> <?php echo $row['id'];?>      </td>
	  
      <td>
	  <a href="/admin/deposits/?usid=<?php echo $row['user_id'];?>"><i class="icon eye blocked"></i>          <?php echo $row['user_id'];?></a>
	  </td>
      <td><?php echo $row['transaction_id'];?></td>
      <td><?php echo round($row['amount'],2);?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>
      <td><?php echo $row['type'];?></td>
	  <td><a class="fb<?php echo $row['status'];?>"><?php echo $row['status'];?></a></td>
	  <td>
	  <?php $sts = $row['status'];
	  if($sts == 'Pending'):?>
		  
		  <a class="processtx" id="<?php echo $row['id'];?>"><i class="icon spin full"></i> Process</a> | <a class="canceltx" id="<?php echo $row['id'];?>"><i class="icon ban"></i> Cancel</a>
		  
	  <?php elseif($sts == 'Processing'):?>
		  <a class="markrec usid-<?php echo $row['user_id'];?>" id="<?php echo $row['id'];?>"><i class="icon check all"></i> Mark as Received</a> <i id="<?php echo $row['id'];?>" class="icon info sign"></i>
		  <div id="po<?php echo $row['id'];?>" class="amountcrt">Amount <?php echo round($row['amount'],2);?> will be credited to the user's account and marked it as Received.</div>
	  <?php else:?>
		 <?php echo $sts;?>
	  <?php endif;?>
	  
	   </td>
	  </tr>
	 
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