
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?>
                    <a href="<?php echo site_url('addons/affiliate_course/affiliator_form'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_affiliator'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

            <ul class="nav nav-tabs nav-bordered mb-3">
                    <li class="nav-item">
                        <a href="<?php echo site_url('addons/affiliate_course/active_affiliator'); ?>"   class="nav-link <?php if ($page_name == 'active_affiliator') echo 'active'; ?>">
                            <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block"><?php echo get_phrase('Active Affiliator '); ?><span class="badge badge-success"><?php echo $active_affiliator->num_rows(); ?></span></span>
                        </a>
                    </li>
                  
            
                    <li class="nav-item">
                    <a href="<?php echo site_url('addons/affiliate_course/suspend_affiliator'); ?>" class="nav-link <?php if ($page_name == 'suspend_affiliator' ) echo 'active'; ?>">
                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block"><?php echo get_phrase('Suspend Affiliator '); ?> <span class="badge badge-warning"><?php echo $suspend_affiliator->num_rows(); ?></span></span>
                        </a>
                    </li>
              

                        <li class="nav-item">
                        <a   href="<?php echo site_url('addons/affiliate_course/pending_affiliator'); ?>"   class="nav-link <?php if ( $page_name == 'pending_affiliator') echo 'active'; ?>">
                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block"><?php echo get_phrase('pending Affiliator '); ?><span class="badge badge-danger-lighten"><?php echo $pending_affiliator->num_rows(); ?></span> </span>
                        </a>
                    </li>

                    </ul>


                <h4 class="mb-3 header-title"><?php //echo get_phrase('list_of_applications'); ?></h4>

                <div class="tab-content">
                    <div class="tab-pane show active" id="pending-b1">
                        <div class="table-responsive-sm mt-4">
                            <table id="pending-application" class="table table-striped table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo get_phrase('name'); ?></th>
                                        <th><?php echo get_phrase('address'); ?></th>
                                        <th><?php echo get_phrase('phone'); ?></th>
                                        <th><?php echo get_phrase('message'); ?></th>
                                        <th><?php echo get_phrase('document'); ?></th>
                                        <th><?php echo get_phrase('actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($pending_affiliator->result_array() as $key => $user) :
                                        $CI    = &get_instance();
                                        $CI->load->model('addons/affiliate_course_model');
                                        $user_name = $this->affiliate_course_model->get_userby_id($user['user_id']);


                                    ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>

                                            <td><?php echo $user_name['first_name'] . ' ' . $user_name['last_name']; ?>
                                                <?php if ($user['status'] != 1) : ?>
                                                    <small>
                                                        <p><?php echo get_phrase('status'); ?>: <span class="badge badge-danger-lighten"><?php echo get_phrase('unverified'); ?></span></p>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $user['address']; ?></td>
                                            <td><?php echo $user['phone']; ?></td>
                                            <td><?php echo $user['message']; ?></td>
                                            <td>
                                                <?php if (!empty($user['document'])) : ?>
                                                    <a href="<?php echo site_url( "addons/affiliate_course/pending_affiliator/download/".$user['user_id']); ?>" class="btn btn-info" download>
                                                        <i class="fa fa-download"></i> <?php echo get_phrase('download'); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </td>


                                            <td>
                                                <div class="dropright dropright">
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/affiliate_course/pending_affiliator/approve/' . $user['user_id']); ?>');"><?php echo get_phrase('approve'); ?></a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('addons/affiliate_course/pending_affiliator/delete/' . $user['user_id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        initDataTable(['#pending-application', '#approved-application']);
    });
</script>