<input type="hidden" id="add_master_btn_master_id" value="<?php echo $masterId; ?>">
<?php // This Below Condition Block is added to the common the buttons from the Add Masters -> Akash [13-12-2022]
    if ($masterId == '11') { #For Edit Template Button
        echo $this->Form->submit('Add Template', array('name'=>'add_sms_template', 'id'=>'add_sms_template_btn','label'=>false,'class'=>'btn btn-success float-left')); 
    } elseif ($masterId == '12') { #For Edit PAO Button
        echo $this->Form->submit('Add PAO/DDO', array('name'=>'add_pao', 'id'=>'add_pao_btn','class'=>'btn btn-success float-left', 'label'=>false));
    } else { #For All Other Submit buttons
        echo $this->Form->submit('Add', array('name'=>'add_master', 'id'=>'add_master_btn','label'=>false,'class'=>'btn btn-success float-left'));
    }

     echo $this->Html->script('element/masters_management_elements/button_elements/add_submit_common_btn');
?>
