<?php
  /**
   * Utilities
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if (!Auth::checkAcl("owner")) : print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>

<h3><?php echo Lang::$word->META_T19;?></h3>
<p class="yoyo small text">
  <?php echo Lang::$word->UTL_INFO;?>
</p>
<div class="row gutters">
  <div class="columns screen-50 tablet-100 mobile-100 phone-100">
    <div class="yoyo segment">
      <form method="post" name="yoyo_forma">
        <div class="yoyo form">
          <div class="yoyo fields">
            <div class="field three wide">
              <label><?php echo Lang::$word->UTL_SUB1;?></label>
              <select name="days" class="yoyo small fluid secection dropdown">
                <option value="3">3</option>
                <option value="7">7</option>
                <option value="14">14</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="100">100</option>
                <option value="180">180</option>
                <option value="365">365</option>
              </select>
            </div>
            <div class="field five wide">
              <label><?php echo Lang::$word->DELETE;?></label>
              <button type="button" data-action="processMInactive" name="dosubmit" class="yoyo small negative button"><?php echo Lang::$word->UTL_DELINACTIVE;?></button>
            </div>
          </div>
          <p class="yoyo mini text"><?php echo Lang::$word->UTL_SUB1_T;?></p>
        </div>
      </form>
    </div>
    <div class="yoyo segment">
      <form method="post" name="yoyo_formb">
        <div class="yoyo form">
          <div class="yoyo fields">
            <div class="field three wide basic">
              <label><?php echo Lang::$word->UTL_SUB3;?></label>
              <p class="yoyo mini text"><?php echo str_replace("[NUMBER]", '<span class="yoyo label" id="banned">' . $this->banned . '</span>', Lang::$word->UTL_SUB2_T);?></p>
            </div>
            <div class="field five wide basic">
              <label><?php echo Lang::$word->DELETE;?></label>
              <button type="button" data-action="processMBanned" name="dosubmit" class="yoyo small negative button"><?php echo Lang::$word->UTL_DELBANNED;?></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="yoyo segment">
      <form method="post" name="yoyo_formc">
        <div class="yoyo form">
          <div class="yoyo fields">
            <div class="field three wide basic">
              <label><?php echo Lang::$word->UTL_SUB2;?></label>
              <p class="yoyo mini text"><?php echo str_replace("[NUMBER]", '<span class="yoyo label" id="pending">' . $this->pending . '</span>', Lang::$word->UTL_SUB2_T);?></p>
            </div>
            <div class="field five wide basic">
              <label><?php echo Lang::$word->DELETE;?></label>
              <button type="button" data-action="processMPEnding" name="dosubmit" class="yoyo small negative button"><?php echo Lang::$word->UTL_DELPENDING;?></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="yoyo segment">
      <form method="post" name="yoyo_formd">
        <div class="yoyo form">
          <div class="yoyo fields">
            <div class="field three wide basic">
              <label><?php echo Lang::$word->UTL_CART;?></label>
              <p class="yoyo mini text"><?php echo Lang::$word->UTL_CART_T;?></p>
            </div>
            <div class="field five wide basic">
              <label><?php echo Lang::$word->DELETE;?></label>
              <button type="button" data-action="processMCart" name="dosubmit" class="yoyo small negative button"><?php echo Lang::$word->UTL_CRBTN;?></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="yoyo segment">
      <form method="post" name="yoyo_forme">
        <div class="yoyo form">
          <div class="yoyo fields">
            <div class="field three wide basic">
              <label><?php echo Lang::$word->UTL_INSTALL;?></label>
              <p class="yoyo mini text"><?php echo Lang::$word->UTL_INSTALL_T;?></p>
            </div>
            <div class="field five wide basic">
              <input type="file" name="installer" id="installer" class="filefield" data-input="false">
            </div>
            <div class="field two wide basic">
              <button type="button" data-action="processMInstall" name="dosubmit" class="yoyo positive button"><?php echo Lang::$word->UTL_INSTALL;?></button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="columns screen-50 tablet-100 mobile-100 phone-100">
    <div class="yoyo segment">
      <form method="post" name="yoyo_formf">
        <div class="yoyo form">
          <div class="yoyo fields">
            <div class="field three wide basic">
              <label><?php echo Lang::$word->UTL_SUB4;?></label>
              <p class="yoyo mini text"><?php echo Lang::$word->UTL_SUB4_T;?></p>
            </div>
            <div class="field five wide basic">
              <label><?php echo Lang::$word->UTL_GENERATE;?></label>
              <button type="button" data-action="processMap" name="dosubmit" class="yoyo small positive button"><?php echo Lang::$word->UTL_GENERATE;?></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="yoyo segment">
      <form method="post" name="yoyo_formg">
        <div class="yoyo small form">
          <div class="yoyo small fields">
            <div class="field">
              <label><?php echo Lang::$word->UTL_SUB5;?></label>
              <p class="yoyo small text"><?php echo Lang::$word->UTL_SUB5_T;?></p>
            </div>
          </div>
          <?php foreach($this->core->slugs->moddir as $key => $mod):?>
          <div class="yoyo small fields">
            <div class="field">
              <input type="text" value="<?php echo $key;?>" name="<?php echo $mod;?>">
              <p class="yoyo small text half-top-margin"><?php echo SITEURL . '/' . $key . '/';?></p>
            </div>
            <div class="field">
              <input type="text" disabled value="<?php echo $mod;?>">
            </div>
          </div>
          <?php endforeach;?>
          <div class="yoyo small fields">
            <div class="field">
              <input type="text" value="<?php echo $this->core->slugs->pagedata->page;?>" name="page">
              <p class="yoyo small text half-top-margin"><?php echo SITEURL . '/' . $this->core->slugs->pagedata->page . '/page-title';?></p>
            </div>
            <div class="field">
              <input type="text" disabled value="page">
            </div>
          </div>
          <button type="button" data-action="processSlugs" name="dosubmit" class="yoyo small positive button"><?php echo Lang::$word->UPDATE;?></button>
        </div>
      </form>
    </div>
  </div>
</div>