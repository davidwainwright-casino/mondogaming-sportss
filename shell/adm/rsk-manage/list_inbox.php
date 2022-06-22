<?php error_reporting(0);include('../../db.php');

if(isset($_POST['method']) && $_POST['method'] == 'listbox'){
 $query = "SELECT * FROM `sh_sf_inbox` WHERE id IN (SELECT max(id) FROM sh_sf_inbox GROUP BY send_to) ORDER BY date_record DESC LIMIT 20";
 $qcount = mysqli_fetch_array(mysqli_query($conn, "SELECT count(id) as cc FROM sh_sf_inbox WHERE support IS NULL AND seen = 0"));
 echo '<div class="ccseen" id="'.$qcount['cc'].'"></div>';
 
     $result = mysqli_query($conn, $query);
	  echo '<input type="hidden" class="cfvaluex" value="20">';
	  echo '<input type="text" class="lsearch" id="lsearch" placeholder="Search by name."> <i id="findsrx" class="icon find"></i>';
	  echo '<div id="shso">';
      if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {?>
	   
	   <ul class="userlisting">
	     <li class="fshow" id="<?php echo $row['send_to'];?>">
		 
		 <?php if($row['seen'] == '1'):?>
		 <a id="<?php echo $row['send_from'];?>" class="usrname"><div class="bgavt">.</div> <?php echo $row['name_rec'];?></a></br>
		 <div class="useen"><?php echo substr($row['user'], 0, 60);?> <?php $sup = substr($row['support'], 0, 60);if(!empty($sup)){ echo '<div class="subbb"><i class="icon arrow backward"></i> '.$sup.'</div>';};?><span class="timup"><?php echo date ("m-d-y H:i", $row['date_record']);?></span></div>
		 <?php else:?>
		 <a id="<?php echo $row['send_from'];?>" class="usrname"><div class="bgavt">.</div> <?php echo $row['name_rec'];?></a></br>
		 <div class="unseen"><?php echo substr($row['user'], 0, 60);?> <?php $sup = substr($row['support'], 0, 60);if(!empty($sup)){ echo '<div class="subbb"><i class="icon arrow backward"></i> '.$sup.'</div>';};?> <span class="timup"><?php echo date ("m-d-y H:i", $row['date_record']);?></span></div>
		 <?php endif;?>
		 </li>
	  </ul>	
		   
	   <?php }
	  }
	  
	  if ($result->num_rows > 2) {
		  echo '<div class="loadyes" id="moreyes">Load More...</div>';
	  }
	  echo '</div>';
	
	
}

//search box search user message by name

if(isset($_POST['method']) && $_POST['method'] == 'search_box'){
	$nam = $_POST['nam'];

	 $query = "SELECT * FROM `sh_sf_inbox` WHERE name_rec LIKE '%".$nam."%' GROUP BY send_to";
     $result = mysqli_query($conn, $query);
      if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {?>
	   
	   <ul class="userlisting">
	     <li class="fshow" id="<?php echo $row['send_to'];?>">
		 
		 <?php if($row['seen'] == '1'):?>
		 <a id="<?php echo $row['send_from'];?>" class="usrname"><div class="bgavt">.</div> <?php echo $row['name_rec'];?></a></br>
		 <div class="useen"><?php echo substr($row['user'], 0, 60);?> <?php $sup = substr($row['support'], 0, 60);if(!empty($sup)){ echo '<div class="subbb"><i class="icon arrow backward"></i> '.$sup.'</div>';};?><span class="timup"><?php echo date ("m-d-y H:i", $row['date_record']);?></span></div>
		 <?php else:?>
		 <a id="<?php echo $row['send_from'];?>" class="usrname"><div class="bgavt">.</div> <?php echo $row['name_rec'];?></a></br>
		 <div class="unseen"><?php echo substr($row['user'], 0, 60);?> <?php $sup = substr($row['support'], 0, 60);if(!empty($sup)){ echo '<div class="subbb"><i class="icon arrow backward"></i> '.$sup.'</div>';};?> <span class="timup"><?php echo date ("m-d-y H:i", $row['date_record']);?></span></div>
		 <?php endif;?>
		 </li>
	  </ul>	
		   
	   <?php }
	  }
	
}


