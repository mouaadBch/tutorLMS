<?php
    $live_class = $this->db->where('course_id', $course_id)->get('live_class');
    $live_class_date = date('m/d/Y', $live_class->row('date'));
    $live_class_time = date('h:i:s A', $live_class->row('time'));
    $live_class_note = $live_class->row('note_to_students');
    $live_class_password = $live_class->row('zoom_meeting_password');
    $live_class_meeting_id = $live_class->row('zoom_meeting_id');

?>
<div class="tab-pane" id="live-class">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="live_class_schedule_date"><?php echo get_phrase('live_class_schedule').' ('.get_phrase('date').')'; ?></label>
                <div class="col-md-6">
                    <input type="text" name="live_class_schedule_date" class="form-control date" id="live_class_schedule_date" data-toggle="date-picker" data-single-date-picker="true" value="<?php echo $live_class_date; ?>">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="live_class_schedule_time"><?php echo get_phrase('live_class_schedule').' ('.get_phrase('time').')'; ?></label>
                <div class="col-md-6">
                    <input type="text" name="live_class_schedule_time" id="live_class_schedule_time" class="form-control" data-toggle="timepicker" value="<?php echo $live_class_time; ?>">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="note_to_students"><?php echo get_phrase('note_to_students'); ?></label>
                <div class="col-md-6">
                    <textarea class="form-control" name="note_to_students" id="note_to_students" rows="5"><?php echo $live_class_note; ?></textarea>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="zoom_meeting_id"><?php echo get_phrase('Meeting ID'); ?></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="zoom_meeting_id" id="zoom_meeting_id" value="<?php echo $live_class_meeting_id; ?>">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="zoom_meeting_password"><?php echo get_phrase('Meeting password'); ?></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="zoom_meeting_password" id="zoom_meeting_password" value="<?php echo $live_class_password; ?>">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="alert alert-success text-center" role="alert">
                <h4 class="alert-heading"><?php echo get_phrase('course_enrolment_details'); ?></h4>
                <p>
                    <?php
                    $number_of_enrolments = $this->crud_model->enrol_history($course_id)->num_rows();
                    echo get_phrase('number_of_enrolment').' : <strong>'.$number_of_enrolments.'</strong>';
                    ?>
                </p>
                <hr>
                <p class="mb-0"><?php echo get_phrase('get').' Zoom '.get_phrase('meeting_plans_that_fit_your_business_perfectly'); ?>.</p>
                <div class="mt-2">
                    <a href="https://zoom.us/pricing" target="_blank" class="btn btn-outline-success btn-sm mb-1"><?php echo get_phrase('zoom_meeting_plans'); ?>
                        <i class="mdi mdi-arrow-right ml-1"></i>
                    </a>
                </div>
                <?php if ($live_class->row('meeting_invite_link') != ""): ?>
                    <!-- <div class="mt-2">
                        <a href="<?php echo $live_class->row('meeting_invite_link'); ?>" target="_blank" class="btn btn-outline-info btn-sm mb-1"><?php echo get_phrase('start_meeting'); ?>
                            <i class="mdi mdi-arrow-right ml-1"></i>
                        </a>
                    </div> -->
                <?php endif; ?>
            </div>


            <?php $live_class_settings = $this->db->where('user_id', $course_details['creator'])->get('zoom_live_class_settings'); ?>

            <?php if($live_class_settings->num_rows() > 0 && $live_class_settings->row('client_id') != '' && $live_class_settings->row('client_secret') != ''): ?>
                <a href="<?php echo site_url('modal/popup/zoom_live_class_start_form/'.$course_details['id']) ?>" class="btn btn-success btn-sm mb-1"><?php echo get_phrase('Click here to start a live class'); ?></a>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading"><?php echo get_phrase('Zoom live class settings'); ?></h4>
                    <?php if($course_details['creator'] == $this->session->userdata('user_id')): ?>
                        <p><?php echo get_phrase('Please configure Client ID and Secret Key in your Zoom settings to start your live class from here') ?></p>
                    <?php else: ?>
                        <p><?php echo get_phrase('The course creator must have Zoom Client ID and Secret Key configured') ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>