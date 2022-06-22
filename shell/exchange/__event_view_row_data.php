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


        <style>
            .w-100 {
                width: 100%;
            }
        </style>


        <div>
            <?php include_once('top-horizontal-sports.php'); ?>
        </div>

        <div class="_rowdivider w-100">

            <?php if ($device == 'desktop'): ?>
                <!-- FOR FILTER BY SPORTS (SIDEBAR) -->
                <div class="_divsp tleft">
                    <?php $ge = mysqli_query($conn, "SELECT * FROM af_inplay_bet_events WHERE is_active=1 ORDER BY spid=4,spid ASC");
                    $cnt = mysqli_num_rows(mysqli_query($conn, "SELECT bet_event_id FROM `af_inplay_bet_events`")); ?>
                    <div class="qlinks fr"><?= Lang::$word->LIST_OF_LIVE_EVENTS; ?> (<?php echo $cnt; ?>)</div>
                    <ul class="crprelist" id="crlisidebar">
                        <?php while ($kg = mysqli_fetch_assoc($ge)) { ?>
                            <li class="<?php echo $kg['spid']; ?>" id="<?php echo $kg['bet_event_id']; ?>">
                                <span class="sp_sprit <?php echo $kg['sname']; ?>">!</span>
                                <a class="datalink<?php echo $kg['bet_event_id']; ?>">
                                    <div id="tmtwrapper"> <?php $br = $kg['bet_event_name'];
                                        $br = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($br)])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($br)] : $br;
                                        $br = str_replace('vs', '-', $br);
                                        $br = str_replace(' - ', ' - ', $br);
                                        $fn = explode(' - ', $br);
                                        echo '<span class="hmtmt">' . $fn[0] . '</span>';
                                        echo '</br>';
                                        echo '<span class="hmtmt">' . $fn[1] . '</span>'; ?></div>
                                </a> <span class="crcount">(<?php echo $kg['ss']; ?>)</span></li>
                        <?php } ?>
                    </ul>
                </div>

            <?php else: ?>
                <div class="_divsp tleft" style="width:100%">
                    <?php $ge = mysqli_query($conn, "SELECT * FROM af_inplay_bet_events WHERE is_active=1 ORDER BY spid=4,spid ASC");
                    $cnt = mysqli_num_rows(mysqli_query($conn, "SELECT bet_event_id FROM `af_inplay_bet_events`")); ?>
                    <div class="qlinks fr"><?= Lang::$word->LIST_OF_LIVE_EVENTS; ?> (<?php echo $cnt; ?>)</div>
                    <div id="hdmob">
                        <ul class="crprelist fk" id="crlisidebar">
                            <?php while ($kg = mysqli_fetch_assoc($ge)) { ?>
                                <li class="<?php echo $kg['spid']; ?>" id="<?php echo $kg['bet_event_id']; ?>">
                                    <span class="sp_sprit <?php echo $kg['sname']; ?>">!</span>
                                    <a class="datalink<?php echo $kg['bet_event_id']; ?>">
                                        <div id="tmtwrapper"> <?php $br = $kg['bet_event_name'];
                                            $fn = explode(' - ', $br);
                                            echo '<span class="hmtmt">' . $fn[0] . '</span>';
                                            echo '</br>';
                                            echo '<span class="hmtmt">' . $fn[1] . '</span>'; ?></div>
                                    </a> <span class="crcount">(<?php echo $kg['ss']; ?>)</span></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>


            <div class="_divsp tright">

                <div class="setStats">
                    <?php include_once("__stats.php"); ?>
                </div>

                <div class="toplvbhgh"><span class="_lt10"><?= Lang::$word->LIVE_MARKETS; ?></span>
                    <span class="_lt11"><i id="cmrr" class="icon camera alt"></i></span></div>
                <ul class="splivehome">
                    <li class="toplvblt cact"><i id="lvice" class="icon icecream"></i> <?= Lang::$word->LIVE_EVENTS; ?>
                    </li>
                    <a class="toplvblts" href="/sportsbook-prematch/">
                        <li class="toplvblt"><i id="rzcmg" class="icon resize"></i> <?= Lang::$word->UPCOMING_EVENTS; ?>
                        </li>
                    </a>
                </ul>


                <div id="shfullevent">
                    <?php include_once("__full_event_view.php"); ?>
                </div>

            </div>


        </div>


    </div>


    <div class="_column _two">
        <?php include_once("__common_slip.php"); ?>
    </div>
</div>
