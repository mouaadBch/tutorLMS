<?php
$instructor_id = $this->session->userdata('user_id');
$number_of_courses = $this->crud_model->get_instructor_wise_courses($instructor_id)->num_rows();
$number_of_enrolment_result = $this->crud_model->instructor_wise_enrolment($instructor_id);
if ($number_of_enrolment_result) {
    $number_of_enrolment = $number_of_enrolment_result->num_rows();
} else {
    $number_of_enrolment = 0;
}
$total_pending_amount = $this->crud_model->get_total_pending_amount($instructor_id);
$requested_withdrawal_amount = $this->crud_model->get_requested_withdrawal_amount($instructor_id);
?>


<div class="row align-items-center ">
    <?php
    $subject = "Mon profil sur ostadi.org";
    $link_profile = site_url("home/instructor_page/$instructor_id");
    $body = 'Merci de consultez mon profil sur ostadi. org : ' . $link_profile;
    ?>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('dashboard'); ?> </h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card">
            <div class="card-body row">
                <div class="col-6 mb-2">
                    <h4 class="header-title">
                        <?= site_phrase('Share My profile') ?> :
                    </h4>
                    <!-- <i class="fas fa-share-square"></i> --> 
                </div>
                <div class="col-6 text-right">
                    <a href="<?= $link_profile ?>" target="_blank">
                        <i class="fas fa-eye"></i> <?= site_phrase('my profile') ?>
                    </a>
                </div>
                <div class="col-md-12 ">
                    <div class="social-buttons" style="display: flex;flex-wrap: nowrap;align-items: center;justify-content: space-evenly;">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $link_profile ?>" class="share-btn" target="_blank" title="Share on Facebook">
                            <i class="fab fa-facebook-square fa-2x" style="color: #1877F2;"></i>
                        </a>
                        <!-- WhatsApp -->
                        <a href="https://wa.me/?text=<?= $subject . ' ' . $link_profile ?>" class="share-btn" target="_blank" title="Share on WhatsApp">
                            <i class="fab fa-whatsapp fa-2x" style="color: #25D366;"></i>
                        </a>
                        <!-- Gmail -->
                        <a href="mailto:?subject=<?= $subject ?>&body=<?= $body ?>" class="share-btn" target="_blank" title="Share via Gmail">
                            <i class="fas fa-envelope fa-2x" style="color: #D44638;"></i>
                        </a>
                        <!-- Twitter -->
                        <a href="https://twitter.com/share?url=<?= $link_profile ?>" class="share-btn" target="_blank" title="Share on Twitter">
                            <i class="fab fa-twitter fa-2x" style="color: #1DA1F2;"></i>
                        </a>
                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url=<?= $link_profile ?>&text=<?= $subject ?>" class="share-btn" target="_blank" title="Share on Telegram">
                            <i class="fab fa-telegram-plane fa-2x" style="color: #0088cc;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4"><?php echo get_phrase('instructor_revenue'); ?></h4>
                <div class="mt-3 chartjs-chart" style="height: 320px;">
                    <canvas id="task-area-chart"></canvas>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-body p-0">
                <div class="row no-gutters">
                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('user/courses'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0">
                                <div class="card-body text-center">
                                    <i class="dripicons-archive text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $number_of_courses; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('number_of_courses'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="card shadow-none m-0 border-left">
                            <div class="card-body text-center">
                                <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                <h3><span><?php echo $number_of_enrolment; ?></span></h3>
                                <p class="text-muted font-15 mb-0"><?php echo get_phrase('number_of_enrolment'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('user/payout_report'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-inbox text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $total_pending_amount > 0 ? currency($total_pending_amount) : currency_code_and_symbol() . '' . $total_pending_amount; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('pending_balance'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('user/payout_report'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-pin text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $requested_withdrawal_amount > 0 ? currency($requested_withdrawal_amount) : currency_code_and_symbol() . '' . $requested_withdrawal_amount; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('requested_withdrawal_amount'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div> <!-- end row -->
            </div>
        </div> <!-- end card-box-->
    </div> <!-- end col-->
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4"><?php echo get_phrase('course_overview'); ?></h4>
                <div class="my-4 chartjs-chart" style="height: 202px;">
                    <canvas id="project-status-chart"></canvas>
                </div>
                <div class="row text-center mt-2 py-2">
                    <div class="col-6">
                        <i class="mdi mdi-trending-up text-success mt-3 h3"></i>
                        <h3 class="font-weight-normal">
                            <span><?php echo $this->crud_model->get_status_wise_courses_for_instructor('active')->num_rows(); ?></span>
                        </h3>
                        <p class="text-muted mb-0"><?php echo get_phrase('active_courses'); ?></p>
                    </div>
                    <div class="col-6">
                        <i class="mdi mdi-trending-down text-warning mt-3 h3"></i>
                        <h3 class="font-weight-normal">
                            <span><?php echo $this->crud_model->get_status_wise_courses_for_instructor('pending')->num_rows(); ?></span>
                        </h3>
                        <p class="text-muted mb-0"> <?php echo get_phrase('pending_courses'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>