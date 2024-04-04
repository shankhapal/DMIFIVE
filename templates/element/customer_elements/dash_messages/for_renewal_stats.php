
<div class="row">
    <div class="col-lg-12">
        <?php
            if ($show_renewal_btn == 'yes') {
                if ($show_renewal_button == 'Renewal') { ?>
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                        Please click on <b>Apply For -> Renewal</b> button to proceed for renewal application. Thankyou
                    </div>
            <?php } elseif ($show_renewal_button == 'Renewal Status') {

                    if ($renewal_final_submit_status == 'referred_back') { ?>

                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                            Your application for Renewal has been referred back by DMI. Please check and reply. Go from <b>Apply For -> Renewal Status</b> button. Thankyou
                        </div>

                    <?php } elseif ($renewal_final_submit_status == 'replied') { ?>

                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                            You replied on renewal application. <br>
                            To check the renewal application Go from <b>Apply For -> Renewal Status</b> button. </br>
                            To check the application version Pdf for renewal click on the <b>Renewal Application</b> Tab. Thankyou
                        </div>

                    <?php } else { ?>

                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                            To check your renewal application status please click on <b>Apply For -> Renewal Status</b> button. Thankyou
                        </div>
                    
                    <?php }
                            
                }
        
            } else { ?>

                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                    <?php 
                        echo "Hello, Your Old Application has been successfully verified. 
                        <br />Your Certificate is valid upto <b>$valid_upto_date</b>.
                        <br /> A <b>Renewal</b> button option will be available to you from the <u>date of verification or three months before valid upto date</u>, whichever is later.
                        <br />This option for <b>Renewal</b> will be available till one month from date of validity, after which you won't be able to apply for renewal. Thank you";
                    ?>
                </div>
            <?php  }
        ?>
    </div>
</div>