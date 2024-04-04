<div class="card-body">
	<?php echo $this->Form->create(); ?>
		<div class="panel panel-primary filterable">
			<table id="active_users_list" class="table m-0 table-striped table-bordered">
				<thead class="tablehead">
					<tr>
						<th>Sr.No.</th>
						<th>Name</th>
						<th>Email Id</th>
						<th>Division</th>
						<th>Posted Office</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($all_users)) {
							$i=0;
							$sr_no=1;
							foreach ($all_users as $each_user) { 
								if ($each_user['status'] == 'active') { ?>
							
									<tr>
										<td><?php echo $sr_no; ?></td>
										<td><?php echo $each_user['f_name'].' '; echo $each_user['l_name'];?></td>
										<td><?php echo base64_decode($each_user['email']); //for email encoding ?></td>
										<td><?php echo $each_user['division']; ?></td>
										<td><?php if (!empty($posted_ro_office[$i])) { echo $posted_ro_office[$i];} ?></td>
										<td><?php echo $this->Html->link('', array('controller' => 'users', 'action'=>'fetch_user_id', $each_user['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> |
											<?php if ($each_user['status'] == 'active') {
														echo $this->Html->link('', array('controller' => 'users', 'action'=>'change_status_user_id', $each_user['id']),array('class'=>'fas fa-user-times deactivate_button','title'=>'Deactivate'));
													} else {
														echo $this->Html->link('', array('controller' => 'users', 'action'=>'change_status_user_id', $each_user['id']),array('class'=>'fas fa-check activate_button','title'=>'Activate'));
													}
											?>
										</td>
									</tr>

                        <?php } $sr_no++; $i=$i+1; 
                        } 
                    } 
                    ?>
				</tbody>
			</table>
		</div>
	<?php echo $this->Form->end(); ?>
</div>