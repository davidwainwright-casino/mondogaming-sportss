<ul class="iconnav cku" style="display:none">
    <li class="topiconlist">
			   <span class="liconav">
                   <span class="username">
                    <?php echo App::Auth()->fname; ?></br>
                   </span>
                   <b>
                       <?php echo $cur; ?> <?php echo $moo; ?>
                   </b>
               </span>
        <span class="riconav"><a class="icodeposit" href="/bt_accounts/?pg103=bnk&bb=bb&dd=1"><?= Lang::$word->ACC_DEPOSIT; ?></a></span>
        <hr>
        <span class="liconav"><?= Lang::$word->ACC_WITHDRAWALABLE; ?></br>
            <b><?php echo $cur; ?> <?php echo number_format((float)$moo, 2, '.', ''); ?></b></span>
        <span class="riconav bcr"><?= Lang::$word->ACC_BET_CREDITS; ?></br>
            <b><?php echo $cur; ?> <?php echo number_format((float)$gu->promo, 2, '.', ''); ?></b></span>
    </li>


    <a href="/bt_accounts/?pg103=bnk&bb=bb" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_BANK; ?></span>
            <span class="riconav"><i style="font-size:13px" class="icon money"></i></span>
        </li>
    </a>

    <?php $kty = App::Auth()->type;
    if ($kty == 'agent'): ?>
        <a href="/affagent/" class="acc-menu-item">
            <li class="agtdash">
                <span class="liconav"><?= Lang::$word->ACC_AGENT_DASHBOARD; ?></span>
                <span class="riconav"><i class="icon dashboard"></i></span>
            </li>
        </a>
    <?php elseif ($kty == 'Sagent'): ?>
        <a href="/suppagent/" class="acc-menu-item">
            <li class="sagtdash">
                <span class="liconav"><?= Lang::$word->ACC_SUPER_AGENT_DASHBOARD; ?></span>
                <span class="riconav"><i class="icon dashboard"></i></span>
            </li>
        </a>
    <?php endif; ?>

    <!--<a href="/bt_accounts/?pg103=sl" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_OPEN_BETSLIPS; ?></span>
            <span class="riconav"><i class="icon invoice"></i></span>
        </li>
    </a>

    <a href="/bt_accounts/?pg103=sl&esettled=1" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_SETTLED_BETSLIPS; ?></span>
            <span class="riconav"><i class="icon layer"></i></span>
        </li>
    </a>-->

    <a href="/bt_accounts/?pg104=casgame" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_CASINO_SLOT; ?></span>
            <span class="riconav"><i class="icon gamepad"></i></span>
        </li>
    </a>


    <!--<a href="/bt_accounts/?pg105=mkt" class="acc-menu-item">
        <li>
            <span class="liconav">Markets</span>
            <span class="riconav"><i class="icon bar chart alt"></i></span>
        </li>
    </a>

    <a href="/dashboard/settings/?pg103=acset" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_SETTINGS_PROFILE; ?></span>
            <span class="riconav"><i class="icon settings alt"></i></span>
        </li>
    </a>

    <a href="/bt_accounts/?pg103=pcredit" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_PROMO_CREDIT; ?></span>
            <span class="riconav"><i class="icon gift"></i></span>
        </li>
    </a>

    <a href="/bt_accounts/?pg103=msg" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_MESSAGING; ?></span>
            <span class="riconav"><i class="icon email alt"></i></span>
        </li>
    </a>

    <a href="/bt_accounts/?pg103=gmbc" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_GAMBLING_CONTROL; ?></span>
            <span class="riconav"><i class="icon asterisk"></i></span>
        </li>
    </a>-->


    <a href="/logout/" class="acc-menu-item">
        <li>
            <span class="liconav"><?= Lang::$word->ACC_LOGOUT; ?></span>
            <span class="riconav"><i style="color:#f00" class="icon power"></i></span>
        </li>
    </a>

</ul>
		   