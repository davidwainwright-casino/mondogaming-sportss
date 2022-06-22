<?php
 /**
 * Affiliates
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */
 
 if (!defined("_YOYO"))
 die('Direct access to this location is not allowed.');
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php if(App::Auth()->is_User()){
 $usid = App::Auth()->uid;
 $aid = Auth::$udata->afid;
 $afbal = Auth::$udata->afbal;
 }
 //get data from other sources
 include_once('affiliate_data.php');
 $count_aff = Db::run()->pdoQuery("SELECT count(id) AS caf FROM users WHERE afid = '$usid' AND id <> '$usid'");
 ?>
 
 
 
<div class="yoyo-grid">
 <div id="jinxaff">

 <div class="rowaff">

<div class="colaff left" style="width:200px">

 <div id="mySidenav" class="sidenav">
 <div class="toptextleft">
 Stats may appear delayed at times. Figures are subject to re-adjustment, if any.
 </div>

 <a style="color:#fff;border:none" href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
 <a id="dashboards" class="dcolor" href="/dashboard/affiliates/?pg103=aff"> Dashboard</a>
 <div id="affnav">
 <a id="allplayers" href="#"> Affiliates List</a>
 <a id="playersslips" href="#"> Tickets Highlights</a>
 <a id="performances" href="#"> Performances</a>
 <a id="blockedplayers" href="#"> Blocked Players</a>
 <a id="toolsmarketing" href="#"> Tools & Marketing</a>
 <a id="withdrawals" href="#"> Withdrawals</a>
 <a id="printreports" href="#"> Print Reports</a>
 <a id="sssupports" href="#"> Support</a>
 </div>
 </div>
</div>
 
 
 
 
 
 
 <div class="colaff right" style="width:73%">
 <div id="aj-fetch"> <!----aj div----->
 <ul class="topdash">
 <li class="dashleft">Dashboard</br><span class="dtext">Your affiliate dashboard</span></li>
 <li class="dashright af">Affiliates</br> <i class="icon user alt"></i> <span class="afbb"><?php $cc = $count_aff->aResults[0]->caf; echo $cc;?></span></li>
 <li class="dashright">Commission</br> <i class="icon bar chart"></i> <span class="afbb">$<?php echo $afbal;?></span></li>
 </ul>
 
 <!-------------------For users numbers------------------------------>
 <ul class="topdash unu">
 <li class="dashleft">Today Registered</br><i class="icon user alt"></i> <span class="afbb"><?php echo $ctoday['rtoday'];?></span></li>
 <li class="dashr af">Total Active Users</br> <i class="icon user alt"></i> <span class="afbb"><?php echo $ctoday['ractive'];?></span></li>
 <li class="dashr">Pending Users</br> <i class="icon user alt"></i> <span class="afbb"><?php echo $ctoday['rinactive'];?></span></li>
 
 <li class="dashleft">Today's Earning <i title="It's Not a final Commission" class="icon question sign"></i></br><i class="icon bar chart"></i> <span class="afbb">$<?php echo round($net_commission, 2);?></span></li>
 <li class="dashr af">Cleared Commission</br> <i class="icon bar chart"></i> <span class="afbb">$<?php echo $afbal;?></span></li>
 <li class="dashr"> Pending Volume <i title="It's a total volume before settlement" class="icon question sign"></i></br> <i class="icon bar chart"></i> <span class="afbb">$<?php echo $pending_volume;?></span></li>
 </ul>
 
 
 
 
 
 <div id='chart_div' style="max-width:100%"></div>
 
 <div class="recentreg">
 <div id="bkaw">Recently Registered Affiliates</div>
 <table id="customers">
 <tr>
 <th>No.</th>
 <th>Country</th>
 <th>Full Name</th>
 <th>Joined</th>
 </tr>
 <?php $shusers= "SELECT id,fname,lname,created,country FROM users WHERE id <> '$usid' AND afid = '$aid' ORDER BY created DESC LIMIT 5";
$urs = Db::run()->pdoQuery($shusers);
//var_dump($shslips);
$i = 1;
foreach ($urs->aResults as $record) {?>
 <tr>
 <td><?php echo $i++;?></td>
 <td><?php $cc = $record->country; if(!empty($cc)){ echo $cc;}else{ echo 'Pending';};?></td>
 <td><?php echo $record->fname; echo ' '; echo $record->lname;?></td>
 <td> <?php echo $record->created;?></td>
 </tr>
<?php } ?>
 
 
 </table>
 
 </div>
 
 
