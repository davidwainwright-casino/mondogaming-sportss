<?php

include_once("../db.php");
include_once('../../init.php');

$usid = $_POST['usid'];
 if(isset($_POST['method']) && $_POST['method'] == 'allplayers') {?>
      <div class="headtit"><?= Lang::$word->AFF_LIST_OF_YOUR_AFFILIATES; ?></div>
	  <p><?= Lang::$word->AFF_TABLE_CC_DESC; ?></p>
	  <input type="hidden" class="cfvalue" value="50">
	  
	  <div style="overflow-x:auto;">
 	   <table id="customers" class="allplaytab">
	   <tr>
	   <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_NO; ?></th>
	   <th><?= Lang::$word->AFF_TABLE_CC; ?></th>
	   <th><?= Lang::$word->AFF_TABLE_LAST_NAME; ?></th>
	   <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_JOINED; ?></th>
	   
	   </tr>
	   <?php $query = "SELECT id,fname,lname,created,country FROM users WHERE id <> '$usid' AND afid = '$usid' ORDER BY created DESC LIMIT 50";
$result = mysqli_query($conn, $query);
  $i=1;
  if ($result->num_rows > 0) {
   while($row = $result->fetch_assoc()) {?>
 <tr>
  <td><?php echo $i++;?></td>
  <td><?php $cc = $row['country']; if(!empty($cc)){ echo $cc;}else{ echo 'None';};?></td>
  <td><?php echo $row['lname'];?></td>
  <td> 
  <?php $crt = $row['created'];
  $current = strtotime(date("Y-m-d"));
  $date    = strtotime($crt);
  $datediff = $date - $current;
  $difference = floor($datediff/(60*60*24));
 if($difference==0){
    echo 'Today';
 }else{
	 echo $crt;
 }?>
</td>
</tr>
<?php }
  } else {
	 echo '<div style="padding:10px">'.Lang::$word->WITHDRAWAL_NO_ACTIVE_RECORDS_FOUND.'</div>';
	 die();
     }
	 
echo '</table></div>';	 
  if ($result->num_rows >= 1) {
	echo '<div id="loadaf" class="afload">'.Lang::$word->WITHDRAWAL_LOAD_MORE.'</div>';
  } else {
	  echo '<div style="padding:10px">'.Lang::$word->AFF_NO_MORE_RECORDS.'</div>';
  }
  
} 




 // Agent add balance to player
 if(isset($_POST['method']) && $_POST['method'] == 'mydash') {
	 $que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}

	$aids = implode (", ", $agent_ids);
	$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE()- INTERVAL 1 DAY THEN 1 END) AS today, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 7 DAY THEN 1 END) AS thisweek, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 30 DAY THEN 1 END) AS thismonth, COUNT(id) AS allsa FROM users WHERE said=$usid"));



	$getbal = mysqli_query($conn, "SELECT country,stripe_cus,chips FROM users WHERE id=$usid");
	$getb = mysqli_fetch_assoc($getbal);
	$cc = $getb['country'];
	$shbal = $getb['chips'];
	$currency = $getb['stripe_cus'];


	$ifyes =  mysqli_query($conn, "SELECT id FROM sh_sf_withdraws WHERE status = 'Pending' AND cc='$cc' OR status = 'Processing' AND aprov_id=$usid AND cc='$cc'");
	$ttcount = $ifyes->num_rows;
	$wthdep= mysqli_fetch_assoc(mysqli_query($conn,"SELECT depo_comm,wth_comm FROM risk_management"));
	$transfer_com = $wthdep['depo_comm'];
	$withdraw_com = $wthdep['wth_comm'];
	$netcom = $wthdep['depo_comm']+$wthdep['wth_comm'];

	?>
<h2><?= Lang::$word->AFF_TRANSFERDASHBOARD; ?></h2>

</br></br>
<div id="ccajax">

<div class="availbalebb"><?= Lang::$word->AFF_AVAILABLE_BALANCE_FOR_EXCHANGE; ?> <b class="abchp"><?php echo $shbal;echo ' ';echo $currency;?> </b></div>


</br>

</br>
<div id="showtrhistory">
<h5><?= Lang::$word->AFF_TRANSFER_FUNDS_FROM_YOUR_BALANCE; ?></h5>
<div class="warnintxt"><?= Lang::$word->AFF_TRANSFER_FUNDS_FROM_YOUR_BALANCE_DESC; ?></div>



<input type="text" class="searchfund" id="sendfunds" placeholder="<?= Lang::$word->AFF_INPUT_RECIPIENT_EMAIL; ?>"><i id="fico" class="find icon"></i>
</br>


<div class="sacur">
<input id="putcash" class="tramount" value="" placeholder="<?= Lang::$word->AFF_INPUT_AMOUNT_IN; ?> <?php echo $currency;?>">
</div>

<div id="showavl"></div>


</div>
</br>

