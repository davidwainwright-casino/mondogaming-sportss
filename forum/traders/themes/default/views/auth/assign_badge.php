<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('assign_badge'); ?></h4>
        </div>
        <?= form_open("users/assign_badge/".$user_id); ?>
        <div class="modal-body mm-body">

                <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('select_badge', 'badge'); ?>
                            <?php
                            $pcs[''] = lang('select_badge');
                            if($badges) {
                                foreach ($badges as $pc) {
                                    $pcs[$pc->id] = $pc->title;
                                }
                            }
                            ?>
                            <?= form_dropdown('badge', $pcs, set_select('badge'), 'class="form-control select2" id="badge" style="width:100%;" required="required"'); ?>
                        </div>
                </div>
            
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <?= form_submit('assign_badge', lang('assign_badge'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
