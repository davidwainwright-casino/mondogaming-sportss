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

<script>
if($(window).width() <= 767) {
		 var devi = 'mobile';
		 showMob();
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
			btype:"all",
			usid:'<?php echo $usid;?>'
			},
	     	success: function(html) {
			$('._homeIndex').removeClass("rload");
            $("._homeIndex").empty().append(html);
			showUnSubmittedSlip();
            }
        }); 
 
  //load events by country on click
  $('body').on('click', 'ul#cccrlisidebar li', function(){
	  var cc = $(this).attr("id");
	  $("ul#crlisidebar li, ul#cccrlisidebar li").css("background", "");
	  $(this).css("background", "#171717");
	$("._divsp.tright").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/__update_home.php",
			data: {
			method:'sport_index',
			btype:'country',
			cc:cc,
			usid:'<?php echo $usid;?>'
			}, 
	     	success: function(response) {
			$('._divsp.tright').removeClass("rload");
            $("._divsp.tright").empty().append(response);
            new Swiper(".sports-swiper", gambaCategoriesSliderSettings);
            }
        })
	});

		
	 //load sports on click
  $('body').on('click', 'ul#crlisidebar li', function(){
	  var spid = $(this).attr("id");
	  $("ul#crlisidebar li, ul#cccrlisidebar li").css("background", "");
	  $(this).css("background", "#171717");
	$("._divsp.tright").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/__update_home.php",
			data: {
			method:'sport_index',
			btype:'sport',
			kspid:spid,
			usid:'<?php echo $usid;?>'
			}, 
	     	success: function(response) {
			$('._divsp.tright').removeClass("rload");
            $("._divsp.tright").empty().append(response);
            new Swiper(".sports-swiper", gambaCategoriesSliderSettings);
            }
         })
	})

    //load sports on click - slider
    $('body').on('click', '.gambabet-categories-slider a', function(){
        var spid = $(this).attr("id");
        $("ul#crlisidebar li, ul#cccrlisidebar li").css("background", "");
        $(this).css("background", "#171717");
        $("._divsp.tright").empty().append("<div id='loading'></div>");
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/exchange/__update_home.php",
            data: {
                method:'sport_index',
                btype:'sport',
                kspid:spid,
                usid:'<?php echo $usid;?>'
            },
            success: function(response) {
                $('._divsp.tright').removeClass("rload");
                $("._divsp.tright").empty().append(response);
                new Swiper(".sports-swiper", gambaCategoriesSliderSettings);
            }
        })
    })
 
 //show live events on left bar in mobile
function showMob(){
$('body').on('click', '.qlinks.fr', function(){
	$("#hdmob").toggle();
 })
}
 
//fetch ref 30 secs

var stopin = setInterval(setReload, 20000);
function setReload(){
var tempScrollTop = $("._one").scrollTop();	
var savclick = $("div#emptyResonse").click();
var btype = $("input#btype").val();
var kspid = $("input#kspid").val();
var cc = $("input#kcc").val();
   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/__update_home.php",
			data: {
			method:'home_index',
			btype:btype,
			device:devi,
			kspid:kspid,
			cc:cc,
			usid:'<?php echo $usid;?>'
			},
	     	success: function(response) {
            $("._divsp.tright").empty().append(response);
           new Swiper(".sports-swiper", gambaCategoriesSliderSettings);
			$("._one").scrollTop(tempScrollTop);
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
				slipupdate();	

			
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
		  $.each(jQuery.parseJSON(response), function(index, item){
			  
			  });
			localStorage.setItem("itemsArray", JSON.stringify(cpur));	
            //console.log(response);
			showUnSubmittedSlip();
            }
        });

}


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

//slips

	$('body').on('click', 'div[id^=bet__option__btn]', function (event) {
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
				$("a#delete_all_unsubmitted_bets").html("<?= Lang::$word->BET_EMPTY; ?>");
				$("._slip_container").html("<?= Lang::$word->CLICK_ODD_TO_ADD_SELECTION; ?>");
				$("div#hdinside").css("display", "none");
				$("input#todd_value").val("0");
				$("input#return_value").val("0");
			}
		  var numbet = $('.ccounter').length;
		  $('.numcc').val(numbet);
		  $("input#maxSelect").val(numbet);
		  $(".bback.activatorL").removeClass("activatorL");
		 // var netnum = parseInt(numbet * 9.1);
		  //$(".progress-bar.progress-bar-warning.xp").css("width", netnum + "%");
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
		
		$("span.slip-bet-odd").each(function(e) {
		    var rtid = $(this).attr('id');
			if(rtid==0) {		
			$("div#sherrors").html("One of your selection is suspended");
			return false;
			
			}
	      })
		
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
				$(".ssmsg").css("opacity", "");
				//showUnSubmittedSlip();
				$('.place_bet_button').text('Submit Quiz');
				//$(".bback.activatorL").removeClass("activatorL");
				$('.place_bet_button').css('pointer-events','');
				if(response == 'Bet Successfully Accepted'){
					$('._slip_container').html("<span id='edcoss'>" + response + "</span>");
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
				setInterval(setReload, 20000);
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


</style>