<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('purchase_history'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="card">
  <div class="card-body">
    <!-- code mouaad -->
    <form class=" " action="<?php echo site_url('admin/purchase_history/filter_by_date_range') ?>" method="get">
      <div class="row justify-content-md-center mb-2">
        <div class="col-xl-1">
          <label for="date_range"><?php echo get_phrase('date'); ?>: </label>
        </div>
        <div class="col-xl-5">  
            <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-light" style="width: 100%;">
              <i class="mdi mdi-calendar"></i>&nbsp;
              <span id="selectedValue"><?php echo date("F d, Y", $timestamp_start) . " - " . date("F d, Y", $timestamp_end); ?></span> <i class="mdi mdi-menu-down"></i>
            </div>
            <input id="date_range" type="hidden" name="date_range" value="<?php echo date("d F, Y", $timestamp_start) . " - " . date("d F, Y", $timestamp_end); ?>">
        </div>
        <div class="col-xl-2">
          <div class="d-flex">
            <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter'); ?></button>
            <button type="button" class="btn btn-info btn-sm mx-1" onclick="redirect()"> <?php echo get_phrase('remove'); ?></button>
          </div>
        </div>
      </div>
    </form>
    <!-- code mouaad -->
    <?php $amount = 0;
    $instructor_revenue = 0;
    $nb_commands = 0; ?>
    <div class="card">
      <div class="card-body border border-info rounded p-2">
        <div class="row justify-content-center align-items-center g-2">
          <div class="col-md-4">
            <p class="text-center text-info" style="font-weight: 800;font-size: 19px;"> <?php echo get_phrase('Total amount'); ?>: </p>
            <p class="text-center" style="font-size: 20px;" id="amount_value"></p>
          </div>
          <div class="col-md-4">
            <p class="text-center text-info" style="font-weight: 800;font-size: 19px;"> <?php echo get_phrase('Instructor revenue'); ?>: </p>
            <p class="text-center" style="font-size: 20px;" id="instructor_revenue_value"></p>
          </div>
          <div class="col-md-4">
            <p class="text-center text-info" style="font-weight: 800;font-size: 19px;"> <?php echo get_phrase('Number orders'); ?>: </p>
            <p class="text-center" style="font-size: 20px;" id="nb_commands_value"></p>
          </div>
        </div>
      </div>
    </div>
    <table id="basic-datatable" class="table table-striped table-centered mb-0">
      <thead>
        <tr>
          <th>id</th>
          <th><?php echo get_phrase('user'); ?></th>
          <th><?php echo get_phrase('course'); ?></th>
          <th><?php echo get_phrase('instructeur'); ?></th>
          <th><?php echo get_phrase('paid_amount'); ?></th>
          <th><?php echo get_phrase('payment_method'); ?></th>
          <th><?php echo get_phrase('purchased_date'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($purchase_history->result_array() as $purchase) :
          $user_data = $this->db->get_where('users', array('id' => $purchase['user_id']))->row_array();
          $course_data = $this->db->get_where('course', array('id' => $purchase['course_id']))->row_array(); ?>
          <tr class="gradeU">
            <td> <?= $purchase['id'] ?></td>
            <td>
              <?php echo ($user_data['first_name'] ?? null) . ' ' . ($user_data['last_name'] ?? null); ?><br>
              <small class="badge badge-light"><?php echo $user_data['email'] ?? null; ?></small>
            </td>
            <?php if (isset($course_data['id'])) : ?>
              <td class="w-25">
                <a href="<?php echo site_url('home/course/' . rawurlencode(slugify($course_data['title'])) . '/' . $course_data['id']); ?>" target="_blank">
                  <?php echo $course_data['title']; ?>
                </a>
              </td>
              <td>
                <?php
                $instructeur_data = $this->db->get_where('users', array('id' =>  $course_data['creator']))->row_array();
                echo $instructeur_data['first_name'] . ' ' . $instructeur_data['last_name'];
                ?>
                <small class="badge badge-light"><i class="fas fa-money-bill-wave"></i> : <?php echo currency($purchase['instructor_revenue'] ?? null); ?> </small>
              </td>
            <?php else : ?>
              <td>-</td>
              <td>-</td>
            <?php endif; ?>
            <td>
              <?php echo currency($purchase['amount'] + $purchase['tax']); ?><br>
            </td>
            <td><?php echo ucfirst($purchase['payment_type']); ?></td>
            <td><?php echo date('d-m-Y', $purchase['date_added']); ?></td>
          </tr>
        <?php $amount += $purchase['amount'];
          $instructor_revenue += $purchase['instructor_revenue'];
          $nb_commands++;
        endforeach; ?>
      </tbody>
    </table>

    <p id="amount" class="invisible"><?php echo currency($amount); ?></p>
    <p id="instructor_revenue" class="invisible"><?php echo currency($instructor_revenue); ?></p>
    <p id="nb_commands" class="invisible"><?php echo $nb_commands; ?></p>

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
    location.replace("<?php echo site_url('admin/purchase_history'); ?>")
  }
</script>