
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','smsSettings')?>

<h3><?php echo t("Merchant SMS Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled SMS on merchant")?>?</label>
  <?php 
  echo CHtml::checkBox('mechant_sms_enabled',
  Yii::app()->functions->getOptionAdmin('mechant_sms_enabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Purchase SMS Credit")?>?</label>
  <?php 
  echo CHtml::checkBox('mechant_sms_purchase_disabled',
  Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use admin SMS credits to send SMS")?>?</label>
  <?php 
  echo CHtml::checkBox('mechant_sms_purchase_disabled',
  Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>


<h3><?php echo t("SMS Gateway to use when sending SMS")?></h3>
<div class="uk-form-row">  
<ul>
<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="twilio"?true:false
,array(
'class'=>"icheck",
'value'=>"twilio"
));
echo "<span>".t("use twilio")."</span>";
?>
</li>
<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="nexmo"?true:false
,array(
'class'=>"icheck",
'value'=>'nexmo'
));
echo "<span>".t("use Nexmo")."</span>";
?></li>


<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="clickatell"?true:false
,array(
'class'=>"icheck",
'value'=>'clickatell'
));
echo "<span>".t("use Clickatell")."</span>";
?></li>

<!--<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="private"?true:false
,array(
'class'=>"icheck",
'value'=>'private'
));
echo "<span>".t("use Private SMS")."</span>";
?></li>-->

<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="bhashsms"?true:false
,array(
'class'=>"icheck",
'value'=>'bhashsms'
));
echo "<span>".t("use BHASHSMS")."</span>";
?></li>

<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="smsglobal"?true:false
,array(
'class'=>"icheck",
'value'=>'smsglobal'
));
echo "<span>".t("use SMSGlobal")."</span>";
?></li>


<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="swift"?true:false
,array(
'class'=>"icheck",
'value'=>'swift'
));
echo "<span>".t("use Swift SMS gateway")."</span>";
?></li>


<li><?php 
echo CHtml::radioButton('sms_provider',
Yii::app()->functions->getOptionAdmin('sms_provider')=="solutionsinfini"?true:false
,array(
'class'=>"icheck",
'value'=>'solutionsinfini'
));
echo "<span>".t("use Solutionsinfini")."</span>";
?></li>


</ul>
</div>

<hr/>

