<?php
  /**
   * Register
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php //affiliates tracking
  if(!isset($_SESSION['aff']) and $_GET['aff']){
    $cookie_expire = time()+60*60*24*30;
    $_SESSION['aff'] = $_GET['aff'];
    setcookie('aff', $_SESSION['aff'], $cookie_expire, '/', '.'.$_SERVER['HTTP_HOST']);
} else if(!isset($_SESSION['said']) and $_GET['said']){
    $cookie_expire = time()+60*60*24*30;
    $_SESSION['said'] = $_GET['said'];
    setcookie('said', $_SESSION['said'], $cookie_expire, '/', '.'.$_SERVER['HTTP_HOST']);
}?>
<header id="loginHeader">
<div class="row">
<div class="columns screen-33 tablet-hide mobile-hide phone-hide">
  <a href="<?php echo SITEURL;?>/" class="white logo">
    <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?></a>
</div>
<div class="columns screen-hide tablet-100 mobile-100 phone-100">
  <a href="<?php echo SITEURL;?>/" class="dark logo">
    <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?></a>
</div>
</header>
<main>
  <div class="row fullsize">
    <div id="sOverlay" class="columns relative fullsize flex-block screen-33 tablet-hide mobile-hide phone-hide">
      <div class="wSlider" style="height:100vh" data-wslider='{"items":1,"autoloop":true,"arrows":false,"buttons":false,"autoplay":true,"autoplaySpeed":"500", "autoplayHoverPause":false}'>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-1.jpg);height:100vh"></div>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-2.jpg);height:100vh"></div>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-3.jpg);height:100vh"></div>
        <div class="holder" style="background-position: top center;background-repeat: no-repeat;background-size: cover;background-image: url(<?php echo ADMINVIEW;?>/images/sidebar-4.jpg);height:100vh"></div>
      </div>
    </div>
    <div class="columns align-center align-self-middle tablet-100 mobile-100 phone-100">
      <div class="yoyo-grid">
        <div class="row align-center">
          <div class="columns screen-50 tablet-80 mobile-100 phone-100">
            <div id="regForm">
              <form method="post" id="reg_form" name="reg_form">

			  
                <h3 class="yoyo primary text"><?php echo Lang::$word->AUTH_WELCOME_TO;?> -
                  <span class="yoyo semi text"><?php echo $this->core->company;?>
                  </span></h3>
                <p class="margin-bottom"><?php echo Lang::$word->AUTH_REGISTER_DESCRIPTION;?></p>
                <div class="yoyo form">
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->AUTH_EMAIL;?>
                        <i class="icon asterisk"></i></label>
                      <input name="email" type="email" placeholder="<?php echo Lang::$word->AUTH_EMAIL;?>">
                    </div>
                    <div class="field">
                      <label><?php echo Lang::$word->AUTH_PASSWORD;?>
                        <i class="icon asterisk"></i></label>
                      <input type="password" name="password" placeholder="********">
                    </div>
                  </div>
                  <div class="yoyo fields">
                    <div class="field">
                      <label><?php echo Lang::$word->AUTH_FIRST_NAME;?>
                        <i class="icon asterisk"></i></label>
                      <input name="fname" type="text" placeholder="<?php echo Lang::$word->AUTH_FIRST_NAME;?>">
                    </div>
                    <div class="field">
                      <label><?php echo Lang::$word->AUTH_LAST_NAME;?>
                        <i class="icon asterisk"></i></label>
                      <input name="lname" type="text" placeholder="<?php echo Lang::$word->AUTH_LAST_NAME;?>">
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
				  
				  
				  
				  <div class="yoyo fields">
                    <div class="field three wide" style="display:none">
                      <label>
                        <?php // echo 'Base Currency';?>
                        <?= Lang::$word->AUTH_LOCAL_CURRENCY; ?>
                        </label>
                      <select name="maincurrency" disabled>
					    <option value="USD">USD</option>
					  </select>
					  
                    </div>
                    <div class="field">
                      <label>
                        <?php //echo 'Local Currency';?>
                          <?= Lang::$word->AUTH_LOCAL_CURRENCY; ?>
                        <i class="icon asterisk"></i></label>
                      <select name="ucurrency">
					    <option value="USD">USD</option>
						
						<!-- <option value="USD">USD</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="AMD">AMD</option><option value="ANG">ANG</option><option value="AOA">AOA</option><option value="ARS">ARS</option><option value="AUD">AUD</option><option value="AWG">AWG</option><option value="AZN">AZN</option><option value="BAM">BAM</option><option value="BBD">BBD</option><option value="BCH">BCH</option><option value="BDT">BDT</option><option value="BGN">BGN</option><option value="BHD">BHD</option><option value="BIF">BIF</option><option value="BMD">BMD</option><option value="BND">BND</option><option value="BOB">BOB</option><option value="BRL">BRL</option><option value="BSD">BSD</option><option value="BTC">BTC</option><option value="BTG">BTG</option><option value="BWP">BWP</option><option value="BZD">BZD</option><option value="CAD">CAD</option><option value="CDF">CDF</option><option value="CHF">CHF</option><option value="CLP">CLP</option><option value="CNH">CNH</option><option value="CNY">CNY</option><option value="COP">COP</option><option value="CRC">CRC</option><option value="CUC">CUC</option><option value="CUP">CUP</option><option value="CVE">CVE</option><option value="CZK">CZK</option><option value="DASH">DASH</option><option value="DJF">DJF</option><option value="DKK">DKK</option><option value="DOP">DOP</option><option value="DZD">DZD</option><option value="EGP">EGP</option><option value="EOS">EOS</option><option value="ETB">ETB</option><option value="ETH">ETH</option><option value="FJD">FJD</option><option value="GEL">GEL</option><option value="GHS">GHS</option><option value="GIP">GIP</option><option value="GMD">GMD</option><option value="GNF">GNF</option><option value="GTQ">GTQ</option><option value="GYD">GYD</option><option value="HKD">HKD</option><option value="HNL">HNL</option><option value="HRK">HRK</option><option value="HTG">HTG</option><option value="HUF">HUF</option><option value="IDR">IDR</option><option value="ILS">ILS</option><option value="IQD">IQD</option><option value="IRR">IRR</option><option value="ISK">ISK</option><option value="JMD">JMD</option><option value="JOD">JOD</option><option value="JPY">JPY</option><option value="KES">KES</option><option value="KGS">KGS</option><option value="KHR">KHR</option><option value="KMF">KMF</option><option value="KRW">KRW</option><option value="KWD">KWD</option><option value="KYD">KYD</option><option value="KZT">KZT</option><option value="LAK">LAK</option><option value="LBP">LBP</option><option value="LKR">LKR</option><option value="LRD">LRD</option><option value="LSL">LSL</option><option value="LTC">LTC</option><option value="LYD">LYD</option><option value="MAD">MAD</option><option value="MDL">MDL</option><option value="MKD">MKD</option><option value="MMK">MMK</option><option value="MOP">MOP</option><option value="MUR">MUR</option><option value="MVR">MVR</option><option value="MWK">MWK</option><option value="MXN">MXN</option><option value="MYR">MYR</option><option value="MZN">MZN</option><option value="NAD">NAD</option><option value="NGN">NGN</option><option value="NIO">NIO</option><option value="NOK">NOK</option><option value="NPR">NPR</option><option value="NZD">NZD</option><option value="OMR">OMR</option><option value="PAB">PAB</option><option value="PEN">PEN</option><option value="PGK">PGK</option><option value="PHP">PHP</option><option value="PKR">PKR</option><option value="PLN">PLN</option><option value="PYG">PYG</option><option value="QAR">QAR</option><option value="RON">RON</option><option value="RSD">RSD</option><option value="RUB">RUB</option><option value="RWF">RWF</option><option value="SAR">SAR</option><option value="SBD">SBD</option><option value="SCR">SCR</option><option value="SDG">SDG</option><option value="SEK">SEK</option><option value="SGD">SGD</option><option value="SLL">SLL</option><option value="SOS">SOS</option><option value="SRD">SRD</option><option value="SVC">SVC</option><option value="SZL">SZL</option><option value="THB">THB</option><option value="TJS">TJS</option><option value="TMT">TMT</option><option value="TND">TND</option><option value="TOP">TOP</option><option value="TRY">TRY</option><option value="TTD">TTD</option><option value="TWD">TWD</option><option value="TZS">TZS</option><option value="UAH">UAH</option><option value="UGX">UGX</option><option value="UYU">UYU</option><option value="UZS">UZS</option><option value="VND">VND</option><option value="XAF">XAF</option><option value="XAG">XAG</option><option value="XAU">XAU</option><option value="XCD">XCD</option><option value="XLM">XLM</option><option value="XOF">XOF</option><option value="XRP">XRP</option><option value="YER">YER</option><option value="ZAR">ZAR</option><option value="ZMW">ZMW</option>
						-->
					  </select>					
                    </div>
                  </div>

				  
				  
				  
                  <div class="yoyo block fields">
                    <div class="field">
                      <label><?php echo Lang::$word->AUTH_CAPTCHA;?>
                        <i class="icon asterisk"></i></label>
                      <div class="yoyo right labeled fluid input">
                        <input placeholder="<?php echo Lang::$word->AUTH_CAPTCHA;?>" name="captcha" type="text">
                        <span class="yoyo label">
                        <?php echo Session::captcha();?>
                        </span>
                      </div>
                    </div>
                    <div class="field">
                      <div class="yoyo checkbox">
                        <input name="agree" type="checkbox" value="1" id="agree">
                        <label for="agree"><a href="<?php echo Url::url('/' . App::Core()->system_slugs->policy[0]->{'slug' . Lang::$lang});?>" class="secondary dashed"><small><?php echo Lang::$word->AUTH_POLICY;?></small></a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="yoyo fields align-middle">
                    <div class="field"><span class="yoyo small secondary text"><?php echo Lang::$word->AUTH_HAVE_AN_ACCOUNT;?></span>
                      <a href="<?php echo Url::url('/' . $this->core->system_slugs->login[0]->{'slug' . Lang::$lang});?>"><span class="yoyo small text"><?php echo Lang::$word->LOGIN;?>.</span></a>
                    </div>
                    <div class="field content-right">
                      <button class="yoyo primary button" data-action="register" name="dosubmit" type="button"><?php echo Lang::$word->AUTH_REGISTER_NOW;?></button>
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


<?php 
        if(!empty($_GET['aff'])){
		  $uaid = $_GET['aff'];
		  $ido = 'affc'.$uaid.'in';
		  $agid = preg_replace('/[^0-9]/', '', $ido);
		 }else{
		  $agid = '0';
		 }
		 
		 if(!empty($_GET['said'])){
			 $saaid = $_GET['said'];
		 }else{
			 $saaid = '0';
		 }
		 
		 $ui = $_SERVER['REMOTE_ADDR'];
		 if(!empty($ui)){
			 $uip = $ui;
		 }else{
			 $uip = 'unknown';
		 };
?>		

<script>

           
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/users-hits/stats",
			data: {
			method:'stats',
			uip:'<?php echo $uip;?>',
			agid:<?php echo $agid;?>,
			saaid:<?php echo $saaid;?>,
			},
		   success: function(html) {
			   //alert(html);
               }
		      });
			  
	</script>		  


	

<style>
body#fullpage {
    background: #181818;
}
.yoyo.form {
    border-left: 5px solid #eb1515;
    padding-left: 10px;
}
.onamebg.one {
    background-position: 0 -2049px;
    float: left;
    margin-top: 3px;
    margin-right: 5px;
    height: 73px;
}
.promflex {
    font-size: 83%;
    color: #9E9E9E;
}
#reg_form {
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
.yoyo.label {
    background-color: rgb(100 101 101);
}

a, a.inverted, a.secondary, a.black, a.white {
    color: #eb1515 !important;
}

a:hover, a.hover, a:focus {
    color: #b01010 !important;
}

h1, h2, h3, h4, h5, h6, label, span {
    color: #fff !important;
}
</style>