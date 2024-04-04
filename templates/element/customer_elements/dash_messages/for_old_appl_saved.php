<?php if ($final_submit_status == 'referred_back') { ?>

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
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                You replied to DMI on the reffered back application. Please Check and Reply . Go from <b>Apply For -> Application Status</b>. Thank You.
            </div>
        </div>
    </div>

<?php } else {  ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                Your old application details are saved and finally submitted successfully, to check the application status please click on
                <b>Apply For -> Application Status</b> button. Thankyou
            </div>
        </div>
    </div>

<?php } ?>

