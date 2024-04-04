<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Application Pdf</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Application Pdf</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<?php echo $this->Form->create(); ?>
						<div class="card card-cyan">
							<div class="card-header"><h3 class="card-title-new">List of Report for Routine Inspection</h3></div>
							<div class="card-body">
								<div class="container">
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group row ">
												<?php
													$options=array('ca'=>' Certificate Of Authorisation');
													$attributes=array('legend'=>false, 'value'=>'yes', 'id'=>'ca');
													echo $this->form->radio('pdf',$options,$attributes); 
												?>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group row ">
												<?php
													$options=array('pp'=>' Printing Permission');
													$attributes=array('legend'=>false, 'value'=>'', 'id'=>'pp');
													echo $this->form->radio('pdf',$options,$attributes); 
												?>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group row ">
												<?php
													$options=array('lab'=>' Approval Of Laboratory');
													$attributes=array('legend'=>false, 'value'=>'', 'id'=>'lab');
													echo $this->form->radio('pdf',$options,$attributes); ?>
											</div>
										</div>
									</div>
								</div>
								<?php
								$pdf_tables = array(

									'ca' => array(
											'id' => 'ca_pdf_table',
											'appl_array' => $appl_array_ca,
									),
									'pp' => array(
											'id' => 'pp_pdf_table',
											'appl_array' => $appl_array_pp,
									),
									'lab' => array(
											'id' => 'lab_pdf_table',
											'appl_array' => $appl_array_lab,
									),

                );
								foreach ($pdf_tables as $pdf_type => $pdf_table) {
									$table_id = $pdf_table['id'];
									$appl_array = $pdf_table['appl_array'];		
										
									if (!empty($appl_array)) {
										echo '<table id="' . $table_id . '" class="table table-hover table-bordered table-striped">';
										echo '<thead class="tablehead">';
										echo '<tr>';
										echo '<th>Sr. No.</th>';
										echo '<th>Applicant Id</th>';
										echo '<th>Approved Date</th>';
										echo '<th>Report. | Pdf</th>';
										echo '<th>Version</th>';
										echo '</tr>';
										echo '</thead>';
										echo '<tbody>';

										$i = 0;
										foreach ($appl_array as $each) {
											echo '<tr>';
											echo '<td>' . ($i + 1) . '</td>';
											echo '<td id="customer_id' . $i . '">' . $each['customer_id'] . '</td>';
											echo '<td id="customer_id' . $i . '">' . $each['on_date'] . '</td>';

											$report_pdf_path = explode("/", $each['report_pdf']);
											$report_pdf_name = $report_pdf_path[count($report_pdf_path) - 1];

											echo '<td>';
											echo '<a target="_blank" href="' . $each['report_link'] . '">View</a>';
											echo ' | ';
											echo '<a target="_blank" href="' . $each['report_pdf'] . '">Pdf</a>';
											echo '</td>';

											echo '<td>' . $each['pdf_version'] . '</td>';
											echo '</tr>';

											$i++;
   									}

										echo '</tbody>';
										echo '</table>';
									}
								}							
								?>
								
					<?php echo $this->Form->end(); ?>
				</section>
			</div>
		</div>
	</section>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('othermodules/application_pdf_rti'); ?>