</div> <!---- end of ajax div------> 
 </div>
 
 </div>
 </div>
</div>





























 <script>
 //different tabs on click
 $('body').on('click', ' #affnav a', function(){
 $('#aj-fetch').html("<div id='loading'></div>");
 $('#mySidenav a').removeClass('dcolor');
 $(this).addClass('dcolor');
 $('#aj-fetch').addClass("dbg");
 var meth = $(this).attr('id'); 
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav.php",
data: {
usid:<?php echo $usid;?>,
method:meth
},
 success: function(response) {
 $("#aj-fetch").html('');
 $("#aj-fetch").append(response);
 }
 });
 return false;
});

 //load more players list
$('body').on('click', ' .afload', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav.php",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'allpmore'
},
 success: function(response) {
 $('.afload').html('');
 $("#aj-fetch").append(response);
 var fx = 30;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});



//copy to clipboard
function myFunction() {
 /* Get the text field */
 var copyText = document.getElementById("myInput");

 /* Select the text field */
 copyText.select();
 copyText.setSelectionRange(0, 99999); /*For mobile devices*/

 /* Copy the text inside the text field */
 document.execCommand("copy");

 /* Alert the copied text */
 alert("Copied!");
 }
 
 //copy iframe code
function copyToClipboard(element) {
 var $temp = $("<input>");
 $("body").append($temp);
 $temp.val($(element).text()).select();
 document.execCommand("copy");
 alert('Copied');
 $temp.remove();
 }

 //close withdraw form
 $('body').on('click', ' .payclose', function(){
$('#manwithdraw').css("background", "");
$("#mancashback").empty();
});

 //show withdrawal form
 $('body').on('click', ' #manwithdraw', function(){
 $('#mancashback').html('Loading...');
 $('#manwithdraw').css("background", "");
 $('#manwithdraw').css("background", "#eb1515");
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws.php",
data: {
usid:<?php echo $usid;?>,
method:'manwithdraw'
},
 success: function(response) {
 $('#mancashback').html('');
 $("#mancashback").empty().append(response);
 }
 });
 return false;
});

//withdrawal request submit
$('body').on('click', ' .wrequest', function(){
 $('.sherr').html('Loading...');
 var paytype = $('#paymenttype').val();
 var amount = $('#mamount').val();
 var acnum = $('#acnum').val();
 var ref = $('#tref').val();
 if (amount < 100){
 $('.sherr').text('Amount cannot be empty or less than 100');
 return;
 }
 if (ref.length > 100){
 $('.sherr').text('Maximum 100 characters allowed');
 return;
 }
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws.php",
data: {
usid:<?php echo $usid;?>,
amount:amount,
acnum:acnum,
ref:ref,
paytype:paytype,
method:'werr',

},
 success: function(response) {
 $("._mandeposit").html(response);
 }
 });
 return false;
});

//delete withdrawal pending
$('body').on('click', ' span.wpending', function(){
 var idto = $(this).attr('id');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws.php",
data: {
usid:<?php echo $usid;?>,
idto:idto,
method:'wpending'
},
 success: function(response) {
 $("li.idto-" + idto).html("Successfully Deleted");
 }
 });
 return false;
});

 //show more withdrawal history
 $('body').on('click', ' .wload', function(){
 var rowCount = $('.cfvalue').val();
 $(this).html('Loading...');
 $.ajax({
type: "POST",
 url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav.php",
data: {
rc:rowCount,
usid:<?php echo $usid;?>,
method:'lwithdraw'
},
 success: function(response) {
 $('.wload').html('');
 $("#aj-fetch").append(response);
 var fx = 20;
 var cc = parseInt(rowCount) + parseInt(fx);
 $('.cfvalue').val(cc);
 }
 });
 return false;
});

 //print report

