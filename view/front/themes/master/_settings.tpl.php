<?php
 /**
 * Settings
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */
 
 if (!defined("_YOYO"))
 die('Direct access to this location is not allowed.');
?>

<div class="yoyo-grid">
<div id="jinx" style="padding:10px">
 <h4>
     <?= Lang::$word->ACC_SETTINGS_ACCOUNT_SETTINGS; ?>
 </h4>
 <p><?= Lang::$word->ACC_SETTINGS_ACCOUNT_SETTINGS_DESC; ?></p>
 <form method="post" id="yoyo_form" name="yoyo_form">
 <div class="yoyo form">


 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_FNAME;?>
 <i class="icon asterisk"></i></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_FNAME;?>" value="<?php echo $this->user->fname;?>" name="fname">
 </div>
 </div>
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_LNAME;?>
 <i class="icon asterisk"></i></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_LNAME;?>" value="<?php echo $this->user->lname;?>" name="lname">
 </div>
 </div>
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_EMAIL;?>
 <i class="icon asterisk"></i></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_EMAIL;?>" value="<?php echo $this->user->email;?>" name="email">
 </div>
 </div>
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_PHONE_NUM;?></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_PHONE_NUM;?>" value="<?php echo $this->user->notes;?>" name="notes">
 </div>
 </div>
 
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->NEWPASS;?></label>
 </div>
 <div class="field">
 <input type="password" name="password">
 </div>
 </div>
 
 
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_ADDRESS;?>
 <i class="icon asterisk"></i></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_ADDRESS;?>" value="<?php echo $this->user->address;?>" name="address">
 </div>
 </div>
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_CITY;?>
 <i class="icon asterisk"></i></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_CITY;?>" value="<?php echo $this->user->city;?>" name="city">
 </div>
 </div>
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_STATE;?>
 <i class="icon asterisk"></i></label>
 </div>
 <div class="field">
 <input type="text" placeholder="<?php echo Lang::$word->M_STATE;?>" value="<?php echo $this->user->state;?>" name="state">
 </div>
 </div>
 
 
 <!--<div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_ZIP;?> / <?php echo Lang::$word->M_COUNTRY;?></label>
 </div>
 <div class="field">
 <div class="yoyo action fluid input">
 <input type="text" placeholder="<?php echo Lang::$word->M_ZIP;?>" value="<?php echo $this->user->zip;?>" name="zip">
 <select class="yoyo search selection dropdown" name="country">
 <?php echo Utility::loopOptions($this->clist, "abbr", "name", $this->user->country);?>
 </select>
 </div>
 </div>
 </div>-->
 
 
 
 
 
 <div class="yoyo fields align-middle">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?php echo Lang::$word->M_SUB10;?></label>
 </div>
 <div class="field">
 
 <div class="yoyo checkbox inline fitted">
 <input id="newsletter_1" name="newsletter" type="checkbox" value="1" <?php Validator::getChecked($this->user->newsletter, 1); ?>>
 <label for="newsletter_1"><?php echo Lang::$word->YES;?></label>
 </div>
 
 </div>
 </div>
 
 
 
 
 
 
 
 
 <div class="yoyo fields">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?= Lang::$word->ACC_SETTINGS_ACCOUNT_BANKING_DETAILS; ?></label>
 </div>
 <div class="field">
 <textarea class="small" placeholder="<?= Lang::$word->ACC_SETTINGS_ACCOUNT_BANKING_DETAILS; ?>" name="info"><?php echo $this->user->info;?></textarea>
 </div>
 </div>
 
 
 <div class="yoyo fields">
 <div class="field four wide labeled">
 <label class="content-right mobile-content-left"><?= Lang::$word->ACC_SETTINGS_ACCOUNT_KYC; ?></label>
 </div>
 <div class="field kyc">
     <?= Lang::$word->ACC_SETTINGS_ACCOUNT_KYC_CONTENT; ?>
 </div>
 </div>
 


 
 
 
 
 
 
 <div class="yoyo fields">
 <div class="field four wide labeled">
 </div>
 <div class="field">
 <button type="button" data-action="profile" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->M_UPDATE;?></button>
 </div>
 </div>
 </div>
 </form>
 </div>
</div>

<style>
    body{
        background-color: #000000 !important;
    }

    #jinx {
        background: #1f1f1f !important;
        border-left: 3px solid #eb1515 !important;
    }

    #jinx h4, p, label {
        color: #fff !important;
    }

    .field.kyc {
        margin-left: 10px;
        padding: 5px;
        font-size: 12px;
        background: #343434 !important;
        border: 1px solid #eb1515;
        border-radius: 3px;
        color: #fff !important;
        letter-spacing: 0px !important;
        font-family: 'Inter' !important;
    }
    input[name="zip"] {
        margin-bottom: 15px !important;
    }

    .yoyo.primary.buttons .button, .yoyo.primary.button {
        background-color: #ffebeb;
        border-color: #ffebeb;
    }
</style>