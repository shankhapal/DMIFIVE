<!-- Chemist application pdf template new file added by laxmi Bhadade on 12-12-2022 -->
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
	?>

<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">		
			<h4>Application For Chemist Training </h4>
		</td>
		</tr>
</table>

<table width="100%"><br><br>	
		<tr>
			<td><br>To,</td>
		</tr>	
</table>	
<table  width="100%">
    <tr>
        <td>
         The <br>
		 Dy. AMA/Asstt. AMA/SMO,<br>
         Directorate of Marketing & Inspection,<br>
         RO/SO Office,<br>
         <?php echo $ro_office_address ?>.<br><br>
        </td>
	 </tr>

    <tr>
		<td><br>Subject: Application for training of chemist for analysis, grading and marking of <?php echo $commodities_cate_list; ?> (<?php echo $sub_commodities_list;?>) under Agmark.</td><br><br>
	</tr>

    <tr>
		<td><br>Dear Sir,</td><br>
	</tr>

    <tr>
		<td><br>I am to inform that,
            I am applying for training of my chemist, <?php echo $fname. "&nbsp;". $lname ;?> <?php echo $middle_name_type; ?> <?php echo $middle_name; ?> for analysis, grading and marking of <?php echo $commodities_cate_list; ?> (<?php echo $sub_commodities_list;?>) under Agmark.<br>
            It is requested to provide him/her training and permit to take up the work relating to analysis, grading and marking of <?php echo $commodities_cate_list; ?> (<?php echo $sub_commodities_list;?>) under Agmark in accoradance with the provisions of General Grading & Marking Rules, 1988
			(as amended 2008) under Agriculture Produce (Grading & Marking) Act, 1937, and the instructions issued in this connection from time to time by Agriculture Marketing Adviser to the Govt. of
            India<br>
			I have deposited training charges RS. <?php echo $payment;?> on Bharatkosh.

           </td>

	  
     
    </tr>
       
      </table>

      <table>	
	    <tr>
		<td align="right"><br><strong>Your’s faithfully</strong><br>
                           <?php echo $firm_name;?><br>
                           <?php echo $firm_address; ?><br>
				               <?php echo $district; ?>,<?php echo $state; ?> - <?php echo $pin_code;?>	<br>			
		</td>
	   </tr>
      </table>	
     
      <table>
      	<tr>
	   	<td>
	   		Copy to:<br>
            1.The Agriculture Marketing Adviser to the Govt. of India, DMI, Head Office, Faridabad for
              favour of information.<br>
            2.<?php echo $fname. "&nbsp;". $lname ;?> <?php echo $middle_name_type; ?> <?php echo $middle_name; ?>, <?php echo $chemist_address; ?>, for necessary action.
	   	</td>
	   </tr>
      </table>