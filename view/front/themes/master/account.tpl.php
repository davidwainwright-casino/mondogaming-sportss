<?php
/**
 * Account
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<main>
  <?php include_once(THEMEBASE . '/inc_account_header.php');?>
  
  <?php switch(Url::segment($this->segments, 1)): case "affiliates": ?>
  <?php include_once(THEMEBASE . '/_affiliates.tpl.php');?>
  <?php break;?>
  <?php case "settings": ?>
  <?php include_once(THEMEBASE . '/_settings.tpl.php');?>
  <?php break;?>
  <?php case "super_agent": ?>
  <?php include_once(THEMEBASE . '/_super_agent.tpl.php');?>
  <?php break;?>
 <?php endswitch;?>
  
</main>







<style>

    .accontrol .text {
        color: #fff;
    }

div#ui-datepicker-div{background:#fff;padding:10px}span.ui-icon.ui-icon-circle-triangle-e{float:right}i#frdate,i#todate{position:absolute;margin-top:3px;cursor:pointer}i#frdate:hover,i#todate:hover{visibility:hidden}input#my_date_picker1,input#my_date_picker2{padding-left:18px;border:none;cursor:pointer;max-width:90px}.filterdate{border-bottom:1px solid #3bcc45;padding-bottom:10px;margin-bottom:10px;padding-left:10px}ul.history_menu{width:100%;padding:10px 5px 1px 5px;overflow-x:auto;overflow-y:hidden;white-space:nowrap;margin:0}.hactive{border-bottom:2px solid #eb1515}.addfilter{display:inline-block;background:#03a9f4;padding:0 5px;font-size:12px;color:#fff;border-radius:3px}.addfilter:hover{background:#094794;cursor:pointer}div#fetchslips{padding-bottom:100px}#jinx{border-left:3px solid #2196f3;min-height:780px;max-width:760px;margin:0 auto;margin-top:20px;background:#fff;margin-bottom:100px}ul.history_menu li{display:inline-block;padding:1px 10px;margin:0;color:#f1f1f1;font-size:16px}.accontrol{max-width:760px;margin:0 auto}#dashboard{padding-top:10px;margin:0}ul.topsubnav{padding:0 10px;max-width:760px;margin:0 auto;border-left:3px solid #27b028;margin-top:10px}ul.topsubnav li{display:inline-block;margin-right:15px;color:#464286}.tsub{background:#b1b1b1;border-radius:3px;padding:0 10px;color:#000;cursor:pointer}ul.topsubnav li:hover{color:red;cursor:pointer}ul.jx li,ul.jxsettled li{display:inline-block;font-family:arial;margin-right:15px}ul.jx,ul.jxsettled{padding:4px 10px;background:#d4e3f9;overflow-x:auto;overflow-y:hidden;white-space:nowrap}ul.jx li:hover,ul.jxsettled li:hover{color:red;cursor:pointer}.jxset,.jxsub{border-bottom:3px solid #04d2b3;color:red}ul.jxsettled{background:#dbffb1}.exsportsbook{max-width:760px;margin:0 auto;border-bottom:1px solid #bbb;padding:5px 10px;background:#fbfbfb;font-family:arial}a#cgames,a#sportsbook{margin-left:10px}a#sportsbook:hover{color:#000}ul.aslipwrap li{display:inline-block;background:#ecffc7;max-width:760px;width:100%;padding:5px 10px;line-height:20px;font-family:arial;font-size:14px;color:#529000}ul.aslipwrap{margin:10px;padding:0}.rightwrap{float:right}.rightwrap.type{font-weight:700;color:red}.loadmo,.loadmox,.preloadmo{color:#a2a2a2;background:#d8d8d8;margin-left:10px;font-family:arial;max-width:120px;border-radius:3px;cursor:pointer;text-align:center}.loadmo:hover,.loadmox:hover,.preloadmo:hover{background:#615f5f}.eventslip{border-top:1px solid #c7c7c7;font-size:12px;color:#9a9191}.leftwrap.bold{font-weight:700;color:#000}.timefram{padding-left:10px;border-bottom:1px solid #3fd20c;padding-bottom:10px;font-size:13px;color:red}ul.history_menu a:hover{color:#b7b5b5}ul.history_menu a{color:#fff}.ifield input{max-width:460px;width:100%;padding:5px 10px;border-radius:3px;border:1px solid #2196f3}.ifield_wrapper{padding:10px;color:#a7a7a7}.yoyo.form input[type=number],.yoyo.form input[type=password],.yoyo.form input[type=text],.yoyo.form select{height:36px;padding:5px 10px;font-size:14px}.field.kyc{margin-left:10px;padding:5px;font-size:12px;background:#f5f5f5;border:1px solid #9bffa0;border-radius:3px}
</style>