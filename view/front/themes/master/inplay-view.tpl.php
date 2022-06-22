<?php
  /**
   * mypage
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  header("Location: /");
?>
<meta http-equiv="refresh" content="600" >
<?php if($this->data->show_header):?>
<!-- Page Caption & breadcrumbs-->
<div id="pageCaption"<?php echo Content::pageHeading();?>>
  <div class="yoyo-grid">
    <div class="row gutters">
      <div class="columns screen-100 tablet-100 mobile-100 phone-100 phone-content-center">
        <?php if($this->data->{'caption' . Lang::$lang}):?>
        <h1><?php echo $this->data->{'caption' . Lang::$lang};?></h1>
        <?php endif;?>
      </div>
      <?php if($this->core->showcrumbs):?>
      <div class="columns screen-100 tablet-100 mobile-100 phone-100 content-right  phone-content-left align-self-bottom">
        <div class="yoyo small white breadcrumb">
          <?php echo Url::crumbs($this->crumbs ? $this->crumbs : $this->segments, "/", Lang::$word->HOME);?>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php endif;?>

<?php if(App::Auth()->is_User()){
     $usid = App::Auth()->uid;
	 $afid = Auth::$udata->afid; 
  } else {
	 $usid = 999999999; 
  } ?>


<div class="exrow">
  <div class="excol ileft">
    <div class="sportscol">
	<div class="sportsview">Available Live Sports</div>
	 <div class="presport-wrap" id="presidebar">
	 <?php $ge = "SELECT spid,sname, COUNT(spid) as ccu FROM af_inplay_bet_events GROUP BY spid ORDER BY spid ASC";
	  $kge = Db::run()->pdoQuery($ge);?>
	 <ul class="prelist" id="lisidebar">
	 <?php foreach ($kge->aResults as $kg) {?>
	  <li id="<?php echo $kg->spid;?>"><span class="sp_sprit <?php echo $kg->sname;?>">!</span> <a class="datalink"><?php echo $kg->sname;?></a> [<?php echo $kg->ccu;?>]</li>
	 <?php } ?>
	 </ul>
	</div>
   </div>	
  </div>
  
  
 
<div class="excol icenter">

<div class="col_evUpdate">
<?php $ge = "SELECT * FROM abc_event_refresh";
   $sg = Db::run()->pdoQuery($ge);
   $kge = $sg->aResults[0]->timer;
   ///var_dump($sg);
   //$maint =$sg->aResults[0]->maint; 
   $tm = time();
   $net = $tm - 30;
   $mnet = $tm - 60;
	if($kge < $net){
	$tm = time();
	Db::run()->pdoQuery("UPDATE abc_event_refresh SET timer = $tm");
	$sp = SITEURL."/shell/inplay_exchange/update_events";
	$ch =  curl_init(''.$sp.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    $viewR = curl_exec($ch);
	}
  ?>
  </div>
  
 <div class="onceload" id="statload">
 
<?php $event_id=$_GET['VwatchID'];if(!empty($event_id)):?>
<?php $ks = "SELECT * FROM sh_sf_events_scores WHERE bet_event_id=$event_id";
	       $sr = Db::run()->pdoQuery($ks);
		   $betId = $sr->aResults[0]->bet_event_id;
		   $radar = $sr->aResults[0]->our_id;
		   $spid = $sr->aResults[0]->spid;
		   $en = $sr->aResults[0]->e_name;
		   $league = $sr->aResults[0]->league;
		   $pl = explode(' - ', $en);
		   $team_home = $pl[0];
		   $team_away = $pl[1];
		   $timer = $sr->aResults[0]->timer;
		   $datts = $sr->aResults[0]->score; $fn = explode('-', $datts); 
		   $home_score = $fn[0];
		   $away_score = $fn[1];
		   $sttus = $sr->aResults[0]->status;
		   //cricket
		   $crs = $sr->aResults[0]->score; $cfn = explode(',', $crs); 
		   $home_cri = $cfn[0];
		   $away_cri = $cfn[1];
		   
		   $on_tar = $sr->aResults[0]->on_tar;
		   $timerx = $sr->aResults[0]->timer;
		   $off_tar = $sr->aResults[0]->off_tar;
		   $poss = $sr->aResults[0]->poss;
		   $overs = $sr->aResults[0]->atts;
		   $bowling = $sr->aResults[0]->dan_atts;
		   $batsman = $sr->aResults[0]->cor;
		   $runs = $sr->aResults[0]->pen;
		   $recentOvers = $sr->aResults[0]->on_tar;
		   
		   
		   
		   
		   if($spid == 1 || $spid == 4){
		   
       $ycard = $sr->aResults[0]->yelo; $yc = explode('-', $ycard);
       $y_home = $yc[0];
       $y_away = $yc[1];
	   
       $rcard = $sr->aResults[0]->red; $rc = explode('-', $rcard);
       $r_home = $rc[0];
       $r_away = $rc[1];
	   
       $ccorners = $sr->aResults[0]->cor; $ccr = explode('-', $ccorners);
       $cc_home = $ccr[0];
       $cc_away = $ccr[1];
	   
       $penalty = $sr->aResults[0]->pen; $pent = explode('-', $penalty);
       $p_home = $pent[0];
       $p_away = $pent[1];
		   }
		   $comm = $sr->aResults[0]->comm;
		   $events = json_decode($comm,true);
		   $homelogo = $sr->aResults[0]->img_h_id;
		   $awaylogo = $sr->aResults[0]->img_a_id;
		   $yes = $sr->aResults[0]->yes;
		   
		   
		   if(empty($yes)){
			   if(!empty($betId)){
			$img_home = $sr->aResults[0]->img_h_id;
			$img_away = $sr->aResults[0]->img_a_id;
			
			$homeurl=file_get_contents('https://assets.b365api.com/images/team/m/'.$img_home.'.png');
			$h_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_home.'.png';
			$h_upload =file_put_contents($h_put_file, $homeurl);
			
			$awayurl=file_get_contents('https://assets.b365api.com/images/team/m/'.$img_away.'.png');
			$a_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_away.'.png';
			$a_upload =file_put_contents($a_put_file, $awayurl);
			
			//update after insert is done;
			Db::run()->pdoQuery("UPDATE sh_sf_events_scores SET yes = 1 WHERE bet_event_id=$betId");
			}
			   
		   }?>
   
   <div class="scoreDash">
    <div class="kso spo<?php echo $spid;?>" id="scoreInner">
	 <div class="lbanner">
	 <a class="navback" id="nvvbk">
	 <i class="icon angle double left"></i> Back
	 </a>
	 <span class="toptr"><span class="sp_sprit sp<?php echo $spid;?>">!</span></span> <?php echo $league;?>
	 </div>
	 
	 
	   <div class="tlogowrap">
	     <div class="forhome">
		   <img src="<?php echo SITEURL;?>/uploads/jersey/<?php echo $homelogo;?>.png" onerror="this.src='<?php echo SITEURL;?>/uploads/bet/blank_home.png'">
		   </div>
		   
		   <div class="forcenter fles<?php echo $spid;?>">
		   <?php echo $home_score;?> -  <?php echo $away_score;?>
		   </div>
		   
		   <div class="foraway">
		   <img src="<?php echo SITEURL;?>/uploads/jersey/<?php echo $awaylogo;?>.png" onerror="this.src='<?php echo SITEURL;?>/uploads/bet/blank_away.png'">
		   </div>
		</div>
		
		
		
		
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
	<div class="fiexp">Overs : <b><?php echo $overs; ?></b> || Bowling : <b><?php echo $bowling; ?></b></div>
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
			</div>
		</div>


<?php else:?>
<?php $isfeat = "SELECT * FROM af_inplay_bet_events WHERE is_active IS NOT NULL AND feat=1";
	       $isf = Db::run()->pdoQuery($isfeat);
		   if(!empty($isevent)):
		   $isid = $isf->aResults[0]->bet_event_id;
		   $isevent = $isf->aResults[0]->bet_event_name;
		   $isscore = $isf->aResults[0]->ss;
		   $lgnm = $isf->aResults[0]->event_name;
		   $bteam = explode('-', $isevent);
		   $ts_home = $bteam[0];
		   $ts_away = $bteam[1];
		   $bscore = explode(':', $isscore);
		   $ss_home = $bscore[0];
		   $ss_away = $bscore[1];
		   $issname = $isf->aResults[0]->sname;
		   else:
		   $isfeat = 'SELECT * FROM af_inplay_bet_events WHERE is_active IS NOT NULL AND spid IN("3","4") ORDER BY spid ASC LIMIT 1';
	       $isf = Db::run()->pdoQuery($isfeat); 
		   $isid = $isf->aResults[0]->bet_event_id;
		   $isevent = $isf->aResults[0]->bet_event_name;
		   $isscore = $isf->aResults[0]->ss;
		   $lgnm = $isf->aResults[0]->event_name;
		   $bteam = explode('-', $isevent);
		   $ts_home = $bteam[0];
		   $ts_away = $bteam[1];
		   $bscore = explode(':', $isscore);
		   $ss_home = $bscore[0];
		   $ss_away = $bscore[1];
		   $issname = $isf->aResults[0]->sname;
		   ?>
<div class="topmatchbg" class="topmatchbg">
<a class="onevent" id="<?php echo $isid;?>" href="#">
<div class="tophg"><?= Lang::$word->LIVE_HIGHLIGHTS; ?></div> <div class="lignss"><span class="sp_sprit <?php echo $issname;?>">!</span><?php echo $lgnm;?></div>

<div class="tpeventwrap">
<div class="topshss tp"> <span class="lltm"><i id="ixhome" class="icon shirt"></i> <?php echo $ts_home;?></span> <span class="rrtm"><?php echo $ss_home;?></span></div>
<div class="topshss btm"> <span class="lltm"><i id="ixaway" class="icon shirt"></i> <?php echo $ts_away;?></span> <span class="rrtm"><?php echo $ss_away;?></span></div>
</div>

<ul class="showdra">
 <li><span class="colhm hm">Home</span></li>
 <li><span class="colhm dr">Draw</span></li>
 <li><span class="colhm aw">Away</span></li>
</ul>
</a>
</div>

<?php endif;?>
<?php endif;?>
</div>

<div id="ajax-content">
<div id="ajc"></div>
</div>
<div class="box stack-top" style="background: blue;"></div>
  
  <div class="supercover">
  <div class="slipcover">
  <div class="slip_wrapper">
  
    <div class="top-wrapper" style="display:none">
	 <div class="slip_container iv">
	   <input type="text" class="numcc" value="0" disabled><span class="cvib">Click Option to Add..</span><span class="closetop"><i class="icon unfold less"></i> Close</span>
	</div>

   <div class="_slip_wrapper" style="display:none">	
    <div class="topbtn">
    	<div class="unsubtd">Ubsubmitted slips</div>
    	<a href="" id="delete_all_unsubmitted_bets"><i class="icon trash alt"></i><?= Lang::$word->BET_CLEAR_ALL; ?></a>
    </div>
	
	 <div class="_slip_container">
	 	<div id="unsubmitted_slips" style="width: 100px,margin-top:20px">
				
		</div>
		</div>
		
		  <div class="cbtn">
			<div class="number xp">
			  <span class="minus">-</span>
			   <input type="number" id="stake_value" value="0"/>
			  <span class="plus">+</span>
			</div>

		  <div class="vbtn">		
				<span class="ereturn">E. Return</span>
				 <input type="text" class="preview" name="0.00" id="return_value" value="0.00" disabled="">	
                <span class="ereturn">Est. Risk</span>
                 <input type="text" class="preview xp" name="0.001" id="risk_value" value="0.00" disabled="">
		  </div>
		</div>
       <div class="text-center">
		<button class="place_bet_button" id="submit_bet">Submit Bet</button>
       </div>

	</div>
  </div>
  </div>
  <input type="hidden" class="ty" id="bet_type">
 </div>
</div>
</div>
  

<div class="excol iright">
<div class="fetchsidebar"></div>
</div>
</div>





<script>
//return if back suspended return
$('body').on('click', ' span.bback', function () {
	var getval = $(this).html();
	if(getval == '0.00'){
		$("span.cvib").html("Selection Suspended");
		$('.place_bet_button').css('pointer-events','');
		return false;
	}
});
//return if lay suspended return
$('body').on('click', ' span.blay', function () {
	var getval = $(this).html();
	if(getval == '0.00'){
		$("span.cvib").html("Selection Suspended");
		$('.place_bet_button').css('pointer-events','');
		return false;
	}
});
//EVENT REFRESH

  function refreshTimer(){
       $(".col_evUpdate").load(" .col_evUpdate");
  };
  refreshTimer();
  
  //Try to refresh every 1 minute.. for this use cron to reduce https request as it's already done during page load to fetch
  setInterval(function(){
    refreshTimer();
  }, 25000);


setInterval(function(){
    $(".scoreboard").load(" .scoreboard");
  }, 60000);
	   
//display stats on click
$('body').on('click', ' .commentryof', function(){
	$(".upstats").toggle();
});	

//hide stats on click
$('body').on('click', ' a#toggme', function(){
	$(".upstats").toggle();
});	
//show cricket stats
$("body").on("click", ".sloader", function(){
  $("tr.displayer").toggle();
  $(".sloader").toggleClass("colkr");
});	
 //fetch sidebar
if($(window).width() >= 1016) {
       $(".fetchsidebar").empty().append("<div id='loading'></div>");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/sidebar/fetch_sidebar_inplay",
			data: {	
			site:'<?php echo SITEURL;?>',
			method:'fsidebar'
			},
		   success: function(response) {
             $(".fetchsidebar").empty().append(response);
               }
       });
};
//load sports event on click
 $('body').on('click', '.prelist li', function(){
	   var spid = $(this).attr("id");
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");
	   $('a', this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_sports',
			spid:spid
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preREF=' + spid);
				}
				setTimeout(function(){
				$(".onceload").load(" #statload");
				}, 100);
            }
    });
    return false;
    });
	

	//auto load events on visit
	        let searchParams = new URLSearchParams(window.location.search);
			var spid = searchParams.get('preREF');
			var vevent = searchParams.get('VwatchID');
			var vspid = searchParams.get('preREF');
			var vlig = searchParams.get('preLIG');
			var vtd = searchParams.get('preTT');
			var vtw = searchParams.get('preTOM');
			//var default = searchParams.get('default');
			
		if(vevent != null) {
		  $("#ajax-content").empty().append("<div id='loading'></div>");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_view.php",
			data: {
			usid:<?php echo $usid;?>,	
			method2:'do_events',
			evid:vevent
			},
		   success: function(html) {
			$('#ajax-content').removeClass("rload");
            $("#ajax-content").empty().append(html);
              }
		    });
			} else if(vlig != null){
		   $("#ajax-content").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_league',
			ligid:vlig
			},
	     	success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preLIG=' + vlig);
				}
            }
        }); 
				 
	   } else if(vspid != null){
		   $("#ajax-content").empty().append("<div id='loading'></div>");	 
			$.ajax({
			type: "POST",
			cache: false,
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_sports',
			spid:vspid
			},
		  success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preREF=' + vspid);
				}
            } 
			 
	   });
	 } else {
	       $("#ajax-content").empty().append("<div id='loading'></div>");
			$('li.topev').css("color", "red");
	       $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_default',
			usid:<?php echo $usid;?>
			},
		success: function(html) {
			$('li.topev').css("color", "red");
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
            }
		});
   };

	
	
	//load sidebar sports
	/*$("#presidebar").empty().append("<div id='loading'></div>");
	       $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/sports-sidebar",
			data: {
			method:'sports_sidebar'
			},
		success: function(html) {
             $("#presidebar").empty().append(html);
            }
     });
*/
	
	//Load sports for mobile
 $('body').on('click', '.showsp_mob', function(){
	   $('.showsp_mob').css("color","");
	   $('.showsp_mob').css("color","");
	   $(this).css("color", "red");
	   $('.excol.ileft').css("display","block");

    });
	
	$("body *:not(li.msports)").click(function() {
		 $('.excol.ileft').css("display", "");
		 $('.showsp_mob').css("color","");
	});
	
	
	
	
	
	//Load by league
 $('body').on('click', '.event-name', function(){
	   var ligid = $(this).attr("id");
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");	  
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_league',
			ligid:ligid
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preLIG=' + ligid);
				}
            }
    });
    return false;
    });
	
	
	
	//Load by cc
 $('body').on('click', '.prcc', function(){
	   var cc = $(this).attr("id");
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");	  
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_cc',
			cc:cc
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preCC=' + cc);
				}
            }
    });
    return false;
    });
	
	
	
	
	//Load full event
 $('body').on('click', '.onevent', function(){
	   var evid = $(this).attr('id');
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_view",
			data: {
			method2:'do_events',
			usid:<?php echo $usid;?>,
			evid:evid
			},
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			 //$("#ajax-content").scrollTop($("#ajax-content")[100].scrollHeight);
			 $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?VwatchID=' + evid);
				}
				
			//load scores div
				setTimeout(function(){
				$(".onceload").load(" #statload");
				}, 1000);	
            }
    });
    return false;
    });
	
	
	
	//$('a#cor-1366930792').css("opacity", "0.1");
	
	
	//For category laod
 $('body').on('click', ' ul.omarkers li', function(){
	   var catid = $(this).attr('id');
	   $('ul.omarkers li').removeClass("active");
	   var evname = $('.evidf').text();
	   var evidf = $(".evidf").attr("id");
	   var savthis = $(this);
	    $("#fetchcat").empty().append("<div id='loading'></div>");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/categories",
			data: {
			method:'football_cat',
			catid:catid,
			usid:<?php echo $usid;?>,
			evid:evidf,
			evname:evname
			},
		success: function(response) {
             $("div#fetchcat").empty().append(response);
			 $(savthis).addClass('active');
            }
    });
    return false;
    });
	
	
	//For cricket category laod
 $('body').on('click', ' ul.crimarkers li', function(){
	   var catid = $(this).attr('id');
	   $('ul.crimarkers li').removeClass("pxxactive");
	   var evname = $('.evidf').text();
	   var evidf = $(".evidf").attr("id");
	   var savthis = $(this);
	    $("#fetchcat").empty().append("<div id='loading'></div>");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/cricket_categories",
			data: {
			method:'cricket_cat',
			catid:catid,
			usid:<?php echo $usid;?>,
			evid:evidf,
			evname:evname
			},
		success: function(response) {
             $("div#fetchcat").empty().append(response);
			 $(savthis).addClass('pxxactive');
            }
    });
    return false;
    });
	
	
	//Refresh event on view page button click
 $('body').on('click', ' #evrefresh', function(){
	  var evidf = $(".evidf").attr("id");
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_view",
			data: {
			method2:'do_events',
			usid:<?php echo $usid;?>,
			evid:evidf
			},
		success: function(html) {
			$('#ajax-content').removeClass("rload");
            $("#ajax-content").empty().append(html);	
            }
    });
    return false;
    });  
	
	
	
	

	
	
	
	
	
	
	

