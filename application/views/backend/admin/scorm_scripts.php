<script type="text/javascript">
	'use strict';

	function upload_scorm_curriculum(btn){
		var btn_text = $(btn).text();
		//loading start
		$(btn).html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span><?= get_phrase('uploading'); ?>...');
		$(btn).prop("disabled",true);

		var form_data = new FormData();
		var scorm_provider = "";
		if (document.getElementById('ispring').checked) {
			scorm_provider = document.getElementById('ispring').value;
		}else if(document.getElementById('articulate').checked){
			scorm_provider = document.getElementById('articulate').value;
		}else if(document.getElementById('adobe_captivate').checked){
			scorm_provider = document.getElementById('adobe_captivate').value;
		}

	    var scorm_zip = $('#scorm_zip').prop('files')[0];
	    form_data.append('scorm_zip', scorm_zip);
	    form_data.append('scorm_provider', scorm_provider);
	    $.ajax({
	        url: '<?= site_url('addons/scorm/add_curriculum/'.$course_details['id']); ?>',
	        dataType: 'text',  // what to expect back from the PHP script, if anything
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: form_data,                         
	        type: 'post',
	        success: function(response){
	   			//end loading
	   			if(response == 'success'){
	         		location.reload();
				}else{
					$.NotificationApp.send("<?php echo get_phrase('oh_snap'); ?>!", response ,"top-right","rgba(0,0,0,0.2)","error");
					$(btn).html(btn_text);
					$(btn).prop("disabled",false);
	   			}
	        }
	     });
	}
</script>