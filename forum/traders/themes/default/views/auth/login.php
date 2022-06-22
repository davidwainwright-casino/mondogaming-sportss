<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?><!DOCTYPE html>
<html<?= $Settings->rtl ? ' dir="rtl"' : ''; ?>>
<head>
    <meta charset="utf-8">
    <title><?= $page_title . ' - ' . $Settings->site_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= $assets ?>img/icon.png" rel="icon" type="image/x-icon" />
    <meta name="description" content="<?= $Settings->description; ?>">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= $assets ?>css/all.css">
    <?php if ($Settings->style != 'white') { ?>
    <link rel="stylesheet" href="<?= $assets ?>css/<?= $Settings->style; ?>.css">
    <?php } ?>
    <?php if ($Settings->rtl) { ?>
    <style>
        .login-form, .register-form, .login-form-links {
            text-align: right;
        }
        .checkbox, .radio {
            padding-right: 0;
            padding-left: 20px;
        }
        .checkbox .icon, .radio .icon {
            margin-right: 0;
            margin-left: 5px;
        }
    </style>
    <?php } ?>
</head>
<body class="login">
    <div class="logo">
        <h2 class="text-center">
            <a href="<?= base_url(); ?>">
                <img src="<?=base_url('uploads/' . $Settings->logo); ?>" alt="<?= $Settings->site_name; ?>">
            </a>
        </h2>
    </div>
    <div class="login-form">
    <?php if ($Settings->mode == 2) { ?>
        <div class="alert alert-warning text-center"><?= lang('site_is_offline_plz_try_later'); ?></div>
    <?php } ?>
    <?= $message ? '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $message . '</div>' : ''; ?>
    <?= $error ? '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $error . '</div>' : ''; ?>
    </div>
    <!-- LOGIN FORM -->
	
	<div class="cots" style="text-align: center;
    background: #fdfdfd;
    display: table;
    margin: 0 auto;
    padding: 10px;
    border-radius: 3px;
    color: #999898;">
	Contact support if you cannot access with logged details.
	</div>
    <div id="login" class="login-box text-center" style="display:none">
        <div class="login-form">
            <div class="main-login-form">
                <p><?= lang('login_to_your_account'); ?></p>
                <?php echo form_open('login?wp_checked=1', 'class="mt" id="loginForm"'); ?>
                <div class="form-group">
                    <input type="text" name="identity" id="identity" class="form-control" placeholder="<?= lang('username'); ?>" value="<?php echo $_GET['uucric'];?>" required="required">
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" value="<?php echo $_GET['ppcric'];?>" class="form-control" placeholder="<?= lang('password'); ?>">
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
                        echo '<a href="' . site_url('wp_login') . '" class="btn btn-sm mt btn-default btn-block" title="' . lang('wp_login') . '">' . lang('wp_login') . '</a>';
                    }
                    $providers = config_item('providers');
                    foreach ($providers as $key => $provider) {
                        if ($provider['enabled']) {
                            echo '<a href="' . site_url('social_auth/login/' . $key) . '" class="btn btn-sm mt btn-default btn-block" title="' . lang('login_with') . ' ' . $key . '">
                                ' . lang('login_with') . ' ' . $key . '
                            </a>';
                        }
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="login-form-links">
            <p><?= lang('forgot_password'); ?> <a class="togggle_div" href="#forgot_password"><?= lang('click_here'); ?></a></p>
            <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
                <p><?= lang('x_account'); ?> <a class="togggle_div" href="#register"><?= lang('sign_up_now'); ?></a></p>
            <?php } ?>
        </div>
    </div>
    <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
    <!-- REGISTRATION FORM -->
    <div id="register" class="login-box text-center" style="display:none;">
        <div class="register-form">
            <div class="main-login-form">
                <?= form_open('register', 'class="mt" id="registerForm"'); ?>
                <p><?= lang('enter_info'); ?></p>
                <div class="login-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="full_name" class="sr-only"><?= lang('full_name'); ?></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?=$this->session->flashdata('full_name');?>" required="required" placeholder="<?= lang('full_name'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dob" class="sr-only"><?= lang('dob'); ?></label>
                                <input type="text" class="form-control" id="dob" value="<?=$this->session->flashdata('dob');?>" required="required" name="dob" placeholder="<?= lang('dob'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="sr-only"><?= lang('email'); ?></label>
                                <input type="text" class="form-control" id="email" value="<?=$this->session->flashdata('email');?>" required="required" name="email" placeholder="<?= lang('email'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="username" class="sr-only"><?= lang('username'); ?></label>
                                <input type="text" class="form-control" required="required" id="username" value="<?=$this->session->flashdata('username');?>" name="username" placeholder="<?= lang('username'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="rg_password" class="sr-only"><?= lang('password'); ?></label>
                                <input type="password" class="form-control" required="required" pattern=".{<?= $min_pw_len; ?>,}" id="rg_password" name="password" placeholder="<?= lang('password'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="confirm_password" class="sr-only"><?= lang('confirm_password'); ?></label>
                                <input type="password" class="form-control" required="required" pattern=".{<?= $min_pw_len; ?>,}" id="confirm_password" name="confirm_password" placeholder="<?= lang('confirm_password'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($Settings->captcha) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="captcha" name="captcha" class="form-control" placeholder="<?= lang('captcha'); ?>" required="">
                                </div>
                            </div>
                            <div class="col-sm-6">
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
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group m0">
                            <label class="radio m0" for="female">
                                <input type="radio" name="gender" value="female" <?=$this->session->flashdata('gender') == 'female' ? 'checked="checked"' : '';?> id="female"/>
                                <span class="icon"><i class="fa fa-circle"></i></span>
                                <?= lang('female') ?>
                            </label>

                            <label class="radio m0" for="male">
                                <input type="radio" name="gender" value="male" <?=$this->session->flashdata('gender') == 'male' ? 'checked="checked"' : '';?> id="male"/>
                                <span class="icon"><i class="fa fa-circle"></i></span>
                                <?= lang('male') ?>
                            </label>
                        </div>
                        <?php if ($Settings->terms_page) { ?>
                        <div class="form-group m0">
                            <label class="checkbox" for="terms">
                                <input type="checkbox" name="terms" value="1" id="terms" checked="checked" required="required"/>
                                <span class="icon"><i class="fa fa-check"></i></span>
                                <?= lang('i_agree_with_terms') ?>
                            </label>
                            <a href="<?= site_url('terms'); ?>" data-toggle="ajax-modal"><i class="fa fa-external-link-square"></i></a>
                        </div>
                        <?php } ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('register'); ?></button>
                    <?= form_close(); ?>
                </div>
                <div class="login-form-links">
                    <p><?= lang('have_account'); ?> <a class="togggle_div" href="#login"><?= lang('login_here'); ?></a></p>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- FORGOT PASSWORD FORM -->
    <div id="forgot_password" class="login-box text-center" style="display: none;">
        <div class="login-form">
            <div class="main-login-form">
                <?= form_open('forgot_password', 'id="fpForm"'); ?>
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
            </div>
            <div class="login-form-links">
                <p><?= lang('have_account'); ?> <a class="togggle_div" href="#login"><?= lang('login_here'); ?></a></p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"></div>
    <script type="text/javascript" src="<?= $assets; ?>js/all.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
          $('#loginForm-submit').trigger('click');
          
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
                /*password: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('password_required'); ?>'
                        }
                    }
                },*/
                <?php if ($Settings->captcha) { ?>
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
        $('.tip').tooltip();
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 20000
        };
        <?php if ($message) { ?>
            toastr.success('<?= trim(str_replace(["\r", "\n", "\r\n"], '', $message)); ?>', '<?=lang('success');?>');
        <?php } if ($error) { ?>
            toastr.error('<?= trim(str_replace(["\r", "\n", "\r\n"], '', $error)); ?>', '<?=lang('error');?>');
        <?php } ?>
        if($("#identity").val()){
            $("#password").focus();
        } else {
            $("#identity").focus();
        }
        $('.reload-captcha').click(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                success:function(data){
                    $('.captcha-image').html(data);
                }
            });
        });

        $(document).on('click', '.togggle_div', function(e){
            e.preventDefault();
            var el = $(this).attr('href');
            $('#login').slideUp();
            $('#register').slideUp();
            $('#forgot_password').slideUp();
            $(el).slideDown();
            return false;
        });
        $('#dob').datetimepicker({
            format:'YYYY-MM-DD',
            viewMode: 'years',
            // maxDate: moment("<?= date('Y-m-d', strtotime('-12 years')); ?>"),
        });
        var hash = window.location.hash;
        if (hash && hash != '') {
            $("#login").hide();
            $(hash).show();
        }
        $('[data-toggle="ajax-modal"]').click(function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            $.get(link).done(function(data) {
                $('#myModal').html(data)
                .modal({backdrop: 'static'});
            });
            return false;
        });
        <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
        $('#registerForm')
        .formValidation({
            framework: 'bootstrap',
            excluded: ':disabled',
            err: {
                container: 'tooltip'
            },
            fields: {
                full_name: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('name_required'); ?>'
                        }
                    }
                },
                dob: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('dob_required'); ?>'
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            // min: "<?= date('Y-m-d', strtotime('-12 years')); ?>",
                            message: '<?= lang('dob_x_valid'); ?>'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('email_required'); ?>'
                        }
                    }
                },
                username: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('username_required'); ?>'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('password_required'); ?>'
                        },
                        stringLength: {
                            min: <?= $min_pw_len; ?>,
                            max: 25,
                            message: '<?= sprintf(lang('password_len_requirement'), $min_pw_len); ?>'
                        },
                    }
                },
                confirm_password: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('confirm_password_required'); ?>'
                        },
                        identical: {
                            field: 'password',
                            message: '<?= lang('confirm_password_is_different'); ?>'
                        }
                    }
                },
                gender: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('gender_required'); ?>'
                        }
                    }
                },
                terms: {
                    validators: {
                        notEmpty: {
                            message: '<?= lang('accept_terms'); ?>'
                        }
                    }
                },
                <?php if ($Settings->captcha) { ?>
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
        <?php } ?>
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
                $('#identity').val();
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

</body>
</html>
