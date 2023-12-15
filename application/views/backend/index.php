<?php
$system_name = $this->db->get_where('settings', array('key' => 'system_name'))->row()->value;
$system_title = $this->db->get_where('settings', array('key' => 'system_title'))->row()->value;
$user_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
$text_align     = $this->db->get_where('settings', array('key' => 'text_align'))->row()->value;
$logged_in_user_role = strtolower($this->session->userdata('role'));
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo get_phrase($page_title); ?> | <?php echo $system_title; ?></title>
    <!-- all the meta tags -->
    <?php include 'metas.php'; ?>
    <!-- all the css files -->
    <?php include 'includes_top.php'; ?>

    <!--  code Mouaad -->
    <style type="text/css" media="print">
        * {
            display: none;
        }
    </style>
    <!-- Google tag (gtag.js) 
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y7ZNR3T682"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-Y7ZNR3T682');
    </script>-->
    <!-- / code Mouaad -->

    <style>
        .button-menu-mobile span {
            background-color: #e85555 !important;
            height: 3px !important;
        }

        .button-menu-mobile .lines {
            width: 24px;
        }
    </style>

</head>

<!--  code Mouaad -->

<body data-layout="detached" style="user-select:none;">
    <!-- / code Mouaad -->

    <!-- HEADER -->
    <?php include 'header.php'; ?>
    <div class="container-fluid">
        <div class="wrapper">
            <!-- BEGIN CONTENT -->
            <!-- SIDEBAR -->
            <?php include $logged_in_user_role . '/' . 'navigation.php' ?>
            <!-- PAGE CONTAINER-->
            <div class="content-page">
                <div class="content">
                    <!-- BEGIN PlACE PAGE CONTENT HERE -->
                    <?php include $logged_in_user_role . '/' . $page_name . '.php'; ?>
                    <!-- END PLACE PAGE CONTENT HERE -->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
    </div>
    <!-- all the js files -->
    <?php include 'includes_bottom.php'; ?>
    <?php include 'modal.php'; ?>
    <?php include 'common_scripts.php'; ?>

    <!-- code Mouaad -->
    <script>
        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
        }, false);
        window.onbeforeprint = function(event) {
            event.preventDefault(); // Cancel the print operation
        };
    </script>
    <!-- / code Mouaad -->
</body>

</html>