<?php include('../db.php'); error_reporting(0);
if(isset($_POST['method']) && $_POST['method'] == 'fsidebar'){
$sql_ev = "SELECT bet_event_id, bet_event_name, cc, sname FROM af_inplay_bet_events ORDER BY spid ASC LIMIT 25";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="ttourliv"><span class="numrow">'.mysqli_num_rows($onev).'</span> In-Play Live Now <i class="icon star" style="float:right;padding-right:8px"></i></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="clige">
	<a class="onevent" id="<?php echo $efetch['bet_event_id'];?>">
	<li class="livevent-name">
	<span class="sp_sprit <?php echo $efetch['sname'];?>" id="bgsepia">!</span> <?php $trc = $efetch['bet_event_name']; echo substr($trc,0, 30).'..';?> 
<span class="scoreright">	
<?php $trc = $efetch['ss'];if(!empty($trc)): echo $trc; else: echo '0:0'; endif;?>
</span>
	</li>
	</a>
	</ul>
<?php }?>

    <div id="play_games">
     <div class="pgheader">Play Games & Slot</div>
      <div class="playcontent">
	   <a href="/slot/">
	  <img src="<?php echo $_POST['site'];?>/uploads/sidebar/play_games_r_sidebar.jpg">
	  </a>
	  </div>
   </div>
   
   <div id="play_games">
      <div class="playcontent">
	   <a href="/casino/">
	  <img src="<?php echo $_POST['site'];?>/uploads/sidebar/casino.jpg">
	  </a>
	  </div>
   </div>
	
	
<?php }

?>

