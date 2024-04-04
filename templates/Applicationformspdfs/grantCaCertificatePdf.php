<?php ?>	
<style>
	h4 {
		padding: 5px;
		font-family: times;
		font-size: 12pt;
	}	
	table{
		padding: 5px;
		font-size: 9pt;
		font-family: times;
	}
	.cRed{
		color: red;
	}
</style>


	<!--  This Below Blocks Are used for the Surrender , Suspension and Cancellation of Certificate - Akash [05-06-2023] -->
	<?php if ($isSurrender== 'yes') { ?>
			<table width="100%" border="1">
				<tr>
					<td>
						<h4 class="cRed">
							This Certificate of Authorisation is cancelled by the competent authority dated <b> <?php echo date('d-m-Y'); ?> </b> 
							Applicant do not grade and mark "<?php echo $commodityNames ?>" commodity/ies under AGMARK.
							If, violation is observed than action shall be taken as per APGM Act and GGM Rule.
						</h4>
					</td>
				</tr>
			</table>
	<?php } ?>
	<!--- End of Block -->
		
		
		
	<table width="100%" border="1">
		<tr>
			<td width="12%" align="center">
				<img width="35" src="img/logos/emblem.png">
			</td>
			<td width="76%" align="center">
				<h4>Government of India <br> Ministry of Agriculture and Farmers Welfare<br>
				Department of Agriculture & Farmers Welfare<br>
				Directorate of Marketing & Inspection</h4>
			</td>
			<td width="12%" align="center">
				<img src="img/logos/agmarklogo.png">
			</td>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
			<td align="center" style="padding:5px;">
				<h4><span style="font-family: krutidev010; font-weight:bold; font-size:13px;">Ákf/kdkj izek.k i=</span><br>
				<?php if ($form_type=='F') { ?>Certificate Of Authorisation (Export)
				<?php }else { ?>Certificate Of Authorisation <?php } ?>
				 <br>
				<span style="font-family: krutidev010; font-weight:bold; font-size:13px;">¼d`f’k mit ¼Js.khdj.k vkSj fpãzadu½ vf/kfu;e] 1937 ds varjxZr vf/klwfpr lkekU; Js.khdj.k vkSj fpãzadu fu;e] 1988 ds mica/kks ds v/khu tkjh½</span><br>
				Issued under the provision of General Grading & Marking Rules, 1988 notified under Agriculture Produce(Grading & Marking) Act,1937</h4>
			</td>
		</tr>
	</table>

	<table width="100%">
		<tr>
			<td>
				Certificate No. <?php echo $customer_id;?>
			</td>
			<td align="right">
				Date: <?php echo $pdf_date;?>
			</td>
		</tr>
	</table>
					
	<table width="100%" border="1">
		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>PART I</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>See Rules 3(7) & 3(8)</b></th>
		</tr>

		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>Field Name</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>Description</b></th>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">1. Name of Authorized Packer :</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?></td>
		</tr>
				
		<tr>
			<td style="padding:10px; vertical-align:top;">2. Full Postal Address:</td>
			<td style="padding:10px; vertical-align:top;">
				<?php 	
					echo $customer_firm_data['street_address'].', ';
					echo $firm_district_name.', ';
					echo $firm_state_name.', ';
					echo $customer_firm_data['postal_code'].'.<br>';
					if(!empty($customer_firm_data['email'])){ echo 'Email: '.base64_decode($customer_firm_data['email']).',<br>'; } //for email encoding
					if(!empty($customer_firm_data['mobile_no'])){ echo 'Phone: '.base64_decode($customer_firm_data['mobile_no']).',<br>'; }
					if(!empty($customer_firm_data['fax_no'])){ echo 'Landline: '.base64_decode($customer_firm_data['fax_no']); }
				?>
			</td>
		</tr>
				
		<tr>
			<td style="padding:10px; vertical-align:top;">3. Status(proprietorship/ Partnership/ Public Limited/ Private Limited/ Registered Co-operative Society/ Govt. Undertaking etc.)</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type)){ echo $business_type; }else{ echo 'NA'; }  ?></td>
		</tr>
				
		<tr>
			<td style="padding:10px; vertical-align:top;">4. Name of Commodities on which grade designation marks may be applied</td>
			<td style="padding:10px; vertical-align:top;">
				<?php
				$i=1;	
				foreach($commodity_name_list as $commodity_name){ ?>
				
					<b><?php echo $i.'.'. $commodity_name['category_name']; ?></b>
					<ol>
						<?php 
						foreach($sub_commodity_data as $sub_commodity){
								if($sub_commodity['category_code'] == $commodity_name['category_code']){ ?>
							
								<li><?php echo $sub_commodity['commodity_name']; ?></li>
								
							<?php } ?>
						
						<?php } ?>
					</ol>
					
				<?php $i=$i+1; } ?>
			</td>
		</tr>
				
		<?php if($ca_bevo_applicant == 'no'){ ?>	
		
			<tr>
				<td style="padding:10px; vertical-align:top;">5. Address of authorised premises:</td>
				<td style="padding:10px; vertical-align:top;">
					<?php
						echo $premises_data[0]['street_address'].', ';
						echo $premises_district_name['district_name'].', ';
						echo $premises_state_name.', ';
						echo $premises_data[0]['postal_code'];
					?>
				</td>
			</tr>
				
			<tr>
				<td style="padding:10px; vertical-align:top;">6. Name and address of grading laboratory:</td>
				<td style="padding:10px; vertical-align:top;">
					<?php
						echo $laboratory_data[0]['laboratory_name'].', ';
						echo $laboratory_data[0]['street_address'].', ';
						echo $laboratory_district_name['district_name'].', ';
						echo $laboratory_data[0]['postal_code'].'.<br>';
						if(!empty($laboratory_data[0]['lab_email_id'])){ echo 'Email: '.base64_decode($laboratory_data[0]['lab_email_id']).',<br>'; } //for email encoding
						if(!empty($laboratory_data[0]['lab_mobile_no'])){ echo 'Phone: '.base64_decode($laboratory_data[0]['lab_mobile_no']).',<br>'; }
						if(!empty($laboratory_data[0]['lab_fax_no'])){ echo 'Landline: '.base64_decode($laboratory_data[0]['lab_fax_no']); }
					?>
				</td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">7. Name and address of processing unit(Grinding Mill, Oil Mill, Tie-up Arrangement etc.)</td>
				<td style="padding:10px; vertical-align:top;"><?php echo 'NA'; ?></td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">8. Particulars of trade brand, trade mark, private mark taken in record:</td>
				<td style="padding:10px; vertical-align:top;">
					<table width="100%" border="1">
						<tr>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">TBL Name</th>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">Registered?</th>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">Reg. No.</th>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">Uploaded File</th>
						</tr>
					
						<?php
							$i=1;
							foreach($added_tbls_details[1][0] as $each_tbl){ ?>
							<tr>
								<td style="padding:10px; vertical-align:top;"><?php  if(!empty($each_tbl['tbl_name'])){ echo $each_tbl['tbl_name']; }else{ echo 'NA'; }  ?></td>
								<td style="padding:10px; vertical-align:top;"><?php  if(!empty($each_tbl['tbl_registered'])){ echo ucfirst($each_tbl['tbl_registered']); }else{ echo 'NA'; }  ?></td>
								<td style="padding:10px; vertical-align:top;">
									<?php  
										if($each_tbl['tbl_registered']=='yes'){
											echo $each_tbl['tbl_registered_no']; 
										}else{
											echo "NA";
										}
									?>
								</td>
								<td style="padding:10px; vertical-align:top;"><?php if($each_tbl['tbl_registration_docs'] != null){?>
									<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$each_tbl['tbl_registration_docs']); ?>">Preview</a>
									<?php }else{ echo "No File Attached";} ?>
								</td>
							</tr>
						<?php  $i=$i+1; } ?>
					</table>
				</td>
			</tr>
				
			<tr>
				<td style="padding:10px; vertical-align:top;">9. Special Conditions/Remarks, if any:</td>
				<td style="padding:10px; vertical-align:top;"><?php echo 'NA'; ?></td>
			</tr>
				
			<tr>
				<td style="padding:10px; vertical-align:top;">10. Name and addresses of proprietors/Partners/Directors/Office beares etc.</td>
				<td style="padding:10px; vertical-align:top;">
					<table width="100%" border="1">
						<tr>
							<th style="padding:10px;" width="15%" cellspacing="50" align="left">S.No.</th>
							<th style="padding:10px;" width="30%" cellspacing="50" align="left">Name</th>
							<th style="padding:10px;" width="55%" cellspacing="50" align="left">Address</th>
						</tr>
						<?php $i=1; foreach($added_directors_details as $each_detail){ ?>
							<tr>
								<td style="padding:10px; vertical-align:top;"><?php echo $i; ?></td>
								<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_name'])){ echo $each_detail['d_name']; }else{ echo 'NA'; } ?></td>
								<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_address'])){ echo ucfirst($each_detail['d_address']); }else{ echo 'NA'; } ?></td>
							</tr>
						<?php $i=$i+1; } ?>
						
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">11. The Certificate will remain valid from this date to:</td>
				<td style="padding:10px; vertical-align:top;"><?php if(!empty($certificate_valid_upto[0])){ echo $certificate_valid_upto[0]; }else{ echo 'NA'; } ?></td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">12. Signature, Name, Designation and Office address of Certificate issuing officer:</td>
				<td style="padding:10px; vertical-align:top;">E-Signed By: <?php if(!empty($user_full_name[0])){ echo $user_full_name[0]; }else{ echo 'NA'; } ?></td>
			</tr>

		<?php }elseif($ca_bevo_applicant=='yes'){ ?>

			<tr>
				<td style="padding:10px; vertical-align:top;">5. Name and address of processing unit(Grinding Mill, Oil Mill, Tie-up Arrangement etc.)</td>
				<td style="padding:10px; vertical-align:top;"><?php echo 'NA'; ?></td>
			</tr>

			<tr>
				<td style="padding:10px; vertical-align:top;">6. Special Conditions/Remarks, if any:</td>
				<td style="padding:10px; vertical-align:top;"><?php echo 'NA'; ?></td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">7. Name and addresses of proprietors/Partners/Directors/Office beares etc.</td>
				<td style="padding:10px; vertical-align:top;">
					<table width="100%" border="1">
						<tr>
							<th style="padding:10px;" width="15%" cellspacing="50" align="left">S.No.</th>
							<th style="padding:10px;" cellspacing="50" align="left">Name</th>
							<th style="padding:10px;" width="50%" cellspacing="50" align="left">Address</th>
						</tr>
						<?php $i=1; foreach($added_directors_details as $each_detail){ ?>
							<tr>
								<td style="padding:10px; vertical-align:top;"><?php echo $i; ?></td>
								<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_name'])){ echo $each_detail['d_name']; }else{ echo 'NA'; } ?></td>
								<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_address'])){ echo $each_detail['d_address']; }else{ echo 'NA'; } ?></td>
							</tr>
						<?php $i=$i+1;} ?>
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">8. The Certificate will remain valid from this date to:</td>
				<td style="padding:10px; vertical-align:top;"><?php if(!empty($certificate_valid_upto[0])){ echo $certificate_valid_upto[0]; }else{ echo 'NA'; } ?></td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">9. Particulars of trade brand, trade mark, private mark taken in record:</td>
				<td style="padding:10px; vertical-align:top;">
					<table width="100%" border="1">
						<tr>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">TBL Name</th>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">Registered?</th>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">Reg. No.</th>
							<th style="padding:10px;" width="25%" cellspacing="50" align="left">Uploaded File</th>
						</tr>
				
						<?php
						$i=1;
						foreach($added_tbls_details[1][0] as $each_tbl){ ?>
						<tr>
							<td style="padding:10px; vertical-align:top;"><?php  if(!empty($each_tbl['tbl_name'])){ echo $each_tbl['tbl_name']; }else{ echo 'NA'; }  ?></td>
							<td style="padding:10px; vertical-align:top;"><?php  if(!empty($each_tbl['tbl_registered'])){ echo ucfirst($each_tbl['tbl_registered']); }else{ echo 'NA'; }  ?></td>
							<td style="padding:10px; vertical-align:top;">
								<?php  
									if($each_tbl['tbl_registered']=='yes'){
										echo $each_tbl['tbl_registered_no']; 
									}else{
										echo "NA";
									}
								?>
							</td>
							<td style="padding:10px; vertical-align:top;"><?php if($each_tbl['tbl_registration_docs'] != null){?>
								<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$each_tbl['tbl_registration_docs']); ?>">Preview</a>
								<?php }else{ echo "No File Attached";} ?>
							</td>
						</tr>
						<?php  $i=$i+1; } ?>
				
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="padding:10px; vertical-align:top;">10. Signature, Name, Designation and Office address of Certificate issuing officer:</td>
				<td style="padding:10px; vertical-align:top;">E-Signed By: <?php if(!empty($user_full_name[0])){ echo $user_full_name[0]; }else{ echo 'NA'; } ?></td>
			</tr>
			
		<?php } ?>
	</table>
			

		
		
	<!-- for Renewal part -->
	<p></p>
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<th style="padding:10px;" cellspacing="50" align="left"><b>PART II</b></th>
			<th style="padding:10px;" cellspacing="50" align="left"></th>
		</tr>
	</table>
	
	<table width="100%" border="1">
		<tr>
			<td>
			<?php if ($form_type=='F') { ?><b>Renewal of Certificate of Authorisation (Export)</b>
				<?php }else { ?><b>Renewal of Certificate of Authorisation</b> <?php } ?>
				
			</td>
			<td>
				<b>See Rule no. 4</b>
			</td>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
			<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Date of Application</b></th>
			<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Date upto which renewed</b></th>
			<th style="padding:10px;" width="40%" cellspacing="50" align="left"><b>E-Signed By</b></th>
		</tr>
			
		<!-- updated below code on 03-10-2020 by Amol for mutiple renewal-->
		
		<?php 
			if(!empty($check_renewal_final_submit)){
				$i=0; 
				foreach($user_full_name as $each){ 
					
					if($i > 0){ //bcoz o is for first grant ?>
						
						<tr>
							<td style="padding:10px; vertical-align:top;">Date: <?php if(!empty($user_full_name[$i])){ echo $renewal_application_date[$i]; }else{ echo 'NA'; } ?></td>
							<td style="padding:10px; vertical-align:top;">Date: <?php if(!empty($user_full_name[$i])){ echo $certificate_valid_upto[$i]; }else{ echo 'NA'; } ?></td>
							<td style="padding:10px; vertical-align:top;">
								<?php if ($_SESSION['current_level']=='pao'){ echo 'Verified By:'; }else{ echo 'E-Signed By:'; } ?>
								<?php if(!empty($user_full_name[$i])){ echo $user_full_name[$i]; }else{ echo 'NA'; } ?>
							</td>
						</tr>	
						
					<?php }
					$i=$i+1; 
				}
					
			}else{ ?>
				<tr>
					<td style="padding:10px; vertical-align:top;">Date:<?php echo 'NA'; ?></td>
					<td style="padding:10px; vertical-align:top;">Date:<?php echo 'NA'; ?></td>
					<td style="padding:10px; vertical-align:top;">E-Signed By:<?php echo 'NA'; ?></td>
				</tr>
			<?php } ?>
	</table>
		
		
		
	<!-- for change in certification part -->
	<p></p>
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<th style="padding:10px;" cellspacing="50" align="left"><b>PART III</b></th>
			<th style="padding:10px;" cellspacing="50" align="left"></th>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
			<td>
			<?php if ($form_type=='F') { ?><b>Change in the Certificate of Authorisation (Export)</b>
				<?php }else { ?><b>Change in the Certificate of Authorisation</b> <?php } ?>
			</td>
			<td>
				<b>See Rule no. 5</b>
			</td>
		</tr>
	</table>
		
	<table width="100%" border="1" >
		<tr>
			<th style="padding:10px;" width="25%" cellspacing="50" align="left"><b>Date of Application</b></th>
			<th style="padding:10px;" width="45%" cellspacing="50" align="left"><b>Details of the changes recorded</b></th>
			<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Signature & Designation of Authorised Officer</b></th>
			
		</tr>
		
		<!-- element call to show change updates added on 02-01-2023-->
		<?php
			//condition added on 26-05-2023, to get changed details only when appl. is for change or already changed earlier 
			if($_SESSION['application_type']==3 || !empty($getNoOfAppl)){
				echo $this->element('application_forms/change/elementChangeUpdatesOnCertificate');
			}
			?>
		<tr>
			<td style="padding:10px; vertical-align:top;">Date: <?php echo 'NA'; ?></td>
			<td style="padding:10px; vertical-align:top;"><?php echo 'NA'; ?></td>
			<td style="padding:10px; vertical-align:top;">E-Signed By: <?php echo 'NA'; ?></td>
		</tr>
	</table>
	<!-- end of change in certification part -->
		
		
	<!-- for cancellation in certification part This updated for the suspension / cancellation process  : Akash [01-06-2023] -->
	<p></p>
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>PART IV</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>See Rule no. 7</b></th>		
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">Endorsement of the competent authority about suspension or cancellation of the Certificate of Authorization:</td>
			<td style="padding:10px; vertical-align:top;">
				E-signed By:	<?php 
									if ($isSurrender== 'yes') {
										echo $surrender_approved_by;
									}else{
										echo "N/A";
									}
								?>
								<br>
				Date: <?php 
						if ($isSurrender== 'yes') {
							echo date("d-m-y"); 
						}
					?>
			</td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">
				<?php 
					if ($isSurrender== 'yes') { 
						 if ($form_type=='F') { echo "This Certificate of Authorisation (Export) is cancelled by the competent authority.";
						}else { echo "This Certificate of Authorisation is cancelled by the competent authority."; } 
					} 
				?>
			</td>

			<?php 
				if ($isSurrender== 'yes') { ?>
				<td style="padding:10px; vertical-align:top;">
					<b class="cRed"><?php echo 'S U R R E N D E R E D'; ?></b>
				</td>
			<?php } ?>
		</tr>
	</table>
	<!-- end of cancellation in certification part This updated for the suspension / cancellation process  : Akash [01-06-2023] -->

	<!-- QR Code added by shankhpal shende on 16/08/2022 -->
	<div style="text-align: left;"> <img width="100" height="100" src="<?php echo $result_for_qr['qr_code_path']; ?>"></div>
	