//back button ajax 
$(window).on("popstate", function (e) {
	let searchParams = new URLSearchParams(window.location.search);
	var spid = searchParams.get('preREF');
	var ligid = searchParams.get('preLIG');
	var cc = searchParams.get('preCC');
	var tt = searchParams.get('preTT');
	var tom = searchParams.get('preTOM');
	var evd = searchParams.get('VwatchID');
      // var spid = $_GET['preREF'];
	  
	   $('li a').css("color","");
	   $('a', this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			data: {
			method:'do_back',
			method2:'do_events',
			spid:spid,
			ligid:ligid,
			cc:cc,
			tt:tt,
			tom:tom,
			evid:evd
			},
		
		
		success: function(html) {
			 $('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			 setTimeout(function(){
				$(".onceload").load(" #statload");
				}, 100);	
			 
            }
    });
    return false;
    });
	
	//back button ajax  text version
$('body').on('click', ' .navback', function(ev){
	ev.preventDefault();
    window.history.back();
    });


//SHOW ON LOAD MORE
$('body').on('click', ' .loadmo', function(){
		var rowCount = $('.cfvalue').val();
           var spid = $(".datalink.xp").attr("id");
		   $('.loadmo').css({'padding' : '0px','border' : 'none'});
		   $('.loadmo').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_default",
			type: "post",
			data: {
				spid:spid,
				rc:rowCount,
				method:"lomore"
			},
			success: function (response) {
				$('.loadmo').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 120;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}
		});
});