//more list
elseif(isset($_POST['method']) && $_POST['method'] == 'morelist'){
	$rc = $_POST['rc']; 
	$query = "SELECT * FROM `sh_sf_inbox` WHERE id IN (SELECT max(id) FROM sh_sf_inbox GROUP BY send_to) ORDER BY date_record DESC LIMIT $rc, 20";
     $result = mysqli_query($conn, $query);
	  echo '<input type="hidden" class="cfvaluex" value="20">';
      if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {?>
	   <ul class="userlisting">
	     <li class="fshow" id="<?php echo $row['send_to'];?>">
		 
		 <?php if($row['seen'] == '1'):?>
		 <a id="<?php echo $row['send_from'];?>" class="usrname"><div class="bgavt">.</div> <?php echo $row['name_rec'];?></a></br>
		 <div class="useen"><?php echo substr($row['user'], 0, 60);?> <?php $sup = substr($row['support'], 0, 60);if(!empty($sup)){ echo '<div class="subbb"><i class="icon arrow backward"></i> '.$sup.'</div>';};?><span class="timup"><?php echo date ("m-d-y H:i", $row['date_record']);?></span></div>
		 <?php else:?>
		 <a id="<?php echo $row['send_from'];?>" class="usrname"><div class="bgavt">.</div> <?php echo $row['name_rec'];?></a></br>
		 <div class="unseen"><?php echo substr($row['user'], 0, 60);?> <?php $sup = substr($row['support'], 0, 60);if(!empty($sup)){ echo '<div class="subbb"><i class="icon arrow backward"></i> '.$sup.'</div>';};?> <span class="timup"><?php echo date ("m-d-y H:i", $row['date_record']);?></span></div>
		 <?php endif;?>
		 </li>
	  </ul>	
		   
	   <?php }
	  }
	   
	   if ($result->num_rows > 0) {
		  echo '<div class="loadyes" id="moreyes">Load More...</div>';
	   } else {
		   echo '<div class="loadyes" id="moreyes">No more records found....</div>';
	   }
	  
	
}

//newmsg
elseif(isset($_POST['method']) && $_POST['method'] == 'newmsg'){?>
    </br>
	<div class="shownews">
	<div class="searchwrap">
     Type user ID or Email address of the recepient</br></br>
	<input type="text" class="esearch" id="esearch" placeholder="Search by user ID or Email.">
        <i id="findsr" class="icon find"></i>
	</div></br>
	<div class="qresult"></div>
	</div>
	
			
<?php }

//list search
else if(isset($_POST['method']) && $_POST['method'] == 'listsearch'){
	$lid = $_POST['lid'];
	$res = $conn -> query("SELECT id, email FROM users WHERE id = $lid");
	$row = $res->fetch_assoc();
	?>
     <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">ID</th>
        <th data-sort="string">Email</th>
		<th data-sort="int">Actions</th>
      </tr>
    </thead>

	 <tr>
	 <td><?php echo $row['id'];?></td>
	 <td><?php echo $row['email'];?></td>
	 <td><a id="<?php echo $row['id'];?>" class="csend"><?php if(!empty($row['id'])):?> ClickSend<?php endif;?></a></td>
	 </tr>
	</table>
	
<?php }

//list search new insert
else if(isset($_POST['method']) && $_POST['method'] == 'csend'){
	$csid = $_POST['csid'];
	$chk = $conn -> query("SELECT id FROM sh_sf_inbox WHERE send_to=$csid LIMIT 1");
	$crow = $chk->fetch_assoc();
	if ($chk->num_rows > 0) {
	$query = "SELECT * FROM (SELECT * FROM sh_sf_inbox WHERE send_to=$csid ORDER BY id DESC LIMIT 100) AS sq ORDER BY id ASC";
     $result = mysqli_query($conn, $query);
	
	 echo '<div class="topinbox" id="'.$sendtoid.'">';
	  echo '<div class="kgo" id="'.$sendtoid.'">.</div>';
      if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {
		$date = date ("m-d-y H:i", $row['date_record']);
		if($row['user'] !== NULL){   
        echo '<div class="csupport">'.$row['user'].' <div class="msgbtn"><span class="stf"><a href="/admin/users/edit/'.$row['send_to'].'">'.$row['name_rec'].'</a></span> <span class="msgdt">'.$date.'</span></div></div>';
		} else if($row['support'] !== NULL){
		echo '<div class="cusers"> '.$row['support'].' <div class="msgbtn"><span class="stf usr">'.$row['name'].' [staff]</span> <span class="msgdt usr">'.$date.'</span></div></div>';
	    }
	   }
      }
	  echo "</div>";
	  die();
	}
	
	
	$res = $conn -> query("SELECT fname, lname, type FROM users WHERE id='$csid'");
	$row = $res->fetch_assoc();
	$fnn = $row['fname'].' '.$row['lname'];
	$tn = $row['type'];
	 echo '<div class="bkkof"><i class="icon angle double left"></i> Back</div>';
	echo 'Sending message to <b>'.$fnn.'</b> ['.$tn.']</br>';
	echo 'Type your message below and send...';
	echo '<div id="'.$csid.'" class="topinbox">.....................</div>';
	
}

