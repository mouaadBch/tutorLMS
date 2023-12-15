<html>
<head>
	<title> CCAvenue Payment Gateway Integration kit</title>
</head>
<body>
	<center>
		<?php
			//ended common code of all payment gateway
			define("CCA_ACCESS_CODE", $keys['ccavenue_access_code']);
			error_reporting(0);

			$access_code = CCA_ACCESS_CODE;
		?>
		<form method="post" name="redirect" action="<?php echo $form_action; ?>">
			<input type="hidden" name="encRequest" value="<?php echo $encrypted_data; ?>">
			<input type="hidden" name="access_code" value="<?php echo $access_code; ?>">
		</form>
	</center>
	<script language='javascript'>document.redirect.submit();</script>
</body>
</html>