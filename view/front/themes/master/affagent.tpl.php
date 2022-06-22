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
<?php $usid = App::Auth()->uid;
$ob = App::Auth()->type;
if ($ob != 'agent') {
    echo '<div class="noexist">The page you are looking for does not exist!</div>';
    die();
} ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php if (App::Auth()->is_User()) {
    $usid = App::Auth()->uid;
    $aid = Auth::$udata->afid;
    $afbal = Auth::$udata->afbal;
    $curry = '৳';
}
//get data from other sources
include_once('affiliate_data.php');
$count_aff = Db::run()->pdoQuery("SELECT count(id) AS caf FROM users WHERE afid = '$usid' AND id <> '$usid'");
?>


<div class="supp-header">
    <ul class="suppmenu ag">
        <li class="mennav">
            <div class="menuv"><?= Lang::$word->AFF_MENU; ?> <i class="icon reorder"></i></div>
        </li>
        <li class="supplogo">
            <div class="columns shrink mobile-80 phone-80">
                <a href="<?php echo SITEURL; ?>/" class="logo">
                    <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?>
                </a>
            </div>
        </li>


        <li class="suppsubmenu">
            <div id="superuser">
  <span class="supbal">
  <?php echo $curry; ?><?php echo number_format((float)App::Auth()->afbal, 2, '.', ''); ?>
  </span>
                <span class="activesuper">u</span>

            </div>
        </li>
    </ul>
</div>

<div class="yoyo-grid">


    <div id="jinxaff">
        <div class="rowaff">


            <div class="colaff left" style="width:200px">
                <div id="mySidenav" class="sidenav">

                    <div class="toptextleft">
                        <?= Lang::$word->AFF_TOP_MSG; ?>
                    </div>
                    <a id="bkhm" href="/"><i class="icon home"></i> <?= Lang::$word->AFF_BACK_HOME; ?></a></br>
                    <a style="color:#fff;border:none" href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <a id="dashboards" class="dcolor" href="/affagent/">
                        <i class="icon dashboard"></i> <?= Lang::$word->AFF_BACK_DASH; ?></a>

                    <div id="affnav">
                        <a id="allplayers" href="#"><i class="icon user profile"></i> <?= Lang::$word->AFF_BACK_LIST; ?></a>
                        <a id="createplayers"><i class="icon unlock"></i> <?= Lang::$word->AFF_BACK_CREATE; ?></a>
                        <a id="mydash"><i class="icon money"></i><?= Lang::$word->AFF_MONEY_TRANSFER; ?></a>
                        <!--<a id="playersslips" href="#"><i class="icon copy"></i> <?= Lang::$word->AFF_BACK_TICKET; ?></a>-->
                        <a id="performances" href="#"><i class="icon bar chart"></i> <?= Lang::$word->AFF_BACK_STATS; ?></a>
                        <!--<a id="blockedplayers" href="#"><i class="icon ban"></i> <?= Lang::$word->AFF_BACK_BLOCKED; ?></a>-->
                        <!--<a id="toolsmarketing" href="#"><i class="icon unfold out"></i> <?= Lang::$word->AFF_BACK_TOOLS; ?></a>-->
                        <a id="withdrawals" href="#"><i class="icon arrow backward"></i> <?= Lang::$word->AFF_BACK_WITHDRAWALS; ?></a>
                        <!--<a id="printreports" href="#"><i class="icon printer"></i> <?= Lang::$word->AFF_BACK_PRINT_REPORTS; ?></a>-->
                        <!--<a id="sssupports" href="#"><i class="icon question sign"></i> <?= Lang::$word->AFF_BACK_SUPPORT; ?></a>-->
                    </div>

                </div>
            </div>


            <div class="colaff right" style="width:73%">
                <div id="thistopnotice">
                    <?php echo $kge; ?>
                </div>
                <div id="aj-fetch"> <!----aj div----->
                    <ul class="topdash">
                        <li class="dashleft"><?= Lang::$word->AFF_BACK_DASHBOARD; ?></br><span class="dtext"><?= Lang::$word->AFF_BACK_DASH_TITLE; ?></span></li>
                        <li class="dashright af"><?= Lang::$word->AFF_BACK_AFFILIATES; ?></br> <i class="icon user alt"></i>
                            <span class="afbb"><?php $cc = $count_aff->aResults[0]->caf;
                                echo $cc; ?></span></li>
                        <li class="dashright"><?= Lang::$word->AFF_BACK_COMMISSION; ?></br> <i class="icon bar chart"></i>
                            <span class="afbb"><?php echo $curry; ?><?php echo $afbal; ?></span></li>
                    </ul>

                    <!-------------------For users numbers------------------------------>
                    <ul class="topdash unu">
                        <li class="dashleft"><?= Lang::$word->AFF_BACK_TODAY_REG; ?></br><i class="icon user alt"></i>
                            <span class="afbb"><?php echo $ctoday['rtoday']; ?></span></li>
                        <li class="dashr af"><?= Lang::$word->AFF_BACK_TOTAL_ACTIVE; ?></br> <i class="icon user alt"></i>
                            <span class="afbb"><?php echo $ctoday['ractive']; ?></span></li>
                        <li class="dashr"><?= Lang::$word->AFF_BACK_TOTAL_PEDING; ?></br> <i class="icon user alt"></i>
                            <span class="afbb"><?php echo $ctoday['rinactive']; ?></span></li>

                        <li class="dashleft"><?= Lang::$word->AFF_BACK_TODAY_ERNING; ?>
                            <i title="It's Not a final Commission" class="icon question sign"></i></br>
                            <i class="icon bar chart"></i>
                            <span class="afbb"><?php echo $curry; ?><?php echo round($net_commission, 2); ?></span></li>
                        <li class="dashr af"><?= Lang::$word->AFF_BACK_COMMISSION_CLEAR; ?></br> <i class="icon bar chart"></i>
                            <span class="afbb"><?php echo $curry; ?><?php echo $afbal; ?></span></li>
                        <li class="dashr"> <?= Lang::$word->AFF_BACK_PEDNING_VOLUME; ?>
                            <i title="It's a total volume before settlement" class="icon question sign"></i></br>
                            <i class="icon bar chart"></i>
                            <span class="afbb"><?php echo $curry; ?><?php echo $pending_volume; ?></span></li>
                    </ul>


                    <div id='chart_div' style="max-width:100%"></div>

                    <div class="recentreg">
                        <div id="bkaw"><?= Lang::$word->AFF_BACK_RECENTLY; ?></div>
                        <table id="customers">
                            <tr>
                                <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_NO; ?></th>
                                <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_COUNTRY; ?></th>
                                <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_FULL_NAME; ?></th>
                                <th><?= Lang::$word->AFF_BACK_RECENTLY_TABLE_JOINED; ?></th>
                            </tr>
                            <?php $shusers = "SELECT id,fname,lname,created,country FROM users WHERE id <> '$usid' AND afid = '$aid' ORDER BY created DESC LIMIT 5";
                            $urs = Db::run()->pdoQuery($shusers);
                            //var_dump($shslips);
                            $i = 1;
                            foreach ($urs->aResults as $record) { ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php $cc = $record->country;
                                        if (!empty($cc)) {
                                            echo $cc;
                                        } else {
                                            echo Lang::$word->AFF_BACK_PEDNING;
                                        }; ?></td>
                                    <td><?php echo $record->fname;
                                        echo ' ';
                                        echo $record->lname; ?></td>
                                    <td> <?php echo $record->created; ?></td>
                                </tr>
                            <?php } ?>


                        </table>

                    </div>


                </div> <!---- end of ajax div------>
                </br></br></br></br>

            </div>

        </div>
    </div>
