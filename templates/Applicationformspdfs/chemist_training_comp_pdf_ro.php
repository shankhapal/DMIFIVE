<?php //chemist training completed at Ro side pdf letter added new template file by laxmi on 25-12-2022 ?>
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
<!-- for multiple commodities added by laxmi on 10-1-2023 -->
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
        <tr><td align="center" style="padding:5px;"><h4>Letter from <?php echo $office_type;?> to Completion of Chemist Training</h4></td></tr>
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
            <td>  
            	
                  <?php echo $firmName; ?>,<br>
                    <?php echo $firm_address; ?>,<br>
                   <?php echo $district?>, <?php echo $state?> – <?php echo $pin_code?> <br> 
            </td>
            <?php if(!empty($profile_photo)){ ?>
            <td align="right"> <img src ="<?php echo $profile_photo; ?>" width="auto" height="80">
           </td>
            <?php } ?>
        </tr>
        </table>

      <table  width="100%">

        <tr>    
            <td><br>Subject: Relieving of <?php echo $prefix. "&nbsp;" .$chemist_fname."&nbsp;". $chemist_lname ;?> <?php echo $middle_name_type; ?> <?php echo $middle_name; ?> of <?php echo $firmName; ?>  <?php echo $firm_address; ?> after completion of training for analysis of <?php echo $commodities_cate_list; ?>  (<?php echo $sub_commodities_list; ?>) to be graded under Agmark –reg.</td>
        </tr>
                    
        <tr>
            <td><br>Dear Sir/Madam,</td><br>
        </tr>   

        <tr>
            <td>I am to refer to above cited subject & inform that of <?php echo $prefix. "&nbsp;" .$chemist_fname."&nbsp;". $chemist_lname ;?> <?php echo $middle_name_type; ?> <?php echo $middle_name; ?> sponsored chemist undergone analysis training in <?php echo $office_type;?> office, <?php echo $ro_office;?> from <?php echo $schedule_from;?> to <?php echo $schedule_to;?> for analysis, grading, marking of <?php echo $commodities_cate_list; ?> (<?php echo $sub_commodities_list; ?>) to be graded under Agmark.<br>
			
			<?php echo $he_she; ?> is relieved from the <?php echo $office_type;?> office, <?php echo $ro_office;?> with this request to join <?php echo $his_her; ?> duty at <?php echo $firmName; ?> <?php echo $firm_address; ?>. Send a copy of <?php echo $his_her; ?> joining report in this office, so that a approval letter of <?php echo $prefix. "&nbsp;" .$chemist_fname."&nbsp;". $chemist_lname ;?> <?php echo $middle_name_type; ?> <?php echo $middle_name; ?> may be issued as early as possible<br>
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
				 <?php echo $ro_fname."&nbsp;". $ro_lname ?><br>
                 Incharge, <?php echo $office_type ;?> office,<br>
				Directorate of Marketing and Inspection<br> 
				<?php echo $ro_office;?><br>
			
			</td>
		</tr>
		<tr>
            <td><br></td>
        </tr>
	</table>


	<br>

	<table align="left">	
					
		<tr>
			<td>Copy to:<br> 
			1.<?php echo $prefix."&nbsp;" .$chemist_fname."&nbsp;". $chemist_lname ;?> <?php echo $middle_name_type; ?> <?php echo $middle_name; ?> of <?php echo $firmName; ?>, <?php echo $firm_address; ?> with this instruction to join your duty at
           above unit & send a copy of your joining report, so that an approval letter may be issued for analysis, grading, marking of <?php echo $commodities_cate_list; ?> (<?php echo $sub_commodities_list; ?>) to be graded under Agmark.<br>
			</td>
		</tr>
		<tr>
            <td><br></td>
        </tr>
	</table>

	<br>
	<table align="right">	
					
		<tr>
			<td>
            <?php echo $ro_fname."&nbsp;". $ro_lname ?><br>
                 Incharge, <?php echo $office_type ;?> office,<br>
				Directorate of Marketing and Inspection<br> 
				<?php echo $ro_office;?><br>
			</td>
		</tr>
		<tr>
            <td><br></td>
        </tr>
	</table>


    
	
	
        