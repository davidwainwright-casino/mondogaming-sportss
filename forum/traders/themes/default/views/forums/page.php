<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
        <div class="content-box-top">
            <h2><?= $page->title; ?></h2>
            <div class="meta-box">
                    <span class="label label-info"><i class="fa fa-clock-o"></i> <?= lang('last_updated'); ?>: <?= $this->tec->hrld($page->updated_at); ?></span>
                <?php if ($Admin) { ?>
                <a href="<?= site_url('pages/edit/'.$page->id); ?>" data-toggle="ajax-modal">
                    <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                </a>
                <a href="<?= site_url('pages/delete/'.$page->id); ?>" class="po-del">
                    <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                </a>
                <?php } ?>
            </div>
        </div>
        <div class="content-box-md">
            <?= $this->tec->decode_html($page->body); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