<div class="twillio-logo"></div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('sms_sender_id',
  Yii::app()->functions->getOptionAdmin('sms_sender_id')
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account SID")?></label>
  <?php 
  echo CHtml::textField('sms_account_id',
  Yii::app()->functions->getOptionAdmin('sms_account_id')
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","AUTH Token")?></label>
  <?php 
  echo CHtml::textField('sms_token',
  Yii::app()->functions->getOptionAdmin('sms_token')
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.twilio.com/">https://www.twilio.com/</a>

<hr/>

<div class="nexmo-logo"></div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php 
  echo CHtml::textField('nexmo_sender_id',
  Yii::app()->functions->getOptionAdmin('nexmo_sender_id')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Key")?></label>
  <?php 
  echo CHtml::textField('nexmo_key',
  Yii::app()->functions->getOptionAdmin('nexmo_key')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Secret")?></label>
  <?php 
  echo CHtml::textField('nexmo_secret',
  Yii::app()->functions->getOptionAdmin('nexmo_secret')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('nexmo_use_curl',
  Yii::app()->functions->getOptionAdmin('nexmo_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use Unicode")?>?</label>
  <?php 
  echo CHtml::checkBox('nexmo_use_unicode',
  Yii::app()->functions->getOptionAdmin('nexmo_use_unicode')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.nexmo.com/">https://www.nexmo.com/</a>

<hr/>

<div class="clickatel-logo"></div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","User")?></label>
  <?php 
  echo CHtml::textField('clickatel_user',
  Yii::app()->functions->getOptionAdmin('clickatel_user')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('clickatel_password',
  Yii::app()->functions->getOptionAdmin('clickatel_password')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php 
  echo CHtml::textField('clickatel_sender',
  Yii::app()->functions->getOptionAdmin('clickatel_sender')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API ID")?></label>
  <?php 
  echo CHtml::textField('clickatel_api_id',
  Yii::app()->functions->getOptionAdmin('clickatel_api_id')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('clickatel_use_curl',
  Yii::app()->functions->getOptionAdmin('clickatel_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use Unicode")?>?</label>
  <?php 
  echo CHtml::checkBox('clickatel_use_unicode',
  Yii::app()->functions->getOptionAdmin('clickatel_use_unicode')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.clickatell.com/">https://www.clickatell.com/</a>

<!--<h3><?php echo t("Private SMS")?></h3>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Username")?></label>
  <?php 
  echo CHtml::textField('privatesms_username',
  Yii::app()->functions->getOptionAdmin('privatesms_username')
  ,array(
    'value'=>1,
    'class'=>"uk-form-width-large"    
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('privatesms_password',
  Yii::app()->functions->getOptionAdmin('privatesms_password')
  ,array(
    'value'=>1,
    'class'=>"uk-form-width-large"    
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php 
  echo CHtml::textField('privatesms_sender',
  Yii::app()->functions->getOptionAdmin('privatesms_sender')
  ,array(
    'value'=>1,
    'class'=>"uk-form-width-large"    
  ))
  ?> 
</div>-->

<hr/>

<h3><?php echo t("BHASHSMS")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","User")?></label>
  <?php 
  echo CHtml::textField('bhashsms_user',
  Yii::app()->functions->getOptionAdmin('bhashsms_user')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('bhashsms_pass',
  Yii::app()->functions->getOptionAdmin('bhashsms_pass')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('bhashsms_senderid',
  Yii::app()->functions->getOptionAdmin('bhashsms_senderid')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SMS Type")?></label>
  <?php 
  echo CHtml::dropDownList('bhashsms_smstype',Yii::app()->functions->getOptionAdmin('bhashsms_smstype'),array(
    'normal'=>t("normal"),
    'flash'=>t("flash"),
    'unicode'=>t("unicode"),
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Priority")?></label>
  <?php 
  echo CHtml::dropDownList('bhashsms_priority',Yii::app()->functions->getOptionAdmin('bhashsms_priority'),array(
    'ndnd'=>t("ndnd"),
    'dnd'=>t("dnd")    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('bhashsms_use_curl',
  Yii::app()->functions->getOptionAdmin('bhashsms_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<hr/>
<h3><?php echo t("SMSGlobal")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('smsglobal_senderid',
  Yii::app()->functions->getOptionAdmin('smsglobal_senderid')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API username")?></label>
  <?php 
  echo CHtml::textField('smsglobal_username',
  Yii::app()->functions->getOptionAdmin('smsglobal_username')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API password")?></label>
  <?php 
  echo CHtml::textField('smsglobal_password',
  Yii::app()->functions->getOptionAdmin('smsglobal_password')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.smsglobal.com/">https://www.smsglobal.com/</a>


<hr>

<h3><?php echo t("Swift SMS Gateway")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account Key")?></label>
  <?php 
  echo CHtml::textField('swift_accountkey',
  Yii::app()->functions->getOptionAdmin('swift_accountkey')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('swift_usecurl',
  Yii::app()->functions->getOptionAdmin('swift_usecurl')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="http://smsgateway.ca">http://smsgateway.ca</a>

<hr>


<h3><?php echo t("Solutionsinfini")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API Key")?></label>
  <?php 
  echo CHtml::textField('solutionsinfini_apikey',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_apikey')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('solutionsinfini_sender',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_sender')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('solutionsinfini_usecurl',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_usecurl')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use Unicode")?>?</label>
  <?php 
  echo CHtml::checkBox('solutionsinfini_useunicode',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_useunicode')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="http://solutionsinfini.com/">http://solutionsinfini.com/</a>

<hr>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
<a href="javascript:;" class="uk-button test-sms"><?php echo t("Test SMS")?></a>
</div>

</form>