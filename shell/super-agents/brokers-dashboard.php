<?php error_reporting(0);include('../db.php');
$usid = $_POST['usid'];

require_once('../../init.php');


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

<h2>Transfer Dashboard</h2>

<div id="topbtnwrapper">
<a href="#" class="mydash active" id="mydash">Dash</a>
</div>
</br></br>
<div id="ccajax">

<div class="availbalebb">Available Balance for Exchange <b class="abchp"><?php echo $shbal;echo ' ';echo $currency;?> </b></div>

 
</br>

</br>
<div id="showtrhistory">
<h5>Transfer Funds from Your balance to any players</h5>
<div class="warnintxt">All super agent currency and balance is that of betting account. There is no separate wallet. All inter currency exchange are converted using latest central bank exchange rate. Transfers once made cannot be reversed so you are advised to excercise with caution.</div>



<input type="text" class="searchfund" id="sendfunds" placeholder="Input Recipient Email"><i id="fico" class="find icon"></i>
</br>


<div class="sacur">
<input id="putcash" class="tramount" value="" placeholder="Input Amount in <?php echo $currency;?>">
</div>				

<div id="showavl"></div> 


</div>
</br>

<div id="quathistory">
<div class="txnhistory">Transfer History</div>
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
	  <div class="ccredit">A2A Transfer<span class="deptright"><?php echo $amount;?></span></div>
	  <div class="ccredit"> Sent TO: <span class="deptright"><?php echo $sendto;?></span></div>
	  <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> Date: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  } else {
	 echo '<div style="padding:10px">No Active Records Found</div>';
  }
  
  if ($result->num_rows > 1) {
	echo '<div id="lrem" class="tload">Load More...</div>';
  }?>

</div>




</div>
</br></br>




<?php }

//transfer
else if(isset($_POST['method']) && $_POST['method'] == 'mytransfer') {?>

<div class="depositform">
	<b style="color:#f00"><i class="icon question sign"></i> :</b> Whenever a player within your country request a withdrawals, you will receive the request to process withdrawals in exchange of commission(as given in dashboard).<span style="display:none" id="showtrr"> It's a first mark first basis to earn commission. Any other broker in your country can also see the same request. If you are ready to transfer, you can "mark processing" at which other broker will no longer be able to do it. After transfer, you can "mark completed" by confirming the transfer with recipient. Within 1-3 days, you will get the commission credited to your account.</span> <a id="clikshowtrr">See more...</a></br>
	<b style="color:#f00">DO NOT mark processing or completed without having done or not able to transfer. Misusing of this features will lead to permanent deletion of accountss</b>
	</div>

 <?php 
 $getbal = mysqli_query($conn, "SELECT country FROM users WHERE id=$usid");
	$getb = mysqli_fetch_assoc($getbal);
	$cc = $getb['country'];
  $query = "SELECT * FROM sh_sf_withdraws WHERE status = 'Pending' AND cc='$cc' OR status = 'Processing' AND aprov_id=$usid AND cc='$cc' ORDER BY date DESC";
   $result = mysqli_query($conn, $query);?>
    <div style="overflow-x:auto;" id="seetick">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">Full Name</th>
		 <th data-sort="int">Status</th>
		<th data-sort="int">Mark Actions</th>
        <th data-sort="int">WP/Phone</th>
		<th data-sort="int">Amount</th>
		<th data-sort="int">Date</th>
		<th data-sort="int">TXN ID</th>
		<th data-sort="int">Type</th>
		<th data-sort="int">Bank/Payment Details</th>
		<th data-sort="int">CC</th>
		<th data-sort="int">E-Mail ID</th>
      </tr>
    </thead>
   
   <?php
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$urd = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname,lname,notes,email FROM users WHERE id=".$row['user_id'].""));
		$name = $urd['fname'].' '.$urd['lname'];
		$phone = $urd['notes'];
		$email = $urd['email'];

		
		
		?>
	<tr>
     <td><?php echo $name;?></td>
	 <td><b><a class="kgp<?php echo $row['status'];?>"><?php echo $row['status'];?></a></b></td>
	 <td>
	 <?php if($row['status'] =='Processing'){
		echo '<a class="crkpr cr-'.$row['user_id'].'" id="'.$row['id'].'">Mark Completed</a>';
	 }else if($row['status']=='Pending'){
		 echo '<a class="mrkpr pr-'.$row['user_id'].'" id="'.$row['id'].'">Mark Processing</a>';
		 
	 }?>
	 </td>
	 <td><?php echo $phone;?></td>
	 <td><b style="color:#f00"><?php echo $row['amount'];?></b></td>
	 <td><?php echo date ("m-d-y H:i", $row['date']);?></td> 
	 <td><?php echo $row['transaction_id'];?></td>
	 <td><?php echo $row['type'];?></td>
	 <td><?php echo nl2br($row['withdrawal_details']);?></td> 
	 <td><?php echo $row['cc'];?></td>
	 <td><a><?php echo $email;?></a></td>
	 
	 </tr>
	<?php }?>
	
	</table>
	 </div>
