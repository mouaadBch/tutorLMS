<div class="col-md-12 p-4">
	<!--Show more loading icon-->
	<div class="row justify-content-center">
	    <div class="col-md-7 p-0 hidden" id="show_more_loding_icon">
	        <img width="100%" src="<?php echo base_url('assets/global/gif/page-loader.gif'); ?>">
	        <img width="100%" src="<?php echo base_url('assets/global/gif/page-loader.gif'); ?>">
	        <img width="100%" src="<?php echo base_url('assets/global/gif/page-loader.gif'); ?>">
	    </div>
	    <div class="col-md-7 p-0 text-center hidden py-5 my-5" id="show_assignments">
	        <img width="100" class="my-5" src="<?php echo base_url('assets/global/gif/page-loader-2.gif'); ?>">
	    </div>
	</div>
</div>

<script type="text/javascript">
	'use strict';

	function load_course_assignments(course_id){
		$('.remove-active').removeClass('active');
		$('#assignment_tab').addClass('active');

		$('#load-tabs-body').hide();
		$('#show_assignments').show();
		$.ajax({
			url: '<?= site_url('addons/assignment/load_assignments_with_ajax/'); ?>'+course_id,
			success: function(response){
				setTimeout(function(){
					$('#show_assignments').hide();
					$('#load-tabs-body').show();
					$('#load-tabs-body').html(response);
				},200);
			}
  		});
	}

	function load_assignment_submit_form(course_id, assignment_id){
		$.ajax({
			url: '<?= site_url('addons/assignment/assignment_submit_form/'); ?>'+course_id+'/'+assignment_id,
			success: function(response){
				$('#load-tabs-body').html(response);
			}
  		});
	}

	function load_submitted_assignment_result(course_id, assignment_id){
		$.ajax({
			url: '<?= site_url('addons/assignment/submitted_assignment_result/'); ?>'+course_id+'/'+assignment_id,
			success: function(response){
				$('#load-tabs-body').html(response);
			}
  		});
	}

</script>