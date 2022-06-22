<?php
header("Content-Type:application/json");
// /games/au_ewallet?opKey=DffdhzEPup2MMJLH&userId=1
if (isset($_POST['opKey']) && $_POST['userId']!="" && $_POST['opKey'] == "DffdhzEPup2MMJLH") {
 include('db.php');
 $userId = $_POST['userId'];
 $gameId = $_POST['gameId'];
 $bet = $_POST['bet'];
 $win = $_POST['win'];
 $difference = $_POST['diff'];
 $gameAct = $_POST['gameAct'];
 $gameHand = $_POST['gameHand'];
 $created_at = $_POST['timestamp'];
 $updated_at = time();
 
 mysqli_query($con, "UPDATE sh_usermeta SET meta_value = meta_value + ".$difference." WHERE user_id = ".$userId." AND meta_key = 'sf_points'");
 if(mysqli_affected_rows($conn) > 0){
	 mysqli_query($conn, "INSERT INTO `sh_history_games` (`user_id`, `game_name`, `bet`, `win`, `diff`, `state`, `icon`, `created_at`, `updated_at`) VALUES ($userId, '$gameId', $bet, $win, $difference, '$gameAct', '$gameHand', $created_at, $updated_at)");
	 if(mysqli_affected_rows($conn) > 0){
		 echo 'Success!';
		 exit;
	 }else {
		 echo 'Failed to update';
		 die();
	 }
 }
}
$con -> close();
?>