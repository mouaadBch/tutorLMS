
<form action="<?php echo base_url('payment/cmi_payment')?>" method="post">
  <button class="gateway <?php echo $payment_gateway['identifier']; ?>-gateway payment-button float-end" id="cmiPayButton"><?php echo get_phrase("pay_with_cmi"); ?></button>
</form>

