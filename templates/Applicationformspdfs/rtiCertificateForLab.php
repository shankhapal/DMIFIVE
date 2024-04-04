
<!--  Comment:This file updated as per change and suggestions for UAT module after test run
	    Reason: updated as per change and suggestions for UAT module after test run
	    Name of person : shankhpal shende
	    Date: 26-05-2023
*/ -->
<?php ?>
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
			<td align="center" style="padding:5px;"><h4>Routine Inspection Report (Approved Laboratory)</h4></td>
			</tr>
	</table>

  <table width="100%" border="1">
	
		<tr>
			  <td style="padding:10px; vertical-align:top;">Date of Last Inspection :</td>
			  <td style="padding:10px; vertical-align:top;">Date:
				<?php echo isset($rti_lab_data['date_last_inspection'])?$rti_lab_data['date_last_inspection']:"NA"; ?></td>
		</tr>
    <tr>

		<td style="padding:10px; vertical-align:top;">Date & Time of present Inspection :</td>
    		<td style="padding:10px; vertical-align:top;">Date:
				<?php echo isset($rti_lab_data['date_p_inspection']) ? $rti_lab_data['date_p_inspection'] : "NA"; ?>, Time:
				<?php $time = date("h:i A", strtotime($rti_lab_data['time_p_inspection']));
            echo isset($time) ? $time : "NA"; ?></td>

		</tr>

    <tr>
        <td style="padding:10px; vertical-align:top;">1. Name and addres of the laboratory Contact details :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo 
				'Name :'. $firm_details['firm_name']."<br>".
				'Addres :'.$firm_details['street_address']."<br>".
				'Email Id :'. base64_decode($firm_details['email'])."<br>".
				'Mobile No : '.base64_decode($firm_details['mobile_no']); ?></td>
		</tr>
		
    <tr>
        <td style="padding:10px; vertical-align:top;">Certificate No :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($firm_details['customer_id'])?$firm_details['customer_id']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">2. Commodities for which approved :</td>
			  <td style="padding:10px; vertical-align:top;">
          <?php 
            $i=0;
            foreach ($sub_commodity_value as $value) {
                $comma = ($i!=0)?', ':'';
                echo $comma.$value;
                $i++;
            } 
          ?>
        </td>
		</tr>
    
	<tr>
			<td style="padding:10px; vertical-align:top;">3. Name of the approved chemist Present at the time of inspection :</td>
			<td style="padding:10px; vertical-align:top;"><?php $approved_chemist = implode(",", $chemist_full_name);
			echo isset($approved_chemist) && !empty($approved_chemist) ? $approved_chemist : "NA"; ?></td>

	</tr>


		<tr>
        <td style="padding:10px; vertical-align:top;">4. Whether present at the time of Inspection :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['present_time_of_inspection']) && !empty($rti_lab_data['present_time_of_inspection'])?$rti_lab_data['present_time_of_inspection']:"NA"; ?></td>
		</tr>
		
     <tr>
        <td style="padding:10px; vertical-align:top;">5. Is the laboratory well lighted Ventilated and hygienic  :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['is_lab_well_lighted'])?$rti_lab_data['is_lab_well_lighted']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">6. Is the Laboratory properly equipped for the grading of the commodities :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['is_properly_equipped'])?$rti_lab_data['is_properly_equipped']:"NA"; ?></td>
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">	7. Is the equipment is in working order :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['eq_working_order'])?$rti_lab_data['eq_working_order']:"NA"; ?></td>
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">8. Is the analytical register properly Maintained. :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['is_analytical_reg_maintained'])?$rti_lab_data['is_analytical_reg_maintained']:"NA"; ?></td>
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">9. Grading records. :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo 
				'1) Are the up to date ? : '.$rti_lab_data['are_up_to_date']."<br>".
				'2) Are they being forwarded to Concerned offices in time : '.$rti_lab_data['being_forwarded']; ?></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">10. Last lot analyzed :</td>
			<td style="padding:10px; vertical-align:top;">
				<?php
				echo 'Lot No: ' . $rti_lab_data['last_lot_no'] . "<br>" .
					'Commodity: ' . $rti_lab_data['commodity'] . "<br>" .
					'Name of the Packers: ' . $rti_lab_data['name_of_packers'] . "<br>" .
					'Analytical results: ' . $rti_lab_data['p_analytical_reg'] . "<br>" .
					'Analytical Results Doc: ';
				if (!empty($rti_lab_data['analytical_result_docs'])) {
					$split_file_path = explode("/", $rti_lab_data['analytical_result_docs']);
					$file_name = $split_file_path[count($split_file_path) - 1];
					?>
					<a href="<?php echo $rti_lab_data['analytical_result_docs']; ?>"><?php echo substr($file_name, 23); ?></a>
					<?php
				} else {
					echo 'NA';
				}
				?>
			</td>
		</tr>

		<tr>
        <td style="padding:10px; vertical-align:top;">11. Suggestions given during last :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['e_briefly_suggestions_radio'])?$rti_lab_data['e_briefly_suggestions_radio']:"NA"; ?><br>
				<?php if($rti_lab_data['e_briefly_suggestions_radio'] == 'yes'){
					echo $rti_lab_data['enumerate_briefly_suggestions'];
				}
				?></td> 
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">Suggestions given during last :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['e_briefly_suggestions_radio'])?$rti_lab_data['e_briefly_suggestions_radio']:"NA"; ?></td>
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">12. Shortcomings noticed in present Inspection :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['shortcomings_noticed'])?$rti_lab_data['shortcomings_noticed']:"NA"; ?></td>
		</tr>
			<tr>
        <td style="padding:10px; vertical-align:top;">13. Suggestions :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['suggestions'])?$rti_lab_data['suggestions']:"NA"; ?></td>
		</tr>
		
    <tr>
        <td style="padding:10px; vertical-align:top;">Name of the Office Authorized person :</td>
				<td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['authorized_persion_name'])?$rti_lab_data['authorized_persion_name']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Signature of the Office Authorized person of the laboratory :</td>
			  <td style="padding:10px; vertical-align:top;">
           <?php if(!empty($rti_lab_data['authorized_signature_docs'])){ $split_file_path = explode("/",$rti_lab_data['authorized_signature_docs']);$file_name = $split_file_path[count($split_file_path) - 1];?>
					<a href="<?php echo $rti_lab_data['authorized_signature_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?>
        </td>
		</tr>
		 <tr>
        <td style="padding:10px; vertical-align:top;">Name of the Inspecting Officer :</td>
				<td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['name_of_inspecting_officer'])?$rti_lab_data['name_of_inspecting_officer']:"NA"; ?></td>
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">Designation of the Inspecting Officer :</td>
				<td style="padding:10px; vertical-align:top;"><?php echo isset($rti_lab_data['designation_inspecting_officer'])?$rti_lab_data['designation_inspecting_officer']:"NA"; ?></td>
		</tr>
		<tr>
        <td style="padding:10px; vertical-align:top;">Signature of the  Inspecting Officer :</td>
			  <td style="padding:10px; vertical-align:top;">
           <?php if(!empty($rti_lab_data['signnature_of_inspecting_officer_docs'])){ $split_file_path = explode("/",$rti_lab_data['signnature_of_inspecting_officer_docs']);$file_name = $split_file_path[count($split_file_path) - 1];?>
					<a href="<?php echo $rti_lab_data['signnature_of_inspecting_officer_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?>
        </td>
		</tr>
</table>