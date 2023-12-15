<div class="accordion curriculum-accordion mx-2">
    <?php
    $sections = $this->crud_model->get_section('course', $course_id)->result_array();
    foreach ($sections as $key => $section) : ?>
    <!-- Accordion Area -->
        <div class="accordion-item">
            <h2 class="accordion-header mx-2">
                <button class="accordion-button <?php if($key > 0) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#curriculumSectionCol<?php echo $section['id']; ?>" aria-expanded="false" aria-controls="curriculumSectionCol<?php echo $section['id']; ?>">
                    <div class="row w-100">
                        <div class="col-auto accordion-item-title"><?php echo $section['title']; ?></div>
                        <div class="col-auto ms-auto pe-0">
                            <span class="ms-auto me-2 pe-2 border-end text-14px text-muted fw-400">
                                <?php echo $this->crud_model->get_lessons('section', $section['id'])->num_rows() . ' ' . site_phrase('lessons'); ?>
                            </span>
                            <span class="me-0 text-14px text-muted fw-400">
                                <?php echo $this->crud_model->get_total_duration_of_lesson_by_section_id($section['id']); ?>
                            </span>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="curriculumSectionCol<?php echo $section['id']; ?>" class="accordion-collapse collapse <?php if($key == 0) echo 'show'; ?>" data-bs-parent="#curriculumSection<?php echo $section['id']; ?>">
                <div class="accordion-body p-0">
                    <ul class="ac-lecture">
                        <?php $lessons = $this->crud_model->get_lessons('section', $section['id'])->result_array();
                        foreach ($lessons as $lesson) : ?>
                            <li>
                                <a href="#" onclick="actionTo('<?php echo site_url('home/play_lesson/'.$lesson['id']); ?>')" class="checkPropagation">
                                    <span class="d-flex align-items-center ellipsis-line-2">
                                        <i class="fa-regular fa-circle-play"></i>
                                        <?php echo $lesson['title']; ?>
                                    </span>

                                    <?php if($lesson['is_free']): ?>
                                        <div class="lecture-info ms-auto pe-2 me-2">
                                            <span onclick="lesson_preview('<?php echo site_url('home/play_lesson/'.$lesson['id'].'/preview') ?>', '<?php echo $lesson['title']; ?>', 'lg')" class="checkPropagation cursor-pointer badge bg-light text-dark fw-400 text-13px"><i class="fas fa-eye me-1 text-13px"></i> <?php echo get_phrase('Preview') ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="lecture-info" style="width: 60px"><?php echo $lesson['duration']; ?></div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
