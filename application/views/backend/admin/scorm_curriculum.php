<?php 
	$CI	=&	get_instance();
	$CI->load->model('addons/scorm_model');
	$scourm_query = $CI->scorm_model->get_scorm_curriculum_by_course_id($course_details['id']);
	if($scourm_query->num_rows() > 0){
		$scourm_details = $scourm_query->row_array();
	}else{
		$scourm_details = array('scorm_provider' => null);
	}
?>
<?php if($scourm_details['scorm_provider'] == null): ?>
	<div class="row justify-content-center">
		<div class="col-md-12 text-center">
			<img src="<?= base_url('uploads/scorm/logo/file-upload.png') ?>" width="150">
		</div>
		<div class="col-md-12 text-center">
			<a href="javascript:;"  onclick="showAjaxModal('<?= site_url('modal/popup/scorm_course_uploading_form/'.$course_details['id']); ?>', '<?= get_phrase('upload_scorm_course'); ?>')" id="upload-modal-btn" class="btn btn-primary btn-sm"><?= get_phrase('upload_course'); ?><i class="mdi mdi-upload"></i></a>
		</div>
		<div class="col-md-6 pt-2">
			<div class="alert alert-success" role="alert">
	            <h5 class="text-center"><?php echo get_phrase('instruction'); ?></h5>
	            <p class="mb-0 p-2">
	            	1. <?= get_phrase('click_the_upload_course_button'); ?>. <?= get_phrase('you_will_get_a_floating_form_after_clicking_the_button'); ?>.<br>
	            	2. <?= get_phrase('select_a_scorm_provider_first'); ?>.<br>
	            	3. <?= get_phrase('choose_your_scorm_zip_file'); ?>.<br>
	            	4. <?= get_phrase('Click_the_upload_button_to_save_the_course_file'); ?>.<br>
	            </p>
	        </div>
		</div>
	</div>
<?php else: ?>
	<div class="row justify-content-center mb-4">
		<div class="col-md-12 text-center">
			<img src="<?= base_url('uploads/scorm/logo/'.$scourm_details['scorm_provider'].'.png'); ?>" width="150">
		</div>
		<div class="col-md-6 pt-4">
			<div class="alert alert-light text-center" role="alert">
        		<?= get_phrase('current_course_provider'); ?> <strong><?= get_phrase($scourm_details['scorm_provider']); ?></strong>
            </div>
            <div class="row">
	            <div class="col-xl-4 pt-1  text-center">
	            	<button type="button" class="btn btn-info btn-block"  onclick="showLargeModal('<?= site_url('modal/popup/preview_scorm_course/'.$course_details['id']); ?>')"><?= get_phrase('preview'); ?></button>
	            </div>
	            <div class="col-xl-4 pt-1">
	            	<button type="button" class="btn btn-success btn-block"  onclick="showAjaxModal('<?= site_url('modal/popup/scorm_course_uploading_form/'.$course_details['id']); ?>', '<?= get_phrase('update_scorm_course'); ?>')"><?= get_phrase('update_course'); ?></button>
	            </div>
	            <div class="col-xl-4 pt-1">
	            	<button type="button" class="btn btn-danger btn-block"  onclick="confirm_modal('<?= site_url('addons/scorm/remove_curriculum/'.$course_details['id']); ?>')"><?= get_phrase('remove'); ?></button>
	            </div>
	        </div>
		</div>
	</div>
<?php endif; ?>

<?php include "scorm_scripts.php"; ?>