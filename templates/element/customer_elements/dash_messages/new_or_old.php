<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
            <?php 
                if ($is_already_granted == 'yes') {
                    echo "To fill your old application details, please click on <b>Apply For -> <i class='far fa-plus-square'></i> New Certification</b> button. Thankyou";
                } else {
                    echo "Please click on <b>Apply For -> <i class='far fa-plus-square'></i> New Certification</b> button to fill application details. Thankyou";
                }
            ?>
        </div>
    </div>
</div>