<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
            <?php 
				$message = <<<EOD
					This certificate is valid upto Date: <b>{$valid_upto_date}</b> 

					EOD;

				echo $message;
			?> 
        </div>
    </div>  
</div>