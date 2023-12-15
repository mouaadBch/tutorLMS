<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row ">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
            	<h4 class="card-title"><?php echo $tickets['title']; ?></h4>
			
			   <!-- List of Ticket replies -->					
			   <div class="profile-env">
			      <section class="profile-feed p-0 m-0">
			         <!-- user profile -->
			         <div class="profile-stories">
			         	<div class="mb-4 max-height-475 overfloy-y-auto">
			         	<?php foreach ($ticket_details->result_array() as $key => $details) { ?>
				            <article class="story px-2">
				               <aside class="user-thumb">
				                  <a href="#">
				                  <img src="<?php echo $this->user_model->get_user_image_url($details['user_id']); ?>" alt="user-image" width="40" height="40" class="rounded-circle shadow-sm">
				                  </a>
				               </aside>
				               <div class="story-content">
				                  <!--  header -->
				                  <header>
				                     <div class="publisher">
				                     	<a href="#">
				                     		<?php $user_details = $this->user_model->get_all_user($details['user_id'])->row_array();
											?>
											<span class="leftbar-user-name"><?php echo $user_details['first_name'].' '.$user_details['last_name']; ?></span>
				                     	</a>
				                        <em><small>
				                        <i class="mdi mdi-dot"></i> 
				                        <?php echo date('d M Y', $details['date']) ?>
				                        </small></em>
				                     </div>
				                  </header>
				                  <div class="story-main-content">
				                     <?php echo nl2br($details['description']); ?>
				                  </div>
				                  
				                  <?php if(file_exists('uploads/support_files/'.$details['file_name'])) { ?>
				                  	<a href="<?php echo base_url('uploads/support_files/'.$details['file_name']); ?>" class="" download><i class="mdi mdi-download"></i><?php echo $details['file_name']; ?></a>
				                  <?php } ?>
				               </div>
				            </article>
				            <hr>
				        <?php } ?>
				    </div>
				        <form action="<?= site_url('addons/customer_support/support_reply'); ?>" method="post" enctype="multipart/form-data">
				        	<input type="hidden" name="code" value="<?php echo $tickets['code']; ?>">
					        <div class="form-group">
				        		<textarea class="form-control" id="description" rows="5" name="description" placeholder="Write some note.."></textarea>
					        </div>
					        <div class="form-group mb-3">
	                            <input type="file" id="support_file" name="support_file" class="form-control-file">
	                        </div>
	                        <?php if($this->session->userdata('admin_login') == true) { ?>
			                    <div class="form-group row mb-3">
				                    <div class="input-group col-md-8">  
			                            <select class="form-control select2" onchange="apply_macro(this)" data-toggle="select2" name="macro_id" id="macro_id">
			                                <option value=""><?php echo get_phrase('apply_macro'); ?></option>
			                                <?php $macroes = $this->customer_support_model->get_support_macros($macro['id'])->result_array(); 
			                                    foreach ($macroes as $key => $macro): ?>
			                                    <option value="<?php echo $macro['description']; ?>"><?php echo $macro['title']; ?></option>
			                              	<?php endforeach; ?>
			                            </select>
			                        </div>
			                    </div>
			                <?php } ?>
		                    <div class="form-group">
		                        <button class="btn btn-primary float-left"><?= get_phrase('post_reply'); ?></button>
		                    </div>
		                </form>
			         </div>
			      </section>
			   </div>
          	</div> <!-- end card -->
        </div>
    </div><!-- end col-->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
            	<h4 class="mb-3 mt-1"><?php echo get_phrase('ticket_summary') ?></h4>
            	<table class="table table-striped">
				   <tbody>
				      <tr>
				         <td><i class="entypo-dot"></i><?php echo get_phrase('ticket_code') ?></td>
				         <td> : </td>
				         <td><?php echo $tickets['code']; ?></td>
				      </tr>
				      <tr>
				         <td><i class="entypo-dot"></i><?php echo get_phrase('category') ?></td>
				         <td> : </td>
				         <td>
				         	<?php echo $this->customer_support_model->get_support_categories($tickets['category_id'])->row('title'); ?>	
				         </td>
				      </tr>
				      <tr>
				         <td><i class="entypo-dot"></i><?php echo get_phrase('ticket_status') ?></td>
				         <td> : </td>
				         <td>
				         	<?php if($tickets['status'] == 'opened') { ?>
                              <span class="badge badge-danger"><?php echo get_phrase('opened'); ?></span>
                            <?php } else { ?>
                              <span class="badge badge-light"><?php echo get_phrase('closed'); ?></span>
                            <?php } ?>
				         </td>
				      </tr>
				      <tr>
				         <td><i class="entypo-dot"></i><?php echo get_phrase('ticket_priority') ?></td>
				         <td> : </td>
				         <td>
				         	<?php if($tickets['priority'] == 'high') { ?>
	                            <span class="badge badge-danger"><?php echo get_phrase('high'); ?></span>
	                        <?php } else if($tickets['priority'] == 'medium') { ?>
	                            <span class="badge badge-info"><?php echo get_phrase('medium'); ?></span>
	                        <?php } else { ?>
	                            <span class="badge badge-light"><?php echo get_phrase('low'); ?></span>
	                        <?php } ?>
				         </td>
				      </tr>
				   </tbody>
				</table>
            </div> <!-- end card -->
        </div>
    </div>
</div>
<script type="text/javascript">
	function apply_macro(element)
	{
		var macro = $(element).val();
		var preMsg = $('#description').val();
		if(preMsg == "")
		{
			$('#description').val(macro);
		} 
		else
		{
			$('#description').val(preMsg +'\n'+ macro);
		}
	}
</script>