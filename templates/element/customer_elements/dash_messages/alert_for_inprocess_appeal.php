<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
            <?php
                $date = $InProcessAppeal['created'];
                
                $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $date);
                $submitted_date = $dateTime->format('d/m/Y');
                $message = <<<EOD
                    You have successfully initiated an appeal on $submitted_date.<br>
                    While the current appeal is still in progress, you won't have the ability to initiate another appeal.
                EOD;

                echo $message;
            ?>
        </div>
    </div>
</div>
