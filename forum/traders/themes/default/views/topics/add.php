<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content mm-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('new_topic'); ?></h4>
        </div>
        <?= form_open("topics/add", 'id="addTopicForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <ul id="errors"></ul>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('category', 'category'); ?>
                    <?php
                    $pcs[''] = lang('select_parent');
                    if($parent_categories) {
                        foreach ($parent_categories as $pc) {
                            $pcs[$pc->id] = $pc->name;
                        }
                    }
                    ?>
                    <?= form_dropdown('category', $pcs, set_select('category'), 'class="form-control select2 parent_category" id="category" style="width:100%;" required="required" data-fv-notempty-message="'.lang('category_required').'"'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('child_category', 'child_category'); ?>
                    <?php $scs[''] = lang('select_parent_first'); ?>
                    <?= form_dropdown('child_category', $scs, set_select('child_category'), 'class="form-control select2" id="child_category" style="width:100%;"'); ?>
                </div>
            </div>
            <div class="col-md-<?= $Member ? '12' : '6'; ?>">
                <div class="form-group">
                    <?= lang('title', 'title'); ?>
                    <?= form_input('title', set_value('title'), 'class="form-control tip" id="title" required="required" data-fv-notempty-message="'.lang('title_required').'"'); ?>
                </div>
            </div>
            <?php if ( ! $Member) { ?>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('slug', 'slug'); ?>
                    <?= form_input('slug', set_value('slug'), 'class="form-control tip" id="slug" data-fv-regexp="true" data-fv-regexp-regexp="^[a-zA-Z0-9\_-]+$" data-fv-regexp-message="'.lang('slug_regex').'" required="required" data-fv-notempty-message="'.lang('slug_required').'"'); ?>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <?php if ($Admin || $Moderator) { ?>
                    <div class="form-group">
                        <?= lang('description', 'description'); ?>
                        <?= form_input('description', set_value('description'), 'class="form-control tip description" id="description" required="required" data-fv-notempty-message="'.lang('description_required').'"'); ?>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <?= lang('body', 'body'); ?>
                    <?= form_textarea('body', set_value('body'), 'class="form-control tip body" id="body" '.( $this->Settings->editor == 'simpledme' ? '' : 'required="required" data-fv-notempty-message="'.lang('body_required').'"')); ?>
                </div>
            </div>
            <div id="fields" class="col-md-12">
                <?php
                if ( ! empty($fields)) {
                    $fields_data = $this->tec->fields($fields);
                    echo $fields_data['html'];
                }
                ?>
            </div>
            <div id="category_fields" class="col-md-12"></div>
            <div id="child_category_fields" class="col-md-12"></div>
            <div class="col-md-12">
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
                <div class="form-group">
                    <?= lang('who_can_see', 'private'); ?>
                    <label class="radio" for="for_public">
                        <input type="radio" name="protected" value="0" checked="checked" id="for_public"/>
                        <span class="icon"><i class="fa fa-circle"></i></span>
                        <?= lang('everyone') ?>
                    </label>
                    <label class="radio" for="for_members">
                        <input type="radio" name="protected" value="1" id="for_members"/>
                        <span class="icon"><i class="fa fa-circle"></i></span>
                        <?= lang('members') ?>
                    </label>
                    <label class="radio" for="for_staff">
                        <input type="radio" name="protected" value="2" id="for_staff"/>
                        <span class="icon"><i class="fa fa-circle"></i></span>
                        <?= lang('staff_n_me') ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12 text-left">
                <div class="form-group">
                    <?php if ($this->session->userdata('subscription') == 3) { ?>
                    <label class="checkbox" for="notify">
                        <input type="checkbox" name="notify" value="1" id="notify" checked="checked"/>
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <?= lang('email_me_when_replied') ?>
                    </label>
                    <?php } ?>
                    <?php if ($Admin || $Moderator) { ?>
                    <label class="checkbox" for="sticky_category">
                        <input type="checkbox" name="sticky_category" value="1" id="sticky_category" />
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <?= lang('sticky_category') ?>
                    </label>
                    <?php if ($Admin) { ?>
                    <label class="checkbox" for="sticky">
                        <input type="checkbox" name="sticky" value="1" id="sticky" />
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <?= lang('sticky') ?>
                    </label>
                    <label class="checkbox" for="active">
                        <input type="checkbox" name="active" value="1" id="active" checked="" />
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <?= lang('active') ?>
                    </label>
                    <?php } ?>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <?= form_submit('add_topic', lang('add_topic'), 'class="btn btn-primary btn-lg"'); ?>
                </div>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
