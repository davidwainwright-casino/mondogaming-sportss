<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</div>
</div>
</div>

<?php if ($Settings->ad_footer) { ?>
<div class="ad ad-footer text-center"><?= $Settings->ad_footer_code; ?></div>
<?php } ?>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="footer-box">
                    <?php if ($menu_pages) { ?>
                    <nav class="footer-nav">
                        <ul>
                            <?php
                            foreach ($menu_pages as $menu_page) {
                                echo '<li><a href="'.site_url('pages/'.$menu_page->slug).'"><i class="fa fa-circle"></i> '.$menu_page->title.'</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                    <?php } ?>
                    <ul class="social-nav">
                        <?php if ($Settings->facebook != '') { ?>
                        <li><a href="<?=$Settings->facebook;?>" class="tip" title="<?= lang('facebook'); ?>" target="_blank"><i class="fa fa-facebook-official"></i></a></li>
                        <?php } if ($Settings->twitter != '') { ?>
                        <li><a href="<?=$Settings->twitter;?>" class="tip" title="<?= lang('twitter'); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <?php } if ($Settings->google_plus != '') { ?>
                        <li><a href="<?=$Settings->google_plus;?>" class="tip" title="<?= lang('google_plus'); ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                        <?php } ?>
                        <!-- <li><a href="#"><i class="fa fa-rss"></i></a></li> -->
                    </ul>
                    <h2>&copy; <?= date('Y').' '.$Settings->site_name.' <small>(v'.$Settings->version.')</small> - '.lang('all_rights_reserved'); ?><span><?= lang('forum_time').': '.$this->tec->getTimezoneAbbr().' ('.TIMEZONE.')'; ?></span></h2>
                </div>
            </div>
        </div>
    </div>
</footer>
<div id="ajaxCall"><img src="<?= $assets; ?>img/ring-40.gif" alt="loading..."></div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"></div>

<script type="text/javascript" src="<?= $assets ?>js/all.js"></script>
<?php if ($Settings->editor == 'redactor') { ?>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/trumbowyg.min.js"></script>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/plugins/upload/trumbowyg.file.js"></script>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/plugins/upload/trumbowyg.upload.min.js"></script>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/plugins/noembed/trumbowyg.noembed.js"></script>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/emojify/js/emojify.min.js"></script>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/plugins/emoji/trumbowyg.emoji.min.js"></script>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js"></script>
<?php if ($Settings->rtl) { ?>
<script type="text/javascript" src="<?= $assets; ?>components/trumbowyg/langs/ar.min.js"></script>
<?php } ?>
<!-- <script type="text/javascript" src="<?= $assets; ?>components/redactor/redactor.min.js"></script> -->
<?php } elseif ($Settings->editor == 'simpledme') { ?>
<script type="text/javascript" src="<?= $assets; ?>components/simplemde/dist/simplemde.min.js"></script>
<?php } elseif ($Settings->editor == 'sceditor') { ?>
<script type="text/javascript" src="<?= $assets; ?>components/sceditor/minified/jquery.sceditor.bbcode.min.js"></script>
<?php }
$footer_code = $Settings->footer_code;
unset($Settings->setting_id, $Settings->description, $Settings->file_path, $Settings->records_per_page, $Settings->default_email, $Settings->protocol, $Settings->smtp_host, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->smtp_crypto, $Settings->mailpath, $Settings->banned_words, $Settings->censore_word, $Settings->facebook, $Settings->twitter, $Settings->google_plus, $Settings->notification, $Settings->ad_thread, $Settings->ad_thread_code, $Settings->ad_sidebar, $Settings->ad_sidebar_code, $Settings->ad_sidebar2, $Settings->ad_sidebar2_code, $Settings->ad_footer, $Settings->ad_footer_code, $Settings->apis, $Settings->footer_code, $Settings->login_modal, $Settings->review_option, $Settings->wp_login, $Settings->wp_url, $Settings->wp_client_id, $Settings->wp_secret, $Settings->guest_reply, $Settings->registration, $Settings->review_option);
?>
<script type="text/javascript">
var base_url = '<?= base_url(); ?>', site_url = '<?= site_url(); ?>', assets = '<?= $assets ?>', Settings = <?= json_encode($Settings); ?>;
var lang = { 'yes': '<?= lang('yes'); ?>', 'no': '<?= lang('no'); ?>', 'close': '<?= lang('close'); ?>', 'delete': '<?= lang('delete'); ?>', r_u_sure: '<?= lang('r_u_sure'); ?>', 'action_x_undo': '<?= lang('action_x_undo'); ?>', 'select_child': '<?= lang('select_child'); ?>', 'no_child': '<?= lang('no_child'); ?>', 'select_parent_first': '<?= lang('select_parent_first'); ?>', 'who_can_see_required': '<?= lang('who_can_see_required'); ?>', 'user_x_found': '<?= lang('user_x_found'); ?>', 'reply': '<?= lang('reply'); ?>', 'quoting': '<?= lang('quoting'); ?>'
};

<?php if ($message || $warning || $error) { ?>
$(document).ready(function() {
    <?php if ($message) { ?>
        toastr.success('<?= trim(str_replace(array("\r","\n","\r\n"), '', addslashes($message))); ?>', '<?=lang('success');?>', {timeOut: 8000});
    <?php } if ($warning) { ?>
        toastr.warning('<?= trim(str_replace(array("\r","\n","\r\n"), '', addslashes($warning))); ?>', '<?=lang('warning');?>', {timeOut: 8000});
    <?php } if ($error) { ?>
        toastr.error('<?= trim(str_replace(array("\r","\n","\r\n"), '', addslashes($error))); ?>', '<?=lang('error');?>', {timeOut: 15000});
    <?php } ?>

});
<?php } ?>
<?php
$yesterday = date('Y-m-d', strtotime('-1 day'));
if ($Settings->digest_date < $yesterday) {
echo "var yesterday = '{$yesterday}';";
?>
$(document).ready(function() { $.get(base_url+'ajax_calls/send_digest'); });
<?php } ?>
</script>
<script src="<?= $assets ?>js/main.min.js"></script>
<?php if (strtolower($this->router->fetch_class()) == 'forums' && strtolower($this->router->fetch_method()) == 'topic') { ?>
<script type="text/javascript">
$(document).ready(function ($) {
  $('.rrssb-buttons').rrssb({
    title: '<?= $topic->title; ?>',
    url: page_url,
    image: '<?=base_url('uploads/'.$Settings->logo); ?>',
    description: $("meta[name='description']").attr('content'),
    // emailSubject: '',
    // emailBody: '',
  });
  $("meta[name='shareUrl']").attr('content', page_url);
  $("meta[name='shareImage']").attr('content', '<?=base_url('uploads/'.$Settings->logo); ?>');
});
</script>
<?php } ?>
<?php if ($footer_code) { echo $footer_code; } ?>
</body>
</html>
