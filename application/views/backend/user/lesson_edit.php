<?php
// $param2 = lesson id and $param3 = course id
$lesson_details = $this->crud_model->get_lessons('lesson', $param2)->row_array();
$sections = $this->crud_model->get_section('course', $param3)->result_array();
?>
<!-- SHOWING THE LESSON TYPE IN AN ALERT VIEW -->
<div class="alert alert-info" role="alert">
    <?php echo get_phrase("lesson_type"); ?> :
    <strong>
        <?php
        if ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'html5') {
            echo get_phrase("video_url").' [.mp4]';
        }elseif ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'system') {
            echo get_phrase("video_file");
        }elseif ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'youtube') {
            echo 'YouTube '.get_phrase("video");
        }elseif ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'vimeo') {
            echo 'Vimeo '.get_phrase("video");
        }elseif ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'amazon') {
            echo 'Amazon S3';
        }elseif($lesson_details['lesson_type'] == 'other' && strtolower($lesson_details['attachment_type']) == 'doc' || strtolower($lesson_details['attachment_type']) == 'pdf' || strtolower($lesson_details['attachment_type']) == 'txt'){
            echo get_phrase('document');
        }elseif($lesson_details['lesson_type'] == 'other' && strtolower($lesson_details['attachment_type']) == 'img'){
            echo get_phrase('image');
        }elseif($lesson_details['lesson_type'] == 'other' && strtolower($lesson_details['attachment_type']) == 'iframe'){
            echo get_phrase('iframe');
        }
        ?>.
    </strong>
</div>

<!-- ACTUAL LESSON ADDING FORM -->
<form class="ajaxFormSubmission" action="<?php echo site_url('user/lessons/'.$param3.'/edit'.'/'.$param2); ?>" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label><?php echo get_phrase('title'); ?></label>
        <input type="text" name = "title" class="form-control" required value="<?php echo $lesson_details['title']; ?>">
    </div>

    <input type="hidden" name="course_id" value="<?php echo $param3; ?>">

    <div class="form-group">
        <label for="section_id"><?php echo get_phrase('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>" <?php if($lesson_details['section_id'] == $section['id']) echo 'selected'; ?>><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'youtube'): include('youtube_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'vimeo'): include('vimeo_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'html5'): include('html5_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'system'): include('video_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'video' && strtolower($lesson_details['video_type']) == 'amazon' && addon_status('amazon-s3')): include('amazon_s3_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'other' && strtolower($lesson_details['attachment_type']) == 'doc' || strtolower($lesson_details['attachment_type']) == 'pdf' || strtolower($lesson_details['attachment_type']) == 'txt'): include('document_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'other' && strtolower($lesson_details['attachment_type']) == 'img'): include('image_type_lesson_edit.php'); endif; ?>
    <?php if ($lesson_details['lesson_type'] == 'other' && strtolower($lesson_details['attachment_type']) == 'iframe'): include('iframe_type_lesson_edit.php'); endif; ?>

    <div class="form-group">
        <label><?php echo get_phrase('summary'); ?></label>
        <textarea name="summary"  id="summary" class="form-control"><?php echo $lesson_details['summary']; ?></textarea>
    </div>

    <div class="text-center">
        <button class = "btn btn-success formSubmissionBtn" type="submit" name="button"><?php echo get_phrase('update_lesson'); ?></button>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
    /* code mouaad  */
    initSummerNote(['#summary']);
    /* code mouaad */
    initSelect2(['#section_id','#lesson_type', '#lesson_provider', '#lesson_provider_for_mobile_application']);
    initTimepicker();

    // HIDING THE SEARCHBOX FROM SELECT2
    $('select').select2({
        minimumResultsForSearch: -1
    });
});

function ajax_get_video_details(video_url) {
    $('#perloader').show();
    if(checkURLValidity(video_url)){
        $.ajax({
            url: '<?php echo site_url('user/ajax_get_video_details');?>',
            type : 'POST',
            data : {video_url : video_url},
            success: function(response)
            {
                jQuery('#duration').val(response);
                $('#perloader').hide();
                $('#invalid_url').hide();
            }
        });
    }else {
        $('#invalid_url').show();
        $('#perloader').hide();
        jQuery('#duration').val('');

    }
}

function checkURLValidity(video_url) {
    var youtubePregMatch = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    var vimeoPregMatch = /^(http\:\/\/|https\:\/\/)?(www\.)?(vimeo\.com\/)([0-9]+)$/;
    if (video_url.match(youtubePregMatch)) {
        return true;
    }
    else if (vimeoPregMatch.test(video_url)) {
        return true;
    }
    else {
        return false;
    }
}

$(function() {
    var formSubmissionBtn = $('.formSubmissionBtn');
    var formSubmissionBtnTxt = $(formSubmissionBtn).html();
    //The form of submission to RailTeam js is defined here.(Form class or ID)
    $('.ajaxFormSubmission').ajaxForm({
        beforeSend: function() {
            var percentVal = '0%';
            $(formSubmissionBtn).html('<?php echo get_phrase('uploading'); ?>... '+percentVal);
            $(formSubmissionBtn).attr('disabled', true);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            if(percentComplete < 100){
                $(formSubmissionBtn).html('<?php echo get_phrase('uploading'); ?>... '+percentVal);
            }else{
                $(formSubmissionBtn).html('<?php echo get_phrase('please_wait'); ?>... '+percentVal);
            }
        },
        complete: function(xhr) {
            var response = xhr.responseText;
            console.log(response);

            setTimeout(function(){

                if (response) {
                    response = JSON.parse(response);

                    if(typeof response.error != "undefined"){
                        error_notify(response.error);
                        $(formSubmissionBtn).attr('disabled', false);
                        $(formSubmissionBtn).html(formSubmissionBtnTxt);
                    }

                    if(typeof response.success != "undefined"){
                        success_notify(response.success);
                    }

                    if(typeof response.redirect != "undefined"){
                        window.location.href = response.redirect;
                    }

                    if(typeof response.reload != "undefined"){
                        location.reload();
                    }
                }

                //set_js_flashdata('<?php echo site_url('home/set_flashdata_for_js/flash_message/your_video_file_uploaded_succesfully') ?>');
            }, 500);
        },
        error: function()
        {
            //You can write here your js error message
        }
    });
});
</script>
