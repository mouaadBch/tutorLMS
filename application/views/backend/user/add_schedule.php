<style>
    #schedule .card {
        background-color: #f1f3fa;
    }
</style>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('add_schedule'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <h4 class="mb-3 header-title"><?php echo get_phrase('add_schedule'); ?>
                    </h4>

                    <form class="required-form" action="<?php echo site_url('addons/tutor_booking/schedule/add'); ?>" method="post" enctype="multipart/form-data">

                        <div id="schedule">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-lg-12">

                                                <div class="form-group">
                                                    <label for="topic"><?php echo get_phrase('tution_topic'); ?> <span class="required">*</span></label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="topic title" required>
                                                </div>

                                                <div class="form-group">
                                                    <select class="form-control select2" name="category_id" id="category_id" data-toggle="select2" data-allow-clear="true" onchange="categoryWiseStateSubcategory(this.value)" data-placeholder="<?php echo get_phrase('Category'); ?>">
                                                        <option value="0"><?php echo get_phrase('Select Category'); ?></option>
                                                        <?php foreach ($categories as $category) : ?>
                                                            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="sub_category_id" id="sub_category_id" class="form-control select2" data-toggle="select2" required>
                                                        <option value=""><?php echo get_phrase('sub_category'); ?></option>
                                                    </select>
                                                </div>

                                                <div class="form-group ">
                                                    <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="online" name="class_type" required value="1" onclick="show_meeting_link('yes')">
                                                    <label for="online"><?php echo get_phrase('online'); ?></label> &nbsp;
                                                    <input type="radio" id="in_person" name="class_type" value="2" onclick="show_meeting_link('no')">
                                                    <label for="in_person"><?php echo get_phrase('in_person'); ?></label>
                                                    &nbsp;
                                                    <input type="radio" id="online_in_person" name="class_type" value="3" onclick="show_meeting_link('yes')">
                                                    <label for="online_in_person"><?php echo get_phrase('online & in person'); ?></label>

                                                </div>

                                                <div class="form-group hidden" id="meeting_link_from">
                                                    <label for="meeting_link"><?php echo get_phrase('class_invitation_link'); ?> <span class="required">*</span></label>
                                                    <input type="url" name="meeting_link" id="meeting_link" class="form-control" placeholder="zoom invitation link" required>
                                                </div>

                                                <div class="form-group" id="message_from">
                                                    <label  class="form-label" for="messages"><?php echo get_phrase('messages'); ?> <span class="required">*</span></label>
                                                    <textarea  class="form-control" name="message" id="messages" required></textarea>
                                                </div>

                                                <div class="form-group ">
                                                    <label for="Price_type"><?php echo get_phrase('Price_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="fixed" name="price_type" required value="fixed">
                                                    <label for="fixed"><?php echo get_phrase('fixed'); ?></label> &nbsp;
                                                    <input type="radio" id="hourly" name="price_type" value="hourly">
                                                    <label for="hourly"><?php echo get_phrase('hourly'); ?></label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="price"><?php echo get_phrase('price'); ?> <span class="required">*</span></label>
                                                    <input type="number" name="price" id="price" min="0" step="any" class="form-control" placeholder="put a price" required>
                                                </div>

                                                <br>

                                                <div class="form-group" id='tution_type_1'>
                                                    <label for="tution_type_1"><?php echo get_phrase('tution_type'); ?><span class="required">*</span></label><br>
                                                    <input type="radio" id="single_1" name="1_tution_type_indentify" class="name_attr" required="required" onclick="show_weeks(this.id,'no')" value="1">
                                                    <label id="label_single_1" for="single_1"><?php echo get_phrase('Single_time'); ?></label> &nbsp;
                                                    <!--   <input type="radio" id="everyday_class_1" name="1_tution_type_indentify" onclick="show_weeks(this.id,'no')" value="7">
                                                      <label id='label_everyday_class_1' for="everyday_class_1"><?php // echo get_phrase('everyday'); 
                                                                                                                ?></label> &nbsp; -->
                                                    <input type="radio" id="selected_class_1" name="1_tution_type_indentify" onclick="show_weeks(this.id,'yes')" value="0">
                                                    <label id='label_selected_class_1' for="selected_class_1"><?php echo get_phrase('repeated days '); ?></label>
                                                </div>

                                                <div class="form-group hidden " id="week_list_1">
                                                    <div class="d-flex justify-content-between">
                                                        <div> <label for="Select_days"><?php echo get_phrase('Select Days'); ?><span class="required">* </span></label> </div>
                                                        <div> <label class="float-end" for="check_all_days"><?= get_phrase('Check_all ') ?></label>&nbsp;<input type="checkbox" id="check_all_days"></div>
                                                    </div>

                                                    <br>
                                                    <input type="checkbox" id="sunday_1" name="1_day[]" value="sunday">
                                                    <label id="label_sunday_1" for="sunday_1"><?php echo get_phrase('sunday'); ?></label> &nbsp;

                                                    <input type="checkbox" id="monday_1" name="1_day[]" value="monday">
                                                    <label id="label_monday_1" for="monday_1"><?php echo get_phrase('monday'); ?></label> &nbsp;

                                                    <input type="checkbox" id="tuesday_1" name="1_day[]" value="tuesday">
                                                    <label id="label_tuesday_1" for="tuesday_1"><?php echo get_phrase('tuesday'); ?></label>

                                                    <input type="checkbox" id="wednesday_1" name="1_day[]" value="wednesday">
                                                    <label id="label_wednesday_1" for="wednesday_1"><?php echo get_phrase('wednesday'); ?></label> &nbsp;

                                                    <input type="checkbox" id="thursday_1" name="1_day[]" value="thursday">
                                                    <label id="label_thursday_1" for="thursday_1"><?php echo get_phrase('thursday'); ?></label> &nbsp;

                                                    <input type="checkbox" id="friday_1" name="1_day[]" value="friday">
                                                    <label id="label_friday_1" for="friday_1"><?php echo get_phrase('friday'); ?></label>

                                                    <input type="checkbox" id="saturday_1" name="1_day[]" value="saturday">
                                                    <label id="label_saturday_1" for="saturday_1"><?php echo get_phrase('saturday'); ?></label>
                                                </div>

                                                <div class="">
                                                    <!-- <button type="button" class="btn btn-outline-success btn-sm float-right " onClick="add_schedule_div()">+</button> <br> -->
                                                    <label><?php echo get_phrase('Schedule Start Time'); ?></label>

                                                    <div class="input-group">
                                                        <input type="datetime-local" class="form-control" value="<?php echo strval(date('Y-m-d\TH:i')) ?>" id="start_time_1" name="start_time[]">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                        </div>
                                                    </div>
                                                </div><br>

                                                <div class="d-none" id="valid_till_div">
                                                    <label><?php echo get_phrase('Schedule End Time'); ?></label>

                                                    <div class="input-group">
                                                        <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="valid_till_1" name="valid_till[]">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo get_phrase('Class Duration'); ?></label>
                                                    <select required class="form-control select2" id="end_time_1" name="end_time[]" data-toggle="select2" data-allow-clear="true" data-placeholder="<?php echo get_phrase('duration'); ?> ">

                                                        <option value="0"><?php echo get_phrase('Select Duration'); ?> </option>


                                                        <option value="60"> 1:00 </option>
                                                        <option value="75"> 1:15 </option>
                                                        <option value="90"> 1:30 </option>
                                                        <option value="105"> 1:45 </option>
                                                        <option value="120"> 2:00 </option>
                                                        <option value="135"> 2:15 </option>
                                                        <option value="150"> 2:30 </option>
                                                        <option value="165"> 2:45 </option>
                                                        <option value="180"> 3:00 </option>

                                                    </select>
                                                </div>

                                                <div class="form-group hidden">
                                                    <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="group_class_1" name="class_type[]" value="group">
                                                    <label for="group_class"><?php echo get_phrase('Group'); ?></label> &nbsp;
                                                    <input type="radio" id="single_class_1" name="class_type[]" value="single">
                                                    <label for="single_class"><?php echo get_phrase('single'); ?></label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="schedule_input">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-lg-12">
                                                <button type="button" class="close" aria-label="Close" onclick="delete_schedule_div(this)">
                                                    <span aria-hidden="true">&times;</span>
                                                </button><br>

                                                <label><?php echo get_phrase('Start Time'); ?></label>
                                                <div class="input-group">
                                                    <input type="datetime-local" value="<?php echo strval(date('Y-m-d\TH:i')) ?>" class="form-control" id="start_time" name="start_time[]">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                    </div>
                                                </div> <br>




                                                <div class="form-group">
                                                    <label><?php echo get_phrase('End Time'); ?></label>
                                                    <select required class="form-control select2" id="end_time" name="end_time[]" data-allow-clear="true">

                                                        <option value="0"><?php echo get_phrase('Select Duration'); ?> </option>


                                                        <option value="60"> 1:00 </option>
                                                        <option value="75"> 1:15 </option>
                                                        <option value="90"> 1:30 </option>
                                                        <option value="105"> 1:45 </option>
                                                        <option value="120"> 2:00 </option>
                                                        <option value="135"> 2:15 </option>
                                                        <option value="150"> 2:30 </option>
                                                        <option value="165"> 2:45 </option>
                                                        <option value="180"> 3:00 </option>

                                                    </select>
                                                </div>


                                                <br>

                                                <div class="form-group hidden">
                                                    <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                      <input type="radio" id="group_class" name="class_type[]" value="group">
                                                      <label for="group_class"><?php echo get_phrase('Group'); ?></label> &nbsp;
                                                      <input type="radio" id="single_class" name="class_type[]" value="single">
                                                      <label for="single_class"><?php echo get_phrase('single'); ?></label>

                                                </div>
                                                <div class="form-group" id='tution_type'>
                                                    <label for="tution_type"><?php echo get_phrase('tution_type'); ?><span class="required">*</span></label> <br>
                                                      <input type="radio" id="single" name="_tution_type_indentify" class="name_attr" required="required" onclick="show_weeks(this.id,'no')" value="1">
                                                      <label id="label_single" for="single"><?php echo get_phrase('one_time'); ?></label> &nbsp;
                                                      <input type="radio" id="everyday_class" name="_tution_type_indentify" class="name_attr" onclick="show_weeks(this.id,'no')" value="7">
                                                      <label id="label_everyday_class" for="everyday_class"><?php echo get_phrase('everyday'); ?></label> &nbsp;
                                                      <input type="radio" id="selected_class" name="_tution_type_indentify" class="name_attr" onclick="show_weeks(this.id,'yes')" value="0">
                                                      <label id='label_selected_class' for="selected_class"><?php echo get_phrase('selected_days'); ?></label>
                                                </div>

                                                <div class="form-group hidden " id="week_list">
                                                    <label for="Select_days"><?php echo get_phrase('Select Days'); ?><span class="required">*</span></label><br>
                                                      <input type="checkbox" id="sunday" name="day[]" value="sunday">
                                                      <label id='label_sunday' for="sunday"><?php echo get_phrase('sunday'); ?></label> &nbsp;

                                                      <input type="checkbox" id="monday" name="day[]" value="monday">
                                                      <label id='label_monday' for="monday"><?php echo get_phrase('monday'); ?></label> &nbsp;

                                                      <input type="checkbox" id="tuesday" name="day[]" value="tuesday">
                                                      <label id='label_tuesday' for="tuesday"><?php echo get_phrase('tuesday'); ?></label>

                                                      <input type="checkbox" id="wednesday" name="day[]" value="wednesday">
                                                      <label id='label_wednesday' for="wednesday"><?php echo get_phrase('wednesday'); ?></label> &nbsp;

                                                      <input type="checkbox" id="thursday" name="day[]" value="thursday">
                                                      <label id='label_thursday' for="thursday"><?php echo get_phrase('thursday'); ?></label> &nbsp;

                                                      <input type="checkbox" id="friday" name="day[]" value="friday">
                                                      <label id='label_friday' for="friday"><?php echo get_phrase('friday'); ?></label>

                                                      <input type="checkbox" id="saturday" name="day[]" value="saturday">
                                                      <label id='label_saturday' for="saturday"><?php echo get_phrase('saturday'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- okay now -->

                        <button type="button" class="btn btn-primary" onclick="checkRequiredFields_schedules()"><?php echo get_phrase('save_Schedule'); ?></button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    function categoryWiseStateSubcategory(parent) {
        $.ajax({
            url: "<?php echo site_url('addons/tutor_booking/categoryWiseSubcategory/'); ?>" + parent,
            type: "GET",
            success: function(response) {
                $('#sub_category_id').html(response);
            }
        });
    }

    var start_time = 1;
    var end_time = 1;
    var class_type = 1; //hidden 
    var tution_type = 1;
    var schedule_count = 1;

    //schedule
    //schedule_input
    var blank_schedule = '';
    $(document).ready(function() {
        $('#schedule').show();
        $('#schedule_input').hide();
        blank_allowance = $('#schedule_input').html();
    });


    function add_schedule_div() {
        div_add();

        schedule_count++;

        $("#schedule").append(blank_allowance);
        initTimepicker();

        $('#single').attr('name', schedule_count + '_tution_type_indentify');
        $('#label_single').attr('for', 'single_' + schedule_count);
        $('#label_single').attr('id', 'label_single_' + schedule_count);
        $('#single').attr('id', 'single_' + schedule_count);

        $('#everyday_class').attr('name', schedule_count + '_tution_type_indentify');
        $('#label_everyday_class').attr('for', 'everyday_class_' + schedule_count);
        $('#label_everyday_class').attr('id', 'label_everyday_class_' + schedule_count);
        $('#everyday_class').attr('id', 'everyday_class_' + schedule_count);

        $('#selected_class').attr('name', schedule_count + '_tution_type_indentify');
        $('#label_selected_class').attr('for', 'selected_class_' + schedule_count);
        $('#label_selected_class').attr('id', 'label_selected_class_' + schedule_count);
        $('#selected_class').attr('id', 'selected_class_' + schedule_count);


        $('#sunday').attr('name', schedule_count + '_day[]');
        $('#monday').attr('name', schedule_count + '_day[]');
        $('#tuesday').attr('name', schedule_count + '_day[]');
        $('#wednesday').attr('name', schedule_count + '_day[]');
        $('#thursday').attr('name', schedule_count + '_day[]');
        $('#friday').attr('name', schedule_count + '_day[]');
        $('#saturday').attr('name', schedule_count + '_day[]');

        $('#start_time').attr('id', 'start_time_' + schedule_count);
        $('#end_time').attr('id', 'end_time_' + schedule_count);
        $('#class_type').attr('id', 'class_type_' + schedule_count);
        $('#tution_type').attr('id', 'tution_type_' + schedule_count);
        $('#week_list').attr('id', 'week_list_' + schedule_count);

        $('#sunday').attr('name', schedule_count + '_day[]');
        $('#label_sunday').attr('for', 'sunday_' + schedule_count);
        $('#label_sunday').attr('id', 'label_sunday_' + schedule_count);
        $('#sunday').attr('id', 'sunday_' + schedule_count);

        $('#monday').attr('name', schedule_count + '_day[]');
        $('#label_monday').attr('for', 'monday_' + schedule_count);
        $('#label_monday').attr('id', 'label_monday_' + schedule_count);
        $('#monday').attr('id', 'monday_' + schedule_count);

        $('#tuesday').attr('name', schedule_count + '_day[]');
        $('#label_tuesday').attr('for', 'tuesday_' + schedule_count);
        $('#label_tuesday').attr('id', 'label_tuesday_' + schedule_count);
        $('#tuesday').attr('id', 'tuesday_' + schedule_count);

        $('#wednesday').attr('name', schedule_count + '_day[]');
        $('#label_wednesday').attr('for', 'wednesday_' + schedule_count);
        $('#label_wednesday').attr('id', 'label_wednesday_' + schedule_count);
        $('#wednesday').attr('id', 'wednesday_' + schedule_count);

        $('#thursday').attr('name', schedule_count + '_day[]');
        $('#label_thursday').attr('for', 'thursday_' + schedule_count);
        $('#label_thursday').attr('id', 'label_thursday_' + schedule_count);
        $('#thursday').attr('id', 'thursday_' + schedule_count);

        $('#friday').attr('name', schedule_count + '_day[]');
        $('#label_friday').attr('for', 'friday_' + schedule_count);
        $('#label_friday').attr('id', 'label_friday_' + schedule_count);
        $('#friday').attr('id', 'friday_' + schedule_count);

        $('#saturday').attr('name', schedule_count + '_day[]');
        $('#label_saturday').attr('for', 'saturday_' + schedule_count);
        $('#label_saturday').attr('id', 'label_saturday_' + schedule_count);
        $('#saturday').attr('id', 'saturday_' + schedule_count);
    }

    function delete_schedule_div(n) {
        div_remove();
        n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
    }

    function show_meeting_link(status) {
        if (status == 'yes') {
            $('#meeting_link_from').removeClass('hidden');
            $('#meeting_link').attr('required',true);
        } else {
            $('#meeting_link_from').addClass('hidden');
            $('#meeting_link').attr('required',false);
            $('#messages').attr('required',true);
        }
    }

    function show_weeks(id, status) {
        var id = id;
        var week_list = '#week_list_';
        var current_id = week_list.concat(id.charAt(id.length - 1));

        if (status == 'yes') {
            $(current_id).removeClass('hidden');
        } else {
            $(current_id).addClass('hidden');
            //  $("#week_list").empty();

        }
    }



    function checkRequiredFields_schedules() {
        var pass = 1;
        var check = 0;
        $('form.required-form').find('input, select, radio').each(function() {
            if ($(this).prop('required')) {
                if ($(this).val() === "") {
                    pass = 0;
                }
            }
        });


        if ($('#selected_class_1').is(':checked')) {
            if ($('#sunday_1').is(':checked') || $('#monday_1').is(':checked') || $('#tuesday_1').is(':checked') || $('#wednesday_1').is(':checked') || $('#thursday_1').is(':checked') || $('#friday_1').is(':checked') || $('#saturday_1').is(':checked')) {

                pass = 1;
            } else {
                pass = 0;
            }


        }






        if (!$("input[name=price_type]").is(":checked")) {
            pass = 0;
        }


        if (!$("input[name=class_type]").is(":checked")) {
            pass = 0;
        }

        var duration = $('#end_time_1').val();

        if (duration == 0) {
            pass = 0;

        }





        if (pass === 1) {
            $('form.required-form').submit();
        } else {
            //error_required_field();
            checkbox_error();
        }


    }

    function checkbox_error() {
        $.NotificationApp.send("<?php echo get_phrase('Required field'); ?>!", '<?php echo get_phrase('you can not keep any field empty') ?>', "top-right", "rgba(0,0,0,0.2)", "error");

    }


    $('#single_1').on('change', function() {
        var checked = this.checked
        if (checked) {
            $("#valid_till_div").addClass("d-none");

        }


    });

    $('#selected_class_1').on('change', function() {
        var checked = this.checked
        if (checked) {
            $("#valid_till_div").removeClass("d-none");
        }


    });

    $('#check_all_days').on('change', function() {
        var checked = this.checked


        if (checked == true) {
            $('#sunday_1').prop('checked', true);
            $('#monday_1').prop('checked', true);
            $('#tuesday_1').prop('checked', true);
            $('#wednesday_1').prop('checked', true);
            $('#thursday_1').prop('checked', true);
            $('#friday_1').prop('checked', true);
            $('#saturday_1').prop('checked', true);
        }

        if (checked == false) {
            $('#sunday_1').prop('checked', false);
            $('#monday_1').prop('checked', false);
            $('#tuesday_1').prop('checked', false);
            $('#wednesday_1').prop('checked', false);
            $('#thursday_1').prop('checked', false);
            $('#friday_1').prop('checked', false);
            $('#saturday_1').prop('checked', false);
        }
    });




    $('#sunday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
    $('#monday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
    $('#tuesday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
    $('#wednesday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
    $('#thursday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
    $('#friday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
    $('#saturday_1').on('change', function() {
        var checked = this.checked

        if (checked == false) {
            $('#check_all_days').prop('checked', false);
        }

    });
</script>