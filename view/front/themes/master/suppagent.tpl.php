<?php
  /**
   * super agent
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php $usid = App::Auth()->uid;$ob = App::Auth()->type; if($ob !='Sagent'){
	echo '<div class="noexist">The page you are looking for does not exist!</div>';
	die();
}?>

<?php $curry = '৳';?>
<div class="supp-header">
<ul class="suppmenu">
<li class="mennav"> <div class="menuv">MENU <i class="icon reorder"></i></div></li>
<li class="supplogo"> 
  <div class="columns shrink mobile-80 phone-80">
          <a href="<?php echo SITEURL;?>/" class="logo">
		  <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?>
		  </a>
  </div>
</li>


 <li class="suppsubmenu"> 
  <div id="superuser">
  <span class="supbal">
  <?php echo $curry;?> <?php echo number_format((float)App::Auth()->sabal, 2, '.', '');?>
  </span>
  <span class="activesuper">u</span>
   
  </div>
 </li>
</ul>
</div>

<div class="supprow">
  <div class="suppcol sleft" style="width:25%">
   <div class="sagentd">
   <div class="supadm"><i class="icon users"></i> Super Agent</div>
   <span class="homeoff"><i class="icon home"></i> Home</span>
   </div>
     
   <ul class="sidenavs">
   <li id="dashboardsa" style="background:#e7e7e7"><i class="icon dashboard"></i>S. Agent Dashboard <i class="icon chevron right"></i></li>
   <li id="exbrokersa"><i class="icon dice"></i> E. Broker Dashboard <i class="icon chevron right"></i></li>
   <li id="agentlistsa"><i class="icon user profile"></i> Your Agents List <i class="icon chevron right"></i></li>
   <li id="noo"><a target="_blank" href="/agent-registration/?asaid=<?php echo $usid;?>"><i class="icon unlock"></i> Create an Agent <i class="icon chevron right"></i></a></li> 
   <li id="ticketshistorysa"><i class="icon copy"></i> Tickets History <i class="icon chevron right"></i></li>
   <li id="blockagentssa"><i class="icon ban"></i> Inactive Agents/Downline <i class="icon chevron right"></i></li>
   <li id="toolsmarketingsa"><i class="icon unfold out"></i> Tools & Marketing <i class="icon chevron right"></i></li>
   <li id="withdrawalssa"><i class="icon arrow backward"></i> Withdrawals <i class="icon chevron right"></i></li>
   <li id="transferhistorysa"><i class="icon history"></i> Transactions Logs <i class="icon chevron right"></i></li>
   <li id="printreportssa"><i class="icon printer"></i> Print Reports <i class="icon chevron right"></i></li>
   <li id="supportsa"><i class="icon question sign"></i> Support <i class="icon chevron right"></i></li>
   
   
   
   

   </ul>   
	 
	 
	 
  </div>
  
  <div class="suppcol sright" style="padding:0px 5px; width:70%">
  <div class="wrapper-sa">
    <div id="ajax-content"></div>
	</div>
</br></br></br></br></br></br>
  </div> 
</div>





<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
//on default show agent dashboard
$('#ajax-content').html("<div id='loading'></div>");
	  var savethis = $(this);
	  var tabid = $(this).attr("id");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/navigation",
			data: {
			usid:<?php echo $usid;?>,
			method:'dashboardsa'
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		 });
		 
if($(window).width() < 800) {
       $('body').on('click', ' .menuv', function(){
		   $(window).scrollTop(0);
		   $(".suppcol.sleft.shmob").css("display", "");
		   $('.suppcol.sleft').toggleClass("shmob");
		   $('.menuv').toggleClass("adk");
	   });
	   
} else {
	$('body').on('click', ' .menuv', function(){
		   $('.suppcol.sleft').toggleClass("nobig");
		   $('.menuv').toggleClass("adk");
	   });
}
			   

  //different tabs on click
    $('body').on('click', ' li#dashboardsa,li#agentlistsa,li#toolsmarketingsa,li#supportsa', function(){
		var tabid = $(this).attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  $('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
	  //$(".suppcol.sleft.shmob").css("display", "none");
	  $(".menuv").trigger("click");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/navigation",
			data: {
			usid:<?php echo $usid;?>,
			method:tabid
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
	
	//brokers dashboard
    $('body').on('click', ' li#exbrokersa', function(){
		var tabid = $(this).attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  $('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
	 $(".menuv").trigger("click"); 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			usid:<?php echo $usid;?>,
			method:"mydash"
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		
		//show amount to send
		$('body').on('keyup', ' input#putcash', function(){
			var tremail = $("input#sendfunds").val();
			var trmoney = $(this).val();
			if(tremail == ''){
		   return false;
		   }
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			usid:<?php echo $usid;?>,
			tremail:tremail,
			trmoney:trmoney,
			method:"selcurrency"
			},
		   success: function(response) {
             $("#showavl").empty().append(response);
               }
		    });
			   return false;
		});
		
		//submit transfer
		$('body').on('click', ' .subtransfer', function(){
			$(this).text("Sending cash...");
			var tremail = $("input#sendfunds").val();
			var trmoney = $("input#putcash").val();
			
			if(tremail == ''){
				$("#showavl").html("invalid email");
				$(this).text("Send Money");
		   return false;
		   }
		   if(trmoney == ''){
				$("#showavl").html("invalid amount");
				$(this).text("Send Money");
		   return false;
		   }
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			usid:<?php echo $usid;?>,
			tremail:tremail,
			trmoney:trmoney,
			method:"submittransfer"
			},
		   success: function(response) {
             $("#showavl").empty().append(response);
			 $("input#putcash").val("");
			 $("input#sendfunds").val("");
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
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			rc:rowCount,
			usid:<?php echo $usid;?>,		
			method:'ltransferb'
			},
		   success: function(response) {
			 $('.tload').html('');
             $("div#quathistory").append(response);
			  var fx = 50;
			  var cc =  parseInt(rowCount) +  parseInt(fx);
			 $('.cfvalue').val(cc);
               }
		      });
			   return false;
		});
		
		
		
		//brokers dashboard each tab
    $('body').on('click', ' a#mytransfer,a#myhistory', function(){
		var tabid = $(this).attr("id");
	  $('div#ccajax').html("<div id='loading'></div>");
	  //$('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			usid:<?php echo $usid;?>,
			method:tabid
			},
		   success: function(response) {
			 $("div#topbtnwrapper a").removeClass("active");  
			 $(savethis).addClass("active"); 
             $("div#ccajax").empty().append(response);
               }
		      });
			   return false;
		});
		
	//agent tickets load more
    $('body').on('click', ' .loadwhistory', function(){
		   var rowCount = $('.cfvalue').val();
		   $('.loadwhistory').css({'padding' : '0px','border' : 'none'});
		   $('.loadwhistory').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			type: "post",
			data: {
				rc:rowCount,
				usid:<?php echo $usid;?>,
				method:"withmore"
			},
			success: function (response) {
				$('.loadwhistory').html('');
				 $("#ajax-content").append(response);
				 var fx = 100;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
});
		
		
		//mark as processing
    $('body').on('click', ' a.mrkpr', function(){
		if (!confirm("Are you sure about this action? You cannot revert once marked as Processing")){
            return false;
       }
		var rowid = $(this).attr("id");
		var uid = $(this).attr("class").replace("mrkpr pr-", "");
	    var savethis = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			usid:<?php echo $usid;?>,
			rowid:rowid,
			uid:uid,
			method:"markprocess"
			},
		   success: function(response) {
			 $(savethis).text(response); 
               }
		      });
			   return false;
		});
		
			//mark as completed/processed
    $('body').on('click', ' a.crkpr', function(){
		if (!confirm("You are authorized to mark 'processed' Only if you have already transferred the fund and confirmed with the receiver. Any misuse of platfrom will lead to suspension of your account. Click 'Ok' to go ahead; Click 'Cancel' to stop marking.")){
            return false;
       }
		var rowid = $(this).attr("id");
	    var savethis = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
			data: {
			usid:<?php echo $usid;?>,
			rowid:rowid,
			method:"markcompleted"
			},
		   success: function(response) {
			 $(savethis).text(response); 
               }
		      });
			   return false;
		});
		
		//show more info request tab
		
	$('body').on('click', ' a#clikshowtrr', function(){
		$("span#showtrr").toggle();
	});
		
		
	//main dashboard brokers
    $('body').on('click', ' a#mydash', function(){
		$("li#exbrokersa").trigger("click");
	});
	
	//read more 
	$('body').on('click', ' a#shmoo', function(){
		$("#ooshow").hide();
		$("#oohide").show();
	});
	
	
	//acollapse
	$('body').on('click', ' a.ohcollapse', function(){
		$("#oohide").hide();
		$("#ooshow").show();
	});
			
		
  //tickets history
    $('body').on('click', ' li#ticketshistorysa', function(){
		var tabid = $(this).attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  $('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
	  $(".menuv").trigger("click");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/tickets-history",
			data: {
			usid:<?php echo $usid;?>,
			method:tabid
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});

		
  //SHOW ON LOAD MORE AGENTS LIST
    $('body').on('click', ' .loadmo', function(){
		   var rowCount = $('.cfvalue').val();
		   $('.loadmo').css({'padding' : '0px','border' : 'none'});
		   $('.loadmo').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/navigation",
			type: "post",
			data: {
				rc:rowCount,
				usid:<?php echo $usid;?>,
				method:"aglistmore"
			},
			success: function (response) {
				$('.loadmo').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
});


//view individual agent
    $('body').on('click', ' a.glist', function(){
		var usid = $(this).attr("id");
		var uname = $('td.unames').attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  var savethis = $(this);
	  
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/individual-agent",
			data: {
			usid:usid,
			uname:uname,
			method:"salist"
			},
		   success: function(response) {
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});


//view individual agent more
    $('body').on('click', ' .loadmoul', function(){
		   var rowCount = $('.cfvalue').val();
		   var usid = $('.suser').val();
		   $('.loadmoul').css({'padding' : '0px','border' : 'none'});
		   $('.loadmoul').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/individual-agent",
			type: "post",
			data: {
				rc:rowCount,
				usid:usid,
				method:"salistmore"
			},
			success: function (response) {
				$('.loadmoul').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
});

//back to all agent list
    $('body').on('click', ' .backmeif', function(){
		$(".menuv").trigger("click");
		$("li#agentlistsa").trigger("click");
	});




//view individual agent downline ticket list
    $('body').on('click', ' a.tkaglist', function(){
		var usid = $(this).attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  var savethis = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/tickets-history-user",
			data: {
			usid:usid,
			method:"downlinetickets"
			},
		   success: function(response) {
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
		
	//load more agent downline ticket listv
     $('body').on('click', ' .loadmothx', function(){
		   var rowCount = $('.cfvalue').val();
		   var usid = $('.suser').val();
		   $('.loadmothx').css({'padding' : '0px','border' : 'none'});
		   $('.loadmothx').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/tickets-history-user",
			type: "post",
			data: {
				rc:rowCount,
				usid:usid,
				method:"aidmore"
			},
			success: function (response) {
				$('.loadmothx').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
});	
	

//back to all ticket list
    $('body').on('click', ' .backmeifx', function(){
		$(".menuv").trigger("click");
		$("li#ticketshistorysa").trigger("click");
	});
	
	
	
	
	
	
	//transaction logs
    $('body').on('click', ' li#transferhistorysa', function(){
	  $('#ajax-content').html("<div id='loading'></div>");
	  var savethis = $(this);
	  $('ul.sidenavs li').css("background", "");
	  $(".menuv").trigger("click");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/txn_logs",
			data: {
			usid:<?php echo $usid;?>,
			method:"txnlogs"
			},
		   success: function(response) {
             $("#ajax-content").empty().append(response);
			 $(savethis).css("background", "#e7e7e7");
               }
		      });
			   return false;
		});
		
		
		//load more txn logs
     $('body').on('click', ' .loadmothtr', function(){
		   var rowCount = $('.cfvalue').val();
		   //var usid = $('.suser').val();
		   $('.loadmothtr').css({'padding' : '0px','border' : 'none'});
		   $('.loadmothtr').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/txn_logs",
			type: "post",
			data: {
				rc:rowCount,
				usid:<?php echo $usid;?>,
				method:"logsmore"
			},
			success: function (response) {
				$('.loadmothtr').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});	
	
	//search txn logs
	 $("body").on('change', 'input#searchmetr', function() {
      var es = $(this).val(); 
	  if(es.length < 1){
		  return;
	  }
	  
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/txn_logs",
			data: {
			es:es,	
			method:"searchlogs"
			},
			
	     success: function(html) {
             $("#seetick").empty().append(html);
			 $('.loadmothtr').css('display', 'none');
			 $('span.searcktr').html("search X");
			}
		});
    return false;
	  
	  });
	  
	 //back to txn logs
    $('body').on('click', ' span.searcktr', function(){
		$(".menuv").trigger("click");
		$("li#transferhistorysa").trigger("click");
	});



//view individual logs record
    $('body').on('click', ' a.tkaglistlog', function(){
		var usid = $(this).attr("id");
		$(".loadmothtr").addClass("ind");
	  $('#ajax-content').html("<div id='loading'></div>");
	  var savethis = $(this);
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/individual_logs",
			data: {
			usid:usid,
			method:"indlogs"
			},
		   success: function(response) {
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		//load more individual txn logs
     $('body').on('click', ' .loadmothtrind', function(){
		   var rowCount = $('.cfvalue').val();
		   var usidm = $(".cckmorelog").attr("id");
		   $('.loadmothtrind').css({'padding' : '0px','border' : 'none'});
		   $('.loadmothtrind').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/individual_logs",
			type: "post",
			data: {
				rc:rowCount,
				usidm:usidm,
				method:"logsindmore"
			},
			success: function (response) {
				$('.loadmothtrind').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});	
		
		
		
		
		
   //back to transaction logs
    $('body').on('click', ' .backmeiftr', function(){
		$(".menuv").trigger("click");
		$("li#transferhistorysa").trigger("click");
	});




  //agent tickets load more
    $('body').on('click', ' .loadmoth', function(){
		   var rowCount = $('.cfvalue').val();
		   $('.loadmoth').css({'padding' : '0px','border' : 'none'});
		   $('.loadmoth').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/super-agents/tickets-history",
			type: "post",
			data: {
				rc:rowCount,
				usid:<?php echo $usid;?>,
				method:"aidmore"
			},
			success: function (response) {
				$('.loadmoth').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
});


//search tickets slips
	 $("body").on('change', 'input#searchme', function() {
      var es = $(this).val(); 
	  if(es.length < 1){
		  return;
	  }
	  
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/tickets-history",
			data: {
			es:es,	
			method:"searchlist"
			},
			
	     success: function(html) {
             $("#seetick").empty().append(html);
			 $('.loadmoth').css('display', 'none');
			 $('span.searck').html("search X");
			}
		});
    return false;
	  
	  });	  

//back to ticket list after search
    $('body').on('click', ' span.searck', function(){
		$(".menuv").trigger("click");
		$("li#ticketshistorysa").trigger("click");
	});
	
	
	
	//view super agent blocked players
    $('body').on('click', ' li#blockagentssa', function(){
		var tabid = $(this).attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  $('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
	  $(".menuv").trigger("click");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/blocked-players",
			data: {
			usid:<?php echo $usid;?>,
			method:"blockagentssa"
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
    
	
	
	//withdrawals
    $('body').on('click', ' li#withdrawalssa', function(){
		var tabid = $(this).attr("id");
		 $(".shownomore").css("display", "block");
	  $('#ajax-content').html("<div id='loading'></div>");
	  $('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
	  $(".menuv").trigger("click");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/withdrawals",
			data: {
			usid:<?php echo $usid;?>,
			method:tabid
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		
		
		
		
		
		//real saagent transfer show money to credit
		$('body').on('keyup', ' input#saagpaynum', function(){
			var trmoney = $(this).val();
			if(trmoney == ''){
		   return false;
		   }
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/sa_withdraws",
			data: {
			usid:<?php echo $usid;?>,
			trmoney:trmoney,
			method:"sashowvaltotr"
			},
		   success: function(response) {
             $(".sashowvaltotr").empty().append(response);
               }
		    });
			   return false;
		});
		
		
		//submit real agent transfer submit
	  $('body').on('click', ' .sasubtransferag', function(){
		$(this).html("Sending..");
		var trmoney = $('input#saagpaynum').val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/sa_withdraws",
			data: {
			usid:<?php echo $usid;?>,
			trmoney:trmoney,		
			method:'saagpaynum'
			},
		   success: function(response) {
             $(".sashowvaltotr").html(response);
			 $(".sasubtransferag").html("Transfer Balance");
			 $('input#saagpaynum').val("");
               }
		      });
			   return false;
		});











    //onclick display withdrawal form
	$('body').on('click', ' .agform', function(){
		 $(".shownomore").css("display", "block");
		$(".sawith").toggle();
		$(this).addClass("ccm");
		$("span.payclose").html("X");
		
	});
	
	$('body').on('click', ' .agform.ccm', function(){
		$(".sawith").css("display", "none");
		$(this).removeClass("ccm");
		$("span.payclose").html("");
		
	});
	

   	//withdrawal request submit
	$('body').on('click', ' .awrequest', function(){
	  $('.sherr').html('Loading...');
	   var paytype = $('#paymenttype').val();
	  var amount = $('#mamount').val();
	  var acnum = $('#acnum').val();
	  var ref = $('#tref').val();
	  if (amount < 100){
		  $('.sherr').text('Amount cannot be empty or less than 100');
		  return;
	  }
	  if (acnum.length < 5){
		  $('.sherr').text('Account number should be more than 5 characters');
		  return;
	  }
	  
	  if (ref.length > 200){
		  $('.sherr').text('Maximum 100 characters allowed');
		  return;
	  }
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/withdrawals",
			data: {
			usid:<?php echo $usid;?>,
			amount:amount,
			acnum:acnum,
			ref:ref,
			paytype:paytype,		
			method:'submitwith',
			
			},
		   success: function(response) {
             $(".showwith").html(response);
			 if(response == '<i class="icon check all"></i> Successfully Submitted'){
				 $(".shownomore").css("display", "none");
			 }
			  $(".sherr").html("");
               }
		      });
			   return false;
		});



    //delete pending slips
	
	$('body').on('click', ' span.wpending', function(){
	 var idto = $(this).attr('id');
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/withdrawals",
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

   //auto credit transfer to betting account
	/*
	$('body').on('click', ' button#paynu', function(){
		$(this).html("Sending..");
		var amount = $('input#paynum').val();
		if(amount < 10){
			$(".shreturn").html("Minimum 10 required");
			return false;
		}
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/withdrawals",
			data: {
			usid:<?php echo $usid;?>,
			amount:amount,		
			method:'paynum'
			},
		   success: function(response) {
             $(".shreturn").html(response);
			 $("button#paynu").html("Send");
               }
		      });
			   return false;
		});
  
  */
  //Send Email..
    $('body').on('click', ' a.sdmail', function(){
	  $('div#hidemail').html("<div id='loading'></div>");
	  $('.loadmo').css("display", "none");
	  var savethis = $(this);
	  var email = $(this).attr("id");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/send-email",
			data: {
			usid:<?php echo $usid;?>,
			email:email,
			method:'sendemailsa'
			},
		   success: function(response) {
             $("div#hidemail").empty().append(response);
               }
		      });
			   return false;
		});
		
	//submit email	
	$('body').on('click', ' .msgrequest', function(){
	  $('#shower').html("Sending.....");
	  var gtmail = $('input#gtmail').val();
	  var subject = $("input#submail").val();
	  var msgcontent = $("textarea#msgcontent").val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/send-email",
			data: {
			usid:<?php echo $usid;?>,
			gtmail:gtmail,
			method:'mailsubmit'
			},
		   success: function(response) {
             $("#shower").empty().append(response);
               }
		      });
			   return false;
		});

    //print reports
    $('body').on('click', ' li#printreportssa', function(){
		var tabid = $(this).attr("id");
	  $('#ajax-content').html("<div id='loading'></div>");
	  $('ul.sidenavs li').css("background", "");
	  var savethis = $(this);
	  $(".menuv").trigger("click");
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/super-agents/print-reports",
			data: {
			usid:<?php echo $usid;?>,
			method:"printreportssa"
			},
		   success: function(response) {
			 $(savethis).css("background", "#e7e7e7");
             $("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});


	
	//expand collapse
	//event name
	$('body').on('click', ' .shadowme', function(){
		var iid = $(this).attr('id');
       $(this).html('Collapse');
	   $(this).addClass('xp');
	   $('#show' + iid).css("display", "block");
	});
	
   $('body').on('click', ' .shadowme.xp', function(){
		var iid = $(this).attr('id');
       $(this).html('Expand..');
	   $(this).removeClass('xp');
	   $('#show' + iid).css("display", "none");
	});	
	
	//cat name
	$('body').on('click', ' .shadowmex', function(){
		var iid = $(this).attr('id');
       $(this).html('Collapse');
	   $(this).addClass('xp');
	   $('#showx' + iid).css("display", "block");
	});
	
   $('body').on('click', ' .shadowmex.xp', function(){
		var iid = $(this).attr('id');
       $(this).html('Expand..');
	   $(this).removeClass('xp');
	   $('#showx' + iid).css("display", "none");
	});	
	
	//option name
	$('body').on('click', ' .shadowmey', function(){
		var iid = $(this).attr('id');
       $(this).html('Collapse');
	   $(this).addClass('xp');
	   $('#showy' + iid).css("display", "block");
	});
	
   $('body').on('click', ' .shadowmey.xp', function(){
		var iid = $(this).attr('id');
       $(this).html('Expand..');
	   $(this).removeClass('xp');
	   $('#showy' + iid).css("display", "none");
	});

//agents chartvz

setTimeout(function () {
google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);

function drawChart(){
    var jsonData = $.ajax({
        url: "<?php echo SITEURL;?>/shell/super-agents/chart.php?usid=<?php echo $usid;?>",
        dataType:"json",
        async: false
}).responseText;

// Create our data table out of JSON data loaded from server.
var data = new google.visualization.DataTable(jsonData);

var options = {'title':'Monthly agents Registration chart', 'colors': ['#c62828']};

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
chart.draw(data,options); 
}

}, 1000);

// check all select box
	$('body').on('click', ' input#checkAll', function(){
     $('input:checkbox').not(this).prop('checked', this.checked);
	 var chk =  $("input[type=checkbox]:checked").length; //$(this).is(":checked").length;
	if(chk > 0){
	$('.binme').css("color", "#f00");
	} else {
	$('.binme').css("color", "");	
	}
 });



function PrintPanel() {
    var panel = document.getElementById("printTable");
    var printWindow = window.open('', '', '');
    printWindow.document.write('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Print Invoice</title>');
    
    // Make sure the relative URL to the stylesheet works:    
    // Add the stylesheet link and inline styles to the new document:
    printWindow.document.write('<link rel="stylesheet" href="css/invoice.css">');
    printWindow.document.write('<style type="text/css">.printwrap { max-width: 560px; background: #f2ffe3; margin: 0 auto; } span.rightpr { float: right; font-weight:bold; } ul.printul li { border-bottom: 1px solid #dcdcdc; } .wrtp { padding: 7px 15px; background: #ffd25c; } span.righttp { float: right; font-weight: bold; }</style>');
    
    printWindow.document.write('</head><body >');
    printWindow.document.write(panel.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    setTimeout(function () {
        printWindow.print();
    }, 500);
    return false;
};

$('body').on('click', ' button#prstats', function(){	
PrintPanel();
});


</script>





<style type="text/css">
ul.sidenavs {
    padding: 0;
}
i.icon.chevron.right {
    float: right;
    margin-top: 5px;
}
.sidenavs li:hover {
    background: #e3e0e0;
}
.sidenavs li {
    position: relative;
    display: block;
    width: 100%;
    padding: 10px 15px;
    border-bottom: 1px solid #e7e7e7;
    cursor: pointer;
    color: #000000;
    letter-spacing: .5px;
}
.sagentd {
    background: #e7e7e7;
	padding:10px;
}
.supadm {
    font-size: 24px;
    font-weight:bold;
}
i.icon.users {
    font-size: 20px;
}
li.supplogo img {
    max-width: 130px;
}
.suppcol.sleft {
    background-color: #f8f8f8;
    max-width: 300px;
}
.supp-header {
    background-color: #1f1f1f;
    border-color: #e7e7e7;
    z-index: 1000;
    border-width: 0 0 1px;
    border-bottom: 1px solid #e7e7e7;
	position: fixed;
    width: 100%;
	padding:10px;
}
ul.suppmenu {
    display: flex;
    width: 100%;
	padding:0px;
	margin:0px;
}

ul.suppmenu li {
    display: table-cell;
    width: 50%;
}
.menuv {
    font-size: 18px;
    font-weight: bold;
    margin-top: 5px;
    display: inline-block;
    cursor: pointer;
    color: #fff;
}
i.icon.reorder {
    font-size: 20px;
    position: absolute;
    margin-left: 5px;
    margin-top: 3px;
    font-weight: bold;
}
.menuv:hover {
    color: #f00;
}

li.suppsubmenu {
    text-align: right;
	padding-right: 10px;
}
span.supbal {
    font-size: 20px;
    font-weight: bold;
    display: inline-block;
    margin-top: 20px;
    color:#fff;
}
div#superuser {
    float: right;
    margin: 0;
    display: inline-block;
    padding: 0;
    line-height: 0;
    vertical-align: text-bottom;
}

.wrapper-sa {
    max-width: 780px;
    margin: 0 auto;
    min-height: 200px;
    margin-top: 10px;
	border-left: 5px solid #f3f3f3;
    padding-left: 5px;
}
.containersa {
    min-height: 100px;
    color: #fff;
}


.colsa {
    float: left;
    padding: 10px;
    margin: 5px;
    background: #282828;
    width: calc(33.33% - 10px);
}

#noo a {
    color: #000;
}

.containersa:after {
  content: "";
  display: table;
  clear: both;
}
.agdwn {
    border-bottom: 3px solid;
    background: #e7e7e7;
    padding: 5px 10px;
    border-color: #eb1515;
}
span.righelse {
    float: right;
}
span.usvalue {
    float: right;
    font-size: 30px;
    margin-right: 2px;
}

span.userle {
    float: left;
    margin-top: 10px;
}
div#cott .colsa {
    background: #c42929;
}

.infcontent a {
    color: red;
}

.noteus {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ffc409;
    margin-top: 10px;
}
.backmeif, .backmeifx,.backmeiftr {
    background: #d0d0d0;
    display: inline-block;
    padding: 3px 20px;
    cursor: pointer;
    margin-bottom:20px
}

.backmeif:hover,.backmeifx:hover {
    background: #a7a7a7;
}
.shadowme,.shadowmex,.shadowmey {
    cursor: pointer;
    color: #0377da;
}
.shadowme:hover,.shadowmex:hover,.shadowmey:hover {
    color: #f00;
}
a#ksow {
    display: inline-block;
    padding: 10px;
}
.countrec {
    font-size: 17px;
    font-weight: bold;
    margin-left: 20px;
    color: #009688;
}
.ollist {
    font-size: 12px;
    padding: 0;
}
.showmo {
    display: inline-block;
}
.srid {
    display: inline-block;
    float: right;
}
.srid i.find.icon {
    padding: 10px;
    font-size: 10px;
    background: #e2e5e2;
	cursor:pointer;
    }

