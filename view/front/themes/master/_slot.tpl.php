<?php
 /**
 * Slot
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */
 
 if (!defined("_YOYO"))
 die('Direct access to this location is not allowed.');
?>
<?php if($this->data->show_header):?>
<!-- Page Caption & breadcrumbs-->
<div id="pageCaption"<?php echo Content::pageHeading();?>>
 <div class="yoyo-grid">
 <div class="row gutters">
 <div class="columns screen-100 tablet-100 mobile-100 phone-100 phone-content-center">
 <?php if($this->data->{'caption' . Lang::$lang}):?>
 <h1><?php echo $this->data->{'caption' . Lang::$lang};?></h1>
 <?php endif;?>
 </div>
 <?php if($this->core->showcrumbs):?>
 <div class="columns screen-100 tablet-100 mobile-100 phone-100 content-right phone-content-left align-self-bottom">
 <div class="yoyo small white breadcrumb">
 <?php echo Url::crumbs($this->crumbs ? $this->crumbs : $this->segments, "/", Lang::$word->HOME);?>
 </div>
 </div>
 <?php endif;?>
 </div>
 </div>
</div>
<?php endif;?>

<?php if(App::Auth()->is_User()){
 $usid = App::Auth()->uid;
 $afid = Auth::$udata->afid;
 } else {
 $usid = 999999999; 
 } ?>


<div class="containt-wrapper">
<div class="exrow">
 
 
 
<div class="excol icenter">






<div class="topspotxp"><span class="vsleft"><?= Lang::$word->SLOT_AND_GAMES; ?></span> <span class="vsright"><?= Lang::$word->SLOT_MORE_GAMES; ?></span></div>


<div class="ifdiffc">
<span id="nosta"><i class="icon bell"></i></span><?= Lang::$word->SLOT_AND_GAMES_DESCRIPTION; ?>
</div>

 <div class="vrbody">

 <!--- SLOT GAMES -->
 </br>
 
 
<ul class="slotlister">
	<li id="igrosoft">IGroSoft</li>
	<li id="novomatic">Novomatic</li>
	<li id="habanero">Habanero</li>
	<li id="isoftbet">ISoftBet</li>
	<li id="playngo">Play'N Go</li>
	<li id="amatic">Amatic</li>
	<li id="betsoft">Betsoft</li>
	<li id="nemesis">Nemesis</li>
	<li id="zeusplay">Zeus Play</li>	
	<li id="dbg">DBG Poker</li>
	<li id="egt">EGT</li>
	<li id="noble">Noble</li>
	<li id="atronic">Atronic</li>
	<li id="bomba">Bomba Games</li>
	<li id="evoplay">Evo Play</li>
	<li id="gameart">Game Art</li>
	<li id="igt">IGT</li>
	<li id="xplosive">Xplosive Slots</li>
	<li id="gaminator">Gaminator</li>
	<li id="yggdrasil">YGG Drasil</li>
	<li id="quickspin">Quick Spin</li>
	<li id="1x2">1X2</li>
	<li id="netent">Netent</li>
	<li id="playson">Playson</li>
	<li id="wazdan">Wazdan</li>
	<li id="tablegames">Table Games</li>
	<li id="irondog">Iron Dog</li>
	<li id="pragmatic">Pragmatic Play</li>
	<li id="ottoelotto">8 & Lotto</li>
	<li id="goldenrace">Golden Race</li>
	

</ul>





 
<div id="racewrapper">
 <div id="ajax-content">
 </div>
 
</div>

 <!--- FOR OTHERS IF ANY -->
 
 
 
 
 
 
 </div>
</div>
 

 

 
</div>
</div>


<?php $getlogo = SITEURL . '/uploads/' . $this->core->logo;?>
<script>
$( '#button' ).click( function() {
    $( '#some_id' ).width( $( window ).width() );
    $( '#some_id' ).height( $( window ).height() );
});

//fetch sidebar
if($(window).width() >= 1016) {
 $(".fetchsidebar").empty().append("<div id='loading'></div>");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/sidebar/games_sidebar",
data: {
site:'<?php echo SITEURL;?>',
method:'fsidebar'
},
 success: function(response) {
 $(".fetchsidebar").empty().append(response);
 }
 });
};

//get link for play
 $('body').on('click', 'a.generateslot.cls', function(){
	 if($(window).width() <= 680) {
		 var devi = 'mobile';
	 }else{
		 var devi = 'desktop';
	 }
	  $(this).html("Wait a moment...");
	   var gameId = $(this).attr("id");
	   var providerID = $("li.sactive").attr("id");
	   var savthis = $(this);
	   //$('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/games/session_link.php",
			data: {
			method:'session_link',
			gameId:gameId,
			providerID:providerID,
			device:devi,
			usid:"<?php echo $usid;?>"
			},
		success: function(response) {
            $(savthis).empty().append(response);
			$(savthis).attr('id', response);
			$(savthis).html("Launching...");
			$(savthis).css("background","#ffee5c");
			$(savthis).removeClass("cls");
			$(savthis).addClass("ifra");
			setTimeout(function(){
			$("a.generateslot.ifra").trigger("click");
			if (history && history.pushState){
				 history.pushState(null, null, '#' + gameId);
				}
		}, 500);
            }
    });
    return false;
    });



