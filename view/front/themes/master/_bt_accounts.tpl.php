<?php
 /**
 * History
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */
 
 if (!defined("_YOYO"))
 die('Direct access to this location is not allowed.');
?>
<?php if(empty($_GET["pg102"])):?>
<meta http-equiv="refresh" content="600" >
<?php endif;?>
<?php if(App::Auth()->is_User()){
 $usid = App::Auth()->uid;
 $aid = Auth::$udata->afid;
 }; ?>
 
<?php if(empty($usid)){
Url::redirect(SITEURL . '/login/'); 
			  exit; 
}?> 
<main>
 <?php include_once('inc_account_header.php');?>
 
 <?php $path = $_SERVER['DOCUMENT_ROOT']."/shell/accounts/";
 if($_GET['pg102'] == '$P$Bk4Bdhtqgi%credit%QRofP0KgfLqNSzX.PEiI0'){ //credit
 echo 'Credit';
 } elseif($_GET['pg103'] == 'bnk'){?>
 <ul class="topbank">
 <li class="tbank" id="bbalances"><?= Lang::$word->ACC_P1_BALANCES; ?></li>
 <li id="bdeposit"><?= Lang::$word->ACC_P1_DEPOSIT; ?></li>
 <li id="btransfer"><?= Lang::$word->ACC_P1_TRANSFER; ?></li>
 <li id="bwithdraw"><?= Lang::$word->ACC_P1_WITHDRAW; ?></li>
 </ul>
 <div id="jinx">
 <?php include_once($path."inc_bank.php");?>
 </div> 
 <?php } elseif($_GET['pg103'] == 'acset'){ //accounts settings
 include($path."inc_settings.php"); 
 } elseif($_GET['pg103'] == 'pcredit'){
 //include($path."promo_credit.php"); //promo credit
 } elseif($_GET['pg103'] == 'gmbc'){
 //include($path."gambling_control.php"); //gambling_control
 } elseif($_GET['pg103'] == 'msg'){
 include($path."messaging.php"); //messaging 
 }
 
 //perfect money
 elseif($_GET['pg105'] == 'pf_money'){?> 
 
<div class="cryptoWrapper">
 <div class="topcrmsg">
 <a id="bktopaytb" href="/bt_accounts/?pg103=bnk&bb=bb&dd=1"><?= Lang::$word->ACC_P1_BACK_TO_DEPOSIT_TAB; ?></a></br>
 </div>
 
 
 <?php include("perfect_money.php");?>
 
 <script>
 $("#pmajax").empty().append("<div id='loading'></div>");
  $.ajax({
	  type: "POST",
	  url: "<?php echo SITEURL;?>/shell/accounts/perfect_money",
	  data: {
		  usid:<?php echo $usid;?>,
		  method:'pmmoney'
		  },
		  success: function(response) {
			  $("#pmajax").empty().append(response);
			  }
		});
		
 </script>

 <?php }
