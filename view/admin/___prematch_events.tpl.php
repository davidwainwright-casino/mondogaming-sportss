<?php
  /**
   * Prematch Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>









<div id="ajax-content"></div>












<script>
$(document).ready(function() {	
//auto load sports

$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events.php",
			data: {
			method:"sportsv"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			
      }
    });
	
//onclick load all sports
$('body').on('click', ' .bktosport', function(){
	  $("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"sportsv"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			
      }
      });
	});
	
	
	
//SHOW ON LOAD MORE
$('body').on('click', ' .loadmo', function(){
		var rowCount = $('.cfvalue').val();
           var idt = jQuery('.spnn').attr("id");
		   $('.loadmo').html('');
		$(".addload").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				idt:idt,
				rc:rowCount,
				method:"fseventsmore"
			},
			success: function (response) {
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
  
  //onclick show sports events
  $('body').on('click', 'button', function () {
		var idt = jQuery(this).attr("id");
		$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				idt:idt,
				method:"fsevents"
			},
			success: function (response) {
				 $("#ajax-content").empty().append(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    });   
	 
	 
	 //is active
  $('body').on('click', '.isac', function () {
		var isa = jQuery('.ayes').text();
		var iname = $('.mainedit').attr("id");
		
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				isa:isa,
				iname:iname,
				method:"isact"
			},
			success: function (response) {
				$(".editv").trigger("click");
				 //$("#ajax-content").empty().append(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    });   
	
	
	 
	 //is featured
  $('body').on('click', '#isfeat', function () {
		var fsa = jQuery('.afyes').text();
		var fname = $('.mainedit').attr("id");
		
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				fsa:fsa,
				fname:fname,
				method:"isfeat"
			},
			success: function (response) {
				$(".editv").trigger("click")
				 //$("#ajax-content").empty().append(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    });   
	 
	 
	 
	  //onclick create new uvent
  $('body').on('click', '.addevt', function () {
		var bkspo = jQuery('.spnn').attr("id");
		$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				bkspo:bkspo,
				method:"addevt"
			},
			success: function (response) {
				 $("#ajax-content").empty().append(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    });   
	 
	 
	   //onclick show full events
  $('body').on('click', '.editv', function () {
		var edi = $(this).attr("id").replace("ed-", "");
		var spo = $(this).attr("class").replace("editv spk-", "");
		
		$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				edi:edi,
				spo:spo,
				method:"edi"
			},
			success: function (response) {
				 $("#ajax-content").empty().append(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    }); 
	
	
	
	//onclick show full events new event
  $('body').on('click', '.evit', function () {
		var evit = jQuery('.evit').attr("id");
		var spid = $('#spids option:selected').val();
		
		$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				edi:evit,
				spo:spid,
				method:"newedi"
			},
			success: function (response) {	
				 $("#ajax-content").empty().append(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    }); 
	

      
	  
	  
	  //Create new event
	$('body').on('click', '.addingev', function(){ 
	     var spid = $('#spids option:selected').val();
		 var spname = $("#spids option:selected").text();
		 var ligname = $('.eventnam').val();
		 var beventname = $('.eventbetnam').val();
		 var edate = $('.eventdt').val();
		 var ecc = $('.eventcc').val();
		 
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"addingev",
			spid:spid,
			spname:spname,
			ligname:ligname,
			beventname:beventname,
			edate:edate,
			ecc:ecc
			},
		success: function(html) {
			$('.evit').attr("id",html);
			$(".evit").trigger("click");
            // $('.addingev').html('<i class="icon check all"></i> Edit Events here');
			 
            }
    });
    return false;
    });
	

	
	
	
	//change odd on keyup
	
	$('body').on('change', '.optionna', function(){    
	     var osid = $(this).attr("id").replace("optodd-", "");
		 var odv = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
		 //alert(odv);
         $(this).empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"optionna",
			osid:osid,
			odv:odv
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	//change option name
	$('body').on('change', '.bename', function(){    
	     var beid = $(this).attr("id").replace("optodd-", "");
		 var bename = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"bename",
			beid:beid,
			bename:bename
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	
	//delete options
	$('body').on('click', '.odel', function(){    
	     var odil = $(this).attr("id");
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"odelete",
			odil:odil
			},
		success: function(html) {
			$(".editv").trigger("click");
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	//delete categories
	$('body').on('click', '.delcat', function(){    
	     var cdil = $(this).attr("id");
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"cdelete",
			cdil:cdil
			},
		success: function(html) {
			$(".editv").trigger("click");
			 
            }
    });
    return false;
    });
	
	
	//add options
	$('body').on('click', '.addop', function(){    
	     var okid = $(this).attr("id");
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"addopp",
			okid:okid
			},
		success: function(html) {
			$(".editv").trigger("click");
			$(this).css("color", "#f00");
			 
            }
    });
    return false;
    });
	
	
	
	
	//edit bet event name
	$('body').on('change', '.mevente', function(){    
	     var emi = $('.mainedit').attr("id");
		 var emn = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"mevente",
			emi:emi,
			emn:emn
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	//edit deadline
	$('body').on('change', '.meventd', function(){    
	     var emi = $('.mainedit').attr("id");
		 var emn = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"meventd",
			emi:emi,
			emn:emn
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	//edit event name
	$('body').on('change', '.meventl', function(){    
	     var emi = $('.mainedit').attr("id");
		 var emn = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"meventl",
			emi:emi,
			emn:emn
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	//edit cc
	$('body').on('change', '.meventc', function(){    
	     var emi = $('.mainedit').attr("id");
		 var emn = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"meventc",
			emi:emi,
			emn:emn
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
	
	

	
	
	//edit category name on keyup
	
	$('body').on('change', '.catedit', function(){    
	     var ctid = $(this).attr("id");
		 var ctname = $(this).val();
		 $('.succm').html('');
		 $('.succm').css('display', 'block');
		 //alert(odv);
         $(this).empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
         jQuery.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			method:"catedit",
			ctid:ctid,
			ctname:ctname
			},
		success: function(html) {
             $('.succm').html('<i class="icon check all"></i> Saved');
			 $(".succm").delay(3000).fadeOut("slow");
			 
            }
    });
    return false;
    });
	
	
//show new category field
	$('body').on('click', '.addcat', function () {
//Append a new row of code to the "#items" div
  $("#ajax-content").append('<div style="padding:10px" class="tyno">Type your category Name</br><input class="catna" type="text" palceholder="Type category name" required><div class="scat">Add Category</div></div><div class="csmsg"></div>');
  $(".addcat").css("display", "none");
});



	
		   //save new category
  $('body').on('click', '.scat', function () {
	  if( !$('.catna').val() ) {
          alert('Field Cannot be empty!');
		  return;
    }
		var catn = $('.catna').val();
		var evid = $('.addcat').attr("id").replace("catf-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				catn:catn,
				evid:evid,
				method:"addcat"
			},
			success: function (response) {
				$(".catna").css("display", "none");
				$(".tyno").css("display", "none");
				 $(".scat").html('<div style="color:#ffeb00;pointer-events:none;cursor:default;">Successfully Saved!</div>');
				 
				 var ho = $('.catna').val();
				  $("#ajax-content").append('<div class="opwrapper"><span class="nact">Category Name:</span> ' + ho + '<div class="ofnn"><b class="opc">Option Name</b> <input class="optionn" type="text" palceholder="Type category name"></div><div class="opoo"> <b class="opc">Option Odd</b> <input class="optionod" type="text" palceholder="Type category name"> <div class="soptions">Add Bet Option</div></div></div>');
				  $('.nact').attr("id",response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    }); 
	
	//search events
	 $("body").on('keyup', 'input#esearch', function() {
      var es = $("input#esearch").val(); 
	  if (es.length < 1){
		  $(".preevents.xp").empty();
		  return;
	  }
	  var idt = $('.spnn').attr("id");
	  if (es.length < 3){
		  return;
	  }
	  
	  $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			data: {
			es:es,
			idt:idt,
			method:"esearch"
			},
		success: function(data) {
			
             $("#esearchview").html(data);
			 
            }
    });
    return false;
	  
	  });
	
	
			   //save new bet options
  $('body').on('click', '.soptions', function () {
	  if( !$('.optionn').val() && !$('.optionod').val() ) {
          alert('Field Cannot be empty!');
		  return;
    }
		var opname = $('.optionn').val();
		var optionod = $('.optionod').val();
		var catid = $('.nact').attr("id");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch-events",
			type: "post",
			data: {
				opname:opname,
				optionod:optionod,
				catid:catid,
				method:"soptions"
			},
			success: function (response) {
				$(".catna").css("display", "none");
				$(".tyno").css("display", "none");
				$('.optionn').addClass('ioptionn').removeClass('optionn');
				$('.optionod').addClass('ioptionod').removeClass('optionod');
				$("input.ioptionn").prop('disabled', true);
				$("input.ioptionod").prop('disabled', true);
				$(".soptions").css("display", "none");
				
				 $(".scat").html('<div style="color:#ffeb00;pointer-events:none;cursor:default;">Successfully Saved!</div>');
				 
				 var ho = $('.catna').val();
				  $("#ajax-content").append('<div class="opwrapper"><span class="nact">Category Name:</span> ' + ho + '<div class="ofnn"><b class="opc">Option Name</b> <input class="optionn" type="text" palceholder="Type category name"></div><div class="opoo"> <b class="opc">Option Odd</b> <input class="optionod" type="text" palceholder="Type category name"> <div class="soptions">Add Bet Option</div></div></div>');
				  $('.nact').attr("id",response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}


		});
		
    }); 


});//end of doc
</script>





<style type="text/css">
.mainedit {
    border-bottom: 1px solid #FFC107;
    padding-bottom: 5px;
}
span.mevente, span.meventd, span.meventl, span.meventc {
    margin-right: 10px;
    border: 1px solid #e8e8e8;
    padding: 10px;
}
.nevent {
    padding: 20px;
}
.addingev {
    width: 260px!important;
    max-width: 260px!Important;
}
.addingev:hover {
    background: #004b69;
}
.yoyo.select {
    padding: 7px;
    max-width: 260px;
    width: 260px;
    min-width: 260px;
}
.nevent input[type="text"] {
    border: none;
    border-right: 4px solid #2196F3;
    padding: 6px;
    width: 260px;
    background: #f5f5f5;
}
.splabel {
    font-size: 13px;
    color: #8e8a8a;
}
.delcat, .addop {
    display: inline-block;
    cursor: pointer;
    color: #9e9e9e;
}
.delcat:hover, .addop:hover {
    color: #000;
}
.delcat {
    font-size: 14px;
    margin-right: 10px;
}
.addop {
    font-size: 14px;
}
.odel {
    float: right;
    /* position: absolute; */
    margin-right: -75px;
    /* border: 1px solid #000; */
    font-weight: bold;
    /* background: #f00; */
    color: #b1b1b1;
    margin-top: 3px;
    border-radius: 50%;
    /* padding: 0px 3px; */
    cursor: pointer;
    font-size: 5px!Important;
}
.odel:hover{
	color:#f00;
}
.catedit {
    width: 75%;
    border: none;
    font-size: 22px;
    box-shadow: none;
	margin-bottom:10px;
}

