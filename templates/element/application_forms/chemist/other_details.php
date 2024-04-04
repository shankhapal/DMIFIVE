

<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'other_details','class'=>'form_name')); ?>
<div id="form_outer_main" class="card card-success form_outer_class">
	<div class="card-header"><h3 class="card-title-new">Other Details</h3></div>
		<div class="form-horizontal mb-3">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-4 mt-3 ml-5">
							<label for="field3"><span>Social Work Experience/Involvement</span>	</label>
						</div>
						<div class="col-md-8 ml-5">
							<?php echo $this->Form->control('social_work', array('type'=>'textarea', 'id'=>'social_work',  'escape'=>false, 'value'=>$section_form_details[0]['social_work'], 'class'=>'cvAlphaNum form-control h80', 'maxlength'=>500, 'label'=>false)); ?>
							<div class="err_cv"></div>
						</div>
					</div>
					<!-- <div class="col-md-12 mt-2">
						<div class="col-md-4 mt-3 ml-5">
							<label for="field3"><span>Membership Of Prestigious Institution</span>	</label>
						</div>
						<div class="col-md-8 ml-5">
							<?php echo $this->Form->control('prest_instit', array('type'=>'textarea', 'id'=>'prest_instit',  'escape'=>false, 'value'=>$section_form_details[0]['prest_instit'], 'class'=>'cvAlphaNum form-control h80', 'maxlength'=>500, 'label'=>false)); ?>
							<div class="err_cv"></div>
						</div>
					</div>
					<div class="col-md-12 mt-2">
						<div class="col-md-4 mt-3 ml-5">
							<label for="field3"><span>Academic Focus/Major Strength In Relevant Field</span>	</label>
						</div>
						<div class="col-md-8 ml-5">
							<?php echo $this->Form->control('academic_focus', array('type'=>'textarea', 'id'=>'academic_focus',  'escape'=>false, 'value'=>$section_form_details[0]['academic_focus'], 'class'=>'cvAlphaNum form-control h80', 'maxlength'=>500, 'label'=>false)); ?>
							<div class="err_cv"></div>
						</div>
					</div>
					<div class="col-md-12 mt-1">
						<div class="col-md-4 mt-3 ml-5">
							<label for="field3"><span>Detail Of An Articles Published Research / Publication</span>	</label>
						</div>
						<div class="col-md-8 ml-5">
							<?php echo $this->Form->control('articles_pub', array('type'=>'textarea', 'id'=>'articles_pub',  'escape'=>false, 'value'=>$section_form_details[0]['articles_pub'], 'class'=>'cvAlphaNum form-control h80', 'maxlength'=>500, 'label'=>false)); ?>
							<div class="err_cv"></div>
						</div>
					</div> -->

					<!--  attachment option added by laxmi Bhadade on 10-07-2023  -->
					<div class="col-md-12 mt-1">
					     <div class="col-md-4 mt-3 ml-5">
							<label for="field3"><span>Please Add Attachment For Any Other Details</span></label>
                         </div> 	
					     <div class="col-md-8 ml-5">	
							<?php echo $this->Form->control('other_details_attachment', array('type'=>'file', 'id'=>'other_details_attachment',  'escape'=>false, 'value'=>$section_form_details[0]['other_details_attachment'], 'class'=>'cvAlphaNum form-control ', 'label'=>false)); ?>
							<?php // if other_details_attachment uploaded then it visible added by laxmi [07-07-2023]
						  if(!empty($section_form_details[0]['other_details_attachment'])){
							$reciept = $section_form_details[0]['other_details_attachment'];?>
                            <a href = "<?php echo $reciept; ?>" target = "_blank" > Other Option Attachmnet </a>
						 <?php }
						?>
						</div>

                       </div>


				</div>
			</div>
		</div>

	<?php echo $this->Form->control('application_dashboard', array('type'=>'hidden', 'id'=>'application_dashboard', 'value'=>$_SESSION['application_dashboard'])); ?>
</div>
