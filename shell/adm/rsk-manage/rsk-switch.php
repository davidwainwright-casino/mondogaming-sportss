<?php error_reporting(0);include('../../db.php');

//for deposits
if(isset($_POST['method']) && $_POST['method'] == 'sswitch'):?>
<?php 
$ccs = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(bet_event_cat_id) AS catc FROM af_pre_bet_events_cats WHERE yn = 1")); $csc = $ccs['catc'];
$inplay = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(bet_event_cat_id) AS cati FROM af_inplay_bet_events_cats WHERE yn = 1")); $inp = $inplay['cati'];

//$csk = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(bet_event_cat_id) AS catc FROM af_pre_bet_events_cats WHERE yn = 2")); 
//$cff = $csk['catc'];
//$isk = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(bet_event_cat_id) AS cati FROM af_inplay_bet_events_cats WHERE yn = 2")); $cfk = $isk['cati'];
?>
 
 
 <h2><i class="icon badge"></i> Events Switch</h2>
 <p class="swinfo">This switch will disable entire sports of particular selection. If you want to disable single or few sports only, you can do it from prematch/inplay control page. Please note that even if you disable here, events will still be visible to the players but they won't be able to bet..</p>
 <hr>
 <h4> <i class="icon move horizontal"></i> FULL EVENTS SWITCH</h4>
 
 <div class="isact">
 <b>PRE-MATCH : </b> Is pre-match active?
 <?php if($csc > 10):?>
    <b>No</b> <span class="enablesp" id="pdisable">Enable</span>
 <?php else:?>
	<b>Yes</b> <span class="disablesp" id="pdisable">Disable</span>
 <?php endif;?>	
 </div>
 
 <div class="isact">
 <b>IN-PLAY : </b> Is In-Play active?
 <?php if($inp > 10):?>
    <b>No</b> <span class="enableinp" id="idisable">Enable</span>
 <?php else:?>
    <b>Yes</b> <span class="disableinp" id="idisable">Disable</span>
 <?php endif;?>	
 </div>
 
 
 
 
 
 
 
 
 <hr>
 <h4> <i class="icon move horizontal"></i> EMPTY DISABLED EVENTS</h4>
 <p class="swinfo">This button will delete all the events you disabled previously. You can do this to delete all those events which is disabled and event is over.</p>
 
 <div class="isact">
 Clean up all the disabled events? <span class="xdisablesp" id="cleani">Empty all</span>
 </div>
 </br></br>
 
 
 
 
 
 
 
 
 
 
  <hr>
 <h4> <i class="icon move horizontal"></i> EMPTY AND RELOAD PREMATCH DATA</h4>
 <p class="swinfo">This button will REGENERATE new data on Prematch. This may take several minutes. You can head over to other work. Remember, this will remove even custom events and disabled events. Literally, everything</p>
 
 <div class="isact">
 Clean up all prematch data? <span class="xdisablesp" id="cleanprematch">Reload Prematch</span>
 </div>
 </br>
 <h4> <i class="icon move horizontal"></i> EMPTY AND RELOAD IN-PLAY DATA</h4>
 <p class="swinfo">This button will REGENERATE new data on In-Play. This may take a minute or two</p>
 
 <div class="isact">
 Clean up all In-Play data? <span class="xdisablesp" id="cleaninplay">Reload In-Play</span>
 </div>
 </br>
  <hr>
 </br> 
 
 
 
 
 
 
 <h4>Disable Individual Sport (Ex & Sp)</h4>
 <div class="searchsr">
      <input type="text" class="spsearch" id="spsearch" placeholder="Search by sport ID..">
        <i id="findsr" class="icon find"></i>
 </div>
 
 <div class="spresult"></div>
 
 </br></br>

<?php elseif(isset($_POST['method']) && $_POST['method'] == 'cstake'):?>

<?php $rm = $conn->query("SELECT * FROM risk_management");
    $row = $rm->fetch_assoc();
	$min_chips = $row['mn_b'];
	$max_chips = $row['mx_b'];
	$min_promo = $row['pro_min'];
	$max_promo = $row['pro_max'];
	$max_win = $row['max_win'];


