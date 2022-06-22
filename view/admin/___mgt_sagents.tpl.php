<?php
  /**
   * Super Agents Management
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
      <h2>SUPER AGENTS/EXCHANGE BROKERS MANAGEMENT</h2>
      Super Agents or exchange brokers have specialized privileges such as ability to create new agents or transfer money from their super agent account to agents and other players directly..
  </div>
  <div class="columns shrink mobile-100"> <a href="/admin/users/new/" class="yoyo secondary button"><i class="icon plus alt"></i>Add New User</a> </div>
</div>









<div class="topingsg">
<h2>Registered SAgents stats</h2><hr>
<div class="rightcount">
<div id="slips_counts"></div>
</div>
</div>


<div class="filtersr">

<ul class="toptickets">
 <li style="width:25%">
<p>Country Wise Registration</p> 
<div class="cowise" id="ccwise">Check here</div>
</li>

<li style="width:30%"> 
Filter by registration date
<div class="filterdate">
		 <i id="frdate" class="icon calendar alt"></i><input type="text" id="my_date_picker1" value="Start">  
         <i id="todate" class="icon calendar remove"></i><input type="text" id="my_date_picker2" value="End">
 <div class="addfilter unset inplay">Add Filter</div>
</div>
</li>

<li style="width:44%">
Search Form</br>
<div class="searchsr">
      <input type="text" class="esearch" id="esearch" placeholder="Search by user ID or email..">
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
		    url: "<?php echo SITEURL;?>/shell/management/super_agents",
			data: {
			method:"userslist"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			
      }
    });
	
	//go back from country
	$('body').on('click', ' .gobacker', function(){
		$("#ajax-content").empty().append("<div id='loading'><img src='<?php echo SITEURL;?>/shell/images/ajax-loader.gif' alt='Loading' /></div>");
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/management/super_agents",
			data: {
			method:"userslist"
			},
			
	success: function(html) {
             jQuery("#ajax-content").empty().append(html);
			
      }
     });
	});
	
	
	
	//settled slips counts
	$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/management/users_stats",
			data: {
			method:"super_data"
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
			url: "<?php echo SITEURL;?>/shell/management/super_agents",
			type: "post",
			data: {
				rc:rowCount,
				method:"usersmore"
			},
			success: function (response) {
				$('.lodo').html('');
				 $("#ajax-content").append(response);
				 var fx = 50;
				 var cc =  parseInt(rowCount) +  parseInt(fx);
				 $('.ticketvalue').val(cc);
				 $('.hoper').html(cc);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	//search events
	 $("body").on('change', 'input#esearch', function() {
      var es = $("input#esearch").val(); 
	  if (es.length < 1){
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/management/super_agents",
			data: {
			method:"userslist"
			},
			
	     success: function(html) {
             $("#ajax-content").empty().append(html);
			}
		});
	
		  return;
	  }
	  var idt = $('.spnn').attr("id");
	  if (es.length < 1){
		  return;
	  }
	  
	  $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/management/super_agents",
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
				
	

	
	

  //date filter 
    $('body').on('click', ' .addfilter', function(){
	  $('#ajax-content').html("<div class='oftr'>Loading.......</div>");
	      var dt1 = $('#my_date_picker1').val();
	      var dt2 = $('#my_date_picker2').val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/management/super_agents",
			data: {
			dt1:dt1,
			dt2:dt2,
			method:'sdate'
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});
		
		
		
	//For country count
      $('body').on('click', ' #ccwise', function(){
		$("#ajax-content").html('Loading...');
		$.ajax({
			url: "<?php echo SITEURL;?>/shell/management/bycountry",
			type: "post",
			data: {
				method:"ccountry"
			},
			success: function (response) {
				$("#ajax-content").empty().append(response);
			 
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});


		

		
				
				
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	
				
				
				
	
	
	
	
	
	

});//end of doc
</script>





<style type="text/css">



ul.toptickets li {
    display: inline-block;
}
.filtersr {
    background: #fff;
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
    background: #fff;
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
    font-size: 20px;
	font-weight: bold;
    color: #F44336;
}

.topingsg {
    background: #d8d9de;
    padding: 10px;
}
input.ttcount.xp {
    color: #eb1515;
}


div#ajax-content {
    background: #fff;
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

.lodo {
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