<input type="hidden" name="lesson_type" value="bunny-video">
<input type="hidden" name="lesson_provider" value="bunny">

<div class="form-group">
    <label> <?php echo get_phrase('upload_video_file'); ?>( <?php echo get_phrase('for_web_and_mobile_application'); ?> )</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="video_file_for_bunny" name="video_file_for_bunny">
            <label class="custom-file-label" for="video_file_for_bunny"><?php echo get_phrase('select_a_video_file'); ?></label>
        </div>
    </div>
    <small class="badge badge-primary"><?php echo 'maximum_upload_size'; ?>: <?php echo ini_get('upload_max_filesize'); ?></small>
    <small class="badge badge-primary"><?php echo 'post_max_size'; ?>: <?php echo ini_get('post_max_size'); ?></small>
    <small class="badge badge-secondary"><?php echo '"post_max_size" '.get_phrase("has_to_be_bigger_than").' "upload_max_filesize"'; ?></small>
</div>
<lable for="video_link_for_bunny" ><?php echo get_phrase('video_link_for_bunny'); ?></lable>
<div class="input-group mb-3">
  <input id="video_link_for_bunny" type="url" class="form-control"  placeholder="URL" name="video_link_for_bunny" value="<?=  $lesson_details['video_url']; ?>" >
</div>
<div class="form-group">
    <label><?php echo get_phrase('duration'); ?>( <?php echo get_phrase('for_web_and_mobile_application'); ?> )</label>
    <input type="text" class="form-control" data-toggle='timepicker' data-minute-step="5" name="bunny_duration" id = "bunny_duration" data-show-meridian="false" value="<?php echo $lesson_details['duration']; ?>" required>
</div>