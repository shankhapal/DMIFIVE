
<?php  ?>
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
			<h4>Certificate of Approval of Designated Person</h4>
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




<table width="100%"><br><br>
		<tr>
			<td><br>To,</td><br>
		</tr>
</table>
<table  width="100%">
    <tr>
       <td><?php echo $customer_firm_data['firm_name'].',<br>';
        echo $customer_firm_data['street_address'].', <br>';
        echo $firm_district_name.', ';
        echo $firm_state_name.', ';?>
       <?php echo $customer_firm_data['postal_code'].'.<br>';
        ?></td>
	</tr>

    <tr>
		<td><br><strong>Subject: Approval of
			designated person of the laboratory to issue Certificate of Agmark Grading (C.A.G) for Grading & Marking of <?= isset($commo_name)?$commo_name:'NA'; ?> under AGMARK for Export-reg.</strong>
	</td><br>
	</tr>

    <tr>
		<td><br>Sir,</td><br>
	</tr>

    <tr>
		<td><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;With reference to your application for approval of designated persons pertaining to
        laboratory firm No. <?= $customer_id ?>  dated <?= $createdDate; ?>
        on above subject, it is hereby informed that the following names are approved as Authorized designated persons of your laboratory to issue CAG</td>
	</tr>

        <?php
		     $i=1;
		    foreach($designated_person as $person_detail){?>
        <tr>
			<td><?php echo $i .")". " ". $person_detail['person_name'] ." , ". $person_detail['designation'];?></td>
        </tr>
        <?php $i=$i+1;} ?>
</table>

<table width="100%">
    <tr>
        <td><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            It is also informed that you will strictly comply with provisions of the Agricultural Produce (Grading & Marking) Act, 1937. General Grading & Marking Rules, 1988 (as amended), concerned
            Comodity Grading & Marking Rules and instructions issued by the Agricultural Marketing Adviser to the Govt. of India or any officer authorized
            by him from time to time. It may be ensured that grading is undertaken only by approved chemists
            & Certificate of AGMARK Grading (CAG) is issued only by Authorized designated person of the laboratory.
        </td>
    </tr>
</table>

<table>
	<tr>
		<td><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        The CAG will be issued subject to the following terms & conditions:</td><br>


	</tr>
	<tr>
		<td>1. The approval of the laboratory is valid on date.</td>
	</tr>
	<tr>
		<td>2. The consignment has been inspected by the approved chemist of the laboratory.</td>
	</tr>
	<tr>
		<td>3. The information in the prescribed inspection report provides the complate details of the lot inspected.</td>
	</tr>
	<tr>
		<td>4. All the requirements prescribed in General Grading & Marking Pules, 1988, <?= isset($commo_name)?$commo_name:'NA'; ?> Grading & Marking Rules, 2004 as amended from time to timeand instructions issued are adhered to.</td>
	</tr>
	<tr>
		<td>5. The Authorized packers have deposited the prescribed Grading Charges in advance.</td>
	</tr>
	<tr>
		<td>6. The details of Grading Charges realized from authorized packers should be forwarded to concerned Regional Office fortnightly under intimation to Head Office.</td>
	</tr>
	<tr>
		<td>7. The details of CAG issued are sent to concerned office of DMI every week. </td>
	</tr>
	<tr>
		<td>8. Strictly comply the provisions of Food Safety parameters contained in <?= isset($commo_name)?$commo_name:'NA'; ?> Grading & Marking Rules, 2004 (as amended) & requirement of importing countries </td>
	</tr>
	<tr>
		<td><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Further, the approval of the laboratory may be withdrawn at any time by the competent authority if there are sufficient reasons to believe
				that grading & marking is not correctly done or the rules. instructions issued thereof are not followed and the laboratory has become ineligible for approval as per guidelines issued in this regard.
				It is further instructed to maintain the records related to the analysis for verification as & when required. The laboratory is valid up to <strong> <?= $valid_upto_date; ?> (Up to NABL Validity).</strong>


	</td>
	</tr>

	<tr>
		<td>The receipt of this letter may be acknowledged.</td>
	</tr>
</table>

<table>
	<tr>
		<td align="right"><br><strong>Yours faithfully</strong><br><br>
			<?= $user_full_name ?><br>
			<?= $office_name ?><br>
			Deputy Agricultural Marketing Adviser for Agricultural Marketing Adviser<br>
			to the Govt. of India
		</td>

	</tr>
</table>

<br pagebreak="true" />
<?php foreach ($designated_person as $person_detail): ?>
    <?php
        $signatureDocsPath = $person_detail['signature_docs'];
        $extension = pathinfo($signatureDocsPath, PATHINFO_EXTENSION);
    ?>

    <?php if ($extension != 'pdf'): ?>
    <table style="border-collapse: collapse; width: 100%; border: 1px solid black;" width="100%">
        <tr>
            <td style="border: 1px solid black; padding: 6px; position: relative; text-align: right;">
                <div style="text-align: left; display: inline-block;">
                    <img src="<?= $person_detail['profile_pic']; ?>" width="auto" height="80" style="max-width: 100%; max-height: 100%; object-fit: contain; vertical-align: top;">
                    <span style="display: inline-block; width: 100%;">Name: <?= $person_detail['person_name'].", ".$person_detail['designation']; ?></span>
                </div>
            </td>
        </tr>

        <tr>
            <td style="border: 1px solid black; padding: 5px; text-align: center;">
                <img src="<?= $person_detail['signature_docs']; ?>" width="auto" height="600"  style="max-width: 100%; max-height: 100%; vertical-align: top;">
            </td>
        </tr>
    </table>
<?php endif; ?>


<?php endforeach; ?>







