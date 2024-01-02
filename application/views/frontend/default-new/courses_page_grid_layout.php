<div class="grid-view-body courses">
        <?php include 'courses_page_sorting_section.php'; ?>
    
        <div class="courses-card ">
            <div class="row">
                <?php foreach ($courses as $course) : ?>
                    <?php
                    $lessons = $this->crud_model->get_lessons('course', $course['id']);
                    $instructor_details = $this->user_model->get_all_user($course['user_id'])->row_array();
                    $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($course['id']);
                    $total_rating =  $this->crud_model->get_ratings('course', $course['id'], true)->row()->rating;
                    $number_of_ratings = $this->crud_model->get_ratings('course', $course['id'])->num_rows();
                    if ($number_of_ratings > 0) {
                        $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                    } else {
                        $average_ceil_rating = 0;
                    }
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <a href="<?php echo site_url('home/course/' . rawurlencode(slugify($course['title'])) . '/' . $course['id']); ?>" class="checkPropagation courses-card-body">
                            <div class="courses-card-image" style="height: 162px;">
                                <img src="<?php echo $this->crud_model->get_course_thumbnail_url($course['id']); ?>">
                                <div class="courses-icon <?php if(in_array($course['id'], $my_wishlist_items)) echo 'red-heart'; ?>" id="coursesWishlistIcon<?php echo $course['id']; ?>">
                                    <i class="fa-solid fa-heart checkPropagation" onclick="actionTo('<?php echo site_url('home/toggleWishlistItems/'.$course['id']); ?>')"></i>
                                </div>
                                <div class="courses-card-image-text">
                                    <h3><?php echo get_phrase($course['level']); ?></h3>
                                </div> 
                            </div>
                            <div class="courses-text">
                                <h5 class="mb-2"><?php echo $course['title']; ?></h5>
                                <div class="review-icon">
                                    <div class="review-icon-star align-item-center">
                                        <p><?php echo $average_ceil_rating; ?></p>
                                        <p><i class="fa-solid fa-star <?php if($number_of_ratings > 0) echo 'filled'; ?>"></i></p>
                                        <p>(<?php echo $number_of_ratings; ?> <i class="fa-solid fa-comments text-dark"></i><?php #echo get_phrase('Reviews') ?>)</p>
                                    </div>
                                    <div class="review-btn d-flex align-items-center">
                                        <span class="compare-img checkPropagation" onclick="redirectTo('<?php echo base_url('home/compare?course-1='.slugify($course['title']).'&course-id-1='.$course['id']); ?>');">
                                            <img src="<?php echo base_url('assets/frontend/default-new/image/compare.png') ?>">
                                            <?php echo get_phrase('Compare'); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php if(($course['is_affiliate']==1) && (($is_affiliator ?? 0)==1)): ?>
                                    <p><i class="fa-solid fa-bullhorn text-success fs-6 "> <?= get_settings('affiliate_addon_percentage')?>%  </i></p>
                                <?php endif; ?>
                                <p class="ellipsis-line-2"><?php echo $course['short_description'] ?></p>
                                <div style="text-align: right;">
                                    <p class="m-0"><i class="fa-regular fa-clock text-15px p-0"></i> <?php echo $course_duration; ?></p>
                                </div>

                                <div class="courses-price-border">
                                    <div>
                                        <?php $secondary_price = json_decode($course['secondary_price'],true) ?>
                                        <?php if($course['is_free_course']): ?>
                                            <div  class="courses-price-left">
                                                <h5 class="price-free"><?php echo get_phrase('Free'); ?></h5>
                                            </div>
                                        <?php else:?>
                                            <div  class="courses-price-left">
                                                <?php if($course['discount_flag']):?>
                                                    <h5><?php echo currency($course['discounted_price']); ?></h5>
                                                    <i class="fas fa-info-circle text-secondary" style="font-size: 10px;"  data-bs-toggle="tooltip" data-bs-html="true" title="<?php echo get_phrase('Pay').' '.currency($course['discounted_price']).' '.get_phrase('instead of').' '. currency($course['price']).' '.get_phrase('a limited-time discount!')?>"></i>
                                                <?php else:?>
                                                    <h5><?php echo currency($course['price']); ?></h5>
                                                <?php endif;?>
                                                <h5> / <?php echo ($course['expiry_period'] =='' )? get_phrase('Lifetime') : $course['expiry_period'].' '.get_phrase('months'); ?> </h5>
                                            </div>
                                            <?php if($secondary_price['secondary_price']):?>
                                                <div  class="courses-price-left">
                                                    <?php if($secondary_price['discounted_price']):?>
                                                        <h5><?php echo currency($secondary_price['discounted_price']); ?></h5>
                                                        <i class="fas fa-info-circle text-secondary" style="font-size: 10px;"  data-bs-toggle="tooltip" data-bs-html="true" title="<?php echo get_phrase('Pay').' '.currency($secondary_price['discounted_price']).' '.get_phrase('instead of').' '.currency($secondary_price['price']).' '.get_phrase('a limited-time discount!')?>"></i>
                                                        <?php else:?>
                                                            <h5><?php echo currency($secondary_price['price']); ?></h5>
                                                    <?php endif;?>
                                                    <h5> / <?php echo ($secondary_price['expiry_period'] =='' )? get_phrase('Lifetime') : $secondary_price['expiry_period']; ?> </h5>
                                                </div>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </div>
                                </div>
                             </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
             <!------- pagination Start ------>
            <div class="pagenation-items mb-0 mt-3">
                <?php echo $this->pagination->create_links(); ?>
            </div>
            <!------- pagination end ------>
        </div>
    </div>                        
</div>