<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms">
<?php echo CHtml::hiddenField('action','paymentgatewaySettings')?>

<?php 
$paymentgateway=Yii::app()->functions->getMerchantListOfPaymentGateway();
?>

<h4><?php echo t("list of enabled payment gateway on merchant")?></h4>
  
  <div class="uk-form-row">  
  <ul>
  
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('cod',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>"cod"
   ));
   echo "<span>".t("Cash On delivery")."</span>";
   ?>
   </li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('ocr',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>"ocr"
   ));
   echo "<span>".t("Offline Credit Card Payment")."</span>";
   ?>
   </li>
  
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('paypal',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>"paypal"
   ));
   echo "<span>".t("paypal")."</span>";
   ?>
   </li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('stripe',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'stripe'
   ));
   echo "<span>".t("stripe")."</span>";
   ?></li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('mercadopago',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'mercadopago'
   ));
   echo "<span>".t("mercapado")."</span>";
   ?></li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('ide',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'ide'
   ));
   echo "<span>".t("sisow")."</span>";
   ?></li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('payu',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'payu'
   ));
   echo "<span>".t("payumoney")."</span>";
   ?></li>
    
      <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('pys',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'pys'
   ));
   echo "<span>".t("paysera")."</span>";
   ?></li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('pyr',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'pyr'
   ));
   echo "<span>".t("Pay On Delivery")."</span>";
   ?></li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('bcy',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'bcy'
   ));
   echo "<span>".t("Barclay")."</span>";
   ?></li>
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('epy',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'epy'
   ));
   echo "<span>".t("EpayBg")."</span>";
   ?></li>   
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('atz',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'atz'
   ));
   echo "<span>".t("Authorize.net")."</span>";
   ?></li>   
   
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array('obd',(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'obd'
   ));
   echo "<span>".t("Offline Bank Deposit")."</span>";
   ?></li>   
   
  </ul>
</div>




<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>