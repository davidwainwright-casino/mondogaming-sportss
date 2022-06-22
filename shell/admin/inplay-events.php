<?php //<link rel="stylesheet" href="https://sp.sportsfier.com/shell/admin/str.css" type="text/css">;?>
<?php
include_once('../db.php'); ?>

<?php
//sports view
if(isset($_POST['method']) && $_POST['method'] == 'sportsv'){?>

<ul class="tilesWrap">
	<li>
		<h2>04</h2>
		<h3>FOOTBALL</h3>
		<p>
			Football or Soccer all events and leagues
		</p>
		<button id="4">View/Edit Events</button>
	</li>
	<li>
		<h2>3</h2>
		<h3>CRICKET</h3>
		<p>
			All cricket leagues and Events
		</p>
		<button id="3">View/Edit Events</button>
	</li>
	<li>
		<h2>05</h2>
		<h3>TENNIS</h3>
		<p>
			All Tennis events and matches
		</p>
		<button id="5">View/Edit Events</button>
	</li>
	<li>
		<h2>07</h2>
		<h3>BASKETBALL</h3>
		<p>
			All basketball Events and Leagues
		</p>
		<button id="7">View/Edit Events</button>
	</li>
	
	
	<li>
		<h2>11</h2>
		<h3>A. FOOTBALL</h3>
		<p>
			American Football league Matches
		</p>
		<button id="11">View/Edit Events</button>
	</li>
	<li>
		<h2>23</h2>
		<h3>BASEBALL</h3>
		<p>
			All Baseball leagues and Events
		</p>
		<button id="23">View/Edit Events</button>
	</li>
	<li>
		<h2>12</h2>
		<h3>ICE HOCKEY</h3>
		<p>
			Top Ice Hockey events list
		</p>
		<button id="12">View/Edit Events</button>
	</li>
	<li>
		<h2>18</h2>
		<h3>VOLLEYBALL </h3>
		<p>
			All Volleyball Events and matches
		</p>
		<button id="18">View/Edit Events</button>
	</li>
	
	
		
	<li>
		<h2>24</h2>
		<h3>BOXING</h3>
		<p>
			All boxing league Matches
		</p>
		<button id="24">View/Edit Events</button>
	</li>
	<li>
		<h2>31</h2>
		<h3>RUGBY LEAGUE</h3>
		<p>
		    Rugby leagues matches and Events
		</p>
		<button id="31">View/Edit Events</button>
	</li>
	<li>
		<h2>32</h2>
		<h3>RUGBY UNION</h3>
		<p>
			Rugby Union events list
		</p>
		<button id="32">View/Edit Events</button>
	</li>
	<li>
		<h2>16</h2>
		<h3>HANDBALL </h3>
		<p>
			All Handball Events and matches
		</p>
		<button id="16">View/Edit Events</button>
	</li>
	
	
		<li>
		<h2>33</h2>
		<h3>Snooker</h3>
		<p>
			All Snooker Events & Matches
		</p>
		<button id="33">View/Edit Events</button>
	</li>
	<li>
		<h2>34</h2>
		<h3>DARTS</h3>
		<p>
		   Darts matches and Events
		</p>
		<button id="34">View/Edit Events</button>
	</li>
	<li>
		<h2>56</h2>
		<h3>TABLE TENNIS</h3>
		<p>
			Table Tennis events list
		</p>
		<button id="56">View/Edit Events</button>
	</li>
	<li>
		<h2>40</h2>
		<h3>MOTORBIKES </h3>
		<p>
			All Motorbikes Events and matches
		</p>
		<button id="40">View/Edit Events</button>
	</li>
	
	
	<li>
		<h2>6</h2>
		<h3>FORMULA 1</h3>
		<p>
			Formula 1 Events & Matches
		</p>
		<button id="6">View/Edit Events</button>
	</li>
	<li>
		<h2>9</h2>
		<h3>ALPINE SKIING</h3>
		<p>
		   Alpine Skiing matches and Events
		</p>
		<button id="9">View/Edit Events</button>
	</li>
	<li>
		<h2>10</h2>
		<h3>CYCLING</h3>
		<p>
			Cycling matches & events list
		</p>
		<button id="10">View/Edit Events</button>
	</li>
	<li>
		<h2>13</h2>
		<h3>GOLF </h3>
		<p>
			Golf tours, Events and matches
		</p>
		<button id="13">View/Edit Events</button>
	</li>
	
	
	<li>
		<h2>64</h2>
		<h3>Biathlon</h3>
		<p>
			Biathlon Events & Matches
		</p>
		<button id="64">View/Edit Events</button>
	</li>
	<li>
		<h2>70</h2>
		<h3>FUTSAL</h3>
		<p>
		   Futsal matches and Events
		</p>
		<button id="70">View/Edit Events</button>
	</li>
	<li>
		<h2>100</h2>
		<h3>OTHERS</h3>
		<p>
			Custom Events and Sports
		</p>
		<button id="100">View/Edit Events</button>
	</li>
	
	
	
	
</ul>

<?php }

