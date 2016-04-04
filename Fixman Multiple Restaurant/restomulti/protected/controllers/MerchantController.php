<?php
/**
 * MerchantController Controller
 *
 */
if (!isset($_SESSION)) { session_start(); }

class MerchantController extends CController
{
	public $layout='merchant_tpl';	
	public $crumbsTitle='';
	
	public function accessRules()
	{		
		
	}
	
	public function beforeAction(CAction $action)
    {    	
    	$action_name= $action->id ;
    	$accept_controller=array('login','ajax','autologin');
	    //if(!Yii::app()->functions->isMerchantLogin() )
	    if(!Yii::app()->functions->validateMerchantSession() )
	    {
	    	if (!in_array($action_name,$accept_controller)){	 	           
	           if ( Yii::app()->functions->has_session){
	    	   	    $message_out=t("You were logout because someone login with your account");
	    	   	    $this->redirect(array('merchant/login/?message='.urlencode($message_out)));
	    	   } else $this->redirect(array('merchant/login'));	           
	    	}
	    }		    
	    
	    if ( $action_name=="autologin"){
	    	return true;
	    }
	    /*echo $this->uniqueid;
	    echo '<br/>';
	    echo $action_name;*/
	    if ( $this->uniqueid=="merchant"){
	    	if ( !Yii::app()->functions->hasMerchantAccess($action_name)){
	    		if ( $action_name!="login"){
	    			if ( $action_name!="index"){
	    				$this->crumbsTitle=Yii::t("default","No Access");		
	    		        $this->render('noaccess');
	    		        return ;
	    			}    
	    		}
	    	}
	    }
	    return true;	    
    }	
        	
	public function init()
	{		
		 $name=Yii::app()->functions->getOptionAdmin('website_title');
		 if (!empty($name)){		 	
		 	 Yii::app()->name = $name;
		 }		 
		 
		 
		 $mtid=Yii::app()->functions->getMerchantID();		 
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 		 
		 $mt_timezone=Yii::app()->functions->getOption("merchant_timezone",$mtid);	   	   	    	
    	 if (!empty($mt_timezone)){    	 	
    		Yii::app()->timeZone=$mt_timezone;
    	 }		     	 
	}
				  
	public function actionIndex()
	{					
		if ( !Yii::app()->functions->isMerchantLogin()){						
			$this->layout='login_tpl';
			$this->render('login');
		} else {											
			$this->crumbsTitle=Yii::t("default","Dashboard");		
			$this->render('dashboard');			
		}		
	}	
	
	
	public function actionDashBoard()
	{					
		$this->crumbsTitle=Yii::t("default","Dashboard");
		
		if ( !Yii::app()->functions->isMerchantLogin()){						
			$this->layout='login_tpl';
			$this->render('login');
		} else {									
			$this->crumbsTitle=Yii::t("default","Dashboard");
			$this->render('dashboard');			
		}		
	}	
	
	
	public function actionLogin()
	{		
		if (isset($_GET['logout'])){
			//Yii::app()->request->cookies['kr_merchant_user'] = new CHttpCookie('kr_merchant_user', ""); 			
			unset($_SESSION['kr_merchant_user']);
		}		
		$this->layout='login_tpl';
	    $this->render('login');
	}
	
	public function actionAjax()
	{			
		if (isset($_REQUEST['tbl'])){
		   $data=$_REQUEST;	
		} else $data=$_POST;
				
		if (isset($data['debug'])){
			dump($data);
		}
		$class=new AjaxAdmin;
	    $class->data=$data;
	    $class->$data['action']();	    
	    echo $class->output();
	    yii::app()->end();
	}	
	
