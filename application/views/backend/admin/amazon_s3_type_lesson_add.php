<input type="hidden" name="lesson_type" value="s3-video">
<input type="hidden" name="lesson_provider" value="amazon">

<div class="form-group">
    <label> <?php echo get_phrase('upload_video_file'); ?>( <?php echo get_phrase('for_web_and_mobile_application'); ?> )</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="video_file_for_amazon_s3" name="video_file_for_amazon_s3" onchange="changeTitleOfImageUploader(this)" >
            <label class="custom-file-label" for="video_file_for_amazon_s3"><?php echo get_phrase('select_a_video_file'); ?></label>
        </div>
    </div>
    <small class="badge badge-primary"><?php echo 'maximum_upload_size'; ?>: <?php echo ini_get('upload_max_filesize'); ?></small>
    <small class="badge badge-primary"><?php echo 'post_max_size'; ?>: <?php echo ini_get('post_max_size'); ?></small>
    <small class="badge badge-secondary"><?php echo '"post_max_size" '.get_phrase("has_to_be_bigger_than").' "upload_max_filesize"'; ?></small>
</div>

<lable for="video_link_for_amazon_s3" ><?php echo get_phrase('video_link_for_amazon_s3'); ?></lable>
<div class="input-group mb-3">
  <input id="video_link_for_amazon_s3" type="url" class="form-control"  placeholder="URL" name="video_link_for_amazon_s3" >
</div>

<div class="form-group">
    <label><?php echo get_phrase('duration'); ?>( <?php echo get_phrase('for_web_and_mobile_application'); ?> )</label>
    <input type="text" class="form-control" data-toggle='timepicker' data-minute-step="5" name="amazon_s3_duration" id = "amazon_s3_duration" data-show-meridian="false" value="00:00:00" required>
</div>
