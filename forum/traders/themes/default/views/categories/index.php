<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
    <h3><i class="fa fa-folder"></i> <?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
    <div class="content-panel">
        <?php
        if ($categories) {
            foreach ($categories as $category) {
                ?>
                <div class="list-box">
                        <div class="btn-group-vertical pull-right" role="group">
                            <a href="<?= site_url('settings/fields/'.$category->id); ?>" class="btn btn-primary tip" title="<?= lang('list_fields'); ?>">
                                <i class="fa fa-list"></i>
                            </a>
                            <a href="<?= site_url('categories/edit/'.$category->id); ?>" class="btn btn-warning tip" data-toggle="ajax-modal" title="<?= lang('edit_category'); ?>">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="<?= site_url('categories/delete/'.$category->id); ?>" class="btn btn-danger tip po-del" title="<?= lang('delete_category'); ?>">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                        <h3><?= $parent ? $category->name : '<a href="'.site_url('categories/'.$category->id).'">'. $category->name.'</a>'; ?></h3>
                        <p class="slug">
                            <span class="label label-info"><?= lang('slug'); ?></span> <?= $category->slug; ?>
                            <span class="label label-info" style="margin-left: 15px;"><?= lang('order'); ?></span> <?= $category->order_no; ?>
                        </p>
                        <div class="description">
                            <span class="label label-info"><?= lang('description'); ?></span> <?= $category->description; ?>
                        </div>
                        <div class="clearfix"></div>
                </div>
                <?php
            }
        } else {
            echo lang('no_category_to_display');
        }
        ?>
    </div>
</div>
</div>
