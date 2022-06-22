<?php
  /**
   * Prematch Settled Slips
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>





<div class="topingsg">
<h2>Pre-Match Settled Tickets</h2>
<div class="rightcount">
<div id="slips_counts"></div>
</div>
</div>


<div class="filtersr">

<ul class="toptickets">
 <li style="width:25%">
<p>Filter BY</p> 
<a class="ttype" id="all" href="">Reset</a>
<a class="ttype xp" id="allevents">All</a>
<a class="ttype xp" id="back">Back</a>
<a class="ttype xp" id="lay">Lay</a>
<a class="ttype xp" id="sbook">Sportsbook</a>
</li>

<li style="width:30%"> 
Filter by date
<div class="filterdate">
		 <i id="frdate" class="icon calendar alt"></i><input type="text" id="my_date_picker1" value="Start">  
         <i id="todate" class="icon calendar remove"></i><input type="text" id="my_date_picker2" value="End">
 <div class="addfilter unset inplay">Add Filter</div>
</div>
</li>

<li style="width:44%">
Search Form</br>
<div class="searchsr">
      <input type="text" class="esearch" id="esearch" placeholder="Search by Slip/User ID or Event Name..">
        <i id="findsr" class="icon find"></i>
 </div>
</li>

</ul>

</div>

<div id="ajax-content"></div>












<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 

<script>
$(document).ready(function() {	
//auto load sports

$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/prematch_settled_set",
			data: {
			method:"prematch_active"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			 $('a#all').css({"color": "#eb1515", "font-weight": "bold"});
			
      }
    });
	
	//settled slips counts
	$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/slips_counts",
			data: {
			method:"pre_settled_counts"
			},
			
	success: function(success) {
             $("#slips_counts").empty().append(success);
		}
	});
	
	
	
	
	//SHOW ON LOAD MORE SET
      $('body').on('click', ' .lodo', function(){
		var rowCount = $('.hoper').val();
		var kvl = $(this).attr('id');
		$('.lodo').html('');
		$(this).html('Loading...');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch_settled_set",
			type: "post",
			data: {
				rc:rowCount,
				method:"prelodo"
			},
			success: function (response) {
				$('.lodo').html('');
				if(response.trim().length == 0){
					$(".addload").html('');
					return;
				}
 
				 $("#ajax-content").append(response);
				 var fx = 100;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.ticketvalue').val(cc);
				 $(".addload").empty().append('');
				 $('.hoper').val(cc);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
 //SHOW LOAD MORE on single
      $('body').on('click', ' .lodosingle', function(){
		var rowCount = $('.hoper').val();
		var lomo = $('.addload').attr('id');
		$(this).html('Loading...');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch_settled_single",
			type: "post",
			data: {
				rc:rowCount,
				lomo:lomo,
				method:"prelodo"
			},
			success: function (response) {
				$('.lodosingle').html('');
				if(response.trim().length == 0){
					$(".lodosingle").html('');
					return;
				}
 
				 $("#ajax-content").append(response);
				 var fx = 100;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.ticketvalue').val(cc);
				 $(".addload").empty().append('');
				 $('.hoper').val(cc);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});	
	
	
	
	
	
	
	//For filter by type
      $('body').on('click', ' .ttype.xp', function(){
		var ttype = $(this).attr('id');
		$('.ttype').css({"color": "", "font-weight": ""});
		$("#ajax-content").html('Loading...');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/prematch_settled_single",
			type: "post",
			data: {
				ttype:ttype,
				method:"typesingle"
			},
			success: function (response) {
				$("#ajax-content").empty().append(response);
				$('#' + ttype).css({"color": "#eb1515", "font-weight": "bold"});
			 
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	
	
	///SETTLEMENT
	
	//Prematch win
      $('body').on('click', ' a.pwin', function(){
		var afc = jQuery(this).attr("class").replace("pwin pr-", "");
		var winid = jQuery(this).attr("id").replace("pwin-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/pre_winning",
			type: "post",
			data: {
				afc:afc,
				winid:winid,
				method:"updateStatus"
			},
			success: function (response) {
				jQuery(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//Prematch Lose
      $('body').on('click', ' a.plose', function(){
		var afc = jQuery(this).attr("class").replace("plose pr-", "");
		var winid = jQuery(this).attr("id").replace("plose-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/pre_losing",
			type: "post",
			data: {
				afc:afc,
				winid:winid,
				method:"updateStatus"
			},
			success: function (response) {
				jQuery(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//Prematch canceled
      $('body').on('click', ' a.pcan', function(){
		var afc = jQuery(this).attr("class").replace("pcan pr-", "");
		var winid = jQuery(this).attr("id").replace("pcan-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/pre_canceled",
			type: "post",
			data: {
				afc:afc,
				winid:winid,
				method:"updateStatus"
			},
			success: function (response) {
				jQuery(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//Prematch multibet winning
      $('body').on('click', ' a.mwin', function(){
		var winid = $(this).attr("class").replace("mwin pr-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/multi_winning",
			type: "post",
			data: {
				afc:'pre',
				id:winid,
				method:"updateStatus"
			},
			success: function (response) {
				jQuery(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//Prematch multibet losing
      $('body').on('click', ' a.mlose', function(){
		var winid = $(this).attr("class").replace("mlose pr-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/multi_losing",
			type: "post",
			data: {
				afc:'pre',
				id:winid,
				method:"updateStatus"
			},
			success: function (response) {
				jQuery(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//Prematch multibet canceled
      $('body').on('click', ' a.mcan', function(){
		var winid = $(this).attr("class").replace("mcan pr-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/multi_losing",
			type: "post",
			data: {
				afc:'pre',
				id:winid,
				method:"updateStatus"
			},
			success: function (response) {
				jQuery(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	
	
	
	
	
	
	
	//prematch REVERT
  $('body').on('click', ' a.winlosei', function(){
		var winid = $(this).attr("id").replace("winlose-", "");
		var afc = $(this).attr("class").replace("winlosei afc-", "");
		var stt = $(".revert-" + winid).attr('id');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/revert",
			type: "post",
			data: {
			 id:winid,
			 afc:afc, 
			 stt:stt,
			 method:"updateStatus"
			},
			success: function (response) {
				$(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	//prematch multibet REVERT
  $('body').on('click', ' a.winlosem', function(){
		var winid = jQuery(this).attr("id");
		var stt = jQuery(this).attr("class").replace("winlosem sta-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/settlement/revert_multi",
			type: "post",
			data: {
			 id:winid,
			 stt:stt,
			 typ:'pre',
			 method:"updateStatus"
			},
			success: function (response) {
				$(".pmsg-"+ winid).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	
	
   //DELETE PREMATCH Slips
   $('body').on('click', ' .binme', function(){
    var post_arr = [];
    // Get checked checkboxes
    $('input[type=checkbox]').each(function() {
      if ($(this).is(":checked")) {
        var id = this.id;
        var splitid = id.split('_');
        var postid = splitid[1];
        post_arr.push(postid);  
      }
    });
    if(post_arr.length > 0){
        var isDelete = confirm("Do you really want to delete records?");
        if (isDelete == true) {
           // AJAX Request
           $.ajax({
              url: '<?php echo SITEURL;?>/shell/admin/del_pre_slips',
              type: 'POST',
              data: { 
			   post_id: post_arr,
			   method:"binme"   
			  },
              success: function(response){
                 $.each(post_arr, function( i,l ){
                     $("#tr_"+l).remove();
                 });
              }
           });
        } 
    } else {
		alert('Please select rows to trash slip');
		return;
	}
  });
	
	
//color trash icon on click
$('body').on('click', ' #recordsTable input[type=checkbox]', function(){
	var chk =  $("#recordsTable input[type=checkbox]:checked").length; //$(this).is(":checked").length;
	if(chk > 0){
	$('.binme').css("color", "#f00");
	} else {
	$('.binme').css("color", "");	
	}
	
});	


//settled actions/deletes
  $('body').on('click', ' .tdrk', function(){
		var delres = jQuery(this).attr("id");
		//alert('Do you really want to proceed?');
		var conf = confirm("Do you really want to proceed?");
		if (conf == false) {
			return;
		}
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/admin/del_pre_slips",
			type: "post",
			data: {
			 delres:delres,
			 method:"drestore"
			},
			success: function (response) {
				$("#"+ delres).html('Done!');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	
	//date picker
		 $(function() { 
                    $("#my_date_picker1").datepicker({}); 
                }); 
  
                $(function() { 
                    $("#my_date_picker2").datepicker({}); 
                }); 
  
                $('#my_date_picker1').change(function() { 
                    startDate = $(this).datepicker('getDate'); 
                    $("#my_date_picker2").datepicker("option", "minDate", startDate); 
                }) 
  
                $('#my_date_picker2').change(function() { 
                    endDate = $(this).datepicker('getDate'); 
                    $("#my_date_picker1").datepicker("option", "maxDate", endDate); 
                });
				
	

	
	//search events
	 $("body").on('change', 'input#esearch', function() {
      var es = $("input#esearch").val(); 
	  if (es.length < 1){
		  $("#ajax-content").html('Try with new term or Fetch all events');
		  return;
	  }
	  var idt = $('.spnn').attr("id");
	  /*if (es.length < 3){
		  return;
	  }*/
	  
	  $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/pre_settled_slips_search",
			data: {
			es:es,
			idt:idt,
			method:"esearch"
			},
		success: function(data) {
			
             $("#ajax-content").html(data);
			 
            }
    });
    return false;
	  
	  });

  //date filter settled prematch
    $('body').on('click', ' .addfilter', function(){
	  $('#ajax-content').html("Loading.......");
	      var dt1 = $('#my_date_picker1').val();
	      var dt2 = $('#my_date_picker2').val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin/pre_settled_slips_datesearch",
			data: {
			dt1:dt1,
			dt2:dt2,
			method:'inplaydatefilter'
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});	  
				
				
				
				
				
	
	
	
	
	
	

});//end of doc
</script>





