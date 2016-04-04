<?php if (is_array($data) && count($data)>=1):?>
<?php
    $merchant_id=$data['merchant_id'];
    $json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
    if ( $json_details !=false){
	    Yii::app()->functions->displayOrderHTML(array(
	       'merchant_id'=>$data['merchant_id'],
	       'order_id'=>$data['order_id'],
	       'delivery_type'=>$data['trans_type'],
	       'delivery_charge'=>$data['delivery_charge'],
	       'packaging'=>$data['packaging'],
	       'cart_tip_value'=>$data['cart_tip_value'],
		   'cart_tip_percentage'=>$data['cart_tip_percentage'],
		   'card_fee'=>$data['card_fee'],
		   'donot_apply_tax_delivery'=>$data['donot_apply_tax_delivery'],
		   'points_discount'=>isset($data['points_discount'])?$data['points_discount']:'' /*POINTS PROGRAM*/
	     ),$json_details,true);
	     
	     $data2=Yii::app()->functions->details;
	     
	     $merchant_info=Yii::app()->functions->getMerchant(isset($merchant_id)?$merchant_id:'');
         $full_merchant_address=$merchant_info['street']." ".$merchant_info['city']. " ".$merchant_info['state'].
         " ".$merchant_info['post_code'];

		 if (isset($data['contact_phone1'])){
			if (!empty($data['contact_phone1'])){
				$data['contact_phone']=$data['contact_phone1'];
			}
		 }				
		 if (isset($data['location_name1'])){
			if (!empty($data['location_name1'])){
				$data['location_name']=$data['location_name1'];
			}
		}
    }    
?>

<div class="section-grey2 section-receipt">
   <div class="inner">
       <h1><?php echo t("Order Details")?></h1>
       <div class="box-grey"> 
       
      <!-- <div class="text-center bottom10">
	   <i class="ion-ios-checkmark-outline i-big-extra green-text"></i>
	   </div>  -->
	   
	   <table class="table table-striped">
	   <tbody>	
	       <?php 
	       FunctionsV3::receiptTableRow('Customer Name',$data['full_name']);
	       FunctionsV3::receiptTableRow('Merchant Name',$data['merchant_name']);
	       if (isset($data['abn']) && !empty($data['abn'])){
	       	   FunctionsV3::receiptTableRow('ABN',$data['abn']);
	       }
	       FunctionsV3::receiptTableRow('Telephone',$data['merchant_contact_phone']);
	       FunctionsV3::receiptTableRow('Address',$full_merchant_address);
	       FunctionsV3::receiptTableRow('TRN Type', t($data['trans_type']) );
	       FunctionsV3::receiptTableRow('Payment Type',$data['payment_type']);
	       if ( $data['payment_provider_name']){
	       	  FunctionsV3::receiptTableRow('Card#',$data['payment_provider_name']);
	       }
	       if ( $data['payment_type'] =="pyp"){
	       	  $paypal_info=Yii::app()->functions->getPaypalOrderPayment($data['order_id']);	
	          FunctionsV3::receiptTableRow('Paypal Transaction ID', 
	            isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
	          );
	       }
	       FunctionsV3::receiptTableRow('Reference #', Yii::app()->functions->formatOrderNumber($data['order_id']) );
	       if ( !empty($data['payment_reference'])){
	       	  FunctionsV3::receiptTableRow('Payment Ref',$data['payment_reference']);
	       }
	       if ( $data['payment_type']=="ccr" || $data['payment_type']=="ocr"){
	           FunctionsV3::receiptTableRow('Card #',
	           $card=Yii::app()->functions->maskCardnumber($data['credit_card_number'])
	           );
	       }
	       $trn_date=date('M d,Y G:i:s',strtotime($data['date_created']));
	       FunctionsV3::receiptTableRow('TRN Date',  Yii::app()->functions->translateDate($trn_date) );
	       
	       if ($data['trans_type']=="delivery"){
	           /*DELIVERY*/
	           if (isset($data['delivery_date'])){
	           	  $date = prettyDate($data['delivery_date']);
		          $date=Yii::app()->functions->translateDate($date);
	              FunctionsV3::receiptTableRow('Delivery Date', $date );
	           }
	           if (isset($data['delivery_time'])){
	       	  	  if ( !empty($data['delivery_time'])){
	       	  	  	  FunctionsV3::receiptTableRow('Delivery Time',
	       	  	  	  Yii::app()->functions->timeFormat($data['delivery_time'],true) );
	       	  	  }
	       	   }
	       	   if (isset($data['delivery_asap'])){
	       	   	   if ( !empty($data['delivery_asap'])){
	       	   	   	   FunctionsV3::receiptTableRow('Deliver ASAP',
	       	   	   	    $data['delivery_asap']==1?t("Yes"):''
	       	   	   	   );
	       	   	   }
	       	   }
	       	   if (!empty($data['client_full_address'])){
		         	$delivery_address=$data['client_full_address'];
		       } else $delivery_address=$data['full_address'];
	       	   FunctionsV3::receiptTableRow('Deliver to', $delivery_address );
	       	   
	       	   if (!empty($data['delivery_instruction'])){
	       	      FunctionsV3::receiptTableRow('Delivery Instruction',$data['delivery_instruction']);
	       	   }
	       	   
       	       if (!empty($data['location_name1'])){
	         	  $data['location_name']=$data['location_name1'];
	           }
	       	   FunctionsV3::receiptTableRow('Location Name',$data['location_name']);
	       	   
	       	   if ( !empty($data['contact_phone1'])){
		          $data['contact_phone']=$data['contact_phone1'];
		       }
	       	   FunctionsV3::receiptTableRow('Contact Number',$data['contact_phone']);
	       	   
	       	   if ($data['order_change']>=0.1){
	       	   	  FunctionsV3::receiptTableRow('Change', FunctionsV3::prettyPrice($data['order_change']) );
	       	   }
	       	   
	       } else {
	       	  /*PICKUP*/
	       	  FunctionsV3::receiptTableRow('Contact Number',$data['contact_phone']);
	       	  if (isset($data['delivery_date'])){
	       	  	  FunctionsV3::receiptTableRow('Pickup Date',$data['delivery_date']);
	       	  }
	       	  if (isset($data['delivery_time'])){
	       	  	  if ( !empty($data['delivery_time'])){
	       	  	  	  FunctionsV3::receiptTableRow('Pickup Time',$data['delivery_time']);
	       	  	  }
	       	  }
	       	  
	       	  if ($data['order_change']>=0.1){
	       	  	  FunctionsV3::receiptTableRow('Change',
	       	  	  FunctionsV3::prettyPrice($data['order_change'])
	       	  	  );
	       	  }
	       }
	       ?>   
	   </tbody>
	   </table>
       
	   <div class="receipt-wrap order-list-wrap">
	    <?php echo $data2['html'];?>
	   </div>
	   
       </div> <!--box-grey-->
   </div> <!--inner-->
</div> <!--sections-->

<?php else :?>
<div class="container center top30">
  <p class="text-danger"><?php echo t("receipt not available")?></p>
</div>  
<?php endif;?>