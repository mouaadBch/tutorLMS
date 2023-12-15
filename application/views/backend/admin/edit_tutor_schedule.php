


<style>
        .modal-right
    {
    position: absolute; 
    right: 0;
    height: 100%;
    margin: 0;
    background-color: #fff;
    }
</style>

<form class="required-form" action="<?php echo site_url('addons/tutor_booking/schedule_data/update/'.$schedule_details['id']); ?>" method="post" enctype="multipart/form-data">

<div class="mt-1 botder-top">
                                              
                                                     <label><?php echo get_phrase('Start Time'); ?></label>

                                                   
                                                     
                                                        <div class="input-group">
                                                            <input type="datetime-local" class="form-control"  id="start_time_1" name="start_time" value="<?php echo date('Y-m-d\TH:i', (int)$schedule_details['start_time']); ?>">
                                                            <div class="input-group-append"> 
                                                                <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                            </div>
                                                        </div>
                                                </div>   <br>

                                               
                                               

                                                <!-- <div>
                                                <label><?php//echo get_phrase('End Time'); ?></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" data-toggle='timepicker' id="end_time_1" name="end_time" value="<?php echo  date('h:i A', (int)$schedule_details['end_time']); ?>">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="dripicons-clock"></i></span>
                                                        </div>
                                                    </div> 
                                                    
                                                </div>  -->

                                                <?php
                                                   
                                                   $t1=$schedule_details['start_time'];
                                                   $t2=$schedule_details['end_time'];
                                                   $t3=$t2-$t1;
                                                   $t3=$t3/60;
                                                
                                                ?>
                                                
                                                <div class="form-group">
                                                <label><?php echo get_phrase('End Time'); ?></label>
                                                <select required class="form-control select2" id="end_time_1" name="end_time" data-toggle="select2" data-allow-clear="true"  data-placeholder="<?php echo get_phrase('duration'); ?> " >
                                                 
                                                        <option value="0"><?php echo get_phrase('Select Duration'); ?> </option>
                                                  
                                                      
                                                            <option value="60" <?php if($t3==60) echo 'selected'  ?>>  1:00 </option>
                                                            <option value="75" <?php if($t3==75) echo 'selected'  ?>>  1:15 </option>
                                                            <option value="90" <?php if($t3==90) echo 'selected'  ?> >  1:30 </option>
                                                            <option value="105" <?php if($t3==105) echo 'selected'  ?>> 1:45 </option>
                                                            <option value="120" <?php if($t3==120) echo 'selected'  ?>> 2:00 </option>
                                                            <option value="135" <?php if($t3==135) echo 'selected'  ?>> 2:15 </option>
                                                            <option value="150" <?php if($t3==150) echo 'selected'  ?>> 2:30 </option>
                                                            <option value="165" <?php if($t3==165) echo 'selected'  ?>> 2:45 </option>
                                                            <option value="180" <?php if($t3==180) echo 'selected'  ?>> 3:00 </option>
                                          
                                                    </select>
                                                </div>
                                                
                                                
                                                <br>


                                            

                                                <div class="form-group d-none hidden">
                                                    <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
                                                              <input type="radio" id="group_class_1" name="class_type" value="group">
                                                              <label for="group_class"><?php echo get_phrase('Group'); ?></label> &nbsp;
                                                              <input type="radio" id="single_class_1" name="class_type" value="single">
                                                              <label for="single_class"><?php echo get_phrase('single'); ?></label>
                                                    
                                                </div>
                                                <div class="form-group d-none " id='tution_type_1'>
                                                    <label for="tution_type_1"><?php echo get_phrase('tution_type'); ?><span class="required">*</span></label><br>
                                                              <input type="radio" id="single_1" name="1_tution_type_indentify" onclick="show_weeks(this.id,'no')" <?php if($schedule_details['tuition_type'] == 1) echo 'checked'; ?> value="1">
                                                              <label id='label_single_1' for="single_1"><?php echo get_phrase('one_time'); ?></label> &nbsp;
                                                              <input type="radio" id="everyday_class_1" name="1_tution_type_indentify" onclick="show_weeks(this.id,'no')" <?php if($schedule_details['tuition_type'] == 7) echo 'checked'; ?> value="7">
                                                              <label  id='label_everyday_class_1' for="everyday_class_1"><?php echo get_phrase('everyday'); ?></label>  &nbsp;
                                                              <input type="radio" id="selected_class_1" name="1_tution_type_indentify"  onclick="show_weeks(this.id,'yes')" <?php if($schedule_details['tuition_type'] == 0) echo 'checked'; ?> value="0">
                                                              <label id='label_selected_class_1' for="selected_class_1"><?php echo get_phrase('selected_days'); ?></label>
                                                </div>

                                                <?php if($schedule_details['tuition_type']==0):
                                                      $days=json_decode($schedule_details['selected_days']);   else: $days=array()?> 
                                                <?php endif;?>

                                                <div class="form-group d-none hidden " id="week_list_1">
                                                    <label for="Select_days"><?php echo get_phrase('Select Days'); ?><span class="required">*</span></label><br>
                                                              <input type="checkbox" id="sunday_1" name="day[]" <?php if(in_array("sunday", $days)) echo 'checked'; ?>  value="sunday">
                                                              <label id="label_sunday_1"for="sunday_1"><?php echo get_phrase('sunday'); ?></label> &nbsp;

                                                              <input type="checkbox" id="monday_1" name="day[]"  <?php if(in_array("monday", $days)) echo 'checked'; ?> value="monday">
                                                              <label  id="label_monday_1"for="monday_1"><?php echo get_phrase('monday'); ?></label>  &nbsp;

                                                              <input type="checkbox" id="tuesday_1" name="day[]"  <?php if(in_array("tuesday", $days)) echo 'checked'; ?> value="tuesday">
                                                              <label  id="label_tuesday_1"for="tuesday_1"><?php echo get_phrase('tuesday'); ?></label>

                                                              <input type="checkbox" id="wednesday_1" name="day[]" <?php if(in_array("wednesday", $days)) echo 'checked'; ?> value="wednesday">
                                                              <label  id="label_wednesday_1"for="wednesday_1"><?php echo get_phrase('wednesday'); ?></label> &nbsp;

                                                              <input type="checkbox" id="thursday_1" name="day[]" <?php if(in_array("thursday", $days)) echo 'checked'; ?> value="thursday">
                                                              <label  id="label_thursday_1"for="thursday_1"><?php echo get_phrase('thursday'); ?></label>  &nbsp;
                                                            
                                                              <input type="checkbox" id="friday_1" name="day[]" <?php if(in_array("friday", $days)) echo 'checked'; ?> value="friday">
                                                              <label  id="label_friday_1"for="friday_1"><?php echo get_phrase('friday'); ?></label>

                                                              <input type="checkbox" id="saturday_1" name="day[]" <?php if(in_array("saturday", $days)) echo 'checked'; ?> value="saturday">
                                                              <label  id="label_saturday_1"for="saturday_1"><?php echo get_phrase('saturday'); ?></label>
                                                </div>

                                              

                                          </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                  </div> 

                                                <button type="button" class="btn btn-primary" onclick="checkRequiredFields()"><?php echo get_phrase('update_schedule'); ?></button>

</form>

<script>

"use strict";

$( document ).ready(function() {



    var value='<?php echo $schedule_details['tuition_type'] ?>';
    

    if(value==0)
    {
        $('#week_list_1').removeClass('hidden');
    }
   
});

function categoryWiseStateSubcategory(parent) {

                
$.ajax({
    url: "<?php echo site_url('addons/tutor_booking/categoryWiseSubcategory/'); ?>" + parent,
    type: "GET",
    success: function(response) {
        $('#sub_category_id').html(response);
    }
});

}

function show_weeks(id,status)
    {
     
        console.log('herer');
        $('#week_list_1').removeClass('hidden');

        if(status=='yes')
        {
            $('#week_list_1').removeClass('hidden');
        }
        else
        {
            $('#week_list_1').addClass('hidden');
        
        
    }
}
</script>