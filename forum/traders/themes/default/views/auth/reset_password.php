<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $page_title .' - '.$Settings->site_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $assets; ?>components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assets ?>css/style.css">
    <?php if ($Settings->style != 'white') { ?>
    <link rel="stylesheet" href="<?= $assets ?>css/<?= $Settings->style; ?>.css">
    <?php } ?>
</head>
<body class="login">
    <div class="logo">
        <h2 class="text-center">
            <a href="<?= base_url(); ?>">
                <img src="<?=base_url('uploads/'.$Settings->logo); ?>" alt="<?= $Settings->site_name; ?>">
            </a>
        </h2>
    </div>

    <div id="reset_password" class="login-box text-center">
        <div class="login-form">
            <div class="main-login-form">
                <?= form_open('reset_password/' . $code); ?>
                <p><?= sprintf(lang('reset_password_email'), $identity_label); ?></p>
                <div class="form-group">
                    <?= form_input($new_password); ?>
                </div>
                <div class="form-group">
                    <?= form_input($new_password_confirm); ?>
                </div>
                <?= form_input($user_id); ?>
                <?= form_hidden($csrf); ?>
                <button type="submit" class="btn btn-primary btn-block"><?= lang('reset_password'); ?></button>
                <?= form_close(); ?>
            </div>
            <div class="login-form-links">
                <p><?= lang('have_account'); ?> <a class="togggle_div" href="<?= site_url('login'); ?>"><?= lang('login_here'); ?></a></p>
            </div>
        </div>
    </div>

</body>
</html>
