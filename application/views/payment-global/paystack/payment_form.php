<script src="https://js.paystack.co/v1/inline.js"></script> 
<button type="button" class="gateway <?php echo $payment_gateway['identifier']; ?>-gateway payment-button float-end" onclick="payWithPaystack()"><?php echo get_phrase('pay_by_paystack'); ?></button>

<?php
//start common code of all payment gateway
if($payment_details['is_instructor_payout_user_id'] > 0){
  $instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
  $test_mode = 0;
  $keys = json_decode($instructor_details['payment_keys'], true);
  $keys = $keys[$payment_gateway['identifier']];
}else{
  $keys = json_decode($payment_gateway['keys'], true);
  if($payment_gateway['enabled_test_mode'] == 1){
    $test_mode = 1;
  }else{
    $test_mode = 0;
  }
}
//ended common code of all payment gateway
if($test_mode == 1){
  $key =  $keys['public_test_key'];
} else {
  $key =  $keys['public_live_key'];
}
$amount = $payment_details['total_payable_amount'];
$user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
?>

<!-- place below the html form -->
<script >
  function payWithPaystack(){
    var handler = PaystackPop.setup({
      key: '<?php echo $key; ?>',
      email: '<?php echo $user_details['email']; ?>',
      amount: '<?php echo $amount*100; ?>',
      currency: "<?php echo get_settings('paystack_currency'); ?>",
      metadata: {
        custom_fields: [
          {
            display_name: "<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>",
            variable_name: "paid_on",
            value: '<?php echo site_url('home'); ?>'
          }
        ]
      },
      callback: function(response){
        location.replace("<?php echo $payment_details['success_url'].'/'.$payment_gateway['identifier']; ?>?reference="+response.reference);
      },
      onClose: function(){
        location.replace("<?php echo $payment_details['cancel_url']; ?>");
      }
    });
    handler.openIframe();
  }
</script>