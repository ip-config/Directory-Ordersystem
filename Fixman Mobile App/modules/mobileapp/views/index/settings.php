
<div class="pad10">

 <?php echo CHtml::beginForm(); ?> 
 <?php 
 
 $ios_push_dev_cer=getOptionA('ios_push_dev_cer');
 $ios_push_prod_cer=getOptionA('ios_push_prod_cer');
 
 echo CHtml::hiddenField('mobile_default_image_not_available',
 getOptionA('mobile_default_image_not_available')
 ,array(
   'class'=>'mobile_default_image_not_available'
 ));
 
 echo CHtml::hiddenField('ios_push_dev_cer',$ios_push_dev_cer,array(
  'class'=>'ios_push_dev_cer'
 ));
 echo CHtml::hiddenField('ios_push_prod_cer',$ios_push_prod_cer,array(
  'class'=>'ios_push_prod_cer'
 ));
 ?>
 
 <div class="form-group" id="chosen-field">
  <label ><?php echo t("Your mobile API URL")?></label><br/>
  <p class="bg-success inlineblock"><?php echo websiteUrl()."/mobileapp/api" ?></p>
  <p><?php echo t("Set this url on your mobile app config files on")?> www/js/config.js</p>
 </div>
 
 <div class="form-group" id="chosen-field">
    <label ><?php echo t("Location")?></label>
    <?php echo CHtml::dropDownList('mobile_country_list[]',
    $mobile_country_list,
   (array)$country_list,
   array(
    'class'=>'form-control chosen',
    'multiple'=>true
  ))?>  
  </div>
      
  
   <div class="form-group">
    <label ><?php echo t("Default Image")?></label>
    <a id="upload-file" href="javascript:;" class="btn btn-default"><?php echo t("Browse")?></a>
    <?php if (!empty($default_image_url)):?>
    <img src="<?php echo $default_image_url?>" alt="" class="my-thumb img-thumbnail">       
    <?php endif;?>
  </div>
  
  
  <div class="form-group">
    <label ><?php echo t("Android Push API Key")?></label>
    <?php 
    echo CHtml::textField('mobile_android_push_key',getOptionA('mobile_android_push_key'),array(
      'class'=>'form-control',
    ));
    ?>
  </div>
  
  <hr/>
  
  
  <p style="font-size:12px;color:red;">
  <?php echo t("Note: for ios push notification to work make sure your server port 2195 is open")?>.
  </p>
  
 <div class="form-group">
    <label ><?php echo t("IOS Push Mode")?></label>
    <?php 
    echo CHtml::dropDownList('ios_push_mode',getOptionA('ios_push_mode'),array(
      "development"=>t("Development"),
      "production"=>t("Production")
    ),array(
      'class'=>"form-control"
    ));
    ?>
  </div>
      
  <div class="form-group">
    <label ><?php echo t("IOS Push Certificate PassPhrase")?></label>
    <?php 
    echo CHtml::textField('ios_passphrase',getOptionA('ios_passphrase'),array(
      'class'=>'form-control',
    ));
    ?>
  </div>
  
  <div class="form-group">
    <label ><?php echo t("IOS Push Development Certificate")?></label>
    <a id="upload-certificate-dev" href="javascript:;" class="btn btn-default"><?php echo t("Browse")?></a>        
    <?php if (!empty($ios_push_dev_cer)):?>
    <span><?php echo $ios_push_dev_cer?>...</span>
    <?php endif;?>
  </div>
  
  <div class="form-group">
    <label ><?php echo t("IOS Push Production Certificate")?></label>
    <a id="upload-certificate-prod" href="javascript:;" class="btn btn-default"><?php echo t("Browse")?></a> 
    <?php if (!empty($ios_push_prod_cer)):?>
    <span><?php echo $ios_push_prod_cer?>...</span>
    <?php endif;?>
  </div>
  
  <hr/>
    
  <div class="form-group">
    <label ><?php echo t("Order Push Template Title")?></label>
    <?php 
    echo CHtml::textField('mobile_push_order_title',getOptionA('mobile_push_order_title'),array(
      'class'=>'form-control',
      'maxlength'=>200
    ));
    ?>
  </div>
  
  <p class="bg-success inlineblock"><?php echo t("Available tags")?>: {order_id} {order_status}</p>
  
  <div class="form-group">
    <label ><?php echo t("Order Push Template Message")?></label>
    <?php 
    echo CHtml::textArea('mobile_push_order_message',getOptionA('mobile_push_order_message'),array(
      'class'=>'form-control',      
    ));
    ?>
  </div>
  
  <p class="bg-success inlineblock"><?php echo t("Available tags")?>: {order_id} {order_status}</p>
  
  <hr/>
 
  <div class="form-group">  
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
  
 <?php echo CHtml::endForm(); ?>

</div>