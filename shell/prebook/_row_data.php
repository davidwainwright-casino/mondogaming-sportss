<?php include_once('../db.php');
$rsk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mn_b, mx_b, max_win, deadline FROM risk_management"));
$min_bet = $rsk['mn_b'];
$max_bet = $rsk['mx_b'];
$dline = $rsk['deadline'];
$device = $_POST['device'];
$max_win = $rsk['max_win'];
$usid = $_POST['usid'];
$ucr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT chips, promo FROM users WHERE id=$usid"));
$bons_balance = $ucr['promo'];
$chips = $ucr['chips'];

require_once('../../init.php');
?>

<div class="_row">
    <div class="_column _one">
        <?php include_once('../exchange/top-horizontal-sports.php'); ?>


        <div class="_rowdivider">
            <?php if ($device == 'desktop'): ?>
                <!-- FOR FILTER BY SPORTS (SIDEBAR) -->
                <div class="_divsp tleft">
                    <?php $ge = mysqli_query($conn, "SELECT spid,sname, count(bet_event_id) AS spcount FROM af_pre_bet_events WHERE UNIX_TIMESTAMP() < (deadline - $dline) GROUP BY spid ORDER BY spid ASC");

                    $cc = mysqli_query($conn, "SELECT cc, count(bet_event_id) AS spcount FROM af_pre_bet_events WHERE UNIX_TIMESTAMP() < (deadline - $dline) GROUP BY cc ORDER BY cc ASC"); ?>
                    <div class="qlinks fr"><?= Lang::$word->UPCOMING_SPORTS; ?></div>

                    <ul class="crprelist" id="crlisidebar">
                        <?php while ($kg = mysqli_fetch_assoc($ge)) { ?>
                            <li id="<?php echo $kg['spid']; ?>" data-position="<?php echo $kg['spcount']; ?>">
                                <span class="sp_sprit <?php echo $kg['sname']; ?>">!</span>
                                <a class="datalink">
                                    <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $kg['sname']))})): ?>
                                        <?= Lang::$word->{strtoupper(str_replace(' ', '_', $kg['sname']))}; ?>
                                    <?php else: ?>
                                        <?= $kg['sname']; ?>
                                    <?php endif; ?>
                                </a>
                                <span class="crcount">(<?php echo $kg['spcount']; ?>)</span></li>
                        <?php } ?>
                    </ul>

                    <!-- FOR FILTER BY COUNTRY (SIDEBAR) -->
                    <div class="qlinks fr cr"><?= Lang::$word->SPORTS_BY_COUNTRY; ?></div>
                    <ul class="crprelist" id="cccrlisidebar">
                        <?php while ($ckg = mysqli_fetch_assoc($cc)) { ?>
                            <li id="<?php echo $ckg['cc']; ?>" data-position="<?php echo $ckg['spcount']; ?>">
                                <i id="cflags" class="icon flag"></i>
                                <a class="datalink">
                                    <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $ckg['cc']))})): ?>
                                        <?= Lang::$word->{strtoupper(str_replace(' ', '_', $ckg['cc']))}; ?>
                                    <?php else: ?>
                                        <?= $ckg['cc']; ?>
                                    <?php endif; ?>
                                </a>
                                <span class="crcount">(<?php echo $ckg['spcount']; ?>)</span></li>
                        <?php } ?>
                    </ul>
                </div>


            <?php else: ?>
                <?php $cnt = mysqli_num_rows(mysqli_query($conn, "SELECT bet_event_id FROM `af_pre_bet_events`")); ?>
                <!-- FOR FILTER BY SPORTS (SIDEBAR) -->
                <div class="_divsp tleft" style="width:100%">

                    <?php $ge = mysqli_query($conn, "SELECT spid,sname, count(bet_event_id) AS spcount FROM af_pre_bet_events WHERE UNIX_TIMESTAMP() < (deadline - $dline) GROUP BY spid ORDER BY spid ASC");

                    $cc = mysqli_query($conn, "SELECT cc, count(bet_event_id) AS spcount FROM af_pre_bet_events WHERE UNIX_TIMESTAMP() < (deadline - $dline) GROUP BY cc ORDER BY cc ASC"); ?>
                    <div class="qlinks fr"><?= Lang::$word->BROWSE_BY_SPORT_AND_COUNTRY_WISE; ?> (<?php echo $cnt; ?>)</div>
                    <div id="hdmob">
                        <ul class="crprelist" id="crlisidebar">
                            <?php while ($kg = mysqli_fetch_assoc($ge)) { ?>
                                <li id="<?php echo $kg['spid']; ?>" data-position="<?php echo $kg['spcount']; ?>">
                                    <span class="sp_sprit <?php echo $kg['sname']; ?>">!</span>
                                    <a class="datalink">
                                        <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $kg['sname']))})): ?>
                                            <?= Lang::$word->{strtoupper(str_replace(' ', '_', $kg['sname']))}; ?>
                                        <?php else: ?>
                                            <?= $kg['sname']; ?>
                                        <?php endif; ?>
                                    </a>
                                    <span class="crcount">(<?php echo $kg['spcount']; ?>)</span></li>
                            <?php } ?>
                        </ul>

                        <!-- FOR FILTER BY COUNTRY (SIDEBAR) -->

                        <ul class="crprelist fk" id="cccrlisidebar">
                            <?php while ($ckg = mysqli_fetch_assoc($cc)) { ?>
                                <li id="<?php echo $ckg['cc']; ?>" data-position="<?php echo $ckg['spcount']; ?>">
                                    <i id="cflags" class="icon flag"></i>
                                    <a class="datalink">
                                        <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $ckg['cc']))})): ?>
                                            <?= Lang::$word->{strtoupper(str_replace(' ', '_', $ckg['cc']))}; ?>
                                        <?php else: ?>
                                            <?= $ckg['cc']; ?>
                                        <?php endif; ?>
                                    </a>
                                    <span class="crcount">(<?php echo $ckg['spcount']; ?>)</span></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>

            <script>
                $(function () {
                    $("#crlisidebar li").sort(sort_li).appendTo('#crlisidebar');
                    $("#cccrlisidebar li").sort(sort_li).appendTo('#cccrlisidebar');

                    function sort_li(a, b) {
                        return ($(b).data('position')) > ($(a).data('position')) ? 1 : -1;
                    }
                });
            </script>

            <div class="_divsp tright">

                <?php require_once '../components/grid-header-prebook.php'; ?>

                <div class="toplvbhgh"><span class="_lt10"><?= Lang::$word->UPCOMING_HIGHLIGHTS; ?></span>
                    <span class="_lt11"><i id="cmrr" class="icon camera alt"></i></span>
                </div>

                <?php
                    $ge = mysqli_query($conn, "SELECT spid,sname, count(bet_event_id) AS spcount FROM af_pre_bet_events GROUP BY spid ORDER BY spid ASC");
                    $cc = mysqli_query($conn, "SELECT cc, count(bet_event_id) AS spcount FROM af_pre_bet_events GROUP BY cc ORDER BY cc ASC");
                ?>

                <div class="gambabet-categories-slider" data-type="prebook">
                    <div class="swiper-container sports-swiper">
                        <div class="swiper-wrapper">
                            <?php while ($kg = mysqli_fetch_assoc($ge)): ?>
                                <div class="swiper-slide">
                                    <a href="#/" id="<?php echo $kg['spid']; ?>">
                                        <img src="/view/front/themes/master/images/sport-categories/<?php echo str_replace(' ', '-', strtolower($kg['sname'])); ?>.svg" onerror="this.src='/view/front/themes/master/images/sport-categories/general-sport.svg'">
                                        <span>
                                            <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $kg['sname']))})): ?>
                                                <?= Lang::$word->{strtoupper(str_replace(' ', '_', $kg['sname']))}; ?>
                                            <?php else: ?>
                                                <?= $kg['sname']; ?>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <!-- navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>

                <!-- Initialize Swiper -->
                <script>
                    var gambaCategoriesSliderSettings;

                    $(document).ready(function () {
                        gambaCategoriesSliderSettings = {
                            slidesPerView: 10,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            breakpoints: {
                                100: {
                                    slidesPerView: 2,
                                },
                                250: {
                                    slidesPerView: 3,
                                },
                                300: {
                                    slidesPerView: 4,
                                },
                                640: {
                                    slidesPerView: 4,
                                },
                                768: {
                                    slidesPerView: 4,
                                },
                                1024: {
                                    slidesPerView: 8,
                                },
                            },
                        };

                        new Swiper(".sports-swiper", gambaCategoriesSliderSettings);
                    });
                </script>

                <ul class="splivehome">
                    <li class="toplvblt cact"><i id="lvice" class="icon icecream"></i> <?= Lang::$word->UPCOMING_EVENTS; ?></li>
                    <a class="toplvblts" href="/">
                        <li class="toplvblt"><i id="rzcmg" class="icon resize"></i> <?= Lang::$word->LIVE_EVENTS; ?></li>
                    </a>
                </ul>


                <div id="shfullevent">
                    <?php include_once("__odd_format_functions.php"); ?>
                </div>

            </div>


        </div>


    </div>


    <div class="_column _two">
        <?php include_once("../exchange/__common_slip.php"); ?>
    </div>
</div>
