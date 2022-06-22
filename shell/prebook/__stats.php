 <?php error_reporting(0);
	include_once('../db.php');
	
	$sr=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM af_pre_bet_events WHERE bet_event_id=$evid"));
		   $betId = $sr['bet_event_id'];
		   $radar = $sr['bradar'];
		   $spid = $sr['spid'];
		   $en = $sr['bet_event_name'];
		   $league = $sr['event_name'];
		   $pl = explode('-', $en);
		   $team_home = $pl[0];
		   $team_away = $pl[1];
		   $deadline = $sr['deadline'];
		   //GET LOCALTIME COOKIE
		   if(isset($_COOKIE['localtime'])) {
			   $oftime = $_COOKIE['localtime'] * 60;
			   }else{
				   $oftime = 0;
			}
		   $timer = $deadline - $oftime;
		   $baseURL = $_SERVER['SERVER_NAME'];
		   ?>
		   
		      <div class="scoreDash">
    <div class="kso spo<?php echo $spid;?>" id="predashboard">
	 


        <div class="tlogowrap">
            <div class="forhome">
                <img src="<?php echo SITEURL;?>/uploads/jersey/<?php echo $sr['img_h_id'];?>.png" onerror="this.src='<?php echo SITEURL;?>/uploads/bet/blank_home.png'">
            </div>
            <div class="forcenter xp">
                <?php echo $team_home;?>
                <div class="tmtmdiv">Vs</div>
                <?php echo $team_away;?>
            </div>

            <div class="foraway">
                <img src="<?php echo SITEURL;?>/uploads/jersey/<?php echo $sr['img_a_id'];?>.png" onerror="this.src='<?php echo SITEURL;?>/uploads/bet/blank_away.png'">
            </div>
        </div>

<div class="scoreboard xp">
 <div id="sctopupdates" class="ort">
 <div class="deadtime">
<?php $etime=$timer; $tm = date ("y-m-d H:i", $etime);
$htm = date ("m-d", $etime);
$stm = date ("H:i", $etime);
$rem = strtotime($tm) - time();
$ntime = time() + 3600;
if($tm < date ("y-m-d H:i", $ntime)){
echo '<span class="stin">';	
echo 'Starts in </br>';	
$min = floor(($rem % 3600) / 60);
if($min) echo "$min Mins ";
echo '</span>';
}else{
 echo $htm; echo '</br>'; echo $stm;
}?>
</div>
  </div>
 </div>

		
<div class="bottomso">
<div class="commentryof"><?= Lang::$word->EXPERT_ANALYSIS; ?> <i class="icon chevron down"></i></div>

<div class="seefullof"><a id="preco" target="_blank" href="https://s5.sir.sportradar.com/bwin/en/match/<?php echo $radar;?>"><i class="icon bar chart"></i> <?= Lang::$word->HISTORICAL_STATS; ?></a>
</div>
</div>
</div>
</div>