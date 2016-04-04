<?php
$s=$_SESSION;
$continue=false;

$merchant_address='';		
if ($merchant_info=Yii::app()->functions->getMerchant($s['kr_merchant_id'])){	
	$merchant_address=$merchant_info['street']." ".$merchant_info['city']." ".$merchant_info['state'];
	$merchant_address.=" "	. $merchant_info['post_code'];
}

if (isset($is_guest_checkout)){
	$continue=true;	
} else {	
	$client_info='';
	$client_info = Yii::app()->functions->getClientInfo(Yii::app()->functions->getClientId());
	if (isset($s['kr_search_address'])){	
		$temp=explode(",",$s['kr_search_address']);		
		if (is_array($temp) && count($temp)>=2){
			$street=isset($temp[0])?$temp[0]:'';
			$city=isset($temp[1])?$temp[1]:'';
			$state=isset($temp[2])?$temp[2]:'';
		}
		if ( isset($client_info['street'])){
			if ( empty($client_info['street']) ){
				$client_info['street']=$street;
			}
		}
		if ( isset($client_info['city'])){
			if ( empty($client_info['city']) ){
				$client_info['city']=$city;
			}
		}
		if ( isset($client_info['state'])){
			if ( empty($client_info['state']) ){
				$client_info['state']=$state;
			}
		}
	}	
	
	if (isset($s['kr_merchant_id']) && Yii::app()->functions->isClientLogin() && is_array($merchant_info) ){
		$continue=true;
	}
}
echo CHtml::hiddenField('mobile_country_code',Yii::app()->functions->getAdminCountrySet(true));

echo CHtml::hiddenField('admin_currency_set',getCurrencyCode());

echo CHtml::hiddenField('admin_currency_position',
Yii::app()->functions->getOptionAdmin("admin_currency_position"));
?>

<div class="page-right-sidebar payment-option-page">
  <div class="main">
  <div class="inner">
