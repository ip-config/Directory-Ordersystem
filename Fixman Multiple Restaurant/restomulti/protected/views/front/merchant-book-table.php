
<div class="box-grey rounded" style="margin-top:0;">

<form class="forms" id="frm-book" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','bookATable')?>
<?php echo CHtml::hiddenField('currentController','store')?>
<?php echo CHtml::hiddenField('merchant-id',$merchant_id)?>
	     
<div class="section-label">
    <a class="section-label-a">
      <span class="bold" style="background:#fff;">
      <?php echo t("Booking Information")?></span>
      <b></b>
    </a>     
</div>  

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Number Of Guests")?>
   </div>
   <div class="col-md-3 ">
	 <?php echo CHtml::textField('number_guest',''			 
	  ,array(
	  'class'=>'numeric_only grey-inputs',
	  'required'=>true
	  ))?>
   </div>
</div> <!--row-->

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Date Of Booking")?>
   </div>
   <div class="col-md-3 ">
	 <?php echo CHtml::hiddenField('date_booking')?>
	  <?php echo CHtml::textField('date_booking1',''			 
	  ,array(
	  'class'=>'date_booking grey-inputs',
	  'required'=>true,
	  'data-id'=>'date_booking'
	  ))?>
   </div>
</div> <!--row-->

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Time")?>
   </div>
   <div class="col-md-3 ">
	 <?php echo CHtml::textField('booking_time',''			 
	  ,array(
	  'class'=>'grey-inputs',
	  'required'=>true,
	  ))?>
   </div>
</div> <!--row-->

<div class="section-label">
    <a class="section-label-a">
      <span class="bold" style="background:#fff;">
      <?php echo t("Contact Information")?></span>
      <b></b>
    </a>     
</div>  

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Name")?>
   </div>
   <div class="col-md-6 ">
	  <?php echo CHtml::textField('booking_name',''			 
	  ,array(
	  'class'=>'grey-inputs',
	  'required'=>true,
	  ))?>
   </div>
</div> <!--row-->

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Email")?>
   </div>
   <div class="col-md-6 ">
	  <?php echo CHtml::textField('email',''			 
	  ,array(
	  'class'=>'grey-inputs',
	  'required'=>true,
	  ))?>
   </div>
</div> <!--row-->

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Mobile")?>
   </div>
   <div class="col-md-6 ">
	  <?php echo CHtml::textField('mobile',''			 
	  ,array(
	  'class'=>'grey-inputs',
	  'required'=>true,
	  ))?>
   </div>
</div> <!--row-->

<div class="row top10">
   <div class="col-md-3 ">
     <?php echo t("Your Instructions")?>
   </div>
   <div class="col-md-6 ">
	  <?php echo CHtml::textArea('booking_notes',''			 
	  ,array(
	  'class'=>'grey-inputs'			 
	  ))?>
   </div>
</div> <!--row-->

<div class="row top10">  
  <div class="col-md-3 "></div>
  <div class="col-md-6 text-left ">
  <input type="submit" value="<?php echo t("Book a Table")?>" class="rounded book-table-button green-button inline">
  </div>
</div><!-- row-->

</form>

</div> <!--end box-->