<?php 
   }else {
	  echo 'No Availabe request at the moment.';
   }
   
   
 } 


//history
else if(isset($_POST['method']) && $_POST['method'] == 'myhistory') {?>
	
	<?php 
 $getbal = mysqli_query($conn, "SELECT country FROM users WHERE id=$usid");
	$getb = mysqli_fetch_assoc($getbal);
	$cc = $getb['country'];
  $query = "SELECT * FROM sh_sf_withdraws WHERE status = 'Processed' AND aprov_id=$usid ORDER BY date DESC LIMIT 100";
   $result = mysqli_query($conn, $query);?>
   <input type="hidden" class="cfvalue" value="100">
    <div style="overflow-x:auto;" id="seetick">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">Full Name</th>
        <th data-sort="int">E-Mail ID</th>
		<th data-sort="int">TXN ID</th>
		<th data-sort="int">Amount</th>
		<th data-sort="int" style="min-width:120px">Date</th>
		<th data-sort="int" style="min-width:110px">Type</th>
		<th data-sort="int">CC</th>
		<th data-sort="int">Status</th>
      </tr>
    </thead>
   
   <?php
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$urd = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname,lname,notes,email FROM users WHERE id=".$row['user_id'].""));
		$name = $urd['fname'].' '.$urd['lname'];
		$phone = $urd['notes'];
		$email = $urd['email'];

		
		
		?>
	<tr>
     <td><?php echo $name;?></td>
	 <td><a><?php echo $email;?></a></td>
	 <td><?php echo $row['transaction_id'];?></td>
	 <td><b style="color:#f00"><?php echo $row['amount'];?></b></td>
	 <td><?php echo date ("m-d-y H:i", $row['date']);?></td> 
	 <td><?php echo $row['type'];?></td>
	 <td><?php echo $row['cc'];?></td>
	 <td><b><a class="kgp<?php echo $row['status'];?>"><?php echo $row['status'];?></a></b></td>
	 </tr>
	<?php }?>
	
	</table>
	 </div>
<?php 
   }else {
	  echo 'No Availabe records found.';
	  die();
   }
   
   if ($result->num_rows > 99) {
	echo '<div class="loadwhistory">Load More..</div>';
  }
}

//Load more withdraw

