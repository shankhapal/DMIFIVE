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
				<h4>Application for Appeal</h4>
			</td>
		</tr>
	</table>

	<table width="100%"><br><br>
		<tr>
			<td><br>To,  Dated: <?php echo $pdf_date; ?></td><br>
		</tr>
	</table>

	<table  width="100%">
		<tr>
			<td>
				<br><strong>The Agricultural Marketing Adviser, to the Govt. of India</strong><br>
				DMI, Head Office, Faridabad<br>
			</td>
		</tr>

		<tr>
			<td><br>Subject: Appeal against rejection order of application of <b> M/s <?php echo $firmData['firm_name']; ?> </b> having AQCMS ID <?php echo $customer_id; ?> -Reg.</td><br>
		</tr>

		<tr>
			<td><br>Sir/Madam,</td><br>
		</tr>

		<tr>
			<td>
				<br>With reference to the DMI RO/SO Intimation of rejection letter dated <?php echo $rejection_date; ?> for conveying the
                    rejection of my application (Application Type: <u><?php echo $associated_rejected_app_title ?></u>) of <?php echo $firmData['firm_name'].' , '.$firmData['street_address'].' , '.$firm_district_name.' , '.$firm_state_name; ?> having AQCMS id <?php echo $customer_id; ?><br>
                    In this connection, I wish to appeal against the decision of rejection of above application and hereby
                    appeal and submit the required additional documents (if any) for reconsideration of above application.
			</td>
		</tr>
	</table>




	<table>
		<tr>
			<td align="right">(Name of the applicant with address and e-sign)<br>
				<?php echo $firmData['firm_name']; ?><br>
				<?php echo $firmData['street_address'].', <br>';
					echo $firm_district_name.', ';
					echo $firm_state_name.', ';
					echo $firmData['postal_code'].'.<br>';?>
			</td>
		</tr>
	</table>


    <table>
		<tr>
			<td align="left">Copy to:<br>
				In-Charge,RO/SO
			</td>
		</tr>
	</table>
