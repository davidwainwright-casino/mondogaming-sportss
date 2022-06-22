<?php
/**
 * Header
 *
 * @package Yoyo Framework
 * @author yoyostack.com
 * @copyright 2015
 */

if (!defined("_YOYO"))
    die('Direct access to this location is not allowed.');

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->title; ?></title>
    <meta name="theme-color" content="#1f2121"/>
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#1f2121">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#1f2121">
    <meta name="keywords" content="<?php echo $this->keywords; ?>">
    <meta name="description" content="<?php echo $this->description; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="dcterms.rights" content="<?php echo $this->core->company; ?> &copy; All Rights Reserved">
    <meta name="robots" content="noindex, nofollow">
    <meta name="revisit-after" content="1 day">
    <meta name="generator" content="Powered by Gambabet">
    <link rel="shortcut icon" href="<?php echo SITEURL; ?>/assets/favicon.ico" type="image/x-icon">
    <?php if (in_array(Core::$language, array("ar", "he"))): ?>
        <link href="<?php echo THEMEURL . '/cache/' . Cache::cssCache(array('base_rtl.css', 'transition_rtl.css', 'button_rtl.css', 'divider_rtl.css', 'icon_rtl.css', 'flag_rtl.css', 'image_rtl.css', 'label_rtl.css', 'form_rtl.css', 'input_rtl.css', 'list_rtl.css', 'segment_rtl.css', 'card_rtl.css', 'table_rtl.css', 'dropdown_rtl.css', 'exchange_rtl.css', 'popup_rtl.css', 'statistic_rtl.css', 'datepicker_rtl.css', 'message_rtl.css', 'dimmer_rtl.css', 'modal_rtl.css', 'progress_rtl.css', 'accordion_rtl.css', 'item_rtl.css', 'feed_rtl.css', 'comment_rtl.css', 'editor_rtl.css', 'utility_rtl.css', 'style_rtl.css'), THEMEBASE); ?>" rel="stylesheet" type="text/css">
    <?php else: ?>
        <link href="<?php echo THEMEURL . '/cache/' . Cache::cssCache(array('base.css', 'transition.css', 'button.css', 'divider.css', 'icon.css', 'flag.css', 'image.css', 'label.css', 'form.css', 'input.css', 'list.css', 'segment.css', 'card.css', 'table.css', 'dropdown.css', 'exchange.css', 'popup.css', 'statistic.css', 'datepicker.css', 'message.css', 'dimmer.css', 'modal.css', 'progress.css', 'accordion.css', 'item.css', 'feed.css', 'comment.css', 'editor.css', 'utility.css', 'style.css'), THEMEBASE); ?>" rel="stylesheet" type="text/css">
    <?php endif; ?>
    <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
    <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/global.js"></script>
    <link href="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginCssCache(THEMEBASE . '/plugins'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleCssCache(THEMEBASE . '/modules'); ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginJsCache(THEMEBASE . '/plugins'); ?>"></script>
    <script type="text/javascript" src="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleJsCache(THEMEBASE . '/modules'); ?>"></script>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.0-alpha.35/swiper-bundle.min.js" integrity="sha512-D1vIXFpf8Y81wm7PVp4it9AigVuZkH5o93Df9BfBS83f/hTK8KnIR+qx5haxgAcZLuAofQ16pcOPtVsIBhX4UQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="page_<?php echo Url::doSeo($this->segments[0]); ?>" <?php echo (in_array(Core::$language, array("ar", "he"))) ? 'dir="rtl"' : ''; ?>>
<?php if ($this->core->ploader): ?>
    <!-- Page Loader -->
    <div id="master-loader">
        <div class="wanimation"></div>
        <div class="curtains left"></div>
        <div class="curtains right"></div>
    </div>
