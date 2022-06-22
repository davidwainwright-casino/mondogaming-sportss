<?php
  /**
   * Index
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if(App::Auth()->is_User()){
     $usid = App::Auth()->uid;
	 $afid = Auth::$udata->afid; 
  } else {
	 $usid = 999999999; 
  } ?>

<main<?php echo Content::pageBg();?>>
<?php include_once('top-horizontal-sports.php');?>
<div class="_homeIndex">

		













</div>
</main>
<?php $gt= $_GET['pid'];$sp= $_GET['sp'];?>
<script>
//auto load events on visit
let searchParams = new URLSearchParams(window.location.search);
	var evid = searchParams.get('pid');
	var spid = searchParams.get('sp');
			
if($(window).width() <= 767) {
		 var devi = 'mobile';
		 showMob();
	 }else{
		 var devi = 'desktop';
	 }
	$("._homeIndex").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/__event_view_row_data.php",
			data: {
			method:'home_index',
			device:devi,
			evid:evid,
			spid:spid,
			usid:'<?php echo $usid;?>'
			},
	     	success: function(html) {
			$('._homeIndex').removeClass("rload");
            $("._homeIndex").empty().append(html);
			showUnSubmittedSlip();
			$("a.datalink" + evid).css("color", "#ffb309");
            }
        }); 
 
 
						
 //load full event on left side list click 
 
 $('body').on('click', 'ul#crlisidebar li', function(){
	 var evid = $(this).attr("id");
	 var spid = $(this).attr("class");
	 if($(window).width() <= 767) {
		 var devi = 'mobile';
	 }else{
		 var devi = 'desktop';
	 }
	 var ssthis = $(this);
	$("div#shfullevent").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/__click_full_events.php",
			data: {
			method:'home_index',
			device:devi,
			evid:evid,
			spid:spid,
			usid:'<?php echo $usid;?>'
			},
	     	success: function(html) {
			//window.history.pushState('page2', 'Title', '/events/?pid=' + evid + '&sp=' + spid);	
			if (history && history.pushState){
				 history.pushState(null, null, '/events/?pid=' + evid + '&sp=' + spid);
				}
			$('._homeIndex').removeClass("rload");
            $("._column._one").empty().append(html);
			$("a.datalink" + evid).css("color", "#ffb309");
            }
        }); 
 
 });
 
 
 
//fetch ref 30 secs
var stopin = setInterval(setReload, 20000);
 function setReload(){
	var tempScrollTop = $("._one").scrollTop();	
	var savclick = $("div#emptyResonse").click();
	var cevid = $("input#cevid").val();
	var cspid = $("input#cspid").val();
	const tnames = JSON.parse(localStorage.getItem("tid")) || [];
	var carr = [];
	$('body').find('.geegoo').each(function(e){
		var fids = $(this).attr("id").replace("d", "");
		carr.push(fids);
	  });
	  sessionStorage.setItem("tid", JSON.stringify(carr));
	   $.ajax({
				type: "POST",
				url: "<?php echo SITEURL;?>/shell/exchange/__full_event_view.php",
				data: {
				method:'home_index',
				device:devi,
				evid:cevid,
				spid:cspid,
				usid:'<?php echo $usid;?>'
				},
				success: function(response) {
				slipupdate();	
				$("div#shfullevent").empty().append(response);
				$("._one").scrollTop(tempScrollTop);
				const sesItem = JSON.parse(sessionStorage.getItem("tid")) || [];
				$.each(sesItem, function(index, value){
				var getTid = value;	
				$("#d" + getTid).addClass("geegoo");
				$("#c" + getTid).css("display", "block"); 
				});
				sessionStorage.removeItem('tid');
				
				//slip selection bg color
				$('body').find('a.delete_single_bet').each(function(e){
					var gtid = $(this).attr('id');
					if($('.sopt').attr('id') === 'lay'){
						$("#corx-" + gtid).addClass("activatorLay");
					} else { 
						$("#cor-" + gtid).addClass("activatorL");
						}
				})
				
					setTimeout(function(){
						$(".topmatchbg").trigger("click");
						}, 1000);
						
						
				
				}
			});
		
};

slipupdate();
//localStorage.clear();
//betslip odd update
function slipupdate(){
	var sdata = localStorage.getItem("itemsArray");
	//console.log(sdata);
	var sdata = JSON.parse(sdata);
	
	//setTimeout(function() {
		$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/__slipupdate.php",
			data: {
			method:'slipupdate',
			dataType:"JSON",
			sdata:sdata,
			usid:'<?php echo $usid;?>'
			}, 
	     	success: function(response) {
			localStorage.removeItem('itemsArray');	
			//console.log(response);
		  //var person = JSON.parse(response.cdata);
		  var cpur = JSON.parse(response);
			localStorage.setItem("itemsArray", JSON.stringify(cpur));	
            //console.log(response);
			showUnSubmittedSlip();
            }
        });

}


//$('#iframe').attr('src', "https://betting-software.sportscaff.com/");

$('body').on('click', '#button', function(){
    $("#frame").attr("src", "https://cs.betradar.com/ls/widgets/?/universalsoftwaresolutions/en/Europe:Berlin/page/lmts#matchId=27197614");
});

setTimeout(function(){
		$("iframe#iframe").css("display", "block");
		$(".framewrapper").css("display", "block");
		$("div#dctn").css("display", "none");
		$('.framewrapper').addClass("adks");
	}, 1000);

//show live events on left bar in mobile
function showMob(){
$('body').on('click', '.qlinks.fr', function(){
	$("#hdmob").toggle();
 })
}

 
//put event name
	setTimeout(function(){
		var gevn = $(".evt-name").html();
		$("span.getEvn").html(gevn);
	}, 1000);
	
//show full commentary on click
$('body').on('click', '.commentryof, .sloader', function(){
	$(".upstats").addClass("shcom");
	sessionStorage.setItem("hideCom", "shcom");
});	
//hide commentary
$('body').on('click', 'a#toggme', function(){
	$(".upstats").removeClass("shcom");
	sessionStorage.removeItem('hideCom');
});	




//show stats
var stopin = setInterval(shStats, 30000);
 function shStats(){
	 if($(window).width() <= 680) {
		 var devi = 'mobile';
	 }else{
		 var devi = 'desktop';
	 }
	var savcls = $("#gtparam").attr("class").replace("upstats", ""); 
	var tempScrollTop = $("._one").scrollTop();	
	var savclick = $("div#emptyResonse").click();
	var cevid = $("input#cevid").val();
	var cspid = $("input#cspid").val();
	   $.ajax({
				type: "POST",
				url: "<?php echo SITEURL;?>/shell/exchange/__stats.php",
				data: {
				method:'stats_index',
				device:devi,
				evid:cevid,
				spid:cspid,
				usid:'<?php echo $usid;?>'
				},
				success: function(response) {
				$(".setStats").empty().append(response);
				//const hdc = JSON.parse(sessionStorage.getItem("hideCom")) || [];
				
					$(".upstats").addClass(savcls);
				
				
				}
			})
		}








//toggle categories
$('body').on('click', '.catfid', function(){
	var getgid = $(this).attr("id").replace("d", "");
	var catid = getgid;
	$(this).toggleClass("geegoo");
	//document.getElementById(trueModalId).style.display = "block";
	$("#c" + catid).slideToggle();
	//$(this).find("i#catcol").html('<i id="catcol" class="icon angle down"></i>');
})




//for cashout
  $('body').on('click', 'span.casout', function() {
	  clearInterval(stopin);
	  var cspid = $("input#cspid").val();
	  var slip_id = $(this).attr("id");
	  var odd = $(this).attr("class").replace("casout dod", "");
	  var savthis = $(this);
	  $(savthis).html("cashing out..");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/cashout_inplay.php",
			data: {
			method:'cashout',
			odd:odd,
			spid:cspid, 
			slip_id:slip_id,
			usid:'<?php echo $usid;?>'
			},
	     	success: function(response) {
			 $(savthis).empty().append(response);
			 console.log(response);
			 alert(response);
			 //setInterval(setReload, 15000);
            }
        }); 
  });


//show slip for mobile on click
	$('body').on('click', '.wrrslip', function(){
		$(".xmodal-content").css("display", "block");
		$(".xclose").addClass("copen");
		$(".wrrslip").addClass("kop");
	})
	//mobile close slip
	$('body').on('click', 'i#closeSlip', function(){
		$(".xmodal-content").css("display", "none");
		$(".xclose").removeClass("copen");
		$(".wrrslip").removeClass("kop");
	})
//back to home
$('body').on('click', '#backtohome', function(){
	window.history.back();
	var tempScrollTop = $("._one").scrollTop();	
	if($(window).width() <= 680) {
		 var devi = 'mobile';
	 }else{
		 var devi = 'desktop';
	 }
	$("._homeIndex").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/_row_data.php",
			data: {
			method:'home_index',
			device:devi,
			usid:'<?php echo $usid;?>'
			}, 
	     	success: function(html) {
			$('._homeIndex').removeClass("rload");
            $("._homeIndex").empty().append(html);
			$("._one").scrollTop(tempScrollTop);
			showUnSubmittedSlip();
            }
        }); 
 
  
})




//error display timout
function tout(){
	setTimeout(function() {
			  $("div#sherrors").html('');
		  }, 10000);
}

			//plus minus input
			$('body').on('click', '.minus', function () {
				var $input = $(this).parent().find('input#stake_value');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				$("#stake_value").trigger("keyup");
				return false;
			});
			$('body').on('click', '.plus', function () {
				var $input = $(this).parent().find('input#stake_value');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				$("#stake_value").trigger("keyup");
				return false;
		});
		
		
		
//trigger input
function inputZero(){
	var letterToUse = '0';
		var e = $.Event("keyup");
		e.which=e.keyCode=letterToUse.charCodeAt();
		$("input#stake_value").val(letterToUse).trigger(e);
}
//trigger input
function triggerInput(){
	var letterToUse = $("input#stake_value").val();
		var e = $.Event("keyup");
		e.which=e.keyCode=letterToUse.charCodeAt();
		$("input#stake_value").val(letterToUse).trigger(e);
}

	
	
	
  $('body').on('keyup', 'input#stake_value', function() {
	  var betval = $(this).val();
	  var totodd = $("input#todd_value").val();
	  var maxwinner = parseInt($("input#maxwinner").val());
	  var getTotal = betval * totodd;
	  var nettotal = parseFloat((getTotal).toFixed(2));
	  if(nettotal > maxwinner){
		 var dcount = maxwinner + " (Limit)";
	  }else{
		 var dcount = nettotal;
	  }
	  $("input#return_value").val(dcount);
  });		

function newWinner(){
	  var betval = $("input#stake_value").val();
	  var totodd = $("input#todd_value").val();
	  var maxwinner = parseInt($("input#maxwinner").val());
	  var getTotal = betval * totodd;
	  var nettotal = parseFloat((getTotal).toFixed(2));
	  if(nettotal > maxwinner){
		 var dcount = maxwinner + " (Limit)";
	  }else{
		 var dcount = nettotal;
	  }
	  $("input#return_value").val(dcount);
}

	
	
	//slips main selection div
	//for add bet options
	$('body').on('click', 'div[id^=bet__option__btn]', function (event) {
		if ($(event.target).is("span.casout")) {
			return false;
		}
		var savthis = $(this);
		var uids = '<?php echo $usid;?>';
		if(uids == 100){
			$("a#myBtn").trigger("click");
			return false;
		}
		var comp = $("input.numcc").val();
		if(comp == 11){
			alert("Maximum selection reached. Remove some and re-select");
			return false;
		}
		$('._slip_container').css("background", "");  
		var numbet = $('.ccounter').length;
		if(numbet <= 1){
			$('._slip_wrapper').css("display", "block");
			$('.top-wrapper').css("display", "block");
		}
        var selected_option_id = this.id;
        var bet_info=selected_option_id.split('__');
		var event_id=bet_info[3];
        var event_name=bet_info[4];
		var cat_id=bet_info[5];
        var cat_name=bet_info[6];
        var oid = bet_info[7];
        var bon=bet_info[8];
		var spid=bet_info[9];
		var bodk=bet_info[10];
		var bod=bet_info[11];
		var type=bet_info[12];
		var oname = $(this).attr("class").replace("b_option_odd evn-", "");
		//$(".bback.activatorL").removeClass("activatorL");
		$(this).addClass("opspin");
		
		//set time expiry
		var tnow = $.now();
		if (localStorage.getItem("sliptimeout") === null) {
			localStorage.setItem("sliptimeout", tnow);
		}else{
			var getTo = localStorage.getItem("sliptimeout");
			var tos = parseInt(getTo) + parseInt("600000");
			if(tnow > tos){
				localStorage.clear();
			}
			//localStorage.clear("shiptimeout");
			localStorage.setItem("sliptimeout", tnow);
			
			
		}
		
		
		
var coitems = 'itemsArray';
const oldItems = JSON.parse(localStorage.getItem(coitems)) || [];
var letst = oldItems.length;
var nlength = parseInt(letst);
if(nlength == 11){
	alert("Maximum selection reached. Remove some and re-select");
	return false;
}
const idToUse = event_id;
const existingItem = oldItems.find(({ event_id }) => event_id === idToUse);
//const conidItem = oldItems.find(({ conid }) => conid === conid);
if (existingItem) {
  Object.assign(existingItem, {
	'cat_name': cat_name,  
    'bet_option_id': oid,
	'bet_option_name': bon,
	'sodd':bodk,
	'odd':bod,
  })
} else {
  const newItem = {
    'event_id' : event_id,
    'event_name': event_name,
	'cat_id': cat_id,
	'cat_name': cat_name,
	'bet_option_id': oid,
	'bet_option_name': bon,
	'sodd':bodk,
	'odd':bod,
	'sp':spid,
	'type':type,
	'aid':'<?php echo $afid;?>',
	'usid':'<?php echo $usid;?>',
    

  };
  oldItems.push(newItem);
}

//console.log(oldItems);

localStorage.setItem(coitems, JSON.stringify(oldItems));
	showUnSubmittedSlip(savthis);
	$(".cvib").empty().append("Loading slip...");
	$("div#hdinside").css("display", "block");
	$(".dco" + cat_id).css("color", "#f00");
});
















	//unsubmitted slips
	function showUnSubmittedSlip(savthis){
		var ifsigned = '<?php echo $usid;?>';
		if(ifsigned == 100){
			return false;
		}
		totOdds=1;
		var unsubmitted_slips_container=$('._slip_container');
		
		var coitems = 'itemsArray';
		const response = JSON.parse(localStorage.getItem(coitems)) || [];
	//var response = localStorage.getItem(coitems, JSON.stringify(oldItems));

		//var evid = $("li.ssevname").attr("id");
		//var evid = searchParams.get('VwatchID');
		unsubmitted_slips_container.html("");
		
		setTimeout(function() {
			const oldItems = JSON.parse(localStorage.getItem(coitems)) || [];
			var letst = oldItems.length;
			var nlength = parseInt(letst);
			if(nlength >1){
				$(".ssmsg").html("<?= Lang::$word->MULTI_BET_PARLAYS; ?>");
				$("a#delete_all_unsubmitted_bets").html('<i class="icon trash alt"></i> <?= Lang::$word->BET_CLEAR_ALL; ?>');
				$("div#hdinside").css("display", "block");
				$(".topbtn").removeClass("expandHslip");
			}else if(nlength ==1){
				$(".ssmsg").html("<?= Lang::$word->BET_SINGLE_BET; ?>");
				$("a#delete_all_unsubmitted_bets").html('<i class="icon trash alt"></i> <?= Lang::$word->BET_CLEAR_ALL; ?>');
				$("div#hdinside").css("display", "block");
				$(".topbtn").removeClass("expandHslip");
			}else{
				$(".ssmsg").html("<?= Lang::$word->CLICK_ODD_TO_ADD_SELECTION; ?>");
				$(".topbtn").addClass("expandHslip");
				$("a#delete_all_unsubmitted_bets").html("Empty");
				//$("._slip_container").html("<?= Lang::$word->CLICK_ODD_TO_ADD_SELECTION; ?>");
				$("div#hdinside").css("display", "none");
				$("input#todd_value").val("0");
				$("input#return_value").val("0");
			}
		  var numbet = $('.ccounter').length;
		  $('.numcc').val(numbet);
		  $("input#maxSelect").val(numbet);
		  $(".bback.activatorL").removeClass("activatorL");
		 //suspended
		 $("span.slip-bet-odd").each(function(e) {
			 var getsus = $(this).html();
			 if(getsus=='suspended'){
				 $(this).css({"background-color": "#767676!important", "color": "#e5dcdc"});
			 }
		 });
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {		
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      })
		  $(savthis).removeClass("opspin");
		  }, 100);

				
  				var html="";
  				$.each(response,(key,value)=>{
					
  					if(value){
              
	  					$(".dco" + value.cid).css("color", "#f00");
						totOdds *= parseFloat(value.odd);
						var dcount = parseFloat((totOdds).toFixed(2));
						$("input#todd_value").val(dcount);
	  					html=`<div class="ccounter" id="itemsd">
						 			
								<div class="evname">
								<span class="evsodl">%event_name%</span><span class="lvcoin %ctype%">%type%</span>
								</div>
						 			<p class="catnn" style="font-weight: 700,margin:0">%cat_name%</p>
								<a href="" class="delete_single_bet dco%event_id%" title="Delete current unsubmitted bet" id="%bet_id%">X</a>	
						 			<div class="sbtslip">
						 				<p class="betnn">%bet_name%</p>
						 				<span class="slip-bet-odd" id="%og_odd%">%bet_odd%</span>
						 			</div>
								</div>`;
  						html=html.replace('%cat_id%',value.cat_id);
  						html=html.replace('%cat_name%',value.cat_name);
  						html=html.replace('%bet_name%',value.bet_option_name);
						html=html.replace('%bet_odd%',value.sodd);
  						html=html.replace('%og_odd%',value.odd);
  						html=html.replace('%bet_id%',value.bet_option_id);
						html=html.replace('%event_id%',value.event_id);
						html=html.replace('%event_name%',value.event_name);
						html=html.replace('%type%',value.type);
						html=html.replace('%ctype%',value.type);
  	  				unsubmitted_slips_container.append(html);
	  					//unsubmitted_slips_container.append("<hr>");
  					}
  				});
				setTimeout(function(){
		newWinner();
		}, 500);
  			}
			
			
			
			
			
			
			
			
			
	//listener to delete single unsubmitted bet
	$('body').on('click', '.delete_single_bet', function(){
		var removebg = $(this).attr("class").replace("delete_single_bet dco", "");
		$(".dco" + removebg).css("color","");
	});
		
	$('body').on('click', '._slip_container', function(event){
		event.preventDefault();
    	var bet_id=event.target.id;
		var coitems = 'itemsArray';
		$('._slip_wrapper').css("opacity", "0.5");
		var getStored = localStorage.getItem(coitems);
		var getStoredArr = JSON.parse(getStored);
		for(var i = 0; i < getStoredArr.length; i++){
			if (getStoredArr[i].bet_option_id == bet_id){
				getStoredArr.splice(i, 1);
				localStorage.setItem(coitems, JSON.stringify(getStoredArr));
				$("#cor-" + bet_id).removeClass("activatorL");
				}
			}
		slipupdate();	
	
		setTimeout(function(){
		showUnSubmittedSlip();
		}, 500);
		
			$('.cvib').html('<a style="color:#fff">Deleting selection...</a>');
				    $('.cvib').html('Deleted');
					setTimeout(function() {
						var numbet = $('.ccounter').length; 
						$('.numcc').val(numbet);
						}, 1000);
						$("#corx-" + bet_id).removeClass("activatorLay");
						$('._slip_wrapper').css("opacity", "");
	});


	//listener to delete all unsubmitted slips
	 $('body').on('click', '#delete_all_unsubmitted_bets', function(event){
		event.preventDefault();
		$('._slip_wrapper').css("opacity", "0.5");
		$('.cvib').html('<a style="color:#fff">Deleting all slips...</a>');
		var coitems = 'itemsArray';
		localStorage.removeItem(coitems);
		slipupdate();
		$(".catTop1.xp").css("color","");
		
				setTimeout(function(){
					showUnSubmittedSlip();
					var numbet = $('.ccounter').length; 
					$('.numcc').val(numbet);
						//color slip	
					$(".bback.activatorL").removeClass("activatorL");
					$('.cvib').html('Deleted');
					$("span.totalccc").html('+0.00');
				}, 500);
				$('._slip_wrapper').css("opacity", "");
				
	});

	
	
	
	
	 $('body').on('click', ' #submit_bet', function(event){
		 clearInterval(stopin);
		 /*$("span.slip-bet-odd").each(function(e) {
		    var rtid = $(this).attr('id');
			if(rtid==0) {		
			$("div#sherrors").html("One of your selection is suspended");
			return false;
			}
	      });
		  */
	
		 var tnow = $.now();
		 var getTo = localStorage.getItem("sliptimeout");
		 var tos = parseInt(getTo) + parseInt("600000");
			if(tnow > tos){
				localStorage.clear();
				$("._slip_container").html("Slip Timed Out");
				$(".bback.activatorL").removeClass("activatorL");
				return false;
			}
    	const stake=parseFloat(document.querySelector('#stake_value').value);	
		var svalue = $("#stake_value").val();
		var totbal = $("input#chipsbal").val();
		var winnings = $("input#return_value").val();
	
		var coitems = 'itemsArray';
		var getStored = localStorage.getItem(coitems);
		var getStoredArr = JSON.parse(getStored);
		var minbet = $("#minbet").val();
		var maxbet = $("#maxbet").val();
		//console.log(getStoredArr);
		
		var numbet = $('.ccounter').length;
		if(numbet > 11 ){
			$("div#sherrors").html("Max. 11 selection allowed");
			tout();
			return false;
		}
		if(numbet < 3 && numbet > 1 ){
			$("div#sherrors").html("Multi Bet requires minimum 3 Selection");
			tout();
			return false;
		}
		if(parseInt(svalue) < parseInt(minbet)){
			$("div#sherrors").html("Minimum bet allowed is " + minbet);
			tout();
			return false;
		}
		if(parseInt(svalue) > parseInt(maxbet)){
			$("div#sherrors").html("Maximum bet allowed is " + maxbet);
			tout();
			return false;
		}
		
		  
		
		if($("input#checkpromo").is(':checked')){
				   var ifref = 'promo'; 
			   } else {
				   var ifref = 'chips';
				   if( parseFloat(svalue) > parseFloat(totbal)){
					   $('.place_bet_button').text('Submit Quiz');
					   $("div#sherrors").html("You don't have sufficient credit to Join this contest");
					   tout();
					   return false;
					 }
			if(parseInt(svalue) > parseInt(totbal)){
			$("div#sherrors").html("Your balance is too low");
			tout();
			return false;
			} 
		}
		
		
		$('.place_bet_button').addClass('stop');
		$('.place_bet_button').css('pointer-events','none');
		$(".modelwrap, #ajax-content, ._slip_wrapper").css("pointer-events", "none");
		$("._slip_container").css("opacity", "0.5");
		$('.place_bet_button').html('<div class="ofslide">.</div><div class="lds-hourglass"></div> Wait a moment...');
		myTim1= setTimeout(function () {
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/exchange/add_bet",
			type: "post",
			data: {
				getStoredArr:getStoredArr,
				ifref:ifref,
				stake:svalue,
				winnings:winnings,
				usid:'<?php echo $usid;?>',
				submit_bet:'submit_bet'
			},
			success: function (response) {
				$(".modelwrap, #ajax-content, ._slip_wrapper").css("pointer-events", "");
				$("._slip_container").css("opacity", "");
				//showUnSubmittedSlip();
				$('.place_bet_button').text('Submit Quiz');
				//$(".bback.activatorL").removeClass("activatorL");
				$('.place_bet_button').css('pointer-events','');
				if(response == 'Bet Successfully Accepted'){
					$('.ssmsg').html("<span id='edcoss'>" + response + "</span>");
					setTimeout(function(){
					showUnSubmittedSlip();
					}, 7000);
					$("div#hdinside").css("display", "none");
					$("div#sherrors").html('');
					$("input#checkpromo").prop("checked", false);
					$(".catTop1").css("color", "#1e2022");
					$(".ptptomo").html("");
					$(".bback.activatorL").removeClass("activatorL");
					localStorage.removeItem("itemsArray");
				}else{
					$("div#sherrors").html(response);
				}
				setInterval(setReload, 15000);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}

		});
		}, 1000);
		
	});
			
			
			
 
