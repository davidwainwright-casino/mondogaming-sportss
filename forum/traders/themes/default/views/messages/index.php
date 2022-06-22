<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <div class="page-box">
        <h3>
            <i class="fa fa-envelope"></i> <?= $page_title; ?>
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= lang('actions'); ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li style="padding-left:0;"><a href="#" id="mread"><?= lang('mark_as_read'); ?></a></li>
                    <li style="padding-left:0;"><a href="#" id="munread"><?= lang('mark_as_ubread'); ?></a></li>
                    <li style="padding-left:0;"><a href="#" id="mimportant"><?= lang('mark_as_important'); ?></a></li>
                    <li style="padding-left:0;"><a href="#" id="munimportant"><?= lang('remove_important'); ?></a></li>
                    <li role="separator" class="divider"></li>
                    <li style="padding-left:0;"><a href="#" id="mdelete"><?= lang('delete_messages'); ?></a></li>
                </ul>
            </div>
        </h3>
        <p><?= lang('list_results'); ?></p>
        <div class="content-panel">
            <?php
            if ($conversations) {
                echo form_open('messages/actions', 'id="msg-actions"');
            ?>
            <div class="table-responsive">
                <table class="table vamd" style="margin-bottom:5px;">
                    <thead>
                        <tr class="active">
                            <td>
                                <label class="checkbox">
                                    <input type="checkbox" class="select_all"/>
                                    <span class="icon"><i class="fa fa-check"></i></span>
                                </label>
                            </td>
                            <td class="col-xs-2"><?= lang('from'); ?></td>
                            <td class="col-xs-2"><?= lang('to'); ?></td>
                            <td class="col-xs-4"><?= lang('subject'); ?></td>
                            <td class="col-xs-2"><?= lang('created_at'); ?></td>
                            <td class="col-xs-2"><?= lang('last_reply'); ?></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tdobay>
                        <?php 
                        foreach ($conversations as $conversation) { 
                            $page = ceil(($conversation->total_messages-1) / $Settings->records_per_page);
                        ?>
                            <tr class="msg_link<?= (($conversation->receiver_id == $user_id && $conversation->receiver_read != 1) || ($conversation->sender_id == $user_id && $conversation->sender_read != 1)) ? ' bold_msg' : ''; ?>" data-id="<?= $conversation->id; ?>" data-page="<?= $page; ?>">
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" name="msg_id[]" class="select_msg" value="<?= $conversation->id; ?>" />
                                        <span class="icon"><i class="fa fa-check"></i></span>
                                    </label>
                                </td>
                                <td><?= $conversation->from_user; ?></td>
                                <td><?= $conversation->to_user; ?></td>
                                <td><?= stripslashes($conversation->subject); ?></td>
                                <td><?= $this->tec->hrld($conversation->created_at); ?></td>
                                <td><?= $this->tec->timespan($conversation->last_reply_time).' '.lang('ago'); ?></td>
                                <td><?= $conversation->important ? '<i class="fa fa-star tip" title="'.lang('important').'"></i>' : ''; ?></td>
                            </tr>
                        <?php } ?>
                    </tdobay>
                    <tfoot>
                        <tr class="active">
                            <td>
                                <label class="checkbox">
                                    <input type="checkbox" class="select_all"/>
                                    <span class="icon"><i class="fa fa-check"></i></span>
                                </label>
                            </td>
                            <td class="col-xs-2"><?= lang('from'); ?></td>
                            <td class="col-xs-2"><?= lang('to'); ?></td>
                            <td class="col-xs-4"><?= lang('subject'); ?></td>
                            <td class="col-xs-2"><?= lang('created_at'); ?></td>
                            <td class="col-xs-2"><?= lang('last_reply'); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div style="display:none;"><div id="msg-action"></div></div>
            <?php
            echo form_close();
                } else {
                    echo '<h4>'.lang('no_conversation_to_display').'</h4>';
                }
            ?>
            <div class="clearfix"></div>
            <?php if($links) { ?>
                <nav class="pagination-box">
                    <ul class="page-info pull-left">
                        <li class="previous disabled"><?= str_replace(array('{from}', '{till}', '{total}'), array($records['from'], $records['till'], $records['total']), lang('page_info'));?></li>
                    </ul>
                    <div class="nav-input-page pull-right ml">
                        <div class="input-group">
                            <input type="text" class="form-control" id="page-num" placeholder="<?= lang('page'); ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="go-to-page"><i class="fa fa-chevron-<?=$Settings->rtl ? 'left' : 'right';?>"></i></button>
                            </span>
                        </div>
                    </div>
                    <span class="pull-right"><?= $links; ?></span>
                </nav>
                <div class="clearfix"></div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
var page_url = '<?= site_url('messages'); ?>';
</script>
