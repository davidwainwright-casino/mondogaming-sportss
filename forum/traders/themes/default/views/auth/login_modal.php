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

                <p><?= lang('login_to_your_account'); ?></p>
                <?php echo form_open("login?wp_checked=1", 'class="mt" id="loginForm"'); ?>
                <div class="form-group">
                    <input type="text" name="identity" id="identity" class="form-control" placeholder="<?= lang('username'); ?>" required="required">
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="<?= lang('password'); ?>" required="required">
                </div>
                <?php if ($Settings->captcha) { ?>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="captcha-image"><?= $image; ?></span>
                            <span class="input-group-addon" id="basic-addon2">
                                <a href="<?= base_url(); ?>users/reload_captcha" class="reload-captcha">
                                    <i class="fa fa-refresh"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="captcha" name="captcha" class="form-control" placeholder="<?= lang('captcha'); ?>" required="">
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="checkbox m0" for="remember">
                            <input type="checkbox" name="remember" value="1" id="remember" checked="checked"/>
                            <span class="icon"><i class="fa fa-check"></i></span>
                            <?= lang('remember') ?>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="loginForm-submit">
                        <i class="fa fa-sign-in"></i> <?= lang('login'); ?>
                    </button>
                    <?= form_close(); ?>

                    <div class="social-login-icons">
                        <?php if (DEMO) { ?>
                            <h4 style="margin-bottom:0;">Demo Login as</h4>
                            <button type="button" id="admin-login" class="btn btn-sm mt btn-default col-xs-6" style="text-align:left;">Admin</button>
                            <button type="button" id="member1-login" class="btn btn-sm mt btn-default col-xs-5 pull-right" style="text-align:left;">User1</button>
                            <button type="button" id="mod-login" class="btn btn-sm mt btn-default col-xs-6" style="text-align:left;">Moderator</button>
                            <button type="button" id="member2-login" class="btn btn-sm mt btn-default col-xs-5 pull-right" style="text-align:left;">User2</button>
                            <div class="clearfix"></div>
                        <?php } ?>
                        <?php
                        if ($this->Settings->wp_login) { 
                            echo '<a href="'.site_url('wp_login').'" class="btn btn-sm mt btn-default btn-block" title="'.lang('wp_login').'">'.lang('wp_login').'</a>';
                        }
                        $providers = config_item('providers');
                        foreach($providers as $key => $provider) {
                            if($provider['enabled']) {
                                echo '<a href="'.site_url('social_auth/login/'.$key).'" class="btn btn-sm mt btn-default btn-block" title="'.lang('login_with').' '.$key.'">
                                '.lang('login_with').' '.$key.'
                            </a>';
                        }
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>

                <div class="mt">
                    <p><?= lang('forgot_password'); ?> <a data-toggle="ajax-modal" href="<?= site_url('forgot_password_modal'); ?>"><?= lang('click_here'); ?></a></p>
                    <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
                        <p><?= lang('x_account'); ?> <a data-toggle="ajax-modal" href="<?= site_url('register_modal'); ?>"><?= lang('sign_up_now'); ?></a></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#loginForm')
        .formValidation({
            framework: 'bootstrap',
            fields: {
                identity: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('identity_required'); ?>'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('password_required'); ?>'
                        }
                    }
                },
                <?php if($Settings->captcha) { ?>
                    captcha: {
                        validators: {
                            notEmpty: {
                                message: '<?= lang('captcah_required'); ?>'
                            }
                        }
                    }
                    <?php } ?>
                }
            });
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

        $('.modal').on('shown.bs.modal', function() {
            if($("#identity").val()){
                $("#password").focus();
            } else {
                $("#identity").focus();
            }
        });
        
        $('.reload-captcha').click(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                success:function(data){
                    $('.captcha-image').html(data);
                }
            });
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

        <?php if (DEMO) { ?>
            $('#admin-login').click(function(event) {
                $('#identity').val('admin');
                $('#password').val('12345678');
                $('#loginForm-submit').click();
            });
            $('#mod-login').click(function(event) {
                $('#identity').val('moderator');
                $('#password').val('12345678');
                $('#loginForm-submit').click();
            });
            $('#member1-login').click(function(event) {
                $('#identity').val('user1');
                $('#password').val('12345678');
                $('#loginForm-submit').click();
            });
            $('#member2-login').click(function(event) {
                $('#identity').val('user2');
                $('#password').val('12345678');
                $('#loginForm-submit').click();
            });
        <?php } ?>
    });
</script>
