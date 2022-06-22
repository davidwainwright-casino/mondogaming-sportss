<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content mm-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('add_page'); ?></h4>
        </div>
        <?= form_open("pages/add", 'id="pageForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5">
                        <div class="form-group">
                            <?= lang('title', 'title'); ?>
                            <?= form_input('title', set_value('title'), 'class="form-control" id="title" pattern=".{3,10}" required="" data-fv-notempty-message="'.lang('title_required').'"'); ?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <?= lang('slug', 'slug'); ?>
                            <?= form_input('slug', set_value('slug'), 'class="form-control" id="slug" required="" data-fv-notempty-message="'.lang('slug_required').'"'); ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?= lang('menu_order', 'order_no'); ?>
                            <?= form_input('order_no', set_value('order_no'), 'class="form-control" id="order_no" required=""'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('description', 'description'); ?>
                            <?= form_input('description', set_value('description'), 'class="form-control" id="description" required="" data-fv-notempty-message="'.lang('description_required').'"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('body', 'body'); ?>
                            <?= form_textarea('body', set_value('body'), 'class="form-control body" id="body" required="" data-fv-notempty-message="'.lang('body_required').'"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <div class="pull-left">
                    <label class="checkbox" for="active">
                        <input type="checkbox" name="active" value="1" id="active" checked="checked"/>
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <?= lang('active') ?>
                    </label>
                </div>
                <?= form_submit('add_page', lang('add_page'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
