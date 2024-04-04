<?php ?>
<?php echo $this->Form->create(null, array()); ?>
	<div class="card card-info">
		<div class="card-header">
			<h3 class="card-title-new">List of Officer's wise Pending Application from All Offices Under this Incharge</h3>
		</div>
		
		<!--<div class="card-body">
			<div class="form-group row pd10_0_0">
				<div class="col-md-2">
					<?php //echo $this->Form->control('from_dt',array('type'=>'text','id'=>'from_dt','placeholder'=>'From Date','label'=>false,'class'=>'form-control','readonly'=>true)); ?>
				</div>
				<div class="col-md-2">
					<?php //echo $this->Form->control('to_dt',array('type'=>'text','id'=>'to_dt','placeholder'=>'To Date','label'=>false,'class'=>'form-control','readonly'=>true)); ?>
				</div>
				<div class="col-md-2">
					<?php //echo $this->Form->control('Get Logs',array('type'=>'submit','id'=>'search','label'=>false,'class'=>'btn btn-success')); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>-->
								
		<table id="officer_wise_list_to_incharge" class="table m-0 table-bordered table-striped table-hover">
			<thead class="tablehead">
				<tr>
					<!--<th>Sr. No.</th>-->
					<th>Office</th>
					<th>Officer Name</th>
					<th>Application Id</th>
					<th>Application For</th>
					<th>In Process For</th>
					<th>Last Transaction</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$l=0;
					foreach($getOfficerUnderIncharge as $eachofficer){ ?>

					<?php $i=0;
						foreach($flow_wise_tables as $eachflow){
							$j=0;
							foreach($level_arr as $eachLevel){
								$k=0;
								foreach($checkCurPosition[$l][$i][$j] as $eachAppl){ 
									if(!empty($appl_list[$l][$i][$j][$k])){
									?>
										<tr>
											 <!--<td><?php //echo $k+1;?></td> -->
											<td><?php echo $appl_list[$l][$i][$j][$k]['office_name']; ?></td>
											<td><?php echo $eachofficer['f_name'].' '.$eachofficer['l_name'].' ('.base64_decode($eachofficer['email']).')'; ?></td>
											<td><?php echo $appl_list[$l][$i][$j][$k]['appl_id']; ?></td>
											<td><?php echo $appl_list[$l][$i][$j][$k]['appl_type']; ?></td>			  
											<td><?php echo $appl_list[$l][$i][$j][$k]['process']; ?></td>		
											<td><?php echo substr($appl_list[$l][$i][$j][$k]['last_trans_date'],0,10); ?></td>		
										</tr>
									<?php	}
									$k=$k+1; 
								} 
							$j=$j+1;
							}
						$i=$i+1;
						}
					$l=$l+1;
					} ?>
				</tbody>
		</table>
	</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('othermodules/officer_pending_list_for_RO'); ?>
