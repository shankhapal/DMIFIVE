<?php //added new file by Laxmi Bhadade for movement of application on 20-07-2023 ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><label class="badge info2 float-left"> Application Movement's</label></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Application Movements</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->create(null,array('id'=>'movement_application','class'=>'form-group')); ?>
    <section class="content form-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-Lightblue">
                        <div class="card-header">
                            <h3 class="card-title-new"> Application Movements</h3>
                        </div>
                        <div class="form-horizontal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">


                                            <label>Application Id <span class="cRed">*</span></label>
                                            <?php echo $this->Form->control('appl_id', array('type'=>'select', 'id'=>'appl_id', 'label'=>false, 'empty'=>'--Search Application ID--','class'=>'form-control selectpicker ', 'aria-hidden'=>true)); ?>
                                            <span id="error_appl_id" class="error invalid-feedback"></span>


                                        </div>
                                    </div>
                                    <div class="col-sm-3 appli_type">
                                        <div class="form-group">
                                            <label>Application type <span class="cRed">*</span></label>
                                            <?php //echo $this->Form->control('appl_type', array('type'=>'select', 'id'=>'appl_type', 'label'=>false, 'options'=>$applTypesList, 'empty'=>'--Select--','class'=>'form-control', 'aria-hidden'=>true)); ?>
                                            <?php echo $this->Form->control('appl_type', array('type'=>'select', 'id'=>'appl_type', 'label'=>false, 'empty'=>'--Select--','class'=>'form-control selectpicker ', 'aria-hidden'=>true)); ?>

                                            <span id="error_appl_type" class="error invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 chemist">
                                        <div class="form-group">
                                            <label>Chemist Application ID <span class="cRed">*</span></label>
                                            <?php //echo $this->Form->control('appl_type', array('type'=>'select', 'id'=>'appl_type', 'label'=>false, 'options'=>$applTypesList, 'empty'=>'--Select--','class'=>'form-control', 'aria-hidden'=>true)); ?>
                                            <?php echo $this->Form->control('chemist_id', array('type'=>'select', 'id'=>'chemist_id', 'label'=>false, 'empty'=>'--Select--','class'=>'form-control selectpicker ', 'aria-hidden'=>true)); ?>

                                            <span id="error_chemist_id" class="error invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><span></span><br></label>
                                            <?php echo $this->Form->submit('Get Movement', array('type'=>'submit', 'id'=>'get_movement', 'label'=>'Get Movement', 'class'=>'form-control btn btn-success')); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="error_noappl">
                                       <label class="badge badge-danger text cneter"></label>
                                    </div>
                                 </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>
    </section>




    <section class="content form-middle listdata">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-Lightblue">
                        <div class="card-header">
                            <h3 class="card-title-new"> Application Movement</h3>
                            <?php if(!empty($application_type) && !empty($firm_name)){ ?>
                            <br>
                            <h6 class="text-center"><span>Application Type: <?php echo $application_type;?></span>
                                &nbsp; <span>Application ID: <?php echo $application_id;?></span> &nbsp; <span> Firm
                                    Name : <?php echo $firm_name;?></span></h6>
                            <?php } ?>
                        </div>
                        <div class="form-horizontal">
                            <div class="card-body">
                                <div class="row1">
                                    <table id="movement_history"
                                        class="table m-0 table-bordered table-striped table-hover movmentTable">
                                        <thead class="tablehead">
                                            <tr>
                                                <th scope="col">MO. NO.</th>
                                                <th scope="col">From</th>
                                                <th scope="col">To</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $i=0;
                           
                            if(!empty($output)){
                            $countArray = count($output);
                            foreach ($output as $key => $fm) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo ($countArray+1)-1; ?>
                                                </td>
                                                <td>
                                                    <?php echo $fm['from']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $fm['to']; ?>
                                                </td>
                                                <?php 
                              if(date('Y-m-d H:i:s' , strtotime($fm['sentdate'])) == '1970-01-01 05:30:00'){ ?>
                                                <td>
                                                    <?php echo "Old Application"; ?>
                                                </td>
                                                <?php }else{ ?>
                                                <td>
                                                    <?php echo $fm['sentdate']; ?>
                                                </td>
                                                <?php } ?>
                                                <td>
                                                    <?php echo $fm['action']; ?>
                                                </td>
                                            </tr>
                                            <?php $countArray--; $i++; } } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>
<?php echo $this->Html->script('movements/movements_appl'); ?>