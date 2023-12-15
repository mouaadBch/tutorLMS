<?php
$payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $payment_gateway['identifier']])->row_array();
?>
<div id="marocpayPaymentResponse" class="text-danger"></div>
<!-- Buy button -->
<div class="gateway <?php echo $payment_gateway['identifier']; ?>-gateway">
    <div style="padding: 20px;width: 100%;background-color: #E6E9FC;margin: 20px 0;border-radius: 10px;">
        <?php echo get_phrase('text_marocpay'); ?>
    </div>
    <?php

    $myarray = [];

    /* $link = str_replace(['é', 'è', 'É', 'È'], 'e', strtolower($payment_details['items'][0]['title']));
    $link = str_replace(['-'], ' ', $link);
    $link = preg_replace('/\s{2,}/', ' ', $link);
    $link = str_replace([' '], '-', $link); */
    $link =preg_replace('/\s+/', '-', str_replace(['é', 'è', 'É', 'È', '-'], ['e', 'e', 'e', 'e', ' '], strtolower($payment_details['items'][0]['title'])));



    $linkProd = "https://lyceenumerique.ma/produit/" . $link;
    ?>
    <a href="<?php echo $linkProd; ?>" target="_blank" class="gateway <?php echo $payment_gateway['identifier']; ?>-gateway payment-button float-end" id="marocpayPayButton">
        <?php echo get_phrase('pay_by_marocpay');?>

    </a>
</div>