<?php //pr($office_type);die; ?>
<?php echo $this->Html->css('dashboard/get-scenarios-view'); ?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'firm_form')); ?>
<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid form-group wd1080">
		<h5 class="mt-1 mb-2">Scenario-Based Certificate Granting Process</h5>
		<div class="row">
			<div class="col-md-12">
				<div id="firm_details_block" class="card card-success">
					<div class="card-header"><h3 class="card-title"></h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
								<div class="form-group row scenariosoption ">
									<div class="fieldgroup">
									<?php
										$options=array('pp'=>'Printing Press','ca_bevo'=>'CA BEVO','ca_non_bevo'=>'CA NON BEVO','lab_d'=>'Laborotory Domestic','lab_e'=>'Laborotory Export');
										$attributes=array('legend'=>false, 'value'=>'', 'id'=>'scenario','class'=>'radio');
										echo $this->form->radio('maptype',$options,$attributes); ?>
										 
								</div>
								
								<div class="container">
									<div class="scenarios-title" >
												<p class="font-weight-bold">for <span class="text-primary"><?php echo base64_decode($username); ?> </span>the following scenarios with roles are below displayed </p>
										</div>
									<div class="justify-content-center c-container printing_press">
										
									   <?php 
												if($office_type == 'SO'){ 

													if($officerPresentInOff > 1 && $so_power_to_grant_appl == 'yes'){?>
														 <!-- condition for SO  office where SO In-charge has power(role) to grant P.P (with more than one officer posted) -->
															 <div class="scenarios">
															    <div class="scenarios-title" >
																      <h3>SO  office where SO In-charge has power(role) to grant P.P (with more than one officer posted)</h3>
												                <hr class="new1">
														      </div>
                                  <p>Application will come to SO office.</p>
																	<p>SO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
																	<p>SO In-charge will allocate the application for site Inspection to IO officer.</p>
																	<p>SO In-charge will accept the report.</p>
																	<p>SO In-charge will forward the application to RO In-charge</p>
																	<p>(Conditional) RO In-charge will forward the application for HO approval</p>
																	<p>RO In-charge will approve the application and forward again to SO</p>
																	<p>SO In-charge will Grant the application.</p>
												       </div>
												<?php 	}elseif($officerPresentInOff > 1 ) { ?>
												    
												<!-- // condition for SO office with more than one office posted -->
												    <div class="scenarios">
															<div class="scenarios-title" >
																<h3>SO office with more than one officer posted</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to SO office.</p>
															<p>SO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
															<p>SO In-charge will allocate the application for site Inspection to IO officer.</p>
															<p>SO In-charge will accept the report.</p>
															<p>SO In-charge will forward the application to RO In-charge</p>
															<p>(Conditional) RO In-charge will forward the application for HO approval</p>
															<p>RO In-charge will grant</p>
												    </div>
												<?php }elseif($officerPresentInOff == 1) { ?>
											     <!-- condition for SO office with single officer posted -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>SO office with single officer posted</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to SO office.</p>
															<p>SO In-charge will Transfer the application to RO In-charge.</p>
															<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
															<p>RO In-charge will allocate the application for site Inspection to IO officer.</p>
															<p>RO In-charge will accept the report.</p>
															<p>(Conditional) RO In-charge will forward the application for HO approval</p>
															<p>RO In-charge will grant</p>
												    </div>

											 <?php } }elseif($office_type == 'RO'){ ?>

												     <!-- condition for Applications from RO Jurisdiction -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Applications from RO Jurisdiction</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to RO office.</p>
															<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
															<p>RO In-charge will allocate the application for site Inspection to IO officer.</p>
															<p>RO In-charge will accept the report.</p>
														  <p>(Conditional) RO In-charge will forward the application for HO approval</p>
															<p>RO In-charge will grant</p>
												    </div>
														<br>
														
											<?php } ?>
                        <!-- condition for Applications from RO Jurisdiction -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>if printing press sponsored by CA holder (outside jurisdiction)</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to SO office/RO office.</p>
															<p>SO/RO In-charge will transfer the application to the RO/SO office of the sponsored CA jurisdiction.</p>
															<p>The application will be then processed by the RO/SO office of the sponsored CA jurisdiction.</p>
															<p>As per applicable i.e. as per above scenario</p>
												    </div>
                    </div>
										
										<div class="justify-content-center c-container ca_non_bevo" id="ca_non_bevo">
									   <?php 
											 if($office_type == 'SO'){ 
												
												if($officerPresentInOff > 1){?>
													 <!-- condition for SO office with more than one officer posted -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>SO office with more than one officer posted</h3>
												       <hr class="new1">
														  </div>
                               <p>Application will come to SO office.</p>
															 <p>SO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
															 <p>SO In-charge will allocate the application for site Inspection to IO officer.</p>
															 <p>SO In-charge will accept the report.</p>
															 <p>SO In-charge will grant</p>
												    </div>
											  <?php	}elseif($officerPresentInOff == 1){ ?>
												    <!-- condition for SO office with single officer posted -->
														<div class="scenarios">
																<div class="scenarios-title" >
																	<h3>SO office with single officer posted</h3>
																<hr class="new1">
																</div>
																<p>Application will come to SO office.</p>
																<p>SO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
																<p>SO In-charge will allocate the application for site Inspection to IO officer.</p>
																<p>SO In-charge will accept the report.</p>
																<p>SO In-charge will forward the application to RO In-charge</p>
																<p>RO In-charge will approve the application and forward again to SO</p>
																<p>SO In-charge will Grant the application.</p>
															</div>
									     <?php }}elseif($office_type == 'RO'){ ?>
                                <!-- condition for Applications from RO Jurisdiction -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Applications from RO Jurisdiction</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to RO office.</p>
															<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
															<p>RO In-charge will allocate the application for site Inspection to IO officer.</p>
															<p>RO In-charge will accept the report.</p>
														  <p>RO In-charge will grant</p>
												    </div>
														<br>
													
											 <?php } ?>	
											 	 <!-- condition for Applications from RO Jurisdiction -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Application for CA Non BEVO Export</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to RO/SO office.</p>
															<p>Further the application will be processed as per the above scenario.</p>
															<p>In CA export the Site inspection Officer will upload a report document with single upload option.</p>
															<p>Further grant by SO/RO in-charge.</p>
												    </div>
                    </div>

										<div class="justify-content-center c-container ca_bevo" id="ca_bevo">
									   <?php 
										    if($office_type == 'SO'){ ?>
                           <?php if($officerPresentInOff > 1) {?>
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>SO office with more than one officer posted</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will come to SO office.</p>
															<p>SO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
															<p>SO In-charge will allocate the application for site Inspection to IO officer.</p>
															<p>SO In-charge will accept the report.</p>
															<p>SO In-charge will forward the application to RO In-charge</p>
															<!-- as per change request added 6 point on 18-04-2023 by shankhpal shende -->
															<p>RO In-charge will forward the application to HO QC for Jt. AMA approval</p> 
															<p>RO In-charge will grant</p>
												    </div>
												    <br>
														<?php }elseif($officerPresentInOff == 1){ ?>
                                 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>SO office with single officer posted</h3>
												       <hr class="new1">
														  </div>
                              <p>Application will be processed as per above scenario </p>
															<p>There will be no conditional flow on the no. of officer posted in SO office.</p>
												    </div>
														<?php } ?>
												 <?php }else{?>
                              <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Applications from RO Jurisdiction</h3>
												       <hr class="new1">
														  </div>
                                <p>Application will come to RO office.</p>
																<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
																<p>RO In-charge will allocate the application for site Inspection to IO officer.</p>
																<p>RO In-charge will accept the report.</p>
																<!-- as per change request added 6 point on 18-04-2023 by shankhpal shende -->
																<p>RO In-charge will forward the application to HO QC for Jt. AMA approval</p> 
																<p>RO In-charge will grant</p>
												    </div>
													<?php } ?>
                     	</div>

										<div class="justify-content-center c-container lab_d">
									   <?php 
										   if($office_type == "RO" ){ ?>
											   <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Application from RO Jurisdiction  (If Laboratory is NABL Accredited)</h3>
												       <hr class="new1">
														  </div>
                                <p>Application will come to RO office.</p>
																<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
																<p>No site inspection will be done for NABL accredited Laboratory.</p>
																<p>RO In-charge will forward the application for HO approval.</p>
																<p>After AMA approval RO In-charge will grant application</p>
												    </div>
											      <br>
														 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Application from RO Jurisdiction  (If Laboratory is not NABL Accredited)</h3>
												       <hr class="new1">
														  </div>
                                  <p>Application will come to RO office.</p>
																	<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
																	<p>RO In-charge will allocate the application for site Inspection to IO officer.</p>
																	<p>RO In-charge will accept the report.</p>
																	<p>RO In-charge will forward the application for HO approval.</p>
																	<p>After AMA approval RO In-charge will grant application</p>
												    </div>
											      <br>
															<div class="scenarios">
																<div class="scenarios-title" >
																	<h3>Application from SO jurisdiction</h3>
																<hr class="new1">
																</div>
																		<p>No laboratory applications will be processed by SO office.</p>
																		<p>Application will come to RO office.</p>
																		<p>Further the application will be processed as per applicable i.e. as per above scenario.</p>
															</div>
									  	<?php } ?>
                    </div>

										<div class="justify-content-center c-container lab_e">
									   <?php 
										   if($office_type == "RO" ){?>
											     <!-- condition for Application from RO Jurisdiction  (If Laboratory is NABL Accredited) -->
													 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Application from RO Jurisdiction  (If Laboratory is NABL Accredited) </h3>
												       <hr class="new1">
														  </div>
																	<p>Application will come to RO office.</p>
																	<p>RO In-charge will scrutinize the application (either from Scrutiny officer or by himself)</p>
																	<p>As the NABL accreditation is mandatory for laboratory export, No site inspection will be done.</p>
																	<p>RO In-charge will forward the application for HO approval.</p>
																	<p>After AMA approval  Dy. AMA (HO QC) will grant application</p>
												    </div>
														<br>
														 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Application from RO Jurisdiction  (If Laboratory is not NABL Accredited)</h3>
												       <hr class="new1">
														  </div>
																	<p>For Approval of Laboratory (export) the NABL accreditation is mandatory.</p>
																	<p>The application will not be submitted without NABL accreditation.</p>
												    </div>
														<br>
														 <div class="scenarios">
															<div class="scenarios-title" >
																<h3>Application from SO jurisdiction</h3>
												       <hr class="new1">
														  </div>
																	<p>No laboratory applications will be processed by SO office.</p>
																	<p>Application will come to RO office.</p>
																	<p>Further the application will be processed as per applicable i.e. as per above scenario.</p>
												    </div>
														
											<?php  } ?>
                    </div>

									</div>
								</div>
						  </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<input type="hidden" id="checkrole" value="<?php echo $office_type; ?>">
	<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('scenarios/get_scenarios_view'); ?>
