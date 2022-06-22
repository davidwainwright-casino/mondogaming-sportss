<?php
 /**
 * Casino
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






<div class="topspotxp"><span class="vsleft">Live Casino Dealers</span> <span class="vsright">More..</span></div>

<div class="ifdiffc">
<span id="nosta"><i class="icon bell"></i></span> Casino & Slot uses USD as default currency. If your account currency is not USD, then it will be converted in to USD value as per latest exchange rates. When you play the games, your winning and stake is converted back to your default local currency.
</div>
 <div class="vrbody">
 
  <div class="framewrapper">
  <div id="framecloser">X</div>
  <ul class="ttbrace" style="display:none">
  <li class="cloleft"><div class="framebacker">Close X</div></li>
  <li class="rtlog"><img id="logower" src="<?php echo SITEURL . '/uploads/' . $this->core->logo;?>"></li>
  </ul>
  
  <iframe class="frame" id="iframe" name="myIframe" frameborder="0" width="100%" height="100%"></iframe>
  </div>
 
 <!--- FOR CASINO DEALER -->
 </br>
<div id="racewrapper">
 <div id="ajax-content">
 <?php if(App::Auth()->is_User()):?>
 <ul class="shcasino">
	<li id="evolution"><img src="<?php echo THEMEURL;?>/images/games/evolution.jpg"><div class="llobby" id="evolution">Evolution Lobby</div></li>
	<li id="vivolive"><img src="<?php echo THEMEURL;?>/images/games/vivogaming.jpg"><div class="llobby" id="vivolive">Vivo Lobby</div></li>
	<li id="n2live"><img src="<?php echo THEMEURL;?>/images/games/n2live.jpg"><div class="llobby" id="n2live">N2Live Lobby</div></li>
	<li id="portomaso"><img src="<?php echo THEMEURL;?>/images/games/portomaso.jpg"><div class="llobby" id="portomaso">Portomaso Lobby</div></li>
 
 </ul>
<?php else:?> 
 
 <p class="plogs"><?= Lang::$word->PLEASE_LOGIN_TO_ACCESS_THIS_PAGE; ?></p>
 
<?php endif;?> 
 </div>
 
</div>

 <!--- FOR OTHERS IF ANY -->
 
 
 
 
 
 
 </div>
</div>
 

 

 
</div>
</div>



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
 $('body').on('click', '.llobby, ul.shcasino li', function(){
	 if($(window).width() <= 680) {
		 var devi = 'mobile';
	 }else{
		 var devi = 'desktop';
	 }
	  var gameid = $(this).attr("id");
	  var savtext = $(this).html();
	  $("div#"+ gameid).html("Launcing...");
	   var savthis = $(this);
	   //$('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
			url: "<?php echo SITEURL;?>/shell/games/casino_view",
			data: {
				method:'casino_view',
				devi:devi,
				gameid:gameid,
				usid:'<?php echo $usid;?>'
				},
			success: function(response) {
				$('#iframe').attr('src', response);
				$(savthis).html(savtext);
				setTimeout(function(){
					$("iframe#iframe").css("display", "block");
					$(".framewrapper").css("display", "block");
					$("div#dctn").css("display", "none");
					$('.framewrapper').addClass("adks");
					//$(svv).html("Launch Game");
					if (history && history.pushState){
				 history.pushState(null, null, '#' + gameid);
				}
				}, 1000);
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
	if (!confirm("Are you sure you want to exit the entire lobby?")){
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
 //$("#ajax-content").empty().append("<div id='loading'></div>");




//iframe expand



</script>







<style type="text/css">
.framewrapper{
	display:none;
	width:100vh;
	height:100vh;
	max-width:100%;
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
	/*display:flex;*/
}
li.nav-item.men90 {
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
 max-width: 720px;
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
ul.shcasino li {
    display: block;
    border-bottom: 10px solid #f5f5f5;
    padding-left: 10px;
}

ul.shcasino {
    margin: 0;
    padding: 0;
}
.llobby {
    background: #009688;
    display: table;
    max-width: 200px;
    width: 100%;
    text-align: center;
    margin: 0 auto;
    margin-top: 3px;
    margin-bottom: 3px;
    padding: 7px;
    border-radius: 3px;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
}

.llobby:hover {
    background: #135a54;
}
ul.shcasino img:hover {
    cursor: pointer;
    transition-duration: .3s;
    transition-property: transform,box-shadow;
    transition-timing-function: ease-out;
    transform: scale(1) translateZ(0) translateZ(0);
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
}
img#logower {
    max-height: 24px;
}
li.rtlog {
    float: right;
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