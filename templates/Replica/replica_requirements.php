<div class="jumbotron jumbotron-fluid">
    <div class="col-sm-11">
        <a href="../customers/secondary_home" class="btn btn-primary float-right">Back</a>
    </div>
  <div class="container">
    <h3>Replica Requirements</h3>
    <p class="lead">This are the few steps the applicant needs to complete before apply for the replica. If all the requirements are fullfilled then the Proceed to Replica button will be appear.</p>

    <div class="alert alert-light" role="alert">
       <?php if ($chemistRegister) {
           echo '<i class="fas fa-check-circle greenBew"></i>  1. Chemist registration is done.';
        } else {
            echo '<i class="fas fa-times-circle cRed"></i> 1. Chemist Registration : You need to register a Chemist from Apply For-><b>Chemist Registration</b>.';
        }
      ?>
    </div>
    <div class="alert alert-light" role="alert">
        <?php if ($check_che_incharge) {
            echo '<i class="fas fa-check-circle greenBew"></i> 2. Chemist In-charge is present.';
        } else {
          echo '<i class="fas fa-times-circle cRed"></i> 2. Chemist In-charge : You need to set the approved chemist as In-charge . From Dashboard -> Registered Chemist.
            <p>Applicant can set the incharge chemist which are Self-Registered Chemist or Lab Registered Chemist.</p>';
        }
        ?>
       
    </div>
    <div class="alert alert-light" role="alert">
        <?php 
        if ($attached_lab_data || $attached_pp_data) {

            echo '3. Attachment of Printer and Lab </br>';
            
            if ($attached_lab_data) {
                echo '<i class="fas fa-check-circle greenBew"></i> Laboratory is Attached <br>';
            } else {
                echo '<i class="fas fa-times-circle cRed"></i> Laboratory is not attached <br>'; 
            }

            if ($attached_pp_data) {
                echo '<i class="fas fa-check-circle greenBew"></i> Printer is Attached';
            }else{
                echo '<i class="fas fa-times-circle cRed"></i> Printer is not attached';
            }

        } else {
            echo '<i class="fas fa-times-circle cRed"></i>  3. Attachment of Printer and Lab : You need to attach Printing Press and Laboratory from the menu Apply For-><b>Attach Printing Press/Lab</b>.';
        }
        ?>
    </div>
    <div class="alert alert-light" role="alert">
        <?php 
        if ($checkPayment) {
            if ($checkPayment['payment_confirmation'] == 'confirmed') {
                echo '<i class="fas fa-check-circle greenBew"></i> Advance paymemt is done.';
            } else {
                echo '<i class="fas fa-exclamation-circle cYellw"></i> 4. Applied for advance payment , yet to approve from Agamrk.';
            }
            
        } else {
            echo '<i class="fas fa-times-circle cRed"></i> 4. Advance Payment : You need to apply for the Advance Payment from the menu Apply For-><b>Advance Payment</b>. After the appproval of payment it will reflect on account.       ';
        }
        ?>
    </div>
      <?php
        if ($chemistRegister && $check_che_incharge && $attached_lab_data && $checkPayment && $checkPayment['payment_confirmation'] == 'confirmed') { ?>
            <a href="../replica/replica_application" class="btn btn-success float-left">Proceed for Replica</a>
        <?php } ?>

  </div>
</div>