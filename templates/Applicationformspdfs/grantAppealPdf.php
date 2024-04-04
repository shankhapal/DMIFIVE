
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
				
</style>

<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">		
			<h4> Appeal Grant Certificate</h4>
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
       <td><?php echo $firmData['firm_name'].',<br>';
        echo $firmData['street_address'].', <br>'; 
        echo $firm_district_name.', '; 
        echo $firm_state_name.', ';?>
       <?php echo $firmData['postal_code'].'.<br>'; 
       Date:  echo $pdf_date; ?></td>
	</tr>

    <tr>
		<td><br>Subject: Grant of appeal application for candidate ID <?php echo $customer_id; ?></td><br>
	</tr>

    <tr>
		<td><br>Dear Applicant,</td><br>
	</tr>

    <tr>
		<td><br>We have granted approval for your appeal application which you initiated against your application(Application Type <u><?php echo $associated_rejected_app_title ?></u>)  .<br><br></td>
	</tr>
        
</table>

<table>	
	<tr>
		<td align="right"><br><strong>The Deputy Agriculture Marketing Director</strong><br>
                           Incharge-Regional Office<br>
                           Directorate of Marketing & Inspection<br>
                           (Ministry of Agriculture & Farmers Welfare)<br>
				<?php echo $get_office['ro_office']; ?>,<?php echo $firm_state_name; ?>
				
		</td>
	</tr>
</table>	
