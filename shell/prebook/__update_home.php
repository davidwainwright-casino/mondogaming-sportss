<?php include_once('../db.php');
$rsk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT deadline FROM risk_management"));
$dline = $rsk['deadline'];
$device = $_POST['device'];
$btype = $_POST['btype'];

require_once('../../init.php');
?>

<?php require_once '../components/grid-header-prebook.php'; ?>

<div class="toplvbhgh"><span class="_lt10"><?= Lang::$word->LIVE_HIGHLIGHTS; ?></span>
    <span class="_lt11"><i id="cmrr" class="icon camera alt"></i></span>
</div>

<?php
$ge = mysqli_query($conn, "SELECT spid,sname, count(bet_event_id) AS spcount FROM af_inplay_bet_events GROUP BY spid ORDER BY spid ASC");
$cc = mysqli_query($conn, "SELECT cc, count(bet_event_id) AS spcount FROM af_inplay_bet_events GROUP BY cc ORDER BY cc ASC");
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
    <li class="toplvblt cact"><i id="lvice" class="icon icecream"></i> <?= Lang::$word->LIVE_EVENTS; ?></li>
    <a class="toplvblts" href="/">
        <li class="toplvblt"><i id="rzcmg" class="icon resize"></i> <?= Lang::$word->UPCOMING_EVENTS; ?></li>
    </a>
</ul>


<?php include_once("__odd_format_functions.php"); ?>
	