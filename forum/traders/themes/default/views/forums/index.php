<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-8">
    <?php
    if (isset($child_categories) && ! empty($child_categories)) {
        foreach ($child_categories as $cc) { ?>
            <div class="add-box">
                <h2>
                    <a href="<?= site_url($cc->slug); ?>">
                        <?= $cc->name; ?>
                        <i class="pull-right fa fa-folder"></i>
                    </a>
                </h2>
            </div>
            <?php
        }
    }
    if ($topics) {
        $sopt = array(0 => lang('newest'), 1 => lang('oldest'), 2 => lang('higher_votes'), 3 => lang('lower_votes'), 4 => lang('newest_reply'), 5 => lang('oldest_reply'), 6 => lang('most_views'), 7 => lang('least_views'));
        $r = 0;
    foreach ($topics as $topic) {
        if ($Settings->ad_thread && $r == 1) {
            echo '<div class="ad ad-thread text-center">'.$Settings->ad_thread_code.'</div>';
        }
        ?>
        <div class="content-box<?= $topic->sticky || (!empty($category) && $topic->sticky_category) ? ' sticky' : ''; ?>">
            <div class="content-box-left">
                <a href="#" class="user user_<?= $topic->user_id; ?>" data-id="<?= $topic->user_id; ?>">
                    <div class="circle">
                        <div class="circle-avatar">
                            <?= $topic->avatar ? '<span class="tip" title="'.$topic->username.'"><img src="'.base_url('uploads/avatars/thumbs/'.$topic->avatar).'" alt="" class="img-circle img-responsive"></span>' : '<span class="avatar tip" data-name="'.$topic->username.'" title="'.$topic->username.'"></span>'; ?>
                        </div>
                        <div class="small-circle <?= $this->tec->checkLastActivit($topic->user_id) ? 'online' : 'offline'; ?>"></div>
                    </div>
                </a>
                <div class="user-info load" style="display:none;">
                    <div class="hidden-content-box-left">
                        <div class="udata">
                            <img src="<?= $assets; ?>img/ring-40.gif" alt="loading...">
                        </div>
                    </div>
                </div>

                <div class="pinned-box">
                    <?php
                    if ($loggedIn && $topic->user_id != $this->session->userdata('user_id')) {
                        echo ' <a href="'.site_url('messages/send/'.$topic->user_id).'" data-toggle="ajax-modal" class="btn btn-xs btn-block btn-primary tip mb" title="'.lang('send_message').'"><i class="fa fa-envelope-o"></i></a>';
                    } else { echo '<div class="clearfix"></div>'; } ?>
                    <div class="<?=$loggedIn ? '' : 'mt mb'; ?>">
                    <?= $topic->active ? '<i class="tip fa fa-check" title="'.lang('active').'"></i>' : '<i class="tip fa fa-lock" title="'.lang('closed').'"></i>'; ?>
                    <?= $topic->sticky || (!empty($category) && $topic->sticky_category) ? '<i class="tip fa fa-thumb-tack" title="'.lang('sticky').'"></i>' : ''; ?>
                    <?= $loggedIn && $Settings->flag_option ? '<a href="'.site_url('complain/'.$topic->slug).'" data-toggle="ajax-modal" class="tip" title="'.lang('complain').'"><i class="fa fa-flag" ></i></a>' : ''; ?>
                    </div>
                </div>
            </div>
            <div class="content-box-middle">
                <?php
                if ($this->Settings->reply_sorting == 1) {
                    $page = ceil(($topic->total_posts-1) / $Settings->records_per_page);
                } else {
                    $page = 0;
                }
                ?>
                <h2 class="break-words"><a href="<?= site_url($topic->category_slug.'/'.$topic->slug.($page > 1 ? '?page='.$page : '')); ?>"><?= stripslashes($topic->title); ?></a>
                    <?php if ($Settings->voting == 1 && ($topic->up_votes || $topic->down_votes)) { ?>
                    <small class="pull-right" style="margin-top:4px;"><strong>
                        <span class="text-success"><?=$topic->up_votes;?> <i class="fa fa-thumbs-o-up"></i></span> |
                        <span class="text-danger"><?=$topic->down_votes;?> <i class="fa fa-thumbs-o-down"></i></span>
                    </strong></small>
                    <?php } elseif ($Settings->voting == 2 && $topic->stars > 0) { ?>
                        <small class="pull-right" style="margin-top:-2px; font-size:10px;"><input type="hidden" class="index-threads-rating" value="<?= $topic->stars; ?>"></small>
                    <?php } ?>
                </h2>
                <div class="meta-box">
                    <a href="<?= site_url('archive/'.date('Y', strtotime($topic->created_at)).'/'.date('m', strtotime($topic->created_at))); ?>">
                        <span class="label label-info"><i class="fa fa-clock-o"></i> <?= $this->tec->hrld($topic->created_at); ?></span>
                    </a>
                    <a href="<?= site_url($topic->category_slug); ?>">
                        <span class="label label-info"><i class="fa fa-folder"></i> <?= $topic->category; ?></span>
                    </a>
                    <?php if ($topic->child_category_id) { ?>
                    <a href="<?= site_url($topic->child_category_slug); ?>">
                        <span class="label label-info"><i class="fa fa-folder-o"></i> <?= $topic->child_category; ?></span>
                    </a>
                    <?php } ?>
                    <?php if ($Admin || $Moderator || $this->session->userdata('user_id') == $topic->created_by) { ?>
                    <a href="<?= site_url('topics/edit/'.$topic->id); ?>" data-toggle="ajax-modal">
                        <span class="label label-warning"><i class="fa fa-edit"></i> <?= lang('edit'); ?></span>
                    </a>
                    <?php if ( ! $Moderator) { ?>
                        <a href="<?= site_url('topics/delete/'.$topic->id); ?>" class="po-del">
                            <span class="label label-danger"><i class="fa fa-trash-o"></i> <?= lang('delete'); ?></span>
                        </a>
                    <?php } ?>
                    <?php } ?>
                </div>
                <h3 class="break-words">
                    <?= $this->tec->character_limiter(stripslashes($topic->description), 120); ?>
                </h3>
            </div>
            <div class="content-box-right" <?= $topic->last_reply_by ? 'style="padding-top:5px;"' : ''; ?>>
                <!-- <h2 class="info-box"><?= $topic->total_posts; ?></h2> -->
                <h3 class="tip" title="<?=lang('views');?>">
                    <i class="fa fa-eye"></i>
                    <span><?= $this->tec->short_num($topic->views).' '.($topic->views < 2 ? lang('view') : lang('views')); ?></span>
                </h3>
                <h3 class="tip" title="<?=lang('replies');?>">
                    <i class="fa fa-comments-o"></i>
                    <span><?= $this->tec->short_num($topic->total_posts-1).' '.(($topic->total_posts-1) < 2 ? lang('reply') : lang('replies')); ?></span>
                </h3>
                <h3 class="tip" title="<?=lang('created_at');?>">
                    <i class="fa fa-clock-o"></i>
                    <span><?= $this->tec->timespan($topic->created_at); ?></span>
                </h3>
                <h3 class="tip" title="<?=lang('last_reply_time');?>">
                    <i class="fa fa-reply"></i>
                    <span><?= $this->tec->timespan($topic->last_reply_time); ?></span>
                </h3>
                <?php if ($topic->last_reply_by) { ?>
                    <h3 class="tip" title="<?=lang('last_reply_by');?>">
                        <i class="fa fa-user"></i>
                        <span><?= $topic->last_reply_by; ?></span>
                    </h3>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php
        $r++;
        }
    ?>
    <div class="clearfix"></div>
    <?php if($links) { ?>
        <nav class="pagination-box">
            <ul class="page-info pull-left">
                <li class="previous disabled"><?= str_replace(array('{from}', '{till}', '{total}'), array($records['from'], $records['till'], $records['total']), lang('page_info'));?></li>
            </ul>
            <?php if ($loggedIn) { ?>
            <div class="dropup pull-right ml">
                <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default">
                    <?= lang('sort'); ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dLabel">
                    <?php
                    $csort = $this->session->userdata('sorting') ? $this->session->userdata('sorting') : $Settings->sorting;
                    foreach ($sopt as $key => $value) {
                        echo '<li class="'.($key == $csort ? 'active' : '').'"><a href="?sort='.$key.'">'.$value.'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <?php } ?>
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
    <?php
}
} else {
    echo '<h2>'.lang('no_topic_to_display').'</h2>';
}
?>
<div class="clearfix"></div>
</div>
<script type="text/javascript">
<?php
if (isset($category)) {
    echo "var page_url = '".site_url($category->slug)."';";
} elseif (isset($user_threads)) {
    echo "var page_url = '".site_url('user_topics/'.$user_threads)."';";
} elseif ($this->input->get('query')) {
    echo "var page_url = '".site_url('search?query='.$this->input->get('query'))."';";
} else {
    echo "var page_url = '".site_url()."';";
}
?>
</script>