.srid input {
    padding: 6px 10px;
    width: 220px;
    border: 1px solid #eb1515;
}
.srid input:focus {
    background: #efefef;
}
.srid i.find.icon:hover {
    background: #908e8e;
}
.topwwp {
    display: block;
    border-bottom: 1px solid #d4d4d4;
    padding-bottom: 20px;
}
span.searck {
    color: #f00;
    cursor: pointer;
}
span.searck:hover {
    color: #000;
}

/*withdrawal*/
#paymenttype, #mamount, #tref, #acnum {
    width: 260px;
    padding: 8px 10px;
    border: 1px solid #86bcfb;
    border-radius: 3px;
    margin-bottom: 10px;
}
.agform {
    box-shadow: inset 0px 1px 0px 0px #ffffff;
    background:linear-gradient(to bottom, #ededed 5%, #dfdfdf 100%);
    background-color:#ededed;
    border-radius:6px;
    border:1px solid #dcdcdc;
    display:inline-block;
    cursor:pointer;
    color:#777777;
    font-size:16px;
    font-weight:bold;
    padding:6px 24px;
    text-decoration:none;
    text-shadow:0px 1px 0px #ffffff;
}
span.payclose {
    position: absolute;
    margin-top: -15px;
    border-radius: 50%;
    border: 1px solid #fff;
    padding: 0px 6px;
    background: #f00;
    color: #fff;
}
.wrapallmsg {
    display: table;
    margin: 0 auto;
    max-width:460px;
    width:100%;
}

input#submail {
    width: 100%;
    padding: 6px;
}

