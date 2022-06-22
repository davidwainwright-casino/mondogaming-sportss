<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('add_category'); ?></h4>
        </div>
        <?= form_open("categories/add", 'id="categoryForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('title', 'title'); ?>
                            <?= form_input('name', set_value('name'), 'class="form-control" id="title" required="" data-fv-notempty-message="'.lang('title_required').'"'); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('slug', 'slug'); ?>
                            <?= form_input('slug', set_value('slug'), 'class="form-control" data-fv-regexp="true" data-fv-regexp-regexp="^[a-zA-Z0-9\_-]+$" data-fv-regexp-message="'.lang('slug_regex').'" id="slug" required="required" data-fv-notempty-message="'.lang('slug_required').'"'); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('parent_category', 'parent_category'); ?>
                            <?php
                            $pcs[''] = lang('select_parent');
                            if($parent_categories) {
                                foreach ($parent_categories as $pc) {
                                    $pcs[$pc->id] = $pc->name;
                                }
                            }
                            ?>
                            <?= form_dropdown('parent_category', $pcs, set_value('parent_category'), 'class="form-control select2" id="parent_category" style="width:100%;"'); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('order', 'order'); ?>
                            <?= form_input('order', set_value('order'), 'class="form-control" id="order"'); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('active', 'active'); ?>
                            <?php $yn = array(0 => lang('no'), 1 => lang('yes')); ?>
                            <?= form_dropdown('active', $yn, set_value('active', 1), 'class="form-control tip select2" id="active" style="width:100%;"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('private', 'private'); ?>
                            <?php $yn = array(0 => lang('no'), 1 => lang('yes')); ?>
                            <?= form_dropdown('private', $yn, set_value('private', 0), 'class="form-control tip select2" id="private" style="width:100%;"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('description', 'description'); ?>
                            <?= form_textarea('description', set_value('description'), 'class="form-control mh" id="description" required="" data-fv-notempty-message="'.lang('description_required').'"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <?= form_submit('add_category', lang('add_category'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
