<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('add_badge'); ?></h4>
        </div>
        <?= form_open_multipart('users/edit_badge/' . $badge->id, 'id="badgeForm" class="form"'); ?>
        <div class="modal-body mm-body">
            <div class="">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= lang('title', 'title'); ?>
                        <?= form_input('title', set_value('title', $badge->title), 'class="form-control" id="title" pattern=".{3,10}" required="" data-fv-notempty-message="' . lang('title_required') . '"'); ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('class', 'class'); ?>
                                <?= form_input('class', set_value('class', $badge->class), 'class="form-control" id="class"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang('image', 'uploadImage'); ?>
                                <div class="input-group">
                                    <input type="text" class="form-control tip upload-img" placeholder="Choose Image" disabled="disabled" />
                                    <div class="fileUpload input-group-addon btn btn-primary">
                                        <span><?= lang('browse'); ?></span>
                                        <input id="uploadImage" name="image" type="file" class="upload" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mm-footer">
            <div class="col-md-12">
                <div style="font-size:32px;float:left;margin-right:5px;" id="cl"></div>
                <img src="<?= $badge->image ? base_url('uploads/' + $badge->image) : ''; ?>" id="img" style="max-width:32px;max-height:32px;float:left;" />
                <?= form_submit('edit_badge', lang('edit_badge'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#class').bind("change, keyup, input",function() {
            $('#cl').html('<i class="'+$(this).val()+'"></i>');
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#uploadImage").change(function(){
            readURL(this);
            $('.upload-img').val(this.value);
        });
    });
</script>
