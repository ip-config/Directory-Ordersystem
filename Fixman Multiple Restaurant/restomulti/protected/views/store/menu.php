<?php
/*POINTS PROGRAM*/
if (Yii::app()->hasModule('pointsprogram')){
	unset($_SESSION['pts_redeem_amt']);
	unset($_SESSION['pts_redeem_points']);
}

$merchant_photo_bg=getOption($merchant_id,'merchant_photo_bg');
if ( !file_exists(FunctionsV3::uploadPath()."/$merchant_photo_bg")){
	$merchant_photo_bg='';
} 

/*RENDER MENU HEADER FILE*/
$ratings=Yii::app()->functions->getRatings($merchant_id);   
$merchant_info=array(   
  'merchant_id'=>$merchant_id ,
  'minimum_order'=>$data['minimum_order'],
  'ratings'=>$ratings,
  'merchant_address'=>$data['merchant_address'],
  'cuisine'=>$data['cuisine'],
  'restaurant_name'=>$data['restaurant_name'],
  'background'=>$merchant_photo_bg,
  'merchant_website'=>$merchant_website,
  'merchant_logo'=>FunctionsV3::getMerchantLogo($merchant_id)
);
$this->renderPartial('/front/menu-header',$merchant_info);

/*ADD MERCHANT INFO AS JSON */
$cs = Yii::app()->getClientScript();
$cs->registerScript(
  'merchant_information',
  "var merchant_information =".json_encode($merchant_info)."",
  CClientScript::POS_HEAD
);		

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/order-progress-bar',array(
   'step'=>3,
   'show_bar'=>true
));

$now=date('Y-m-d');
$now_time='';

$checkout=FunctionsV3::isMerchantcanCheckout($merchant_id); 
$menu=Yii::app()->functions->getMerchantMenu($merchant_id); 

echo CHtml::hiddenField('is_merchant_open',isset($checkout['code'])?$checkout['code']:'' );

/*hidden TEXT*/
echo CHtml::hiddenField('restaurant_slug',$data['restaurant_slug']);
echo CHtml::hiddenField('merchant_id',$merchant_id);
echo CHtml::hiddenField('is_client_login',Yii::app()->functions->isClientLogin());

echo CHtml::hiddenField('website_disbaled_auto_cart',
Yii::app()->functions->getOptionAdmin('website_disbaled_auto_cart'));

$hide_foodprice=Yii::app()->functions->getOptionAdmin('website_hide_foodprice');
echo CHtml::hiddenField('hide_foodprice',$hide_foodprice);

echo CHtml::hiddenField('accept_booking_sameday',getOption($merchant_id
,'accept_booking_sameday'));

echo CHtml::hiddenField('customer_ask_address',getOptionA('customer_ask_address'));

echo CHtml::hiddenField('merchant_required_delivery_time',
  Yii::app()->functions->getOption("merchant_required_delivery_time",$merchant_id));   
  
/** add minimum order for pickup status*/
$merchant_minimum_order_pickup=Yii::app()->functions->getOption('merchant_minimum_order_pickup',$merchant_id);
if (!empty($merchant_minimum_order_pickup)){
	  echo CHtml::hiddenField('merchant_minimum_order_pickup',$merchant_minimum_order_pickup);
	  
	  echo CHtml::hiddenField('merchant_minimum_order_pickup_pretty',
         displayPrice(baseCurrency(),prettyFormat($merchant_minimum_order_pickup)));
}
 
$merchant_maximum_order_pickup=Yii::app()->functions->getOption('merchant_maximum_order_pickup',$merchant_id);
if (!empty($merchant_maximum_order_pickup)){
	  echo CHtml::hiddenField('merchant_maximum_order_pickup',$merchant_maximum_order_pickup);
	  
	  echo CHtml::hiddenField('merchant_maximum_order_pickup_pretty',
         displayPrice(baseCurrency(),prettyFormat($merchant_maximum_order_pickup)));
}  

/*add minimum and max for delivery*/
$minimum_order=Yii::app()->functions->getOption('merchant_minimum_order',$merchant_id);
if (!empty($minimum_order)){
	echo CHtml::hiddenField('minimum_order',unPrettyPrice($minimum_order));
	echo CHtml::hiddenField('minimum_order_pretty',
	 displayPrice(baseCurrency(),prettyFormat($minimum_order))
	);
}
$merchant_maximum_order=Yii::app()->functions->getOption("merchant_maximum_order",$merchant_id);
 if (is_numeric($merchant_maximum_order)){
 	echo CHtml::hiddenField('merchant_maximum_order',unPrettyPrice($merchant_maximum_order));
    echo CHtml::hiddenField('merchant_maximum_order_pretty',baseCurrency().prettyFormat($merchant_maximum_order));
 }

