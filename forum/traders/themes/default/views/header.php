<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!doctype html>
<html<?= $Settings->rtl ? ' dir="rtl"' : ''; ?>>
<head>
    <meta charset="UTF-8">
<?php if (DEMO) { ?>
        <script type="text/javascript">if(parent.frames.length !==0 ){top.location = '<?=base_url();?>';}</script>
<?php } ?>
    <title><?= $page_title .' - '.$Settings->site_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=base_url('uploads/'.$Settings->favicon); ?>" rel="icon" type="image/x-icon" />
    <meta name="description" content="<?= $meta_description ? $meta_description : $Settings->description; ?>">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <?php if ($Settings->editor == 'redactor') { ?>
    <link rel="stylesheet" href="<?= $assets; ?>components/trumbowyg/ui/trumbowyg.min.css">
    <link rel="stylesheet" href="<?= $assets; ?>components/trumbowyg/plugins/emoji/ui/trumbowyg.emoji.css">
    <!-- <link rel="stylesheet" href="<?= $assets; ?>components/redactor/redactor.css"> -->
    <?php } elseif ($Settings->editor == 'simpledme') { ?>
    <link rel="stylesheet" href="<?= $assets; ?>components/simplemde/dist/simplemde.min.css">
    <?php } elseif ($Settings->editor == 'sceditor') { ?>
    <link rel="stylesheet" href="<?= $assets; ?>components/sceditor/minified/themes/modern.min.css">
    <?php } ?>
    <link rel="stylesheet" href="<?= $assets ?>css/all.css">
    <?php if ($Settings->style != 'white') { ?>
    <link rel="stylesheet" href="<?= $assets ?>css/<?= $Settings->style; ?>.css">
    <?php } ?>
    <?php if ($Settings->rtl) { ?>
    <link rel="stylesheet" href="<?= $assets ?>css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="<?= $assets ?>css/style-rtl.css">
    <?php } ?>
    <?php if (file_exists(FCPATH.'themes'.DIRECTORY_SEPARATOR.$Settings->theme.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'custom.css')) { ?>
    <link rel="stylesheet" href="<?= $assets ?>css/custom.css">
    <?php } ?>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <div id="logo" class="navbar-brand">
                    <h2>
                        <a href="<?= base_url(); ?>">
                            <img src="<?=base_url('uploads/'.$Settings->logo); ?>" alt="<?= $Settings->site_name; ?>" class="img-responsive">
                        </a>
                    </h2>
                </div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only"><?= lang('toggle_nav'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php if ($menu_pages) { ?>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-files-o hidden-xs"></i> <span class="visible-xs"><i class="fa fa-files-o"></i> <?= lang('pages'); ?><span class="caret"></span></span></a>
                            <ul class="dropdown-menu">
                                <?php
                                foreach ($menu_pages as $menu_page) {
                                    echo '<li><a href="'.site_url('pages/'.$menu_page->slug).'"><i class="fa fa-file-text-o"></i> '.$menu_page->title.'</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>

                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if ($Admin) {
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-users hidden-xs"></i> <span class="visible-xs"><i class="fa fa-users"></i> <?= lang('users'); ?><span class="caret"></span></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= site_url('users'); ?>"><i class="fa fa-users"></i> <?= lang('list_users'); ?></a></li>
                            <li><a href="<?= site_url('users/add'); ?>"><i class="fa fa-user-plus"></i> <?= lang('add_user'); ?></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?= site_url('users/badges'); ?>"><i class="fa fa-certificate"></i> <?= lang('list_badges'); ?></a></li>
                            <li><a href="<?= site_url('users/add_badge'); ?>" data-toggle="ajax-modal"><i class="fa fa-plus-circle"></i> <?= lang('add_badge'); ?></a></li>
                        </ul>
                    </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cogs hidden-xs"></i> <span class="visible-xs"><i class="fa fa-cogs"></i> <?= lang('settings'); ?><span class="caret"></span></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= site_url('settings'); ?>"><i class="fa fa-cogs"></i> <?= lang('settings'); ?></a></li>
                                <li><a href="<?= site_url('settings/ads'); ?>"><i class="fa fa-puzzle-piece"></i><?= lang('ad_settings'); ?></a></li>
                                <?php if ($Settings->apis > 0) { ?>
                                <li><a href="<?= site_url('settings/api_keys'); ?>"><i class="fa fa-wrench"></i><?= lang('api_keys'); ?></a></li>
                                <?php } ?>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?= site_url('categories'); ?>"><i class="fa fa-folder"></i> <?= lang('list_categories'); ?></a></li>
                                <li><a href="<?= site_url('categories/add'); ?>" data-toggle="ajax-modal"><i class="fa fa-plus-circle"></i> <?= lang('add_category'); ?></a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?= site_url('settings/fields'); ?>"><i class="fa fa-keyboard-o"></i> <?= lang('list_fields'); ?></a></li>
                                <li><a href="<?= site_url('settings/add_field'); ?>" data-toggle="ajax-modal"><i class="fa fa-plus-circle"></i> <?= lang('add_field'); ?></a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?= site_url('pages'); ?>"><i class="fa fa-file-text"></i> <?= lang('list_pages'); ?></a></li>
                                <li><a href="<?= site_url('pages/add'); ?>" data-toggle="ajax-modal"><i class="fa fa-plus-circle"></i> <?= lang('add_page'); ?></a></li>
                                <!-- <li role="separator" class="divider"></li>
                                <li><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-download"></i> <?= lang('backups'); ?></a></li>
                                <li><a href="<?= site_url('settings/updates'); ?>"><i class="fa fa-upload"></i> <?= lang('updates'); ?></a></li> -->
                            </ul>
                        </li>
                    <?php
                    } if ($loggedIn) {
                    ?>
					 <li><a href="https://gambabet.com"><i class="fa fa-home"></i> Home</a></li>
                        <li class="num-badge">
                            <a href="<?= site_url('messages'); ?>">
                                <i class="fa fa-envelope"></i>
                                <span class="hidden-sm hidden-md hidden-lg" style="padding-left:5px;"><?= lang('messages'); ?></span>
                                <?= $unread_messages ? '<span class="label label-danger number">'.$unread_messages.'</span>' : ''; ?>
                            </a>
                        </li>
                        <?php
                        $total_reviews = $total_pending_topics+$total_pending_posts;
                        if (($Admin || $Moderator) && $total_reviews > 0) {  ?>
                            <li class="dropdown num-badge">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bell"></i>
                                    <span class="hidden-sm hidden-md hidden-lg" style="padding-left:5px;"><?= lang('reviews'); ?></span>
                                    <?= $total_reviews ? '<span class="label label-danger number">'.$total_reviews.'</span>' : ''; ?>
                                </a>
                                <ul class="dropdown-menu">
                                <?php if ($total_pending_topics > 0) { ?>
                                    <li>
                                        <a href="<?= site_url('reviews'); ?>">
                                            <span class="pull-right badge"><?=$total_pending_topics;?></span>
                                            <i class="fa fa-bell"></i> <?= lang('threads'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($total_pending_posts > 0) { ?>
                                    <li>
                                        <a href="<?= site_url('reviews/posts'); ?>">
                                            <span class="pull-right badge"><?=$total_pending_posts;?></span>
                                            <i class="fa fa-bell"></i> <?= lang('Replies'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= lang('hi').' <span class="hidden-sm">'.$this->session->userdata('username').' <span class="caret"></span></span>'; ?></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?= site_url('users/profile/'.$this->session->userdata('username')); ?>">
                                        <i class="fa fa-user"></i> <?= lang('profile'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= site_url('messages'); ?>">
                                        <i class="fa fa-envelope"></i> <?= lang('messages'); ?>
                                    </a>
                                </li>
								
								<li>
                                    <a style="color:#f00" href="https://sp.sportsfier.com/">
									
                                        <i class="fa fa-database"></i> Back to Main Portal
                                    </a>
                                </li>
								
                                <li><a href="<?= site_url('logout'); ?>"><i class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li>
                            </ul>
                        </li>
                        <?php
                    } else {
                        /*if ($this->Settings->wp_login) { ?>
                            <li><a href="<?= site_url('wp_login'); ?>"><?= lang('wp_login'); ?></a></li>
                            <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
                                <li><a href="<?= $Settings->wp_url.'wp-login.php?action=register&redirect_to='.site_url('wp_login'); ?>"><?= lang('register'); ?></a></li>
                            <?php }
                        }*/
                        if ($this->Settings->login_modal) {
                            ?>
                            <li><a href="<?= site_url('login_modal'); ?>" data-toggle="ajax-modal"><?= lang('login'); ?></a></li>
                            <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
                            <li><a href="<?= site_url('register_modal'); ?>" data-toggle="ajax-modal"><?= lang('register'); ?></a></li>
                            <?php } ?>
                        <?php } else { ?>
						<li><a href="https://gambabet.com"><i class="fa fa-home"></i> Home</a></li>
                            <li><a href="/login/"><?= lang('login'); ?></a></li>
                            <?php if ($Settings->mode != 2 && $Settings->registration) { ?>
							
							<li><a href="/registration/"><?= lang('register'); ?></a></li>
							<?php /* ?>
                            <li><a href="<?= site_url('login#register'); ?>"><?= lang('register'); ?></a></li>
							<?php // */ ?>
                            <?php } ?>
                        <?php
                        }
                    }
                    ?>
                </ul>
                <div class="hidden-xs hidden-sm">
                    <?= form_open('search', 'class="header-search pull-right" method="GET"'); ?>
                    <input type="text" name="query" placeholder="<?= lang('search'); ?>" class="font300" value="<?= $this->input->get('query') ? $this->input->get('query') : ''; ?>"/>
                    <button type="submit" class="search-filter"><i class="fa fa-search"></i></button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="container">
            <div class="row">
            <?php if ( ! empty($Settings->alert)) { ?>
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <?= $Settings->alert; ?>
                </div>
            </div>
            <?php } ?>
            <div class="col-xs-12 mb visible-xs visible-sm">
                <?php if ($loggedIn) { ?>
                <div class="add-box visible-xs">
                    <h2>
                        <a href="<?= site_url('topics/add'); ?>" data-toggle="ajax-modal">
                            <?= lang('add_thread'); ?>
                            <i class="pull-right fa fa-plus-circle"></i>
                        </a>
                    </h2>
                </div>
                <div class="clearfix"></div>
                <div class="visible-xs mb"></div>
                <?php } ?>
                <?= form_open('search', 'class="header-search pull-right m0" style="width:100%;" method="GET"'); ?>
                <input type="text" name="query" placeholder="<?= lang('search'); ?>" class="font300" value="<?= $this->input->get('query') ? $this->input->get('query') : ''; ?>"/>
                <button type="submit" class="search-filter"><i class="fa fa-search"></i></button>
                <?= form_close(); ?>
            </div>
            <div class="clearfix"></div>