<div id="quathistory">
<div class="txnhistory"><?= Lang::$word->AFF_TRANSFER_HISTORY; ?></div>
  <?php
   $usid = $_POST['usid'];
   $query = "SELECT * FROM sh_sf_transfers WHERE user_id=$usid ORDER BY date DESC LIMIT 50";
   $result = mysqli_query($conn, $query);
   echo '<input type="hidden" class="cfvalue" value="50">';
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
     $id = $row['id'];
	 $amount = $row['amount'];
     $ref = $row['transaction_id'];
     $date = date ("m-d-y H:i", $row['date']);
	 $sendto = $row['send_to'];
	 ?>

    <ul class="deptshow">
	 <li class="idto-<?php echo $id;?>">
	  <div class="ccredit"><?= Lang::$word->AFF_A2A_TRANSFER; ?>: <span class="deptright"><?php echo $amount;?></span></div>
	  <div class="ccredit"> <?= Lang::$word->AFF_SENT_TO; ?>: <span class="deptright"><?php echo $sendto;?></span></div>
	  <div class="ccredit"> <?= Lang::$word->AFF_MTS_REF; ?>: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> <?= Lang::$word->AFF_MTS_DATE; ?>: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>


<?php }
  } else {
	 echo '<div style="padding:10px">'.Lang::$word->WITHDRAWAL_NO_ACTIVE_RECORDS_FOUND.'</div>';
  }

  if ($result->num_rows > 1) {
	echo '<div id="lrem" class="tload">'.Lang::$word->WITHDRAWAL_LOAD_MORE.'</div>';
  }?>

</div>

</div>
</br></br>


















