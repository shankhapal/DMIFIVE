<thead>
	<tr>
		<th>SR.No</th>
		<th>Firm Type</th>
		<th>Period (month)</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php
	if (!empty($all_records)) {

		$sr_no=1;
		foreach ($all_records as $each_record) { ?>

			<tr>
				<td><?php echo $sr_no; ?></td>
				<td><?php if($each_record['firm_type'] == 1){
						echo "Grant of Certificate of Authorisation";
						}elseif($each_record['firm_type'] == 2){
						echo "Grant of Permission to Printing Press";
						}elseif($each_record['firm_type'] == 3){
							echo "Approval of Laboratory";
						}
						?></td>

				<td><?php echo $each_record['period'];?></td>
				<td><?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each_record['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> |
				
				</td>
			</tr>

		<?php	$sr_no++; } } ?>
	<input type="hidden" id="masterId" value="<?php echo $masterId;?>">
</tbody>

<?php echo $this->Html->script('element/masters_management_elements/list_master_elements/all_period'); ?>
