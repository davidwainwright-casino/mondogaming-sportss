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

  if (!App::Auth()->is_Admin()) {
	  Url::redirect(SITEURL . '/admin/login/'); 
	  exit; 
  }
 ?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $this->title;?></title>
<?php if(in_array(Core::$language, array("ar", "he"))):?>
<link href="<?php echo ADMINVIEW . '/cache/' . Cache::cssCache(array('base_rtl.css','transition_rtl.css','dropdown_rtl.css','image_rtl.css','button_rtl.css','message_rtl.css','list_rtl.css','table_rtl.css','datepicker_rtl.css','divider_rtl.css','form_rtl.css','input_rtl.css','icon_rtl.css','flags_rtl.css','label_rtl.css','segment_rtl.css','popup_rtl.css','dimmer_rtl.css','modal_rtl.css','progress_rtl.css','editor_rtl.css','utility_rtl.css','style_rtl.css'), ADMINBASE);?>" rel="stylesheet" type="text/css" />
<?php else:?>
<link href="<?php echo ADMINVIEW . '/cache/' . Cache::cssCache(array('base.css','transition.css','dropdown.css','image.css','button.css','message.css','list.css','table.css','datepicker.css','divider.css','form.css','input.css','icon.css','flags.css','label.css','segment.css','popup.css','dimmer.css','modal.css','progress.css','editor.css','utility.css','style.css'), ADMINBASE);?>" rel="stylesheet" type="text/css" />
<?php endif;?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
</head>
<body>
<?php if($this->core->ploader):?>
<div id="master-loader">
  <div class="wanimation"></div>
  <div class="curtains left"></div>
  <div class="curtains right"></div>
</div>
<?php endif;?>
<div id="mResults" class="yoyo page inverted dimmer">
  <a class="close white"><i class="icon delete"></i></a>
  <div class="padding"></div>