<?php endif; ?>
<header id="header">
    <div class="yoyo-grid">
        <div class="top-bar">

            <ul class="topbarul">
                <li class="lgoholder">
                    <a href="<?php echo SITEURL; ?>/" class="logo">
                        <?php if(!Filter::isMobile()): ?>
                        <?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?>
                        <?php else: ?>
                           <img src="/uploads/mobile-logo.png" alt="GambaBet Logo">
                        <?php endif; ?>
                    </a>
                </li>


                <!--<li class="srbarr">
                    <div class="wrap">
                        <div class="search">
                            <input type="text" class="searchTerm" placeholder="<?= Lang::$word->SEARCH_BY_EVENT_NAME; ?>">
                            <button type="submit" class="searchButton"><i class="icon find"></i>
                            </button>
                        </div>
                        <div id="resulting"></div>
                    </div>
                </li>-->


                <li class="usernotify">

                    <?php //if($this->core->showlogin):?>
                    <?php if (App::Auth()->is_User()): ?>

                        <div href="#" id="acuser">
              <span class="cchips"> <span class="cicon">
			  <?php $usid = App::Auth()->uid;
              $gu = Db::run()->first(Users::mTable, array("stripe_cus", "chips", "promo"), array("id" => $usid));
              $cur = $gu->stripe_cus;
              $moo = $gu->chips;
              echo $cur; ?></span> <span class="cbalf"> <?php echo number_format((float)$moo, 2, '.', ''); ?></span></span>
                            <span class="activeusr">u</span>
                        </div>
                        <?php include_once("__header_notification.php"); ?>
                        <?php include_once("__logged_navigation.php"); ?>


                    <?php else: ?>
                        <div class="item login" id="logjoin">
                            <?php if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                                $url = "https://";
                            else
                                $url = "http://";
                            // Append the host(domain name, ip) to the URL.
                            $url .= $_SERVER['HTTP_HOST'];

                            // Append the requested resource location to the URL
                            $xurl .= $_SERVER['REQUEST_URI'];
                            ?>
                            <a href="/login/?cref=<?php echo $xurl; ?>" class="joinuri"><?= Lang::$word->LOGIN; ?>
                            </a>
                        </div>

                        <div class="item" id="logjoin">
                            <?php if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                                $url = "https://";
                            else
                                $url = "http://";
                            // Append the host(domain name, ip) to the URL.
                            $url .= $_SERVER['HTTP_HOST'];

                            // Append the requested resource location to the URL
                            $xurl .= $_SERVER['REQUEST_URI'];
                            ?>
                            <a href="/registration/?cref=<?php echo $xurl; ?>" class="joinuri"><?= Lang::$word->REGISTER; ?>
                            </a>
                        </div>

                        <?php include_once("__header_notification.php"); ?>


                    <?php endif; ?>
                    <?php //endif;?>
                </li>

                <?php if(!Filter::isMobile()): ?>
                <li style="float: right">
                    <a data-dropdown="#dropdown-langChange" class="yoyo mini secondary button">
                        <div class="description">
                            <span class="flag icon en" id="repcl"></span>
                            <!--<span id="ltext" class="ttxt active"><?= Lang::$word->ENGLISH; ?> </span>-->
                        </div>
                        <i class="icon small chevron down" id="cvvr"></i>
                    </a>
                    <div class="yoyo small dropdown menu top-center" id="dropdown-langChange">
                        <?php include_once('language_list.php'); ?>
                    </div>
                </li>

                <li style="float: right">
                    <label class="timer"></label>
                </li>

                <?php endif; ?>

            </ul>


        </div>
    </div>
    </br></br>


    <style type="text/css">

        label.timer {
            color: #fff;
            vertical-align: middle;
            display: block;
            padding-top: 8px;
        }

        .To.Win.the.Toss {
            display: none;
        }

        ._homeIndex {
            min-height: 700px;
        }

        .yoyo.horizontal.list.xp {
            position: relative;
            float: left;
        }

        .cgcolor {
            background: #f00;
            color: #fff;
        }

        .showtopnotes::after {
            width: 0;
            height: 0;
            content: '';
            display: inline-block;
            position: absolute;
            border-color: transparent;
            border-style: solid;
            -webkit-transform: rotate(
                    360deg
            );
            border-width: 0 8px 10px;
            border-bottom-color: #eb1515;
            top: -11px;
            right: 8px;
        }

        ._homeIndex ._column._two {
            background: #1f1f1f;
        }


        .cbtn {
            background: #fff;
            padding: 10px;

            -moz-user-select: -moz-none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }


        body.page_suppagent #header, body.page_suppagent footer, body.page_affagent #header, body.page_affagent footer {
            display: none;
        }

        body.page_suppagent, body.page_agent-registration, body.page_affagent, body.page_agent-registration {
            background: #fff;
        }

        body.page_agent-registration #header, body.page_agent-registration footer {
            display: none;
        }


        /*agent top navigation*/
        ul.suppmenu {
            display: flex;
            width: 100%;
            padding: 0px;
            margin: 0 auto;
        }

        ul.suppmenu li {
            display: table-cell;
            width: 50%;
        }

        .sidenavs li {
            position: relative;
            display: block;
            width: 100%;
            padding: 10px 15px;
            border-bottom: 1px solid #e7e7e7;
            cursor: pointer;
            color: #337ab7;
        }

        /*for profile dashboard list */
        ul.history_menu {
            width: 100%;
            padding: 20px 0px 0px 0px;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            margin: 0;
        }

        ul.history_menu li {
            display: inline-block;
            padding: 1px 10px;
            margin: 0px;
            color: #f1f1f1;
            font-size: 16px;
        }


        /*nweeew*/
        ul.topbarul {
            margin: 0;
            padding: 0;
        }

        ul.topbarul li {
            display: inline-block;
        }

        .item.xp {
            display: inline-block;
        }

        div#logjoin {
            display: inline-block;
            margin-top: 5px;
        }

        li.srbarr {
            width: 40%;
        }

        li.usernotify {
            float: right;
        }

        li.lgoholder {
            width: 20%;
        }

        li.usernotify > div {
            margin-left: 15px;
        }

        a.loginuri {
            margin-right: 50px;
        }

        ._homeIndex {
            margin-top: 20px;
        }

        ._homeIndex ._column._two {
            background: #1f1f1f;
        }


        .cbtn {
            background: #fff;
            padding: 10px;

            -moz-user-select: -moz-none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .text-center {
            text-align: center;
        }

        ._slip_container {
        }

        .search {
            width: 100%;
            position: relative;
            display: flex;
        }

        .searchTerm {
            width: 100%;
            border: 3px solid #fffbfb;
            border-right: none;
            padding: 15px;
            height: 20px;
            border-radius: 5px 0 0 5px;
            outline: none;
            color: #000;
        }

        .searchTerm:focus {
            color: #000;
        }

        .searchButton {
            width: 40px;
            height: 38px;
            border: 3px solid #bb2f2f;
            background: #bb2f2f;
            text-align: center;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 20px;
        }


        span#\30 {
            color: #e5dcdc !important;
            background: #767676 !important;
            font-size: 12px;
            padding-top: 2px;
        }

        a.dchat {
            display: block;
            background: #ffffff;
            border: solid 2px #fff;
            text-align: center;
            color: #000;
            padding: 5px;
            font-weight: 700;
            border-radius: 3px;
        }


        @media screen and (max-width: 767px) {
            .excol.ileft {
                display: none;
            }

            li.srbarr {
                display: none !Important;
            }

            a.loginuri {
                margin-right: 0px;
            }

            ._column._one {
                width: 100% !Important
            }

            ._divsp.tright {
                width: 100% !Important;
            }

            ._one {
                position: relative !important;
            }

            ._column._two {
                width: 100%;
                z-index: 9999;
                position: fixed;
                bottom: 0;
                height: auto;
            }


            .excol.icenter {
                width: 100%;
            }
        }
    </style>