</script>


		
<style>
* {
  box-sizing: border-box;
}


._oaod {
    background: #126b16;
    display: inherit;
    border-radius: 50%;
    padding: 6px 5px;
}

.qlinks.fr {
    background: #000000;
    color: #fbfbfb;
}
._rowdivider {
    display: flex;
}
._divsp.tright {
    width: 64%;
    background: #0c0c0c;
    margin-top: 20px;
    box-shadow: 0px 0px 2px 2px #0d150b;
}
._divsp{
	float: left;
}
._rowdivider:after {
    content: "";
    display: table;
    clear: both;
}
._divsp.tleft {
    width: 30%;
    background: #2f2f2f;
    margin: 20px;
    box-shadow: 0 1px 3px 1px rgb(99 99 99 / 81%);
}

ul#crlisidebar li {
    display: inline-block;
    width: 100%;
    margin: 5px 0px;
    font-family: arial, ui-sans-serif;
	background: #252525;
    color: #fff;
    font-size: 90%;
    border-bottom: 1px solid #404040;
    padding-bottom: 5px;
}
ul#crlisidebar {
    margin: 0;
    padding: 0;
}
ul#crlisidebar li a {
    color: #c7c7c7;
}
span.crcount {
    float: right;
    margin-right: 5px;
}


.qlinks.fr::after {
    border-top-color: #ffc107;
}
i#cflags {
    float: left;
    border: 1px solid #eb1515;
    font-size: 10px;
    border-radius: 50%;
    padding: 5px;
    margin-right: 5px;
    background: #000;
	margin-left: 10px;
}
.qlinks.fr.cr {
    background: #ed7020;
}

