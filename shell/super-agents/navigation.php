<?php error_reporting(0);include('../db.php');

$usid = $_POST['usid'];


if(isset($_POST['method']) && $_POST['method'] == 'dashboardsa') {
	 $que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}

	$aids = implode (", ", $agent_ids);
	$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE()- INTERVAL 1 DAY THEN 1 END) AS today, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 7 DAY THEN 1 END) AS thisweek, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 30 DAY THEN 1 END) AS thismonth, COUNT(id) AS allsa FROM users WHERE said=$usid"));
	
	$down = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN created >= CURDATE()- INTERVAL 1 DAY THEN 1 END) AS today, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 7 DAY THEN 1 END) AS thisweek, COUNT(CASE WHEN created >= CURDATE()- INTERVAL 30 DAY THEN 1 END) AS thismonth, COUNT(id) AS allsa FROM users WHERE afid IN($aids)"));
	
	$earn = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN FROM_UNIXTIME(dt,'%Y-%m-%d') >= CURDATE()- INTERVAL 1 DAY THEN amt END) AS todayearn, SUM(CASE WHEN FROM_UNIXTIME(dt,'%Y-%m-%d') >= CURDATE()- INTERVAL 30 DAY THEN amt END) AS monthearn, SUM(amt) AS allsa FROM sh_agents_credit_records WHERE agent_id IN($aids)"));
	
	
	?>


<h2>Super Agent Dashboard</h2>

<div class="agdwn">Agents & Downline registration stats</div>
</br>





	<?php $binfo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT country,notes FROM users WHERE id= ".$_POST['usid'].""));
	  $bankinfo = $binfo['notes'];
	  $cc = $binfo['country'];
	  //for checking if there is pending request
	  $ifyes =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM sh_sf_withdraws WHERE status = 'Pending' AND cc='$cc'"));
	  //for getting super agent commission
	  $sacom = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ex_sagents FROM risk_management"));
	  $supercom = $sacom['ex_sagents'];
	  $sg = mysqli_fetch_assoc(mysqli_query($conn,"SELECT super_agent FROM sh_messages_board"));
	  $kge=$sg['super_agent'];
	  if(!empty($kge)){
	  echo '<div class="supernotice">'.trim($kge).'</div>';
	  }
	  
	  echo '<div class="superearning">Your commission is <span class="supspan">'.$supercom.'% </span>on net earnings of all your affiliates/agents</span></div>';
	  if(!empty($ifyes)){
		  echo '<div class="ifys"> You have withdrawal request pending from one of the players. Please check E.Broker Dashboard "request" tab for details</div></br>';
	  }
	  
	  if(empty($bankinfo)){
		  echo '<div class="infcontent"><span class="errwarn"><i class="icon warning sign"></i> ERROR:</span>. You haven\'t updated your WhatsAPP number for players to contact you for deposit/withdrawals on commission. Please update it or your exchange broker status will be lowered to players. <a href="/dashboard/settings/?pg103=acset">Update contact here</a></div>';
	  }?>











</br></br>
<div id='chart_div' style="max-width:100%;max-height:160px;"></div>
</br></br>
<h3>Agents (<?php echo $ccs['allsa'];?>)</h3>
<div class="containersa">

  <div class="colsa saleft">
  <span class="ltwenty">Last 24 hours</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon user"></i></span>  <span class="usvalue"><?php echo $ccs['today'];?></span>
   </br>
  </div>
  
  <div class="colsa sacenter">
  <span class="ltwenty">Last 7 Days</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon user"></i></span>  <span class="usvalue"><?php echo $ccs['thisweek'];?></span>
   </br>
  </div>
  
  <div class="colsa saright">
  <span class="ltwenty">Last 30 Days</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon user"></i></span>  <span class="usvalue"><?php echo $ccs['thismonth'];?></span>
   </br>
  </div>
  

  
</div>
  

