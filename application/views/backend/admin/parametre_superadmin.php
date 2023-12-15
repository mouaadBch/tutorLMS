<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <h4 class="mb-3 header-title"><?php echo get_phrase('setting_product'); ?></h4>
                    <form action="<?php echo site_url('super_admin/update_property_siteweb'); ?>" method="post">
                        <?php foreach ($parametres as $parametre) : ?>
                            <div class="mb-3">
                                <label for="<?= $parametre['option'] ?>" class="form-label"><?php echo get_phrase($parametre['option']); ?></label>
                                <input type="number" class="form-control" id="<?= $parametre['option'] ?>" name="<?= $parametre['option'] ?>" value="<?= $parametre['value'] ?>">
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-primary"><?php echo get_phrase('update'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>