//sports leagues view
if(isset($_POST['method']) && $_POST['method'] == 'fsevents'){
	$spi = $_POST['idt'];
	echo '<a class="bktosport" href="#"><i class="icon long arrow up left"></i> Back to Sports</a>';
	echo '<a class="addevt">Create New Event</a></br></br>';?>
	<div class="wrapsr">
   <div class="searchsr">
      <input type="text" class="esearch" id="esearch" placeholder="Type event name or team name eg. Liverpool, Chelsea">
        <i id="findsr" class="icon find"></i>
   </div>
   <div class="esearchview" id="esearchview"></div>
</div>

	<?php $xr = "SELECT spid, sname FROM af_inplay_bet_events WHERE spid = '$spi'";
	$result = mysqli_query($conn, $xr);
	$c_row = $result->fetch_assoc();
	$tk = $c_row['sname'];
	$tis = $c_row['spid'];
	echo '<h1 class="spnn" id='.$tis.'>'.$tk.'</h1>';
	echo '<input type="hidden" class="cfvalue" value="5">';
	
	$equery = "SELECT * FROM af_inplay_bet_events WHERE spid = '$spi' ORDER BY deadline ASC LIMIT 50";
    $result = $conn->query($equery);
      echo '<div class="preevents">';
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if($row['event_name'] !=$ename){?>
			</br>
			<h3>
		    <i class="icon fire"></i> <?php echo $row['event_name'];?> [<?php echo $row['cc'];?>]
			</h3> 

			<?php } ?>
			
			<?php $ename = $row['event_name'];?>
			<div class="evsna">			
			<a class="yoyo basic icon fluid button primary" style="margin:0; padding:0; color:#000;background-color:#fff;display: inline-flex;"> <i class="icon spin full"></i> </a> <?php echo $row['bet_event_name'];?> (<?php echo $row['cc'];?>) [<?php $dt = $row['deadline']; echo date ("m-d H:i", $dt);?>]
			<div class="editv spk-<?php echo $row['spid'];?>" id="ed-<?php echo $row['bet_event_id'];?>"> <i class="icon underline"></i> Edit/View</div>
			</div>
			
			
		 <?php } echo '<div class="addload"></div>';
	} else {
		echo 'No Active events on this sport';
		
	}; echo '<div class="loadmo"><span class="lodmo">Load more events...</span> </div>';
	
	echo '</div>';
	
	
}