<?php
}

  //For all players load more
  else if(isset($_POST['method']) && $_POST['method'] == 'allpmore') {
  $rc = $_POST['rc'];  
  $query = "SELECT id,fname,lname,created,country FROM users WHERE id <> '$usid' AND afid = '$usid' ORDER BY created DESC LIMIT $rc, 50";
$result = mysqli_query($conn, $query);
  $i= $rc+1;
  echo '<div style="overflow-x:auto;"><table id="customers" class="allplaytab">';
  if ($result->num_rows > 0) {
   while($row = $result->fetch_assoc()) {?>
 <tr>
  <td style="min-width:29px"><?php echo $i++;?></td>
  <td><?php $cc = $row['country']; if(!empty($cc)){ echo $cc;}else{ echo 'Pending';};?></td>
  <td><?php echo $row['lname'];?></td>
  <td> 
  <?php $crt = $row['created'];
  $current = strtotime(date("Y-m-d"));
  $date    = strtotime($crt);
  $datediff = $date - $current;
  $difference = floor($datediff/(60*60*24));
 if($difference==0){
    echo 'Today';
 }else{
	 echo $crt;
 }?></td></tr>
	 
	 
   <?php } 
   }; echo '</table></div>';
   if ($result->num_rows >= 1) {
	echo '<div id="loadaf" class="afload">'.Lang::$word->WITHDRAWAL_LOAD_MORE.'</div>';
  } else {
	  echo '<div style="padding:10px">'.Lang::$word->AFF_NO_MORE_RECORDS.'</div>';
  }
 }   
 
 
 
   
   
   //for tickets highlights
   else if(isset($_POST['method']) && $_POST['method'] == 'playersslips') {
	   $usid = $_POST['usid'];
	   ?>
	 <div class="tickethigh">
	 <span class="tctext">
	 <?= Lang::$word->AFF_YOUR_AFFILIATES_LATEST_BET; ?>
	 </span></br>
	 <p class="tcinfo">
	 <?= Lang::$word->AFF_NO_MORE_BET_AFF_DESC; ?>
	 </p>
	 </div>
	 
	 <div class="tcname"><?= Lang::$word->AFF_LATEST_IN_PLAY_TICKETS; ?></div>
	 <div style="overflow-x:auto;"> 
	 <table id="customers" class="allplaytab">
	   <tr>
	   <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_NO; ?></th>
	   <th><?= Lang::$word->AFF_STAKE; ?></th>
	   <th><?= Lang::$word->AFF_EVENT_NAME; ?></th>
	   <th><?= Lang::$word->AFF_CATEGORY; ?></th>
	   <th><?= Lang::$word->AFF_SELECTION; ?></th>
	   <th style="min-width:50px"><?= Lang::$word->AFF_DATE; ?></th>
	   </tr>
	 <?php $query = "SELECT * FROM sh_sf_tickets_history WHERE user_id <> '$usid' AND bet_info IS NULL AND aid = '$usid' ORDER BY date DESC LIMIT 10";
$result = mysqli_query($conn, $query);
  if ($result->num_rows > 0) {
	  $i= 1;
   while($row = $result->fetch_assoc()) {?>
       <tr>
	    <td><?php echo $i++;?></td>
		<td><?php echo $row['stake'];?></td>
		<td><?php echo $row['event_name'];?></td>
		<td><?php echo $row['cat_name'];?></td>
		<td><?php echo $row['bet_option_name'];?></td>
		<td><?php $dt = $row['date']; echo date('d-m', $dt); ?></td>
        </tr>
   <?php }
     }
	 echo '</table></div>';?>
	 
	 
	 
	 
	 <div class="bdtp"><?= Lang::$word->AFF_DIVIDE; ?></div>
	 <div class="tcname"><?= Lang::$word->AFF_LATEST_PRE_MATCH_TICKETS; ?></div>
	 <div style="overflow-x:auto;">
	 <table id="customers" class="allplaytab">
	   <tr>
	   <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_NO; ?></th>
	   <th><?= Lang::$word->AFF_STAKE; ?></th>
	   <th><?= Lang::$word->AFF_EVENT_NAME; ?></th>
	   <th><?= Lang::$word->AFF_CATEGORY; ?></th>
	   <th><?= Lang::$word->AFF_SELECTION; ?></th>
	   <th style="min-width:50px"><?= Lang::$word->AFF_DATE; ?></th>
	   </tr>
	 <?php $query = "SELECT * FROM sh_sf_slips_history WHERE user_id <> '$usid' AND bet_info IS NULL AND aid = '$usid' ORDER BY date DESC LIMIT 10";
$result = mysqli_query($conn, $query);
  if ($result->num_rows > 0) {
	  $i= 1;
   while($row = $result->fetch_assoc()) {?>
       <tr>
	    <td><?php echo $i++;?></td>
		<td><?php echo $row['stake'];?></td>
		<td><?php echo $row['event_name'];?></td>
		<td><?php echo $row['cat_name'];?></td>
		<td><?php echo $row['bet_option_name'];?></td>
		<td><?php $dt = $row['date']; echo date('d-m', $dt); ?></td>
        </tr>
   <?php }
     }
	 echo '</table></div>';
 } 
 
 
 
 
 
 
 
 
 
 
 
 else if(isset($_POST['method']) && $_POST['method'] == 'performances') {
	 
	 //views/clicks stats
	 $stats = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN day >= ( CURDATE() - INTERVAL 1 DAY ) THEN uniquevisitors END) AS duniquevisitors, SUM(CASE WHEN day >= ( CURDATE() - INTERVAL 1 DAY ) THEN pageviews END) AS dpageviews, SUM(CASE WHEN day >= ( CURDATE() - INTERVAL 7 DAY ) THEN uniquevisitors END) AS uniquevisitors, SUM(CASE WHEN day >= ( CURDATE() - INTERVAL 7 DAY ) THEN pageviews END) AS pageviews, SUM(CASE WHEN day >= ( CURDATE() - INTERVAL 30 DAY ) THEN uniquevisitors END) AS muniquevisitors, SUM(CASE WHEN day >= ( CURDATE() - INTERVAL 30 DAY ) THEN pageviews END) AS mpageviews FROM stats WHERE agid = $usid"));
	 //daily
	 $dclicks = $stats['duniquevisitors'];
	 $dpageviews = $stats['dpageviews'];
	 //weekly
	 $clicks = $stats['uniquevisitors'];
	 $pageviews = $stats['pageviews'];
	 //monthly
	 $mclicks = $stats['muniquevisitors'];
	 $mpageviews = $stats['mpageviews'];
	 
	 //registered users numbers
	 $creg = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= ( CURDATE() - INTERVAL 1 DAY ) THEN 1 END) AS dtweek, COUNT(CASE WHEN active = 'y' AND created >= ( CURDATE() - INTERVAL 1 DAY ) THEN 1 END) AS dtactive, COUNT(CASE WHEN created >= ( CURDATE() - INTERVAL 7 DAY ) THEN 1 END) AS tweek, COUNT(CASE WHEN active = 'y' AND created >= ( CURDATE() - INTERVAL 7 DAY ) THEN 1 END) AS tactive, COUNT(CASE WHEN created >= ( CURDATE() - INTERVAL 30 DAY ) THEN 1 END) AS mtweek, COUNT(CASE WHEN active = 'y' AND created >= ( CURDATE() - INTERVAL 30 DAY ) THEN 1 END) AS mtactive FROM users WHERE afid = '$usid' AND id <> $usid"));
	 //daily
	 $dtotalreg = $creg['dtweek'];
	 $dtotalactive = $creg['dtactive'];
	 //weekly
	 $totalreg = $creg['tweek'];
	 $totalactive = $creg['tactive'];
	 //monthly
	 $mtotalreg = $creg['mtweek'];
	 $mtotalactive = $creg['mtactive'];
	 
	 
	 $svolume = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') >= ( CURDATE()- INTERVAL 1 DAY ) THEN stake END) AS dtsinplay, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') >= ( CURDATE()- INTERVAL 7 DAY ) THEN stake END) AS tsinplay, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') >= ( CURDATE()- INTERVAL 30 DAY ) THEN stake END) AS mtsinplay FROM sh_sf_tickets_history WHERE aid = $usid AND debit = 'chips'"));
	 
	 $pvolume = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') >= ( CURDATE()- INTERVAL 1 DAY ) THEN stake END) AS dtspre, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') >= ( CURDATE()- INTERVAL 7 DAY ) THEN stake END) AS tspre, SUM(CASE WHEN FROM_UNIXTIME(date,'%Y-%m-%d') >= ( CURDATE()- INTERVAL 30 DAY ) THEN stake END) AS mtspre FROM sh_sf_slips_history WHERE aid = $usid AND debit = 'chips'"));
	 //daily
	 $dtotal_stake = $svolume['dtsinplay']+$pvolume['dtspre'];
	 //weekly
	 $total_stake = $svolume['tsinplay']+$pvolume['tspre'];
	 //monthly
	 $mtotal_stake = $svolume['mtsinplay']+$pvolume['mtspre'];
	 
	 
	 
	 $earn = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN FROM_UNIXTIME(dt,'%Y-%m-%d') >= CURDATE()- INTERVAL 1 DAY THEN amt END) AS dweekearn, SUM(CASE WHEN FROM_UNIXTIME(dt,'%Y-%m-%d') >= CURDATE()- INTERVAL 7 DAY THEN amt END) AS weekearn, SUM(CASE WHEN FROM_UNIXTIME(dt,'%Y-%m-%d') >= CURDATE()- INTERVAL 30 DAY THEN amt END) AS mweekearn FROM sh_agents_credit_records WHERE agent_id=$usid"));
	 //daily
	 $dtotal_earning=$earn['dweekearn'];
	 //weekly
	 $total_earning=$earn['weekearn'];
	 //monthly
	 $mtotal_earning=$earn['mweekearn'];

	 ?>
 <div class="statwrapper"> 
 
 
 <h3> <?= Lang::$word->AFF_DAILY_24_HOURS_RECORDS; ?></h3>
   
