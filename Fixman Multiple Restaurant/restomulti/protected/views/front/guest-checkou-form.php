<div class="row top10">
<div class="col-md-10">
  <?php echo CHtml::textField('first_name','',array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","First Name"),
   'data-validation'=>"required"
  ))?>
 </div> 
</div>

<div class="row top10">
<div class="col-md-10">
  <?php echo CHtml::textField('last_name','',array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","Last Name"),
   'data-validation'=>"required"
  ))?> 
 </div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('street',isIsset($client_info['street']),array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","Street"),
   'data-validation'=>"required"
  ))?> 
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('city',isIsset($client_info['city']),array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","City"),
   'data-validation'=>"required"
  ))?> 
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('state',isIsset($client_info['state']),array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","State"),
   'data-validation'=>"required"
  ))?> 
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('zipcode',isIsset($client_info['zipcode']),array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","Zip code")
  ))?> 
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('location_name',isIsset($client_info['location_name']),array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","Apartment suite, unit number, or company name"),   
  ))?>
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('contact_phone',isIsset($client_info['contact_phone']),array(
   'class'=>'grey-fields mobile_inputs',
   'placeholder'=>Yii::t("default","Mobile Number"),
   'data-validation'=>"required"  
  ))?> 
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('delivery_instruction','',array(
  'class'=>'grey-fields full-width',
  'placeholder'=>Yii::t("default","Delivery instructions")   
))?> 
</div> 
</div>

<div class="row top10">
<div class="col-md-10">
<?php echo CHtml::textField('email_address','',array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","Email address"),   
  ))?> 
</div> 
</div>

<?php FunctionsV3::sectionHeader('Create Account')?>		  
<p class="text-muted text-small">***<?php echo t("Optional")?></p>

<div class="row top10">
<div class="col-md-10">
   <?php echo CHtml::passwordField('password','',array(
   'class'=>'grey-fields full-width',
   'placeholder'=>Yii::t("default","Password"),   
  ))?>
 </div> 
</div>          