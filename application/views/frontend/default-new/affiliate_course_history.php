<?php

$CI    = &get_instance();
$CI->load->model('addons/affiliate_course_model');



$user_id = $this->session->userdata('user_id');
$course_affiliation_tableinfo = $this->affiliate_course_model->get_affiliate_course_table_info_by_user($user_id);
$count = 0;
$te = 0;


if ($course_affiliation_tableinfo->num_rows() > 0) {
  foreach ($course_affiliation_tableinfo->result_array() as $total_earning_history) {
    $te = $te + $total_earning_history['amount'];
  }
}



//get info for withdrawl

$w = $this->affiliate_course_model->get_withdrawl_request_info_for_referral_course_amount($user_id);
$total_withdraw_amount = 0;
$pending = 0;
$p_id = 0;



if ($w->num_rows() > 0) {
  foreach ($w->result_array() as $withdrale_history) {
    $total_withdraw_amount = $total_withdraw_amount + $withdrale_history['amount'];

    if ($withdrale_history['status'] == "pending") {
      $pending = $withdrale_history['amount'];
      $p_id = $withdrale_history['user_id'];
    }
  }
}


?>

<style>
 .borderexample {
    border-style: solid;
    border-color: #287EC7;
    color: #19619ced;
    line-height: 2.5;
  }
  tbody, td, tfoot, th, thead, tr{
    border-bottom: 1px solid #ddd !important;
  }
  .min-width-180px{
    min-width: 150px;
  }
.affiliate_modal .modal-body label{
   margin-bottom: 7px;
}
.affiliate_modal .btn-close {
	line-height: 1em;
	color: #000;
	background:#eee;
	opacity: 1;
}
.affiliate_modal  .input-group  .form-control {
      background: #261954;
      color: #fff;
      border-left: 1px solid #fff;
  }
  .affiliate_modal  .input-group{
    display:flex;
  }
  .modal_form{
    padding: 0 20px;
    color:#fff;
  }
  .affiliate_modal .modal-footer{
    border-top: none;
  }
  .btn-customwith {
	color: #fff;
	background-color: #754FFE;
	padding: 9.5px 18px;
	border-radius: 5px !important;
	line-height: 0.8;
	font-weight: 600;
	margin-left: 5px !important;
	font-size: 15px;
}
  .btn-custom {
    color: #fff;
    background-color: #754FFE;
    padding: 7.5px 10px;
    border-radius: 5px !important;
    line-height: 1.35135;
    font-weight: 600;
    margin-left: 5px !important;
  }
  .btn-customwith:hover,
  .btn-custom:hover {
    background-color: transparent;
    color: #676C7D;
    border: 1px solid #676C7D;
  }
  .user-dashboard-area .nav-pills .nav-link.active, 
  .user-dashboard-area .nav-pills .show > .nav-link{
    background-color: #754FFE;
  }
.total_text{
  font-size:18px;
}
.bg-card{
  color: #676C7D;
}
.nav-pills .nav-link{
  color: #000;
}
.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    background-color: #754FFE;
    color: #fff;
}
.history-affi span{
  font-size: 12px;;
  display:inline-block;
  color: #676C7D;
}
.text-head {
    font-size: 16px;
    color: #000;
    font-weight: 500;
}
</style>

<?php include "breadcrumb.php"; ?>


