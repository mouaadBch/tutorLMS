<form name="frmPayment" class="gateway <?php echo $payment_gateway['identifier']; ?>-gateway" action="<?php echo site_url('addons/ccavenue/ccavenue_request/'.$payment_gateway['identifier']); ?>" method="POST">

    <input type="hidden" name="language" value="EN">
    <div class="row mb-3">
        <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo get_phrase('address'); ?></label>
        <div class="col-sm-10">
            <input required="true" type="text" name="billing_address" value="" class="form-control ccavenueAddress" Placeholder="Billing Address">
        </div>
    </div>

    <div class="row mb-3">
        <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo get_phrase('country'); ?></label>
        <div class="col-sm-10">
            <input required="true" type="text" name="billing_country" value="" class="form-control ccavenueCountry" Placeholder="Country">        </div>
    </div>

    <div class="row mb-3">
        <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo get_phrase('city'); ?></label>
        <div class="col-sm-10">
            <input required="true" type="text" name="billing_city" value="" class="form-control ccavenueCity" Placeholder="City">
        </div>
    </div>

    <div class="row mb-3">
        <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo get_phrase('zip_code'); ?></label>
        <div class="col-sm-10">
            <input required="true" type="text" name="billing_zip" value="" class="form-control ccavenueZip" Placeholder="Zipcode">
        </div>
    </div>

    <div class="row mb-3">
        <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo get_phrase('state'); ?></label>
        <div class="col-sm-10">
            <input required="true" type="text" name="billing_state" value="" class="form-control" Placeholder="State">
        </div>
    </div>

    <div class="row mb-3">
        <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo get_phrase('phone'); ?></label>
        <div class="col-sm-10">
            <input required="true" type="text" name="billing_tel" value="" class="form-control" Placeholder="Phone">        
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button class="payment-button float-end" type="submit"><?php echo get_phrase('pay_now'); ?></button>
        </div>
    </div>
</form>


<script type="text/javascript">
    var requestUrl = "http://ip-api.com/json";
    $.ajax({
      url: requestUrl,
      type: 'GET',
      success: function(json){
        $('.ccavenueAddress').val(json.city + ', ' + json.country);
        $('.ccavenueCountry').val(json.country);
        $('.ccavenueCity').val(json.city);
        $('.ccavenueZip').val(json.zip);
      },
      error: function(err){
        console.log("Request failed, error= " + err);
      }
    });
</script>