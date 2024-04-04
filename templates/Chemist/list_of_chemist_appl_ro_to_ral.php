
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Chemist Training</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
						<li class="breadcrumb-item active">Chemist Forwarded to RAL</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
                    <?php  echo $this->Form->create(null, array( 'enctype'=>'multipart/form-data', 'id'=>'ro_toral','class'=>'form_name'));  ?>
                    <div class="card card-purple">
                        <div class="card-header"><h2 class="card-title-new"><b>Chemist Forwarded to RAL for Training</b></h2></div>
                        <div class="form-horizontal">
                            <div class="card-body">
                                <table class="table table-hover table-striped table-bordered ro_to_ral">
                                    <thead class="tableHead">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Chemist ID</th>
                                            <th scope="col">Chemist Name</th>
                                            <th scope="col"><?php if(!empty($office_type)){ echo $office_type ; }?> Office</th>
                                            <th scope="col">RAL/CAL Office</th>
                                            <th scope="col">Forwarded On</th>
                                            <th scope="col">Training End On</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $i = 0;
                                        if(!empty($listOfChemistApp)){
                                        foreach ($listOfChemistApp as $key => $list) {
                                        $shedule_to = date('d-m-Y', strtotime(str_replace('/','.', $list['shedule_to'])));
                                        $forwarded = date('d-m-Y', strtotime(str_replace('/','.', $list['created'])));
                                        ?>

                                        <tr>
                                            <th scope="row"><?php echo $i+1; ?></th>
                                            <td><?php echo $list['chemist_id'];?></td>
                                            <td><?php echo $list['chemist_first_name']."&nbsp".$list['chemist_last_name'];?></td>
                                            <td><?php echo $ro_office[$i]; ?></td>
                                            <td><?php echo $ral_offices[$i] ;?></td>
                                            <td><?php echo $forwarded;?></td>
                                            <td><?php echo $shedule_to;?></td>
                                            <td><?php if(!empty($ral_schedule_pdf[$i])) { ?> <a href="<?php echo $ral_schedule_pdf[$i] ;?>" target="_blank" type="application/pdf" rel="alternate" class="cNavy far fa-file-pdf"> Letter</a>
                                            | <?php } ?> <a href="<?php echo $this->request->getAttribute("webroot").'scrutiny/form_scrutiny_fetch_id/'. $chemisttblId[$i]['id']. '/view/' .  $list['appliaction_type']; ?>" class="fas far fa-eye"> Application</a>
                                            </td>
                                        </tr>
                                        <?php $i++; } } ?>
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


<?php echo $this->Html->script('chemist/forward_applicationto_ral');?>