$is_ok_delivered=1;
if (is_numeric($merchant_delivery_distance)){
	if ( $distance>$merchant_delivery_distance){
		$is_ok_delivered=2;
		/*check if distance type is feet and meters*/
		if($distance_type=="ft" || $distance_type=="mm" || $distance_type=="mt"){
			$is_ok_delivered=1;
		}
	}
} 

echo CHtml::hiddenField('is_ok_delivered',$is_ok_delivered);
echo CHtml::hiddenField('merchant_delivery_miles',$merchant_delivery_distance);
echo CHtml::hiddenField('unit_distance',$distance_type);
echo CHtml::hiddenField('from_address', FunctionsV3::getSessionAddress() );

echo CHtml::hiddenField('merchant_close_store',getOption($merchant_id,'merchant_close_store'));
/*$close_msg=getOption($merchant_id,'merchant_close_msg');
if(empty($close_msg)){
	$close_msg=t("This restaurant is closed now. Please check the opening times.");
}*/
echo CHtml::hiddenField('merchant_close_msg',
isset($checkout['msg'])?$checkout['msg']:t("Sorry merchant is closed."));

echo CHtml::hiddenField('disabled_website_ordering',getOptionA('disabled_website_ordering'));
echo CHtml::hiddenField('web_session_id',session_id());

echo CHtml::hiddenField('merchant_map_latitude',$data['latitude']);
echo CHtml::hiddenField('merchant_map_longtitude',$data['lontitude']);
echo CHtml::hiddenField('restaurant_name',$data['restaurant_name']);
?>

