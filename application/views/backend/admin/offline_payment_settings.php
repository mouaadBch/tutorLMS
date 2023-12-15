<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
	<div class="col-lg-12">
		<?php 
		$offtext = json_decode(get_settings('offline_bank_information'), true);
		?>
		<div class="card">
			<div class="card-body">
				<form class="row justify-content-center align-items-center" action="<?php echo site_url('addons/offline_payment/settings/save'); ?>" method="post">
					<?php $languages = $this->crud_model->get_all_languages();
                    foreach ($languages as $language): ?>
						<div class="col-md-12">
							<span class="badge bg-dark text-white fw-bolder align-middle"><?php echo ucwords($language);?></span>
							<div class="form-group">
								<label for="bank_information"><?php echo get_phrase('enter_your_bank_information'); ?></label>
								<textarea class="bank_information" name="bank_information[<?= $language?>]" id = "bank_information" class="form-control" rows="5">
									<?php echo htmlspecialchars_decode($offtext[$language]); ?>
								</textarea>
							</div>
						</div>
						<?php endforeach; ?>
						<div class="form-group">
							<button class="btn btn-primary"><?php echo get_phrase('Submit'); ?></button>
						</div>
					</form>
				</div>
		</div>
	</div>
</div>


<script type="text/javascript">
  $(document).ready(function () {
    initSummerNote(['.bank_information']);
  });
</script>