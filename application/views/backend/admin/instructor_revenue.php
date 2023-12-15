<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('instructor_revenue'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="mb-3 header-title"><?php echo get_phrase('instructor_revenue'); ?></h4>
        <!-- code mouaad -->
        <div class="row justify-content-md-center">
          <div class="col-xl-12">
            <form class="form-inline" action="<?php echo site_url('admin/instructor_revenue/filter_by_date_range') ?>" method="get">
              <div class="col-xl-5">
                <div class="form-group">
                  <label for="instructor_id"><?php echo get_phrase('instructor'); ?></label>
                  <select class="form-control server-side-select2" name="instructor_id" id='instructor_id' action="<?php echo site_url('admin/get_select2_instructor_data/all'); ?>">
                    <option value="all" <?php if ($selected_instructor_id == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>

                    <?php if (isset($_GET['instructor_id']) && $_GET['instructor_id'] != 'all') : ?>
                      <?php $instructor_details = $this->user_model->get_all_user($_GET['instructor_id'])->row_array(); ?>
                      <option value="<?php echo $_GET['instructor_id']; ?>" selected><?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?></option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
              <div class="col-xl-5">
                <div class="form-group">
                  <label for="date_range"><?php echo get_phrase('date'); ?></label>
                  <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-light" style="width: 100%;">
                    <i class="mdi mdi-calendar"></i>&nbsp;
                    <span id="selectedValue"><?php echo date("F d, Y", $timestamp_start) . " - " . date("F d, Y", $timestamp_end); ?></span> <i class="mdi mdi-menu-down"></i>
                  </div>
                  <input id="date_range" type="hidden" name="date_range" value="<?php echo date("d F, Y", $timestamp_start) . " - " . date("d F, Y", $timestamp_end); ?>">
                </div>
              </div>
              <div class="col-xl-2 d-flex">
                <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter'); ?></button>
                <button type="button" class="btn btn-info btn-sm mx-1" onclick="redirect()"> <?php echo get_phrase('remove'); ?></button>
              </div>
            </form>
          </div>
        </div>
        <!-- code mouaad -->
        <?php $amount = 0;
        $instructor_revenue = 0;
        $nb_commands = 0; ?>
        <div class="table-responsive-sm mt-4">
          <div class="card">
            <div class="card-body border border-info rounded p-2">
              <div class="row justify-content-center align-items-center g-2">
                <div class="col-md-4">
                  <p class="text-center text-info" style="font-weight: 800;font-size: 19px;">Total amount: </p>
                  <p class="text-center" style="font-size: 20px;" id="amount_value"></p>
                </div>
                <div class="col-md-4">
                  <p class="text-center text-info" style="font-weight: 800;font-size: 19px;">Instructor revenue: </p>
                  <p class="text-center" style="font-size: 20px;" id="instructor_revenue_value"></p>
                </div>
                <div class="col-md-4">
                  <p class="text-center text-info" style="font-weight: 800;font-size: 19px;">Number orders: </p>
                  <p class="text-center" style="font-size: 20px;" id="nb_commands_value"></p>
                </div>
              </div>
            </div>
          </div>
          <table id="basic-datatable" class="table table-striped table-centered mb-0">
            <thead>
              <tr>
                <th> id </th>
                <th><?php echo get_phrase('user'); ?></th>
                <th><?php echo get_phrase('enrolled_course'); ?></th>
                <th><?php echo get_phrase('instructor'); ?></th>
                <th><?php echo get_phrase('total_amount'); ?></th>
                <th><?php echo get_phrase('instructor_revenue'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payment_history as $payment) :
                $course_data = $this->db->get_where('course', array('id' => $payment['course_id']))->row_array();
                $user_instructor = $this->db->get_where('users', array('id' => $course_data['creator']))->row_array();
                $user_data = $this->db->get_where('users', array('id' =>  $payment['user_id']))->row_array();

                /*   $payment_keys = json_decode($user_data['payment_keys'], true);
                $paypal_keys = $payment_keys['paypal'];
                $stripe_keys = $payment_keys['stripe'];
                $razorpay_keys = $payment_keys['razorpay']; */
              ?>
                <tr class="gradeU">
                  <td><?php echo $payment['id'] ;?></td>
                  <td>
                    <?= $user_data['first_name'].' '.$user_data['last_name'] ?>
                    <small class="badge badge-light"><?php echo $user_data['email'] ?? null; ?></small>
                  </td>
                  <td>
                    <strong><a href="<?php echo site_url('home/course/' . slugify($course_data['title']) . '/' . $course_data['id']); ?>" target="_blank"><?php echo $course_data['title']; ?></a></strong><br>
                    <small class="text-muted"><?php echo get_phrase('enrolment_date') . ': ' . date('D, d-M-Y', $payment['date_added']); ?></small>
                    <?php if ($payment['coupon']) : ?>
                      <small class="d-block">
                        <span class="text-muted">
                          <?php echo get_phrase('coupon_applied'); ?> :
                        </span>
                        <span class="badge badge-success">
                          <i class="fas fa-tags"></i> <?php echo $payment['coupon']; ?>
                        </span>
                      </small>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $user_instructor['first_name'] . ' ' . $user_instructor['last_name']; ?></td>
                  <td>
                    <?php echo currency($payment['amount']);
                    $amount += $payment['amount']; ?>
                  </td>
                  <td>
                    <?php echo currency($payment['instructor_revenue']);
                    $instructor_revenue += $payment['instructor_revenue']; ?>
                  </td>
                </tr>
              <?php $nb_commands++;
              endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <p id="amount" class="invisible"><?php echo  currency($amount); ?></p>
    <p id="instructor_revenue" class="invisible"><?php echo   currency($instructor_revenue); ?></p>
    <p id="nb_commands" class="invisible"><?php echo  $nb_commands; ?></p>

  </div>
</div>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('amount_value').innerHTML = document.getElementById('amount').innerHTML;
    document.getElementById('instructor_revenue_value').innerHTML = document.getElementById('instructor_revenue').innerHTML;
    document.getElementById('nb_commands_value').innerHTML = document.getElementById('nb_commands').innerHTML;
  });

  function update_date_range() {
    var x = $("#selectedValue").html();
    $("#date_range").val(x);
  }

  function redirect() {
    location.replace("<?php echo site_url('admin/instructor_revenue'); ?>")
  }
</script>