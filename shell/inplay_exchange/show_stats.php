 <?php error_reporting(0);
	include_once('../db.php');
	$event_id= $_POST['evid'];
	?>
	
<?php $sr=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_events_scores WHERE bet_event_id=$event_id"));
		   $betId = $sr['bet_event_id'];
		   $radar = $sr['our_id'];
		   $spid = $sr['spid'];
		   $en = $sr['e_name'];
		   $league = $sr['league'];
		   $pl = explode('-', $en);
		   $team_home = $pl[0];
		   $team_away = $pl[1];
		   $timer = $sr['timer'];
		   $datts = $sr['score']; $fn = explode('-', $datts); 
		   $home_score = $fn[0];
		   $away_score = $fn[1];
		   $sttus = $sr['status'];
		   //cricket
		   $crs = $sr['score']; $cfn = explode(',', $crs); 
		   $home_cri = $cfn[0];
		   $away_cri = $cfn[1];
		   
		   $on_tar = $sr['on_tar'];
		   $timerx = $sr['timer'];
		   $off_tar = $sr['off_tar'];
		   $poss = $sr['poss'];
		   $overs = $sr['atts'];
		   $bowling = $sr['dan_atts'];
		   $batsman = $sr['cor'];
		   $runs = $sr['pen'];
		   $recentOvers = $sr['on_tar'];
		   
		   
		   
		   
		   if($spid == 1 || $spid == 4){
		   
       $ycard = $sr['yelo']; $yc = explode('-', $ycard);
       $y_home = $yc[0];
       $y_away = $yc[1];
	   
       $rcard = $sr['red']; $rc = explode('-', $rcard);
       $r_home = $rc[0];
       $r_away = $rc[1];
	   
       $ccorners = $sr['cor']; $ccr = explode('-', $ccorners);
       $cc_home = $ccr[0];
       $cc_away = $ccr[1];
	   
       $penalty = $sr['pen']; $pent = explode('-', $penalty);
       $p_home = $pent[0];
       $p_away = $pent[1];
		   }
		   $comm = $sr['comm'];
		   $events = json_decode($comm,true);
		   $homelogo = $sr['img_h_id'];
		   $awaylogo = $sr['img_a_id'];
		   $yes = $sr['yes'];?>
   
   
   
   
   
   <?php if($spid==1):?> 		
<div class="scoreboard">
 <div id="sctopupdates">
 <table border="0" cellspacing="0" cellpadding="0" style="width:100%" class="scotab">
   <tbody><tr class="sheader">
    <th>
     <div class="timers">
	 <?php if($sttus ==2):?>
	 Ended
	 <?php else:?>
	 <?php if($timer < 1){echo '00.00';}else{echo $timer;}?>'
	 <?php endif;?>
	 </div>
    </th>
    <th><div class="fch" title="Corners">Cor</div></th>
    <th><div class="yels" title="Yellow Cards">Y.C</div></th>
    <th><div class="reds" title="Red Cards">R.C</div></th>
    <th class="penaRs"><div class="sp_spritk" title="Penalty">Pen</div></th>
    <th class="goalsSw"><div class="sp_sprits foot y" title="score">SC</div></th>
  </tr>
  <tr class="hometm">
    <td class="txR"><?php echo $team_home;?></td>
    <td><?php echo $cc_home;?></td>
    <td><?php echo $y_home;?></td>
    <td><?php echo $r_home;?></td>
    <td><?php echo $p_home;?></td>
    <td><?php echo $home_score;?></td>
  </tr>
  <tr class="awaytm">
    <td class="txR"><?php echo $team_away;?></td>
    <td><?php echo $cc_away;?></td>
    <td><?php echo $y_away;?></td>
    <td><?php echo $r_away;?></td>
    <td><?php echo $p_away;?></td>
    <td><?php echo $away_score;?></td>
     </tr>                                                
    </tbody>
   </table>
  </div>
 </div>
 <div class="bottomso">
 <div class="commentryof">Commentary <i class="icon chevron down"></i></div>
