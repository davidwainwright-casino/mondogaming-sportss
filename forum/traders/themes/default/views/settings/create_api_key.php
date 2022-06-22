<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('create_api_key'); ?></h4>
        </div>
        <?= form_open("settings/create_api_key", 'id="categoryForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('reference', 'reference'); ?>
                            <?= form_input('reference', set_value('reference'), 'class="form-control" id="reference" required="" data-fv-notempty-message="'.lang('reference_required').'"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('level', 'level'); ?>
                            <?php $opts = array(1 => lang('read'), 2 => lang('read_write_delete')) ?>
                            <?= form_dropdown('level', $opts, set_value('level'), 'class="form-control tip select2" id="level" required="required" style="width:100%;"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('ip_addresses', 'ip_addresses'); ?>
                            <?= form_input('ip_addresses', set_value('ip_addresses'), 'class="form-control" id="ip_addresses"'); ?>
                        </div>
                        <div class="form-group">
                            <?= lang('ignore_limits', 'ignore_limits'); ?>
                            <?= form_input('ignore_limits', set_value('ignore_limits'), 'class="form-control" id="ignore_limits"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <?= form_submit('create_api_key', lang('create_api_key'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
