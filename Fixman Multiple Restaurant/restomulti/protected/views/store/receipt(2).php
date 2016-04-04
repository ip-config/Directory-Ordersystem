<?php
/*POINTS PROGRAM*/
/*unset($_SESSION['pts_earn']);
unset($_SESSION['pts_redeem_amt']);*/

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
		  //'points_discount'=>$data['points_discount'] /*POINTS PROGRAM*/
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

<div class="page">
	<div class="main"> 
	<div class="inner">
     <?php if ($ok==TRUE):?>
         <div class="receipt-main-wrap">
         <h3><?php echo Yii::t("default","Thank You")?></h3>
         <p><?php echo Yii::t("default","Your order has been placed.")?></p>
         
	     <div class="receipt-wrap order-list-wrap">
	     	   
	       <?php echo Widgets::receiptLogo();?>	     
	       
	       <h4><?php echo Yii::t("default","Order Details")?></h4>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Customer Name")?> :</div>
	         <div class="value"><?php echo $data['full_name']?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Customer Name"),
	         'value'=>$data['full_name']
	       );
	       ?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Merchant Name")?> :</div>
	         <div class="value"><?php echo $data['merchant_name']?></div>
	         <div class="clear"></div>
	       </div>	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Merchant Name"),
	         'value'=>$data['merchant_name']
	       );
	       ?>
	       
	       <?php if (isset($data['abn']) && !empty($data['abn'])):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","ABN")?> :</div>
	         <div class="value"><?php echo $data['abn']?></div>
	         <div class="clear"></div>
	       </div>	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","ABN"),
	         'value'=>$data['abn']
	       );
	       ?>
	       <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Telephone")?> :</div>
	         <div class="value"><?php echo $data['merchant_contact_phone']?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Telephone"),
	         'value'=>$data['merchant_contact_phone']
	       );
	       ?>
		   
		   <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Address")?> :</div>
	         <div class="value"><?php echo $full_merchant_address?></div>
	         <div class="clear"></div>
	       </div>	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Address"),
	         'value'=>$full_merchant_address
	       );
	       ?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","TRN Type")?> :</div>
	         <div class="value"><?php echo Yii::t("default",$data['trans_type'])?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Type"),
	         'value'=>$data['trans_type']
	       );
	       ?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Payment Type")?> :</div>
	         <div class="value"><?php echo strtoupper(t($data['payment_type']))?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Type"),
	         'value'=>strtoupper($data['payment_type'])
	       );
	       ?>
	       	       
	       <?php if ( $data['payment_provider_name']):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Card#")?> :</div>
	         <div class="value"><?php echo $data['payment_provider_name']?></div>
	         <div class="clear"></div>
	       </div>
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
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Paypal Transaction ID")?> :</div>
	         <div class="value"><?php echo isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:'';?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Paypal Transaction ID"),
	         'value'=>isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
	       );
	       ?>
	       <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Reference #")?> :</div>
	         <div class="value"><?php echo Yii::app()->functions->formatOrderNumber($data['order_id'])?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Reference #"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       
	       <?php if ( !empty($data['payment_reference'])):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Payment Ref")?> :</div>
	         <div class="value"><?php echo $data['payment_reference']?></div>
	         <div class="clear"></div>
	       </div>
	       <?php
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Ref"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <?php if ( $data['payment_type']=="ccr" || $data['payment_type']=="ocr"):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Card #")?> :</div>
	         <div class="value"><?php echo $card=Yii::app()->functions->maskCardnumber($data['credit_card_number'])?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Card #"),
	         'value'=>$card
	       );
	       ?>
	       <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","TRN Date")?> :</div>
	         <div class="value"><?php $trn_date=date('M d,Y G:i:s',strtotime($data['date_created']));
	         echo Yii::app()->functions->translateDate($trn_date);
	         ?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Date"),
	         'value'=>$trn_date
	       );
	       ?>
	       
	       <?php if ($data['trans_type']=="delivery"):?>
		       	       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_date'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Date")?> :</div>
		         <div class="value"><?php $deliver_date=prettyDate($_SESSION['kr_delivery_options']['delivery_date']);
		         echo Yii::app()->functions->translateDate($deliver_date);
		         ?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Date"),
		         'value'=>$deliver_date
		       );
		       ?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Time")?> :</div>
		         <div class="value"><?php echo $_SESSION['kr_delivery_options']['delivery_time']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Time"),
		         'value'=>$_SESSION['kr_delivery_options']['delivery_time']
		       );
		       ?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_asap'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_asap'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Deliver ASAP")?> :</div>
		         <div class="value">
		         <?php echo $delivery_asap=$_SESSION['kr_delivery_options']['delivery_asap']==1?t("Yes"):'';?></div>
		         <div class="clear"></div>
		       </div>
			   <?php 	       
				$print[]=array(
				 'label'=>Yii::t("default","Deliver ASAP"),
				 'value'=>$delivery_asap
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Deliver to")?> :</div>
		         <div class="value">
		         <?php 		         
		         if (!empty($data['client_full_address'])){
		         	echo $delivery_address=$data['client_full_address'];
		         } else echo $delivery_address=$data['full_address'];		         
		         ?></div>
		         <div class="clear"></div>
		       </div>
				<?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Deliver to"),
				  'value'=>$delivery_address
				);
				?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Instruction")?> :</div>
		         <div class="value"><?php echo $data['delivery_instruction']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Delivery Instruction"),
				  'value'=>$data['delivery_instruction']
				);
				?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Location Name")?> :</div>
		         <div class="value"><?php 
		         if (!empty($data['location_name1'])){
		         	$data['location_name']=$data['location_name1'];
		         }
		         echo $data['location_name'];
		         ?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Location Name"),
				  'value'=>$data['location_name']
				);
				?>
				
				<div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Contact Number")?> :</div>
		         <div class="value"><?php 
		         if ( !empty($data['contact_phone1'])){
		         	$data['contact_phone']=$data['contact_phone1'];
		         }
		         echo $data['contact_phone'];?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
				
				<?php if ($data['order_change']>=0.1):?>	
				<div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Change")?> :</div>
		         <div class="value"><?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?></div>
		         <div class="clear"></div>
		       </div>		       
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
		      <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Contact Number")?> :</div>
		         <div class="value"><?php echo $data['contact_phone']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
		       		     		  
		      <?php if (isset($_SESSION['kr_delivery_options']['delivery_date'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Pickup Date")?> :</div>
		         <div class="value"><?php echo $_SESSION['kr_delivery_options']['delivery_date']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Pickup Date"),
				  'value'=>$_SESSION['kr_delivery_options']['delivery_date']
				);
				?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Pickup Time")?> :</div>
		         <div class="value"><?php echo $_SESSION['kr_delivery_options']['delivery_time']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				 'label'=>Yii::t("default","Pickup Time"),
				 'value'=>$_SESSION['kr_delivery_options']['delivery_time']
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if ($data['order_change']>=0.1):?>	
				<div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Change")?> :</div>
		         <div class="value"><?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>$data['order_change']
				);
				?>
				<?php endif;?> 
		       
	       
	       <?php endif;?>
	       
	       <div class="spacer-small"></div>
	       
	       <?php echo $item_details=Yii::app()->functions->details['html'];?>
	     </div> <!--receipt-wrap-->
	     
	     <div class="print_wrap">
          <a class="print_element left" href="javascript:;"><i class="fa fa-print"></i> <?php echo Yii::t("default","Click here to print")?></a>       
          <div class="clear"></div>
        </div>	    
        
        </div>
     <?php else :?>
     <p class="uk-alert uk-alert-warning"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
     <?php $order_ok=false;?>
     <?php endif;?>
     </div>
    </div> <!--main-->
</div> <!--page-->

<?php 
$data_raw=Yii::app()->functions->details['raw'];
/*dump($data_raw);
die();*/
$receipt=EmailTPL::salesReceipt($print,Yii::app()->functions->details['raw']);
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

if (isset($_SESSION['api_token'])){	
	$api=new ApiFunctions();
	$api->deleteToken($_SESSION['api_token']);
}