//Add new event first step
if(isset($_POST['method']) && $_POST['method'] == 'addevt'){
	$bkspo = $_POST['bkspo'];
	?>
	<div class="nevent"><button class="bkev" id="<?php echo $bkspo;?>"><i class="icon long arrow up left"></i> Back to Events</button>
		<div class="evit" id="">Create New Event</div>
	<div class="splabel"><i class="icon info sign"></i> Select sport</div>	
	<i class="icon pen alt"></i> <select id="spids">
		<option value="4">Football</option>
		<option value="22">Cricket</option>
		<option value="5">Tennis</option>
		<option value="7">Basketball</option>
		<option value="11">American Football</option>
		<option value="23">Baseball</option>
		<option value="12">Ice Hockey</option>
		<option value="18">Volleyball</option>
		<option value="24">Boxing</option>
		<option value="31">Rugby League</option>
		<option value="32">Rugby Union</option>
		<option value="16">Handball</option>
		<option value="33">Snooker</option>
		<option value="34">Darts</option>
		<option value="56">Table Tennis</option>
		<option value="40">Motorbikes</option>
		<option value="6">Formula 1</option>
		<option value="9">Alpine Skiing</option>
		<option value="10">Cycling</option>
		<option value="13">Golf</option>
		<option value="64">Biathlon</option>
		<option value="70">Futsal</option>
		<option value="100">Others</option>
	</select></br></br>
		
		<div class="splabel"><i class="icon info sign"></i> League/event Name e.g: English Premeir League</div>	
		<i class="icon pen alt"></i> <input type="text" class="eventnam" placeholder="League/Event Name"> </br></br>
		
		<div class="splabel"><i class="icon info sign"></i> Select bet event Name eg. Chelsea - Manchester City</div>	
		<i class="icon pen alt"></i> <input type="text" class="eventbetnam" Placeholder="Bet/team event Name"> </br></br>
		
		<div class="splabel"><i class="icon info sign"></i> Type date in format year-month-day hour:minute</div>	
		<i class="icon pen alt"></i> <input type="text" class="eventdt" value="<?php echo date("Y-m-d H:i");?>"> </br></br>
		
		<div class="splabel"><i class="icon info sign"></i> Region or Country eg. World, England, India etc.</div>	
		<i class="icon pen alt"></i> <input type="text" class="eventcc" placeholder="Region/Country" value=""> </br></br>
		<div class="addingev">Add New Event</div>	
</div>
	
<?php }

//sports leagues view load more
if(isset($_POST['method']) && $_POST['method'] == 'fseventsmore'){
	$spi = $_POST['idt'];
	$rc = $_POST['rc'];

	
	$equery = "SELECT * FROM af_inplay_bet_events WHERE spid = '$spi' ORDER BY deadline ASC LIMIT $rc, 50";
    $result = $conn->query($equery);
	if ($result->num_rows < 1) {
		die();
	}
	//var_dump($result);
      echo '<div class="preevents">';
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if($row['event_name'] !=$ename){?>
			</br>
			<h3>
		    <i class="icon fire"></i> <?php echo $row['event_name'];?> [<?php echo $row['cc'];?>]
			</h3> 

			<?php } ?>
			
			<?php $ename = $row['event_name'];?>
			<div class="evsna">			
			<a class="yoyo basic icon fluid button primary" style="margin:0; padding:0; color:#000;background-color:#fff;display: inline-flex;"> <i class="icon spin full"></i> </a> <?php echo $row['bet_event_name'];?> (<?php echo $row['cc'];?>) [<?php $dt = $row['deadline']; echo date ("m-d H:i", $dt);?>]
			<div class="editv spk-<?php echo $row['spid'];?>" id="ed-<?php echo $row['bet_event_id'];?>"> <i class="icon underline"></i> Edit/View</div>
			</div>
			
			
		 <?php } echo '<div class="addload"></div>';
	} else {
		echo 'No More Events to Load';
		die();
		
	};  echo '<div class="loadmo"><span class="lodmo">Load more events... </span></div>'; echo '<div id="nomore"></div>';
	
	echo '</div>';
	
	
}




//for full event view

