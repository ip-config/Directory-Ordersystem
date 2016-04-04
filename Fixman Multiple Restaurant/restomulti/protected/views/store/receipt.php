<?php
unset($_SESSION['pts_earn']);
unset($_SESSION['pts_redeem_amt']);

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Thank You"),
   'sub_text'=>t("Your order has been placed.")
));

$data='';
$ok=false;
if ( $data=Yii::app()->functions->getOrder2($_GET['id'])){				
	$merchant_id=$data['merchant_id'];
	$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
	if ( $json_details !=false){
		Yii::app()->functions->displayOrderHTML(array(
		  'merchant_id'=>$data['merchant_id'],
		  'delivery_type'=>$data['trans_type'],
		  'delivery_charge'=>$data['delivery_charge'],
		  'packaging'=>$data['packaging'],
		  'cart_tip_value'=>$data['cart_tip_value'],
		  'cart_tip_percentage'=>$data['cart_tip_percentage'],
		  'card_fee'=>$data['card_fee'],
		  'points_discount'=>isset($data['points_discount'])?$data['points_discount']:'' /*POINTS PROGRAM*/
		  ),$json_details,true);
		if ( Yii::app()->functions->code==1){
			$ok=true;
		}
	}	
}
unset($_SESSION['kr_item']);
unset($_SESSION['kr_merchant_id']);
unset($_SESSION['voucher_code']);
unset($_SESSION['less_voucher']);
unset($_SESSION['shipping_fee']);

$print='';

$order_ok=true;

$merchant_info=Yii::app()->functions->getMerchant(isset($merchant_id)?$merchant_id:'');
$full_merchant_address=$merchant_info['street']." ".$merchant_info['city']. " ".$merchant_info['state'].
" ".$merchant_info['post_code'];
?>

