<?php
  if(is_array($watch_history) && !empty($watch_history['completed_lesson'])):
    $completed_lessons = json_decode($watch_history['completed_lesson'], true);
  else:
    $completed_lessons = array();
  endif;
  $completed_lessons = is_array($completed_lessons) ? $completed_lessons : array();
  $user_id = $this->session->userdata('user_id');
  $is_course_instructor = $this->crud_model->is_course_instructor($course_details['id'], $user_id);
  $is_locked = 0;
  $locked_lesson_ids = array();
?>
<?php if(is_array($sections) && count($sections) > 0): ?>
  <div class="course-playing-sidebar">
    <h4 class="title"><?php echo get_phrase('Course Content'); ?></h4>
    
    <!-- Content List -->
    <div class="accordion custom-accordion" id="accordionContent">
      <?php foreach($sections as $key => $section): ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="section<?php echo $section['id']; ?>">
            <button class="accordion-button <?php if(($lesson_details['section_id']??'') != $section['id']) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $section['id'] ?>" aria-expanded="true" aria-controls="collapseOne<?php echo $section['id'] ?>"><?php echo $section['title']; ?></button>
          </h2>
          <?php if ($lesson_details): ?>
            <div id="collapseOne<?php echo $section['id'] ?>" class="accordion-collapse collapse <?php if($lesson_details['section_id'] == $section['id']) echo 'show'; ?>" aria-labelledby="section<?php echo $section['id']; ?>" data-bs-parent="#accordionContent">
              <div class="accordion-body">
                <ul class="course-content-items">

                  <?php
                  $lessons = $this->crud_model->get_lessons('section', $section['id'])->result_array();
                  foreach($lessons as $key => $lesson):

                    //Check is bundle or course
                    if(isset($bundle_id) && $bundle_id > 0):
                      $lesson_url = site_url('addons/course_bundles/lesson/'.rawurlencode(slugify($course_details['title'])).'/'.$bundle_id.'/'.$course_id.'/'.$lesson['id']);
                    else:
                      $lesson_url = site_url('home/lesson/'.slugify($course_details['title']).'/'.$course_id.'/'.$lesson['id']);
                    endif;
                    //End check is bundle or course
                    ?>
                    

                    <li class="item <?php if($lesson['id'] == $lesson_details['id']) echo 'active'; ?>">
                      <a href="<?php echo $lesson_url; ?>" class="d-flex align-items-baseline w-100 checkbox-box-a">
                        <?php if(in_array($lesson['id'], $completed_lessons)){
                          $chekbox = 'title="'.get_phrase('Uncheck').'" data-bs-toggle="tooltip" checked';
                        }else{
                          $chekbox = 'title="'.get_phrase('Mark as Complete').'" data-bs-toggle="tooltip"';
                        }?>

                        <?php if($course_details['enable_drip_content']): ?>
                          <?php if($is_locked): ?>
                            <i class="fas fa-lock" title="<?php echo get_phrase('Complete previous lesson to unlock it'); ?>" data-bs-toggle="tooltip"></i>
                          <?php else: ?>
                            <?php if(in_array($lesson['id'], $completed_lessons) && $lesson['lesson_type'] == 'video' ||in_array($lesson['id'], $completed_lessons) && $lesson['lesson_type'] == 'quiz'): ?>
                              <i class="fas fa-check" title="<?php echo get_phrase('Completed'); ?>" data-bs-toggle="tooltip"></i>
                            <?php else: ?>
                              <?php if($lesson['lesson_type'] == 'video'): ?>
                                <i class="fas fa-play me-2" title="<?php echo get_phrase('Play Now'); ?>" data-bs-toggle="tooltip"></i>
                              <?php elseif($lesson['lesson_type'] == 'quiz'): ?>
                                <i class="fas fa-question" title="<?php echo get_phrase('Start Now'); ?>" data-bs-toggle="tooltip"></i>
                              <?php else: ?>
                                <div class="checkbox checkbox-box">
                                  <input class="lesson_checkbox" type="checkbox" onchange="actionTo('<?php echo site_url('home/update_watch_history_manually?lesson_id='.$lesson['id'].'&course_id='.$course_details['id']); ?>', 'post', event);" <?php echo $chekbox; ?>>
                                </div>
                              <?php endif; ?>
                            <?php endif; ?>
                          <?php endif; ?>
                        <?php else: ?>

                          <div class="checkbox checkbox-box">
                            <input class="lesson_checkbox" type="checkbox" onchange="actionTo('<?php echo site_url('home/update_watch_history_manually?lesson_id='.$lesson['id'].'&course_id='.$course_details['id']); ?>', 'post', event);" <?php echo $chekbox; ?>>
                          </div>
                        <?php endif; ?>


                        <span class="mx-2 d-grid">
                          <span class="m-0 p-0"><?php echo $lesson['title']; ?></span>
                          <span class="lesson-icon">
                            <?php if($lesson['lesson_type'] == 'other' || $lesson['lesson_type'] == 'text'): ?>
                              <i class="far fa-file-alt me-1"></i>
                              <?php echo get_phrase($lesson['attachment_type']); ?>
                            <?php elseif($lesson['lesson_type'] == 'quiz'): ?>
                              <i class="far fa-question-circle me-1"></i><?php echo get_phrase('Quiz'); ?>
                            <?php else: ?>
                              <i class="far fa-file-video me-1"></i><?php echo get_phrase('Video'); ?>
                            <?php endif; ?>
                          </span>
                        </span>
                        <span class="ms-auto"><?php echo $lesson['duration']; ?></span>
                      </a>
                    </li>


                    <?php
                    //check dripcontent
                    if($is_locked) $locked_lesson_ids[] = $lesson['id'];
                    if(
                      !in_array($lesson['id'], $completed_lessons)
                      && $is_locked == 0
                      && $course_details['enable_drip_content'] == 1
                      && $this->session->userdata('user_login') == 1
                      && $is_course_instructor == false
                    ):
                      $is_locked = 1;
                    endif; ?>
                  <?php endforeach; ?>
                  
                </ul>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>


<script type="text/javascript">
  $(document).ready(function() {
    $('.checkbox-box').each(function(index) {
      var checkboxBox = $(this).html();
      $(this).parent().parent().prepend('<div class="checkbox custom-checkbox" style="width: 20px; height: 20px;">'+checkboxBox+'</div>');
      $(this).remove();
    });
  });

</script>