<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="col-sm-8">
    <div class="page-box">
        <?php
        $ps = array('0' => $this->lang->line("disable"), '1' => $this->lang->line("enable"));
        ?>

        <h3><i class="fa fa-cogs"></i> <?= lang('settings'); ?></h3>
        <p><?= lang('update_info'); ?></p>

        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("settings", $attrib); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("site_name", "site_name"); ?>
                    <?php echo form_input('site_name', $Settings->site_name, 'class="form-control tip" id="site_name"  required="required"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('mode', 'mode'); ?>
                    <?php
                    $eopt = array(0 => lang('public'), 1 => lang('private'), 2 => lang('maintainence'));
                    echo form_dropdown('mode', $eopt, $Settings->mode, 'class="form-control tip select2" id="mode"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?= lang("description", "description"); ?>
                    <?php echo form_input('description', $Settings->description, 'class="form-control tip" id="description"  required="required"'); ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?= lang("banned_words", "banned_words"); ?>
                    <?php echo form_input('banned_words', $Settings->banned_words, 'class="form-control tip" id="banned_words"  required="required"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("censore_word", 'censore_word'); ?><?php echo form_input('censore_word', $Settings->censore_word, 'class="form-control tip" id="censore_word"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('language', 'language'); ?>
                    <?php
                    $available_langs = array(
                        'english' => 'English',
                        );
                    ?>
                    <?= form_dropdown('language', $available_langs, $Settings->language, 'class="form-control tip select2" id="language"  required="required" style="width:100%;"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("default_email", 'email'); ?><?php echo form_input('email', $Settings->default_email, 'class="form-control tip" required="required" id="email"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("date_format", 'date_format'); ?>
                    <?php echo form_input('date_format', $Settings->dateformat, 'class="form-control tip" id="date_format" required="required"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("time_format", 'time_format'); ?>
                    <?php echo form_input('time_format', $Settings->timeformat, 'class="form-control tip" id="time_format" required="required"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("records_per_page", 'records_per_page'); ?>
                    <?php echo form_input('records_per_page', $Settings->records_per_page, 'class="form-control tip" id="records_per_page" required="required"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('style', 'style'); ?>
                    <?php
                    $sopt = array('white' => 'White', 'orange' => 'Orange', 'blue' => 'Blue', 'red' => 'Red', 'green' => 'Green', 'purple' => 'Purple', 'dark_grey' => 'Dark Grey', 'grey' => 'Grey', 'yellow' => 'Yellow');
                    echo form_dropdown('style', $sopt, $Settings->style, 'class="form-control tip select2" id="style"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('login_register_style', 'login_modal'); ?>
                    <?php
                    $opts = array(0 => lang('page'), 1 => lang('modal'));
                    ?>
                    <?= form_dropdown('login_modal', $opts, $Settings->login_modal, 'class="form-control select2" id="login_modal" required="required" style="width:100%;"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('sidebar', 'sidebar'); ?>
                    <?php
                    if ($Settings->rtl) {
                        if ($Settings->sidebar == 'left') {
                            $sidebar = 'right';
                        } elseif ($Settings->sidebar == 'right') {
                            $sidebar = 'left';
                        }
                    } else {
                        $sidebar =$Settings->sidebar;
                    }
                    $sopt = array('right' => lang('right'), 'left' => lang('left'));
                    echo form_dropdown('sidebar', $sopt, $sidebar, 'class="form-control tip select2" id="sidebar"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('editor', 'textarea'); ?>
                    <?php
                    $eopt = array('redactor' => 'Trumbowyg (HTML)', 'simpledme' => 'SimpleMDE (Markdown)', 'sceditor' => 'SCEditor (BBCODE)');
                    echo form_dropdown('editor', $eopt, $Settings->editor, 'class="form-control tip select2" id="textarea"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('captcha', 'captcha'); ?>
                    <?php
                    $copt = array(0 => lang('disable'), 1 => lang('login'), 2 => lang('forum'));
                    echo form_dropdown('captcha', $copt, $Settings->captcha, 'class="form-control tip select2" id="captcha"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('captcha_length', 'captcha_length'); ?>
                    <?php
                    $copt = array(4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8');
                    echo form_dropdown('captcha_length', $copt, $Settings->captcha_length, 'class="form-control tip select2" id="captcha_length"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <?= lang('facebook', 'facebook'); ?>
                <?= form_input('facebook', set_value('facebook', $Settings->facebook), 'class="form-control tip" id="facebook"'); ?>
            </div>
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('twitter', 'twitter'); ?>
                    <?= form_input('twitter', set_value('twitter', $Settings->twitter), 'class="form-control tip" id="twitter"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('google_plus', 'google_plus'); ?>
                    <?= form_input('google_plus', set_value('google_plus', $Settings->google_plus), 'class="form-control tip" id="google_plus"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('terms_page', 'terms_page'); ?>
                    <?php
                    $popt = array('' => lang('select_terms_page'));
                    foreach ($menu_pages as $menu_page) {
                        $popt[$menu_page->slug] = $menu_page->title;
                    }
                    echo form_dropdown('terms_page', $popt, $Settings->terms_page, 'class="form-control tip select2" id="terms_page"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('review_option', 'review_option'); ?>
                    <?php
                    $ropt = array(0 => lang('disable'), 1 => lang('all'), 2 => lang('first_five'), 3 => lang('first_ten'));
                    echo form_dropdown('review_option', $ropt, $Settings->review_option, 'class="form-control tip select2" id="review_option"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('notification', 'notification'); ?>
                    <?php
                    $nopt = array(0 => lang('disable'), 1 => lang('admins'), 2 => lang('admins_n_mods'));
                    echo form_dropdown('notification', $nopt, $Settings->notification, 'class="form-control tip select2" id="notification"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('sorting', 'sorting'); ?>
                    <?php
                    $sopt = array(0 => lang('newest'), 1 => lang('oldest'), 2 => lang('higher_votes'), 3 => lang('lower_votes'), 4 => lang('newest_reply'), 5 => lang('oldest_reply'), 6 => lang('most_views'), 7 => lang('least_views'));
                    echo form_dropdown('sorting', $sopt, $Settings->sorting, 'class="form-control tip select2" id="sorting"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('reply_sorting', 'reply_sorting'); ?>
                    <?php
                    $rsopt = array(0 => lang('desc'), 1 => lang('asc'));
                    echo form_dropdown('reply_sorting', $rsopt, $Settings->reply_sorting, 'class="form-control tip select2" id="reply_sorting"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('voting', 'voting'); ?>
                    <?php
                    $vopt = array(0 => lang('disable'), 1 => lang('thumbs_rating'), 2 => lang('stars_rating'));
                    echo form_dropdown('voting', $vopt, $Settings->voting, 'class="form-control tip select2" id="voting"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('change_vote', 'change_vote'); ?>
                    <?php
                    $ynopt = array(0 => lang('no'), 1 => lang('yes'));
                    echo form_dropdown('change_vote', $ynopt, $Settings->change_vote, 'class="form-control tip select2" id="change_vote"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('api_keys', 'apis'); ?>
                    <?php
                    $api_opts = array(0 => lang('disable'), 1 => lang('enable_admins'), 2 => lang('enable_all'));
                    echo form_dropdown('apis', $api_opts, $Settings->apis, 'class="form-control tip select2" id="apis" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('rtl_support', 'rtl'); ?>
                    <?php
                    $yn = array(0 => lang('disable'), 1 => lang('enable'));
                    echo form_dropdown('rtl', $yn, $Settings->rtl, 'class="form-control tip select2" id="rtl" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('guest_reply', 'guest_reply'); ?>
                    <?= form_dropdown('guest_reply', $yn, $Settings->guest_reply, 'class="form-control select2" id="guest_reply" required="required" style="width:100%;"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('registration', 'registration'); ?>
                    <?= form_dropdown('registration', $yn, $Settings->registration, 'class="form-control select2" id="registration" required="required" style="width:100%;"'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('flag_option', 'flag_option'); ?>
                    <?php
                    $foopt = array(0 => lang('disable'), 1 => lang('hide_thread'), 2 => lang('show_thread'));
                    echo form_dropdown('flag_option', $foopt, $Settings->flag_option, 'class="form-control tip select2" id="flag_option"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('member_page', 'member_page'); ?>
                    <?php
                    $mpopt = array(0 => lang('disable'), 1 => lang('enable'));
                    echo form_dropdown('member_page', $mpopt, $Settings->member_page, 'class="form-control tip select2" id="member_page"  style="width:100%;" required="required"');
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('signature', 'signature'); ?>
                    <?= form_dropdown('signature', $yn, $Settings->signature, 'class="form-control select2" id="signature" required="required" style="width:100%;"'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('email_protocol', 'protocol'); ?>
                    <?php
                    $popt = array('mail' => 'PHP Mail Function', 'sendmail' => 'Send Mail', 'smtp' => 'SMTP');
                    echo form_dropdown('protocol', $popt, $Settings->protocol, 'class="form-control tip select2" id="protocol" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="sendmail_config" style="display: none;">
                <div class="col-md-12 well well-sm" style="border-radius:0;border:0;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang('mailpath', 'mailpath'); ?>
                            <?php echo form_input('mailpath', $Settings->mailpath, 'class="form-control tip" id="mailpath"'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="smtp_config" style="display: none;">
                <div class="col-md-12 well well-sm" style="border-radius:0;border:0;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("smtp_host", 'smtp_host'); ?>
                            <?php echo form_input('smtp_host', $Settings->smtp_host, 'class="form-control tip" id="smtp_host"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("smtp_user", 'smtp_user'); ?>
                            <?php echo form_input('smtp_user', $Settings->smtp_user, 'class="form-control tip" id="smtp_user"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("smtp_pass", 'smtp_pass'); ?>
                            <?php echo form_password('smtp_pass', $smtp_pass, 'class="form-control tip" id="smtp_pass"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("smtp_port", 'smtp_port'); ?>
                            <?php echo form_input('smtp_port', $Settings->smtp_port, 'class="form-control tip" id="smtp_port"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("smtp_crypto", 'smtp_crypto'); ?>
                            <?php
                            $crypto_opt = array('' => lang('none'), 'tls' => 'TLS', 'ssl' => 'SSL');
                            echo form_dropdown('smtp_crypto', $crypto_opt, $Settings->smtp_crypto, 'class="form-control tip select2" id="smtp_crypto" style="width:100%;"');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang('wp_login', 'wp_login'); ?>
                    <?php
                    $yn = array(0 => lang('disable'), 1 => lang('enable'));
                    echo form_dropdown('wp_login', $yn, $Settings->wp_login, 'class="form-control tip select2" id="wp_login" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="wordpress_config" style="display: none;">
                <div class="col-md-12 well well-sm" style="border-radius:0;border:0;">
                    <div class="col-md-12">
                        <p><strong>This is a beta feature for testing on the same domain. The <a href="https://wordpress.org/plugins/simple-forum-widgets/" target="_blank">Simple Forum Widgets</a> plugin should be installed on your wordpress site to get the information below.</strong><hr></p>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("wp_url", 'wp_url'); ?>
                            <?php echo form_input('wp_url', $Settings->wp_url, 'class="form-control tip" id="wp_url"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("wp_client_id", 'wp_client_id'); ?>
                            <?php echo form_input('wp_client_id', $Settings->wp_client_id, 'class="form-control tip" id="wp_client_id"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("wp_secret", 'wp_secret'); ?>
                            <?php echo form_input('wp_secret', $Settings->wp_secret, 'class="form-control tip" id="wp_secret"'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both; height: 10px;"></div>
            <div class="col-xs-12">
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom: 0;">
                                <?= lang("logo", "uploadLogo"); ?>
                                <div class="input-group">
                                    <input type="text" class="form-control tip upload-logo" placeholder="Choose Logo" disabled="disabled" />
                                    <div class="fileUpload input-group-addon btn btn-primary">
                                        <span><?= lang('browse'); ?></span>
                                        <input id="uploadLogo" name="logo" type="file" class="upload" />
                                    </div>
                                </div>
                                <span><small><?= lang('logo_tip'); ?></small></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom: 0;">
                                <?= lang("favicon", "uploadFav"); ?>
                                <div class="input-group">
                                    <input type="text" class="form-control tip upload-fav" placeholder="Choose Favicon" disabled="disabled" />
                                    <div class="fileUpload input-group-addon btn btn-primary">
                                        <span><?= lang('browse'); ?></span>
                                        <input id="uploadFav" name="favicon" type="file" class="upload" />
                                    </div>
                                </div>
                                <span><small><?= lang('fav_tip'); ?></small></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_submit('update_settings', $this->lang->line("update_settings"), 'class="btn btn-primary"'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div class="form-inline well well-sm">
                <div class="form-group col-sm-12">
                        <div class="row">
                            <label for="sitemap" class="col-sm-2 control-label" style="margin-top:8px;"><?= lang("sitemap"); ?></label>
                            <div class="col-sm-10">
                                <div class="input-group col-sm-12">
                                    <input type="text" class="form-control" value="<?= base_url('sitemap.xml'); ?>" readonly>
                                    <a href="<?= base_url('sitemap.xml'); ?>" target="_blank" class="input-group-addon btn btn-primary" id="basic-addon2"><?=lang('visit');?></a>
                                    <a href="<?= site_url('settings/sitemap'); ?>" target="_blank" class="input-group-addon btn btn-warning" id="basic-addon2"><?=lang('update');?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php echo form_close(); ?>
            <?php if ( ! DEMO ) { ?>
            <div class="col-md-12">
                <div class="well well-sm">
                    <p><?= lang('call_back_heading'); ?></p>
                    <p class="text-info">
                        <code><?= site_url('social_auth/endpoint?hauth_done=XXXXXX'); ?></code>
                    </p>
                    <p><?= lang('replace_xxxxxx_with_provider'); ?></p>
                    <p><strong><?= lang('enable_config_file'); ?></strong></p>
                    <p><code>app/config/hybridauthlib.php</code></p>
                    <p><?= lang('documentation_at'); ?>: <a href="http://hybridauth.github.io/hybridauth/userguide.html" target="_blank">http://hybridauth.github.io/hybridauth/userguide.html</a></p>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script>
    document.querySelector("#uploadLogo").onchange = function () {
        document.querySelector(".upload-logo").value = this.value;
    };
    document.querySelector("#uploadFav").onchange = function () {
        document.querySelector(".upload-fav").value = this.value;
    };
</script>
