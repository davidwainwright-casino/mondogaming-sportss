<div id="xmyModal" class="xmodal">
	<div class="xclose" style="display:none"><i id="closeSlip" class="icon compress"></i></div>
  <!-- Modal content -->
<div class="xmodal-content">
<div class="slip_wrapper">
<input id="maxwinner" value="<?php echo $max_win;?>" hidden>			
    <div class="topbtn">
    	<div class="unsubtd"><?= Lang::$word->BET_SLIP; ?></div>
		<div class="ssmsg"></div>
    	<a href="" id="delete_all_unsubmitted_bets"><?= Lang::$word->BET_EMPTY; ?></a>
    </div>
<div id="hdinside">
	 <div class="_slip_container">
	 <div id="unsubmitted_slips"></div>
	</div>
  
	<div class="cbtn">
			<div class="number xp">
			  <span class="minus">-</span>
			   <input type="number" id="stake_value" value="0"/>
			  <span class="plus">+</span>
			</div>
		  <div class="vbtn">		
                <span class="etodds"><?= Lang::$word->BET_T_ODDS; ?></span>
                 <input type="text" class="preview xp" name="0.001" id="todd_value" value="0" disabled="">
				 
				 <span class="ereturn"><?= Lang::$word->BET_T_RETURN; ?></span>
				 <input type="text" class="preview" name="0.00" id="return_value" value="0" disabled="">
		  </div>
		</div>
 <div class="ifchecker">
  <?php if($bons_balance > 1):?>
  <input type='checkbox' name="usepromo" class="usepromo" id="checkpromo"> Use bonus balance? Available <b><?php echo $bons_balance;?></b>
  <?php endif;?>
  </div>
  
  <input id="chipsbal" value="<?php echo $chips;?>" hidden>
  <input id="promobet" value="<?php echo $promo;?>" hidden>
  <input id="minbet" value="<?php echo $min_bet;?>" hidden>
  <input id="maxbet" value="<?php echo $max_bet;?>" hidden>
  
  <div id="sherrors"></div>
  <div class="ptptomo"></div>
    <div class="text-center">
        <?php if($usid==999999999):?>
            <a href="/login">
                <button class="place_bet_button"><?= Lang::$word->BET_LOGIN_TO_BET; ?></button>
            </a>
        <?php else:?>
            <button class="place_bet_button" id="submit_bet"><?= Lang::$word->BET_SUBMIT_BET; ?></button>
        <?php endif;?>
    </div>
</div>
</div>
</div>
</div>