else if(isset($_POST['method']) && $_POST['method'] == 'withmore') {?>
		<?php 
 $rc = $_POST['rc'];		
 $getbal = mysqli_query($conn, "SELECT country FROM users WHERE id=$usid");
	$getb = mysqli_fetch_assoc($getbal);
	$cc = $getb['country'];
  $query = "SELECT * FROM sh_sf_withdraws WHERE status = 'Processed' AND aprov_id=$usid ORDER BY date DESC LIMIT $rc, 100";
   $result = mysqli_query($conn, $query);?>
   <input type="hidden" class="cfvalue" value="100">
    <div style="overflow-x:auto;" id="seetick">
    <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">Full Name</th>
        <th data-sort="int">E-Mail ID</th>
		<th data-sort="int">TXN ID</th>
		<th data-sort="int">Amount</th>
		<th data-sort="int" style="min-width:120px">Date</th>
		<th data-sort="int" style="min-width:110px">Type</th>
		<th data-sort="int">CC</th>
		<th data-sort="int">Status</th>
      </tr>
    </thead>
   
   <?php
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$urd = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname,lname,notes,email FROM users WHERE id=".$row['user_id'].""));
		$name = $urd['fname'].' '.$urd['lname'];
		$phone = $urd['notes'];
		$email = $urd['email'];

		
		
		?>
	<tr>
     <td><?php echo $name;?></td>
	 <td><a><?php echo $email;?></a></td>
	 <td><?php echo $row['transaction_id'];?></td>
	 <td><b style="color:#f00"><?php echo $row['amount'];?></b></td>
	 <td><?php echo date ("m-d-y H:i", $row['date']);?></td> 
	 <td><?php echo $row['type'];?></td>
	 <td><?php echo $row['cc'];?></td>
	 <td><b><a class="kgp<?php echo $row['status'];?>"><?php echo $row['status'];?></a></b></td>
	 </tr>
	<?php }?>
	
	</table>
	 </div>
<?php 
   }else {
	  echo 'No more Availabe records found.';
	  die();
   }
   
   if ($result->num_rows > 0) {
	echo '<div class="loadwhistory">Load More..</div>';
  }
	
}


//update as processing

else if(isset($_POST['method']) && $_POST['method'] == 'markprocess') {
	$broker_id = $_POST['usid'];
	$row_id = $_POST['rowid'];
	$tm = time();
	$grow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_sf_withdraws WHERE id=$row_id AND aprov_id IS NULL"));
	if(empty($grow)){
		echo 'Already taken..';
		die();
	}
	$ramount = $grow['amount'];
	$request_user=$grow['user_id'];
	$usermo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT chips FROM users WHERE id=$request_user"));
	$usr_money = $usermo['chips'];
	$af = $usr_money - $ramount;
	 mysqli_query($conn, "UPDATE users SET chips = chips - $ramount WHERE id=$request_user");
	if(mysqli_affected_rows($conn)>0){
		mysqli_query($conn, "UPDATE sh_sf_withdraws SET status='Processing', aprov_id=$broker_id WHERE id=$row_id");
		 if(mysqli_affected_rows($conn)>0){
		   mysqli_query($conn, "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($request_user,'Withdrawal debit','-$ramount','$tm','chips','$usr_money','$af')");
		   if(mysqli_affected_rows($conn)>0){
			   echo 'Success!!';
		   }else{
			   mysqli_query($conn, "UPDATE sh_sf_withdraws SET status='Pending' WHERE id=$row_id");
			   mysqli_query($conn, "UPDATE users SET chips = chips + $ramount WHERE id=$usid");
			   echo 'Failed!! Contact Support if problem persist';
			   die();
		   }
		   
		  }else{
			mysqli_query($conn, "UPDATE users SET chips = chips + $ramount WHERE id=$usid");
		 }
	}

}

//update as completed
else if(isset($_POST['method']) && $_POST['method'] == 'markcompleted') {
	$broker_id =$_POST['usid'];
	$row_id = $_POST['rowid'];
	mysqli_query($conn, "UPDATE sh_sf_withdraws SET status='Processed' WHERE id=$row_id");
    if(mysqli_affected_rows($conn)>0){
		echo 'Success!Check history to confirm';
		die();
		
	}

}




