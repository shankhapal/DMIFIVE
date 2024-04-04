
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Chemist Training</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
						<li class="breadcrumb-item active">Chemist Forwarded to RO</li>
					</ol>
				</div>
			</div>
		</div>
    </div>
    <section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
                    <div class="card card-purple">
                        <div class="card-header"><h2 class="card-title-new"><b>Chemist Application Forwarded From RAL to <?php if(!empty($_SESSION['level_3_for'])){ echo $_SESSION['level_3_for'];}?> for Training</b></h2></div>
                        <div class="form-horizontal">
                            <div class="card-body">
                                <table class="table table-bordered ral_to_ro">
                                    <thead class="tableHead">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Chemist ID</th>
                                            <th scope="col">Chemist Name</th>
                                            <th scope="col">RAL/CAL Office</th>
                                            <th scope="col"><?php if(!empty($_SESSION['level_3_for'])){ echo $_SESSION['level_3_for'];}?> Office</th>
                                            <th scope="col">Forwarded On</th>
                                            <th scope="col">Training Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        if(!empty($listOfChemistApp)) {
                                            foreach ($listOfChemistApp as $key => $list) { ?>
                                            <tr>
                                                <th scope="row"><?php echo $i+1; ?></th>
                                                <td><?php echo $list['chemist_id']; ?></td>
                                                <td><?php echo $list['chemist_first_name']."&nbsp".$list['chemist_last_name']; ?></td>
                                                <td><?php echo $ral_office[$i]; ?></td>
                                                <td><?php echo $ro_offices; ?></td>

                                                <?php if(!empty($list['created'])){
                                                    $forwarded = date('d-m-Y', strtotime(str_replace('/','.',$list['created']))); ?>
                                                    <td><?php echo $forwarded;?></td>
                                                <?php } ?>

                                                <?php if($list['training_completed'] == 1){ ?>
                                                    <td><?php echo "Completed";?></td>
                                                <?php } ?>

                                                <td>
                                                    <a href="<?php echo $list['pdf_file'] ;?>" target="_blank" type="application/pdf" rel="alternate" class="far fa-envelope-open"> Relieving Letter</a> |

                                                <?php if(empty($isTrainingComplete[$i]) && empty($is_trainingScheduleRO[$i]) && $is_trainingScheduleRO[$i] == '' ){ ?>
                                                    <a href="<?php echo $this->getRequest()->getAttribute('webroot')."chemist/trainingScheduleAtRo/".$list['id']; ?>" class="text-dark fas fa-calendar-check">  Training Schedule At <?php if(!empty($_SESSION['level_3_for'])){ echo $_SESSION['level_3_for'];} ?></a>
                                                <?php }elseif(!empty($is_trainingScheduleRO[$i]) && $is_trainingScheduleRO[$i] == 1  && (empty($reschedule_status[$i]) && $reschedule_status[$i] != 'confirm')) { ?>
                                                    <a class="btn btn-success text-white trainingScheduleConfirm" id ="triningDatesConfirm">Confirm Dates</a><br> <br>
                                                    <a href="<?php echo $this->getRequest()->getAttribute('webroot')."chemist/trainingScheduleAtRo/".$list['id']; ?>" class=" btn btn-success" id ="RescheduleTrainingDates">Reschedule Training Dates</a>
                                                <?php }elseif(empty($isTrainingComplete[$i]) && $is_trainingScheduleRO[$i] == 1 && $reschedule_status[$i] == 'confirm'){?>
                                                    <a href="<?php echo $ro_schedule_letter[$i] ;?>" target="_blank" type="application/pdf" rel="alternate"><?php if(!empty($_SESSION['level_3_for'])){ echo $_SESSION['level_3_for'];}?> Training Schedule Letter</a>
                                                    | <a href="<?php echo $this->getRequest()->getAttribute('webroot')."chemist/chemistTrainingCompleteAtRo/".$list['id'];?>" class=" btn btn-success">Mark Training Completed</a>
                                                <?php }else{
                                                    if(!empty($pdf_file)){ ?>
                                                    <a href="<?php echo $pdf_file[$i] ;?>" target="_blank" type="application/pdf" rel="alternate"><?php if(!empty($_SESSION['level_3_for'])){ echo $_SESSION['level_3_for'];}?> Relieving Letter</a>
                                                <?php } ?>
                                                | <?php if(!empty($chemistTblid[$i]) && empty($grant_approval_pdf[$i]) && !empty($isTrainingComplete[$i]) ){?>
                                                    <a href="<?php echo '../scrutiny/form_scrutiny_fetch_id/'.$chemistTblid[$i].'/view/'.  $list['appliaction_type'];?>" class="badge badge-success fas fa-hand-point-right"> Proceed Grant Certificate</a>
                                                <?php }else{ ?>
                                                    <a href="<?php if(!empty($grant_approval_pdf[$i])){ echo $grant_approval_pdf[$i] ; }?>" target = "_blank">Grant Certificate</a>
                                                <?php }
                                                }

                                                if(empty($grant_approval_pdf[$i])){
                                                    echo '|  <button id=rejectApp_'.$chemistTblid[$i].' class = "rejectModel badge badge-danger far fa-times-circle" value='.$list['chemist_id'].' appl_type ="' .$appl_type[$i].'"> Reject </button>';
                                                }
                                                $i++;
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php  } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- reject application model body -->
<!-- The Modal -->
 <div id="myModal" class="modal1">



 <!--Modal content -->
<div class="modal-content">
  <div class="modal-header">

    <h4>Rejection of Application for Chemist Training</h4>
    <span class="close">&times;</span>
  </div>
  <div class="modal-body">
    <table id="rej-appl-table" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Application Type</th>
          <th>Application Id</th>
          <th>Remark/Reason</th>
          <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <tr>
        <?php  echo $this->Form->create(null, array( 'enctype'=>'multipart/form-data', 'id'=>'rejectApp','class'=>'form_name'));  ?>
          <td>
           <?php echo $this->Form->control('application_type', array('type'=>'text', 'readonly'=>true, 'class'=>'cvOn cvReq cvAlphaNum applicationType', 'value'=>'', 'label'=>false)) ;?>
          </td>
          <td><?php echo $this->Form->control('application_id', array('type'=>'text', 'readonly'=>true, 'class'=>'cvOn cvReq cvAlphaNum chemistId ', 'label'=>false)) ;?>

        </td>

          <td><?php  echo $this->Form->control('remark', array('type'=>'textarea', 'id'=>'remark', 'escape'=>false,  'placeholder'=>'Enter Remark/Reason', 'value'=>'','class'=>'cvOn cvReq cvAlphaNum reject',   'label'=>false)); ?>
          <div><b class="errorClass text-red"></b></div></td>
          <td><a class="btn btn-primary" type="submit" id="rejectBtn">Reject</a></td>
          <?php  echo $this->Form->end();  ?>
        </tr>
      </tbody>
  </table>
  </div>
  <div class="modal-footer">

  </div>
</div>

</div>

<?php echo $this->Html->css('chemist/reject_application');?>
<?php echo $this->Html->Script('chemist/reject_application');?>