<ul class="topdash unu stat">

   <li class="dashr"><?= Lang::$word->AFF_TOTAL_CLICKS; ?> <i title="This is unique clicks/visitors" class="icon question sign"></i> </br> <i class="icon radio checked"></i> <span class="afbb"><?php echo $dclicks;?></span></li>
   <li class="dashleft"><?= Lang::$word->AFF_TOTAL_PAGE_VIEWS; ?> <i title="One visitor can generate many page views" class="icon question sign"></i></br><i class="icon eye"></i> <span class="afbb"><?php echo $dpageviews;?></span></li>
   
   <li class="dashleft"><?= Lang::$word->AFF_REGISTERED; ?></br><i class="icon user alt"></i> <span class="afbb"><?php echo $dtotalreg;?></span></li>
   <li class="dashr af"><?= Lang::$word->AFF_ACTIVATED; ?></br> <i class="icon user alt"></i> <span class="afbb"><?php echo $dtotalactive;?></span></li>
   
   
   <li class="dashr af"><?= Lang::$word->AFF_STAKE_VOLUME; ?> <i title="Bet amount before or after settlement, it's just a volume bet not earnings" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb"><?php echo round($dtotal_stake,2);?></span></li>
   <li class="dashr"> <?= Lang::$word->AFF_APPROX_EARNINGS; ?> <i title="It's an approx. earnings only on chips betting, promo betting not counted" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb"><?php echo round($dtotal_earning,2);?></span></li>
   
</ul>




 
	<h3> <?= Lang::$word->AFF_WEEKLY_7_DAYS_RECORDS; ?></h3>
   
<ul class="topdash unu stat">

   <li class="dashr"><?= Lang::$word->AFF_TOTAL_CLICKS; ?> <i title="This is unique clicks/visitors" class="icon question sign"></i> </br> <i class="icon radio checked"></i> <span class="afbb"><?php echo $clicks;?></span></li>
   <li class="dashleft"><?= Lang::$word->AFF_TOTAL_PAGE_VIEWS; ?> <i title="One visitor can generate many page views" class="icon question sign"></i></br><i class="icon eye"></i> <span class="afbb"><?php echo $pageviews;?></span></li>
   
   <li class="dashleft"><?= Lang::$word->AFF_REGISTERED; ?></br><i class="icon user alt"></i> <span class="afbb"><?php echo $totalreg;?></span></li>
   <li class="dashr af"><?= Lang::$word->AFF_ACTIVATED; ?></br> <i class="icon user alt"></i> <span class="afbb"><?php echo $totalactive;?></span></li>
   
   
   <li class="dashr af"><?= Lang::$word->AFF_STAKE_VOLUME; ?> <i title="Bet amount before or after settlement, it's just a volume bet not earnings" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb"><?php echo round($total_stake,2);?></span></li>
   <li class="dashr"> <?= Lang::$word->Approx. Earnings; ?> <i title="It's an approx. earnings only on chips betting, promo betting not counted" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb"><?php echo round($total_earning,2);?></span></li>
   
</ul>




<h3> <?= Lang::$word->AFF_MONTHLY_30_DAYS_RECORDS; ?></h3>
   
