<?php
  /**
   * Header
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
 ?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $this->title;?></title>
<meta name="keywords" content="<?php echo $this->keywords;?>">
<meta name="description" content="<?php echo $this->description;?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="dcterms.rights" content="<?php echo $this->core->company;?> &copy; All Rights Reserved">
<meta name="robots" content="index">
<meta name="robots" content="follow">
<meta name="revisit-after" content="1 day">
<meta name="generator" content="Powered by CRIC INTERNATIONAL! v<?php echo $this->core->yoyov;?>">
<link rel="shortcut icon" href="<?php echo SITEURL;?>/assets/favicon.ico" type="image/x-icon">
<?php (in_array(Core::$language, array("ar", "he"))):?>
<link href="<?php echo THEMEURL . '/cache/' . Cache::cssCache(array('base_rtl.css', 'transition_rtl.css', 'button_rtl.css', 'divider_rtl.css', 'icon_rtl.css', 'flag_rtl.css', 'image_rtl.css', 'label_rtl.css', 'form_rtl.css', 'input_rtl.css', 'list_rtl.css', 'segment_rtl.css', 'exchange_rtl.css','card_rtl.css', 'table_rtl.css', 'dropdown_rtl.css', 'popup_rtl.css', 'statistic_rtl.css', 'datepicker_rtl.css', 'message_rtl.css', 'dimmer_rtl.css', 'modal_rtl.css', 'progress_rtl.css', 'editor_rtl.css', 'item_rtl.css', 'feed_rtl.css', 'comment_rtl.css', 'utility_rtl.css', 'style_rtl.css'), THEMEBASE); ?>" rel="stylesheet" type="text/css">
<?php else:?>
<link href="<?php echo THEMEURL . '/cache/' . Cache::cssCache(array('base.css','transition.css', 'button.css', 'divider.css', 'icon.css', 'flag.css', 'image.css', 'label.css', 'form.css', 'input.css', 'list.css','segment.css','card.css','table.css','dropdown.css','exchange.css','popup.css','statistic.css','datepicker.css','message.css','dimmer.css','modal.css','progress.css','accordion.css','item.css','feed.css','comment.css','editor.css','utility.css','style.css'), THEMEBASE);?>" rel="stylesheet" type="text/css">
<?php endif;?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
<link href="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginCssCache(THEMEBASE . '/plugins');?>" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleCssCache(THEMEBASE . '/modules');?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginJsCache(THEMEBASE . '/plugins');?>"></script>
<script type="text/javascript" src="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleJsCache(THEMEBASE . '/modules');?>"></script>
</head>
<body class="page_<?php echo Url::doSeo($this->segments[0]);?>">
<?php if($this->core->ploader):?>
<!-- Page Loader -->
<div id="master-loader">
  <div class="wanimation"></div>
  <div class="curtains left"></div>
  <div class="curtains right"></div>
</div>
<?php endif;?>
<header id="header">
  <div class="yoyo-grid">
    <div class="top-bar">
      <div class="row align-middle">
        <!--Lang Switcher-->
        <?php if($this->core->showlang):?>
        <div class="columns shrink">
          <a data-dropdown="#dropdown-langChange" class="yoyo mini secondary button">
          <div class="description">
		  <span class="flag icon en" id="repcl"></span> <span id="ltext" class="ttxt active">English </span></div>
          <i class="icon small chevron down" id="cvvr"></i>
          </a>
          <div class="yoyo small dropdown menu top-center" id="dropdown-langChange">
		    <?php include_once('language_list.php');?>
          </div>
        </div>
        <?php endif;?>
        <!--Lang Switcher End if($this->core->showsearch)-->
        
        <div class="columns content-right">
		
		 
		<div class="yoyo fluid action input" id="offno">
           
		   <a href="<?php echo SITEURL;?>/" class="logo">
		  <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?>
		  </a>
            </div>
		
       

		  
          <div class="yoyo horizontal list xp">
		  <div class="showtopnotes" style="display:none">
			  <span class="noticeon">
			  <?php $ths = $this->core->noticef;
			  if(!empty($ths)){
				  echo $ths;echo '</br>';
				  $ncount = 1;
				  echo 'Read more <a href="/page/noticeboard/">here</a>';
			  }else{
				   echo 'No new notification found..';
				   $ncount = 0;
			  }
			  ?>
			 
			  </span>
			  <hr>
			  
			   <span id="odd-switcher-wrapper">
			   Odds Format
				    <select id="ThemeSelect" onchange="saveTheme(this.value);">
					 <option value="decimal" selected>Decimal</option>
					 <option value="fraction">Fraction</option>
					 <option value="american">American</option>
				    </select>
				</span>
				</br>
				<hr>
				
				<span class="locktime">
				Your Local time has been set at</br>
				<b><?php $lt = $_COOKIE['localtime']; echo $lt/60;?> Hours GMT</b></span>
				</br>
				</br>				
			 </div>
		    <div class="item xp">
			 <div href="#" class="topnotes">
			  <i class="icon bell"></i>
			  <span class="notifone"><?php $ncc = $ncount +1; echo $ncc;?></span>
			 </div>
			 
			 
			 
			 
			 
			 
            </div>
			
			<div class="item" id="nomobsi">
			 <div href="#" class="topqs">
			 <?php $go=str_replace(' ', '', App::Auth()->fname);
			if(!empty($go)):?>
  <a href="/forum/traders/login?ppcric=0fc431347b1699eef6&uucric=<?php echo $go;?>">
			  <i class="icon copy"></i>
			  </a>
			  <?php else:?>
			  <a href="/forum/traders">
			  <i class="icon copy"></i>
			  </a>
			  <?php endif;?>
			 </div>
            </div>
		

			<?php //if($this->core->showlogin):?>
			 <?php if(App::Auth()->is_User()):?>
			 
			 <div href="#" id="acuser">
              <span class="cchips"> <span class="cicon">
			  <?php $usid=App::Auth()->uid;$gu = Db::run()->first(Users::mTable, array("stripe_cus", "chips", "promo"), array("id" => $usid));$cur=$gu->stripe_cus; $moo=$gu->chips;echo $cur; ?></span> <span class="cbalf"> <?php echo number_format((float)$moo, 2, '.', '');?></span></span> <span class="activeusr">u</span> 
			  </div>
			  
			 <ul class="iconnav" style="display:none">
			  <li class="topiconlist">
			   <span class="liconav"><?php echo App::Auth()->fname;?></br><b><?php echo $cur;?>  <?php echo $moo;?></b></span> 
			   <span class="riconav"><a class="icodeposit" href="/bt_accounts/?pg103=bnk&bb=bb&dd=1">Deposit</a></span>
			   <hr>
			   <span class="liconav">Withdrawalable</br><b><?php echo $cur;?> <?php echo number_format((float)$moo, 2, '.', '');?></b></span> 
			   <span class="riconav bcr">Bet Credits</br><b><?php echo $cur;?> <?php echo number_format((float)$gu->promo, 2, '.', '');?></b></span>
			  </li>
			  
			  <a href="/bt_accounts/?pg103=bnk&bb=bb">
			  <li>
			   <span class="liconav">Bank</span> 
			   <span class="riconav"><i style="font-size:13px" class="icon money"></i></span>
			  </li>
			  </a>
			  
			  <?php $kty = App::Auth()->type;if($kty =='agent'):?>
			  <a href="/affagent/">
			  <li class="agtdash">
			   <span class="liconav">Agent/aff. Dashboard</span> 
			   <span class="riconav"><i class="icon dashboard"></i></span>
			  </li>
			 </a>
			 <?php elseif($kty=='Sagent'):?>
			 <a href="/suppagent/">
			  <li class="sagtdash">
			   <span class="liconav">Super Agent. Dashboard</span> 
			   <span class="riconav"><i class="icon dashboard"></i></span>
			  </li>
			 </a>
			 <?php endif;?>
			  
			  <a href="/bt_accounts/?pg103=sl">
			  <li>
			   <span class="liconav">Open Betslips</span> 
			   <span class="riconav"><i class="icon invoice"></i></span>
			  </li>
			  </a>
			  
			  <a href="/bt_accounts/?pg103=sl&esettled=1">
			  <li>
			   <span class="liconav">Settled Betslips</span> 
			   <span class="riconav"><i class="icon layer"></i></span>
			  </li>
			  </a>
			  
			  <a href="/bt_accounts/?pg104=casgame">
			  <li>
			   <span class="liconav">Casino,Slot,Virtual History</span> 
			   <span class="riconav"><i class="icon gamepad"></i></span>
			  </li>
			  </a>
			  
			 
			 <!--<a href="/bt_accounts/?pg105=mkt">
			  <li>
			   <span class="liconav">Markets</span> 
			   <span class="riconav"><i class="icon bar chart alt"></i></span>
			  </li>
			 </a>-->
			 
			 <a href="/dashboard/settings/?pg103=acset">
			  <li>
			   <span class="liconav">Settings/Profile</span> 
			   <span class="riconav"><i class="icon settings alt"></i></span>
			  </li>
			 </a>
			 
			 <a href="/bt_accounts/?pg103=pcredit">
			  <li>
			   <span class="liconav">Promo Credit</span> 
			   <span class="riconav"><i class="icon gift"></i></span>
			  </li>
			 </a>
			 
			 <a href="/bt_accounts/?pg103=msg">
			  <li>
			   <span class="liconav">Messaging</span> 
			   <span class="riconav"><i class="icon email alt"></i></span>
			  </li>
			 </a>
			 
			 <a href="/bt_accounts/?pg103=gmbc">
			  <li>
			   <span class="liconav">Gambling Control</span> 
			   <span class="riconav"><i class="icon asterisk"></i></span>
			  </li>
			 </a>
			 
			 
			 <a href="/logout/">
			  <li>
			   <span class="liconav">Logout</span> 
			   <span class="riconav"><i style="color:#f00" class="icon power"></i></span>
			  </li>
			 </a>
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			  
		   </ul>
              
			  
			  <?php else:?>
            <div class="item" id="logjoin">
              <a href="/registration/" class="joinuri"><i class="icon user add"></i> JOIN</a>
            </div>
			
			<div class="item" id="logjoin">
			<?php  
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $xurl.= $_SERVER['REQUEST_URI'];    
      

  ?> 
              <a href="/login/?cref=<?php echo $xurl;?>" class="loginuri"><i class="icon unlock"></i> LOGIN </a>
            </div>
			
			<a href="/login/?cref=<?php echo $xurl;?>" class="loginleft" style="display:none"><i class="icon unlock"></i> LogIn</a>
	         <div class="logjos" id="jolog" style="display:none">
              <a href="/registration/" class="loginjs"><i class="icon users"></i> JOIN</a>
            </div>
			
			
			
			<?php endif;?>
			<?php //endif;?>
			
			
			</div>


		  
        </div>
      </div>
	  
    </div>
	
	     
 
    <div class="bottom-bar">
      <div class="row align-middle">
	  
	  
        <div class="columns shrink mobile-80 phone-80">
		 
          <a href="<?php echo SITEURL;?>/" class="logo">
		  <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?>
		  </a>
		  </div>
        
		
		
        <div class="columns mobile-20 screen-hide tablet-hide phone-20 content-right">
		<a href="#" class="menu-mobile"></a>
        </div>
		
		
		
        <div class="columns mobile-100 phone-100 content-right">
          <nav class="menu"><?php echo App::Content()->renderMenu($this->menu);?></nav>
        </div>
		
		
      </div>
    </div>
  </div>
  




<style type="text/css">
.showtopnotes {
    position: absolute;
    background: #f1f1f1;
    padding: 10px;
    color: #338636;
    z-index: 999;
    margin-top: 40px;
    right: 0;
    text-align: left;
    width: 300px;
}
.yoyo.horizontal.list.xp {
    position: relative;
    float: left;
}
.cgcolor{
	background:#f00;
	color:#fff;
}
.showtopnotes::after {
    width: 0;
    height: 0;
    content: '';
    display: inline-block;
    position: absolute;
    border-color: transparent;
    border-style: solid;
    -webkit-transform: rotate(360deg);
    border-width: 0 8px 10px;
    border-bottom-color: #f1f1f1;
    top: -8px;
    right: 8px;
}


body.page_suppagent #header, body.page_suppagent footer,body.page_affagent #header, body.page_affagent footer {
    display: none;
}
body.page_suppagent, body.page_agent-registration,body.page_affagent, body.page_agent-registration {
    background: #fff;
}
body.page_agent-registration #header, body.page_agent-registration footer {
    display: none;
}


/*agent top navigation*/
ul.suppmenu {
    display: flex;
    width: 100%;
    padding: 0px;
    margin: 0 auto;
}

