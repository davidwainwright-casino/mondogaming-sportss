<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:1.5em;margin-top:-0.5em;"><span aria-hidden="true" style="font-size:2em;">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?=$page->title;?></h4>
        </div>
        <div class="modal-body terms-text">
            <?= $this->tec->decode_html($page->body); ?>
        </div>
    </div>
</div>
