<form action="<?php echo site_url('addons/paytm/payThroughPaytm/'.$payment_gateway['identifier']); ?>" method="post" class="gateway <?php echo $payment_gateway['identifier']; ?>-gateway">

	<input type="text" class="d-none" id="user" tabindex="4" maxlength="12" size="12" name="user" autocomplete="off" value="<?php echo htmlspecialchars($user_details['first_name']).' '.htmlspecialchars($user_details['last_name']); ?>" readonly style="background: none;">
    <!-- Default input -->
    <input type="text" class="form-control d-none" id="amount_to_pay" tabindex="4" maxlength="12" size="12" name="amount_to_pay" autocomplete="off" value="<?php echo htmlspecialchars($payment_details['total_payable_amount']); ?>" readonly style="background: none;">
    <button type="submit" class="payment-button float-end"><?php echo get_phrase('pay_by_paytm'); ?></button>
</form>