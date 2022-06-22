<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="topic-box">
        <div class="content-box-top">
            <a href="#" class="user user_<?= $topic->user_id; ?> pull-left mr" data-id="<?= $topic->user_id; ?>">
                <div class="circle">
                    <div class="circle-avatar">
                        <?= $topic->avatar ? '<span class="tip" title="'.$topic->username.'"><img src="'.base_url('uploads/avatars/thumbs/'.$topic->avatar).'" alt="" class="img-circle img-responsive"></span>' : '<span class="tavatar tip" data-name="'.$topic->username.'" title="'.$topic->username.'"><span>'; ?>
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
            <h2><?= stripslashes($topic->title); ?></h2>
            <div class="meta-box">
                <?php
                if ($loggedIn && $topic->user_id != $this->session->userdata('user_id')) {
                    echo ' <a href="'.site_url('messages/send/'.$topic->user_id).'" data-toggle="ajax-modal" class="tip" title="'.lang('send_message').'"><span class="label label-info"><i class="fa fa-envelope-o"></i></span></a>';
                } ?>
                <?= $topic->active ?
                '<span class="label label-success"><i class="fa fa-check"></i> '.lang('active').'</span>' :
                '<span class="label label-danger"><i class="fa fa-lock"></i> '.lang('closed').'</span>'; ?>
                <span class="label label-success"><i class="fa fa-comments-o"></i> <?= $this->tec->short_num($topic->total_posts-1).' '.(($topic->total_posts-1) < 2 ? lang('reply') : lang('replies')); ?></span>
                <span class="label label-info"><i class="fa fa-eye"></i> <?= $this->tec->short_num($topic->views).' '.($topic->views < 2 ? lang('view') : lang('views')); ?></span>
                <a href="<?= site_url('archive/'.date('Y', strtotime($topic->created_at)).'/'.date('m', strtotime($topic->created_at))); ?>">
                    <span class="label label-info"><i class="fa fa-clock-o"></i> <?= $topic->created_at; ?></span>
                </a>
                <a href="<?= site_url($topic->category_slug); ?>">
                    <span class="label label-info"><i class="fa fa-folder"></i> <?= $topic->category; ?></span>
                </a>
                <?php if ($Admin || $Moderator || $this->session->userdata('user_id') == $topic->created_by) { ?>
                <a href="<?= site_url('topics/edit/'.$topic->id); ?>" data-toggle="ajax-modal">
                    <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                </a>
                <?php if ( ! $Moderator) { ?>
                    <a href="<?= site_url('topics/delete/'.$topic->id); ?>" class="po-del">
                        <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                    </a>
                <?php } ?>
                <?php if ($topic->status == 1) {
                    echo '<span class="label label-success"><i class="fa fa-check"></i> '.lang('published').'</span>';
                } else { ?>
                <a href="<?= site_url('reviews/approve?topic='.$topic->id); ?>" class="pull-right" data-topic="<?= $topic->id; ?>">
                    <span class="label label-success"><i class="fa fa-check"></i> <?= lang('publish'); ?></span>
                </a>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="content-box-md">
            <?= $this->tec->display_contents($topic->body); ?>
            <?php
            if ( ! empty($fields)) {
                echo '<hr>';
                foreach($fields as $field) {
                    if ($field->public || $this->session->userdata('user_id') == $topic->created_by || $Admin || $Moderator) {
                        echo '<strong>'.$field->name.'</strong>: '.($field->type == 'checkbox' ? str_replace('_|_', ', ', $field->value) : $field->value).'<br>';
                    }
                }
            }
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>