if(isset($_POST['method']) && $_POST['method'] == 'edi'){
	$edi = $_POST['edi'];
	$spo = $_POST['spo'];
	echo '<button class="bkev" id="'.$spo.'"><i class="icon long arrow up left"></i> Back to Events</button> <a class="bktosport" href="#"><i class="icon long arrow up left"></i> Back to Sports</a>';
	$ck = "SELECT is_active, feat FROM af_inplay_bet_events WHERE bet_event_id = '$edi'";
	$rck = mysqli_query($conn, $ck);
	$cck = $rck->fetch_assoc();
	//if active
	if ($cck['is_active'] == 1) {?> 
	 <div class="isac">Is Active ? <a href="#" class="ayes">Yes</a></div>
	<?php } else {?>
	 <div class="isac">Is Active ? <a href="#" class="ayes">No</a></div>	
	<?php }
	//if featured
	if ($cck['feat'] == 1) {?> 
	 <div class="isfeat" id="isfeat">Is featured ? <a href="#" class="afyes">Yes</a></div>
	<?php } else {?>
	 <div class="isfeat" id="isfeat">Is featured ? <a href="#" class="afyes">No</a></div>	
	<?php }
	
	
	
	
	
	echo '<div style="padding: 3px 10px; margin-top: 20px; border: 1px solid #1cffb1; margin-right: 10px; border-radius: 3px;" class="editv spk-'.$spo.'" id="ed-'.$edi.'"><i class="icon antena"></i> Refresh</div>';
	
	$edix = "SELECT e.bet_event_id, e.sname, e.cc, ec.bet_event_cat_id,ec.bet_event_cat_name,e.deadline, e.spid, e.event_name, e.bet_event_name, o.o_sort, o.bet_option_id, o.bet_option_name, o.bet_option_odd
            FROM af_inplay_bet_events e
            JOIN af_inplay_bet_events_cats ec ON e.bet_event_id = ec.bet_event_id
            JOIN af_inplay_bet_options o ON ec.bet_event_cat_id = o.bet_event_cat_id WHERE e.bet_event_id = $edi ORDER BY ec.c_sort ASC";
    $edis = $conn->query($edix);
	//$result = mysqli_query($conn, $xr);
	  $cwc = $edis->fetch_assoc();
      echo '<div class="ediview">';
	  echo '<div class="succm"></div>';?></br></br>
	  
	  
	  <?php if(!empty($cwc['bet_event_id'])){?>
	  
	 <div class="mainedit" id="<?php echo $cwc['bet_event_id'];?>">
	 <h2><?php echo $cwc['sname'];?></h2>
	 
	 <input class="mevente" placeholder="Event Name" value="<?php echo $cwc['bet_event_name'];?>"> 
	 <input class="meventd" placeholder="Deadline" value="<?php $dt = $cwc['deadline']; echo date ("Y-m-d H:i", $dt);?>"> 
	  <input class="meventl" placeholder="League Name" value="<?php echo $cwc['event_name'];?>"> 
	  <input class="meventc" placeholder="Country Name" value="<?php echo $cwc['cc'];?>"> 
	 </div>
	  <?php } else {?>
	  <?php $edip = "SELECT * FROM af_inplay_bet_events WHERE bet_event_id = $edi";
       $edif = $conn->query($edip);
	   $cpa = $edif->fetch_assoc();
	?>
	  <div class="mainedit" id="<?php echo $cpa['bet_event_id'];?>">
	 <h2><?php echo $cpa['sname'];?></h2>
	 
	 <input class="mevente" placeholder="Event Name" value="<?php echo $cpa['bet_event_name'];?>"> 
	 <input class="meventd" placeholder="Deadline" value="<?php $dt = $cpa['deadline']; echo date ("Y-m-d H:i", $dt);?>"> 
	  <input class="meventl" placeholder="League Name" value="<?php echo $cpa['event_name'];?>"> 
	  <input class="meventc" placeholder="Country Name" value="<?php echo $cpa['cc'];?>"> 
	 </div></br>
	 <h4> No active bet category or options</h4>
	 
	  <?php } ?>
	 
	 
	 
	 
	 
	 
	 
	<?php if ($edis->num_rows > 0) { 
		while($row = $edis->fetch_assoc()) {
			if($row['bet_event_cat_name'] !=$ename){?>
			</br></br>
			
			
		    <i class="icon fire"></i>
			<input type="text/css" class="catedit" id="<?php echo $row['bet_event_cat_id'];?>" value="<?php echo $row['bet_event_cat_name'];?> ">
			
			<div class="delcat" id="<?php echo $row['bet_event_cat_id'];?>"> <i class="icon trash"></i> Cat</div> 
			<div class="addop" id="<?php echo $row['bet_event_cat_id'];?>"> <i class="icon plus"></i> Add Options</div>

			<?php } ?>
			
			<?php $ename = $row['bet_event_cat_name'];?>
			
			<div class="optiondiv">
			

			<input type="text/css" class="bename" value="<?php echo $row['bet_option_name'];?>" id="optodd-<?php echo $row['bet_option_id'];?>">
			
			<input type="text/css" class="optionna" value=" <?php echo $row['bet_option_odd'];?>" id="optodd-<?php echo $row['bet_option_id'];?>"> <span class="odel" id="<?php echo $row['bet_option_id'];?>"><i class="icon trash alt"></i></span>
			
			
			</div>
			
			
		 <?php } ?>
		 
		<div class="addcat" id="catf-<?php echo $edi;?>"> Add New Category</div>
	<?php } else {?>
	<div class="addcat" id="catf-<?php echo $edi;?>"> Add New Category</div>
		
	<?php }
	
	echo '</div>'; echo '</br>';
     
	
	
}