textarea#msgcontent {
    width: 100%;
    min-height:100px
}
.awrequest, .msgrequest{
    padding: 3px 10px;
    background: #eaff94;
    max-width: 245px;
    margin-left: 5px;
    text-align: center;
    background-color: #44c767;
    border-radius: 28px;
    border: 1px solid #18ab29;
    cursor: pointer;
    color: #ffffff;
    font-family: Arial;
    font-size: 17px;
    text-decoration: none;
    text-shadow: 0px 1px 0px #2f6627;
}
.showwith {
    color: #f00;
}
ul.deptshow {
    float: left;
    padding: 0;
    width: 96%;
    margin-left: 2%;
}
ul.deptshow.k li {
    background: #dcdcdc;
    border-left: 3px solid #2196F3;
}
ul.deptshow li {
    display: inline-block;
    background: #f5f5f5;
    width: 100%;
    max-width: 660px;
    padding: 10px;
}
.ccredit {
    border-bottom: 1px solid #eceaea;
    font-size: 15px;
}
span.deptright {
    float: right;
}
._mandeposit {
    max-width: 460px;
    background: #fffef6;
    font-size: 16px;
    padding: 10px;
    color: #00794d;
}
#mancashback {
     margin: 0px 20px;
}
 .wrequest:hover {
    background: #f9ea67;
}
.wpending {
    position: absolute;
    margin-top: -20px;
    color: #f00;
    border-radius: 50%;
    background: #000;
    line-height: 10px;
    padding: 5px;
    font-weight: bold;
    cursor: pointer;
}
input#paynum {
    padding: 6px;
}
button#paynu {
    padding: 6px;
    cursor: pointer;
}
button#paynu:hover {
    background: #fff;
    border: 1px solid #000;
    border-radius: 3px;
}
.shreturn {
    color: #f00;
}
div#shower {
    color: #f00;
}

