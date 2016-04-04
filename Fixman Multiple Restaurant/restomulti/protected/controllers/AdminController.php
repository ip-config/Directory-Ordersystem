<?php
/**
 * AdminController Controller
 *
 */
if (!isset($_SESSION)) { session_start(); }

class AdminController extends CController
{
	public $layout='admin_tpl';	
	public $crumbsTitle='';
	
	/*public function accessRules()
	{				
	}
	
	public function filters()
	{		
	}
	*/
	public function beforeAction($action)
    {    	    	
    	$action_name= $action->id ;
    	$accept_controller=array('login','ajax');
	    if(!Yii::app()->functions->isAdminLogin() )
	    {
	    	if (!in_array($action_name,$accept_controller)){	    		
	    	   if ( Yii::app()->functions->has_session){
	    	   	    $message_out=t("You were logout because someone login with your account");
	    	   	    $this->redirect(array('admin/login/?message='.urlencode($message_out)));
	    	   } else $this->redirect(array('admin/login'));	           
	    	}
	    }	
	    
	    $aa_access=Yii::app()->functions->AAccess();
	    $menu_list=Yii::app()->functions->AAmenuList();	    
	    if (in_array($action_name,(array)$menu_list)){
	    	if (!in_array($action_name,(array)$aa_access)){	    		
	    		//dump($_SESSION['kr_user']);
	    		$this->redirect(Yii::app()->createUrl('/admin/noaccess'));
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
		 		 
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 				 
	}
	
	public function actionDashboard()
	{
		if ( !Yii::app()->functions->isAdminLogin()){			
			$this->layout='login_tpl';
			$this->render('login');
		} else {						
			$this->crumbsTitle=Yii::t("default","Dashboard");		
			$this->render('dashboard');			
		}		
	}
				  
	public function actionIndex()
	{					
		if ( !Yii::app()->functions->isAdminLogin()){			
			$this->layout='login_tpl';
			$this->render('login');
		} else {						
			$aa_access=Yii::app()->functions->AAccess();
			if (in_array('dashboard',(array)$aa_access)){
				$this->crumbsTitle=Yii::t("default","Dashboard");		
				$this->render('dashboard');			
			} else $this->render('error',array('msg'=>t("Sorry but you don't have access to dashboard")));			
		}		
	}	
	
	public function actionLogin()
	{		
		if (isset($_GET['logout'])){
			//Yii::app()->request->cookies['kr_user'] = new CHttpCookie('kr_user', ""); 			
			//Yii::app()->request->cookies['kr_admin_lang_id'] = new CHttpCookie('kr_admin_lang_id', ""); 			
			unset($_SESSION['kr_user']);
			unset($_SESSION['kr_user_session']);
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
		
		/**add ons */     
		if (isset($data['addon'])){
			Yii::import('application.extension.AddonExport.*');
			$class=new $data['addon'];
			$class->data=$data;
			if (method_exists($class,$data['action'])){
				$class->$data['action']();	    
	            echo $class->output();
			}
			yii::app()->end();
		}
		/**add ons */     
		
		$class=new AjaxAdmin;
	    $class->data=$data;	    
	    if (method_exists($class,$data['action'])){
	    	$class->$data['action']();	    
	         echo $class->output();
	    } else {
	    	 $class=new Ajax;
	    	 $class->data=$data;
	    	 $class->$data['action']();	    
	         echo $class->output();
	    }
	    yii::app()->end();
	}	
	
	public function actionMerchant()
	{
		$this->crumbsTitle=Yii::t("default","Merchant");
		$this->render('merchant-list');
	}
	
	public function actionMerchantAdd()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Add");
		$this->render('merchant-add');
	}
	
	public function actionPackages()
	{		
		if (isset($_GET['Do'])){
		   $this->crumbsTitle=Yii::t("default","Packages Sort");
		   $this->render('packages-sort');
		} else {
		   $this->crumbsTitle=Yii::t("default","Packages");
		   $this->render('packages-list');
		}
	}
	
	public function actionPackagesAdd()
	{
		$this->crumbsTitle=Yii::t("default","Packages add");
		$this->render('packages-add');
	}
	
	public function actionPaypalSettings()
	{
		$this->crumbsTitle=Yii::t("default","Paypal Settings");
		$this->render('paypal-settings');
	}
	
	public function actionSettings()
	{
		$this->crumbsTitle=Yii::t("default","Settings");
		$this->render('settings');
	}
	
	public function actionRptMerchantReg()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Registration");
		$this->render('rpt-merchant-reg');
	}
	
	public function actionRptMerchantPayment()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Payment");
		$this->render('rpt-merchant-payment');
	}
	
