<?php 
define("_YOYO", true);
  include_once("../../init.php");
  ?>

<div class="columns align-center align-self-middle tablet-100 mobile-100 phone-100">
      <div class="yoyo-grid">
        <div class="row align-center">
          <div class="columns screen-50 tablet-80 mobile-100 phone-100">
            <div id="regForm">
              <form method="post" id="reg_form" name="reg_form">
			  <div class="whyus">Why join, today?</div>
			  <div class="onamebg one">.</div> 
			  <div class="promflex">
			  50% 1st deposit bonus upto <cc class="cricoin" style="width:20px;position:absolute;margin-top:2px;color:transparent;">.</cc> <span style="margin-left:15px">500.00</span> (within 7 days) t&c.</br>
			  NO nonesense short and precise registration form.</br>
			  Deposit & withdrawals with almost every payment methods</br>
			  Range of sports, exchanges, games and casinos all in one place.</br>
			  </div></br>
			  
                <h3 class="yoyo primary text"><?php echo Lang::$word->M_SUB30;?>
                  <span class="yoyo semi text"><?php echo $this->core->company;?>
                  </span></h3>
                <p class="margin-bottom"><?php echo Lang::$word->M_SUB23;?></p>
                <div class="yoyo form">
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->M_EMAIL;?>
                        <i class="icon asterisk"></i></label>
                      <input name="email" type="email" placeholder="<?php echo Lang::$word->M_EMAIL;?>">
                    </div>
                    <div class="field">
                      <label><?php echo Lang::$word->M_PASSWORD;?>
                        <i class="icon asterisk"></i></label>
                      <input type="password" name="password" placeholder="********">
                    </div>
                  </div>
                  <div class="yoyo fields">
                    <div class="field">
                      <label><?php echo Lang::$word->M_FNAME;?>
                        <i class="icon asterisk"></i></label>
                      <input name="fname" type="text" placeholder="<?php echo Lang::$word->M_FNAME;?>">
                    </div>
                    <div class="field">
                      <label><?php echo Lang::$word->M_LNAME;?>
                        <i class="icon asterisk"></i></label>
                      <input name="lname" type="text" placeholder="<?php echo Lang::$word->M_LNAME;?>">
                    </div>
                  </div>
                  <?php echo $this->custom_fields;?>
                  <?php if($this->core->enable_tax):?>
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->M_ADDRESS;?>
                        <i class="icon asterisk"></i></label>
                      <input type="text" name="address" placeholder="<?php echo Lang::$word->M_ADDRESS;?>">
                    </div>
                  </div>
                  <div class="yoyo fields">
                    <div class="field">
                      <label><?php echo Lang::$word->M_CITY;?>
                        <i class="icon asterisk"></i></label>
                      <input type="text" name="city" placeholder="<?php echo Lang::$word->M_CITY;?>">
                    </div>
                    <div class="field">
                      <label><?php echo Lang::$word->M_STATE;?>
                        <i class="icon asterisk"></i></label>
                      <input type="text" name="state" placeholder="<?php echo Lang::$word->M_STATE;?>">
                    </div>
                  </div>
                  <div class="yoyo fields">
                    <div class="field three wide">
                      <label>
                        <?php echo Lang::$word->M_ZIP;?>
                        <i class="icon asterisk"></i></label>
                      <input type="text" name="zip">
                    </div>
                    <div class="field">
                      <label>
                        <?php echo Lang::$word->M_COUNTRY;?>
                        <i class="icon asterisk"></i></label>
                      <select name="country">
                        <?php echo Utility::loopOptions($this->clist, "abbr", "name");?>
                      </select>
                    </div>
                  </div>
                  <?php endif;?>
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->CAPTCHA;?>
                        <i class="icon asterisk"></i></label>
                      <div class="yoyo right labeled fluid input">
                        <input placeholder="<?php echo Lang::$word->CAPTCHA;?>" name="captcha" type="text">
                        <span class="yoyo label">
                        <?php echo Session::captcha();?>
                        </span>
                      </div>
                    </div>
                    <div class="field">
                      <div class="yoyo checkbox">
                        <input name="agree" type="checkbox" value="1" id="agree">
                        <label for="agree"><a href="<?php echo Url::url('/' . App::Core()->system_slugs->policy[0]->{'slug' . Lang::$lang});?>" class="secondary dashed"><small><?php echo Lang::$word->AGREE;?></small></a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="yoyo fields align-middle">
                    <div class="field"><span class="yoyo small secondary text"><?php echo Lang::$word->M_SUB31;?></span>
                      <a href="<?php echo Url::url('/' . $this->core->system_slugs->login[0]->{'slug' . Lang::$lang});?>"><span class="yoyo small text"><?php echo Lang::$word->LOGIN;?>.</span></a>
                    </div>
                    <div class="field content-right">
                      <button class="yoyo primary button" data-action="register" name="dosubmit" type="button"><?php echo Lang::$word->M_SUB24;?></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>