//generate link and show in iframe
 $('body').on('click', 'a.generateslot.ifra', function(){
	 var ifrc = $(this).attr("id");
	 $('#iframe').attr('src', ifrc);
	 var svv = $(this);
	 setTimeout(function(){
		$("iframe#iframe").css("display", "block");
		$(".framewrapper").css("display", "block");
		$("div#dctn").css("display", "none");
		$('.framewrapper').addClass("adks");
		$(svv).html("Launch Game");
	}, 1000);
    });

//close iframe wrapper
$('body').on('click', '.framebacker, div#framecloser', function(){
	if (!confirm("Are you sure you want to exit this game?")){
            return false;
       }
		$("iframe#iframe").css("display", "none");
		$(".framewrapper").css("display", "none");
		$("div#dctn").css("display", "block");
		$('.framewrapper').removeClass("adks");

    });

/*	
$('body').on('click', 'div#thebutton', function(){
    $('div#thediv').addClass("adks");
});
*/
//Load casino
$("#ajax-content").empty().append("<div id='loading'></div>");
var ggid = 'igrosoft';
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/games/slot_view",
data: {
	method:'slot_view',
	gameId:ggid,
	getlogo:"<?php echo $getlogo;?>",
	usid:'<?php echo $usid;?>'
},
success: function(response) {
	$("#ajax-content").empty().append(response);
	$("ul.slotlister li").css("background", "");
	$("ul.slotlister li").removeClass("sactive");
	$("li#igrosoft").addClass("sactive");
	$("li#igrosoft").css("background","#f9ff94");
 
 }
 });

//get different provider 
$('body').on('click', 'ul.slotlister li', function(){
	   var gameId = $(this).attr("id");
	   var savthis = $(this);
	   $("#ajax-content").empty().append("<div id='loading'></div>");
	   $.ajax({
		   type: "POST",
		   url: "<?php echo SITEURL;?>/shell/games/slot_view",
		   data: {
			   method:'slot_view',
			   gameId:gameId,
			   getlogo:"<?php echo $getlogo;?>",
			   usid:'<?php echo $usid;?>'
			   },
		success: function(response) {
			$("#ajax-content").empty().append(response);
			$("ul.slotlister li").css("background", "");
			$("ul.slotlister li").removeClass("sactive");
			$(savthis).addClass("sactive");
			$(savthis).css("background","#f9ff94");
			}
		});
	});


</script>







<style type="text/css">
.framewrapper{
	display:none;
	width:100vh;
	height:100vh;
}
iframe#iframe {
    display: none;
}
.framebacker {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    color: #f00;
}
.framebacker:hover {
    cursor: pointer;
    color: #f00;
}
iframe{
  width : 100%;
  height : 100vh;
}


.adks{
	position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 99999999;
    height: 100%;
    background: #000;
    width: 100%;
}


   .fullScreen {
      width: 100vw;
      height: 100vh;
   }


#ajax-content{
	display:flex;
}
li.nav-item.men89 {
 background: #e5e5e5;
}
div#dctn {
 text-align: center;
 padding: 20px;
 font-weight: bold;
 color: #f00;
 width: 100%;
}
.exrow {
 max-width: 980px;
}

.excol.icenter {
 width: 100%;
 margin-left: 0;
}
.excol.icenter {
 background: transparent;
}
.topspotxp {
 padding: 10px;
 background: #d8d8d8;
 border-radius: 2px;
 margin-top: 20px;
}
span.vsright {
 float: right;
 color: #a9a9a9;
 cursor: pointer;
}
span.vsleft {
 font-weight: bold;
}
.vrbody {
 background: #f4f4f4;
 min-height: 500px;
}
p.plogs {
    padding: 30px 10px;
    color: #f00;
    font-size: 20px;
	text-algin:center;
}
ul.ttbrace li {
    color: #fff;
    display: inline-block;
}
ul.ttbrace {
    padding: 5px 20px;
    margin: 0;
    background: linear-gradient(to right, #0e0e0e,#f3e5f5,#f9f9f9)!important;
	position: absolute;
    width: 100%;
}
img#logower {
    max-height: 24px;
}
li.rtlog {
    float: right;
}
ul.slotlister li {
    display: inline-block;
    text-align: center;
    background: #ffffff;
    padding: 3px 10px;
    border: 1px solid #9f9f9f;
    border-radius: 2px;
    cursor: pointer;
    box-shadow: 0 1px 3px 0 rgb(58 58 58), 0 4px 6px 0 rgb(0 0 0 / 5%);
    margin: 5px 10px 5px 0px;
    min-width: 60px;
    border-left: 3px solid #eb1515;
   }

ul.slotlister {
    width: 100%;
    padding: 10px 5px 1px 5px;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    margin: 0;
    background: #e0e0e0;
}

div#framecloser {
    background: #fff;
    position: absolute;
    padding: 3px 12px;
    border-radius: 50%;
    font-size: 20px;
    font-weight: bold;
    color: #f00;
    right: 2px;
    bottom: 3px;
    cursor: pointer;
}

div#framecloser:hover {
    background: #dadada;
}
</style>

<!-- Page Content-->
<main<?php echo Content::pageBg();?>>

 <!-- Validate page access-->
 <?php if(Content::validatePage()):?>
 <!-- Run page-->
 <?php echo Content::parseContentData($this->data->{'body' . Lang::$lang});?>
 
 <!-- Parse javascript -->
 <?php if ($this->data->jscode):?>
 <?php echo Validator::cleanOut(json_decode($this->data->jscode));?>
 <?php endif;?>
 <?php endif;?>
 <?php if($this->data->is_comments):?>
 <?php include_once(FMODPATH . 'comments/index.tpl.php');?>
 <?php endif;?>
</main>