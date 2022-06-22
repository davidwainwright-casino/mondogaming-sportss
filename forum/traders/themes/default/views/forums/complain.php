<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:1.5em;margin-top:-0.5em;"><span aria-hidden="true" style="font-size:2em;">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?=$page_title;?></h4>
        </div>
        <div class="modal-body terms-text">
            <?= form_open("complain/".$thread->slug.($post_id ? '?post='.$post_id : ''), 'id="flagForm"'); ?>
            <p style="font-weight:bold;"><?= lang('thread').': <span style="text-transform: uppercase;">'.$thread->title.'</span>'; ?></p>
            <div class="form-group">
                <?= lang('reason_label'); ?>
                <?= form_textarea('reason', '', 'class="form-control" id="reason" required="required" style="height: 60px !important;"'); ?>
            </div>

            <?= form_submit('complain', lang('submit'), 'class="btn btn-primary"'); ?>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.modal').on('shown.bs.modal', function() {
            $("#reason").focus();
        });
        $('#flagForm')
        .formValidation({
            framework: 'bootstrap',
            fields: {
                reason: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('required'); ?>'
                        }
                    }
                }
            }
        });
    });
</script>
