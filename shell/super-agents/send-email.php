<?php error_reporting(0);include_once('../db.php');
if(isset($_POST['method']) && $_POST['method'] == 'mailsubmit'){
	$usid = $_POST['usid'];
	$cqres = mysqli_query($conn, "SELECT email FROM users WHERE id ='$usid'");
	$check = $cqres->fetch_assoc();
	$smail = $check['email'];
	$email = $_POST['gtmail'];
	$subj = $_POST['subject'];
	$msgcontent = $_POST['msgcontent'];
	
$to = '$email';
$subject = '$subj';
$from = '$smail';
 
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
// Create email headers
$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
 
// Compose a simple HTML email message
$message = '<html><body>';
$message .= '<p style="color:#080;font-size:18px;">'.$msgcontent.'</p>';
$message .= '</body></html>';
 
// Sending email
if(mail($to, $subject, $message, $headers)){
    echo 'Your mail has been sent successfully.';
} else{
    echo 'Unable to send email. Please try again.';
}
	
	
	die();
}
if(isset($_POST['method']) && $_POST['method'] == 'sendemailsa'){
	$usid = $_POST['usid'];
	$email = $_POST['email'];
	$cqres = mysqli_query($conn, "SELECT fname FROM users WHERE email ='$email'");
	$check = $cqres->fetch_assoc();
?>
<div class="wrapallmsg">
<div class="backmeif"><i class="icon chevron right"></i> Go Back</div>
 <h2>Send Email to <?php echo $check['fname'];?></h2>
 <p>Message will be sent to agent's registered email id. Replies to your message will be routed to your registered email id.</p>
 <div id="shower"></div>
<input id="gtmail" value="<?php echo $email;?>" hidden>

<div class="mailwrapper">
<label for="subject">Mail Subject</label>
<div class="submailss">
<input type="text" placeholder="Subject" id="submail" required>
</div>
 
 <label for="paytype">Message Content. Max.500 characters</label>
 <div class="dcontent">
  <textarea name="msgcontent" form="msgform" id="msgcontent">Type your message...</textarea>
 </div>
  <div class="msgrequest">Send Email</div>
  </div>
 

<?php } ?>