<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= $page_title; ?></h4>
        </div>
        <?= form_open("reviews/ban?user=".$member->id, 'id="categoryForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <?php if ($member->unban_date) { ?>
                            <div class="alert alert-danger">
                                <?= sprintf(lang('user_is_banned'), $this->tec->hrsd($member->unban_date), $member->message); ?>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <span class="bold"><?= lang('user'); ?>:</span>
                            <strong><?= ($member->avatar ? '<img src="'.base_url('uploads/avatars/thumbs/'.$member->avatar).'" alt="" style="width:25px;height:25px;border-radius:15px;margin-top:-4px;">' : '<span class="avatar tip" data-name="'.$member->username.'" title="'.$member->username.'"><span>').' '.$member->first_name.' '.$member->last_name.' ('.$member->username.')';
                            ?></strong>
                        </div>

                        <div class="form-group">
                            <?= lang('message', 'message'); ?>
                            <?= form_textarea('message', set_value('message'), 'class="form-control mh" id="message" required="" data-fv-notempty-message="'.lang('message_body_required').'"'); ?>
                        </div>

                        <div class="form-group">
                            <?= lang('unban_date', 'date'); ?>
                            <?= form_input('unban_date', set_value('unban_date'), 'placeholder="yyyy-mm-dd" class="form-control" id="date" required'); ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <?= form_submit('ban_user', lang('ban_user'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