<div class="sections section-grey2 section-receipt">
   <div class="container">
      
   <?php if ($ok==TRUE):?>
   <div class="inner" id="receipt-content">
	   <h1><?php echo t("Order Details")?></h1>
	   <div class="box-grey">     
	   
	   <div class="text-center bottom10">
	   <i class="ion-ios-checkmark-outline i-big-extra green-text"></i>
	   </div>
	   	   
	   <table class="table table-striped">
	    <tbody>	     
	       
	       <tr>
	         <td><?php echo Yii::t("default","Customer Name")?></td>
	         <td class="text-right"><?php echo $data['full_name']?></td>
	       </tr>	       
	       <?php $print[]=array( 'label'=>Yii::t("default","Customer Name"), 'value'=>$data['full_name'] );?>	       
	       <tr>
	         <td><?php echo Yii::t("default","Merchant Name")?></td>
	         <td class="text-right"><?php echo $data['merchant_name']?></td>
	       </tr>       
	       <?php $print[]=array( 'label'=>Yii::t("default","Merchant Name"), 'value'=>$data['merchant_name']); ?>
	       
	       <?php if (isset($data['abn']) && !empty($data['abn'])):?>	       
	       <tr>
	         <td><?php echo Yii::t("default","ABN")?></td>
	         <td class="text-right"><?php echo $data['abn']?></td>
	       </tr> 
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","ABN"),
	         'value'=>$data['abn']
	       );
	       ?>
	       <?php endif;?>
	       
	       <tr>
	         <td><?php echo Yii::t("default","Telephone")?></td>
	         <td class="text-right"><?php echo $data['merchant_contact_phone']?></td>
	       </tr>	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Telephone"),
	         'value'=>$data['merchant_contact_phone']
	       );
	       ?>
		   		   
	       <tr>
	         <td><?php echo Yii::t("default","Address")?></td>
	         <td class="text-right"><?php echo $full_merchant_address?></td>
	       </tr>    
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Address"),
	         'value'=>$full_merchant_address
	       );
	       ?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","TRN Type")?></td>
	         <td class="text-right"><?php echo Yii::t("default",$data['trans_type'])?></td>
	       </tr>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Type"),
	         'value'=>$data['trans_type']
	       );
	       ?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","Payment Type")?></td>
	         <td class="text-right"><?php echo strtoupper(t($data['payment_type']))?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Type"),
	         'value'=>strtoupper($data['payment_type'])
	       );
	       ?>
	       	       
	       <?php if ( $data['payment_provider_name']):?>	      
	       <tr>
	         <td><?php echo Yii::t("default","Card#")?></td>
	         <td class="text-right"><?php echo $data['payment_provider_name']?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Card#"),
	         'value'=>strtoupper($data['payment_provider_name'])
	       );
	       ?>
	       <?php endif;?>	       	       
	       	       
	       <?php if ( $data['payment_type'] =="pyp"):?>
	       <?php 
	       $paypal_info=Yii::app()->functions->getPaypalOrderPayment($data['order_id']);	       
	       ?>	       
	       <tr>
	         <td><?php echo Yii::t("default","Paypal Transaction ID")?></td>
	         <td class="text-right"><?php echo isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:'';?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Paypal Transaction ID"),
	         'value'=>isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","Reference #")?></td>
	         <td class="text-right"><?php echo Yii::app()->functions->formatOrderNumber($data['order_id'])?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Reference #"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       
	       <?php if ( !empty($data['payment_reference'])):?>	      
	       <tr>
	         <td><?php echo Yii::t("default","Payment Ref")?></td>
	         <td class="text-right"><?php echo $data['payment_reference']?></td>
	       </tr>
	       <?php
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Ref"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <?php if ( $data['payment_type']=="ccr" || $data['payment_type']=="ocr"):?>	       
	       <tr>
	         <td><?php echo Yii::t("default","Card #")?></td>
	         <td class="text-right"><?php echo $card=Yii::app()->functions->maskCardnumber($data['credit_card_number'])?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Card #"),
	         'value'=>$card
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","TRN Date")?></td>
	         <td class="text-right">
	         <?php 
	         $trn_date=date('M d,Y G:i:s',strtotime($data['date_created']));
	         echo Yii::app()->functions->translateDate($trn_date);
	         ?>
	         </td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Date"),
	         'value'=>$trn_date
	       );
	       ?>
	       
	       <?php if ($data['trans_type']=="delivery"):?>
		       	       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_date'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Delivery Date")?></td>
		         <td class="text-right">
		         <?php 
		         $deliver_date=prettyDate($_SESSION['kr_delivery_options']['delivery_date']);
		         echo Yii::app()->functions->translateDate($deliver_date);
		         ?>
		         </td>
		       </tr>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Date"),
		         'value'=>$deliver_date
		       );
		       ?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_time'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Delivery Time")?></td>
		         <td class="text-right"><?php echo Yii::app()->functions->timeFormat($_SESSION['kr_delivery_options']['delivery_time'],true)?></td>
		       </tr>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Time"),
		         'value'=>Yii::app()->functions->timeFormat($_SESSION['kr_delivery_options']['delivery_time'],true)
		       );
		       ?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_asap'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_asap'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Deliver ASAP")?></td>
		         <td class="text-right">
		         <?php echo $delivery_asap=$_SESSION['kr_delivery_options']['delivery_asap']==1?t("Yes"):'';?>
		         </td>
		       </tr>
			   <?php 	       
				$print[]=array(
				 'label'=>Yii::t("default","Deliver ASAP"),
				 'value'=>$delivery_asap
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       		       
		       <tr>
		         <td><?php echo Yii::t("default","Deliver to")?></td>
		         <td class="text-right">
		         <?php 		         
		         if (!empty($data['client_full_address'])){
		         	echo $delivery_address=$data['client_full_address'];
		         } else echo $delivery_address=$data['full_address'];		         
		         ?>
		         </td>
		       </tr>
				<?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Deliver to"),
				  'value'=>$delivery_address
				);
				?>
						       		      
		       <tr>
		         <td><?php echo Yii::t("default","Delivery Instruction")?></td>
		         <td class="text-right"><?php echo $data['delivery_instruction']?></td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Delivery Instruction"),
				  'value'=>$data['delivery_instruction']
				);
				?>
		       		       
		       <tr>
		         <td><?php echo Yii::t("default","Location Name")?></td>
		         <td class="text-right">
		         <?php 
		         if (!empty($data['location_name1'])){
		         	$data['location_name']=$data['location_name1'];
		         }
		         echo $data['location_name'];
		         ?>
		         </td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Location Name"),
				  'value'=>$data['location_name']
				);
				?>
								
		       <tr>
		         <td><?php echo Yii::t("default","Contact Number")?></td>
		         <td class="text-right">
		         <?php 
		         if ( !empty($data['contact_phone1'])){
		         	$data['contact_phone']=$data['contact_phone1'];
		         }
		         echo $data['contact_phone'];?>
		         </td>
		       </tr>       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
				
				<?php if ($data['order_change']>=0.1):?>					
		       <tr>
		         <td><?php echo Yii::t("default","Change")?></td>
		         <td class="text-right">
		         <?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?>
		         </td>
		       </tr>     
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>normalPrettyPrice($data['order_change'])
				);
				?>
				<?php endif;?>
				
								
		   <?php else :?>   
		   		   		  
               <?php 
				if (isset($data['contact_phone1'])){
					if (!empty($data['contact_phone1'])){
						$data['contact_phone']=$data['contact_phone1'];
					}
				}
			   ?>		      
		       <tr>
		         <td><?php echo Yii::t("default","Contact Number")?></td>
		         <td class="text-right"><?php echo $data['contact_phone']?></td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
		       		     		  
		      <?php if (isset($_SESSION['kr_delivery_options']['delivery_date'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Pickup Date")?></td>
		         <td class="text-right">
		         <?php echo $_SESSION['kr_delivery_options']['delivery_date']?>
		         </td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Pickup Date"),
				  'value'=>$_SESSION['kr_delivery_options']['delivery_date']
				);
				?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_time'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Pickup Time")?></td>
		         <td class="text-right"><?php echo $_SESSION['kr_delivery_options']['delivery_time']?></td>
		       </tr>
		       <?php 	       
				$print[]=array(
				 'label'=>Yii::t("default","Pickup Time"),
				 'value'=>$_SESSION['kr_delivery_options']['delivery_time']
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if ($data['order_change']>=0.1):?>					
		       <tr>
		         <td><?php echo Yii::t("default","Change")?></td>
		         <td class="text-right">
		         <?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?>
		         </td>
		       </tr>  
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>$data['order_change']
				);
				?>
				<?php endif;?> 
		       
	       
	       <?php endif;?>
	       
	       <tr>
			 <td colspan="2"></td>
		   </tr>
	       	    
	    </tbody>
	   </table>
	   
	    <div class="receipt-wrap order-list-wrap">
	    <?php echo $item_details=Yii::app()->functions->details['html'];?>
	    </div>
	   
	   </div> <!--box-grey-->
	   
   </div> <!--inner-->
   
   <div class="row">
      <div class="col-sm-12 text-right">
        <a href="javascript:;" class="print-receipt"><i class="ion-ios-printer-outline"></i></a>
      </div> <!--col-->
   </div> <!--row-->
    
   <?php else :?>
    <p class="text-warning"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
    <?php $order_ok=false;?>
   <?php endif;?>
    
   </div> <!--container-->
</div>  <!--section-receipt-->

<?php 
$data_raw=Yii::app()->functions->details['raw'];
$receipt=EmailTPL::salesReceipt($print,Yii::app()->functions->details['raw']);
//dump($receipt);
$tpl=Yii::app()->functions->getOption("receipt_content",$merchant_id);
if (empty($tpl)){
	$tpl=EmailTPL::receiptTPL();
}
$tpl=Yii::app()->functions->smarty('receipt',$receipt,$tpl);
$tpl=Yii::app()->functions->smarty('customer-name',$data['full_name'],$tpl);
$tpl=Yii::app()->functions->smarty('receipt-number',Yii::app()->functions->formatOrderNumber($data['order_id']),$tpl);

$receipt_sender=Yii::app()->functions->getOption("receipt_sender",$merchant_id);
$receipt_subject=Yii::app()->functions->getOption("receipt_subject",$merchant_id);
if (empty($receipt_subject)){	
	$receipt_subject=getOptionA('receipt_default_subject');
	if (empty($receipt_subject)){
	    $receipt_subject="We have receive your order";
	}
}
if (empty($receipt_sender)){
	$receipt_sender='no-reply@'.$_SERVER['HTTP_HOST'];
}
$to=isset($data['email_address'])?$data['email_address']:'';

if (!isset($_SESSION['kr_receipt'])){
	$_SESSION['kr_receipt']='';
}

if (!in_array($data['order_id'],(array)$_SESSION['kr_receipt'])){	
	
	if ( $order_ok==false){
		return ;
	}
	
    sendEmail($to,$receipt_sender,$receipt_subject,$tpl);    
    
    /*send email to merchant address*/
    $merchant_notify_email=Yii::app()->functions->getOption("merchant_notify_email",$merchant_id);    
    $enabled_alert_notification=Yii::app()->functions->getOption("enabled_alert_notification",$merchant_id);    
    /*dump($merchant_notify_email);
    dump($enabled_alert_notification);   */
    if ( $enabled_alert_notification==""){   
    	 	
    	$merchant_receipt_subject=Yii::app()->functions->getOption("merchant_receipt_subject",$merchant_id);
    	
    	$merchant_receipt_subject=empty($merchant_receipt_subject)?t("New Order From").
    	" ".$data['full_name']:$merchant_receipt_subject;
    	
    	$merchant_receipt_content=Yii::app()->functions->getMerchantReceiptTemplate($merchant_id);
    	
    	$final_tpl='';    	
    	if (!empty($merchant_receipt_content)){
    		$merchant_token=Yii::app()->functions->getMerchantActivationToken($merchant_id);
    		$confirmation_link=Yii::app()->getBaseUrl(true)."/store/confirmorder/?id=".$data['order_id']."&token=$merchant_token";
    		$final_tpl=smarty('receipt-number',Yii::app()->functions->formatOrderNumber($data['order_id'])
    		,$merchant_receipt_content);    		
    		$final_tpl=smarty('customer-name',$data['full_name'],$final_tpl);
    		$final_tpl=smarty('receipt',$receipt,$final_tpl); 
    		$final_tpl=smarty('confirmation-link',$confirmation_link,$final_tpl); 
    	} else $final_tpl=$tpl;
    	    	
    	$global_admin_sender_email=Yii::app()->functions->getOptionAdmin('global_admin_sender_email');
    	if (empty($global_admin_sender_email)){
    		$global_admin_sender_email=$receipt_sender;
    	}     	
    	    	
    	// fixed if email is multiple
    	$merchant_notify_email=explode(",",$merchant_notify_email);    	
    	if (is_array($merchant_notify_email) && count($merchant_notify_email)>=1){
    		foreach ($merchant_notify_email as $merchant_notify_email_val) {    			
    			if(!empty($merchant_notify_email_val)){
    			sendEmail(trim($merchant_notify_email_val),$global_admin_sender_email,$merchant_receipt_subject,$final_tpl);
    			}
    		}
    	}    	    	
    }   
    
    // send SMS    
    Yii::app()->functions->SMSnotificationMerchant($merchant_id,$data,$data_raw);
        
    // SEND FAX
    Yii::app()->functions->sendFax($merchant_id,$_GET['id']);
    
}
$_SESSION['kr_receipt']=array($data['order_id']);