//onclick show top leagues
$('body').on('click', ' .shall', function(){
	$('ul.sptoplig').addClass('shall');
	$(this).html('Hide more');
	$(this).addClass('hid');
});

$('body').on('click', ' .shall.hid', function(){
	$('ul.sptoplig').removeClass('shall');
	$(this).html('Show more..');
	$(this).removeClass('hid');
});




//trigger input
function triggerInput(){
	var letterToUse = $("input#stake_value").val();
		var e = $.Event("keyup");
		e.which=e.keyCode=letterToUse.charCodeAt();
		$("input#stake_value").val(letterToUse).trigger(e);
}

//trigger input
function inputZero(){
	var letterToUse = '0';
		var e = $.Event("keyup");
		e.which=e.keyCode=letterToUse.charCodeAt();
		$("input#stake_value").val(letterToUse).trigger(e);
}







//cashout
$('body').on('click', ' span.casout', function(){
	$(this).text('cashing....');
	var slid = $(this).attr('id');
	//alert(slid);
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/inplay_exchange/cashout",
			type: "post",
			data: {
				slid:slid,
				method:"cashout"
			},
			success: function (response) {
				$("#" + slid).html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}
		});
	
});


//plus minus input
        $('.minus').click(function () {
				var $input = $(this).parent().find('input#stake_value');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				$("#stake_value").trigger("keyup");
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input#stake_value');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				$("#stake_value").trigger("keyup");
				return false;
		});

    //color slip
    setTimeout(function() {
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      });

      var numbet = $('.ccounter').length; 
       $('.numcc').val(numbet);
	   if($('input#bet_type').val() === 'lay'){
		   $('._slip_container').css("background", "#ffc8c8");
		   };
		  if(numbet > 1){
		   $('._slip_wrapper').css("display", "none");
		   //$('.top-wrapper').attr("style", "display: block !important");
		   $('.top-wrapper').addClass('shw');
		   $('.closetop').text('Open');
		   $('.slipcover').css("bottom", "54px");
		   }
		  if(numbet == 1){
		   $('.top-wrapper').css("display", "block");
		   $('._slip_wrapper').css("display", "block");
		   }
		   
	}, 1000); // <-- time

   
     

   //close slip on click
   $('body').on('click', ' .closetop', function(){
	$('._slip_wrapper').css("display", "none");
	$('.top-wrapper').addClass('shw');
	$('.closetop').text('Open');
	$('.slipcover').css("bottom", "54px");
	});
	
	//show slip on click
	$('body').on('click', ' .top-wrapper.shw', function(){
	$('._slip_wrapper').css("display", "block");
	$('.top-wrapper').css("display", "block");
	$('.top-wrapper').removeClass('shw');
	$('.closetop').text('Close');
	$('.slipcover').css("bottom", "0px");
	});







  var totalOdss=0;
  var resturn_est=$('#return_value');
  var bet_type_field=document.querySelector('#bet_type');
  var risk_value=$('#risk_value');
  showUnSubmittedSlip();
	// $("body").on('DOMSubtreeModified', ".b_option_odd", function() {
	//     // alert('changed');
	// });
	
	
	
	//FUNCTION FOR CASHOUT main
	function cashmeMain(){
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_view.php",
			data: {
			usid:<?php echo $usid;?>,	
			method2:'do_events',
			evid:vevent
			},
		   success: function(html) {
			$('#ajax-content').removeClass("rload");
            $("#ajax-content").empty().append(html);
               }
		   });
		 }
		 
		 
	//FUNCTION FOR CASHOUT tab
	function cashmeTab(){
     $('body').on('click', ' ul.omarkers li', function(){
	   var catid = $(this).attr('id');
	   if(catid == 'popularf'){
		   return false;
	   }
	   $('ul.omarkers li').removeClass("active");
	   var evname = $('.evidf').html();
	   var evidf = $(".evidf").attr("id");
	   var savthis = $(this);
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/categories",
			data: {
			method:'football_cat',
			catid:catid,
			usid:<?php echo $usid;?>,
			evid:evidf,
			evname:evname
			},
		success: function(response) {
             $("div#fetchcat").empty().append(response);
			 $(savthis).addClass('active');
            }
    });
    return false;
    });
		 }
	



  $('#stake_value').on('keyup',(event)=>{
    const stake=event.target.value;
    if(bet_type_field.value=='lay'){
      var lat = stake * totalOdss;
      //resturn_est.val(lat.toFixed(2));
	  var rsk = (totalOdss-1)*stake;
	  var lat = rsk * totalOdss;
	   resturn_est.val(lat.toFixed(2));
      risk_value.val(rsk.toFixed(2));
	  
    }else if (bet_type_field.value=='back'){

      risk_value.val(stake)
	  var tk = stake*totalOdss;
      resturn_est.val(tk.toFixed(2));
    }
  });
  


	
	

	$('body').on('click', 'div[id^=bet__option__btn]', function (event) {
		if(event.target.className=="bbackk" || event.target.className=="blayy"){
			return false;
			
		}
		$('._slip_container').css("background", "");  
		var numbet = $('.ccounter').length;
		if(numbet <= 1){
			$('._slip_wrapper').css("display", "block");
			$('.top-wrapper').css("display", "block");
		}
		
		
        var selected_option_id = this.id;
        var bet_info=selected_option_id.split('__');
        var event_name=bet_info[6];
        var cat_name=bet_info[7];
        var event_id=bet_info[4];
        var cat_id=bet_info[5];
        var oid = bet_info[3];
        var bod=bet_info[8];
        var lod=bet_info[9];
		var sport_id=bet_info[10];
		var sbk=bet_info[11];
		var sly=bet_info[12];
    	var oname = $(this).attr("class").replace("b_option_odd evn-", "");
		if(oname == 'Suspended'){
			$("span.cvib").html("Selection Suspended");
			return false;
		}
		
		
		
    		odd=0;
    		if(event.target.className=="bback"){
    			var btype = 'back';
    			odd=bod;
				sodd=sbk;
				$('.unsubtd').html('Type:<b>Back</b>');
    		} else if(event.target.className=="blay"){
    			var btype = 'lay';
    			odd=lod;
				sodd=sly;
				$('.unsubtd').html('Type:<b>Lay</b>');
    		}

    		$(".cvib").empty().append("Loading slip...");
  			$.ajax({
  			url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_add_bet",
  			type: "post",
  			data: {
  				oid:oid,
  				event_name:event_name,
  				cat_name:cat_name,
  				event_id:event_id,
  				cat_id:cat_id,
  				oname:oname,
  				btype:btype,
  				odd:odd,
				sodd:sodd,
				sport_id:sport_id,
				aid:'<?php echo $afid;?>',
  				usid:<?php echo $usid;?>,
  				save_bet:'save_post'
  			},
  			success: function (response) {
				$(".bback.activatorL").removeClass("activatorL");
				$(".blay.activatorLay").removeClass("activatorLay");
  				showUnSubmittedSlip();
				$('.cvib').html(response);
  				setTimeout(function() {
					var numbet = $('.ccounter').length;
					$('.numcc').val(numbet);
					if($('input#bet_type').val() === 'lay'){
						$('._slip_container').css("background", "#ffc8c8");
						};
				   if(numbet > 1){
					   $('._slip_wrapper').css("display", "none");
					   $('.top-wrapper').addClass('shw');
					   $('.closetop').text('Open');
					  } 
					}, 1000);
					
	  setTimeout(function() {
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      });
		  triggerInput();
		  }, 1000); // <-- time
					
  			},
  			error: function(jqXHR, textStatus, errorThrown) {
  			   //console.log(textStatus, errorThrown);
  			}

  		}); 
	        
	});

	function showUnSubmittedSlip(){
		totalOdss=1
		var unsubmitted_slips_container=$('._slip_container');
		unsubmitted_slips_container.html("");
		$.ajax({
  			url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_add_bet",
  			type: "post",
  			dataType: "json",
  			data: {
  				get_unsubmitted_slip:'get_unsubmitted_slip',
  				user_id:<?php echo $usid;?>
  			},
  			success: function (response) {
  				var html="";
  				$.each(response,(key,value)=>{
  					if(value){
						$('.ccounter').attr('class', 'ccounter');
						setTimeout(function() {
							$("#itemfg-"+value.bet_option_id).addClass("suspop"+ value.sodd);
							},100);
              bet_type_field.value=value.type;
	  					totalOdss=totalOdss*parseFloat(value.odd);
	  					html=`<div class="ccounter" id="itemfg-`+value.bet_option_id+`" style="width: 100%,border:1px green solid;padding-right: 3px;padding-left: 3px">
						 			<div class="sopt" id="`+value.type+`">
						 			<p class="eventnn">%event_name%</p>
						 			<a href="" class="delete_single_bet" title="Delete current unsubmitted bet" id="%bet_id%">X</a>
						 			</div>
						 			<p class="catnn" style="font-weight: 700,margin:0">%cat_name%</p>
						 			<div class="sbtslip">
						 				<p class="betnn" id="bbon-`+value.bet_option_id+`" style="margin:0,font-size: 14px">%bet_name%</p>
						 				<span class="slip-bet-odd" id="cors-`+value.bet_option_id+`">%bet_odd%</span>
						 			</div>
								</div>`;
  						html=html.replace('%event_name%',value.event_name);
  						html=html.replace('%cat_name%',value.cat_name);
  						html=html.replace('%bet_name%',value.bet_option_name);
  						html=html.replace('%bet_odd%',value.sodd);
  						html=html.replace('%bet_id%',value.bet_option_id);
  	  				unsubmitted_slips_container.append(html);
	  					unsubmitted_slips_container.append("<hr>");
  					}
  				})
  			},
  			error: function(jqXHR, textStatus, errorThrown) {
  			   //console.log(textStatus, errorThrown);
  			}
		});
	}

	//listener to delete single unsubmitted bet
	document.querySelector('._slip_container').addEventListener('click',function(event){
		event.preventDefault();
		var getUrl = window.location;
    	var baseurl = getUrl.origin + '/shell/inplay_add_bet';
    	const bet_id=event.target.id;
		if(event.target.className=='delete_single_bet'){
			$('._slip_wrapper').css("opacity", "0.5");
			$('.cvib').html('<a style="color:#fff">Deleting selection...</a>');
				$.ajax({
	  			url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_add_bet",
	  			type: "post",
	  			data: {
	  				bet_id:bet_id,
	  				usid:<?php echo $usid;?>,
	  				delete_single_bet:'delete_single_bet'
	  			},
	  			success: function (response) {
					setTimeout(function(){ 
					triggerInput();
					}, 2000);
	  				showUnSubmittedSlip();
	  				$('._slip_wrapper').css("opacity", "");
				    $('.cvib').html(response);
					setTimeout(function() {
						var numbet = $('.ccounter').length; 
						$('.numcc').val(numbet);
						//color slip
						if($('input#bet_type').val() === 'lay'){
							$('._slip_container').css("background", "");
							}
						}, 1000);
						$("#cor-" + bet_id).removeClass("activatorL");
						$("#corx-" + bet_id).removeClass("activatorLay");
						 
						
	  			},
	  			error: function(jqXHR, textStatus, errorThrown) {
	  			   //console.log(textStatus, errorThrown);
	  			}

	  		}); 
		}
	});

	//listener to delete all unsubmitted slips
	document.querySelector('#delete_all_unsubmitted_bets').addEventListener('click',function(event){
		event.preventDefault();
		$('._slip_wrapper').css("opacity", "0.5");
		$('.cvib').html('<a style="color:#fff">Deleting all slips...</a>');
		var getUrl = window.location;
    	var baseurl = getUrl.origin + '/shell/inplay_add_bet';
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_add_bet",
			type: "post",
			data: {
				usid:<?php echo $usid;?>,
				delete_all_unsubmitted_bets:'delete_all_unsubmitted_bets'
			},
			success: function (response) {
				setTimeout(function(){ 
				triggerInput();
				}, 2000);
				showUnSubmittedSlip();
				$('._slip_wrapper').css("opacity", "");
				$('.cvib').html(response);
					setTimeout(function() {
						var numbet = $('.ccounter').length; 
						$('.numcc').val(numbet);
						//color slip
						if($('input#bet_type').val() === 'lay'){
							$('._slip_container').css("background", "");
							}
						}, 1000);
						$(".bback.activatorL").removeClass("activatorL");
				        $(".blay.activatorLay").removeClass("activatorLay");
						
						
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}

		}); 
		
	})

	document.querySelector('#submit_bet').addEventListener('click',function(event){
    	const stake=parseFloat(document.querySelector('#stake_value').value);
		var svalue = $("#stake_value").val();
		
		if(svalue < 1){
		  $('.place_bet_button').text('Submit Bet');
		  $('.cvib').html('Stake cannot be Empty');
			return false;
			
		}
		
		$('.place_bet_button').addClass('stop');
		$('.place_bet_button').css('pointer-events','none');
		$(".modelwrap, #ajax-content, ._slip_wrapper").css("pointer-events", "none");
		$("._slip_container").css("opacity", "0.5");

		$('.place_bet_button').html('<div class="ofslide">.</div><div class="lds-hourglass"></div> Matching bet...');
		myTim1= setTimeout(function () {
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_add_bet",
			type: "post",
			data: {
				total_odd:parseFloat(totalOdss),
				stake:stake,
				usid:<?php echo $usid;?>,
				submit_bet:'submit_bet'
			},
			success: function (response) {
				$(".modelwrap, ._slip_wrapper").css("pointer-events", "");
				$("._slip_container").css("opacity", '');
				showUnSubmittedSlip();
				$('.cvib').html(response);
				$('.place_bet_button').text('Submit Bet');
				$(".bback.activatorL").removeClass("activatorL");
				$(".blay.activatorLay").removeClass("activatorLay");
				$('.place_bet_button, #ajax-content').css('pointer-events','');
				setTimeout(function(){ 
					if($.trim($("div._slip_container").html())==''){
					$("input.numcc").val("0");
					inputZero();
					$("div._slip_container").html("Click option to add bet");
					var cricid = $(".cricid").attr("id");
					if(cricid !=3){
					cashmeMain();
					}
					
					 }
					}, 2000);
					
	  setTimeout(function() {
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      });
		  triggerInput();
		  }, 500); // <-- time
		
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}

		});
		}, 5000);
		
	});
	
	

	
