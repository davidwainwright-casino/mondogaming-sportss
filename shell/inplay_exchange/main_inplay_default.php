<?php include('../db.php');error_reporting(0);
	//$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - 300) AND e.spid = 4 AND ec.bet_event_cat_name = 'Match Result' OR UNIX_TIMESTAMP() < (e.deadline - 300) AND e.spid = 22 AND ec.bet_event_cat_name = '2 way (including super over)'";

if(isset($_POST['method']) && $_POST['method'] == 'do_default'){
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');
$spd = array("3","4");
$sportsav = join("','",$spd);

//$filter = "WHERE ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE ec.c_sort = '1' AND o.o_sort IN('$eids') ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort";
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events ORDER BY spid ASC";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Top Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 0) {
echo '</br><div id="shshall" class="shall">Show more..</div> <div class="showsp_mob">Live Sports ('.$csport["cspid"].')</div>';
}

}



	
//sports
if(isset($_POST['method']) && $_POST['method'] == 'do_sports'){
$spid = $_POST['spid'];


$filter = "WHERE e.spid = '$spid' AND ec.c_sort = '1'";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort LIMIT 120";
			

			
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events WHERE spid = '$spid'";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div> <div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }
  if ($onev->num_rows > 0) {	
echo '</br><div id="shshall" class="shall">Show more..</div> <div class="showsp_mob">Live Sports ('.$csport["cspid"].')</div>';
}
}

//load more sports events
if(isset($_POST['method']) && $_POST['method'] == 'lomore'){
$spid = $_POST['spid'];
$rc = $_POST['rc'];

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - 300) AND e.spid = '$spid' AND ec.c_sort = '1'";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort LIMIT $rc, 120";
}

//back button
if(isset($_POST['method']) && $_POST['method'] == 'do_back'){
$spid = $_POST['spid'];
$ligid = $_POST['ligid'];
$cc = $_POST['cc'];
$tt = $_POST['tt'];
$tom = $_POST['tom'];
$evd = $_POST['evid'];


if(!empty($spid)){
$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE e.spid = '$spid' AND ec.c_sort = '1' ORDER BY e.deadline, e.event_name, e.bet_event_name, o.o_sort";			
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events ORDER BY spid ASC";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));	
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div> <div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 0) {  
echo '</br><div id="shshall" class="shall">Show more..</div> <div class="showsp_mob">Live Sports ('.$csport["cspid"].')</div>';
}

} else if(!empty($ligid)){
$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE e.event_id = '$ligid' AND ec.c_sort = '1' ORDER BY e.deadline, e.event_name, e.bet_event_name, o.o_sort";
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events WHERE event_id=$ligid";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div> <div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }							
	
 } else if(!empty($cc)){
$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE e.cc = '$cc' AND ec.c_sort = '1' ORDER BY e.deadline, e.event_name, e.bet_event_name, o.o_sort";
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events WHERE cc='$cc'";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div> <div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Leagues By Country.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }							
		
	
 } else if(!empty($evd)){
    include_once('inplay_view.php');
	exit;

	
 } else {

$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');
$spd = array("22","4");
$sportsav = join("','",$spd);	 
$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE ec.c_sort = '1' ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort";
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events ORDER BY spid ASC";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Top Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 18).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 0) {	  
echo '</br><div id="shshall" class="shall">Show more..</div> <div class="showsp_mob">Live Sports ('.$csport["cspid"].')</div>';
}
			
 }

} //ifback





//league
if(isset($_POST['method']) && $_POST['method'] == 'do_league'){
$ligid = $_POST['ligid'];


$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE e.event_id = '$ligid' AND ec.c_sort = '1' ORDER BY e.deadline, e.event_name, e.bet_event_name, o.o_sort";
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events WHERE event_id=$ligid";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div> <div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }			
}

if(isset($_POST['method']) && $_POST['method'] == 'do_cc'){
$cc = $_POST['cc'];

$sql_pre = "SELECT e.bet_event_id, e.cc, e.ss, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            WHERE e.cc = '$cc' AND ec.c_sort = '1' ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort";
			
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_inplay_bet_events WHERE cc='$cc'";
$csport = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(DISTINCT spid) as cspid FROM af_inplay_bet_events"));
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div> <div class="toptour"><a id="lnow">Live Now </a> | <a class="ttour">Leagues By Country.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }						
		
}


			
	
$event_data=mysqli_query($conn,$sql_pre);
echo '<input type="hidden" class="cfvalue" value="120">';
if ($event_data->num_rows < 1) {
	echo '<p class="opno">No more active events available.</p>'; die();
}	
	  