</br>
<h3>Downline (<?php echo $down['allsa'];?>)</h3>
<div class="containersa">
  
  <div class="colsa saleft">
  <span class="ltwenty">Last 24 hours</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon users"></i></span>  <span class="usvalue"><?php echo $down['today'];?></span>
   </br>
  </div>
  
  <div class="colsa sacenter">
  <span class="ltwenty">Last 7 Days</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon users"></i></span>  <span class="usvalue"><?php echo $down['thisweek'];?></span>
   </br>
  </div>
  
  <div class="colsa saright">
  <span class="ltwenty">Last 30 Days</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon users"></i></span>  <span class="usvalue"><?php echo $down['thismonth'];?></span>
   </br>
  </div>
  
</div>




</br></br></br></br>
<h3>Earnings Stats </h3>
<div class="containersa" id="cott">
  
  <div class="colsa saleft">
  <span class="ltwenty">Last 24 hours</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon database"></i></span>  <span class="usvalue"><?php echo round($earn['todayearn'],2);?></span>
   </br>
  </div>
  
  <div class="colsa sacenter">
  <span class="ltwenty">This Month</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon database"></i></span>  <span class="usvalue"><?php echo round($earn['monthearn'],2);?></span>
   </br>
  </div>
  
  <div class="colsa saright">
  <span class="ltwenty">All Time</span>     <span class="righelse"><i class="icon ellipsis vertical"></i></span>
   </br></br>
   <span class="userle"><i class="icon database"></i></span>  <span class="usvalue"><?php echo round($earn['allsa'],2);?></span>
   </br>
  </div>
  





</div>
 
 

 
<?php } 
  
  //For all players load more
  else if(isset($_POST['method']) && $_POST['method'] == 'supportsa') {?>
	  <h2>Support</h2>

<div class="agdwn">Reach out to our support</div>
<div class="noteus"><b>Info :</b> As we already have dedicated messaging services from the users panel, please go to the users panel messaging service and send us the queries directly from there.</br></br>
<p style="color:#f00">Use our users messaging panel to send message or reach out to our email support at: <b>hello@gambabet.com</b></p></div>
</br></br>
	 
  
<?php }
 
 //agents list
 else if(isset($_POST['method']) && $_POST['method'] == 'agentlistsa') {
	 
  $que="SELECT id FROM users WHERE said = $usid LIMIT 50";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}

	$aids = implode (", ", $agent_ids);	 
    $prec = "SELECT id,fname,email, created,lastlogin,country,active,afbal FROM users WHERE id IN($aids)";	 
    $result = mysqli_query($conn, $prec);?>
  
  
<h2>Agents List</h2>

<div class="agdwn">All your registered agents</div>
<div class="noteus"><b>Facts :</b> Due to Player's privacy terms and it's allied securities, super agents are not allowed to edit or view sensitive data such as password, address etc. Once account is created, you are no longer able to make edits</br></br>
ACT = Active, DLINE = DOWNLINE COUNT, EMAIL = SEND EMAIL</div>
</br></br>
 
 
 <input type="hidden" class="cfvalue" value="50">
 <div style="overflow-x:auto;" id="hidemail">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><input type="checkbox" id="checkAll">ID</th>
        <th data-sort="string">First Name</th>
        <th data-sort="int">Created</th>
        <th data-sort="int">Last Login</th>
		<th data-sort="int">Country</th>
		<th data-sort="int">Act</th>
		<th data-sort="int">DLine</th>
		<th data-sort="int">Cash</th>
		<th data-sort="int">Actions</th>
      </tr>
    </thead>

 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	 
	 <tr class="singbt">
      <td><a class="glist" id="<?php echo $row['id'];?>"><i class="icon eye blocked"></i> <?php echo $row['id'];?></a></td>
      <td><?php echo $row['fname'];?></td>
      <td><?php $date = $row['created'];$dt = new DateTime($date);echo $dt->format('Y-m-d');?></td>
      <td><?php $ll = $row['lastlogin'];$ds = new DateTime($ll);if(empty($ll)){echo 'Never';}else{echo $ds->format('Y-m-d');}?></td>
	  <td><?php $cc = $row['country'];if(empty($cc)){echo 'Unknown';}else{echo $cc;}?></td>
      <td>
	  <?php $ck = $row['active'];if($ck == 'y'){ echo 'Yes';} else { echo 'No';};?></td>
	  <td><?php $ci = $row['id'];$cou = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) AS ucc FROM users WHERE afid=$ci"));echo $cou['ucc'];?></td>
	  <td style="color:#00bc90;font-weight:bold;"><?php echo $row['afbal'];?></td>
	  <td><a class="sdmail" id="<?php echo $row['email'];?>">Email</a></td>
	  </tr>
		 
	 <?php 
	 }
	 
 } else {
	  echo '<div style="padding:10px">You don\'t have any registered agent yet.</div>';
	  die();
  }?> 
  </table>
  </div>