.opwrapper {
    padding: 10px;
    border: 1px solid #e8e8e8;
    background: #fff6f6;
}
.sxp {
    padding: 3px 10px;
    margin-top: -30px;
    border: 1px solid #1cffb1;
    margin-right: 10px;
    border-radius: 3px;
}
b.opc {
    width: 150px;
    display: inline-block;
    color: #59768e;
    font-weight: normal;
    font-size: 14px;
}
.ofnn {
    margin-bottom: 10px;
}
.nact {
    font-family: cursive;
    color: #0470c5;
    margin-bottom: 20px;
    display: inline-block;
    font-weight: bold;
}
input.optionn, input.optionod,input.ioptionn, input.ioptionod, input.catna {
    padding: 5px;
    border: 1px solid #eb1515;
    border-left: 5px solid #2196F3;
	width:250px
}

.soptions, .addcat, .scat, .addingev {
    background: #0085ba;
    border-color: #0073aa #006799 #006799;
    box-shadow: 0 1px 0 #006799;
    color: #fff;
    text-decoration: none;
    text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;
    /* display: inline-block; */
    text-decoration: none;
    font-size: 13px;
    line-height: 26px;
    height: 28px;
    margin: 0;
    text-align: center;
    margin-bottom: 10px;
    margin-top: 20px;
    max-width: 140px;
    padding: 0 10px 1px;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    -webkit-appearance: none;
    border-radius: 3px;
    white-space: nowrap;
    box-sizing: border-box;
}
span.lodmo {
    background: #bbb;
    padding: 5px 20px;
    margin-top: 30px;
    float: left;
    margin-bottom: 40px;
    border: 1px solid #04ab6a;
    border-radius: 3px;
    font-weight: bold;
    cursor: pointer;
}
span.lodmo:hover {
    background: #d4cd0a;
}
.succm {
    position: fixed;
    right: 0;
    display: block;
    color: #0aca56;
    font-weight: bold;
} 
.spnn{
	padding: 12px 10px 10px 20px;
    display: inline-block;
    float: left;
  }