</header>

<!-- Code provided by Google -->
<div id="google_translate_element" style="display:none"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'google_translate_element'); //remove the layout
    }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>


<script type="text/javascript">

    setTimeout(function () {
            var lang = $("select.goog-te-combo option:selected").text();
            if (lang == 'Select Language') {
                return;
            }
            $('.ttxt.active').text(lang);
            //var rep = $('#repcl').attr('class');
        },
        5000);


    var d = new Date();
    setInterval(function() {
        d.setSeconds(d.getSeconds() + 1);
        $('.timer').text((d.getHours() +':' + d.getMinutes() + ':' + d.getSeconds() ));
    }, 1000);

    function triggerHtmlEvent(element, eventName) {
        var event;
        if (document.createEvent) {
            event = document.createEvent('HTMLEvents');
            event.initEvent(eventName, true, true);
            element.dispatchEvent(event);
        } else {
            event = document.createEventObject();
            event.eventType = eventName;
            element.fireEvent('on' + event.eventType, event);
        }
    }

    <!-- Flag click handler -->
    $('.yoyo.small.dropdown.menu.top-center lan').click(function (e) {
        e.preventDefault();
        var lang = $(this).data('lang');
        $('#google_translate_element select option').each(function () {
            if ($(this).text().indexOf(lang) > -1) {
                $(this).parent().val($(this).val());
                var container = document.getElementById('google_translate_element');
                var select = container.getElementsByTagName('select')[0];
                triggerHtmlEvent(select, 'change');
            }
        });
        setTimeout(function () {
                lang = $("select.goog-te-combo option:selected").text();
                $('.ttxt.active').text(lang);
            },
            5000);
    });


    //search events
    $("body").on('change', 'input.searchTerm', function () {
        var es = $(this).val();
        if (es.length < 3) {
            $("div#resulting").html('');
            return;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL;?>/shell/search/events_search",
            data: {
                es: es,
                method: "events_search"
            },
            success: function (response) {

                $("div#resulting").html(response);

            }
        })
    })


    $(document).on('click', function (e) {
        if ($(e.target).closest("input.searchTerm").length === 0) {
            $("div#resulting").html('');
        }
    });
</script>