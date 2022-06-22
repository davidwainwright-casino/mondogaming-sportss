<?php include_once("__odds_switcher.php");
$btype=$_POST['btype'];
$kspid=$_POST['kspid'];
$cc = $_POST['cc'];
echo '<input id="btype" value="'.$btype.'" hidden>';
echo '<input id="kspid" value="'.$kspid.'" hidden>';
echo '<input id="kcc" value="'.$cc.'" hidden>';
			//Live Events Query
			$ops=array("1","2", "3");
			$oddc = join("','",$ops);
			$spd = array("3","4","5");
			$spid = join("','",$spd);

			//$filter = "WHERE ec.c_sort = '1' AND o.o_sort IN('$eids')";
			if($btype=='all'){
			$hquery = mysqli_query($conn,"SELECT e.bet_event_id, e.cc, e.ss, e.sname, e.spid, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd FROM af_inplay_bet_events e JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id WHERE ec.c_sort = 1 AND o.o_sort IN('$oddc') AND e.spid IN('$spid') ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort");
			}
			else if($btype=='sport'){
			$hquery = mysqli_query($conn,"SELECT e.bet_event_id, e.cc, e.ss, e.sname, e.spid, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd FROM af_inplay_bet_events e JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id WHERE ec.c_sort = 1 AND o.o_sort IN('$oddc') AND e.spid=$kspid ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort");
			}
			else if($btype=='country'){
			$hquery = mysqli_query($conn,"SELECT e.bet_event_id, e.cc, e.ss, e.sname, e.spid, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd FROM af_inplay_bet_events e JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id WHERE ec.c_sort = 1 AND o.o_sort IN('$oddc') AND e.cc='$cc' ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort");
			}


			$spid = NULL;
			$deadline = NULL;
			$eventName = NULL;
			$betEventName = NULL;
			$betOptionNames = '';
			$betOptionOdds = '';
			$done = false;
			$i=0;
			while($record=mysqli_fetch_assoc($hquery)){
				$done = false;





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
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="/events/?pid=<?php echo $bet_event_id;?>&sp=<?php echo $spid;?>">
        <?php $betEventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($betEventName)])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($betEventName)] : $betEventName; ?>

        <?php $evname = $betEventName; $es = explode('-', $evname);
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0] . '<br>';//substr($es[0],0,22); echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];//substr($es[1],0,22);
			   ?></a>
  </span>
</div>


			  <span class="b_odd_wrapper" id="susu-<?php echo $bet_event_id;?>"> <?php echo $betOptionOdds;?></span>
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

	if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $record['cc']))}))
	    $record['cc_name'] = Lang::$word->{strtoupper(str_replace(' ', '_', $record['cc']))};

	$eventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])] : $eventName;

    echo '<div class="event-name xp bg" id='.$record['event_id'].'><span id="spico" class="sp_sprit '.$record['sname'].'">!</span> ' . $eventName . ' <span class="prcc" id="'.$record['cc'].'">'.$record['cc_name'].'</span></div>';

	echo '<div class="event-name cu" id='.$record['event_id'].'> <a id="lnow">' .Lang::$word->LIVE_NOW_BG . '</a> <span class="b_name_wrapper" style="margin-top:0px"> <span class="b_option_name">1</span><span class="b_option_name '.$test.'" id="twoo'.$spid.'">X</span><span class="b_option_name">2</span></span></div>';
  }





//For showing bet_options on active deadline and event names with options odds
  if ($record['bet_event_name'] != $betEventName) {
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
			<span class="b_event_name"> 
				<a class="onevent" id="<?php echo $bet_event_id;?>" href="/events/?pid=<?php echo $bet_event_id;?>&sp=<?php echo $spid;?>">
                    <?php $betEventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($betEventName)])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($betEventName)] : $betEventName; ?>
                    <?php $evname = $betEventName; $es = explode('-', $evname);
					echo '<i id="ihome" class="icon shirt"></i>';echo $h_name = $es[0] . '<br>';//substr($es[0],0,22); echo '</br>';
					echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];//substr($es[1],0,22);;?>
				</a>
			</span>
		</div>
		<span class="b_odd_wrapper" id="susu-<?php echo $bet_event_id;?>"> <?php echo $betOptionOdds;?></span>
	</div>
</div>

    <?php
        $betOptionNames = '';
        $betOptionOdds = '';
      }
      //for last brac
      $deadline = $record['deadline'];
      $eventName = $record['event_name'];
	  $evid = $record['event_id'];
      $betEventName = $record['bet_event_name'];
	  $bet_event_id = $record['bet_event_id'];
	  $bet_event_cat_id = $record['bet_event_cat_id'];
	  $rss = $record['ss'];
	  $spid = $record['spid'];
  }
	  //for first brac
	  $event_name=$evname;
	  $spid = $record['spid'];
	  $cat_name=$record['bet_event_cat_name'];
	  $event_id=$record['bet_event_id'];
	  $cat_id=$record['bet_event_cat_id'];
	  $boi = $record['bet_option_id'];
	  $bon = $record['bet_option_name'];
	  $type='live';



  $bodk = bgetOdd($record['bet_option_odd']);
  $bod = $record['bet_option_odd'];

  $backrm = rand(10,1000);
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
  $betOptionOdds .= '<div class="b_option_odd evn-'.$bon.' hsu'.(int)($bodk).''.$ecg.'" id="bet__option__btn__'.$event_id.'__'.$betEventName.'__'.$cat_id.'__'.$cat_name.'__'.$boi.'__'.$bon.'__'.$spid.'__'.$bodk.'__'.$bod.'__'.$type.'">
  
  <span class="bback" id="cor-'.$boi.'">' . $bodk .'</span> 
  </div>';
  echo '</div>';



}






if ($spid != -1) {?>

 <div class="betwrapper" id="b<?php echo $record['event_id'];?>">


<div class="event_bottom_wrapper">

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
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="/events/?pid=<?php echo $bet_event_id;?>&sp=<?php echo $spid;?>">
                <?php $betEventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($betEventName)])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($betEventName)] : $betEventName; ?>
                <?php $evname = $betEventName; $es = explode('-', $evname);
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>


			  <span class="b_odd_wrapper"> <?php echo $betOptionOdds;?></span>
          </div>

		 </div>
		 </div>
		 </div>

	<?php
} else {
  echo "<h3>No record found!</h3>";
}
