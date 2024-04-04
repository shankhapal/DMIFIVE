<!-- added new file to show firm list by laxmi-->
<?php echo $this->html->css('customers/customer_information'); ?>
<?php echo $this->html->css('customers/certified_firm_list'); ?>
  
<h2 class="text-center"><u> Search Certified Firms</u></h2>
<div class="container main-cont">
  <h4 class="text-center"><u>Certification Types</u></h4>
<div class="error" id="error_msg"></div>
<div class="clearfix">&nbsp;</div>
 <?php echo $this->Form->create(null, array('id'=>'search_customer','type'=>'file', 'enctype'=>'multipart/form-data')); ?>
  <div class="row">
  
    <div class="col-sm-3">
     <input type="radio" id="certficate" name="caCertificate" class="box" value="1">
  <label for="certficate"><b>Certificate Of Authorization</b></label>
    </div>
    <div class="col-sm-3">
     <input type="radio" id="printing" name="printing" class="box" value="2">
  <label for="printing"><b>Approval of Printing Permission</b></label>
    </div>
    <div class="col-sm-3">
     <input type="radio" id="lab" name="lab" class="box" value="3">
  <label for="lab"><b>Approval Of Laboratory</b></label>
    </div>
     <div class="col-sm-3">
      <input type="submit" value="View List" name="save" class="btn btn-submit search-btn" id="search_btn">
    </div>    
</div>

<div class="clearfix">&nbsp;</div>


<?php echo $this->Form->end(); ?>


<!-- after search customer table show -->
<div class="container cont">
  <h2 class="text-center"><u>Certified Firm List</u></h2>
      
       
        <table class="table table-striped table-class" id= "table-id">
  
  
        <thead>
        <tr>
        <th>ID</th>
        <th>Customer ID</th>
        <th>Name</th>
        <th>Commodity</th>
        <th>Valid Date</th>
        </tr>
        </thead>
        <tbody id="customer_data">
        
        <tbody>
        </table>

       

         </div> <!--     End of Container -->
         </div>
         </div>
</div>
<div class="clearfix">&nbsp;</div>


  <?php echo $this->html->script('customers/certified_firm_list'); ?>