.optionna {
    border: none;
    background: #e2dfdf;
    display: inline-block;
    max-width: 60px;
    text-align: center;
    float: right;
    margin-top: 3px;
	font-weight:bold;
}
.optionna:hover {
    background: #e4ff76;
}
.optiondiv {
    display: inline-block;
    margin-right: 20px;
    border-bottom: 1px solid #eb1515;
    width: 30%;
    border-top: 1px solid #eb1515;
	margin-bottom: 3px;
}

.bkev, .bktosport, .isac, .isfeat, .addevt {
    margin-top: 20px;
    display: inline-block;
    margin-left: 10px;
    box-shadow: inset 0px 1px 0px 0px #9acc85;
    background: linear-gradient(to bottom, #d0e079 5%, #d8d8d8 100%);
    background-color: #727d6c;
    border: 1px solid #b7b7b7;
    display:inline-block;
    cursor:pointer;
    color: #000000;
    font-family:Arial;
    font-size:13px;
    font-weight:bold;
    padding:6px 12px;
    text-decoration:none;
}
.bktosport{
    background:#FFEB3B!Important;
    padding:4px 20px!Important
}
a.addevt:hover {
    background: #02524a;
}
a.addevt {
    background: #009688;
    color: #fff;
}
.isac, .isfeat{
	background:none
}
.ayes:hover, .afyes:hover {
    color: #17d60a;
}
a.bktosport:hover{
    background:#03A9F4!Important;
}
.bkev:hover {
    background: #FFEB3B;
}
.ediview {
    margin-left: 10px;
}
span.bename {
    padding-left: 5px;
	font-size: 13px;
    font-family: sans-serif;
	background: #f0ecf7;
    padding: 2px;
    }
.preevents {
    padding: 20px;
}
span.bename:focus {
    background: #fff;
}

.editv {
    float: right;
    font-weight: bold;
    color: #009688;
    cursor: pointer;
}
.editv:hover {
    color: #f00;
}
.evsna {
    border-bottom: 1px solid #1ab942;
    padding: 3px;
    border-top: 1px solid #1ab942;
    margin-bottom: 3px;
}
.tilesWrap {
	padding: 0;
	margin: 50px auto;
	list-style: none;
	text-align: center;
}
.tilesWrap li {
	display: inline-block;
	width: 20%;
	min-width: 200px;
	max-width: 230px;
	padding: 80px 20px 40px;
	position: relative;
	vertical-align: top;
	margin: 10px;
	font-family: 'helvetica', san-serif;
	min-height: 25vh;
	background: #262a2b;
	border: 1px solid #252727;
	text-align: left;
}
.tilesWrap li h2 {
	font-size: 114px;
	margin: 0;
	position: absolute;
	opacity: 0.2;
	top: 50px;
	right: 10px;
	transition: all 0.3s ease-in-out;
}
.tilesWrap li h3 {
	font-size: 20px;
	color: #b7b7b7;
	margin-bottom: 5px;
}
.tilesWrap li p {
	font-size: 16px;
	line-height: 18px;
	color: #b7b7b7;
	margin-top: 5px;
}
.tilesWrap li button {
	background: transparent;
	border: 1px solid #b7b7b7;
	padding: 10px 20px;
	color: #b7b7b7;
	border-radius: 3px;
	position: relative;
	transition: all 0.3s ease-in-out;
	transform: translateY(-40px);
	opacity: 0;
	cursor: pointer;
	overflow: hidden;
}
.tilesWrap li button:before {
	content: '';
	position: absolute;
	height: 100%;
	width: 120%;
	background: #b7b7b7;
	top: 0;
	opacity: 0;
	left: -140px;
	border-radius: 0 20px 20px 0;
	z-index: -1;
	transition: all 0.3s ease-in-out;
	
}
.tilesWrap li:hover button {
	transform: translateY(5px);
	opacity: 1;
}
.tilesWrap li button:hover {
	color: #262a2b;
}
.tilesWrap li button:hover:before {
	left: 0;
	opacity: 1;
}
.tilesWrap li:hover h2 {
	top: 0px;
	opacity: 0.6;
}

.tilesWrap li:before {
	content: '';
	position: absolute;
	top: -2px;
	left: -2px;
	right: -2px;
	bottom: -2px;
	z-index: -1;
	background: #fff;
	transform: skew(2deg, 2deg);
}
i.icon.trash.alt:hover {
    color: #000;
}
i.icon.trash.alt {
    float: right;
    margin-top: -10px;
    font-size: 14px;
    color: #f00;
}
select#spids {
    border-radius: 3px;
    padding: 5px 10px;
	width:260px;
}
.evit {
    padding: 15px 10px;
    font-size: 24px;
    color: #178c6b;
}
.mainedit input {
    padding: 10px;
    border-radius: 3px;
    border: 1px solid #73ff2e;
    font-size:15px;
    font-weight:bold;
    color:#94a550;
}
.bename {
    border: none;
    padding: 3px 10px;
    width: 75%;
}
.tilesWrap li:after {
	content: '';
	position: absolute;
	width: 40%;
	height: 100%;
	left: 0;
	top: 0;
	background: rgba(255, 255, 255, 0.02);
}

