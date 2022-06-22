<?php error_reporting();
    $ge = "SELECT spid,sname, COUNT(spid) FROM af_inplay_bet_events GROUP BY spid ORDER BY spid ASC";
    $kge = Db::run()->pdoQuery($ge);?>
	 <ul class="prelist" id="lisidebar">
	 <?php foreach ($kge->aResults as $kg) {?>
	  <li id="<?php echo $kg->spid;?>"><span class="sp_sprit <?php echo $kg->sname;?>">!</span> <a class="datalink"><?php echo $kg->sname;?></a> [<?php echo $kg->COUNT(spid);?>]</li>
	 <?php } ?>
	 </ul>