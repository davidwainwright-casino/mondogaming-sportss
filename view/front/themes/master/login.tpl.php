<?php
  /**
   * Login
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<header id="loginHeader">
<div class="row">
<div class="columns screen-33 tablet-30 mobile-hide phone-hide">
  <a href="<?php echo SITEURL;?>/" class="white logo">
    <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?></a>
</div>
<div class="columns screen-hide tablet-hide mobile-100 phone-100">
  <a href="<?php echo SITEURL;?>/" class="dark logo">
    <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?></a>
</div>
</header>
<main>
  <div class="row fullsize">
    <div id="sOverlay" class="columns relative fullsize align-middle flex-block screen-33 tablet-30 mobile-hide phone-hide">

        <!-- data-wslider='{"items":1,"autoloop":true,"arrows":false,"buttons":false,"autoplay":true,"autoplaySpeed":"500", "autoplayHoverPause":false, "rtl":true}' -->
      <div class="wSlider1" style="width: 100%;ackground-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-1.jpg);height:100vh;height:100vh">
        <!--
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-1.jpg);height:100vh"></div>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-2.jpg);height:100vh"></div>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-3.jpg);height:100vh"></div>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-4.jpg);height:100vh"></div>
        -->
      </div>
    </div>
    <div class="columns align-self-middle align-center tablet-70 mobile-100 phone-100">
      <div class="yoyo-grid">
        <div class="row align-center">
          <div class="columns screen-50 tablet-80 mobile-100 phone-100">
            <div id="loginForm">
              <form method="post" id="login_form" name="yoyo_form">
                <h3 class="yoyo primary text"><?php echo Lang::$word->AUTH_WELCOME;?>
                  <span class="yoyo semi text"><?php echo Lang::$word->AUTH_BACK;?></span></h3>
                <p class="margin-bottom"><?php echo Lang::$word->AUTH_LOGIN_DESCRIPTION;?></p>
                <div class="yoyo form">
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->AUTH_EMAIL;?></label>
                      <input placeholder="<?php echo Lang::$word->AUTH_EMAIL;?>" name="email" type="text">
                    </div>
                    <div class="field">
                      <span class="yoyo mini text push-right"><a id="passreset" class="secondary dashed"><?php echo Lang::$word->AUTH_PASSWORD_RESET;?>?</a>
                      </span>
                      <label><?php echo Lang::$word->AUTH_PASSWORD;?></label>
                      <input placeholder="********" name="password" type="password">
                    </div>
                  </div>
                  <div class="yoyo fields">
                    <div class="field align-middle">
                      <p class="yoyo small text"><?php echo Lang::$word->AUTH_DONE_HAVE_AN_ACCOUNT;?>
                        <a href="<?php echo Url::url('/' . App::Core()->system_slugs->register[0]->{'slug' . Lang::$lang});?>"><?php echo Lang::$word->AUTH_SIGN_UP_HERE;?>.</a>
                      </p>
                    </div>
                    <div class="field content-right">
                      <button id="doLogin" class="yoyo primary wide button" name="submit" type="button"><?php echo Lang::$word->LOGIN;?></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div id="passForm" class="hide-all">
              <form method="post" id="pass_form" name="pass_form">
                <h3 class="yoyo primary text"><?php echo Lang::$word->M_SUB27;?>
                  <span class="yoyo semi text"><?php echo Lang::$word->M_SUB27_1;?></span></h3>
                <p><?php echo Lang::$word->M_SUB28;?></p>
                <div class="yoyo form">
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->M_EMAIL;?></label>
                      <input placeholder="<?php echo Lang::$word->M_EMAIL;?>" name="pemail" type="email">
                    </div>
                  </div>
                  <div class="yoyo fields">
                    <div class="field align-middle">
                      <p class="yoyo small text">
                        <a id="backToLogin"><?php echo Lang::$word->M_SUB14;?></a>
                      </p>
                    </div>
                    <div class="field content-right">
                      <button id="doPassword" class="yoyo primary wide button" name="submit" type="button"><?php echo Lang::$word->M_SUB29;?></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<p id="loginFooter">Copyright &copy;<?php echo date('Y') . ' '. $this->core->company;?></p>



<style>
body#fullpage {
    background: #181818;
}

.yoyo.form {
    border-left: 5px solid #eb1515;
    padding-left: 10px;
}

a, a.inverted, a.secondary, a.black, a.white {
    color: #eb1515 !important;
}

a:hover, a.hover, a:focus {
    color: #b01010 !important;
}

form#login_form {
    padding: 10px;
}
#sOverlay::after {
    background-image: linear-gradient(
            0deg
            , #000000a6 0%, #000000a6 100%);
}
#loginFooter {
	position:fixed;
}
#loginHeader a.logo img {
    width: 7rem;
}

h1, h2, h3, h4, h5, h6, label {
    color: #fff !important;
}
</style>