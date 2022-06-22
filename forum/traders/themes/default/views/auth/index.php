<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
        <div class="content-panel">
            <h3><i class="fa fa-users"></i> <?= $page_title; ?></h3>
            <p><?= lang('list_results'); ?></p>
            <div class="form-group">
                <?php $sts = array('all' => lang('all'), 'pending' => lang('pending'), 'inactive' => lang('inactive'), 'active' => lang('active'), 'banned' => lang('banned')); ?>
                <div class="form-group">
                    <?= form_input('uquery', ($this->input->get('uquery') ? $this->input->get('uquery') : ''), 'class="form-control" id="uquery" placeholder="'.lang('search').'"'); ?>
                </div>
                <?= form_dropdown('status', $sts, ($this->input->get('status') ? $this->input->get('status') : 'all'), 'class="form-control select2" id="status" style="width:100%"'); ?>
            </div>
            <?php if ($users) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped cf">
                    <thead class="cf">
                    <tr>
                        <th><?php echo lang('first_name'); ?></th>
                        <th><?php echo lang('last_name'); ?></th>
                        <th><?php echo lang('email'); ?></th>
                        <th><?php echo lang('group'); ?></th>
                        <th style="width:100px;"><?php echo lang('status'); ?></th>
                        <th style="width:100px;"><?php echo lang('actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($users as $user) {
                        echo '<tr>';
                        echo '<td>' . $user->first_name . '</td>';
                        echo '<td>' . $user->last_name . '</td>';
                        echo '<td>' . $user->email . '</td>';
                        echo '<td>' . $user->group . '</td>';
                        echo '<td class="text-center" style="padding:6px;">';
                        if ($user->active == 1) {
                            echo '<span class="label label-success">' . lang('active') . '</span>';
                        } elseif ($user->active == -2) {
                            echo '<span class="label label-warning">' . lang('inactive') . '</span>';
                        } elseif ($user->active == -1 ) {
                            echo '<span class="label label-info">' . lang('pending') . '</span>';
                        } elseif ($user->active == -3 ) {
                            echo '<span class="label label-danger">' . lang('banned') . '</span>';
                        }
                        echo '</td>';
                        echo '<td class="text-center" style="padding:6px;"><div class="btn-group btn-group-justified" role="group"><div class="btn-group btn-group-xs" role="group"><a class="tip btn btn-warning btn-xs" title="' . lang("profile") . '" href="' . site_url('users/profile/' . $user->username) . '"><i class="fa fa-user"></i></a></div><div class="btn-group btn-group-xs" role="group"><a class="tip btn btn-danger btn-xs" title="' . lang("ban_user") . '" data-toggle="ajax-modal" href="' . site_url('reviews/ban?user=' . $user->id) . '"><i class="fa fa-ban"></i></a></div><div class="btn-group btn-group-xs" role="group"><a href="'.site_url('users/delete/'.$user->id).'" class="btn btn-danger po-del"><i class="fa fa-trash-o"></i></a>
                        </div></div></td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php if($links) { ?>
                <nav class="pagination-box">

                        <ul class="page-info pull-left">
                            <li class="previous disabled"><?= str_replace(array('{from}', '{till}', '{total}'), array($records['from'], $records['till'], $records['total']), lang('page_info'));?></li>
                        </ul>

                    <?= $links; ?>
                    <div class="nav-input-page pull-right ml">
                        <div class="input-group">
                            <input type="text" class="form-control" id="page-num" placeholder="<?= lang('page'); ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="go-to-page"><i class="fa fa-chevron-right"></i></button>
                            </span>
                        </div>
                    </div>
                </nav>
            <?php
        }
        } else {
            echo '<h2>'.lang('no_user_to_display').'</h2>';
        }
        ?>
        </div>
    </div>
</div>
