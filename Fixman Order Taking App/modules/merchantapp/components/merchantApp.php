<?php
class merchantApp
{	
	public static function moduleBaseUrl()
	{
		return Yii::app()->getBaseUrl(true)."/protected/modules/merchantapp";
	}
	
	public static function t($message='')
	{
		return Yii::t("default",$message);
	}
	
	public static function moduleName()
	{
		return self::t("Merchant Mobile App");
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
	
	public static function q($data)
	{
		return Yii::app()->db->quoteValue($data);
	}
	
	public static function generateUniqueToken($length,$unique_text=''){	
		$key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));	
	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }	
	    return $key.md5($unique_text);
	}
	
    public static function prettyPrice($amount='')
	{
		if(!empty($amount)){
			return displayPrice(getCurrencyCode(),prettyFormat($amount));
		}
		return 0;
	}	
		
	public static function login($username='',$password='')
	{
		$DbExt=new DbExt; 
		$stmt="SELECT * FROM
		{{merchant}}
		WHERE
		username=".self::q($username)."
		AND
		password=".self::q($password)."		
		AND
		status = 'active'	
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			$res=$res[0];
			$res['user_type']="admin";			
			
			$token=self::generateUniqueToken(15,$res['username']);
			$params=array(
			  'mobile_session_token'=>$token
			);					
			$DbExt->updateData("{{merchant}}",$params,'merchant_id',$res['merchant_id']);
			
			$res['token']=$token;			
			return $res;
		} else {			
			$stmt="SElECT * FROM
			{{merchant_user}}
			WHERE
			username=".self::q($username)."
			AND
			password=".self::q($password)."
			AND
			status = 'active'
			LIMIT 0,1
			";			
			if ($res=$DbExt->rst($stmt)){
				$res=$res[0];
				
				$token=self::generateUniqueToken(15,$res['username']);
				$params=array(
				  'mobile_session_token'=>$token
				);					
				$DbExt->updateData("{{merchant_user}}",$params,'merchant_user_id',$res['merchant_user_id']);
			
			    $res['user_type']="user";
			    $res['token']=$token;
			    return $res;
			}
		}
		return false;
	}
	
	public static function validateToken($mtid='',$token='',$user_type='admin')
	{
		$DbExt=new DbExt;
		if ( $user_type=="admin"){
			$stmt="
			SELECT mobile_session_token,merchant_id,username FROM
			{{merchant}}
			WHERE
			merchant_id =".self::q($mtid)."
			AND
			mobile_session_token = ".self::q($token)."
			LIMIT 0,1
			";
			if ($res=$DbExt->rst($stmt)){
				$res[0]['user_type']="admin";
				return $res[0];
			}
		} else {
			$stmt="
			SELECT mobile_session_token,merchant_id,merchant_user_id,username   FROM
			{{merchant_user}}
			WHERE
			merchant_id =".self::q($mtid)."
			AND
			mobile_session_token = ".self::q($token)."
			LIMIT 0,1
			";
			if ($res=$DbExt->rst($stmt)){
				$res[0]['user_type']="user";
				return $res[0];
			}
		}
		return false;
	}
	
	public static function getUserByToken($token='')
	{
		$DbExt=new DbExt;
		
		$stmt="
		SELECT mobile_session_token,merchant_id  FROM
		{{merchant}}
		WHERE
		mobile_session_token = ".self::q($token)."
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			$res[0]['user_type']="admin";
			return $res[0];
		} else {		
			$stmt="
			SELECT merchant_user_id,mobile_session_token,merchant_id  FROM
			{{merchant_user}}
			WHERE			
			mobile_session_token = ".self::q($token)."
			LIMIT 0,1
			";
			if ($res=$DbExt->rst($stmt)){
				$res[0]['user_type']="user";
				return $res[0];
			}
		}
		return false;
	}	
	
	public static function getUserByEmail($email_address='')
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT merchant_id,contact_email FROM
		{{merchant}}
		WHERE
		contact_email =".self::q($email_address)."
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			$res[0]['user_type']="admin";
			return $res[0];
		} else {
			$stmt="SELECT merchant_id,contact_email FROM
			{{merchant_user}}
			WHERE
			contact_email =".self::q($email_address)."
			LIMIT 0,1
			";
			if ($res=$DbExt->rst($stmt)){
				$res[0]['user_type']="user";
			    return $res[0];
			}
		}
		return false;
	}
	
	public static function getMerchantByCode($lost_pass_code='',$email_address='',$user_type='')
	{
		$DbExt=new DbExt;
		if ( $user_type=="admin"){
			$stmt="SELECT * FROM
			{{merchant}}
			WHERE
			contact_email =".self::q($email_address)."
			AND
			lost_password_code=".self::q($lost_pass_code)."
			LIMIT 0,1
			";
		}  else {
			$stmt="SELECT * FROM
			{{merchant_user}}
			WHERE
			contact_email =".self::q($email_address)."
			AND
			lost_password_code=".self::q($lost_pass_code)."
			LIMIT 0,1
			";
		}
		if( $res=$DbExt->rst($stmt)){
			return $res[0];
		}		
		return false;
	}	
	
	public static function getDeviceInfo($device_id='')
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT * FROM
		{{mobile_device_merchant}}
		WHERE
		device_id=".self::q($device_id)."
		LIMIT 0,1
		";
		if( $res=$DbExt->rst($stmt)){
			return $res[0];
		}		
		return false;
	}
	
	public static function getDeviceByID($id='')
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT * FROM
		{{mobile_device_merchant}}
		WHERE
		id=".self::q($id)."
		LIMIT 0,1
		";
		if( $res=$DbExt->rst($stmt)){
			return $res[0];
		}		
		return false;
	}	

	public static function paymentType()
	{
		return array(
		  'cod'=> t("Cash On delivery"),
		  'ocr'=> t("Offline Credit Card Payment"),
		  'pyp'=> t("Paypal"),
		  'pyr'=> t("Pay On Delivery"),
		  'stp'=> t("Stripe"),
		  'mcd'=> t("Mercadopago"),
		  'ide'=> t("Sisow"),
		  'payu'=> t("PayUMoney"),
		  'pys'=> t("Paysera"),
		  'bcy'=> t("Barclaycard"),
		  'epy'=> t("EpayBg"),
		  'atz'=> t("Authorize.net"),
		  'obd'=> t("Offline Bank Deposit ")
		);
	}	
	
    public function orderStatusList($merchant_id='')
    {    	
    	$list='';    	
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM 
    	  {{order_status}} 
    	  WHERE
    	  merchant_id IN ('0','$merchant_id')
    	  ORDER BY stats_id";	    	
    	if ($res=$db_ext->rst($stmt)){
    		foreach ($res as $val) {       			
    			$list[$val['description']]=$val['description'];
    		}
    		return $list;
    	}
    	return false;    
    }    	
    
    public function getOrderHistory($order_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{order_history}}
    	WHERE
    	order_id =".self::q($order_id)."
    	ORDER BY date_created DESC
    	";
    	if ( $res=$db_ext->rst($stmt)){
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
	
    public static function pushNewOrder($order_id='')
    {
    	   
    	if ( $res=Yii::app()->functions->getOrder($order_id)){
    		
    		if ($res['status']=="initial_order"){
    			return ;
    		}
    		
    		$merchant_id=$res['merchant_id'];
    		$client_id=$res['client_id'];    	
    		
    		$db_ext=new DbExt;
    		$stmt="
    		SELECT * FROM
    		{{mobile_device_merchant}}
    		WHERE
    		merchant_id =".self::q($merchant_id)."
    		AND
    		enabled_push ='1'
    		AND
    		status ='active'
    		
    		ORDER BY id ASC
    		LIMIT 0,20
    		";
    		    		
    		if ( $device=$db_ext->rst($stmt)){
    			
    			/*get the template*/
    			$title=getOptionA('push_tpl_new_order_title');    			
    			$content=getOptionA('push_tpl_new_order_content');  
    			  			
    			if ( empty($title) || empty($content)){
    				return ;
    			}
    			    			
    			$title=smarty('order_id',$res['order_id'],$title);
    			$title=smarty('total_amount',self::prettyPrice($res['total_w_tax']),$title);
    			$title=smarty('merchant_name',$res['merchant_name'],$title);
    			$title=smarty('customer_name',$res['full_name'],$title);    			
    			$title=smarty('order_status',$res['status'],$title);
    			$title=smarty('trans_type',$res['trans_type'],$title);
    			$title=smarty('payment_type',$res['payment_type'],$title);
    			
    			$content=smarty('order_id',$res['order_id'],$content);
    			$content=smarty('total_amount',self::prettyPrice($res['total_w_tax']),$content);
    			$content=smarty('merchant_name',$res['merchant_name'],$content);
    			$content=smarty('customer_name',$res['full_name'],$content);    			
    			$content=smarty('order_status',$res['status'],$content);
    			$content=smarty('trans_type',$res['trans_type'],$content);
    			$content=smarty('payment_type',$res['payment_type'],$content);
    			    			
    			foreach ($device as $val) {    				
    				$params=array(
    				  'merchant_id'=>$val['merchant_id'],
    				  'user_type'=>$val['user_type'],
    				  'merchant_user_id'=>$val['merchant_user_id'],
    				  'device_platform'=>$val['device_platform'],
    				  'device_id'=>$val['device_id'],
    				  'push_title'=>$title,
    				  'push_message'=>$content,
    				  'date_created'=>date('c'),
    				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    				  'order_id'=>$order_id
    				);
    				$db_ext->insertData('{{mobile_merchant_pushlogs}}',$params);
    			}
    		} else echo 'no records';
    	}    	
    }
    
    public static function sendEmailSMS($order_id='')
    {
    	$_GET['backend']='';    	
    	    	    	
    	if ( $res=Yii::app()->functions->getOrder2($order_id)){
    		
    		/*SEND EMAIL*/
    		$tpl=self::getEmailTemplate($res['status']);    		
    		if (is_array($tpl) && count($tpl)>=1){
    			
    			$tpl['title']=smarty('customer_name',$res['full_name'],$tpl['title']);
    			$tpl['title']=smarty('order_id',$res['order_id'],$tpl['title']);
    			$tpl['title']=smarty('order_status',$res['status'],$tpl['title']);
    			$tpl['title']=smarty('remarks',$_GET['remarks'],$tpl['title']);
    			$tpl['title']=smarty('delivery_time',$_GET['delivery_time'],$tpl['title']);
    			
    			$tpl['content']=smarty('customer_name',$res['full_name'],$tpl['content']);
    			$tpl['content']=smarty('order_id',$res['order_id'],$tpl['content']);
    			$tpl['content']=smarty('order_status',$res['status'],$tpl['content']);
    			$tpl['content']=smarty('remarks',$_GET['remarks'],$tpl['content']);
    			$tpl['content']=smarty('delivery_time',$_GET['delivery_time'],$tpl['content']);
    			    			
    			$to=$res['email_address'];  
    			if (!empty($to)){
	    			if(sendEmail($to,'',$tpl['title'],$tpl['content'])){  
	    			} 
    			}
    		}
    		
    		
    		/*SEND SMS*/
    		$contact_phone=$res['contact_phone1'];    	
    		$sms_balance=Yii::app()->functions->getMerchantSMSCredit($res['merchant_id']);
    		/*dump($sms_balance);
    		dump($contact_phone); */
    		
    		/*check if merchant sms is enabled*/    		
    		$sms_enabled_alert=getOption($res['merchant_id'],'sms_enabled_alert');    			
    		if  ( $sms_enabled_alert!=1){
    			return ;
    		}
    		
    		if (!empty($contact_phone) && $sms_balance>0 ){    		    		
	    		$sms_tpl=self::getSMSTemplate($res['status']);	    		
	    		if (!empty($sms_tpl)){
	    			$sms_tpl=smarty('customer_name',$res['full_name'],$sms_tpl);
	    			$sms_tpl=smarty('order_id',$res['order_id'],$sms_tpl);
	    			$sms_tpl=smarty('order_status',$res['status'],$sms_tpl);
	    			$sms_tpl=smarty('remarks',$_GET['remarks'],$sms_tpl);
	    			$sms_tpl=smarty('delivery_time',$_GET['delivery_time'],$sms_tpl);
	    			
	    			$res_sms=Yii::app()->functions->sendSMS($contact_phone,$sms_tpl);	    			
	    			$params=array(
	    			  'client_id'=>isset($res['client_id'])?$res['client_id']:'',
	    			  'merchant_id'=>isset($res['merchant_id'])?$res['merchant_id']:'',
	    			  'client_name'=>isset($res['full_name'])?$res['full_name']:'',
					  'contact_phone'=>$contact_phone,
					  'sms_message'=>$sms_tpl,
					  'status'=>isset($res_sms['msg'])?$res_sms['msg']:'',
					  'gateway_response'=>isset($res_sms['raw'])?$res_sms['raw']:'',
					  'gateway'=>isset($res_sms['sms_provider'])?$res_sms['sms_provider']:'',
					  'date_created'=>date('c'),
					  'ip_address'=>$_SERVER['REMOTE_ADDR']
					);					
					$DbExt=new DbExt;
			        $DbExt->insertData("{{sms_broadcast_details}}",$params);
	    		}    		
    		}
    	}
    }
    
    public static function getEmailTemplate($status='')
    {
    	switch ($status) {
    		case 'accepted':
    			$title=getOptionA('tpl_order_accept_title');
    			$content=getOptionA('tpl_order_accept_content');
    			break;
    	
    		case "decline":
    			$title=getOptionA('tpl_order_denied_title');
    			$content=getOptionA('tpl_order_denied_content');
    			break; 
    				
    		default:
    			$title=getOptionA('tpl_order_change_title');
    			$content=getOptionA('tpl_order_change_content');
    			break;
    	}
    	if (!empty($title)){
	    	return array(
	    	  'title'=>$title,
	    	  'content'=>$content
	    	);
    	} 
    	return false;
    }
    
    public static function getSMSTemplate($status='')
    {
    	switch ($status) {
    		case 'accepted':    			
    			$content=getOptionA('sms_tpl_order_accept_content');
    			break;
    	
    		case "decline":    			
    			$content=getOptionA('sms_tpl_order_denied_content');
    			break; 
    				
    		default:    			
    			$content=getOptionA('sms_tpl_order_change_content');
    			break;
    	}
    	if (!empty($content)){
	    	return $content;
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
    
	public static function isArray($data='')
	{
		if (is_array($data) && count($data)>=1){
			return true;
		}
		return false;
	}	
	
	public static function getMerchantNotification($merchant_id='',$user_type='',$merchant_user_id='')
	{		
		if ( $user_type=="admin"){
			$stmt="SELECT * FROM
			{{mobile_merchant_pushlogs}}
			WHERE
			merchant_id = ".self::q($merchant_id)."
			AND
			user_type =".self::q($user_type)."
			ORDER BY date_created DESC
			LIMIT 0,50
			";
		} else {
			$stmt="SELECT * FROM
			{{mobile_merchant_pushlogs}}
			WHERE
			merchant_id = ".self::q($merchant_id)."
			AND
			user_type =".self::q($user_type)."
			AND
			merchant_user_id =".self::q($merchant_user_id)."
			ORDER BY date_created DESC
			LIMIT 0,50
			";
		}
		//dump($stmt);
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function searchOrderByMerchantId($order_id='',$merchant_id='')
	{
		if (is_numeric($order_id)){
			$stmt="
		    SELECT * FROM
		    {{order}}
		    WHERE
		    merchant_id =".self::q($merchant_id)."
		    AND
		    order_id =".self::q($order_id)."
		    AND 
			status NOT IN ('initial_order')					
		    ORDER BY order_id ASC
		    LIMIT 0,100
		    ";
		} else {
			$stmt="		
			SELECT a.* FROM
		    {{order}} a
		    WHERE
		    merchant_id =".self::q($merchant_id)."
		    AND 
			status NOT IN ('initial_order')					
		    AND
		    client_id IN (
		       select client_id
		       from {{client}}
		       where
		       first_name LIKE '".$order_id."%'
		       OR 
		       last_name LIKE '".$order_id."%'
		    )
		    ORDER BY order_id ASC
		    LIMIT 0,100
		    ";
		}				
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function getPendingTables($merchant_id='')
	{
		$stmt="SELECT * FROM
		{{bookingtable}}
		WHERE
		merchant_id=".self::q($merchant_id)."
		AND
		status IN ('pending')
		ORDER BY booking_id DESC
		LIMIT 0,100		
		";
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function getAllBooking($merchant_id='')
	{
		$stmt="SELECT * FROM
		{{bookingtable}}
		WHERE
		merchant_id=".self::q($merchant_id)."
		ORDER BY booking_id DESC
		LIMIT 0,100		
		";
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}	
	
	public static function getBookingDetails($merchant_id='',$booking_id='')
	{
		$stmt="SELECT a.*,
		b.restaurant_name 
		FROM 
		{{bookingtable}} a
		left join {{merchant}} b
        ON
        a.merchant_id =  b.merchant_id
		
		WHERE
		a.merchant_id=".self::q($merchant_id)."
		
		AND
		booking_id =".self::q($booking_id)."		
		LIMIT 0,1
		";		
		
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}		
	
	public static function savePushTable($merchant_id='',$booking_id='')
	{
		if ( $res=merchantApp::getBookingDetails($merchant_id,$booking_id)){
			
			$subject=Yii::app()->functions->getOptionAdmin('push_tpl_booking_title');
			$content=Yii::app()->functions->getOptionAdmin('push_tpl_booking_content');
			
			
			$subject=smarty('merchant_name',$res['restaurant_name'],$subject);
       	    $subject=smarty('booking_name',$res['booking_name'],$subject);
       	    $subject=smarty('booking_date',
       	    Yii::app()->functions->FormatDateTime($res['date_booking'],false),$subject);
       	    $subject=smarty('booking_time',$res['booking_time'],$subject);
       	    $subject=smarty('number_of_guest',$res['number_guest'],$subject);
       	    $subject=smarty('booking_id',$res['booking_id'],$subject);       	    
       	    
       	    $content=smarty('merchant_name',$res['restaurant_name'],$content);
       	    $content=smarty('booking_name',$res['booking_name'],$content);
       	    $content=smarty('booking_date',
       	    Yii::app()->functions->FormatDateTime($res['date_booking'],false),$content);
       	    $content=smarty('booking_time',$res['booking_time'],$content);
       	    $content=smarty('number_of_guest',$res['number_guest'],$content);
       	    $content=smarty('booking_id',$res['booking_id'],$content);       	    
       	    
       	    $params=array(       	      
       	      'push_title'=>$subject,
       	      'push_message'=>$content,
       	      'push_type'=>'booking',
       	      'date_created'=>date('c'),
       	      'ip_address'=>$_SERVER['REMOTE_ADDR'],
       	      'booking_id'=>$res['booking_id']
       	    );
       	    dump($params);
       	    
       	    if ( empty($subject) && !empty($content)){
       	    	return false;
       	    }
       	    
       	    $db_ext=new DbExt;
    		$stmt="
    		SELECT * FROM
    		{{mobile_device_merchant}}
    		WHERE
    		merchant_id =".self::q($res['merchant_id'])."
    		AND
    		enabled_push ='1'
    		AND
    		status='active'
    		
    		ORDER BY id ASC
    		LIMIT 0,20
    		";
    		    		
    		if ( $resp=$db_ext->rst($stmt)){
    			foreach ($resp as $val) {
    				$params['merchant_id']=$val['merchant_id'];
    				$params['user_type']=$val['user_type'];
    				$params['merchant_user_id']=$val['merchant_user_id'];
    				$params['device_id']=$val['device_id'];    				
    				$params['device_platform']=$val['device_platform'];
    				dump($params);
    				$db_ext->insertData('{{mobile_merchant_pushlogs}}',$params);
    			}
    		}
				       	   
		}
	}
    
} /*end class*/