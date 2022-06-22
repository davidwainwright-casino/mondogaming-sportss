<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
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

                <?php if ($Settings->mode != 2 && $Settings->registration) { ?>

                    <?= form_open("register", 'class="mt" id="registerForm"'); ?>
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
                                <a href="<?= site_url('pages/terms'); ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('register'); ?></button>
                        <?= form_close(); ?>

                        <div class="mt">
                            <p><?= lang('have_account'); ?> <a data-toggle="ajax-modal" href="<?= site_url('login_modal'); ?>"><?= lang('login_here'); ?></a></p>
                        </div>

                        <?php } else { ?>
                            <h3><?= lang('registration_is_closed'); ?></h3>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    <script type="text/javascript">
    $(document).ready(function() {
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

        <?php if ($Settings->mode != 2 && $Settings->registration) { ?>

        $('#dob').datetimepicker({
            format:'YYYY-MM-DD',
            viewMode: 'years',
            // maxDate: moment("<?= date('Y-m-d', strtotime('-12 years')); ?>"),
        });

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
        <?php } ?>
    });
    </script>
