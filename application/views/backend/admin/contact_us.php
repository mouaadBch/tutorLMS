<div class="row ">
	<div class="col-xl-12">
		<div class="card">
			<div class="card-body">
				<h4 class="page-title">
					<i class="mdi mdi-apple-keyboard-command title_icon"></i>
					<?php echo get_phrase('contact_us'); ?>
				</h4>
			</div>
		</div>
	</div>
</div>

<div class="row ">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="header-title mb-3">
					<?php echo get_phrase('basic_info'); ?>
				</h4>
				<table class="table table-responsive">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Users</th>
							<th scope="col">email</th>
							<th scope="col">phone numbre</th>
							<th scope="col">subject</th>
							<th scope="col">message</th>
							<th scope="col" style="width: 130px;">date</th>
							<th scope="col">action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($page_contact_list as $contact_list) : ?>
							<tr>
								<th scope="row"><?= $contact_list['id'] ?></th>
								<td scope="row"><?= $contact_list['name'] ?></td>
								<td><a target="_blank" href="https://mail.google.com/mail/?view=cm&to=<?= $contact_list['email'] ?>"><?= $contact_list['email'] ?></a></td>
								<td><?= $contact_list['phone_numbre'] ?></td>
								<td>
									<p><?= $contact_list['subject'] ?></p>
								</td>
								<td>
									<p><?= $contact_list['message'] ?></p>
								</td>
								<td>
									<p><?= date('Y-m-d', $contact_list['date_add']) ?></p>
									<b>
										<?php if ($contact_list['status']) : ?>
											<span class="badge badge-success-lighten">read</span>
										<?php else : ?>
											<span class="badge badge-danger-lighten">unread</span>
										<?php endif; ?>
									</b>
								</td>
								<td>
									<div class="dropright dropright">
										<button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="mdi mdi-dots-vertical"></i>
										</button>
										<ul class="dropdown-menu">
											<li>
												<?php if ($contact_list['status']) : ?>
													<a class="dropdown-item" href="<?= site_url('admin/contact_us/update_status/' . $contact_list['id']) ?>">Mark as unread</a>
												<?php else : ?>
													<a class="dropdown-item" href="<?= site_url('admin/contact_us/update_status/' . $contact_list['id']) ?>">Mark as read</a>
												<?php endif; ?>
											</li>
											<li>
												<a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('admin/contact_us/delete/' . $contact_list['id']); ?>');">
													<?php echo get_phrase('delete'); ?>
												</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>