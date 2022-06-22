<?php include_once('../db.php');
$evnid = $_POST['evnid'];
$csm = mysqli_query($conn,"SELECT bet_event_id,bet_event_name,spid FROM af_pre_bet_events WHERE event_id=$evnid ORDER BY deadline ASC");
		while ($dg=mysqli_fetch_assoc($csm)) {?>
		<ul class="csmlig" id="csmligue">
		<a href="/upevents/?pid=<?php echo $dg['bet_event_id'];?>&sp=<?php echo $dg['spid'];?>">
			<li class="liscms" id="<?php echo $dg['bet_event_id'];?>"><?php echo $dg['bet_event_name'];?></li>	
		</a>	
		</ul>
		<?php }
	 