//for cryptocurrency deposit 
 elseif($_GET['pg102'] == 'pay_crypto'){?> 
 
 <div class="cryptoWrapper">
 <div class="topcrmsg">
 <a id="bktopaytb" href="/bt_accounts/?pg103=bnk&bb=bb&dd=1"><?= Lang::$word->ACC_P1_BACK_TO_DEPOSIT_TAB; ?></a>
     <?= Lang::$word->ACC_P1_DESCDE_1; ?></br></br>
 </div>
 <h4><?= Lang::$word->ACC_P1_SELECT_DENOMINATION_CRYPTOCURRENCY; ?></h4>
 <?php include_once("crypto_pay.php");?>
 </div>

 <?php } elseif($_GET['pg103'] == 'sl') {?>
 <div id="content-aj">
 <div class="exsportsbook pgactiv">
<ul class="pghistory">
 <a href="/bt_accounts/?pg103=sl">
 <li class="spbook pgdefault"><?= Lang::$word->ACC_P1_SPORTS_EX_SB; ?></li>
 </a>
 <a href="/bt_accounts/?pg104=casgame">
 <li class="csgaming"><?= Lang::$word->ACC_P1_CASINO_SLOT_GAMES; ?></li>
 </a>
    <?php if(false): ?>
 <a href="/bt_accounts/?pg105=mkt">
 <li class="mrmarkets"><?= Lang::$word->ACC_P1_MARKETS; ?></li>
 </a>
     <?php endif; ?>
</ul>
 </div>

 <ul class="topsubnav">
 <li class="tsub" id="subunsettled"><?= Lang::$word->ACC_P1_UNSETTLED; ?></li>
 <li id="subsettled"><?= Lang::$word->ACC_P1_SETTLED; ?></li>
 </ul>
 <div id="jinx">
 <ul class="jx">
 <li class="jxsub" id="inplay"><?= Lang::$word->ACC_P1_ACTIVE_SLIPS; ?></li>
 <li id="games"><?= Lang::$word->ACC_P1_OTHER_MARKETS; ?></li>
 </ul>
 <ul class="jxsettled" style="display:none">
 <li class="jxsub" id="inplay"><?= Lang::$word->ACC_P1_SETTLED_HISTORY; ?></li>
 <li id="games"><?= Lang::$word->ACC_P1_OTHER_MARKETS; ?></li>
 </ul>
 <div class="filterdate">
     <?= Lang::$word->ACC_P1_FROM; ?> <i id="frdate" class="icon calendar alt"></i><input type="text" id="my_date_picker1" value="select">
     <?= Lang::$word->ACC_P1_TO; ?> <i id="todate" class="icon calendar remove"></i><input type="text" id="my_date_picker2" value="select">
 <div class="addfilter unset inplay"><?= Lang::$word->ACC_P1_ADD_FILTER; ?></div>
</div>
 
 <div id="fetchslips"></div>
 </div>
 </div>
 
 <?php } elseif($_GET['pg104'] == 'casgame') {?>
 <div id="content-aj">

 <div class="exsportsbook pgactiv">
<ul class="pghistory">
 <a href="/bt_accounts/?pg103=sl">
 <li class="spbook"><?= Lang::$word->ACC_P1_SPORTS_EX_SB; ?></li>
 </a>
 <a href="/bt_accounts/?pg104=casgame">
 <li class="csgaming pgdefault"><?= Lang::$word->ACC_P1_CASINO_SLOT_GAMES; ?></li>
 </a>
     <?php if(false): ?>
 <a href="/bt_accounts/?pg105=mkt">
 <li class="mrmarkets"><?= Lang::$word->ACC_P1_MARKETS; ?></li>
 </a>
     <?php endif; ?>
</ul>
 </div>
 
 <ul class="topsubcash">
 <li class="tcash" id="topcash"><?= Lang::$word->ACC_P1_CASINO_AND_SLOT; ?></li>
 <li id="topvirtual"><?= Lang::$word->ACC_P1_VIRTUAL; ?> </li>
 <li id="topgames"><?= Lang::$word->ACC_P1_GAMES; ?></li>
 </ul>
 
 
<div id="jinx">
 <div id="fetchslips"></div>
</div>
 
 </div>
 
 <?php } elseif($_GET['pg105'] == 'mkt') {?>
 <div id="content-aj">

 <div class="exsportsbook pgactiv">
<ul class="pghistory">
 <a href="/bt_accounts/?pg103=sl">
 <li class="spbook"><?= Lang::$word->ACC_P1_SPORTS_EX_SB; ?></li>
 </a>
 <a href="/bt_accounts/?pg104=casgame">
 <li class="csgaming"><?= Lang::$word->ACC_P1_CASINO_SLOT_GAMES; ?></li>
 </a>
 <!--<a href="/bt_accounts/?pg105=mkt">
 <li class="mrmarkets pgdefault">Markets</li>
 </a>-->
</ul>
 </div>
 
 <ul class="topsubmkt">
 <li class="mcash" id="topfx">Forex</li>
 <li id="topstock">Stocks</li>
 <li id="topcrypto">Crypto Currency</li>
 <li id="topcommodity">Commodities</li>
 <li id="topmktothers">Others</li>
 </ul>
 
 
<div id="jinx">
 <div id="fetchslips"></div>
</div>
 </div>
 
 <?php } ?>
</main> 



<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 


<script>

