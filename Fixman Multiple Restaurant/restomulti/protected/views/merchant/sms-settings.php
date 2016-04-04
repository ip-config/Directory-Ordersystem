<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$sms_enabled_alert=Yii::app()->functions->getOption("sms_enabled_alert",$merchant_id);
$sms_notify_number=Yii::app()->functions->getOption("sms_notify_number",$merchant_id);
$sms_alert_message=Yii::app()->functions->getOption("sms_alert_message",$merchant_id);
$sms_alert_customer=Yii::app()->functions->getOption("sms_alert_customer",$merchant_id);
$merchant_info=Yii::app()->functions->getMerchantInfo();
?>
<?php echo CHtml::hiddenField('country_code',$merchant_info[0]->country_code)?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','SMSAlertSettings')?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled SMS alert")?>?</label>
  <?php 
  echo CHtml::checkBox('sms_enabled_alert',
  $sms_enabled_alert==1?true:false
  ,array('value'=>1,'class'=>"icheck"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Notify Mobile Number")?></label>
  <?php 
  echo CHtml::textField('sms_notify_number',$sms_notify_number,array(
    'class'=>"uk-form-width-large "
  ));
  //mobile_inputs
  ?>
</div>


<p class="uk-text-muted"><?php echo Yii::t("default","Mobile number that will receive notification when there is a new order. multiple numbers must be separated by comma. Mobile number include country prefix eg. +1 for USA")?></p>


<div class="uk-form-row">
  <label class="uk-form-label">
  <?php echo Yii::t("default","SMS Notification Message")?>
  <?php echo t("to merchant")?>
  </label>
  <?php 
  echo CHtml::textArea('sms_alert_message',$sms_alert_message,array(
    'class'=>"uk-form-width-large",
    'style'=>"height:150px"
  ))
  ?>
</div>
<div class="spacer"></div>


<!--
<p class="uk-text-muted" style="margin:0;padding:0;">
<?php echo Yii::t("default","Available Tags {receipt} = full details or order")?>
</p>
<p class="uk-text-muted" style="margin:0;padding:0;">
</p>-->

<ul style="margin:0;padding:0;">
<?php echo Yii::t("default","Available Tags")?>:
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{customer-name} = client name")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{receipt} = full details or order")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{orderno} = Order number")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{customername} = Customer name")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{customermobile} = Customer mobile")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{customeraddress} = Customer address")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{amount} = total amount ordered")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{website-ddress} = Website Address")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{payment-type}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{transaction-type}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{delivery-instruction}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{delivery-date}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{delivery-time}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{order-change}")?></li>
</ul>

<div class="spacer"></div>



<div class="uk-form-row">
  <label class="uk-form-label">
  <?php echo Yii::t("default","SMS Notification Message")?>
  <?php echo t("to customer")?>
  <p class="uk-text-small">(<?php echo t("leave empty to if you don't want to send sms to customer")?>)</p>
  </label>
  <?php 
  echo CHtml::textArea('sms_alert_customer',$sms_alert_customer,array(
    'class'=>"uk-form-width-large",
    'style'=>"height:150px"
  ))
  ?>
</div>
<div class="spacer"></div>

<ul style="margin:0;padding:0;">
<?php echo Yii::t("default","Available Tags")?>:
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{customer-name} = Customer name")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{orderno} = Order number")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{merchantname} = Merchant name")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{merchantphone} = Merchant phone")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{website-address} = Website Address")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{payment-type}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{transaction-type}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{delivery-instruction}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{delivery-date}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{delivery-time}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{order-change}")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{merchant-address}")?></li>
</ul>


<div class="spacer"></div>

<div class="uk-form-row">
  <label class="uk-form-label">
  <?php echo Yii::t("default","SMS Notification Message When Order Status has change")?>  
  <p class="uk-text-small">(<?php echo t("leave empty to if you don't want to send sms")?>)</p>
  </label>
  <?php 
  echo CHtml::textArea('sms_alert_change_status',
  Yii::app()->functions->getOption("sms_alert_change_status",$merchant_id)
  ,array(
    'class'=>"uk-form-width-large",
    'style'=>"height:150px"
  ))
  ?>
</div>

<ul style="margin:0;padding:0;">
<?php echo Yii::t("default","Available Tags")?>:
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{orderno} = Order number")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{customer-name} = Customer name")?></li>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{orderno} = Order number")?></li></ul>
<li style="list-style:none;" class="uk-text-muted"><?php echo t("{order-status} = Order Status")?></li></ul>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>