?>
 <h2><i class="icon database"></i> Stake Control</h2>
 <p class="swinfo">Apply minimum or maxmium bet amount on both chips betting and promo betting. This apply to entire users. For individual control limit see users panel</br>
 <b style="color:red"> info</b>: All the values are relative to <a>USD</a> base. So, any number you put is in <a>USD</a>.</p>
 <div class="rmessage"></div>
 <hr>
 <h4> <i class="icon move horizontal"></i> Chips</h4>
 <div class="isact">
 <b> Minimum Stake Allowed: </b> <input type="number" class="imnb" id="minval" value="<?php echo $min_chips;?>"></br></br>
 <b> Maximum Stake Allowed: </b> <input type="number" class="imxb" id="minval" value="<?php echo $max_chips;?>">
 </div>
 
 <hr>
 
 <h4> <i class="icon move horizontal"></i> Promos</h4>
 <div class="isact">
 <b> Minimum Stake Allowed: </b> <input type="number" class="pmnb" id="minval" value="<?php echo $min_promo;?>"></br></br>
 <b> Maximum Stake Allowed: </b> <input type="number" class="pmxb" id="minval" value="<?php echo $max_promo;?>">
 </div>
 
 <hr>
 
 
 <h4> <i class="icon move horizontal"></i> Maximum Winable/Payout</h4>
 <div class="isact">
 <b>Max. win allowed (in USD term): </b> <input type="number" class="pmnbm" id="minval" value="<?php echo $max_win;?>"></br></br>

 </div>
 
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'commi'):?>

 <?php $rm = $conn->query("SELECT * FROM risk_management");
    $row = $rm->fetch_assoc();
	$ex_comi = $row['ex_comi'];
	$sp_comi = $row['sp_comi'];
	$ex_sagents = $row['ex_sagents'];
	$sp_sagents = $row['sp_sagents'];


?>
 <h2><i class="icon cogs"></i> Commission Control</h2>
 <p class="swinfo">Frequently changing commission value is highly NOT recommended. Keep it fixed commission at once after proper analysis. Already existing commission balance doesn't change with the change of commission percentage. New commission % will start right after the updates. </p>
  <div class="rmessage"></div>
 <hr>
 <h4> <i class="icon move horizontal"></i> Agents</h4>
 <div class="isact">
 <b> Exchange Commission %: </b> <input type="number" class="cmnb" id="minvalc" value="<?php echo $ex_comi;?>"></br></br>
 <b> Sportsbook Commission %: </b> <input type="number" class="cmxb" id="minvalc" value="<?php echo $sp_comi;?>">
 </div>
 
 <h4> <i class="icon move horizontal"></i> Super Agents/Exchange brokers</h4>
 <p class="swinfo">Super Agents or exchange brokers earn on top of their agents commission. They don't earn commission directly from players.</p>
 <div class="isact">
 <b> Net Commission %: </b> <input type="number" class="kmnb" id="minvalc" value="<?php echo $ex_sagents;?>"></br></br>

 </div>
 
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'deadline'):?>
  <?php $rm = $conn->query("SELECT * FROM risk_management");
    $row = $rm->fetch_assoc();$dead = $row['deadline'];?>

    <h2><i class="icon clock"></i> Deadline Control</h2>
 <p class="swinfo">Deadline is time in seconds before the match starts. This is applicable only for Pre-match(both exchange and sportsbook). Betting on prematch will close 'X' seconds before the scheduled match starts date.. </p>
  <div class="rmessage"></div>
 <hr>
 <h4> <i class="icon move horizontal"></i> Exchange & Sportsbook Pre-Match</h4>
 <div class="isact">
 <b> Deadline in seconds : </b> <input type="number" class="cdeadline" id="minval" value="<?php echo $dead;?>"></br></br>
 </div>
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'umaxbet'):?>

 <h2><i class="icon users"></i> Maximum Bet per User</h2>
 <p class="swinfo">Here you can set maximum bet for each individual players. This is useful for limiting players who are consistent winners from betting large numbers. Keep input field empty or 0 if you don't want to limit the user.</p>
  <div class="rmessage"></div>
 <hr>
 <h4> <i class="icon move horizontal"></i> Type user ID or email</h4>
 <div class="searchsr">
      <input type="text" class="esearch" id="esearch" placeholder="Search by user ID or email ID..">
        <i id="findsr" class="icon find"></i>
 </div>
 
 <div class="eresult"></div>
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'slipsmanager'):?>
 <?php $pre = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS slid FROM sh_sf_slips_history")); $precount = $pre['slid'];