//for casino
function xdisplayRecords(numRecords, pageNum) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo SITEURL;?>/shell/accounts/slot_casino_games",
                                    cache: false,
									data: {
										show:numRecords,
										pagenum:pageNum,
										usid:"<?php echo $usid;?>",
										method:'topcash'
										},
                                    success: function(response) {
                                         $("#fetchslips").empty().append(response);
                                       
                                    }
                                });
                            }
							
function xchangeDisplayRowCount(numRecords) {
	xdisplayRecords(numRecords, 1);
}


$('body').on('click', ' #my_date_picker1', function(){
var strt = $(this).val();
});
 //active menu coloring
 $('body').on('click', ' ul.history_menu li', function(){
$('ul.history_menu li').removeClass('hactive');
$(this).addClass('hactive');
});

 //active sub menu
 $('body').on('click', ' ul.topsubnav li', function(){
$('ul.topsubnav li').removeClass('tsub');
$(this).addClass('tsub');
});

//active jx sub
 $('body').on('click', ' ul.jx li', function(){
$('ul.jx li').removeClass('jxsub');
$(this).addClass('jxsub');
});
 
 
 
 
 
 
 //auto show inplay bet
 let searchParams = new URLSearchParams(window.location.search);
var autos = searchParams.get('pg103');
var balance = searchParams.get('bb');
var deposit = searchParams.get('dd');
var transfer = searchParams.get('tt');
var withdraw = searchParams.get('ww');
var rmclass = searchParams.get('pg103');
var settledslips = searchParams.get('esettled');
var casgame = searchParams.get('pg104');
var mkt = searchParams.get('pg105');

if(autos != null) {
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/unsettled_slip_history",
data: {
method:'inplay',
usid:<?php echo $usid;?>
},
 success: function(response) {
 $("#fetchslips").empty().append(response);
 }
 })
} else if(rmclass != null){
 $('ul.history_menu').removeClass('hactive');
}


//balances
 if(balance !=null){
 $('#aj-fetch').html("<div id='loading'></div>");
 $('ul.topbank li').removeClass('tbank');
 $('#bbalances').addClass('tbank');
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank.php",
data: {
usid:<?php echo $usid;?>,
method:'bbalances'
},
 success: function(response) {
 $("#aj-fetch").html('');
 $("#aj-fetch").append(response);
 }
 });
};

 //deposit
 if(deposit !=null){
 $('#aj-fetch').html("<div id='loading'></div>");
 $('ul.topbank li').removeClass('tbank');
 $('#bdeposit').addClass('tbank');
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank.php",
data: {
usid:<?php echo $usid;?>,
method:'bdeposit'
},
 success: function(response) {
 $("#aj-fetch").html('');
 $("#aj-fetch").append(response);
 }
 });
};


//transfer
 if(transfer !=null){
 $('#aj-fetch').html("<div id='loading'></div>");
 $('ul.topbank li').removeClass('tbank');
 $('#btramsfer').addClass('tbank');
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank.php",
data: {
usid:<?php echo $usid;?>,
method:'btransfer'
},
 success: function(response) {
 $("#aj-fetch").html('');
 $("#aj-fetch").append(response);
 }
 });
};

//withdraw
 if(withdraw !=null){
 $('#aj-fetch').html("<div id='loading'></div>");
 $('ul.topbank li').removeClass('tbank');
 $('#bwithdraw').addClass('tbank');
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank.php",
data: {
usid:<?php echo $usid;?>,
method:'bwithdraw'
},
 success: function(response) {
 $("#aj-fetch").html('');
 $("#aj-fetch").append(response);
 }
 });
};

 if(settledslips !=null){
 setTimeout(function(){
$("li#subsettled").trigger("click");
}, 2000);
}

//for casino games slot etc
if(casgame !=null){
 $('#fetchslips').html("<div id='loading'></div>");
 xdisplayRecords(20, 1);
}

