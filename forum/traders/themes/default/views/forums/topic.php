<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="topic-box">
        <div class="content-box-top">
            <a href="#" class="user user_<?= $topic->user_id; ?> pull-left mr" data-id="<?= $topic->user_id; ?>">
                <div class="circle">
                    <div class="circle-avatar">
                        <?= $topic->avatar ? '<span class="tip" title="' . $topic->username . '"><img src="' . base_url('uploads/avatars/thumbs/' . $topic->avatar) . '" alt="" class="img-circle img-responsive"></span>' : '<span class="tavatar tip" data-name="' . $topic->username . '" title="' . $topic->username . '"><span>'; ?>
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
            <h2 class="break-words"><?= stripslashes($topic->title); ?></h2>
            <div class="meta-box">
                <?php
                if ($loggedIn && $topic->user_id != $this->session->userdata('user_id')) {
                    echo ' <a href="' . site_url('messages/send/' . $topic->user_id) . '" data-toggle="ajax-modal" class="tip" title="' . lang('send_message') . '"><span class="label label-info"><i class="fa fa-envelope-o"></i></span></a>';
                } ?>
                <?= $topic->active ?
                '<span class="label label-success"><i class="fa fa-check"></i> ' . lang('active') . '</span>' :
                '<span class="label label-danger"><i class="fa fa-lock"></i> ' . lang('closed') . '</span>'; ?>
                <span class="label label-success"><i class="fa fa-comments-o"></i> <?= $this->tec->short_num($topic->total_posts - 1) . ' ' . (($topic->total_posts - 1) < 2 ? lang('reply') : lang('replies')); ?></span>
                <span class="label label-info"><i class="fa fa-eye"></i> <?= $this->tec->short_num($topic->views) . ' ' . ($topic->views < 2 ? lang('view') : lang('views')); ?></span>
                <a href="<?= site_url('archive/' . date('Y', strtotime($topic->created_at)) . '/' . date('m', strtotime($topic->created_at))); ?>">
                    <span class="label label-info"><i class="fa fa-clock-o"></i> <?= $topic->created_at; ?></span>
                </a>
                <a href="<?= site_url($topic->category_slug); ?>">
                    <span class="label label-info"><i class="fa fa-folder"></i> <?= $topic->category; ?></span>
                </a>
                <?php if ($Admin || $Moderator || $this->session->userdata('user_id') == $topic->created_by) { ?>
                <a href="<?= site_url('topics/edit/' . $topic->id); ?>" data-toggle="ajax-modal">
                    <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                </a>
                <?php if (!$Moderator) { ?>
                    <a href="<?= site_url('topics/delete/' . $topic->id); ?>" class="po-del">
                        <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                    </a>
                <?php } ?>
                <?php } ?>
                <?= $loggedIn && $Settings->flag_option ? '<a href="' . site_url('complain/' . $topic->slug) . '" data-toggle="ajax-modal" class="tip pull-right ml" title="' . lang('complain') . '"><i class="fa fa-flag" ></i></a>' : ''; ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="content-box-md">
            <?= $this->tec->display_contents($topic->body); ?>
            <?php
            if (!empty($fields)) {
                echo '<hr>';
                foreach ($fields as $field) {
                    if ($field->public || $this->session->userdata('user_id') == $topic->created_by || $Admin || $Moderator) {
                        echo '<strong>' . $field->name . '</strong>: ' . ($field->type == 'checkbox' ? str_replace('_|_', ', ', $field->value) : ($field->type == 'url' ? '<a href="' . $field->value . '" target="_blank">' . $field->value . '</a>' : $field->value)) . '<br>';
                    }
                }
            }
            if ($this->Settings->signature && !empty($topic->signature)) {
                echo '<hr style="margin-bottom: 4px;">';
                echo $this->tec->display_contents($topic->signature);
            }
            ?>
        </div>
        <div class="clearfix"></div>
        <?php if ($Settings->voting == 1) { ?>
        <div class="btn-group btn-group-justified mt vote-con" role="group" data-postid="<?= $topic->id; ?>" data-score="<?= $topic->votes; ?>" data-cast="<?= !empty($vote) ? 1 : 0; ?>">
            <div class="btn-group" role="group">
                <button type="button" data-action="up" class="btn btn-lg btn-success vote vote-up"<?= (!$loggedIn || $vote && (!$Settings->change_vote || $vote == 1)) ? ' disabled="disabled"' : ''; ?>><i class="fa fa-thumbs-o-up"></i> <span class="total-up"><?= $thread_votes['up'] ? $thread_votes['up'] : 0; ?></span>  <span class="hidden-xs"><?= lang('up_vote'); ?></span></button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" data-action="down" class="btn btn-lg btn-danger vote vote-down"<?= (!$loggedIn || $vote && (!$Settings->change_vote || $vote == -1)) ? ' disabled="disabled"' : ''; ?>><i class="fa fa-thumbs-o-down"></i> <span class="total-down"><?= $thread_votes['down'] ? $thread_votes['down'] : 0; ?></span> <span class="hidden-xs"><?= lang('down_vote'); ?></span></button>
            </div>
        </div>
        <?php } elseif ($Settings->voting == 2) { ?>
        <div class="well well-sm mt">
        <div class="row stars-con" data-postid="<?= $topic->id; ?>" data-stars="<?= $topic->stars; ?>" data-cast="<?= $my_stars ? 1 : 0; ?>" data-votes="<?= $thread_votes; ?>" data-my-stars="<?= $my_stars ? $my_stars : 0; ?>">
            <div class="col-md-<?=$loggedIn ? 8 : 12;?> col-xs-12 text-center">
                <input type="hidden" class="thread-rating" value="<?= $topic->stars; ?>">
                <p><strong><?= lang('total_votes'); ?>: <span class="total_votes"><?= $thread_votes; ?></span></strong></p>
            </div>
            <?php if ($loggedIn) { ?>
            <div class="col-md-4 col-xs-12 text-center">
                <p><?= lang('your_rating'); ?>:</p>
                <p style="font-size:10px;"><input type="hidden" id="rating" name="rating" data-show-clear="false" value="<?= $my_stars; ?>"<?= ($my_stars && !$Settings->change_vote) ? ' data-disabled="true"' : ''; ?>></p>
                <strong><?= $my_stars > 1 ? $my_stars . ' ' . lang('stars') : lang('not_voted_yet'); ?></strong>
            </div>
            <?php } ?>
        </div>
        </div>
        <?php } ?>
        <hr>
        <?php include('share.php'); ?>
        <hr>
        <?php
        if ($Settings->ad_thread) {
            echo '<div class="ad ad-thread text-center">' . $Settings->ad_thread_code . '</div>';
        }
        ?>
        <div class="clearfix"></div>
    </div>

    <?php
    if ($posts) {
        foreach ($posts as $post) { ?>
            <div class="post-box replies">
                <div class="content-box-left">
                    <a<?= $post->user_id ? ' href="#" class="user user_' . $post->user_id . '" data-id="' . $post->user_id . '"' : ''; ?>">
                        <div class="circle">
                            <div class="circle-avatar">
                            <?php
                            if ($post->username) {
                                echo $post->avatar ? '<span class="tip" title="' . $post->username . '"><img src="' . base_url('uploads/avatars/thumbs/' . $post->avatar) . '" alt="" class="img-circle img-responsive"></span>' : '<span class="tavatar tip" data-name="' . $post->username . '" title="' . $post->username . '"><span>';
                            } else {
                                echo '<span class="tavatar tip" data-name="G" title="' . $post->guest_name . '"><span>';
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
                                <span class="author"><a href="<?= site_url('users/profile/' . $post->username); ?>"><?= $post->username; ?></a></span> <?= lang('replied'); ?>
                            <?php } else { ?>
                                <span class="author"><a<?= ($Admin || $Moderator) ? ' class="tip" title="' . $post->guest_email . '"' : ''; ?>><?= $post->guest_name; ?></a></span> <?= lang('replied'); ?>
                            <?php } ?>

                            <div class="pull-right">
                                <?= $loggedIn ? '<span class="label label-info pointer quote-reply" id="' . $post->id . '">' . lang('quote') . '</span>' : ''; ?>
                                <?php
                                if ($loggedIn && $post->user_id != $this->session->userdata('user_id') && $post->user_id) {
                                    echo ' <a href="' . site_url('messages/send/' . $post->user_id) . '" data-toggle="ajax-modal" class="tip" title="' . lang('send_message') . '"><span class="label label-info"><i class="fa fa-envelope-o"></i></span></a>';
                                } ?>
                                <span class="label label-info"><i class="fa fa-clock-o"></i> <?= $this->tec->timespan($post->created_at); ?></span>
                                <?php if ($Admin || $Moderator || $this->session->userdata('user_id') == $post->created_by) { ?>
                                <a href="<?= site_url('topics/edit_post/' . $post->id); ?>" data-toggle="ajax-modal">
                                    <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                                </a>
                                <?php if (!$Moderator) { ?>
                                    <a href="<?= site_url('topics/delete_post/' . $post->id); ?>" class="po-del">
                                        <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                                    </a>
                                <?php } ?>
                                <?php } ?>
                                <?= $loggedIn && $Settings->flag_option ? '<a href="' . site_url('complain/' . $topic->slug . '?post=' . $post->id) . '" data-toggle="ajax-modal" class="tip pull-right ml" title="' . lang('complain') . '"><i class="fa fa-flag" ></i></a>' : ''; ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div id="reply<?= $post->id; ?>">
                            <?= $this->tec->display_contents($post->body); ?>
                            <?php
                            if ($this->Settings->signature && !empty($post->signature)) {
                                echo '<hr style="margin-bottom: 4px;">';
                                echo $this->tec->display_contents($post->signature);
                            }
                            ?>
                            <?php
                            if ($loggedIn && ($Settings->editor == 'simpledme' || $Settings->editor == 'sceditor')) {
                                echo '<div id="mde' . $post->id . '" style="display:none;">' . $post->body . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
        }
    }
    ?>
    <div class="clearfix"></div>
    <?php if ($links) { ?>
        <nav class="pagination-box">
            <ul class="page-info pull-left">
                <li class="previous disabled"><?= str_replace(['{from}', '{till}', '{total}'], [$records['from'], $records['till'], $records['total']], lang('page_info'));?></li>
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
        <div class="clearfix"></div>
        <?php } if (($loggedIn || $Settings->guest_reply) && $topic->active) { ?>
        <div class="reply-box">
            <div class="content-box-left">
                <div class="circle">
                    <div class="circle-avatar">
                    <?php $user_name = $this->session->userdata('username') ? $this->session->userdata('username') : lang('guest'); ?>
                        <?= $this->session->userdata('avatar') ? '<span class="tip" title="' . $this->session->userdata('username') . '"><img src="' . base_url('uploads/avatars/thumbs/' . $this->session->userdata('avatar')) . '" alt="" class="img-circle img-responsive"></span>' : '<span class="tavatar tip" data-name="' . $user_name . '" title="' . $user_name . '"><span>'; ?>
                    </div>
                    <div class="small-circle online"></div>
                </div>
            </div>
            <div class="content-box-md">
            <?php $form_action = (!$loggedIn) ? 'ajax_calls/post?topic=' . $topic->id : 'topics/add_post/' . $topic->id; ?>
                <?= form_open($form_action, 'id="addPost"'); ?>
                <div class="form-group" style="margin-bottom:5px;">
                    <?= form_textarea('body', set_value('body'), 'class="form-control tip" id="editor" required="required" data-fv-notempty-message="' . lang('body_required') . '" minlength="2" data-fv-stringlength-message="' . lang('body_required') . '"'); ?>
                </div>
                <?php if (!$loggedIn && $Settings->guest_reply) { ?>
                    <div class="form-group" style="margin-bottom: 0;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <?= form_input('name', '', 'placeholder="' . lang('name') . '" class="form-control" required="required" data-fv-notempty-message="' . lang('name_required') . '"'); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <?= form_input('email', '', 'placeholder="' . lang('email') . '" class="form-control" required="required" data-fv-notempty-message="' . lang('email_required') . '"'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>
                <?php if ($Settings->captcha == 2 || ($Settings->guest_reply && !$this->loggedIn)) { ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <input type="captcha" name="captcha" class="form-control" placeholder="<?= lang('captcha'); ?>" required="" data-fv-notempty-message="<?=lang('captcha_required');?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="captcha-image"><?= $image; ?></span>
                                    <span class="input-group-addon" id="basic-addon2">
                                        <a href="<?= base_url(); ?>users/reload_captcha" class="reload-captcha">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!$Settings->guest_reply) { ?>
                <label class="checkbox" for="notify">
                    <input type="checkbox" name="notify" value="1" id="notify" checked="checked"/>
                    <span class="icon"><i class="fa fa-check"></i></span>
                    <?= lang('email_me_when_replied') ?>
                </label>
                <?php } ?>
                <?= form_submit('add_post', lang('add_post'), 'class="btn btn-primary btn-block"'); ?>
                <?= form_close(); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
        <?php if (!$loggedIn && !$Settings->guest_reply) { ?>
            <p style="margin:16px 0;font-size:16px;font-weight:bold;">
                <?php
                echo str_replace('{login}', '<a href="' . site_url('login') . '">' . lang('login') . '</a>', lang('login_to_reply'));
                echo str_replace('{register}', '<a href="' . site_url('login#register') . '">' . lang('register') . '</a>', ' ' . lang('otherwise_register'));
                ?>
            </p>
        <?php } ?>
    </div>
    <script type="text/javascript">
    var page_url = '<?= site_url($topic->category_slug . '/' . $topic->slug); ?>';
    </script>
