<?php $db = $_SERVER['DOCUMENT_ROOT']."/shell/"; include_once($db."db.php");
if(isset($_POST['method']) && $_POST['method'] == 'gcontrol') {
	$usid = $_POST['usid'];
	$b_update ="UPDATE users SET active = 'n' WHERE id='$usid'";
	 mysqli_query($conn,$b_update);
	 
}?>
<div id="jinx">
<div class="dptext"><?= Lang::$word->ACC_GAMBLING_CONTROL; ?></div>
 <div class="depositform" style="border: 1px solid #ff9f1b;">
	<h6><?= Lang::$word->ACC_CONTROL_BREACK; ?></h6>
     <?= Lang::$word->ACC_CONTROL_BREACK_NOTE; ?> </br>
	 <h5><?= Lang::$word->ACC_CONTROL_BREACK_RESPO; ?></h5>
	<button id="myBtn"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_NOTE_MORE; ?></button>
	</div>
<div id="mancashback">
	<div class="promo-info">
	<h3><?= Lang::$word->ACC_CONTROL_BREACK_EXCLUSION; ?></h3>
	<div class="deactme"><?= Lang::$word->ACC_CONTROL_BREACK_DEACTIVE; ?></div>
	</div>
</div>





<div class="counc">
 <h3><?= Lang::$word->ACC_CONTROL_BREACK_OUT; ?></h3>
    <?= Lang::$word->ACC_CONTROL_BREACK_SHOULD; ?>
  </div>
	
</div>	



 