//for markets
if(mkt !=null){
 $('#fetchslips').html("<div id='loading'></div>");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/market_tickets",
data: {
usid:<?php echo $usid;?>,
method:'topfx'
},
 success: function(response) {
 $("#fetchslips").empty().append(response);
 }
 });

}







 
//SHOW each tab on list click
 $('body').on('click', ' ul.jx li', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var meth = $(this).attr('id');
 $('.addfilter').removeClass('inplay');
 $('.addfilter').removeClass('pre'); 
 $('.addfilter').removeClass('games'); 
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/unsettled_slip_history.php",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#fetchslips").html('');
 $("#fetchslips").append(response);
 $('#lrem').removeClass('inplay');
 
 $('.loadmo').addClass(meth);
 $('.addfilter').addClass(meth); 
 

 /*
 if (history && history.pushState){
 history.pushState(null, null, 'bet-history');
} */
 }
 });
 return false;
});

 
 
//china
 //SHOW ON LOAD MORE inplay unsettled
$('body').on('click', ' .loadmo.inplay', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/unsettled_slip_history",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'lomore'
},
 success: function(response) {
 $('.loadmo').html('');
 $('.addload').removeClass('.addload');
 if(response.trim().length == 0){
$(".addload").html('');
return;
}
 $("#fetchslips").append(response);
 var fx = 30;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});

//SHOW ON LOAD MORE prematch unsettled
$('body').on('click', ' .loadmo.pre', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/unsettled_slip_history",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'prelomore'
},
 success: function(response) {
 $('.loadmo.pre').html('');
 $('.addload').removeClass('.addload');
 if(response.trim().length == 0){
$(".addload").html('');
return;
}
 $("#fetchslips").append(response);
 var fx = 30;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 $('.loadmo').removeClass("inplay");
 $('.loadmo').addClass("pre");
 }
 });
 return false;
});








 //show settled on click
 $('body').on('click', ' #subsettled', function(){
 $('ul.jx').css("display", "none");
$('.jxsettled').css("display", "block");
$('li#pre').removeClass('jxsub');
$('li#games').removeClass('jxsub');
$('.addfilter').removeClass('unset');
 $('#fetchslips').html("<div id='loading'></div>");
 var meth = $(this).attr('id');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/settled_slip_history",
data: {
usid:<?php echo $usid;?>,
method:'inplay'
},
 success: function(response) {
 $("#fetchslips").html('');
 $("#fetchslips").append(response);
 $('.addfilter').addClass('set');
 $('li#inplay').addClass('jxsub');
 $('.addfilter').addClass('set inplay');
 }
 });
 return false;
});


 //Show more inplay settled
 $('body').on('click', ' .loadmox.inplay', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/settled_slip_history",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'lomore'
},
 success: function(response) {
 $('.loadmox.inplay').html('');
 $('.addload').removeClass('.addload');
 if(response.trim().length == 0){
$(".addload").html('');
return;
}
 $("#fetchslips").append(response);
 var fx = 30;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});

//Settled show more prematch
 $('body').on('click', ' .loadmox.pre', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/settled_slip_history",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'prelomore'
},
 success: function(response) {
 $('.loadmox.pre').html('');
 $('.addload').removeClass('.addload');
 if(response.trim().length == 0){
$(".addload").html('');
return;
}
 $("#fetchslips").append(response);
 var fx = 30;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 $('.loadmox').removeClass("inplay");
 $('.loadmox').addClass("pre");
 }
 });
 return false;
});


//SHOW each tab on list click settled
 $('body').on('click', ' ul.jxsettled li', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 $('.addfilter').removeClass('set inplay');
 $('.addfilter').removeClass('set pre');
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/settled_slip_history",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#fetchslips").html('');
 $("#fetchslips").append(response);
 $('#lrem').removeClass('inplay');
 
 $('#lrem').addClass(meth);
 $('.addfilter').addClass('set ' + meth);

 }
 });
 return false;
});

//active jx sub
 $('body').on('click', ' ul.jxsettled li', function(){
 $('ul.jxsettled li').removeClass('jxsub');
 $(this).addClass('jxsub');
 });
 
 
 


//show back unsettled
 $('body').on('click', ' #subunsettled', function(){
 $('.jxsettled').css("display", "none");
 $('ul.jx').css("display", "block");
 $("ul.jx li#inplay").trigger("click");
});


