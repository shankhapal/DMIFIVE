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
</style>
	<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;"><h4>Routine Inspection Report (Printing Press)</h4></td>
			</tr>
	</table>

  <table width="100%" border="1">
	
      <tr>
          <td style="padding:10px; vertical-align:top;">Date of Last Inspection :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['date_last_inspection'])?$rti_pp_data['date_last_inspection']:"NA"; ?></td>
      </tr>
      <tr>
        <td style="padding:10px; vertical-align:top;">Date & Time of present Inspection :</td>
        <td style="padding:10px; vertical-align:top;">Date:
        <?php echo isset($rti_pp_data['date_p_inspection']) ? $rti_pp_data['date_p_inspection'] : "NA"; ?>, Time:

            <?php $time = date("h:i A", strtotime($rti_pp_data['time_p_inspection']));
            echo isset($time) ? $time : "NA"; ?>

        </td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">1. Name of the Printing Press :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo isset($firm_details['firm_name'])?$firm_details['firm_name']:"NA"; ?></td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">2. Full address with Telephone nos. and e-mail etc. :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['street_address'])?$rti_pp_data['street_address']:"NA"; ?></td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">Email Id:</td>
          <td style="padding:10px; vertical-align:top;"><?php echo base64_decode(isset($firm_details['email'])?$firm_details['email']:"NA"); ?></td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">Mobile No :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo base64_decode(isset($firm_details['mobile_no'])?$firm_details['mobile_no']:"NA"); ?></td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">a) Registered Office :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo isset($registered_office_address)?$registered_office_address:"NA"; ?></td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">b) Printing Press premises. :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo isset($printing_premises_address)?$printing_premises_address:"NA"; ?></td>
      </tr>
      <tr>
          <td style="padding:10px; vertical-align:top;">3. Permission valid upto :</td>
          <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['valid_upto'])?$rti_pp_data['valid_upto']:"NA"; ?></td>
      </tr>

      <tr>
          <td style="padding:10px; vertical-align:top;">4. Permitted packaging material :</td>
          <td style="padding:10px; vertical-align:top;">
          <?php
            if (!empty($packaging_materials_value)) {
                echo implode(',', $packaging_materials_value);
            } else {
                echo 'NA';
            }
            ?></td>
      </tr>
</table>  
<table width="100%" border="1">
  <tr>
      <td style="padding:5px;">5. List of packers granted permission to print Agmark Replica by the printing press</td>
  </tr>
</table>
<table width="100%" border="1">
      <tr>
          <th align="center" style="padding:5px;">Sr.No</th>
          <th align="center" style="padding:5px;">Packer</th>
          <th align="center" style="padding:5px;">CA No.</th>
          <th align="center" style="padding:5px;">Validity</th>
          <th align="center" style="padding:5px;">Commodities</th>
          <th align="center" style="padding:5px;">TBL</th>
      </tr>
      <?php 
        $i=1;
        if(!empty($all_packers_value)){
          foreach ($all_packers_value as $each_value) {  ?>
          <tr>
            <td align="center" style="padding:5px;"><?php echo $i; ?></td>
            <td align="center" style="padding:5px;"><?php echo $each_value['firm_name']; ?></td>
            <td align="center" style="padding:5px;"><?php echo $each_value['customer_id']; ?></td>
            <td align="center" style="padding:5px;"><?php echo $each_value['validupto']; ?></td>
            <td align="center" style="padding:5px;"><?php echo implode(",",$each_value['sub_commodity']); ?></td>
            <td align="center" style="padding:5px;"><?php echo implode(",",$each_value['tbl_name']); ?></td>
          </tr>
      <?php $i++; }}?>
</table>
<table width="100%" border="1">
  <tr>
      <td style="padding:5px;">6. Available stock of printed packaging material with Agmark replica (packer wise)</td>
  </tr>