<?php //if (isset($s['kr_merchant_id']) && Yii::app()->functions->isClientLogin() && is_array($merchant_info) ):?>
<?php if ( $continue==TRUE):?>
   <?php $merchant_id=$s['kr_merchant_id'];?>
   <?php echo CHtml::hiddenField('merchant_id',$merchant_id);?>
   
   
   <div class="grid">
     <div class="grid-1 left">        
     
        <form id="frm-delivery" class="frm-delivery uk-form" method="POST" onsubmit="return false;">
        
        <?php echo CHtml::hiddenField('action','placeOrder')?>
        <?php echo CHtml::hiddenField('country_code',$merchant_info['country_code'])?>
        <?php echo CHtml::hiddenField('currentController','store')?>
        <?php 
        echo CHtml::hiddenField('delivery_type',$s['kr_delivery_options']['delivery_type']);
        ?>
        <?php if (isset($is_guest_checkout)):?>
        <?php echo CHtml::hiddenField('is_guest_checkout',2);?>
        <?php endif;?>
              
        <?php echo CHtml::hiddenField('cart_tip_percentage','')?>  
        <?php echo CHtml::hiddenField('cart_tip_value','')?>  
                
        <?php echo CHtml::hiddenField('client_order_sms_code')?>
        <?php echo CHtml::hiddenField('client_order_session')?>
        
        <?php 
        /*if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
        	echo CHtml::hiddenField('admin_paypal_fee',
	        Yii::app()->functions->getOption('merchant_paypal_fee',$merchant_id));       
        } else {
	        echo CHtml::hiddenField('card_fee',
	        Yii::app()->functions->getOption('merchant_paypal_fee',$merchant_id));       
        }*/
        ?>
        
        <?php if ( $s['kr_delivery_options']['delivery_type']=="pickup"):?> 
        
        <h3><?php echo Yii::t("default","Pickup information")?></h3>
        <p class="uk-text-bold"><?php echo $merchant_address;?></p>
        
        
         <?php if (!isset($is_guest_checkout)):?> 
          <?php if ( getOptionA('mechant_sms_enabled')==""):?>
          <?php if ( getOption($merchant_id,'order_verification')==2):?>
          <?php $sms_balance=Yii::app()->functions->getMerchantSMSCredit($merchant_id);?>
          <?php if ( $sms_balance>=1):?>
                    
            <div class="uk-form-row">
              <?php echo CHtml::textField('contact_phone',isIsset($client_info['contact_phone']),array(
               'class'=>'uk-width-1-1 mobile_inputs',
               'placeholder'=>Yii::t("default","Mobile Number"),
               'data-validation'=>"required"  
              ))?>
             </div>             
          
		  <?php endif;?>
          <?php endif;?>
          <?php endif;?>
          <?php endif;?>
          
        
          <?php if (isset($is_guest_checkout)):?> <!--PICKUP GUEST-->
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
              <?php echo CHtml::textField('street',isIsset($client_info['street']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Street"),
               'data-validation'=>"required"
              ))?>
             </div>
             <div class="uk-form-row">
              <?php echo CHtml::textField('city',isIsset($client_info['city']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","City"),
               'data-validation'=>"required"
              ))?>
             </div>
             <div class="uk-form-row">
              <?php echo CHtml::textField('state',isIsset($client_info['state']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","State"),
               'data-validation'=>"required"
              ))?>
             </div>
             <div class="uk-form-row">
              <?php echo CHtml::textField('zipcode',isIsset($client_info['zipcode']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Zip code")
              ))?>
             </div>             
             
                          
             <div class="uk-form-row">
              <?php echo CHtml::textField('location_name',isIsset($client_info['location_name']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Apartment suite, unit number, or company name"),
               //'data-validation'=>"required"  
              ))?>
             </div>             
             <div class="uk-form-row">
              <?php echo CHtml::textField('contact_phone',isIsset($client_info['contact_phone']),array(
               'class'=>'uk-width-1-1 mobile_inputs',
               'placeholder'=>Yii::t("default","Mobile Number"),
               'data-validation'=>"required"  
              ))?>
             </div>             
             <div class="uk-form-row">
              <?php echo CHtml::textField('delivery_instruction','',array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Delivery instructions")   
              ))?>
             </div>                                                                    
            
              <div class="uk-form-row">
              <?php echo CHtml::textField('email_address','',array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Email address"),
               //'data-validation'=>"required"
              ))?>
             </div>
                           
	        <div  style="height:25px;"></div>
	        <div class="uk-panel uk-panel-box">                                                              
	        <h3><?php echo t("Create Account")?> <span class="uk-text-muted uk-text-small">***<?php echo t("Optional")?></span></h3>                
	             
	             <div class="uk-form-row">
	              <?php echo CHtml::passwordField('password','',array(
	               'class'=>'uk-width-1-1',
	               'placeholder'=>Yii::t("default","Password"),
	               //'data-validation'=>"required"
	              ))?>
	             </div>
	        
	        </div> <!--uk-panel-->         
           <?php endif;?>  <!--PICKUP GUEST-->
         
        
        <?php else :?>
        
        <h3><?php echo Yii::t("default","Delivery information")?></h3>
        
        <p>
        <?php echo ucwords($merchant_info['restaurant_name'])?> <?php echo Yii::t("default","Restaurant")?> 
        <?php echo "<span class='uk-text-bold'>".Yii::t("default",ucwords($s['kr_delivery_options']['delivery_type'])) . "</span> ";
        if ($s['kr_delivery_options']['delivery_asap']==1){
        	$s['kr_delivery_options']['delivery_date']." ".Yii::t("default","ASAP");
        } else {
          echo '<span class="uk-text-bold">'.date("M d Y",strtotime($s['kr_delivery_options']['delivery_date'])).
          " ".t("at"). " ". $s['kr_delivery_options']['delivery_time']."</span> ".t("to");
        }
        ?>
        </p>
        
        <div class="uk-panel uk-panel-box">                                                              
        
           <?php if (isset($is_guest_checkout)):?>
           
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
            <div class="spacer"></div>
           <?php endif;?>
        
           <?php if ( Yii::app()->functions->getOptionAdmin('website_enabled_map_address')==2 ):?>
           <?php Widgets::AddressByMap()?>
           <?php endif;?>
           
           <?php $address_book=Yii::app()->functions->showAddressBook();?>
           <?php if ( $address_book):?>
              <div class="uk-form-row address_book_wrap">
               <?php 
               $address_list=Yii::app()->functions->addressBook(Yii::app()->functions->getClientId());
               echo CHtml::dropDownList('address_book_id',$address_book['id'],
               (array)$address_list,array(
                  'class'=>"uk-width-1-1"
               ));
               ?>
               <a href="javascript:;" class="edit_address_book"><i class="fa fa-pencil"></i> <?php echo t("Edit")?></a>
               </div>               
               <div class="spacer"></div>
           <?php endif;?>
           
           <div class="address-block" style="margin-bottom:15px;">
            <div class="uk-form-row">
              <?php echo CHtml::textField('street',isIsset($client_info['street']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Street"),
               'data-validation'=>"required"
              ))?>
             </div>
             <div class="uk-form-row">
              <?php echo CHtml::textField('city',isIsset($client_info['city']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","City"),
               'data-validation'=>"required"
              ))?>
             </div>
             <div class="uk-form-row">
              <?php echo CHtml::textField('state',isIsset($client_info['state']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","State"),
               'data-validation'=>"required"
              ))?>
             </div>
             <div class="uk-form-row">
              <?php echo CHtml::textField('zipcode',isIsset($client_info['zipcode']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Zip code")
              ))?>
             </div>             
             
                          
             <div class="uk-form-row">
              <?php echo CHtml::textField('location_name',isIsset($client_info['location_name']),array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Apartment suite, unit number, or company name"),
               //'data-validation'=>"required"  
              ))?>
             </div>       
           </div> 
                   
             <div class="uk-form-row">
              <?php echo CHtml::textField('contact_phone',isIsset($client_info['contact_phone']),array(
               'class'=>'uk-width-1-1 mobile_inputs',
               'placeholder'=>Yii::t("default","Mobile Number"),
               'data-validation'=>"required"  
              ))?>
             </div>             
             <div class="uk-form-row">
              <?php echo CHtml::textField('delivery_instruction','',array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Delivery instructions")   
              ))?>
             </div>             

                          
             <div class="uk-form-row saved_address_block">
              <?php
              echo CHtml::checkBox('saved_address',false,array('class'=>"icheck",'value'=>2));
              echo " ".t("Save to my address book");
              ?>
             </div>                                                                    
             
             
            <?php if (isset($is_guest_checkout)):?>
            <div class="uk-form-row">
              <?php echo CHtml::textField('email_address','',array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Email address"),
               //'data-validation'=>"required"
              ))?>
             </div>
             
             <?php if (getOptionA('captcha_customer_signup')==2):?>
             <div class="recaptcha" id="RecaptchaField2"></div>
             <?php endif;?> 
             
             <?php endif;?>
                                                  
        
        </div> <!--uk-panel--> 
        
        
        <?php if (isset($is_guest_checkout)):?>
        <div  style="height:25px;"></div>
        <div class="uk-panel uk-panel-box">                                                              
        <h3><?php echo t("Create Account")?> <span class="uk-text-muted uk-text-small">***<?php echo t("Optional")?></span></h3>                
             
             <div class="uk-form-row">
              <?php echo CHtml::passwordField('password','',array(
               'class'=>'uk-width-1-1',
               'placeholder'=>Yii::t("default","Password"),
               //'data-validation'=>"required"
              ))?>
             </div>
        
        </div> <!--uk-panel--> 
        <?php endif;?>
        
        <?php endif;?>

        <?php Widgets::merchantPaymentList($merchant_id);?>       
        
        
        <!--tips section-->
        <?php if ( Yii::app()->functions->getOption("merchant_enabled_tip",$merchant_id)==2):?>
        <?php 
        $merchant_tip_default=Yii::app()->functions->getOption("merchant_tip_default",$merchant_id);
        if ( !empty($merchant_tip_default)){
        	echo CHtml::hiddenField('default_tip',$merchant_tip_default);
        }        
        $FunctionsK=new FunctionsK();
        $tips=$FunctionsK->tipsList();        
        ?>
        <h3><?php echo t("Tip Amount")?> (<span class="tip_percentage">0%</span>)</h3>        
        <div class="uk-panel uk-panel-box">
         <ul class="tip-wrapper">
           <?php foreach ($tips as $tip_key=>$tip_val):?>           
           <li>
           <a class="tips" href="javascript:;" data-type="tip" data-tip="<?php echo $tip_key?>">
           <?php echo $tip_val?>
           </a>
           </li>
           <?php endforeach;?>           
           <li><a class="tips" href="javascript:;" data-type="cash" data-tip="0"><?php echo t("Tip cash")?></a></li>
           <li><?php echo CHtml::textField('tip_value','',array(
             'class'=>"numeric_only uk-form-width-small",
             'style'=>"width:70px;"
           ));?></li>
         </ul>
        </div>
        <?php endif;?>
        <!--END tips section-->
        
        
       </form>
         
       <div class="spacer"></div>
        
        <div class="credit_card_wrap hidden">    
                                            
            <form id="frm-creditcard" class="frm-creditcard uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">
            
            <h3><?php echo Yii::t("default","Credit Card information")?> <a href="javascript:;" class="cc-add uk-button"><?php echo Yii::t("default","Add new card")?></a></h3>
            
            <p class="uk-text-muted"><?php echo Yii::t("default","select credit card below")?></p>
            <ul class="uk-list uk-list-striped uk-list-cc">
            <!--<li>
              <div class="uk-grid">
                <div class="uk-width-1-2">12344455</div>
                <div class="uk-width-1-2">&nbsp;<input type="radio" name="cc_id" class="cc_id" value=""></div>
              </div>
            </li>-->
            </ul>
                           
              <div class="cc-add-wrap hidden">
               <p class="uk-text-bold"><?php echo Yii::t("default","New Card")?></p>
               <?php echo CHtml::hiddenField('action','addCreditCard')?>
               <?php echo CHtml::hiddenField('currentController','store')?>
               
               <div class="uk-form-row">                  
	              <?php echo CHtml::textField('card_name','',array(
	               'class'=>'uk-width-1-1',
	               'placeholder'=>Yii::t("default","Card name"),
	               'data-validation'=>"required"  
	              ))?>
               </div>             
               
               <div class="uk-form-row">                  
	              <?php echo CHtml::textField('credit_card_number','',array(
	               'class'=>'uk-width-1-1 numeric_only',
	               'placeholder'=>Yii::t("default","Credit Card Number"),
	               'data-validation'=>"required",
	               'maxlength'=>20
	              ))?>
               </div>             
               
               <div class="uk-form-row">                  
	              <?php echo CHtml::dropDownList('expiration_month','',
	              Yii::app()->functions->ccExpirationMonth()
	              ,array(
	               'class'=>'uk-width-1-1',
	               'placeholder'=>Yii::t("default","Exp. month"),
	               'data-validation'=>"required"  
	              ))?>
               </div>             
               
               <div class="uk-form-row">                  
	              <?php echo CHtml::dropDownList('expiration_yr','',
	              Yii::app()->functions->ccExpirationYear()
	              ,array(
	               'class'=>'uk-width-1-1',
	               'placeholder'=>Yii::t("default","Exp. year") ,
	               'data-validation'=>"required"  
	              ))?>
               </div>             
               
               <div class="uk-form-row">                  
	              <?php echo CHtml::textField('cvv','',array(
	               'class'=>'uk-width-1-1',
	               'placeholder'=>Yii::t("default","CVV"),
	               'data-validation'=>"required",
	               'maxlength'=>4
	              ))?>
               </div>             
               
               <div class="uk-form-row">                  
	              <?php echo CHtml::textField('billing_address','',array(
	               'class'=>'uk-width-1-1',
	               'placeholder'=>Yii::t("default","Billing Address"),
	               'data-validation'=>"required"  
	              ))?>
               </div>             
               
               <div class="uk-form-row">   
                  <input type="submit" value="<?php echo Yii::t("default","Add Credit Card")?>" class="uk-button uk-button-success uk-width-1-1">
               </div>
             </div> 
             </form>
        </div> <!--credit_cart_wrap-->
        
     </div> <!--grid-1-->
     <div class="grid-2 left">
        <div class="order-list-wrap">
          <h5><?php echo Yii::t("default","Your Order")?></h5>
          <div class="item-order-wrap"></div>
                    
          
          <?php $minimum_order=Yii::app()->functions->getOption('merchant_minimum_order',$merchant_id);
          if ( $s['kr_delivery_options']['delivery_type']=="pickup"){
          	  $minimum_order=Yii::app()->functions->getOption('merchant_minimum_order_pickup',$merchant_id);
          }  
          ?>
	      <?php if (!empty($minimum_order)):?>
	      <?php 
	            echo CHtml::hiddenField('minimum_order',unPrettyPrice($minimum_order));
	            echo CHtml::hiddenField('minimum_order_pretty',baseCurrency().prettyFormat($minimum_order))
	       ?>
          
          <p class="uk-text-muted"><?php echo Yii::t("default","Subtotal must exceed")?> 
            <?php echo baseCurrency().prettyFormat($minimum_order,$merchant_id)?>
          </p>      
          <?php endif;?>
          
          
          <?php 
          /*POINTS PROGRAM*/
          //PointsProgram::redeemForm();
          ?>
                    
          <?php if ( getOptionA('captcha_order')==2):?>
          <div class="recaptcha" id="RecaptchaField1"></div>
          <?php endif;?>
          
          <!--SMS Order verification-->
          <?php if ( getOptionA('mechant_sms_enabled')==""):?>
          <?php if ( getOption($merchant_id,'order_verification')==2):?>
          <?php $sms_balance=Yii::app()->functions->getMerchantSMSCredit($merchant_id);?>
          <?php if ( $sms_balance>=1):?>
          <?php $sms_order_session=Yii::app()->functions->generateCode(50);?>
          <p style="margin-top:10px;">
          <?php echo t("This merchant has required SMS verification")?><br/>
          <?php echo t("before you can place your order")?>.<br/>
          <?php echo t("Click")?> <a href="javascript:;" class="send-order-sms-code" data-session="<?php echo $sms_order_session;?>">
             <?php echo t("here")?></a>
          <?php echo t("receive your order sms code")?>
          </p>
          <div class="uk-form">
          <?php 
          echo CHtml::textField('order_sms_code','',array(
            'style'=>"display:block;margin:auto;text-align:center;",
            'placeholder'=>t("SMS Code"),
            'maxlength'=>8
          ));
          ?>
          </div>
          <?php endif;?>
          <?php endif;?>
          <?php endif;?>
          
          <div class="spacer2"></div>
          <a href="javascript:;" class="uk-button uk-button-success place_order"><?php echo Yii::t("default","Place Order")?></a>
          
        </div> <!--order-list-wrap-->
     </div> <!--grid-2-->
     <div class="clear"></div>
   </div> <!--grid-->  
<?php else :?>
  <p class="uk-alert uk-alert-warning"><?php echo Yii::t("default","Something went wrong Either your visiting the page directly or your session has expired.")?></p>
<?php endif;?>
   </div>
  </div>
</div>