//show currency transfer
else if(isset($_POST['method']) && $_POST['method'] == 'selcurrency') {
	$tremail= $_POST['tremail'];
	$trmoney=$_POST['trmoney'];
	
	function receiver_exchange($receiver_curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$receiver_curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	function broker_exchange($broker_curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$broker_curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	
	//$cur_value=get_exchange($curr,$conn); //user rate
	
	//get broker details
	$getusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,country,stripe_cus,chips FROM users WHERE id=$usid"));
	$broker_curr = $getusr['stripe_cus']; //broker rate
	$broker_balance = $getusr['chips'];
	if($trmoney > $broker_balance){
		echo 'You don\'t have sufficient balance';
		die();
	}
	
	$broker_value=broker_exchange($broker_curr,$conn);
	
	//get user details
	$rusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,country,stripe_cus,chips FROM users WHERE email='$tremail'"));
	$receiver_curr = $rusr['stripe_cus'];
	if(empty($receiver_curr)){
		echo 'No account found with this email';
		die();
	}
	$receiver_value = receiver_exchange($receiver_curr,$conn);
	
	
	if($broker_curr == $receiver_curr){
	$net_rate = $receiver_value['rate'] / $broker_value['rate'];
	$available_bal = round($net_rate * $trmoney, 2);
	}else{
	$net_rate = $receiver_value['rate'] / $broker_value['rate'];	
	$net_ded = $net_rate * 5/100;
	$net_value = $net_rate - $net_ded;
	
	$available_bal = round($net_value * $trmoney, 2);
	}
	
	echo Lang::$word->AFF_RECIPIENT_WILL_GET . " : <span style='color:#009688;font-weight:bold'> ".$available_bal. " ".$receiver_curr."</span><div class='subtransfer'>".."</div>";
	
	
}


//submit transfer
else if(isset($_POST['method']) && $_POST['method'] == 'submittransfer') {
	$tremail= $_POST['tremail'];
	$trmoney=$_POST['trmoney'];
	function receiver_exchange($receiver_curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$receiver_curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	function broker_exchange($broker_curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$broker_curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
	
	//$cur_value=get_exchange($curr,$conn); //user rate
	
	//get broker details
	$getusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,country,stripe_cus,chips FROM users WHERE id=$usid"));
	$broker_curr = $getusr['stripe_cus']; //broker rate
	$broker_balance = $getusr['chips'];
	$broker_id = $getusr['id'];
	if($trmoney > $broker_balance){
		echo '<i class="icon warning sign"></i>  You don\'t have sufficient balance';
		die();
	}
	
	$broker_value=broker_exchange($broker_curr,$conn);
	
	//get user details
	$rusr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id,country,stripe_cus,chips FROM users WHERE email='$tremail'"));
	$receiver_curr = $rusr['stripe_cus'];
	$receiver_id = $rusr['id'];
	$receiver_balance = $rusr['chips'];
	if(empty($receiver_curr)){
		echo '<i class="icon warning sign"></i>  No account found with this email';
		die();
	}
	$receiver_value = receiver_exchange($receiver_curr,$conn);
	
	
	
	if($broker_curr == $receiver_curr){
	$net_rate = $receiver_value['rate'] / $broker_value['rate'];
	$available_bal = round($net_rate * $trmoney, 2);
	}
	else{
	$net_rate = $receiver_value['rate'] / $broker_value['rate'];	
	$net_ded = $net_rate * 5/100;
	$net_value = $net_rate - $net_ded;
	
	$available_bal = round($net_value * $trmoney, 2);
	}
	
	
	 $minvalue = round($broker_value['rate'],2);
	 
	if($minvalue > $trmoney){
		echo '<i class="icon warning sign"></i> Minimum allowed '.$minvalue.' ' .$broker_curr.'';
		die();
	}
	
	$maxtr= mysqli_fetch_assoc(mysqli_query($conn,"SELECT sa_tr_limit FROM risk_management"));
	
	$nettrmax = round($broker_value['rate'] * $maxtr['sa_tr_limit'],2);
	if($trmoney > $nettrmax){
		echo '<i class="icon warning sign"></i> Max allowed at a time '.$nettrmax.' ' .$broker_curr.'';
		die();
	}
	
	$ref = substr(str_shuffle("QR8123MUXQWLG48URPLEE945Y"), 0, 20); 
	$tm = time();
	
	//sh_sf_deposits->1, sh_sf_points_log->2, sh_sf_transfers->1
	mysqli_query($conn, "UPDATE users SET chips = chips - $trmoney WHERE id=$broker_id");
	if(mysqli_affected_rows($conn)>0){
		mysqli_query($conn, "UPDATE users SET chips = chips + $available_bal WHERE id=$receiver_id");
		 if(mysqli_affected_rows($conn)>0){
			 
			 
			 //insert in transfers
		   mysqli_query($conn, "INSERT INTO `sh_sf_transfers` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`, `send_to`) VALUES ($broker_id,'$ref','-$trmoney','$tm','A2A Transfer','Delivered','$tremail')");
		   //insert to deposits
		    mysqli_query($conn, "INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`) VALUES ($receiver_id,'$ref','$available_bal','$tm','A2A Transfer','Received')");
		   
			//broker records on logs
			$af = $broker_balance - $trmoney;
		   $bkr = mysqli_query($conn, "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($broker_id,'A2A Transfer Debit','-$trmoney','$tm','chips','$broker_balance','$af')");
		   
		   if(mysqli_affected_rows($conn)>0){
		   //user log records on logs
			$afu = $receiver_balance + $available_bal;
		   mysqli_query($conn, "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($receiver_id,'A2A Transfer Credit','$available_bal','$tm','chips','$receiver_balance','$afu')");
		   
		   //deposit commission
		   $trdep= mysqli_fetch_assoc(mysqli_query($conn,"SELECT depo_comm FROM risk_management"));
		   $dpc = $trmoney * $trdep['depo_comm']/100;
		   $net_dpc = round($dpc);
		   mysqli_query($conn, "UPDATE users SET chips = chips + $net_dpc WHERE id=$broker_id");
		   if(mysqli_affected_rows($conn)>0){
			   //insert to deposits
		    mysqli_query($conn, "INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `date`, `type`, `status`) VALUES ($broker_id,'$ref','$net_dpc','$tm','Deposit Commission','Received')");
			//insert to log history
			$bk_bal = $broker_balance + $net_dpc;
			mysqli_query($conn, "INSERT INTO `sh_sf_points_log` (`user_id`, `comment_id`, `amount`, `date`, `type`, `bf`, `af`) VALUES ($broker_id,'Deposit Commission','$net_dpc','$tm','chips','$broker_balance','$bk_bal')");
			//success message
			echo "<div style='background:#f3ff7a'>". Lang::$word->AFF_SUCCESSFULLY_TRANSFERRED ." : <span style='color:#009688;font-weight:bold'> ".$available_bal. " ".$receiver_curr."</span></div>";
		    }
		   }
		   
		   
		   
		  }else{
			mysqli_query($conn, "UPDATE users SET chips = chips + $trmoney WHERE id=$broker_id");
			echo '<i class="icon warning sign"></i> Not able to transfer. Try again.';
			die();
		 }
	}
	
	
	
}

/////FOR TRANSFER LOAD MORE..
  elseif(isset($_POST['method']) && $_POST['method'] == 'ltransferb'){  
   $usid = $_POST['usid'];
   $rc = $_POST['rc'];
   $query = "SELECT * FROM sh_sf_transfers WHERE user_id=$usid ORDER BY date DESC LIMIT $rc, 50";
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
	  <div class="ccredit">A2A Transfer<span class="deptright"><?php echo $amount;?></span></div>
	  <div class="ccredit"> Sent TO: <span class="deptright"><?php echo $sendto;?></span></div>
	  <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> Date: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  }
  if ($result->num_rows > 1) {
	echo '<div id="lrembankb" class="tload">Load More...</div>';
  } else {
	  echo 'No more records Found..';
  }
 }





 ?>