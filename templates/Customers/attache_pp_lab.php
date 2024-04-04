
<?php
/**
 * Updated file added own lab option and can be delete added firm  
 * @author Shankhpal Shende
 * @version 15/06/2023
 */
echo $this->Html->css('Replica/attach_pp_lab'); ?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'firm_form','novalidate'=>'novalidate')); ?>

<section class="content form-middle form_outer_class" id="form_outer_main">
	<a href="../customers/secondary_home" class="btn btn-primary">Back</a>
	<div class="container-fluid form-group wd1080">
		<h5 class="mt-1 mb-2">Application For Attach Printing Press / Laboratory</h5>
		<div class="row">
			<div class="col-md-12">
				<div id="firm_details_block" class="card card-success">
					<div class="card-header"><h3 class="card-title">Attach Printing Press / Laboratory</h3></div>
					<div class="form-horizontal">
						<p class="note"><strong>Note:</strong></p>
						<ol>
							<li>This module is useful to attach Packer with Printing Press or Laboratory</li>
							<li>Only one laboratory can be attached with one packer, Printing Press can be multiple</li>
							<li>This is mandatory to attach Printing Press and Laboratory to apply for Replica allotment.</li>
						</ol>
						<hr>
						<div class="card-body">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8">
								<div class="form-group row ">
									<?php
												if(trim($laboratory_type_name) == "Own Laboratory"){
													$options=array('pp'=>'Printing Press','lab'=>'Authorised Domestic Laboratory','wonlab'=>'Own Laboratory');
												}else{
													$options=array('pp'=>'Printing Press','lab'=>'Authorised Domestic Laboratory');
												}
												
										$attributes=array('legend'=>false, 'value'=>'', 'id'=>'pp');
										echo $this->form->radio('maptype',$options,$attributes); ?>
								</div>
								<div class="pp box">
									<?php echo $this->Form->control('pp_id', array('type'=>'select', 'id'=>'pp','options'=>$printing_data, 'value'=>$selected_PP,'empty'=>'--Select Authorised Printers--', 'class'=>'form-control', 'label'=>'Authorised Printers', 'required'=>true)); ?>
								</div>
								<div class="lab box">
									<?php echo $this->Form->control('lab_id', array('type'=>'select', 'id'=>'lab','options'=>$lab_data, 'value'=>$selected_lab,'empty'=>'--Select Authorised Laboratory--', 'class'=>'form-control', 'label'=>'Authorised Laboratory', 'required'=>true)); ?>
								</div>
					
										<div class="wonlabmargin">
											<?php echo $this->Form->control('won_id', array('type'=>'select', 'id'=>'won_lab','options'=>$own_lab_data, 'value'=>$own_lab_data, 'class'=>'form-control', 'readonly'=>'true','label'=>false)); ?>
											<?php echo $this->Form->control('won_lab_name', array('type'=>'hidden', 'id'=>'won_lab', 'value'=>$own_lab_data, 'class'=>'form-control','label'=>false)); ?>
							</div>
								</div>
								<div class="col-md-2"></div>
							</div>

							<div class="row">
								<div class="column">
									<table class="table table-bordered">
										<?php if (!empty($resultArr)): ?>
									<tr>
												<th class="tablehead">Sr.No.</th>
										<th>Attached Printing Press</th>
												<th>Action</th>
									</tr>
											<?php $i = 1; ?>
											<?php foreach ($result as $each_pp): ?>
												<?php if ($each_pp['type'] == 'pp'): ?>
										<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo isset($each_pp['p_name']) ? $each_pp['p_name'] : ''; ?></td>
														<td>
															<a href="#deleteEmployeeModal" class="delete_pp_id far fa-trash-alt" data-toggle="modal" id="<?php echo $each_pp['id']; ?>"></a>
														</td>
										</tr>
													<?php $i++; ?>
												<?php elseif ($resultArray_ca_pp == null): ?>
													<tr>
														<td colspan="7" class="fs-4"><?php echo "NO Records Available"; ?></td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</table>
								</div>
								
								<div class="column">
								<?php if ($is_own_lab == null): ?>
									<table class="table table-bordered lab">
										<?php if (!empty($resultArr)): ?>
									<tr>
												<th class="tablehead">Sr.No.</th>
										<th>Attached Laboratory</th>
												<th>Action</th>
									</tr>
											<?php $i = 1; ?>
									<?php 
											 foreach ($result as $each_lab): ?>
												<?php if ($each_lab['type'] == 'lab'): ?>
										<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo $each_lab['l_name']; ?></td>
														<td>
															<a href="#" class="delete_lab_id far fa-trash-alt" id="<?php echo $each_lab['id']; ?>"></a>
														</td>
										</tr>
													<?php $i++; ?>
												<?php elseif ($resultArray_ca_pp == null): ?>
													<tr>
														<td colspan="7" class="fs-4"><?php echo "NO Records Available"; ?></td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</table>

								<?php elseif ($is_own_lab == "yes"): ?>
									<table class="table table-bordered won-lab">
										<?php if (!empty($resultArray_own_lab)): ?>
											<tr>
												<th class="tablehead">Sr.No.</th>
												<th>Attached Own Laboratory</th>
												<th>Action</th>
											</tr>
											<?php $i = 1; ?>
											<?php 
											 foreach ($resultArray_own_lab as $lab_value): ?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><?php echo $lab_value['lab_name']; ?></td>
													<td>
														<a href="#" class="delete_own_lab_id far fa-trash-alt" data-toggle="modal" id="<?php echo $lab_value['id'].'/Own'; ?>"></a>
													</td>
												</tr>
												<?php $i++; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</table>
								<?php endif; ?>
						</div>
					</div>
				</div>
								</div>
							</div>
						</div>
	<!-- Delete Modal HTML -->
	
    <div id="replicaModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">                      
                        <h4 class="modal-title">Replica Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <div class="modal-body"></div>
										 <span class="error pl-3" id="remark_err"> </span>   
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="button" class="btn btn-danger" id="delete_pplab" value="Remove">

				</div>
      	  </form>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<?php if(empty($dataArray[0]['customer_id'])){ $btn_name = 'Save & Apply'; }else{ $btn_name = 'Attach'; } ?>
		<?php echo $this->Form->control($btn_name, array('type'=>'submit', 'id'=>'save', 'name'=>'save', 'class'=>'btn btn-success', 'label'=>false,)); ?>
	</div>
	<div class="clear"></div>
</section>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('replica/attach_pp_lab'); ?>
