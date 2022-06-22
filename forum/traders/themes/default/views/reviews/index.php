<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <?php
    if ($topics) {
    foreach ($topics as $topic) {
        ?>
        <div class="content-box<?= $topic->sticky ? ' sticky' : ''; ?>" id="<?= $topic->id; ?>">
            <small class="top-con"><?= empty($topic->status) ? '<span class="label label-primary">'.lang('new').'</span>' : '<span class="label label-warning">'.lang('flagged').'</span>'; ?></small>
            <div class="content-box-left">
                <a href="#" class="user user_<?= $topic->user_id; ?>" data-id="<?= $topic->user_id; ?>">
                    <div class="circle">
                        <div class="circle-avatar">
                            <?= $topic->avatar ? '<span class="tip" title="'.$topic->username.'"><img src="'.base_url('uploads/avatars/thumbs/'.$topic->avatar).'" alt="" class="img-circle img-responsive"></span>' : '<span class="avatar tip" data-name="'.$topic->username.'" title="'.$topic->username.'"></span>'; ?>
                        </div>
                        <div class="small-circle <?= $this->tec->checkLastActivit($topic->user_id) ? 'online' : 'offline'; ?>"></div>
                    </div>
                </a>
                <div class="user-info load" style="display:none;">
                    <div class="hidden-content-box-left">
                        <div class="udata">
                            <img src="<?= $assets; ?>img/ring-40.gif" alt="loading...">
                        </div>
                    </div>
                </div>

                <div class="pinned-box">
                    <?php
                    if ($loggedIn && $topic->user_id != $this->session->userdata('user_id')) {
                        echo ' <a href="'.site_url('messages/send/'.$topic->user_id).'" data-toggle="ajax-modal" class="btn btn-xs btn-block btn-primary tip mb" title="'.lang('send_message').'"><i class="fa fa-envelope-o"></i></a>';
                        if ($this->tec->in_group('member', $topic->user_id)) {
                            echo ' <a href="'.site_url('reviews/ban?user='.$topic->user_id).'" data-toggle="ajax-modal" class="btn btn-xs btn-block btn-danger tip mb" title="'.lang('ban_user').'"><i class="fa fa-ban"></i></a>';
                        }
                    } else { echo '<div class="clearfix"></div>'; } ?>
                </div>
            </div>
            <div class="content-box-middle">
                <?php 
                if ($this->Settings->reply_sorting == 1) {
                    $page = ceil(($topic->total_posts-1) / $Settings->records_per_page); 
                } else {
                    $page = 0;
                }
                ?>
                <h2><a href="<?= site_url('reviews/topic?slug='.$topic->slug); ?>"><?= stripslashes($topic->title); ?></a></h2>
                <div class="meta-box">
                    <a href="<?= site_url('archive/'.date('Y', strtotime($topic->created_at)).'/'.date('m', strtotime($topic->created_at))); ?>">
                        <span class="label label-info"><i class="fa fa-clock-o"></i> <?= $this->tec->hrld($topic->created_at); ?></span>
                    </a>
                    <a href="<?= site_url($topic->category_slug); ?>">
                        <span class="label label-info"><i class="fa fa-folder"></i> <?= $topic->category; ?></span>
                    </a>
                    <?php if ($topic->child_category_id) { ?>
                    <a href="<?= site_url($topic->child_category_slug); ?>">
                        <span class="label label-info"><i class="fa fa-folder-o"></i> <?= $topic->child_category; ?></span>
                    </a>
                    <?php } ?>
                    <?php if ($Admin || $Moderator) { ?>
                    <a href="<?= site_url('topics/edit/'.$topic->id); ?>" data-toggle="ajax-modal">
                        <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                    </a>
                    <a href="<?= site_url('reviews/delete?topic='.$topic->id); ?>" class="delete_review" data-id="<?= $topic->id; ?>">
                        <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                    </a>
                    <a href="<?= site_url('reviews/approve?topic='.$topic->id); ?>" class="pull-right approve" data-id="<?= $topic->id; ?>">
                        <span class="label label-success"><i class="fa fa-check"></i> <?= lang('publish'); ?></span>
                    </a>
                    <?php } ?>
                </div>
                <h3>
                    <?= $this->tec->character_limiter(stripslashes($topic->description), 120); ?>
                    <?php
                    if ($topic->status == 2) {
                        $cuser = explode('__', $topic->complained_by);
                        echo '<div class="well well-sm mt ml mr">';
                        echo '<p><strong>'.lang('reason_label').':</strong> '.$topic->reason.'</p>';
                        echo '<a class="label label-primary" href="'.site_url('users/profile/'.$cuser[1]).'">'.lang('by').': '.$cuser[1].'</a>';
                        if ($this->session->userdata('user_id') != $cuser[0]) {
                            echo ' <a class="label label-primary" data-toggle="ajax-modal" href="'.site_url('messages/send/'.$cuser[0]).'">'.lang('send_message').'</a>';
                            if ($this->tec->in_group('member', $cuser[0])) {
                                echo ' <a href="'.site_url('reviews/ban?user='.$cuser[0]).'" data-toggle="ajax-modal" class="label label-danger">'.lang('ban_user').'</a>';
                            }
                        }
                        echo '</div>';
                    }
                    ?>
                </h3>
            </div>
            <div class="content-box-right" <?= $topic->last_reply_by ? 'style="padding-top:5px;"' : ''; ?>>
                <!-- <h2 class="info-box"><?= $topic->total_posts; ?></h2> -->
                <h3 class="tip" title="<?=lang('views');?>"><i class="fa fa-eye"></i> <?= $this->tec->short_num($topic->views).' '.($topic->views < 2 ? lang('view') : lang('views')); ?></h3>
                <h3 class="tip" title="<?=lang('replies');?>"><i class="fa fa-comments-o"></i> <?= $this->tec->short_num($topic->total_posts-1).' '.(($topic->total_posts-1) < 2 ? lang('reply') : lang('replies')); ?></h3>
                <h3 class="tip" title="<?=lang('created_at');?>"><i class="fa fa-clock-o"></i> <?= $this->tec->timespan($topic->created_at); ?></h3>
                <h3 class="tip" title="<?=lang('last_reply_time');?>"><i class="fa fa-reply"></i> <?= $this->tec->timespan($topic->last_reply_time); ?></h3>
                <?php if ($topic->last_reply_by) { ?>
                    <h3 class="tip" title="<?=lang('last_reply_by');?>"><i class="fa fa-user"></i> <?= $topic->last_reply_by; ?></h3>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php
        }
    ?>
    <div class="clearfix"></div>
    <?php if($links) { ?>
        <nav class="pagination-box">
            <ul class="page-info pull-left">
                <li class="previous disabled"><?= str_replace(array('{from}', '{till}', '{total}'), array($records['from'], $records['till'], $records['total']), lang('page_info'));?></li>
            </ul>
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
    echo '<h2>'.lang('no_topic_to_review').'</h2>';
}
?>
<div class="clearfix"></div>
</div>
<script type="text/javascript">
    var page_url = '<?= site_url('reviews'); ?>';
</script>
