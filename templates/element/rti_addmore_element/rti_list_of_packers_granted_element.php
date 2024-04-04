 <?php ?>
 <div class="form-horizontal">
    <div class="card-body">
        <div class="row">
        <!-- table-->
        <table id="replica_appl_list_table1" class="table table-bordered table-hover table-striped">
                <thead class="tablehead">
                    <tr>
                        <th>Sr.No</th>
                        <th>Packer</th>
                        <th>CA No.</th>
                        <th>Validity</th>
                        <th>Commodities</th>
                        <th>TBL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i=0;
                        $sr_no = 1;
                        foreach($section_form_details[3] as $each_details){ ?>
                            <tr>
                                <td><?php echo $sr_no; ?></td>
                                <td><?php echo $each_details['firm_name'];?></td>
                                <td><?php echo $each_details['customer_id'];?></td>
                                <td><?php echo $each_details['validupto'];?></td>
                                <td><?php echo $this->Form->control('commList', array('type'=>'select', 'options'=>$each_details['sub_commodity'], 'multiple'=>'multiple', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
                                </td><td><?php echo $this->Form->control('tblList', array('type'=>'select', 'options'=>$each_details['tbl_name'], 'multiple'=>'multiple', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
                                </td>
                                
                            </tr>                                        

                    <?php $sr_no++; $i=$i+1; } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>