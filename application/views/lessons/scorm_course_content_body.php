<?php
	$scorm_course_content_url = "";
	if($scorm_curriculum['scorm_provider'] == 'ispring'):
		if(file_exists("uploads/scorm/courses/".$scorm_curriculum['identifier'].'/index_scorm.html')){
			
			$scorm_course_content_url = "uploads/scorm/courses/".$scorm_curriculum['identifier'].'/index_scorm.html';
		 }else{
			 $scorm_course_content_url = "uploads/scorm/courses/".$scorm_curriculum['identifier'].'/story.html';
		 }
	elseif($scorm_curriculum['scorm_provider'] == 'articulate'):
		if(file_exists("uploads/scorm/courses/".$scorm_curriculum['identifier'].'/story.html')){
			$scorm_course_content_url = "uploads/scorm/courses/".$scorm_curriculum['identifier'].'/story.html';
		  }else{
			 $scorm_course_content_url = "uploads/scorm/courses/".$scorm_curriculum['identifier'].'/index.html';
		  }
		
	elseif($scorm_curriculum['scorm_provider'] == 'adobe_captivate'):
		if(file_exists("uploads/scorm/courses/".$scorm_curriculum['identifier'].'/index_scorm.html')){
			$scorm_course_content_url = "uploads/scorm/courses/".$scorm_curriculum['identifier'].'/index_scorm.html';
		}else{
			$scorm_course_content_url = "uploads/scorm/courses/".$scorm_curriculum['identifier'].'/index.html';
		}
		
	endif;
?>
<?php if(addon_status('scorm_course')): ?>
	<div class="col-lg-12 p-0 border-0">
		<iframe class="mt-5" sandbox="allow-scripts allow-forms allow-pointer-lock allow-same-origin" id="scorm_iframe" frameBorder="0" src="<?= base_url($scorm_course_content_url); ?>" width="100%" title="Scorm course"></iframe>
	</div>
<?php else: ?>
	<div class="col-lg-12">
		<div class="alert alert-warning p-5 mt-5" role="alert">
	        <h4 class="alert-heading"><?= site_phrase('heads_up'); ?>!</h4>
	        <p><?= site_phrase('currently_the_scorm_course_addon_is_deactivate'); ?>. <?= site_phrase('please_activate_the_scorm_course_addon_to_use_it'); ?>.</p>
	    </div>
	</div>
<?php endif ?>
<script type="text/javascript">
	'use strict';
	//For Scorm course body
	$(document).ready(function(){
	  var width = $('#scorm_iframe').width();
	  $('#scorm_iframe').attr("height", width/2);
	  window.onresize = function(event) {
	    var width = $('#scorm_iframe').width();
	    $('#scorm_iframe').attr("height", width/2);
	  };
	});
	//End for Scorm course body
</script>