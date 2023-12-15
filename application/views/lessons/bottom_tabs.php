<ul class="nav nav-tabs ct-tabs-custom-one player-bottom-tabs" role="tablist">

  <?php if(addon_status('live-class') || addon_status('jitsi-live-class')): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="live-class-tab" data-bs-toggle="tab" data-bs-target="#live-class-content" type="button" role="tab" aria-controls="live-class-content" aria-selected="true">
        <?php echo get_phrase('Live class'); ?>
        <span></span>
      </button>
    </li>
  <?php endif ?>
  <?php if(addon_status('assignment')): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="assignment-tab" data-bs-toggle="tab" data-bs-target="#assignment-content" type="button" role="tab" aria-controls="assignment-content" aria-selected="true">
        <?php echo get_phrase('Assignment'); ?>
        <span></span>
      </button>
    </li>
  <?php endif ?>
  <?php if(addon_status('forum')): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link" onclick="load_questions('<?= $course_id; ?>')" id="forum-tab" data-bs-toggle="tab" data-bs-target="#forum-content" type="button" role="tab" aria-controls="forum-content" aria-selected="true">
        <?php echo get_phrase('Forum'); ?>
        <span></span>
      </button>
    </li>
  <?php endif ?>
  <?php if(addon_status('noticeboard')): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="noticeboard-tab" onclick="load_course_notices('<?= $course_id; ?>')" data-bs-toggle="tab" data-bs-target="#noticeboard-content" type="button" role="tab" aria-controls="noticeboard-content" aria-selected="true">
        <?php echo get_phrase('Noticeboard'); ?>
        <span></span>
      </button>
    </li>
  <?php endif ?>
  <?php if(addon_status('certificate')): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="certificate-tab" onclick="actionTo('<?php echo site_url('addons/certificate/certificate_progress/'.$course_details['id']); ?>')" data-bs-toggle="tab" data-bs-target="#certificate-content" type="button" role="tab" aria-controls="certificate-content" aria-selected="true">
        <?php echo get_phrase('Certificate'); ?>
        <span></span>
      </button>
    </li>
  <?php endif ?>
</ul>

<div class="tab-content ct-tabs-content">
  <?php if (addon_status('assignment')): ?>
    <div class="tab-pane fade" id="assignment-content" role="tabpanel" aria-labelledby="assignment-tab">
      <?php include 'assignment_body.php'; ?>
    </div>
  <?php endif; ?>

  <?php if(addon_status('live-class') || addon_status('jitsi-live-class')): ?>
     <div class="tab-pane fade" id="live-class-content" role="tabpanel" aria-labelledby="live-class-tab">
          <?php if (addon_status('live-class')): ?>
              <?php  $live_class = $this->db->get_where('live_class', array('course_id' => $course_id));
                if ($live_class->num_rows() > 0):
                    $live_class = $this->db->get_where('live_class', array('course_id' => $course_id))->row_array(); ?>
                    <div  class="live_class">
                        <i class="fa fa-calendar-check"></i> <?php echo get_phrase('zoom_live_class_schedule'); ?>
                        <h5 style="margin-top: 20px;"><?php echo date('h:i A', $live_class['time']); ?> : <?php echo date('D, d M Y', $live_class['date']); ?></h5>
                        <div class="live_class_note">
                            <?php echo $live_class['note_to_students']; ?>
                        </div>
                          <?php $time = ($live_class['date']+24*60*60) > time() ;?>
                          <a href="<?php echo $time ? $live_class['meeting_invite_link'] : '#';?>" target="_blank" class="btn btn_zoom <?php echo $time ? '' : 'disabled';?>">
                            <i class="fa fa-video"></i>&nbsp;
                            <?php echo get_phrase('join_live_video_class') ; ?>
                          </a>
                        <!-- <a href="<?php #echo site_url('addons/liveclass/join/'.$course_id);?>" target="_blank" class="btn btn_zoom">
                          <i class="fa fa-video"></i>&nbsp;
                          <?php #echo get_phrase('join_live_video_class'); ?>
                        </a>  -->
                    </div>
                <?php else: ?>
                <div class="alert alert-warning" role="alert" style="padding: 30px 0px;">
                  <?php echo get_phrase('live_class_is_not_scheduled_to_this_course_yet'); ?>
                </div>
               <?php endif; ?>
              <?php endif; ?>
              <?php if (addon_status('live-class') && addon_status('jitsi-live-class'))echo '<hr>'; ?>
              <?php if (addon_status('jitsi-live-class')): ?>
                  <?php $live_class = $this->db->get_where('jitsi_live_class', array('course_id' => $course_id));
                  if ($live_class->num_rows() > 0):
                      $live_class = $live_class->row_array(); ?>
                      <div  class="live_class">
                          <i class="fa fa-calendar-check"></i> <?php echo get_phrase('jitsi_live_class_schedule'); ?>
                          <h5 style="margin-top: 20px;"><?php echo date('h:i A', $live_class['time']); ?> : <?php echo date('D, d M Y', $live_class['date']); ?></h5>
                          <div class="live_class_note">
                              <?php echo $live_class['note_to_students']; ?>
                          </div>
                          <?php $time = ($live_class['date']+24*60*60) > time() ;?>
                          <a target="_blank" href="<?php echo $time ? site_url('addons/jitsi_liveclass/join/'.$course_id) : '#';?>" class="btn btn_zoom <?php echo $time ? '' : 'disabled';?>">
                            <i class="fa fa-video"></i>&nbsp;
                            <?php echo get_phrase('join_live_video_class').$time; ?>
                          </a>
                      </div>
                  <?php else: ?>
                      <div class="alert alert-warning" role="alert" style="padding: 30px 0px;">
                        <?php echo get_phrase('live_class_is_not_scheduled_to_this_course_yet'); ?>
                      </div>
                  <?php endif; ?>
            <?php endif; ?>
     </div>
   <?php endif;?>

  <?php if (addon_status('forum')): ?>
    <div class="tab-pane fade" id="forum-content" role="tabpanel" aria-labelledby="forum-tab"></div>
  <?php endif; ?>

  <?php if(addon_status('noticeboard')): ?>
    <div class="tab-pane fade" id="noticeboard-content" role="tabpanel" aria-labelledby="noticeboard-tab"></div>
  <?php endif; ?>
  
  <?php if (addon_status('certificate')): ?>
    <div class="tab-pane fade" id="certificate-content" role="tabpanel" aria-labelledby="certificate-tab"></div>
  <?php endif; ?>
</div>


<?php if (addon_status('forum')): ?>
  <?php include 'course_forum_scripts.php'; ?>
<?php endif; ?>
<?php if(addon_status('noticeboard')): ?>
    <?php include 'noticeboard_scripts.php'; ?>
<?php endif; ?>

<script>
  $(function(){
    $('.player-bottom-tabs li:first button').trigger('click');
  });
</script>