ul.suppmenu li {
    display: table-cell;
    width: 50%;
}

.sidenavs li {
    position: relative;
    display: block;
    width: 100%;
    padding: 10px 15px;
    border-bottom: 1px solid #e7e7e7;
    cursor: pointer;
    color: #337ab7;
}
/*for profile dashboard list */
ul.history_menu {
    width: 100%;
    padding: 20px 0px 0px 0px;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    margin: 0;
}
ul.history_menu li {
    display: inline-block;
    padding: 1px 10px;
    margin: 0px;
    color: #f1f1f1;
    font-size: 16px;
}













@media screen and (max-width: 48.063em){
.excol.ileft {
    display: none;
}

.excol.icenter {
    width: 100%;
 }
}
</style>
</header> 

<!-- Code provided by Google -->
<div id="google_translate_element" style="display:none"></div>
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'google_translate_element'); //remove the layout
  }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>


<script type="text/javascript">

setTimeout(function(){
var lang = $("select.goog-te-combo option:selected").text();
if(lang == 'Select Language'){
	return;
}
 $('.ttxt.active').text(lang);
 //var rep = $('#repcl').attr('class');
}, 
5000);


    function triggerHtmlEvent(element, eventName) {
var event; 
if(document.createEvent) {
    event = document.createEvent('HTMLEvents');
    event.initEvent(eventName, true, true);
    element.dispatchEvent(event);
} else {
    event = document.createEventObject();
    event.eventType = eventName;
    element.fireEvent('on' + event.eventType, event);
}
}
            <!-- Flag click handler -->
 $('.yoyo.small.dropdown.menu.top-center lan').click(function(e) {
  e.preventDefault();
  var lang = $(this).data('lang');
  $('#google_translate_element select option').each(function(){
    if($(this).text().indexOf(lang) > -1) {
        $(this).parent().val($(this).val());
        var container = document.getElementById('google_translate_element');
        var select = container.getElementsByTagName('select')[0];
        triggerHtmlEvent(select, 'change');
    }
});
setTimeout(function(){
lang = $("select.goog-te-combo option:selected").text();
 $('.ttxt.active').text(lang);
}, 
5000);
});

        </script>