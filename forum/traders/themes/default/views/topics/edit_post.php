<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content mm-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('edit_post'); ?></h4>
        </div>
        <?= form_open("topics/edit_post/".$post->id, 'id="editPost"'); ?>
        <div class="modal-body mm-body">
            <div class="col-md-12">
                <div class="form-group">
                    <p><?= lang('please_update_post'); ?></p>
                    <?= form_textarea('body', (isset($_POST['body']) ? $_POST['body'] : $this->tec->decode_html($post->body)), 'class="form-control tip body" id="body" required="required" data-fv-notempty-message="'.lang('body_required').'"'); ?>
                </div>
                <?php if ($Settings->captcha == 2) { ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <input type="captcha" name="captcha" class="form-control" placeholder="<?= lang('captcha'); ?>" required="">
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
            </div>
            <div class="modal-footer mm-footer">
            <?php if ($post->created_by == $this->session->userdata('user_id')) { ?>
                <div class="pull-left">
                    <label class="checkbox" for="notify1">
                        <input type="checkbox" name="notify" value="1" id="notify1" <?= $post->notify == 1 ? 'checked=""' : ''; ?>/>
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <?= lang('email_me_when_replied') ?>
                    </label>
                </div>
                <?php } ?>
                <?= form_submit('edit_post', lang('edit_post'), 'class="btn btn-primary btn-lg"'); ?>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
