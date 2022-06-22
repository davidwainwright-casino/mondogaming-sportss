<?php $db = $_SERVER['DOCUMENT_ROOT']."/shell/"; include_once($db."db.php");
if(isset($_POST['method']) && $_POST['method'] == 'clearpromo') {
	$usid = $_POST['usid'];
	$b_update ="UPDATE users SET promo = '0' WHERE id='$usid'";
	 mysqli_query($conn,$b_update);
}?>
<div id="jinx">
<div class="dptext"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT; ?></div>
 <div class="depositform" style="border: 1px solid #ff9f1b;">
	<h6><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_H6; ?></h6>
     <?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_NOTE; ?></br>
	 <h5><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_NOTE_H5; ?></h5>
	<button id="myBtn"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_NOTE_MORE; ?></button>
	</div>
	
	<?php $query = "SELECT * FROM users WHERE id=$usid";
   $result = mysqli_query($conn, $query);
   $row = $result->fetch_assoc();
   $chips = $row['chips'];
   $promo = $row['promo'];?>
	
	
	
	<div id="mancashback">
	<div class="promo-info">
        <?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_INFO1; ?></br>
        <?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_INFO2; ?></div>
	
	<span class="ccbalance"><?php echo $promo;?></span> <span class="clearpromo"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_CLEAR; ?></span>
	
	</div></br></br>
	
	
	
	
	
	
	
	
 
  <div class="boverv" style="background:#FFEB3B"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_OVERVIEW; ?></div></br></br>
  <div class="isportswrap">
    <div class="ilabel"><?= Lang::$word->ACC_PROMO_Promo_Bet_Credit_CASINO_SPORTS; ?></div>
     <div class="itop1">
      <span class="xpwith"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_WITHDRAWABLE; ?></span>   <span class="xpnumb"><?php echo '0.00';?></span>
     </div>
	 <div class="itop2">
      <span class="xpwith"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_AMONT; ?></span>   <span class="xpnumb"><?php echo $promo;?></span>
     </div>
	 <div class="itop3">
      <span class="xpwith tt"><?= Lang::$word->ACC_PROMO_PROMO_BET_CREDIT_TOTAL; ?></span>   <span class="xpnumb tt"><?php echo round($promo, 2);?></span>
     </div>
	</div>
	
	
	
	
	
	
	
	
	
	
	
</div>	



 