//esearch
if(isset($_POST['method']) && $_POST['method'] == 'esearch'){
	$es = $_POST['es'];
	$idt = $_POST['idt'];
	echo '</br></br>';
	
	$equery = "SELECT * FROM af_pre_bet_events WHERE bet_event_name LIKE '%".$es."%'";
    $result = $conn->query($equery);
      echo '<div class="preevents xp" style="background: #fae6ff;">';
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if($row['event_name'] !=$ename){?>
			</br>
			<h3>
		    <i class="icon fire"></i> <?php echo $row['event_name'];?> [<?php echo $row['cc'];?>][<?php echo $row['sname'];?>]
			</h3> 

			<?php } ?>
			
			<?php $ename = $row['event_name'];?>
			<div class="evsna">			
			<a class="yoyo basic icon fluid button primary" style="margin:0; padding:0; color:#000;background-color:#fff;display: inline-flex;"> <i class="icon cd"></i> </a> <?php echo $row['bet_event_name'];?> (<?php echo $row['cc'];?>) [<?php $dt = $row['deadline']; echo date ("m-d H:i", $dt);?>]
			<div class="editv spk-<?php echo $row['spid'];?>" id="ed-<?php echo $row['bet_event_id'];?>"> <i class="icon underline"></i> Edit/View</div>
			</div>
			
			
		 <?php }
	} else {
		echo 'No events found with this search term';
		
	};
	
	echo '</div>';
	
	
}




//New event adding full view
//for full event view

