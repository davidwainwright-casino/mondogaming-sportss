<?php error_reporting(0);
define("_YOYO", true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/config.ini.php');
    // Create connection
	if ( defined('DB_SERVER') && defined('DB_USER') && defined('DB_PASS') && defined('DB_DATABASE') ){
    $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	mysqli_set_charset($conn,"utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . $conn->connect_error);
    }
	}
	$curry = '$';
?>