<div class="loadmo">Load More..</div>
 
 <?php }
//agents list Loadmore
 else if(isset($_POST['method']) && $_POST['method'] == 'aglistmore') {
  $rc = $_POST['rc'];

  $que="SELECT id FROM users WHERE said = $usid LIMIT $rc, 50";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}

	$aids = implode (", ", $agent_ids);	 
    $prec = "SELECT id,email,fname,created,lastlogin,country,active,afbal FROM users WHERE id IN($aids)";	 
    $result = mysqli_query($conn, $prec);?>
 
 <input type="hidden" class="cfvalue" value="50">
 <div style="overflow-x:auto;" id="hidemail">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><input type="checkbox" id="checkAll">ID</th>
        <th data-sort="string">First Name</th>
        <th data-sort="int">Created</th>
        <th data-sort="int">Last Login</th>
		<th data-sort="int">Country</th>
		<th data-sort="int">Act</th>
		<th data-sort="int">DLine</th>
		<th data-sort="int">Cash</th>
		<th data-sort="int">Actions</th>
      </tr>
    </thead>

 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {?>
	 
	 <tr class="singbt" id="tr_<?php echo $row['id'];?>">
      <td><a class="glist" id="<?php echo $row['id'];?>"><i class="icon eye blocked"></i> <?php echo $row['id'];?></a></td>
      <td class="unames" id="<?php echo $row['fname'];?>"><?php echo $row['fname'];?></td>
      <td><?php $date = $row['created'];$dt = new DateTime($date);echo $dt->format('Y-m-d');?></td>
      <td><?php $ll = $row['lastlogin'];$ds = new DateTime($ll);if(empty($ll)){echo 'Never';}else{echo $ds->format('Y-m-d');}?></td>
	  <td><?php $cc = $row['country'];if(empty($cc)){echo 'Unknown';}else{echo $cc;}?></td>
      <td>
	  <?php $ck = $row['active'];if($ck == 'y'){ echo 'Yes';} else { echo 'No';};?></td>
	  <td><?php $ci = $row['id'];$cou = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) AS ucc FROM users WHERE afid=$ci"));echo $cou['ucc'];?></td>
	  <td style="color:#00bc90;font-weight:bold;"><?php echo $row['afbal'];?></td>
	  <td><a class="sdmail" id="<?php echo $row['email'];?>">Email</a></td>
	  </tr>
		 
	 <?php 
	 }
	 
 } else {
	  echo '<div style="padding:10px">No more results found.</div>';
	  die();
  }?> 
  </table>
  </div>
<div class="loadmo">Load More..</div>
 
 <?php }
 
 
 
 
 
 //tickets history
if(isset($_POST['method']) && $_POST['method'] == 'ticketshistorysa') {
	$que="SELECT id FROM users WHERE said = $usid";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['id'];
		}
	  $aids = implode (", ", $agent_ids);
		
	$sql = "SELECT * FROM sh_sf_tickets_history WHERE aid IN($aids) UNION SELECT * FROM sh_sf_slips_history WHERE aid IN($aids) ORDER BY date DESC LIMIT 50";
	$result = $conn->query($sql);?>
	
	
	 <div style="overflow-x:auto;">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><input type="checkbox" id="checkAll">SlipID</th>
        <th data-sort="string">Agent_id</th>
		<th data-sort="int">UID</th>
        <th data-sort="int">Status</th>
        <th data-sort="int">Stake</th>
		<th data-sort="int">Win</th>
		<th data-sort="int">Date</th>
		<th data-sort="int">Odd</th>
		<th data-sort="int">Event Name</th>
		<th data-sort="int">Cat Name</th>
		<th data-sort="int">Option Name</th>
		<th data-sort="int">Type</th>
		<th data-sort="int">Debit</th>
      </tr>
    </thead>

