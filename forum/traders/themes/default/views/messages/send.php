<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
    <?php if ($member->accept_messages || $Admin || $Moderator) { ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('send_message'); ?></h4>
        </div>
        <?= form_open("messages/send/".$member->id, 'class="form validate"'); ?>
        <div class="modal-body mm-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="bold"><?= lang('to'); ?>:</span>
                            <?= ($member->avatar ? '<img src="'.base_url('uploads/avatars/thumbs/'.$member->avatar).'" alt="" style="width:25px;height:25px;border-radius:15px;margin-top:-4px;">' : '<span class="avatar tip" data-name="'.$member->username.'" title="'.$member->username.'"><span>').' '.$member->first_name.' '.$member->last_name.' ('.$member->username.')';
                            ?>
                        </div>
                        <div class="form-group">
                            <?= lang('subject', 'subject'); ?>
                            <?= form_input('subject', set_value('subject'), 'class="form-control" id="subject" pattern=".{3,90}" required data-fv-notempty-message="'.lang('subject_required').'"'); ?>
                        </div>

                        <div class="form-group">
                            <?= lang('message_body', 'message_body'); ?>
                            <?= form_textarea('message_body', set_value('message_body'), 'class="form-control mh" id="message_body" required data-fv-notempty-message="'.lang('message_body_required').'"'); ?>
                        </div>
                        <?php if ($Settings->captcha == 2) { ?>
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <input type="captcha" name="captcha" class="form-control" placeholder="<?= lang('captcha'); ?>" required data-fv-notempty-message="<?=lang('captcha_required');?>">
                                    </div>
                                </div>
                                <div class="col-sm-5">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <?= form_submit('send_message', lang('send_message'), 'class="btn btn-primary" id="send-message"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    <?php } else { ?>
        <div class="modal-header" style="background-color: #cd3939;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('error'); ?></h4>
        </div>
        <div class="modal-body mm-body">
        <p style="font-weight:bold;" class="mb"><?= sprintf(lang('user_x_accept_messages'), $member->username); ?></p>
        <div class="clearfix"></div>
        </div>
    <?php } ?>
    </div>
</div>
