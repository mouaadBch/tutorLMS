<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?>
                    <a href="<?php echo site_url('addons/tutor_booking/schedule'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_booking'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('booking_lists'); ?></h4>
                <div class="table-responsive-sm mt-4">
                    <table id="basic-datatable" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('Title'); ?></th>
                                <th><?php echo get_phrase('Category'); ?></th>
                                <th><?php echo get_phrase('Class Type'); ?></th>
                                <th><?php echo get_phrase('price_type'); ?></th>
                                <th><?php echo get_phrase('price'); ?></th>
                                <th><?php echo get_phrase('added by'); ?></th>
                                <th><?php echo get_phrase('action'); ?></th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($booking_list->num_rows()>0):
                            foreach ($booking_list->result_array() as $key => $booking) : ?>

                
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                               
                                    <td><?php echo  get_phrase($booking['title']); ?></td>

                                    <?php
                                    if($booking['category_id']!=0):
                                     $category_name = $this->db->get_where('tutor_category', array('id' => $booking['category_id']))->row_array(); ?>
                                      <td><?php echo get_phrase($category_name['name']); ?>

                                      <?php else :  ?>

                                        <td><?php echo get_phrase('no category')?>


                                     <?php endif;  if($booking['sub_category_id']!=0): 

                                         $sub_category_name = $this->db->get_where('tutor_category', array('id' => $booking['sub_category_id']))->row_array();  ?>  
                                         <small>
                                                <p><span><?php  echo get_phrase($sub_category_name['name']); ?></span></p>
                                          </small>
                                            
                                            
                                            </td>

                                               <?php else :  ?>
                                        <small>
                                                <p><span><?php  echo get_phrase('no sub_category') ?></span></p>
                                          </small>
                                    </td>


                                     <?php endif;  ?> 

                                     <?php if($booking['tution_class_type']==1)
                                               $c_type="online";
                                           elseif($booking['tution_class_type']==2)   
                                                $c_type="in person";
                                            elseif($booking['tution_class_type']==3)   
                                                 $c_type="online / in person";
                                      ?> 

                                     

                                     <td><?php echo  get_phrase($c_type); ?></td>
                                     
                                     <td><?php echo get_phrase($booking['price_type']); ?></td>

                                     <td><?php echo currency($booking['price']) ?></td>

                                     <?php 
                                      $user_details = $this->db->get_where('users', array('id' => $booking['tutor_id']))->row_array();?>

                                     <td><?php echo get_phrase($user_details['first_name']." ".$user_details['last_name']); ?></td>

                                    <td>
                                        <div class="dropright dropright">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo site_url('addons/tutor_booking/tutor_schedule_list_by_booking_id/'.$booking['id']) ?>" ><i class="mdi mdi-timetable"></i><?php echo get_phrase(' Schedules'); ?></a></li>
                                                <li><a class="dropdown-item" href="<?php echo site_url('addons/tutor_booking/edit_booking_by_id/'.$booking['id']) ?>"><i class="mdi mdi-pencil"></i><?php echo get_phrase('edit schedules'); ?></a></li>
                                                <!--  <li><a class="dropdown-item" href="#" onclick="showRightModal('<?php #echo site_url('addons/tutor_booking/edit_booking_on_booking_table_page/'.$booking['id']); ?>', '<?php #echo get_phrase('Edit Booking'); ?>');"><i class="mdi mdi-pencil"></i><?php #echo get_phrase('Edit booking'); ?></a></li> -->
                                                <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/tutor_booking/booking_data/inactive/'.$booking['id']); ?>');"><i class="fa fa-lock"></i><?php echo get_phrase(' inactive booking'); ?></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                            
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
        <?php if($inactive_booking->num_rows()>0): ?>
        <a href="<?php echo site_url('addons/tutor_booking/tutor_inactive_booking_list'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"></i><?php echo get_phrase('Inactived Booking lists'); ?></a>
        <?php endif;?>
    </div><!-- end col-->
</div>