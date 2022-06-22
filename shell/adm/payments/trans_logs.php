<?php error_reporting(0);include('../../db.php');

  //for transactions logs
  $rc = $_POST['rc'];
  $usid = $_POST['usid'];
  if($_POST['method'] == 'creditrec'){
	$sql = "SELECT * FROM sh_sf_points_log ORDER BY date DESC LIMIT 100";
	$prec = "SELECT id FROM sh_sf_points_log";
  } 
   else if($_POST['method'] == 'usersmore'){
	$sql = "SELECT * FROM sh_sf_points_log ORDER BY date DESC LIMIT $rc, 100";
	$prec = "SELECT id FROM sh_sf_points_log";
   }
   
   //date query all
   else if($_POST['method'] == 'alldates'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_points_log WHERE date >= $start AND date <= $end ORDER BY date DESC LIMIT 100";
  $prec = "SELECT id FROM sh_sf_points_log WHERE date >= $start AND date <= $end";  
   }
   
   else if($_POST['method'] == 'alldatesmore'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_points_log WHERE date >= $start AND date <= $end ORDER BY date DESC LIMIT $rc, 100";
  $prec = "SELECT id FROM sh_sf_points_log WHERE date >= $start AND date <= $end";  
   }
   
   //search query
   else if($_POST['method'] == 'searchlist'){
	$es = $_POST['es'];
	 $sql = "SELECT * FROM sh_sf_points_log WHERE user_id = '$es' OR comment_id = '$es' OR type = '$es' OR aff_id = '$es' LIMIT 500";
	 $prec = "SELECT id FROM sh_sf_points_log WHERE user_id = '$es' OR comment_id = '$es' OR type = '$es' OR aff_id = '$es' LIMIT";
  }
  
  //for users first load fetch
 else if($_POST['method'] == 'xpcreditrec'){
	$sql = "SELECT * FROM  sh_sf_points_log WHERE user_id=$usid ORDER BY date DESC LIMIT 100";
	$prec = "SELECT id FROM sh_sf_points_log WHERE user_id=$usid";
	$res = mysqli_query($conn, "SELECT fname,lname FROM users WHERE id = $usid"); $cr = $res->fetch_assoc();
	echo '<a id="ksow">Showing Transactions logs of '.$cr['fname'].' '.$cr['lname'].'</a>';
  } else if($_POST['method'] == 'xpusersmore'){
	$sql = "SELECT * FROM sh_sf_points_log WHERE user_id=$usid ORDER BY date DESC LIMIT $rc, 100";
	$prec = "SELECT id FROM  sh_sf_points_log WHERE user_id=$usid";
 }
  
  
  
  
  
 //users date  
 else if($_POST['method'] == 'xpalldates'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
  $res = mysqli_query($conn, "SELECT fname,lname FROM users WHERE id = $usid"); $cr = $res->fetch_assoc();
	echo '<a id="ksow">Showing Transactions logs of '.$cr['fname'].' '.$cr['lname'].'</a>';
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM sh_sf_points_log WHERE user_id=$usid AND date >= $start AND date <= $end ORDER BY date DESC LIMIT 500";
  $prec = "SELECT id FROM sh_sf_points_log WHERE user_id=$usid AND date >= $start AND date <= $end";  
   }
   
 
   
   
  //delete rows
  else if($_POST['method'] == 'binme'){
	$post_ids = $_POST['post_id'];
	foreach($post_ids as $id){ 
     $query = "DELETE FROM sh_sf_points_log WHERE id = $id"; 
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
        <th data-sort="int" style="min-width:140px">Type</th>
        <th data-sort="int">AID</th>
		<th data-sort="int">Amount</th>
		<th data-sort="int" style="min-width:90px">Date</th>
		<th data-sort="int">Cat</th>
		<th data-sort="int">Amount Before</th>
		<th data-sort="int">Amount After</th>
		<th data-sort="int">Txn ID</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="100">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	  <tr class="singbt" id="tr_<?php echo $row['id'];?>">
	  
	  <td>
	 <input type='checkbox' name="slipname" class="delop" id='del_<?php echo $row['id'];?>'> <?php echo $row['id'];?>      </td>
	  
      <td>
	  <a href="/admin/trans-log/?usid=<?php echo $row['user_id'];?>"><i class="icon eye blocked"></i>          <?php echo $row['user_id'];?></a>
	  </td>
      <td><?php echo $row['comment_id'];?></td>
	  <td><?php echo $row['aff_id'];?></td>
      <td><?php echo round($row['amount'],2);?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>
	  <td><?php echo $row['type'];?></td>
	  <td><?php echo $row['bf'];?></td>
	  <td><?php echo $row['af'];?></td>
	  <td><?php $ck = $row['txn_id'];if(!empty($ck)){
		  echo $ck;
	  }else{
		  echo 'None';
	  }?></td>
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
	echo '</br><div id="loadid" class="lodo">Load More...</div>';
  }


?>