<ul class="topdash unu stat">

   <li class="dashr"><?= Lang::$word->AFF_TOTAL_CLICKS; ?> <i title="This is unique clicks/visitors" class="icon question sign"></i> </br> <i class="icon radio checked"></i> <span class="afbb"><?php echo $mclicks;?></span></li>
   <li class="dashleft"><?= Lang::$word->AFF_TOTAL_PAGE_VIEWS; ?> <i title="One visitor can generate many page views" class="icon question sign"></i></br><i class="icon eye"></i> <span class="afbb"><?php echo $mpageviews;?></span></li>
   
   <li class="dashleft"><?= Lang::$word->AFF_REGISTERED; ?></br><i class="icon user alt"></i> <span class="afbb"><?php echo $mtotalreg;?></span></li>
   <li class="dashr af"><?= Lang::$word->AFF_ACTIVATED; ?></br> <i class="icon user alt"></i> <span class="afbb"><?php echo $mtotalactive;?></span></li>
   
   
   <li class="dashr af"><?= Lang::$word->AFF_STAKE_VOLUME; ?><i title="Bet amount before or after settlement, it's just a volume bet not earnings" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb"><?php echo round($mtotal_stake,2);?></span></li>
   <li class="dashr"> <?= Lang::$word->AFF_APPROX_EARNINGS; ?> <i title="It's an approx. earnings only on chips betting, promo betting not counted" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb"><?php echo round($mtotal_earning,2);?></span></li>
   
</ul>


</div>



	 
 <?php } 
 
 
 
 
 
 
 
 
 
 
 
 
 
 else if(isset($_POST['method']) && $_POST['method'] == 'joinaffiliates') {
 $site = mysqli_real_escape_string($conn, $_POST['mysite']);;
 $usid = $_POST['usid'];
 $tm = time();
 $ds = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$usid"));
 $fname = $ds['fname'];
 $lname = $ds['lname'];
 $fullname = $fname.' '.$lname;
 $email = $ds['email'];
 if(!empty($ds['country'])){
   $country = $ds['country'];
 }else{
   $country = 'No';
 }
 
 $ins = mysqli_query($conn,"INSERT INTO `sh_agent_request` (`uid`, `siteurl`, `fullname`, `email`, `country`, `ddate`) VALUES ($usid, '$site', '$fullname', '$email', '$country','$tm')");
 
 if(mysqli_affected_rows($conn) > 0){
	 echo mysqli_query($conn,"UPDATE `users` SET `type`='agent', `afid`= NULL, `said`= '4' WHERE id=$usid");
	 if(mysqli_affected_rows($conn) > 0){
		 echo '<div class="succag"><i class="icon check" style="background: #eb1515;border-radius: 50%;color: #fff;"></i> '.Lang::$word->AFF_CONGRATS_DESC.'</div>';
	 }else{
		echo Lang::$word->AFF_UNFORT_DESC;;
	 }
 }else{
	 echo Lang::$word->AFF_UNFORT_DESC;;
 }
 
 
 
 
 
 
 
 
 }
 
 
 
 
 
 
 
 
 //for blocked players
 else if(isset($_POST['method']) && $_POST['method'] == 'blockedplayers') {?>
	 
	 <div class="tickethigh bloc">
	 <span class="tctext bloc">
	 <?= Lang::$word->AFF_INACTIVE_AFFILIATES; ?>
	 </span></br>
	 <p class="tcinfo bloc">
	 <?= Lang::$word->AFF_INACTIVE_AFFILIATES_DESC; ?>
	 </p>
	 </div>
	 
	 
	  
	   <?php $query = "SELECT id,fname,lname,created,country, active FROM users WHERE id <> '$usid' AND active <> 'y' AND afid = '$usid' ORDER BY created DESC LIMIT 50";
$result = mysqli_query($conn, $query);
  $i=1;
  if ($result->num_rows > 0) {?>
  <div style="overflow-x:auto;">
 	   <table id="customers" class="allplaytab">
	   <tr>
	   <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_NO; ?></th>
	   <th><?= Lang::$word->AFF_TABLE_CC; ?></th>
	   <th><?= Lang::$word->AFF_TABLE_LAST_NAME; ?></th>
	   <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_JOINED; ?></th>
	   <th><?= Lang::$word->AFF_STATUS; ?></th>
	   </tr>
	   <?php
   while($row = $result->fetch_assoc()) {?>
 <tr>
  <td><?php echo $i++;?></td>
  <td><?php $cc = $row['country']; if(!empty($cc)){ echo $cc;}else{ echo 'None';};?></td>
  <td><?php echo $row['lname'];?></td>
  <td> 
  <?php $crt = $row['created'];
  $current = strtotime(date("Y-m-d"));
  $date    = strtotime($crt);
  $datediff = $date - $current;
  $difference = floor($datediff/(60*60*24));
 if($difference==0){
    echo 'Today';
 }else{
	 echo $crt;
 }?>
  </td>
  <td> 
  <?php $stu = $row['active']; if($stu == 'n'){ echo Lang::$word->AFF_INACTIVE; } else if($stu == 't'){ echo Lang::$word->AFF_PENDING;} elseif($stu == 'b'){ echo Lang::$word->AFF_BANNED;}?>
  
  </td>
  </tr>
<?php } ?>
	   
 </table>
</div>
  <?php } else {
	  echo '<div style="font-size:18px;padding:10px">'.Lang::$word->AFF_NO_INACTIVE_PLAYERS_FOUND.'</div>';
  }
}



