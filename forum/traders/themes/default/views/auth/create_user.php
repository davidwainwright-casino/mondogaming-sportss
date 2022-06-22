<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
        <div class="content-panel">
            <h3><i class="fa fa-user-plus"></i> <?= lang('add_user'); ?></h3>
            <p><?= lang('enter_info'); ?></p>
            <?php
            echo form_open("users/add", 'id="userForm" class="form"');
            ?>
            <div class="row">
                <div class="col-md-12">

                        <div class="form-group">
                            <?php echo lang('first_name', 'first_name'); ?>
                            <div class="controls">
                                <?php echo form_input('first_name', '', 'class="form-control" id="first_name" pattern=".{3,10}" required="" data-fv-notempty-message="'.lang('first_name_required').'"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo lang('last_name', 'last_name'); ?>
                            <div class="controls">
                                <?php echo form_input('last_name', '', 'class="form-control" id="last_name" required="" data-fv-notempty-message="'.lang('last_name_required').'"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= lang("group", "group"); ?>
                            <?php
                            $gp[""] = "";
                            foreach ($groups as $group) {
                                if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                    $gp[$group['id']] = $group['name'];
                                }
                            }
                            echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : ''), 'id="group" data-placeholder="' . lang("select") . ' ' . lang("group") . '" class="form-control input-tip select2" style="width:100%;" required="" data-fv-notempty-message="'.lang('group_required').'"');
                            ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('phone', 'phone'); ?>
                            <div class="controls">
                                <?php echo form_input('phone', '', 'class="form-control" id="phone"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= lang('dob', 'dob'); ?>
                            <?= form_input('dob', set_value('dob'), 'class="form-control tip" id="dob" placeholder="yyyy-mm-dd"'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('email', 'email'); ?>
                            <div class="controls">
                                <input type="email" id="email" name="email" class="form-control" required="" data-fv-notempty-message="<?=lang('email_required');?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo lang('username', 'username'); ?>
                            <div class="controls">
                                <input type="text" id="username" name="username" class="form-control" pattern=".{4,20}" required="" data-fv-notempty-message="<?=lang('username_required');?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo lang('password', 'password'); ?>
                            <div class="controls">
                                <?php echo form_password('password', '', 'class="form-control tip" id="password" required="" data-fv-notempty-message="'.lang('password_required').'" data-fv-stringlength="true" data-fv-stringlength-min="8" data-fv-stringlength-max="25" data-fv-stringlength-message="'.lang('password_len_requirement').'"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo lang('confirm_password', 'confirm_password'); ?>
                            <div class="controls">
                                <?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password" required="" data-fv-notempty-message="'.lang('password_required').'" data-fv-identical="true" data-fv-identical-field="password" data-fv-identical-message="'.lang('confirm_password_is_different').'"'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= lang('gender', 'gender'); ?>
                            <?php
                            $gopt = array('male' => lang('male'), 'female' => lang('female'));
                            echo form_dropdown('gender', $gopt, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" class="form-control input-tip select2" style="width:100%;" required="" data-fv-notempty-message="'.lang('gender_required').'"');
                            ?>
                        </div>

                        <div class="form-group">
                            <?= lang('status', 'new_status'); ?>
                            <?php
                            $opt = array(1 => lang('active'), -2 => lang('inactive'));
                            echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="new_status" data-placeholder="' . lang("select") . ' ' . lang("status") . '" class="form-control input-tip select2" style="width:100%;" required="" data-fv-notempty-message="'.lang('status_required').'"');
                            ?>
                        </div>

                    <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="checkbox" for="notify">
                                <input type="checkbox" name="notify" value="1" id="notify" checked="checked"/>
                                <span class="icon"><i class="fa fa-check"></i></span>
                                <?= lang('notify_user_by_email') ?>
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <p><?php echo form_submit('add_user', lang('add_user'), 'class="btn btn-primary"'); ?></p>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script src="<?= $assets; ?>components/bootstrap-datetimepicker/js/moment.min.js"></script>
<script src="<?= $assets; ?>components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#dob').datetimepicker({format:'YYYY-MM-DD',viewMode: 'years'});
    });
</script>
