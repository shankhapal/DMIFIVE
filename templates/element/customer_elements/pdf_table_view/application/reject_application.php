<section class="col-lg-12 connectedSortable">
    <div class="card card-info">
        <div class="card-header"><h3 class="card-title"> Rejected Application </h3></div>
            <div class="card-body">
            <table id="example2" class="table m-0 table-bordered">
                <thead class="tablehead">
                <tr>
                    <th>Applicant Id</th>
                    <th>Application Type</th>
                    <th>Rejection Reason</th>
                    <th>Rejection Date</th>
                    <th>Deadline for filing appeal</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                //@@Author: Joshi, Akash
                    //Below method will compare provided time with current time
                    //Parameter: Date in Y-m-d format
                    //Return: Meaningfull boolean value.
                function hasCutoffDatePassed($cutoffDate)
                {
                  return strtotime(date("Y-m-d"))> strtotime($cutoffDate);
                }
                
                foreach ($is_appl_rejected as $each_record) { ?>
                <tr>
                    <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                    <td><?php 
                    $rejected_appl_type_id= $each_record['appl_type'];
                    $rejected_appl_type_title=$appl_mapping[$rejected_appl_type_id]; 
                    echo $rejected_appl_type_title;
                     ?>
                     </td>
                    <td ><?php echo $each_record['remark']; ?></td>
                    <td><?php
                     $date = $each_record['created'];
                     $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $date);
                    echo $dateTime->format('d/m/Y'); ?></td>
                    <td><?php
                    //Applicable Appeal only for New Application Type
                    if($rejected_appl_type_id==1){
                    $cutoffDate =  date ("Y-m-d", strtotime ( $dateTime->format('d-m-Y') ."+30 days"));
                    $deadlineDateForDisplay = date ("d/m/Y", strtotime ( $dateTime->format('d-m-Y') ."+30 days"));
                    echo $deadlineDateForDisplay; 
                    }
                    else{
                    echo "Not Applicable";
                    }
                    ?>
                    </td>
                    <td>
                    <?php

                    //1. Check for existing appeal and list out those
                    //2.
                    //3. Check for cutoff date.
                    //4. If cutoff date passed then print it
                    //5. If cutoff date hasn't passed then allow appeal creation.
                    //6. If any appeal is in progress then stop new appeal creation.

                      $is_apl_submitted='';
                      $form_status='';
                      if(!empty($appealMap) && !empty($each_record['appeal_id'])){
                        $appealDetailInfo=$appealMap[$each_record['appeal_id']];
                        if(!empty($appealDetailInfo)){
                           $is_apl_submitted=$appealDetailInfo['is_final_submitted'];
                           $form_status=$appealDetailInfo['form_status'];
                        }
                      }
                     
                      if($rejected_appl_type_id!=1){ ?>
                        Not Applicable
                      <?php }
                      elseif($form_status =='referred_back' || (!empty($is_apl_submitted) && $is_apl_submitted =='yes'))  { ?>
                        <a  href="<?php echo $this->request->getAttribute("webroot");?>application/application-type/12?associated-rejectedapp=<?php echo $rejected_appl_type_id?>" class="nav-link">Appeal Reference</a>
                      <?php }
                      elseif(hasCutoffDatePassed($cutoffDate))  { ?>
                        Appeal deadline has passed!
                      <?php }
                      elseif(!empty($InprocessMsg)){ ?>
                        Another Application in Progress!
                      <?php }
                      elseif (empty($InProcessAppeal))
                      {
                        ?>
                                <a href="<?php echo $this->request->getAttribute("webroot");?>application/application-type/12?associated-rejectedapp=<?php echo $rejected_appl_type_id?>" class="nav-link">Submit Appeal</a>
                      <?php
                      }?>

                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
