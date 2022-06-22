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
$spid = $_POST['spid'];
$evid = $_POST['evid'];

require_once('../../init.php');

?>
<div class="_row">
    <div class="_column _one">
        <?php require_once('../exchange/top-horizontal-sports.php'); ?>


        <div class="_rowdivider" style="width: 100%;">
            <?php $ge = mysqli_query($conn, "SELECT event_id, event_name,spid,sname,cc FROM af_pre_bet_events WHERE spid=" . $_POST['spid'] . " AND UNIX_TIMESTAMP() < (deadline - $dline) GROUP BY event_id ORDER BY deadline ASC");
            ?>
            <?php if ($device == 'desktop'): ?>
                <!-- FOR FILTER BY SPORTS (SIDEBAR) -->
                <div class="_divsp tleft">
                    <div class="qlinks fr"><?= Lang::$word->BROWSE_BY_LEAGUE; ?></div>
                    <ul class="crprelist crack" id="crlisidebar">

                        <?php while ($kg = mysqli_fetch_assoc($ge)) { ?>
                            <li id="<?php echo $kg['event_id']; ?>" class="scrapT">
                                <span class="sp_sprit <?php echo $kg['sname']; ?>">!</span>
                                <div id="tmtwrapper">
                                    <?php echo $kg['event_name']; ?></br>
                                    <span class="srkkg">
                                      <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $kg['cc']))})): ?>
                                          <?= Lang::$word->{strtoupper(str_replace(' ', '_', $kg['cc']))}; ?>
                                      <?php else: ?>
                                          <?= $kg['cc']; ?>
                                      <?php endif; ?>
                                    </span>
                                </div>

                                <span class="crcount" id="jk<?php echo $kg['event_id']; ?>">+</span>
                                <div class="shleague" id="dk<?php echo $kg['event_id']; ?>"></div>
                            </li>
                        <?php } ?>
                    </ul>

                </div>


            <?php else: ?>
                <div class="_divsp tleft" style="width:100%">
                    <div class="qlinks fr"><?= Lang::$word->BROWSE_BY_LEAGUE; ?></div>
                    <div id="hdmob">
                        <ul class="crprelist crack" id="xcrlisidebar">

                            <?php while ($kg = mysqli_fetch_assoc($ge)) { ?>
                                <li id="<?php echo $kg['event_id']; ?>" class="scrapT">
                                    <span class="sp_sprit <?php echo $kg['sname']; ?>">!</span>
                                    <div id="tmtwrapper">
                                        <?php echo $kg['event_name']; ?></br>
                                        <span class="srkkg">
                                          <?php if (isset(Lang::$word->{strtoupper(str_replace(' ', '_', $kg['cc']))})): ?>
                                              <?= Lang::$word->{strtoupper(str_replace(' ', '_', $kg['cc']))}; ?>
                                          <?php else: ?>
                                              <?= $kg['cc']; ?>
                                          <?php endif; ?>
                                        </span>
                                    </div>

                                    <span class="crcount" id="jk<?php echo $kg['event_id']; ?>">+</span>
                                    <div class="shleague" id="dk<?php echo $kg['event_id']; ?>"></div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>


            <div class="_divsp tright">

                <div class="setStats">
                    <?php include_once("__stats.php"); ?>
                </div>

                <div class="toplvbhgh"><span class="_lt10"><?= Lang::$word->LIVE_MARKETS; ?></span> <span class="_lt11"><i id="cmrr" class="icon camera alt"></i></span>
                </div>
                <ul class="splivehome">
                    <li class="toplvblt cact">
                        <i id="lvice" class="icon icecream"></i> <?= Lang::$word->UPCOMING_EVENTS; ?></li>
                    <a class="toplvblts" href="/live">
                        <li class="toplvblt"><i id="rzcmg" class="icon resize"></i> <?= Lang::$word->LIVE_EVENTS; ?>
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
        <?php include_once("../exchange/__common_slip.php"); ?>
    </div>
</div>
