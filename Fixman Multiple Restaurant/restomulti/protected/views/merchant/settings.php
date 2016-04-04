<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$merchant_currency=Yii::app()->functions->getOption("merchant_currency",$merchant_id);
$merchant_decimal=Yii::app()->functions->getOption("merchant_decimal",$merchant_id);
$merchant_use_separators=Yii::app()->functions->getOption("merchant_use_separators",$merchant_id);
$merchant_minimum_order=Yii::app()->functions->getOption("merchant_minimum_order",$merchant_id);
$merchant_tax=Yii::app()->functions->getOption("merchant_tax",$merchant_id);
$merchant_delivery_charges=Yii::app()->functions->getOption("merchant_delivery_charges",$merchant_id);
$stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);

$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
$stores_open_custom_text=!empty($stores_open_custom_text)?(array)json_decode($stores_open_custom_text):false;

$merchant_photo=Yii::app()->functions->getOption("merchant_photo",$merchant_id);
$merchant_delivery_estimation=Yii::app()->functions->getOption("merchant_delivery_estimation",$merchant_id);
$merchant_delivery_charges_type=Yii::app()->functions->getOption("merchant_delivery_charges_type",$merchant_id);

$merchant_photo_bg=Yii::app()->functions->getOption("merchant_photo_bg",$merchant_id);

$merchant_extenal=Yii::app()->functions->getOption("merchant_extenal",$merchant_id);
$merchant_maximum_order=Yii::app()->functions->getOption("merchant_maximum_order",$merchant_id);

$merchant_switch_master_cod=Yii::app()->functions->getOption("merchant_switch_master_cod",$merchant_id);
$merchant_switch_master_ccr=Yii::app()->functions->getOption("merchant_switch_master_ccr",$merchant_id);

$merchant_minimum_order_pickup=Yii::app()->functions->getOption("merchant_minimum_order_pickup",$merchant_id);
$merchant_maximum_order_pickup=Yii::app()->functions->getOption("merchant_maximum_order_pickup",$merchant_id);

$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;

$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;

$FunctionsK=new FunctionsK();
$tips_list=$FunctionsK->tipsList(true);
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantSettings')?>

<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Merchant Logo")?></label>
  <div style="display:inline-table;margin-left:1px;" class="button uk-button" id="photo"><?php echo Yii::t('default',"Browse")?></div>	  
  <DIV  style="display:none;" class="photo_chart_status" >
	<div id="percent_bar" class="photo_percent_bar"></div>
	<div id="progress_bar" class="photo_progress_bar">
	  <div id="status_bar" class="photo_status_bar"></div>
	</div>
  </DIV>		  
</div>

<?php if (!empty($merchant_photo)):?>
<div class="uk-form-row"> 
<?php else :?>
<div class="input_block preview">
<?php endif;?>
<label><?php echo Yii::t('default',"Preview")?></label>
<div class="image_preview">
 <?php if (!empty($merchant_photo)):?>
 <input type="hidden" name="photo" value="<?php echo $merchant_photo;?>">
 <img class="uk-thumbnail uk-thumbnail-small" src="<?php echo Yii::app()->request->baseUrl."/upload/".$merchant_photo;?>?>" alt="" title="">

 <div>
   <a href="javascript:;" class="remove-merchant-logo"><?php echo Yii::t("default","Remove Logo")?></a>
 </div> 
 <?php endif;?>
</div>
</div>


<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Merchant Header/Background")?></label>
  <div style="display:inline-table;margin-left:1px;" class="button uk-button" id="photo2"><?php echo Yii::t('default',"Browse")?></div>	  
  <DIV  style="display:none;" class="photo2_chart_status" >
	<div id="percent_bar" class="photo2_percent_bar"></div>
	<div id="progress_bar" class="photo2_progress_bar">
	  <div id="status_bar" class="photo2_status_bar"></div>
	</div>
  </DIV>		  
</div>
<p class="uk-text-muted uk-text-small"><?php echo Yii::t("default","Filename of image must not have spaces")?></p>

