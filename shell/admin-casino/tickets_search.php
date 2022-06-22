<?php //<link rel="stylesheet" href="https://sp.sportsfier.com/shell/admin/str.css" type="text/css">;?>
<?php
include_once('../db.php'); ?>

 <?php 
 $es = $_POST['es'];
 $idt = $_POST['idt'];
 //for number ic or wildcard search. not required //AND event_name LIKE '%".$es."%'
 
 
 if (is_numeric($es)){
	 $cnt = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM sh_slot_casino_dealers WHERE admin is null AND user_id = $es OR  admin is null AND agent_id=$es"));
  echo '<div class="showtxrr dtf">Total<b> '.$cnt.' </b>records found <span class="rgfound">X</span></div>';
 
 $fsd=mysqli_query($conn, "SELECT * FROM sh_slot_casino_dealers WHERE admin is null AND user_id = $es OR admin is null AND agent_id=$es");
 }else{
 $cnt = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM sh_slot_casino_dealers WHERE admin is null AND transaction_id LIKE '%".$es."%'"));
  echo '<div class="showtxrr dtf">Total<b> '.$cnt.' </b>records found <span class="rgfound">X</span></div>';
 
 $fsd=mysqli_query($conn, "SELECT * FROM sh_slot_casino_dealers WHERE admin is null AND transaction_id LIKE '%".$es."%'");
	 
 }
 ?>
	


  
  
  <div style="overflow-x:auto;">
	<table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">UserID</th>
        <th data-sort="string">AgentID</th>
        <th data-sort="int">gameID</th>
        <th data-sort="int">gameName</th>
        <th data-sort="int">Stake</th>
		<th data-sort="int">userWin</th>
		<th data-sort="int">agentWin</th>
		<th data-sort="int">transactionID</th>
		<th data-sort="int">Date</th>
      </tr>
    </thead>
	<?php
	while($crr=mysqli_fetch_assoc($fsd)){
		?>
		<tr class="gamcasino">
		<td><a target="_blank" href="/admin/users/edit/<?php echo $crr['user_id'];?>/"><?php echo $crr['user_id'];?></a></td>
		<td><?php echo $crr['agent_id'];?></td>
		<td><?php echo $crr['game_id'];?></td>
		<td><?php echo $crr['game_name'];?></td>
		<td><b><?php echo $crr['stake'];?></b></td>
		<td><?php echo $crr['user_win'];?></td>
		<td><?php $awin = $crr['agent_win'];if(!empty($awin)){
			echo $awin;
		}else{
			echo'NA';
		}?></td>
		<td><?php echo $crr['transaction_id'];?></td>
		<td><?php $tm = $crr['updated_at'];echo date ("m-d-y H:i", $tm);?></td>
		
		</tr>
		
	<?php
	}
	?>
	</table>
</div>