.toplvbhgh {
    background: #232323;
    padding: 7px 10px;
    color: #ffffff;
    font-weight: bold;
	position:relative;
}

i#cmrr {
    float: right;
    font-size: 12px;
    margin-top: 5px;
    z-index: 9;
    color: #000;
}
.toplvbhgh::after {
    content: "";
    width: 0;
    height: 0;
    display: block;
    border-top: 37px solid #eb1515;
    border-bottom: 0px solid #fdfdfd;
    border-left: 56px solid transparent;
    border-right: 0px solid #f3f3f3;
    position: absolute;
    right: 0;
    top: 0;
}

ul.splivehome li:last-child {
    border-right: none;
}
.cact {
    background: #eb1515;
}




div#btheader .bback {
    font-weight: bold;
    float: right;
    text-align: center;
    font-size: 15px;
    font-family: sans-serif;
    color: #f1e6e6;
	width:60px;
}

div#btheader .b_option_odd {
    width: 100%;
    padding: 5px 10px;
    color: #ccc;
    border-bottom: 1px solid #000000;
    border-top: 1px solid #171717;
}

#shfullevent .catfid {
    background: #212121;
    padding: 8px 10px;
    font-weight: bold;
    color: #fff;
}

span.catsuspended {
    padding: 10px;
    color: #464646;
    float: left;
}

