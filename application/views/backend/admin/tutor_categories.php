
<style>

    .font18px{
      font-size: 18px;
    }

    .itslyc{

        font-style: italic;

    }
    .cat_dt
    {
        float: right; margin-left: 5px; display: none; height: 20px;
    }
    .cat_edt
    {
        float: right; display: none; height: 20px;
    }

    .cat_e
    {
        display: none;
        margin-right:5px;
    }

    .cdt
    {
       float: right; display: none;
       margin-right:5px;
    }


</style>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('tutor categories'); ?>
                    <a href="<?php echo site_url('addons/tutor_booking/tutor_category_form/add_category'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_new_category'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <?php foreach ($categories->result_array() as $category) :
        if ($category['parent'] > 0)
            continue;
        $sub_categories = $this->tutor_booking_model->get_tutor_sub_categories($category['id']); ?>
        <div class="col-md-6 col-lg-6 col-xl-4 on-hover-action" id="<?php echo $category['id']; ?>">
            <div class="card d-block">
               
                <div class="card-body">
                    <h4 class="card-title mb-0"><?php echo $category['name']; ?></h4>
                    <small class="itslyc ">
                        <p class="card-text"><?php echo count($sub_categories) . ' ' . get_phrase('sub_categories'); ?></p>
                    </small>
                </div>

                <ul class="list-group list-group-flush">
                    <?php foreach ($sub_categories as $sub_category) : ?>
                        <li class="list-group-item on-hover-action" id="<?php echo $sub_category['id']; ?>">
                            <span> <?php echo $sub_category['name']; ?></span>
                            <span class="category-action cat_dt" id='category-delete-btn-<?php echo $sub_category['id']; ?>' >
                                <a href="javascript:;" class="action-icon" onclick="confirm_modal('<?php echo site_url('addons/tutor_booking/tutor_categories/delete/' . $sub_category['id']); ?>');"> <i class="mdi mdi-delete  font18px" ></i></a>
                            </span>
                            <span class="category-action cat_edt" id='category-edit-btn-<?php echo $sub_category['id']; ?>'>
                                <a href="<?php echo site_url('addons/tutor_booking/tutor_category_form/edit_category/' . $sub_category['id']); ?>" class="action-icon"> <i class="mdi mdi-pencil  font18px" ></i></a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-body">
                    <a href="<?php echo site_url('addons/tutor_booking/tutor_category_form/edit_category/' . $category['id']); ?>" class="btn btn-icon btn-outline-info btn-sm cat_e" id="category-edit-btn-<?php echo $category['id']; ?>">
                        <i class="mdi mdi-wrench"></i> <?php echo get_phrase('edit'); ?>
                    </a>
                    <a href="#" class="btn btn-icon btn-outline-danger btn-sm cdt" id="category-delete-btn-<?php echo $category['id']; ?>"  onclick="confirm_modal('<?php echo site_url('addons/tutor_booking/tutor_categories/delete/' . $category['id']); ?>');" >
                        <i class="mdi mdi-delete"></i> <?php echo get_phrase('delete'); ?>
                    </a>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    "use strict";

    $('.on-hover-action').mouseenter(function() {
        var id = this.id;
        $('#category-delete-btn-' + id).show();
        $('#category-edit-btn-' + id).show();
    });
    $('.on-hover-action').mouseleave(function() {
        var id = this.id;
        $('#category-delete-btn-' + id).hide();
        $('#category-edit-btn-' + id).hide();
    });
</script>