</script>

		



<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script>   
    var socket = io.connect('https://cricmarkets.com:4200');
        socket.on('connect', function(data) {
        socket.emit('join', 'Connected');
		});
		socket.on('disconnect', function(data) {
        location.reload();
		});
		
    socket.on('data_updated', function(data) {
		
		var cricid = $(".cricid").attr("id");
		
		let searchParams = new URLSearchParams(window.location.search);
	    var evid = searchParams.get('VwatchID');
		
		//function for cricket
		function getCricketSlip(){
			totalOdss=1;	
          $.ajax({
			type: "POST",
			dataType: "json",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/cricket_view_update",
			data: {
			get_all_events:'get_all_events',
            user_id:<?php echo $usid; ?>,
			event_id:evid
			},
		  success: function(response) {
			  //console.log(response);
		  //cashout
		  $.each(response.cash_data, function(ax, bx) {
             $("#slip-"+ ax).html("Cash " + bx);
          });
		  
		  if(response.unsubmitted_keys== ''){
			  showUnSubmittedSlip();
			  $("span.cvib").html("Click bet option to add");
			  return false;
		  }
		  
		  //update slip
		  $.each(response.unsubmitted_keys,(key,value)=>{
			  $('.ccounter').attr('class', 'ccounter');

  			 if(value){
			setTimeout(function() {
			  $("#itemfg-"+value.bet_option_id).addClass("suspop"+ value.sodd);
			  },100);
			  
              bet_type_field.value=value.type;
	  			totalOdss=totalOdss*parseFloat(value.odd);
				$("#cors-"+ value.bet_option_id).html(value.sodd);
				$("#bbon-"+ value.bet_option_id).html(value.bet_option_name);
					}
				});
				
				
	   setTimeout(function() {
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      });
		  triggerInput();
		  }, 500); // <-- time
		  
		},
		error: function (response) {
           //console.error(response);
        }
     });
		
		//trigger keyup value change
		setTimeout(function(){ 
		triggerInput();
		}, 2000);
 }
		
		//if in single page
	     if(evid != null) {
			 //cashmeMain();
			 //alert('sheee');
			 //console.log(evid);
		if(cricid ==3){
			var loadName = $("ul.crimarkers li.pxxactive").attr("id");
			if(loadName =="Popularc"){
				//$("li#Popularc").trigger("click");
				//return false;
				var catid='Popularc';
			}else if(loadName =="Runsc"){
				var catid='Runsc';
			}else if(loadName =="Wicketsc"){
				var catid='Wicketsc';
			}
			else if(loadName =="Scoresc"){
				var catid='Scoresc';
			}
			else if(loadName =="Oversc"){
				var catid='Oversc';
			}
			
			$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/cricket_view_refresh",
			data: {	
			method:'cricket_cat',
			catid:catid,
			usid:<?php echo $usid; ?>,
			evid:evid
			},
		   success: function(response) {
			$("ul.crimarkers li").removeClass("pxxactive");
			var okt = response.okact;
			$("#" + okt).addClass("pxxactive"); 
            $("#ajax-content").empty().append(response);
			getCricketSlip();
				//console.log(response);
            }
		  });
			
			
			  
		//for non cricket	  
		}else{

			
		totalOdss=1;	
          $.ajax({
			type: "POST",
			dataType: "json",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_view_update",
			data: {
			get_all_events:'get_all_events',
            user_id:<?php echo $usid; ?>,
			event_id:evid
			},
		  success: function(response) {
			  $(".cksuspend").html("Suspended");
			  $(".bback").html("0.00");
			  $(".blay").html("0.00");
			  $(".bbackk").html("0.00");
			  $(".blayy").html("0.00");
			  $(".betnn").html("Suspended");
			  $(".slip-bet-odd").html("0.00");
			 
            $.each(response.event_data, function(index, element) {
             $("#cor-"+ index).html(element[0] + "<ft class='bm'>"+ Math.floor(Math.random() * 100) + 20 +"</ft>");
			 $("#corx-"+ index).html(element[1] + "<ft class='lm'>"+ Math.floor(Math.random() * 100) + 5 +"</ft>");
			 });
			 
		  $.each(response.xdata, function(index, element) {
			 $("#cork-"+ index).html(element[0] + "</br><k class='kbm'>"+ Math.floor(Math.random() * 10) + 10 +"</k>");
			 $("#corkk-"+ index).html(element[1] + "</br><k class='klm'>"+ Math.floor(Math.random() * 10) + 2 +"</k>");
			 //$("#cors-"+ index).html(element[0]);
          });
		  
		  //option names
		  $.each(response.odata, function(ox, oy) {
             $(".ocg-"+ ox).html(oy);
          });
		  
		  //cashout
		  $.each(response.cash_data, function(ax, bx) {
             $("."+ ax).html("Cash " + bx);
			 //console.log(ax);
			 //console.log(bx);
          });
		  
		  //update slip
		  $.each(response.unsubmitted_keys,(key,value)=>{
			  $('.ccounter').attr('class', 'ccounter');
  			if(value){
				setTimeout(function() {
			  $("#itemfg-"+value.bet_option_id).addClass("suspop"+ value.sodd);
			  },100);
              bet_type_field.value=value.type;
	  			totalOdss=totalOdss*parseFloat(value.odd);
				$("#cors-"+ value.bet_option_id).html(value.sodd);
				$("#bbon-"+ value.bet_option_id).html(value.bet_option_name);
					}
				});
		},
		error: function (response) {
           //console.error(response);
        }
     });
		
		//trigger keyup value change
		setTimeout(function(){ 
		triggerInput();
		}, 2000);
		
		
		
		
		};
	 
    } else {
		//alert('allv');
		//console.log('single front');
		//inplay front update
		totalOdss=1;	
          $.ajax({
			type: "GET",
			dataType: "json",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/inplay_front_update",
			data: {
			get_all_events:'get_all_events',
            user_id:<?php echo $usid; ?>
			},
		  success: function(response) {
			  $(".cksuspend").html("Suspended");
			  $(".bback").html("0.00");
			  $(".blay").html("0.00");
			  $(".betnn").html("Suspended");
			  $(".slip-bet-odd").html("0.00");
			  //console.log(response);
			  //$(".event_bottom_wrapper").addClass("sus
			  //$(".event_bottom_wrapper").addClass("suspop"+ value.sodd);
            $.each(response.event_data, function(index, element) {
				//console.log(element);
				 //$(".event_bottom_wrapper").addClass("suspop"+ value.sodd);
             $("#cor-"+ index).html(element[0] + "<ft class='bm'>"+ Math.floor(Math.random() * 100) + 10 +"</ft>");
			 $("#corx-"+ index).html(element[1] + "<ft class='lm'>"+ Math.floor(Math.random() * 100) + 1 +"</ft>");
          });
		  
		  
		  //update slip
		  $.each(response.unsubmitted_keys,(key,value)=>{
			  $('.ccounter').attr('class', 'ccounter');
  			
			if(value){
			setTimeout(function() {
			  $("#itemfg-"+value.bet_option_id).addClass("suspop"+ value.sodd);
			  },100);
              bet_type_field.value=value.type;
	  			totalOdss=totalOdss*parseFloat(value.odd);
				$("#bbon-"+ value.bet_option_id).html(value.bet_option_name);
				$("#cors-"+ value.bet_option_id).html(value.sodd);
					}
				});
			
         //if(cricid ==3){
			 
			//suspend event id
			$.each(response.kdata, function(idx) {
				//$(".event_bottom_wrapper").addClass("suspop");
				$(".b_odd_wrapper").addClass("suspop");
				 setTimeout(function() {
					 $("#susu-" + idx).removeClass("suspop");
				},10);
			});
			
			setTimeout(function() {
					 $(".suspop").addClass("pxp");
				},100);	
				
			}
		});
	 

		
		//trigger keyup value change
		setTimeout(function(){ 
		triggerInput();
		}, 2000);
		
	};
	
	
}); //socket data
	
	
	
	//back button ajax 
