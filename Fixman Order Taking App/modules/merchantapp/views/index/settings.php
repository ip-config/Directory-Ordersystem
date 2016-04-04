
<?php echo CHtml::beginForm('','post',array(
'class'=>"form-horizontal"
)); ?> 

<?php 
$ios_push_dev_cer=getOptionA('mt_ios_push_dev_cer');
$ios_push_prod_cer=getOptionA('mt_ios_push_prod_cer');

echo CHtml::hiddenField('mt_ios_push_dev_cer',$ios_push_dev_cer,array(
'class'=>'mt_ios_push_dev_cer'
));
echo CHtml::hiddenField('mt_ios_push_prod_cer',$ios_push_prod_cer,array(
'class'=>'mt_ios_push_dev_cer'
));
?>

<div style="padding-left:20px;">
 <div class="form-group" id="chosen-field">
  <label ><b><?php echo t("Your mobile API URL")?></b></label><br/>
  <p class="bg-success inlineblock"><?php echo websiteUrl()."/merchantapp/api" ?></p>
  <p><?php echo t("Set this url on your merchant app config files on")?> www/js/config.js</p>
 </div>
 </div>

<!--
<div class="form-group">
  <label class="col-sm-3 control-label"><?php echo t("Enabled Points System")?>?</label>
  <div class="col-sm-8">
  <?php 
  echo CHtml::checkBox('points_enabled',
  getOptionA('points_enabled')==1?true:false
  ,array(
    'class'=>"",
    'value'=>1
  ));
  ?>
  </div>
</div>-->

<h4><?php echo t("Android PlatForm")?></h4>

<div class="form-group ">
  <label class="col-sm-3 control-label"><?php echo t("Android Push API Key")?></label>
  <div class="col-sm-8">
    <?php 
    echo CHtml::textField('merchant_android_api_key',getOptionA('merchant_android_api_key'),array(
      'class'=>'form-control'      
    ));
    ?>   
  </div>
</div>

<hr/>


<h4><?php echo t("IOS PlatForm")?></h4>

<p style="font-size:12px;color:red;">
<?php echo t("Note: for ios push notification to work make sure your server port 2195 is open")?>.
</p>

 <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo t("IOS Push Mode")?></label>
    <div class="col-sm-8">
    <?php 
    echo CHtml::dropDownList('mt_ios_push_mode',getOptionA('mt_ios_push_mode'),array(
      "development"=>t("Development"),
      "production"=>t("Production")
    ),array(
      'class'=>"form-control"
    ));
    ?>
    </div>
  </div>
      
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo t("IOS Push Certificate PassPhrase")?></label>
    <div class="col-sm-8">
    <?php 
    echo CHtml::textField('mt_ios_passphrase',getOptionA('mt_ios_passphrase'),array(
      'class'=>'form-control',
    ));
    ?>
    </div>
  </div>
  
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo t("IOS Push Development Certificate")?></label>
    <div class="col-sm-8">
    <a id="upload-certificate-dev" href="javascript:;" class="btn btn-default"><?php echo t("Browse")?></a>        
    <?php if (!empty($ios_push_dev_cer)):?>
    <span><?php echo $ios_push_dev_cer?></span>
    <?php endif;?>
    </div>
  </div>
  
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo t("IOS Push Production Certificate")?></label>
    <div class="col-sm-8">
    <a id="upload-certificate-prod" href="javascript:;" class="btn btn-default"><?php echo t("Browse")?></a> 
    <?php if (!empty($ios_push_prod_cer)):?>
    <span><?php echo $ios_push_prod_cer?></span>
    <?php endif;?>
    </div>
  </div>
  
  <hr/>

<ul class="nav nav-tabs" role="tablist">

<li role="presentation" class="active"><a href="#pushtpl" aria-controls="push" role="tab" data-toggle="tab"><?php 
echo t("Push Template")?></a></li>

<li role="presentation"><a href="#emailtpl" aria-controls="email" role="tab" data-toggle="tab">
<?php echo t("Email Template")?></a></li>

<li role="presentation"><a href="#smstpl" aria-controls="sms" role="tab" data-toggle="tab"><?php 
echo t("SMS Template")?></a></li>

<li role="presentation"><a href="#bookingemailtpl" aria-controls="email" role="tab" data-toggle="tab">
<?php echo t("Booking Email Template")?></a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content">


<div role="tabpanel" class="tab-pane active" id="pushtpl">
  <div class="panel-body">
     <?php require_once('push-tpl.php')?>
  </div>
</div>

<div role="tabpanel" class="tab-pane" id="emailtpl">
  <div class="panel-body">
     <?php require_once('email-tpl.php')?>
  </div>
</div>

<div role="tabpanel" class="tab-pane" id="smstpl">
  <div class="panel-body">
     <?php require_once('sms-tpl.php')?>
  </div>
</div>

<div role="tabpanel" class="tab-pane" id="bookingemailtpl">
  <div class="panel-body">
     <?php require_once('booking-email-tpl.php')?>
  </div>
</div>


</div>


<div style="height:30px;"></div>

<div class="clear"></div>

 <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
  <?php
echo CHtml::ajaxSubmitButton(
	'Save Settings',
	array('ajax/savesettings'),
	array(
		'type'=>'POST',
		'dataType'=>'json',
		'beforeSend'=>'js:function(){
		                 busy(true); 	
		                 $("#save-settings").val("Processing");
		                 $("#save-settings").css({ "pointer-events" : "none" });	                 	                 
		              }
		',
		'complete'=>'js:function(){
		                 busy(false); 		                 
		                 $("#save-settings").val("Save Settings");
		                 $("#save-settings").css({ "pointer-events" : "auto" });	                 	                 
		              }',
		'success'=>'js:function(data){	
		               if(data.code==1){		               
		                 nAlert(data.msg,"success");
		               } else {
		                  nAlert(data.msg,"warning");
		               }
		            }
		'
	),array(
	  'class'=>'btn btn-primary',
	  'id'=>'save-settings'
	)
);
?>
    </div>
  </div>


<?php echo CHtml::endForm(); ?>