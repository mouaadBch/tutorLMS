<style>
    #schedule .card {
        background-color: #f1f3fa;
    }

    .s_list .card {
        background-color: #f1f3fa;
    }
</style>
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <h4 class="mb-3 header-title"><?php echo get_phrase('Eidt_schedule'); ?>
                    </h4>

                    <form class="required-form" action="<?php echo site_url('addons/tutor_booking/schedule/edit'); ?>" method="post" enctype="multipart/form-data">

                        <div id="schedule">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="col-lg-12">

                                                <div class="form-group">
                                                    <label for="topic"><?php echo get_phrase('tution_topic'); ?> <span class="required">*</span></label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="topic title" value="<?php echo $booking_details['title']  ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <select class="form-control select2" name="category_id" id="category_id" data-toggle="select2" data-allow-clear="true" onchange="categoryWiseStateSubcategory(this.value)" data-placeholder="<?php echo get_phrase('Category'); ?>">


                                                        <?php foreach ($categories as $category) : ?>
                                                            <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $booking_details['category_id']) echo 'selected'; ?>><?php echo $category['name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="sub_category_id" id="sub_category_id" class="form-control select2" data-toggle="select2" data-bs-toggle="select2" required>
                                                        <?php if ($booking_details['sub_category_id'] != 0) :
                                                            $sub_category_name = $this->db->get_where('tutor_category', array('id' => $booking_details['sub_category_id']))->row_array(); ?>
                                                            <option value="<?php echo $booking_details['sub_category_id']  ?>"><?php echo get_phrase($sub_category_name['name']); ?></option>

                                                        <?php else : ?>

                                                            <option value="0"><?php echo get_phrase('no category found'); ?></option>

                                                        <?php endif; ?>

                                                    </select>
                                                </div>

                                                <input type="hidden" name="booking_id" value="<?php echo $booking_details['id']  ?>">
                                                <input type="hidden" name="tutor_id" value="<?php echo $booking_details['tutor_id']  ?>">


                                                <?php foreach ($schedule_ids as $s_id) : ?>
                                                    <input type="hidden" id="schedule_ids" name="schedule_ids[]" value="<?php echo $s_id ?>">
                                                <?php endforeach; ?>

                                                <div class="form-group ">
                                                    <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="online" name="class_type" <?php if ($booking_details['tution_class_type'] == '1') echo 'checked'; ?> required value="1" onclick="show_meeting_link('yes')">
                                                    <label for="online"><?php echo get_phrase('online'); ?></label> &nbsp;
                                                    <input type="radio" id="in_person" name="class_type" <?php if ($booking_details['tution_class_type'] == '2') echo 'checked'; ?> value="2" onclick="show_meeting_link('no')">
                                                    <label for="in_person"><?php echo get_phrase('in_person'); ?></label>
                                                    &nbsp;
                                                    <input type="radio" id="online_in_person" name="class_type" value="3" <?php if ($booking_details['tution_class_type'] == '3') echo 'checked'; ?> onclick="show_meeting_link('yes')">
                                                    <label for="online_in_person"><?php echo get_phrase('online & in person'); ?></label>
                                                </div>

                                                <div class="form-group <?php echo (($booking_details['tution_class_type'] == '1')&&($booking_details['tution_class_type'] == '3')) ? '' : 'hidden' ?>" id="meeting_link_from">
                                                    <label for="meeting_link"><?php echo get_phrase('class_invitation_link'); ?> <span class="required">*</span></label>
                                                    <input type="url" value="<?= $booking_details['meeting_link'] ?? '' ?>" name="meeting_link" id="meeting_link" class="form-control" placeholder="zoom invitation link">
                                                </div>

                                                <div class="form-group" id="message_from">
                                                    <label class="form-label" for="messages"><?php echo get_phrase('messages'); ?> <span class="required">*</span></label>
                                                    <textarea class="form-control" name="message" id="messages"><?= $booking_details['message'] ?? '' ?></textarea>
                                                </div>

                                                <div class="form-group ">
                                                    <label for="Price_type"><?php echo get_phrase('Price_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="fixed" name="price_type" required="required" <?php if ($booking_details['price_type'] == 'fixed') echo 'checked'; ?> value="fixed">
                                                    <label for="fixed"><?php echo get_phrase('fixed'); ?></label> &nbsp;
                                                    <input type="radio" id="hourly" name="price_type" <?php if ($booking_details['price_type'] == 'hourly') echo 'checked'; ?> value="hourly">
                                                    <label for="hourly"><?php echo get_phrase('hourly'); ?></label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="price"><?php echo get_phrase('price'); ?> <span class="required">*</span></label>
                                                    <input type="number" name="price" id="price" min="0" step="any" class="form-control" placeholder="put a price" value="<?php echo $booking_details['price']; ?>" required>
                                                </div>

                                                <?php $created_schedules = $schedule_details->num_rows();
                                                    $schedule_details = $schedule_details->result_array();
                                                if ($created_schedules > 0) :
                                                    ?>

                                                    <div class="mt-5 botder-top">
                                                        <label><?php echo get_phrase('Start Time'); ?></label>

                                                        <div class="input-group">
                                                            <input type="datetime-local" value="<?php echo strval(date('Y-m-d\TH:i', (int)$schedule_details[0]['start_time'])); ?>" class="form-control" id="start_time_1" value="<?php echo  date('h:i A', (int)$schedule_details[0]['start_time']); ?>" name="start_time[]">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                            </div>
                                                        </div>
                                                    </div> <br>



                                                    <?php

                                                    $t1 = $schedule_details[0]['start_time'];
                                                    $t2 = $schedule_details[0]['end_time'];
                                                    $t3 = $t2 - $t1;
                                                    $t3 = $t3 / 60;

                                                    ?>

                                                    <div class="form-group">
                                                        <label><?php echo get_phrase('End Time'); ?></label>
                                                        <select required class="form-control select2" id="end_time_1" name="end_time[]" data-toggle="select2" data-allow-clear="true" data-placeholder="<?php echo get_phrase('duration'); ?> ">

                                                            <option value="0"><?php echo get_phrase('Select Duration'); ?> </option>

                                                            <option value="60" <?php if ($t3 == 60) echo 'selected'  ?>> 1:00 </option>
                                                            <option value="75" <?php if ($t3 == 75) echo 'selected'  ?>> 1:15 </option>
                                                            <option value="90" <?php if ($t3 == 90) echo 'selected'  ?>> 1:30 </option>
                                                            <option value="105" <?php if ($t3 == 105) echo 'selected'  ?>> 1:45 </option>
                                                            <option value="120" <?php if ($t3 == 120) echo 'selected'  ?>> 2:00 </option>
                                                            <option value="135" <?php if ($t3 == 135) echo 'selected'  ?>> 2:15 </option>
                                                            <option value="150" <?php if ($t3 == 150) echo 'selected'  ?>> 2:30 </option>
                                                            <option value="165" <?php if ($t3 == 165) echo 'selected'  ?>> 2:45 </option>
                                                            <option value="180" <?php if ($t3 == 180) echo 'selected'  ?>> 3:00 </option>

                                                        </select>
                                                    </div>

                                                    <!-- <div class="form-group">
                                                        <label for="meeting_link"><?php #echo get_phrase('class_invitation_link'); 
                                                                                    ?> <span class="required">*</span></label>
                                                        <input type="url" name="meeting_link" id="meeting_link" class="form-control" placeholder="zoom invitation link" value="<?php #echo $booking_details['meeting_link']  
                                                                                                                                                                                ?>" required>
                                                    </div> -->


                                                    <br>




                                                    <div class="form-group hidden">
                                                        <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                        <input type="radio" id="group_class_1" name="class_type[]" value="group">
                                                        <label for="group_class"><?php echo get_phrase('Group'); ?></label> &nbsp;
                                                        <input type="radio" id="single_class_1" name="class_type[]" value="single">
                                                        <label for="single_class"><?php echo get_phrase('single'); ?></label>

                                                    </div>
                                                    <div class="form-group d-none  " id='tution_type_1'>
                                                        <label for="tution_type_1"><?php echo get_phrase('tution_type'); ?><span class="required">*</span></label><br>
                                                        <input type="radio" id="single_1" name="1_tution_type_indentify" required="required" onclick="show_weeks(this.id,'no')" <?php if ($schedule_details[0]['tuition_type'] == 1) echo 'checked'; ?> value="1">
                                                        <label id='label_single_1' for="single_1"><?php echo get_phrase('one_time'); ?></label> &nbsp;
                                                        <input type="radio" id="everyday_class_1" name="1_tution_type_indentify" onclick="show_weeks(this.id,'no')" <?php if ($schedule_details[0]['tuition_type'] == 7) echo 'checked'; ?> value="7">
                                                        <label id='label_everyday_class_1' for="everyday_class_1"><?php echo get_phrase('everyday'); ?></label> &nbsp;
                                                        <input type="radio" id="selected_class_1" name="1_tution_type_indentify" onclick="show_weeks(this.id,'yes')" value="0" <?php if ($schedule_details[0]['tuition_type'] == 0) echo 'checked'; ?>>
                                                        <label id='label_selected_class_1' for="selected_class_1"><?php echo get_phrase('selected_days'); ?></label>
                                                    </div>

                                                    <input type="hidden" name="schedule_id_1" value="<?php echo $schedule_details[0]['id'] ?>">

                                                    <?php if ($schedule_details[0]['tuition_type'] == 0) :
                                                        $days = json_decode($schedule_details[0]['selected_days']);  ?>


                                                        <div class="form-group d-none " id="week_list_1">
                                                            <label for="Select_days"><?php echo get_phrase('Select Days'); ?><span class="required">*</span></label><br>
                                                            <input type="checkbox" id="sunday_1" name="1_day[]" <?php if (in_array("sunday", $days)) echo 'checked'; ?> value="sunday">
                                                            <label id="label_sunday_1" for="sunday_1"><?php echo get_phrase('sunday'); ?></label> &nbsp;

                                                            <input type="checkbox" id="monday_1" name="1_day[]" <?php if (in_array("monday", $days)) echo 'checked'; ?> value="monday">
                                                            <label id="label_monday_1" for="monday_1"><?php echo get_phrase('monday'); ?></label> &nbsp;

                                                            <input type="checkbox" id="tuesday_1" name="1_day[]" <?php if (in_array("tuesday", $days)) echo 'checked'; ?> value="tuesday">
                                                            <label id="label_tuesday_1" for="tuesday_1"><?php echo get_phrase('tuesday'); ?></label>

                                                            <input type="checkbox" id="wednesday_1" name="1_day[]" <?php if (in_array("wednesday", $days)) echo 'checked'; ?> value="wednesday">
                                                            <label id="label_wednesday_1" for="wednesday_1"><?php echo get_phrase('wednesday'); ?></label> &nbsp;

                                                            <input type="checkbox" id="thursday_1" name="1_day[]" <?php if (in_array("thursday", $days)) echo 'checked'; ?> value="thursday">
                                                            <label id="label_thursday_1" for="thursday_1"><?php echo get_phrase('thursday'); ?></label> &nbsp;

                                                            <input type="checkbox" id="friday_1" name="1_day[]" <?php if (in_array("friday", $days)) echo 'checked'; ?> value="friday">
                                                            <label id="label_friday_1" for="friday_1"><?php echo get_phrase('friday'); ?></label>

                                                            <input type="checkbox" id="saturday_1" name="1_day[]" <?php if (in_array("saturday", $days)) echo 'checked'; ?> value="saturday">
                                                            <label id="label_saturday_1" for="saturday_1"><?php echo get_phrase('saturday'); ?></label>
                                                        </div>

                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $num_of_schedules = $created_schedules;


                        $e = 2;
                        if ($num_of_schedules >= 0) :
                            for ($i = 1; $i <= $num_of_schedules - 1; $i++) {
                            ?>

                                <div class="s_list" id="schedule_input_edit_<?= $e ?>">


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-lg-12">
                                                        <button type="button" class="close" aria-label="Close" onclick="delete_schedule_div(this,'<?= $e ?>')">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button><br>

                                                        <label><?php echo get_phrase('Start Time'); ?></label>
                                                        <div class="input-group">
                                                            <input type="datetime-local" class="form-control" value="<?php echo strval(date('Y-m-d\TH:i', (int)$schedule_details[$i]['start_time'])); ?>" id="start_time_<?= $e ?>" name="start_time[]" value="<?php echo  date('h:i A', (int)$schedule_details[$i]['start_time']); ?>">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                            </div>
                                                        </div> <br>




                                                        <?php

                                                        $t1 = $schedule_details[$i]['start_time'];
                                                        $t2 = $schedule_details[$i]['end_time'];
                                                        $t3 = $t2 - $t1;
                                                        $t3 = $t3 / 60;

                                                        ?>

                                                        <div class="form-group">
                                                            <label><?php echo get_phrase('End Time'); ?></label>
                                                            <select required class="form-control select2" id="end_time_<?= $e ?>" name="end_time[]" data-toggle="select2" data-allow-clear="true" data-placeholder="<?php echo get_phrase('duration'); ?> ">

                                                                <option value="0"><?php echo get_phrase('Select Duration'); ?> </option>


                                                                <option value="60" <?php if ($t3 == 60) echo 'selected'  ?>> 1:00 </option>
                                                                <option value="75" <?php if ($t3 == 75) echo 'selected'  ?>> 1:15 </option>
                                                                <option value="90" <?php if ($t3 == 90) echo 'selected'  ?>> 1:30 </option>
                                                                <option value="105" <?php if ($t3 == 105) echo 'selected'  ?>> 1:45 </option>
                                                                <option value="120" <?php if ($t3 == 120) echo 'selected'  ?>> 2:00 </option>
                                                                <option value="135" <?php if ($t3 == 135) echo 'selected'  ?>> 2:15 </option>
                                                                <option value="150" <?php if ($t3 == 150) echo 'selected'  ?>> 2:30 </option>
                                                                <option value="165" <?php if ($t3 == 165) echo 'selected'  ?>> 2:45 </option>
                                                                <option value="180" <?php if ($t3 == 180) echo 'selected'  ?>> 3:00 </option>

                                                            </select>
                                                        </div>



                                                        <br>

                                                        <div class="form-group  hidden">
                                                            <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                            <input type="radio" id="group_class" name="class_type[]" value="group">
                                                            <label for="group_class"><?php echo get_phrase('Group'); ?></label> &nbsp;
                                                            <input type="radio" id="single_class" name="class_type[]" value="single">
                                                            <label for="single_class"><?php echo get_phrase('single'); ?></label>

                                                        </div>
                                                        <?php $name = $e . "_tution_type_indentify" ?>
                                                        <div class="form-group d-none  " id='tution_type_<?= $e ?>'>
                                                            <label for="tution_type_<?= $e ?>"><?php echo get_phrase('tution_type'); ?><span class="required">*</span></label> <br>
                                                            <input type="radio" id="single_<?= $e ?>" name="<?php echo $name ?>" required="required" class="name_attr" onclick="show_weeks(this.id,'no')" <?php if ($schedule_details[$i]['tuition_type'] == 1) echo 'checked'; ?> value="1">
                                                            <label for="single_<?= $e ?>" id="label_single_<?= $e ?>"><?php echo get_phrase('one_time'); ?></label> &nbsp;
                                                            <input type="radio" id="everyday_class_<?= $e ?>" name="<?php echo $name ?>" class="name_attr" onclick="show_weeks(this.id,'no')" <?php if ($schedule_details[$i]['tuition_type'] == 7) echo 'checked'; ?> value="7">
                                                            <label for="everyday_class_<?= $e ?>" id="label_everyday_class_<?= $e ?>"><?php echo get_phrase('everyday'); ?></label> &nbsp;
                                                            <input type="radio" id="selected_class_<?= $e ?>" name="<?php echo $name ?>" class="name_attr" onclick="show_weeks(this.id,'yes')" <?php if ($schedule_details[$i]['tuition_type'] == 0) echo 'checked'; ?> value="0">
                                                            <label for="selected_class_<?= $e ?>" id='label_selected_class_<?= $e ?>'><?php echo get_phrase('selected_days'); ?></label>
                                                        </div>

                                                        <input type="hidden" name="schedule_id_<?= $e ?>" value="<?php echo $schedule_details[$i]['id'] ?>">

                                                        <?php if ($schedule_details[$i]['tuition_type'] == 0) :
                                                            $days = json_decode($schedule_details[$i]['selected_days']);  ?>

                                                            <?php $day_name = $e . "_day[]" ?>
                                                            <div class="form-group d-none  hidden " id="week_list_<?= $e ?>">
                                                                <label for="Select_days"><?php echo get_phrase('Select Days'); ?><span class="required">*</span></label><br>
                                                                <input type="checkbox" id="sunday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("sunday", $days)) echo 'checked'; ?> value="sunday">
                                                                <label id='label_sunday_<?= $e ?>' for="sunday_<?= $e ?>"><?php echo get_phrase('sunday'); ?></label> &nbsp;

                                                                <input type="checkbox" id="monday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("monday", $days)) echo 'checked'; ?> value="monday">
                                                                <label id='label_monday_<?= $e ?>' for="monday_<?= $e ?>"><?php echo get_phrase('monday'); ?></label> &nbsp;

                                                                <input type="checkbox" id="tuesday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("tuesday", $days)) echo 'checked'; ?> value="tuesday">
                                                                <label id='label_tuesday_<?= $e ?>' for="tuesday_<?= $e ?>"><?php echo get_phrase('tuesday'); ?></label>

                                                                <input type="checkbox" id="wednesday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("wednesday", $days)) echo 'checked'; ?> value="wednesday">
                                                                <label id='label_wednesday_<?= $e ?>' for="wednesday_<?= $e ?>"><?php echo get_phrase('wednesday'); ?></label> &nbsp;

                                                                <input type="checkbox" id="thursday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("thursday", $days)) echo 'checked'; ?> value="thursday">
                                                                <label id='label_thursday_<?= $e ?>' for="thursday_<?= $e ?>"><?php echo get_phrase('thursday'); ?></label> &nbsp;

                                                                <input type="checkbox" id="friday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("friday", $days)) echo 'checked'; ?> value="friday">
                                                                <label id='label_friday_<?= $e ?>' for="friday_<?= $e ?>"><?php echo get_phrase('friday'); ?></label>

                                                                <input type="checkbox" id="saturday_<?= $e ?>" name="<?php echo $day_name ?>" <?php if (in_array("saturday", $days)) echo 'checked'; ?> value="saturday">
                                                                <label id='label_saturday_<?= $e ?>' for="saturday_<?= $e ?>"><?php echo get_phrase('saturday'); ?></label>
                                                            </div>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <?php $e++ ?>

                            <?php } ?>

                        <?php endif; ?>

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
                                                    <input type="datetime-local" class="form-control" value="<?php echo strval(date('Y-m-d\TH:i')) ?>" id="start_time" name="start_time[]">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                    </div>
                                                </div> <br>

                                                <div class="form-group">
                                                    <label><?php echo get_phrase('End Time'); ?></label>
                                                    <select required class="form-control select2" id="end_time" name="end_time[]" data-allow-clear="true" data-placeholder="<?php echo get_phrase('duration'); ?> ">

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

                                                <div class="form-group  hidden">
                                                    <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="group_class" name="class_type[]" value="group">
                                                    <label for="group_class"><?php echo get_phrase('Group'); ?></label> &nbsp;
                                                    <input type="radio" id="single_class" name="class_type[]" value="single">
                                                    <label for="single_class"><?php echo get_phrase('single'); ?></label>

                                                </div>
                                                <div class="form-group d-none " id='tution_type'>
                                                    <label for="tution_type"><?php echo get_phrase('tution_type'); ?><span class="required">*</span></label> <br>
                                                    <input type="radio" id="single" name="_tution_type_indentify" required="required" class="name_attr" onclick="show_weeks(this.id,'no')" value="1">
                                                    <label id="label_single" for="single"><?php echo get_phrase('one_time'); ?></label> &nbsp;
                                                    <input type="radio" id="everyday_class" name="_tution_type_indentify" class="name_attr" onclick="show_weeks(this.id,'no')" value="7">
                                                    <label id="label_everyday_class" for="everyday_class"><?php echo get_phrase('everyday'); ?></label> &nbsp;
                                                    <input type="radio" id="selected_class" name="_tution_type_indentify" class="name_attr" onclick="show_weeks(this.id,'yes')" value="0">
                                                    <label id='label_selected_class' for="selected_class"><?php echo get_phrase('selected_days'); ?></label>
                                                </div>

                                                <input type="hidden" id="schedule_id" name="schedule_id" value="0">

                                                <div class="form-group d-none  hidden " id="week_list">
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



                        <button type="button" class="btn btn-primary" onclick="checkRequiredFields_schedules()"><?php echo get_phrase('update_Schedule'); ?></button>

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

    var no_of_schedules = '<?php echo $no_of_schedules; ?>';

    var start_time = no_of_schedules;
    var end_time = no_of_schedules;
    var class_type = no_of_schedules; //hidden 
    var tution_type = no_of_schedules;
    var schedule_count = no_of_schedules;
    var entry_to_if = no_of_schedules;
    var deleted_div_track = [];


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

        one_time = document.getElementById("single_1").checked;
        everyday = document.getElementById("everyday_class_1").checked;
        selected_day = document.getElementById("selected_class_1").checked;

        sunday = document.getElementById("sunday_1").checked;
        monday = document.getElementById("monday_1").checked;
        tuesday = document.getElementById("tuesday_1").checked;
        wednesday = document.getElementById("wednesday_1").checked;
        thursday = document.getElementById("thursday_1").checked;
        friday = document.getElementById("friday_1").checked;
        saturday = document.getElementById("saturday_1").checked;

        schedule_count++;
        last_edit_div_number = schedule_count - 1;

        if (entry_to_if == 1) {
            $('#schedule').append(blank_allowance);
        } else {
            $('#schedule_input_edit_' + last_edit_div_number).append(blank_allowance);
        }

        initTimepicker();

        $('#schedule_id').attr('id', 'schedule_id_' + schedule_count);
        $('#schedule_id').attr('name', 'schedule_id_' + schedule_count);

        $('#single').attr('name', schedule_count + '_tution_type_indentify');
        $('#label_single').attr('for', 'single_' + schedule_count);
        $('#label_single').attr('id', 'label_single_' + schedule_count);
        $('#single').attr('id', 'single_' + schedule_count);

        if (one_time) {
            temp_id = '#single_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#everyday_class').attr('name', schedule_count + '_tution_type_indentify');
        $('#label_everyday_class').attr('for', 'everyday_class_' + schedule_count);
        $('#label_everyday_class').attr('id', 'label_everyday_class_' + schedule_count);
        $('#everyday_class').attr('id', 'everyday_class_' + schedule_count);

        if (everyday) {
            temp_id = '#everyday_class_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#selected_class').attr('name', schedule_count + '_tution_type_indentify');
        $('#label_selected_class').attr('for', 'selected_class_' + schedule_count);
        $('#label_selected_class').attr('id', 'label_selected_class_' + schedule_count);
        $('#selected_class').attr('id', 'selected_class_' + schedule_count);


        if (selected_day) {
            temp_id = '#selected_class_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

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

        if (sunday) {
            temp_id = '#sunday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#monday').attr('name', schedule_count + '_day[]');
        $('#label_monday').attr('for', 'monday_' + schedule_count);
        $('#label_monday').attr('id', 'label_monday_' + schedule_count);
        $('#monday').attr('id', 'monday_' + schedule_count);

        if (monday) {
            temp_id = '#monday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#tuesday').attr('name', schedule_count + '_day[]');
        $('#label_tuesday').attr('for', 'tuesday_' + schedule_count);
        $('#label_tuesday').attr('id', 'label_tuesday_' + schedule_count);
        $('#tuesday').attr('id', 'tuesday_' + schedule_count);

        if (tuesday) {
            temp_id = '#tuesday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#wednesday').attr('name', schedule_count + '_day[]');
        $('#label_wednesday').attr('for', 'wednesday_' + schedule_count);
        $('#label_wednesday').attr('id', 'label_wednesday_' + schedule_count);
        $('#wednesday').attr('id', 'wednesday_' + schedule_count);

        if (wednesday) {
            temp_id = '#wednesday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }


        $('#thursday').attr('name', schedule_count + '_day[]');
        $('#label_thursday').attr('for', 'thursday_' + schedule_count);
        $('#label_thursday').attr('id', 'label_thursday_' + schedule_count);
        $('#thursday').attr('id', 'thursday_' + schedule_count);

        if (thursday) {
            temp_id = '#thursday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#friday').attr('name', schedule_count + '_day[]');
        $('#label_friday').attr('for', 'friday_' + schedule_count);
        $('#label_friday').attr('id', 'label_friday_' + schedule_count);
        $('#friday').attr('id', 'friday_' + schedule_count);

        if (friday) {
            temp_id = '#friday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        $('#saturday').attr('name', schedule_count + '_day[]');
        $('#label_saturday').attr('for', 'saturday_' + schedule_count);
        $('#label_saturday').attr('id', 'label_saturday_' + schedule_count);
        $('#saturday').attr('id', 'saturday_' + schedule_count);

        if (saturday) {
            temp_id = '#saturday_' + schedule_count;
            $(temp_id).prop('checked', true);
        }

        //  $('#allowance_amount_delete').attr('id', 'allowance_amount_delete_' + schedule_count);
        //  $('#allowance_amount_delete_' + schedule_count).attr('onclick', 'deleteAllowanceParentElement(this, ' + schedule_count + ')');
    }


    function delete_schedule_div(n, number) {
        deleted_div_track.push(number);
        div_remove();
        jQuery(n).parent().parent().remove();

    }

    $(document).ready(function() {

        var multiple_day_index = '<?php echo json_encode($multiple_day_index)  ?>';
        multiple_day_index = JSON.parse(multiple_day_index);

        for (var i = 0; i < multiple_day_index.length; i++) {

            var week_list = '#week_list_';
            var current_id = week_list.concat(multiple_day_index[i]);
            $(current_id).removeClass('hidden');
        }

        var no_of_schedule = '<?php echo $no_of_schedules; ?>';

        if (no_of_schedule < 1) {
            $('#schedule_input_edit').hide();
        }

        var value = '<?php echo (isset($schedule_details[0]['tuition_type'])? $schedule_details[0]['tuition_type'] : 1 ) ?>';

        if (value == 0) {
            $('#week_list_1').removeClass('hidden');
        }

    });

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
        $('form.required-form').find('input, select, radio').each(function() {
            if ($(this).prop('required')) {
                if ($(this).val() === "") {
                    pass = 0;
                }
            }
        });


        if (!$("input[name=price_type]").is(":checked")) {
            pass = 0;
        }

        // for(i=1; i<=schedule_count; i++)
        // {
        //     if(!deleted_div_track.includes(i.toString()))
        //     {
        //         console.log(i);
        //         if(!$("input[name="+i+"_tution_type_indentify]").is(":checked")) 
        //             {
        //                 pass=0;
        //             }

        //     }

        // }

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
</script>