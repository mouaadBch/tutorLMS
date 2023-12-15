<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('Affiliate_addon_settings'); ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('settings');?></h4>

                <form action="<?php echo site_url('addons/affiliate_course/affiliate_addon_settings/update'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><?php echo get_phrase('Allow Public affiliator'); ?></label>
                        <select class="form-control select2" data-toggle="select2" name="affiliate_addon_active_status" required>
                            <option value="1" <?php if(get_settings('affiliate_addon_active_status') == 1) echo 'selected'; ?>><?php echo get_phrase('yes'); ?></option>
                            <option value="0" <?php if(get_settings('affiliate_addon_active_status') == 0) echo 'selected'; ?>><?php echo get_phrase('no'); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="instructor_revenue"><?php echo get_phrase('affiliate_commission_percentage'); ?></label>
                        <div class="input-group">
                            <input type="number" name = "affiliate_addon_percentage" id = "affiliate_addon_percentage" class="form-control"  min="0" max="100" value="<?php echo get_settings('affiliate_addon_percentage'); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="mdi mdi-percent"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-7">
                            <button type="submit" class="btn btn-primary btn-block"><?php echo get_phrase('update_settings'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