<input type="hidden" class="ticketvalue" value="100">
 <?php if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		 $data=unserialize($row['bet_info']);?>
		 
	  <?php if($row['bet_info'] == null){?> 
	  <tr class="singbt" id="tr_<?php echo $row['slip_id'];?>">
	  <td><input type='checkbox' name="slipname" class="delop" id='del_<?php echo $row['slip_id'];?>'> <?php echo $row['slip_id'];?></td>
	  
      <td><a href="/admin/bet-history/?aid=<?php echo $row['aid'];?>"><i class="icon eye"></i> <?php echo $row['aid'];?></a></td>
	  <td><?php echo $row['user_id'];?></td>
      <td><?php echo $row['status'];?></td>
      <td><?php echo $row['stake'];?></td>
      <td><?php echo $row['winnings'];?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>
      <td><?php echo $row['odd'];?></td>
	  <td><?php echo $row['event_name'];?></td>
	  <td><?php echo $row['cat_name'];?></td>
	  <td><?php echo $row['bet_option_name'];?></td>
	  <td><?php echo $row['user_id'];?></td>
	  <td><?php echo $row['type'];?></td>
	  <td><?php echo $row['debit'];?></td>
	  </tr>
	  
	  <?php } else {?>
	  
	  <tr class="multibs" id="tr_<?php echo $row['slip_id'];?>">
	  
	  
	  <td><input type='checkbox' name="slipname" class="delop" id='del_<?php echo $row['slip_id'];?>'>Multi</br><?php echo $row['slip_id'];?></td>
      <td><a href="/admin/bet-history/?aid=<?php echo $row['aid'];?>"><i class="icon eye"></i> <?php echo $row['aid'];?></a></td>
	  <td><?php echo $row['user_id'];?></td>
      <td><?php echo $row['status'];?></td>
      <td><?php echo $row['stake'];?></td>
      <td><?php echo $row['winnings'];?></td>
	  <td><?php echo date ("m-d H:i", $row['date']);?></td>	  
	  
	  <td>
	  <ol class="ollist">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['odd'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	   <div class="shadowme" id="<?php echo $row['slip_id'];?>">Expand..</div>
	  <ol class="ollist" style="display:none" id="show<?php echo $row['slip_id'];?>">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['event_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	 <div class="shadowmex" id="<?php echo $row['slip_id'];?>">Expand..</div> 
	  <ol class="ollist" style="display:none" id="showx<?php echo $row['slip_id'];?>">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['cat_name'].'</li>';
	  };?>
	  </ol>
	  </td>
	  
      <td>
	   <div class="shadowmey" id="<?php echo $row['slip_id'];?>">Expand..</div>  
	  <ol class="ollist" style="display:none" id="showy<?php echo $row['slip_id'];?>">
	  <?php foreach ($data as $keyChild => $childValue) {
		  echo '<li>'.$childValue['bet_option_name'].'</li>';
	  };?>
	  </ol>
	  </td>

	  <td><?php echo $row['type'];?></td>
	  <td><?php echo $row['debit'];?></td>
	  
	  </tr>
	  
	  <?php } ?>

	 <?php
	 }
 } else {
	  echo '<div style="padding:10px">No active Tickets Found</div>';
	  die();
  }?> 
  </table>
  </div>
  <?php if ($result->num_rows > 1) {
	echo '<div class="addload" id="triggerlodo"></div>';
	echo '</br><div id="'.$counter.'" class="lodo">Load More...</div>';
  }
	
	
	
	
	
	
	
	
	
	
} 
  
  
  
  //for tickets history load more
  
  else if($_POST['method'] == 'toolsmarketingsa'){?>
	<h2>Tools & Marketing</h2>

<div class="agdwn">All tools and marketing materials for promotion</div>
<div class="noteus"><b>Facts :</b> Super Agents cannot directly promote their businesses in the internet. Super agents can only create agents under his network. All the downline of agents will be your downline too, with shared % of profits. You can bring agents under your own capacity, and shouldn't use website logo in your advertisement. Read our terms for more information</div>
</br></br>


   <?php } ?>
   
   
   
   
   
   
   
   
   
   
   
   