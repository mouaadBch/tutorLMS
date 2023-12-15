<script type="text/javascript">
    "use strict";

    function load_analytics_chart (){
        var check_loaded_data = $('#load_analytics_body').html();
        if(check_loaded_data == ""){
            $('.ajax_loaderBar').addClass('start_ajax_loading');
            $.ajax({
                url:"<?php echo site_url('addons/course_analytics/load_analytics_body/'.$course_details['id']); ?>",
                success: function(response){
                    $('#load_analytics_body').html(response);
                    load_analytics_chart_of_enrolments();
                }
            });
        }
    }

    function load_analytics_chart_of_enrolments (){
        $.ajax({
            url:"<?php echo site_url('addons/course_analytics/get_course_enrolment_data/'.$course_details['id']); ?>",
            success: function(response){
                $('#course_enrolment_analytics_body').html(response);
                $('.ajax_loaderBar').removeClass('start_ajax_loading');
            }
        });
    }

    function filter_enrolment_analytics(){
        $('.ajax_loaderBar').addClass('start_ajax_loading');
        var date_for_filter_chart = $('#date_for_filter_chart').val();
        $.ajax({
            type: 'post',
            url:"<?php echo site_url('addons/course_analytics/get_course_enrolment_data/'.$course_details['id']); ?>",
            data:{filter_date:date_for_filter_chart},
            success: function(response){
                $('#course_enrolment_analytics_body').html(response);
                $('.ajax_loaderBar').removeClass('start_ajax_loading');
            }
        });
    }
</script>