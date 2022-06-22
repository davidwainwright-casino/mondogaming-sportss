<?php include('../db.php');
function maxi_bet($user_id,$conn){
		$query="SELECT deadline FROM risk_management";
		$mxbt=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($mxbt);
	}
$mbet = maxi_bet($user_id,$conn);
$dline = $mbet['deadline'];
	$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid = 4 AND ec.bet_event_cat_name = 'Match Result' OR UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid = 3 AND ec.bet_event_cat_name = 'To Win the Match'";

if(isset($_POST['method']) && $_POST['method'] == 'do_default'){
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');
$spd = array("3","4");
$sportsav = join("','",$spd);

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid IN('$sportsav') AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid ASC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE spid IN('$sportsav')";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="toptour"><a class="ttour">Top Upcoming Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
}

}


//TODAY
if(isset($_POST['method']) && $_POST['method'] == 'do_today'){
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND DATE_FORMAT(from_unixtime(e.deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d') AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname cc FROM af_pre_bet_events WHERE DATE_FORMAT(from_unixtime(deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Today\'s Upcoming Leagues</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
}

}


//TOMORROW
if(isset($_POST['method']) && $_POST['method'] == 'do_tomorrow'){
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND DATE_FORMAT(from_unixtime(e.deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE() + 1, '%Y-%m-%d') AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE DATE_FORMAT(from_unixtime(deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE() + 1, '%Y-%m-%d')";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Tommorrow\'s Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><?php $trc = $efetch['event_name']; echo substr($trc,0, 18).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
 }
}



//sports
if(isset($_POST['method']) && $_POST['method'] == 'do_sports'){
$spid = $_POST['spid'];
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid = '$spid' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";




$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE spid = '$spid'";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Upcoming Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }
  if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
}
}

//load more sports events
if(isset($_POST['method']) && $_POST['method'] == 'lomore'){
$spid = $_POST['spid'];
$rc = $_POST['rc'];
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid = '$spid' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT $rc, 120";
}




//back button
if(isset($_POST['method']) && $_POST['method'] == 'do_back'){
$spid = $_POST['spid'];
$ligid = $_POST['ligid'];
$cc = $_POST['cc'];
$tt = $_POST['tt'];
$tom = $_POST['tom'];
$evd = $_POST['evid'];
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');


if(!empty($spid)){
$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid = '$spid' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE spid = '$spid'";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Upcoming Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }
  if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
}

} else if(!empty($ligid)){
$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.event_id = '$ligid' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE event_id = $ligid";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Upcoming Events</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }

 } else if(!empty($cc)){
$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.cc = '$cc' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE cc = '$cc'";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Upcoming Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }

 } else if(!empty($tt)){

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND DATE_FORMAT(from_unixtime(e.deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d') AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname cc FROM af_pre_bet_events WHERE DATE_FORMAT(from_unixtime(deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Today\'s Upcoming Leagues</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span>  <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
}


 } else if(!empty($tom)){
$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND DATE_FORMAT(from_unixtime(e.deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE() + 1, '%Y-%m-%d') AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE DATE_FORMAT(from_unixtime(deadline), '%Y-%m-%d') = DATE_FORMAT(CURDATE() + 1, '%Y-%m-%d')";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Tommorrow\'s Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><?php $trc = $efetch['event_name']; echo substr($trc,0, 18).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
 }

 } else if(!empty($evd)){
    include_once('prematch_view.php');
	exit;


 } else {

$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');
$spd = array("22","4");
$sportsav = join("','",$spd);
$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.spid IN('$sportsav') AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE spid IN('$sportsav')";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="toptour"><a class="ttour">Top Upcoming Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></li>
	</ul>
<?php }
if ($onev->num_rows > 8) {
echo '<div id="shshall" class="shall">Show more..</div>';
}

 }

} //ifback





//league
if(isset($_POST['method']) && $_POST['method'] == 'do_league'){
$ligid = $_POST['ligid'];
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.event_id = '$ligid' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";

$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE event_id = $ligid";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Upcoming Events</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }
}