$inp = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(slip_id) AS inpl FROM sh_sf_tickets_history")); $inpcount = $inp['inpl'];
$ttcc = $precount + $inpcount;
$allc =  mysqli_fetch_assoc(mysqli_query($conn, "select (select count(id) from sh_users_credit_records) as count1,
  (select count(id) from sh_agents_credit_records) as count2, (select count(id) from sh_sf_points_log) as count3, (select count(id) from sh_sf_deposits) as count4, (select count(id) from sh_sf_withdraws) as count5, (select count(id) from sh_sf_transfers) as count6, (select count(id) from sh_sf_inbox) as count7"));
?>

 <h2><i class="icon files"></i> Manage Slips</h2>
 <p class="swinfo">Here you can take actions of slips. You shouldn't do this unless it's absolutely required because it will totally erase slip data without backup, and you won't be able to restore. Prior Backup is highly recommended before taking any actions. Marking awaiting tickets to trashed will not credit or debit balance.</p>
  
 <hr>
 <h4> <i class="icon move horizontal"></i> PRE-MATCH (both exchange & sportsbook) — <?php echo $precount;?></h4>
 
 <div class="isact">
  Mark all awaiting tickets as settled(trashed) <span class="enablesp" id="setall">Settle All</span></br>
  Empty trashed tickets <span class="enablesp" id="setall">Empty trashed</span></br>
  Empty all existing tickets <span class="enablesp" id="setall">Empty all</span>
 </div>
 
 <hr>
 <h4> <i class="icon move horizontal"></i> IN-PLAY (both exchange & sportsbook) — <?php echo $inpcount;?></h4>
 
 <div class="isact">
  Mark all awaiting tickets as settled(trashed) <span class="enablesp" id="inpall">Settle All</span></br>
  Empty trashed tickets <span class="enablesp" id="inpall">Empty trashed</span></br>
  Empty all existing tickets <span class="enablesp" id="inpall">Empty all</span>
 </div>
 <hr>
 <h4>DELETE HISTORY BY DATE/NUM DAYS</h4>
 <p class="swinfo">Input days older than those you want to delete.. For eg. delete tickets older than 30 days -- put 30. Make sure to back up before delete. This will imporve performance.</p>
 <div class="rmessage"></div>
 <hr>
 <h4>For Tickets/slips History (<?php echo $ttcc;?>)</h4>
 Delete Older than X days: <input type="number" class="delta" id="minval" value="90"><span class="delfc">Delete</span></br></br>
 
 
 
 

 <h4>For users credit logs (<?php echo $allc['count1'];?>)</h4>
 Delete Older than X days: <input type="number" class="adelta" id="minval" value="90"><span class="delfcx" id="ucredit">Delete</span></br></br>
 
 <h4>For agents credit logs (<?php echo $allc['count2'];?>)</h4>
 Delete Older than X days: <input type="number" class="bdelta" id="minval" value="90"><span class="delfcx" id="acredit">Delete</span></br></br>
 
  <h4>For all points/txn logs (<?php echo $allc['count3'];?>)</h4>
 Delete Older than X days: <input type="number" class="cdelta" id="minval" value="90"><span class="delfcx" id="plogs">Delete</span></br></br>
 
 <h4>Deposit History (<?php echo $allc['count4'];?>)</h4>
 Delete Older than X days: <input type="number" class="ddelta" id="minval" value="90"><span class="delfcx" id="dephs">Delete</span></br></br>
 
  <h4>Withdrawal History (<?php echo $allc['count5'];?>)</h4>
 Delete Older than X days: <input type="number" class="edelta" id="minval" value="90"><span class="delfcx" id="withs">Delete</span></br></br>
 
  <h4>Transfer History (<?php echo $allc['count6'];?>)</h4>
 Delete Older than X days: <input type="number" class="fdelta" id="minval" value="90"><span class="delfcx" id="trhs">Delete</span></br></br>
 
  <h4>Messages/inbox records (<?php echo $allc['count7'];?>)</h4>
 Delete Older than X days: <input type="number" class="gdelta" id="minval" value="90"><span class="delfcx" id="inboxhs">Delete</span></br></br>
 
 </br>

 
 
 
 
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'credits'):?>
  <?php $rm = $conn->query("SELECT * FROM risk_management");
    $row = $rm->fetch_assoc();$signup_chip = $row['sup_credit'];$signup_promo = $row['sup_cpromo'];$mindep = $row['min_deposit'];$maxdep = $row['max_deposit'];$minwithdraw = $row['min_withdraw'];$maxwithdraw = $row['max_withdraw'];$fdb = $row['fdb'];?>

 <h2><i class="icon trophy"></i> Balance & Credit Control</h2>
 <p class="swinfo">Do you want to give sign up credit or give free bets to all users. You can do here accordingly for both chips and promo betting </p>
  <div class="rmessage"></div>
 <hr>
 <h4> <i class="icon move horizontal"></i> SIGN-UP CREDIT</h4>
 <div class="isact">
 <b> Chips </b> <input type="number" class="amnb" id="minvalcd" value="<?php echo $signup_chip;?>"></br></br>
 <b> Promo </b> <input type="number" class="bmxb" id="minvalcd" value="<?php echo $signup_promo;?>">
 </div>
 
 <h4> <i class="icon move horizontal"></i> FREE BET CREDITS</h4>
 <p class="swinfo">Give players a free bet credit on occassions or any special events. It's always adviseable to credit promo bet, and keep qualifying criteria for withdrawals. Only active users will get the credit. For individual credit, you can do it from his account profile page. And of course, it's reversible if done by mistake. Just put '-' before value like -100, -50 ect</p>
 <div class="isact">
 <b> Chips </b> <input type="number" class="cmnb" id="minvalcd" value="0"></br></br>
 <b> Promo </b> <input type="number" class="dmxb" id="minvalcd" value="0">
 </div>
 <hr>
 
 
 
 <h4> <i class="icon move horizontal"></i> FIRST DEPOSIT BONUS</h4>
 <div class="fdbmessage"></div>
 <p class="swinfo">First deposit bonus in percentage (%)</p>
 <div class="isact">
 <b> Deposit Bonus % </b> <input type="number" class="xfdb" id="xfdb" value="<?php echo $fdb;?>"></br></br>
 </div>
 
 <hr>
 
 
 <div class="rmessage"></div>
 <h4> <i class="icon move horizontal"></i> DEPOSIT/WITHDRAWAL LIMITS</h4>
 <p class="swinfo">The limits you put here will apply to any payment gateway you integrate. All values are in USD terms</p>
 <div class="isact">
 <b> Minimum Deposit </b> <input type="number" class="depwith" id="mindeposit" value="<?php echo $mindep;?>"></br></br>
 <b> Maximum Deposit </b> <input type="number" class="depwith" id="maxdeposit" value="<?php echo $maxdep;?>"></br></br>
<b> Minimum Withdraw </b> <input type="number" class="depwith" id="minwithdraw" value="<?php echo $minwithdraw;?>"></br></br>
<b> Maximum Withdraw </b> <input type="number" class="depwith" id="maxwithdraw" value="<?php echo $maxwithdraw;?>"> 
 </div>
 <hr>
 
 
 
 <h4>RESET USER'S BALANCE</h4>
 <p class="swinfo">Be cautious before taking any actions here. Once you reset balance to '0' you won't be able to restore their balance. However, database back up will do the thing.</p>
 <div class="isact">
  Reset all users chips balance to 0 <span class="enablesp" id="resetme">Reset Chips</span></br>
  Reset all users Promo balance to 0 <span class="enablesp" id="resetme">Reset Promo</span></br>
 </div>
 
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'bcontrol'):?>

 balance control
 
 <?php elseif(isset($_POST['method']) && $_POST['method'] == 'astatus'):?>

 <?php $account = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) AS act FROM users WHERE active = 'x'")); $acts = $account['act'];
?>
 <h2><i class="icon ban"></i> Temporarily restrict All users</h2>
 <p class="swinfo">If you don't want to put up maintenance page or want to temporarily deny users login, you can restrict all users temporarily. Note* users will not forced logout. They will be denied access only in next login attempt. For ban or individual restrictions please use "user's management" edit page.</p>
  <div class="rmessage"></div>
 <hr>
 
  <div class="isact">
 Are all users restricted?
 <?php if($acts > 1):?>
    <b>Yes</b> <span class="enablesp" id="restric">Allow</span>
 <?php else:?>
    <b>No</b> <span class="disablesp" id="restric">Restrict</span>
 <?php endif;?>	
 </div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 


<?php endif;?>