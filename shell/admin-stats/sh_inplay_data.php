<?php error_reporting();include_once('../db.php');
//for awaiting tickets
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ttcount, COUNT(CASE WHEN type = 'back' THEN 1 END) AS tback, COUNT(CASE WHEN type = 'lay' THEN 1 END) AS tlay, COUNT(CASE WHEN type = 'sbook' THEN 1 END) AS tbook, COUNT(CASE WHEN FROM_UNIXTIME(date) >= CURDATE() THEN 1 END) AS ttoday FROM sh_sf_tickets_history WHERE status = 'awaiting'"));

//for settled count
$ddk = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS ikcount FROM sh_sf_tickets_history WHERE status <> 'awaiting'"));

//settled today
$dds = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS icount, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS twin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS tlose, COUNT(CASE WHEN status = 'canceled' THEN 1 END) AS tcan, SUM(CASE WHEN status <> 'awaiting' THEN stake END) AS svol, SUM(CASE WHEN status <> 'awaiting'  THEN winnings END) AS wvol, SUM(CASE WHEN status <> 'awaiting'  THEN odd END) AS ovol FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) > (NOW() - INTERVAL 1 DAY)"));

//settled this week
$ddw = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS icount, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS twin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS tlose, COUNT(CASE WHEN status = 'canceled' THEN 1 END) AS tcan, SUM(CASE WHEN status <> 'awaiting' THEN stake END) AS svol, SUM(CASE WHEN status <> 'awaiting'  THEN winnings END) AS wvol, SUM(CASE WHEN status <> 'awaiting'  THEN odd END) AS ovol FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY)"));

//settled this month
$ddm = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(CASE WHEN user_id <> 1 THEN 1 END) AS icount, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS twin, COUNT(CASE WHEN status = 'losing' THEN 1 END) AS tlose, COUNT(CASE WHEN status = 'canceled' THEN 1 END) AS tcan, SUM(CASE WHEN status <> 'awaiting' THEN stake END) AS svol, SUM(CASE WHEN status <> 'awaiting'  THEN winnings END) AS wvol, SUM(CASE WHEN status <> 'awaiting'  THEN odd END) AS ovol FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY)"));

//earnings calculations
$ear1 = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS wina, SUM(CASE WHEN status = 'losing'  THEN stake END) AS losea FROM sh_sf_tickets_history"));
$ear2 = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS wina, SUM(CASE WHEN status = 'losing'  THEN stake END) AS losea FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) > (NOW() - INTERVAL 7 DAY)"));
$ear3 = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(CASE WHEN status = 'winning' THEN winnings - stake END) AS wina, SUM(CASE WHEN status = 'losing'  THEN stake END) AS losea FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) > (NOW() - INTERVAL 1 DAY)"));

//players performance
$pp = "SELECT user_id AS uid, COUNT(CASE WHEN status = 'winning' THEN 1 END) AS winslip, COUNT(CASE WHEN status IN('winning','losing') THEN 1 END) AS totslip, SUM(CASE WHEN status IN('winning', 'losing') THEN stake END) AS stakes, SUM(CASE WHEN status = 'winning'  THEN winnings - stake END) AS won FROM sh_sf_tickets_history WHERE status IN('winning', 'losing') AND FROM_UNIXTIME(date) > (NOW() - INTERVAL 30 DAY) GROUP BY user_id ORDER BY won DESC LIMIT 100";
$result = $conn->query($pp);

?>

<h2>Earnings Stats</h2>
<div class="awtick">

<div class="shpre">All Time <span class="cotfig te"><?php echo round($ear1['losea'] - $ear1['wina'], 2);?></span></div>
<div class="shpre">This Week <span class="cotfig te"><?php echo round($ear2['losea'] - $ear2['wina'], 2);?></span></div>
<div class="shpre">Today <span class="cotfig te"><?php echo round($ear3['losea'] - $ear3['wina'], 2);?></span></div>
</div>

<hr>

<h2>Awaiting Tickets</h2>
<div class="awtick">

<div class="shpre">Total Tickets <span class="cotfig"><?php echo $ccs['ttcount'];?></span></div>
<div class="shpre">Today's Tickets <span class="cotfig"><?php echo $ccs['ttoday'];?></span></div>
<div class="shpre">Back <span class="cotfig"><?php echo $ccs['tback'];?></span></div>
<div class="shpre">Lay <span class="cotfig"><?php echo $ccs['tlay'];?></span></div>
<div class="shpre">SportsBook <span class="cotfig"><?php echo $ccs['tbook'];?></span></div>
<div class="shpre">Others <span class="cotfig">0.00</span></div>
</div>