//casino slot games late addition
 $('body').on('click', ' ul.topsubcash li', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var meth = $(this).attr("id");
 var savthis = $(this);
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/slot_casino_games",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#fetchslips").empty().append(response);
 $("ul.topsubcash li").removeClass("tcash");
 $(savthis).addClass("tcash");
 }
 });
return false;
});

//virtual loadmore
 $('body').on('click', ' #virtualmore', function(){
 var rowCount = $('.cfvalue').val();
 var sthis = $(this);
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/slot_casino_games",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'virtualmore'
},
 success: function(response) {
 $(sthis).html('');
 
 $("#fetchslips").append(response);
 var fx = 50;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});










 //market
 $('body').on('click', ' ul.topsubmkt li', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var meth = $(this).attr("id");
 var savthis = $(this);
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/market_tickets",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#fetchslips").empty().append(response);
 $("ul.topsubmkt li").removeClass("mcash");
 $(savthis).addClass("mcash");
 }
 });
return false;
});










//date picker
 $(function() { 
 $("#my_date_picker1").datepicker({}); 
 }); 
 
 $(function() { 
 $("#my_date_picker2").datepicker({}); 
 }); 
 
 $('#my_date_picker1').change(function() { 
 startDate = $(this).datepicker('getDate'); 
 $("#my_date_picker2").datepicker("option", "minDate", startDate); 
 }) 
 
 $('#my_date_picker2').change(function() { 
 endDate = $(this).datepicker('getDate'); 
 $("#my_date_picker1").datepicker("option", "maxDate", endDate); 
 }) 


 //get date filter results inplay unsettled
 $('body').on('click', ' .addfilter.unset.inplay', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var dt1 = $('#my_date_picker1').val();
 var dt2 = $('#my_date_picker2').val();
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/unsettled_slip_history",
data: {
usid:<?php echo $usid;?>,
dt1:dt1,
dt2:dt2,
method:'inplaydatefilter'
},
 success: function(response) {
$("#fetchslips").empty().append(response);
 }
 });
 return false;
});

//get date filter results prematch unsettled
 $('body').on('click', ' .addfilter.unset.pre', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var dt1 = $('#my_date_picker1').val();
 var dt2 = $('#my_date_picker2').val();
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/unsettled_slip_history",
data: {
usid:<?php echo $usid;?>,
dt1:dt1,
dt2:dt2,
method:'predatefilter'
},
 success: function(response) {
$("#fetchslips").empty().append(response);
 }
 });
 return false;
});



//get inplay date filter results settled
 $('body').on('click', ' .addfilter.set.inplay', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var dt1 = $('#my_date_picker1').val();
 var dt2 = $('#my_date_picker2').val();
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/settled_slip_history",
data: {
usid:<?php echo $usid;?>,
dt1:dt1,
dt2:dt2,
method:'datefilter'
},
 success: function(response) {
$("#fetchslips").empty().append(response);
 }
 });
 return false;
});

//get date prematch filter results settled
 $('body').on('click', ' .addfilter.set.pre', function(){
 $('#fetchslips').html("<div id='loading'></div>");
 var dt1 = $('#my_date_picker1').val();
 var dt2 = $('#my_date_picker2').val();
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/settled_slip_history",
data: {
usid:<?php echo $usid;?>,
dt1:dt1,
dt2:dt2,
method:'prefilter'
},
 success: function(response) {
$("#fetchslips").empty().append(response);
 }
 });
 return false;
});




//show multibet details on click
 $('body').on('click', ' .showmulti', function(){
 var gtid = $(this).attr('id');
 $('#showm-' + gtid).show('slow');
 $(this).text('Hide Bet Details');
 $(this).addClass('hid');
 $(this).css("color", "#bf0050");
 });

//hide multibet details on click
 $('body').on('click', ' .showmulti.hid', function(){
 var gtid = $(this).attr('id');
 $('#showm-' + gtid).hide('slow');
 $(this).text('Show Bet Details');
 $(this).removeClass('hid');
 $(this).css("color", "");
 });


