<?php  // added new template file for chemist training approval certificate pdf by laxmi Bhadade on 10-1-2023 ?>
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
    $commodities_list = implode(',',$commodities_cate_array);

   //set chemist prefix on the basis of middle name type added by laxmi on 05-09-2023
   if(!empty($middle_name_type)){
    if($middle_name_type == 'D/o'){
        $prefix = 'Ms.';
        $his_her = 'her';
        $mam_sir = 'madam';
    }elseif($middle_name_type == 'S/o'){
        $prefix = 'Shri.';
        $his_her = 'his';
        $mam_sir = 'sir';
    }elseif($middle_name_type == 'W/o'){
        $prefix = 'Smt.';
        $his_her = 'her';
        $mam_sir = 'madam';
    }
    
}

?>
	
    <table width="100%" border="1">
        <tr><td align="center" style="padding:5px;"><h4>Letter from <?php echo $office_type;?> To Chemist for Training schedule</h4></td></tr>
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
            	  <?php echo $chemist_fname."&nbsp;". $chemist_lname ;?>, <br>
                  <?php echo $customer_firm_data['firm_name']; ?>,<br>
                  <?php echo $customer_firm_data['street_address']; ?>,<br>
                  <?php echo $firm_district_name;?>,
                  <?php echo $firm_state_name; ?> – <?php echo $customer_firm_data['postal_code']; ?>
            </td>
            <?php if(!empty($profile_photo)){ ?>
                <td align = "right"><img src="<?php echo $profile_photo; ?>" width= "auto" height="80">
            </td>
                <?php }?>
        </tr>
     </table>
     <table  width="100%">
        <tr>    
            <td><br>Subject: Scheduled for training of chemist for analysis, grading and marking of  <?php echo $commodities_list; ?> (<?php echo $sub_commodities_list; ?>) under Agmark.</td>
        </tr>
          <br>


        <tr>
            <td><br>Dear Sir/Madam,</td><br>
        </tr>                                                                                                                                                                                                                                                                                                                        

        <tr>
            <td>I am to refer to above cited subject & inform that the procedural training of <b> <?php echo $prefix. "&nbsp;" .$chemist_fname."&nbsp;". $chemist_lname ;?> <?php echo $middle_name_type; ?> <?php echo $parent_name; ?> </b> for analysis, grading & marking of <b><?php echo $commodities_list; ?> (<?php echo $sub_commodities_list; ?>) </b> under Agmark is Scheduled
             from <?php echo $schedule_from; ?> to  <?php echo $shedule_to;?> at <?php echo $office_type;?> office <?php echo $ro_office;?><br>
			
			It is requested to attend the said training at <b><?php echo $office_type;?> office</b>  <?php echo $ro_office;?>.
	
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
				<?php echo $ro_fname;?>  <?php echo $ro_lname;?>,<br> 
               Incharge, <?php echo $office_type;?> office <br>
               Directorate of Marketing and Inspection <br>
				 <?php echo $ro_office;?>
			</td>
		</tr>
		<tr>
            <td><br></td>
        </tr>
	</table>


	


    
	
	
        