<hr>
<h2 style="display:inline-block">Settled Tickets (<?php echo $ddk['ikcount'];?>)</h2><a id="revstatx">Expand..</a>
<div class="shstatsx">

<h3>Today's Statistics (<?php echo $dds['icount'];?>)</h3>
<div class="shpre">Winning Tickets <span class="cotfig xi"><?php echo $dds['twin'];?></span></div>
<div class="shpre">Losing Tickets <span class="cotfig xi"><?php echo $dds['tlose'];?></span></div>
<div class="shpre">Canceled Tickets <span class="cotfig xi"><?php echo $dds['tcan'];?></span></div>

<div class="shpre">Stake Volume <span class="cotfig xid"><?php echo $dds['svol'];?></span></div>
<div class="shpre">Winnings Volumne <span class="cotfig xid"><?php echo $dds['wvol'];?></span></div>
<div class="shpre">Total Odds <span class="cotfig xid"><?php echo $dds['ovol'];?></span></div>


<hr>
<h3>This Week (<?php echo $dds['icount'];?>)</h3>
<div class="shpre">Winning Tickets <span class="cotfig xi"><?php echo $ddw['twin'];?></span></div>
<div class="shpre">Losing Tickets <span class="cotfig xi"><?php echo $ddw['tlose'];?></span></div>
<div class="shpre">Canceled Tickets <span class="cotfig xi"><?php echo $ddw['tcan'];?></span></div>

<div class="shpre">Stake Volume <span class="cotfig xid"><?php echo $ddw['svol'];?></span></div>
<div class="shpre">Winnings Volumne <span class="cotfig xid"><?php echo $ddw['wvol'];?></span></div>
<div class="shpre">Total Odds <span class="cotfig xid"><?php echo $ddw['ovol'];?></span></div>
<hr>
<h3>This Month (<?php echo $dds['icount'];?>)</h3>
<div class="shpre">Winning Tickets <span class="cotfig xi"><?php echo $ddm['twin'];?></span></div>
<div class="shpre">Losing Tickets <span class="cotfig xi"><?php echo $ddm['tlose'];?></span></div>
<div class="shpre">Canceled Tickets <span class="cotfig xi"><?php echo $ddm['tcan'];?></span></div>

<div class="shpre">Stake Volume <span class="cotfig xid"><?php echo $ddm['svol'];?></span></div>
<div class="shpre">Winnings Volumne <span class="cotfig xid"><?php echo $ddm['wvol'];?></span></div>
<div class="shpre">Total Odds <span class="cotfig xid"><?php echo $ddm['ovol'];?></span></div>
</div>





<h2 style="display:inline-block">Players Performances</h2>
<a id="playstatx">Expand..</a>
<p class="primerperx">
<h4>Last 30 Days records</h4>
Note* It's always adviseable to limit or suspend consistent winners to avoid foulplay. Look at NET(winnings) and win% carefully. The more NET and win% of high WC means consitent winners. Suspend or restrict for such consitent players. Win% is calculated based on number of games played against number of winnings. Click on the ID to see that user's profile. Terms: UID = User ID. TT = Total Tickets. WC = Win Count(tickets). Net = Net Win amount by the user.
</p>
<div class="rankplayx"> 



<table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">UID</th>
		<th data-sort="int">TT</th>
        <th data-sort="string">WC</th>
        <th data-sort="int">Won</th>
		<th data-sort="int">Net</th>
        <th data-sort="int">Win%</th>

      </tr>
    </thead> 
	
<?php foreach($result as $pf){?>	
	<tr>
	<td><a href="/admin/bet-history/?usid=<?php echo $pf['uid'];?>"><?php echo $pf['uid'];?></a></td>
	<td><?php echo $pf['totslip'];?></td>
	<td><?php echo $pf['winslip'];?></td>
	<td><?php echo round($pf['won'], 2);?></td>
	<td><?php echo round($pf['won'] - $pf['stakes'], 2);?></td>
	<td><?php $cof = $pf['winslip'] * 100/$pf['totslip'];?> 
	<?php if($pf['totslip'] > 100 && $cof > 80){ 
	    echo '<span style="color:#f00">'.round($cof,2).'</span>';
	} else {
		echo round($cof,2);
	}?>%</td>
	
	</tr>
<?php }?>
	
	
	</table>





</div>








