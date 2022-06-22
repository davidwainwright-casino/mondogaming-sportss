<div id="dashboard">
   <div class="accontrol">
    <div class="yoyo-grid">
      <div class="row align-middle">
        <div class="columns shrink">
          <!--- <input type="file" name="avatar" data-process="true" data-action="avatar" data-class="rounded small" data-type="image" data-exist=" echo UPLOADURL;?>/avatars/echo (Auth::$udata->avatar) ? Auth::$udata->avatar : 'blank.png';?>" accept="image/png, image/jpeg"> -->
		  <div class="ppic">..</div>
        </div>
        <div class="columns padding-left">
          <h5 class="yoyo white text">
            <?php //echo Lang::$word->WELCOMEBACK;?>
            <span class="yoyo demi text">
            <?php echo Auth::$udata->name;?>! </span>
          </h5>
          <p id="gotoagent" class="yoyo white dimmed text">
		   
            <?php $typ = Auth::$udata->type;
			if($typ=="agent"):?>
			<a id="agpen" href="/affagent/"><?= Lang::$word->ACC_GO_TO_YOUR_AGENT_PANEL; ?></a>
			<?php elseif($typ=="Sagent"):?>
				<a id="agpen" href="/suppagent/"><?= Lang::$word->ACC_GO_TO_YOUR_BROKER_PANEL; ?></a>
			<?php else:?>
				<!--<a id="myBtn"><?= Lang::$word->ACC_JOIN_AFFILIATES_MAKE_EASY_MONEY; ?></a>-->
				<a id="myBtn"><?= Lang::$word->ACC_JOIN_AFFILIATES_MAKE_EASY_MONEY; ?></a>
				<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="closeme">&times;</span>
	<div class="modalinner">
	<h2><?= Lang::$word->ACC_ONE_STEP_AWAY; ?></h2>
    <p><?= Lang::$word->ACC_ONE_STEP_AWAY_DESC; ?></p>
	<h3><?= Lang::$word->ACC_THESE_ARE_SOME_BASIC_FEATURES; ?></h3>
	<ul>
	 <li><?= Lang::$word->ACC_FEATURE_1; ?></li>
	 <li><?= Lang::$word->ACC_FEATURE_2; ?></li>
	 <li><?= Lang::$word->ACC_FEATURE_3; ?></li>
	 <li><?= Lang::$word->ACC_FEATURE_4; ?></li>
	 <li><?= Lang::$word->ACC_FEATURE_5; ?></li>
	 <li><?= Lang::$word->ACC_FEATURE_6; ?></li>
	</ul>
	 
<b>*<?= Lang::$word->ACC_IMPORTANT; ?></b> : <?= Lang::$word->ACC_DESC2; ?> </br></br>

<label><?= Lang::$word->ACC_DESC3; ?></label></br>
<h4><?= Lang::$word->ACC_DESC4; ?></h4>
<p>*<?= Lang::$word->ACC_REQUIRED; ?></p>
<input type="url" id="onelink" placeholder="<?= Lang::$word->ACC_TYPE_PASTE_YOUR_LINK; ?>">


</br>
<div class="oneerror"></div>
</br>
<div class="yoyo primary wide button" id="joinaff"><?= Lang::$word->ACC_JOIN_NOW; ?></div>
	</div> 
  </div>
</div>
			 <?php endif;?>
          </p>
        </div>
      </div>
	  
	  <?php $ms = Db::run()->pdoQuery("SELECT id FROM sh_sf_inbox WHERE send_to=$usid AND seen = 0");
	  $mg = $ms->aResults[0]->id;
	  if(!empty($mg)){
		  $yesmg = '<span class="mgyes">1</span>';
	  }else{
		  $yesmg = '';
	  }?>
	  <ul class="history_menu">
	  <li class="bnk"><a href="/bt_accounts/?pg103=bnk&bb=bb"><?= Lang::$word->ACC_M_BANK; ?></a></li>
	  <li class="sl casgame mkt"><a href="/bt_accounts/?pg103=sl"><?= Lang::$word->ACC_M_BET_HISTORY; ?></a></li>
	  <li class="acset"><a href="/dashboard/settings/?pg103=acset"><?= Lang::$word->ACC_M_SETTINGS; ?></a></li>

      <?php if(false): ?>
	  <li class="pcredit"><a href="/bt_accounts/?pg103=pcredit"><?= Lang::$word->ACC_M_PROMO_CREDIT; ?></a></li>
      <?php endif; ?>

	  <li class="msg"><a href="/bt_accounts/?pg103=msg"><?= Lang::$word->ACC_M_MESSAGING; ?><?php echo $yesmg;?></a></li>

      <?php if(false): ?>
	  <li class="gmbc"><a href="/bt_accounts/?pg103=gmbc"><?= Lang::$word->ACC_M_GAMBLING_CONTROL; ?></a></li>
      <?php endif; ?>
	  </ul>
	  </div>
	  </div>
	 </div>
	 