<?php
  /**
   * mypage
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<meta http-equiv="refresh" content="600" >
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
      <div class="columns screen-100 tablet-100 mobile-100 phone-100 content-right  phone-content-left align-self-bottom">
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
<div id="master-loader">
  <div class="wanimation"></div>
  <div class="curtains left"></div>
  <div class="curtains right"></div>
</div>


  <div class="excol ileft">
   <div class="sportscol">
    <?php include_once('presidebar-sports.php');?>
   </div>	
  </div>
  
  
 
<div class="excol icenter">
<div class="onceload" id="statload">
<?php $event_id=$_GET['VwatchID'];if(!empty($event_id)):?>
<?php $ks = "SELECT * FROM af_pre_bet_events WHERE bet_event_id=$event_id";
	       $sr = Db::run()->pdoQuery($ks);
		   $betId = $sr->aResults[0]->bet_event_id;
		   $radar = $sr->aResults[0]->bradar;
		   $spid = $sr->aResults[0]->spid;
		   $en = $sr->aResults[0]->bet_event_name;
		   $league = $sr->aResults[0]->event_name;
		   $pl = explode('-', $en);
		   $team_home = $pl[0];
		   $team_away = $pl[1];
		   //GET LOCALTIME COOKIE
		   if(isset($_COOKIE['localtime'])) {
			   $oftime = $_COOKIE['localtime'] * 60;
			   }else{
				   $oftime = 0;
			}
		   $timer = $sr->aResults[0]->deadline - $oftime;
		   //set feat=7 is image already uploaded
		  $yes = $sr->aResults[0]->feat;
		   $img_home = $sr->aResults[0]->img_h_id;
		   $img_away = $sr->aResults[0]->img_a_id; 
		   
		   if(empty($yes)){
			if(!empty($betId)){
			$homeurl=file_get_contents('https://assets.b365api.com/images/team/m/'.$img_home.'.png');
			$h_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_home.'.png';
			$h_upload =file_put_contents($h_put_file, $homeurl);
			
			$awayurl=file_get_contents('https://assets.b365api.com/images/team/m/'.$img_away.'.png');
			$a_put_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/jersey/'.$img_away.'.png';
			$a_upload =file_put_contents($a_put_file, $awayurl);
			
			//update after insert is done;
			Db::run()->pdoQuery("UPDATE af_pre_bet_events SET feat = 7 WHERE bet_event_id=$betId");
			}
			   
		   }?>
   
  
   <div class="scoreDash">
    <div class="kso spo<?php echo $spid;?>" id="predashboard">
	 <div class="lbanner xp">
	 <a class="navback" id="nvvbk">
	 <i class="icon angle double left"></i> Back
	 </a>

	 <span class="toptr"><span class="sp_sprit sp<?php echo $spid;?>">!</span></span> <?php echo $league;?>
	 </div>
	 
	 
	   <div class="tlogowrap">
	     <div class="forhome">
		   <img src="<?php echo SITEURL;?>/uploads/jersey/<?php echo $img_home;?>.png" onerror="this.src='<?php echo SITEURL;?>/uploads/bet/blank_home.png'">
		   </div>
		   
		   <div class="forcenter xp">
		   <div class="ocoming">UPCOMING NEXT...</div>
		   </div>
		   
		   <div class="foraway">
		   <img src="<?php echo SITEURL;?>/uploads/jersey/<?php echo $img_away;?>.png" onerror="this.src='<?php echo SITEURL;?>/uploads/bet/blank_away.png'">
		   </div>
		</div>
		
		
		
		

<div class="scoreboard xp">
 <div id="sctopupdates" class="ort">
 <div class="deadtime">
<?php $etime=$timer; $tm = date ("y-m-d H:i", $etime);
$htm = date ("m-d", $etime);
$stm = date ("H:i", $etime);
$rem = strtotime($tm) - time();
$ntime = time() + 3600;
if($tm < date ("y-m-d H:i", $ntime)){
echo '<span class="stin">';	
echo 'Starts in </br>';	
$min = floor(($rem % 3600) / 60);
if($min) echo "$min Mins ";
echo '</span>';
}else{
 echo $htm; echo '</br>'; echo $stm;
}?>
</div>
  </div>
 </div>

		
<div class="bottomso">
<div class="commentryof"><?= Lang::$word->EXPERT_ANALYSIS; ?> <i class="icon chevron down"></i></div>

<div class="seefullof"><a id="preco" target="_blank" href="https://s5.sir.sportradar.com/bwin/en/match/<?php echo $radar;?>"><i class="icon bar chart"></i> <?= Lang::$word->HISTORICAL_STATS; ?></a>
  </div>
 </div> 
</div>
</div>


<?php else:?>
  <ul class="toppag">
  <li class="msports"><i class="icon ellipsis vertical"></i>Sports List</li>
  <li class="topev"><i class="icon widgets"></i> Top Events</li>
  <li class="toptoday"><i class="icon laptop"></i> Today</li>
  <li class="toptomorrow"><i class="icon history"></i> Tomorrow</li>
 </ul>
<?php endif;?>
</div>


<div id="ajax-content">
<div id="ajc"></div>
  </div>
 <div class="box stack-top" style="background: blue;"></div>
  
  
  
  
  
  
  
  <div class="supercover">
  <div class="slipcover">
  <div class="slip_wrapper">
  
    <div class="top-wrapper" style="display:none">
	 <div class="slip_container">
	   <input type="text" class="numcc" value="0" disabled><span class="cvib">Click Option to Add..</span><span class="closetop"><i class="icon unfold less"></i> Close</span>
	</div>

   <div class="_slip_wrapper" style="display:none">
    <div class="topbtn">
    	<div class="unsubtd">Ubsubmitted slips</div>
    	<a href="" id="delete_all_unsubmitted_bets"><i class="icon trash alt"></i><?= Lang::$word->; ?></a>
    </div>
	
	
	

	 <div class="_slip_container">
	 <div id="unsubmitted_slips" style="width: 100px,margin-top:20px">
		</div>
		</div>
		  <div class="cbtn">
			<div class="number xp">
			  <span class="minus">-</span>
			   <input type="number" id="stake_value" value="0"/>
			  <span class="plus">+</span>
			</div>
		  <div class="vbtn">		
				<span class="ereturn">E. Return</span>
				 <input type="text" class="preview" name="0.00" id="return_value" value="0.00" disabled="">
                <span class="ereturn">Est. Risk</span>
                 <input type="text" class="preview xp" name="0.001" id="risk_value" value="0.00" disabled="">
		  </div>
		</div>
       <div class="text-center">
		<button class="place_bet_button" id="submit_bet">Submit Bet</button>
       </div>
	</div>
  </div>
  </div>
  <input type="hidden" class="ty" id="bet_type">
 </div>
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
	
//function to load/unload scoreboard
function loadScore(){
	//load scores div
	setTimeout(function(){
	$(".onceload").load(" #statload");
	}, 500);
}
			  
//load sports event on click
 $('body').on('click', '.prelist li', function(){
	   var spid = $(this).attr("id");
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");
	   $('a', this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
			contentType: "application/x-www-form-urlencoded;charset=utf-8",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_sports',
			spid:spid
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preREF=' + spid);
				}
				loadScore();
            }
    });
    return false;
    });
	

	//auto load events on visit
	        let searchParams = new URLSearchParams(window.location.search);
			var spid = searchParams.get('preREF');
			var vevent = searchParams.get('VwatchID');
			var vspid = searchParams.get('preREF');
			var vlig = searchParams.get('preLIG');
			var vtd = searchParams.get('preTT');
			var vtw = searchParams.get('preTOM');
			//var default = searchParams.get('default');
			
			function loadDefault(){
	        $("#ajax-content").empty().append("<div id='loading'></div>");
		     $.ajax({
			 type: "POST",
		     url: "<?php echo SITEURL;?>/shell/exchange/prematch_view",
			 data: {
			  usid:<?php echo $usid;?>,	
			  method2:'do_events',
			  evid:vevent
			  },
		     success: function(html) {
			  $('#ajax-content').removeClass("rload");
              $("#ajax-content").empty().append(html);
               }
		      });
			}
			
		if(vevent != null) {
		      loadDefault();
	         } else if(vlig != null){
		   $("#ajax-content").empty().append("<div id='loading'></div>");	 
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_league',
			ligid:vlig
			},
	     	success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preLIG=' + vlig);
				}
            }
        }); 
				 
	   } else if(vspid != null){
		   $("#ajax-content").empty().append("<div id='loading'></div>");	 
			$.ajax({
			type: "POST",
			cache: false,
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_sports',
			spid:vspid
			},
		  success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preREF=' + vspid);
				}
            } 
			 
	   });
	 } else if(vtd != null){
		  $("#ajax-content").empty().append("<div id='loading'></div>");	
		  $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_today'
			},
		success: function(html) {
			$('li.toptoday').css("color", "red");
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preTT=today');
				}
            }
    }); 
		 
	 } else if(vtw != null){
		 $("#ajax-content").empty().append("<div id='loading'></div>");	
		 $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_tomorrow'
			},
		success: function(html) {
			$('li.toptomorrow').css("color", "red");
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preTOM=tomorrow');
				}
            }
       });
	 } else {
	        $("#ajax-content").empty().append("<div id='loading'></div>");
			$('li.topev').css("color", "red");
	        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_default',
			usid:<?php echo $usid;?>
			},
		success: function(html) {
			$('li.topev').css("color", "red");
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
            }
     });
   };



//Load top events
 $('body').on('click', 'li.topev', function(){
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");
	   $(this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_default'
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null);
				}
            }
    });
    return false;
    });
	
	
	//Load today
 $('body').on('click', 'li.toptoday', function(){
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");
	   $(this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_today'
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preTT=today');
				}
            }
    });
    return false;
    });
	
	
	//Load tomorrow
 $('body').on('click', 'li.toptomorrow', function(){
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");
	   $(this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_tomorrow'
			},
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preTOM=tomorrow');
				}
				
            }
    });
    return false;
    });
	
	//Load sports for mobile
 $('body').on('click', 'li.msports', function(){
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");
	   $(this).css("color", "red");
	   $('.excol.ileft').css("display","block");

    });
	
	$("body *:not(li.msports)").click(function() {
		 $('.excol.ileft').css("display", "");
		 $('ul.toppag li').css("color","");
	});
	
	
	
	
	
	//Load by league
 $('body').on('click', '.event-name', function(){
	   var ligid = $(this).attr("id");
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");	  
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_league',
			ligid:ligid
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preLIG=' + ligid);
				}
            }
    });
    return false;
    });
	
	
	
	//Load by cc
 $('body').on('click', '.prcc', function(){
	   var cc = $(this).attr("id");
	   $('ul.toppag li').css("color","");
	   $('li a').css("color","");	  
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_cc',
			cc:cc
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			  $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?preCC=' + cc);
				}
            }
    });
    return false;
    });
	
	
	
	
	//Load full event
 $('body').on('click', '.onevent', function(){
	   var evid = $(this).attr('id');
	   $('#ajax-content').addClass("rload");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/prematch_view",
			data: {
			method2:'do_events',
			usid:<?php echo $usid;?>,
			evid:evid
			},
		
		
		success: function(html) {
			$('#ajax-content').removeClass("rload");
             jQuery("#ajax-content").empty().append(html);
			 //$("#ajax-content").scrollTop($("#ajax-content")[100].scrollHeight);
			 $('html').scrollTop(0);
			 if (history && history.pushState){
				 history.pushState(null, null, '?VwatchID=' + evid);
				}
				//load scores div
				loadScore();
            }
    });
    return false;
    });
	
	$('a#cor-1366930792').css("opacity", "0.1");
	
	
	
	//For category laod
 $('body').on('click', ' ul.omarkers li', function(){
	   var catid = $(this).attr('id');
	   $('ul.omarkers li').removeClass("active");
	   var evname = $('.evidf').text();
	   var evidf = $(".evidf").attr("id");
	   var savthis = $(this);
	    $("#fetchcat").empty().append("<div id='loading'></div>");
        $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/fetch_prematch",
			data: {
			method:'football_cat',
			catid:catid,
			usid:<?php echo $usid;?>,
			evid:evidf,
			evname:evname
			},
		success: function(response) {
             $("div#fetchcat").empty().html(response);
			 $(savthis).addClass('active');
            }
    });
    return false;
    });
	
	
	
	
	

//back button ajax 
$(window).on("popstate", function getpop() {
	let searchParams = new URLSearchParams(window.location.search);
	var spid = searchParams.get('preREF');
	var ligid = searchParams.get('preLIG');
	var cc = searchParams.get('preCC');
	var tt = searchParams.get('preTT');
	var tom = searchParams.get('preTOM');
	var evd = searchParams.get('VwatchID');
      // var spid = $_GET['preREF'];
	  
	   $('li a').css("color","");
	   $('a', this).css("color", "red");
	   $('#ajax-content').addClass("rload");
        jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			data: {
			method:'do_back',
			method2:'do_events',
			spid:spid,
			ligid:ligid,
			cc:cc,
			tt:tt,
			tom:tom,
			evid:evd
			},
		success: function(html) {
			 $('#ajax-content').removeClass("rload");
             $("#ajax-content").empty().append(html);
			 //load scores div
				loadScore();
			 
            }
    });
    return false;
    });
	
	//back button ajax  text version
$('body').on('click', ' .navback', function(ev){
	ev.preventDefault();
    window.history.back();
    });


//SHOW ON LOAD MORE
$('body').on('click', ' .loadmo', function(){
		var rowCount = $('.cfvalue').val();
           var spid = $(".datalink.xp").attr("id");
		   $('.loadmo').css({'padding' : '0px','border' : 'none'});
		   $('.loadmo').html('');
		$(this).html('Wait a Moment!');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/exchange/main_prematch_default",
			type: "post",
			data: {
				spid:spid,
				rc:rowCount,
				method:"lomore"
			},
			success: function (response) {
				$('.loadmo').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
					}
 
				 $("#ajax-content").append(response);
				 var fx = 120;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.cfvalue').val(cc);
				 $(".addload").empty().append('');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}
		});
});


//onclick show top leagues
$('body').on('click', ' .shall', function(){
	$('ul.sptoplig').addClass('shall');
	$(this).html('Hide more');
	$(this).addClass('hid');
});

$('body').on('click', ' .shall.hid', function(){
	$('ul.sptoplig').removeClass('shall');
	$(this).html('Show more..');
	$(this).removeClass('hid');
});












//cashout
$('body').on('click', ' span.casout', function(){
	$(this).text('cashing..');
	var slid = $(this).attr('id');
	var odd = $(this).attr("class").replace("casout mo-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/exchange/cashout",
			type: "post",
			data: {
				slid:slid,
				odd:odd,
				method:"cashout"
			},
			success: function (response) {
				$("#" + slid).html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}
		});
	
});


//plus minus input
        $('.minus').click(function () {
				var $input = $(this).parent().find('input#stake_value');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				$("#stake_value").trigger("keyup");
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input#stake_value');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				$("#stake_value").trigger("keyup");
				return false;
		});

    //color slip
    setTimeout(function() {
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      });

      var numbet = $('.ccounter').length; 
       $('.numcc').val(numbet);
	   if($('input#bet_type').val() === 'lay'){
		   $('._slip_container').css("background", "#ffc8c8");
		   };
		   
		  if(numbet > 1){
		   $('._slip_wrapper').css("display", "none");
		   //$('.top-wrapper').attr("style", "display: block !important");
		   $('.top-wrapper').addClass('shw');
		   $('.slipcover').css("bottom", "54px");
		   $('.closetop').text('Open');
		   }
		  if(numbet == 1){
		   $('.top-wrapper').css("display", "block");
		   $('._slip_wrapper').css("display", "block");
		   }
		   
	}, 1000); // <-- time

   
     

   //close slip on click
   $('body').on('click', ' .closetop', function(){
	$('._slip_wrapper').css("display", "none");
	$('.top-wrapper').addClass('shw');
	$('.closetop').text('Open');
	$('.slipcover').css("bottom", "54px");
	});
	
	//show slip on click
	$('body').on('click', ' .top-wrapper.shw', function(){
	$('._slip_wrapper').css("display", "block");
	$('.top-wrapper').css("display", "block");
	$('.top-wrapper').removeClass('shw');
	$('.closetop').text('Close');
	$('.slipcover').css("bottom", "0px");
	});







  var totalOdss=0;
  var resturn_est=$('#return_value');
  var bet_type_field=document.querySelector('#bet_type');
  var risk_value=$('#risk_value');
  showUnSubmittedSlip();
	// $("body").on('DOMSubtreeModified', ".b_option_odd", function() {
	//     // alert('changed');
	// });
	
	
	
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

	
	
	

  $('#stake_value').on('keyup',(event)=>{
    const stake=event.target.value;
    if(bet_type_field.value=='lay'){
      var lat = stake * totalOdss;
      //resturn_est.val(lat.toFixed(2));
	  var rsk = (totalOdss-1)*stake;
	  var lat = rsk * totalOdss;
	   resturn_est.val(lat.toFixed(2));
      risk_value.val(rsk.toFixed(2));
    }else if (bet_type_field.value=='back'){

      risk_value.val(stake)
	  var tk = stake*totalOdss;
      resturn_est.val(tk.toFixed(2));
    }
  });
  


	
	

	$('body').on('click', 'div[id^=bet__option__btn]', function (event) {
		if(event.target.className=="bbackk" || event.target.className=="blayy"){
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
        var event_name=bet_info[6];
        var cat_name=bet_info[7];
        var event_id=bet_info[4];
        var cat_id=bet_info[5];
        var oid = bet_info[3];
        var bod=bet_info[8];
        var lod=bet_info[9];
		var spid=bet_info[10];
		var xbk=bet_info[11];
		var xly=bet_info[12];
    		var oname = $(this).attr("class").replace("b_option_odd evn-", "");
    		odd=0;
			bodd=0;
    		if(event.target.className=="bback" || event.target.className=="bbacker"){
    			var btype = 'back';
    			odd=bod;
				sodd=xbk;
				$('.unsubtd').html('Type:<b>Back</b>');
    		} else if(event.target.className=="blay" || event.target.className=="blayer"){
    			var btype = 'lay';
    			odd=lod;
				sodd=xly;
				$('.unsubtd').html('Type:<b>Lay</b>');
    		}
    		var getUrl = window.location;
    		var ff = getUrl.origin;
    		var baseurl = getUrl.origin + '/shell/exchange/add_bet';
    		$(".cvib").empty().append("Loading slip...");
  			$.ajax({
  			url: baseurl,
  			type: "post",
  			data: {
  				oid:oid,
  				event_name:event_name,
  				cat_name:cat_name,
  				event_id:event_id,
  				cat_id:cat_id,
  				oname:oname,
  				btype:btype,
  				odd:odd,
				sodd:sodd,
				spid:spid,
				aid:'<?php echo $afid;?>',
  				usid:<?php echo $usid;?>,
  				save_bet:'save_post'
  			},
  			success: function (response) {
				$(".bback.activatorL").removeClass("activatorL");
				$(".blay.activatorLay").removeClass("activatorLay");
  				showUnSubmittedSlip();
				$('.cvib').html(response);
  				setTimeout(function() {
					var numbet = $('.ccounter').length;
					$('.numcc').val(numbet);
					if($('input#bet_type').val() === 'lay'){
						$('._slip_container').css("background", "#ffc8c8");
						};
				   if(numbet > 1){
					   $('._slip_wrapper').css("display", "none");
					   $('.top-wrapper').addClass('shw');
					   $('.closetop').text('Open');
					  } 
					}, 1000);
					
	  setTimeout(function() {
		$("a.delete_single_bet").each(function(e) {
		    var gtid = $(this).attr('id');
			if($('.sopt').attr('id') === 'lay'){
	       $("#corx-" + gtid).addClass("activatorLay");
			} else {
			$("#cor-" + gtid).addClass("activatorL");	
			}
	      });
		  triggerInput();
		  }, 1000); // <-- time
					
  			},
  			error: function(jqXHR, textStatus, errorThrown) {
  			  // console.log(textStatus, errorThrown);
  			}

  		}); 
	        
	});

	function showUnSubmittedSlip(){
		totalOdss=1
		var unsubmitted_slips_container=$('._slip_container');
		unsubmitted_slips_container.html("");
		var getUrl = window.location;
    	var baseurl = getUrl.origin + '/shell/exchange/add_bet';
		$.ajax({
  			url: baseurl,
  			type: "post",
  			dataType: "json",
  			data: {
  				get_unsubmitted_slip:'get_unsubmitted_slip',
  				user_id:<?php echo $usid;?>
  			},
  			success: function (response) {
  				var html="";
  				$.each(response,(key,value)=>{
  					if(value){
              bet_type_field.value=value.type;
	  					totalOdss=totalOdss*parseFloat(value.odd);
	  					html=`<div class="ccounter" id="item" style="width: 100%,border:1px green solid;padding-right: 3px;padding-left: 3px">
						 			<div class="sopt" id="`+value.type+`">
						 			<p class="eventnn">%event_name%</p>
						 			<a href="" class="delete_single_bet" title="Delete current unsubmitted bet" id="%bet_id%">X</a>
						 			</div>
						 			<p class="catnn" style="font-weight: 700,margin:0">%cat_name%</p>
						 			<div class="sbtslip">
						 				<p class="betnn" style="margin:0,font-size: 14px">%bet_name%</p>
						 				<span class="slip-bet-odd" style="background-color:#22A25C;padding-right: 7px;padding-left: 7px;height: 23px;border-radius: 10%;color:white">%bet_odd%</span>
						 			</div>
								</div>`;
  						html=html.replace('%event_name%',value.event_name);
  						html=html.replace('%cat_name%',value.cat_name);
  						html=html.replace('%bet_name%',value.bet_option_name);
  						html=html.replace('%bet_odd%',value.sodd);
						//setTimeout(html=html.replace('%bet_id%',value.bet_option_id), 2000);
  						html=html.replace('%bet_id%',value.bet_option_id);
  	  				unsubmitted_slips_container.append(html);
	  					unsubmitted_slips_container.append("<hr>");
  					}
  				})
  			},
  			error: function(jqXHR, textStatus, errorThrown) {
  			   //console.log(textStatus, errorThrown);
  			}
		});
	}

	//listener to delete single unsubmitted bet
	document.querySelector('._slip_container').addEventListener('click',function(event){
		event.preventDefault();
		var getUrl = window.location;
    	var baseurl = getUrl.origin + '/shell/exchange/add_bet';
    	const bet_id=event.target.id;
		if(event.target.className=='delete_single_bet'){
			$('._slip_wrapper').css("opacity", "0.5");
			$('.cvib').html('<a style="color:#fff">Deleting selection...</a>');
				$.ajax({
	  			url: baseurl,
	  			type: "post",
	  			data: {
	  				bet_id:bet_id,
	  				usid:<?php echo $usid;?>,
	  				delete_single_bet:'delete_single_bet'
	  			},
	  			success: function (response) {
	  				showUnSubmittedSlip();
	  				$('._slip_wrapper').css("opacity", "");
				    $('.cvib').html(response);
					setTimeout(function() {
						var numbet = $('.ccounter').length; 
						$('.numcc').val(numbet);
						//color slip
						if($('input#bet_type').val() === 'lay'){
							$('._slip_container').css("background", "");
							}
						}, 1000);
						$("#cor-" + bet_id).removeClass("activatorL");
						$("#corx-" + bet_id).removeClass("activatorLay");
						 
						
	  			},
	  			error: function(jqXHR, textStatus, errorThrown) {
	  			   //console.log(textStatus, errorThrown);
	  			}

	  		}); 
		}
	});

	//listener to delete all unsubmitted slips
	document.querySelector('#delete_all_unsubmitted_bets').addEventListener('click',function(event){
		event.preventDefault();
		$('._slip_wrapper').css("opacity", "0.5");
		$('.cvib').html('<a style="color:#fff">Deleting all slips...</a>');
		var getUrl = window.location;
    	var baseurl = getUrl.origin + '/shell/exchange/add_bet';
		$.ajax({
			url: baseurl,
			type: "post",
			data: {
				usid:<?php echo $usid;?>,
				delete_all_unsubmitted_bets:'delete_all_unsubmitted_bets'
			},
			success: function (response) {
				showUnSubmittedSlip();
				$('._slip_wrapper').css("opacity", "");
				$('.cvib').html(response);
					setTimeout(function() {
						var numbet = $('.ccounter').length; 
						$('.numcc').val(numbet);
						//color slip
						if($('input#bet_type').val() === 'lay'){
							$('._slip_container').css("background", "");
							}
						}, 1000);
						$(".bback.activatorL").removeClass("activatorL");
				        $(".blay.activatorLay").removeClass("activatorLay");
						
						
						
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}

		}); 
		
	})

	document.querySelector('#submit_bet').addEventListener('click',function(event){
		var getUrl = window.location;
    	var baseurl = getUrl.origin + '/shell/exchange/add_bet';
    	const stake=parseFloat(document.querySelector('#stake_value').value);	
		var svalue = $("#stake_value").val();
		if(svalue < 1){
		  $('.place_bet_button').text('Submit Bet');
		  $('.cvib').html('Stake cannot be Empty');
			return;
		}
		$('.place_bet_button').addClass('stop');
		$('.place_bet_button').css('pointer-events','none');
		$(".modelwrap, #ajax-content, ._slip_wrapper").css("pointer-events", "none");
		$("._slip_container").css("opacity", "0.5");
		
		$('.place_bet_button').html('<div class="ofslide">.</div><div class="lds-hourglass"></div> Matching bet...');
		myTim1= setTimeout(function () {
		$.ajax({
			url: baseurl,
			type: "post",
			data: {
				total_odd:parseFloat(totalOdss),
				stake:stake,
				usid:<?php echo $usid;?>,
				submit_bet:'submit_bet'
			},
			success: function (response) {
				$(".modelwrap, ._slip_wrapper, #ajax-content").css("pointer-events", "");
				$("._slip_container").css("opacity", "");
				showUnSubmittedSlip();
				$('.cvib').html(response);
				$('.place_bet_button').text('Submit Bet');
				$(".bback.activatorL").removeClass("activatorL");
				$(".blay.activatorLay").removeClass("activatorLay");
				
				$('.place_bet_button').css('pointer-events','');
				setTimeout(function(){ 
					if($.trim($("div._slip_container").html())==''){
						$("input.numcc").val("0");
					inputZero();
					$.ajax({
			 type: "POST",
		     url: "<?php echo SITEURL;?>/shell/exchange/prematch_view",
			 data: {
			  usid:<?php echo $usid;?>,	
			  method2:'do_events',
			  evid:'<?php echo $_GET["VwatchID"];?>'
			  },
		     success: function(html) {
			  $('#ajax-content').removeClass("rload");
              $("#ajax-content").empty().append(html);
               }
		      });
					$("div._slip_container").html("Click option to add bet");
					
					 }
					}, 2000);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   //console.log(textStatus, errorThrown);
			}

		});
		}, 4500);
		
	});

	
</script>

		





<style type="text/css">
li.nav-item.men86 {
    background: #e5e5e5;
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