<style type="text/css">
.yoyo.basic.table th {
    padding: 0.5em 0.1em!Important;
}
.lodo, .lodosingle {
    text-align: center;
    margin-left: 10px;  
    font-size: 20px;
    color: #043ee2;
    cursor: pointer;
    background: #b4f767;
    max-width: 140px;
    border-radius: 3px;
	margin: 20px 10px;
}
.yoyo.basic.table td {
    line-height: 16px;
}
.lodo:hover, .lodosingle {
    color: #ff0349;
 }
 .hoper {
    display: inline-block;
    text-align: center;
    max-width: 50px;
    border: none;
    color:#f00;
}
.showmo {
    padding: 10px;
    border-bottom: 1px solid #FFEB3B;
}
span.terback {
    background: #36888c;
    color: #fff;
    padding: 0px 2px;
    border-radius: 2px;
}
span.terlay {
    background: #d23c51;
    color: #fff;
    padding: 0px 2px;
    border-radius: 2px;
}
span.tersbook {
    background: #6ba001;
    color: #fff;
    padding: 0px 2px;
    border-radius: 2px;
}
ul.toptickets li {
    display: inline-block;
}
.filtersr {
    background: #fff;
}
ul.toptickets {
    padding: 10px;
}
.ttype {
    margin-right: 10px;
    background: #fff3f3;
}
ol.ollist {
    padding: 0px;
}
tr.multibs {
    background: #bce1ff;
    color: #000;
}
a.pwin,a.mwin {
    background: #eb1515;
    color: #fff;
    padding: 0px 3px;
}
a.plose,a.mlose {
    background: #F44336;
    color: #fff;
    padding: 0px 3px;
}
a.pcan,a.mcan {
    background: #0e0e0e;
    color: #fff;
    padding: 0px 3px;
    margin: 0px 2px;
}
td.tdset{
	min-width:80px
}
a.pwin:hover, a.plose:hover, a.pcan:hover {
    background: #3F51B5;
}
a.winlosei, a.winlosem {
    background: #0095ff;
    color: #fff;
    padding: 1px 6px;
    border-radius: 3px;
}
a.winlosei:hover, a.winlosem:hover {
    background: #f00;
}