	public function actionCategoryList()
	{	    
		$this->crumbsTitle=Yii::t("default","Category");
		
	    if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->render('category_add');
			} elseif ( $_GET['Do'] =="Sort" ){	
			   $this->render('category_sort');
			} else $this->render('category_list');
		} else $this->render('category_list');
	}
		
	public function actionAddOnCategory()
	{
				
		$this->crumbsTitle=Yii::t("default","Addon Category");
		
		if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->render('addon_category_add');
			} elseif ( $_GET['Do'] =="Sort" ){					
			   $this->render('addon_category_sort');		
			} else $this->render('addon_category_list');
		} else $this->render('addon_category_list');
	}		
	
	public function actionAddOnItem()
	{		
		$this->crumbsTitle=Yii::t("default","Addon Item");
		
		if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->render('addon_item_new');		
            } elseif ( $_GET['Do'] =="Sort" ){	
			   $this->render('addon_item_sort');	
			} else $this->render('addon_item_list');		
		} else $this->render('addon_item_list');		
	}

	public function actionSize()
	{
		$this->crumbsTitle=Yii::t("default","Size");
		
		if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->render('size_add');			
           } elseif ( $_GET['Do'] =="Sort" ){	
			   $this->render('size_sort');	
			} else $this->render('size');		
		} else $this->render('size');		
	}
	
	public function actionCookingRef()
	{			
		$this->crumbsTitle=Yii::t("default","Cooking Reference");
		
		if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->render('cooking-ref-add');			
            } elseif ( $_GET['Do'] =="Sort" ){	
			   $this->render('cooking_ref_sort');	
			} else $this->render('cooking-ref');		
		} else $this->render('cooking-ref');
	}
	
	public function actionFoodItem()
	{
		$this->crumbsTitle=Yii::t("default","Food Item");
		
		if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->render('food-item-add');
			} elseif ( $_GET['Do'] =="Sort" ){	
			   $this->render('food_item_sort');	
			} else $this->render('food-item-list');		
		} else $this->render('food-item-list');
	}
	
	public function actionMerchant()
	{
		$this->crumbsTitle=Yii::t("default","Merchant");
		$this->render('merchant-info');
	}
	
	public function actionSettings()
	{
		$this->crumbsTitle="Settings";
		$this->render('settings');
	}
	
	public function actionSocialSettings()
	{
		$this->crumbsTitle=t("Social Settings");
		$this->render('social-settings');
	}
	
	public function actionAlertSettings()
	{
		$this->crumbsTitle=Yii::t("default","Alert Settngs");
		$this->render('alert-settings');
	}
	
	public function actionSMSSettings()
	{
		$mechant_sms_enabled=Yii::app()->functions->getOptionAdmin('mechant_sms_enabled');
		if ( $mechant_sms_enabled=="yes"){
			$this->render('noaccess');
		} else {		
			
			$ha_sms_credits=Yii::app()->functions->hasSMSCredits();	
			$mechant_sms_purchase_disabled=Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled');		
			if ( $mechant_sms_purchase_disabled=="yes"){
				$ha_sms_credits=true;
			}
			//if (Yii::app()->functions->hasSMSCredits()){
			if ($ha_sms_credits){
			   $this->crumbsTitle=Yii::t("default","SMS Settings");		
			   $this->render('sms-settings');
			} else {
			   $this->crumbsTitle=Yii::t("default","SMS Purchase Credits");
			   $this->render('sms-purchase');
			}
		}
	}
	
	public function actionPaypalSettings()
    {
    	$py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('paypal',(array)$py) || in_array('pyp',(array)$py) ){
	    	$this->crumbsTitle=Yii::t("default","Paypal Settings");
	    	$this->render('paypal-settings');
        } else $this->render('noaccess');
    }
    
    public function actionSalesReport()
    {
    	$this->crumbsTitle=Yii::t("default","Sales Report");
    	$this->render('sales-report');
    }
    
    public function actionSalesSummaryReport()
    {
    	$this->crumbsTitle=Yii::t("default","Sales Summary Report");
    	$this->render('sales-summary-report');
    }
    
    public function actionOrderStatus()
    {
    	if (isset($_GET['Do'])){
    		if ( getOptionA('merchant_status_disabled')!=2){
	    	   $this->crumbsTitle=Yii::t("default","Order Status");
	    	   $this->render('order-status-add');
    		} else $this->render('error',array('message'=>t("This options is disabled by website owner")));
    	} else {
    	   $this->crumbsTitle=Yii::t("default","Order Status");
    	   $this->render('order-status');
    	}
    }
    
    public function actionMerchantStatus()
    {
    	$mt_id=Yii::app()->functions->getMerchantID();
    	if ( $res=Yii::app()->functions->isMerchantCommission($mt_id)){
    		$this->crumbsTitle=Yii::t("default","404 page");
    		$this->render('error',array('message'=>t("Sorry but your not allowed to access this page")));
    	} else {
	    	$this->crumbsTitle=Yii::t("default","Merchant Status");
	    	$this->render('merchant-status');
    	}
    }
    
    public function actionReceiptSettings()
    {
    	$this->crumbsTitle=Yii::t("default","Receipt Settings");
    	$this->render('receipt-settings');
    }
    
    public function actionStripeSettings()
    {
    	$py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('stripe',(array)$py) || in_array('stp',(array)$py) ){
    	    $this->crumbsTitle=Yii::t("default","Stripe Settings");
    	   $this->render('stripe-settings');
    	} else $this->render('noaccess');
    }
    
	public function actionSetlanguage()
	{		
		if (isset($_GET['Id'])){			
			Yii::app()->request->cookies['kr_merchant_lang_id'] = new CHttpCookie('kr_merchant_lang_id', $_GET['Id']);						
			$id=Yii::app()->functions->getMerchantID();			
			Yii::app()->functions->updateMerchantLanguage($id,$_GET['Id']);
			
			if (!empty($_SERVER['HTTP_REFERER'])){
					header('Location: '.$_SERVER['HTTP_REFERER']);
					die();
		    } else {
		    	header('Location: '.Yii::app()->request->baseUrl);
		    	die();
		    }
		}
		header('Location: '.Yii::app()->request->baseUrl);
	}	    
	
	public function actionCreditCardInit()
	{
		$this->crumbsTitle=Yii::t("default","Purchase using Offline Credit Card");
		$this->render('select-cc');
	}
	
	public function actionSmsReceipt()
	{
		$this->crumbsTitle=Yii::t("default","Receipt");
		$this->render('sms-receipt');
	}
	
	public function actionPaypalInit()
	{		
		if ( $info=Yii::app()->functions->getSMSPackagesById($_GET['package_id']) ){
			
			$price=$info['price'];
    	    if ( $info['promo_price']>0){
                 $price=$info['promo_price'];
    		}	    	
    		
    		$paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();    		
    		
    		$type=isset($_GET['type'])?$_GET['type']:'';
    		$getparams="type/".$type."/package_id/".$_GET['package_id'];
    		
	        $params='';
			$x=1;
			$params['L_NAME'.$x]=isset($info['title'])?$info['title']:Yii::t("default","No description");
	        $params['L_NUMBER'.$x]=$info['package_id'];
	        $params['L_DESC'.$x]=isset($info['title'])?$info['title']:Yii::t("default","No description");
	        $params['L_AMT'.$x]=normalPrettyPrice($price);
	        $params['L_QTY'.$x]=1;					
				        
			$params['AMT']=normalPrettyPrice($price);
		    $params['RETURNURL']="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/merchant/paypalPurchase/$getparams";
		    $params['CANCELURL']="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/merchant/smsSettings/";	  	  
		    $params['NOSHIPPING']='1';
	        $params['LANDINGPAGE']='Billing';
	        $params['SOLUTIONTYPE']='Sole';
	        $params['CURRENCYCODE']=adminCurrencyCode();
	        
	        
	        $paypal=new Paypal($paypal_con);
	  	    $paypal->params=$params;
	  	    $paypal->debug=false;
	  	    if ($resp=$paypal->setExpressCheckout()){  	   	  			  	  	  
	  	  	  header("Location: ".$resp['url']);
	  	    } else {
	  	    	$this->render('error',array('message'=>"ERROR: ".$paypal->getError() ));
	  	    }
    		
		} else {
			$this->render('error',array('message'=>Yii::t("default","ERROR: Cannot get package information")));
		}
	}
	
	public function actionPaypalPurchase()
	{
		$this->crumbsTitle=Yii::t("default","Paypal Confirm Purchase");
		$this->render('paypal-confirmation');
	}
	
	public function actionStripeInit()
	{
		$this->crumbsTitle=Yii::t("default","Stripe Payment");
		$this->render('stripe-init');
	}
	
	public function actionSmsBroadcast()
	{
		if (Yii::app()->functions->hasSMSCredits()){
			if (isset($_GET['Do'])){
				if ($_GET['Do']=="view"){
					$this->crumbsTitle=Yii::t("default","SMS BroadCast Details". " ($_GET[bid])");
			        $this->render('sms-broadcast-details');
				} else {
				    $this->crumbsTitle=Yii::t("default","Add SMS BroadCast");
			        $this->render('sms-broadcast');
				}
			} else {		
				$this->crumbsTitle=Yii::t("default","SMS BroadCast");
			    $this->render('sms-broadcast-list');
			}
		} else {
		   $this->crumbsTitle=Yii::t("default","SMS Purchase Credits");
		   $this->render('sms-purchase');
		}
	}
	
	public function actionPurchaseSMS()
	{
		$this->crumbsTitle=Yii::t("default","SMS Purchase Credits");
        $this->render('sms-purchase');
	}
	
	public function actionMercadopagoInit()
	{		
		$this->crumbsTitle=Yii::t("default","Mercadopago Payment");
		$this->render('mercadopago-init');
	}
	
	public function actionmercadopagoSettings()
	{
		$py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('mercadopago',(array)$py) || in_array('mcd',(array)$py)){
		   $this->crumbsTitle=Yii::t("default","Mercadopago");
		   $this->render('mercadopago-settings');
		} else $this->render('noaccess');
	}	
	
	public function actionUser()
	{
		$this->crumbsTitle=Yii::t("default","User List");
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","User Add/Update");
			$this->render('user-add');		
		} else $this->render('user-list');		
	}
	
	public function actionVoucher()
	{
		$this->crumbsTitle=Yii::t("default","Voucher List");
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","Voucher Add/Update");
			$this->render('voucher-add');		
		} else $this->render('voucher-list');		
	}
	
	public function actionReview()
	{
		$this->crumbsTitle=Yii::t("default","Customer reviews");		
		if (isset($_GET['Do'])){
			if ( Yii::app()->functions->getOptionAdmin('merchant_can_edit_reviews')=="yes"){			
				$this->render('error',array(
				 'message'=>t("Sorry but you don't have access this page.")
				));
			} else {				
				$this->crumbsTitle=Yii::t("default","Customer reviews Update");
				$this->render('review-add');
			}
		} else $this->render('review-list');				
	}
	
	public function actionPaylineSettings()
	{
		$this->crumbsTitle=Yii::t("default","Payline Settings");
		$this->render('payline-settings');
	}
	
	public function actionPaylineInit()
	{
		$this->crumbsTitle=Yii::t("default","Payline Payment");
		$this->render('payline-init');
	}
	
	public function actionSisowSettings()
	{		
	    $py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('ide',(array)$py)){
		   $this->crumbsTitle=Yii::t("default","Sisow Settings");
		   $this->render('sisow-settings');
		} else $this->render('noaccess');
	}	
	
	public function actionSisowInit()
	{
		$this->crumbsTitle=Yii::t("default","SMS Purchase Credits");
		$this->render('sisow-init');
	}
	
	public function actionpayumoneysettings()
	{		
	    $py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('payu',(array)$py)){
		$this->crumbsTitle=Yii::t("default","PayUMoney Settings");		
		$this->render('payumoney-settings');
		} else $this->render('noaccess');
	}
	
	public function actionPayuInit()
	{
		$this->crumbsTitle=Yii::t("default","Pay using PayUMoney");		
		$this->render('payuinit');
	}
	
	public function actionTableBooking()
	{
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="settings"){
				$this->crumbsTitle=Yii::t("default","Table Booking Settings");		
			    $this->render('tablebooking-settings');
			} else {
			   $this->crumbsTitle=Yii::t("default","Table Booking");		
			   $this->render('tablebooking-add');
			}
		} else {
			$this->crumbsTitle=Yii::t("default","Table Booking");		
			$this->render('tablebooking');
		}
	}
	
	public function actionPayseraSettings()
	{
		$py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('pys',(array)$py)){
			$this->crumbsTitle=Yii::t("default","paysera settings");		
			$this->render('paysera-settings');
		} else $this->render('noaccess');
	}
	
	public function actionPysinit()
	{				
		$db_ext=new DbExt;
				
		$error='';
		$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
		$amount_to_pay=0;
		
		$back_url=Yii::app()->request->baseUrl."/merchant/purchasesms";
		$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');		
		$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
		
		$merchant_id=Yii::app()->functions->getMerchantID();		
		
		if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
			$amount_to_pay=$res['price'];
			if ( $res['promo_price']>0){
				$amount_to_pay=$res['promo_price'];
			}	    										
			$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';	
			$payment_description.=isset($res['title'])?$res['title']:'';		
			
			/*dump($payment_description);
			dump($amount_to_pay);
			dump($payment_ref);*/
						
			$amount_to_pay=number_format($amount_to_pay,2,'.','');	
			
            $cancel_url=Yii::app()->getBaseUrl(true)."/merchant/purchasesms";
            
            $accepturl=Yii::app()->getBaseUrl(true)."/merchant/pysinit/?type=purchaseSMScredit&package_id=".
            $package_id."&mode=accept&mtid=$merchant_id";	
                                                
            $callback=Yii::app()->getBaseUrl(true)."/paysera/?type=purchaseSMScredit&package_id=".
            $package_id."&mode=callback&mtid=$merchant_id";	
			
			$country=Yii::app()->functions->getOptionAdmin('admin_paysera_country');
		    $mode=Yii::app()->functions->getOptionAdmin('admin_paysera_mode');
		    $lang=Yii::app()->functions->getOptionAdmin('admin_paysera_lang');
		    $currency=Yii::app()->functions->adminCurrencyCode();	  
		    $projectid=Yii::app()->functions->getOptionAdmin('admin_paysera_project_id');		  
		    $password=Yii::app()->functions->getOptionAdmin('admin_paysera_password');
					    
		    if (isset($_GET['mode'])){				    	
		    	
		    	if ($_GET['mode']=="accept"){
		    		
	    		    $payment_code=Yii::app()->functions->paymentCode("paysera");
				  	$params=array(
						  'merchant_id'=>$_GET['mtid'],
						  'sms_package_id'=>$package_id,
						  'payment_type'=>$payment_code,
						  'package_price'=>$amount_to_pay,
						  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
						  'date_created'=>date('c'),
						  'ip_address'=>$_SERVER['REMOTE_ADDR'],
						  'payment_gateway_response'=>json_encode($_GET),						  
						  //'payment_reference'=>$response['orderid']
					 );							 					
					 $db_ext->insertData("{{sms_package_trans}}",$params);		    		
		    		 header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
		    		 die();		    		 
		    	}
		    			    			    			    			    	   
		    	try {
		    		
		    		$response = WebToPay::checkResponse($_GET, array(
		              'projectid'     => $projectid,
		              'sign_password' => $password,
		            ));      
		            		            
		            if (is_array($response) && count($response)>=1){  
		            	
		            	if ($response['status']==0){
		            		die("payment has no been executed");
		            	}
		            	if ($response['status']==3){
		            		die("additional payment information");
		            	}		    
		            			            			            	 
		            	$stmt="SELECT * FROM
		            	{{sms_package_trans}}
		            	WHERE
		            	merchant_id ='".$_GET['mtid']."'
		            	AND
		            	sms_package_id='".$_GET['package_id']."'
		            	ORDER BY id DESC
		            	LIMIT 0,1
		            	";		            	
		            	if ( $res2=$db_ext->rst($stmt)){		            		
		            		$current_id=$res2[0]['id'];
		            		$params_update=array('status'=>"paid");
		            		$db_ext->updateData("{{sms_package_trans}}",$params_update,'id',$current_id);
		            	}		            
						echo 'OK';
            	        die();
            	         		            	
		            } else $error=t("ERROR: api returns empty");	
		    		
		    	} catch (WebToPayException $e) {
	               $error=t("ERROR: Something went wrong").". ".$e;
	            }    			    	
		    } else {
				try {									
					$params_request=array(
				        'projectid'     => $projectid,
				        'sign_password' => $password,
				        'orderid'       => $payment_ref,
				        'amount'        => $amount_to_pay*100,
				        'currency'      => $currency,
				        'country'       => $country,
				        'accepturl'     => $accepturl,
				        'cancelurl'     => $cancel_url,
				        'callbackurl'   => $callback,
				        'test'          => $mode,
				        'lang'          =>$lang
				       );	
				     if ($mode==2){
				       	unset($params_request['test']);
				     }       
				     				     				     				     				    
				     $request = WebToPay::redirectToPayment($params_request);
					
				} catch (WebToPayException $e) {
		           $error=t("ERROR: Something went wrong").". ".$e;
		        }    			
		    }
		} else $error=Yii::t("default","Failed. Cannot process payment");  
				
		if (!empty($error)){
			$this->render('error',array('message'=>$error));
		}		
	}	
	
	public function actionOBDinit()
	{		
		$db_ext=new DbExt;
		
		$this->crumbsTitle=Yii::t("default","SMS Purchase Credits");
		
		$error='';
		$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
		$amount_to_pay=0;
		$merchant_id=Yii::app()->functions->getMerchantID();			
		
		$back_url=Yii::app()->request->baseUrl."/merchant/purchasesms";
		$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');			
		if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
			$amount_to_pay=$res['price'];
			if ( $res['promo_price']>0){
				$amount_to_pay=$res['promo_price'];
			}	    										
			$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';	
			$payment_description.=isset($res['title'])?$res['title']:'';		
									
			$merchant_info=Yii::app()->functions->getMerchantInfo();			
			$merchant_email=$merchant_info[0]->contact_email;			
			if (!empty($merchant_email)){				
				
				$subject=Yii::app()->functions->getOptionAdmin('admin_deposit_subject');
		    	$from=Yii::app()->functions->getOptionAdmin('admin_deposit_sender');
		    	
		    	if (empty($from)){
		    	    $from='no-reply@'.$_SERVER['HTTP_HOST'];
		    	}
		    	if (empty($subject)){
		    	    $subject=Yii::t("default","Bank Deposit instructions");
		    	}    	
		    			    	
		    	
		    	$link=Yii::app()->getBaseUrl(true)."/merchant/bankdepositverify/?ref=".$payment_ref;
    	        $links="<a href=\"$link\" target=\"_blank\" >".Yii::t("default","Click on this link")."</a>";
    	        $tpl=Yii::app()->functions->getOptionAdmin('admin_deposit_instructions');
		    	if (!empty($tpl)){   
		    		$tpl=Yii::app()->functions->smarty('amount',
    	            Yii::app()->functions->adminCurrencySymbol().Yii::app()->functions->standardPrettyFormat($amount_to_pay),$tpl);
    	            $tpl=Yii::app()->functions->smarty('verify-payment-link',$links,$tpl);    	            
    	            
    	            if (Yii::app()->functions->sendEmail($merchant_email,$from,$subject,$tpl)){
    	            	
    	            	$payment_code=Yii::app()->functions->paymentCode("bankdeposit");
					  	$params=array(
							  'merchant_id'=>$merchant_id,
							  'sms_package_id'=>$package_id,
							  'payment_type'=>$payment_code,
							  'package_price'=>$amount_to_pay,
							  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
							  'date_created'=>date('c'),
							  'ip_address'=>$_SERVER['REMOTE_ADDR'],
							  'payment_gateway_response'=>json_encode($_GET),						  
							  'payment_reference'=>$payment_ref
						 );							
						 		
						 $db_ext->insertData("{{sms_package_trans}}",$params);		    		
			    		 header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
			    		 die();		    		 
    	            	
    	            } else $error=t("ERROR: cannot send email to")." ".$merchant_email;
		    	} else $error=Yii::t("bank deposit instruction not yet available");
    					
			} else $error=t("please correct your email address. we cannot sent bank instruction with empty merchant email address");
		} else $error=Yii::t("default","Failed. Cannot process payment");  	
		
		if (!empty($error)){
			$this->render('error',array('message'=>$error));
		}				
	}
	
	public function actionBankDepositVerify()
	{
		$this->render('bank-deposit-verification');
	}
	
	public function actionAutoLogin()
	{
		$DbExt=new DbExt;
		$data=$_GET;		
		$stmt="SELECT * FROM
		       {{merchant}}
		       WHERE
		       merchant_id=".Yii::app()->db->quoteValue($data['id'])."
		       AND
		       password=".Yii::app()->db->quoteValue($data['token'])."
		       LIMIT 0,1
		";							
		if ( $res=$DbExt->rst($stmt)){										
			$_SESSION['kr_merchant_user']=json_encode($res);
			
			$session_token=Yii::app()->functions->generateRandomKey().md5($_SERVER['REMOTE_ADDR']);				
			 $params=array(
			  'session_token'=>$session_token,
			  //'last_login'=>date('c')
			 );
			 $DbExt->updateData("{{merchant}}",$params,'merchant_id',$res[0]['merchant_id']);
			 
			 $_SESSION['kr_merchant_user_session']=$session_token;
			 $_SESSION['kr_merchant_user_type']='admin';
			
			$this->redirect(baseUrl()."/merchant",true);			
		} else $msg=t("Login Failed. Either username or password is incorrect");
		echo $msg;
	}
		
	public function actionGallerySettings()
	{
		$this->crumbsTitle=Yii::t("default","gallery settings");		
		$this->render('gallery-settings');
	}
	
	public function actionPayOnDelivery()
	{		
		$py=Yii::app()->functions->getMerchantListOfPaymentGateway();		
		if (in_array('pyr',(array)$py)){
			$merchant_switch_master_pyr=Yii::app()->functions->getOption("merchant_switch_master_pyr",
			Yii::app()->functions->getMerchantID()); 			
			if ( $merchant_switch_master_pyr==2){
				 $this->render('noaccess');
			} else {
		    	$this->crumbsTitle=Yii::t("default","Pay On Delivery");		
			    $this->render('payondelivery');
			}
        } else $this->render('noaccess');
	}
	
	public function actionOffers()
	{
		$this->crumbsTitle=Yii::t("default","Offers");		
		if (isset($_GET['Do'])){
			if ( $_GET['Do']=="Add"){
				$this->crumbsTitle=Yii::t("default","Offers - add");		
				$this->render('offers_add');			
			} else $this->render('category_list');
		} else 	$this->render('offers');		
	}
	
	public function actionBarclay()
	{
		$this->crumbsTitle=Yii::t("default","Barclay settings");		
		$this->render('barclay-settings');
	}
	
	public function actionEpagbg()
	{
		$this->crumbsTitle=Yii::t("default","EpayBg settings");		
		$this->render('epaybg-settings');
	}	
	
	public function actionStatement()
	{
		$this->crumbsTitle=Yii::t("default","Statement");		
		$this->render('statement');
	}
	
	public function actionEarnings()
	{
		$this->crumbsTitle=Yii::t("default","Earnings");		
		$this->render('earnings');
	}
	
	public function actionIngredients()
	{
		$this->crumbsTitle=Yii::t("default","Ingredients");		
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="Add"){
				$this->crumbsTitle=Yii::t("default","Ingredients Add");		
				$this->render('ingredients-add');		
			} else {
				$this->crumbsTitle=Yii::t("default","Ingredients Sort");		
				$this->render('ingredients-sort');		
			}		
		} else $this->render('ingredients');		
	}
	
	public function actionWithdrawals()
	{
		$wd_enabled_paypal=getOptionA('wd_enabled_paypal');
		$wd_bank_deposit=getOptionA('wd_bank_deposit');		
		if ( $wd_enabled_paypal==2 || $wd_bank_deposit==2 ){
			$stats=yii::app()->functions->getOptionAdmin('wd_payout_disabled');		
			if ($stats==2){
				$this->crumbsTitle=Yii::t("default","Withdrawals");		
				$this->render('error',array('message'=>t("Sorry but widthrawal is disabled by the site owner")));
			} else {
				$this->crumbsTitle=Yii::t("default","Withdrawals");		
				$this->render('withdrawals');
			}
		} else {
			$this->render('error',array('message'=>t("Sorry but withdrawals is not available this time. admin has not yet set any payment method")));
		}
	}
	
	public function actionWithdrawalStep2()
	{
		$this->crumbsTitle=Yii::t("default","Withdrawals Complete");		
		$this->render('withdrawals-step2');
	}
	
	public function actionWithdrawalsHistory()
	{
		$this->crumbsTitle=Yii::t("default","Withdrawal History");		
		$this->render('withdrawals-history');
	}
	
	public function actionFaxSettings()
	{
		$this->crumbsTitle=Yii::t("default","Fax Settings");		
		$this->render('fax-settings');
	}
	
	public function actionFaxPurchase()
	{
		$this->crumbsTitle=Yii::t("default","Fax Purchase Credits");		
		$this->render('fax-purchase');
	}
	
	public function actionPay()
	{
		$get=$_GET;
		$raw=base64_decode(isset($_GET['raw'])?$_GET['raw']:'');
		parse_str($raw,$raw_decode);		
		$price='';		
		$description='';
		
		/*dump($get);
		dump($raw_decode);*/
		$package_id=$get['package_id'];
		
		if (is_array($raw_decode) && count($raw_decode)>=1){
			$price=isset($raw_decode['price'])?$raw_decode['price']:'';
			$description=isset($raw_decode['description'])?$raw_decode['description']:'';
		}
		
		$get_params="&method=".$get['method'];
		$get_params.="&purchase=".$get['purchase'];
		$get_params.="&package_id=".$get['package_id'];
		$get_params.="&raw=".$get['raw'];					
		
		if (!empty($price)){
			switch ($get['method']) {
				case "pyp":
					$paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();  
										
					$params='';
					$x=0;
					$params['L_NAME'.$x]=$description;
			        $params['L_NUMBER'.$x]=$get['package_id'];
			        $params['L_DESC'.$x]=$description;
			        $params['L_AMT'.$x]=normalPrettyPrice($price);
			        $params['L_QTY'.$x]=1;					
						        
					$params['AMT']=normalPrettyPrice($price);
$params['RETURNURL']="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/merchant/paymentconfirm/?$get_params";
				    $params['CANCELURL']=$get['return_url'];	  	  
				    $params['NOSHIPPING']='1';
			        $params['LANDINGPAGE']='Billing';
			        $params['SOLUTIONTYPE']='Sole';
			        $params['CURRENCYCODE']=adminCurrencyCode();			        
			        
			        $paypal=new Paypal($paypal_con);
			  	    $paypal->params=$params;
			  	    $paypal->debug=false;
			  	    if ($resp=$paypal->setExpressCheckout()){  	   	  			  	  	  
			  	  	    header("Location: ".$resp['url']);
			  	    } else {
			  	    	$this->render('error',array('message'=>"ERROR: ".$paypal->getError() ));
			  	    }
																	
					break;
					
				case "stp":				
				    $this->crumbsTitle=Yii::t("default","Fax Purchase Credits");			    
				    $this->render('pay_stripe',array(
				      'package_id'=>$package_id,
				      'price'=>$price,
				      'description'=>$description,
				      'redirect'=>"faxreceipt",
				      'payment_type'=>$get['method']
				    ));
				    break;
			
				default:
					break;
			}
		} else $this->render('error',array('message'=>t("Price is not define")));
	}
	
	public function actionPaymentConfirm()
	{
		$get=$_GET;				
		$raw=base64_decode($_GET['raw']);
		parse_str($raw,$raw_decode);		
		$price='';		
		$description='';
		
		//dump($raw_decode);
		if (is_array($raw_decode) && count($raw_decode)>=1){
			$price=isset($raw_decode['price'])?$raw_decode['price']:'';
			$description=isset($raw_decode['description'])?$raw_decode['description']:'';
		}
		
		//dump($get);
		if (!empty($price)){
			switch ($get['method']) {
				case "pyp":
					$this->crumbsTitle=Yii::t("default","Payment Confirmation");		
					$this->render('payment-paypal');
					break;
			
				default:
					$this->render('error',array(
					'message'=>t("Sorry but we cannot find what you are looking for.")));
					break;
			}
		} else $this->render('error',array('message'=>t("Price is not define")));		
	}
	
	public function actionfaxreceipt()
	{
		$this->crumbsTitle=Yii::t("default","Receipt");		
		$this->render('fax-receipt');
	}
	
	public function actionfaxbankdepositverification()
	{
		$this->crumbsTitle=Yii::t("default","Bank Deposit Verification");		
		$this->render('fax-deposit-verify');
	}
	
	public function actionFaxStats()
	{
		$this->crumbsTitle=Yii::t("default","Fax Stats");		
		$this->render('faxstats');
	}
	
	public function actionProfile()
	{
		$merchant_info=Yii::app()->functions->getMerchantInfo();
		$user_id=$merchant_info[0]->merchant_user_id;
		$data=Yii::app()->functions->getMerchantUserInfo($user_id);
		if (is_array($data) && count($data)>=1){
		    $this->crumbsTitle=Yii::t("default","Profile");		
			$this->render('profile',array('data'=>$data));
		} else {
			$this->crumbsTitle=Yii::t("default","Error");		
			$this->render('error',array('message'=>t("Error session has expired")));
		}
	}
	
	public function actionFaxPurchaseTrans()
	{
		$this->crumbsTitle=Yii::t("default","Purchase Credit Transactions");		
		$this->render('fax-purchasetrans');
	}
	
	public function actionPurchaseSmsTransaction()
	{
		$this->crumbsTitle=Yii::t("default","Purchase Credit Transactions");		
		$this->render('sms-purchasetrans');
	}
	
	public function actionShippingRate()
	{
		$this->crumbsTitle=Yii::t("default","Delivery Charges Rates");		
		$this->render('shippingrate');
	}
	
	public function actionBookingReport()
	{
		$this->crumbsTitle=Yii::t("default","Booking Summary Report");		
		$this->render('rpt-bookingreport');
	}
	
	public function actionCashStatement()
	{
		$this->crumbsTitle=Yii::t("default","Cash Statement");
		$this->render('statement-cash');
	}
	
	public function actionAuthorize()
	{
		$this->crumbsTitle=Yii::t("default","Authorize.net");
		$this->render('authorize-settings');
	}
	
	public function actionAtzinit()
	{		
		$this->crumbsTitle=Yii::t("default","Pay using Authorize.net");
		$this->render('atz-init');
	}
	
	public function actionEpyinit()
	{
		$this->crumbsTitle=Yii::t("default","Pay using EpayBg");
		$this->render('epy-init');
	}
	
	public function actionEpaybg()
	{
		$post=$_POST;
		$get=$_GET;		
		$error='';
		
			
		switch ($get['mode']) {
			case "accept":
				if ( $res=Yii::app()->functions->barclayGetTokenTransaction($get['token'])){
					
					if ( $package_info=Yii::app()->functions->getSMSPackagesById($res['param1']) ){
						
						$amount_to_pay=$package_info['price'];
						if ( $package_info['promo_price']>0){
							$amount_to_pay=$package_info['promo_price'];
						}	    
						
						$db_ext=new DbExt;
						$payment_code=Yii::app()->functions->paymentCode("epaybg");
	        	        
				        $params=array(
						  'merchant_id'=>Yii::app()->functions->getMerchantID(),
						  'sms_package_id'=>$package_info['sms_package_id'],
						  'payment_type'=>$payment_code,
						  'package_price'=>$amount_to_pay,
						  'sms_limit'=>isset($package_info['sms_limit'])?$package_info['sms_limit']:'',
						  'date_created'=>date('c'),
						  'ip_address'=>$_SERVER['REMOTE_ADDR'],
						  'payment_reference'=>$res['orderid']
						  /*'payment_gateway_response'=>json_encode($chargeArray),
						  'status'=>"paid"*/
						);	    	
						
						if ( $db_ext->insertData("{{sms_package_trans}}",$params)){				
header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
				           die();
			            } else $error=Yii::t("default","ERROR: Cannot insert record.");	
					}
				} else $error=t("Transaction token not found");
				header('Location: '.websiteUrl()."/merchant/purchasesms?error=".$error); 
				break;
				
			case "cancel":
				header('Location: '.websiteUrl()."/merchant/purchasesms"); 
				break;
			default:
				header('Location: '.websiteUrl()."/merchant/purchasesms"); 
				break;
		}
	}
	
	public function actionOBD()
	{
		$this->crumbsTitle=Yii::t("default","Offline Bank Deposit");
		$this->render('obd-settings');
	}
	
	public function actionOBDReceive()
	{
		$this->crumbsTitle=Yii::t("default","Receive Bank Deposit");
		$this->render('obd-deposit-receive');
	}

}
/*END CONTROLLER*/
