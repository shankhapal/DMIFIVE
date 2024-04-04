<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><label class="badge badge-primary">Masters Home</label></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
                        <li class="breadcrumb-item active">Masters Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content form-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-Lightblue">
                        <div class="card-header"><h3 class="card-title-new">Masters Management</h3></div>
                        <?php echo $this->Form->create(); ?>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/1">State</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/2">District</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/3">Business Type</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/4">Packing Type</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/5">Laboratory Type</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/6">Machine Type</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/7">Tank Shape</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/8">Application Charges</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/9">Business Years</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/10">Offices</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/11">Message Templates</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/12">PAO/DDO</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/13">Application for Re-Esign</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/14">Extend Dates</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/15">Feedback Type</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/16">Replica Charges</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/17">Education Types</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/18">Division Grade</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/19">Documents List</a>
                                            </div>
                                        </div>
                                        <!-- For Routine Inspection added by shankhpal shende on 06/12/2022 -->
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <a  class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/20">Routine Inspection Period</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- For User Manual Master menu added by Akash Thakre on 06/12/2023 -->
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="btn btn-block btn-outline-secondary" href="<?php echo $this->getRequest()->getAttribute('webroot');?>masters/fetchAndRedirect/25">User Manuals</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

       
<?php echo $this->Html->script('Masters/masters_home'); ?>