//notice

else if(isset($_POST['method']) && $_POST['method'] == 'notice'){
	$sho = mysqli_fetch_assoc(mysqli_query($conn,"SELECT noticef FROM settings"));
	$agents = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sh_messages_board"));
	//var_dump($sho);
	
	?>
	
  <div class="noticewrapper"></br>
  <h3>General Notice</h3>
  <p style="padding:5px;font-size:12px">This notice is general and displayed to all users. Notice here is displayed under bell icon of the front header menu right. First publish your notice from content/page/noticeboard and submit short synopsis here. Link to noticeboard is already attached so no links to be provided. For eg. We have new website skins available from 15th Nov.. Update blank to remove notice</p>
	<textarea id="unotice" name="ddnotice" rows="6" cols="36"><?php echo $sho['noticef'];?></textarea>
	</br>
	<div class="upnotice"></div>
	  <button type="button" id="updatenotice" class="yoyo primary button ubal">Update Notice</button>
	  
	  </br></br></br>
	  
	<h3>Affiliates/agent Notice</h3>
  <p style="padding:5px;font-size:12px">This notice is specifically for agents/affiliates. For individual you can use personal profile page notice service. Message here appears in top of content area within affiliate/agent dashboard</p>
	<textarea id="agunotice" name="ddnotice" rows="6" cols="36"><?php echo $agents['agent_notice'];?></textarea>
	</br>
	<div class="affnotice"></div>
   <button type="button" id="affiliatenotice" class="yoyo primary button ubal">Update Notice</button>  
   
   </br></br></br>
   
   <h3>Super Agent Notice</h3>
  <p style="padding:5px;font-size:12px">This notice is specifically for Super agents/exchange brokers. For individual you can use personal profile page notice service. Message here appears in top of super agent dashboard</p>
	<textarea id="saunotice" name="ddnotice" rows="6" cols="36"><?php echo $agents['super_agent'];?></textarea>
	</br>
	<div class="sanotice"></div>
   <button type="button" id="supernotice" class="yoyo primary button ubal">Update Notice</button>  
	  
	  </br></br></br>
	  
	  
	  
  </div>
  
	</div>
<?php }


//submit notice
else if(isset($_POST['method']) && $_POST['method'] == 'subnotice'){
 $user_id = $_POST['usid'];
 $sg = $_POST['msg'];
 $msg = mysqli_real_escape_string($conn, $sg);
 $dt = time();

 
	mysqli_query($conn,"UPDATE settings SET noticef = '$msg'");
	
  if(mysqli_affected_rows($conn) > 0){
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
     }
 }


//submit agent notice
else if(isset($_POST['method']) && $_POST['method'] == 'affnotice'){
 $user_id = $_POST['usid'];
 $sg = $_POST['msg'];
 $msg = mysqli_real_escape_string($conn, $sg);
 $dt = time();

 
	mysqli_query($conn,"UPDATE sh_messages_board SET agent_notice = '$msg'");
	
  if(mysqli_affected_rows($conn) > 0){
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
     }
 }

//submit agent notice
else if(isset($_POST['method']) && $_POST['method'] == 'supernotice'){
 $user_id = $_POST['usid'];
 $sg = $_POST['msg'];
 $msg = mysqli_real_escape_string($conn, $sg);
 $dt = time();

 
	mysqli_query($conn,"UPDATE sh_messages_board SET super_agent = '$msg'");
	
  if(mysqli_affected_rows($conn) > 0){
	       echo 'Updated Successfully';
    	   }else {
            echo "Couldn't Update";
     }
 }





































?>