if(isset($_POST['method']) && $_POST['method'] == 'do_cc'){
$cc = $_POST['cc'];
$cats=array("1","2", "3");
$eids = join("','",$cats); //ec.bet_event_cat_name IN('$eids');

$filter = "WHERE UNIX_TIMESTAMP() < (e.deadline - $dline) AND e.cc = '$cc' AND ec.c_sort = '1' AND o.o_sort IN('$eids')";
$sql_pre = "SELECT e.bet_event_id, e.cc, e.sname, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.event_id, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_pre_bet_events e
            JOIN af_pre_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_pre_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id
            $filter ORDER BY e.spid DESC, e.deadline, e.event_name, e.bet_event_name, o.o_sort ASC LIMIT 120";
$sql_ev = "SELECT DISTINCT event_id, event_name, sname, cc FROM af_pre_bet_events WHERE cc = '$cc'";
$onev = mysqli_query($conn,$sql_ev);
echo '<div class="navback"><i class="icon angle double left"></i> Back</div><div class="toptour"><a class="ttour xbk">Upcoming Leagues/Tour.</a></div>';
while($efetch = mysqli_fetch_assoc($onev)){?>
	<ul class="sptoplig">
	<li class="event-name" id="<?php echo $efetch['event_id'];?>"><span class="sp_sprit <?php echo $efetch['sname'];?>" id="tlbg">!</span> <?php $trc = $efetch['event_name']; echo substr($trc,0, 22).'..';?></br><span class="licc"><?php echo $efetch['cc'];?></span></div></li>
	</ul>
<?php }
}




$event_data=mysqli_query($conn,$sql_pre);
echo '<input type="hidden" class="cfvalue" value="120">';
if ($event_data->num_rows < 1) {
	echo '<p class="opno">No more active events available.</p>'; die();
}
//GET LOCALTIME COOKIE
if(isset($_COOKIE['localtime'])) {
$oftime = $_COOKIE['localtime'] * 60;
}else{
	$oftime = 0;
}

$spid = NULL;
$deadline = NULL;
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
<?php $etime=$deadline - $oftime; $tm = date ("y-m-d H:i", $etime);
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

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode('-', $evname);
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
<?php $etime=$deadline - $oftime; $tm = date ("y-m-d H:i", $etime);
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

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode('-', $evname);
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
	$last_word_start = strrpos($eventName, ' ') + 1;
	$test = substr($eventName, $last_word_start);
	$spid = $record['spid'];
	$bet_event_id = $record['bet_event_id'];
	$evid = $record['event_id'];

	$eventName = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($record['bet_event_name'])] : $eventName;


      echo '<div class="event-name xp" id='.$record['event_id'].'> <i class="icon trophy"></i> ' . $eventName . ' <span class="prcc" id="'.$record['cc'].'">'.$record['cc'].'</span></div>';

	echo '<div class="event-name cu" id='.$record['event_id'].'> Coming Up <span class="b_name_wrapper" style="margin-top:0px"> <span class="b_option_name">1</span><span class="b_option_name '.$test.'" id="twoo'.$spid.'">X</span><span class="b_option_name">2</span></span></div>';
  }




//For showing bet_options on active deadline and event names with options odds
  if ($record['deadline'] != $deadline || $record['bet_event_name'] != $betEventName) {
      if ($betOptionNames != '' && $betOptionOdds != '') {?>

       <div class="betwrapper" id="b<?php echo $evid;?>">

<div class="event_bottom_wrapper">

<div class="deadlinewrapper">
<div class="cwrapper">
  <div class="clockok">.</div>
</div>
<div class="etimer">
<?php $etime=$deadline - $oftime; $tm = date ("y-m-d H:i", $etime);
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

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode('-', $evname);
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

      $deadline = $record['deadline'];
      $eventName = $record['event_name'];
	  $evid = $record['event_id'];
      $betEventName = $record['bet_event_name'];
	  $bet_event_id = $record['bet_event_id'];
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
  $opn = $record['o_sort'];


  echo '<div class="b_option_wrapper">';
  $betOptionNames .= '<span class="b_option_name">' . $opn . '</span>';
  $betOptionOdds .= '<div class="b_option_odd evn-'.$bon.'" id="bet__option__btn__'.$boi.'__'.$event_id.'__'.$cat_id.'__'.$betEventName.'__'.$cat_name.'__'.$bodk.'__'.$lodk.'__'.$spid.'__'.$bod.'__'.$lod.'">
  
  <div class="bback" id="cor-'.$boi.'"><span class="bbacker" id="cor-'.$boi.'">' . $bod . '</span> </br> <ft class="bm">'.$backrm.'</ft></div>
  <div class="blay hidetf" id="corx-'.$boi.'"> <span class="blayer" id="corx-'.$boi.'">' . $lod . '</span> </br> <ft class="lm">'.$layrm.'</ft></div>
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
<?php $etime=$deadline - $oftime; $tm = date ("y-m-d H:i", $etime);
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

<div class="ewraper">
<span class="b_event_name"> <a class="onevent" id="<?php echo $bet_event_id;?>" href="#"><?php $evname = $betEventName; $es = explode('-', $evname);
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