<div class="seefullof"><a target="_blank" href="https://s5.sir.sportradar.com/bwin/en/match/<?php echo $radar;?>"><i class="icon bar chart"></i> Full Stats</a>

<a target="_blank" id="ltrk" href="https://cs.betradar.com/ls/widgets/?/universalsoftwaresolutions/en/Europe:Berlin/page/lmts#matchId=<?php echo $radar;?>"><i class="icon bolt"></i> Live Tracker</a>
 </div>
</div>
 
 <!-- For cricket -->
<?php elseif($spid==3):?>
<div class="scoreboard">
 <div id="sctopupdates">
 <table border="0" cellspacing="0" cellpadding="0" style="width:100%" class="scotab">
   <tbody><tr class="sheader">
    <th>
     <div class="timers"><?php if($timer < 1){echo 'Live';}else{echo $timer;}?>'</div>
    </th>
    <th class="goalsSw"><div class="sp_sprits foot y" title="score">score</div></th>
  </tr>
  <tr class="hometm">
    <td class="txR"><?php echo $team_home;?></td>
    <td><?php echo $home_cri;?></td>
  </tr>
  <tr class="awaytm">
    <td class="txR"><?php echo $team_away;?></td>
    <td><?php echo $away_cri;?></td>
  </tr>
  
  <tr class="awaytm">
    <td class="txR r"><?php echo $on_tar;?></td>
    <td class="fft">
	<div class='sloader'>
	<i class="icon bar chart"></i> <i class="icon chevron down"></i>
	</div>
	</td>
  </tr>
  
  <tr class="displayer" style="display:none">
    <td class="commtryplay">
    <div class="nedWin"><?php echo $timerx; ?></div>
	<hr>
	<div class="offtr"><?php echo $off_tar; ?></div>
	<div class="fie"><?php echo $poss; ?></div>
	<div class="fiexp">Overs : <b><?php echo $atts; ?></b> || Bowling : <b><?php echo $bowling; ?></b></div>
	<div class="fiexp">Batsman : <b><?php echo $batsman; ?></b> || Runs : <b><?php echo $runs; ?></b></div>
	<div class="fiexp">Recent Overs: <?php echo $on_tar; ?></div>
	</td>	
   </tr>
    	 
    </tbody>
   </table>
  </div>
 </div>
 
<?php else:?>
<div class="scoreboard">
 <div id="sctopupdates">
 <table border="0" cellspacing="0" cellpadding="0" style="width:100%" class="scotab">
   <tbody><tr class="sheader">
    <th>
     <div class="timers"><?php if($timer < 1){echo '00.00';}else{echo $timer;}?>'</div>
    </th>
    <th class="goalsSw"><div class="sp_sprits foot y" title="score">score</div></th>
  </tr>
  <tr class="hometm">
    <td class="txR"><?php echo $team_home;?></td>
    <td><?php echo $home_score;?></td>
  </tr>
  <tr class="awaytm">
    <td class="txR"><?php echo $team_away;?></td>
    <td><?php echo $away_score;?></td>
     </tr>                                                
    </tbody>
   </table>
  </div>
 </div>
<div class="bottomso">
 <div class="commentryof">Commentary <i class="icon chevron down"></i></div>
<div class="seefullof"><a target="_blank" href="https://s5.sir.sportradar.com/bwin/en/match/<?php echo $radar;?>"><i class="icon bar chart"></i> Full Stats</a>

<a target="_blank" id="ltrk" href="https://cs.betradar.com/ls/widgets/?/universalsoftwaresolutions/en/Europe:Berlin/page/lmts#matchId=<?php echo $radar;?>"><i class="icon bolt"></i> Live Tracker</a>
 </div>
</div>	  
<?php endif;?>
		
 
		   
		   
		   <div class="upstats" style="display:none">
		   <a id="toggme">X</a>
				<?php foreach ($events as $key => $event) {?>
                  <?php echo $comm = $event['text'];?></br>
				  <?php } ?>
				</div>