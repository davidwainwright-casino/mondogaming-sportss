<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="col-sm-8">
    <div class="page-box">
        <?php
        $de = array('0' => $this->lang->line("disable"), '1' => $this->lang->line("enable"));
        ?>

        <h3><i class="fa fa-puzzle-piece"></i> <?= lang('ad_settings'); ?></h3>
        <p><?= lang('update_info'); ?></p>

        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'ad_settings');
        echo form_open_multipart("settings/ads", $attrib); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("ad_thread", "ad_thread"); ?>
                    <?= form_dropdown('ad_thread', $de, $Settings->ad_thread, 'class="form-control select2 ad_select" id="ad_thread"  style="width:100%;" required="required"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("ad_thread_code", "ad_thread_code"); ?>
                    <?php echo form_textarea('ad_thread_code', $Settings->ad_thread_code, 'class="form-control" id="ad_thread_code"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("ad_sidebar", "ad_sidebar"); ?>
                    <?= form_dropdown('ad_sidebar', $de, $Settings->ad_sidebar, 'class="form-control select2 ad_select" id="ad_sidebar"  style="width:100%;" required="required"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("ad_sidebar_code", "ad_sidebar_code"); ?>
                    <?php echo form_textarea('ad_sidebar_code', $Settings->ad_sidebar_code, 'class="form-control" id="ad_sidebar_code"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("ad_sidebar2", "ad_sidebar2"); ?>
                    <?= form_dropdown('ad_sidebar2', $de, $Settings->ad_sidebar2, 'class="form-control select2 ad_select" id="ad_sidebar2"  style="width:100%;" required="required"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("ad_sidebar2_code", "ad_sidebar2_code"); ?>
                    <?php echo form_textarea('ad_sidebar2_code', $Settings->ad_sidebar2_code, 'class="form-control" id="ad_sidebar2_code"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("ad_footer", "ad_footer"); ?>
                    <?= form_dropdown('ad_footer', $de, $Settings->ad_footer, 'class="form-control select2 ad_select" id="ad_footer"  style="width:100%;" required="required"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("ad_footer_code", "ad_footer_code"); ?>
                    <?php echo form_textarea('ad_footer_code', $Settings->ad_footer_code, 'class="form-control" id="ad_footer_code"'); ?>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="mb" style="border-bottom: 1px dotted #ccc;"></div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("footer_code", "footer_code"); ?>
                    <?php echo form_textarea('footer_code', $Settings->footer_code, 'class="form-control" id="footer_code"'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("alert_message", "alert"); ?>
                    <?php echo form_textarea('alert', $Settings->alert, 'class="form-control" id="alert"'); ?>
                </div>
            </div>

            <div style="clear: both; height: 10px;"></div>
            <div class="col-xs-12">
                <div class="form-group">
                    <?php echo form_submit('update', $this->lang->line("update"), 'class="btn btn-primary"'); ?>
                </div>
            </div>
            <?php echo form_close(); ?>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
