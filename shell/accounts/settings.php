<div class="wojo-grid">
  <h4>
    <?php echo Lang::$word->SETTINGS;?>
  </h4>
  <p><?php echo Lang::$word->M_INFO15;?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo form">
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_FNAME;?>
            <i class="icon asterisk"></i></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_FNAME;?>" value="<?php echo $this->user->fname;?>" name="fname">
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_LNAME;?>
            <i class="icon asterisk"></i></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_LNAME;?>" value="<?php echo $this->user->lname;?>" name="lname">
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_EMAIL;?>
            <i class="icon asterisk"></i></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_EMAIL;?>" value="<?php echo $this->user->email;?>" name="email">
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->NEWPASS;?></label>
        </div>
        <div class="field">
          <input type="password" name="password">
        </div>
      </div>
      <?php if($this->custom_fields):?>
      <?php echo $this->custom_fields;?>
      <?php endif;?>
      <?php if($this->core->enable_tax):?>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_ADDRESS;?>
            <i class="icon asterisk"></i></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_ADDRESS;?>" value="<?php echo $this->user->address;?>" name="address">
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_CITY;?>
            <i class="icon asterisk"></i></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_CITY;?>" value="<?php echo $this->user->city;?>" name="city">
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_STATE;?>
            <i class="icon asterisk"></i></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_STATE;?>" value="<?php echo $this->user->state;?>" name="state">
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_ZIP;?> / <?php echo Lang::$word->M_COUNTRY;?></label>
        </div>
        <div class="field">
          <div class="wojo action fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->M_ZIP;?>" value="<?php echo $this->user->zip;?>" name="zip">
            <select class="wojo search selection dropdown" name="country">
              <?php echo Utility::loopOptions($this->clist, "abbr", "name", $this->user->country);?>
            </select>
          </div>
        </div>
      </div>
      <?php endif;?>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_SUB10;?></label>
        </div>
        <div class="field">
       
            <div class="wojo checkbox inline fitted">
              <input id="newsletter_1" name="newsletter" type="checkbox" value="1" <?php Validator::getChecked($this->user->newsletter, 1); ?>>
              <label for="newsletter_1"><?php echo Lang::$word->YES;?></label>
            </div>
     
        </div>
      </div>
      <div class="wojo fields align-middle">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->SOCIAL;?></label>
        </div>
        <div class="basic field">
          <div class="wojo block fields">
            <div class="field">
              <div class="wojo fluid right icon input">
                <input type="text" placeholder="Facebook" value="<?php echo $this->user->fb_link;?>" name="fb_link">
                <i class="icon facebook"></i>
              </div>
            </div>
            <div class="field">
              <div class="wojo fluid right icon input">
                <input type="text" placeholder="Twitter" value="<?php echo $this->user->tw_link;?>" name="tw_link">
                <i class="icon twitter"></i>
              </div>
            </div>
            <div class="field">
              <div class="wojo fluid right icon input">
                <input type="text" placeholder="Google +" value="<?php echo $this->user->gp_link;?>" name="gp_link">
                <i class="icon google plus"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->ABOUT;?></label>
        </div>
        <div class="field">
          <textarea class="small" placeholder="<?php echo Lang::$word->ABOUT;?>" name="info"><?php echo $this->user->info;?></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field four wide labeled">
        </div>
        <div class="field">
          <button type="button" data-action="profile" name="dosubmit" class="wojo primary button"><?php echo Lang::$word->M_UPDATE;?></button>
        </div>
      </div>
    </div>
  </form>
</div>
