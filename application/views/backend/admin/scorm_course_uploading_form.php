<?php 
	$CI	=&	get_instance();
	$CI->load->model('addons/scorm_model');
	$scourm_query = $CI->scorm_model->get_scorm_curriculum_by_course_id($param2);
	if($scourm_query->num_rows() > 0){
		$scourm_details = $scourm_query->row_array();
	}else{
		$scourm_details = array('scorm_provider' => null);
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<div class="row">
				<div class="col-md-12 p-1"><label for="scorm_provider"><?= get_phrase('select_scorm_provider'); ?></label></div>
				<div class="col-md-4 p-1 m-0">
					<div class="course-provider-logo cursor-pointer <?php if($scourm_details['scorm_provider'] == 'ispring') echo 'course-provider-logo-checked'; ?>"  onclick="checked_scorm_provider(this, 'ispring')">
						<img class="ispring" src="<?= base_url('uploads/scorm/logo/ispring.png'); ?>">
					</div>
				</div>
				<div class="col-md-4 p-1 m-0">
					<div class="course-provider-logo cursor-pointer <?php if($scourm_details['scorm_provider'] == 'articulate') echo 'course-provider-logo-checked'; ?>"  onclick="checked_scorm_provider(this, 'articulate')">
						<img class="articulate" src="<?= base_url('uploads/scorm/logo/articulate.png'); ?>">
					</div>
				</div>
				<div class="col-md-4 p-1 m-0">
					<div class="course-provider-logo cursor-pointer <?php if($scourm_details['scorm_provider'] == 'adobe_captivate') echo 'course-provider-logo-checked'; ?>"  onclick="checked_scorm_provider(this, 'adobe_captivate')">
						<img class="adobe_captivate" src="<?= base_url('uploads/scorm/logo/adobe_captivate.png'); ?>">
					</div>
				</div>

				<div class="d-none">
					<div class="custom-control custom-radio">
	                    <input type="radio" id="ispring" value="ispring" name="scorm_provider" class="custom-control-input scorm-provider-radio" <?php if($scourm_details['scorm_provider'] == 'ispring') echo 'checked'; ?>>
	                    <label class="custom-control-label" for="ispring"><?= get_phrase('ispring'); ?></label>
	                </div>
	                <div class="custom-control custom-radio ml-2">
	                    <input type="radio" id="articulate" value="articulate" name="scorm_provider" class="custom-control-input scorm-provider-radio" <?php if($scourm_details['scorm_provider'] == 'articulate') echo 'checked'; ?>>
	                    <label class="custom-control-label" for="articulate"><?= get_phrase('articulate'); ?></label>
	                </div>
	                <div class="custom-control custom-radio ml-2">
	                    <input type="radio" id="adobe_captivate" value="adobe_captivate" name="scorm_provider" class="custom-control-input scorm-provider-radio" <?php if($scourm_details['scorm_provider'] == 'adobe_captivate') echo 'checked'; ?>>
	                    <label class="custom-control-label" for="adobe_captivate"><?= get_phrase('adobe_captivate'); ?></label>
	                </div>
	            </div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<label for="scorm_zip"><?= get_phrase('scorm_zip'); ?></label>
				<div class="custom-file">
                    <div class="custom-file">
						<input type="file" class="custom-file-input" id="scorm_zip" onchange="changeTitleOfImageUploader(this)" accept=".zip">
						<label class="custom-file-label ellipsis" for="scorm_zip"><?php echo get_phrase('choose_file'); ?></label>
					</div>
				</div>
				<div class="col-md-12 p-0">
					<small class="badge badge-light"><?php echo 'maximum_upload_size'; ?>: <?php echo ini_get('upload_max_filesize'); ?></small>
				    <small class="badge badge-light"><?php echo 'post_max_size'; ?>: <?php echo ini_get('post_max_size'); ?></small>
				    <small class="badge badge-info-lighten"><?php echo '"post_max_size" '.get_phrase("has_to_be_bigger_than").' "upload_max_filesize"'; ?></small>
				</div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-12 p-0">
				<div class="form-group">
					<?php if($scourm_details['scorm_provider'] == null): ?>
						<button type="button" onclick="upload_scorm_curriculum(this)" class="btn btn-primary"><?= get_phrase('upload'); ?></button>
					<?php else: ?>
						<button type="button" onclick="upload_scorm_curriculum(this)" class="btn btn-primary"><?= get_phrase('update'); ?></button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	"use strict";

	function checked_scorm_provider(finder, scorm_provider){
		$(".scorm-provider-radio").prop("checked", false);
		$("#"+scorm_provider).prop("checked", true);

		$(".course-provider-logo").removeClass("course-provider-logo-checked");
		$(finder).addClass("course-provider-logo-checked");
	}
</script>