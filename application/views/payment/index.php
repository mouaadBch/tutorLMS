<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $page_title . ' | ' . get_settings('system_name'); ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="author" content="<?php echo get_settings('author') ?>" />

	<meta name="keywords" content="<?php echo get_settings('website_keywords'); ?>" />
	<meta name="description" content="<?php echo get_settings('website_description'); ?>" />

	<link name="favicon" type="image/x-icon" href="<?php echo base_url('uploads/system/' . get_frontend_settings('favicon')); ?>" rel="shortcut icon" />
	<?php include 'includes_top.php'; ?>
	<!-- code Mouaad -->
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

</head>

<body>
	<?php
	if ($this->session->userdata('app_url')) :
		include "go_back_to_mobile_app.php";
	endif;
	echo '<div class="container-fluid">';
	include 'payment_gateway.php';
	echo '</div>';

	include 'includes_bottom.php';
	if (get_frontend_settings('cookie_status') == 'active') :
		include 'eu-cookie.php';
	endif;
	?>
</body>

</html>