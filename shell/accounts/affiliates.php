

<div id='chart_div'></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


    <script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart(){
    var jsonData = $.ajax({
        url: "<?php echo SITEURL;?>/shell/accounts/affiliates-data.php?usid=<?php echo $usid;?>",
        dataType:"json",
        async: false
}).responseText;

// Create our data table out of JSON data loaded from server.
var data = new google.visualization.DataTable(jsonData);

var options = {'title':'Ticket Sales',
'width':500,
'height':400};

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
chart.draw(data,options); 
}
    </script>