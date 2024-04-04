
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

	<?php if ($isSurrender== 'yes') { ?>
		<table width="100%" border="1">
			<tr>
				<td>
					<h4 class="cRed">
						This Approval of Laboratory is cancelled by the competent authority dated <b><?php echo date('d-m-Y'); ?></b>. 
						Laboratory should be issue NOC to associated packer to migrate to another Laboratory for
						commodity/ies under AGMARK. If a violation is observed, action shall be taken as per APGM Act and GGM Rule.
					</h4>
				</td>
			</tr>
		</table>
	<?php } ?>
	
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
				<h4><span style="font-family: krutidev010; font-weight:bold; font-size:13px;">iz;¨x'kkyk ds vuqe¨nu dk izek.k i=</span><br>
				
				<?php if ($form_type=='C') { ?>Certificate of Approval of Laboratory(Export)
				<?php }else { ?>Certificate of Approval of Laboratory <?php } ?>
				<br>
				<span style="font-family: krutidev010; font-weight:bold; font-size:13px;">¼d`f’k mit ¼Js.khdj.k vkSj fpãzadu½ vf/kfu;e] 1937 ds varjxZr vf/klwfpr lkekU; Js.khdj.k vkSj fpãzadu fu;e] 1988 ds mica/kks ds v/khu tkjh½</span><br>
				Issued under the provision of General Grading & Marking Rules, 1988 notified under Agriculture Produce(Grading & Marking) Act,1937</h4>
			</td>
		</tr>
	</table>

	<table width="100%" >
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
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>See Rules 8(1) & 8(2)</b></th>
		</tr>
	
		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>Field Name</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>Description</b></th>
		</tr>
	
		<tr>
			<td style="padding:10px; vertical-align:top;">1. Name of Approved laboratory :</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?></td>
		</tr>
		
		<!-- Distributed postal address and premises address separately on 31-10-2023 by Amol, as per UAT suggestion -->
		<tr>
			<td style="padding:10px; vertical-align:top;">2. (i) Full Postal Address: </td>
			<td style="padding:10px; vertical-align:top;">
				<?php echo $primaryAppData['street_address'].', ';
						echo $pri_district_name.', ';
						echo $pri_state_name.', ';
						echo $primaryAppData['postal_code'].'.<br>';
						if(!empty($primaryAppData['email'])){ echo 'Email: '.base64_decode($primaryAppData['email']).',<br>'; } //for email encoding
						if(!empty($primaryAppData['mobile'])){ echo 'Phone: '.base64_decode($primaryAppData['mobile']).',<br>'; } 
						if(!empty($primaryAppData['landline'])){ echo 'Landline: '.base64_decode($primaryAppData['landline']); }
						
				?>
			</td>
		</tr>

		<tr>
			<td style="padding:10px; vertical-align:top;">2. (ii) Address of Laboratory Premises: </td>
			<td style="padding:10px; vertical-align:top;">
				<?php echo $customer_firm_data['street_address'].', ';
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
			<td style="padding:10px; vertical-align:top;">3. Type of Laboratory (State Grading Laboretory/Co-operative Laboretory/Association Laboretory/Approved Commercial Laboretory)</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_type)){ echo $laboratory_type; }else{ echo 'NA'; }  ?></td>
		</tr>
		
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4. Status(proprietorship/ Partnership/ Public Limited/ Private Limited/ Registered Co-operative Society/ Govt. Undertaking etc.)</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type)){ echo $business_type; }else{ echo 'NA'; }  ?></td>
		</tr>

		
		<tr>
			<td style="padding:10px; vertical-align:top;">5. Name of Commodities on which grade designation marks may be applied</td>
			<td style="padding:10px; vertical-align:top;">
				<?php
				$i=1;	
				foreach($commodity_name_list as $commodity_name){ ?>
				
					<b><?php echo $i.'.'. $commodity_name['category_name']; ?></b>
					<ol>
						<?php 

							foreach($sub_commodity_data as $sub_commodity){ ?>
						
							<?php if($sub_commodity['category_code'] == $commodity_name['category_code']){?>
							
								<li><?php echo $sub_commodity['commodity_name']; ?></li>
								
							<?php } ?>
						
						<?php  } ?>
						
					</ol>
					
				<?php $i=$i+1; } ?>
			</td>
		</tr>
			
		<tr>
			<td style="padding:10px; vertical-align:top;">6. Date of Validity of Approval:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($certificate_valid_upto[0])){  echo $certificate_valid_upto[0]; }else{ echo 'NA'; } ?></td>
		</tr>
			
		<?php if (!empty($added_directors_details)) { ?>
		
			<tr>
				<td style="padding:10px; vertical-align:top;">7. Name and addresses of proprietors/Partners/Directors/Office beares etc.</td>
				<td style="padding:10px; vertical-align:top;">
						<table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="15%" cellspacing="50" align="left">S.No.</th>
								<th style="padding:10px;" width="30%" cellspacing="50" align="left">Name</th>
								<th style="padding:10px;" width="55%" cellspacing="50" align="left">Address</th>
							</tr>
							<?php $i=1; foreach($added_directors_details as $each_detail){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php echo $i; ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_name'])){ echo $each_detail['d_name']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_address'])){ echo ucfirst($each_detail['d_address']); }else{ echo 'NA'; } ?></td>

								</tr>
							<?php $i=$i+1;} ?>
							
						</table>
				</td>
			</tr>	
		
		<?php } ?>
	</table>
			
	<table width="100%" border="1" style="margin-top:50px;">
		<tr><!-- updated this line in single td and remove NA on 31-10-2023 by Amol -->
			<td style="padding:10px; vertical-align:top;"> This certificate  is issued under the provisions of and subject to the conditions prescribed in the General Grading & Marketing Rules,1988.</td>
			
		</tr>
	</table>
			
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<td style="padding:10px; vertical-align:top;">Signature, Name, Designation and Office address of Certificate issuing officer:</td>
			<td style="padding:10px; vertical-align:top;">E-Signed By: <?php if(!empty($user_full_name[0])){  echo $user_full_name[0]; }else{ echo 'NA'; }  ?></td>
		</tr>
	</table>
	

	<!-- for Renewal part -->
	<p></p>
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>PART II</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"></th>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
			<td>
				<b><?php if ($form_type=='C') { ?>Renewal of Approval of Laboratory (Export)<?php }else{ ?>Renewal of Approval of Laboratory<?php } ?></b>
			</td>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
			<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Date of Application</b></th>
			<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Date upto which renewed</b></th>
			<th style="padding:10px;" width="40%" cellspacing="50" align="left"><b>Signature & Designation of Authorised Officer</b></th>
		</tr>
		
		<?php if(!empty($check_renewal_final_submit)){
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
	<!--end of Renewal part -->

		
	<!-- for change in certification part -->
	<p></p>
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>PART III</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"></th>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
			<td>
				<b><?php if ($form_type=='C') { ?>Change in the Approval of Laboratory(Export)<?php }else{ ?>Change in the Approval of Laboratory<?php } ?></b>
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
	</table>
	<!--end of change in certification part -->


		
	<!-- for cancellation in certification part -->
	<p></p>
	<table width="100%" border="1" style="margin-top:50px;">
		<tr>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>PART IV</b></th>
			<th style="padding:10px;" width="50%" cellspacing="50" align="left"><b>See Rule 8(3)</b></th>		
			
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">
				<?php if ($form_type=='C') { ?>
					Endorsement of the competent authority about withdrawal of approval of Laboratory(Export):
				<?php }else{ ?>
					Endorsement of the competent authority about withdrawal of approval of Laboratory:
				<?php } ?>
			</td>
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
						echo "This Approval of Laboratory cancelled by the competent authority.";
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
	<!-- end of cancellation in certification part -->


	 <!-- QR Code added by shankhpal shende on 17/08/2022 -->
	 <div style="text-align: left;"> <img width="100" height="100" src="<?php echo $result_for_qr['qr_code_path']; ?>"></div>
	