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
		<td align="center"><h4>FORM C3</h4></td>
	</tr>
</table>	
	
<table width="100%" border="1">
	<tr>
		<td align="center" style="padding:5px;">
			<h4>Application for Renewal for Approval of Laboratory for Export</h4>
		</td>
	</tr>
</table>
	
<table width="100%" border="1" >
	<tr>
		<td>
			Applicant Id. <?php echo $customer_id; ?>
		</td>
		<td align="right">
			Date: <?php echo $pdf_date; ?>
		</td>
	</tr>
</table>
	
<table width="100%">
	<tr><td></td></tr>
	<tr>
		<td><br>To,</td><br>
	</tr>	
</table>

<table  width="100%">
	
	<tr>
		<td><br>The Dy. Agricultural Marketing Adviser<br>
			Asstt. Agricultural Marketing Adviser,<br>
			<!-- applied this cond. of office type on 14-11-2023 by Amol, on UAT suggestion -->
			<?php if($get_office['office_type']=='RO'){ ?>
				Incharge, Regional Office,<br>
			<?php }elseif($get_office['office_type']=='SO'){ ?>
				Incharge, Sub Office,<br>
			<?php } ?>
			Directorate of Marketing & Inspection,<br>
			<?php echo $get_office['ro_office']; ?>,<?php echo $state_value; ?>
		</td>
	</tr>

	<tr>
		<td><br>Subject:  Renewal of approval for Grading and marking of fruits and Vegetables for Exports.</td>
	</tr>
		
	<tr>
		<td><br>Sir,</td><br>
	</tr>
		
	<tr>
		<td><br>We have been approved for the grading and marking of Fruits and Vegetables, vide letter No. <?php echo $firm_detail['customer_id']; ?> dated <?php if (!empty($last_grant_date)){ echo chop($last_grant_date,'00:00:00'); }else{ echo 'NA'; }  ?></td>						
	</tr>

	<tr>
		<td><br>We desire to continue grading and marking of fruits and vegetables for a further period.</td>
	</tr>
	
	<tr>
		<td><br>A Online payment made on dated  <?php if (!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> for Rs <?php if (!empty($total_charges)){ echo $total_charges; }else{ echo 'NA'; } ?> as a renewal fee.</td>
	</tr>
	
</table>
	
<table align="right">	
	<tr><td></td></tr>
	<tr>
	<td><?php echo $firm_detail['firm_name']; ?><br>
			<?php echo $firm_detail['street_address'].', <br>';
					echo $district_value['district_name'].', ';
					echo $state_value.', ';
					echo $firm_detail['postal_code']; ?>
	</td>
	</tr>
</table>	

<br pagebreak="true" />
	
<table width="100%" border="1">
	<tr>
		<td align="center"><h4>FORM C4</h4></td>
	</tr>
</table>
		
<table  width="100%" border="1">
	
	<tr>
		<td style="padding:10px; vertical-align:top;">1.(a) Name of Of the laboratory :</td>
		<td style="padding:10px; vertical-align:top;"><?php if (!empty($firm_detail['firm_name'])){ echo $firm_detail['firm_name']; }else{ echo 'NA'; }  ?></td>
	</tr>

	<tr>
		<td style="padding:10px; vertical-align:top;">2. Mailing Address with contact details i.e. Mobile No., e-mail etc.</td>
		<td style="padding:10px; vertical-align:top;">
			<?php echo $firm_detail['street_address'].', ';
					echo $district_value['district_name'].', ';
					echo $state_value.', ';
					if (!empty($firm_detail['postal_code'])){ echo $firm_detail['postal_code']; }else{ echo 'NA'; } ?><br>
			<?php	if (!empty($firm_detail['email'])){ echo base64_decode($firm_detail['email']); }else{ echo 'NA'; } ?><br>
			<?php	if (!empty($firm_detail['mobile_no'])){ echo base64_decode($firm_detail['mobile_no']);	}else{ echo 'NA'; } ?><br>
		</td>
	</tr>
			
	<tr>
		<td style="padding:10px; vertical-align:top;">3. Commodities List:</td>
		<td style="padding:10px; vertical-align:top;">
			<?php
			$i = 0;
			$totalCommodities = count($laboratory_commodity_values);

			foreach ($laboratory_commodity_values as $commodity_values) {
				if (!empty($commodity_values)) {
					echo $commodity_values;

					if ($i < $totalCommodities - 1) {
						echo ', ';
					} else {
						echo '.';
					}
					echo '<br>';
				} else {
					echo 'NA<br>';
				}
				$i = $i + 1;
			}
			?>
		</td>
	</tr>
</table>
		
<table  width="100%" border="1">
	<tr>
		<td style="padding:10px; vertical-align:top;">4.(a) Accreditation Number :</td>
		<td style="padding:10px; vertical-align:top;">
			<?php 
				if (!empty($renewal_details) && !empty($renewal_details['accreditation_no'])) {
					echo $renewal_details['accreditation_no']; 
				} else {
					echo 'N/A'; 
				}
			?>
		</td>
	</tr>
	
	<tr>
		<td style="padding:10px; vertical-align:top;">4.(b) Scope  :</td>
		<td style="padding:10px; vertical-align:top;">
			<?php 
				if (!empty($renewal_details) && !empty($renewal_details['accreditation_scope'])) {
					echo $renewal_details['accreditation_scope']; 
				} else {
					echo 'N/A'; 
				}
			?>
		</td>
	</tr>
	<tr> 
		<td style="padding:10px; vertical-align:top;">4.(b) NABL Accreditated upto :</td>
		<td style="padding:10px; vertical-align:top;">
			<?php 
			
				if (!empty($renewal_details) && !empty($renewal_details['nabl_accreditated_upto'])) {
					echo $renewal_details['nabl_accreditated_upto']; 
				} else {
					echo 'N/A'; 
				}
			?>
		</td>
	</tr>
</table>	
<table  width="100%" border="1">
	<tr>
		<td style="padding:10px; vertical-align:top;">5. Document for NABL certificate & scope :</td>
		<td style="padding:10px; vertical-align:top;"><?php if(!empty($renewal_details['nabl_cert_docs'])){ $split_file_path = explode("/",$renewal_details['nabl_cert_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $renewal_details['nabl_cert_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
		</td>
	</tr>
	
	<tr>
		<td style="padding:10px; vertical-align:top;">6. Document for APEDA certificate & scope  :</td>
		<td style="padding:10px; vertical-align:top;"><?php if(!empty($renewal_details['apeda_cert_docs'])){ $split_file_path = explode("/",$renewal_details['apeda_cert_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $renewal_details['apeda_cert_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
		</td>
	</tr>
	<tr> 
		<td style="padding:10px; vertical-align:top;">7. Document for Grading details during validity period :</td>
		<td style="padding:10px; vertical-align:top;"><?php if(!empty($renewal_details['grading_details_docs'])){ $split_file_path = explode("/",$renewal_details['grading_details_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $renewal_details['grading_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
		</td>
	</tr>
</table>	


		
<table>
		<tr>
			<td align="left">
				<h4>I hereby declare that the above information is correct.</h4>
			</td>
		</tr>
</table>

<table>					
	<tr>
		<td  align="left">
			Date: <?php echo $pdf_date; ?>
		</td>
	</tr>
</table>

<table align="right">	
	<tr><td></td></tr>
	<tr>
		<td><?php echo $firm_detail['firm_name']; ?><br>
				<?php echo $firm_detail['street_address'].', <br>';
						echo $district_value['district_name'].', ';
						echo $state_value.', ';
						if (!empty($firm_detail['postal_code'])){ echo $firm_detail['postal_code'];  }else{ echo 'NA'; } ?>
		</td>
	</tr>
</table>
