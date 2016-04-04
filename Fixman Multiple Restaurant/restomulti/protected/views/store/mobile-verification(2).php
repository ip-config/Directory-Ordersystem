<div class="page">
  <div class="main">   
  <div class="inner">
    
  <h3><?php echo t("Your registration is almost complete")?></h3>
  
  <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
  <?php echo CHtml::hiddenField('action','verifyMobileCode')?>         
  <?php echo CHtml::hiddenField('client_id',isset($_GET['id'])?$_GET['id']:'') ?>
  
  <?php if (isset($_GET['checkout'])):?>
  <?php echo CHtml::hiddenField('redirect',Yii::app()->request->baseUrl."/store/paymentOption")?>
  <?php endif;?>
  
  <h4><?php echo t("Please enter you verification code")?></h4>
  
  <div class="uk-form-row">
  <?php 
  echo CHtml::textField('code','',array(
    'class'=>"numeric_only",
    'data-validation'=>"required"
  ));
  ?>
  </div>
  
  <div class="uk-form-row">
 <input type="submit" value="<?php echo t("Submit")?>" class="uk-button uk-button-success">

 <?php echo t("Did not receive your verification code")?>?
 <a href="javascript:;" class="resend-code"><?php echo t("Click here to resend")?></a>
 
 </div>
 
  </form>
  
  </div>
</div>
</div>  