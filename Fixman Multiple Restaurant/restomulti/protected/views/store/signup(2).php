<div class="page">
  <div class="main">   
  <div class="inner">
  <?php echo CHtml::hiddenField('mobile_country_code',Yii::app()->functions->getAdminCountrySet(true));?>

<div class="login-modal-wrap" style="width:100%;">
 <div class="modal-header">
   <h3 class="left"><?php echo Yii::t("default","Login & Signup")?></h3>   
   <div class="clear"></div>
 </div>
 <div class="modal-body">
   
  <div class="section1">
     
   <?php $fb_flag=Yii::app()->functions->getOptionAdmin('fb_flag');?>
   
   <?php if ( $fb_flag==""):?>
   <div class="sigin-fb-wrap" style="padding-left:-1px;">
   <fb:login-button scope="public_profile,email" onlogin="checkLoginState();"><?php echo Yii::t('default',"Sign in with Facebook")?></fb:login-button>
   </div> <!--sigin-fb-wrap-->
   
   <?php if ( yii::app()->functions->getOptionAdmin('google_login_enabled')==2):?>
   <a class="google-login" href="<?php echo websiteUrl()."/store/GoogleLogin"; ?>">
   <i class="fa fa-google-plus"></i> <?php echo t("Sign in with Google")?>
   </a>
   <?php endif;?>
   
   <p class="uk-text-muted"><?php echo Yii::t("default","Or use your email address")?></p>
   <?php endif;?>     
   
   <div class="login-btn-wrap">
     <a href="javascript:;" class="login-link uk-button"><?php echo Yii::t("default","Login")?></a>
     <a href="javascript:;" class="signup-link uk-button" style="margin-right:0px;"><?php echo Yii::t("default","Sign up")?></a>
   </div>
 </div> <!-- section1--> 
  
 <div class="section2"> 
      <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
      
         <?php echo CHtml::hiddenField('action','clientLogin')?>         
         <h3><?php echo Yii::t("default","Log in to your account")?></h3>
         <div class="uk-form-row">
           <?php echo CHtml::textField('username','',
            array('class'=>'uk-width-1-1','placeholder'=>Yii::t("default","Email"),
           'data-validation'=>"required"))?>
         </div>
         <div class="uk-form-row">
         <?php echo CHtml::passwordField('password','',
         array('class'=>'uk-width-1-1','placeholder'=>Yii::t("default","Password"),'data-validation'=>"required"))?>
         </div>
         
         <?php if (getOptionA('captcha_customer_login')==2):?>
         <div class="recaptcha" id="RecaptchaField1"></div>
         <?php endif;?> 
         
         <div class="uk-form-row">
         <input type="submit" value="<?php echo Yii::t("default","Login")?>" class="uk-button uk-width-1-1 uk-button-success">
         </div>
      </form>      
            
      <a href="javascript:;" class="back-link left"><i class="fa fa-angle-left"></i> <?php echo Yii::t("default","Back")?></a>      
      <a href="javascript:;" class="forgot-pass-link right"><?php echo Yii::t("default","Forgot Password")?>?</a>      
      <div class="clear"></div>
      
 </div> <!--section2-->
 
 <div class="section-forgotpass">
    <form id="frm-modal-forgotpass" class="frm-modal-forgotpass uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">
    <?php echo CHtml::hiddenField('action','forgotPassword')?>
     <?php echo CHtml::hiddenField('do-action',$_GET['do-action'])?>     
     <h3><?php echo Yii::t("default","Forgot Password")?></h3>
         
    <div class="uk-form-row">
       <?php echo CHtml::textField('username-email','',
        array('class'=>'uk-width-1-1','placeholder'=>Yii::t("default","Email address"),
       'data-validation'=>"email"))?>
     </div>
         
    <div class="uk-form-row">
      <input type="submit" value="<?php echo Yii::t("default","Retrieve Password")?>" class="uk-button uk-width-1-1 uk-button-success">
    </div>     
     
    </form>
    
    <a href="javascript:;" class="back-link left"><i class="fa fa-angle-left"></i> <?php echo Yii::t("default","Back")?></a>      
    <div style="height:10px;"></div>
        
 </div> <!--section-forgotpass-->
  
 <div class="section3"> 
  <form id="form-signup" class="form-signup uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">
    <?php echo CHtml::hiddenField('action','clientRegistrationModal')?>
    <?php echo CHtml::hiddenField('single_page',2)?>    
    <?php 
    $verification=Yii::app()->functions->getOptionAdmin("website_enabled_mobile_verification");	    
    if ( $verification=="yes"){
        echo CHtml::hiddenField('verification',$verification);
    }
    ?>
    
     <h3><?php echo Yii::t("default","Sign up")?></h3>
     <div class="uk-form-row">
      <?php echo CHtml::textField('first_name','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","First Name"),
       'data-validation'=>"required"
      ))?>
     </div>
     <div class="uk-form-row">
      <?php echo CHtml::textField('last_name','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Last Name"),
       'data-validation'=>"required"
      ))?>
     </div>
     <div class="uk-form-row">
      <?php echo CHtml::textField('contact_phone','',array(
       'class'=>'uk-width-1-1 mobile_inputs',
       'placeholder'=>yii::t("default","Mobile"),
       'data-validation'=>"required"
      ))?>
     </div>
     <div class="uk-form-row">
      <?php echo CHtml::textField('email_address','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>yii::t("default","Email address"),
       'data-validation'=>"email"
      ))?>
     </div>
     
     <?php 
     $FunctionsK=new FunctionsK();
     $FunctionsK->clientRegistrationCustomFields();
     ?>
                                     
     <div class="uk-form-row">
      <?php echo CHtml::passwordField('password','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Password"),
       'data-validation'=>"required"
      ))?>
     </div>
     
     <div class="uk-form-row">
      <?php echo CHtml::passwordField('cpassword','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Confirm Password"),
       'data-validation'=>"required"       
      ))?>      
     </div>
          
     <?php if (getOptionA('captcha_customer_signup')==2):?>
     <div class="recaptcha" id="RecaptchaField2"></div>
     <?php endif;?> 
     
     <p class="uk-text-muted" style="text-align: left;">
        <?php echo Yii::t("default","By creating an account, you agree to receive sms from vendor.")?>
     </p>
     
     
  <?php if ( Yii::app()->functions->getOptionAdmin('website_terms_customer')=="yes"):?>
  <?php 
  $terms_link=Yii::app()->functions->getOptionAdmin('website_terms_customer_url');
  $terms_link=Yii::app()->functions->prettyLink($terms_link);
  ?>
  <div class="uk-form-row">
  <label class="uk-form-label"></label>
  <?php 
  echo CHtml::checkBox('terms_n_condition',false,array(
   'value'=>2,
   'class'=>"",
   'data-validation'=>"required"
  ));
  echo " ". t("I Agree To")." <a href=\"$terms_link\" target=\"_blank\">".t("The Terms & Conditions")."</a>";
  ?>  
  </div>  
  <?php endif;?>
       
     
     <div class="uk-form-row">
     <input type="submit" value="<?php echo Yii::t("default","Create Account") ?>" class="uk-button uk-width-1-1 uk-button-primary">
     </div>
  </form>
  <a href="javascript:;" class="back-link"><i class="fa fa-angle-left"></i> <?php echo Yii::t("default","Back")?></a>
 </div> <!--section3-->
   
 </div> <!--modal-body-->
</div> <!--end login-modal-wrap-->
  
  </div> <!--inner-->
  </div>
</div>  