/////////////////////////////////////////////
 //////////////BANK///////////////////////////

 //different tabs on click
 $('body').on('click', ' ul.topbank li', function(){
 $('#aj-fetch').html("<div id='loading'></div>");
 $('ul.topbank li').removeClass('tbank');
 $(this).addClass('tbank');
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#aj-fetch").html('');
 $("#aj-fetch").append(response);
 }
 });
 return false;
});




 //different tabs on deposit click
 $('body').on('click', ' ul.depositbar li', function(){
 $('#depositbox').html("<div id='loading'></div>");
 $('ul.depositbar li').removeClass('dactive');
 $(this).addClass('dactive');
 var meth = $(this).attr('id');
 if(meth == 'localPayments'){
 $('li#bdeposit').trigger('click');
 return false;
 }
 
if(meth == 'cryptoCurrency'){
	var origin   = window.location.origin;
	var url = origin + "/bt_accounts/?pg102=pay_crypto";
	window.open(url, "_self");
	$('#depositbox').html("");
	return false;
}	
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/deposit_tab",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#depositbox").html('');
 $("#depositbox").append(response);
 }
 });
 return false;
});



//for select country


$('body').on('change', ' select[name="country"]', function(){
var ccode = $(this).val();
$("._mandeposit").css("display", "none");
$('#semidepostbox').html("<div id='loading'></div>");
if(ccode == 'BD'){
 $('li#localPayments').trigger('click');
 return false;
 }

 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/deposit_tab",
data: {
usid:<?php echo $usid;?>,
code:ccode,
method:"localpay"
},
 success: function(response) {
 $("#semidepostbox").empty().append(response);
 }
 });
 return false;
});



//Indian Payment tab
 $('body').on('click', ' ul.indpayment li', function(){
 $('#payrespond').html("<div id='loading'></div>");
 $('ul.indpayment li').removeClass('indactive');
 $(this).addClass('indactive');
 var indpayer = $(this).attr('id');
 if(meth == 'localPayments'){
 $('li#bdeposit').trigger('click');
 return false;
 
 }
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/deposit_tab",
data: {
usid:<?php echo $usid;?>,
method:indpayer
},
 success: function(response) {
 $("#payrespond").empty().append(response);
 }
 });
 return false;
});














//delete pending request
$('body').on('click', ' span.cpending', function(){
 var idto = $(this).attr('id');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank",
data: {
usid:<?php echo $usid;?>,
idto:idto,
method:'cpending'
},
 success: function(response) {
 $("li.idto-" + idto).html("Successfully Deleted");
 }
 });
 return false;
});




//show more deposit history
 $('body').on('click', ' .dload', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'ldeposit'
},
 success: function(response) {
 $('.dload').html('');
 $("#aj-fetch").append(response);
 var fx = 20;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});


//Manual Payment
 $('body').on('click', ' #mandeposit', function(){
 $('#mancashback').html('Loading...');
 $('#redcashback').css("background", "");
 $('#mandeposit').css("background", "#000");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/manual-deposit.php",
data: {
usid:<?php echo $usid;?>,
method:'mandeposit'
},
 success: function(response) {
 $('#mancashback').html('');
 $("#mancashback").empty().append(response);
 }
 });
 return false;
});

//cashback Payment
 $('body').on('click', ' #redcashback', function(){
 $('#mancashback').html('Loading...');
 $('#mandeposit').css("background", "");
 $('#redcashback').css("background", "#000");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/redeem-cashback",
data: {
usid:<?php echo $usid;?>,
method:'cashback'
},
 success: function(response) {
 $('#mancashback').html('');
 $("#mancashback").empty().append(response);
 }
 });
 return false;
});

//submit cashback
$('body').on('click', ' .redeemit', function(){
 $('.sherr').html('Loading...');
 var cashid = $('#cashid').val();
 if(cashid.length < 5){
$('.sherr').html('Invalid Voucher Code');
return;
}
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/redeem-cashback",
data: {
usid:<?php echo $usid;?>,
cashid:cashid,
method:'subcash'
},
 success: function(response) {
 $("._mandeposit").html(response);
 }
 });
 return false;
});




//close paytab
$('body').on('click', ' .payclose', function(){
$('#redcashback' ).css("background", "");
$('#mandeposit').css("background", "");
$('#manwithdraw').css("background", "");
$("#mancashback").empty();
});



