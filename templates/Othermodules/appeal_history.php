<?php ?>
<?php echo $this->Form->create(null, array()); ?>
	<div class="card card-info">
		<div class="card-header"><h3 class="card-title-new">List of Approved Appeal</h3></div>
		<table id="suspended_firms" class="table m-0 table-bordered table-striped table-hover">
			<thead class="tablehead">
				<tr>
					<th>Sr. No.</th>
					<th>Firm Name</th>
					<th>Firm Contact</th>
					<th>Applicant Id</th>
					<th>Appeal Approval Date</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$i=0;
					if (!empty($approved_appeal_list)) {

						foreach ($approved_appeal_list as $each) { ?>

						<tr>
							<td><?php echo $i+1;?></td>
							<td><?php echo $each['firm_name'];?></td>
							<td>
								<?php echo "<span class='badge'>Mobile:</span>".base64_decode($each['mobile_no']); ?>
								<br>
								<?php echo "<span class='badge'>Email:</span>".base64_decode($each['email']); ?>
							</td>
							<td><?php echo $each['customer_id'];?></td>
							<td>
								<?php 
									$date = $each['date'];
									$from_date = DateTime::createFromFormat('d/m/Y H:i:s', $date)->format('d/m/Y');
									echo $from_date;
								?>
							</td>
						</tr>
						<?php	$i=$i+1;
						}
					} 
				?>
			</tbody>
		</table>
	</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('othermodules/list_of_suspended_firms'); ?>