.printwrap {
    max-width: 560px;
    background: #f8f8f8;
    margin: 0 auto;
}
span.rightpr {
    float: right;
    font-weight:bold;
}
ul.printul li {
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 25px;
}
.wrtp {
    padding: 7px 15px;
    background: #1f1f1f;
    color: #fff;
}
span.righttp {
    float: right;
    font-weight: bold;
}
button#prstats {
    display: table;
    margin: 0 auto;
}
.mydash,.mytransfer,.myhistory {
    background-color: #e8e8e8;
    border-radius: 28px;
    border: 1px solid #d02222;
    display: inline-block;
    cursor: pointer;
    color: #1f1f1f;
    font-family: Georgia;
    font-size: 17px;
    font-weight: bold;
    padding: 7px 20px;
    text-decoration: none;
    text-shadow: 0px 1px 0px #c62828;
}
.mydash:hover, .mytransfer:hover, .myhistory:hover {
	background-color:#5cbf2a;
}
.mydash:active, .mytransfer:active, .myhistory:active {
	position:relative;
	top:1px;
}
a.mydash.active,a.myhistory.active,a.mytransfer.active {
    background: #fff;
}
b.abchp {
    font-size: 24px;
    color: #a30851;
}
.infowro {
    padding: 10px;
    border: 1px solid #d8d7d7;
    border-radius: 3px;
	background: #f9f9f9;
}
.infcontent {
    margin: 10px;
    background: #232221;
    color: #fff;
    line-height: 20px;
    padding: 10px 2px;
}
.ifys {
    background: #009688;
    padding: 10px;
	color:#fff;
}
span#ttcount {
    background: #0d0d0d;
    position: absolute;
    padding: 3px 10px;
    border-radius: 50%;
    color: #f00;
    margin-top: -20px;
    margin-left: -5px;
}
.depositform {
    border: 1px solid #009688;
    padding: 5px;
    margin-bottom: 30px;
    border-radius: 5px;
    background: #f3f3f3;
}

