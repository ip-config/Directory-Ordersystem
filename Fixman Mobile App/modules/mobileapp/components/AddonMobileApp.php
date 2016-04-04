<?php
class AddonMobileApp
{
	
	public static function moduleBaseUrl()
	{
		return Yii::app()->getBaseUrl(true)."/protected/modules/mobileapp";
	}

	public static function prettyCuisineList($cuisine_json='')
	{		
		if (!empty($cuisine_json)){
			$cuisine_json=!empty($cuisine_json)?json_decode($cuisine_json):false;
			if($cuisine_json!=False){
				$cuisine_list=Yii::app()->functions->Cuisine(true);					
				$cuisine='';
				foreach ($cuisine_json as $cuisine_id) {					
					if(array_key_exists($cuisine_id,(array)$cuisine_list)){
						$cuisine.=$cuisine_list[$cuisine_id].", ";
					}
				}
				return substr($cuisine,0,-2);
			}
		}
		return false;
	}
				
	public static function q($data)
	{
		return Yii::app()->db->quoteValue($data);
	}
	
    public static function t($message='')
	{
		return Yii::t("default",$message);
	}	
	
	public static function isMerchantOpen($merchant_id='')
	{
		$open = Yii::app()->functions->isMerchantOpen($merchant_id); 			 			
	    $preorder= Yii::app()->functions->getOption("merchant_preorder",$merchant_id);
			
		$now=date('Y-m-d');				 			
	    if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)){  
      	   if (in_array($now,(array)$m_holiday)){
      	   	  $open=false;
      	   }
        }
        
        if (!$open){
        	if($preorder){
        		$open=true;
        	}
        }
               
        return $open;
	}
	
	public static function isCashAvailable($merchant_id='')
	{
		$cod=self::t("Cash on delivery available");
        if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
        	$paymentgateway=getOptionA('paymentgateway');
        	$paymentgateway=!empty($paymentgateway)?json_decode($paymentgateway,true):false;
        	if($paymentgateway!=false){
        		if(!in_array('cod',(array)$paymentgateway)){
        			$cod='';
        		}
        	}
        } else {
        	if (getOption($merchant_id,'merchant_disabled_cod')!=""){
        		$cod='';
        	}
        }
        return $cod;
	}
	
	public static function getMerchantLogo($merchant_id='')
	{		
		if ( !$logo = getOption($merchant_id,'merchant_photo') ){			
			$logo = Yii::app()->functions->getOptionAdmin('mobile_default_image_not_available');
			if (empty($logo)){
			   $logo="mobile-default-logo.png";
			}
		}		
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload/";			
		if (file_exists($path_to_upload."/$logo")){
			return Yii::app()->getBaseUrl(true)."/upload/$logo";
		} 
		return self::moduleBaseUrl()."/assets/images/$logo";				
	}
	
	public static function getImage($image='')
	{		
		$default="mobile-default-logo.png";
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload/";				
		
		if (empty($image)){
			$image=Yii::app()->functions->getOptionAdmin('mobile_default_image_not_available');
		}	
		
		if (!empty($image)){			
			if (file_exists($path_to_upload."/$image")){							
				$default=$image;				
				$url = Yii::app()->getBaseUrl(true)."/upload/$default";
			} else $url=self::moduleBaseUrl()."/assets/images/$default";
		} else $url=self::moduleBaseUrl()."/assets/images/$default";
		return $url;
	}
	
	public static function merchantInformation($merchant_id='')
	{
		$data='';
		
		if ($merchantinfo=Yii::app()->functions->getMerchant($merchant_id)){
					
			$data['merchant_id']=$merchant_id;
			$data['restaurant_name']=$merchantinfo['restaurant_name'];
			$data['country']=Yii::app()->functions->countryCodeToFull($merchantinfo['country_code']);
			$data['address']=$merchantinfo['street']." ".$merchantinfo['city']." ".
			$merchantinfo['state']." ".$merchantinfo['post_code']." ".$data['country'];			
						
			/*check if mechant is open*/			
			$data['open']=AddonMobileApp::isMerchantOpen($merchant_id);
			$data['merchant_close_store']=getOption($merchant_id,'merchant_close_store')=="yes"?true:false;
			
			$minimum_order=getOption($merchant_id,'merchant_minimum_order');
			$minimum_order_raw=$minimum_order;
 			if(!empty($minimum_order)){
	 			$minimum_order=displayPrice(getCurrencyCode(),prettyFormat($minimum_order));
 			}
 			$data['minimum_order']=$minimum_order;
 			$data['minimum_order_raw']=$minimum_order_raw;
 			
 			$data['logo']=AddonMobileApp::getMerchantLogo($merchant_id);
 			
 			$delivery_fee=getOption($merchant_id,'merchant_delivery_charges');
 			$delivery_fee_raw=$delivery_fee;
 			if (!empty($delivery_fee)){
 				$delivery_fee=displayPrice(getCurrencyCode(),prettyFormat($delivery_fee));
 			}
 			$data['delivery_fee']=$delivery_fee;
 			$data['delivery_fee_raw']=$delivery_fee_raw;
			 			
 			$data['ratings']=Yii::app()->functions->getRatings($merchant_id);
 			
 			if ( $res_offers=Yii::app()->functions->getMerchantOffersActive($merchant_id)){ 				
 				unset($res_offers['date_created']);
 				unset($res_offers['date_modified']);
 				unset($res_offers['ip_address']);
 				$res_offers['message']=number_format($res_offers['offer_percentage'],0)."% ".
 				self::t("off today on orders over")." ".
 				displayPrice(getCurrencyCode(),prettyFormat($res_offers['offer_price']));
 				
 				$data['offers_found']=2;
 				$data['offers']=$res_offers;
 			} else $data['offers_found']=1;
 			
 			$data['free_delivery']=1;
 			$price_above=Yii::app()->functions->getOption("free_delivery_above_price",$merchant_id); 			
 			if(is_numeric($price_above) && $price_above>=1){
 				$data['free_delivery']=2;
 				$data['free_price']=$price_above;
 				$data['free_price_pretty']=displayPrice(getCurrencyCode(),prettyFormat($price_above));
 				$data['free_message']=self::t("Free Delivery On Orders Over")." ".
 				displayPrice(getCurrencyCode(),prettyFormat($price_above));
 			}
 			 			
 			$resto_cuisine='';
 			$cuisine_list=Yii::app()->functions->Cuisine(true);	  			
 			$cuisine=!empty($merchantinfo['cuisine'])?(array)json_decode($merchantinfo['cuisine']):false;  
 			if($cuisine!=false){
 				foreach ($cuisine as $valc) {	    						
					if ( array_key_exists($valc,(array)$cuisine_list)){
						$resto_cuisine.=$cuisine_list[$valc].", ";
					}				
				}
				$resto_cuisine=!empty($resto_cuisine)?substr($resto_cuisine,0,-2):'';
 			} 		 		
 			$data['cuisine']=$resto_cuisine;
 			
 			return $data; 	
		}
		return false;
	}
	
	public static function prettyPrice($amount='')
	{
		if(!empty($amount)){
			return displayPrice(getCurrencyCode(),prettyFormat($amount));
		}
		return 0;
	}
	
	public static function isArray($data='')
	{
		if (is_array($data) && count($data)>=1){
			return true;
		}
		return false;
	}	
	
	public static function getDeliveryCharges($merchant_id='',$unit='',$distance='')
	{				
		$delivery_fee=0;
		
		$default_delivery_charges=getOption($merchant_id,'merchant_delivery_charges');
				
		if ($default_delivery_charges<0){
			return false;
		}

		$FunctionsK=new FunctionsK();
		$delivery_fee=$FunctionsK->getDeliveryChargesByDistance(
    	$merchant_id,
    	$distance,
    	$unit,
    	$default_delivery_charges); 			
    	return array(
    	  'delivery_fee'=>$delivery_fee,
    	  'unit'=>$unit,
    	  'use_distance'=>$distance
    	);	
		
		return false;		
	}
	
	public static function getDistance($merchant_id='',$customer_address='')
	{
		$merchant_distance_type=Yii::app()->functions->getOption("merchant_distance_type",$merchant_id);
		
		$DbExt=new DbExt; 
		$stmt="SELECT concat(street,' ',city,' ',state,' ',post_code,' ',country_code) as merchant_address
		FROM
		{{merchant}}
		WHERE
		merchant_id=".self::q($merchant_id)."
		LIMIT 0,1
		";
		$merchant_address='';
		if($res=$DbExt->rst($stmt)){
			$merchant_address=$res[0]['merchant_address'];
		}
		
		$miles=getDeliveryDistance2($customer_address,$merchant_address); 
		if (self::isArray($miles)){			
			if ( $merchant_distance_type=="km"){
				$unit="km";
				$use_distance=$miles['km'];
			} else {
				$unit='miles';
				$use_distance=$miles['mi'];
			}
			if (preg_match("/ft/i",$miles['mi'])) {
				$unit='ft';
			}
			
			$use_distance=str_replace(array(
			 'ft','mi','km',','
			),'',$use_distance);
			
			return array(
			  'unit'=>$unit,
			  'distance'=>$use_distance
			);
		}	
		return false;
	}
	
	public static function parseValidatorError($error='')
	{
		$error_string='';
		if (is_array($error) && count($error)>=1){
			foreach ($error as $val) {
				$error_string.="$val\n";
			}
		}
		return $error_string;		
	}		
		
	public static function generateUniqueToken($length,$unique_text=''){	
		$key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));	
	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }	
	    return $key.md5($unique_text);
	}
			
	public static function computeCart($data='')
	{
		
		if ($data['transaction_type']=="null" || empty($data['transaction_type'])){
			$data['transaction_type']="delivery";
		}
		
		if ($data['delivery_date']=="null" || empty($data['delivery_date'])){
			$data['delivery_date']=date("Y-m-d");
		}
				
		$mtid=$data['merchant_id'];
		
		$cart_content='';
		$subtotal=0;
		$taxable_total=0;
		$item_total=0;
		
		Yii::app()->functions->data="list";
		$subcat_list=Yii::app()->functions->getSubcategory2($mtid);		
		
		$cart=json_decode($data['cart'],true);
		
		if (is_array($cart) && count($cart)>=1){
			foreach ($cart as $val) {
			    				    	
		    	/*group sub item*/
		    	$new_sub='';
		    	if (AddonMobileApp::isArray($val['sub_item'])){
		    		foreach ($val['sub_item'] as $valsubs) {			    			
		    			$new_sub[$valsubs['subcat_id']][]=array( 
		    			  'value'=>$valsubs['value'],
		    			  'qty'=>$valsubs['qty']
		    			);
		    		}
		    		$val['sub_item']=$new_sub;
		    	}		
		    				    				    				   
		    	$item_price=0;
		    	$item_size='';
		    	$temp_price=explode("|",$val['price']);			    	
		    	if (AddonMobileApp::isArray($temp_price)){
		    		$item_price=isset($temp_price[0])?$temp_price[0]:'';
		    		$item_size=isset($temp_price[1])?$temp_price[1]:'';
		    	}			    
		    		    	
		    	$food=Yii::app()->functions->getFoodItem($val['item_id']);			    	
		    	
		    	/*check if item qty is less than 1*/
		    	if($val['qty']<1){
		    		$val['qty']=1;
		    	}			    
		    	
		    	$discounted_price=0;
		    	if ($val['discount']>0){
		    		$discounted_price=$item_price-$val['discount'];
		    		$subtotal+=($val['qty']*$discounted_price);
		    	} else {
		    		$subtotal+=($val['qty']*$item_price);
		    	}			    
		    				    	
		    	//$subtotal+=($val['qty']*$item_price);
		    	if ( $food['non_taxable']==1){
		    	   $taxable_total=$subtotal;
		    	}
		    	
		    	$item_total+=$val['qty'];
		    				    	
		    	$sub_item='';
		    	if(is_array($val['sub_item']) && count($val['sub_item'])>=1){
		    		foreach ($val['sub_item'] as $sub_cat_id=> $valsub0) {			    			
		    			foreach ($valsub0 as $valsub) {				    				
			    			if(!empty($valsub['value'])){
			    				$sub=explode("|",$valsub['value']);
			    				
			    				if ( $valsub['qty']=="itemqty"){
			    				   $qty=$val['qty'];
			    				} else {
			    					$qty=$valsub['qty'];
			    					if ($qty<1){
			    						$qty=1;
			    						$valsub['qty']=1;
			    					}				    				
			    				}				    			
			    				
			    				$subitem_total=($qty*$sub[1]);
			    				$subtotal+=$subitem_total;
			    				if ( $food['non_taxable']==1){
			    				   $taxable_total+=$subitem_total;
			    				}
			    								    				
			    				$category_name='';
			    				if(array_key_exists($sub_cat_id,(array)$subcat_list)){
			    					$category_name=$subcat_list[$sub_cat_id];
			    				}			    			
			    				
			    				$sub_item[$category_name][]=array(
			    				  'subcat_id'=>$sub_cat_id,
			    				  'category_name'=>$category_name,
			    				  'sub_item_id'=>$sub[0],
			    				  'price'=>$sub[1],
			    				  'price_pretty'=>AddonMobileApp::prettyPrice($sub[1]),
			    				  'qty'=>$valsub['qty'],
			    				  'total'=>$subitem_total,
			    				  'total_pretty'=>AddonMobileApp::prettyPrice($subitem_total),
			    				  'sub_item_name'=>$sub[2]				    				  
			    				);
			    			}
		    			}
		    		}
		    	}
		    	
		    	$cooking_ref='';
		    	if (AddonMobileApp::isArray($val['cooking_ref'])){
		    		foreach ($val['cooking_ref'] as $valcook) {
		    			$cooking_ref[]=$valcook['value'];
		    		}
		    	}
		    	
		    	$ingredients='';
		    	if (AddonMobileApp::isArray($val['ingredients'])){
		    		foreach ($val['ingredients'] as $valing) {
		    			//$ingredients[]=$valing['ingredients'];
		    			$ingredients[]=$valing['value'];
		    		}
		    	}
		    	
		    	$cooking_ref='';
		    	if(AddonMobileApp::isArray($val['cooking_ref'])){
		    		$cooking_ref=$val['cooking_ref'][0]['value'];
		    	}
		    	$ingredients='';
		    	if(AddonMobileApp::isArray($val['ingredients'])){
		    		foreach ($val['ingredients'] as $val_ing) {
		    			$ingredients[]=$val_ing['value'];
		    		}
		    	}			    

		    	$discount_amt=0;
		    	if (isset($val['discount'])){
		    		$discount_amt=$val['discount'];
		    	}
			    	  			   
		    	$cart_content[]=array(
		    	  'item_id'=>$val['item_id'],
		    	  'item_name'=>$food['item_name'],
		    	  'item_description'=>$food['item_description'],
		    	  'qty'=>$val['qty'],
		    	  'price'=>$item_price,
		    	  'price_pretty'=>AddonMobileApp::prettyPrice($item_price),
		    	  'total'=>$val['qty']*($item_price-$discount_amt),
		    	  'total_pretty'=>AddonMobileApp::prettyPrice($val['qty']* ($item_price-$discount_amt) ),
		    	  'size'=>$item_size,		
		    	  'discount'=>isset($val['discount'])?$val['discount']:'',
			      'discounted_price'=>$discounted_price,
			      'discounted_price_pretty'=>AddonMobileApp::prettyPrice($discounted_price),	
		    	  'cooking_ref'=>$cooking_ref,
		    	  'ingredients'=>$ingredients,
		    	  'order_notes'=>$val['order_notes'],
		    	  'sub_item'=>$sub_item,
		    	  'non_taxable'=>$food['non_taxable']
		    	);
		    	
		    } /*end foreach*/
		    
            $ok_distance=2;
		    $delivery_charges=0;
		    $distance='';
		    
		    if ( $data['transaction_type']=="delivery"){					    			    	
		    	if($distance=AddonMobileApp::getDistance($mtid,$data['search_address'])){
		    	  $mt_delivery_miles=Yii::app()->functions->getOption("merchant_delivery_miles",$mtid); 	
		    	  if($mt_delivery_miles>0){
		    	  	 if ($mt_delivery_miles<=$distance['distance']){
		    	  	 	$ok_distance=1;
		    	  	 }
		    	  }
		    	  			    		
				  if($res_delivery=AddonMobileApp::getDeliveryCharges($mtid,$distance['unit'],$distance['distance'])){
					 $delivery_charges=$res_delivery['delivery_fee'];										
				  }
		    	}
		    }
			
			$merchant_tax_percent=0;
			$merchant_tax=getOption($mtid,'merchant_tax');			
			
            /*get merchant offers*/
	    	$discount='';
	    	if ( $offer=Yii::app()->functions->getMerchantOffersActive($mtid)){			    		
	    		$merchant_spend_amount=$offer['offer_price'];
	        	$merchant_discount_amount=number_format($offer['offer_percentage'],0);			        	
	        	if ( $subtotal>=$merchant_spend_amount){
	        		$merchant_discount_amount1=$merchant_discount_amount/100;
	        		$discounted_amount=$subtotal*$merchant_discount_amount1;
	        		
	        		$subtotal-=$discounted_amount;
	        		if ( $food['non_taxable']==1){
	        		    $taxable_total-=$discounted_amount;
	        		}		        		
	        		$discount=array(
	        		  'discount'=>$merchant_discount_amount,
	        		  'amount'=>$discounted_amount,
	        		  'amount_pretty'=>AddonMobileApp::prettyPrice($discounted_amount),
	        		  'display'=>self::t("Discount")." ".number_format($offer['offer_percentage'],0)."%"
	        		);
	        	}
	    	}			
						    	
	        /*check if has offer for free delivery*/
	    	$free_delivery_above_price=getOption($mtid,'free_delivery_above_price');
	        if(is_numeric($free_delivery_above_price)){
	        	if ($subtotal>=$free_delivery_above_price){
	        		$delivery_charges=0;
	        	}			        
	        }
	        
	        /*packaging*/		        
	        $merchant_packaging_charge=getOption($mtid,'merchant_packaging_charge');
	        if ($merchant_packaging_charge>0){
	        	if ( getOption($mtid,'merchant_packaging_increment')==2){		 		      		        		
	        		$merchant_packaging_charge=$merchant_packaging_charge*$item_total;
	        	}
	        } else $merchant_packaging_charge=0;	
	        	        	        
	        //check if has voucher	        
	        if (isset($data['voucher_code'])){
	        	if (!empty($data['voucher_amount'])){
	        		if ( $data['voucher_type']=="fixed amount"){
	        			$subtotal=$subtotal-$data['voucher_amount'];
	        			$taxable_total=$taxable_total-$data['voucher_amount'];
	        		} else {
	        			$voucher_percent=$subtotal*($data['voucher_amount']/100);	        			
	        			$subtotal=$subtotal-$voucher_percent;
	        			$taxable_total=$taxable_total-$voucher_percent;
	        		}	        	
	        	}	        
	        }			        

	        /*dump("taxable_total=>$taxable_total");        
	        dump("delivery_charges=>$delivery_charges");
	        dump("merchant_packaging_charge=>$merchant_packaging_charge");
	        dump($taxable_total+$delivery_charges+$merchant_packaging_charge);*/
	        
           /*get the tax*/
	        $tax=0;
	        if ( $merchant_tax>0){
	        	$merchant_tax_charges=getOption($mtid,'merchant_tax_charges');
	        	if ( $merchant_tax_charges==2){	        		
	        		$tax=($taxable_total+$merchant_packaging_charge)*($merchant_tax/100);
	        	} else $tax=($taxable_total+$delivery_charges+$merchant_packaging_charge)*($merchant_tax/100);
	        }			    
	        
	        		        			    
			$cart_final_content=array(
			  'cart'=>$cart_content,
			  'sub_total'=>array(
			    'amount'=>$subtotal,
			    'amount_pretty'=>AddonMobileApp::prettyPrice($subtotal)
			  )			      
			);				
							
			if (AddonMobileApp::isArray($discount)){
				$cart_final_content['discount']=$discount;
			}

			if ($delivery_charges>0){
				$cart_final_content['delivery_charges']=array(
				  'amount'=>$delivery_charges,
				  'amount_pretty'=>AddonMobileApp::prettyPrice($delivery_charges)
				);
			}
			if ($merchant_packaging_charge>0){
				$cart_final_content['packaging']=array(
				  'amount'=>$merchant_packaging_charge,
				  'amount_pretty'=>AddonMobileApp::prettyPrice($merchant_packaging_charge)
				);					
			}
			if ($tax>0){
				$cart_final_content['tax']=array(
				  'tax'=>$merchant_tax>0?$merchant_tax/100:0,
				  'amount_raw'=>$tax,
				  'amount'=>AddonMobileApp::prettyPrice($tax),
				  'tax_pretty'=>self::t("Tax")." ".$merchant_tax."%"
				);					
			}
			
			$grand_total=$subtotal+$delivery_charges+$merchant_packaging_charge+$tax;
			$cart_final_content['grand_total']=array(
			  'amount'=>$grand_total,
			  'amount_pretty'=>AddonMobileApp::prettyPrice($grand_total)
			);
			
			/*validation*/																
			$validation_msg='';
							
			if ( $data['transaction_type']=="delivery"){
			if ($ok_distance==1){
				$distanceOption=Yii::app()->functions->distanceOption();
				$validation_msg=t("Sorry but this merchant delivers only with in ").
				getOption($mtid,'merchant_delivery_miles')." ".$distanceOption[getOption($mtid,'merchant_distance_type')];
			}
			}
			
			if ( $data['transaction_type']=="delivery"){
				/*delivery*/
				$minimum_order=getOption($mtid,'merchant_minimum_order');
			    $maximum_order=getOption($mtid,'merchant_maximum_order');
			    if(is_numeric($minimum_order)){				    	
			    	if ($subtotal<=$minimum_order){
			    		$validation_msg=self::t("Sorry but Minimum order is")." ".AddonMobileApp::prettyPrice($minimum_order);
			    	}				    
			    }
			    if(is_numeric($maximum_order)){				    	
			    	if ($subtotal>=$maximum_order){
			    		$validation_msg=self::t("Maximum Order is")." ".AddonMobileApp::prettyPrice($maximum_order);
			    	}				    
			    }				    				    
			} else {
				/*pickup*/
				$minimum_order_pickup=getOption($mtid,'merchant_minimum_order_pickup');
			    $maximum_order_pickup=getOption($mtid,'merchant_maximum_order_pickup');
			    if(is_numeric($minimum_order_pickup)){				    	
			    	if ($subtotal<=$minimum_order_pickup){
			    		$validation_msg=self::t("sorry but the minimum pickup order is")." ".
			    		AddonMobileApp::prettyPrice($minimum_order_pickup);
			    	}				    
			    }
			    if(is_numeric($maximum_order_pickup)){				    	
			    	if ($subtotal>=$maximum_order_pickup){
			    		$validation_msg=self::t("sorry but the maximum pickup order is")." ".
			    		AddonMobileApp::prettyPrice($maximum_order_pickup);
			    	}				    
			    }
			}			
			
			return array(			  
			  'cart'=>$cart_final_content,
			  'validation_msg'=>$validation_msg,
			  'distance'=>$distance
			);
				           	    
		} /*end is array*/
		
		return false;
	}
	
	public static function cartMobile2WebFormat($data='',$post_data='')
	{
		//dump($post_data);
		$json_data='';
		if (self::isArray($data['cart']['cart'])){
			foreach ($data['cart']['cart'] as $val) {				
				$sub_item=''; $addon_qty=''; $addon_ids='';
				if (self::isArray($val['sub_item'])){
					foreach ($val['sub_item'] as $key=>$val2) {								
						$addon_item='';	
						$addon_qtys='';
						foreach ($val2 as $val3) {							
							/*$sub_item[$val3['subcat_id']]=array(
							     $val3['sub_item_id']."|".$val3['price']."|".$val3['sub_item_name']
							);*/			
							$addon_item[]=$val3['sub_item_id']."|".$val3['price']."|".$val3['sub_item_name'];
							//$addon_qty[]=$val3['qty']=="itemqty"?$val['qty']:$val3['qty'];
							$addon_qtys[]=$val3['qty']=="itemqty"?$val['qty']:$val3['qty'];
							$addon_ids[]=$val3['sub_item_id'];
						}						
						$addon_qty[$val3['subcat_id']]=$addon_qtys;
						$sub_item[$val3['subcat_id']]=$addon_item;
					}
				}
				
				$json_data[]=array(
				  'item_id'=>$val['item_id'],
				  'merchant_id'=>$post_data['merchant_id'],
				  'discount'=>$val['discount'],
				  'price'=>$val['price']."|".$val['size'],
				  'qty'=>$val['qty'],
				  'cooking_ref'=>$val['cooking_ref'],
				  'ingredients'=>$val['ingredients'],
				  'notes'=>$val['order_notes'],
				  'order_notes'=>$val['order_notes'],				  
				  'sub_item'=>$sub_item,
				  'addon_qty'=>$addon_qty,
				  'addon_ids'=>$addon_ids,
				  'non_taxable'=>isset($val['non_taxable'])?$val['non_taxable']:1
				);
			}
			//dump($json_data);
			return $json_data;
		}
		return json_encode(array());
	}
	
	public static function getClientTokenInfo($token='')
	{
		$DbExt=new DbExt; 
		$stmt="
		SELECT * FROM
		{{client}}
		WHERE
		token=".self::q($token)."
		LIMIT 0,1
		";				
		if ($res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
	}
	
	public static function getMerchantPaymentMethod($mtid='')
	{
		$merchant_payment_list='';
				
		$mobile_payment=array('cod','paypal','pyr');
			
		$payment_list=getOptionA('paymentgateway');
		$payment_list=!empty($payment_list)?json_decode($payment_list,true):false;		
		
		$pay_on_delivery_flag=false;
		
		if (AddonMobileApp::isArray($payment_list)){			
			foreach ($mobile_payment as $val) {
				if(in_array($val,(array)$payment_list)){					
					switch ($val) {
						case "cod":			
						    if (Yii::app()->functions->isMerchantCommission($mtid)){
						    	$merchant_payment_list[]=array(
								  'icon'=>'fa-usd',
								  'value'=>$val,
								  'label'=>self::t("Cash On delivery")
								);
						    	continue;
						    }
							if ( getOption($mtid,'merchant_disabled_cod')!="yes"){
								$merchant_payment_list[]=array(
								  'icon'=>'fa-usd',
								  'value'=>$val,
								  'label'=>self::t("Cash On delivery")
								);
							}
							break;
					
						case "paypal":	
						  
						  if (Yii::app()->functions->isMerchantCommission($mtid)){
						  	  if ( getOptionA('adm_paypal_mobile_enabled')=="yes"){
						  	  	$merchant_payment_list[]=array(
							       'icon'=>'fa-paypal',
							        'value'=>$val,
							        'label'=>self::t("Paypal")
							    );
						  	  }						  
						  	  continue;
						  }
						  
						  if (getOption($mtid,'mt_paypal_mobile_enabled') =="yes"){
						      $merchant_payment_list[]=array(
							     'icon'=>'fa-paypal',
							     'value'=>$val,
							     'label'=>self::t("Paypal")
							  );
						   }
						   break;
						
						case "pyr":	
						    $pay_on_delivery_flag=true;
						   if (Yii::app()->functions->isMerchantCommission($mtid)){
						   	   $merchant_payment_list[]=array(
							    'icon'=>'fa-cc-visa',
							    'value'=>$val,
							    'label'=>self::t("Pay On Delivery")
							   );
						   	   continue;
						   }
						   if ( getOption($mtid,'merchant_payondeliver_enabled')=="yes"){
						      $merchant_payment_list[]=array(
							    'icon'=>'fa-cc-visa',
							    'value'=>$val,
							    'label'=>self::t("Pay On Delivery")
							  );
						   }
						   break;
						         
						default:
							break;
					}					
				}			
			}
			
			if (AddonMobileApp::isArray($merchant_payment_list)){				
				return $merchant_payment_list;
			}	
		} 
		return false;
	}
	
	public static function getOperationalHours($merchant_id='')
	{
        $stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
		$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
		$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);
		
		$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
		$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
		$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
		$stores_open_custom_text=!empty($stores_open_custom_text)?(array)json_decode($stores_open_custom_text):false;
		
		
		$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
		$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;
		
		$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
		$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;		
						
		$tip='';						
		$open_starts='';
		$open_ends='';
		$open_text='';		
		if (is_array($stores_open_day) && count($stores_open_day)>=1){
			foreach ($stores_open_day as $val_open) {	
				if (array_key_exists($val_open,(array)$stores_open_starts)){
					$open_starts=timeFormat($stores_open_starts[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_ends)){
					$open_ends=timeFormat($stores_open_ends[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_custom_text)){
					$open_text=$stores_open_custom_text[$val_open];
				}					
				
				$pm_starts=''; $pm_ends=''; $pm_opens='';
				if (array_key_exists($val_open,(array)$stores_open_pm_start)){
					$pm_starts=timeFormat($stores_open_pm_start[$val_open],true);
				}											
				if (array_key_exists($val_open,(array)$stores_open_pm_ends)){
					$pm_ends=timeFormat($stores_open_pm_ends[$val_open],true);
				}								
							
				$full_time='';
				if (!empty($open_starts) && !empty($open_ends)){					
					$full_time=$open_starts." - ".$open_ends."&nbsp;&nbsp;";
				}			
				if (!empty($pm_starts) && !empty($pm_ends)){
					if ( !empty($full_time)){
						$full_time.=" / ";
					}				
					$full_time.="$pm_starts - $pm_ends";
				}
																				
				$tip.= ucwords(self::t($val_open))." ".$full_time." ".$open_text."<br/>";
				
				$open_starts='';
		        $open_ends='';
		        $open_text='';
			}
		} else $tip=self::t("Not available.");
		return $tip;
	}	
	
	public static function previewMerchantReview($mtid='')
	{
		$DbExt=new DbExt; 
		$stmt="
		SELECT a.client_id,
		a.date_created,
		(
		  select first_name
		  from {{client}}
		  where
		  client_id=a.client_id
		) as client_name
		,
		(
		  select count(*) as total_review
		  from {{review}}
		  where
		  merchant_id=".self::q($mtid)."
		) as total_review
		 FROM
		{{review}} a
		WHERE
		merchant_id=".self::q($mtid)."
		AND status IN ('publish','published')
		ORDER BY date_created DESC
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function getOrderDetails($order_id='')
	{
		$DbExt=new DbExt; 
		$stmt="
		SELECT a.item_id, a.item_name,
		a.qty,
		(
		  select total_w_tax 
		  from {{order}}
		  where
		  order_id= a.order_id
		) as total_w_tax
		FROM
		{{order_details}} a
		WHERE
		order_id=".self::q($order_id)."
		ORDER BY id ASC
		";
		if ($res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}
	
    public static function getAddressBook($client_id='')
    {
    	$db_ext=new DbExt;    	
    	/*$stmt="SELECT  
    	       concat(street,' ',city,' ',state,' ',zipcode) as address,
    	       id,location_name,country_code,as_default,
    	       street,city,state,zipcode,location_name
    	       FROM
    	       {{address_book}}
    	       WHERE
    	       client_id =".self::q($client_id)."
    	       ORDER BY id DESC    	       
    	";*/    	    	
    	$stmt="SELECT  
    	       concat(street,' ',city,' ',state,' ',zipcode) as address,
    	       a.id,
    	       a.location_name,
    	       a.country_code,
    	       a.as_default,
    	       a.street,
    	       a.city,
    	       a.state,
    	       a.zipcode,
    	       a.location_name,
    	       (
    	       select contact_phone from {{client}} where client_id=a.client_id limit 0,1
    	       ) as contact_phone
    	       FROM
    	       {{address_book}} a
    	       WHERE
    	       client_id =".self::q($client_id)."
    	       ORDER BY id DESC    	       
    	";
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;
    } 	        	
    
    public static function hasAddressBook($client_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT * FROM
    	{{address_book}}
    	WHERE client_id =".self::q($client_id)."
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;
    }
    
    public static function checkifEmailExists($email='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT client_id,email_address,first_name,last_name,
    	password FROM
    	{{client}}
    	WHERE email_address =".self::q($email)."
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }
    
    public static function getDeviceID($device_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT * FROM
    	{{mobile_registered}}
    	WHERE
    	device_id=".self::q($device_id)."
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }
    
   public static function getMerchantInfo($merchant_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT * FROM
    	{{merchant}}
    	WHERE
    	merchant_id=".self::q($merchant_id)."
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }    
    
    public static function updateDeviceInfo($device_id='',$client_id='')
    {    	    	
    	$db_ext=new DbExt;   
         if (!empty($client_id)){
         	if (!empty($device_id)){
         		$params=array(
         		  'client_id'=>$client_id,
         		  'date_modified'=>date('c'),
         		  'ip_address'=>$_SERVER['REMOTE_ADDR']
         		);         		
         		if ( $res=self::getDeviceID($device_id)){
         			//if ( $res['client_id']<1){
         				$db_ext->updateData("{{mobile_registered}}",$params,'device_id',$device_id);
         			//}
         		}
         	}
         }
    }
    
    public static function getDeviceByID($id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT * FROM
    	{{mobile_registered_view}}
    	WHERE
    	id=".self::q($id)."
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }    
    
    public static function sendPush($platform='Android',$api_key='',$device_id='',$message='')
    {    	
    	if (empty($api_key)){
    		return array(
    		  'success'=>0,
    		  'results'=>array(
    		     array(
    		       'error'=>'missing api key'
    		     )
    		  )
    		);
    	}
    	if (empty($device_id)){
    		return array(
    		  'success'=>0,
    		  'results'=>array(
    		     array(
    		       'error'=>'missing device id'
    		     )
    		  )
    		);
    	}
    	    	
    	$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
           'registration_ids' => array($device_id),
           'data' => $message,
        );
        //dump($fields);
        
        $headers = array(
		  'Authorization: key=' . $api_key,
		  'Content-Type: application/json'
        );
        //dump($headers);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));		
		$result = curl_exec($ch);
		if ($result === FALSE) {
		    //die('Curl failed: ' . curl_error($ch));
		   return array(
    		  'success'=>0,
    		  'results'=>array(
    		     array(
    		       'error'=>'Curl failed: '. curl_error($ch)
    		     )
    		  )
    		);
		}
		
        curl_close($ch);
        //echo $result; 
        $result=!empty($result)?json_decode($result,true):false;
        //dump($result);
        if ($result==false){
        	return array(
    		  'success'=>0,
    		  'results'=>array(
    		     array(
    		       'error'=>'invalid response from push service'
    		     )
    		  )
    		);
        }
        return $result;   
    }
    
    public static function savedOrderPushNotification($data='')
    {    	
    	if (!self::isArray($data)){
    		return ;
    	}
    	    
    	$push_title=getOptionA('mobile_push_order_title');
    	$push_message=getOptionA('mobile_push_order_message');    	
    	
    	if (empty($push_message)){
    		$push_message=$data['remarks'];
    	}
    	
    	if (empty($push_title)){
    		return ;
    	}
    	if (empty($push_message)){
    		return ;
    	}
    	
    	$push_title=Yii::app()->functions->smarty('order_id',$data['order_id'],$push_title);
    	$push_title=Yii::app()->functions->smarty('order_status',$data['status'],$push_title);
    	
    	$push_message=Yii::app()->functions->smarty('order_id',$data['order_id'],$push_message);
    	$push_message=Yii::app()->functions->smarty('order_status',$data['status'],$push_message);
    	
    	$stmt="
    	SELECT a.order_id,
    	a.merchant_id,
    	a.client_id,
    	b.client_name,
    	b.device_platform,
    	b.device_id,
    	b.enabled_push    	
    	FROM
    	{{order}} a
    	LEFT JOIN {{mobile_registered_view}} b
    	ON
    	a.client_id=b.client_id
    	WHERE
    	order_id=".q($data['order_id'])."
    	LIMIT 0,1
    	";
    	$db_ext=new DbExt; 
    	if ( $res=$db_ext->rst($stmt)){
    		$val=$res[0];    		
    		if ($val['enabled_push']==1){
    			$params=array(
    			  'client_id'=>$val['client_id'],
    			  'client_name'=>$val['client_name'],
    			  'device_platform'=>$val['device_platform'],
    			  'device_id'=>$val['device_id'],
    			  'push_title'=>$push_title,
    			  'push_message'=>$push_message,
    			  'date_created'=>date('c'),
    			  'ip_address'=>$_SERVER['REMOTE_ADDR']
    			);    			
    			$db_ext->insertData("{{mobile_push_logs}}",$params);
    		}    	
    	}
    }
    
    public static function sendOrderSMS($data='',$info='',$order_id='',$full_data='',$ok_send_notification=true)
    {    	    	
    	if (!self::isArray($data)){
    	    return false;
    	}
    	    	    
    	$merchant_id=isset($info['merchant_id'])?$info['merchant_id']:'';
    	    	
    	if (!is_numeric($merchant_id)){
    		return false;
    	}    
    	
    	$sms_enabled_alert=Yii::app()->functions->getOption("sms_enabled_alert",$merchant_id);
		if ($sms_enabled_alert!=1){
			return false;
		}
				
    	if (isset($data['cart'])){
    		
    		$db_ext=new DbExt; 
    		
    		$sms_provider=Yii::app()->functions->getOptionAdmin('sms_provider');    	    	    	    	
            $sms_provider=strtolower($sms_provider);
    		
            $merchant_notify_number=Yii::app()->functions->getOption("sms_notify_number",$merchant_id);
            $merchant_sms_tpl=Yii::app()->functions->getOption("sms_alert_message",$merchant_id);
                		
    		$client_info=Yii::app()->functions->getClientInfo($info['client_id']);    
    		if ($client_info){
    		    $client_fullname=$client_info['first_name']." ".$client_info['last_name'];
    		} else $client_fullname='';
    		
    		if (!empty($merchant_sms_tpl) && !empty($merchant_notify_number) ){
	    		$item_order='';
	    		$in_msg=t("OrderNo:").$order_id." ";
	            $in_msg.=t("ClientName:").$client_fullname; 
	            $in_msg.=" ";
	            
	    		foreach ($data['cart'] as $val) {	    			
	    			$item_order.="(".$val['qty']."x)".$val['item_name']." ".$val['order_notes'].", ";
	    			if (is_array($val['sub_item']) && count($val['sub_item'])>=1){ 
	    				foreach ($val['sub_item'] as $subcategory=>$sub_val) {      	    				   
	    				   if(self::isArray($sub_val)){	    				   	   
		    				   foreach ($sub_val as $sub_val2) {	
		    				   	   $sub_val2['qty']=$sub_val2['qty']=="itemqty"?$val['qty']:$sub_val2['qty'];
			    				   $item_order.=$sub_val2['category_name'].":";
		        				   $item_order.="(".$sub_val2['qty']."x)".$sub_val2['sub_item_name'];
		        				   $item_order.=",";		        				   
		    				   }		    				   
	    				   }
	    				}
	    			}	
	    		}
	    			    		
	    		$item_order=substr($item_order,0,-1);    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("receipt",$in_msg.$item_order,$merchant_sms_tpl); 
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("orderno",
	    		$order_id,$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("customername",
	    		$client_fullname,$merchant_sms_tpl);
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("customer-name",
	    		$client_fullname,$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("payment-type",
	    		$info['payment_type'],$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("transaction-type",
	    		$info['trans_type'],$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("delivery-instruction",
	    		$full_data['delivery_instruction'],$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("delivery-date",
	    		Yii::app()->functions->translateDate(Yii::app()->functions->FormatDateTime($info['delivery_date']))
	    		,$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("delivery-time",
	    		$info['delivery_time'],$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("order-change",
	    		displayPrice(adminCurrencySymbol(),
	    		Yii::app()->functions->standardPrettyFormat($info['order_change']))
	    		,$merchant_sms_tpl);
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("customermobile",
	    		$full_data['contact_phone'],$merchant_sms_tpl);  		
	    		
	    		$customer_address=$full_data['street']." ";
	    		$customer_address.=$full_data['city']." ";
	    		$customer_address.=$full_data['state']." ";
	    		$customer_address.=$full_data['zipcode']." ";
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("customeraddress",
	    		$customer_address,$merchant_sms_tpl);  		
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("amount",
	    		$info['total_w_tax'],$merchant_sms_tpl);  		
	    		
	    		$merchant_sms_tpl=Yii::app()->functions->smarty("website-ddress",
	    		websiteUrl(),$merchant_sms_tpl);  		
	    			    			    		
	    		/*send sms to merchant*/	    		
	    		$merchant_notify_number=explode(",",$merchant_notify_number);
	    		if (self::isArray($merchant_notify_number)){
	    			foreach ($merchant_notify_number as $merchant_number) {	    				
	    				$balance=Yii::app()->functions->getMerchantSMSCredit($merchant_id);	    				
	    				if (is_numeric($balance) && $balance>=1){
	    					
	    					if ($ok_send_notification){	    						    					
		    					$resp=Yii::app()->functions->sendSMS($merchant_number,$merchant_sms_tpl);
					        	$params=array(
					        	  'merchant_id'=>$merchant_id,
					        	  'broadcast_id'=>"999999999",
					        	  'client_id'=>$info['client_id'],
					        	  'client_name'=>$client_fullname,
					        	  'contact_phone'=>$merchant_number,
					        	  'sms_message'=>$merchant_sms_tpl,
					        	  'status'=>$resp['msg'],
					        	  'gateway_response'=>$resp['raw'],
					        	  'date_created'=>date('c'),
					        	  'date_executed'=>date('c'),
					        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
					        	  'gateway'=>$sms_provider
					        	);				        	        
					        	$db_ext->insertData("{{sms_broadcast_details}}",$params);	
	    					} else {
	    						// saved sms for future sending
	    						$params=array(
	    						  'email_type'=>"sms",
	    						  'order_id'=>$order_id,
	    						  'client_email'=>$merchant_number,
	    						  'tpl'=>$merchant_sms_tpl,
	    						  'merchant_id'=>$merchant_id,
	    						  'client_name'=>$client_fullname,
	    						  'gateway'=>$sms_provider
	    						);
	    						$db_ext->insertData("{{mobile_temp_email}}",$params);	
	    					}     	
	    				}
	    			}
	    		}	    		    
    		}
    		
    		/*send sms to customer*/
    		$sms_tpl=getOption($merchant_id,'sms_alert_customer');    		
    		$client_contact_phone=$full_data['contact_phone'];    		
    		if (!empty($sms_tpl) && !empty($client_contact_phone)){
    			
    			$sms_tpl=Yii::app()->functions->smarty("customer-name",
	    		$client_fullname,$sms_tpl);  		
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("orderno",
	    		$order_id,$sms_tpl);  		
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("website-address",
	    		websiteUrl(),$sms_tpl);  		
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("payment-type",
	    		$info['payment_type'],$sms_tpl);  	
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("transaction-type",
	    		$info['trans_type'],$sms_tpl);	
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("delivery-instruction",
	    		$full_data['delivery_instruction'],$sms_tpl);
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("delivery-date",
	    		Yii::app()->functions->translateDate(Yii::app()->functions->FormatDateTime($info['delivery_date']))
	    		,$sms_tpl);
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("delivery-time",
	    		$info['delivery_time'],$sms_tpl);
	    		
	    		$sms_tpl=Yii::app()->functions->smarty("order-change",
	    		displayPrice(adminCurrencySymbol(),
	    		Yii::app()->functions->standardPrettyFormat($full_data['order_change']))
	    		,$sms_tpl);
	    		
	    		if ($merchant_info=self::getMerchantInfo($merchant_id)){
	    			$sms_tpl=Yii::app()->functions->smarty("merchantname",
	    		    $merchant_info['restaurant_name'],$sms_tpl);
	    		    
	    		    $sms_tpl=Yii::app()->functions->smarty("merchantphone",
	    		    $merchant_info['restaurant_phone'],$sms_tpl);
	    		    
	    		    $merchant_address=$merchant_info['street']." ";
	    		    $merchant_address.=$merchant_info['city']." ";
	    		    $merchant_address.=$merchant_info['state']." ";
	    		    $merchant_address.=$merchant_info['post_code']." ";
	    		    
	    		    $sms_tpl=Yii::app()->functions->smarty("merchant-address",
	    		    $merchant_address,$sms_tpl);
	    		}    			    			    	
	    			    		
	    		$balance=Yii::app()->functions->getMerchantSMSCredit($merchant_id);		    		
	    		if (is_numeric($balance) && $balance>=1){
	    			
	    			if ($ok_send_notification){
		    			$resp2=Yii::app()->functions->sendSMS($client_contact_phone,$sms_tpl);
		        		$params=array(
			        	  'merchant_id'=>$merchant_id,
			        	  'broadcast_id'=>"999999999",
			        	  'client_id'=>$info['client_id'],
			        	  'client_name'=>$client_fullname,
			        	  'contact_phone'=>$client_contact_phone,
			        	  'sms_message'=>$sms_tpl,
			        	  'status'=>$resp2['msg'],
			        	  'gateway_response'=>$resp2['raw'],
			        	  'date_created'=>date('c'),
			        	  'date_executed'=>date('c'),
			        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        	  'gateway'=>$sms_provider
			        	);	  			        	
			        	$db_ext->insertData("{{sms_broadcast_details}}",$params);
	    			} else {	    				
	    				// saved sms for future sending
	    				$params=array(
						  'email_type'=>"sms",
						  'order_id'=>$order_id,
						  'client_email'=>$client_contact_phone,
						  'tpl'=>$sms_tpl,
						  'client_id'=>$info['client_id'],
						  'merchant_id'=>$merchant_id,
						  'client_name'=>$client_fullname,
						  'gateway'=>$sms_provider
						);
						$db_ext->insertData("{{mobile_temp_email}}",$params);	
	    			}	        	
	    		}
    		}    
    		return true;	    		
    	}       	
    	return false;
    }
    
    public static function sendOrderEmail($data='',$info='',$order_id='',$full_data='',$ok_send_notification=true)
    {
    	
    	if (!self::isArray($data)){
    	    return false;
    	}
    	    	        	
    	$merchant_id=isset($info['merchant_id'])?$info['merchant_id']:'';
    	    	
    	if (!is_numeric($merchant_id)){
    		return false;
    	}    
    	    		      
        if($client_info=Yii::app()->functions->getClientInfo($info['client_id'])){
	        $client_fullname=$client_info['first_name']." ".$client_info['last_name'];
	        $client_email=$client_info['email_address'];
        } else {
        	$client_fullname='';
        	$client_email='';
        }    
                
        if($merchant_info=self::getMerchantInfo($merchant_id)){        
	        $merchant_address=$merchant_info['street']." ";
		    $merchant_address.=$merchant_info['city']." ";
		    $merchant_address.=$merchant_info['state']." ";
		    $merchant_address.=$merchant_info['post_code']." ";
        } else $merchant_address='';
	    
        if (isset($full_data['street']) && isset($full_data['city'])){
		    $customer_address=$full_data['street']." ";
			$customer_address.=$full_data['city']." ";
			$customer_address.=$full_data['state']." ";
			$customer_address.=$full_data['zipcode']." ";
        } else $customer_address='';
		    		        
        $print[]=array(
          'label'=>Yii::t("default","Customer Name"),
          'value'=>$client_fullname
        );
                
        $print[]=array(
	         'label'=>Yii::t("default","Merchant Name"),
	         'value'=>$merchant_info['restaurant_name']
	       );
	       
	    if (!empty($merchant_info['abn'])){
		  $print[]=array(
	         'label'=>Yii::t("default","ABN"),
	         'value'=>$merchant_info['abn']
	       );		
	    }
		$print[]=array(
	         'label'=>Yii::t("default","Telephone"),
	         'value'=>$merchant_info['restaurant_phone']
	       );		
		$print[]=array(
	         'label'=>Yii::t("default","Address"),
	         'value'=>$merchant_address
	       );		
		$print[]=array(
	         'label'=>Yii::t("default","TRN Type"),
	         'value'=>$info['trans_type']
	       );		
		$print[]=array(
	         'label'=>Yii::t("default","Payment Type"),
	         'value'=>strtoupper($info['payment_type'])
	       );				
	       	       
	    if ( isset($info['payment_provider_name'])){
	    	$print[]=array(
	         'label'=>Yii::t("default","Card#"),
	         'value'=>strtoupper($info['payment_provider_name'])
	       );
	    }   
	    	    
	    if ( $info['payment_type'] =="pyp"){
	    	$paypal_info=Yii::app()->functions->getPaypalOrderPayment($order_id);
	    	if ($paypal_info){
		    	$print[]=array(
		         'label'=>Yii::t("default","Paypal Transaction ID"),
		         'value'=>isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
		        );
	    	}
	    }    
	    
	    $print[]=array(
          'label'=>Yii::t("default","Reference #"),
          'value'=>Yii::app()->functions->formatOrderNumber($order_id)
        );

        $trn_date=date('M d,Y G:i:s',strtotime($info['date_created']));
	    $trn_date= Yii::app()->functions->translateDate($trn_date);
	         
        $print[]=array(
         'label'=>Yii::t("default","TRN Date"),
         'value'=>$trn_date
        );       
        
        if ($info['trans_type']=="delivery"){
        	$print[]=array(
		         'label'=>Yii::t("default","Delivery Date"),
		         'value'=>$info['delivery_date']
		     );        
                
	        if (isset($info['delivery_time'])){
	        	if (!empty($info['delivery_time'])){
	        		$print[]=array(
			         'label'=>Yii::t("default","Delivery Time"),
			         'value'=>$info['delivery_time']
			       );
	        	}     
	        } 
	        		        
	        $print[]=array(
			  'label'=>Yii::t("default","Deliver to"),
			  'value'=>$customer_address
			);
					
			$print[]=array(
			  'label'=>Yii::t("default","Delivery Instruction"),
			  'value'=>$info['delivery_instruction']
			);
			
			$print[]=array(
			  'label'=>Yii::t("default","Location Name"),
			  'value'=>$full_data['location_name']
			);
			
			$print[]=array(
			  'label'=>Yii::t("default","Contact Number"),
			  'value'=>$full_data['contact_phone']
			);
			
			if (isset($full_data['order_change'])){
			   if ($full_data['order_change']>0){
			   	    $print[]=array(
					  'label'=>Yii::t("default","Change"),
					  'value'=>normalPrettyPrice($full_data['order_change'])
					);
			   }		
			}    		
			
        } else {
        	//pickup
        	
        	if (isset($full_data['contact_phone'])){
        	$print[]=array(
			  'label'=>Yii::t("default","Contact Number"),
			  'value'=>$full_data['contact_phone']
			);
        	}
			
			$print[]=array(
		         'label'=>Yii::t("default","Pickup Date"),
		         'value'=>$info['delivery_date']
		     );        
                
	        if (isset($info['delivery_time'])){
	        	if (!empty($info['delivery_time'])){
	        		$print[]=array(
			         'label'=>Yii::t("default","Pickup Time"),
			         'value'=>$info['delivery_time']
			       );
	        	}     
	        }   
	        
	        if (isset($full_data['order_change'])){
			   if ($full_data['order_change']>0){
			   	    $print[]=array(
					  'label'=>Yii::t("default","Change"),
					  'value'=>normalPrettyPrice($full_data['order_change'])
					);
			   }		
			}    		
        	
        }    
        				                
        $item='';        
        if (self::isArray($data['cart'])){
        	foreach ($data['cart'] as $val) {
        		
        		$sub_item='';
        		
        		if (self::isArray($val['sub_item'])){
        			foreach ($val['sub_item'] as $val2) {
        				if (self::isArray($val2)){
        				    foreach ($val2 as $val3) {        				       
        				       $sub_item[]=array(
        				         'addon_name'=>$val3['sub_item_name'],
        				         'addon_category'=>$val3['category_name'],
        				         'addon_qty'=>$val3['qty']=="itemqty"?$val['qty']:$val3['qty'],
        				         'addon_price'=>$val3['price']
        				       );
        				    }	
        				}        			
        			}
        		}
        		
        		$item[]=array(
        		  'item_id'=>$val['item_id'],
        		  'item_name'=>$val['item_name'],
        		  'size_words'=>$val['size'],
        		  'qty'=>$val['qty'],
        		  'normal_price'=>$val['price'],
        		  'discounted_price'=>$val['discounted_price']>0?$val['discounted_price']:$val['price'],
        		  'order_notes'=>$val['order_notes'],
        		  'cooking_ref'=>$val['cooking_ref'],
        		  'ingredients'=>$val['ingredients'],
        		  'non_taxable'=>1,
        		  'sub_item'=>$sub_item
        		);
        	}
        }
        
        $paypal_card_fee=isset($info['card_fee'])?$info['card_fee']:0;        
        
        $total=array(
          'mid'=>$merchant_id,
          'subtotal'=>$data['sub_total']['amount'],
          'delivery_charges'=>isset($data['delivery_charges']['amount'])?$data['delivery_charges']['amount']:'',
          'taxable_total'=>$data['tax']['amount_raw'],
          'tax_amt'=>str_replace(array("Tax","%"),'',$data['tax']['tax_pretty']),
          'total'=>$data['grand_total']['amount']+$paypal_card_fee,
          'curr'=>Yii::app()->functions->adminCurrencySymbol(),
          'packaging'=>isset($data['packaging']['amount'])?$data['packaging']['amount']:'',
          'card_fee'=>$paypal_card_fee
        );    
        
        /*dump($data);
        dump($total);*/
                                                     
    	$receipt=EmailTPL::salesReceipt($print,array(
    	  'item'=>$item,
    	  'total'=>$total
    	));    	    	
    	$tpl=Yii::app()->functions->getOption("receipt_content",$merchant_id);
		if (empty($tpl)){
			$tpl=EmailTPL::receiptTPL();
		}
		$tpl=Yii::app()->functions->smarty('receipt',$receipt,$tpl);
        $tpl=Yii::app()->functions->smarty('customer-name',$client_fullname,$tpl);
        $tpl=Yii::app()->functions->smarty('receipt-number',Yii::app()->functions->formatOrderNumber($order_id),$tpl);
    	    	
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
				
		/*dump($tpl);
		die();*/
		
		$db_ext=new DbExt; 
				
		if ($ok_send_notification){
		   sendEmail($client_email,$receipt_sender,$receipt_subject,$tpl);
		} else {
			/// saved to database and send it once actually paid
			$params=array(
			  'order_id'=>$order_id,
			  'client_email'=>$client_email,
			  'receipt_sender'=>$receipt_sender,
			  'receipt_subject'=>$receipt_subject,
			  'tpl'=>$tpl			  
			);
			$db_ext->insertData("{{mobile_temp_email}}",$params);
		}   
		
		/*send email to merchant address*/ 
		$merchant_notify_email=Yii::app()->functions->getOption("merchant_notify_email",$merchant_id);    
        $enabled_alert_notification=Yii::app()->functions->getOption("enabled_alert_notification",$merchant_id);            
        
        if ( $enabled_alert_notification==""){ 
        	
        	$merchant_receipt_subject=Yii::app()->functions->getOption("merchant_receipt_subject",$merchant_id);
    	
    	    $merchant_receipt_subject=empty($merchant_receipt_subject)?t("New Order From").
    	    " ".$client_fullname:$merchant_receipt_subject;
    	
    	    $merchant_receipt_content=Yii::app()->functions->getMerchantReceiptTemplate($merchant_id);
    	    
    	    $final_tpl='';    	
	    	if (!empty($merchant_receipt_content)){
	    		$merchant_token=Yii::app()->functions->getMerchantActivationToken($merchant_id);
	    		$confirmation_link=Yii::app()->getBaseUrl(true)."/store/confirmorder/?id=".$order_id."&token=$merchant_token";
	    		$final_tpl=smarty('receipt-number',Yii::app()->functions->formatOrderNumber($order_id)
	    		,$merchant_receipt_content);    		
	    		$final_tpl=smarty('customer-name',$client_fullname,$final_tpl);
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
	    				if ($ok_send_notification){
		    			    sendEmail(trim($merchant_notify_email_val),
		    			    $global_admin_sender_email,$merchant_receipt_subject,$final_tpl);
	    				} else {
	    					/// saved to database and send it once actually paid
	    					$params=array(
							  'order_id'=>$order_id,
							  'client_email'=>$merchant_notify_email_val,
							  'receipt_sender'=>$global_admin_sender_email,
							  'receipt_subject'=>$merchant_receipt_subject,
							  'tpl'=>$final_tpl,
							  'email_type'=>"merchant"
							);
							$db_ext->insertData("{{mobile_temp_email}}",$params);
	    				}	    			
	    			}
	    		}
	    	}    	    	
	    	
        }	
		
    }
    
    public function processPendingReceiptEmail($order_id='')
    {    	
    	$db_ext=new DbExt; 
    	$stmt="SELECT * FROM
    	{{mobile_temp_email}}
    	WHERE
    	order_id=".self::q($order_id)."
    	AND 
    	status='pending'    	    
    	";
    	if ($res=$db_ext->rst($stmt)){    		
    		foreach ($res as $val) {    			
    			$id=$val['id'];
    			    			    			
    			switch ($val['email_type']) {
    				
    				case "client":   
    				case "merchant": 					    					    			
	    			$send_stats=sendEmail($val['client_email'],$val['receipt_sender'],
	    			$val['receipt_subject'],$val['tpl']);
	    			if($send_stats){
	    				$send_stats='sent';
	    			} else $send_stats='sending failed';    		
	    			
	    			$params=array('status'=>$send_stats);      			
	    			$db_ext->updateData("{{mobile_temp_email}}",$params,'id',$id);
    			    break;

    				case "sms":
    					$params=array(
    					  'merchant_id'=>$val['merchant_id'],
    					  'broadcast_id'=>'999999999',
    					  'client_id'=>$val['client_id'],
    					  'client_name'=>$val['client_name'],
    					  'contact_phone'=>$val['client_email'],
    					  'sms_message'=>$val['tpl'],
    					  'date_created'=>date('c'),
    					  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    					  'gateway'=>$val['gateway']
    					);    					
    					$db_ext->insertData("{{sms_broadcast_details}}",$params);
    					
    					$params=array('status'=>'saved');      			
	    			    $db_ext->updateData("{{mobile_temp_email}}",$params,'id',$id);
    					break ;	    						    
    			}
    		}
    	}
    	return false;  
    }
    
    public static function platFormList()
    {
    	return array(
	    	1=>"android",
	        2=>'ios',
	        3=>"all platform"
    	);
    }
    
    public static function getBroadcast($broadcast_id='')
    {
    	$stmt="
    	SELECT * FROM
    	{{mobile_broadcast}}
    	WHERE
    	broadcast_id=".self::q($broadcast_id)."
    	";
    	$db_ext=new DbExt; 
    	if ($res=$db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;    
    }
    
    public static function availableLanguages()
    {
    	$lang['en']='English';
    	$stmt="
    	SELECT * FROM
    	{{languages}}
    	WHERE
    	status in ('publish','published')
    	";
    	$db_ext=new DbExt; 
    	if ($res=$db_ext->rst($stmt)){
    		foreach ($res as $val) {
    			$lang[$val['lang_id']]=$val['language_code'];
    		}    		
    	}
    	return $lang;
    }
    
    public function translateItem($type='category',$string='',$id='',$field1='category_name_trans')
    {    	
    	$lang_id=$_GET['lang_id'];
    	
    	$db_ext=new DbExt; 
    	    	
    	switch ($type) {
    		case "category":    			
    			$stmt="SELECT $field1 FROM
    			{{category}}
    			WHERE
    			cat_id=".self::q($id)."
    			LIMIT 0,1
    			";    		    			
    			break;
    			
    		case "item":	
    		   $stmt="SELECT $field1 FROM
    			{{item}}
    			WHERE
    			item_id=".self::q($id)."
    			LIMIT 0,1
    			";    	
    		    //dump($stmt);	    			
    			break;
    			
    		case "cookingref":	
    		   $stmt="SELECT $field1 FROM
    			{{cooking_ref}}
    			WHERE
    			cook_id=".self::q($id)."
    			LIMIT 0,1
    			";    	
    		    //dump($stmt);	    			
    			break;	
    		
    		case "ingredients":	
    		   $stmt="SELECT $field1 FROM
    			{{ingredients}}
    			WHERE
    			ingredients_id =".self::q($id)."
    			LIMIT 0,1
    			";    	
    		    //dump($stmt);	    			
    			break;			
    	
    		default:
    			break;
    	}
    	
    	if ($res=$db_ext->rst($stmt)){
    		$res=$res[0];
    		//dump($res);
    		$text=!empty($res[$field1])?json_decode($res[$field1],true):false;
    		if ($text!=false){
    			//dump($text);
    			if (array_key_exists($lang_id,(array)$text)){
    				if (!empty($text[$lang_id])){
    					return $text[$lang_id];
    				}    			
    			}    		
    		}    	
    	}
    	return $string;
    }
    
    public function getVoucherCodeNew($client_id='',$voucher_code='',$merchant_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="
    	SELECT a.*,
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".self::q($voucher_code)."
    	and
    	client_id=".$client_id."  	
    	LIMIT 0,1
    	) as found,
    	
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".self::q($voucher_code)."    	
    	LIMIT 0,1
    	) as number_used    
    	
    	FROM
    	{{voucher_new}} a
    	WHERE
    	voucher_name=".self::q($voucher_code)."
    	AND
    	merchant_id=".self::q($merchant_id)."
    	AND status IN ('publish','published')
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		    		
    		return $res[0];
    	}
    	return false;
    } 
    
    public function getVoucherCodeAdmin($client_id='',$voucher_code='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="
    	SELECT a.*,
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".self::q($voucher_code)."
    	and
    	client_id=".$client_id."  	
    	LIMIT 0,1
    	) as found,
    	
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".self::q($voucher_code)."    	
    	LIMIT 0,1
    	) as number_used    	
    	
    	FROM
    	{{voucher_new}} a
    	WHERE
    	voucher_name=".self::q($voucher_code)."
    	AND
    	voucher_owner='admin'
    	AND status IN ('publish','published')
    	LIMIT 0,1
    	";    	     	
    	if ($res=$db_ext->rst($stmt)){    		    		
    		return $res[0];
    	}
    	return false;
    }         
        
    
    public static function getMerchantOffers($merchant_id='')
    {
    	$offer='';
    	$price_above=Yii::app()->functions->getOption("free_delivery_above_price",$merchant_id);
    	if (is_numeric($price_above) && $price_above>=1){
    		$offer[]=self::t("Free Delivery On Orders Over")." ".AddonMobileApp::prettyPrice($price_above);
    	}
    	    	
    	if ( $res=Yii::app()->functions->getMerchantOffersActive($merchant_id)){
    		$offer[]=number_format($res['offer_percentage'],0)."% ".self::t("Off");
    	}    
    	return $offer;
    }
    
}/* end class*/