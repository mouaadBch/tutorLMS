
<div class="container mt-5">

<?php if($_POST['Response'] == 'Approved' || $_POST['Response'] == 'ACTION=POSTAUTH' ){ ?>
    <div class="row">
      <div class="col">
        <div class="alert alert-success">
          <h4 class="alert-heading"><?php echo site_phrase('Payment Successful!'); ?></h4>
          <hr>
          <p class="mb-0"><?php echo site_phrase('Thank you for your payment. Your transaction has been completed successfully.'); ?></p>
        </div>
      </div>
    </div>
<?php } else { ?>
    <div class="row">
      <div class="col">
        <div class="alert alert-danger">
          <h4 class="alert-heading"><?php echo site_phrase('Payment Failed!'); ?></h4>
          <hr>
          <p class="mb-0"><?php echo site_phrase('We are sorry, but there was an issue with your payment. Please try again later.'); ?></p>
        </div>
      </div>
    </div>
<?php } ?>

    <div class="row mt-4">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h5><?php echo site_phrase('Invoice Details'); ?></h5>
          </div>
          <div class="card-body">
            <table class="table">
              <tbody>
                <tr>
                  <th scope="row"><?php echo site_phrase('Order Id:'); ?></th>
                  <td><?php echo $_POST['oid']; ?></td>
                </tr>
                <tr>
                  <th scope="row"><?php echo site_phrase('Date:'); ?></th>
                  <td><?php echo date('M d, Y'); ?></td>
                </tr>
                <tr>
                  <th scope="row"><?php echo site_phrase('Amount:'); ?></th>
                  <td><?php echo $_POST['amount']; ?> <?php echo strtoupper(site_phrase('MAD')); ?></td>
                </tr>
                <tr>
                  <th scope="row"><?php echo site_phrase('Payment Method:'); ?></th>
                  <td>CMI</td>
                </tr>
                <tr>
                  <th scope="row"><?php echo site_phrase('Purchased By:'); ?></th>
                  <td><?php echo $_POST['BillToName']; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col text-center">


    	<a href="<?php echo site_url(); ?>" class="btn btn-primary"><?php echo site_phrase('Back'); ?></a>


      </div>
    </div>

</div>


