<?php
 /**
 * Financial Markets
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
</br></br>
<div style="max-width:460px; margin:0 auto">
<script id="__init-script-inline-widget__">(function(d,t,u,s,e){e=d.getElementsByTagName(t)[0];s=d.createElement(t);s.src=u;s.async=1;e.parentNode.insertBefore(s,e);})(document,'script','//cricmarkets.com/livechat/php/app.php?widget-init-inline.js');</script>

</div>
</div>
 


 
</div>
</div>






<script>
//load center by default

 $("#ajax-content").empty().append("<div id='loading'></div>");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/finmarkets/front_default",
data: {
method:'finmarket',
usid:<?php echo $usid;?>,
},
success: function(response) {
 $("#ajax-content").empty().append(response);
 }
})





//fetch sidebar
if($(window).width() >= 1016) {
 $(".fetchsidebar").empty().append("<div id='loading'></div>");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/sidebar/fetch_sidebar",
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
li.nav-item.men88 {
 background: #e5e5e5;
}
.exrow {
 max-width: 2800px;
}
.excol.icenter {
 width: 100%;
 margin-top: -10px;
 margin: -10px 0px 0px 0px;
}
div#ajax-content {
 max-width: 1260px;
 margin: 0 auto;
 border-left: 5px solid #e9e9e9;
}
.image.tk {
 max-width: 100px;
}
.idmarket {
 background: #e1e1e1;
 padding: 10px;
 color: #2196F3;
 font-weight: bold;
}
.cconwrap {
 padding: 10px;
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