if(isset($_POST['method']) && $_POST['method'] == 'newedi'){
	$edi = $_POST['edi'];
	$spo = $_POST['spo'];
	echo '<button class="bkev" id="'.$spo.'"><i class="icon long arrow up left"></i> Back to Events</button> <a class="bktosport" href="#"><i class="icon long arrow up left"></i> Back to Sports</a>';
	$ck = "SELECT is_active, feat FROM af_inplay_bet_events WHERE bet_event_id = '$edi'";
	$rck = mysqli_query($conn, $ck);
	$cck = $rck->fetch_assoc();
	//if active
	if ($cck['is_active'] == 1) {?> 
	 <div class="isac">Is Active ? <a href="#" class="ayes">Yes</a></div>
	<?php } else {?>
	 <div class="isac">Is Active ? <a href="#" class="ayes">No</a></div>	
	<?php }
	//if featured
	if ($cck['feat'] == 1) {?> 
	 <div class="isfeat" id="isfeat">Is featured ? <a href="#" class="afyes">Yes</a></div>
	<?php } else {?>
	 <div class="isfeat" id="isfeat">Is featured ? <a href="#" class="afyes">No</a></div>	
	<?php }
	
	
	
	
	
	echo '<div style="padding: 3px 10px; margin-top: 20px; border: 1px solid #1cffb1; margin-right: 10px; border-radius: 3px;" class="editv spk-'.$spo.'" id="ed-'.$edi.'"><i class="icon antena"></i> Refresh</div>';
	
	$edix = "SELECT * FROM af_inplay_bet_events WHERE bet_event_id = $edi";
    $edis = $conn->query($edix);
	//$result = mysqli_query($conn, $xr);
	  $cwc = $edis->fetch_assoc();
      echo '<div class="ediview">';
	  echo '<div class="succm"></div>';?></br></br>
	  
	 <div class="mainedit" id="<?php echo $cwc['bet_event_id'];?>">
	 <h2><?php echo $cwc['sname'];?></h2>
	 
	 <span class="mevente" contenteditable="true"> 
	 <?php echo $cwc['bet_event_name'];?></span>
	 
	 <span class="meventd" contenteditable="true">
	 <?php $dt = $cwc['deadline']; echo date ("Y-m-d H:i", $dt);?></span>
	 
	 <span class="meventl" contenteditable="true">
	 <?php echo $cwc['event_name'];?></span>
	 
	 <span class="meventc" contenteditable="true">
	  <?php echo $cwc['cc'];?></span>
	 
	 
	 
	 
	 
	 
	 
	 </div>
	 
	 
	 
	 
	 
	 
	 
	<?php if ($edis->num_rows > 0) { 
		while($row = $edis->fetch_assoc()) {
			if($row['bet_event_cat_name'] !=$ename){?>
			</br></br>
			
			
		    <i class="icon fire"></i>
			<input type="text/css" class="catedit" id="<?php echo $row['bet_event_cat_id'];?>" value="<?php echo $row['bet_event_cat_name'];?> ">
			
			<div class="delcat" id="<?php echo $row['bet_event_cat_id'];?>"> <i class="icon trash"></i> Cat</div> 
			<div class="addop" id="<?php echo $row['bet_event_cat_id'];?>"> <i class="icon plus"></i> Add Options</div>

			<?php } ?>
			
			<?php $ename = $row['bet_event_cat_name'];?>
			
			<div class="optiondiv">
			
			<span class="bename" id="<?php echo $row['bet_option_id'];?>" contenteditable="true"> <?php echo $row['bet_option_name'];?></span> <input type="text/css" class="optionna" value=" <?php echo $row['bet_option_odd'];?>" id="optodd-<?php echo $row['bet_option_id'];?>"> <span class="odel" id="<?php echo $row['bet_option_id'];?>"><i class="icon trash alt"></i></span>
			
			
			</div>
			
			
		 <?php } ?>
		 
		<div class="addcat" id="catf-<?php echo $edi;?>"> Add New Category</div>
	<?php } else {?>
	<div class="addcat" id="catf-<?php echo $edi;?>"> Add New Category</div>
		
	<?php }
	
	echo '</div>'; echo '</br>';
     
	
	
}








//edit option name
if(isset($_POST['method']) && $_POST['method'] == 'bename'){
	$beid = $_POST['beid'];
	$bename = $_POST['bename'];
	
	$edibe = "UPDATE af_inplay_bet_options SET bet_option_name = '$bename' WHERE bet_option_id = '$beid'";
    $be = $conn->query($edibe);
	
}



//update option odd
if(isset($_POST['method']) && $_POST['method'] == 'optionna'){
	$osid = $_POST['osid'];
	$odv = $_POST['odv'];
	
	$edi = "UPDATE af_inplay_bet_options SET bet_option_odd = $odv WHERE bet_option_id = $osid";
    $suc = $conn->query($edi);
	
}

//delete options
if(isset($_POST['method']) && $_POST['method'] == 'odelete'){
	$dids = $_POST['odil'];
	
	$did = "DELETE FROM af_inplay_bet_options WHERE bet_option_id = $dids";
    $sucd = $conn->query($did);
	
}



//delete category
if(isset($_POST['method']) && $_POST['method'] == 'cdelete'){
	$cdil = $_POST['cdil'];
	
	$cdik = "DELETE FROM af_inplay_bet_events_cats WHERE bet_event_cat_id = $cdil";
	$cdik = $conn->query($cdik);
	$cdil = "DELETE FROM af_inplay_bet_options WHERE bet_event_cat_id = $cdil";
    $cdid = $conn->query($cdil);
	
}

