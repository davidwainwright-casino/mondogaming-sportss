<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="vertical-alignment-helper">
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content">
            <div class="modal-body">
                <a role="button" class="close" data-dismiss="modal" style="margin-top:-8px;">&times;</a>
                <div class="logo">
                    <h2 class="text-center" style="margin-top: 0;">
                        <a href="<?= base_url(); ?>">
                            <img src="<?=base_url('uploads/'.$Settings->logo); ?>" alt="<?= $Settings->site_name; ?>">
                        </a>
                    </h2>
                </div>

                <?= form_open("forgot_password", 'id="fpForm"'); ?>
                <p><?= lang('forgot_password_heading'); ?></p>
                <div class="login-group">
                    <div class="form-group">
                        <label for="fp_email" class="sr-only"><?= lang('email'); ?></label>
                        <input type="email" name="forgot_email" placeholder="<?= lang('email'); ?>" autocomplete="off"
                        class="form-control" id="fp_email" required="required">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-send"></i> <?= lang('submit'); ?></button>
                <?= form_close(); ?>

                <div class="mt">
                    <p><?= lang('have_account'); ?> <a data-toggle="ajax-modal" href="<?= site_url('login_modal'); ?>"><?= lang('login_here'); ?></a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#fpForm')
        .formValidation({
            framework: 'bootstrap',
            fields: {
                forgot_email: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('email_required'); ?>'
                        }
                    }
                }
            }
        });

        $('[data-toggle="ajax-modal"]').click(function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            $.get(link).done(function(data) {
                $('#myModal').html(data)
                .modal({backdrop: 'static'});
            });
            return false;
        });
    });
</script>
