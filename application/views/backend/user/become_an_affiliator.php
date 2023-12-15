<?php
$CI    = &get_instance();
$CI->load->model('addons/affiliate_course_model');

$applications = $this->affiliate_course_model->get_applications_for_becoming_an_affiliator($this->session->userdata('user_id'), 'user');
?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('become_an_afiiliator'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<?php
$x = $this->affiliate_course_model->is_affilator($this->session->userdata('user_id'));
if ($x == 0) : ?>
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <?php if ($applications->num_rows() == 0) : ?>
                        <?php include 'affiliator_application_form.php'; ?>
                    <?php else : ?>
                        <?php include 'affiliator_application_list.php'; ?>
                    <?php endif; ?>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    </div>
<?php elseif($x==2) : ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><?php echo get_phrase('sorry'); ?>!</h4>
        <p><?php echo get_phrase('currently_you_are_suspened'); ?></p>
    </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><?php echo get_phrase('congratulations'); ?>!</h4>
        <p><?php echo get_phrase('you_are_already_an_affiliator'); ?></p>
    </div>
<?php endif; ?>


<style media="screen">
    body {
        overflow-x: hidden;
    }
</style>