<?php if (!empty($merchant_photo_bg)):?>
<div class="uk-form-row"> 
<?php else :?>
<div class="input_block preview">
<?php endif;?>
<label><?php echo Yii::t('default',"Preview")?></label>
<div class="image_preview2">
 <?php if (!empty($merchant_photo_bg)):?>
 <input type="hidden" name="photo2" value="<?php echo $merchant_photo_bg;?>">
 <img class="uk-thumbnail uk-thumbnail-small" src="<?php echo Yii::app()->request->baseUrl."/upload/".$merchant_photo_bg;?>?>" alt="" title="">

 <div>
   <a href="javascript:;" class="remove-merchant-bg"><?php echo Yii::t("default","Remove")?></a>
 </div>
 <?php endif;?>
</div>
</div>

<hr/>

<!--<h3><?php echo Yii::t("default","Store Currency")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Currency Code")?></label>
  <?php echo CHtml::dropDownList('merchant_currency',
  empty($merchant_currency)?"USD":$merchant_currency
  ,Yii::app()->functions->currencyList()
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>-->

<!--<h3><?php echo Yii::t("default","Price Format")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Decimal Places")?></label>
  <?php echo CHtml::dropDownList('merchant_decimal',empty($merchant_decimal)?2:$merchant_decimal,Yii::app()->functions->decimalPlacesList()
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use 1000 Separators(,)?")?></label>
  <?php 
  echo CHtml::checkBox('merchant_use_separators',
  $merchant_use_separators=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?>
</div>-->
  <!--
<h3><?php echo Yii::t("default","Menu Options")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Menu")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="default"?true:false
  ,array(
    'value'=>"default",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Activate Menu 1")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="1"?true:false
  ,array(
    'value'=>"1",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Activate Menu 2")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="2"?true:false
  ,array(
    'value'=>"2",
    'class'=>"icheck"
  ))
  ?> 
</div>
-->  
  
<?php if ( getOptionA('mechant_sms_enabled')==""):?>
<h2><?php echo t("Order Options")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Order SMS Verification")?></label>  
  <?php 
  echo CHtml::checkBox('order_verification',
   Yii::app()->functions->getOption('order_verification',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Customer can request sms code every")?></label>  
  <?php echo CHtml::textField('order_sms_code_waiting',getOption($merchant_id,'order_sms_code_waiting'),array(
  'class'=>"numeric_only"
  ))?>
  <span>(<?php echo t("Minutes")?>) <?php echo t("default is 5 minutes")?></span>
  
</div>

<hr/>
<?php endif;?>

  
<h2><?php echo t("Food Item Options")?></h2>

<h5><?php echo t("If item is not available do the following actions")?></h5>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Hide")?></label>  
  <?php 
  echo CHtml::radioButton('food_option_not_available',
   Yii::app()->functions->getOption('food_option_not_available',$merchant_id)==1?true:false
   ,array(
   'class'=>"icheck",
   'value'=>1
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled")?></label>  
  <?php 
  echo CHtml::radioButton('food_option_not_available',
   Yii::app()->functions->getOption('food_option_not_available',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled food gallery")?></label>  
  <?php 
  echo CHtml::checkBox('disabled_food_gallery',
   Yii::app()->functions->getOption('disabled_food_gallery',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>  
</div>

<hr/>
  
<h2><?php echo Yii::t("default","Merchant Open/Close")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Close Store")?>?</label>  
  <?php 
  echo CHtml::checkBox('merchant_close_store',
   Yii::app()->functions->getOption('merchant_close_store',$merchant_id)=="yes"?true:false
   ,array(
   'class'=>"icheck",
   'value'=>"yes"
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Show Merchant Current Time")?>?</label>  
  <?php 
  echo CHtml::checkBox('merchant_show_time',
   Yii::app()->functions->getOption('merchant_show_time',$merchant_id)=="yes"?true:false
   ,array(
   'class'=>"icheck",
   'value'=>"yes"
  ))
  ?>  
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Ordering")?>?</label>  
  <?php 
  echo CHtml::checkBox('merchant_disabled_ordering',
   Yii::app()->functions->getOption('merchant_disabled_ordering',$merchant_id)=="yes"?true:false
   ,array(
   'class'=>"icheck",
   'value'=>"yes"
  ))
  ?>  
</div>
  

<h3><?php echo Yii::t("default","External Website")?></h3>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Website address")?></label>
<?php 
echo CHtml::textField('merchant_extenal',$merchant_extenal,array(
'class'=>"uk-width-1-2"
))
?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Voucher")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_enabled_voucher',
  Yii::app()->functions->getOption("merchant_enabled_voucher",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Make Delivery Time Required")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_required_delivery_time',
  Yii::app()->functions->getOption("merchant_required_delivery_time",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<?php 
$paymentgateway=Yii::app()->functions->getMerchantListOfPaymentGateway();
?>
  
<h3><?php echo Yii::t("default","Payment Option")?></h3>

<?php if (in_array("cod",(array)$paymentgateway)):?>
<?php if ( $merchant_switch_master_cod==""):?>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Cash On delivery")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_disabled_cod',
  Yii::app()->functions->getOption("merchant_disabled_cod",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>
<?php endif;?>
<?php endif;?>

<?php if (in_array("ocr",(array)$paymentgateway)):?>
<?php if ( $merchant_switch_master_ccr==""):?>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Offline Credit Card Payment")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_disabled_ccr',
  Yii::app()->functions->getOption("merchant_disabled_ccr",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>
<?php endif;?>
<?php endif;?>


<h3><?php echo Yii::t("default","Minimum Order")?> <?php echo t("Delivery")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Minimum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_minimum_order',$merchant_minimum_order,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<h3><?php echo Yii::t("default","Maximum Order")?> <?php echo t("Delivery")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Maximum purchase amount")?></label>
  <?php 
  echo CHtml::textField('merchant_maximum_order',
  $merchant_maximum_order
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>


<h3><?php echo Yii::t("default","Minimum Order")?> <?php echo t("Pickup")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Minimum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_minimum_order_pickup',$merchant_minimum_order_pickup,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<h3><?php echo Yii::t("default","Maximum Order")?> <?php echo t("Pickup")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Maximum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_maximum_order_pickup',$merchant_maximum_order_pickup,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>


<h3><?php echo Yii::t("default","Packaging Charge")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Packaging Charge")?></label>
  <?php 
  echo CHtml::textField('merchant_packaging_charge',
  Yii::app()->functions->getOption("merchant_packaging_charge",$merchant_id)
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Packaging Incremental")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_packaging_increment',
  Yii::app()->functions->getOption("merchant_packaging_increment",$merchant_id)==2?true:false
  ,array(
  'class'=>"icheck",
  'value'=>2
  ))
  ?>  
</div>

<h3><?php echo Yii::t("default","Tax & Delivery Charges")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Tax")?></label>
  <?php 
  echo CHtml::textField('merchant_tax',$merchant_tax,array(
    'class'=>"numeric_only"
  ))
  ?>%  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Delivery Charges")?></label>
  <?php 
  //echo CHtml::dropDownList('merchant_delivery_charges_type',$merchant_delivery_charges_type
  //,Yii::app()->functions->deliveryChargesType());
  echo CHtml::textField('merchant_delivery_charges',$merchant_delivery_charges,array(
    'class'=>"numeric_only",
    //'style'=>"width:80px;"
  ));
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>      
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Do not apply tax to delivery charges")?></label>
<?php echo CHtml::checkBox('merchant_tax_charges',
Yii::app()->functions->getOption("merchant_tax_charges",$merchant_id)==2?true:false
,array(
'class'=>"icheck",
'value'=>2
));  
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Delivery Estimation")?></label>
<?php 
  echo CHtml::textField('merchant_delivery_estimation',$merchant_delivery_estimation,array(
  'placeholder'=>Yii::t("default","1 hour approx.")
  ));
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Delivery Distance Covered")?></label>
<?php 
  echo CHtml::textField('merchant_delivery_miles',
  Yii::app()->functions->getOption("merchant_delivery_miles",$merchant_id)
  ,array(
  'placeholder'=>"",
  "class"=>"numeric_only"
  ));
  ?>
  <?php //echo Yii::t("default","Miles")?>
  <?php 
  echo CHtml::dropDownList('merchant_distance_type',
  Yii::app()->functions->getOption("merchant_distance_type",$merchant_id),Yii::app()->functions->distanceOption());
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","Leave the fields empty to not check the distance")?></p>



<h3><?php echo Yii::t("default","Tips")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_enabled_tip',
  Yii::app()->functions->getOption("merchant_enabled_tip",$merchant_id)==2?true:false
  ,array(
  'class'=>"icheck",
  'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Tip")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_tip_default',
  Yii::app()->functions->getOption("merchant_tip_default",$merchant_id)
  ,(array)$tips_list,
  array(   
  ));
  ?>  
</div>


<?php $days=Yii::app()->functions->getDays();?>
<h3><?php echo Yii::t("default","Store Hours")?></h3>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Time Zone")?></label>
<?php 
echo CHtml::dropDownList('merchant_timezone',
Yii::app()->functions->getOption("merchant_timezone",$merchant_id)
,Yii::app()->functions->timezoneList())
?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label">
  <?php echo Yii::t("default","Store days(s) Open:")?>  
  </label>  
  <div class="clear"></div>
  <p class="uk-text-muted"><?php echo Yii::t("default","If days has not been selected then merchant will be set to open")?></p>
  <ul class="uk-list uk-list-striped">
  <?php foreach ($days as $key=>$val):?>
  <li>
  <div class="uk-grid" >
  
    <div class="uk-width-1-6" style="width:5%;">
    <?php echo CHtml::checkBox('stores_open_day[]',
    in_array($key,(array)$stores_open_day)?true:false
    ,array('value'=>$key,'class'=>"icheck"))?>
    </div>
    
    <div class="uk-width-1-6" style="width:12%"><?php echo ucwords($val);?></div>    
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_starts[$key]",
      array_key_exists($key,(array)$stores_open_starts)?timeFormat($stores_open_starts[$key],true):""
      ,array('placeholder'=>Yii::t("default","Start"),'class'=>"timepick"));?>
    </div>
    
    <div class="uk-width-1-6" style="width:5%;"><?php echo Yii::t("default","To")?></div>
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_ends[$key]",
      array_key_exists($key,(array)$stores_open_ends)?timeFormat($stores_open_ends[$key],true):""
      ,array('placeholder'=>Yii::t("default","End"),'class'=>"timepick"));?>
    </div>
    
    <div class="uk-width-1-6" style="width:5%;">
    /
    </div>
        
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_pm_start[$key]",
      array_key_exists($key,(array)$stores_open_pm_start)?timeFormat($stores_open_pm_start[$key],true):""
      ,array('placeholder'=>Yii::t("default","Start"),'class'=>"timepick"));?>
    </div>
    <div class="uk-width-1-6" style="width:5%;"><?php echo Yii::t("default","To")?></div>
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_pm_ends[$key]",
      array_key_exists($key,(array)$stores_open_pm_ends)?timeFormat($stores_open_pm_ends[$key],true):""
      ,array('placeholder'=>Yii::t("default","End"),'class'=>"timepick"));?>
    </div>
    
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_custom_text[$key]",
      array_key_exists($key,(array)$stores_open_custom_text)?$stores_open_custom_text[$key]:""
      ,array('placeholder'=>Yii::t("default","Custom text")));?>
     </div>
   </div>    
  </li>
  <?php endforeach;?>
  </ul>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Accept Pre-orders")?></label>
<?php 
echo CHtml::checkBox('merchant_preorder',
Yii::app()->functions->getOption("merchant_preorder",$merchant_id)==1?true:false
,array(
'class'=>"icheck",
'value'=>1
));
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Close Message")?></label>
<?php 
echo CHtml::textArea('merchant_close_msg',
Yii::app()->functions->getOption("merchant_close_msg",$merchant_id)
,array(
'class'=>"uk-form-width-large"
));
?>
</div>



<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Holidays")?>:</label>

<div class="holiday_list">


<?php if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)):?>
<?php foreach ($m_holiday as $m_h):?>
<div class="holiday_row">
<?php echo CHtml::textField('merchant_holiday[]',$m_h,array(
  'class'=>"j_date_normal small_date"
));?>
<a href="javascript:;" class="remove_holiday"><i class="fa fa-minus-square"></i></a>
</div>
<?php endforeach;?>

<?php else:?>

<div class="holiday_row">
<?php echo CHtml::textField('merchant_holiday[]','',array(
  'class'=>"j_date_normal small_date"
));?>
</div>

<?php endif;?>

</div> <!--holiday_list-->

<a href="javascript:;" class="add_new_holiday"><i class="fa fa-plus-square"></i></a>

</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Holiday Close Message")?></label>
<?php 
echo CHtml::textArea('merchant_close_msg_holiday',
Yii::app()->functions->getOption("merchant_close_msg_holiday",$merchant_id)
,array(
'class'=>"uk-form-width-large"
));
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>