function PrintPanel() {
 var panel = document.getElementById("printTable");
 var printWindow = window.open('', '', '');
 printWindow.document.write('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Print Invoice</title>');
 
 // Make sure the relative URL to the stylesheet works: 
 // Add the stylesheet link and inline styles to the new document:
 printWindow.document.write('<style type="text/css">ul.printme { margin: 0px; background: #f5f5f5; max-width: 360px; margin: 0 auto; padding: 10px; border-left: 3px solid #2196F3; } ul.printme li { display: inline-block; width: 100%; border-bottom: 1px solid #bdbdbd; padding: 5px 0px; } span.printright { float: right; background: #d4f377; width: 70px; text-align: center; color: #000; border-radius: 3px; } li.ccomm { background: #dcdcdc; font-weight: bold; }p.didcenter { margin: 0 auto; max-width: 360px; padding-bottom: 10px; }</style>');
 
 printWindow.document.write('</head><body >');
 printWindow.document.write(panel.innerHTML);
 printWindow.document.write('</body></html>');
 printWindow.document.close();
 setTimeout(function () {
 printWindow.print();
 }, 500);
 return false;
};

$('body').on('click', ' #printbtnx', function(){
PrintPanel();
})


 






//for front chart
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

var options = {'title':'<?= Lang::$word->AFF_REGISTRATION_CHART; ?>'};

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
chart.draw(data,options); 
}


//Open close navigation
function openNav() {
 document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
 document.getElementById("mySidenav").style.width = "0";
}


 </script>






<style>

