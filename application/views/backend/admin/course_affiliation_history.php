<script src="https://js.stripe.com/v3/"></script>

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('Affiliator History'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('list_of_Course Affiliation'); ?></h4>





                </ul>

                <div class="tab-content">

                    <div class="tab-pane show active" id="completed-b1">

                        <form class="w-100" action="<?php echo site_url('addons/affiliate_course/course_affiliation_history/filter_by_date_range') ?>" method="get">

                            <div class="row justify-content-md-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                
                               
                                    <select name="dropdown_user" class="browser-default custom-select" >
                                        <option value="">Select Affiliator </option>

                                             <?php
                                             if($all_affiliator_id->num_rows()>0) :
                                             foreach ($all_affiliator_id->result_array() as  $affiliator) :
                                              

                                              $user_data = $this->user_model->get_all_user($affiliator['user_id'])->row_array(); 
                                              if(isset($user_data['id'])):
                                              ?>

                                            <option value="<?php echo $user_data['id']?>">   <?php echo $user_data['first_name']?> </option>
                                            <?php 
                                        endif;
                                        endforeach; 
                                    endif;?>

                                        </select>
                                    
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-light" style="width: 100%;">

                                            <i class="mdi mdi-calendar"></i>&nbsp;
                                            <span id="selectedValue"><?php echo date("F d, Y", $timestamp_start) . " - " . date("F d, Y", $timestamp_end); ?></span> <i class="mdi mdi-menu-down"></i>
                                        </div>
                                        <input id="date_range" type="hidden" name="date_range" value="<?php echo date("d F, Y", $timestamp_start) . " - " . date("d F, Y", $timestamp_end); ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive-sm mt-4">
                        <table id="completed-payout" class="table table-striped table-centered mb-0">
                        <caption style="caption-side: top;"><h4> Affiliators Earning History :</h4></caption>
                            <thead>
                                <tr>

                                    <th><?php echo get_phrase('Date'); ?></th>
                                    <th><?php echo get_phrase('Affiliator'); ?></th>
                                    <th><?php echo get_phrase('Course'); ?></th>

                                    <th><?php echo get_phrase('affiliated amount'); ?></th>


                                    <th><?php echo get_phrase('Buyer'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($course_affiliation_table->result_array() as $key => $table_info) :

                                    $buyer_user_data = $this->db->get_where('users', array('id' => $table_info['buyer_id']))->row_array();

                                    $referee_user_data = $this->db->get_where('users', array('id' => $table_info['referee_id']))->row_array();
                                    $course_name = $this->db->get_where('course', array('id' => $table_info['course_id']))->row_array();
                                    $user_status=$this->db->get_where('affiliator_status', array('user_id' => $table_info['referee_id']))->row_array();

                                ?>

                                    <tr class="gradeU">


                                        <td> <strong> <?php echo date('m/d/Y', $table_info['date_added']); ?></strong> </td>

                                     <?php   if (isset($referee_user_data['id']) && isset($user_status['user_id'])) : ?>
                                        <td> <strong> <?php echo  $referee_user_data['first_name']; ?></strong> 

                                        <?php else:?>

                                               <span class="badge  badge-danger"><?php echo get_phrase('affiliator_deleted'); ?></span>

                                        <?php endif;?>
                                  
                                        <?php 
                                     
                                    
                                        if (isset($user_status['status']) && $user_status['status'] == 1) : ?>
                                                    <small>
                                                        <p><?php echo get_phrase('status'); ?>: <span class="badge  badge-success"><?php echo get_phrase('active'); ?></span></p>
                                                    </small>
                                                <?php elseif(isset($user_status['status'])&&$user_status['status'] == 2): ?>

                                                    <small>
                                                        <p><?php echo get_phrase('status'); ?>: <span class="badge  badge-warning"><?php echo get_phrase('suspended'); ?></span></p>
                                                    </small>


                                                <?php endif; ?>

                                                
                              
                                    </td>
                                        <td> <strong> <?php echo  $course_name['title']; ?></strong> </td>
                                        <td> <strong> <?php echo  $table_info['amount']; ?></strong> </td>


                                        <td> <strong> <?php echo  $buyer_user_data['first_name']; ?></strong> </td>




                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <span>Export</span>
    
                        <!-- <button class="btn btn-custom" id="download-button"> CSV</button> -->
                        <a class="btn btn-custom" href=<?php echo site_url('addons/affiliate_course/download_csv')?>>CSV</a>
                        <button class="btn btn-custom" onclick="Export()">PDF </button>
                    </div>
                </div>

            </div>
        </div> <!-- end card-body-->
    </div> <!-- end card-->
</div>
</div>
</div>
</div> <!-- end card-body-->
</div> <!-- end card-->
</div>
</div>


<style>

#completed-payout{
  caption-side: top;
}
    .btn-custom {
        color: #fff;
        background-color: #39afd1;

        padding: 7.5px 10px;
        border-radius: 10px !important;
        line-height: 1.35135;
        font-weight: 600;
        margin-left: 5px !important;
    }

    .btn-custom:hover {
        background-color: #c33333;
        color: white;
    }
</style>



<script type="text/javascript">
    $(document).ready(function() {
        initDataTable(['#pending-payout', '#completed-payout']);
    });

    function update_date_range() {
        var x = $("#selectedValue").html();
        $("#date_range").val(x);
    }

    function stripe_checkout(stripe_public_key, payout_id, payout_type) {

        var createCheckoutSession = function(stripe) {
            console.log(payout_type);
            return fetch("<?= site_url('admin/stripe_checkout_for_instructor_revenue/'); ?>" + payout_id + '/' + payout_type, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    checkoutSession: 1,
                }),
            }).then(function(result) {
                return result.json();
            });
        };

        createCheckoutSession().then(function(data) {
            if (data.sessionId) {
                Stripe(stripe_public_key).redirectToCheckout({
                    sessionId: data.sessionId,
                }).then(handleResult);
            } else {
                handleResult(data);
            }
        });
    }


</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
    function Export() {
        html2canvas(document.getElementById('completed-payout'), {
            onrendered: function(canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("Affiliators_Earning_History.pdf");
            }
        });
    }
</script>


