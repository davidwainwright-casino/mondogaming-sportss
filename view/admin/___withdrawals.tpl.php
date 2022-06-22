<?php
  /**
   * Withdrawals
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row half-gutters align-middle">
  <div class="column mobile-100">
      <h2>WITHDRAWALS</h2>
      Withdrawals records both pending, processed and history of all users. Pending or Processing are given as well in one count. The format of stats at top are given as: Total sum/No. of deposit rows. Eg. 300.00/3. Clicking on 'process' will make the status processing. Cancel button will cancel the request. Mark as Processed will mean you have completed the transaction.
  </div>
  <div class="columns shrink mobile-100"> <a href="/admin/users/new/" class="yoyo secondary button"><i class="icon plus alt"></i>Add New User</a> </div>
</div>


<div class="topingsg">
<h2>Withdrawals Summary [Amount/No. of Rows]</h2><hr>
<div class="rightcount">
<div id="slips_counts"></div>
</div>
</div>


<div class="filtersr">
 <ul class="toptickets">
 <li style="width:25%">
<p>Total Records</p> 
<div class="countrec">0</div>
</li>

<li style="width:30%"> 
Filter by Deposit date
<div class="filterdate">
		 <i id="frdate" class="icon calendar alt"></i><input type="text" id="my_date_picker1" value="Start">  
         <i id="todate" class="icon calendar remove"></i><input type="text" id="my_date_picker2" value="End">
 <div class="addfilter unset inplay">Add Filter</div>
</div>
</li>

<li style="width:44%">
Search Form</br>
<div class="searchsr">
      <input type="text" class="esearch" id="esearch" placeholder="Search by user ID,status,tnx_id,type etc..">
        <i id="findsr" class="icon find"></i>
 </div> <span class="binme" title="Move selected slips to Trash"><i class="icon trash alt"> Delete</i></span>
</li>

</ul>

</div>

<div id="ajax-content"></div>






<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 

<script>
$(document).ready(function() {	
//auto load sports

let searchParams = new URLSearchParams(window.location.search);
			var vevent = searchParams.get('usid');
			
		if(vevent != null) {
$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			data: {
			usid:vevent,	
			method:"xpcreditrec"
			},
			
	success: function(html) {
             $("#ajax-content").empty().append(html);
			 var ccom = $('.foncounter').html();
			 $('.countrec').html(ccom);
			 $('.lodo').addClass('lodos');
			 $('.lodo').removeClass('lodo');
			 
          }
        });
		
		$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/with_txt_data",
			data: {
			usid:vevent,	
			method:"players_performance"
			},
			
	success: function(success) {
             $("#slips_counts").empty().append(success);
		}
	});
		
		
		//SHOW ON LOAD MORE USERS
      $('body').on('click', ' .lodos', function(){
		var rowCount = $('.hoper').html();
		$('.lodos').html('');
		$(this).html('Loading...');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			type: "post",
			data: {
				rc:rowCount,
				usid:vevent,
				method:"xpusersmore"
			},
			success: function (response) {
				$('.lodos').html('');
				 $("#ajax-content").append(response);
				 var fx = 100;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.ticketvalue').val(cc);
				 $('.hoper').html(cc);
				 $('.lodo').addClass('lodos');
			     $('.lodo').removeClass('lodo');
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	 //date filter
     $('body').on('click', ' .addfilter', function(){
	  $('#ajax-content').html("<div class='oftr'>Loading.......</div>");
	      var dt1 = $('#my_date_picker1').val();
	      var dt2 = $('#my_date_picker2').val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			data: {
			dt1:dt1,
			dt2:dt2,
			usid:vevent,
			method:'xpalldates'
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
			 var ccom = $('.foncounter').html();
			 $('.countrec').html(ccom);
               }
		      });
			   return false;
		});
		
		


	} else {

	$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			data: {
			method:"creditrec"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			 var ccom = $('.foncounter').html();
			 $('.countrec').html(ccom);
			 
          }
        });
		
		$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/with_txt_data",
			data: {
			method:"withdraws_data"
			},
			
	success: function(success) {
             $("#slips_counts").empty().append(success);
		}
	});
	
		
		//SHOW ON LOAD MORE USERS
      $('body').on('click', ' .lodo', function(){
		var rowCount = $('.hoper').html();
		$('.lodo').html('');
		$(this).html('Loading...');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			type: "post",
			data: {
				rc:rowCount,
				method:"usersmore"
			},
			success: function (response) {
				$('.lodo').html('');
				 $("#ajax-content").append(response);
				 var fx = 100;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.ticketvalue').val(cc);
				 $('.hoper').html(cc);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	 //date filter
     $('body').on('click', ' .addfilter', function(){
	  $('#ajax-content').html("<div class='oftr'>Loading.......</div>");
	      var dt1 = $('#my_date_picker1').val();
	      var dt2 = $('#my_date_picker2').val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			data: {
			dt1:dt1,
			dt2:dt2,
			method:'alldates'
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
			 var ccom = $('.foncounter').html();
			 $('.countrec').html(ccom);
               }
		      });
			   return false;
		});
	  
	  //end of elseif


		
	}
	
	
	
	
	
	
	//search events
	 $("body").on('change', 'input#esearch', function() {
      var es = $("input#esearch").val(); 
	  if(es.length < 1){
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			data: {
			method:"creditrec"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			 var ccom = $('.foncounter').html();
			 $('.countrec').html(ccom);
			 
          }
        });
	return;
	  }
		  $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/adm/payments/withdraws",
			data: {
			es:es,	
			method:"searchlist"
			},
			
	     success: function(html) {
             $("#ajax-content").empty().append(html);
			 var ccom = $('.foncounter').html();
			 $('.countrec').html(ccom);
			}
		});
    return false;
	  
	  });
	
			
	  //SHOW PROCESS/PROCESSING
      $('body').on('click', ' .processtx', function(){
		$(this).html('Loading...');
		$(this).addClass('xp');
		var pid = $(this).attr('id');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/payments/with_txt_data",
			type: "post",
			data: {
				pid:pid,
				method:"processtx"
			},
			success: function (response) {
				$('.processtx.xp').html('Done');
				$('.processtx.xp').removeClass('processtx');
				
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//SHOW cancel
      $('body').on('click', ' .canceltx', function(){
		$(this).html('Loading...');
		$(this).addClass('xp');
		var pid = $(this).attr('id');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/payments/with_txt_data",
			type: "post",
			data: {
				pid:pid,
				method:"canceltx"
			},
			success: function (response) {
				$('.canceltx.xp').html('Done');
				$('.canceltx.xp').removeClass('canceltx');
				
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	//SHOW PROCESS/PROCESSING
      $('body').on('click', ' .markrec', function(){
		$(this).html('Loading...');
		$(this).addClass('xp');
		var pid = $(this).attr('id');
		var usid = $(this).attr("class").replace("markrec usid-", "");
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/adm/payments/with_txt_data",
			type: "post",
			data: {
				pid:pid,
				usid:usid,
				method:"markrec"
			},
			success: function (response) {
				$('.markrec.xp').html(response);
				$('.markrec.xp').removeClass('markrec');
				
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	$('body').on('click', ' i.icon.info.sign', function(){
	    var sid = $(this).attr('id');
		$('#po' + sid).slideToggle("fast");
		
		//$(this).addClass('xp');
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
        var isDelete = confirm("Do you really want to delete? You cannot undo once deleted.");
        if (isDelete == true) {
           // AJAX Request
           $.ajax({
              url: '<?php echo SITEURL;?>/shell/adm/payments/withdraws',
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
	
	
	//colored on click
	$('body').on('click', ' .delop', function(){
	var chk =  $("input[type=checkbox]:checked").length; //$(this).is(":checked").length;
	if(chk > 0){
	$('.binme').css("color", "#f00");
	} else {
	$('.binme').css("color", "");	
	}
	});
	
	
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
				
	
	
	
	
	
	

});//end of doc
</script>





<style type="text/css">


tr.multibs {
    background: #f3f1f1;
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

.processtx:hover {
    color: #181b1a;
}
.processtx {
    color: #002af7;
}
.canceltx:hover {
    color: #000;
}
.markrec {
    color: #eb1515;
    font-weight: bold;
}
.markrec:hover {
    color: #1d1c1c;
}
a.fbReceived, a.fbProcessed {
    color: #eb1515;
    font-weight: bold;
}
a.fbProcessing {
    color: #0d34f5;
    font-weight: bold;
}

.amountcrt {
    font-size: 90%;
    background: #eeffda;
    padding: 3px 10px;
    border: 1px solid #ccda42;
    border-radius: 3px;
    position: absolute;
    color: #000;
	display:none;
}

i.icon.info.sign:hover {
    cursor: pointer;
    color: #f00;
}

















ul.toptickets li {
    display: inline-block;
}
.filtersr {
    background: #fdfdfd;
}
ul.toptickets {
    border-radius: 3px;
    padding: 20px 10px;
    border: 1px solid #bdbdbd;
}
.ttype {
    margin-right: 10px;
    background: #fff3f3;
}
ol.ollist {
    padding: 0px;
}

.binme {
    display: inline-block;
    cursor: pointer;
	float:right;
}
.searchsr {
    display: inline-block;
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
    max-width: 100px;
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
    max-width: 200px!Important;
	background:#fff;
    margin: 0 auto;
    
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
    border: 1px solid #dedede;
	height:30px;
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
    font-size: 13px;
    font-weight: bold;
    color: #d2004d;
}

.topingsg {
    background: #d8d9de;
    padding: 10px;
}
input.ttcount.xp {
    color: #eb1515;
}


div#ajax-content {
    background: #fff6f8;
    border: 1px solid #d0d0d0;
}
span.bachips {
    position: absolute;
    font-size: 20px;
    margin: -5px 5px 0px 10px;
    color: #009688;
}

.showmo {
    padding: 10px 15px 0px 15px;
    color: #bfbfbf;
}
.hoper {
    max-width: 80px;
    border: none;
    font-weight: bold;
}

.lodo,.lodos {
    margin: 0px 10px 35px 15px;
    background: #f3f0f0;
    display: inline-block;
    padding: 4px 15px;
	cursor:pointer;
}

div#ui-datepicker-div {
    padding: 10px;
}
.timefram {
    padding: 10px 20px;
}
.oftr {
    padding: 20px;
    min-height: 600px;
}

.hopperf {
    background: #ecffd5;
    display: inline-block;
    max-width: 360px;
    width: 100%;
}
.gobacker {
    cursor: pointer;
    float: right;
    padding: 3px;
    background: #f00;
    color: #fff;
}
.gobacker:hover {
    background: #000;
}
div#ccwise {
    background: #2196F3;
    display: inline-block;
    font-size: 13px;
    color: #fff;
    padding: 0px 5px;
    border-radius: 3px;
    cursor: pointer;
}
div#ccwise:hover {
    background: #1463a2;
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