$spid = NULL;
$deadline = NULL;
$eventName = NULL;
$betEventName = NULL;
$betOptionNames = '';
$betOptionOdds = '';
$done = false;
$i=0;
while($record=mysqli_fetch_assoc($event_data)){
  $done = false;


//for same sports aggregation
  if ($record['spid'] != $spid) {
    if ($spid != -1) {
      $done = true;
      if($betOptionNames != '' && $betOptionOdds != '') {?>
	  
	  
	  <div class="betwrapper" id="b<?php echo $evid;?>">
		  
<div class="event_bottom_wrapper" id="suu-<?php echo $bet_event_id;?>">

<div class="deadlinewrapper xp">
<div class="cwrapper"> 		  
  <i class="icon bar chart"></i>
</div>
<div class="etimer">  
	  <?php if(!empty($rss)){ echo $rss; } else { echo '0:0';};?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode(' - ', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper" id="susu-<?php echo $bet_event_id;?>"> <?php echo $betOptionOdds;?></span>
          </div>
		  </div>
        
        <?php 
		$betOptionNames = '';
        $betOptionOdds = '';
      }
    }

    $spid = $record['spid'];
	$sname = $record['sname'];
	$evid = $record['event_id'];
	$bet_event_id = $record['bet_event_id'];
	$bet_event_cat_id = $record['bet_event_cat_id'];
	$rss = $record['ss'];

    echo '<div class="spid-group"><span class="sp_sprit '.$sname.'">!</span> <a class="datalink xp" id="'.$spid.'"  href="#"> '.$sname.' </a> </div>';
  }



//for same league name/id aggregation
  if ($record['event_name'] != $eventName) {
    if ($spid != -1) {
      if (!$done) {
        if($betOptionNames != '' && $betOptionOdds != '') {?>
		
		
		  
<div class="event_bottom_wrapper" id="suu-<?php echo $bet_event_id;?>">

<div class="deadlinewrapper xp">
<div class="cwrapper"> 		  
  <i class="icon bar chart"></i>
</div>
<div class="etimer">  
<?php if(!empty($rss)){ echo $rss; } else { echo '0:0';};?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode(' - ', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper" id="susu-<?php echo $bet_event_id;?>"> <?php echo $betOptionOdds;?></span>
          </div>
		  
		 </div> 

        <?php 
		  $betOptionNames = '';
          $betOptionOdds = '';
        }
      }
    }

    $eventName = $record['event_name'];
	$last_word_start = strrpos($eventName, ' ') + 1;
	$test = substr($eventName, $last_word_start);
	$spid = $record['spid'];
	$bet_event_id = $record['bet_event_id'];
	$bet_event_cat_id = $record['bet_event_cat_id'];
	$evid = $record['event_id'];
	$rss = $record['ss'];

    echo '<div class="event-name xp bg" id='.$record['event_id'].'> <i class="icon trophy"></i> ' . $eventName . ' <span class="prcc" id="'.$record['cc'].'">'.$record['cc'].'</span></div>';
	
	echo '<div class="event-name cu" id='.$record['event_id'].'> <a id="lnow">'. Lang::$word->LIVE_NOW_BG .'</a> <span class="b_name_wrapper" style="margin-top:0px"> <span class="b_option_name">1</span><span class="b_option_name '.$test.'" id="twoo'.$spid.'">X</span><span class="b_option_name">2</span></span></div>';
  }
  
  
 
  
//For showing bet_options on active deadline and event names with options odds
  if ($record['deadline'] != $deadline || $record['bet_event_name'] != $betEventName) {
      if ($betOptionNames != '' && $betOptionOdds != '') {?>
	  
       <div class="betwrapper" id="b<?php echo $evid;?>">
		
<div class="event_bottom_wrapper" id="suu-<?php echo $bet_event_id;?>">

<div class="deadlinewrapper xp">
<div class="cwrapper"> 		  
  <i class="icon bar chart"></i>
</div>
<div class="etimer">  
<?php if(!empty($rss)){ echo $rss; } else { echo '0:0';};?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode(' - ', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper" id="susu-<?php echo $bet_event_id;?>"> <?php echo $betOptionOdds;?></span>
          </div>
		  
		  
		  
		 </div> 
		  
        <?php
        $betOptionNames = '';
        $betOptionOdds = '';
      }

      $deadline = $record['deadline'];
      $eventName = $record['event_name'];
	  $evid = $record['event_id'];
      $betEventName = $record['bet_event_name'];
	  $bet_event_id = $record['bet_event_id'];
	  $bet_event_cat_id = $record['bet_event_cat_id'];
	  $rss = $record['ss'];
  }
  
  $event_name=$evname;
  $spid = $record['spid'];
  $cat_name=$record['bet_event_cat_name'];
  $event_id=$record['bet_event_id'];
  $cat_id=$record['bet_event_cat_id'];
  $boi = $record['bet_option_id'];
  $bon = $record['bet_option_name'];
  
  
  if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	  //for back
	  $decimal_odd = $record['bet_option_odd'];
	  if (2 > $decimal_odd) {
                $plus_minus = '-';
                $result = 100 / ($decimal_odd - 1);  
            } else {              
                $plus_minus = '+';
                $result = ($decimal_odd - 1) * 100;
            }       
            $bod = ($plus_minus . round($result, 2));
      //for lay
	  $decimal_oddlay = $decimal_odd + 0.02;
	  if (2 > $decimal_oddlay) {
                $plus_minusl = '-';
                $resultl = 100 / ($decimal_oddlay - 1);  
            } else {              
                $plus_minusl = '+';
                $resultl = ($decimal_oddlay - 1) * 100;
            }       
            $lod = ($plus_minusl . round($resultl, 2));
	  
	 
  }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $record['bet_option_odd'];
	  if (2 == $decimal_odd) {
                $bod = '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    $bod = ($dividend . '/' . $divisor);
                }
            }           
            $bod = ($dividend . '/' . $divisor);
			
		//for lay	
		$decimal_oddlay = $decimal_odd + 0.02;	
		if (2 == $decimal_oddlay) {
                $lod = '1/1';
            }         
            $dividendl = intval(strval((($decimal_oddlay - 1) * 100)));
            $divisorl = 100;
            
            $smallerl = ($dividendl > $divisorl) ? $divisorl : $dividendl;
            
            //worst case: 100 iterations
            for ($common_denominatorl = $smallerl; $common_denominatorl > 0; $common_denominatorl --) {
                if ( (0 === ($dividendl % $common_denominatorl)) && (0 === ($divisorl % $common_denominatorl)) ) {              
                    $dividendl /= $common_denominatorl;
                    $divisorl /= $common_denominatorl;                 
                    $lod = ($dividendl . '/' . $divisorl);
                }
            }           
            $lod = ($dividendl . '/' . $divisorl);	
	  
  }else{	  
  $bod = $record['bet_option_odd'];
  $lod = $bod + 0.02;
  }
  
  
  
  
  
  
  $bodk = $record['bet_option_odd'];
  $lodk = $bodk + 0.02;
  
  $backrm = rand(10000,99000);
  $layrm = rand(10,100);
  $spd = $record['spid'];
  $ecg = $record['o_sort']; 
  if($ecg == 2 && $bon == 'X' ){
	$opn = "X";
	} else if($ecg == 3 && $spd == 4){
		$opn = "2";
		} else{ 
		$opn = $record['o_sort'];
	}
	
  echo '<div class="b_option_wrapper">';
  $betOptionNames .= '<span class="b_option_name">' . $opn . '</span>';
  $betOptionOdds .= '<div class="b_option_odd evn-'.$bon.' hsu'.(int)($bodk).''.$ecg.'" id="bet__option__btn__'.$boi.'__'.$event_id.'__'.$cat_id.'__'.$betEventName.'__'.$cat_name.'__'.$bodk.'__'.$lodk.'__'.$spid.'__'.$bod.'__'.$lod.'">
  
  <span class="bback" id="cor-'.$boi.'">' . $bod . '<ft class="bm">'.$backrm.'</ft></span> 
  <span class="blay" id="corx-'.$boi.'">' . $lod . '<ft class="lm">'.$layrm.'</ft></span>
  </div>';
  echo '</div>';
}

if ($spid != -1) {?>

 <div class="betwrapper" id="b<?php echo $record['event_id'];?>">
  
		  
<div class="event_bottom_wrapper" id="suu-<?php echo $bet_event_id;?>">

<div class="deadlinewrapper xp">
<div class="cwrapper"> 		  
  <i class="icon bar chart"></i>
</div>
<div class="etimer">  
<?php $rss = $record['ss']; if(!empty($rss)){ echo $rss; } else { echo '0:0';};?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode(' - ', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper" id="susu-<?php echo $record['bet_event_id'];?>"> <?php echo $betOptionOdds;?></span>
          </div>
		  
		 </div> 
	
	<?php
} else {
  echo "<h3>No record found!</h3>";
}



if ($event_data->num_rows > 60) {
	echo '<div class="addload"></div>';
	echo '<div class="loadmo">Load More</div>';
}	

