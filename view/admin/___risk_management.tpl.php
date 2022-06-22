<?php
  /**
   * File Manager
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
  <div class="yoyo segment">
    <div class="row horizontal-gutters align-middle">
      <div class="shrink columns phone-100">
        <div class="columns shrink mobile-100"> <a href="/admin/users/new/" class="yoyo secondary button"><i class="icon plus alt"></i>Add New User</a> </div>
      </div>
      <div class="columns phone-100 content-center">
        <div class="yoyo right transparent labeled input">
          <input placeholder="Create new risk" name="foldername" type="text">
          <a id="addFolder" class="yoyo basic label">
            <?php echo Lang::$word->ADD;?>
          </a>
        </div>
        <div class="yoyo negative button disabled is_delete"><?php echo Lang::$word->DELETE;?></div>
        <div id="displyType" class="yoyo buttons">
          <a data-type="table" class="yoyo icon button active"><i class="icon reorder"></i></a>
          <a data-type="list" class="yoyo icon button"><i class="icon unordered list"></i></a>
          <a data-type="grid" class="yoyo icon button"><i class="icon apps"></i></a>
        </div>
      </div>
      <div class="columns shrink phone-hide mobile-hide">
        <a id="togglePreview" class="yoyo simple icon button"><i class="icon compress"></i></a>
      </div>
    </div>
  </div>
  <div class="row gutters">
    <div class="shrink columns phone-hide mobile-hide">
      <div class="yoyo segment">
        <h4><?php echo Lang::$word->FM_DISPLAY;?></h4>
        <ul class="rmanage">
		 <li class="ritem active" id="sswitch">Events Switch</li>
		 <li class="ritem" id="cstake">Stake Control</li>
		 <li class="ritem" id="commi">Commission</li>
		 <li class="ritem" id="deadline">Deadline</li>
		 <li class="ritem" id="umaxbet">User Max. Bet</li>
		 <li class="ritem" id="slipsmanager">Slips Manager</li>
		 <li class="ritem" id="credits">Balance & Credits</li>
		 <li class="ritem" id="astatus">Account Status</li>
		 </ul>
        <h4><?php echo Lang::$word->FM_SORT;?></h4>
        <div class="yoyo divider"></div>
        <select class="yoyo small fluid dropdown fileSort">
          <option value="name"><?php echo Lang::$word->TITLE;?></option>
          <option value="size"><?php echo Lang::$word->FM_FSIZE;?></option>
          <option value="type"><?php echo Lang::$word->TYPE;?></option>
          <option value="date"><?php echo Lang::$word->FM_LASTM;?></option>
        </select>
        <input type="hidden" name="dir" value="">
      </div>
    </div>
    <div class="phone-100 columns" style="min-height:500px">
      <div class="row">
        <div class="column align-middle">
          <div id="fcrumbs"><span class="yoyo small bold text">RISK MANAGEMENT</span></div>
        </div>
        <div class="column align-middle shrink">
          <div id="done"></div>
        </div>
      </div>
      <div id="fileList" class="yoyo small attached relaxed middle aligned celled list"></div>
      <div class="yoyo basic divider"></div>
      <div id="ajax-content"></div>
    </div>
    <div class="shrink columns phone-hide mobile-hide">
      <div id="preview" class="padding"><img src="<?php echo ADMINVIEW;?>/images/blank.png" class="yoyo medium image" alt="">
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="yoyo small horizontal relaxed divided list">
      <div class="item"><?php echo Lang::$word->FM_SPACE;?>: <span class="yoyo bold text"><?php echo File::directorySize(UPLOADS, true);?></span></div>
      <div id="tsizeDir" class="item"><?php echo Lang::$word->FM_DIRS;?>: <span class="yoyo bold text">0</span></div>
      <div id="tsizeFile" class="item"><?php echo Lang::$word->FM_FILES;?>: <span class="yoyo bold text">0</span></div>
    </div>
  </div>
</div>
<script src="<?php echo ADMINVIEW;?>/js/manager.js"></script>
<script type="text/javascript"> 
// <![CDATA[	
$(document).ready(function() {
  $("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-switch",
			data: {
			method:"sswitch"
			},
			
	success: function(html) {
             $("#ajax-content").empty().append(html);
          }
        });
		
		//onclick get menutab
		
	$('body').on('click', ' .ritem', function(){
	  $('#ajax-content').html("<div class='oftr'>Loading.......</div>");
	     var gtid = $(this).attr('id');
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-switch",
			data: {
			method:gtid
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
			$('.ritem').removeClass('active');
			$('#' + gtid).addClass('active');
               }
		      });
			   return false;
		});
		
		
		
	   //FOR SPORTS SWITCH
		$('body').on('click', ' #pdisable', function(){
		 var pred = $(this).html();	
	      $(this).html("wait..");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,	
			method:"predisable"
			},
		   success: function(response) {
			$("#pdisable").empty().append(response);
               }
		      });
			   return false;
		});
		
		$('body').on('click', ' #idisable', function(){
		 var pred = $(this).html();	
	      $(this).html("wait..");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,	
			method:"idisable"
			},
		   success: function(response) {
			$("#idisable").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		//individual sports switch
		//users single limit
	   $('body').on('change', ' #spsearch', function(){
		 var amt = $(this).val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,	
			method:"spsearch"
			},
		   success: function(response) {
			$(".spresult").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		$('body').on('click', ' a.exsp', function(){
		 var pred = $(this).html();
		 var amt = $(this).attr('id');
		 var tt = $(this);
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			amt:amt,	
			method:"indsports"
			},
		   success: function(response) {
			$(tt).html(response);
               }
		      });
			   return false;
		});
		
		
		//prematch
		$('body').on('click', ' #ienable', function(){
		 var pred = $(this).html();	
	      $(this).html("wait..");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,	
			method:"ienable"
			},
		   success: function(response) {
			$("#ienable").empty().append(response);
               }
		      });
			   return false;
		});
		
		$('body').on('click', ' #iactive', function(){
		 var pred = $(this).html();	
	      $(this).html("wait..");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,	
			method:"iactive"
			},
		   success: function(response) {
			$("#iactive").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		//clean up prematch
		$('body').on('click', ' a.exsp', function(){
		 var pred = $(this).html();
		 var amt = $(this).attr('id');
		 var tt = $(this);
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			amt:amt,	
			method:"indsports"
			},
		   success: function(response) {
			$(tt).html(response);
               }
		      });
			   return false;
		});
		
		//cstake
		
		$('body').on('change', ' #minval', function(){
		 var pred = $(this).attr('class');
		 var amt = $(this).val();
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			amt:amt,	
			method:"cstake"
			},
		   success: function(response) {
			$(".rmessage").empty().append(response);
			$(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
	
		
		
		//commission
		
		$('body').on('change', ' #minvalc', function(){
		 var pred = $(this).attr('class');
		 var amt = $(this).val();
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			amt:amt,	
			method:"commission"
			},
		   success: function(response) {
			$(".rmessage").empty().append(response);
			$(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		//deadline
		$('body').on('change', ' .cdeadline', function(){
		 var amt = $(this).val();
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,	
			method:"deadline"
			},
		   success: function(response) {
			$(".rmessage").empty().append(response);
			$(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		//users single limit
	   $('body').on('change', ' #esearch', function(){
		 var amt = $(this).val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,	
			method:"esearch"
			},
		   success: function(response) {
			$(".eresult").empty().append(response);
               }
		      });
			   return false;
		});
		
		//update single limit
	   $('body').on('change', ' .maxbet', function(){
		 var amt = $(this).val();
		 var wid = $(this).attr("class").replace("maxbet pr-", "");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,
			wid:wid,
			method:"maxbet"
			},
		   success: function(response) {
			   $(".rmessage").empty().append(response);
			   $(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		//tickets actions prematch
		
		$('body').on('click', ' span#setall', function(){
		 var amt = $(this).html();
		 var ts = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,
			method:"pre_tickets"
			},
		   success: function(response) {
			$(ts).empty().append(response);
               }
		      });
			   return false;
		});
		
		//tickets actions inplay
		
		$('body').on('click', ' span#inpall', function(){
		 var amt = $(this).html();
		 var ts = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,
			method:"inp_tickets"
			},
		   success: function(response) {
			$(ts).empty().append(response);
               }
		      });
			   return false;
		});
		
		
		//delete older tickets
	   $('body').on('click', ' .delfc', function(){
		 var amt = $('.delta').val();
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,
			method:"deldays"
			},
		   success: function(response) {
			   $(".rmessage").empty().append(response);
			   $(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		//all other history delete
	   $('body').on('click', ' .delfcx', function(){
		   var whatis = $(this).attr("id");
		   if(whatis == 'ucredit'){
			   var amt = $('.adelta').val();
		   }else if(whatis == 'acredit'){
			   var amt = $('.bdelta').val();
		   }else if(whatis == 'plogs'){
			   var amt = $('.cdelta').val();
		   }else if(whatis == 'dephs'){
			   var amt = $('.ddelta').val();
		   }else if(whatis == 'withs'){
			   var amt = $('.edelta').val();
		   }else if(whatis == 'trhs'){
			   var amt = $('.fdelta').val();
		   }else if(whatis == 'inboxhs'){
			   var amt = $('.gdelta').val();
		   }
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			whatis:whatis,	
			amt:amt,
			method:"del_rest"
			},
		   success: function(response) {
			   $(".rmessage").empty().append(response);
			   $(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		
		//sign up credit
		
		$('body').on('change', ' #minvalcd', function(){
		 var pred = $(this).attr('class');
		 var amt = $(this).val();
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			amt:amt,	
			method:"ccredit"
			},
		   success: function(response) {
			$(".rmessage").empty().append(response);
			$(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		//first deposit bonus
		$('body').on('change', ' #xfdb', function(){
		 var amt = $(this).val();
		 $('.fdbmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			amt:amt,	
			method:"xfdb"
			},
		   success: function(response) {
			$(".fdbmessage").empty().append(response);
			$(".fdbmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		
		//deposit/withdrawals limits
		$('body').on('change', ' .depwith', function(){
		 var pred = $(this).attr('id');
		 var amt = $(this).val();
		 $('.rmessage').css("display", "block");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			amt:amt,	
			method:"depwith"
			},
		   success: function(response) {
			$(".rmessage").empty().append(response);
			$(".rmessage").delay(3000).fadeOut('slow');
               }
		      });
			   return false;
		});
		
		
		//reset balance to 0
		$('body').on('click', ' #resetme', function(){
		 var pred = $(this).html();
		 var tt = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			method:"resetme"
			},
		   success: function(response) {
			$(tt).html(response);
               }
		      });
			   return false;
		});
		
		
		//reset balance to 0
		$('body').on('click', ' #restric', function(){
		 var pred = $(this).html();
		 var tt = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			pred:pred,
			method:"restric"
			},
		   success: function(response) {
			$(tt).html(response);
               }
		      });
			   return false;
		});
		
		
		//clean i
		$('body').on('click', ' #cleani', function(){
		 var tt = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			method:"cleani"
			},
		   success: function(response) {
			$(tt).html(response);
               }
		      });
			   return false;
		});
		
		
		
		
		//clean up prematch events
		$('body').on('click', ' #cleanprematch', function(){
		 var tt = $(this);
		 $(this).html('Cleaning up...');
		 var siteuri = "<?php echo SITEURL;?>";
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			siteuri:siteuri,	
			method:"clean_prematch"
			},
		   success: function(response) {
			$(tt).html('Success');
               }
		      });
			   return false;
		});
		
		
		//clean up prematch events
		$('body').on('click', ' #cleaninplay', function(){
		 var tt = $(this);
		 $(this).html('Cleaning up...');
		 var siteuri = "<?php echo SITEURL;?>";
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/rsk-manage/rsk-data",
			data: {
			siteuri:siteuri,	
			method:"clean_inplay"
			},
		   success: function(response) {
			$(tt).html('Success');
               }
		      });
			   return false;
		});
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
});
// ]]>
</script>


<style>
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
#ajax-content {
    min-height: 390px;
	background:#fff;
	padding: 10px;
    border: 1px solid #009688;
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
#minval,#minvalc, #minvalcd,.depwith, .xfdb {
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
input#esearch, input#exsearch, input#spsearch {
    padding: 2px 7px;
    width: 300px;
    border-radius: 3px;
    border: 1px solid #dedede;
	height:30px;
}
i#findsr {
    position: absolute;
    margin-top: 5px;
    padding-left: 10px;
}
span.delfc, span.delfcx {
    float: right;
    background: #f00;
    padding: 0px 7px;
    border-radius: 3px;
    color: #fff;
	cursor:pointer;
}

span.delfc:hover,span.delfcx:hover {
    background: #000;
}
.rmessage, .fdbmessage {
    color: #00b952;
    font-weight: bold;
}
input#minvalmax {
    max-width: 74px;
}
</style>