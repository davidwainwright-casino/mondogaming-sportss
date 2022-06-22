<?php
  /**
   * User Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('edit_user')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
  <h1><img src="<?php echo UPLOADURL;?>/avatars/<?php echo $row->avatar ? $row->avatar : "blank.png" ;?>" alt="" class="yoyo avatar image"> <?php echo $this->data->fname;?> <?php echo $this->data->lname;?></br>
  </h1>
  
  
   <div class="depowith"><div id="dwstats"></div> Last Login: <?php echo $this->data->lastlogin ? Date::doDate("long_date", $this->data->lastlogin) : Lang::$word->NEVER;?></div>
  
  
  <div class="yoyo basic segment">
   
     <h5>Balance/Chips Control</h5>
	  <div class="chipsdiv">
        <input class="chipsput" type="number" name="chipsput" placeholder="add or deduct chips"  />
	    <input type="number" id="chips" placeholder="Chips Balance" value="<?php echo $this->data->chips;?>" name="chips"> *Chips
	  </div>
	  
	  <?php if($this->data->type == 'agent'):?>
	  <h5>Agent balance Control</h5>
	  <div class="chipsdiv">
        <input class="afbalput" type="number" name="chipsput" placeholder="add or deduct chips"  />
	    <input type="number" id="afbal" placeholder="Chips Balance" value="<?php echo $this->data->afbal;?>" name="chips"> *(US$)
	  </div>
	  <?php endif;?>
	  
	<?php if($this->data->type == 'Sagent'):?>
	  <h5>Super Agent balance Control</h5>
	  <div class="chipsdiv">
        <input class="sabalput" type="number" name="chipsput" placeholder="add or deduct chips"  />
	    <input type="number" id="sabal" placeholder="Chips Balance" value="<?php echo $this->data->sabal;?>" name="chips"> *(US$)
	  </div>
	  <?php endif;?>
	  
	  <div class="promodiv">
	  <input class="promoput" type="number" name="promoput" placeholder="add or deduct Promo"  />
	   <input type="number" id="cpromo" placeholder="Promo Balance" value="<?php echo $this->data->promo;?>" name="promo"> *Promo
	  </div></br>
	  <div class="supdates"></div>
	   <button type="button" id="ubalance" class="yoyo primary button ubal">Update Balance</button>
	   
	   
	   
	   </br></br>
	   <h3>Send Notice</h3>
	   <textarea id="unotice" name="w3review" rows="2" cols="50">
</textarea>

<div class="upnotice"></div>
	  <button type="button" id="updatenotice" class="yoyo primary button ubal">Update Notice</button>
  </div>
  
  
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->M_FNAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->M_FNAME;?>" value="<?php echo $this->data->fname;?>" name="fname">
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->M_LNAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->M_LNAME;?>" value="<?php echo $this->data->lname;?>" name="lname">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->M_EMAIL;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->M_EMAIL;?>" value="<?php echo $this->data->email;?>" name="email">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->NEWPASS;?></label>
        <div class="yoyo fluid right input icon">
          <input type="text" name="password">
          <button class="yoyo icon button" type="button" id="randPass">
          <i class="lock icon"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->M_SUB8;?>
        </label>
        <div class="row align-middle">
          <div class="column">
            <select name="membership_id" class="yoyo fluid dropdown">
              <option value="0">-/-</option>
              <?php echo Utility::loopOptions($this->mlist, "id", "title" . Lang::$lang, $this->data->membership_id);?>
            </select>
          </div>
          <div class="column shrink half-left-padding">
            <div class="yoyo checkbox toggle fitted inline">
              <input name="update_membership" type="checkbox" value="1" id="update_membership">
              <label for="update_membership"><?php echo Lang::$word->YES;?></label>
            </div>
          </div>
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->M_SUB15;?></label>
        <div class="row align-middle">
          <div class="column">
            <div class="yoyo fluid right icon input" data-datepicker="true">
              <input name="mem_expire" type="text" placeholder="<?php echo Lang::$word->TO;?>">
              <i class="icon calendar alt"></i>
            </div>
          </div>
          <div class="column shrink half-left-padding">
            <div class="yoyo checkbox toggle fitted inline">
              <input name="extend_membership" type="checkbox" value="1" id="extend_membership">
              <label for="extend_membership"><?php echo Lang::$word->YES;?></label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->M_SUB17;?></label>
        <select name="modaccess[]" class="yoyo fluid multiple dropdown selection" multiple>
          <?php echo Utility::loopOptionsMultiple($this->modlist, "modalias", "title" . Lang::$lang, $this->data->modaccess);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_SUB18;?></label>
        <select name="plugaccess[]" class="yoyo fluid multiple dropdown selection" multiple>
          <?php echo Utility::loopOptionsMultiple($this->pluglist, "plugalias", "title" . Lang::$lang, $this->data->plugaccess);?>
        </select>
      </div>
    </div>
	  
	  
	  
	  
    <a class="yoyo icon button" data-trigger="#uAddress" data-type="slide down" data-transition="true"><i class="icon chevron down"></i></a>
    <div class="yoyo basic segment hide-all" id="uAddress">
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_ADDRESS;?></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_ADDRESS;?>" value="<?php echo $this->data->address;?>" name="address">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_CITY;?></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_CITY;?>" value="<?php echo $this->data->city;?>" name="city">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_STATE;?></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_STATE;?>" value="<?php echo $this->data->state;?>" name="state">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_COUNTRY;?>/<?php echo Lang::$word->M_ZIP;?></label>
        </div>
        <div class="field">
          <div class="yoyo fields">
            <div class="field">
              <input type="text" placeholder="<?php echo Lang::$word->M_ZIP;?>" value="<?php echo $this->data->zip;?>" name="zip">
            </div>
            <div class="field">
              <select class="yoyo fluid search dropdown" name="country">
                <option value="">-/-</option>
                <?php echo Utility::loopOptions($this->clist, "abbr", "name", $this->data->country);?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo big space divider"></div>
    <div class="yoyo fields">
      <div class="field four wide">
        <div class="field">
          <label><?php echo Lang::$word->CREATED;?></label>
          <?php echo Date::doDate("long_date", $this->data->created);?>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->M_LASTLOGIN;?></label>
          <?php echo $this->data->lastlogin ? Date::doDate("long_date", $this->data->lastlogin) : Lang::$word->NEVER;?>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->M_LASTIP;?></label>
          <?php echo $this->data->lastip;?>
        </div>
      </div>
      <div class="field six wide">
        <div class="fitted field">
          <label><?php echo Lang::$word->STATUS;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="y" id="active_y" <?php Validator::getChecked($this->data->active, "y"); ?>>
            <label for="active_y"><?php echo Lang::$word->ACTIVE;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="n" id="active_n" <?php Validator::getChecked($this->data->active, "n"); ?>>
			
            <label for="active_n"><?php echo Lang::$word->INACTIVE;?></label>
			<?php $df = $this->data->active; if($df =='n'){ echo Utility::status($this->data->active, $this->data->id);}?>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="t" id="active_t" <?php Validator::getChecked($this->data->active, "t"); ?>>
            <label for="active_t"><?php echo Lang::$word->PENDING;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="b" id="active_b" <?php Validator::getChecked($this->data->active, "b"); ?>>
            <label for="active_b"><?php echo Lang::$word->BANNED;?></label>
          </div>
        </div>
        <div class="fitted field">
          <label><?php echo Lang::$word->M_SUB9;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="type" type="radio" value="staff" id="type_staff" <?php Validator::getChecked($this->data->type, "staff"); ?>>
            <label for="type_staff"><?php echo Lang::$word->STAFF;?></label>
          </div>
		  
		  
		  
		  
          <div class="yoyo checkbox radio fitted inline">
            <input name="type" type="radio" value="editor" id="type_editor" <?php Validator::getChecked($this->data->type, "editor"); ?>>
            <label for="type_editor"><?php echo Lang::$word->EDITOR;?></label>
          </div>
		  
		  <div class="yoyo checkbox radio fitted inline">
            <input name="type" type="radio" value="Sagent" id="type_Sagent" <?php Validator::getChecked($this->data->type, "Sagent"); ?>>
            <label for="type_Sagent"><?php echo 'Super Agent';?></label>
          </div>
		  
		  
		  
		  <div class="yoyo checkbox radio fitted inline">
            <input name="type" type="radio" value="agent" id="type_agent" <?php Validator::getChecked($this->data->type, "agent"); ?>>
            <label for="type_agent"><?php echo 'Agent';?></label>
          </div>
		  
		  
		  
		  
		  
          <div class="yoyo checkbox radio fitted inline">
            <input name="type" type="radio" value="member" id="type_member" <?php Validator::getChecked($this->data->type, "member"); ?>>
            <label for="type_member"><?php echo Lang::$word->MEMBER;?></label>
          </div>
        </div>
        <div class="fitted field">
          <label><?php echo Lang::$word->M_SUB10;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="newsletter" type="radio" value="1" id="newsletter_1" <?php Validator::getChecked($this->data->newsletter, 1); ?>>
            <label for="newsletter_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="newsletter" type="radio" value="0" id="newsletter_0" <?php Validator::getChecked($this->data->newsletter, 0); ?>>
            <label for="newsletter_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
    <textarea placeholder="<?php echo Lang::$word->M_SUB11;?>/Phone" name="notes"><?php echo $this->data->notes;?></textarea>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/users");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processUser" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->M_UPDATE;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>









<script>
$(document).ready(function() {

//for getting deposit/withdrawals stats
$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/deposit_withdraw",
			data: {
			usid: <?php echo $this->data->id;?>,	
			method:"deposit_withdrawal"
			},
			
	success: function(success) {
             $("#dwstats").empty().append(success);
		}
	 });
	 
//chips input change val
$(".chipsput").on("keyup change", function() {
   var value = '<?php echo $this->data->chips;?>';
   var inputs = Number($(".chipsput").val().trim());
   var bb = Number(inputs) + Number(value);
   $("#chips").val(bb.toFixed(2));
});
//afbalv
$(".afbalput").on("keyup change", function() {
   var value = '<?php echo $this->data->afbal;?>';
   var inputs = Number($(".afbalput").val().trim());
   var bb = Number(inputs) + Number(value);
   $("#afbal").val(bb.toFixed(2));
});

//sabal
$(".sabalput").on("keyup change", function() {
   var value = '<?php echo $this->data->sabal;?>';
   var inputs = Number($(".sabalput").val().trim());
   var bb = Number(inputs) + Number(value);
   $("#sabal").val(bb.toFixed(2));
});


//promo input change val
$(".promoput").on("keyup change", function() {
   var value = '<?php echo $this->data->promo;?>';
   var inputs = Number($(".promoput").val().trim());
   var bb = Number(inputs) + Number(value);
   $("#cpromo").val(bb.toFixed(2));
});


//On submit update balance
$('body').on('click', ' #ubalance', function(){
	  var chips = $("#chips"). val();
	  var afbal = $("#afbal"). val();
	  var sabal = $("#sabal"). val();
	  var cpromo = $("#cpromo"). val();
	  var chipsput = $(".chipsput"). val();
	  var afbalput = $(".afbalput"). val();
	  var sabalput = $(".sabalput"). val();
	  var promoput = $(".promoput"). val();
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/update_balance.php",
			type: "post",
			data: {
				chips:chips,
				afbal:afbal,
				sabal:sabal,
				cpromo:cpromo,
				chipsput:chipsput,
				afbalput:afbalput,
				sabalput:sabalput,
				promoput:promoput,
				usid: <?php echo $this->data->id;?>,
				method:"updateStatus"
			},
			success: function (response) {
				$(".supdates").html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
	});

//individual notice
$('body').on('click', ' #updatenotice', function(){
	  var msg = $("#unotice"). val();
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/individual_notice.php",
			type: "post",
			data: {
				msg:msg,
				usid: <?php echo $this->data->id;?>,
				method:"updateStatus"
			},
			success: function (response) {
				$(".upnotice").html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
	});
	
	
	///show msg by default
	
	$.ajax({
			url: "<?php echo ADMINVIEW;?>/cus/show_notice.php",
			type: "post",
			data: {
				
				usid: <?php echo $this->data->id;?>,
				method:"updateStatus"
			},
			success: function (response) {
				$("textarea#unotice").val(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});



});//end of doc
</script>


<style>
input.chipsput, input.afbalput, input.sabalput {
    display: inline-block;
    max-width: 170px;
	margin-bottom: 5px!important;
}

input#chips, input#afbal, input#sabal {
    max-width: 130px;
    border: 0px;
    background: #f3ffe5;
    color: #039a15;
    font-weight: bold;
    font-family: impact;
    text-align: center;
}
span.paydepot {
    font-weight: bold;
    margin-left: 4px;
    color: #FF5722;
}

.chipsdiv {
    margin-bottom: 20px;
}

.depowith {
    margin-left: 120px;
    position: absolute;
    margin-top: -30px;
}

input.promoput {
    display: inline-block;
    max-width: 170px;
	margin-bottom: 5px!important;
}

input#cpromo {
    max-width: 130px;
    border: 0px;
    background: #fff7f7;
    color: #da0808;
    font-weight: bold;
    font-family: impact;
    text-align: center;
}
.yoyo.primary.button.ubal {
    background: #03A9F4;
}
button#updatenotice {
    margin-top: 10px;
    background: #d0d0d0;
    color: #000;
}


</style>