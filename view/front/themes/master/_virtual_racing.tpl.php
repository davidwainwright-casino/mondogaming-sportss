<?php
 /**
 * Virtual Racing
 *
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
<div class="topspotxp"><span class="vsleft">Virtual Racing</span> <span class="vsright">More games..</span></div>
 <div class="vrbody">
 
 <!--- FOR HORSE RACING -->
 </br>
<div id="racewrapper">
<h3 style="text-align:center">Virtual Horse Racing</h3> 
 <div class="horseracer">
 <div class="HOsw">
 <?php if(App::Auth()->is_User()):?>
 <a target="_blank" href="/virtual/horse-racing/">Play Now</a>
 <?php else:?>
 <a href="/login">Login To Play</a>
 <?php endif;?>
 </div>
 </div>
 <div class="shwhrname">Horse Racing</div>
</div>
<!--- FOR GREYHOUND RACING -->
 </br>
<div id="racewrapper">
<h3 style="text-align:center">Virtual Greyhound Racing</h3> 
 <div class="horseracer grey">
 <div class="HOsw">
 <?php if(App::Auth()->is_User()):?>
 <a target="_blank" href="/virtual/greyhound/">Play Now</a>
 <?php else:?>
 <a href="/login">Login To Play</a>
 <?php endif;?>
 </div>
 </div>
 <div class="shwhrname">Greyhound Racing</div>
</div>

 <!--- FOR OTHERS IF ANY -->
 
 
 
 
 
 
 </div>
</div>
 

 <div class="excol iright" id="rightbar">
 <div class="fetchsidebar"></div>

 </div>

 
</div>
</div>






<script>

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


//For cricket category laod
 $('body').on('click', ' ul.crimarkers li', function(){
 var catid = $(this).attr('id');
 $('ul.crimarkers li').removeClass("pxxactive");
 var evname = $('.evidf').text();
 var evidf = $(".evidf").attr("id");
 var savthis = $(this);
 $("#fetchcat").empty().append("<div id='loading'></div>");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/exchange/cricket_categories",
data: {
method:'cricket_cat',
catid:catid,
usid:<?php echo $usid;?>,
evid:evidf,
evname:evname
},
success: function(response) {
 $("div#fetchcat").empty().append(response);
 $(savthis).addClass('pxxactive');
 }
 });
 return false;
 });




</script>







<style type="text/css">
li.nav-item.men91 {
 background: #e5e5e5;
}
.exrow {
 max-width: 980px;
}
.excol.iright {
 width: 29%;
}
.excol.icenter {
 width: 70%;
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
 margin-top: 30px;
 min-height: 500px;
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