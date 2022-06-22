<?php

include_once('../db.php');
require '../../vendor/autoload.php';


use Google\Cloud\Translate\V2\TranslateClient;

$translate = new TranslateClient([
    'key' => 'AIzaSyAz03wNyNKcFHbanTp8KnrXy73tdxjws28'
]);

// ---------------------------------------------------------------------------------------------------------------------

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---------------------------------------------------------------------------------------------------------------------

$lang_file = '../../lang/he/dynamic/lang.de';

$lang_data = array();

if (file_exists($lang_file)) {
    $lang_data = file_get_contents($lang_file);
    $lang_file_data_hash = hash('md5', $lang_data);

    $lang_data = unserialize(gzuncompress($lang_data));
} else {
    $storage_lang = gzcompress(serialize($lang_data));
    $lang_file_data_hash = hash('md5', $storage_lang);

    file_put_contents($lang_file, $storage_lang);
}


// ---------------------------------------------------------------------------------------------------------------------

// SELECT DISTINCT bet_event_cat_name FROM af_pre_bet_events_cats

$data = mysqli_query($conn, "SELECT `bet_event_name`, `event_name` FROM af_pre_bet_events;");

while ($event = mysqli_fetch_assoc($data)) {
    $events[] = $event;
}

foreach ($events as $event) {

    foreach ($event as $key => $string) {

        $event_key = strtoupper(str_replace(' ', '_', $string));

        if (isset($lang_data[$event_key])) continue;

        // Remove uppercase words from name
        $event = explode(' ', $string);

        if (count($event) > 1) {
            foreach ($event as $word_key => $word) {
                if (ctype_upper($word)) {
                    unset($event[$word_key]);
                }
            }
        }

        $event = implode(' ', $event);

        if ($key == 'bet_event_name') {
            $event = str_replace(' - ', ' vs ', $event);
        }

        $result = $translate->translate($event, [
            'target' => 'he'
        ]);

        //$result['text'] = str_replace('vs', '-', $result['text']);
        $result['text'] = str_replace('מול', '-', $result['text']);
        $result['text'] = str_replace('נגד', '-', $result['text']);
        $result['text'] = str_replace('לעומת', '-', $result['text']);
        $result['text'] = str_replace('(W)', '(נשים)', $result['text']);

        $lang_data[$event_key] = $result['text'];
    }

}


/*$data = mysqli_query($conn, "SELECT DISTINCT `bet_event_cat_name` FROM af_pre_bet_events_cats;");

while ($event = mysqli_fetch_assoc($data)) {
    $events[] = $event;
}

foreach ($events as $event) {

    foreach ($event as $key => $string) {

        $event_key = strtoupper(str_replace(' ', '_', $string));

        echo '<br>';
        echo '<br>';
        var_dump($translate->detectLanguage($string));
        echo '<br>';
        echo '<br>';

        if (isset($lang_data[$event_key])) continue;

        $result = $translate->translate($event, [
            'source' => 'uk',
            'target' => 'he'
        ]);

        echo $lang_data[$event_key] = $result['text'];
        die();

    }

}
*/

echo '<pre>';
print_r($lang_data);
echo '</pre>';


$lang_data = gzcompress(serialize($lang_data));

if ($lang_file_data_hash != hash('md5', $lang_data)) {
    file_put_contents($lang_file, $lang_data);
}


//var_dump($rows);


/*$translate = new TranslateClient([
    'key' => 'AIzaSyAz03wNyNKcFHbanTp8KnrXy73tdxjws28'
]);

// Translate text from english to french.
$result = $translate->translate('Ludogorets vs Malmö', [
    'target' => 'he'
]);

echo $result['text'] . "\n";
*/

/*
$lang = array(
    'FC-AstoriaWalldorf-SGSonnenhofGrossaspach' => 'אסטוריה וולדורף נגד זוננהוף גרוספאך',
    'Somerset-LancashireLightning' => 'Somerset - Lancashire Lightning'
);

$lang_file = '../../lang/he/dynamic/lang.de';
if(file_exists($lang_file)) {
    $lang_data = file_get_contents($lang_file);
    $lang_data = unserialize(gzuncompress($lang_data));

    $lang_data['test'] = '123';

    print_r($lang_data);

    //$lang_data = gzcompress( serialize( $lang_data ) );
    //file_put_contents($lang_file,  $lang_data);

}
else {
    $storage_lang = gzcompress( serialize( $lang ) );

    file_put_contents($lang_file,  $storage_lang);
    echo 'done';
}*/