$(window).on("popstate", function (e) {
	let searchParams = new URLSearchParams(window.location.search);
	var spid = searchParams.get('preREF');
	var ligid = searchParams.get('preLIG');
	var cc = searchParams.get('preCC');
	var tt = searchParams.get('preTT');
	var tom = searchParams.get('preTOM');
	var evd = searchParams.get('VwatchID');
	//load inplay list events
    $("#event_data_container").empty().append("<div id='loading'></div>");	 
      // var spid = $_GET['preREF'];
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/inplay_exchange/main_inplay_list",
			data: {
			method:'do_default'
			},
		success: function(response) {
			 setTimeout(function () {
                     $("#event_data_container").empty().append(response);
                 }, 1000);
			}
		});
    return false;
  });
	
	

</script>




<style type="text/css">
.matvhrf.xp {
    padding: 5px 5px;
    background: #607D8B;
    font-size: 12px;
    border-bottom: 1px solid #9c9c9c;
    color: #fff;
    margin-right: 2px;
}
li.nav-item.men87 {
    background: #e5e5e5;
}
</style>

<!-- Page Content-->
<main<?php echo Content::pageBg();?>>

  <!-- Validate page access-->
  <?php if(Content::validatePage()):?>
  <!-- Run page-->
  <?php echo Content::parseContentData($this->data->{'body' . Lang::$lang});?>
  
  <!-- Parse javascript -->
  <?php if ($this->data->jscode):?>
  <?php echo Validator::cleanOut(json_decode($this->data->jscode));?>
  <?php endif;?>
  <?php endif;?>
  <?php if($this->data->is_comments):?>
  <?php include_once(FMODPATH . 'comments/index.tpl.php');?>
  <?php endif;?>
</main>