</div>
<div id="wrapper" class="clearfix">
<aside>
  <div class="bg" style="background-image: url('<?php echo ADMINVIEW;?>/images/<?php echo (isset($_COOKIE['ABG_CMS']) ? $_COOKIE['ABG_CMS'] : "sidebar-1.jpg");?>');">
    <div class="content">
      <a href="<?php echo Url::url("/admin");?>" class="logo">
        <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">': $this->core->company;?>
      </a>
      <div class="optiscroll is_sidebar">
	  
	  
        <nav>
          <ul>
            <li class="has-sub uavatar<?php if (Utility::in_array_any(["myaccount", "mypassword"], $this->segments)) echo ' active open';?>">
              <a href="#">
                <img src="<?php echo UPLOADURL;?>/avatars/<?php echo (App::Auth()->avatar) ? App::Auth()->avatar : "blank.svg";?>" alt="" class="avatar">
                <span><?php echo App::Auth()->name;?></span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
                <li>
                  <a<?php if (in_array("myaccount", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/myaccount");?>"><small>MA</small><span><?php echo Lang::$word->M_MYACCOUNT;?></span></a>
                </li>
                <li>
                  <a<?php if (in_array("mypassword", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/mypassword");?>"><small>PC</small><span><?php echo Lang::$word->M_SUB2;?></span></a>
                </li>
                <li>
                  <a href="<?php echo Url::Url("/admin/logout");?>"><small>L</small><span><?php echo Lang::$word->LOGOUT;?></span></a>
                </li>
                <li>
                  <div class="row half-horizontal-gutters">
                    <div class="columns">
                      <a class="sbg<?php echo (empty($_COOKIE['ABG_CMS']) or $_COOKIE['ABG_CMS'] == "sidebar-1.jpg") ? " active" : "";?>" data-img="sidebar-1.jpg">
                        <img src="<?php echo ADMINVIEW;?>/images/sidebar-1.jpg" alt="">
                      </a>
                    </div>
                    <div class="columns">
                      <a class="sbg<?php echo (isset($_COOKIE['ABG_CMS']) and $_COOKIE['ABG_CMS'] == "sidebar-2.jpg") ? " active" : "";?>" data-img="sidebar-2.jpg">
                        <img src="<?php echo ADMINVIEW;?>/images/sidebar-2.jpg" alt="">
                      </a>
                    </div>
                    <div class="columns">
                      <a class="sbg<?php echo (isset($_COOKIE['ABG_CMS']) and $_COOKIE['ABG_CMS'] == "sidebar-3.jpg") ? " active" : "";?>" data-img="sidebar-3.jpg">
                        <img src="<?php echo ADMINVIEW;?>/images/sidebar-3.jpg" alt="">
                      </a>
                    </div>
                    <div class="columns">
                      <a class="sbg<?php echo (isset($_COOKIE['ABG_CMS']) and $_COOKIE['ABG_CMS'] == "sidebar-4.jpg") ? " active" : "";?>" data-img="sidebar-4.jpg">
                        <img src="<?php echo ADMINVIEW;?>/images/sidebar-4.jpg" alt="">
                      </a>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
			
			
			
			
			
			
			
			
            <li class="has-sub <?php if (Utility::in_array_any(["templates","menus","pages","languages","fields","coupons"], $this->segments)) echo ' active open';?>">
              <a href="#">
                <img src="<?php echo ADMINVIEW;?>/images/menu_content.svg">
                <span><?php echo Lang::$word->ADM_CONTENT;?></span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
                <?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("menus", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/menus");?>"><small>MM</small><span><?php echo Lang::$word->ADM_MENUS;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("pages", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/pages");?>"><small>MP</small><span><?php echo Lang::$word->ADM_PAGES;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_coupons')):?>
                <li>
                  <a<?php if (in_array("coupons", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/coupons");?>"><small>MC</small><span><?php echo Lang::$word->ADM_COUPONS;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_languages')):?>
                <li>
                  <a<?php if (in_array("languages", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/languages");?>"><small>ML</small><span><?php echo Lang::$word->ADM_LNGMNG;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_fields')):?>
                <li>
                  <a<?php if (in_array("fields", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/fields");?>"><small>CF</small><span><?php echo Lang::$word->ADM_CFIELDS;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_email')):?>
                <li>
                  <a<?php if (in_array("templates", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/templates");?>"><small>ET</small><span><?php echo Lang::$word->ADM_EMTPL;?></span></a>
                </li>
                <?php endif;?>
              </ul>
            </li>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			    <li class="has-sub <?php if (Utility::in_array_any(["templates","prematch-events","prematch-active-slips","prematch-settled-slips","prematch-others"], $this->segments)) echo ' active open';?>">
              <a href="#">
                <i style="margin-right:15px!Important;margin-left:8px;font-size:30px;color:#e91e63" class="icon blackboard graph"></i>
                <span>Events Control</span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
			  
                <?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("prematch-events", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/prematch-events");?>"><small>SB</small><span>Sportsbook Prematch Events</span></a>
                </li>
                <?php endif;?>
				
				<?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("inplay-events", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/inplay-events");?>"><small>SI</small><span>Sportsbook In-Play Events</span></a>
                </li>
                <?php endif;?>
				
				
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("exchange-prematch-events", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/exchange-prematch-events");?>"><small>EP</small><span>Exchange Prematch Events</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_coupons')):?>
                <li>
                  <a<?php if (in_array("exchange-inplay-events", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/exchange-inplay-events");?>"><small>EI</small><span>Exchange In-Play Events</span></a>
                </li>
                <?php endif;?>
				
				
				
                <?php if (Auth::hasPrivileges('manage_languages')):?>
                <li>
                  <a<?php if (in_array("prematch-others", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/prematch-others");?>"><small>PO</small><span>Others</span></a>
                </li>
                <?php endif;?>
                
              </ul>
            </li>
			
			
			
			
			
			<li class="has-sub <?php if (Utility::in_array_any(["templates","inplay-events","inplay-active-slips","inplay-settled-slips","inplay-others"], $this->segments)) echo ' active open';?>">
              <a href="#">
                <i style="margin-right:15px!Important;margin-left:8px;font-size:30px;color:#11a753" class="icon book"></i>
                <span>Tickets Control</span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
				
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("inplay-active-slips", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/inplay-active-slips");?>"><small>PA</small><span>Active Slips</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_coupons')):?>
                <li>
                  <a<?php if (in_array("inplay-settled-slips", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/inplay-settled-slips");?>"><small>PS</small><span>Settled Slips</span></a>
                </li>
                <?php endif;?>
				
				
				
                <?php if (Auth::hasPrivileges('manage_languages')):?>
                <li>
                  <a<?php if (in_array("inplay-others", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/inplay-others");?>"><small>PO</small><span>Others</span></a>
                </li>
                <?php endif;?>
                
              </ul>
            </li>
			
			<li<?php if (in_array("casino-slot", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/casino-slot");?>"><i style="margin-right:15px!Important;margin-left:4px;font-size:30px;color:orange;" class="icon database"></i><span>Casino, Slot & Games</span></a>
            </li>
			
			
			
			 <li<?php if (in_array("risk-management", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/risk-management");?>"><i style="margin-right:15px!Important;margin-left:4px;font-size:30px;color:#009688;" class="icon key"></i><span>Risk Management</span></a>
            </li>
			
			
			
			
			
			
			<li class="has-sub <?php if (Utility::in_array_any(["templates","tickets-stats","visitors-stats","other-stats"], $this->segments)) echo ' active open';?>">
              <a href="#">
            <i style="margin-right:15px!Important;margin-left:8px;font-size:30px;" class="icon css"></i>
                <span>Statistics Console</span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
                <?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("tickets-stats", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/tickets-stats");?>"><small>TS</small><span>Earnings & Tickets Stats</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("visitors-stats", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/visitors-stats");?>"><small>VS</small><span>Visitors Stats</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_coupons')):?>
                <li>
                  <a<?php if (in_array("other-stats", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/other-stats");?>"><small>OS</small><span>Other Stats</span></a>
                </li>
                <?php endif;?>
				
                
              </ul>
            </li>
			
			
			
			
			
			<li class="has-sub <?php if (Utility::in_array_any(["templates","players-mgt","agents-mgt","sagents-mgt"], $this->segments)) echo ' active open';?>">
              <a href="#">
            <img src="<?php echo ADMINVIEW;?>/images/menu_users.svg">
                <span>User's Management</span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
				
                <?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("players-mgt", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/players-mgt");?>"><small>PM</small><span>Player's Management</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("agents-mgt", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/agents-mgt");?>"><small>AM</small><span>Agent's Management</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_coupons')):?>
                <li>
                  <a<?php if (in_array("sagents-mgt", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/sagents-mgt");?>"><small>SE</small><span>Super Agents/Exchange Brokers</span></a>
                </li>
                <?php endif;?>
				
				<?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a href="/admin/users/"><small>AM</small><span>All Management</span></a>
                </li>
				<?php endif;?>
				
                
              </ul>
            </li>
			
			
			
			
			<li class="has-sub <?php if (Utility::in_array_any(["templates","users-credit-history","agents-credit-history", "prematch-history", "bet-history","others"], $this->segments)) echo ' active open';?>">
              <a href="#">
            <i style="margin-right:15px!Important;margin-left:8px;font-size:30px;" class="icon calendar add"></i>
                <span>History Management</span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
                <?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("users-credit-history", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/users-credit-history");?>"><small>UC</small><span>User's Credit History</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("agents-credit-history", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/agents-credit-history");?>"><small>AM</small><span>Agent's Credit History</span></a>
                </li>
                <?php endif;?>
				
				<?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("bet-history", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/bet-history");?>"><small>IH</small><span>In-Play Bet History</span></a>
                </li>
                <?php endif;?>
				
				<?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("prematch-history", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/prematch-history");?>"><small>PH</small><span>Prematch Bet History</span></a>
                </li>
                <?php endif;?>
                
                
              </ul>
            </li>
			
			
			
			
			
			
			
			
			
			<li class="has-sub <?php if (Utility::in_array_any(["templates","deposits","withdrawals", "sagents-transfers", "trans-log"], $this->segments)) echo ' active open';?>">
              <a href="#">
            <i style="margin-right:15px!Important;margin-left:8px;font-size:30px;color:#fb002e;" class="icon database alt"></i>
                <span>Financial Management</span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
                <?php if (Auth::hasPrivileges('manage_menus')):?>
                <li>
                  <a<?php if (in_array("deposits", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/deposits");?>"><small>DP</small><span>Deposits</span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("withdrawals", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/withdrawals");?>"><small>AM</small><span>Withdrawals</span></a>
                </li>
                <?php endif;?>
				
				<?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("sagents-transfers", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/sagents-transfers");?>"><small>AW</small><span>Exchange Brokers Transfers</span></a>
                </li>
                <?php endif;?>
				
				<?php if (Auth::hasPrivileges('manage_pages')):?>
                <li>
                  <a<?php if (in_array("trans-log", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/trans-log");?>"><small>TL</small><span>Transactions Logs</span></a>
                </li>
                <?php endif;?>
                
                
              </ul>
            </li>
			
			 <li<?php if (in_array("support", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/support");?>"><i style="margin-right:15px!Important;margin-left:8px;font-size:30px;" class="icon question sign"></i><span>Support Dashboard</span></a>
            </li>
			
			
			
			
			
			
		

            <li<?php if (in_array("layout", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/layout");?>"><img src="<?php echo ADMINVIEW;?>/images/menu_layout.svg"><span><?php echo Lang::$word->ADM_LAYOUT;?></span></a>
            </li>
            <li<?php if (in_array("memberships", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/memberships");?>"><img src="<?php echo ADMINVIEW;?>/images/menu_memberships.svg"><span><?php echo Lang::$word->ADM_MEMBS;?></span></a>
            </li>
            <li<?php if (in_array("modules", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/modules");?>"><img src="<?php echo ADMINVIEW;?>/images/menu_modules.svg"><span><?php echo Lang::$word->MODULES;?></span></a>
            </li>
            <li<?php if (in_array("plugins", $this->segments)) echo ' class="active"';?>>
              <a href="<?php echo Url::Url("/admin/plugins");?>"><img src="<?php echo ADMINVIEW;?>/images/menu_plugins.svg"><span><?php echo Lang::$word->PLUGINS;?></span></a>
            </li>
            <li class="has-sub <?php if (Utility::in_array_any(["backup", "manager", "mailer", "countries", "configuration"], $this->segments)) echo ' active open';?>">
              <a href="#">
                <img src="<?php echo ADMINVIEW;?>/images/menu_settings.svg">
                <span><?php echo Lang::$word->ADM_CONFIG;?></span>
                <i class="icon chevron down"></i>
              </a>
              <ul>
                <?php if (Auth::checkAcl("owner")):?>
                <li>
                  <a<?php if (in_array("configuration", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/configuration");?>"><small>C</small><span><?php echo Lang::$word->ADM_SYSTEM;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_backup')):?>
                <li>
                  <a<?php if (in_array("backup", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/backup");?>"><small>B</small><span><?php echo Lang::$word->ADM_BACKUP;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_files')):?>
                <li>
                  <a<?php if (in_array("manager", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/manager");?>"><small>F</small><span><?php echo Lang::$word->ADM_FM;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_newsletter')):?>
                <li>
                  <a<?php if (in_array("mailer", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/mailer");?>"><small>N</small><span><?php echo Lang::$word->ADM_NEWSL;?></span></a>
                </li>
                <?php endif;?>
                <?php if (Auth::hasPrivileges('manage_countries')):?>
                <li>
                  <a<?php if (in_array("countries", $this->segments)) echo ' class="active"';?> href="<?php echo Url::Url("/admin/countries");?>"><small>C</small><span><?php echo Lang::$word->ADM_CNTR;?></span></a>
                </li>
                <?php endif;?>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</aside>
<main>
<div class="mcontent">
<header>
  <div class="row gutters align-middle">
    <div class="columns shrink mobile-order-1 phone-order-1"><a id="mtoggle" class="yoyo white icon circular button"><i class="icon vertical ellipsis"></i></a>
    </div>
    <div class="columns mobile-order-2 phone-order-2">
      <div class="yoyo small breadcrumb">
        <?php echo Url::crumbs($this->crumbs ? $this->crumbs : $this->segments, "//", Lang::$word->HOME);?>
      </div>
    </div>
    <div class="columns screen-30 tablet-50 mobile-100 phone-100 mobile-order-4 phone-order-4">
      <div class="yoyo fluid transparent right icon input">
        <input type="text" placeholder="<?php echo Lang::$word->FIND;?>" id="masterSearch">
        <button class="yoyo white circular icon button"><i class="icon find"></i></button>
      </div>
    </div>
    <?php if (Auth::checkAcl("owner")):?>
    <div class="columns shrink mobile-order-3 phone-order-3">
      <a data-dropdown="#dropdown-aMenu" class="yoyo white icon circular button">
        <i class="icon horizontal ellipsis"></i>
      </a>
      <div class="yoyo small dropdown menu top-right" id="dropdown-aMenu">
        <a class="item" href="<?php echo Url::url("/admin/permissions");?>"><i class="icon lock"></i>
          <span class="padding-left"><?php echo Lang::$word->ADM_PERMS;?></span></a>
        <a class="item" href="<?php echo Url::url("/admin/transactions");?>"><i class="icon wallet"></i>
          <span class="padding-left"><?php echo Lang::$word->ADM_TRANS;?></span></a>
        <a class="item" href="<?php echo Url::url("/admin/utilities");?>"><i class="icon sliders vertical alt"></i>
          <span class="padding-left"><?php echo Lang::$word->ADM_UTIL;?></span></a>
        <a class="item" href="<?php echo Url::url("/admin/system");?>"><i class="icon laptop"></i>
          <span class="padding-left"><?php echo Lang::$word->SYS_TITLE;?></span></a>
        <a class="item" href="<?php echo Url::url("/admin/gateways");?>"><i class="icon credit card"></i>
          <span class="padding-left"><?php echo Lang::$word->ADM_GATE;?></span></a>
        <a class="item" href="<?php echo Url::url("/admin/trash");?>"><i class="icon trash"></i>
          <span class="padding-left"><?php echo Lang::$word->ADM_TRASH;?></span></a>
      </div>
    </div>
    <?php endif;?>
  </div>
</header>