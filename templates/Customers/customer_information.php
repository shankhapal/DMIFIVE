
<?php echo $this->html->css('customers/customer_information'); 
  ?>
  
<h4 class="text-center"><u>Search Details</u></h4>
<div class="containerbox">
<div class="error" id="error_msg"></div>
<div class="clearfix">&nbsp;</div>
 <?php echo $this->Form->create(null, array('id'=>'search_customer','type'=>'file', 'enctype'=>'multipart/form-data')); ?>
  <div class="row">
   <!--  <div class="col-sm-3">
      <input type="radio" id="primary" name="primary" class="box">
  <label for="primary">Primary</label>
    </div> -->
    <div class="col-sm-3">
     <input type="radio" id="firm" name="firm" class="box">
  <label for="firm">Firm ID</label>
    </div>
    <div class="col-sm-3">
     <input type="radio" id="replica" name="replica" class="box">
  <label for="replica">Replica No.</label>
    </div>
    <div class="col-sm-3">
     <input type="radio" id="Code15Digit" name="code15Digit" class="box">
  <label for="Code15Digit">Code 15 Digit No.</label>
    </div>
    <div class="col-sm-3">
     <input type="radio" id="ecode" name="ecode" class="box">
  <label for="ecode">Ecode No.</label>
    </div>

    
</div>
<div class="clearfix">&nbsp;</div>
<div class="row">
  <div class="col-sm textenter">
  <input type="text" name="search_text" class="form-control serach-box box" placeholder="Enter for search here." id="search_text">
</div>
<div class="col-sm">
  <input type="submit" value="search" name="save" class="btn btn-submit search-btn" id="search_btn">
  </div>
</div>

<?php echo $this->Form->end(); ?>

<div class="clearfix">&nbsp;</div>

<!-- after search customer table show -->
<div class="row" id="customer_data">


</div>

<!-- below  firm list show code added by laxmi B. on 06/02/2023 -->
<div class="clearfix"> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</div>

<div class="row text-right">
<h5 class="text-right"><a href="<?php echo $this->request->getAttribute('webroot')."customers/certifiedFirmList ";?>"class="text-right ">Check Certified Firm List</a></h5>
</div>
</div>
<!-- below code added by laxmi B. on 06/02/2023 -->

	<?php echo $this->html->script('customers/customer_information'); ?>

