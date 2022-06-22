<?php include('../db.php'); error_reporting(0);
if(isset($_POST['method']) && $_POST['method'] == 'fsidebar'){?>

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