//edit bet_event_name
if(isset($_POST['method']) && $_POST['method'] == 'mevente'){
	$emi = $_POST['emi'];
	$emn = $_POST['emn'];
	$une = "UPDATE af_inplay_bet_events SET bet_event_name = '$emn' WHERE bet_event_id = '$emi'";
    $cte = $conn->query($une);
}


//is active?
if(isset($_POST['method']) && $_POST['method'] == 'isact'){
	$isa = $_POST['isa'];
	$iname = $_POST['iname'];
	
	if($isa == 'Yes'){
	$uni = "UPDATE af_inplay_bet_events SET is_active = '0' WHERE bet_event_id = '$iname'";
	$cti = $conn->query($uni);
	$uno = "UPDATE af_inplay_bet_events_cats SET yn = '1' WHERE bet_event_id = '$iname'";
	$cto = $conn->query($uno);
	
	} else if($isa == 'No'){
	$uni = "UPDATE af_inplay_bet_events SET is_active = '1' WHERE bet_event_id = '$iname'";
	$cti = $conn->query($uni);
    $unn = "UPDATE af_inplay_bet_events_cats SET yn = NULL WHERE bet_event_id = '$iname'";
	$ctn = $conn->query($unn);	
	} else {
		echo 'No actions completed';
	}
    
}

//is feat?
if(isset($_POST['method']) && $_POST['method'] == 'isfeat'){
	$fsa = $_POST['fsa'];
	$fname = $_POST['fname'];
	
	if($fsa == 'Yes'){
	$unf = "UPDATE af_inplay_bet_events SET feat = '0' WHERE bet_event_id = '$fname'";
	$ctf = $conn->query($unf);
	} else if($fsa == 'No'){
	$unf = "UPDATE af_inplay_bet_events SET feat = '1' WHERE bet_event_id = '$fname'";
	$ctf = $conn->query($unf);	
	} else {
		echo 'No actions completed';
	}
    
}

//edit deadline
if(isset($_POST['method']) && $_POST['method'] == 'meventd'){
	$emi = $_POST['emi'];
	$emn = $_POST['emn'];
	$deadline = strtotime($emn);
	$une = "UPDATE af_inplay_bet_events SET deadline = '$deadline' WHERE bet_event_id = '$emi'";
    $cte = $conn->query($une);
	 if ($conn->query($cte) === TRUE) {
		echo 'success';
			 } else {
				 //echo "Could not save";
				 echo "Error: " . $cte . "<br>" . $conn->error;	
				 
			 }
}

//edit event name
if(isset($_POST['method']) && $_POST['method'] == 'meventl'){
	$emi = $_POST['emi'];
	$emn = $_POST['emn'];
	$une = "UPDATE af_inplay_bet_events SET event_name = '$emn' WHERE bet_event_id = '$emi'";
    $cte = $conn->query($une);
}

//edit cc
if(isset($_POST['method']) && $_POST['method'] == 'meventc'){
	$emi = $_POST['emi'];
	$emn = $_POST['emn'];
	$une = "UPDATE af_inplay_bet_events SET cc = '$emn' WHERE bet_event_id = '$emi'";
    $cte = $conn->query($une);
}


//edit category
if(isset($_POST['method']) && $_POST['method'] == 'catedit'){
	$ctid = $_POST['ctid'];
	$ctname = $_POST['ctname'];
	
	$edicat = "UPDATE af_inplay_bet_events_cats SET bet_event_cat_name = '$ctname' WHERE bet_event_cat_id = '$ctid'";
    $ct = $conn->query($edicat);
	
}