.binme {
    display: inline-block;
    cursor: pointer;
}
.binmecov {
    display: inline-block;
    float: right;
	font-size:65%;
}
span.trashal {
    margin: 0px 15px;
}
span.emptyal {
    margin-right: 15px;
}
.tdrk {
    color: #b1aeae;
    cursor: pointer;
}
.tdrk:hover {
    color: #f00;
}

input#my_date_picker1, input#my_date_picker2 {
    padding-left: 28px;
    border: none;
    cursor: pointer;
    max-width: 80px;
    background: #FFEB3B;
    display: inline-block;
}
.filterdate {
    color: #fff;
}

i#frdate, i#todate {
    color: #000;
    padding-left: 5px;
    padding-top: 3px;
}

input#my_date_picker1, input#my_date_picker2 {
    height: 25px;
    max-width: 130px;
    text-transform: capitalize;
    padding-left: 25px;
    border-radius: 3px;
}

div#ui-datepicker-div {
    padding: 10px;
    background: #f9f6a9;
}

span.ui-icon.ui-icon-circle-triangle-e {
    float: right;
}
i#frdate {
    float: left;
    margin-top: 2px;
    font-size: 21px;
    position: absolute;
}
i#todate {
    position: absolute;
    margin-top: 1px;
    font-size: 21px;
}
.addfilter {
    display: inline-block;
    background: #eb1515;
    padding: 2px 7px;
    font-size: 12px;
    color: #fff;
    border-radius: 3px;
    cursor: pointer;
}

.addfilter:hover {
    background: #000;
}
input#esearch {
    padding: 2px 7px;
    width: 300px;
    border-radius: 3px;
    border: 1px solid #CDDC39;
}
i#findsr {
    position: absolute;
    margin-top: 5px;
    padding-left: 10px;
}
i#frdate:hover, i#todate:hover {
    visibility: hidden;
}
input.ttcount {
    max-width: 100px;
    border: none;
    background: transparent;
    font-size: 20px;
	font-weight: bold;
    color: #009688;
}

.topingsg {
    background: #dcdcdc;
    padding: 10px;
}
input.ttcount.xp {
    color: #eb1515;
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
	padding:5px;
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