<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?>
            </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
              <h4 class="mb-3 header-title"><?php echo get_phrase('offline_payments'); ?></h4>
              <div class="table-responsive-sm mt-4">
                <table id="basic-datatable" class="table table-striped table-centered mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th><?php echo get_phrase('user'); ?></th>
                      <th><?php echo get_phrase('price'); ?></th>
                      <th><?php echo get_phrase('course'); ?></th>
                      <th><?php echo get_phrase('payment_document'); ?></th>
                      <th><?php echo get_phrase('status'); ?></th>
                      <th><?php echo get_phrase('actions'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                       foreach ($offline_payments as $key => $offline_payment): ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td>
                              <?php $user_data = $this->user_model->get_user($offline_payment['user_id'])->row_array();?>
                              <b><?php echo $user_data['first_name']." ".$user_data['last_name']; ?></b>
                              <p><small><?php echo $user_data['email']; ?></small></p>
                            </td>
                            <td>
                              <span class="badge badge-dark-lighten badge-pill"><?php echo currency($offline_payment['amount']); ?></span>
                            </td>
                            <td>
                              type : <?= $offline_payment['item_type'] ?>
                              <br>
                              <?php
                                if ($offline_payment['item_type'] == 'ebook') :
                                  foreach (json_decode($offline_payment['course_id']) as $course_id) :
                                    $CI    = &get_instance();
                                    $CI->load->model('addons/ebook_model');
                                    $course_title =$CI->ebook_model->get_ebook_by_id($course_id)->row('title');
                                    ?>
                                    <a href="<?php echo site_url('addons/ebook_manager/edit_ebook/' . $course_id); ?>">
                                      <i class="mdi mdi-arrow-right"></i>
                                      <?php echo $course_title; ?>
                                    </a>
                                    <br>
                                    <?php
                                  endforeach;
                                elseif ($offline_payment['item_type'] == 'bundle') :
                                  foreach (json_decode($offline_payment['course_id']) as $course_id) :
                                    $CI    = &get_instance();
                                    $CI->load->model('addons/course_bundle_model');
                                    $course_title = $CI->course_bundle_model->get_bundle($course_id)->row('title');
                                    ?>
                                    <a href="<?php echo site_url('addons/bundle/edit_bundle_form/' . $course_id); ?>">
                                      <i class="mdi mdi-arrow-right"></i>
                                      <?php echo $course_title; ?>
                                    </a>
                                    <br>
                                    <?php
                                  endforeach;
                                elseif ($offline_payment['item_type'] == 'booking') :
                                  foreach (json_decode($offline_payment['course_id']) as $course_id) :
                                    $CI    = &get_instance();
                                    $CI->load->model('addons/tutor_booking_model');
                                    $schedule_info = $CI->tutor_booking_model->get_schedule_info($course_id);
                                    $bookingInfo = $CI->tutor_booking_model->get_tutor_booking_data_by_bokking_id($schedule_info['booking_id'])->row(); ?>
                                    <a href="<?php echo site_url('addons/tutor_booking/edit_booking_by_id/' . $schedule_info['booking_id']); ?>" target="_blank" >
                                      <i class="mdi mdi-arrow-right"></i>
                                      <?php echo $bookingInfo->title; ?>
                                    </a>
                                    <br>
                                    <b>
                                      start : <small><?= date('Y/m/d H:i',$schedule_info['start_time']) ?></small>
                                      <br>
                                      end : <small><?= date('Y/m/d H:i',$schedule_info['end_time']) ?></small>
                                    </b>
                                    <br>
                                    <b>
                                      tutor: 
                                        <?php 
                                      $tutorInfo = $this->user_model->get_user($bookingInfo->tutor_id)->row_array();
                                      echo $tutorInfo['last_name'].' '.$tutorInfo['first_name'] ; ?>
                                    </b>
                                    <br>
                                    <?php
                                  endforeach;
                                else :
                                  foreach (json_decode($offline_payment['course_id']) as $course_id) :
                                    $course_title = $this->crud_model->get_course_by_id($course_id)->row('title');
                                    ?>
                                    <a href="<?php echo site_url('admin/course_form/course_edit/' . $course_id); ?>"><i class="mdi mdi-arrow-right"></i><?php echo $course_title; ?></a><br>
                                    <?php
                                  endforeach;
                                endif;
                              ?>
                              <p><small><?php echo date('d M Y', $offline_payment['timestamp']) ?></small></p>
                            </td>
                            <td>
                              <a href="<?php echo base_url('uploads/payment_document/'.$offline_payment['document_image']); ?>" class="btn btn-outline-info" download><i class="mdi mdi-download"></i><?php #echo get_phrase('payment_document_file'); ?></a>
                              <a>
                            </td>
                            <td>
                              <?php if($offline_payment['status'] == 0): ?>
                                <span class="badge badge-danger-lighten"><?php echo get_phrase('pending') ?></span>
                              <?php elseif($offline_payment['status'] == 1): ?>
                                <span class="badge badge-success-lighten"><?php echo get_phrase('approved') ?></span>
                              <?php elseif($offline_payment['status'] == 2): ?>
                                <span class="badge badge-dark-lighten"><?php echo get_phrase('suspended') ?></span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <div class="dropright dropright">
                                  <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="mdi mdi-dots-vertical"></i>
                                  </button>
                                  <ul class="dropdown-menu">
                                      <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/offline_payment/approve/delete/'.$offline_payment['id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                                  </ul>
                              </div>
                            </td>
                        </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>




<script type="text/javascript">
  function view_payment_document(image_url){
    $('#payment_document_modal').modal('show');
    $('#payment_document_image_view').html("<img src='"+image_url+"' alt='contact-img' title='<?php echo get_phrase('payment_document'); ?>'' class='rounded w-100 text-center'>");
  }
  function show_loader_modal(){
    $("#loader_modal").show();
  }
</script>

<div id="loader_modal" class="loader-modal">
  <p class="p-0 m-0"><?php echo get_phrase('please_wait');?>....</p>
  <p class="p-0 m-0"><?php echo get_phrase('enrolling_the_student_and_sending_mail'); ?>.....</p>
</div>

<!--  Modal content for the above example -->
<div class="modal fade" id="payment_document_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="payment_document_image_view" class="w-100 text-center justify-content-center"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
