<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
            <p>
                <?php 
                    if ($advance_payment_status == 'pending' || $advance_payment_status == 'replied') {
                        echo 'Your application for Advance Payment is Successfully Submitted to AGMARK. Add DDO will process the application further . Thank You';
                    } elseif ($advance_payment_status == 'confirmed') {
                        echo 'Your application for Advance Payment is Successfully Approved by AGMARK. Credited Amount and Transactions will be seen from the <b>Apply For -> Advance Payment</b>.   Thank You';
                    } elseif ($advance_payment_status == 'not_confirmed') {
                        echo 'Your application for Advance Payment is Referred Back by DDO. Please check on the application. Please go from <b>Apply For -> Advance Payment -> Payment Status</b>.   Thank You';
                    } elseif ($advance_payment_status == 'saved') {
                        echo 'Your application for Advance Payment is Saved and yet to final submit. Please note that the application will not process further if it is not <b>Final Submitted</b>. Thank You';
                    }
                ?>
            </p>
        </div>
    </div>  
</div>