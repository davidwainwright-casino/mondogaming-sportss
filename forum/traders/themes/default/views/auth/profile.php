<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <?php if (($loggedIn && $user->username == $this->session->userdata('username')) || $Admin) { ?>
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#details" role="tab" data-toggle="tab"><?= lang('profile'); ?></a></li>
        <li><a href="#edit" role="tab" data-toggle="tab"><?= lang('edit'); ?></a></li>
        <?php if ($user->id == $this->session->userdata('user_id')) { ?>
        <li style="display:none"><a href="#change_password" role="tab" data-toggle="tab"><?= lang('change_password'); ?></a></li>
        <?php } ?>
        <?php if ($Settings->apis > 1 && !$Admin) { ?>
        <li><a href="#api_key" role="tab" data-toggle="tab"><?= lang('api_key'); ?></a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="details">
        <?php } ?>
            <div class="page-box">
                <h2 class="bold"><?= $user->first_name . ' ' . $user->last_name; ?></h2>
                <?= '<span class="">' . lang('member_since') . ': ' . $this->tec->hrsd(date('Y-m-d H:i:s', $user->created_on)) . '</span><br>
                <span class="">' . lang('last_visit') . ': ' . $this->tec->hrld(date('Y-m-d H:i:s', $user->last_login)) . '</span>'; ?><br>
                <span class="label label-info"><?= lang($group->name); ?></span>
                <?php if ($Admin) { ?>
                <a href="<?= site_url('users/assign_badge/' . $user->id); ?>" data-toggle="ajax-modal">
                    <span class="label label-info"><?= lang('assign_badge'); ?></span>
                </a>
                <?php if ($user->active == -2) { ?>
                <a href="<?= site_url('resend_activation/' . $user->id); ?>">
                    <span class="label label-info"><?= lang('resend_activation'); ?></span>
                </a>
                <?php } ?>
                <a href="<?= site_url('delete_avatar/' . $user->id); ?>" class="po-del">
                    <span class="label label-warning"><?= lang('delete_avatar'); ?></span>
                </a>
                <a href="<?= site_url('reviews/ban?user=' . $user->id); ?>" data-toggle="ajax-modal">
                    <span class="label label-danger"><?= lang('ban_user'); ?></span>
                </a>
                <?php } ?>
                <div class="row mt">
                    <div class="col-md-6">
                        <img src="<?= $user->avatar ? base_url('uploads/avatars/' . $user->avatar) : $assets . 'img/' . $user->gender . '.png';?>" alt="" class="img-thumbnail img-responsive mb">
                        <?php if ($this->session->userdata('user_id') && $user->id == $this->session->userdata('user_id')) { ?>
                        <?php echo form_open_multipart('users/update_avatar/' . $user->id, 'id="avatarForm"'); ?>
                        <div class="form-group">
                            <?= lang('change_avatar', 'change_avatar'); ?>
                            <span style="display:none;">
                                <input type="file" name="avatar" required="required" accept="image/*" class="file" data-fv-notempty-message="<?=lang('avatar_required');?>"/>
                            </span>
                            <?php echo form_hidden($csrf); ?>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="browse" type="button"><?=lang('select');?></button>
                                </span>
                                <input type="text" class="form-control col-xs-6" id="subfile" readonly="true">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary"><?= lang('submit'); ?></button>
                                </span>
                            </div>
                            <small class="help-block"><?= lang('avatar_tip'); ?></small>
                        </div>
                        <?php echo form_close(); ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">

                        <?php
                        if ($badges) {
                            echo '<ul class="list-group">';
                            foreach ($badges as $badge) {
                                echo "<li class='list-group-item' style='display:flex;align-items:center;flex-direction:row-reverse;'>" . ($Admin ? "<i class='fa fa-times-circle ml pointer tip detach_bagde' data-id='" . $badge->id . "' title='" . lang('detach') . "'></i>" : '') . "<i class='{$badge->class} tip' title='{$badge->title}'></i>" . (!empty($badge->image) ? '<img src="' . base_url('uploads/' . $badge->image) . '" alt="' . $badge->title . '" style="max-width:24px;max-height:24px;float:right;" />' : '') . " <span style='flex:1;'>{$badge->title}</span></li>";
                            }
                            echo '</ul>';
                        }

                        echo '<h4><a href="' . site_url('user_topics/' . $user->id) . '">' . lang('threads') . ' <span>- ' . $user_threads . '</span></a>  &nbsp;&nbsp;&nbsp;  <a>' . lang('replies') . ' <span>- ' . ($user_replies - $user_threads) . '</span></a></h4>';
                        ?>
                    </div>
                </div>
            <?php if (($loggedIn && $user->username == $this->session->userdata('username')) || $Admin) { ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="edit">
            <?= form_open('auth/edit_user/' . $user->id, 'id="userForm" class="form"'); ?>
            <div class="page-box">
                <p>Edits here are limited to forum only. No change will be affected in main account.</p>
                <div class="form-group">
                    <?= lang('first_name', 'first_name'); ?>
                    <div class="controls">
                        <?= form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required" data-fv-notempty-message="' . lang('first_name_required') . '"'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= lang('last_name', 'last_name'); ?>
                    <div class="controls">
                        <?= form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required" data-fv-notempty-message="' . lang('last_name_required') . '"'); ?>
                    </div>
                </div>
                <div class="form-group" required="required" style="display:none">
                    <?= lang('username', 'username'); ?>
                    <input type="text" name="username" class="form-control" id="username" value="<?= $user->username ?>" required="required" data-fv-notempty-message="<?=lang('username_required');?>" hidden/>
                </div>
                <div class="form-group"
                    <?= lang('email', 'email'); ?>
                    <input type="email" name="email" class="form-control" id="email" value="<?= $user->email ?>" required="required" data-fv-notempty-message="<?=lang('email_required');?>" />
                </div>
                <div class="form-group" style="display:none">
                    <?= lang('phone', 'phone'); ?>
                    <div class="controls">
                        <input type="tel" name="phone" class="form-control" id="phone" value="<?= $user->phone ?>"/>
                    </div>
                </div>
                
                <div class="form-group" style="display:none">
                    <?= lang('gender', 'gender'); ?>
                    <?php
                    $ge = ['male' => lang('male'), 'female' => lang('female')];
                    echo form_dropdown('gender', $ge, ($_POST['gender'] ?? $user->gender), 'class="tip form-control select2" id="gender"  style="width:100%" data-fv-notempty-message="' . lang('gender_required') . '"');
                    ?>
                </div>
                <div class="form-group">
                    <?= lang('subscription', 'subscription'); ?>
                    <?php
                    $ge = [0 => lang('daily_digest'), 1 => lang('weekly_digest'), 2 => lang('monthly_digest'), 3 => lang('individual_emails'), 4 => lang('no_emails')];
                    echo form_dropdown('subscription', $ge, ($_POST['subscription'] ?? $user->subscription), 'class="tip form-control select2" id="subscription" style="width:100%"');
                    ?>
                </div>
                <div class="form-group">
                    <?= lang('accept_messages', 'accept_messages'); ?>
                    <?php
                    $yn = [0 => lang('no'), 1 => lang('yes')];
                    echo form_dropdown('accept_messages', $yn, ($_POST['accept_messages'] ?? $user->accept_messages), 'class="tip form-control select2" id="accept_messages" style="width:100%"');
                    ?>
                </div>
                <?php if ($this->Settings->signature) { ?>
                <div class="form-group">
                    <?= lang('signature', 'signature'); ?>
                    <?= form_textarea('signature', ($_POST['signature'] ?? $this->tec->decode_html($user->signature)), 'class="form-control tip" id="editor" minlength="2"'); ?>
                </div>
                <?php } ?>

            <?php if ($Admin && $user->id != $this->session->userdata('user_id')) { ?>

                <div class="clearfix"></div>
                    <div class="panel panel-warning">
                        <div class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
                        <div class="panel-body" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang('password', 'password'); ?>
                                        <?= form_password('password', '', 'class="form-control" data-fv-stringlength="true" data-fv-stringlength-min="' . $min_pw_len . '" data-fv-stringlength-max="25" data-fv-stringlength-message="' . sprintf(lang('password_len_requirement'), $min_pw_len) . '"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang('confirm_password', 'password_confirm'); ?>
                                        <?= form_password('password_confirm', '', 'class="form-control" data-fv-identical="true" data-fv-identical-field="password" data-fv-identical-message="' . lang('confirm_password_is_different') . '"'); ?>
                                    </div>
                                </div>
                        </div>
                    </div>

                <div class="clearfix"></div>
                    <div class="panel panel-warning">
                        <div class="panel-heading"><?= lang('user_options') ?></div>
                        <div class="panel-body" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang('status', 'active'); ?>
                                        <?php
                                        $opt = [1 => lang('active'), -2 => lang('inactive'), -1 => lang('pending'), -3 => lang('banned')];
                                        echo form_dropdown('status', $opt, ($_POST['status'] ?? $user->active), 'id="active" class="form-control tip select2" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang('group', 'group'); ?>
                                        <?php
                                        $gp[''] = '';
                                        foreach ($groups as $group) {
                                            $gp[$group->id] = $group->name;
                                        }
                                        echo form_dropdown('group', $gp, ($_POST['group'] ?? $user->group_id), 'id="group" data-placeholder="' . lang('select') . ' ' . lang('group') . '" class="form-control tip select2" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                        </div>
                </div>
                <?php } ?>
                <?= form_hidden('id', $user->id); ?>
                <?= form_hidden($csrf); ?>
                <?= form_submit('update', lang('update'), 'class="btn btn-primary"'); ?>
            </div>
            <?= form_close(); ?>
        </div>
        <?php if ($user->id == $this->session->userdata('user_id')) { ?>
            <div role="tabpanel" class="tab-pane fade" id="change_password">
                <div class="page-box">
                    <p>Please change your password from Main Panel</p>
                    <?= form_open('change_password', 'id="pwForm" class="form"'); ?>
                    <div class="row">
                        <div class="col-md-12" style="display:none">
                            <div class="form-group">
                                <?= lang('old_password', 'curr_password'); ?>
                                <?= form_password('old_password', '', 'class="form-control" id="curr_password" required="required" data-fv-notempty-message="' . lang('password_required') . '"'); ?>
                            </div>
                            <div class="form-group">
                                <label for="new_password"><?= sprintf(lang('new_password'), $min_pw_len); ?></label>
                                <?= form_password('new_password', '', 'class="form-control" id="new_password" required="required" data-fv-notempty-message="' . lang('password_required') . '" data-fv-stringlength="true" data-fv-stringlength-min="' . $min_pw_len . '" data-fv-stringlength-max="25" data-fv-stringlength-message="' . sprintf(lang('password_len_requirement'), $min_pw_len) . '"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('confirm_password', 'new_password_confirm'); ?>
                                <?= form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" data-fv-notempty-message="' . lang('confirm_password_required') . '" data-fv-identical="true" data-fv-identical-field="new_password" data-fv-identical-message="' . lang('confirm_password_is_different') . '"'); ?>

                            </div>
                            <?= form_submit('change_password', lang('change_password'), 'class="btn btn-primary"'); ?>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($Settings->apis > 1 && !$Admin) { ?>
            <div role="tabpanel" class="tab-pane fade" id="api_key">
                <div class="page-box">
                    <h2><?= lang('api_key'); ?></h2>
                    <div class="list-box">
                        <?php if ($api_key) { ?>
                            <h3><?= lang('threads'); ?></h3>
                            <strong><?= lang('url'); ?>: <code><?= site_url('api/v1/threads?SF-API-KEY=' . $api_key->key . '&mine=0&count=5&categoty=general'); ?></code></strong>
                            <p>
                                <strong>Params:</strong><br>
                                <code>mine = 0 or 1</code> for user threads (default is 0)<br>
                                <code>count = 1 to 10</code> for total threads (default is 5)<br>
                                <code>category = category_slug</code> for threads from category
                            </p>
                            <h3><?= lang('categories'); ?></h3>
                            <strong><?= lang('url'); ?>: <code><?= site_url('api/v1/categories?SF-API-KEY=' . $api_key->key . '&count=5'); ?></code></strong>
                            <p>
                                <strong>Params:</strong><br>
                                <code>count = 1 to 10</code> for total categoies (default is 0 = all categories)
                            </p>
                            <h3><?= lang('api_key'); ?>:</h3>
                            <div class="input-group">
                                <input type="text" class="form-control copy_api_key" value="<?= $api_key->key; ?>" readonly>
                                <span class="input-group-btn">
                                    <a href="<?= site_url('users/generate_api_key'); ?>" class="btn btn-danger po-del" data-button-title="<?= lang('generate'); ?>" title="<?= lang('regenerate_api_key'); ?>"><i class="fa fa-refresh"></i></a>
                                </span>
                            </div>
                            <span class="help-block help_copy_api_key"></span>
                        <?php } else { ?>
                            <a href="<?= site_url('users/generate_api_key'); ?>" class="btn btn-primary"><i class="fa fa-key"></i> <?= lang('generate_api_key'); ?></a>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <p class="mt ml mb mr" style="font-weight: bold;">Download Wordpress Widgets from <a href="https://wordpress.org/plugins/simple-forum-widgets/" target="_blank">https://wordpress.org/plugins/simple-forum-widgets/</a></p>
            </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>
