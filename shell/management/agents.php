<?php error_reporting(0);include('../db.php');

  //for prematch active slips
  $rc = $_POST['rc'];
  if($_POST['method'] == 'userslist'){
	$sql = "SELECT * FROM users WHERE type = 'agent' ORDER BY chips DESC LIMIT 50";
	$prec = "SELECT id FROM users WHERE type = 'agent'";
  } else if($_POST['method'] == 'usersmore'){
	$sql = "SELECT * FROM users WHERE type = 'agent' ORDER BY chips DESC LIMIT $rc, 50";
} else if($_POST['method'] == 'sdate'){
  $dt1 = $_POST['dt1'];
  $dt2 = $_POST['dt2'];
  $start = strtotime($dt1);
  $end = strtotime($dt2);
  $st =  date('y-m-d', $start);
  $ed = date('y-m-d', $end);
   echo '<div class="timefram">Date Filter '.$dt1.' TO '.$dt2.'</div>';
  $sql = "SELECT * FROM users WHERE created >= FROM_UNIXTIME($start) AND created <= FROM_UNIXTIME($end) AND type='agent' ORDER BY created DESC LIMIT 500";
 $prec = "SELECT id FROM users WHERE created >= FROM_UNIXTIME($start) AND created <= FROM_UNIXTIME($end) AND type='agent'";  
	
 } else  if($_POST['method'] == 'esearch'){
	$es = $_POST['es'];
  $idt = $_POST['idt'];
  if (is_numeric($es)){
	$sql = "SELECT * FROM users WHERE id = $es AND type='agent'";
	$prec = "SELECT id FROM users WHERE id = $es AND type='agent'";
  } else {
	$sql = "SELECT * FROM users WHERE email LIKE '%".$es."%' AND type='agent'";
	$prec = "SELECT id FROM users WHERE email LIKE '%".$es."%' AND type='agent'";
  }
	
}

   $result = $conn->query($sql);
?>
 
<?php
  $netcc = mysqli_query($conn, $prec);
  $counter = $netcc->num_rows;?>
  <div class="showmo">Showing <?php if(empty($rc)){ echo '0';} else { echo $rc;}?> to <span class="hoper">50</span> OF <?php echo $counter;?></div>

<div class="row screen-block-2 tablet-block-1 mobile-block-1 phone-block-1 half-gutters">
<input type="hidden" class="ticketvalue" value="50">
  <?php foreach($result as $row):?>
  <div class="column" id="item_<?php echo $row['id'];?>">
    <div class="yoyo card">
      <div class="grey header">
        <div class="row horizontal-gutters align-middle">
          <div class="column shrink"><img src="/uploads/avatars/<?php echo $row['avatar'] ? $row['avatar'] : "blank.png" ;?>" alt="" class="yoyo avatar image"></div>
          <div class="column">
            <h4>
    
              <a class="white" href="/admin/users/edit/<?php echo $row['id'];?>/"><?php echo $row['fname'];echo ' '; echo  $row['lname'];?></a>
              
            </h4>
          </div>
          <div class="column shrink">
            <a class="yoyo icon circular white button" data-dropdown="#userDrop_<?php echo $row['id'];?>">
            <i class="icon horizontal ellipsis"></i>
            </a>
            <div class="yoyo dropdown small menu pointing top-right" id="userDrop_<?php echo $row['id'];?>">
              
              <a class="item" href="/admin/users/edit/<?php echo $row['id'];?>/"><i class="icon pencil"></i>
              <span class="padding-left">Account Edit</span></a>
             
              <a class="item" href="/admin/bet-history/?usid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Bet History</span></a>
			  
			  <a class="item" href="/admin/bet-history/?aid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Affiliates Bet History</span></a>
			  
			  <a class="item" href="/admin/agents-credit-history/?usid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Credit History</span></a>
			  
			  <a class="item" href="/admin/agents-credit-history/?usid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Agent Credit History</span></a>
			  
			   <a class="item" href="/admin/deposits/?usid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Deposit History</span></a>
			  
			  <a class="item" href="/admin/withdrawals/?usid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Withdrawal History</span></a>
			  
			  <a class="item" href="/admin/trans-log/?usid=<?php echo $row['id'];?>"><i class="icon history"></i>
              <span class="padding-left">Transactions Logs</span></a>
			  
              
              <div class="yoyo basic divider"></div>
              <a class="item" href="#">
              <i class="icon trash"></i>
              <span class="padding-left">Trash</span></a>
              
            </div>
          </div>
        </div>
      </div>
      <div class="footer">
        <div class="row align-middle">
		
          <div class="column">
            <div class="yoyo small divided horizontal list">
			
              <div class="item">
                <div class="header">
                  <span class="yoyo caps text">EMAIL</span>
                  <span class="yoyo caps text"><a href="/admin/mailer/?email=<?php echo urlencode($row['email']);?>"><?php echo $row['email'];?></a>
                  </span>
                </div>
              </div>
              
              <div class="item">
                <div class="header">
                  <span class="yoyo caps text">Joined</span>
                  <span class="yoyo caps text"><?php echo $row['created'];?></span>
				  <span class="bachips"><?php echo $row['chips'];?></span>
                </div>
              </div>
			  
            </div>
          </div>
          <div class="column shrink">
            <?php $status = $row['active']; if($status == 'y'):?>
			<span class="yoyo small positive label">Active</span>
			<?php elseif($status =='n'):?>		
			<span class="yoyo small primary label addAction">Inactive</span>
			<?php elseif($status == 't'):?>
			<span class="yoyo small black label">Pending</span>
			<?php elseif($status == 'b'):?>
			<span class="yoyo small negative label">Banned</span>';
			<?php endif;?>
            <?php echo $row['type'];?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  
  
</div>



 
   <?php if ($result->num_rows > 1) {
	echo '<div id="'.$counter.'" class="lodo">Load More...</div>';
    }?>