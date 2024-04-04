<?php ?>
<style>
	h4 {
		padding: 5px;
		font-family: times;
		font-size: 13pt;
	}

	table{
		padding: 5px;
		font-size: 12pt;
		font-family: times;
	}

	.cRed{
		color: red;
	}
</style>


	<table width="100%" border="1">
		<tr>
			<td align="center" style="padding:5px;">
				<h4>Application for Approval of <b><span class="cRed">SURRENDER</span></b> of Permission of Printing Press</h4>
			</td>
		</tr>
	</table>

	<table width="100%"><br><br>	
		<tr>
			<td><br>To,</td><br>
		</tr>	
	</table>	

	<table  width="100%">
		<tr>
			<td>
				<br><strong>The Deputy Agriculture Marketing Director</strong><br>
				Incharge-Regional Office<br>
				Directorate of Marketing & Inspection<br>
				(Ministry of Agriculture & Farmers Welfare)<br>
				<?php echo $get_office['ro_office']; ?>,<?php echo $firm_state_name; ?>
			</td>
		</tr>
			
		<tr>
			<td><br>Subject: Application for approval of Surrender of Permission of Printing Press.</td><br>
		</tr>

		<tr>
			<td><br>Dear Sir,</td><br>
		</tr>

		<tr>
			<td>
				<br>I,	<?php echo $customerData['f_name']." ".$customerData['l_name']; ?> of 
					  <?php 
							echo $firmData['firm_name'].', '; 
							echo $firmData['street_address'].','; 
							echo $firm_district_name.', '; 
							echo $firm_state_name.', '; 
							echo $firmData['postal_code']; 
						?>  
					seek approval for Surrender the Permission of Printing Press of agricultural commodities for 
					<?php echo $firmData['firm_name']; ?> in accordance with the provision of agriculture produce (Grading and Marking) Act, 
					1937 and rules made there under.
			</td>
		</tr>
	</table>

	
	<table width="100%" border="1">
		<tr>
			<td><br>Declaration:</td><br><br>
		</tr>

		<tr>
			<td>
				<br>I,
					<?php echo $customerData['f_name']." ".$customerData['l_name']; ?> of 
					<?php echo $firmData['firm_name'].', '; 
						  echo $firmData['street_address'].','; 
						  echo $firm_district_name.', '; 
						  echo $firm_state_name.', '; 
						  echo $firmData['postal_code']; 
					?>   
					hereby solemnly and sincerely affirm and state that:
				<br>I agree that Submission of balance printed material and declaration that i will not print under Agmark.<br>
				<br>I do hereby declare that will Associated packers will be conveyed that this press is surrendered.<br>
				<br>I do hereby declare that the above stated facts are true and correct and I have not surppressed any material facts relating to subject matter of declaration.<br>
			</td>
		</tr>
	</table>

	<table width="100%" border="1">
		<tr>
			<td style="padding:10px; vertical-align:top;">1.Reason for Surrender</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $surrenderData[0]['reason']; ?> <br></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">1(a). Undertaking Document:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($surrenderData[0]['required_document'])){ $split_file_path = explode("/",$surrenderData[0]['required_document']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $surrenderData[0]['required_document']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">2.Submission of balance printed material.</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $surrenderData[0]['is_balance_printing_submitted']; ?> <br></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">2(a). Related Document: </td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($surrenderData[0]['is_balance_printing_submitted_docs'])){ $split_file_path = explode("/",$surrenderData[0]['is_balance_printing_submitted_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $surrenderData[0]['is_balance_printing_submitted_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">3.Declaration of not to print under Agmark</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $surrenderData[0]['printing_declaration']; ?> <br></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">3(a). Related Document: </td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($surrenderData[0]['printing_declaration_docs'])){ $split_file_path = explode("/",$surrenderData[0]['printing_declaration_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $surrenderData[0]['printing_declaration_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">4.Is Associated packers conveyed ?</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $surrenderData[0]['is_packers_conveyed']; ?> <br></td>
		</tr>
		<tr>
			<td style="padding:10px; vertical-align:top;">4(a). Related Document: </td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($surrenderData[0]['is_packers_conveyed_docs'])){ $split_file_path = explode("/",$surrenderData[0]['is_packers_conveyed_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];?>
													<a href="<?php echo $surrenderData[0]['is_packers_conveyed_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td  align="left"><br><br><br>
				Place: <?php echo $firm_district_name.', '; echo $firm_state_name.'.';?><br>
				Date: <?php echo $pdf_date;?>
			</td>
		</tr>
	</table>

	<table>	
		<tr>
			<td align="right">Your Sincerely<br>
				<?php echo $firmData['firm_name']; ?><br> 
				<?php echo $firmData['street_address'].', <br>';
					echo $firm_district_name.', ';
					echo $firm_state_name.', ';
					echo $firmData['postal_code'].'.<br>';?>
			</td>
		</tr>
	</table>	