.esearch {
    margin: 10px 20px;
    padding: 10px;
    width: 70%;
    border-radius: 3px;
    border: 2px solid #03A9F4;
}
i#findsr {
    font-size: 40px;
    position: absolute;
    margin-top: 10px;
    color: #c5c5c5;
}


.tilesWrap li:nth-child(1):before {
	background: #C9FFBF;
background: -webkit-linear-gradient(to right, #FFAFBD, #C9FFBF);
background: linear-gradient(to right, #FFAFBD, #C9FFBF);
}
.tilesWrap li:nth-child(2):before {
	background: #f2709c;
background: -webkit-linear-gradient(to right, #ff9472, #f2709c);
background: linear-gradient(to right, #ff9472, #f2709c);
}
.tilesWrap li:nth-child(3):before {
	background: #c21500;
background: -webkit-linear-gradient(to right, #ffc500, #c21500);
background: linear-gradient(to right, #ffc500, #c21500);
}
.tilesWrap li:nth-child(4):before {
	background: #FC354C;
background: -webkit-linear-gradient(to right, #0ABFBC, #FC354C);
background: linear-gradient(to right, #0ABFBC, #FC354C);
}
div#ajax-content, .nevent {
    min-height: 700px;
    clear: both;
    float: left;
    width: 100%;
	margin-bottom: 50px;
    /* display: block; */
    background: #fff;
    /* text-align: left; */
    position: relative;
}

@media screen and (max-width: 48.063em){
.excol.ileft {
    display: none;
}

.excol.icenter {
    width: 100%;
 }
}
</style>