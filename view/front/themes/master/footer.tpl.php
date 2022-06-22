<?php
  /**
   * Footer
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<!-- Footer -->
<footer>
  <div class="wrapper xp">
    <div class="yoyo-grid xp">
      <div class="row gutters xp">
	 <div class="globalWraper"> 
	  <ul class="footulbar">
		<li><a href="/page/live-betting-terms/"><?= Lang::$word->FOOT_BETTING_TERMS; ?></a></li>
		<li><a href="/page/gambabet-faqs/"><?= Lang::$word->FOOT_FAQS; ?></a></li>
		<li><a href="/page/gambabet-affiliate/"><?= Lang::$word->FOOT_AFFILIATE; ?></a></li>
		<li><a href="/privacy-policy/"><?= Lang::$word->FOOT_PRIVACY_AND_COOKIES; ?></a></li>
		<li><a href="/page/terms-conditions/"><?= Lang::$word->FOOT_TERMS; ?></a></li>
		<li><a href="/page/gambabet-contact/"><?= Lang::$word->FOOT_CONTACT; ?></a></li>
		
	  </ul>	
	  
	  
	  
    <div class="betdisclaim">
        <?= Lang::$word->FOOT_CONTACT_DESC; ?>
</div></br>

<img id="gmbaware" src="<?php echo SITEURL;?>/shell/gambleaware.svg ">
		
		</br></br></br>
</div>
  

      <div class="row" id="btmwider">
        <div class="columns xp">
          <div class="content-right" id="showhidfoot">
            <a href="<?php echo SITEURL;?>" class="yoyo small simple icon secondary button"><i class="icon home"></i></a>
            <a href="//validator.w3.org/check/referer" target="_blank" class="yoyo small simple icon secondary button"><i class="icon html5"></i></a>
            <a href="<?php echo URl::url('/' . $this->core->system_slugs->sitemap[0]->{'slug' . Lang::$lang});?>" class="yoyo small simple icon secondary button"><i class="icon apps"></i></a>
            <a href="<?php echo SITEURL;?>/rss.php" class="yoyo small simple icon secondary button"><i class="icon rss"></i></a>
            <a href="//<?php echo $this->core->social->facebook;?>" class="yoyo small simple icon secondary button"><i class="icon facebook"></i></a>
            <a href="//<?php echo $this->core->social->twitter;?>" class="yoyo small simple icon secondary button"><i class="icon twitter"></i></a>
          </div>
        </div>
		</div>
      </div>
    </div>
  </div>
  
  
  
  
  <figure class="absolute xp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="271 -32.7 589.5 112.7">
      <linearGradient id="aa" x1="546.629" x2="574.638" y1="75.039" y2="-83.805" gradientTransform="matrix(1 0 0 -1 0 46.6)" gradientUnits="userSpaceOnUse">
        <stop offset="0" stop-color="#009a44"/>
        <stop offset="1" stop-color="#000"/>
      </linearGradient>
      <path fill="#eb1515" d="M271 32S402.5-30.5 548 8.5s205 66 312.5 71.5H271V32z"/>
      <path fill="#000" d="M271-32.7c0 .1 38.9 5.7 42.5 6.4C351.2-19 387.9-6.8 421.9 11c15.5 8.2 30.3 17.6 45.6 26.3 22.2 12.7 45.4 22.1 70.1 28.9 11 3 22.2 5.5 33.4 7.7 13.6 2.6 27.3 4.7 41 6.2H271V-32.7z"/>
    </svg>
  </figure>

</footer>








<div class="wrrslip" style="display:none">
<input type="text" class="numcc" id="numcs" value="0" disabled="">
<i class="icon grid align bottom right"></i>

</div>

<div class="btmlist" style="display:none">
<!--FOR EXCHANGE -->
<ul class="showexchp" style="display:none">
 <a id="exsp" href="/exchange#exch">
 <li id="fshwsp">
 <i class="icon clock"></i> Exchange sports <span class="nvright" id="gup">Upcoming</span>
 </li>
 </a>
<hr>
<a id="exsp" href="/inplay-view#exch">
 <li id="infshw">
 <i class="icon contrast"></i> Exchange In-Play <span class="nvright" id="gliv">Live</span>
 </li>
 </a>
</ul>

<!--FOR Sportsbook -->
<ul class="showsbook" style="display:none">
 <a id="exsp" href="/sportsbook-prematch#sbook">
 <li id="fshwsp">
 <i class="icon clock"></i> Sportsbook Prematch <span class="nvright" id="gup">Upcoming</span>
 </li>
 </a>
<hr>
<a id="exsp" href="/sportsbook-inplay#sbook">
 <li id="infshw">
 <i class="icon contrast"></i> Sportsbook In-Play <span class="nvright" id="sliv">Live</span>
 </li>
 </a>
</ul>


<!--FOR games -->
<ul class="showgaming" style="display:none">
 <a id="exsp" href="/casino/">
 <li id="fshwsp">
 <i class="icon database"></i> Casino <span class="nvright" id="slivv">Dealer</span>
 </li>
 </a>
<hr>
<a id="exsp" href="/slot/">
 <li id="infshw">
 <i class="icon gamepad"></i> Slot <span class="nvright" id="slivv">Games</span>
 </li>
 </a>
 <hr>
 <a id="exsp" href="/virtual-racing/">
 <li id="infshwx">
 <i class="icon ghost"></i> Virtual <span class="nvright" id="slivv">Racing</span>
 </li>
 </a>
</ul>




 <ul class="orbtmlist">
 <a href="/">
  <li id="exchp"><span class="updow"><i class="icon long arrow down"></i><i class="icon long arrow up"></i> </span></br>InPlay</li>
  </a>
  
  <a href="/sportsbook-prematch/"><li id="sbookp"><div class="onamebg sbook">.</div></br> Upcoming</li></a>
  
  <a href="/casino/"><li id="gamesp"><div class="onamebg games">.</div></br> Casino</li></a>
  
  <a href="/slot/"><li id="marketsp"><div class="onamebg market">.</div></br> Games</li></a>
  
  <a href="/sportsbook-prematch/?ref=today">
  <li id="forump">
  <span class="forumbb"><i class="icon faq"></i></br>Today
  </li>
  </a>
 </ul>

</div>


<script type="text/javascript" src="<?php echo THEMEURL;?>/js/master.js"></script>
<?php Debug::displayInfo();?>
<script type="text/javascript"> 
// <![CDATA[  
<?php if($this->core->ploader):?>
$(window).on('load', function() {
	setTimeout(function() {
		$("body").addClass("loaded");
	}, 200);
});
<?php endif;?>
$(document).ready(function() {
    $.Master({
		url: "<?php echo FRONTVIEW;?>",
		surl: "<?php echo SITEURL;?>",
        weekstart: <?php echo(App::Core()->weekstart);?>,
		ampm: <?php echo (App::Core()->time_format) == "HH:mm" ? 0 : 1;?>,
        lang: {
            monthsFull: [ <?php echo Date::monthList(false);?> ],
            monthsShort: [ <?php echo Date::monthList(false, false);?> ],
            weeksFull: [ <?php echo Date::weekList(false); ?> ],
            weeksShort: [ <?php echo Date::weekList(false, false);?> ],
			weeksMed: [ <?php echo Date::weekList(false, false, true);?> ],
            button_text: "<?php echo Lang::$word->BROWSE;?>",
            empty_text: "<?php echo Lang::$word->NOFILE;?>",
			sel_pic: "<?php echo Lang::$word->SELIMG;?>",
        }
    });
	<?php if($this->core->eucookie):?>
    $("body").acceptCookies({
        position: 'top',
        notice: '<?php echo Lang::$word->EU_NOTICE;?>',
        accept: '<?php echo Lang::$word->EU_ACCEPT;?>',
        decline: '<?php echo Lang::$word->EU_DECLINE;?>',
        decline_t: '<?php echo Lang::$word->EU_DECLINE_T;?>',
        whatc: '<?php echo Lang::$word->EU_W_COOKIES;?>'
    });
	<?php endif;?>
});
// ]]>



//odds change cookie

var saveclass = null;

function saveTheme(cookieValue){
    var sel = document.getElementById('ThemeSelect');

    saveclass = saveclass ? saveclass : document.body.className;
    document.body.className = saveclass + ' ' + sel.value;

    setCookie('theme', cookieValue, 365, { path: '/' });
}

function setCookie(cookieName, cookieValue, nDays) {
    var today = new Date();
    var expire = new Date();

    if (nDays==null || nDays==0)
        nDays=1000;

    expire.setTime(today.getTime() + 3600000*24*nDays);
    document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString() + "; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}
document.addEventListener('DOMContentLoaded', function() {
    var themeSelect = document.getElementById('ThemeSelect');
    var selectedTheme = readCookie('theme');

    themeSelect.value = selectedTheme;
    saveclass = saveclass ? saveclass : document.body.className;
    document.body.className = saveclass + ' ' + selectedTheme;
});

//odd changing reload
    $('select#ThemeSelect').on('change', function () {
	location.reload();
    });
	
	
	function setcookies(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';path=/' + ';expires=' + expires.toUTCString();
    }
	
	var offset = new Date().getTimezoneOffset();
//console.log(offset);

setcookies('localtime',offset, 365,  { path: '/' });

/*
$(document).bind("contextmenu",function(e){
      return false;
   });
	  
*/
</script>







<?php if(Utility::in_array_any(["dashboard", "checkout"], $this->segments)):?>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<?php endif;?>
<?php if($this->core->analytics):?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->core->analytics;?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo $this->core->analytics;?>');
</script>
<?php endif;?>
</body></html>