.sacur {
    display: inline-block;
    padding: 5px 10px;
    margin: 10px 0px;
    background: #ebebeb;
}
div#showavl {
    color: #000;
    margin-bottom: 10px;
    display: table;
}
input#putcash {
    padding: 5px 10px;
    border: 2px solid #d52020;
    border-radius: 5px;
}
.subtransfer {
    padding: 5px 10px;
    background: #eb1515;
    display: block;
    max-width: 180px;
    cursor: pointer;
    margin-top: 10px;
    border-radius: 3px;
    text-align: center;
    color: #fff;
}
.subtransfer:hover {
    background: #f00;
}
.warnintxt {
    font-size: 13px;
    line-height: 15px;
    padding: 5px;
    background: #f3f3f3;
    margin-bottom: 10px;
}
span.searcktr {
    color: #f00;
    cursor: pointer;
}
span.searcktr:hover {
    color: #000;
}

div#withbroker {
    margin-bottom: 200px;
}
span.supspan {
    font-size: 30px;
    font-weight: bold;
}
.superearning {
	padding: 0px 10px;
	}
.sasubtransferag {
    padding: 5px 10px;
    background: #eb1515;
    display: block;
    max-width: 180px;
    cursor: pointer;
    margin-top: 10px;
    border-radius: 3px;
    text-align: center;
    color: #fff;
}
.sasubtransferag:hover {
    background: #f00;
}
.sashowvaltotr {
    padding: 5px 10px;
    margin-top: 10px;
    max-width: 300px;
    color: #f00;
}
input#saagpaynum {
    padding: 5px 10px;
    border: 2px solid #2196F3;
    border-radius: 3px;
}







