<?php //<link rel="stylesheet" href="https://sp.sportsfier.com/shell/admin/str.css" type="text/css">;?>
<?php
include_once('../db.php'); ?>

 <?php 
 $dt1 = $_POST['dt1'];
 $dt2 = $_POST['dt2'];
 $start = strtotime($dt1);
 $end = strtotime($dt2);
$cnt = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM sh_slot_casino_dealers WHERE updated_at >=".$start." AND updated_at <= ".$end." AND admin is null"));
  echo '<div class="showtxrr dtf">Date Filter '.$dt1.' TO '.$dt2.'. Total<b> '.$cnt.' </b>records found <span class="rgfound">X</span></div>';
 $fsd=mysqli_query($conn, "SELECT * FROM sh_slot_casino_dealers WHERE updated_at >=".$start." AND updated_at <= ".$end." AND admin is null ORDER BY updated_at DESC LIMIT 1000");
 //$sql = "SELECT * FROM sh_sf_slips_history WHERE event_name LIKE '%".$es."%'";
 ;?>
	


  
  
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
