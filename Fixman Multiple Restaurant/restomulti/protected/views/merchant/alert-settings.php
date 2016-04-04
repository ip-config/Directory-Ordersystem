<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$merchant_notify_email=Yii::app()->functions->getOption("merchant_notify_email",$merchant_id);
$enabled_alert_notification=Yii::app()->functions->getOption("enabled_alert_notification",$merchant_id);
$enabled_alert_sound=Yii::app()->functions->getOption("enabled_alert_sound",$merchant_id);
?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','AlertSettings')?>

<h3><?php echo Yii::t("default","Enabled Alert Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Email Alert Notification")?>?</label>
  <?php 
  echo CHtml::checkBox('enabled_alert_notification',
  $enabled_alert_notification==1?true:false
  ,array('value'=>1,'class'=>"icheck"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email address")?></label>
  <?php 
  echo CHtml::textField('merchant_notify_email',$merchant_notify_email,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
  <p class="uk-text-muted"><?php echo Yii::t("default","Email address of the person who will receive if there is new order. Multiple email must be separated by comma.")?></p>
</div>
  
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled sounds alert")?>?</label>
  <?php 
  echo CHtml::checkBox('enabled_alert_sound',
  $enabled_alert_sound==1?true:false
  ,array('value'=>1,'class'=>"icheck"))
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","Play alert sounds when there is new order")?></p>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>