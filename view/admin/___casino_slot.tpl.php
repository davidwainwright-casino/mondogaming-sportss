<?php
  /**
   * Prematch Active Slips
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>



<div class="topingsg">
<h2>Casino, Slot & Games</h2>
<div class="rightcount">
<div id="slips_counts"></div>
</div>
</div>


<div class="filtersr">

<ul class="toptickets">
<li style="width:25%">
<select id="casinoRecords">
<option class="optionCasino" value="all">All Records</option>
<?php $sr = Db::run()->pdoQuery("SELECT game_name FROM sh_slot_casino_dealers GROUP BY game_name");
foreach($sr->aResults as $cas){
	?>
	<option class="optionCasino" value="<?php echo $cas->game_name;?>"><?php echo $cas->game_name;?></option>
<?php	
}
?>
</select>
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
      <input type="text" class="esearch" id="esearch" placeholder="Search by userID/agentID/transactionID..">
        <i id="findsr" class="icon find"></i>
 </div>
</li>

</ul>

</div>

<div id="ajax-content"></div>






<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 





<script>

	
	//active slips counts
	$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin-casino/slips_counts",
			data: {
			method:"casino_counts"
			},
			
	success: function(success) {
             $("#slips_counts").empty().append(success);
			 //console.log(success);
		}
	});
	
	
	//casino records
	function casinoRecords(numRecords, pageNum, coption) {
		if(coption == 'all'){
			var coption = 'all';
		}else{
			var coption = coption;
		}
		$("#ajax-content").empty().append("<div id='loading'></div>");
		$.ajax({
				type: "POST",
				url: "<?php echo SITEURL;?>/shell/admin-casino/casino_records",
				data: {
				method:'casino_records',
				show:numRecords,
				pagenum:pageNum,
				coption:coption,
				usid:'<?php echo $usid;?>'
				},
				success: function(response) {	
				$("#ajax-content").empty().append(response);
				//console.log(response);
				}
			});
	}
	function cchangeDisplayRowCount(numRecords) {
		var coption = $("input#copt").val();
		casinoRecords(numRecords, 1, coption);
	}
	
	//load casino default
	casinoRecords(50, 1, 'all');
	
	
	//switch games
	$('body').on('change', ' select#casinoRecords', function(){
    var $option = $(this).find('option:selected');
    var coption = $option.val();
	casinoRecords(50, 1, coption);
	});
	
	$('body').on('click', ' span.rgfound', function(){
		casinoRecords(50, 1, 'all');
	});
	
	
	//reset all casino values, add admin to 1
	$('body').on('click', ' span.resetTo', function(){
		$(this).html('Resetting...');
		var savthis = $(this);
		var conf = confirm("Warning! Do this only if you have already paid the commission on displayed amount else you will lose the records permanently.");
		if (conf == false) {
			$(this).html("Reset to Zero");
			return;
		}
		
		$.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin-casino/resetToZero",
			data: {
			method:'resetToZero'
			},
		   success: function(response) {
			$(savthis).empty().append(response);
			setTimeout(function(){
				location.reload();
				}, 2000);
               }
		    })
	})




	
	
	
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
				
	

	
	//search tickets casino
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
		    url: "<?php echo SITEURL;?>/shell/admin-casino/tickets_search",
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

  //date filter casino
    $('body').on('click', ' .addfilter', function(){
	  $('#ajax-content').html("Loading.......");
	      var dt1 = $('#my_date_picker1').val();
	      var dt2 = $('#my_date_picker2').val();
		   $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin-casino/date_search",
			data: {
			dt1:dt1,
			dt2:dt2,
			method:'casinodatefilter'
			},
		   success: function(response) {
			$("#ajax-content").empty().append(response);
               }
		      });
			   return false;
		});	  
				
				
				
				

</script>





<style type="text/css">
.yoyo.basic.table th {
    padding: 0.5em 0.1em!Important;
}
.lodo, .lodosingle {
    text-align: center;
    margin-left: 10px;
    font-size: 18px;
    color: #60e07b;
    font-weight: bold;
    cursor: pointer;
    background: #cfffa3;
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
    max-width: 96px;
    border: none;
    background: transparent;
    font-size: 16px;
	font-weight: bold;
    color: #009688;
}

.topingsg {
    background: #dcdcdc;
    padding: 10px;
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
	padding:5px;
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