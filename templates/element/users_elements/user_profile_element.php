<div class="alert alert-success">
    <label>Congratulations... You Have Created a New User With Email Id :   <span class="text-secondary"><?php echo base64_decode($userDetails['email']); ?></span></label><br/>
    <!--<a class="text-primary" href="<?php //echo $this->getRequest()->getAttribute('webroot');?>users/login_user">Please Click Here To Login By New Created User</a>-->
</div>
<div class="col-md-8 form-middle">
    <div class="card card-widget widget-user shadow">
        <div class="widget-user-header bg-info">
            <h3 class="widget-user-username"><?php echo $userDetails['f_name']." ".$userDetails['l_name']; ?></h3>
            <p class="widget-user-desc"><?php echo base64_decode($userDetails['email']); ?></p>
        </div>
        <div class="widget-user-image">
            <?php if($userDetails['profile_pic'] != null){
                    $profile_pic = $userDetails['profile_pic'];
                    echo $this->Html->image('../../'.$profile_pic, array("alt"=>"User Image", "width"=>"200", "class"=>"img-circle"));
                }else{ ?>
                    <div class="image">
                        <?php echo $this->Html->image('profile.jpg', array("alt"=>"User Image","class"=>"img-circle elevation-2")); ?>
                    </div>
            <?php } ?>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">Division</h5>
                        <span class="description-text"><?php echo $userDetails['division']; ?></span>
                    </div>
                </div>
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">Office</h5>
                        <span class="description-text"><?php echo $officeDetails[0]; ?></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="description-block">
                        <h5 class="description-header">Office Type</h5>
                        <span class="description-text"><?php echo $officeDetails[1]; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>