</div>


<script>
    if ($(window).width() < 800) {
        $('body').on('click', ' .menuv', function () {
            $(window).scrollTop(0);
            $(".colaff.left.shmob").css("display", "");
            $('.colaff.left').toggleClass("shmob");
            $('.menuv').toggleClass("adk");
        });

    } else {
        $('body').on('click', ' .menuv', function () {
            $('.colaff.left').toggleClass("nobig");
            $('.menuv').toggleClass("adk");
        });
    }

    // =================================================================================================================

    //show amount to send
    $('body').on('keyup', ' input#putcash', function(){
        var tremail = $("input#sendfunds").val();
        var trmoney = $(this).val();
        if(tremail == ''){
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
            data: {
                usid:<?php echo $usid;?>,
                tremail:tremail,
                trmoney:trmoney,
                method:"selcurrency"
            },
            success: function(response) {
                $("#showavl").empty().append(response);
            }
        });
        return false;
    });

    //submit transfer
    $('body').on('click', ' .subtransfer', function(){
        $(this).text("Sending cash...");
        var tremail = $("input#sendfunds").val();
        var trmoney = $("input#putcash").val();

        if(tremail == ''){
            $("#showavl").html("invalid email");
            $(this).text("Send Money");
            return false;
        }
        if(trmoney == ''){
            $("#showavl").html("invalid amount");
            $(this).text("Send Money");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
            data: {
                usid:<?php echo $usid;?>,
                tremail:tremail,
                trmoney:trmoney,
                method:"submittransfer"
            },
            success: function(response) {
                $("#showavl").empty().append(response);
                $("input#putcash").val("");
                $("input#sendfunds").val("");
            }
        });
        return false;
    });

    //show more transfer history
    $('body').on('click', ' .tload', function(){
        var rowCount = $('.cfvalue').val();
        $(this).html('Loading...');
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/super-agents/brokers-dashboard",
            data: {
                rc:rowCount,
                usid:<?php echo $usid;?>,
                method:'ltransferb'
            },
            success: function(response) {
                $('.tload').html('');
                $("div#quathistory").append(response);
                var fx = 50;
                var cc =  parseInt(rowCount) +  parseInt(fx);
                $('.cfvalue').val(cc);
            }
        });
        return false;
    });


    // =================================================================================================================


    //different tabs on click
    $('body').on('click', ' #affnav a', function () {
        $('.colaff.left').toggleClass("shmob");
        $('.menuv').toggleClass("adk");
        $('#aj-fetch').html("<div id='loading'></div>");
        $('#mySidenav a').removeClass('dcolor');
        $(this).addClass('dcolor');
        $('#aj-fetch').addClass("dbg");
        var meth = $(this).attr('id');
        if (meth == 'createplayers') {
            var origin = window.location.origin;
            var url = origin + "/agent-registration/?aaff=<?php echo $usid;?>";
            window.open(url, '_blank');
            $('#aj-fetch').html("");
            return false;
        }


        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav.php",
            data: {
                usid:<?php echo $usid;?>,
                method: meth
            },
            success: function (response) {
                $("#aj-fetch").html('');
                $("#aj-fetch").append(response);
            }
        });
        return false;
    });

    //load more players list
    $('body').on('click', ' .afload', function () {
        var rowCount = $('.cfvalue').val();
        $(this).html('Loading...');
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav",
            data: {
                rc: rowCount,
                usid:<?php echo $usid;?>,
                method: 'allpmore'
            },
            success: function (response) {
                $('.afload').html('');
                $("#aj-fetch").append(response);
                var fx = 50;
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
    $('body').on('click', ' .payclose', function () {
        $('#manwithdraw').css("background", "");
        $("#mancashback").empty();
    });

    //show withdrawal form
    $('body').on('click', ' #manwithdraw', function () {
        $('#mancashback').html('Loading...');
        $('#manwithdraw').css("background", "");
        $('#manwithdraw').css("background", "#eb1515");
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws.php",
            data: {
                usid:<?php echo $usid;?>,
                method: 'manwithdraw'
            },
            success: function (response) {
                $('#mancashback').html('');
                $("#mancashback").empty().append(response);
            }
        });
        return false;
    });

    //withdrawal request submit
    $('body').on('click', ' .wrequest', function () {
        $('.sherr').html('Loading...');
        var paytype = $('#paymenttype').val();
        var amount = $('#mamount').val();
        var acnum = $('#acnum').val();
        var ref = $('#tref').val();
        if (amount < 100) {
            $('.sherr').text('Amount cannot be empty or less than 100');
            return;
        }
        if (ref.length > 100) {
            $('.sherr').text('Maximum 100 characters allowed');
            return;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws.php",
            data: {
                usid:<?php echo $usid;?>,
                amount: amount,
                acnum: acnum,
                ref: ref,
                paytype: paytype,
                method: 'werr',

            },
            success: function (response) {
                $("._mandeposit").html(response);
            }
        });
        return false;
    });


    //real agent transfer show money to credit
    $('body').on('keyup', ' input#agpaynum', function () {
        var trmoney = $(this).val();
        if (trmoney == '') {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws",
            data: {
                usid:<?php echo $usid;?>,
                trmoney: trmoney,
                method: "showvaltotr"
            },
            success: function (response) {
                $(".showvaltotr").empty().append(response);
            }
        });
        return false;
    });

    //submit real agent transfer submit
    $('body').on('click', ' .subtransferag', function () {
        $(this).html("Sending..");
        var trmoney = $('input#agpaynum').val();
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws",
            data: {
                usid:<?php echo $usid;?>,
                trmoney: trmoney,
                method: 'agpaynum'
            },
            success: function (response) {
                $(".showvaltotr").html(response);
                $(".subtransferag").html("Transfer Balance");
                $('input#agpaynum').val("");
            }
        });
        return false;
    });


    //delete withdrawal pending
    $('body').on('click', ' span.wpending', function () {
        var idto = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/agent_withdraws.php",
            data: {
                usid:<?php echo $usid;?>,
                idto: idto,
                method: 'wpending'
            },
            success: function (response) {
                $("li.idto-" + idto).html("Successfully Deleted");
            }
        });
        return false;
    });

    //show more withdrawal history
    $('body').on('click', ' .wload', function () {
        var rowCount = $('.cfvalue').val();
        $(this).html('Loading...');
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/accounts/affiliates_nav",
            data: {
                rc: rowCount,
                usid:<?php echo $usid;?>,
                method: 'lwithdraw'
            },
            success: function (response) {
                $('.wload').html('');
                $("#aj-fetch").append(response);
                var fx = 50;
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

    $('body').on('click', ' #printbtnx', function () {
        PrintPanel();
    })


    //for front chart
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var jsonData = $.ajax({
            url: "<?php echo SITEURL;?>/shell/accounts/affiliates-data.php?usid=<?php echo $usid;?>",
            dataType: "json",
            async: false
        }).responseText;

// Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

        var options = {'title': ''};

// Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
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

    .supp-header {
        background-color: #1f1f1f;
        border-color: #1f1f1f;
        z-index: 1000;
        border-width: 0 0 1px;
        border-bottom: 1px solid #e7e7e7;
        position: fixed;
        width: 100%;
        padding: 10px
    }

    .menuv {
        font-size: 18px;
        font-weight: 700;
        margin-top: 5px;
        display: inline-block;
        cursor: pointer;
        color: #fff;
    }

    i.icon.reorder {
        font-size: 20px;
        position: absolute;
        margin-left: 5px;
        margin-top: 3px;
        font-weight: 700
    }

    .menuv:hover {
        color: red
    }

    li.suppsubmenu {
        text-align: right;
        padding-right: 10px
    }

    span.supbal {
        font-size: 20px;
        font-weight: 700;
        display: inline-block;
        margin-top: 15px;
        margin-right: 5px;
        color: #fff
    }

    div#superuser {
        float: right;
        margin: 0;
        display: inline-block;
        padding: 0;
        line-height: 0;
        vertical-align: text-bottom;
        color: #fff;
    }

    .wrapper-sa {
        max-width: 780px;
        margin: 0 auto;
        min-height: 200px;
        margin-top: 10px;
        border-left: 5px solid #f3f3f3;
        padding-left: 5px
    }

    .containersa {
        min-height: 100px;
        color: #fff
    }

    .colsa {
        float: left;
        padding: 10px;
        margin: 5px;
        background: #03a9f4;
        width: calc(33.33% - 10px)
    }

    .containersa:after {
        content: "";
        display: table;
        clear: both
    }

    .agdwn {
        border-bottom: 1px solid;
        background: #e7e7e7;
        padding: 5px 10px
    }

    span.righelse {
        float: right
    }

    span.usvalue {
        float: right;
        font-size: 30px;
        margin-right: 2px
    }

    span.userle {
        float: left;
        margin-top: 10px
    }

    div#cott .colsa {
        background: #787878
    }

    .noteus {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ffc409;
        margin-top: 10px
    }

    li.supplogo img {
        max-width: 120px
    }

    div#thistopnotice {
        padding: 5px 10px;
        border-top: 3px solid #eb1515;
    }

    .menuv.adk:before {
        content: "X";
        margin-right: 5px;
        color: #fff;
    }

    ul.topdash.unu li {
        width: 48% !important
    }

    main {
        background: #fff
    }

    #jinxaff {
        max-width: 760px;
        margin: 0 auto;
        background: #eaeaea;
        margin-top: 56px
    }

    * {
        box-sizing: border-box
    }

    .colaff {
        float: left
    }

    .rowaff {
        display: flex;
        display: -webkit-flex
    }

    .rowaff:after {
        content: "";
        display: table;
        clear: both
    }

    .colaff.right {
        margin-left: 5px;
        border-left: 1px solid #afacac
    }

    .toptextleft {
        margin: 5px;
        font-size: 13px;
        background: #1f1f1f;
        padding: 10px;
        line-height: 16px;
        color: #ffffff;
        margin-bottom: 20px;
        border-radius: 3px;
        border-left: 2px solid;
    }

    .sidenav {
        height: 100%;
        z-index: 9999;
        background-color: #fff;
        overflow-x: hidden;
        transition: .5s;
        width: 200px;
        border-right: 1px solid #b3b3b3
    }

    .sidenav a {
        padding: 8px 5px 8px 10px;
        text-decoration: none;
        font-size: 16px;
        color: #949494;
        display: block;
        border-bottom: 1px solid #d6d6d6;
        transition: .3s
    }

    .sidenav a:hover {
        color: #d8d0d0
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px
    }

    ul.topdash li {
        display: inline-block;
        font-size: 14px
    }

    ul.topdash {
        padding: 10px;
        margin: 0;
        width: 100%;
        background: #fff;
        border-bottom: 1px solid #bfbfbf;
        margin-bottom: 10px
    }

    li.dashright {
        float: right;
        padding: 0 10px
    }

    span.dtext {
        font-size: 12px;
        color: #b1a6a6
    }

    span.afbb {
        font-size: 17px;
        font-weight: 700;
        font-family: arial
    }

    li.dashright.af {
        border-left: 1px solid #ececec
    }

    ul.topdash.unu li {
        width: 32.8%;
        text-align: center;
        border: 1px solid #b3babf;
        border-radius: 3px;
        background: #f9f9f9;
        margin: 4px 0;
        padding: 10px;
    }

    #customers {
        border-collapse: collapse;
        width: 100%;
        font-size: 15px
    }

    #customers td, #customers th {
        border: 1px solid #f5f5f5;
        padding: 4px;
        text-align: center;
        color: #716f6f
    }

    #customers tr:nth-child(even) {
        background-color: #fffef0
    }

    #customers tr:hover {
        background-color: #ddd
    }

    #customers th {
        background-color: #1f1f1f;
        color: #ffffff;
    }

    .recentreg {
        background: #fff;
        padding: 10px;
        margin-top: 10px;
        border-top: 1px solid #d2d2d2
    }

    #bkaw {
        padding: 10px;
        font-weight: 700;
        color: #eb1515
    }

    a.dcolor {
        color: #eb1515;
        font-weight: 700
    }

    #aj-fetch.dbg {
        background: #fff;
        height: 100%;
        padding: 15px;
    }

    .headtit {
        font-size: 1.5rem;
        font-weight: 500;
        font-family: inherit;
        color: #1e2022;
        line-height: 1.4;
        padding: 10px;
        border-bottom: 5px solid #e4e4e4
    }

    div#loadaf {
        padding: 0 20px;
        font-size: 15px;
        color: #000;
        cursor: pointer;
        background: #ff9800;
        margin-left: 20px;
        max-width: 120px;
        text-align: center;
        border-radius: 3px
    }

    div#loadaf:hover {
        background: #cddc39
    }

    .allplaytab td {
        margin-bottom: 10px;
        border-bottom: 1px solid #cacaca !important;
        border: none
    }

    .allplaytab tr:nth-child(even) {
        background-color: #f8f7ff !important
    }

    .allplaytab th {
        background: #1f1f1f !important;
        color: #e7e7e7 !important;
        border: none !important;
    }

    .tickethigh {
        padding: 10px;
        border-bottom: 7px solid #d8d8d8
    }

    span.tctext {
        margin: 10px 0;
        padding-left: 10px;
        border-left: 3px solid red;
        background: #1f1f1f;
        font-weight: 700;
        color: #fff;
        padding: 7px;
    }

    p.tcinfo {
        font-size: 13px;
        line-height: 15px;
        margin-top: 10px;
        max-width: 460px;
        color: #afaeae;
        border: 1px solid #ff0000;
        padding: 10px;
        border-radius: 3px;
        color: #000;
    }

    .tcname {
        margin: 10px;
        padding: 5px 10px;
        border-left: 3px solid #ff0000;
        background: #f7f8f9
    }

    .bdtp {
        margin: 30px 0;
        border-top: 7px solid #d8d8d8;
        font-size: 0
    }

    span.tctext.bloc {
        background: #1f1f1f;
        color: #fff
    }

    p.tcinfo.bloc {
        border: 1px solid #ffa4a4
    }

    span.tctext.tools {
        background: #1f1f1f;
    }

    .afidwrapper {
        margin: 30px 15px 0px 15px;
        display: inline-block;
        max-width: 360px;
        background: #1f1f1f;
        padding: 10px;
        border-left: 5px solid #f00;
        font-size: 15px;
        width: 100%;
        color: #fff;
    }

    span.totcunt.right {
        float: right;
        background: #ff0000;
        padding: 0 5px;
        font-weight: 700;
        color: #fff;
    }

    .divcpy {
        max-width: 460px;
        margin-left: 15px;
        border-left: 5px solid #ff0000;
        padding: 10px;
        background: #f7f7f7
    }

    .dftcpy {
        font-size: 14px
    }

    input#myInput {
        padding: 3px;
        border-radius: 4px;
        border: none;
        background: #fffbd3;
        width: 200px
    }

    .tooltiptext, span#myTooltip:hover {
        visibility: hidden !important
    }

    button#affbtn {
        background: #ff0000;
        border: none;
        padding: 2px 5px;
        color: #fff;
        border-radius: 3px;
        cursor: pointer
    }

    p.frameinfo {
        margin: 15px;
        font-size: 13px;
        background: #ffdddd;
        padding: 5px;
        border-radius: 3px;
        color: #000000;
        font-weight: 700;
    }

    .imgfram {
        margin: 15px
    }

    .showframe {
        background: #eff0f1;
        margin: 0 15px;
        padding: 10px;
        font-size: 13px;
        border: 1px solid #1d1c1c;
        border-radius: 3px
    }

    button#cpcd {
        margin-left: 15px;
        background: #eb1515;
        padding: 3px 10px;
        color: #fff;
        border: 1px solid #eb1515;
        border-radius: 3px;
        cursor: pointer;
    }

    button#cpcd:hover {
        background: red;
    }

    ul.sociconlist li {
        display: inline-block;
        margin-right: 10px
    }

    ul.sociconlist {
        padding: 15px;
        display: block;
        width: 100%
    }

    ul.sociconlist li a:hover {
        color: #f7f8f9
    }

    .dptext {
        margin: 20px;
        padding-top: 10px;
        color: #bfb4b4
    }

    .depositform {
        font-size: 85%;
        margin: 15px;
        border: 1px solid #43ff1b;
        padding: 5px;
        line-height: 18px;
        max-width: 660px
    }

    ul.deptshow {
        float: left;
        padding: 0;
        width: 96%;
        margin-left: 2%
    }

    ul.deptshow.k li {
        background: #dcdcdc;
        border-left: 3px solid #2196f3
    }

    ul.deptshow li {
        display: inline-block;
        background: #f5f5f5;
        width: 100%;
        max-width: 660px;
        padding: 10px
    }

    .ccredit {
        border-bottom: 1px solid #eceaea;
        font-size: 15px
    }

    span.deptright {
        float: left;
    }

    ._mandeposit {
        max-width: 460px;
        background: #fffef6;
        font-size: 16px;
        padding: 10px;
        color: #00794d
    }

    #mancashback {
        margin: 0 20px
    }

    .wrequest:hover {
        background: #f9ea67
    }

    .wrequest {
        background: linear-gradient(to bottom, #14805e, #14805e);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #14805e), color-stop(100%, #14805e));
        max-width: 260px;
        padding: 8px 10px;
        border: 1px solid #2196f3;
        color: #fff;
        font-size: 14px;
        text-transform: uppercase;
        cursor: pointer;
        border-right: 15px solid #1d654e;
        text-align: center;
        border-radius: 3px
    }

    span.payclose {
        float: left;
        margin-top: -20px;
        background: red;
        line-height: 10px;
        padding: 5px;
        border-radius: 50%;
        color: #fff;
        cursor: pointer
    }

    #acnum, #mamount, #paymenttype, #tref {
        width: 260px;
        padding: 8px 10px;
        border: 1px solid #86bcfb;
        border-radius: 3px;
        margin-bottom: 10px
    }

    .wpending {
        position: absolute;
        margin-top: -20px;
        color: red;
        border-radius: 50%;
        background: #000;
        line-height: 10px;
        padding: 5px;
        font-weight: 700;
        cursor: pointer
    }

    .wpending:hover {
        background: #5f5d5d
    }

    .wload {
        color: #a2a2a2;
        background: #d8d8d8;
        margin-left: 10px;
        font-family: arial;
        max-width: 120px;
        border-radius: 3px;
        cursor: pointer;
        text-align: center;
        clear: both
    }

    .wload:hover {
        background: #615f5f
    }

    h5.dphist {
        margin: 10px;
        margin-top: 50px;
        color: #b7b7b7;
    }

    ul.printme {
        margin: 0;
        background: #f5f5f5;
        max-width: 360px;
        margin: 0 auto;
        padding: 10px;
        border-left: 3px solid #eb1515
    }

    ul.printme li {
        display: inline-block;
        width: 100%;
        border-bottom: 1px solid #bdbdbd;
        padding: 5px 0
    }

    span.printright {
        float: right;
        background: #eb1515;
        width: 70px;
        text-align: center;
        color: #fff;
        border-radius: 3px
    }

    p.tcinfo a {
        color: #eb1515 !important;
    }

    li.ccomm {
        background: #dcdcdc;
        font-weight: 700
    }

    p.didcenter {
        margin: 0 auto;
        max-width: 360px;
        padding-bottom: 10px
    }

    button#printbtnx {
        margin: 15px;
        padding: 5px 15px
    }

    .btncenti {
        max-width: 360px;
        margin: 0 auto
    }

    .subtransferag {
        padding: 5px 10px;
        background: #eb1515;
        display: block;
        max-width: 180px;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 3px;
        text-align: center;
        color: #fff
    }

    .subtransferag:hover {
        background: red
    }

    .showvaltotr {
        padding: 5px 10px;
        margin-top: 10px;
        max-width: 300px;
        color: red
    }

    input#agpaynum {
        padding: 5px 10px
    }

    .agshsuccess {
        margin-left: 10px
    }

    div#succtr {
        background: #e4ffa0;
        padding: 1px 5px;
        line-height: 18px
    }

    ul.topdash.unu.stat li {
        background: #3e3e3e !important;
        color: #fff;
        padding: 10px;
    }

    .statwrapper h3 {
        padding: 10px;
        border-bottom: 10px solid #dcdcdc;
        background: #e7e7e7;
        color: #1f1f1f;
        text-align: center;
    }

    .wthaff {
        float: left;
        margin-bottom: 100px
    }

    ul.suppmenu.ag {
        max-width: 720px
    }

    ul.sociconlist a {
        color: #fff;
        background: #000;
        padding: 5px 10px;
        border-radius: 6px;
    }

    @media screen and (max-width: 799px) {
        .rowaff {
            display: inline-block !important;
            margin-top: 15px
        }

        .colaff.left {
            width: 100% !important;
            display: none
        }

        div#mySidenav {
            width: 100% !important
        }

        .colaff.right {
            width: 100% !important
        }

        .suppcol.sleft {
            display: none
        }

        .suppcol.sleft.noso {
            display: none
        }

        .suppcol.sright {
            width: 100% !important
        }

        span.userle {
            margin-top: 3px !important
        }

        span.usvalue {
            font-size: 16px !important
        }

        div#superuser {
            margin-top: 20px
        }

        .supp-header {
            padding: 0
        }

        .menuv {
            padding: 10px;
            margin-top: 15px;
            color: #fff;
        }

        li.supplogo img {
            max-width: 140px;
            margin-top: 3px
        }

        span.supbal {
            font-size: 16px
        }

        .supprow {
            margin-top: 68px
        }

        .colaff.left.shmob {
            display: block;
            width: 100% !important;
            max-width: 799px
        }
    }

    .yoyo-grid {
        width: 100%;
        margin: 0 auto;
    }


    ul.sidenavs {
        padding: 0;
    }

    i.icon.chevron.right {
        float: right;
        margin-top: 5px;
    }

    .sidenavs li:hover {
        background: #e3e0e0;
    }

    .sidenavs li {
        position: relative;
        display: block;
        width: 100%;
        padding: 10px 15px;
        border-bottom: 1px solid #e7e7e7;
        cursor: pointer;
        color: #000000;
        letter-spacing: .5px;
    }

    .sagentd {
        background: #e7e7e7;
        padding: 10px;
    }

    .supadm {
        font-size: 24px;
        font-weight: bold;
    }

    i.icon.users {
        font-size: 20px;
    }

    li.supplogo img {
        max-width: 130px;
    }

    .suppcol.sleft {
        background-color: #f8f8f8;
        max-width: 300px;
    }

    .supp-header {
        background-color: #1f1f1f;
        border-color: #e7e7e7;
        z-index: 1000;
        border-width: 0 0 1px;
        border-bottom: 1px solid #e7e7e7;
        position: fixed;
        width: 100%;
        padding: 10px;
    }

    ul.suppmenu li {
        display: table-cell;
        width: 50%;
    }

    .menuv {
        font-size: 18px;
        font-weight: bold;
        margin-top: 5px;
        display: inline-block;
        cursor: pointer;
        color: #fff;
    }

    i.icon.reorder {
        font-size: 20px;
        position: absolute;
        margin-left: 5px;
        margin-top: 3px;
        font-weight: bold;
    }

    .menuv:hover {
        color: #f00;
    }

    li.suppsubmenu {
        text-align: right;
        padding-right: 10px;
    }

    span.supbal {
        font-size: 20px;
        font-weight: bold;
        display: inline-block;
        margin-top: 20px;
        color: #fff;
    }

    div#superuser {
        float: right;
        margin: 0;
        display: inline-block;
        padding: 0;
        line-height: 0;
        vertical-align: text-bottom;
    }

    .wrapper-sa {
        max-width: 780px;
        margin: 0 auto;
        min-height: 200px;
        margin-top: 10px;
        border-left: 5px solid #f3f3f3;
        padding-left: 5px;
    }

    .containersa {
        min-height: 100px;
        color: #fff;
    }


    .colsa {
        float: left;
        padding: 10px;
        margin: 5px;
        background: #282828;
        width: calc(33.33% - 10px);
    }

    #noo a {
        color: #000;
    }

    .containersa:after {
        content: "";
        display: table;
        clear: both;
    }

    .agdwn {
        border-bottom: 3px solid;
        background: #e7e7e7;
        padding: 5px 10px;
        border-color: #eb1515;
    }

    span.righelse {
        float: right;
    }

    span.usvalue {
        float: right;
        font-size: 30px;
        margin-right: 2px;
    }

    span.userle {
        float: left;
        margin-top: 10px;
    }

    div#cott .colsa {
        background: #c42929;
    }

    .infcontent a {
        color: red;
    }

    .noteus {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ffc409;
        margin-top: 10px;
    }

    .backmeif, .backmeifx, .backmeiftr {
        background: #d0d0d0;
        display: inline-block;
        padding: 3px 20px;
        cursor: pointer;
        margin-bottom: 20px
    }

    .backmeif:hover, .backmeifx:hover {
        background: #a7a7a7;
    }

    .shadowme, .shadowmex, .shadowmey {
        cursor: pointer;
        color: #0377da;
    }

    .shadowme:hover, .shadowmex:hover, .shadowmey:hover {
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

    .ollist {
        font-size: 12px;
        padding: 0;
    }

    .showmo {
        display: inline-block;
    }

    .srid {
        display: inline-block;
        float: right;
    }

    .srid i.find.icon {
        padding: 10px;
        font-size: 10px;
        background: #e2e5e2;
        cursor: pointer;
    }

    .srid input {
        padding: 6px 10px;
        width: 220px;
        border: 1px solid #eb1515;
    }

    .srid input:focus {
        background: #efefef;
    }

    .srid i.find.icon:hover {
        background: #908e8e;
    }

    .topwwp {
        display: block;
        border-bottom: 1px solid #d4d4d4;
        padding-bottom: 20px;
    }

    span.searck {
        color: #f00;
        cursor: pointer;
    }

    span.searck:hover {
        color: #000;
    }

    /*withdrawal*/
    #paymenttype, #mamount, #tref, #acnum {
        width: 260px;
        padding: 8px 10px;
        border: 1px solid #86bcfb;
        border-radius: 3px;
        margin-bottom: 10px;
    }

    .agform {
        box-shadow: inset 0px 1px 0px 0px #ffffff;
        background: linear-gradient(to bottom, #ededed 5%, #dfdfdf 100%);
        background-color: #ededed;
        border-radius: 6px;
        border: 1px solid #dcdcdc;
        display: inline-block;
        cursor: pointer;
        color: #777777;
        font-size: 16px;
        font-weight: bold;
        padding: 6px 24px;
        text-decoration: none;
        text-shadow: 0px 1px 0px #ffffff;
    }

    span.payclose {
        position: absolute;
        margin-top: -15px;
        border-radius: 50%;
        border: 1px solid #fff;
        padding: 0px 6px;
        background: #f00;
        color: #fff;
    }

    .wrapallmsg {
        display: table;
        margin: 0 auto;
        max-width: 460px;
        width: 100%;
    }

    input#submail {
        width: 100%;
        padding: 6px;
    }

    textarea#msgcontent {
        width: 100%;
        min-height: 100px
    }

    .awrequest, .msgrequest {
        padding: 3px 10px;
        background: #eaff94;
        max-width: 245px;
        margin-left: 5px;
        text-align: center;
        background-color: #44c767;
        border-radius: 28px;
        border: 1px solid #18ab29;
        cursor: pointer;
        color: #ffffff;
        font-family: Arial;
        font-size: 17px;
        text-decoration: none;
        text-shadow: 0px 1px 0px #2f6627;
    }

    .showwith {
        color: #f00;
    }

    ul.deptshow {
        float: left;
        padding: 0;
        width: 96%;
        margin-left: 2%;
    }

    ul.deptshow.k li {
        background: #dcdcdc;
        border-left: 3px solid #2196F3;
    }

    ul.deptshow li {
        display: inline-block;
        background: #f5f5f5;
        width: 100%;
        max-width: 660px;
        padding: 10px;
    }

    .ccredit {
        border-bottom: 1px solid #eceaea;
        font-size: 15px;
    }

    span.deptright {
        float: right;
    }

    ._mandeposit {
        max-width: 460px;
        background: #fffef6;
        font-size: 16px;
        padding: 10px;
        color: #00794d;
    }

    #mancashback {
        margin: 0px 20px;
    }

    .wrequest:hover {
        background: #f9ea67;
    }

    .wpending {
        position: absolute;
        margin-top: -20px;
        color: #f00;
        border-radius: 50%;
        background: #000;
        line-height: 10px;
        padding: 5px;
        font-weight: bold;
        cursor: pointer;
    }

    input#paynum {
        padding: 6px;
    }

    button#paynu {
        padding: 6px;
        cursor: pointer;
    }

    button#paynu:hover {
        background: #fff;
        border: 1px solid #000;
        border-radius: 3px;
    }

    .shreturn {
        color: #f00;
    }

    div#shower {
        color: #f00;
    }

    .printwrap {
        max-width: 560px;
        background: #f8f8f8;
        margin: 0 auto;
    }

    span.rightpr {
        float: right;
        font-weight: bold;
    }

    ul.printul li {
        border-bottom: 1px solid #dcdcdc;
        padding: 5px 25px;
    }

    .wrtp {
        padding: 7px 15px;
        background: #1f1f1f;
        color: #fff;
    }

    span.righttp {
        float: right;
        font-weight: bold;
    }

    button#prstats {
        display: table;
        margin: 0 auto;
    }

    .mydash, .mytransfer, .myhistory {
        background-color: #e8e8e8;
        border-radius: 28px;
        border: 1px solid #d02222;
        display: inline-block;
        cursor: pointer;
        color: #1f1f1f;
        font-family: Georgia;
        font-size: 17px;
        font-weight: bold;
        padding: 7px 20px;
        text-decoration: none;
        text-shadow: 0px 1px 0px #c62828;
    }

    .mydash:hover, .mytransfer:hover, .myhistory:hover {
        background-color: #5cbf2a;
    }

    .mydash:active, .mytransfer:active, .myhistory:active {
        position: relative;
        top: 1px;
    }

    a.mydash.active, a.myhistory.active, a.mytransfer.active {
        background: #fff;
    }

    b.abchp {
        font-size: 24px;
        color: #a30851;
        float: left;
    }

    .infowro {
        padding: 10px;
        border: 1px solid #d8d7d7;
        border-radius: 3px;
        background: #f9f9f9;
    }

    .infcontent {
        margin: 10px;
        background: #232221;
        color: #fff;
        line-height: 20px;
        padding: 10px 2px;
    }

    .ifys {
        background: #009688;
        padding: 10px;
        color: #fff;
    }

    span#ttcount {
        background: #0d0d0d;
        position: absolute;
        padding: 3px 10px;
        border-radius: 50%;
        color: #f00;
        margin-top: -20px;
        margin-left: -5px;
    }

    .depositform {
        border: 1px solid #009688;
        padding: 5px;
        margin-bottom: 30px;
        border-radius: 5px;
        background: #f3f3f3;
    }

    .sacur {
        display: inline-block;
        padding: 5px 10px;
        margin: 10px 0px;
        background: #ebebeb;
    }

    div#showavl {
        color: #000;
        display: table;
    }

    input#putcash {
        padding: 5px 10px;
        border: 2px solid #d52020;
        border-radius: 5px;
    }

    .subtransfer {
        padding: 5px 10px;
        background: #eb1515;
        display: block;
        max-width: 180px;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 3px;
        text-align: center;
        color: #fff;
    }

    .subtransfer:hover {
        background: #f00;
    }

    .warnintxt {
        font-size: 13px;
        line-height: 15px;
        padding: 5px;
        background: #f3f3f3;
        margin-bottom: 10px;
    }

    span.searcktr {
        color: #f00;
        cursor: pointer;
    }

    span.searcktr:hover {
        color: #000;
    }

    div#withbroker {
        margin-bottom: 200px;
    }

    span.supspan {
        font-size: 30px;
        font-weight: bold;
    }

    .superearning {
        padding: 0px 10px;
    }

    .sasubtransferag {
        padding: 5px 10px;
        background: #eb1515;
        display: block;
        max-width: 180px;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 3px;
        text-align: center;
        color: #fff;
    }

    .sasubtransferag:hover {
        background: #f00;
    }

    .sashowvaltotr {
        padding: 5px 10px;
        margin-top: 10px;
        max-width: 300px;
        color: #f00;
    }

    input#saagpaynum {
        padding: 5px 10px;
        border: 2px solid #2196F3;
        border-radius: 3px;
    }


    * {
        box-sizing: border-box;
    }

    .suppcol {
        float: left;
        min-height: 300px;
    }

    .supprow:after {
        content: "";
        display: table;
        clear: both;
    }

    li.supplogo {
        text-align: center;
    }

    .supprow {
        margin-top: 56px;
    }

    .menuv.adk:before {
        content: "X";
        margin-right: 5px;
        color: #000;
    }

    a.ohcollapse {
        position: absolute;
        margin-top: 0px;
        background: #000000;
        padding: 0px 10px;
        color: #f00;
    }

    a.mrkpr, a.crkpr {
        font-size: 14px;
    }

    a.kgpPending {
        color: #7a1313;
    }

    a.crkpr {
        color: #02a70e;
    }

    a.crkpr:hover, a.mrkpr:hover {
        color: #f00;
    }

    input#sendfunds {
        width: 300px;
        padding: 7px;
        border: 2px solid #b7b7b7;
        border-radius: 5px;
    }

    i#fico {
        position: absolute;
        margin-left: -30px;
        margin-top: 10px;
        cursor: pointer;
    }

    .txnhistory {
        display: inline-block;
        background: #f3f3f3;
        cursor: pointer;
        border-bottom: 3px solid #cd2525;
        margin-bottom: 20px;
    }

    .txnhistory:hover {
        background: #fff387;
    }

    .singbt a {
        color: #c52929 !important;
    }

    /*for transfer history*/
    ul.deptshow li {
        display: inline-block;
        background: #f5f5f5;
        width: 100%;
        max-width: 660px;
        padding: 10px;
    }

    ul.deptshow {
        float: left;
        padding: 0;
        width: 99%;
        margin-left: 0%;
    }

    ul.deptshow.k li {
        background: #fff8f8;
        border-left: 3px solid #f00;
    }

    span.deptright {
        float: left !important;
    }

    .ccredit {
        border-bottom: 1px solid #eceaea;
        font-size: 15px;
    }

    .depositform {
        font-size: 85%;
        margin: 15px;
        border: 1px solid #43ff1b;
        padding: 5px;
        line-height: 18px;
        max-width: 660px;
    }

    div#lrembankb {
        cursor: pointer;
    }


    @media screen and (max-width: 799px) {
        .suppcol.sleft {
            display: none;
        }

        .suppcol.sleft.noso {
            display: none;
        }

        .suppcol.sright {
            width: 100% !Important;
        }

        span.userle {
            margin-top: 3px !Important;
        }

        span.usvalue {
            font-size: 16px !important;
        }

        div#superuser {
            margin-top: 20px;
        }

        .supp-header {
            padding: 0px
        }

        .menuv {
            padding: 10px;
            margin-top: 15px;
            color: #fff;
        }

        li.supplogo img {
            max-width: 140px;
            margin-top: 3px;
        }

        span.supbal {
            font-size: 16px;
        }

        .supprow {
            margin-top: 68px;
        }

        .suppcol.sleft.shmob {
            display: block;
            width: 100% !Important;
            max-width: 799px;
        }
    }

</style>