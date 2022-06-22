<?php
  /**
   * Language Manager
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
      <h2>EARNINGS & TICKETS STATS</h2>
      Here you can see the overall earnings of all the players. You can also find tickets and performance analytics of each players to tab at their consistency and responds accordingly.
  </div>
  <div class="columns shrink mobile-100"> <a href="#" class="yoyo secondary button"><i class="icon plus alt"></i>New Reports</a> </div>
</div>


  <div class="column" id="item_fr">
 
  
  
  
  
  
  
  
  <div class="yoyo" style="padding:10px">
  
       <h3>IN-PLAY TICKETS</h3>
      <div class="yoyo divider"></div>
      
        <div class="columns">
		  <div id='chart_div1' style="max-width:100%"></div>
        </div>
		
		<div id="shinplay"></div>
     
  </div>
  
  
  </div>
  
  
  
  
  
  
  
  <script>
  	//for front chart	
/*google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart(){
    var jsonData = $.ajax({
        url: "<?php echo SITEURL;?>/shell/admin-stats/tickets_stats",
        dataType:"json",
        async: false
}).responseText;

// Create our data table out of JSON data loaded from server.
var data = new google.visualization.DataTable(jsonData);

var options = {'title':'Last 15 Days Daily Bet Volume (Stake Volume x Day)'};

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
chart.draw(data,options); 
}
*/

  	//for INPLAY admin chart	
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart1);

function drawChart1(){
    var jsonData1 = $.ajax({
        url: "<?php echo SITEURL;?>/shell/admin-stats/tickets_stats_inplay",
        dataType:"json",
        async: false
}).responseText;

// Create our data table out of JSON data loaded from server.
var data1 = new google.visualization.DataTable(jsonData1);

var options1 = {'title':'Last 15 Days Daily Bet Volume (Stake Volume x Day)'};

// Instantiate and draw our chart, passing in some options.
var chart1 = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
chart1.draw(data1,options1); 
}


//showonclick settled slips
$('body').on('click', ' a#revstat', function(){
	$('.shstats').css('display', 'inline-block');
	$('a#revstat').addClass('hovst');
	$('a#revstat').html('Collapse..');
});

$('body').on('click', ' .hovst', function(){
	$('.shstats').css('display', 'none');
	$('a#revstat').removeClass('hovst');
	$('a#revstat').html('Expand..');
});


//showonclick players
$('body').on('click', ' a#playstat', function(){
	$('.rankplay').css('display', 'inline-block');
	$('a#playstat').addClass('hovstd');
	$('a#playstat').html('Collapse..');
});

$('body').on('click', ' .hovstd', function(){
	$('.rankplay').css('display', 'none');
	$('a#playstat').removeClass('hovstd');
	$('a#playstat').html('Expand..');
});


//for inplayyyyyyyyy
//showonclick settled slips
$('body').on('click', ' a#revstatx', function(){
	$('.shstatsx').css('display', 'inline-block');
	$('a#revstatx').addClass('hovstx');
	$('a#revstatx').html('Collapse..');
});

$('body').on('click', ' .hovstx', function(){
	$('.shstatsx').css('display', 'none');
	$('a#revstatx').removeClass('hovstx');
	$('a#revstatx').html('Expand..');
});


//showonclick players
$('body').on('click', ' a#playstatx', function(){
	$('.rankplayx').css('display', 'inline-block');
	$('a#playstatx').addClass('hovstdx');
	$('a#playstatx').html('Collapse..');
});

$('body').on('click', ' .hovstdx', function(){
	$('.rankplayx').css('display', 'none');
	$('a#playstatx').removeClass('hovstdx');
	$('a#playstatx').html('Expand..');
});


/*

//prematch data
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin-stats/sh_prematch_data",
			data: {
			method:"shprematch"
			},	
	success: function(html) {
             jQuery("#shprematch").empty().append(html);
       }
	  });
	*/  
	  
//inplay data
      $.ajax({
			type: "POST",
		    url: "<?php echo SITEURL;?>/shell/admin-stats/sh_inplay_data",
			data: {
			method:"shprematch"
			},	
	success: function(html) {
             jQuery("#shinplay").empty().append(html);
       }
	  });	  
</script>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  <style>
  .shpre {
    width: 32.6%;
    display: inline-block;
	color: #adadad;
}
  span.cotfig {
    font-size: 20px;
    line-height: 30px;
    font-weight: bold;
    display: block;
	color: #0f80d0;
  }
  
  .yoyo.segment {
    width: 50%;
    float: left;
	}
  span.cotfig.xi {
    color: #d200e0;
	}
	span.cotfig.te {
    font-size: 20px;
	}
.shstats, .rankplay, .shstatsx, .rankplayx {
    display: none;
	width:100%;
 }
a#revstat, a#playstat, a#revstatx, a#playstatx {
    float: right;
    margin-top: 15px;
}
span.cotfig.xid {
    font-size: 22px;
    color: #9a51bf;
}
  
div#item_fr {
    background: #fffbfb;
}  
  
  
  
  
  
  
  
  </style>