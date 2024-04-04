<?php if ($final_submit_status == 'approved' && $final_submit_level == 'level_3') { ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                New Application is approved by the DMI , the certificate is generated , Please click on New Application tab to view the Certificate pdf. Thank You
            </div>
        </div>
    </div>

<?php } elseif ($final_submit_status == 'referred_back') { ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                Your Application is referred back from DMI . Please Check and Reply . Go from <b>Apply For -> Application Status</b>. Thank You.
            </div>
        </div>
    </div>

<?php } elseif ($final_submit_status == 'replied') { ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                You replied on New application. <br>
                To check the application go from <b>Apply For -> Application Status</b> button. </br>
                To check the application version Pdf click on the <b>New Application</b> Tab. Thankyou
            </div>
        </div>
    </div>

<?php } else {  ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                Your application is successfully submitted to AGMARK. To check the application version PDF, click on the New Application tab . Thank You.
            </div>
        </div>
    </div>

<?php } ?>

