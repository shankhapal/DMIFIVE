<?php $username = $_SESSION['username']; ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Admin Log History</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Admin Logs</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<section class="col-lg-12">
					<?php echo $this->Form->create(); ?>
						<div class="card card-dark">
							<div class="card-header"><h3 class="card-title-new">Given Below is log history for Admin User</h3></div>
								<div class="card-body">
									<table id = "user_logs_table" class="table table-hover table-bordered table-striped">
										<thead class="tablehead">
											<tr>
												<th>Date</th>
												<th>User Id</th>
												<th>TimeIn</th>
												<th>TimeOut</th>
												<th>Duration</th>
												<th>Remark</th>
												<th>IP Address</th>
											</tr>
										</thead>
										<tbody>
										<?php
											if (!empty($user_logs)) {
													$i = 0;
													foreach ($user_logs as $user_log) { ?>
													<?php $time_in = strtotime($user_log['time_in']);
																$time_out = strtotime($user_log['time_out']);
																$login_duration = $time_out - $time_in;
													?>
													<tr>
														<td><?php echo $user_log['date'];?></td>
														<td><?php echo base64_decode($user_log['email_id']); //for email encoding ?></td>
														<td><?php echo $user_log['time_in'];?></td>
														<td><?php //updated the logic on 20-11-2020 by Amol
															if(!empty($user_log['time_out'])){
																echo $user_log['time_out'];
															}else{ echo '---'; } ?>
														</td>
														<td>
															<?php
															//updated the logic on 20-11-2020 by Amol
																if($i==0){
																	echo "Current Session";

																}else{
																	if(!empty($user_log['time_out'])){
																		echo round($login_duration/60)." min ".($login_duration%60)." sec";
																	}else{
																		echo "0 min 0 sec";
																	}
																}
															?>
															</td>
															<td><?php
																	$remark = $user_log['remark'];
																	if($remark == 'Success')
																		$badge = "success";
																	else if ($remark == 'Failed')
																		$badge = "danger";
																	else
																		$badge = "info";
																	echo "<span class='badge badge-".$badge."'>".$remark."</span>";
																	?>
															</td>
															<td><?php echo $user_log['ip_address'];?></td>
														</tr>
													<?php $i=$i+1; } }?>
												</tbody>
											</table>
										</div>
									</div>
								<?php echo $this->Form->end(); ?>
							</section>
						</div>
					</div>
				</section>
			</div>



<?php echo $this->Html->script('Users/admin_logs'); ?>
