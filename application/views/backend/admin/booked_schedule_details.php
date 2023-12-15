<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('booked_schedule'); ?></h4>
                <div class="table-responsive-sm mt-4">
                    <table id="basic-datatable" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                            <th>#</th>
                                <th><?php echo get_phrase('Date'); ?></th>
                                <th><?php echo get_phrase('Title'); ?></th>
                                <!-- <th><?php #echo get_phrase('Category'); ?></th> -->
                                <!-- <th><?php #echo get_phrase('Class Type'); ?></th> -->
                                <th><?php echo get_phrase('price_type'); ?></th>
                                <th><?php echo get_phrase('instructor_revenue'); ?></th>
                                <th><?php echo get_phrase('tutor'); ?></th>
                                <!-- <th><?php #echo get_phrase('Tution type'); ?></th>
                                <th><?php #echo get_phrase('Selected days'); ?></th> -->
                                <th><?php echo get_phrase('Booked By'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($schedules->num_rows() > 0) :
                                foreach ($schedules->result_array() as $key => $schedule) : ?>

                                    <?php $booking = $this->db->get_where('tutor_booking', array('id' => $schedule['booking_id']))->row_array(); ?>


                                    <tr>
                                        <td><?php echo $key + 1; ?></td>

                                        <td>
                                            <?php echo  date('m-d-Y', (int)$schedule['start_time']); ?>
                                            <small>
                                                <p><?php echo get_phrase('Time '); ?>: <span><?php echo  date('h:i A', (int)$schedule['start_time']) . " - " . date('h:i A', (int)$schedule['end_time']); ?></span></p>
                                            </small>
                                        </td>

                                        <td>
                                            <?php echo '<b>'.get_phrase($booking['title']).'</b><br><br>'; 
                                                echo get_phrase('Category').': ';
                                                if ($booking['category_id'] != 0) :
                                                $category_name = $this->db->get_where('tutor_category', array('id' => $booking['category_id']))->row_array();
                                                    echo get_phrase($category_name['name']); 
                                                else :  
                                                    echo get_phrase('no category') ;
                                                endif;
                                            if ($booking['sub_category_id'] != 0) :
                                                $sub_category_name = $this->db->get_where('tutor_category', array('id' => $booking['sub_category_id']))->row_array();  ?>
                                                <small>
                                                    <p><span><?php echo get_phrase($sub_category_name['name']); ?></span></p>
                                                </small>
                                            <?php else :  ?>
                                                <small>
                                                    <p><span><?php echo get_phrase('no sub_category') ?></span></p>
                                                </small>
                                            <?php endif;  ?>
                                        </td>

                                        <!-- 
                                        <td>
                                            <?php /* if ($booking['category_id'] != 0) :
                                                $category_name = $this->db->get_where('tutor_category', array('id' => $booking['category_id']))->row_array();
                                                    echo get_phrase($category_name['name']); 
                                                else :  
                                                    echo get_phrase('no category') ;
                                                endif;
                                            if ($booking['sub_category_id'] != 0) :
                                                $sub_category_name = $this->db->get_where('tutor_category', array('id' => $booking['sub_category_id']))->row_array();  */ ?>
                                                <small>
                                                    <p><span><?php #echo get_phrase($sub_category_name['name']); ?></span></p>
                                                </small>
                                            <?php #else :  ?>
                                                <small>
                                                    <p><span><?php #echo get_phrase('no sub_category') ?></span></p>
                                                </small>
                                            <?php #endif;  ?>
                                        </td>
                                        -->

                                        <!-- <td>
                                            <?php
                                                 if ($booking['tution_class_type'] == 1)
                                                $c_type = "online";
                                            elseif ($booking['tution_class_type'] == 2)
                                                $c_type = "in person";
                                            elseif ($booking['tution_class_type'] == 3)
                                                $c_type = "online / in person";
                                            /* echo  get_phrase($c_type); */
                                            ?>
                                        </td> -->

                                        <td>
                                            <?php 
                                            echo get_phrase('Class Type').': <span class="badge text-white bg-primary">'. get_phrase($c_type).'</span><br/>' ;
                                            echo get_phrase('price').': <span class="badge text-white bg-primary">'. currency($booking['price']) .'</span><br/>' ;
                                            echo get_phrase('price_type').': <span class="badge text-white bg-primary">'. get_phrase($booking['price_type']) .'</span><br/>' ;
                                            ?>
                                        </td>

                                        <td class="w-25">
                                            <?php 
                                                $tutor_payment = $this->db->get_where('tutor_payment', ['booking_id' => $schedule['booking_id'],'student_id'=>$schedule['student_id'] ,'schedule_id'=> $schedule['id']])->row_array();    
                                                echo get_phrase('total_amount').': <span class="badge bg-success text-white">'.currency($tutor_payment['amount']).'</span><br>' ;
                                                echo get_phrase('admin_revenue').': <span class="badge bg-success text-white">'.currency($tutor_payment['admin_revenue']).'</span><br>' ;
                                                echo get_phrase('date_of_payment').': <span class="badge">'. date('Y-m-d' ,$tutor_payment['date_added']).'</span>';
                                              ?>
                                        </td>

                                        <td>
                                            <?php
                                                $user_details = $this->db->get_where('users', array('id' => $booking['tutor_id']))->row_array();
                                                echo get_phrase($user_details['first_name'] . " " . $user_details['last_name']);
                                            ?>
                                        </td>

                                        <!-- <td>
                                            <?php #if ($schedule['tuition_type'] == 1) : ?>
                                                <?php #echo get_phrase('one time') ?>
                                            <?php #elseif ($schedule['tuition_type'] == 7) : ?>
                                                <?php #echo get_phrase('everyday') ?>
                                            <?php #elseif ($schedule['tuition_type'] == 0) : ?>
                                                <?php #echo get_phrase('selected_days') ?>
                                            <?php #endif; ?>
                                        </td> -->

                                        <!--  <td>
                                            <?php /* if ($schedule['tuition_type'] == 0) :
                                                $days = json_decode($schedule['selected_days']);  
                                                 foreach ($days as $day) {  
                                                    echo get_phrase($day . ",") ;
                                                }  */
                                            ?>
                                            <?php /* else : 
                                                echo get_phrase('N') . get_phrase('A') ;
                                            endif; */ ?>
                                        </td> -->

                                        <td>
                                            <?php
                                            $user_details = $this->db->get_where('users', array('id' => $schedule['student_id']))->row_array();
                                            echo get_phrase($user_details['first_name'] . " " . $user_details['last_name']);
                                            ?>
                                            <br>
                                            <small class="badge bg-success text-white ">email: <?= $user_details['email'] ?></small>
                                            <br>
                                            <small class="badge bg-success text-white "><?= get_phrase('phone') . ': ' . ($user_details['phone'] ?? '-') ?></small>
                                            <br>
                                           <!--  <a href="<?php #echo $booking['meeting_link']; ?>" target="_blank"><i class="mdi mdi-video"></i> <?php #echo get_phrase('Start Class '); ?></a> -->
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>

                        </tbody>
                    </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>