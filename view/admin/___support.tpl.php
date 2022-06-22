<?php
  /**
   * Support and messaging
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_files')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<div id="fm">

  <div class="row gutters">
    <div class="shrink columns">
      <div class="yoyo segment">
		<div class="okleft">
		<div class="msgboxt">
		<span class="unseenmsg gactive">Inbox (<a id="cunseen">0</a>)</span> <span class="seenmsg">Create New</span> <span class="noticemsg">Notice</span>
		</div>
		
        <div id="list-content"></div>
		
		 </div>
      </div>
    </div>
    <div class="phone-100 columns" style="min-height:500px" id="sicker">
     
      <div id="fileList" class="yoyo small attached relaxed middle aligned celled list"></div>
      <div class="yoyo basic divider"></div>
	  
	  <div class="betweenajax">
      <div id="ajax-content"></div>
	  
	  <div id="msgme">
	  <div class="displayerr"></div>
	  <textarea class="large" id="msginbox" placeholder="Type your message" name="msginbox"></textarea> <span class="subinbox">Send</span>
	  
	 </div>
	 
	 
	 </br></br>
	  
	  </div>
	  
	  
	  
	  
	  
    </div>
    <div class="shrink columns phone-hide mobile-hide">
      <div id="preview" class="padding"><img src="<?php echo ADMINVIEW;?>/images/blank.png" class="yoyo medium image" alt="">
      </div>
    </div>
  </div>

</div>
<?php $usid = App::Auth()->uid;?>
<script src="<?php echo ADMINVIEW;?>/js/manager.js"></script>
<script type="text/javascript"> 
// <![CDATA[	
$(document).ready(function() {
  $("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/inbox",
			data: {
			method:"sswitch"
			},
			
	success: function(html) {
             $("#ajax-content").empty().append(html);
          }
        });
		
		$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			method:"listbox"
			},
		success: function(html) {
             $("#list-content").empty().append(html);
			 var sen = $('.ccseen').attr('id');
			 $('a#cunseen').html(sen);
          }
        });
		
	//load message on click
	$('body').on('click', ' span.unseenmsg', function(){
	  $('#list-content').html("<div class='oftr'>.</div>");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			method:"listbox"
			},
		   success: function(response) {
			$("#list-content").empty().append(response);
			$('span').removeClass('gactive');
			$('span.unseenmsg').addClass('gactive');
			
             }
		   });
	      return false;
		});	
		
	//Crete new message
	$('body').on('click', ' span.seenmsg', function(){
	  $('#list-content').html("<div class='oftr'>.</div>");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			method:"newmsg"
			},
		   success: function(response) {
			$("#list-content").empty().append(response);
			$('span').removeClass('gactive');
			$('span.seenmsg').addClass('gactive');
			
             }
		   });
	      return false;
		});
		
		//Crete new notice
	$('body').on('click', ' span.noticemsg', function(){
	  $('#list-content').html("<div class='oftr'>.</div>");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			method:"notice"
			},
		   success: function(response) {
			$("#list-content").empty().append(response);
			$('span').removeClass('gactive');
			$('span.noticemsg').addClass('gactive');
			
             }
		   });
	      return false;
		});
		
		//submit general notice
$('body').on('click', ' #updatenotice', function(){
	  var msg = $("#unotice"). val();
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			type: "post",
			data: {
				msg:msg,
				method:"subnotice"
			},
			success: function (response) {
				$(".upnotice").html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
	});
		
		//submit agent notice
$('body').on('click', ' #affiliatenotice', function(){
	  var msg = $("#agunotice"). val();
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			type: "post",
			data: {
				msg:msg,
				method:"affnotice"
			},
			success: function (response) {
				$(".affnotice").html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
	});
		
		
		
		//submit super agent notice
$('body').on('click', ' #supernotice', function(){
	  var msg = $("#saunotice"). val();
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			type: "post",
			data: {
				msg:msg,
				method:"supernotice"
			},
			success: function (response) {
				$(".sanotice").html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
	});
		
		
		
		
	//onclick get 
	$('body').on('click', ' .fshow', function(){
	  $('#ajax-content').html("<div class='oftr'>.</div>");
	  $(".betweenajax").css("display", "block");
	     var usid = $(this).attr('id');
		 var savid = $(this);
		 $('.okleft').css("z-index", "0");
		 $('.fshow').css("background", "");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/inbox",
			data: {
			usid:usid,	
			method:"fshow"
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
			$(savid).css("background", "#efefef");
			$(savid).css("color", "#fff");
			$('.clickshows').css("display", "none");
			$(".topinbox").scrollTop($(".topinbox")[0].scrollHeight);
             }
		   });
	      return false;
		});
		
		$('body').on('click', ' .unseen', function(){
			$(this).css("font-weight", "normal");
		});
		
		
		//onclick tirgger box list 
		$('body').on('click', ' .bkkof', function(){
	       $(".okleft").css("display", "block");
			$("span.unseenmsg").trigger("click");
			$(".betweenajax").css("display", "none");
		});
		
	//messaging inbox
	$('body').on('click', ' .subinbox', function(){
		$('.subinbox').html('Sending.....');
		var msg = $('textarea#msginbox').val();
		var sendto = $('.topinbox').attr('id');
		if((msg.length > 1500) || (msg.length < 50)){
			$('.displayerr').html('Minimum 50 characters, Maximum 1500 characters.');
			$('.subinbox').html('Send');
			return;
		}
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/inbox",
			data: {
			usid:<?php echo $usid;?>,
			msg:msg,
			sendto:sendto,
			method:'sendmsg'
			},
		   success: function(response) {
             $("#ajax-content").empty().append(response);
			 $('#msginbox').val('');
			 $('.subinbox').html('Send');
			 $('.displayerr').html('');
			$(".topinbox").scrollTop($(".topinbox")[0].scrollHeight);
			 
			 //document.location.href = '/logout'
               }
		      });
			   return false;
		});	
		
		
		
   //show more messaging
     $('body').on('click', ' .msgload', function(){
	  var rowCount = $('.cfvalue').val();
	  $(this).html('Loading...');
	  var sendto = $('.kgo').attr('id');
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/inbox",
			data: {
			rc:rowCount,
			usid:sendto,		
			method:'moremsg'
			},
		   success: function(response) {
			 $('.msgload').html('');
             $(".topinbox").prepend(response);
			  var fx = 20;
			  var cc =  parseInt(rowCount) +  parseInt(fx);
			 $('.cfvalue').val(cc);
               }
		      });
			   return false;
		});
		
		
		//loadmore list sidebar
     $('body').on('click', ' #moreyes', function(){
	  var rowCount = $('.cfvaluex').val();
	  $(this).html('Loading...');
	  var ss = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			rc:rowCount,
			method:'morelist'
			},
		   success: function(response) {
			 $('#moreyes').html('');
			 $(ss).css("display", "none");
             $("#list-content").append(response);
			  var fx = 20;
			  var cc =  parseInt(rowCount) +  parseInt(fx);
			 $('.cfvaluex').val(cc);
               }
		      });
			   return false;
		});
		
		
		//search events
	 $("body").on('change', ' input#esearch', function() {
      var lid = $("input#esearch").val(); 
	  if(lid.length < 1){
		  return;
	  }
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			lid:lid,		
			method:"listsearch"
			},
	     success: function(html) {
             $(".qresult").empty().append(html);
			}
		});
    return false;
	  
	  });
	  
	  	//search box
	 $("body").on('change', ' input#lsearch', function() {
      var lid = $("input#lsearch").val();
	  if(lid.length < 1){
		  return;
	  }
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			nam:lid,		
			method:"search_box"
			},
	     success: function(html) {
             $("#shso").empty().append(html);
			}
		});
    return false;
	  
	  });
	  
	  
	  
	  //click fetch to content area
	$('body').on('click', ' .csend', function(){
	  $('#ajax-content').html("<div class='oftr'>.</div>");
	  
	  var csid = $(this).attr('id');
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/list_inbox",
			data: {
			csid:csid,
			method:"csend"
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
			$(".topinbox").scrollTop($(".topinbox")[0].scrollHeight);
			$(".okleft").css("display", "none");
			$(".betweenajax").css("display", "block");
			
             }
		   });
	      return false;
		});	

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
});
// ]]>
</script>


<style>



.yoyo.segment {
   padding: 0;
    min-width: 300px;
    margin-top: 0;
    border-radius: 0px;
	
}
#ajax-content {
	background:#fff;
	padding: 10px;
	z-index:9;
}

.okleft {
    position: fixed;
    width: 300px;
    background: #fff;
    bottom: 0;
    height: 550px;
	overflow-y: auto;
	
}

.row.gutters .columns {
    padding: 0px;
    margin: 0px;
}

.betweenajax {
    position: fixed;
    bottom: 0;
	max-height:550px;
	z-index:10;
	max-width:660px;
	height:100%;
	background:#fff;
	margin-right: 20px;
	border-top: 5px solid #a2d202;
	min-width:70px;
}

div#preview {
    display: none;
}

footer{
	display:none!Important;
}
header {
    margin-bottom: 1em!important;
}
span.subinbox {
    float: right;
    background: #eb1515;
    padding: 18px 15px;
    font-weight: bold;
    cursor: pointer;
}
#msgme {
    bottom: 10px;
    display: flex;
    position: absolute;
    width: 100%;
}
#msginbox {
    width: 100%;
    height: 60px;
    background: #ececec;
}
ul.userlisting {
    padding-left: 15px;
}

ul.userlisting li {
    display: inline-block;
}

.usrname {
    font-weight: bold;
}
.unseen {
    color: #000;
    font-weight: bold;
    font-size: 95%;
    line-height: 18px;
}
.useen {
    font-size: 95%;
    line-height: 18px;
    color: #7e7e7e;
}
span.timup {
    float: right;
    margin-right: 10px;
    color: #09766b;
}
.bgavt {
    display: inline-block;
    background: url(<?php echo SITEURL;?>/uploads/avatars/userblanks.png);
    width: 20px;
    height: 20px;
    font-size: 0;
    float: left;
	margin-right: 10px;
}

.msgboxt {display: flex;background: #FFC107;color: #000;border-top: 3px solid #009688;border-bottom: 1px solid #FFC107;}
span.unseenmsg {
    width: 33%;
    text-align: center;
    padding: 5px;
    cursor: pointer;
}
.gactive{
	background:#fff;
}
span.seenmsg {
    text-align: center;
    width: 33%;
    padding: 5px;
    cursor: pointer;
}
span.noticemsg {
    text-align: center;
    width: 33%;
    padding: 5px;
    cursor: pointer;
}


span.unseenmsg:hover {
    background: #fff;
}

span.seenmsg:hover {
    background: #fff;
}
.subbb {
    font-weight: normal;
}

li.fshow:hover {
    background: #efefef;
}
li.fshow {
    cursor: pointer;
}
.oftr{
background:url(<?php echo SITEURL;?>/shell/images/ajax-loader.gif);
width: 32px;
height: 32px;
font-size: 0;
}

.csupport {
    text-align: left;
    max-width: 350px;
    width: 100%;
    clear: both;
    background: #ffffd7;
    margin-bottom: 10px;
    padding: 5px;
    border-radius: 5px;
}
span.stf.usr {
    background: #eb1515;
}
span.stf a {
    color: #fff;
}
span.stf a:hover {
    color: #000;
}
.cusers {
    float: right;
    max-width: 350px;
    width: 100%;
    text-align: left;
    background: #e3e3e3;
    padding: 5px;
    border-radius: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
}
.topinbox {
    max-width: 760px;
    margin: 0 auto;
    padding: 10px;
    position: relative;
    width: 100%;
    font-size: 15px;
    display: inline-block;
    max-height: 450px;
    overflow-y: auto;
    bottom: 0;
    margin-bottom: 10px;
}

span.stf {
    background: #2196F3;
    line-height: 0;
    font-size: 12px;
    padding: 0px 3px;
    color: #fff;
    border-radius: 3px;
}

.clickshows {
    width: 200px;
    height: 200px;
    position: absolute;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}
.displayerr {
    position: absolute;
    margin-top: -20px;
}

div#msgmore {
    cursor: pointer;
    background: #f1f1f1;
    padding: 0px 3px;
    display: inline-block;
}
div#msgmore:hover {
    background: #FFEB3B;
}
div#moreyes {
    margin: 20px;
    background: #f3f3f3;
    padding: 0px 20px;
    border-radius: 3px;
    cursor: pointer;
	max-width: 120px;
}

div#moreyes:hover {
    background: #FFEB3B;
}













ul.rmanage li {
    display: block;
    padding: 5px 0px 5px 0px;
    border-bottom: 1px solid #eb1515;
    color: #eb1515;
    cursor: pointer;
}
ul.rmanage {
    padding: 0;
}
ul.rmanage li:hover {
    color: #f00;
}
.ritem.active{
	color:#f00;
}

span.enablesp,span.enableinp, span.xdisablesp, span.xdisableinp  {
    background: #eb1515;
    cursor: pointer;
    font-size: 13px;
    padding: 1px 5px;
    border-radius: 3px;
    color: #fff;
    float: right;
}

span.enablesp:hover,span.disablesp:hover, span.enableinp:hover, span.disableinp:hover, span.xenableinp:hover, span.xdisableinp:hover, span.xdisablesp:hover,span.xenablesp:hover {
    background: #000;
}
span.disablesp, span.disableinp, span.xenablesp,span.xenableinp {
    background: #f00;
    cursor: pointer;
    font-size: 13px;
    padding: 1px 5px;
    border-radius: 3px;
    color: #fff;
    float: right;
}
.isact {
    margin-bottom: 10px;
	margin-left: 20px;
}
p.swinfo {
    font-size: 14px;
    color: #9c9c9c;
}
#minval,#minvalc, #minvalcd {
    margin-left: 20px;
    padding: 5px;
    max-width: 80px;
    background: #fff;
    border: 1px solid #03A9F4;
    border-radius: 3px;
    font-weight: bold;
    float: right;
    clear: both;
}
input#esearch, input#exsearch, input#spsearch, input#lsearch {
    padding: 2px 7px;
    width: 260px;
    border-radius: 3px;
    border: 1px solid #dedede;
	height:30px;
}
input#lsearch {
	margin-left:10px;
	margin-top:10px;
}
.searchwrap {
    padding: 0px 10px;
}
i#findsr {
    position: absolute;
    margin-top: 5px;
    padding-left: 10px;
}
i#findsrx {
    position: absolute;
    margin-left: -40px;
    margin-top: 17px;
    cursor: pointer;
	padding-left: 10px;
}
i#findsrx:hover {
    color: #f00;
}
span.delfc {
    float: right;
    background: #f00;
    padding: 0px 7px;
    border-radius: 3px;
    color: #fff;
	cursor:pointer;
}

span.delfc:hover {
    background: #000;
}
.rmessage {
    color: #00b952;
    font-weight: bold;
}
input#minvalmax {
    max-width: 74px;
}
aside.transition.visible{
	z-index:9999999;
}

span.seek1:before {
    content: "\e945";
    font-family: 'YoyoIcons';
    color: #E91E63;
    font-size: 20px;
    float: left;
}
.kgo {
    position: absolute;
}


span.seek0:before {
    content: "\e944";
    font-family: 'YoyoIcons';
    color: #000;
    font-size: 18px;
    float: left;
}


@media screen and (max-width: 63em){
.okleft {
    z-index: 11;
 }
 main .mcontent {
    padding: 1em 2em 2em 2em;
}
.okleft {
    width: 340px!Important;
	
 }
}

@media screen and (min-width: 1250px){
.betweenajax {
    width:100%
 }
}
</style>