<?php $live_class = $this->db->get_where('live_class', array('course_id' => $course_id))->row_array(); ?>
<div class="tab-pane" id="live-class">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="live_class_schedule_date"><?php echo get_phrase('live_class_schedule').' ('.get_phrase('date').')'; ?></label>
                <div class="col-md-6">
                    <input type="text" name="live_class_schedule_date" class="form-control date" id="live_class_schedule_date" data-toggle="date-picker" data-single-date-picker="true" value="<?php echo empty($live_class['date']) ? '' : date('m/d/Y', $live_class['date']); ?>">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="live_class_schedule_time"><?php echo get_phrase('live_class_schedule').' ('.get_phrase('time').')'; ?></label>
                <div class="col-md-6">
                    <input type="text" name="live_class_schedule_time" id="live_class_schedule_time" class="form-control" data-toggle="timepicker" value="<?php echo empty($live_class['time']) ? '' : date('m/d/Y', $live_class['time']); ?>">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="note_to_students"><?php echo get_phrase('note_to_students'); ?></label>
                <div class="col-md-6">
                    <textarea class="form-control" name="note_to_students" id="note_to_students" rows="5"><?php echo empty($live_class['note_to_students']) ? '' : $live_class['note_to_students']; ?></textarea>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label" for="meeting_invite_link"><?php echo get_phrase('meeting_invite_link'); ?></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="meeting_invite_link" name = "meeting_invite_link" placeholder="<?php echo get_phrase('enter_meeting_invite_link'); ?>" value="<?php echo empty($live_class['meeting_invite_link']) ? '' : $live_class['meeting_invite_link']; ?>">
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
                <?php if (isset($live_class['meeting_invite_link']) && $live_class['meeting_invite_link'] != ""): ?>
                <div class="mt-2">
                    <a href="<?php echo $live_class['meeting_invite_link']; ?>" target="_blank" class="btn btn-outline-info btn-sm mb-1"><?php echo get_phrase('start_meeting'); ?>
                        <i class="mdi mdi-arrow-right ml-1"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
