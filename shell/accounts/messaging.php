<?php $db = $_SERVER['DOCUMENT_ROOT']."/shell/"; include_once($db."db.php");
if(isset($_POST['method']) && $_POST['method'] == 'msg') {
	$usid = $_POST['usid'];
	//$esk = htmlentities($_POST['msg']);
	//$msg = addslashes($_POST['msg']);
	$msg =  mysqli_real_escape_string($conn, $_POST['msg']);
	$date = time();
	$res = $conn -> query("SELECT fname, lname FROM users WHERE id='$usid'");
	$row = $res->fetch_assoc();
	$fnn = $row['fname'].' '.$row['lname'];
	$qinsert = "INSERT INTO `sh_sf_inbox` (`send_from`, `name`, `name_rec`, `send_to`, `user`, `date_record`) VALUES ($usid, '$fnn', '$fnn', $usid,'$msg','$date')";
	 if(mysqli_query($conn,$qinsert)){
	$query = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$usid ORDER BY id DESC LIMIT 10) AS sq ORDER BY id ASC";
     $result = mysqli_query($conn, $query);
      if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['support'] !== NULL){   
        echo '<div class="csupport">'.$row['support'].' <div class="msgbtn"><span class="stf">staff</span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['user'] !== NULL){
		echo '<div class="cusers"> '.$row['user'].' <div class="msgbtn"><span class="stf usr">You</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }
		}
		die();
	 
} 


//load more msg
elseif(isset($_POST['method']) && $_POST['method'] == 'moremsg'){
   $usid = $_POST['usid'];
   $rc = $_POST['rc']; 
   $query = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$usid ORDER BY id DESC LIMIT  $rc, 20) AS sq ORDER BY id ASC";   
   echo '<input type="hidden" class="cfvalue" value="6">';
     $result = mysqli_query($conn, $query);
      if ($result->num_rows > 0) {
		  if($result->num_rows >= 1){
		  echo '<div class="msgload" id="msgmore"> Fetch older messages...</div>';
		  } else {
			  echo 'No more messages';
		  }
	   while($row = $result->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['support'] !== NULL){   
        echo '<div class="csupport">'.$row['support'].' <div class="msgbtn"><span class="stf">staff</span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['user'] !== NULL){
		echo '<div class="cusers"> '.$row['user'].' <div class="msgbtn"><span class="stf usr">You</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }
 die();
}





///mark as seen on click
else if(isset($_POST['method']) && $_POST['method'] == 'seen') {
	$usid = $_POST['usid'];
	$gupdate = "UPDATE `sh_sf_inbox` SET `seen` = '1' WHERE send_to = '$usid' AND seen = '0'";
	mysqli_query($conn,$gupdate);
	$delt = "DELETE FROM sh_sf_inbox WHERE send_to = '$usid' AND date_record < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 90 DAY))";
	mysqli_query($conn,$delt);
	die();
}?>
<?php $cpa = "SELECT * FROM sh_sf_inbox WHERE send_to=$usid AND seen = 0";
     $cpar = mysqli_query($conn, $cpa);
	 $ccount = $cpar->num_rows;
	 $sho = mysqli_fetch_assoc(mysqli_query($conn,"SELECT message FROM sh_sf_users_notice WHERE uid=$usid"));
	 ?>
<div id="jinx">
<div class="dptext"><?= Lang::$word->ACC_MESSAGING_TITLE; ?></div>
 <div class="depositform chat">
	<h6><?= Lang::$word->ACC_MESSAGING_TITLE_H6; ?></h6>
     <?= Lang::$word->ACC_MESSAGING_H6_DESC; ?> </br>
	 <h5><?= Lang::$word->ACC_MESSAGING_TITLE_H5; ?></h5>
	<button id="myBtn"><?= Lang::$word->ACC_MESSAGING_TITLE_MORE; ?></button>
	</div>
<div id="mancashback">
	<div class="promo-info"></br>
	<?php if(!empty($sho['message'])):?>
	<div class="nottext">
	<?php echo trim($sho['message']);?>
	</div>
	<?php endif;?>
	
	</br>
	<p class="readrow"><?= Lang::$word->ACC_MESSAGING_TITLE_INBOX; ?></p></br>
	
	
	<div class="minbox"><?= Lang::$word->ACC_MESSAGING_INBOX; ?> (<?php echo $ccount;?>)</div>
	</div></br>
	
	
	
	
	
	
    <div class="inboxwrapper" style="display:none">
	<div class="topinbox">
	 <input type="hidden" class="cfvalue" value="6">
	<?php $quk = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$usid ORDER BY id DESC LIMIT 6) AS sq ORDER BY id ASC";
     $resk = mysqli_query($conn, $quk);
      if ($resk->num_rows > 0) {
		  echo '<div class="msgload" id="msgmore"> '. Lang::$word->ACC_MESSAGING_INBOX_OLDER.'</div>';
	   while($row = $resk->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['support'] !== NULL){   
        echo '<div class="csupport">'.$row['support'].' <div class="msgbtn"><span class="stf">'. Lang::$word->ACC_MESSAGING_INBOX_STAFF.'</span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['user'] !== NULL){
		echo '<div class="cusers"> '.$row['user'].' <div class="msgbtn"><span class="stf usr">'. Lang::$word->ACC_MESSAGING_INBOX_YOU.'</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }?>
	 
	
	
	</div>
	
	
	
	<div class="displayerr"></div>
	 <div id="msgme">
	  <textarea class="large" id="msginbox" name="msginbox"></textarea> <span class="subinbox"><?= Lang::$word->ACC_MESSAGING_INBOX_SEND; ?></span>
	 </div>
	
	</div>
	
	
	
	
	
	
	
	
</div>





<div class="counc">
 <h3><?= Lang::$word->ACC_MESSAGING_QUICK_MSG; ?></h3>
    <?= Lang::$word->ACC_MESSAGING_QUICK_MSG_DESC; ?>
  </div>
	
</div>	



 