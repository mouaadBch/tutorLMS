
<form class="required-form" action="<?php echo site_url('addons/tutor_booking/booking_data/update/'.$booking_details['id']); ?>" method="post" enctype="multipart/form-data">

   <div class="form-group">
        <label for="topic"><?php echo get_phrase('tuition_topic'); ?> <span class="required">*</span></label>
        <input type="text" name = "title" id = "title" class="form-control" placeholder="topic title" value="<?php echo $booking_details['title']  ?>" required>
    </div>

    <div class="form-group">
    <select  class="form-control select2" name="category_id" id = "category_id" data-toggle="select2"  data-allow-clear="true" onchange="categoryWiseStateSubcategory(this.value)" data-placeholder="<?php echo get_phrase('Category'); ?>">
     

            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php if($category['id'] == $booking_details['category_id']) echo 'selected'; ?>><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <select name="sub_category_id" id="sub_category_id" class="form-control select2"  data-toggle="select2" data-bs-toggle="select2" required>
        <?php if($booking_details['sub_category_id']!=0):
        $sub_category_name = $this->db->get_where('tutor_category', array('id' => $booking_details['sub_category_id']))->row_array(); ?>
        <option value="<?php echo $booking_details['sub_category_id']  ?>"><?php echo get_phrase($sub_category_name['name']); ?></option>

       <?php else:?>

        <option value="0"><?php echo get_phrase('no category found'); ?></option>

        <?php endif;?>
       
    </select>
    </div>



   <div class="form-group ">
        <label for="class_type"><?php echo get_phrase('class_type'); ?><span class="required">*</span></label> <br>
          <input type="radio" id="online" name="class_type" <?php if($booking_details['tution_class_type'] == '1') echo 'checked'; ?> required value="1">
          <label for="online"><?php echo get_phrase('online'); ?></label> &nbsp;
          <input type="radio" id="in_person" name="class_type" <?php if($booking_details['tution_class_type'] == '2') echo 'checked'; ?> value="2">
          <label for="in_person"><?php echo get_phrase('in_person'); ?></label>

    </div>



    
    <div class="form-group ">
        <label for="Price_type"><?php echo get_phrase('Price_type'); ?><span class="required">*</span></label> <br>
                  <input type="radio" id="fixed" name="price_type"  <?php if($booking_details['price_type'] == 'fixed') echo 'checked'; ?>  value="fixed">
                  <label for="fixed"><?php echo get_phrase('fixed'); ?></label> &nbsp;
                  <input type="radio" id="hourly" name="price_type"<?php if($booking_details['price_type'] == 'hourly') echo 'checked'; ?> value="hourly">
                  <label for="hourly"><?php echo get_phrase('hourly'); ?></label>
        
    </div>

    <div class="form-group">
        <label for="price"><?php echo get_phrase('price'); ?> <span class="required">*</span></label>
        <input type="number" name = "price" id = "price" min="0" step="any" class="form-control" placeholder="put a price" value="<?php echo $booking_details['price']; ?>" required>
    </div>

    <div class="form-group">
        <label for="meeting_link"><?php echo get_phrase('class_invitation_link'); ?> <span class="required">*</span></label>
        <input type="url" name="meeting_link" id="meeting_link" class="form-control" placeholder="zoom invitation link" value="<?php echo $booking_details['meeting_link']  ?>" required>
    </div>

    <button type="button" class="btn btn-primary" onclick="checkRequiredFields()"><?php echo get_phrase('edit_booking'); ?></button>

</form>

<script>

"use strict";

function categoryWiseStateSubcategory(parent) {

                
$.ajax({
    url: "<?php echo site_url('addons/tutor_booking/categoryWiseSubcategory/'); ?>" + parent,
    type: "GET",
    success: function(response) {
        $('#sub_category_id').html(response);
    }
});

}



function update_booking(parent) {

    var booking_id='<?php echo $booking_details['id'] ?>';
    var title=$('[name=title]').val();
    var category_id=$('[name=category_id]').val();
    var sub_category_id=$('[name=sub_category_id]').val();
    var price_type=$('[name=price_type]').val();
    var price=$('[name=price]').val();
                
$.ajax({
    url: "<?php echo site_url('addons/tutor_booking/booking_data/update/'); ?>" + booking_id,
    type: "POST",
    data:{title:title,category_id:category_id,sub_category_id:sub_category_id,price_type:price_type,price:price},
    success: function(response) {
     
    }
});

}
</script>