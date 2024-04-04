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
    .bYello{
        background-color: yellow;
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
        <tr><td align="center" style="padding:5px;"><h4>Approval/Allotement letter from DMI for AGMARK Replica</h4></td></tr>
    </table>

    <table width="100%" border="1">
        <tr><td>Applicant Id: <?php echo $firm_details['customer_id']; ?></td>
            <td align="right">Date: <?php echo date('d/m/Y'); ?></td>
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
            <td>
                <?php // This is converted in to one echo : Akash [13-09-2023]
                    echo "{$firm_details['firm_name']},<br>
						{$firm_details['street_address']},<br>
						{$firm_district_name}, 
						{$firm_details['postal_code']}<br>
						{$firm_state_name}"; 
				?>
			</td>
        </tr>

        <tr>    
            <td><br>Subject: Printing AGMARK replica allotement of serial number regarding.</td>
        </tr>
                    
        <tr>
            <td><br>Dear Sir,</td><br>
        </tr>   
                    
        <tr>
            <td>
                <br>With reference to your letter on <?php echo $appl_date; ?>, 
                on the subject cited above the following serial numbers are allotted for the printing of Agmark replica. 
                <br/>The following replica serial numbers are valid till date <b class="bYello"><?php echo $validUptoDate; ?></b>.
            </td>
        </tr>
              
    </table>




    <table width="100%" border="1">
	

			<tr>
				<th>Sr.No</th>
				<th>Commodity</th>
				<th>Grade</th>
				<th>TBL</th>
				<th>Packing Type</th>
				<th>Printer</th>
				<th>Pack</th>
				<th>Unit</th>
				<th>No of Packets</th>
				<!--<th>Total Quantity</th>-->
				<!--<th>Label Charge</th>-->
				<!--<th>Balance Replica No.</th>-->
				<th>Alloted From (Ser. No.)</th>
				<th>Alloted To (Ser. No.)</th>
				<th>Total Charge (Rs.)</th>
			</tr>
			
			<?php
				if(!empty($tableRowData)){
			
					$i=0;
					$sr_no = 1;
					foreach($tableRowData as $each){ ?>
					
					<tr>
						<td><?php echo $sr_no; ?></td>
						<td><?php echo $each['commodity_name']; ?></td>
						<td><?php echo $each['grade_name']; ?></td>
						<td><?php echo $each['tbl_name']; ?></td>
						<td><?php echo $each['packing_type']; ?></td>
						<td><?php echo $each['printer_name']; ?></td>
						<td><?php echo $each['packet_size']; ?></td>
						<td><?php echo $each['packet_size_unit']; ?></td>
						<td><?php echo $each['no_of_packets']; ?></td>
					<!--<td><?php //echo $each['total_quantity']; ?></td>
						<td><?php //echo $each['label_charge']; ?></td>						
						<td><?php //echo $each['bal_agmark_replica']; ?></td>-->
						<td><?php echo $each['alloted_rep_from']; ?> (<?php echo $each['rep_from_numeric']; ?>)</td>
						<td><?php echo $each['alloted_rep_to']; ?> (<?php echo $each['rep_to_numeric']; ?>)</td>
						<td><?php echo $each['total_label_charges']; ?></td>
					</tr>

			<?php $sr_no++; $i=$i+1;	} } ?>


	</table>
	
	<table align="left">
        <tr style="margin-top: 50px;">
            <td>Tran. No. <b><?php echo $cur_trans_id; ?></b> Date. <?php echo $trans_date; ?> For Rs. <b><?php echo $overall_charges; ?></b> is acknowledged.</td>
        </tr>
    </table>

	<table align="left">
        <tr style="margin-top: 50px;">
            <td><b>Replica Generation Rule: </b><br>
			1. First 5 digits are the packer unique number. It will be unique for each packer and will not change.<br>
			2. The 6th digit is year, mapped with alphabets (eg. year 2021 is A, 2022 is B like wise upto Z)<br>
			3. The 7th digit is month, mapped with alphabets (eg. January is A, February is B like wise upto Z,<br>
			if series range exceeds per month i.e "ZZZ999" than the series will reset to "AAA000" and starts with M as January, N as February etc.)<br>
			eg. If range exceeds for january i.e "AZZZ999" than it will start from "MAAA000" for january, like wise for all months.<br>
			4. The digits from 8th to 13th are series counter, it will start from "AAA000" to "ZZZ999" for each month.
			</td>
        </tr>
    </table>



    <table align="left" style="margin-top: 15px">
        <tr>
            <td>
                As soon as the replica bearing 
                <?php // This loop is addeed to show the replica serial numnber : Akash [13-09-2023]
                $counter = 0;
                $totalElements = count($tableRowData);
                foreach ($tableRowData as $each) {
                    echo $each['alloted_rep_from'] . " To " . $each['alloted_rep_to'];
                    // Check if this is not the last element
                    if ($counter < $totalElements - 1) {
                        echo " & ";
                    }
                    $counter++;
                }
                ?>

                are received from the manufacturer/printing presses, they will be handed over to chemist In-charge who will be responsible for maintaining the account of replica bearing 
                <?php // This loop is addeed to show the replica serial numnber : Akash [13-09-2023]
                $counter = 0;
                $totalElements = count($tableRowData);
                foreach ($tableRowData as $each) {
                    echo $each['alloted_rep_from'] . " To " . $each['alloted_rep_to'];
                    // Check if this is not the last element
                    if ($counter < $totalElements - 1) {
                        echo " & ";
                    }
                    $counter++;
                }
                ?>. <br>
             </td>
         </tr>
         <tr>
             <td>   
                It will be overall responsiblility of the certificate of authorization holder to keep the replica bearing 
                <?php // This loop is addeed to show the replica serial numnber : Akash [13-09-2023]
                $counter = 0;
                $totalElements = count($tableRowData);
                foreach ($tableRowData as $each) {
                    echo $each['alloted_rep_from'] . " To " . $each['alloted_rep_to'];
                    // Check if this is not the last element
                    if ($counter < $totalElements - 1) {
                        echo " & ";
                    }
                    $counter++;
                }
                ?>. in safe custody and the account for the same. <br>
            </td>
        </tr>

        <tr>
             <td>
                The concerned approved printing presses may also be advised to send a copy of this invoice against your order to this office for record. The order placed for the printing AGMARK replica 
                <?php // This loop is addeed to show the replica serial numnber : Akash [13-09-2023]
                $counter = 0;
                $totalElements = count($tableRowData);
                foreach ($tableRowData as $each) {
                    echo $each['alloted_rep_from'] . " To " . $each['alloted_rep_to'];
                    // Check if this is not the last element
                    if ($counter < $totalElements - 1) {
                        echo " & ";
                    }
                    $counter++;
                }
                ?> in lieu of Agmark lables may be endorsed to this office.
            </td>
        </tr>
    
    </table>
 
	<table style="margin-top: 50px;">
        <tr>
			<td>1.The Agril. Officer (Chemistry) SAGL I/II. <br>
				<?php 
				if (!empty($labArray)) {
					echo $labArray['firm_name'] . "<br>" . $labArray['street_address'] . "<br>" . $labArray['district'] . ", " . $labArray['state'] . "<br>" . $labArray['postal_code'];
				} ?>
			</td>
		</tr>

        <tr>
            <td> The account of the containers carrying AGMARK replica shall be maintained in the prescribed performa and can be submitted to this office along with monthly grading returns by the 10th every month. </td>

        </tr>
        
        <tr>
            <td>
				<b>Authorized Printer: </b><br>
				<?php // This printer details loop  is added to the pdf : Akash [13-09-2023]
                if (!empty($printerArray)) {

					$isFirst = true; // Flag to check if it's the first iteration
					$i = 1;
					foreach ($printerArray as $value) {
						if (!$isFirst) {
							echo "<br><br>"; // Add double line breaks except for the first iteration
						}

						echo $i . ". " . $value['firm_name'] . "<br>" . $value['street_address'] . "<br>" . $value['district'] . ", " . $value['state'] . "<br>" . $value['postal_code'];
						$i++;	
						$isFirst = false; // Set the flag to false after the first iteration
					}
                }
                ?><br> (Though Packer)
			</td>
        </tr>

        <tr>   
           <td><br>They shall invariably endorse a copy of thrie invoice/advisory note to this office as soon as the consignment of AGMARK replica containers/packages are dispatched to the concerned authorized packers. 
            </td>
        </tr>
    </table>

	<br><br><p></p>


    <table align="right" style="margin-top: 20px">
        <tr>
            <td>
                <img width="100" height="100" src="<?php echo $result_for_qr['qr_code_path']; ?>">
                <p><strong>It is computer generated Replica number and signature is not required</strong></p><br>
                <!-- QR Code added by shankhpal shende on 16/08/2022 -->
		        
			</td>
        </tr>
    </table>

        