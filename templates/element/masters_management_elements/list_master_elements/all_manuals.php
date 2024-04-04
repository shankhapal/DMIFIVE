<thead>
	<tr>
		<th>SR.No</th>
		<th>Manual</th>
		<th>Link</th>
		<th>For</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
	<?php
		if (!empty($all_records)) {
			$sr_no=1;
			foreach ($all_records as $each_record ) { ?>
			<tr>
				<td><?php echo $sr_no; ?></td>
				<td><?php echo $each_record['manual_name'];?></td>
				<td><a href="<?php echo $each_record['link'];?>" target="_blank">View Manual</a></td>
				<td><?php echo $each_record['for_user'];?></td>
				<td>
					<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each_record['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> |
					<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord', $each_record['id']),array('class'=>'glyphicon glyphicon-remove delete_division_type','title'=>'Delete')); ?>
				</td>
			</tr>
	<?php	$sr_no++; } } ?>
</tbody>

