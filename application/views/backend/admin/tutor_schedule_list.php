<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase("Booking Title : " . $booking['title']); ?>

                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase(' Active Schedule List'); ?></h4>

                <ul class="nav nav-tabs nav-bordered mb-3" id="myTab" role="tablist">

                    <li class="nav-item" role="presentation">

                        <a href="" class="nav-link active" id="upcoming-tab" data-toggle="tab" data-target="#upcomingtable" role="tab" aria-controls="upcomingtable" aria-selected="false">

                            <?php echo get_phrase('upcoming '); ?><p class="badge bg-success ">
                                <?php echo  $schedules->num_rows(); ?>
                            </p>


                        </a>
                    </li>


                    <li class="nav-item" role="presentation">
                        <a href="" class="nav-link" id="achieve-tab" data-toggle="tab" data-target="#achievetable" role="tab" aria-controls="achievetable" aria-selected="false">
                            <?php echo get_phrase('archive '); ?><p class="badge bg-danger ">
                                <?php echo  $schedules_achieve->num_rows(); ?>
                            </p>
                            <span></span>
                        </a>
                    </li>

                </ul>



                <div class="tab-content pb-2" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="upcomingtable" role="tabpanel" aria-labelledby="upcoming-tab">
                        <?php if ($schedules->num_rows() > 0) : ?>

                            <div class="table-responsive-sm mt-4">
                                <table id="basic-datatable" class="table table-striped table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo get_phrase('Date'); ?></th>
                                            <th><?php echo get_phrase('Active Till'); ?></th>
                                            <th><?php echo get_phrase('tution type'); ?></th>
                                            <th><?php echo get_phrase('selected days'); ?></th>
                                            <th><?php echo get_phrase('status'); ?></th>
                                            <th><?php echo get_phrase('Action'); ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($schedules->result_array() as $key => $schedule) : ?>


                                            <tr>
                                                <td><?php echo $key + 1; ?></td>
                                                <td>
                                                    <?php echo  date('m-d-Y', (int)$schedule['start_time']); ?>
                                                    <small>
                                                        <p><?php echo get_phrase('Time '); ?>: <span><?php echo  date('h:i A', (int)$schedule['start_time']) . " - " . date('h:i A', (int)$schedule['end_time']); ?></span></p>
                                                    </small>


                                                </td>

                                                <td> <?php echo  date('m-d-Y', (int)$schedule['valid_till']); ?> </td>


                                                <?php if ($schedule['tuition_type'] == 1) : ?>
                                                    <td><?php echo get_phrase('one time') ?></td>
                                                <?php elseif ($schedule['tuition_type'] == 7) : ?>
                                                    <td><?php echo get_phrase('everyday') ?></td>
                                                <?php elseif ($schedule['tuition_type'] == 0) : ?>
                                                    <td><?php echo get_phrase('selected_days') ?></td>
                                                <?php endif; ?>

                                                <?php if ($schedule['tuition_type'] == 0) :
                                                    $days = json_decode($schedule['selected_days']);  ?>
                                                    <td>
                                                        <?php foreach ($days as $day) {  ?>
                                                            <?php echo get_phrase($day . ",") ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php else : ?>
                                                    <td><?php echo get_phrase('N') . get_phrase('A') ?></td>
                                                <?php endif; ?>

                                                <?php if ($schedule['status'] == 0) : ?>
                                                    <td><?php echo get_phrase('available') ?></td>
                                                <?php elseif ($schedule['status'] == 1) : ?>
                                                    <td><?php echo get_phrase('booked') ?></td>
                                                <?php endif; ?>

                                                <td>
                                                    <div class="dropright dropright">
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="mdi mdi-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">

                                                            <a class="dropdown-item" href="<?php echo $booking['meeting_link']; ?>" target="_blank"><i class="mdi mdi-video"></i> <?php echo get_phrase('Start Class '); ?></a>

                                                            <button class="dropdown-item" onclick="showRightModal('<?php echo site_url('addons/tutor_booking/edit_schedule_by_id/' . $schedule['id']); ?>', '<?php echo get_phrase('Edit schedule'); ?>');" type="button"><i class="mdi mdi-pencil"></i> <?php echo get_phrase('Edit'); ?></button>
                                                            <button class="dropdown-item" onclick="confirm_modal('<?php echo site_url('addons/tutor_booking/schedule_data/inactive/' . $schedule['id']); ?>');" type="button"><i class="fa fa-lock"></i> <?php echo get_phrase(' inactive'); ?></button>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        <?php endforeach;
                                     ?>

                                   

                                    </tbody>
                                </table>
                            </div>

                            <?php  else:?>

                            <?= get_phrase('no data found') ?>

                            <?php endif; ?>

                    </div>

                    <div class="tab-pane fade show " id="achievetable" role="tabpanel" aria-labelledby="achieve-tab">


                        <?php if ($schedules_achieve->num_rows() > 0) : ?>



                            <div class="table-responsive-sm mt-4">
                                <table id="basic-datatable1" class="table table-striped table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo get_phrase('Date'); ?></th>
                                            <th><?php echo get_phrase('Active Till'); ?></th>

                                            <th><?php echo get_phrase('tution type'); ?></th>
                                            <th><?php echo get_phrase('selected days'); ?></th>
                                            <th><?php echo get_phrase('status'); ?></th>
                                            <th><?php echo get_phrase('Action'); ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($schedules_achieve->result_array() as $key => $schedule) : ?>


                                            <tr>
                                                <td><?php echo $key + 1; ?></td>
                                                <td>
                                                    <?php echo  date('m-d-Y', (int)$schedule['start_time']); ?>
                                                    <small>
                                                        <p><?php echo get_phrase('Time '); ?>: <span><?php echo  date('h:i A', (int)$schedule['start_time']) . " - " . date('h:i A', (int)$schedule['end_time']); ?></span></p>
                                                    </small>


                                                </td>

                                                <td> <?php echo  date('m-d-Y', (int)$schedule['valid_till']); ?> </td>


                                                <?php if ($schedule['tuition_type'] == 1) : ?>
                                                    <td><?php echo get_phrase('one time') ?></td>
                                                <?php elseif ($schedule['tuition_type'] == 7) : ?>
                                                    <td><?php echo get_phrase('everyday') ?></td>
                                                <?php elseif ($schedule['tuition_type'] == 0) : ?>
                                                    <td><?php echo get_phrase('selected_days') ?></td>
                                                <?php endif; ?>

                                                <?php if ($schedule['tuition_type'] == 0) :
                                                    $days = json_decode($schedule['selected_days']);  ?>
                                                    <td>
                                                        <?php foreach ($days as $day) {  ?>
                                                            <?php echo get_phrase($day . ",") ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php else : ?>
                                                    <td><?php echo get_phrase('N') . get_phrase('A') ?></td>
                                                <?php endif; ?>

                                                <?php if ($schedule['status'] == 0) : ?>
                                                    <td><?php echo get_phrase('available') ?></td>
                                                <?php elseif ($schedule['status'] == 1) : ?>
                                                    <td><?php echo get_phrase('booked') ?></td>
                                                <?php endif; ?>

                                                <td>
                                                    <div class="dropright dropright">
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="mdi mdi-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">

                                                            <a class="dropdown-item" href="<?php echo $booking['meeting_link']; ?>" target="_blank"><i class="mdi mdi-video"></i> <?php echo get_phrase('Start Class '); ?></a>

                                                            <button class="dropdown-item" onclick="showRightModal('<?php echo site_url('addons/tutor_booking/edit_schedule_by_id/' . $schedule['id']); ?>', '<?php echo get_phrase('Edit schedule'); ?>');" type="button"><i class="mdi mdi-pencil"></i> <?php echo get_phrase('Edit'); ?></button>
                                                            <button class="dropdown-item" onclick="confirm_modal('<?php echo site_url('addons/tutor_booking/schedule_data/inactive/' . $schedule['id']); ?>');" type="button"><i class="fa fa-lock"></i> <?php echo get_phrase(' inactive'); ?></button>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        <?php endforeach;
                                                       ?>
                                 

                                    </tbody>
                                </table>

                            </div>

                            <?php  else:?>

                        <?= get_phrase('no data found') ?>

                        <?php endif; ?>
                    </div>
                </div>


            </div> <!-- end card body-->
        </div> <!-- end card -->

        <?php if ($inactive_schedule > 0) : ?>

            <a href="<?php echo site_url('addons/tutor_booking/tutor_inactive_schedule_list_by_booking_id/' . $booking['id']);
                        ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="fa fa-lock"></i><?php echo get_phrase(' inactive schedule');
                                                                                                                ?></a>
        <?php endif; ?>
    </div><!-- end col-->
</div>

<script>
    "use strict";

    $(document).ready(function() {
        initDataTable(['#basic-datatable1']);
    });
</script>