<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
        <h3>
            <i class="fa fa-folder"></i> <?= $page_title; ?>
            <a href="<?= site_url('settings/create_api_key'); ?>" class="btn btn-primary pull-right" data-toggle="ajax-modal"><?= lang('create_api_key'); ?></a>
        </h3>
        <p><?= lang('list_results'); ?></p>
        <div class="content-panel">
            <?php
            if ($api_keys) {
                foreach ($api_keys as $api_key) {
                    ?>
                    <div class="list-box">
                        <strong><?= $api_key->reference; ?></strong>
                        <div class="input-group">
                            <input type="text" class="form-control copy_api_key" value="<?= $api_key->key; ?>" readonly>
                            <span class="input-group-btn">
                                <a href="<?= site_url('settings/delete_api_key/'.$api_key->id); ?>" class="btn btn-danger po-del" title="<?= lang('delete_api_key'); ?>"><i class="fa fa-trash-o"></i></a>
                            </span>
                        </div>
                        <span class="help-block help_copy_api_key"></span>
                        <?= ($api_key->ignore_limits > 0) ? lang('ignore_limits').': <span class="label label-info">'.$api_key->ignore_limits.'</span>' : ''; ?>
                        <?= ($api_key->ip_addresses) ? lang('ip_addresses').': <span class="label label-info">'.$api_key->ip_addresses.'</span>' : ''; ?>
                        <div class="clearfix"></div>
                        <?php if ($api_key->level == 1) { ?>
                            <strong><?= lang('threads'); ?></strong><br>
                            <strong><?= lang('url'); ?>: <code><?= site_url('api/v1/threads?SF-API-KEY='.$api_key->key.'&mine=0&count=5&categoty=general'); ?></code></strong>
                            <p>
                                <strong>Params:</strong><br>
                                <code>mine = 0 or 1</code> for user threads (default is 0)<br>
                                <code>count = 1 to 10</code> for total threads (default is 5)<br>
                                <code>category = category_slug</code> for threads from category
                            </p>
                            <strong><?= lang('categories'); ?></strong><br>
                            <strong><?= lang('url'); ?>: <code><?= site_url('api/v1/categories?SF-API-KEY='.$api_key->key.'&count=5'); ?></code></strong>
                            <p>
                                <strong>Params:</strong><br>
                                <code>count = 1 to 10</code> for total categoies (default is 0 = all categories)
                            </p>
                        <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                    <?php
                }
            } else {
                echo lang('no_api_key_to_display');
            }
            ?>
            <p class="mt" style="font-weight: bold;">Download Wordpress Widgets from <a href="https://wordpress.org/plugins/simple-forum-widgets/" target="_blank">https://wordpress.org/plugins/simple-forum-widgets/</a></p>
        </div>
    </div>
</div>