//pay submit
$('body').on('click', ' .paysubmit', function(){
 $('.sherr').html('Loading...');
 var paytype = $('#paymenttype').val();
 var amount = parseInt($('#mamount').val());
 var gpnumber = $("input#gpnumber").val();
 var gpname = $("input#gpname").val();
 var ref = $('#tref').val();
 var gpremarks = $("input#gpremarks").val();
 
 var pmin = parseInt($("input#mindpd").val());
 var pmax = parseInt($("input#maxdpd").val());
 
 if (amount < pmin){
 $('.sherr').text('Amount cannot be empty or less than '+ pmin);
 return false;
 }
 if (amount > pmax){
 $('.sherr').text('Amount cannot be more than '+ pmax);
 return false;
 }
 if( gpnumber.length === 0 ) {
 $('.sherr').text('Mandatory fields cannot be empty..');
 return false;
 
 }
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/manual-deposit",
data: {
usid:<?php echo $usid;?>,
paytype:paytype,
amount:amount,
gpnumber:gpnumber,
gpname:gpname,
ref:ref,
gpremarks:gpremarks,
method:'sherr',

},
 success: function(response) {
 $("._mandeposit").html(response);
 }
 });
 return false;
});




//cancel deposit

$('body').on('click', ' span.dltdpt', function(){
	if (!confirm("Are you sure you want to Cancel this deposit request?")){
            return false;
       }
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/deposit_tab",
data: {
usid:<?php echo $usid;?>,
method:'canceldeposit'
},
 success: function(response) {
 $(".depositinfo").html(response);
 }
 });
 return false;
});


//update bank account
$('body').on('change', 'input.bins', function(){    
	     var types = $(this).attr("id");
		 var kvalue = $(this).val();
         $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/accounts/users_bank",
			data: {
			types:types,
			usid:'<?php echo $usid;?>',
			kvalue:kvalue,			
			method:"usersbank"
			},
		success: function(response) {
             $('#shubank').html(response);
			 
            }
    });
    return false;
    });







 //show more transfer history
 $('body').on('click', ' .tload', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'ltransfer'
},
 success: function(response) {
 $('.tload').html('');
 $("#aj-fetch").append(response);
 var fx = 20;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});

//affiliate activation
 $('body').on('click', ' #joinaff', function(){
 var mysite = $("input#onelink").val();
 if(mysite < 6){
$(".oneerror").html("<?= Lang::$word->ACC_INCORRECT_WEBSITE_URL; ?>");
return false;
 }
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav",
data: {
 mysite:mysite,
usid:'<?php echo $usid;?>',
method:'joinaffiliates'
},
 success: function(response) {
 $(".modalinner").html(response);
 }
 });
 return false;
});


//Transfer amount
$('body').on('click', ' .sendamount', function(){
 $('.sherr').html('Loading...');
 var amount = $('#mamount').val();
 var ref = $('#tref').val();
 if (amount < 50){
 $('.sherr').text('Amount cannot be empty or less than 50');
 return;
 }
 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/transfer-amount",
data: {
usid:<?php echo $usid;?>,
amount:amount,
ref:ref,
method:'sherr',

},
 success: function(response) {
 $("._mandeposit").html(response);
 }
 });
 return false;
});


//show more withdrawal history
 $('body').on('click', ' .wload', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/inc_bank",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'lwithdraw'
},
 success: function(response) {
 $('.wload').html('');
 $("#aj-fetch").append(response);
 var fx = 20;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});


//show withdrawal form
 $('body').on('click', ' #manwithdraw', function(){
 $('#mancashback').html('Loading...');
 $('#manwithdraw').css("background", "");
 $('#manwithdraw').css("background", "#eb1515");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/withdraws",
data: {
usid:<?php echo $usid;?>,
method:'manwithdraw'
},
 success: function(response) {
 $('#mancashback').html('');
 $("#mancashback").empty().append(response);
 }
 });
 return false;
});


