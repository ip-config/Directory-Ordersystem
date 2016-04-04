<?php
/**
 * MerchantController Controller
 *
 */
if (!isset($_SESSION)) { session_start(); }

class StoreController extends CController
{
	public $layout='store_tpl';	
	public $crumbsTitle='';
	
	public function beforeAction($action)
	{
		//$cs->registerCssFile($baseUrl.'/css/yourcss.css'); 		
		if( parent::beforeAction($action) ) {			
			
			/** Register all scripts here*/
			ScriptManager::RegisterAllJSFile();
			ScriptManager::registerAllCSSFiles();
			return true;
		}
		return false;
	}
	
	public function accessRules()
	{		
		
	}
		
	public function init()
	{		
		 $name=Yii::app()->functions->getOptionAdmin('website_title');
		 if (!empty($name)){		 	
		 	 Yii::app()->name = $name;
		 }
		 		 
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 		 
	}
	
	public function actionHome()
	{
		$this->redirect(Yii::app()->request->baseUrl."/store");
	}
				  
	public function actionIndex()
	{							
		unset($_SESSION['voucher_code']);
        unset($_SESSION['less_voucher']);
        unset($_SESSION['google_http_refferer']);
        
		if (isset($_GET['token'])){
			if (!empty($_GET['token'])){
			    //Yii::app()->functions->paypalSetCancelOrder($_GET['token']);
			}
		}
				
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_home');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_home_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_home_keywords');
					
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$this->render('index');
	}	
	
	public function actionCity()
	{
		$this->render('index');
	}
	
	public function actionCuisine()
	{
		$this->render('index');
	}
	
	public function actionSignup()
	{
		$this->render('signup');
	}
	
	public function actionSignin()
	{
		$this->render('index');
	}
	
	public function actionMerchantSignup()
	{		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_merchantsignup');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_merchantsignup_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_merchantsignup_keywords');
		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
						
		if (isset($_GET['Do'])){
			switch ($_GET['Do']) {
				case 'step2':
					$this->render('merchant-signup-step2');		
					break;
				case "step3":
					 $this->render('merchant-signup-step3');		
					break;
				case "step3a":
					 $this->render('merchant-signup-step3a');		
					break;	
				case "step3b":					    
					if (isset($_GET['gateway'])){
						if ($_GET['gateway']=="mcd"){
							$this->render('mercado-init');
						} elseif ( $_GET['gateway']=="pyl" ){
							$this->render('payline-init2');
						} elseif ( $_GET['gateway']=="ide" ){
							$this->render('sow-init');
						} elseif ( $_GET['gateway']=="payu" ){							
							$this->render('pau-init');	
						} elseif ( $_GET['gateway']=="pys" ){							
							$this->render('paysera-init');	
						} else {
							$this->render($_GET['gateway'].'-init');	
						}
					} else $this->render('merchant-signup-step3b');
					break;		
				case "step4":					     
				     $disabled_verification=Yii::app()->functions->getOptionAdmin('merchant_email_verification');
				     if ( $disabled_verification=="yes"){
				     	$this->render('merchant-signup-thankyou2');		
				     } else $this->render('merchant-signup-step4');							 
					break;	
				case "thankyou1":
					 $this->render('merchant-signup-thankyou1');		
					break;		
				case "thankyou2":
					$this->render('merchant-signup-thankyou2');		
					break;		
				case "thankyou3":
					$this->render('merchant-signup-thankyou3');		
					break;			
				default:
					$this->render('merchant-signup');		
					break;
			}
		} else $this->render('merchant-signup');		
	}
	
	public function actionAbout()
	{
		$this->render('index');
	}
	
	public function actionContact()
	{
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_contact');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_contact_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_contact_keywords');
		
		if (!empty($seo_title)){
			$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		    $this->pageTitle=$seo_title;
		    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$this->render('contact');
	}
	
	public function actionSearchArea()
	{
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_search');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_search_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_search_keywords');
		
		if (!empty($seo_title)){
			$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		    $this->pageTitle=$seo_title;
		    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$this->render('search_area');
	}
	
	public function actionMenu()
	{
		
		$data=$_GET;		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
									
        if ( isset($data['merchant'])){
		   $re_info=Yii::app()->functions->getMerchantBySlug($data['merchant']);
		} else $re_info='';
						
		//if ( $current_merchant!=""){
			if ( $current_merchant !=$re_info['merchant_id']){				
				unset($_SESSION['kr_item']);
			}
		//}
				
		if (is_array($re_info) && count($re_info)>=1){
			if ( $re_info['status']!="active"){			
				$this->render('error',array('message'=>t("Sorry but this merchant is no longer available")));
				return ;
			}	
		}

		$seo_title=Yii::app()->functions->getOptionAdmin('seo_menu');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_menu_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_menu_keywords');
		
		if (!empty($seo_title)){
			$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
			$seo_title=smarty('merchant_name',ucwords($re_info['restaurant_name']),$seo_title);		    
		    $this->pageTitle=$seo_title;
		    
		    $seo_meta=smarty('merchant_name',ucwords($re_info['restaurant_name']),$seo_meta);
		    $seo_key=smarty('merchant_name',ucwords($re_info['restaurant_name']),$seo_key);		    
		    
		    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
						
		$mt_id=isset($re_info['merchant_id'])?$re_info['merchant_id']:'';							
		
		 $mt_timezone=Yii::app()->functions->getOption("merchant_timezone",$mt_id);	   	   	    	
    	 if (!empty($mt_timezone)){       	 	
    		Yii::app()->timeZone=$mt_timezone;
    	 }		     	 
		
		$bg=Yii::app()->functions->getOption("merchant_photo_bg",$mt_id);		
		$this->render('menu',array('re_info'=>$re_info,'data'=>$_GET,'merchant_header'=>$bg));	
		/*if (!empty($bg)){
			$this->render('menu-with-bg',array('re_info'=>$re_info,
			  'merchant_header'=>$bg,
			  'data'=>$_GET
			  ));
		} else $this->render('menu',array('re_info'=>$re_info,'data'=>$_GET));*/
	}
	
	public function actionCheckout()
	{
		$_SESSION['google_http_refferer']=websiteUrl()."/store/paymentOption";
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_checkout');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_checkout_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_checkout_keywords');
		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
											               		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   if ( $info=Yii::app()->functions->getMerchant($current_merchant)){        	
		   	   $seo_title=smarty('merchant_name',ucwords($info['restaurant_name']),$seo_title);
           }		   
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$this->render('checkout');
	}
	
	public function actionPaymentOption()
	{	
		
		/*POINTS PROGRAM*/
		//PointsProgram::includeFrontEndFiles();	    
		
 	    $seo_title=Yii::app()->functions->getOptionAdmin('seo_checkout');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_checkout_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_checkout_keywords');
		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   if ( $info=Yii::app()->functions->getMerchant($current_merchant)){        	
		   	   $seo_title=smarty('merchant_name',ucwords($info['restaurant_name']),$seo_title);
           }		   
		   $this->pageTitle=$seo_title;		   
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		$this->render('payment-option');
	}
	
	public function actionReceipt()
	{
		$this->render('receipt');
	}
	
	public function actionLogout()
	{
		unset($_SESSION['kr_client']);
		$http_referer=$_SERVER['HTTP_REFERER'];				
		if (preg_match("/receipt/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/orderHistory/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/Profile/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/Cards/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/PaymentOption/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/verification/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if ( !empty($http_referer)){
			header("Location: ".$http_referer);
		} else header("Location: ".Yii::app()->request->baseUrl);		
	}
	
	public function actionPaypalInit()
	{
		$this->render('paypal-init');
	}
	
	public function actionPaypalVerify()
	{
		$this->render('paypal-verify');
	}
	
	public function actionOrderHistory()
	{
		$this->render('order-history');
	}
	
	public function actionProfile()
	{
		$this->render('profile');
	}
	
	public function actionCards()
	{
		if ( getOptionA('disabled_cc_management')=="yes"){
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));
		} else {
			if (isset($_GET['Do'])){
				if ($_GET['Do']=="Edit"){
					$this->render('cards-edit');
				} else $this->render('cards-add');			
			} else $this->render('cards');		
		}
	}
	
	public function actionhowItWork()
	{
		$this->render('dynamic-page');
	}
	
	public function actionforgotPassword()
	{
		$this->render('forgot-pass');
	}
	
	public function actionPage()
	{
		$this->render('custom-page');
	}
	
	public function actionSetlanguage()
	{		
		if (isset($_GET['Id'])){			
			Yii::app()->request->cookies['kr_lang_id'] = new CHttpCookie('kr_lang_id', $_GET['Id']);			
			//$_COOKIE['kr_lang_id']=$_GET['Id'];
			/*dump($_COOKIE);
			die();*/
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
	
	public function actionstripeInit()
	{
		$this->render('stripe-init');
	}
	
	public function actionMercadoInit()
	{
		$this->render('mercado-merchant-init');
	}
	
	public function actionRenewSuccesful()
	{
		$this->render('merchant-renew-successful');
	}
	
	public function actionBrowse()
	{
		$this->render('browse-resto');
	}
	
	public function actionPaylineInit()
	{
		$this->render('payline-init');
	}
	
	public function actionPaylineverify()
	{		
		$this->render('payline-verify');
	}
	
	public function actionsisowinit()
	{
		$this->render('sow-init-merchant');
	}
	
	public function actionPayuInit()
	{		
		$this->render('payuinit-merchant');
	}
	
	public function actionBankDepositverify()
	{
		$this->render('bankdeposit-verify');
	}
	
	public function actionAutoResto()
	{		
		$datas='';
		$str=isset($_POST['search'])?$_POST['search']:'';
		$db_ext=new DbExt;
		$stmt="SELECT restaurant_name
		FROM
		{{view_merchant}}
		WHERE
		restaurant_name LIKE '%$str%'
		AND
		status ='active'
		AND
		is_ready='2'
		ORDER BY restaurant_name ASC
		LIMIT 0,20
		";
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {				
				$name=ucwords($val['restaurant_name']);
				$datas[]=array(				  
				  'value'=>$name,
				  'title'=>$name,
				  'text'=>$val['restaurant_name']
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionAutoStreetName()
	{
		$datas='';
		$str=isset($_POST['search'])?$_POST['search']:'';
		$db_ext=new DbExt;
		$stmt="SELECT street
		FROM
		{{view_merchant}}
		WHERE
		street LIKE '%$str%'
		AND
		status ='active'
		AND
		is_ready='2'
		ORDER BY restaurant_name ASC
		LIMIT 0,20
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {				
				$name=ucwords($val['street']);
				$datas[]=array(				  
				  'value'=>$name,
				  'title'=>$name,
				  'text'=>$val['street']
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionAutoCategory()
	{
		$datas='';
		$str=isset($_POST['search'])?$_POST['search']:'';
		$db_ext=new DbExt;
		$stmt="SELECT cuisine_name
		FROM
		{{cuisine}}
		WHERE
		cuisine_name LIKE '%$str%'		
		ORDER BY cuisine_name ASC
		LIMIT 0,20
		";				
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {				
				$name=ucwords($val['cuisine_name']);
				$datas[]=array(				  
				  'value'=>$name,
				  'title'=>$name,
				  'text'=>$val['cuisine_name']
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionPayseraInit()
	{
		$this->render('merchant-paysera');
	}	
	
	public function actionAutoFoodName()
	{
		$datas='';
		$str=isset($_POST['search'])?$_POST['search']:'';
		$db_ext=new DbExt;
		$stmt="SELECT item_name
		FROM
		{{item}}
		WHERE
		item_name LIKE '%$str%'	
		Group by item_name	
		ORDER BY item_name ASC
		LIMIT 0,16
		";					
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {				
				$name=ucwords($val['item_name']);
				$datas[]=array(				  
				  'value'=>$name,
				  'title'=>$name,
				  'text'=>$val['item_name']
				);
			}			
			echo json_encode($datas);
		}
	}
	
	public function actionConfirmorder()
	{
		$data=$_GET;		
		if (isset($data['id']) && isset($data['token'])){
			$db_ext=new DbExt;
			$stmt="SELECT a.*,
				(
				select activation_token
				from
				{{merchant}}
				where
				merchant_id=a.merchant_id
				) as activation_token
			 FROM
			{{order}} a
			WHERE
			order_id=".Yii::app()->functions->q($data['id'])."
			";
			if ($res=$db_ext->rst($stmt)){
				if ( $res[0]['activation_token']==$data['token']){
					$params=array(
					 'status'=>"received",
					 'date_modified'=>date('c'),
					 'ip_address'=>$_SERVER['REMOTE_ADDR'],
					 'viewed'=>2
					);				
					if ($res[0]['status']=="paid"){
						unset($params['status']);
					}	
					if ( $db_ext->updateData("{{order}}",$params,'order_id',$data['id'])){
						$msg=t("Order Status has been change to received, Thank you!");
					} else $msg= t("Failed cannot update order");
				} else $msg= t("Token is invalid or not belong to the merchant");
			}
		} else $msg= t("Missing parameters");
		$this->render('confirm-order',array('data'=>$msg));
	}
	
	public function actionApicheckout()
	{
		$data=$_GET;		
		if (isset($data['token'])){
			$ApiFunctions=new ApiFunctions;		
			if ( $res=$ApiFunctions->getCart($data['token'])){				
				$order='';
				$merchant_id=$res[0]['merchant_id'];
				foreach ($res as $val) {															
					$temp=json_decode($val['raw_order'],true);				
					$temp_1='';
					if(is_array($temp) && count($temp)>=1){						
						$temp_1['row']=$val['id'];
						$temp_1['row_api_id']=$val['id'];
						$temp_1['merchant_id']=$val['merchant_id'];
						$temp_1['currentController']="store";
						foreach ($temp as $key=>$value) {							
							$temp_1[$key]=$value;
						}						
						$order[]=$temp_1;
					}
				}							
				//unset($_SESSION);
				$_SESSION['api_token']=$data['token'];
				$_SESSION['currentController']="store";
				$_SESSION['kr_merchant_id']=$merchant_id;
				$_SESSION['kr_delivery_options']['delivery_type']=$data['delivery_type'];
				$_SESSION['kr_delivery_options']['delivery_date']=$data['delivery_date'];
				$_SESSION['kr_delivery_options']['delivery_time']=$data['delivery_time'];				
				$_SESSION['kr_item']=$order;								
				$redirect=Yii::app()->getBaseUrl(true)."/store/checkout";
				header("Location: ".$redirect);
				$this->render('error',array('message'=>t("Please wait while we redirect you")));
			} else $this->render('error',array('message'=>t("Token not found")));
		} else $this->render('error',array('message'=>t("Token is missing")));
	}
	
	public function actionPaymentbcy()
	{
		$db_ext=new DbExt;
		
		$data=$_GET;
		//dump($data);		
		if (isset($data['orderID'])){
			if ( $res=Yii::app()->functions->barclayGetTransaction($data['orderID'])){
				//dump($res);
				if ($data['do']=="accept") {
									
					switch ($res['transaction_type']) {
						case "order":							
							$order_id=$res['token'];							
							$order_info=Yii::app()->functions->getOrder($order_id);							
										
							$db_ext=new DbExt;
					        $params_logs=array(
					          'order_id'=>$order_id,
					          'payment_type'=>"bcy",
					          'raw_response'=>json_encode($data),
					          'date_created'=>date('c'),
					          'ip_address'=>$_SERVER['REMOTE_ADDR'],
					          'payment_reference'=>$data['PAYID']
					        );		 
					        					        
					        $db_ext->insertData("{{payment_order}}",$params_logs);		      
			
					        $params_update=array('status'=>'paid');	        
				            $db_ext->updateData("{{order}}",$params_update,'order_id',$order_id);					          
					        header('Location: '.Yii::app()->request->baseUrl."/store/receipt/id/".$order_id);
			        		die();
							
							break;
							
						case "renew":
						case "signup":
							
							$my_token=$res['token'];
							$token_details=Yii::app()->functions->getMerchantByToken($res['token']);
							
							if ( $res['transaction_type']=="renew"){
																							
								$package_id=$token_details['package_id'];
							    if ($new_info=Yii::app()->functions->getPackagesById($package_id)){	   
										$token_details['package_name']=$new_info['title'];
										$token_details['package_price']=$new_info['price'];
										if ($new_info['promo_price']>0){
											$token_details['package_price']=$new_info['promo_price'];
										}			
								}
																
								$membership_info=Yii::app()->functions->upgradeMembership($token_details['merchant_id'],
								$package_id);
																					
			    				$params=array(
						          'package_id'=>$package_id,	          
						          'merchant_id'=>$token_details['merchant_id'],
						          'price'=>$token_details['package_price'],
						          'payment_type'=>Yii::app()->functions->paymentCode('barclay'),
						          'membership_expired'=>$membership_info['membership_expired'],
						          'date_created'=>date('c'),
						          'ip_address'=>$_SERVER['REMOTE_ADDR'],
						          'PAYPALFULLRESPONSE'=>json_encode($data),
						          'TRANSACTIONID'=>$data['PAYID'],
						          'TOKEN'=>$data['PAYID']			           
						        );		
								
							} else {
								$params=array(
						           'package_id'=>$token_details['package_id'],	          
						           'merchant_id'=>$token_details['merchant_id'],
						           'price'=>$token_details['package_price'],
						           'payment_type'=>Yii::app()->functions->paymentCode('barclay'),
						           'membership_expired'=>$token_details['membership_expired'],
						           'date_created'=>date('c'),
						           'ip_address'=>$_SERVER['REMOTE_ADDR'],
						           'PAYPALFULLRESPONSE'=>json_encode($data),
						           'TRANSACTIONID'=>$data['PAYID'],
						           'TOKEN'=>$data['PAYID']			           
							     );										     
							}
							
							if ($data['STATUS']==5 || $data['STATUS']==9 ){
						        $params['status']='paid';
						    }			        
						         					         
					         $db_ext->insertData("{{package_trans}}",$params);				        
			                 $db_ext->updateData("{{merchant}}",
											  array(
											    'payment_steps'=>3,
											    'membership_purchase_date'=>date('c')
											  ),'merchant_id',$token_details['merchant_id']);
					
					         
							if ( $res['transaction_type']=="renew"){
                                header('Location: '.Yii::app()->request->baseUrl."/store/renewSuccesful");
                            } else {
                   header('Location: '.Yii::app()->request->baseUrl."/store/merchantSignup/Do/step4/token/$my_token"); 
                            }
                            die();
							break;
					
						default:
							break;
					}				
				} elseif ( $data['do']=="decline"){
					$this->render("error",array('message'=>t("Your payment has been decline")));
				} elseif ( $data['do']=="exception"){
					$this->render("error",array('message'=>t("Your Payment transactions is uncertain")));
				} elseif ( $data['do']=="cancel"){
					$this->render("error",array('message'=>t("Your transaction has been cancelled")));
				} else {
					$this->render("error",array('message'=>t("Unknow request")));
				}	
			} else $this->render("error",array('message'=>t("Cannot find order id")));
		} else $this->render("error",array('message'=>t("Something went wrong")));
	}
	
	public function actionBcyinit()
	{		
		$this->render("merchant-bcy");
	}
	
	public function actionEpayBg()
	{
		$db_ext=new DbExt;
		$data=$_GET;		
		$msg='';
		$error_receiver='';
				
		if ($data['mode']=="receiver"){
			
			$mode=Yii::app()->functions->getOptionAdmin('admin_mode_epaybg');				
			if ($mode=="sandbox"){					
				$min=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_min');
				$secret=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_secret');
			} else {					
				$min=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_min');
				$secret=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_secret');
			}
			/*dump($min);
			dump($secret);*/
			
			$EpayBg=new EpayBg;
			
			$ENCODED  = $data['encoded'];
            $CHECKSUM = $data['checksum'];                
            $hmac  = $EpayBg->hmac('sha1', $ENCODED, $secret);
                          
            /*dump("Check");
            dump($CHECKSUM);
            dump($hmac);*/
            
            //if ($hmac == $CHECKSUM) {                 	
            	$data_info = base64_decode($ENCODED);
                $lines_arr = split("\n", $data_info);
                $info_data = '';                    
                //dump($lines_arr);
                if (is_array($lines_arr) && count($lines_arr)>=1){                    	                    	
                	foreach ($lines_arr as $line) {
                		if (!empty($line)){
                		     $payment_info=explode(":",$line);	                    	                        	   
                    	     $invoice_number=str_replace("INVOICE=",'',$payment_info[0]);
                    	                                        	     
                    	    $status=str_replace("STATUS=",'',$payment_info[1]);
                    	    if (preg_match("/PAID/i", $payment_info[1])) {	                    	    	
                    	    	$info_data .= "INVOICE=$invoice_number:STATUS=OK\n";
                    	    	Yii::app()->functions->epayBgUpdateTransaction($invoice_number,$status);
                    	    } else {	                    	    	
                    	    	$info_data .= "INVOICE=$invoice_number:STATUS=ERR\n";
                    	    	Yii::app()->functions->epayBgUpdateTransaction($invoice_number,$status);
                    	    }                    		
                		}
                	}
                	echo $info_data;
                	Yii::app()->functions->createLogs($info_data,"epaybg");
                	die();
                } else $error_receiver="ERR=Not valid CHECKSUM\n";
            /*} else {
            	$error_receiver="ERR=Not valid CHECKSUM\n";
            }*/
            
            if (!empty($error_receiver)){
            	echo $error_receiver;
            	Yii::app()->functions->createLogs($error_receiver,"epaybg");
            } else {
            	Yii::app()->functions->createLogs("none response","epaybg");
            }		
			die();
			
		} elseif ( $data['mode']=="cancel" ){
			$msg=t("Transaction has been cancelled");
			
		} elseif (  $data['mode']=="accept"  ) {
								
			if ( $trans_info=Yii::app()->functions->barclayGetTokenTransaction($data['token'])){
				//dump($trans_info);
				switch ($data['mode']){
					case "accept":	
					     if ( $trans_info['transaction_type']=="order"){
					     	  $params_update=array(
					     	    'status'=>"pending",
					     	    'date_modified'=>date('c')
					     	  );
					     	  $db_ext->updateData("{{order}}",$params_update,'order_id',$data['token']);
					     	  header('Location: '.websiteUrl()."/store/receipt/id/".$data['token']);
					     } else {
						    if ( $token_details=Yii::app()->functions->getMerchantByToken($data['token'])){	
								$db_ext->updateData("{{merchant}}",
								  array(
								    'payment_steps'=>3,
								    'membership_purchase_date'=>date('c')
								  ),'merchant_id',$token_details['merchant_id']);
								
								header('Location: '.websiteUrl()."/store/merchantSignup/Do/thankyou2/token/".$data['token']); 
						    } else $msg=t("Token not found");	
					     }
						break;
						
					case "cancel":	
					    if ( $trans_info['transaction_type']=="order"){
					    	header('Location: '.websiteUrl()."/store/"); 
					    } else {
					        header('Location: '.websiteUrl()."/store/merchantSignup/Do/step3/token/".$data['token']); 
					    }
					    break;		
					
				}
			} else $msg=t("Transaction information not found");
		}
		
		if (!empty($msg)){
			$this->render('error',array('message'=>$msg));
		}
	}
	
	public function actionEpyInit()
	{
		$this->render('merchant-epyinit');
	}
	
	public function actionGuestCheckout()
	{
		/*POINTS PROGRAM*/
		//PointsProgram::includeFrontEndFiles();	    
		
		$this->render('payment-option',array('is_guest_checkout'=>true));
	}
	
	public function actionMerchantSignupSelection()
	{
		if ( Yii::app()->functions->getOptionAdmin("merchant_disabled_registration")=="yes"){
			$this->render('error',array('message'=>t("Sorry but merchant registration is disabled by admin")));
		} else $this->render('merchant-signup-selection');		
	}
	
	public function actionMerchantSignupinfo()
	{
		$this->render('merchant-signup-info');
	}
	
	public function actionCancelWithdrawal()
	{
		$this->render('withdrawal-cancel');
	}
	
	public function actionFax()
	{
		$this->layout='_store';
		$this->render('fax');
	}
	
	public function actionATZinit()
	{
		$this->render('atz-merchant-init');
	}
	
	public function actionDepositVerify()
	{
		$this->render('deposit-verify');
	}
	
	public function actionVerification()
	{
		$continue=true;
		$msg='';
		$id=Yii::app()->functions->getClientId();
		if (!empty($id)){
			$continue=false;
			$msg=t("Sorry but we cannot find what you are looking for.");
		}
		if ( $continue==true){
			if( $res=Yii::app()->functions->getClientInfo($_GET['id'])){								
				if ( $res['status']=="active"){
					$continue=false;
					$msg=t("Your account is already verified");
				}
			} else {
				$continue=false;
				$msg=t("Sorry but we cannot find what you are looking for.");
			}
		}		
		
		if ( $continue==true){
		   $this->render('mobile-verification');
		} else $this->render('error',array('message'=>$msg));
	}

	public function actionMap()
	{
		if ( getOptionA('view_map_disabled')==2){
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));
		} else {	
			$this->layout='_store';
			$this->render('map');
		}
	}
	
	public function missingAction($action)
	{
		$this->render('error',array('message'=>t("Sorry but we cannot find what you are looking for.")));
	}
	
	public function actionGoogleLogin()
	{
		if (isset($_GET['error'])){
			$this->redirect(Yii::app()->createUrl('/store')); 
		}
			
		$plus = Yii::app()->GoogleApis->serviceFactory('Oauth2');
		$client = Yii::app()->GoogleApis->client;
		Try {
			 if(!isset(Yii::app()->session['auth_token']) 
			  || is_null(Yii::app()->session['auth_token']))
			    // You want to use a persistence layer like the DB for storing this along
			    // with the current user
			    Yii::app()->session['auth_token'] = $client->authenticate();
			  else
			    			  			  			    
			    if (isset($_SESSION['auth_token'])) {			    	 
				    $client->setAccessToken($_SESSION['auth_token']);
				}		    
				
				if (isset($_REQUEST['logout'])) {				   
				   unset($_SESSION['auth_token']);
				   $client->revokeToken();
				}
																								
			    if ( $token=$client->getAccessToken()){			    	
			    	$t=$plus->userinfo->get();			    			    	
			    	if (is_array($t) && count($t)>=1){
				        $func=new FunctionsK();
				        if ( $resp_t=$func->googleRegister($t) ){						        	
				            Yii::app()->functions->clientAutoLogin($t['email'],
				            $resp_t['password'],$resp_t['password']);
				        	unset($_SESSION['auth_token']);
				            $client->revokeToken();		
				            if (isset($_SESSION['google_http_refferer'])){
				                $this->redirect($_SESSION['google_http_refferer']);   	
				            } else $this->redirect(Yii::app()->createUrl('/store')); 
				            
				        	die();
				        	
				        } else echo t("ERROR: Something went wrong");
			    	} else echo t("ERROR: Something went wrong");
			    }  else {
			    	$authUrl = $client->createAuthUrl();			    	
			    }			    
			    if(isset($authUrl)) {
				    print "<a class='login' href='$authUrl'>Connect Me!</a>";
				} else {
				   print "<a class='logout' href='?logout'>Logout</a>";
				}
		} catch(Exception $e) {
			Yii::app()->session['auth_token'] = null;
            throw $e;
		}
	}
		
	public function actionAddressBook()
	{
		 if ( Yii::app()->functions->isClientLogin()){
		 	if (isset($_GET['do'])){		
		 	   $data='';
		 	   if ( isset($_GET['id'])){
		 	   	    $data=Yii::app()->functions->getAddressBookByID($_GET['id']);
		 	   }		 
		       $this->render('address-book-add',array(
		         'data'=>$data
		       ));
		 	} else $this->render('address-book');
		 } else $this->render('error',array('message'=>t("Sorry but we cannot find what you are looking for.")));
	}
	
	public function actionAutoZipcode()
	{		
		$datas='';
		$str=isset($_POST['search'])?$_POST['search']:'';
		$db_ext=new DbExt;
		$stmt="
		SELECT DISTINCT zipcode,area,city FROM
		{{zipcode}}
		WHERE
		zipcode LIKE '$str%'		
		AND
		status IN ('publish','published')
		ORDER BY zipcode ASC
		LIMIT 0,16
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$full=$val['zipcode']." " .$val['area'] ." ".$val['city'];
				$datas[]=array(				  
				  'value'=>$full,
				  'title'=>$full,
				  'text'=>$full,
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionAutoPostAddress()
	{
		$datas='';
		$str=isset($_POST['search'])?$_POST['search']:'';
		$db_ext=new DbExt;
		$stmt="
		SELECT * FROM
		{{zipcode}}
		WHERE
		stree_name LIKE '$str%'		
		AND
		status IN ('publish','published')
		ORDER BY stree_name ASC
		LIMIT 0,16
		";				
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$full=$val['stree_name']."," .$val['area'] .",".$val['city'].",".$val['zipcode'];
				$datas[]=array(				  
				  'value'=>$full,
				  'title'=>$full,
				  'text'=>$full,
				);
			}			
			echo json_encode($datas);
		}
	}
		
	public function actionSMS()
	{
		$db_ext=new DbExt;
		$data=$_GET;		
		
		$resp='';
		$sms_to_sender='';
		$sms_customer='';
		$customer_number='';
		$sender=isset($data['msisdn'])?$data['msisdn']:'';
		$keys=array(0,1);
				
		if (isset($data['text'])){
			$text_split=explode(" ",$data['text']);			
			switch (strtolower($text_split[0])){
				case "order":
					$order_id=$text_split[1];					
					$stmt="SELECT a.order_id,
					a.client_id,
					b.contact_phone
					 FROM
					{{order}} a					
					left join {{order_delivery_address}} b
                    ON
                    a.order_id=b.order_id
					WHERE
					a.order_id=".q($order_id)."
					LIMIT 0,1
					";					
					if ( $res=$db_ext->rst($stmt)){
						$res=$res[0];						
						$customer_number=$res['contact_phone'];						
						foreach ($text_split as $key=>$val) {
							if (!array_key_exists($key,$keys)){
								$sms_customer.=$val." ";
							}
						}						
					} else {
						$resp="Order ID not found or you have invalid sms syntax.";
						$sms_to_sender=$resp;						
					}								
					break;
					
				default:
					$resp="Undefined SMS keyword";
					break;
			}		
			
			
			$sms_customer=$data['text'];			
			
			/*now we send the sms to either merchant or customer*/
			if (!empty($sms_customer) && !empty($customer_number)){
				/** send sms to customer*/				
				$send_resp=Yii::app()->functions->sendSMS($customer_number,$sms_customer);				
				$params_log=array(
				  'broadcast_id'=>'999999999',
				  'contact_phone'=>$customer_number,
				  'sms_message'=>$sms_customer,
				  'status'=>$send_resp['msg'],
				  'date_created'=>date('c'),
				  'date_executed'=>date('c'),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'gateway'=>$send_resp['sms_provider']
				);			
				$db_ext->insertData("{{sms_broadcast_details}}",$params_log);
				$resp="OK:SMS SEND";
			}		
			
			if (!empty($sms_to_sender) && !empty($sender)){
				/** send sms to sender or merchant*/							
				$send_resp=Yii::app()->functions->sendSMS($sender,$sms_to_sender);				
				$params_log=array(
				  'broadcast_id'=>'999999999',
				  'contact_phone'=>$sender,
				  'sms_message'=>$sms_to_sender,
				  'status'=>$send_resp['msg'],
				  'date_created'=>date('c'),
				  'date_executed'=>date('c'),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'gateway'=>$send_resp['sms_provider']
				);			
				$db_ext->insertData("{{sms_broadcast_details}}",$params_log);				
			}					
		} else $resp='missing text message';
		
		Yii::app()->functions->createLogs(array('msg'=>$resp),'sms-logs');
		echo "SMS:OK";
	}	
	
	public function actionMyPoints()
	{		
		/*POINTS PROGRAM*/
		//PointsProgram::includeFrontEndFiles();

		$points_enabled=getOptionA('points_enabled');
			
		if ( $points_enabled==1){
			if ( Yii::app()->functions->isClientLogin()){			
				$points=PointsProgram::getTotalEarnPoints(
				   Yii::app()->functions->getClientId()
				);			
				
				$points_expirint=PointsProgram::getExpiringPoints(
				   Yii::app()->functions->getClientId()
				);
				
				$this->render('pts-mypoints',array(
				 'earn_points'=>$points,
				 'points_expirint'=>$points_expirint
				));
			} else $this->render('error',array(
			  'message'=>t("Sorry but you need to login first.")
			));		
		} else {
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));		
		}
	}
	
} /*END CLASS*/
	