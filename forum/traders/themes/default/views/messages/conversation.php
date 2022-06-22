<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="box">
        <div class="content-panel">
            <div class="chat">
                <div class="chat-header clearfix">
                    <div class="circle pull-left" style="width:50px;height:50px;">
                        <?= $conversation->avatar ? '<span class="tip" title="'.$conversation->username.'"><img src="'.base_url('uploads/avatars/thumbs/'.$conversation->avatar).'" alt="" class="img-circle img-responsive"></span>' : '<span class="avatar tip" data-name="'.$conversation->username.'" title="'.$conversation->username.'"><span>';
                        ?>
                    </div>
                    <div class="chat-about">
                        <div class="chat-with"><?= stripslashes($conversation->subject); ?></div>
                        <div class="chat-num-messages"><?= lang('by').' <strong>'.$conversation->username.'</strong> '.lang('to').' <strong>'.$receiver->username.'</strong> '.lang('at').': '.$this->tec->hrld($conversation->created_at).' '; ?></div>
                    </div>
                    <?= $conversation->important ? '<i class="fa fa-star"></i>' : ''; ?>
                </div>
                <div class="chat-history">
                    <ul>
                        <?php
                        if ($messages) {
                            foreach ($messages as $message) {
                                ?>
                                <li class="clearfix">
                                    <div class="message-data<?= $message->user_id == $conversation->receiver_id ? ($Settings->rtl ? ' align-left' : '') : ' align-right'; ?>">
                                        <span class="message-data-name"> <?= lang('by').' '.$message->username; ?></span>
                                        <span class="message-data-time"><?= $this->tec->timespan($message->created_at).' '.lang('ago'); ?></span>
                                    </div>
                                    <div class="message <?= $message->user_id == $conversation->receiver_id ? ($Settings->rtl ? 'my-message pull-right' : 'my-message') : 'other-message float-right'; ?>">
                                        <?= $this->tec->display_contents($message->body); ?>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
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
                        <div class="clearfix"></div>
                    <?php } ?>
                </div>
                <div class="chat-message clearfix">
                    <?php if ( ! $conversation->sender_delete && ! $conversation->receiver_delete) { ?>
                    <?= form_open('messages/reply/'.$conversation->id, 'id="postForm"'); ?>
                    <textarea name="message_body" placeholder ="<?= lang('type_message'); ?>"></textarea>
                    <?php if ($Settings->captcha == 2) { ?>
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
                                            <a <a href="<?= base_url(); ?>users/reload_captcha" class="reload-captcha">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?= form_submit('reply', lang('submit'), 'class="btn btn-primary btn-block"'); ?>
                    <?= form_close(); ?>
                    <?php } else {
                        echo '<strong>'.lang('user_deleted_conversation').'<strong>';
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
