<?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];
$uname = $_POST['uname'];
if(isset($_POST['method']) && $_POST['method'] == 'salist') { 
    $prec = "SELECT id,fname,created,lastlogin,country,active,chips FROM users WHERE afid = $usid LIMIT 50";	 
    $result = mysqli_query($conn, $prec);
	$gfname = mysqli_query($conn, "SELECT fname FROM users WHERE id=$usid");
	$fname = $gfname->fetch_assoc();
	
	?>
  
<div class="backmeif"><i class="icon chevron right"></i> Go Back</div>  
<div class="viewindi">You are viewing downline of <span style="color:#f00"><?php echo $fname['fname'];?></span></div>
</br></br>
 
 <input type="hidden" class="suser" value="<?php echo $usid;?>">
 <input type="hidden" class="cfvalue" value="50">
 <div style="overflow-x:auto;">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><input type="checkbox" id="checkAll">ID</th>
        <th data-sort="string">First Name</th>
        <th data-sort="int">Created</th>
        <th data-sort="int">Last Login</th>
		<th data-sort="int">Country</th>
		<th data-sort="int">Active</th>
		<th data-sort="int">Cash</th>
      </tr>
    </thead>

 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	 
	 <tr class="singbt" id="tr_<?php echo $row['id'];?>">
      <td><?php echo $row['id'];?></td>
      <td><?php echo $row['fname'];?></td>
      <td><?php $date = $row['created'];$dt = new DateTime($date);echo $dt->format('Y-m-d');?></td>
      <td><?php $ll = $row['lastlogin'];$ds = new DateTime($ll);if(empty($ll)){echo 'Never';}else{echo $ds->format('Y-m-d');}?></td>
	  <td><?php $cc = $row['country'];if(empty($cc)){echo 'Unknown';}else{echo $cc;}?></td>
      <td>
	  <?php $ck = $row['active'];if($ck == 'y'){ echo 'Yes';} else { echo 'No';};?></td>
	  <td style="color:#f00;font-weight:bold;"><?php echo $row['chips'];?></td>
	  </tr>
		 
	 <?php 
	 }
	 
 } else {
	  echo '<div style="padding:10px">You don\'t have any registered agent yet.</div>';
	  die();
  }?> 
  </table>
  </div>
<div class="loadmoul">Load More..</div>
 
 <?php }
//agents list
 else if(isset($_POST['method']) && $_POST['method'] == 'salistmore') {
  $rc = $_POST['rc'];
  $prec = "SELECT id,fname,created,lastlogin,country,active,chips FROM users WHERE afid = $usid LIMIT $rc, 50";	 
  $result = mysqli_query($conn, $prec);?>
 <input type="hidden" class="suser" value="<?php echo $usid;?>">
 <input type="hidden" class="cfvalue" value="50">
 <div style="overflow-x:auto;">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><input type="checkbox" id="checkAll">ID</th>
        <th data-sort="string">First Name</th>
        <th data-sort="int">Created</th>
        <th data-sort="int">Last Login</th>
		<th data-sort="int">Country</th>
		<th data-sort="int">Active</th>
		<th data-sort="int">Cash</th>
      </tr>
    </thead>

 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	 
	 <tr class="singbt" id="tr_<?php echo $row['id'];?>">
      <td><?php echo $row['id'];?></td>
      <td><?php echo $row['fname'];?></td>
      <td><?php $date = $row['created'];$dt = new DateTime($date);echo $dt->format('Y-m-d');?></td>
      <td><?php $ll = $row['lastlogin'];$ds = new DateTime($ll);if(empty($ll)){echo 'Never';}else{echo $ds->format('Y-m-d');}?></td>
	  <td><?php $cc = $row['country'];if(empty($cc)){echo 'Unknown';}else{echo $cc;}?></td>
      <td>
	  <?php $ck = $row['active'];if($ck == 'y'){ echo 'Yes';} else { echo 'No';};?></td>
	  <td style="color:#f00;font-weight:bold;"><?php echo $row['chips'];?></td>
	  </tr>
		 
	 <?php 
	 }
	 
 } else {
	  echo '<div style="padding:10px">No more results found.</div>';
	  die();
  }?> 
  </table>
  </div>
<div class="loadmoul">Load More..</div>
 
 <?php } ?>