<section class="wish-list-body pt-3 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <?php include "profile_menus.php"; ?>
            </div>
            <div class="col-md-8 col-lg-9 mt-5  my-course-1-full-body ">
              <div class="row justify-content-center">
                <div class="col-lg-6 py-4 pull-right">
                  <div class="card bg-card">
                    <div class="card-body">
                      <div class="float-right bg-white">
                        <i class="mdi mdi-currency-usd widget-icon text-secondary"></i>
                      </div>
                      <h5 class="total_text" title="<?php echo get_phrase('Total Affiliate Earnings :');?>"><?php echo get_phrase('Total Affiliate Earnings :');?></h5>
                      <h3 class="mt-3 mb-3">
                        <span class="text-white"><i class="mdi mdi-arrow-down-bold"></i></span>
                        <?php echo currency($te);
                        ?>
                      </h3>
                    </div> <!-- end card-body-->
                  </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6 py-4 pull-right">
                  <div class="card bg-card">
                    <div class="card-body">
                      <div class="float-right bg-white">
                        <i class="mdi mdi-currency-usd widget-icon text-success"></i>
                      </div>
                      <h5 class="total_text" title="<?php echo get_phrase('Available balance to withdrawal');?>"><?php echo get_phrase('Available balance to withdrawal');?></h5>
                      <?php
                      $view = $te - $total_withdraw_amount;
                      if ($view > 0) :
                      ?>
                        <h3 class="mt-3"><span class="text-white"><i class="mdi mdi-arrow-down-bold"></i></span> <?php echo currency($view); ?></h3>

                        <button style="float: right; margin-top: -21px;" type="button" class="btn btn-customwith" data-bs-toggle="modal" data-bs-target="#exampleModal"> Request a withdrawal </button>

                      <?php else :
                      ?>
                        <h3 class="mt-3 mb-3"><span class="text-white"><i class="mdi mdi-arrow-down-bold"></i></span> <?php echo currency("0");?></h3>
                      <?php endif;
                      ?>
                    </div> <!-- end card-body-->
                  </div> <!-- end card-->
                </div> <!-- end col-->
              </div>




              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo get_phrase('earning_history'); ?></button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo get_phrase('withdrawal_history'); ?></button>
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                  <ul class="purchase-history-list">
                    <?php if ($course_affiliation_tableinfo->num_rows() > 0) : ?>
                      <div class="table-responsive">
                        <table class="table" id="Affiliate_Earning_History">
                            <thead>
                              <tr>
                                <th scope="col" class="min-width-180px"><p class="text-head">Course</p></th>
                                <th scope="col" ><p class="text-head">Actual Amount </p></th>
                                <th scope="col"><p class="text-head">Earned Amount </p></th>
                                <!-- <th scope="col" class="min-width-180px">Note </th> -->
                                <!-- <th scope="col" class="min-width-180px">Bought by </th> -->
                                <th scope="col"><p class="text-head">Date</p></th>
                              </tr>
                            </thead>
                          <?php endif; ?>

                          <tbody>
                            <?php if ($course_affiliation_tableinfo->num_rows() > 0) :

                              foreach ($course_affiliation_tableinfo->result_array() as $each_history) : ?>
                                <?php
                                $user_name = $this->db->get_where('users', array('id' => $each_history['buyer_id']))->row_array();
                                $course_name = $this->db->get_where('course', array('id' => $each_history['course_id']))->row_array();  ?> <tr>
                                  <td class="min-width-180px">
                                    <div class="history-affi">
                                        <p><?php echo $course_name['title'] ?? null; ?></p>
                                         <span>Broght By: <?php echo $user_name['first_name'] ?></span>
                                    </div>
                                  </td>
                                  <td class="min-width-180px">
                                    <div class="history-affi">
                                        <p><?php echo currency($each_history['actual_amount']) ?></p>
                                    </div>
                                  </td>
                                  <td class="min-width-180px">
                                    <div class="history-affi">
                                        <p><?php echo  currency($each_history['amount']) ?></p>
                                        <span>Commission: <?php echo currency($each_history['percentage'].'%') ?></span>
                                    </div>
                                   </td>
                                  <!-- <td class="min-width-180px"><?php //echo $each_history['note'] ?></td> -->

                                  <!-- <td class="min-width-180px"></td> -->
                                  <td scope="row">
                                    <div class="antry-affi">
                                        <p> <?php echo date('m/d/Y', $each_history['date_added']) ?></p>
                                    </div>
                                  </td>
                                </tr>
                                  <?php $count = $count + $each_history['amount']; ?>
                              <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                       <div class="mt-3">
                         <span style="color:#676C7D ;  "><b>Export</b></span>
                         <button class="btn btn-custom" id="download-button">CSV</button>
                          <button class="btn btn-custom" onclick="Export()">PDF </button>
                       </div>
                    <?php else : ?>
                      <li>
                        <div class="row">
                          <div class="col-md-12 text-center">
                            <img style="padding-bottom: 25px;" src="<?php echo base_url('assets/image/no_data_found.png'); ?>" alt="<?php echo site_phrase('no_records_found'); ?>" width="400px" style="">
                          </div>
                        </div>
                      </li>
                    <?php endif; ?>
                  </ul>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                  <?php if ($w->num_rows() > 0) : ?>
                    <div class="table-responsive">
                      <table class="table" id="Withdrawal_History">
                          <thead>
                            <tr>
                              <th scope="col"><p class="head-text">Date</p></th>
                              <th scope="col"><p class="head-text">Amount</p></th>
                              <th scope="col"><p class="head-text">Status</p></th>
                              <th scope="col"><p class="head-text">Action</p></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($w->result_array() as $withdrale_history) : ?>
                              <tr>
                                <td>
                                  <div class="antry-affi">
                                     <p><?php echo date("Y-m-d", (int)$withdrale_history['date']); ?></p>
                                  </div>
                                </td>
                                <td>
                                  <div class="antry-affi">
                                     <p><?php echo currency($withdrale_history['amount']); ?></p>
                                  </div>
                                </td>
                                <td>
                                  <div class="antry-affi">
                                     <p><?php echo $withdrale_history['status'] ?></p>
                                  </div>
                                </td>
                                <?php if ($withdrale_history['status'] == "pending") : ?>
                                  <td><a href="#" onclick="confirm_modal('cancel_user_pending_course/?userid=<?php echo $withdrale_history['user_id'] ?>');" type="button" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
                                <?php else : ?>
                                  <td><?php echo "" ?></td>
                                <?php endif; ?>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                      </table>
                    </div>        
                   <div class="mt-3">
                    <span style="color:#676C7D ;  "><b>Export</b></span>
                    <button class="btn btn-custom" id="download_csv">CSV</button>
                    <button class="btn btn-custom" onclick="Export1()">PDF </button>
                   </div>
                  <?php else : ?>
                    <ul class="purchase-history-list">
                      <li>
                        <div class="row" style="text-align: center;">
                          <?php echo site_phrase(''); ?>
                        </div>
                      </li>
                    </ul>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
            <script type="text/javascript">
              function Export() {
                html2canvas(document.getElementById('Affiliate_Earning_History'), {
                  onrendered: function(canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                      content: [{
                        image: data,
                        width: 500
                      }]
                    };
                    pdfMake.createPdf(docDefinition).download("Affiliate_Earning_History.pdf");
                  }
                });
              }
            </script>
          </div>
        </div>