main{background:#fff}#jinxaff{max-width:760px;margin:0 auto;background:#eaeaea}*{box-sizing:border-box}.colaff{float:left}.rowaff{display:flex;display:-webkit-flex}.rowaff:after{content:"";display:table;clear:both}.colaff.right{margin-left:5px;border-left:1px solid #afacac}.toptextleft{margin:5px;font-size:13px;background:#c5ff82;padding:5px;line-height:16px;color:#128a17;margin-bottom:20px;border-radius:3px;border-left:2px solid}.sidenav{height:100%;z-index:9999;background-color:#fff;overflow-x:hidden;transition:.5s;width:200px;border-right:1px solid #b3b3b3}.sidenav a{padding:8px 5px 8px 10px;text-decoration:none;font-size:16px;color:#949494;display:block;border-bottom:1px solid #d6d6d6;transition:.3s}.sidenav a:hover{color:#d8d0d0}.sidenav .closebtn{position:absolute;top:0;right:25px;font-size:36px;margin-left:50px}ul.topdash li{display:inline-block;font-size:14px}ul.topdash{padding:10px;margin:0;width:100%;background:#fff;border-bottom:1px solid #bfbfbf;margin-bottom:10px}li.dashright{float:right;padding:0 10px}span.dtext{font-size:12px;color:#b1a6a6}span.afbb{font-size:17px;font-weight:700;font-family:arial}li.dashright.af{border-left:1px solid #ececec}ul.topdash.unu li{width:32.8%;text-align:center;border:1px solid #b3babf;border-radius:3px;background:#f9f9f9;margin:4px 0}#customers{border-collapse:collapse;width:100%;font-size:15px}#customers td,#customers th{border:1px solid #f5f5f5;padding:4px;text-align:center;color:#716f6f}#customers tr:nth-child(even){background-color:#fffef0}#customers tr:hover{background-color:#ddd}#customers th{background-color:#f4fbff;color:#84bbff}.recentreg{background:#fff;padding:10px;margin-top:10px;border-top:1px solid #d2d2d2}#bkaw{padding:10px;font-weight:700;color:#00d45e}a.dcolor{color:#2196f3;font-weight:700}#aj-fetch.dbg{background:#fff;height:100%;padding-bottom:80px}.headtit{font-size:1.5rem;font-weight:500;font-family:inherit;color:#1e2022;line-height:1.4;padding:10px;border-bottom:5px solid #e4e4e4}div#loadaf{padding:0 20px;font-size:15px;color:#000;cursor:pointer;background:#ff9800;margin-left:20px;max-width:120px;text-align:center;border-radius:3px}div#loadaf:hover{background:#cddc39}.allplaytab td{margin-bottom:10px;border-bottom:1px solid #cacaca!important;border:none}.allplaytab tr:nth-child(even){background-color:#f8f7ff!important}.allplaytab th{background:#018074!important;color:#dededa!important;border:1px solid #ffad3c!important;border-bottom:3px solid #ffad3c!important}.tickethigh{padding:10px;border-bottom:7px solid #d8d8d8}span.tctext{margin:10px 0;padding-left:10px;border-left:3px solid red;background:#ffff52;font-weight:700}p.tcinfo{font-size:13px;line-height:15px;margin-top:10px;max-width:460px;color:#afaeae;border:1px solid #2196f3;padding:10px;border-radius:3px}.tcname{margin:10px;padding:5px 10px;border-left:3px solid #2196f3;background:#f7f8f9}.bdtp{margin:30px 0;border-top:7px solid #d8d8d8;font-size:0}span.tctext.bloc{background:#ff252578}p.tcinfo.bloc{border:1px solid #ffa4a4}span.tctext.tools{background:#baff69}.afidwrapper{margin:30px 15px 0 15px;display:inline-block;max-width:360px;background:#cddc39;padding:10px;border-left:5px solid #000;font-size:15px;width:100%}span.totcunt.right{float:right;background:#ffea30;padding:0 5px;font-weight:700}.divcpy{max-width:460px;margin-left:15px;border-left:5px solid #3086ad;padding:10px;background:#f7f7f7}.dftcpy{font-size:14px}input#myInput{padding:3px;border-radius:4px;border:none;background:#fffbd3;width:200px}.tooltiptext,span#myTooltip:hover{visibility:hidden!important}button#affbtn{background:#2196f3;border:none;padding:2px 5px;color:#fff;border-radius:3px;cursor:pointer}p.frameinfo{margin:15px;font-size:13px;background:#dde6ff;padding:5px;border-radius:3px;color:#0f8655;font-weight:700}.imgfram{margin:15px}.showframe{background:#eff0f1;margin:0 15px;padding:10px;font-size:13px;border:1px solid #1d1c1c;border-radius:3px}button#cpcd{margin-left:15px;background:#2196f3;padding:3px 10px;color:#fff;border:1px solid #005801;border-radius:3px;cursor:pointer}button#cpcd:hover{background:#0d538a}ul.sociconlist li{display:inline-block;margin-right:10px}ul.sociconlist{padding:15px;display:block;width:100%}ul.sociconlist li a:hover{color:#000}.dptext{margin:20px;padding-top:10px;color:#bfb4b4}.depositform{font-size:85%;margin:15px;border:1px solid #43ff1b;padding:5px;line-height:18px;max-width:660px}ul.deptshow{float:left;padding:0;width:96%;margin-left:2%}ul.deptshow.k li{background:#dcdcdc;border-left:3px solid #2196f3}ul.deptshow li{display:inline-block;background:#f5f5f5;width:100%;max-width:660px;padding:10px}.ccredit{border-bottom:1px solid #eceaea;font-size:15px}span.deptright{float:right}._mandeposit{max-width:460px;background:#fffef6;font-size:16px;padding:10px;color:#00794d}#mancashback{margin:0 20px}.wrequest:hover{background:#f9ea67}.wrequest{background:linear-gradient(to bottom,#14805e,#14805e);background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#14805e),color-stop(100%,#14805e));max-width:260px;padding:8px 10px;border:1px solid #2196f3;color:#fff;font-size:14px;text-transform:uppercase;cursor:pointer;border-right:15px solid #1d654e;text-align:center;border-radius:3px}span.payclose{float:left;margin-top:-20px;background:red;line-height:10px;padding:5px;border-radius:50%;color:#fff;cursor:pointer}#acnum,#mamount,#paymenttype,#tref{width:260px;padding:8px 10px;border:1px solid #86bcfb;border-radius:3px;margin-bottom:10px}.wpending{position:absolute;margin-top:-20px;color:red;border-radius:50%;background:#000;line-height:10px;padding:5px;font-weight:700;cursor:pointer}.wpending:hover{background:#5f5d5d}.wload{color:#a2a2a2;background:#d8d8d8;margin-left:10px;font-family:arial;max-width:120px;border-radius:3px;cursor:pointer;text-align:center;clear:both}.wload:hover{background:#615f5f}h5.dphist{margin:20px;margin-top:50px;color:#b7b7b7}ul.printme{margin:0;background:#f5f5f5;max-width:360px;margin:0 auto;padding:10px;border-left:3px solid #2196f3}ul.printme li{display:inline-block;width:100%;border-bottom:1px solid #bdbdbd;padding:5px 0}span.printright{float:right;background:#d4f377;width:70px;text-align:center;color:#000;border-radius:3px}li.ccomm{background:#dcdcdc;font-weight:700}p.didcenter{margin:0 auto;max-width:360px;padding-bottom:10px}button#printbtnx{margin:15px;padding:5px 15px}.btncenti{max-width:360px;margin:0 auto}
</style>