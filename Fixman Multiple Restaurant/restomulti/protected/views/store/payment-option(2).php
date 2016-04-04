<?php
$s=$_SESSION;
/*$city='';$state='';$street='';
if (isset($s['kr_search_address'])){	
	$temp=explode(",",$s['kr_search_address']);		
	if (is_array($temp) && count($temp)>=2){
		$street=$temp[0];
		$city=$temp[1];
		$state=$temp[2];
	}
}*/
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

$merchant_address='';		
if ($merchant_info=Yii::app()->functions->getMerchant($s['kr_merchant_id'])){
	$merchant_address=$merchant_info['street']." ".$merchant_info['city']." ".$merchant_info['state'];
	$merchant_address.=" "	. $merchant_info['post_code'];
}

?>

<div class="page-right-sidebar payment-option-page">
  <div class="main">
  <div class="inner">
<?php if (isset($s['kr_merchant_id']) && Yii::app()->functions->isClientLogin() && is_array($merchant_info) ):?>
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
                
        
        <?php if ( $s['kr_delivery_options']['delivery_type']=="pickup"):?>
        
        <h3><?php echo Yii::t("default","Pickup information")?></h3>
        <p class="uk-text-bold"><?php echo $merchant_address;?></p>
        <?php else :?>
        
        <h3><?php echo Yii::t("default","Delivery information")?></h3>
        
        <p>
        <?php echo ucwords($merchant_info['restaurant_name'])?> <?php echo Yii::t("default","Restaurant")?> 
        <?php echo "<span class='uk-text-bold'>".Yii::t("default",ucwords($s['kr_delivery_options']['delivery_type'])) . "</span> ";
        if ($s['kr_delivery_options']['delivery_asap']==1){
        	$s['kr_delivery_options']['delivery_date']." ".Yii::t("default","ASAP");
        } else {
          echo '<span class="uk-text-bold">'.date("M d Y",strtotime($s['kr_delivery_options']['delivery_date'])).
          " at ". " ". $s['kr_delivery_options']['delivery_time']."</span> to ";
        }
        ?>
        </p>
        
        <div class="uk-panel uk-panel-box">                                                              
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
               'placeholder'=>Yii::t("default","Apartment suite, unit number, or company name")  
              ))?>
             </div>             
             <div class="uk-form-row">
              <?php echo CHtml::textField('contact_phone',isIsset($client_info['contact_phone']),array(
               'class'=>'uk-width-1-1',
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
        
        </div> <!--uk-panel--> 
        <?php endif;?>

        <?php Widgets::merchantPaymentList($merchant_id);?>       
        
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
                    
          
          <?php $minimum_order=Yii::app()->functions->getOption('merchant_minimum_order',$merchant_id);?>
	      <?php if (!empty($minimum_order)):?>
	      <?php 
	            echo CHtml::hiddenField('minimum_order',unPrettyPrice($minimum_order));
	            echo CHtml::hiddenField('minimum_order_pretty',baseCurrency().prettyFormat($minimum_order))
	       ?>
          
          <p class="uk-text-muted"><?php echo Yii::t("default","Subtotal must exceed")?> 
            <?php echo baseCurrency().prettyFormat($minimum_order,$merchant_id)?>
          </p>      
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