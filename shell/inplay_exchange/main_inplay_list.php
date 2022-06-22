<?php include('../db.php');error_reporting(0);
	$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - 300) AND e.spid = 4 AND ec.bet_event_cat_name = 'Match Result' OR UNIX_TIMESTAMP() < (e.deadline - 300) AND e.spid = 22 AND ec.bet_event_cat_name = '2 way (including super over)'";

if(isset($_POST['method']) && $_POST['method'] == 'do_default'){
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');
$spd = array("22","4");
$sportsav = join("','",$spd);

$filter = "WHERE ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.spid,e.ss, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id 
			$filter ORDER BY e.spid ASC, e.event_name, e.bet_event_name, o.o_sort";
}


			
	
$event_data=mysqli_query($conn,$sql_pre);
echo '<input type="hidden" class="cfvalue" value="120">';
if ($event_data->num_rows < 1) {
	echo '<p class="opno">No more active events available.</p>'; die();
}	
	  
$spid = NULL;
$ss = NULL;
$eventName = NULL;
$betEventName = NULL;
$betOptionNames = '';
$betOptionOdds = '';
$done = false;

while($record=mysqli_fetch_assoc($event_data)){
  $done = false;


//for same sports aggregation
  if ($record['spid'] != $spid) {
    if ($spid != -1) {
      $done = true;
      if($betOptionNames != '' && $betOptionOdds != '') {?>
	  
	  
	  <div class="betwrapper" id="b<?php echo $evid;?>">
		  
		  <div class="event_bottom_wrapper">
              		  
<div class="deadlinewrapper">
<div class="cwrapper"> 		  
  <div class="clockok">.</div>
</div>
<div class="etimer">  
<?php echo $record['ss'];?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>"><?php $evname = $betEventName; $es = explode('-', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper"> <?php echo $betOptionOdds;?></span>
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

    echo '<div class="spid-group"><span class="sp_sprit '.$sname.'">!</span> <a class="datalink xp" id="'.$spid.'"  href="#"> '.$sname.' </a> </div>';
  }



//for same league name/id aggregation
  if ($record['event_name'] != $eventName) {
    if ($spid != -1) {
      if (!$done) {
        if($betOptionNames != '' && $betOptionOdds != '') {?>
		
		
		  
		  <div class="event_bottom_wrapper">
              		  
<div class="deadlinewrapper">
<div class="cwrapper"> 		  
  <div class="clockok">.</div>
</div>
<div class="etimer">  
<?php echo $record['ss'];?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>"><?php $evname = $betEventName; $es = explode('-', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper"> <?php echo $betOptionOdds;?></span>
          </div>
		  
		 </div> 

        <?php 
		  $betOptionNames = '';
          $betOptionOdds = '';
        }
      }
    }

    $eventName = $record['event_name'];
	$spid = $record['spid'];
	$bet_event_id = $record['bet_event_id'];
	$evid = $record['event_id'];

	$eventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])] : $eventName;


	echo '<div class="event-name xp" id='.$record['event_id'].'> <i class="icon trophy"></i> ' . $eventName . ' <span class="prcc" id="'.$record['cc'].'">'.$record['cc'].'</span></div>';
	
	echo '<div class="event-name cu" id='.$record['event_id'].'> Coming Up <span class="b_name_wrapper" style="margin-top:0px"> <span class="b_option_name">1</span><span class="b_option_name" id="twoo'.$spid.'">X</span><span class="b_option_name">2</span></span></div>';
  }
  
  
  
  
//For showing bet_options on active deadline and event names with options odds
  if ($record['bet_event_name'] != $betEventName) {
      if ($betOptionNames != '' && $betOptionOdds != '') {?>
	  
       <div class="betwrapper" id="b<?php echo $evid;?>">
		
<div class="event_bottom_wrapper">
              		  
<div class="deadlinewrapper">
<div class="cwrapper"> 		  
  <div class="clockok">.</div>
</div>
<div class="etimer">  
<?php echo $record['ss'];?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>"><?php $evname = $betEventName; $es = explode('-', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper"> <?php echo $betOptionOdds;?></span>
          </div>
		  
		  
		  
		 </div> 
		  
        <?php
        $betOptionNames = '';
        $betOptionOdds = '';
      }

      $ss = $record['ss'];
      $eventName = $record['event_name'];
	  $evid = $record['event_id'];
      $betEventName = $record['bet_event_name'];
	  $bet_event_id = $record['bet_event_id'];

      $eventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])] : $eventName;

  }
  
  $event_name=$evname;
  $spid = $record['spid'];
  $cat_name=$record['bet_event_cat_name'];
  $event_id=$record['bet_event_id'];
  $cat_id=$record['bet_event_cat_id'];
  $boi = $record['bet_option_id'];
  $bon = $record['bet_option_name'];
  $bod = $record['bet_option_odd'];
  $lod = $bod + 0.02;
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
  $betOptionOdds .= '<div class="b_option_odd evn-'.$bon.'" id="bet__option__btn__'.$boi.'__'.$event_id.'__'.$cat_id.'__'.$betEventName.'__'.$cat_name.'__'.$bod.'__'.$lod.'__'.$spid.'">
  <span class="bback" id="cor-'.$boi.'">' . $bod . '<ft class="bm">'.$backrm.'</ft></span> <span class="blay" id="corx-'.$boi.'">' . $lod . '<ft class="lm">'.$layrm.'</ft></span>
  </div>';
  echo '</div>';
}

if ($spid != -1) {?>

 <div class="betwrapper" id="b<?php echo $record['event_id'];?>">
  
		  
		  <div class="event_bottom_wrapper">
              		  
<div class="deadlinewrapper">
<div class="cwrapper"> 		  
  <div class="clockok">.</div>
</div>
<div class="etimer">  
<?php echo $record['ss'];?>
</div>
</div>

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>"><?php $evname = $betEventName; $es = explode('-', $evname); 
	           echo '<i id="ihome" class="icon shirt"></i>'; echo $h_name = $es[0]; echo '</br>';
	           echo '<i id="iaway" class="icon shirt"></i>'; echo $a_name = $es[1];
			   ?></a>
  </span>
</div>			  


			  <span class="b_odd_wrapper"> <?php echo $betOptionOdds;?></span>
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