//tools and marketing

 else if(isset($_POST['method']) && $_POST['method'] == 'toolsmarketing') {
	 $afid = $_POST['usid'];
	 ?>
	 
	<div class="tickethigh bloc">
	 <span class="tctext tools">
	 <?= Lang::$word->AFF_BACK_TOOLS; ?>
	 </span></br>
	 <p class="tcinfo bloc">
     <?= Lang::$word->AFF_TOOLS_AND_MARKETING_DESC; ?>
	 </p>
	 </div>
	 
	 <div class="afidwrapper">
	 <span class="totcunt left"><?= Lang::$word->AFF_UNIQUE_AFFILIATE_ID; ?></span>
	 <span class="totcunt right">
	 affc<?php echo $afid;?>in
	 <?php //$ido = 'affc'.$afid.'in';$numbers = preg_replace('/[^0-9]/', '', $ido);echo $numbers;?>
	 </span>
</div>

<div class="bdtp">divide</div>


<div class="divcpy">
<div class="dftcpy">
<?= Lang::$word->AFF_UNIQUE_DESC; ?>
</div>
<input type="text" value="https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in" id="myInput">

<div class="tooltip">
<button onclick="myFunction()" id="affbtn">
  <span class="tooltiptext" id="myTooltip">https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in</span>
  Copy Link
  </button>
</div>
</div>

<div class="bdtp"><?= Lang::$word->AFF_DIVIDE; ?></div>
<div class="tcname"><?= Lang::$word->AFF_IFRAME_PROMOTION_IMAGES; ?></div>
<p class="frameinfo"><?= Lang::$word->AFF_IFRAME_PROMOTION_IMAGES_DESC; ?>
</p>

<div class="imgfram">
<img src="https://sp.sportsfier.com/promo-images/src/afxpsports.jpg" />
</div>
<div class="showframe" id="pc">
<?php $html = '<div style="position:relative;">
<iframe src="https://gambabet.com/promo-images/src/afxpsports.jpg" width="" height="" />
<a href="https://gambabet.com/registration/?aff=YOUR UNIQUE AFFILIATE ID" style="position:absolute; top:0; left:0; display:inline-block; width:; height:; z-index:5;"></a>
</div>';?>
<?php echo htmlentities($html, ENT_COMPAT, 'UTF-8');?>
</div>

<textarea id="p1" hidden>
<div style="position:relative;">
<iframe src="https://<?php echo $_SERVER['HTTP_HOST'];?>/promo-images/src/afxpsports.jpg" width="" height="" />
<a href="https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in" style="position:absolute; top:0; left:0; display:inline-block; width:; height:; z-index:5;"></a>
</div>
</textarea>
<button onclick="copyToClipboard('#p1')" id="cpcd"><?= Lang::$word->AFF_COPY_CODE; ?></button>






<div class="bdtp"><?= Lang::$word->AFF_DIVIDE; ?></div>
<div class="tcname"><?= Lang::$word->AFF_SHARE_DIRECTLY; ?></div>





<div class="sharecode">
<ul class="sociconlist">
<li>
<a target="_blank" class="fbshare" href="https://www.facebook.com/sharer/sharer.php?u=https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in"><i class="icon facebook alt"></i> FB </a>
</li>

<li>
<a class="twshare" href="https://twitter.com/intent/tweet?text=https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in" target="_blank" title="Tweet"><i class="icon twitter alt"></i> TW</a>
</li>

<li>
<a class="w-inline-block social-share-btn pin" href="https://pinterest.com/pin/create/button/?url=https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in&description=crickmarkets.com" target="_blank" title="Pin it"><i class="icon pinterest"></i> PIN</a>
</li>

<li>
<a class="w-inline-block social-share-btn redd" href="https://www.reddit.com/submit?url=https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in&title=crickmarkets.com" target="_blank" title="Submit to Reddit">Reddit</a>
</li>

<li>
<a class="w-inline-block social-share-btn tmb" href="http://www.tumblr.com/share?v=3&u=https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in&t=gambabet.com&s=" target="_blank" title="Post to Tumblr"><i class="icon tumblr"></i></a>
</li>

<li>
<a class="w-inline-block social-share-btn email" href="mailto:?subject=Double Bonus exchange betting&body=https://<?php echo $_SERVER['HTTP_HOST'];?>/registration/?aff=affc<?php echo $afid;?>in" target="_blank" title="Email"><i class="icon email alt"></i></a>
</li>


</ul>

</div>

 <?php } 
 
 
 
 
 //Withdrawals
 
 else if(isset($_POST['method']) && $_POST['method'] == 'withdrawals') {?>
	<div class="dptext"><?= Lang::$word->AFF_SHARE_AFFILIATE_WITHDRAWAL_HISTORY; ?></div>
	 <div class="text-dark">
  <div class="depositform" style="border: 1px solid #ff9f1b;">
	<h6><?= Lang::$word->AFF_WITHDRAW_AFFILIATE_BALANCE_TO_YOUR_BANK; ?></h6>
	<?= Lang::$word->AFF_WITHDRAW_AFFILIATE_BALANCE_TO_YOUR_BANK_NOTE; ?></br>
	 <h5><?= Lang::$word->AFF_JOIN_EXCHANGE_MEMBERSHIP_FOR_FASTER_WITHDRAWAL; ?></h5>
	<button id="myBtn"><?= Lang::$word->WITHDRAWAL_HISTORY_MORE; ?></button>
	</div>
</div>

<div class="agtransferbb">
<h4><?= Lang::$word->AFF_TRANSFER_AGENT_BALANCE_TO_BETTING_ACCOUNT; ?></h4>
<div class="agshreturn"></div>
<div class="agshsuccess">
<input type="number" placeholder="" id="agpaynum" required>
<div class="showvaltotr"></div>
</div>
</div>
	
	
	
	
	
	
	

 <div class="wthaff">
 <h5 class="dphist"><?= Lang::$word->AFF_AFFILIATE_TRANSFER_HISTORY; ?></h5>
  <?php
   $usid = $_POST['usid'];	 
   $query = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid AND type='ag_commission' ORDER BY date DESC LIMIT 50";
   $result = mysqli_query($conn, $query);
   echo '<input type="hidden" class="cfvalue" value="50">';
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
     $id = $row['id'];
	 $amount = $row['amount'];
     $ref = $row['transaction_id'];
     $date = date ("m-d-y H:i", $row['date']);
     $type = $row['type'];
	 $status = $row['status'];
	 $sfrom = $row['send_from'];
	 ?> 
 
    <ul class="deptshow k">
	 <li class="idto-<?php echo $id;?>">
	  <div class="ccredit"> <a style="color:#f00"><?= Lang::$word->AFF_TRANSFERRED; ?></a><span class="deptright"><?php echo round($amount,2);?></span></div>
	  <div class="ccredit"> <?= Lang::$word->ACC_P1_FROM; ?>:<span class="deptright"><?= Lang::$word->AFF_AFFILIATE_COMMISSION; ?></span></div>
	  <div class="ccredit"> <?= Lang::$word->AFF_RED; ?>: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> <?= Lang::$word->AFF_DATE; ?>: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  } else {
	 echo '<div style="padding:10px">'.Lang::$word->WITHDRAWAL_NO_ACTIVE_RECORDS_FOUND.'</div>';
  }
  
   echo '</div></br></br></br>';
  
  if ($result->num_rows > 49) {
	echo '<div id="lrem" class="wload">'.Lang::$word->WITHDRAWAL_LOAD_MORE.'</div>';
  }
  
  
 }

  //loadmore withdrawal
 elseif(isset($_POST['method']) && $_POST['method'] == 'lwithdraw'){ 
   $usid = $_POST['usid'];
   $rc = $_POST['rc'];
   $query = "SELECT * FROM sh_sf_agent_withdraws WHERE user_id=$usid ORDER BY date DESC LIMIT $rc, 50";
   $result = mysqli_query($conn, $query);
   echo '<input type="hidden" class="cfvalue" value="50">';
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
     $id = $row['id'];
	 $amount = $row['amount'];
     $ref = substr(str_shuffle("TROM9737XQWDG48URPLEE945Y"), 0, 20); ;
     $date = date ("m-d-y H:i", $row['date']);
     $type = $row['type'];
	 $status = $row['status'];
	 $sfrom = $row['send_from'];
	 ?> 
 
    <ul class="deptshow k">
	 <li class="idto-<?php echo $id;?>">
	  <div class="ccredit"> <?php if($status == 'Processed') { echo '<a>'.Lang::$word->WITHDRAWAL_PROCESSED.'</a>';} else { echo '<a style="color:#f00">'.Lang::$word->WITHDRAWAL_PROCESSING.'</a>'; echo '<span class="cpending" id="'.$id.'" title="cancel request">X</span>'; };?> <span class="deptright"><?php echo $amount;?></span></div>
	  <div class="ccredit"> FROM: <?php echo $sfrom;?> <span class="deptright"><?php echo $type;?></span></div>
	  <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> Date: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  } else {
	 echo '<div style="padding:10px">'.Lang::$word->WITHDRAWAL_NO_MORE_ACTIVE_RECORDS_FOUND.'</div>';
  }
  
  if ($result->num_rows > 1) {
	echo '<div id="lrem" class="wload">'.Lang::$word->TRANSFER_LOAD_MORE.'</div>';
  }
 } 
 
 
 
 
 
 //print reports
 else if(isset($_POST['method']) && $_POST['method'] == 'printreports') {
    $usid = $_POST['usid'];	 
	 //get risk management
$xr = "SELECT ex_comi, sp_comi FROM `risk_management` ";
$result = mysqli_query($conn, $xr);
$c_row = $result->fetch_assoc();
$exc = $c_row['ex_comi'];
$spcomi = $c_row['sp_comi'];

//total affiliate balance
$afbalance = mysqli_fetch_array(mysqli_query($conn, "SELECT afbal FROM `users` WHERE id = '$usid'"));

//today users calculation data
$ctoday = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) AS totusers, COUNT(CASE WHEN created >= CURDATE() THEN 1 END) AS rtoday, COUNT(CASE WHEN active = 'y' THEN 1 END) AS ractive, COUNT(CASE WHEN active <> 'y' THEN 1 END) AS rinactive FROM users WHERE afid = '$usid' AND id <> $usid"));

