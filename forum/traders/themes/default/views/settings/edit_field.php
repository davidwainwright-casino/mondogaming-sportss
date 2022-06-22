<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-times"></i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= $page_title; ?></h4>
        </div>
        <?= form_open("settings/edit_field/".$field->id, 'id="fieldForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('name', 'name'); ?>
                            <?= form_input('name', set_value('name', $field->name), 'class="form-control tip" id="name"  required="required"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('category', 'category'); ?>
                            <?php
                            $cs[''] = lang('select_category');
                            if($categories) {
                                foreach ($categories as $c) {
                                    $cs[$c->id] = $c->name;
                                }
                            }
                            ?>
                            <?= form_dropdown('category', $cs, set_value('category', $field->category_id), 'class="form-control select2" id="category" style="width:100%;"'); ?>
                        </div>
                        <div class="type_con">
                            <div class="form-group">
                                <label><?= lang('type'); ?></label>
                                <?php $options = array('text' => lang('text'), 'select' => lang('select'), 'checkbox' => lang('checkbox'), 'radio' => lang('radio'), 'url' => lang('url')); ?>
                                <?= form_dropdown('type', $options, $field->type, 'class="form-control cftype select2" style="width:100%;"'); ?>
                            </div>
                            <div class="form-group" id="select-con" style="display:<?= $field->type != 'text' ? ' block' : 'none'; ?>;">
                                <label><?= lang('options', 'cfoptions'); ?></label>
                                <?= form_input('options', $field->options, 'class="form-control" id="cfoptions"'); ?>
                                <span class="help-block"><?= lang('options_tip'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <?= lang('required', 'req_field'); ?>
                            <label class="radio" for="for_no">
                                <input type="radio" name="req_field" value="0" <?= $field->required ? '' : 'checked="checked"'; ?> id="for_no"/>
                                <span class="icon"><i class="fa fa-circle"></i></span>
                                <?= lang('no') ?>
                            </label>
                            <label class="radio" for="for_yes">
                                <input type="radio" name="req_field" value="1" <?= $field->required ? 'checked="checked"' : ''; ?> id="for_yes"/>
                                <span class="icon"><i class="fa fa-circle"></i></span>
                                <?= lang('yes') ?>
                            </label>
                        </div>
                        <div class="form-group">
                            <?= lang('public', 'public'); ?>
                            <label class="radio" for="for_no1">
                                <input type="radio" name="public" value="0" <?= $field->public ? '' : 'checked="checked"'; ?> id="for_no1"/>
                                <span class="icon"><i class="fa fa-circle"></i></span>
                                <?= lang('no') ?>
                            </label>
                            <label class="radio" for="for_yes1">
                                <input type="radio" name="public" value="1" <?= $field->public ? 'checked="checked"' : ''; ?> id="for_yes1"/>
                                <span class="icon"><i class="fa fa-circle"></i></span>
                                <?= lang('yes') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <?= form_submit('update_field', lang('update_field'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".cftype").change(function () {
            var sel = $(this).closest('.type_con');
            var type = $(this).val();
            if (type == 'text' || type == 'url') {
                sel.find('#select-con').slideUp();
            } else {
                sel.find('#select-con').slideDown();
            }
        });
    });
</script>
