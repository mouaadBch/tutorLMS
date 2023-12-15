<h4 class="mb-3 header-title"><?php echo get_phrase('your_application'); ?></h4>
<div class="table-responsive-sm mt-4">
    <table id="basic-datatable" class="table table-striped table-centered mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo get_phrase('name'); ?></th>

                <th><?php echo get_phrase('details'); ?></th>
                <th><?php echo get_phrase('status'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications->result_array() as $key => $application) :
                $user_data = $this->user_model->get_all_user($application['user_id'])->row_array(); ?>
                <tr class="gradeU">
                    <td>
                        <?php echo ++$key; ?>
                    </td>
                    <td>
                        <?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?>
                    </td>

                    <td>
                        <?php if (!empty($application['document'])) : ?>
                            <a href="<?php echo site_url( "addons/affiliate_course/become_an_affiliator/download/".$application['user_id']); ?>" class="btn btn-info">
                                <i class="fa fa-download"></i> <?php echo get_phrase('download'); ?>
                                </a>
                        

                   
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($application['status'] == 0) : ?>
                            <div class="badge badge-danger"><?php echo get_phrase('pending'); ?></div>
                        <?php elseif ($application['status'] == 1) : ?>
                            <div class="badge badge-success"><?php echo get_phrase('approved'); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>