//For exchange today earning
$eatoday = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings - stake) AS expre FROM sh_sf_slips_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND status = 'winning' AND type <> 'sbook' AND aid = '$usid' AND debit = 'chips'"));
$inplay_ex = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(winnings - stake) AS inplayex FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND status = 'winning' AND type <> 'sbook' AND aid = '$usid' AND debit = 'chips'"));
$net_value = $eatoday['expre'] + $inplay_ex['inplayex'];
$exchange_prematch = $net_value * $exc/100;

//for sportsbook today earning
$sbook_pre = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'losing' THEN stake END) AS booklose, SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS bookwin FROM sh_sf_slips_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND type = 'sbook' AND aid = '$usid' AND debit = 'chips'"));
$inplay_sbook = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'losing' THEN stake END) AS inplaylose, SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS inplaywin FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) >= CURDATE() AND type = 'sbook' AND aid = '$usid' AND debit = 'chips'"));

$pre_sbooksum = $sbook_pre['booklose'] - $sbook_pre['bookwin'];
$inplay_sbooksum = $inplay_sbook['inplaylose'] - $inplay_sbook['inplaywin'];
$sum_sbook = $pre_sbooksum + $inplay_sbooksum;
$final_sbook = $sum_sbook * $spcomi/100;

//final commission calculation today earning
$net_commission = $exchange_prematch + $final_sbook;

