<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('s3_settings'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <h4 class="mb-3 header-title"><?php echo get_phrase('s3_settings');?></h4>

                    <form class="required-form" action="<?php echo site_url('addons/amazons3/settings/update'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="access_key"><?php echo 'Amazon S3 '.get_phrase('access_key'); ?><span class="required">*</span></label>
                            <input type="text" name = "access_key" id = "access_key" class="form-control" value="<?php echo get_settings('amazon_s3_access_key');  ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="secret_key"><?php echo 'Amazon S3 '.get_phrase('secret_key'); ?><span class="required">*</span></label>
                            <input type="text" name = "secret_key" id = "secret_key" class="form-control" value="<?php echo get_settings('amazon_s3_secret_key');  ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="bucket_name"><?php echo 'Amazon S3 '.get_phrase('bucket_name'); ?><span class="required">*</span></label>
                            <input type="text" name = "bucket_name" id = "bucket_name" class="form-control" value="<?php echo get_settings('amazon_s3_bucket_name');  ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="region_name"><?php echo 'Amazon S3 '.get_phrase('region_name'); ?><span class="required">*</span></label>
                            <input type="text" name = "region_name" id = "region_name" class="form-control" value="<?php echo get_settings('amazon_s3_region_name');  ?>" required>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="checkRequiredFields()"><?php echo get_phrase('save'); ?></button>
                    </form>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