* {
  box-sizing: border-box;
}
.suppcol {
  float: left;
  min-height: 300px;
}
.supprow:after {
  content: "";
  display: table;
  clear: both;
}
li.supplogo {
    text-align: center;
}
.supprow {
    margin-top: 56px;
}

.menuv.adk:before {
    content: "X";
    margin-right: 5px;
	color:#000;
}
a.ohcollapse {
    position: absolute;
    margin-top: 0px;
    background: #000000;
    padding: 0px 10px;
    color: #f00;
}
a.mrkpr, a.crkpr {
    font-size: 14px;
}
a.kgpPending {
    color: #7a1313;
}
a.crkpr {
	color:#02a70e;
}
a.crkpr:hover, a.mrkpr:hover {
	color:#f00;
}
input#sendfunds {
    width: 300px;
    padding: 7px;
    border: 2px solid #b7b7b7;
    border-radius: 5px;
}
i#fico {
    position: absolute;
    margin-left: -30px;
    margin-top: 10px;
    cursor: pointer;
}
.txnhistory {
    display: inline-block;
    background: #f3f3f3;
    cursor: pointer;
    border-bottom: 3px solid #cd2525;
    margin-bottom: 20px;
}
.txnhistory:hover {
    background: #fff387;
}

.singbt a {
    color: #c52929 !important;
}

