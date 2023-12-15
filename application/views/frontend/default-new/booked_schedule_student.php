<?php include "breadcrumb.php"; ?>

<style>
    .tab-content .table> :not(caption)>*>* {
        width: 30px;
        border-bottom: 1px solid #eee;
    }

    .tab-content .table tr:last-child td {
        border-bottom: none !important;
    }

    .btn:hover {
        color: #fff;
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }
</style>
<section class="wish-list-body ">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <?php include "profile_menus.php"; ?>
            </div>
            <div class="col-lg-9">
                <div class="conversation-fulllll-body common-card">

                    <ul class="nav nav-tabs sNav-tabs mt-3" id="myTab" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming-tab-pane" type="button" role="tab" aria-controls="upcoming-tab-pane" aria-selected="true">
                                <?php echo get_phrase('Upcoming'); ?>
                                <p class="badge bg-success ">
                                    <?php echo $schedules->num_rows(); ?>
                                </p>
                            </button>
                        </li>


                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="archive-tab" data-bs-toggle="tab" data-bs-target="#archive-tab-pane" type="button" role="tab" aria-controls="archive-tab-pane" aria-selected="true">
                                <?php echo get_phrase('Archive'); ?>
                                <p class="badge bg-danger ">
                                    <?php echo $archieve_schedules->num_rows(); ?>
                                </p>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment-tab-pane" type="button" role="tab" aria-controls="payment-tab-pane" aria-selected="true">
                                <?php echo get_phrase('Payment'); ?>
                                <p class="badge bg-info ">
                                    <?php echo $student_payments->num_rows(); ?>
                                </p>
                            </button>
                        </li>

                    </ul>


                    <div class="tab-content tutor-tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="upcoming-tab-pane" role="tabpanel" aria-labelledby="upcoming-tab">
                            <?php if ($schedules->num_rows() > 0) : ?>


                                <table id="basic-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo get_phrase('Title'); ?></th>
                                            <th><?php echo get_phrase('tutor'); ?></th>
                                            <!-- <th><?php #echo get_phrase('Date'); 
                                                        ?></th> -->
                                            <th><?php echo get_phrase('Join Class'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($schedules->result_array() as $key => $schedule) : ?>

                                            <?php $booking = $this->db->get_where('tutor_booking', array('id' => $schedule['booking_id']))->row_array(); ?>

                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>

                                                <?php
                                                if ($booking['tution_class_type'] == 1)
                                                    $c_type = "online";
                                                elseif ($booking['tution_class_type'] == 2)
                                                    $c_type = "in person";
                                                elseif ($booking['tution_class_type'] == 3)
                                                    $c_type = "online / in person";
                                                ?>

                                                <td class="w-50">
                                                    <?php echo (strlen($booking['title']) > 45 ? substr($booking['title'], 0, 45) . "..." : $booking['title']); ?>
                                                    <br>
                                                    <small class="badge bg-primary"><?php echo  get_phrase($c_type); ?></small>
                                                    <br>
                                                    <small class="badge bg-info"><?php echo  get_phrase("message").': '.get_phrase($booking['message']); ?></small>
                                                </td>

                                                <?php $user_details = $this->db->get_where('users', array('id' => $booking['tutor_id']))->row_array(); ?>

                                                <td class="w-25">
                                                    <?php echo get_phrase($user_details['first_name'] . " " . $user_details['last_name']); ?>
                                                    <br>
                                                    <small class="badge bg-success text-white ">email: <?= $user_details['email'] ?></small>
                                                    <br>
                                                    <small class="badge bg-success text-white "><?= get_phrase('phone') . ': ' . ($user_details['phone'] ?? '-') ?></small>
                                                </td>

                                                <!-- <td>
                                                    <p><?php #echo  date('d M, y', (int)$schedule['start_time']); 
                                                        ?></p>
                                                    <small class="text-12px">
                                                        <?php #echo  date('h:i A', (int)$schedule['start_time']) . " - " . date('h:i A', (int)$schedule['end_time']); 
                                                        ?>
                                                    </small>
                                                </td> -->

                                                <td class="w-25">
                                                    <?php if (($booking['tution_class_type'] == 1 || $booking['tution_class_type'] == 3)) :  ?>
                                                        <p><?php echo  date('d M, y', (int)$schedule['start_time']); 
                                                            ?></p>
                                                        <small class="text-12px">
                                                            <?php echo  date('h:i A', (int)$schedule['start_time']) . " - " . date('h:i A', (int)$schedule['end_time']); 
                                                            ?>
                                                        </small>
                                                        <a class="btn btn-outline-info" href="<?php echo $booking['meeting_link']; ?>" target="_blank"> <i class="fa fa-video"></i> <?php echo get_phrase('Join Class'); ?> </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>


                            <?php else : ?>
                                <?php echo get_phrase('No data found'); ?>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade show " id="archive-tab-pane" role="tabpanel" aria-labelledby="archive-tab">
                            <?php if ($archieve_schedules->num_rows() > 0) : ?>
                                <table id="basic-datatable" class="table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo get_phrase('Title'); ?></th>
                                            <th><?php echo get_phrase('tutor'); ?></th>
                                            <th><?php echo get_phrase('Date'); ?></th>
                                            <th><?php echo get_phrase('Join'); ?></th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($archieve_schedules->result_array() as $key => $schedule) : ?>

                                            <?php $booking = $this->db->get_where('tutor_booking', array('id' => $schedule['booking_id']))->row_array(); ?>


                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>

                                                <?php if ($booking['tution_class_type'] == 1)
                                                    $c_type = "online";
                                                elseif ($booking['tution_class_type'] == 2)
                                                    $c_type = "in person";
                                                elseif ($booking['tution_class_type'] == 3)
                                                    $c_type = "online / in person";
                                                ?>

                                                <td>
                                                    <?php echo get_phrase(strlen($booking['title']) > 45 ? substr($booking['title'], 0, 45) . "..." : $booking['title']); ?>
                                                    <br>
                                                    <small class="badge bg-primary"><?php echo  get_phrase($c_type); ?></small>
                                                </td>

                                                <?php $user_details = $this->db->get_where('users', array('id' => $booking['tutor_id']))->row_array(); ?>

                                                <td>
                                                    <?php echo get_phrase($user_details['first_name'] . " " . $user_details['last_name']); ?>
                                                </td>

                                                <td>
                                                    <p><?php echo  date('d M, y', (int)$schedule['start_time']); ?></p>
                                                    <small class="text-12px">
                                                        <?php echo  date('h:i A', (int)$schedule['start_time']) . " - " . date('h:i A', (int)$schedule['end_time']); ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    <?php if (($booking['tution_class_type'] == 1 || $booking['tution_class_type'] == 3)) :  ?>
                                                        <a class="btn btn-outline-secondary disabled" href="<?php #echo $booking['meeting_link']; 
                                                                                                            ?>" target="_blank"> <i class="fa fa-video"></i> <?php echo get_phrase('Join Class'); ?> </a>
                                                    <?php endif; ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                            <?php
                                echo get_phrase('No data found');
                            endif; ?>
                        </div>

                        <div class="tab-pane fade show " id="payment-tab-pane" role="tabpanel" aria-labelledby="payment-tab">
                            <?php if ($student_payments->num_rows() > 0) : ?>
                                <table id="basic-datatable" class="table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo get_phrase('Title'); ?></th>
                                            <th><?php echo get_phrase('Tuition date'); ?></th>
                                            <th><?php echo get_phrase('Fee'); ?></th>
                                            <th><?php echo get_phrase('Paid by'); ?></th>
                                            <th><?php echo get_phrase('Payment date'); ?></th>
                                            <th><?php echo get_phrase('Tutor'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($student_payments->result_array() as $key => $schedule) : ?>

                                            <?php $booking = $this->db->get_where('tutor_booking', array('id' => $schedule['booking_id']))->row_array(); ?>

                                            <tr>
                                                <td>
                                                    <?php echo $key + 1; ?>
                                                </td>

                                                <?php $tuition = $this->db->get_where('tutor_schedule', array('id' => $schedule['schedule_id']))->row_array(); ?>

                                                <td>
                                                    <?php echo get_phrase(strlen($booking['title']) > 22 ? substr($booking['title'], 0, 22) . "..." : $booking['title']); ?>
                                                </td>

                                                <td>
                                                    <p><?php echo  date('d M, y', (int)$tuition['start_time']); ?></p>
                                                    <small class="text-11px">
                                                        <?php echo  date('h:i A', (int)$tuition['start_time']) . " - " . date('h:i A', (int)$tuition['end_time']); ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    <?php echo currency($schedule['amount']); ?></td>
                                                <td>
                                                    <small class="badge bg-primary">
                                                        <?php echo get_phrase($schedule['payment_type']); ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    <?php echo get_phrase(date('d M, y', (int)$schedule['date_added'])); ?>
                                                </td>

                                                <?php $user_details = $this->db->get_where('users', array('id' => $schedule['tutor_id']))->row_array(); ?>

                                                <td>
                                                    <?php echo get_phrase($user_details['first_name'] . " " . $user_details['last_name']); ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>

                                    </tbody>
                                </table>


                            <?php else : ?>
                                <?php echo get_phrase('No data found'); ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</section>