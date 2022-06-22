<?php error_reporting(0);include('../../db.php');

if(isset($_POST['method']) && $_POST['method'] == 'fshow'){
	$usid = $_POST['usid'];
	$conn->query("UPDATE `sh_sf_inbox` SET `seen` = '1' WHERE send_to = '$usid' AND support IS NULL");
	
	$query = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$usid ORDER BY id DESC LIMIT 20) AS sq ORDER BY id ASC";
     $result = mysqli_query($conn, $query);
	 echo '<div class="bkkof"><i class="icon angle double left"></i> Back</div>';
	  echo '<input type="hidden" class="cfvalue" value="20">';
	  echo '<div class="kgo" id="'.$usid.'">.</div>';
	 echo '<div class="topinbox" id="'.$usid.'">';
	 
      if ($result->num_rows > 0) {
		  if ($result->num_rows > 19) {
		  echo '<div class="msgload" id="msgmore"> Load older messages...</div>';
		  }
	   while($row = $result->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['user'] !== NULL){   
        echo '<div class="csupport">'.$row['user'].' <div class="msgbtn"><span class="stf"><a href="/admin/users/edit/'.$row['send_to'].'">'.$row['name'].'</a></span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['support'] !== NULL){
		$sen = $row['seen'];
		echo '<div class="cusers"> '.$row['support'].' <div class="msgbtn"><span class="seek'.$sen.'">.</span> <span class="stf usr">'.$row['name'].' [staff]</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }
	  echo "</div>";
	
	
} 

//send message
else if(isset($_POST['method']) && $_POST['method'] == 'sendmsg'){
	$usid = $_POST['usid'];
	$sendtoid = $_POST['sendto'];
	//$esk = htmlentities($_POST['msg']);
	$msg = addslashes($_POST['msg']);
	$date = time();
	$res = $conn -> query("SELECT fname, lname FROM users WHERE id='$usid'");
	$row = $res->fetch_assoc();
	$fnn = $row['fname'].' '.$row['lname'];
	
	$civ = $conn -> query("SELECT fname, lname FROM users WHERE id='$sendtoid'");
	$civr = $civ->fetch_assoc();
	$receiver = $civr['fname'].' '.$civr['lname'];
	
	$qinsert = "INSERT INTO `sh_sf_inbox` (`send_from`, `name`, `name_rec`, `send_to`, `support`, `date_record`) VALUES ($usid, '$fnn', '$receiver', $sendtoid,'$msg','$date')";
	
	
	 if(mysqli_query($conn,$qinsert)){
	$query = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$sendtoid ORDER BY id DESC LIMIT 100) AS sq ORDER BY id ASC";
     $result = mysqli_query($conn, $query);
	 echo '<div class="bkkof"><i class="icon angle double left"></i> Back</div>';
	 echo '<div class="topinbox" id="'.$sendtoid.'">';
	  echo '<div class="kgo" id="'.$sendtoid.'">.</div>';
      if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['user'] !== NULL){   
        echo '<div class="csupport">'.$row['user'].' <div class="msgbtn"><span class="stf"><a href="/admin/users/edit/'.$row['send_to'].'">'.$row['name_rec'].'</a></span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['support'] !== NULL){
			$sen = $row['seen'];
		echo '<div class="cusers"> '.$row['support'].' <div class="msgbtn"><span class="seek'.$sen.'">.</span> <span class="stf usr">'.$row['name'].' [staff]</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }
	  echo "</div>";
	 }
}

//loadmore
elseif(isset($_POST['method']) && $_POST['method'] == 'moremsg'){
   $usid = $_POST['usid'];
   $rc = $_POST['rc']; 
   $query = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$usid ORDER BY id DESC LIMIT  $rc, 20) AS sq ORDER BY id ASC";   
   echo '<input type="hidden" class="cfvalue" value="20">';
     $result = mysqli_query($conn, $query);
      echo '<div class="kgo" id="'.$sendtoid.'">.</div>';
      if ($result->num_rows > 0) {
	   if($result->num_rows >= 1){
		  echo '<div class="msgload" id="msgmore"> Fetch older messages...</div>';
		  } else {
			  echo 'No more messages';
		  };
	   while($row = $result->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['user'] !== NULL){   
        echo '<div class="csupport">'.$row['user'].' <div class="msgbtn"><span class="stf"><a href="/admin/users/edit/'.$row['send_to'].'">'.$row['name_rec'].'</a></span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['support'] !== NULL){
			$sen = $row['seen'];
		echo '<div class="cusers"> '.$row['support'].' <div class="msgbtn"><span class="seek'.$sen.'">.</span> <span class="stf usr">'.$row['name'].' [staff]</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }
	 
}







?>