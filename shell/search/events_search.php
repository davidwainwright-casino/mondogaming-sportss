<?php include_once('../db.php');error_reporting(0);
$keyword = $_POST['es'];
if(isset($_POST['method']) && $_POST['method'] == 'events_search'){
	
	
    $result = $conn->query("(SELECT bet_event_id,bet_event_name,deadline,ss,event_id,event_name,spid,sname,'sname' AS pre FROM af_pre_bet_events WHERE bet_event_name LIKE '%" . $keyword . "%' OR event_name LIKE '%" . $keyword ."%') 
           UNION
           (SELECT bet_event_id,bet_event_name,deadline,ss,event_id,event_name,spid,sname,'sname' AS inp FROM af_inplay_bet_events WHERE bet_event_name LIKE '%" . $keyword . "%' OR event_name LIKE '%" . $keyword ."%')");
	echo '<div class="efetch">';
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if($row['ss'] !=NULL){?>
			<div class="wrrlive">
		<a id="inpsmath" href="/events/?pid=<?php echo $row['bet_event_id'];?>&spid=<?php echo $row['spid'];?>"><span class="sp_sprit <?php echo $row['sname'];?>">!</span> <span class="snammm"><?php echo $row['bet_event_name'];?></span> <span class="evtimes inp"><?php echo $row['ss'];?></span> <span class="lvtext">Live</span></a>
		</div>
			<?php }else{?>
			<div class="wrrpre">
			<a id="presmath" href="/upevents/?pid=<?php echo $row['bet_event_id'];?>&spid=<?php echo $row['spid'];?>"><span class="sp_sprit <?php echo $row['sname'];?>">!</span> <span class="snammm"><?php echo $row['bet_event_name'];?></span> <span class="evtimes pre"><?php $dt=$row['deadline']; echo date ("m-d H:i", $dt); ?></a>
			</div>
			<?php }
		}
	} else {
		echo 'No events found with this keyword';
		
	}
	echo '</div>';
	
	//echo 'events searchxxxxxxxxxxxx';
	
	
	
	
	
	
	
	
	
	
	
}