//withdrawal request
$('body').on('click', ' .wrequest', function(){
 $('.sherr').html('Loading...');
 var paytype = $('#paymenttype').val();
 var amount = $('#mamount').val();
 var ref = $('#tref').val();
 var minwi = parseInt($("#minwit").val());
 if (amount < minwi){
 $('.sherr').text('Amount cannot be empty or less than ' + minwi);
 return;
 }
 if (ref.length > 100){
 $('.sherr').text('Maximum 100 characters allowed');
 return;
 }
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/withdraws",
data: {
usid:<?php echo $usid;?>,
amount:amount,
ref:ref,
paytype:paytype,
method:'werr',

},
 success: function(response) {
 $("._mandeposit").html(response);
 setTimeout(function(){
$("ul.topbank li#bwithdraw").trigger("click");
}, 4000);
 }
 });
 return false;
});

 //delete withdrawal pending
$('body').on('click', ' span.wpending', function(){
 var idto = $(this).attr('id');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/withdraws",
data: {
usid:<?php echo $usid;?>,
idto:idto,
method:'wpending'
},
 success: function(response) {
 $("li.idto-" + idto).html("Successfully Deleted");
 }
 });
 return false;
});

//exhaust promo balance
$('body').on('click', ' span.clearpromo', function(){
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/promo_credit",
data: {
usid:<?php echo $usid;?>,
method:'clearpromo'
},
 success: function(response) {
 $("span.ccbalance").html("0.00");
 }
 });
 return false;
});

 //deactivate
$('body').on('click', ' .deactme', function(){
if (!confirm("Are you sure you want to do this? Once deactivated, you cannot activate yourself unless you contact our support and explain your situation. Click Ok to deactivate or Cancel to go back")){
 return false;
 }
$('.deactme').html('Processing.....');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/gambling_control",
data: {
usid:<?php echo $usid;?>,
method:'gcontrol'
},
 success: function(response) {
 $(".deactme").html("Successfully Deactivated");
 //document.location.href = '/logout'
 }
 });
 return false;
});

//messaging inbox
$('body').on('click', ' .subinbox', function(){
$('.subinbox').html('Sending.....');
var msg = $('textarea#msginbox').val();
if((msg.length > 500) || (msg.length < 50)){
$('.displayerr').html('Minimum 50 characters, Maximum 500 characters.');
$('.subinbox').html('Send');
return;
}
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/messaging",
data: {
usid:<?php echo $usid;?>,
msg:msg,
method:'msg'
},
 success: function(response) {
 $(".topinbox").empty().append(response);
 $('#msginbox').val('');
 $('.subinbox').html('Send');
 $('.displayerr').html('');
$(".topinbox").scrollTop($(".topinbox")[0].scrollHeight);
 
 //document.location.href = '/logout'
 }
 });
 return false;
});

//show message details on click
$('body').on('click', ' .minbox', function(){
$('.inboxwrapper').css("display", "block");
$('.minbox').text('Inbox (0)');
$(".topinbox").scrollTop($(".topinbox")[0].scrollHeight);
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/messaging",
data: {
usid:<?php echo $usid;?>,
method:'seen'
},
 success: function(response) {
 }
 });
 return false;
});


//show more messaging
 $('body').on('click', ' .msgload', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/messaging",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'moremsg'
},
 success: function(response) {
 $('.msgload').html('');
 $(".topinbox").prepend(response);
 var fx = 20;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});



//crypto deposit
$('.cdeposit').on('click', function() {
        var getvar = $(this).attr("id");
		function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

setCookie('crr',getvar,1);
    });

BuyWithCrypto.registerCallback('onSuccess', function(e){
$.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/deposit_tab",
  data: {
  usid:<?php echo $usid;?>,
  method:'cryptoCurrency'
},
 success: function(response) {
  alert(response);
 }
 });
 
});

 
 //var nt = new Date(allt).getTime();
 //var tstart = (nt/1000); 
</script>









<style>

.hactive{
border-bottom:2px solid #eb1515;
}
li.<?php echo $_GET['pg103'];?>{
border-bottom:2px solid #eb1515;
 }
 li.<?php echo $_GET['pg104'];?>{
border-bottom:2px solid #eb1515;
 }
 li.<?php echo $_GET['pg105'];?>{
border-bottom:2px solid #eb1515;
 }
 .padding-left:hover {
 color: #000!Important;
}

</style>