</table>
<table width="100%" border="1">
       <tr>
          <th colspan="2"></th>
          <th align="center" style="padding:5px;" colspan="3" class="text-center">Quantity /Nos.</th>
          <th colspan="2"></th>
      </tr>
      <tr>
          <th align="center" style="padding:5px;">Sr.No</th>
          <th align="center" style="padding:5px;">Packer Id</th>
          <th align="center" style="padding:5px;">Indent</th>
          <th align="center" style="padding:5px;">Supplied</th>
          <th align="center" style="padding:5px;">Balance</th>
          <th align="center" style="padding:5px;" colspan="3" >TBL</th>
      </tr>
        <?php 
            $i=1; 
            foreach($added_packers_details as $p_detail){ ?>    
        <tr>
            <td align="center" style="padding:5px;"><?php echo $i; ?></td>
            <td align="center" style="padding:5px;"><?php echo $p_detail['packer_id'];?></td>
            <td align="center" style="padding:5px;"><?php echo $p_detail['indent'];?></td>
            <td align="center" style="padding:5px;"><?php echo $p_detail['supplied'];?></td>
            <td align="center" style="padding:5px;"><?php echo $p_detail['balance'];?></td>
            <td align="center" style="padding:5px;" colspan="3" ><?php echo $p_detail['tbl'];?></td>
        </tr>   
        <?php $i=$i+1; } ?>
    </table>
<table width="100%" border="1">
     <tr>
        <td style="padding:10px; vertical-align:top;">7. Whether the printed material as in column 6 above is in order as per physical check :</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['physical_check'])?$rti_pp_data['physical_check']:"NA"; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">8. Whether the printing press is printing </td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['is_printing'])?$rti_pp_data['is_printing']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">9. Details of in - house storage facilities for security and safe custody of printing and printed material.</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['storage_facilities'])?$rti_pp_data['storage_facilities']:"NA"; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">10. Whether the printing press maintains proper accounts for printing orders received, executed and send monthly invoice records to concerned RO/SO. </td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['maintains_proper'])?$rti_pp_data['maintains_proper']:"NA"; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">11. Whether press is using right quality of printing ink and food grade packaging material. (Check Certificates)</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['right_quality_of_printing'])?$rti_pp_data['right_quality_of_printing']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">12. Whether the printing press is marking logo of printing unit on packaging material.</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['press_is_marking_logo'])?$rti_pp_data['press_is_marking_logo']:"NA"; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">13. Suggestions given during the last inspection, if any & whether corrective action taken</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['suggestions_last_ins_yes_no'])?$rti_pp_data['suggestions_last_ins_yes_no']:"NA"; ?></td>
        
		</tr>
    <?php if($rti_pp_data['suggestions_last_ins_yes_no'] == "yes"){ ?>
    <tr>
      <td><?php echo $rti_pp_data['last_insp_suggestion']; ?></td>
    </tr>
     <?php } ?>
     <tr>
        <td style="padding:10px; vertical-align:top;">14. Shortcomings observed during the present Inspection.</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['shortcomings_noticed'])?$rti_pp_data['shortcomings_noticed']:"NA"; ?></td>
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">Shortcomings noticed Docs</td>
        <td style="padding:10px; vertical-align:top;"><?php if(!empty($rti_pp_data['shortcomings_noticed_docs'])){ ?><a id="shortcomings_noticed_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$rti_pp_data['shortcomings_noticed_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$rti_pp_data['shortcomings_noticed_docs'])), -1))[0],23);?></a>
        <?php }else{ echo "No Document Provided" ;} ?></td>
        
		</tr>
    <tr>
        <td style="padding:10px; vertical-align:top;">15. Suggestions, if any</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['if_any_sugg'])?$rti_pp_data['if_any_sugg']:"NA"; ?></td>
		</tr>
</table>
 <table width="100%" border="1">
    <tr>
        <td style="padding:5px;">Signnature and Name of the Inspecting Officer</td>
    </tr>
  </table>
  <table width="100%" border="1">
     <tr>
        <td style="padding:10px; vertical-align:top;">Name of the Inspecting Officer</td>
			  <td style="padding:10px; vertical-align:top;"><?php echo isset($rti_pp_data['name_of_inspecting_officer'])?$rti_pp_data['name_of_inspecting_officer']:"NA"; ?></td>
		</tr>
     <tr>
        <td style="padding:10px; vertical-align:top;">Signature</td>
			  <td style="padding:10px; vertical-align:top;"><?php if(!empty($rti_pp_data['signnature_io_docs'])){ ?><a id="signnature_io_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$rti_pp_data['signnature_io_docs']); ?>"><?=$str2 = substr(array_values(array_slice((explode("/",$rti_pp_data['signnature_io_docs'])), -1))[0],23);?></a>
        <?php }else{ echo "No Document Provided" ;} ?></td>
		</tr>
  </table>
   