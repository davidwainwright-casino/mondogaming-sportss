<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
    <h3><i class="fa fa-certificate"></i> <?= $page_title; ?></h3>
    <p><?= lang('list_results'); ?></p>
    <div class="content-panel">
        <?php
        if ($badges) {
            foreach ($badges as $badge) {
                ?>
                <div class="list-box">
                        <div class="btn-group-vertical pull-right" role="group">
                            <a href="<?= site_url('users/edit_badge/' . $badge->id); ?>" class="btn btn-warning" data-toggle="ajax-modal"><i class="fa fa-edit"></i></a>
                            <a href="<?= site_url('users/delete_badge/' . $badge->id); ?>" class="btn btn-danger po-del"><i class="fa fa-trash-o"></i></a>
                        </div>
                        <h3>
                            <?= $badge->title; ?>
                            <i class="<?= $badge->class; ?>"></i>
                            <?php
                            if ($badge->image) {
                                echo '<img src="' . base_url('uploads/' . $badge->image) . '" alt="' . $badge->title . '" style="max-width:32px;max-height:32px;" />';
                            } ?>
                        </h3>
                        <p>
                            <span class="label label-info"><?= lang('class'); ?></span> <?= $badge->class; ?>
                        </p>
                        <div class="clearfix"></div>
                </div>
                <?php
            }
        } else {
            echo lang('no_badge_to_display');
        }
        ?>
    </div>
</div>
</div>
