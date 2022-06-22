<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<aside class="col-sm-4">
    <?php if ($loggedIn) { ?>
        <div class="add-box">
            <h2>
                <a href="<?= site_url('topics/add'); ?>" data-toggle="ajax-modal">
                    <?= lang('add_thread'); ?>
                    <i class="pull-right fa fa-plus-circle"></i>
                </a>
            </h2>
        </div>
        <div class="add-box">
            <h2>
                <a href="<?= isset($category) ? site_url('user_topics/'.$category->slug.'/'.$this->session->userdata('user_id')) : site_url('user_topics/'.$this->session->userdata('user_id')); ?>">
                    <?= lang('my_threads').(isset($category) ? ' '.lang('from').' '.$category ->name : ''); ?>
                    <i class="pull-right fa fa-comments"></i>
                </a>
            </h2>
        </div>
    <?php } ?>
    <?php if (DEMO) { ?>
        <div class="add-box">
            <h2>
                <a href="http://codecanyon.net/item/simple-forum-responsive-bulletin-board/13289844?ref=Tecdiary" target="_blank" class="btn-success">
                    Buy Now
                    <i class="pull-right fa fa-shopping-cart"></i>
                </a>
            </h2>
        </div>
    <?php } ?>    
    <?php if ( ! empty($menu_categories)) { ?>
    <div class="categories-box">
        <h2><?= lang('categories'); ?> <i class="pull-right fa fa-folder"></i></h2>
        <ul>
            <?php foreach ($menu_categories as $mc): ?>
                <li>
                    <a href="<?= site_url($mc->slug); ?>">
                        <span class="label label-info"><?= $mc->topics; ?></span>
                        <?= $mc->name; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php } ?>

    <?php if ($Settings->ad_sidebar) { ?>
    <div class="ad ad-sidebar text-center"><?= $Settings->ad_sidebar_code; ?></div>
    <?php } ?>

    <?php if ($loggedIn && $user_active_topics) { ?>
        <div class="thread-box">
            <h2><?= lang('my_active_threads'); ?> <i class="pull-right fa fa-comments"></i></h2>
            <ul>
                <?php foreach ($user_active_topics as $active_topic) { ?>
                    <li>
                        <a href="<?= site_url($active_topic->category_slug.'/'.$active_topic->slug); ?>">
                            <span class="label label-info"><?= $active_topic->total_posts; ?></span>
                            <?= stripslashes($active_topic->title); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

<?php /* ?>
    <div class="forum-box">
        <h2><?= lang('forum_statistics'); ?> <i class="pull-right fa fa-bar-chart"></i></h2>
        <ul>
            <li>
                <a href="<?= site_url(); ?>">
                    <i class="fa fa-3x fa-comments" style="color:#eee"></i>
                    <h3><?= lang('threads'); ?></h3>
                    <h4><?= $total_topics; ?></h4>
                </a>
            </li>
            <li>
                <i class="fa fa-3x fa-comments-o"></i>
                <h3><?= lang('replies'); ?></h3>
                <h4><?= $total_posts-$total_topics; ?></h4>
            </li>
            <li>
                <?php if ($this->Settings->member_page) { ?>
                <a href="<?= site_url('members'); ?>">
                    <i class="fa fa-3x fa-users" style="color:#eee"></i>
                    <h3><?= lang('members'); ?></h3>
                    <h4><?= $total_users+1729485; ?></h4>
                </a>
                <?php } else { ?>
                <i class="fa fa-3x fa-users" style="color:#eee"></i>
                <h3><?= lang('members'); ?></h3>
                <h4><?= $total_users+1729485; ?></h4>
                <?php } ?>
            </li>
        </ul>
    </div>
<?php // */ ?>
    <?php if (!empty($online_users)) { ?>
        <div class="add-box fade-icon">
            <h2>
                <a <?= $this->Settings->member_page ? "href=\"".site_url('members?online=1')."\"" : "class=\"disabled\""; $onm='585';?>>
                    <?= $online_users+$onm.' '.($online_users > 1 ? lang('members') : lang('member')).' '.lang('online'); ?>
                    <i class="pull-right fa fa-users"></i>
                </a>
            </h2>
        </div>
    <?php } ?>
    <?php if ($today_birthdays || $today_logins) { ?>
        <div class="events-box">
            <h2><?= lang('today_event'); ?> <i class="pull-right fa fa-bullhorn"></i></h2>
            <?php if ($today_birthdays) { ?>
            <ul>
                <li>
                    <i class="fa fa-2x fa-birthday-cake"></i>
                    <h3><?= lang('birthdays'); ?></h3>
                </li>
                <li>
                    <h3>
                        <?php
                        foreach ($today_birthdays as $tbu) {
                            echo $tbu->username.', ';
                        }
                        ?>
                    </h3>
                </li>
            </ul>
            <?php } ?>
            <?php if ($today_logins) { ?>
            <ul>
                <li>
                    <i class="fa fa-2x fa-sign-in"></i>
                    <h3><?= lang('logins'); ?></h3>
                </li>
                <li>
                    <h3>
                        <?php
                        foreach ($today_logins as $tlu) {
                            echo $tlu->username.', ';
                        }
                        ?>
                    </h3>
                </li>
            </ul>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if ($Settings->ad_sidebar2) { ?>
    <div class="ad ad-sidebar-2 mt text-center"><?= $Settings->ad_sidebar2_code; ?></div>
    <?php } ?>
</aside>