</section>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
  function Export1() {
    html2canvas(document.getElementById('Withdrawal_History'), {
      onrendered: function(canvas) {
        var data = canvas.toDataURL();
        var docDefinition = {
          content: [

            {
              image: data,
              width: 800
            }
          ]

        };
        pdfMake.createPdf(docDefinition).download("Withdrawal_History.pdf");
      }
    });
  }
</script>




<!--modal  for payment status table  request Modal -->


<!-- modal for withdrawl request Modal -->
<div class="modal fade affiliate_modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo site_phrase('Withdrawal Available Earning');?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
        <form class="modal_form" action="<?php echo site_url('addons/affiliate_course/withdrawl_request_for_course_amount'); ?>" method="post" id="withdrawl">

          <div class="form-group">
            <label for="withdrawlv"><?php echo site_phrase('Request Withdrawl'); ?> <span id="check_mobile_response_else" class=text-success> </span><span id="check_mobile_response_if" class=text-danger> </span> </label>
            <div class="input-group">
              <span class="input-group-text bg-white" for="withdrawl_reff"> <i class="fas fa-dollar-sign"></i> </span>
              <input type="number" step="any" name="withdrawl_reff" max=<?= $view ?> class="form-control" placeholder="<?php echo site_phrase('Enter Withdrawl Amount'); ?>" id="withdrawl_reff" required>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-custom">Send Withdrawl Request</button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  function downloadCSVFile(csv, filename) {
    var csv_file, download_link;

    csv_file = new Blob([csv], {
      type: "text/csv"
    });

    download_link = document.createElement("a");

    download_link.download = filename;

    download_link.href = window.URL.createObjectURL(csv_file);

    download_link.style.display = "none";

    document.body.appendChild(download_link);

    download_link.click();
  }

  document.getElementById("download-button").addEventListener("click", function() {
    var html = document.querySelector("#Affiliate_Earning_History").outerHTML;
    htmlToCSV(html, "Affiliate_Earning_History.csv");
  });





  function htmlToCSV(html, filename) {
    var data = [];
    var rows = document.querySelectorAll("#Affiliate_Earning_History tr");

    for (var i = 0; i < rows.length; i++) {
      var row = [],
        cols = rows[i].querySelectorAll("td, th");

      for (var j = 0; j < cols.length; j++) {
        row.push(cols[j].innerText);
      }

      data.push(row.join(","));
    }

    //to remove table heading
    //data.shift()

    downloadCSVFile(data.join("\n"), filename);
  }


  document.getElementById("download_csv").addEventListener("click", function() {
    var html = document.querySelector("#Withdrawal_History").outerHTML;
    htmlToCSV2(html, "Withdrawal_History.csv");
  });


  function htmlToCSV2(html, filename) {
    var data = [];
    var rows = document.querySelectorAll("#Withdrawal_History tr");

    for (var i = 0; i < rows.length; i++) {
      var row = [],
        cols = rows[i].querySelectorAll("td, th");

      for (var j = 0; j < cols.length - 1; j++) {
        row.push(cols[j].innerText);
      }

      data.push(row.join(","));
    }

    //to remove table heading
    //data.shift()

    downloadCSVFile(data.join("\n"), filename);
  }
</script>