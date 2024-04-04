<?php   // NEW FILE FOR CHEMIST TRAINING APPROVAL CERTIFICATE TEMPLATE ADDED BY LAXMI BHADADE ON 10-01-23 ?>
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
<?php
    $i=0;
    $sub_commodities_array = array(); 
    $commodities_cate_array = array();    
    foreach($sub_commodity_data as $sub_commodity){
        
        $sub_commodities_array[$i] = $sub_commodity['commodity_name'];
        if(!empty($commodity_name_list[$i]['category_name'])){
        $commodities_cate_array[$i] = $commodity_name_list[$i]['category_name'];
        }
    $i=$i+1;
    } 
    
    $sub_commodities_list = implode(',',$sub_commodities_array);
    $commodities_cate_list = implode(',',$commodities_cate_array);
     //set chemist prefix on the basis of middle name type added by laxmi on 05-09-2023
   if(!empty($middle_name_type)){
    if($middle_name_type == 'D/o'){
        $prefix = 'Ms.';
        $his_her = 'her';
        $mam_sir = 'madam';
        $he_she = 'She';
    }elseif($middle_name_type == 'S/o'){
        $prefix = 'Shri.';
        $his_her = 'his';
        $mam_sir = 'sir';
        $he_she = 'He';
    }elseif($middle_name_type == 'W/o'){
        $prefix = 'Smt.';
        $his_her = 'her';
        $mam_sir = 'madam';
        $he_she = 'She';
    }
    
}
?>

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
        <tr><td align="center" style="padding:5px;"><h4>Certificate of Approval of Chemist for grading and marking under AGMARK</h4></td></tr>
    </table>

    <table width="100%" border="1">
        <tr><td>Applicant Id: <?php echo $customer_id; ?></td>
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
            <td> <?php echo $customer_firm_data['firm_name']; ?>,<br>
                <?php echo htmlspecialchars_decode($customer_firm_data['street_address']); ?>,<br>
                 <?php echo $firm_district_name;?>,
                  <?php echo $firm_state_name; ?> – <?php echo $customer_firm_data['postal_code']; ?>

            </td>
         
            <td align = "right">
                <img src="<?php echo $profile_photo; ?>" width="100" height="100">
            </td>
        </tr>
    </table>
    <table  width="100%">
        <tr>    
            <td><br>Subject: Approval of <?php echo $prefix;?> <?php echo $chemist_fname;?> <?php echo $chemist_lname;?>  <?php echo $middle_name_type ; ?> <?php echo $middle_name ; ?> for grading & marking of <?php echo $commodities_cate_list;?> (<?php echo $sub_commodities_list;?>) under Agmark.</td>
        </tr>
                    
        <tr>
            <td><br>Dear Sir,</td><br>
        </tr>   

        <tr>
            <td>I am to inform that,
               Your chemist, <b><?php echo $prefix;?> <?php echo $chemist_fname;?> <?php echo $chemist_lname;?> <?php echo $middle_name_type ; ?> <?php echo $middle_name ; ?> </b>who has undergone necessary training for the analysis, grading & marking of<b> <?php echo $commodities_cate_list;?> (<?php echo $sub_commodities_list;?>) </b>under Agmark at the Regional Agmark Laboaratory, <b><?php echo $ral_office;?>  </b>
               for the period from <b><?php echo $schedule_from;?></b> to <b> <?php echo $shedule_to;?></b>  and procedural training for sampling, grading, packing and maintainance of records at the <?php echo $office_type; ?>, DMI, <b><?php echo $ro_office;?> </b>for the period from <b><?php echo $ro_schedule_from;?></b>  to <b><?php echo $ro_shedule_to;?></b> 
               is hereby approved as chemist and permitted to take up the work relating to the analysis, grading and marking of <b> <?php echo $commodities_cate_list;?> (<?php echo $sub_commodities_list;?>) </b> under Agmark in accordance with the provisions of General Grading and Marking Rules, 1988 (as amended 2008), relevant Commodity Grading & Marking Rules under Agricultural Produce (Grading & Marking) Act, 1937
               and the Guidelines/instruction issued in this connection from time to time by Agricultural Marketing Adviser to the Govt. of India.<br>

               <b><?php echo $prefix;?>  <?php echo $chemist_fname;?> <?php echo $chemist_lname;?></b> chemist shall be responsible for safe custody of labels, replica bearing containers, maintenance of label account and label charges accounts, submission of regular biannual returns etc in the absence of chemist In-charge.<br>

               It may be noted that as per the relevant instructions, the services of the approved chemist shall not be dispensed without prior consent of the Agricultural Marketing Adviser to the Government of India or any person duly authorized by him.<br>	
			</td>
        </tr>
                    
        <tr>
           <td><br></td>
        </tr>
              
    </table>


	<br>
    <table align="right">	
					
		<tr>
			<td>Your’s faithfully<br> 
				<?php echo $ro_fname;?>  <?php echo $ro_lname;?>,
                <br>Incharge,  DMI, RO/SO<br> 
                <?php echo htmlspecialchars_decode(str_replace(",","<br>",$ro_address)); ?>,<br>
				<?php echo $ro_office; ?><br>
			</td>
		</tr>
	</table>
	<table width="100%">
  <tr>
  	<td>
  		Copy to:<br>
  		1.The Agricultural Marketing Adviser to the Govt. of India, DMI, Head Office, Faridabad for favour of information.<br>
  		2.<?php echo $prefix;?> <?php echo $chemist_fname;?> <?php echo $chemist_lname;?> <?php echo $middle_name_type ; ?> <?php echo $middle_name ; ?>, <?php echo htmlspecialchars_decode($chemist_address);?>, for necessary action.<br>
        <br>  <img width="100" height="100" src="<?php echo $result_for_qr['qr_code_path']; ?>">
  	</td>
      
  </tr>
<!-- </table> -->

	 <!-- <table width="100%"> -->
   <!--<tr>  QR Code added by shankhpal shende on 13/07/2023 -->
  	<!-- <td><img width="100" height="100" src="<?php //echo $result_for_qr['qr_code_path']; ?>"></td>
  </tr> -->
</table>
	
	
        