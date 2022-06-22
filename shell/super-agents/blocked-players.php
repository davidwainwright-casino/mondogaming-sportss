  <?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];
if(isset($_POST['method']) && $_POST['method'] == 'blockagentssa') {
	$que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}
	  $aids = implode (", ", $agent_ids);
	  $sql = "SELECT id,fname,lname,created,country, active, afid FROM users WHERE afid IN($aids) AND active <> 'y' OR id IN($aids) AND active <> 'y' ORDER BY created DESC";
	$result = $conn->query($sql);
	
	?>
	<h2>Inactive Agents/Downline(<?php echo $result->num_rows;?>)</h2>

<div class="agdwn">All InActive agents & Downline</div>
<div class="noteus"><b>Info :</b> These are the list of your agents and their downline who are inactive. Inactive are those who are either suspeded/banned or didn't verify account after creation</div>
</br></br>
  <div style="overflow-x:auto;">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        
       <th><input type="checkbox" id="checkAll"> No.</th>
	   <th>AgentID</th>
	   <th>UserID</th>
	   <th>Country</th>
	   <th>Last Name</th>
	   <th>Joined</th>
	   <th>Status</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="50">
 <?php if ($result->num_rows > 0) {
	 $i=1;
	 while($row = $result->fetch_assoc()) {
		 ?>
  <tr>
   <td><input type="checkbox" id="incheck"> <?php echo $i++;?></td>
   <td><?php $ug = $row['afid']; if(empty($ug)){echo '<a>'.$row['id'].'</a>';}else{echo $row['afid'];};?></td>
   <td><?php $og = $row['afid']; if(empty($og)){echo '<a>Agent</a>';}else{echo $row['id']+19691000;};?></td>
   <td><?php $cc = $row['country']; if(!empty($cc)){ echo $cc;}else{ echo 'Pending';};?></td>
   <td><?php echo $row['lname'];?></td>
   <td> 
  <?php $crt = $row['created'];
  $current = strtotime(date("Y-m-d"));
  $date    = strtotime($crt);
  $datediff = $date - $current;
  $difference = floor($datediff/(60*60*24));
 if($difference==0){
    echo 'Today';
 }else{
	 echo $crt;
 }?>
  </td>
  <td> 
  <?php $stu = $row['active']; if($stu == 'n'){ echo 'Inactive'; } else if($stu == 't'){ echo 'Pending';} elseif($stu == 'b'){ echo 'Banned';}?>
  
  </td>
 </tr>
		 
		 
		 
		 
	 <?php }
 } else {
	  echo '<div style="padding:10px">No Records Found..</div>';
	  die();
  }?> 
  </table>
  </div>
  
<?php } ?>