	public function actionSponsoredMerchantList()
	{
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","Sponsored Merchant Add");
		    $this->render('sponsored-merchant-add');
		} else {
		    $this->crumbsTitle=Yii::t("default","Sponsored Merchant List");
		    $this->render('sponsored-merchant');	
		}		
	}	
	
	public function actionManageCurrency()
	{
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","Currency Add");
		    $this->render('currency-list-add');
		} else {
		    $this->crumbsTitle=Yii::t("default","Currency List");
		    $this->render('currency-list');	
		}		
	}
	
	public function actionCuisine()
	{
	   if (isset($_GET['Do'])){
	   	   if ($_GET['Do']=="Add"){
			   $this->crumbsTitle=Yii::t("default","Cuisine Add");
		       $this->render('cuisine-add');
	   	   } else {
	   	   	   $this->crumbsTitle=Yii::t("default","Cuisine Sort");
		       $this->render('cuisine-sort');
	   	   }
		} else {
		    $this->crumbsTitle=Yii::t("default","Cuisine List");
		    $this->render('cuisine-list');	
		}		
	}
	
	public function actionOrderStatus()
	{
		if (isset($_GET['Do'])){
	   	   if ($_GET['Do']=="Add"){
			   $this->crumbsTitle=Yii::t("default","Order Status Add");
		       $this->render('order-status-add');
	   	   }   	   	   
		} else {
		    $this->crumbsTitle=Yii::t("default","Order Status List");
		    $this->render('order-status-list');	
		}		
	}
	
	public function actionContactSettings()
	{
		$this->crumbsTitle=Yii::t("default","Contact Settings");
		$this->render('contact-settings');
	}
	
	public function actionSocialSettings()
	{
		$this->crumbsTitle=Yii::t("default","Social Settings");
		$this->render('social-settings');
	}
	
	public function actionRatings()
	{
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","Ratings Add");
		   $this->render('ratings-add');
		} else {
	       $this->crumbsTitle=Yii::t("default","Ratings");
		   $this->render('ratings');
		}
	}
	
	public function actionProfile()
	{
		$this->crumbsTitle=Yii::t("default","Profile Settings");
		$this->render('profile');
	}
	
	public function actionUserList()
	{
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","User Add");
		    $this->render('user-add');
		} else {
		    $this->crumbsTitle=Yii::t("default","User List");
		    $this->render('user-list');
		}
	}
	
	public function actionCustomPage()
	{
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="Add"){
			   $this->crumbsTitle=Yii::t("default","Custom page Add");
		       $this->render('custom-add');
			} elseif ($_GET['Do']=="AddCustom"){
				$this->crumbsTitle=Yii::t("default","Add New Custom Link");
		        $this->render('custom-add-link');
			} else {
			   $this->crumbsTitle=Yii::t("default","Assign Page");
		       $this->render('custom-assign-page');
			}
		} else {
		    $this->crumbsTitle=Yii::t("default","Custom page List");
		    $this->render('custom-list');
		}
	}
	
	public function actionManageLanguage()
	{
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="Add"){
			   $this->crumbsTitle=Yii::t("default","Manage Language Add");
		       $this->render('manage-language-add');
			} else {
				$this->crumbsTitle=Yii::t("default","Manage Language Settings");
		        $this->render('manage-language-settings');
			}
		} else {
		   $this->crumbsTitle=Yii::t("default","Manage Language");
		   $this->render('manage-language-list');
		}
	}
	
	public function actionSeo()
	{
		$this->crumbsTitle=Yii::t("default","SEO");
		$this->render('seo-settings');
	}
	
	public function actionStripeSettings()
	{
		$this->crumbsTitle=Yii::t("default","Stripe");
		$this->render('stripe-settings');
	}
	
	public function actionSmsSettings()
	{
		$this->crumbsTitle=Yii::t("default","SMS Settings");
		$this->render('sms-settings');
	}	
	
	public function actionSmsPackage()
	{
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="Add"){
				$this->crumbsTitle=Yii::t("default","SMS Package Add");
		        $this->render('sms-package-add');
			} else {
				$this->crumbsTitle=Yii::t("default","SMS Package Sort");
		        $this->render('sms-package-sort');
			}		
		} else {
		   $this->crumbsTitle=Yii::t("default","SMS Package");
		   $this->render('sms-package-list');
		}
	}	

	public function actionSetlanguage()
	{		
		if (isset($_GET['Id'])){			
			Yii::app()->request->cookies['kr_admin_lang_id'] = new CHttpCookie('kr_admin_lang_id', $_GET['Id']);						
			$id=Yii::app()->functions->getAdminId();
			Yii::app()->functions->updateAdminLanguage($id,$_GET['Id']);
			
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
	
	public function actionViewFile()
	{
		$data=$_GET;	
		$fileName=isset($data['fileName'])?$data['fileName']:'';
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload";	
		if (file_exists($path_to_upload."/".$fileName)){						
			show_source($path_to_upload."/".$fileName);
		} else echo Yii::t("default","File not found");
	}
	
	public function actionmercadopagoSettings()
	{
		$this->crumbsTitle=Yii::t("default","Mercadopago");
		$this->render('mercadopago-settings');
	}
	
	public function actionShowLanguage()
	{
		//header("Content-type: text/plain");
		$file=Yii::getPathOfAlias('webroot')."/mt_language_file.php";
		//show_source($file);		
		header("Content-disposition: attachment; filename=mt_language_file.php");
        header("Content-type: text/plain");
        readfile($file);
	}
	
	public function actionSMStransaction()
	{
		if (isset($_GET['Do'])){	
			$this->crumbsTitle=Yii::t("default","SMS Transaction Update");
		    $this->render('sms-transaction-add');
		} else {	
			$this->crumbsTitle=Yii::t("default","SMS Transaction");
			$this->render('sms-transaction');
		}
	}
	
	public function actionPayLineSettings()
	{
		$this->crumbsTitle=Yii::t("default","Payline Settings");
		$this->render('payline-settings');
	}
	
	public function actionSisowSettings()
	{
		$this->crumbsTitle=Yii::t("default","Sisow Settings");		
		$this->render('sisow-settings');
	}
	
	public function actionPayuMonenySettings()
	{
		$this->crumbsTitle=Yii::t("default","PayUMoney Settings");		
		$this->render('payumoney-settings');
	}
	
	public function actionAnalytics()
	{
		$this->crumbsTitle=Yii::t("default","Google Analytics");		
		$this->render('analytics-settings');
	}
	
	public function actionCustomerlist()
	{
		if (isset($_GET['Do'])){	
			$this->crumbsTitle=Yii::t("default","Customer List");		
		    $this->render('customer-add');
		} else {	
			$this->crumbsTitle=Yii::t("default","Customer List");		
			$this->render('customer-list');
		}
	}
	
	public function actionrptMerchanteSales()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Sales Report");		
		$this->render('rpt-merchant-sales');
	}
	
	public function actionOBDsettings()
	{
		$this->crumbsTitle=Yii::t("default","Offline Bank Deposit");		
		$this->render('offlinebank-settings');
	}
	
	public function actionBankdeposit()
	{
		$this->crumbsTitle=Yii::t("default","Receive Bank Deposit");		
		$this->render('bankdeposit-list');
	}
	
	public function actionPayseraSettings()
	{
		$this->crumbsTitle=Yii::t("default","paysera settings");		
		$this->render('paysera-settings');
	}
	
	public function actionEmailSettings()
	{
		$this->crumbsTitle=Yii::t("default","Mail & SMTP Settings");		
		$this->render('email-settings');
	}
	
	public function actionEmailTPL()
	{
		$this->crumbsTitle=Yii::t("default","Email Template");		
		$this->render('email-tpl');
	}
	
	public function actionPaymentGatewaySettings()
	{
		$this->crumbsTitle=Yii::t("default","Payment Gateway Settings");		
		$this->render('paymentgatewa-settings');
	}	
	
	public function actionPayOnDelivery()
	{
		 $this->crumbsTitle=Yii::t("default","Pay On Delivery settings");				
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="Add"){
				$this->render('payondelivery-settings-add');
			} else $this->render('payondelivery-settings-sort');			
		}  else $this->render('payondelivery-settings');		
	}
	
	public function actionsubscriberlist()
	{
		$this->crumbsTitle=Yii::t("default","Subscriber List");		
		$this->render('subscriber-list');
	}
	
	public function actionMerchantAddBulk()
	{
		$this->crumbsTitle=Yii::t("default","Upload Bulk CSV");		
		$this->render('merchant-bulk');
	}
	
	public function actionSMSlogs()
	{
		$this->crumbsTitle=Yii::t("default","SMS Logs");
		$this->render('sms-logs');
	}
	
	public function actionBarclay()
	{	
		$this->crumbsTitle=Yii::t("default","Barclay Settings");
		$this->render('barclay');
	}
	
	public function actionEpayBg()
	{	
		$this->crumbsTitle=Yii::t("default","EpayBg Settings");
		$this->render('epaybg');
	}	
	
	public function actionCommisionSettings()
	{	
		$this->crumbsTitle=Yii::t("default","Commission Settings");
		$this->render('commision-settings');
	}	
	
	public function actionmerchantcommission()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Commission");
		$this->render('merchant-commision');
	}
	
	public function actionMerchantCommissiondetails()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Commission details");
		$this->render('merchant-commision-details');
	}
	
	public function actionWithdrawalSettings()
	{
		$this->crumbsTitle=Yii::t("default","Withdrawal settings");
		$this->render('withdrawal-settings');
	}
	
	public function actionincomingwithdrawal()
	{
		$this->crumbsTitle=Yii::t("default","Incoming Withdrawal");
		$this->render('withdrawal');
	}
	
	public function actionRptMerchantSalesummary()
	{
		$this->crumbsTitle=Yii::t("default","Merchant Sales Summary Report");
		$this->render('rpt-merchant-sales-summary');
	}
	
	public function actionFaxSettings()
	{
		$this->crumbsTitle=Yii::t("default","Fax Settings");
		$this->render('fax-settings');
	}
	
	public function actionFaxPackage()
	{
		if (isset($_GET['Do'])){
			if ($_GET['Do']=="Add"){
				$this->crumbsTitle=Yii::t("default","Fax Package Add");
		        $this->render('fax-package-add');
			} else {
				$this->crumbsTitle=Yii::t("default","Fax Package Sort");
		        $this->render('fax-package-sort');
			}		
		} else {
		   $this->crumbsTitle=Yii::t("default","Fax Package");
		   $this->render('fax-package-list');
		}	
	}
	
	public function actionFaxTransaction()
	{
		if (isset($_GET['Do'])){	
			$this->crumbsTitle=Yii::t("default","Fax Transaction Add/Update");
		    $this->render('fax-transaction-add');
		} else {	
		   $this->crumbsTitle=Yii::t("default","Fax Payment Transaction");
		   $this->render('fax-transaction');
		}
	}
	
	public function actionRptBookingSummary()
	{
		$this->crumbsTitle=Yii::t("default","Booking Summary Report");
		$this->render('rpt-booking-summary');
	}
	
	public function actionfaxlogs()
	{
		$this->crumbsTitle=Yii::t("default","Fax Logs");
		$this->render('fax-logs');
	}
	
	public function actionAuthorize()
	{
		$this->crumbsTitle=Yii::t("default","Authorize.net");
		$this->render('authorize-settings');
	}
	
	public function actionNoAccess()
	{
		$this->render('error',array('msg'=>t("Sorry but you don't have permission to access this page")));
	}
	
	public function actionReviews()
	{		
		$this->crumbsTitle=t("Reviews");
		if (isset($_GET['Do'])){
			$this->render('review-add');
		} else $this->render('reviews');		
	}
	
	public function actionDishes()
	{
		$this->crumbsTitle=t("Dishes");
		if (isset($_GET['Do'])){
			$this->render('dishes-add');
		} else $this->render('dishes-list');
	}
	
	public function actionVoucher()
	{
		$this->crumbsTitle=Yii::t("default","Voucher List");
		if (isset($_GET['Do'])){
			$this->crumbsTitle=Yii::t("default","Voucher Add/Update");
			$this->render('voucher-add');		
		} else $this->render('voucher-list');		
	}
	
	public function actionCardPaymentSettings()
	{
		$this->crumbsTitle=t("Offline Credit Card Payment");
		$this->render('card-payment-settings');
	}
	
	public function actionOrderTemplate()
	{
		$this->crumbsTitle=t("Order Email Template");
		$this->render('order-email-tpl');
	}
	
	public function actionZipCode()
	{
		if (getOptionA('home_search_mode')=="postcode"){
			$this->crumbsTitle=t("Zip Code");
			if (isset($_GET['Do'])){
				$data=FunctionsK::getZipCode($_GET['id']);
				$this->render('zipcode-add',array(
				  'data'=>$data
				));			
			} else $this->render('zipcode');		
		} else $this->render('error',array('msg'=>t("Zip code only be use if you enabled the searching to post code on settings")));
	}
	
	public function actionThemeSettings()
	{
		$this->render('theme-settings');
	}
} 
/*END CONTROLLER*/
