
<?php $query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM af_inplay_bet_events JOIN af_inplay_bet_events_cats ON af_inplay_bet_events.bet_event_id=af_inplay_bet_events_cats.bet_event_id WHERE af_inplay_bet_events.is_active=1 ORDER BY af_inplay_bet_events.feat=1, af_inplay_bet_events.spid ASC LIMIT 1"));

$cat_id = $query['bet_event_cat_id'];
$fnr = explode(':', $query['ss']);
$ss_home = $fnr[0];
$ss_away = $fnr[1];
$query['bet_event_name'] = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($query['bet_event_name'])])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($query['bet_event_name'])] : $query['bet_event_name'];
$tnr = explode('-', $query['bet_event_name']);
$ts_home = $tnr[0];
$ts_away = $tnr[1];
$query['event_name'] = (isset(Dynamic_Lang::$word[Dynamic_Lang::Key($query['event_name'])])) ? Dynamic_Lang::$word[Dynamic_Lang::Key($query['event_name'])] : $query['event_name'];
?>

<div class="topmatchbg bg">
<span class="holder">
	  <span class="livenow">
		<span></span>
	  </span>

	<a class="onevent" id="<?php echo $query['bet_event_id']; ?>" href="/events/?pid=<?php echo $query['bet_event_id']; ?>&sp=<?php echo $query['spid']; ?>">
        <div class="tophg"><?= Lang::$word->LIVE_HIGHLIGHTS; ?></div>
        <div class="lignss">
            <span class="sp_sprit <?php echo $query['sname']; ?>">!</span><?php echo $query['event_name']; ?>
        </div>

        <div class="tpeventwrap">
            <div class="topshss tp"> <span class="lltm"><i id="ixhome" class="icon shirt"></i> <?php echo $ts_home;//substr($ts_home, 0, 24); ?></span> <span class="rrtm"><?php echo $ss_home; ?></span></div>
            <div class="topshss btm"> <span class="lltm"><i id="ixaway" class="icon shirt"></i> <?php echo $ts_away;//substr($ts_away, 0, 24); ?></span> <span class="rrtm"><?php echo $ss_away; ?></span></div>
        </div>

        <ul class="showdra">
            <?php $getCon = mysqli_query($conn, "SELECT bet_option_name, bet_option_odd FROM af_inplay_bet_options WHERE bet_event_cat_id=$cat_id LIMIT 3");

            while ($record = mysqli_fetch_assoc($getCon)) {
                ?>
                <li>
                    <?php
                    echo '<div class="_naod">' . substr($record['bet_option_name'], 0, 14) . '</div>';
                    echo '<div class="_oaod">' . $record['bet_option_odd'] . '</div>'; ?>
                </li>
                <?php
            } ?>
        </ul>
    </a>


</div>