i#susplock {
    float: right;
    padding: 10px;
    color: #292929;
    margin-right: 3%; 
}
span.bodname {
    max-width: 80%;
}
.suswraper {
    height: 40px;
}


i#catcol {
    float: right;
    margin-top: 4px;
    cursor: pointer;
	margin-right: 20px;
}
i#catcol:hover {
    color: #afafaf;
}
.catheader {
    display: none;
}

.hid1,.hid2,.hid3,.hid4,.hid5 {
    display:block;
}
.catfid {
    border-bottom: 1px solid #000;
}
.geegoo i#catcol {
    color: transparent;
}
.geegoo i#catcol:hover {
    color: #fff;
}
span.catsuspendeds {
    color: #323232;
    margin-top: 6px;
    display: block;
    float: left;
    margin-left: 10px;
    pointer-events: none;
}
div#rvsus {
    display: table;
    margin: 0 auto;
    margin-top: 10%;
    font-size: 30px;
    color: #900000;
}
.sp_sprit{
	margin-left: 5px;
    margin-top: 7px;
    margin-right: 5px;
}
div#tmtwrapper {
    display: inline-block;
    line-height: 18px;
}

.casout {
    background: #03A9F4;
    color: #fff;
    padding: 1px 4px;
    border-radius: 3px;
    cursor: pointer;
	z-index:99999999;
    display: block;
    float: left;
    position: absolute;
    line-height: 16px;
    font-size: 13px;
    margin-top: -2px;
	margin-left: 70px;
}
span.shwinnings {
    display: block;
    position: absolute;
    float: left;
    margin-top: -5px;
    font-size: 15px;
    color: #009688;
    font-weight: bold;
}
.setStats {
    background: linear-gradient( 
0deg
 ,#7c3221,rgb(0 0 0 / 79%)), url(../images/topmatchsav-min.jpg) no-repeat;
    height: 180px;
    width: 100%;
    color: transparent;
}
span.getEvn {
    color: #fff;
    float: right;
    margin: 0px 5px;
}

.upstats.shcom{
	display:block!Important;
}
tr.displayer.cricom{
	display:block!important;
	z-index:99;
}
</style>