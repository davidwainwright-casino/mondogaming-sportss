<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="col-sm-8">
    <div class="page-box">
        <h3><i class="fa fa-puzzle-piece"></i> <?= $page_title.($category ? ' ('.$category->name.')' : ''); ?> <a href="<?= site_url('settings/add_field/'.($category ? $category->id : '')); ?>" class="btn btn-primary pull-right" data-toggle="ajax-modal"><i class="fa fa-plus"></i> <?= lang('add_field'); ?></a> <?= $category ? '<a href="'.site_url('settings/fields').'" class="pull-right btn btn-primary mr">'.lang('list_all_fields').'</a>' : ''; ?></h3>
        <p><?= lang('category_fields'); ?></p>
        <div class="content-panel">
            <?php
            if (isset($fields) && !empty($fields)) {
                foreach ($fields as $field):
                    ?>
                <div class="list-box">
                    <div class="btn-group-vertical pull-right" role="group">
                        <a href="<?= site_url('settings/edit_field/'.$field->id); ?>" class="btn btn-warning tip" data-toggle="ajax-modal" title="<?= lang('edit_field'); ?>">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="<?= site_url('settings/delete_field/'.$field->id); ?>" class="btn btn-danger tip po-del" title="<?= lang('delete_field'); ?>">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </div>
                    <h4><?= $field->name; ?></h4>
                    <p class="slug">
                        <span class="label label-info"><?= lang('category'); ?>: <?= $field->category ? $field->category : lang('all'); ?></span>
                        <span class="label label-info"><?= lang('type'); ?>: <?= $field->type; ?></span>
                        <span class="label label-info"><?= lang('required'); ?>: <?= $field->required ? lang('yes') : lang('no'); ?></span>
                        <span class="label label-info"><?= lang('public'); ?>: <?= $field->public ? lang('yes') : lang('no'); ?></span>
                    </p>
                    <div class="description">
                        <span class="label label-info"><?= lang('options'); ?></span> <?= $field->options; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <?php
                endforeach;
            } else {
                echo lang('no_field_avaialble');
            }
            ?>
        </div>
    </div>
</div>
