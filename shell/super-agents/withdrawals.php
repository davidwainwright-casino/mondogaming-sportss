<?php error_reporting(0);include_once('../db.php');
$usid = $_POST['usid'];

if(isset($_POST['method']) && $_POST['method'] == 'withdrawalssa'){
	$usid = $_POST['usid'];
?>

<h2>Earnings Withdrawals</h2>

<div class="agdwn">Withdrawals and History Form</div>
<div class="noteus">Note* : You need to transfer your super agent/exchange broker balance to your betting account and then withdrawal or play from there. You are allowed to transfer balance only once in 30 days or once per month. Transfer amount shown is history is your defualt betting account currency.</div>
</br></br>




<div class="transferbb">
<h4>Transfer Balance to Betting Account</h4>
<div class="shreturn"></div>
<div class="shsuccess">
<input type="number" placeholder="Amount" id="saagpaynum" required>
<div class="sashowvaltotr"></div>
</div>
</div>





</br></br>
<div id="withbroker">
<h5 class="dphist">Super Agent Transfer History</h5>
  <?php
   $usid = $_POST['usid'];	 
   $query = "SELECT * FROM sh_sf_deposits WHERE user_id=$usid AND type='sa_commission' ORDER BY date DESC LIMIT 50";
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
	  <div class="ccredit"> <a style="color:#f00">Transferred</a><span class="deptright"><?php echo round($amount,2);?></span></div>
	  <div class="ccredit"> FROM:<span class="deptright">SA Commission</span></div>
	  <div class="ccredit"> Ref: <span class="deptright"><?php echo $ref;?></span></div>
	  <div class="ccredit"> Date: <span class="deptright"><?php echo $date;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  } else {
	 echo '<div style="padding:10px">No Active Records Found</div>';
  }
  
   echo '</div></br></br></br>';
  
  if ($result->num_rows > 49) {
	echo '<div id="lrem" class="wload">Load More...</div>';
  }
  echo '</div>';
} ?>

</br></br></br></br>