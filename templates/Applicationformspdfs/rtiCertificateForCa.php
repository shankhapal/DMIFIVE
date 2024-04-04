
<!--  Comment:This file updated as per change and suggestions for UAT module after test run
	    Reason: updated as per change and suggestions for UAT module after test run
	    Name of person : shankhpal shende
	    Date: 15-05-2023
*/ -->
<?php //echo $rti_ca_data;die; ?>
<style>
	h4 {
		padding: 5px;
		font-family: times;
		font-size: 12pt;
	}						 

	table{
		padding: 5px;
		font-size: 10pt;
		font-family: times;
	}				
</style>

	<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;"><h4>Routine Inspection Report (CA-Packer)</h4></td>
			</tr>
	</table>

  <table width="100%" border="1">
	
		<tr>
         <td style="padding:10px; vertical-align:top;">Date of Last Inspection :</td>

			  <td style="padding:10px; vertical-align:top;">Date:
				<?php echo isset($rti_ca_data['date_last_inspection'])?$rti_ca_data['date_last_inspection']:"NA"; ?>
      </td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Date & Time of present Inspection :</td>
    		<td style="padding:10px; vertical-align:top;">Date:
				<?php echo isset($rti_ca_data['date_p_inspection']) ? $rti_ca_data['date_p_inspection'] : "NA"; ?>, Time:

				<?php $time = date("h:i A", strtotime($rti_ca_data['time_p_inspection']));
            echo isset($time) ? $time : "NA"; ?></td>

		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">1. Name of Authorized Packer :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($firm_details['firm_name'])?$firm_details['firm_name']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">2. Address of the Authorized Premises :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($firm_details['street_address'])?$firm_details['street_address']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">3. Contact details of the packer Mobile:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo 'Mobile :'. base64_decode(isset($firm_details['mobile_no'])?$firm_details['mobile_no']:"NA")." , ".'Email ID :'.base64_decode(isset($firm_details['email'])?$firm_details['email']:"NA"); ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">4. Certificate of Authorization No and valid upto :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo 'Certificate No :'. isset($firm_details['customer_id'])?$firm_details['customer_id']:"NA".',','valid upto :'. isset($certificate_valid_upto)?$certificate_valid_upto:"NA"; ?></td>
		</tr>
    
    <tr>
        <td style="padding:10px; vertical-align:top;">5. Commodity (ies) for which CA is granted :</td>
			  <td style="padding:10px; vertical-align:top;">
        <?php 
        if(!empty($sub_commodity_value)){
          $i=0;
          foreach ($sub_commodity_value as $value) {
              $comma = ($i!=0)?', ':'';
              echo $comma.$value;
              $i++;
          } 
        }else{
          echo "NA";
        }
        ?>
      </td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">6. Name of the grading Laboratory :</td>
			  <td style="padding:10px; vertical-align:top;">
         <?php 
              if (!empty($lab_list)) {
                
                $i = 0;
                foreach ($lab_list as $value) {
                 echo isset($value['firm_name']) ? $value['firm_name'] : (isset($value['laboratory_name']) ? $value['laboratory_name'] : 'NA');
                  
                }
              }else{
                echo "NA";
              }
              ?>
      </td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">7. Name of approved Printing press :</td>
			  <td style="padding:10px; vertical-align:top;">
            <?php
              if (!empty($printers_list)) {
                $i = 0;
                foreach ($printers_list as $value) {
                  $comma = ($i != 0) ? ', ' : '';
                  echo $comma . $value;
                  $i++;
                }
              } else {
                echo "NA";
              }
              ?>
          </td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">a) Record of invoice of print Agmark replica is upto date or not? :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['record_of_invice']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">8. Name of the chemist Incharge Whether present at the time of Inspection :</td>
			  <td style="padding:10px; vertical-align:top;"><?php 
           if(!empty($self_registered_chemist)){
               echo $self_registered_chemist[0]['chemist_fname']." ".$self_registered_chemist[0]['chemist_lname'];
           }else{
            echo "NA";
           }
      ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">9. Is the premises adequately lighted, ventilated & hygienic :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['premises_adequately']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">10. Is the laboratory properly equipped :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['lab_properly_equipped']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">11. Grading Records :</td>
       
		</tr>
    <tr> 
      <td style="padding:10px; vertical-align:top;">a) Are they up to date</td>
      <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['are_you_upto_date']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">b) Are they being forwarded to the concerned offices in time?</td>
         <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['concerned_offices']; ?></td>
    </tr>
    <tr>
      
        <td style="padding:10px; vertical-align:top;">12. Last lot No. dated And its analytical results.</td>
			  <td style="padding:10px; vertical-align:top;">
         <div class="row">
          <div class="col-12">
            <table class="table "  border="1">
              <thead>
                <tr>
                  <th>Last lot No</th>
                  <th>Date</th>
                  <th>Analytical Results</th>
                  <th>Analytical Results Doc</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $rti_ca_data['last_lot_no']; ?></td>
                  <td><?php echo $rti_ca_data['last_lot_date'] ?></td>
                  <td><?php echo $rti_ca_data['analytical_results']; ?></td>
                  <td style="padding:10px; vertical-align:top;"><?php if(!empty($rti_ca_data['analytical_result_docs'])){ $split_file_path = explode("/",$rti_ca_data['analytical_result_docs']);$file_name = $split_file_path[count($split_file_path) - 1];?>
				          <a href="<?php echo $rti_ca_data['analytical_result_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
       </td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">13. Quantity graded during current month Upto </td>

			 <td style="padding:10px; vertical-align:top;">Date:<?php echo isset($rti_ca_data['month_upto'])?$rti_ca_data['month_upto']:"NA"; ?>, Quantity:<?php echo isset($rti_ca_data['quantity'])?$rti_ca_data['quantity']:"NA"; ?>, Units:<?php echo isset($rti_ca_data['grade_units'])?$rti_ca_data['grade_units']:"NA"; ?>
      </td>
	   </tr>

    <tr>
        <td style="padding:10px; vertical-align:top;">14. Is the Agmark Replica account correct?</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['replica_account_correct']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">15. Is the packer getting its lots tested by FSSAI approved Lab for food safety parameters every 6 months?</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['fssai_approved']; ?></td>
		</tr>
    </table>
    <table width="100%" border="1">
      <tr>
        <td style="padding:10px; vertical-align:top;">16. Collection of check samples</td>
      </tr>
    </table>
   <table width="100%" border="1">
        <tr>
             <th align="center" style="padding:5px;">S.No</th>
             <th align="center" style="padding:5px;">Commodity</th>
             <th align="center" style="padding:5px;">Pack Size</th>
             <th align="center" style="padding:5px;">Lot No</th>
             <th align="center" style="padding:5px;">Date of Packing</th>
             <th align="center" style="padding:5px;">Best Before</th>
             <th align="center" style="padding:5px;">Replica Sl. No</th>
        </tr>
        <?php 
            $i=1; 
            foreach($added_sample_details as $sample_detail){ ?>    
        <tr>
            <td align="center" style="padding:5px;"><?php echo $i; ?></td>
            <td align="center" style="padding:5px;"><?php echo isset($sub_commodity_value[$sample_detail['commodity_name']])?$sub_commodity_value[$sample_detail['commodity_name']]:"-"; ?></td>
            <td align="center" style="padding:5px;"><?php echo $sample_detail['pack_size'];?></td>
            <td align="center" style="padding:5px;"><?php echo $sample_detail['lot_no'];?></td>
            <td align="center" style="padding:5px;"><?php echo $sample_detail['date_of_packing'];?></td>
            <td align="center" style="padding:5px;"><?php echo $sample_detail['best_before'];?></td>
            <td align="center" style="padding:5px;"><?php echo $sample_detail['replica_si_no'];?></td>
        </tr>   
        <?php $i=$i+1; } ?>
    </table>

    <table width="100%" border="1">
     <tr>
        <td style="padding:10px; vertical-align:top;">17. Enumerate briefly suggestions given during last inspection and state, if carried out:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['e_briefly_suggestions_radio']; ?></td>
		</tr>
    
    <tr>
        <td style="padding:10px; vertical-align:top;">18. Shortcomings noticed in present inspection:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['shortcomings_noticed']; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">Shortcomings noticed Docs : </td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['e_briefly_suggestions_radio']; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">19. Suggestions :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['suggestions']; ?></td>
		</tr>
    
   
    <tr>
        <td style="padding:10px; vertical-align:top;">Name of the Packer or his representative:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['name_packer_representative']; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">Signnature of the Packer or his representative</td>
			  <td style="padding:10px; vertical-align:top;">
           <?php if(!empty($rti_ca_data['signnature_of_packer_docs'])){ $split_file_path = explode("/",$rti_ca_data['signnature_of_packer_docs']);$file_name = $split_file_path[count($split_file_path) - 1];?>
					<a href="<?php echo $rti_ca_data['signnature_of_packer_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?>
        </td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Name of the Inspecting Officer :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['name_of_inspecting_officer']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Signature of the Inspecting Officer:</td>
			  <td style="padding:10px; vertical-align:top;"><?php if(!empty($rti_ca_data['signnature_of_inspecting_officer_docs'])){ $split_file_path = explode("/",$rti_ca_data['signnature_of_inspecting_officer_docs']);$file_name = $split_file_path[count($split_file_path) - 1];?>
				<a href="<?php echo $rti_ca_data['signnature_of_inspecting_officer_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Designation:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $rti_ca_data['designation_inspecting_officer']; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Place:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $firm_district_name.', '; echo $firm_state_name.'.';?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Date:</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo $pdf_date;?></td>
		</tr>
    <table>					
	 <tr>
		<td  align="left"><br><br><br>
		
		</td>
	</tr>
</table>
    

</table>