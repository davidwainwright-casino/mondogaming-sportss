<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
    <h3><i class="fa fa-file-text"></i> <?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
        <div class="content-panel">
            <?php foreach ($pages as $page) { ?>
                <div class="list-box">
                        <div class="btn-group-vertical pull-right" role="group">
                            <a href="<?= site_url('pages/'.$page->slug); ?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
                            <a href="<?= site_url('pages/edit/'.$page->id); ?>" class="btn btn-warning" data-toggle="ajax-modal"><i class="fa fa-edit"></i></a>
                            <a href="<?= site_url('pages/delete/'.$page->id); ?>" class="btn btn-danger po-del"><i class="fa fa-trash-o"></i></a>
                        </div>
                        <h3>
                            <?= $page->title; ?>
                        </h3>
                        <p class="slug">
                            <span class="label label-info"><?= lang('slug'); ?></span> <?= $page->slug; ?>
                        </p>
                        <div class="description">
                            <span class="label label-info"><?= lang('description'); ?></span> <?= $page->description; ?>
                        </div>
                        <div class="clearfix"></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