//pending volume
$pending_prematch = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS prepends FROM sh_sf_slips_history WHERE user_id <> '$usid' AND status = 'awaiting' AND aid = '$usid' AND debit = 'chips'"));
$pending_inplay = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(stake) AS inplaypend FROM sh_sf_tickets_history WHERE user_id <> '$usid' AND status = 'awaiting' AND aid = '$usid' AND debit = 'chips'"));

$pending_volume = $pending_prematch['prepends'] + $pending_inplay['inplaypend'];?>


	 <div class="tickethigh">
	 <span class="tctext">
	 <?= Lang::$word->AFF_PRINT_YOUR_DAILY_REPORTS; ?>
	 </span></br>
	 <p class="tcinfo">
	 <?= Lang::$word->AFF_PRINT_THIS_REPORT_DESC ?>
	 </p>
	 </div>


<div class="tcname" style="text-align:center"><?= Lang::$word->AFF_DATA_PRINTABLE_BLOC; ?></div>
<div id="printTable">
<p class="didcenter"><?= Lang::$word->AFF_PRINT_DATA; ?> // <?php echo $_SERVER['HTTP_HOST'];?></p>
<ul class="printme">
<li><?= Lang::$word->AFF_TODAYS_REGISTERED; ?> <span class="printright"><?php echo $ctoday['rtoday'];?></li>
<li><?= Lang::$word->AFF_TOTAL_ACTIVE_USERS; ?> <span class="printright"><?php echo $ctoday['ractive'];?></span></li>
<li><?= Lang::$word->AFF_PENDING_USERS; ?> <span class="printright"><?php echo $ctoday['rinactive'];?></span></li>
<li><?= Lang::$word->AFF_ALL_TIME_USERS; ?> <span class="printright"><?php echo $ctoday['totusers'];?></span></li>
<li><?= Lang::$word->AFF_TODAYS_EARNINGS; ?> <span class="printright"><?php echo round($net_commission, 2);?></span></li>
<li><?= Lang::$word->AFF_PENDING_VOLUME; ?> <span class="printright"><?php echo $pending_volume;?></span></li>
<li class="ccomm"><?= Lang::$word->AFF_CLEARED_COMMISSION; ?> <span class="printright"><?php $afbal = $afbalance['afbal'];echo round($afbal, 2);?></span></li>
</ul>
</div>
<div class="btncenti">
    <button id="printbtnx"><?= Lang::$word->AFF_PRINT_THIS_REPORT; ?></button>
</div>



	 
 <?php } else if(isset($_POST['method']) && $_POST['method'] == 'sssupports') {?>
	 <div class="tickethigh">
	 <span class="tctext">
	    <?= Lang::$word->AFF_SUPPORT_MESSAGING_INBOX; ?>
	 </span></br>
	 <p class="tcinfo" style="font-size:14px">
	 <?= Lang::$word->AFF_PLEASE_SEND_MESSAGE_FROM_MESSAGING_PAGE_OR; ?> <a href="/bt_accounts/?pg103=msg"><?= Lang::$word->AFF_CLICK_HERE; ?></a>
	 </p>
	 </div>
 <?php }