/*for transfer history*/
ul.deptshow li {
    display: inline-block;
    background: #f5f5f5;
    width: 100%;
    max-width: 660px;
    padding: 10px;
}

ul.deptshow {
    float: left;
    padding: 0;
    width: 99%;
    margin-left: 0%;
}
ul.deptshow.k li {
	background:#fff8f8;
	border-left: 3px solid #f00;
}

span.deptright {
    float: right;
}
.ccredit {
    border-bottom: 1px solid #eceaea;
    font-size: 15px;
}
.depositform {
    font-size: 85%;
    margin: 15px;
    border: 1px solid #43ff1b;
    padding: 5px;
    line-height: 18px;
	max-width:660px;
}
div#lrembankb{
	cursor:pointer;
}






@media screen and (max-width: 799px){
.suppcol.sleft {
    display: none;
}
.suppcol.sleft.noso {
    display: none;
}
.suppcol.sright{
	width:100%!Important; 
}
span.userle{
	margin-top:3px!Important;
}
span.usvalue {
    font-size: 16px!important;
}
div#superuser {
    margin-top: 20px;
 }
 .supp-header{
	 padding:0px
 }
 .menuv {
    padding: 10px;
    margin-top: 15px;
     color: #fff;
 }

li.supplogo img {
    max-width: 140px;
    margin-top:3px;
}
span.supbal {
    font-size: 16px;
}
.supprow {
    margin-top: 68px;
}
.suppcol.sleft.shmob {
    display: block;
    width: 100%!Important;
    max-width: 799px;
 }
}




</style>
<!-- Page Content-->