<div class="sections section-menu section-grey2">
<div class="container">
  <div class="row">

     <div class="col-md-8 border menu-left-content">
         
        <div class="tabs-wrapper" id="menu-tab-wrapper">
	    <ul id="tabs">
		    <li class="active">
		       <span><?php echo t("Menu")?></span>
		       <i class="ion-fork"></i>
		    </li>
		    
		    <?php if ($theme_hours_tab==""):?>
		    <li>
		       <span><?php echo t("Opening Hours")?></span>
		       <i class="ion-clock"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ($theme_reviews_tab==""):?>
		    <li class="view-reviews">
		       <span><?php echo t("Reviews")?></span>
		       <i class="ion-ios-star-half"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ($theme_map_tab==""):?>
		    <li class="view-merchant-map">
		       <span><?php echo t("Map")?></span>
		       <i class="ion-ios-navigate-outline"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ($booking_enabled):?>
		      <li>
		      <span><?php echo t("Book a Table")?></span>
		      <i class="ion-coffee"></i>
		      </li>
		    <?php endif;?>
		    
		    <?php if ($photo_enabled):?>
		      <li class="view-merchant-photos">
		       <span><?php echo t("Photos")?></span>
		       <i class="ion-images"></i>
		      </li>
		    <?php endif;?>
		    
		    <?php if ($theme_info_tab==""):?>
		    <li>
		      <span><?php echo t("Information")?></span>
		      <i class="ion-ios-information-outline"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ( $promo['enabled']==2 && $theme_promo_tab==""):?>
		      <li>
		       <span><?php echo t("Promos")?></span>
		       <i class="ion-pizza"></i>
		      </li>
		    <?php endif;?>
		</ul>
		
		<ul id="tab">
		
		<!--MENU-->
	    <li class="active">
	        <div class="row">
			 <div class="col-md-4 col-xs-4 border category-list">
				<div class="theiaStickySidebar">
				 <?php 
				 $this->renderPartial('/front/menu-category',array(
				  'merchant_id'=>$merchant_id,
				  'menu'=>$menu			  
				 ));
				 ?>
				</div>
			 </div> <!--col-->
			 <div class="col-md-8 col-xs-8 border" id="menu-list-wrapper">
			 <?php 
			 switch (getOptionA('admin_activated_menu'))
			 {
			 	case 1:
			 		$this->renderPartial('/front/menu-merchant-2',array(
					  'merchant_id'=>$merchant_id,
					  'menu'=>$menu,
					  'disabled_addcart'=>$disabled_addcart
					));
			 		break;
			 		
			 	case 2:
			 		$this->renderPartial('/front/menu-merchant-3',array(
					  'merchant_id'=>$merchant_id,
					  'menu'=>$menu,
					  'disabled_addcart'=>$disabled_addcart
					));
			 		break;
			 			
			 	default:	
				 	$this->renderPartial('/front/menu-merchant-1',array(
					  'merchant_id'=>$merchant_id,
					  'menu'=>$menu,
					  'disabled_addcart'=>$disabled_addcart,
					  'tc'=>$tc
					));
			    break;
			 }			 
			 ?>			
			 </div> <!--col-->
			</div> <!--row-->
	    </li>
	    <!--END MENU-->
	    
	    
	    <!--OPENING HOURS-->
	    <?php if ($theme_hours_tab==""):?>
	    <li>	       	     
	    <?php
	    $this->renderPartial('/front/merchant-hours',array(
	      'merchant_id'=>$merchant_id
	    )); ?>           
	    </li>
	    <?php endif;?>
	    <!--END OPENING HOURS-->
	    
	    <!--MERCHANT REVIEW-->
	    <?php if ($theme_reviews_tab==""):?>
	    <li class="review-tab-content">	       	     
	    <?php $this->renderPartial('/front/merchant-review',array(
	      'merchant_id'=>$merchant_id
	    )); ?>           
	    </li>
	    <?php endif;?>
	    <!--END MERCHANT REVIEW-->
	    
	    <!--MERCHANT MAP-->
	    <?php if ($theme_map_tab==""):?>
	    <li>	        	
	    <?php $this->renderPartial('/front/merchant-map'); ?>        
	    </li>
	    <?php endif;?>
	    <!--END MERCHANT MAP-->
	    
	    <!--BOOK A TABLE-->
	    <?php if ($booking_enabled):?>
	    <li>
	    <?php $this->renderPartial('/front/merchant-book-table',array(
	      'merchant_id'=>$merchant_id
	    )); ?>        
	    </li>
	    <?php endif;?>
	    <!--END BOOK A TABLE-->
	    
	    <!--PHOTOS-->
	    <?php if ($photo_enabled):?>
	    <li>
	    <?php 
	    $gallery=Yii::app()->functions->getOption("merchant_gallery",$merchant_id);
        $gallery=!empty($gallery)?json_decode($gallery):false;
	    $this->renderPartial('/front/merchant-photos',array(
	      'merchant_id'=>$merchant_id,
	      'gallery'=>$gallery
	    )); ?>        
	    </li>
	    <?php endif;?>
	    <!--END PHOTOS-->
	    
	    <!--INFORMATION-->
	    <?php if ($theme_info_tab==""):?>
	    <li>
	        <div class="box-grey rounded " style="margin-top:0;">
	          <?php echo getOption($merchant_id,'merchant_information')?>
	        </div>
	    </li>
	    <?php endif;?>
	    <!--END INFORMATION-->
	    
	    <!--PROMOS-->
	    <?php if ( $promo['enabled']==2 && $theme_promo_tab==""):?>
	    <li>
	    <?php $this->renderPartial('/front/merchant-promo',array(
	      'merchant_id'=>$merchant_id,
	      'promo'=>$promo
	    )); ?>        
	    </li>
	    <?php endif;?>
	    <!--END PROMOS-->
	    
	    
	   </ul>
	   </div>
     
     </div> <!-- menu-left-content-->
     
     <?php if (getOptionA('disabled_website_ordering')!="yes"):?>
     <div id="menu-right-content" class="col-md-4 border menu-right-content <?php echo $disabled_addcart=="yes"?"hide":''?>" >
     
     <div class="theiaStickySidebar">
      <div class="box-grey rounded  relative">
      
        <div class="star-float"></div>
      
        <!--DELIVERY INFO-->
        <div class="inner center">
         <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button> 
       
	        <p class="bold"><?php echo t("Delivery Information")?></p>
	        
	        <p>
	        <?php 
	        if ($distance){
	        	echo t("Distance").": ".number_format($distance,1)." $distance_type";
	        } else echo  t("Distance").": ".t("not available");
	        ?>
	        </p>
	        
	        <p class="delivery-fee-wrap">
	        <?php echo t("Delivery Est")?>: <?php echo FunctionsV3::getDeliveryEstimation($merchant_id)?></p>
	        
	        <p class="delivery-fee-wrap">
	        <?php 
	        if (!empty($merchant_delivery_distance)){
	        	echo t("Delivery Distance Covered").": ".$merchant_delivery_distance." $distance_type_orig";
	        } else echo  t("Delivery Distance Covered").": ".t("not available");
	        ?>
	        </p>
	        
	        <p class="delivery-fee-wrap">
	        <?php 
	        if ($delivery_fee){
	             echo t("Delivery Fee").": ".FunctionsV3::prettyPrice($delivery_fee);
	        } else echo  t("Delivery Fee").": ".t("Free Delivery");
	        ?>
	        </p>
	        
	        <a href="javascript:;" class="top10 green-color change-address block text-center">
	        [<?php echo t("Change Your Address here")?>]
	        </a>
	        
        </div>
        <!--END DELIVERY INFO-->
        
        <!--CART-->
        <div class="inner line-top relative">
        
           <i class="order-icon your-order-icon"></i>
           
           <p class="bold center"><?php echo t("Your Order")?></p>
           
           <div class="item-order-wrap"></div>
           
           <!--VOUCHER STARTS HERE-->
           <?php //Widgets::applyVoucher($merchant_id);?>
           <!--VOUCHER STARTS HERE-->
           
           <!--MAX AND MIN ORDR-->
           <?php if ($minimum_order>0):?>
           <div class="delivery-min">
              <p class="small center"><?php echo Yii::t("default","Subtotal must exceed")?> 
              <?php echo displayPrice(baseCurrency(),prettyFormat($minimum_order,$merchant_id))?>
           </div>
           <?php endif;?>
           
           <?php if ($merchant_minimum_order_pickup>0):?>
           <div class="pickup-min">
              <p class="small center"><?php echo Yii::t("default","Subtotal must exceed")?> 
              <?php echo displayPrice(baseCurrency(),prettyFormat($merchant_minimum_order_pickup,$merchant_id))?>
           </div>
           <?php endif;?>
              
	        <a href="javascript:;" class="clear-cart">[<?php echo t("Clear Order")?>]</a>
           
        </div> <!--inner-->
        <!--END CART-->
        
        <!--DELIVERY OPTIONS-->
        <div class="inner line-top relative delivery-option center">
           <i class="order-icon delivery-option-icon"></i>
           <p class="bold"><?php echo t("Delivery Options")?></p>
           
           <?php echo CHtml::dropDownList('delivery_type',$now,
           (array)Yii::app()->functions->DeliveryOptions($merchant_id),array(
             'class'=>'grey-fields'
           ))?>
           
           <?php echo CHtml::hiddenField('delivery_date',$now)?>
           <?php echo CHtml::textField('delivery_date1',
            FormatDateTime($now,false),array('class'=>"j_date grey-fields",'data-id'=>'delivery_date'))?>
           
           <div class="delivery_asap_wrap"> 
	         <?php echo CHtml::textField('delivery_time',$now_time,
	          array('class'=>"timepick grey-fields",'placeholder'=>Yii::t("default","Delivery Time")))?>	       
	          <span class="delivery-asap">
	          <?php echo CHtml::checkBox('delivery_asap',false,array('class'=>"icheck"))?>
	          <span class="text-muted"><?php echo Yii::t("default","Delivery ASAP?")?></span>	          
	         </span>       	         	        
           </div><!-- delivery_asap_wrap-->
           
           <?php if ( $checkout['code']==1):?>
              <a href="javascript:;" class="orange-button medium checkout"><?php echo $checkout['button']?></a>
           <?php else :?>
              <?php if ( $checkout['holiday']==1):?>
                 <?php echo CHtml::hiddenField('is_holiday',$checkout['msg'],array('class'=>'is_holiday'));?>
                 <p class="text-danger"><?php echo $checkout['msg']?></p>
              <?php else :?>
                 <p class="text-danger"><?php echo $checkout['msg']?></p>
                 <p class="small">
                 <?php echo Yii::app()->functions->translateDate(date('F d l')."@".timeFormat(date('c'),true));?></p>
              <?php endif;?>
           <?php endif;?>
                                                                
        </div> <!--inner-->
        <!--END DELIVERY OPTIONS-->
        
      </div> <!-- box-grey-->
      </div> <!--end theiaStickySidebar-->
     
     </div> <!--menu-right-content--> 
     <?php endif;?>
  
  </div> <!--row-->
</div> <!--container-->
</div> <!--section-menu-->