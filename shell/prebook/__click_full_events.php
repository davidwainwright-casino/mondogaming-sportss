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


?>

<?php include_once('top-horizontal-sports.php'); ?>

<?php $query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM af_inplay_bet_events JOIN af_inplay_bet_events_cats ON af_inplay_bet_events.bet_event_id=af_inplay_bet_events_cats.bet_event_id WHERE af_inplay_bet_events.is_active=1 ORDER BY af_inplay_bet_events.feat=1, af_inplay_bet_events.spid ASC LIMIT 1"));

$cat_id = $query['bet_event_cat_id'];
$fnr = explode(':', $query['ss']);
$ss_home = $fnr[0];
$ss_away = $fnr[1];
$tnr = explode('-', $query['bet_event_name']);
$ts_home = $tnr[0];
$ts_away = $tnr[1];
?>

<div class="topmatchbg bg">
		  <span class="holder">
  <span class="livenow">
    <span></span>
  </span>

			<a class="onevent" id="<?php echo $query['bet_event_id']; ?>" href="#">
			<div class="tophg"><?= Lang::$word->LIVE_HIGHLIGHTS; ?></div>

                <div class="lignss">
                    <span class="sp_sprit <?php echo $query['sname']; ?>">!</span>
                    <?php echo $query['event_name']; ?>
                </div>

			<div class="tpeventwrap">
			<div class="topshss tp"> <span class="lltm"><i id="ixhome" class="icon shirt"></i> <?php echo $ts_home; ?></span> <span class="rrtm"><?php echo $ss_home; ?></span></div>
			<div class="topshss btm"> <span class="lltm"><i id="ixaway" class="icon shirt"></i> <?php echo $ts_away; ?></span> <span class="rrtm"><?php echo $ss_away; ?></span></div>
			</div>
			
			<ul class="showdra">
			<?php $getCon = mysqli_query($conn, "SELECT bet_option_name, bet_option_odd FROM af_inplay_bet_options WHERE bet_event_cat_id=$cat_id LIMIT 3");

            while ($record = mysqli_fetch_assoc($getCon)) {
                ?>

                <li>
				<?php
                echo '<div class="_naod">' . substr($record['bet_option_name'], 0, 18) . '</div>';
                echo '<div class="_oaod">' . $record['bet_option_odd'] . '</div>'; ?>
				</li>
                <?php
            } ?>
			</ul>
			</a>


</div>


<div class="_rowdivider">
    <?php if ($device == 'desktop'): ?>
        <!-- FOR FILTER BY SPORTS (SIDEBAR) -->
        <div class="_divsp tleft">
            <?php $ge = mysqli_query($conn, "SELECT * FROM af_inplay_bet_events WHERE is_active=1 ORDER BY spid ASC"); ?>
            <div class="qlinks fr">List of Live Events</div>
            <ul class="crprelist" id="crlisidebar">
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
    <?php endif; ?>


    <div class="_divsp tright">
        <div class="toplvbhgh"><span class="_lt10"><?= Lang::$word->LIVE_HIGHLIGHTS; ?></span>
            <span class="_lt11"><i id="cmrr" class="icon camera alt"></i></span></div>
        <ul class="splivehome">
            <li class="toplvblt cact"><i id="lvice" class="icon icecream"></i> Live Events</li>
            <li class="toplvblt"><i id="rzcmg" class="icon resize"></i> Upcoming Events</li>
        </ul>


        <div id="shfullevent">
            <?php include_once("__full_event_view.php"); ?>
        </div>

    </div>


</div>






		  
		  
		  
		  
		  
