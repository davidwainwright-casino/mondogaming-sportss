<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <?php
    if ($members) {
        $r = 0;
    foreach ($members as $user) {
        ?>
        <div class="content-box">
            <div class="content-box-left" style="padding: 15px 0;">
                <a href="#" class="user user_<?= $user->id; ?>" data-id="<?= $user->id; ?>">
                    <div class="circle">
                        <div class="circle-avatar">
                            <?= $user->avatar ? '<span class="tip" title="'.$user->username.'"><img src="'.base_url('uploads/avatars/thumbs/'.$user->avatar).'" alt="" class="img-circle img-responsive"></span>' : '<span class="avatar tip" data-name="'.$user->username.'" title="'.$user->username.'"></span>'; ?>
                        </div>
                        <div class="small-circle <?= $this->tec->checkLastActivit($user->id) ? 'online' : 'offline'; ?>"></div>
                    </div>
                </a>
                <div class="user-info load" style="display:none;">
                    <div class="hidden-content-box-left">
                        <div class="udata">
                            <img src="<?= $assets; ?>img/ring-40.gif" alt="loading...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-box-middle">
                <h2 style="margin:12px 15px 8px 15px;"><?= '<a href="'.site_url('users/profile/'.$user->username).'">'.$user->first_name.' '.$user->last_name.' ('.$user->username.')</a> <span class="label label-primary" style="padding:0.2em 0.6em 0.3em 0.6em;">'.$user->group.'</span>'; ?></h2>
                <?= '<p style="margin-bottom:0;">'.lang('member_since').': '.$this->tec->hrsd(date('Y-m-d H:i:s', $user->created_on)).' - '.lang('last_visit').': '.$this->tec->hrld(date('Y-m-d H:i:s', $user->last_login)).'</p>'; ?>
            </div>
            <?php if ($loggedIn && $user->accept_messages && $user->id != $this->session->userdata('user_id')) { ?>
            <div class="content-box-right" style="padding:0;">
                <a href="<?= site_url('messages/send/'.$user->id); ?>" data-toggle="ajax-modal" style="display:block; line-height:5em; text-align:center;"><?= lang('send_message'); ?></a>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>

    <?php
        $r++;
        }
    ?>
    <div class="clearfix"></div>
    <?php if($links) { ?>
        <nav class="pagination-box">
            <ul class="page-info pull-left">
                <li class="previous disabled"><?= str_replace(array('{from}', '{till}', '{total}'), array($records['from'], $records['till'], $records['total']), lang('page_info'));?></li>
            </ul>
            <?php if ($loggedIn) { ?>
            <div class="dropup pull-right ml">
                <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-primary">
                    <?= lang('sort'); ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dLabel">
                    <li class="<?= $this->session->userdata('member_sorting') == 1 ? 'active' : ''; ?>">
                        <a href="<?=site_url('members?sort=1'.($online ? '&online=1' : ''));?>">
                            <?= lang('username'); ?>: <?= lang('asc'); ?>
                        </a>
                    </li>
                    <li class="<?= $this->session->userdata('member_sorting') == 2 ? 'active' : ''; ?>">
                        <a href="<?=site_url('members?sort=2'.($online ? '&online=1' : ''));?>">
                            <?= lang('username'); ?>: <?= lang('desc'); ?>
                        </a>
                    </li>
                    <li class="<?= $this->session->userdata('member_sorting') == 3 ? 'active' : ''; ?>">
                        <a href="<?=site_url('members?sort=3'.($online ? '&online=1' : ''));?>">
                            <?= lang('join_date'); ?>: <?= lang('asc'); ?>
                        </a>
                    </li>
                    <li class="<?= $this->session->userdata('member_sorting') == 4 ? 'active' : ''; ?>">
                        <a href="<?=site_url('members?sort=4'.($online ? '&online=1' : ''));?>">
                            <?= lang('join_date'); ?>: <?= lang('desc'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php } ?>
            <div class="nav-input-page pull-right ml">
                <div class="input-group">
                    <input type="text" class="form-control" id="page-num" placeholder="<?= lang('page'); ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="go-to-page"><i class="fa fa-chevron-<?=$Settings->rtl ? 'left' : 'right';?>"></i></button>
                    </span>
                </div>
            </div>
            <span class="pull-right"><?= $links; ?></span>
        </nav>
    <?php
}
} else {
    echo '<h2>'.lang('no_members_to_display').'</h2>';
}
?>
<div class="clearfix"></div>
</div>
<script type="text/javascript">
<?php
    echo "var page_url = '".site_url('members')."';";
?>
</script>
