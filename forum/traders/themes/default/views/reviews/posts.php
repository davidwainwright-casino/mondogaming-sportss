<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <?php
    if ($posts) {
    foreach ($posts as $post) {
    ?>
    <div class="posts-box" id="<?= $post->id; ?>">
        <div class="thread">
            <h3>
            <?= lang('thread'); ?>: <a href="<?= site_url($post->category_slug.'/'.$post->slug); ?>"><?= $post->title; ?></a>
            <small class="top-con"><?= empty($post->status) ? '<span class="label label-primary">'.lang('new').'</span>' : '<span class="label label-warning">'.lang('flagged').'</span>'; ?></small>
            </h3>
        </div>

        <div class="content-box-left">
            <a<?= $post->user_id ? ' href="#" class="user user_'.$post->user_id.'" data-id="'.$post->user_id.'"' : ''; ?>">
                <div class="circle">
                    <div class="circle-avatar">
                        <?php 
                        if ($post->username) {
                            echo $post->avatar ? '<span class="tip" title="'.$post->username.'"><img src="'.base_url('uploads/avatars/thumbs/'.$post->avatar).'" alt="" class="img-circle img-responsive"></span>' : '<span class="tavatar tip" data-name="'.$post->username.'" title="'.$post->username.'"><span>';
                        } else {
                            echo '<span class="tavatar tip" data-name="G" title="'.$post->guest_name.'"><span>';
                        }
                        ?>
                    </div>
                    <div class="small-circle <?= $this->tec->checkLastActivit($post->user_id) ? 'online' : 'offline'; ?>"></div>
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
        <div class="content-box-md">
            <div class="col-xs-12">
                <div class="meta-box">
                <?php if ($post->username) { ?>
                    <span class="author"><a href="<?= site_url('users/profile/'.$post->username); ?>"><?= $post->username; ?></a></span> <?= lang('replied'); ?>
                <?php } else { ?>
                    <span class="author"><a><?= $post->guest_name.($post->guest_email ? ' ('.$post->guest_email.')' : ''); ?></a></span> <?= lang('replied'); ?>
                <?php } ?>
                    
                    <div class="pull-right">
                        <?php
                        if ($loggedIn && $post->user_id != $this->session->userdata('user_id') && $post->user_id) {
                            echo ' <a href="'.site_url('messages/send/'.$post->user_id).'" data-toggle="ajax-modal" class="tip" title="'.lang('send_message').'"><span class="label label-info"><i class="fa fa-envelope-o"></i></span></a>';
                            if ($this->tec->in_group('member', $post->user_id)) {
                                echo ' <a href="'.site_url('reviews/ban?user='.$post->user_id).'" data-toggle="ajax-modal" class="tip" title="'.lang('ban_user').'"><span class="label label-danger"><i class="fa fa-ban"></i></span></a>';
                            }
                        } ?>
                        <span class="label label-info"><i class="fa fa-clock-o"></i> <?= $this->tec->timespan($post->created_at); ?></span>
                        <?php if ($Admin || $Moderator) { ?>
                        <a href="<?= site_url('topics/edit_post/'.$post->id); ?>" data-toggle="ajax-modal">
                            <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                        </a>
                        <a href="<?= site_url('reviews/delete?post='.$post->id); ?>" class="delete_review" data-id="<?= $post->id; ?>">
                            <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                        </a>
                        <a href="<?= site_url('reviews/approve?post='.$post->id); ?>" class="pull-right ml approve" data-id="<?= $post->id; ?>">
                            <span class="label label-success"><i class="fa fa-check"></i> <?= lang('publish'); ?></span>
                        </a>
                        <?php } ?>
                    </div>
                </div>
                <div id="reply<?= $post->id; ?>">
                    <?= $this->tec->display_contents($post->body); ?>
                    <?php
                    if ($post->status == 2) {
                        $cuser = explode('__', $post->complained_by);
                        echo '<div class="well well-sm mt" style="margin-bottom:0;">';
                        echo '<p><strong>'.lang('reason_label').':</strong> '.$post->reason.'</p>';
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
                </div>
            </div>
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
    echo '<h2>'.lang('no_post_to_review').'</h2>';
}
?>
<div class="clearfix"></div>
</div>
<script type="text/javascript">
    var page_url = '<?= site_url('reviews/posts'); ?>';
</script>