//Adding New events
if(isset($_POST['method']) && $_POST['method'] == 'addingev'){
	$beventid = rand(900000000, 999999999);
	$spid = $_POST['spid'];
	$spname = $_POST['spname'];
	$ligname = $_POST['ligname'];
	$beventname = $_POST['beventname'];
	$edate = $_POST['edate'];
	$deadline = strtotime($edate);
	$ecc = $_POST['ecc'];
	$xxr = "SELECT DISTINCT event_id FROM af_inplay_bet_events WHERE event_name = '$ligname'";
	$xresult = mysqli_query($conn, $xxr);
	if ($xresult->num_rows > 0) {
	$xc_row = $xresult->fetch_assoc();
	$eventid = $xc_row['event_id'];
	} else {
	 $eventid = rand(500000, 900000);
	}
	
	 $evin = "INSERT INTO af_inplay_bet_events (bet_event_id, b_sort, bet_event_name, deadline, is_active, feat, event_id, event_name, spid, cc, sname) VALUES('$beventid', '0', '$beventname', '$deadline', '2', '0', '$eventid', '$ligname', '$spid', '$ecc', '$spname')";
	 if ($conn->query($evin) === TRUE) {
		echo trim($beventid);
			 } else {
				 //echo "Could not save";
				 echo "Error: " . $evin . "<br>" . $conn->error;	
				 
			 }
	
}



//insert category
if(isset($_POST['method']) && $_POST['method'] == 'addcat'){
	$catn = $_POST['catn'];
	$es = $_POST['evid'];
	$evid = preg_replace( "/\r|\n/", "", $es);
	$catid = rand(100000, 999999);
	$c_count = "SELECT count(*) as count FROM af_inplay_bet_events_cats WHERE bet_event_id = '$evid'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $c_sort = $c_row['count']+1;
                            } else {
                                $c_sort = 1;
                            }
	 $catin = "INSERT INTO af_inplay_bet_events_cats (bet_event_cat_id, c_sort, bet_event_cat_name, bet_event_id) VALUES('$catid', '$c_sort', '$catn', '$evid')";
	 if ($conn->query($catin) === TRUE) {
		echo trim($catid);
			 } else {
				 //echo "Could not save";
				 echo "Error: " . $catin . "<br>" . $conn->error;	
				 
			 }
	
}





//insert bet options
if(isset($_POST['method']) && $_POST['method'] == 'soptions'){
	$opname = $_POST['opname'];
	$optionod = $_POST['optionod'];
	$rcat = $_POST['catid'];
	$catid = preg_replace( "/\r|\n/", "", $rcat);
	$opid = rand(1000000, 9999999);
	$c_count = "SELECT count(*) as count FROM af_inplay_bet_options WHERE bet_event_cat_id = '$catid'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $o_sort = $c_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
	 $opin = "INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status,  bet_event_cat_id) VALUES('$opid', '$o_sort', '$opname', '$optionod', 'awaiting', '$catid')";
	 if ($conn->query($opin) === TRUE) {
		echo trim($catid);
			 } else {
				 //echo "Could not save";
				 echo "Error: " . $opin . "<br>" . $conn->error;	
				 
			 }
	
}



//add new bet options
if(isset($_POST['method']) && $_POST['method'] == 'addopp'){
	$opname = 'Option Name..';
	$optionod = '0.00';
	$okid = $_POST['okid'];
	$opid = rand(1000000, 9999999);
	$c_count = "SELECT count(*) as count FROM af_inplay_bet_options WHERE bet_event_cat_id = '$okid'";
			 $c_result = $conn->query($c_count);
                            if ($c_result->num_rows > 0) {
                                $c_row = $c_result->fetch_assoc();
                                $o_sort = $c_row['count']+1;
                            } else {
                                $o_sort = 1;
                            }
	 $opin = "INSERT INTO af_inplay_bet_options (bet_option_id, o_sort, bet_option_name, bet_option_odd, status,  bet_event_cat_id) VALUES('$opid', '$o_sort', '$opname', '$optionod', 'awaiting', '$okid')";
	 if ($conn->query($opin) === TRUE) {
		//echo trim($catid);
			 } else {
				 //echo "Could not save";
				 echo "Error: " . $opin . "<br>" . $conn->error;	
				 
			 }
	
}





	
$sql_pre = "SELECT * FROM af_inplay_bet_events LIMIT ".$diff."";
$unsubmitted_slip=mysqli_query($conn,$query);
$result = mysqli_fetch_assoc($unsubmitted_slip);

$spid = NULL;
$deadline = NULL;
$eventName = NULL;
$betEventName = NULL;
$betOptionNames = '';
$betOptionOdds = '';
$done = false;

foreach ($result->aResults as $record) {
	
  echo 'hello';
}
?>