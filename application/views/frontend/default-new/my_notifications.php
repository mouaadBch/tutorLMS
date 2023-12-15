<?php
$AllnotificationsRead = $this->db->order_by('status ASC, id desc')->where('to_user', $logged_user_id)->where('status', 1)->get('notifications');
$AllnotificationsUnRead = $this->db->order_by('status ASC, id desc')->where('to_user', $logged_user_id)->where('status', 0)->get('notifications');
?>
<section class="page-header-area my-course-area bg-danger">
    <div class="container-fluid p-0 position-relative">
        <div class="image-placeholder-1" style="background: #ec5252d9 !important; z-index: 1;"></div>
        <img src="<?php echo base_url('assets/frontend/default/img/education4.png'); ?>" style="min-width: 100%; height: 100%; position: absolute; bottom: 0px; right: 0px;">
        <div class="container" style="position: inherit;">
            <h1 class="page-title py-5 text-white print-hidden position-relative" style="z-index: 22;"><?php echo $page_title; ?></h1>
            <img class="w-sm-25" src="<?php echo base_url('assets/frontend/default/img/education.png'); ?>" style="height: 93%; position: absolute; right: 0; bottom: 0px; z-index: 5;">
        </div>
    </div>
</section>

<section class="user-dashboard-area pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <?php include "profile_menus.php"; ?>
            </div>
            <div class="col-md-8 col-lg-9 mt-4 mt-md-0">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" aria-controls="unread" aria-selected="true">
                            <a href="#unread"> <?= site_phrase("Notification Unread") ?> </a>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab" aria-controls="read" aria-selected="false">
                            <a href="#read"> <?= site_phrase("Notification Read") ?> </a>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="unread" role="tabpanel" aria-labelledby="unread-tab">
                        <div class="p-2">
                            <?php if ($AllnotificationsUnRead->result_array()) { ?>
                                <div class="accordion accordion-flush" id="accordionFlush2">
                                    <?php foreach ($AllnotificationsUnRead->result_array() as $noti) : ?>
                                        <div class="accordion-item" style="border-bottom: 2px solid green;margin: 7px 15px;">
                                            <div class="accordion-heade" id="flush-heading<?= $noti['id'] ?>">
                                                <div class="row justify-content-center align-items-center">
                                                    <div class="col-md-8">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $noti['id'] ?>" aria-expanded="false" aria-controls="flush-collapse<?= $noti['id'] ?>">
                                                            <p><?= $noti['title'] ?></p>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="d-flex justify-content-between">
                                                            <p style="font-size: 15px;"><?= get_past_time($noti['created_at']) ?></p>
                                                            <form action="<?= site_url('home/my_notification_tools/read/unread') ?>" method="post">
                                                                <input type="hidden" name="idNotification" value="<?= $noti['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-success" title="make as read"><i class="fs-5 px-2 far fa-envelope-open"></i></button>
                                                            </form>
                                                            <form action="<?= site_url('home/my_notification_tools/remove/unread') ?>" method="post">
                                                                <input type="hidden" name="idNotification" value="<?= $noti['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-danger" title="delete"><i class="fs-5 px-2  fas fa-trash-alt"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="flush-collapse<?= $noti['id'] ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $noti['id'] ?>" data-bs-parent="#accordionFlush2">
                                                <div class="accordion-body">
                                                    <div class="text-break">
                                                        <?= $noti['description'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <form action="<?php echo site_url("/home/my_notification_tools/read"); ?>" method="get" class="text-center mt-5">
                                    <button class="text-center btn btn-success"><?= site_phrase("make all as read") ?></button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="read" role="tabpanel" aria-labelledby="read-tab">
                        <div class="p-2">
                            <?php if ($AllnotificationsRead->result_array()) { ?>
                                <div class="accordion accordion-flush" id="accordionFlush2">
                                    <?php foreach ($AllnotificationsRead->result_array() as $noti) : ?>
                                        <div class="accordion-item" style="border-bottom: 2px solid black;margin: 7px 15px;">
                                            <div class="accordion-heade" id="flush-heading<?= $noti['id'] ?>">
                                                <div class="row justify-content-center align-items-center">
                                                    <div class="col-md-8">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $noti['id'] ?>" aria-expanded="false" aria-controls="flush-collapse<?= $noti['id'] ?>">
                                                            <p><?= $noti['title'] ?></p>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="d-flex justify-content-between">
                                                            <p style="font-size: 15px;"><?= get_past_time($noti['created_at']) ?></p>
                                                            <form action="<?= site_url('home/my_notification_tools/unread/read') ?>" method="post">
                                                                <input type="hidden" name="idNotification" value="<?= $noti['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn btn-sm btn-primary py-1 px-2 mx-1" title="make as unread"><i class="fs-5 px-2 far fa-envelope"></i></button>
                                                            </form>
                                                            <form action="<?= site_url('home/my_notification_tools/remove/read') ?>" method="post">
                                                                <input type="hidden" name="idNotification" value="<?= $noti['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-danger" title="delete"><i class="fs-5 px-2  fas fa-trash-alt"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="flush-collapse<?= $noti['id'] ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $noti['id'] ?>" data-bs-parent="#accordionFlush2">
                                                <div class="accordion-body">
                                                    <div class="text-break">
                                                        <?= $noti['description'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <form action="<?php echo site_url("home/my_notification_tools/remove"); ?>" method="get" class="text-center mt-5">
                                    <button class="text-center btn btn-danger"><?= site_phrase("Remove all") ?></button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">
    $(document).ready(function() {
        if (window.location.hash.substr(1) === 'read') {
            document.getElementById('unread-tab').classList.remove('active');
            document.getElementById('unread').classList.remove('show', 'active');

            document.getElementById('read-tab').classList.add('active');
            document.getElementById('read').classList.add('show', 'active');
        }

    });

    /*function getCoursesByCategoryId(category_id) {
        $('#my_courses_area').html('<div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>');
        $.ajax({
            type: 'POST',
            url: '<?php #echo site_url('home/my_courses_by_category'); 
                    ?>',
            data: {
                category_id: category_id
            },
            success: function(response) {
                $('#my_courses_area').html(response);
            }
        });
    }
    
    function getCoursesBySearchString(search_string) {
        $('#my_courses_area').html('<div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>');
        $.ajax({
            type : 'POST',
            url : '<?php #echo site_url('home/my_courses_by_search_string'); 
                    ?>',
            data : {search_string : search_string},
            success : function(response){
                $('#my_courses_area').html(response);
            }
        });
    } */
</script>