<?php
if (!isset($_SESSION)) { session_start(); }

class StoreController extends CController
{
	public $layout='store_tpl';	
	public $crumbsTitle='';
	public $theme_compression='';
	
	public function beforeAction($action)
	{
		//$cs->registerCssFile($baseUrl.'/css/yourcss.css'); 		
		if( parent::beforeAction($action) ) {			
			
			/** Register all scripts here*/
			if ($this->theme_compression==2){
				ScriptManagerCompress::RegisterAllJSFile();
			    ScriptManagerCompress::registerAllCSSFiles();
			   
				$compress_css = require_once 'assets/css/css.php';
			    $cs = Yii::app()->getClientScript();
			    Yii::app()->clientScript->registerCss('compress-css',$compress_css);
			} else {
			   ScriptManager::RegisterAllJSFile();
			   ScriptManager::registerAllCSSFiles();
			}
			return true;
		}
		return false;
	}
	
	public function accessRules()
	{		
		
	}
	
    public function filters()
    {
    	$this->theme_compression = getOptionA('theme_compression');
		if ($this->theme_compression==2){
	        $filters = array(  
	            array(
	                'application.filters.HtmlCompressorFilter',
	            ),  
	        );
	        return $filters;
		}
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

		
		$this->render('index',array(
		   'home_search_mode'=>getOptionA('home_search_mode'),
		   'enabled_advance_search'=> getOptionA('enabled_advance_search'),
		   'theme_hide_how_works'=>getOptionA('theme_hide_how_works'),
		   'theme_hide_cuisine'=>getOptionA('theme_hide_cuisine'),
		   'disabled_featured_merchant'=>getOptionA('disabled_featured_merchant'),
		   'disabled_subscription'=>getOptionA('disabled_subscription'),
		   'theme_show_app'=>getOptionA('theme_show_app'),
		   'theme_app_android'=>FunctionsV3::prettyUrl(getOptionA('theme_app_android')),
		   'theme_app_ios'=>FunctionsV3::prettyUrl(getOptionA('theme_app_ios')),
		   'theme_app_windows'=>FunctionsV3::prettyUrl(getOptionA('theme_app_windows')),
		));
	}
				  
	public function actionIndex()
	{							
		$this->redirect(Yii::app()->request->baseUrl."/store/home");
		Yii::app()->end();		
	}	
	
	public function actionCuisine()
	{
		/*update merchant if expired and sponsored*/
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();
		
		/*$category='';
		$getdata=isset($_GET)?$_GET:'';
		if(is_array($getdata) && count($getdata)>=1){
			$category=$getdata['category'];
			$category=str_replace("-"," ",$category);
		}
		
		if ( $cat_res=Yii::app()->functions->GetCuisineByName($category)){
			$cuisine_id=$cat_res['cuisine_id'];
		 } else $cuisine_id="-1";
		 $filter_cuisine[]=$cuisine_id;*/
		
		$cuisine_id=isset($_GET['category'])?$_GET['category']:'';
		 
		 if (!isset($_GET['filter_cuisine'])){
		 	$_GET['filter_cuisine']='';
		 }
		 
		$_GET['filter_cuisine']=$_GET['filter_cuisine'].",$cuisine_id";
		 			 
	    $res=FunctionsV3::searchByMerchant(
		   'kr_search_category',
		   isset($_GET['st'])?$_GET['st']:'',
		   isset($_GET['page'])?$_GET['page']:0,
		   FunctionsV3::getPerPage(),
		   $_GET			  
		);
		
		$country_list=Yii::app()->functions->CountryList();
		$country=getOptionA('merchant_default_country');  
		if (array_key_exists($country,(array)$country_list)){
			$country_name = $country_list[$country];
		} else $country_name="United states";
				
		if ($lat_res=Yii::app()->functions->geodecodeAddress($country_name)){    		
    		$lat_res=array(
    		  'lat'=>$lat_res['lat'],
    		  'lng'=>$lat_res['long'],
    		);
    	} else {
    		$lat_res=array();
    	} 
    	
    	$cs = Yii::app()->getClientScript();
    	$cs->registerScript(
		  'country_coordinates',
		  'var country_coordinates = '.json_encode($lat_res).'
		  ',
		  CClientScript::POS_HEAD
		);
		
		$this->render('merchant-list-cuisine',array(
		  'list'=>$res,
		  'category'=>$category
		));
	}
	
	public function actionSignup()
	{
		$cs = Yii::app()->getClientScript();
		$baseUrl = Yii::app()->baseUrl; 
		$cs->registerScriptFile($baseUrl."/assets/js/fblogin.js?ver=1"); 
		    
		if (Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->createUrl('/store')); 
			die();
		}
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}	
		
		$fb=1;
		$fb_app_id=getOptionA('fb_app_id');
		$fb_flag=getOptionA('fb_flag');
		
		if ( $fb_flag=="" && $fb_app_id<>""){
			$fb=2;
		}
		
		$this->render('signup',array(
		   'terms_customer'=>getOptionA('website_terms_customer'),
		   'terms_customer_url'=>Yii::app()->functions->prettyLink(getOptionA('website_terms_customer_url')),
		   'fb_flag'=>$fb,
		   'google_login_enabled'=>getOptionA('google_login_enabled'),
		   'captcha_customer_login'=>getOptionA('captcha_customer_login'),
		   'captcha_customer_signup'=>getOptionA('captcha_customer_signup')
		));
	}
	
	public function actionSignin()
	{
		$this->render('index');
	}
	
	public function actionMerchantSignup()
	{		
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('resto_signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_merchantsignup');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_merchantsignup_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_merchantsignup_keywords');
		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		if(isset($_GET['package_id'])){
			$_GET['id']=$_GET['package_id'];
		}	
		
		if (isset($_GET['Do'])){
			$_GET['do']=$_GET['Do'];
		}	
		
		if (isset($_GET['do'])){
			switch ($_GET['do']) {
				case 'step2':
					$this->render('merchant-signup-step2',array(
					  'data'=>Yii::app()->functions->getPackagesById($_GET['package_id']),
					  'limit_post'=>Yii::app()->functions->ListlimitedPost(),
					  'terms_merchant'=>getOptionA('website_terms_merchant'),
					  'terms_merchant_url'=>getOptionA('website_terms_merchant_url'),
					  'package_list'=>Yii::app()->functions->getPackagesList(),
					  'kapcha_enabled'=>getOptionA('captcha_merchant_signup')
					));		
					break;
				case "step3":
					 $renew=false;
					 $package_id=isset($_GET['package_id'])?$_GET['package_id']:'';  
					 if (isset($_GET['action'])){	 
						 $renew=true;
					 } 
					 $this->render('merchant-signup-step3',array(
					    'merchant'=>Yii::app()->functions->getMerchantByToken($_GET['token']),
					    'package_id'=>$package_id,
					    'renew'=>$renew					    
					 ));		
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
				     	$this->render('merchant-signup-thankyou2',array(
				     	  'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
				     	));		
				     } else {			
				     					     	
				     	 $continue=true;
						 if ($merchant=Yii::app()->functions->getMerchantByToken($_GET['token'])){	
							if ( $merchant['package_price']>=1){
								if ($merchant['payment_steps']!=3){
									$continue=false;
								}
							}
						 } else $continue=false;
						 						 						
				     	 /*check if payment was offline*/
				     	 $is_offline_paid=false;
			      	 	 if ( $package_info=FunctionsV3::getMerchantPaymentMembership($merchant['merchant_id'],
			      	 	 $merchant['package_id'])){						      	 	 	  	 	
			      	 		$offline_payment=FunctionsV3::getOfflinePaymentList();			      	 		
			      	 		if ( array_key_exists($package_info['payment_type'],(array)$offline_payment)){
			      	 			$is_offline_paid=true;
			      	 		}
			      	 	 }			

			      	 	 if ($is_offline_paid){
			      	 	 	$this->render('merchant-signup-thankyou2',array(
				     	       'data'=>$merchant
				     	    ));		
			      	 	 } else {				   			      	 	   		     						 
					     	 $this->render('merchant-signup-step4',array(
					            'continue'=>$continue
					         ));						
			      	 	 }	 
				     }
					break;	
					
				case "thankyou1":
					 $this->render('merchant-signup-thankyou1',array(
					   'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
					 ));		
					break;		
				case "thankyou2":
					$this->render('merchant-signup-thankyou2',array(
					  'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
					));		
					break;		
				case "thankyou3":
					$this->render('merchant-signup-thankyou3',array(
					  'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
					));		
					break;			
				default:
					$this->render('merchant-signup',array(
					    'list'=>Yii::app()->functions->getPackagesList(),
		               'limited_post'=>Yii::app()->functions->ListlimitedPost()
					));		
					break;
			}
		} else $this->render('merchant-signup',array(
		   'list'=>Yii::app()->functions->getPackagesList(),
		   'limited_post'=>Yii::app()->functions->ListlimitedPost()
		));		
	}
	
	public function actionAbout()
	{
		$this->render('index');
	}
	
	public function actionContact()
	{
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('contact',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}	
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_contact');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_contact_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_contact_keywords');
		
		if (!empty($seo_title)){
			$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		    $this->pageTitle=$seo_title;
		    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$website_map_location=array(
		  'map_latitude'=>getOptionA('map_latitude'),
		  'map_longitude'=>getOptionA('map_longitude'),
		);
				
		$address=getOptionA('website_address');		
		
		if (empty($website_map_location['map_latitude'])){		
			if ($lat_res=Yii::app()->functions->geodecodeAddress($address)){				
				$website_map_location['map_latitude']=$lat_res['lat'];
				$website_map_location['map_longitude']=$lat_res['long'];
	    	} 
		}
						
		$cs = Yii::app()->getClientScript();
		$cs->registerScript(
		  'website_location',
		  'var website_location = '.json_encode($website_map_location).'
		  ',
		  CClientScript::POS_HEAD
		);
			
		$this->render('contact',array(
		  'address'=>$address,
		  'website_title'=>getOptionA('website_title'),
		  'contact_phone'=>getOptionA('website_contact_phone'),
		  'contact_email'=>getOptionA('website_contact_email'),
		  'contact_content'=>getOptionA('contact_content'),
		  'country'=>Yii::app()->functions->adminCountry()		  
		));
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
		
		$_SESSION['search_type']='';
		if (isset($_GET['s'])){
			$_SESSION['kr_search_address']=$_GET['s'];
			$_SESSION['search_type']='kr_search_address';
			Cookie::setCookie('kr_search_address',$_GET['s']);
		}
		
		if (isset($_GET['foodname'])){
			$_SESSION['kr_search_foodname']=$_GET['foodname'];
			$_SESSION['search_type']='kr_search_foodname';
		}
		
		if (isset($_GET['category'])){
			$_SESSION['kr_search_category']=$_GET['category'];
			$_SESSION['search_type']='kr_search_category';
		}
		
		if (isset($_GET['restaurant-name'])){
			$_SESSION['kr_search_restaurantname']=$_GET['restaurant-name'];
			$_SESSION['search_type']='kr_search_restaurantname';
		}
		
		if (isset($_GET['street-name'])){
			$_SESSION['kr_search_streetname']=$_GET['street-name'];
			$_SESSION['search_type']='kr_search_streetname';
		}
		
		if (isset($_GET['zipcode'])){
			$_SESSION['search_type']='kr_postcode';
			$_SESSION['kr_postcode']=isset($_GET['zipcode'])?$_GET['zipcode']:'';
		}
		
		unset($_SESSION['kr_item']);
		unset($_SESSION['kr_merchant_id']);
		
		$filter_cuisine=isset($_GET['filter_cuisine'])?explode(",",$_GET['filter_cuisine']):false;
		$filter_delivery_type=isset($_GET['filter_delivery_type'])?$_GET['filter_delivery_type']:'';		
		$filter_minimum=isset($_GET['filter_minimum'])?$_GET['filter_minimum']:'';
		$sort_filter=isset($_GET['sort_filter'])?$_GET['sort_filter']:'';		
		$display_type=isset($_GET['display_type'])?$_GET['display_type']:'';
		$restaurant_name=isset($_GET['restaurant_name'])?$_GET['restaurant_name']:'';
						
		$current_page_get=$_GET;
		unset($current_page_get['page']);				
		$current_page_link=Yii::app()->createUrl('store/searcharea/',$current_page_get);
		$current_page_url='';
				
		
		/*update merchant if expired and sponsored*/
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();
		
		/*  switch between search type */		
		switch ($_SESSION['search_type']) {
			case "kr_search_address":
				if (isset($_GET['s'])){
					$res=FunctionsV3::searchByAddress(
					  isset($_GET['s'])?$_GET['s']:'' ,
					  isset($_GET['page'])?$_GET['page']:0,
					  FunctionsV3::getPerPage(),
					  $_GET			  
					);
				}		
				$current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  's'=>isset($_GET['s'])?$_GET['s']:''
				));										
				break;
						
			case "kr_search_restaurantname":				
				 $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );					
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'restaurant-name'=>isset($_GET['restaurant-name'])?$_GET['restaurant-name']:''
				));													 
				 break;
				 
			case "kr_search_streetname":	 
			      $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			

				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'street-name'=>isset($_GET['street-name'])?$_GET['street-name']:''
				));													 
							 
			     break;
			     
			case "kr_search_category":    
			
				 if ( $cat_res=Yii::app()->functions->GetCuisineByName( isset($getdata['category'])?$getdata['category']:'' )){
					$cuisine_id=$cat_res['cuisine_id'];
				 } else $cuisine_id="-1";
				 $filter_cuisine[]=$cuisine_id;				 
				 
				 if (!isset($_GET['filter_cuisine'])){
				 	$_GET['filter_cuisine']='';
				 }
				 
				 $_GET['filter_cuisine']=$_GET['filter_cuisine'].",$cuisine_id";
				 				
			     $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			

				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'category'=>isset($_GET['category'])?$_GET['category']:''
				));													 			 
			     break;
				
			case "kr_search_foodname":
				
				$res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'foodname'=>isset($_GET['foodname'])?$_GET['foodname']:''
				));													 			 					 
			     break;
				
			case "kr_postcode":     
			    $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'zipcode'=>isset($_GET['zipcode'])?$_GET['zipcode']:''
				));		
			    break;
			    
			default:
				break;
		}
										
		if (empty($display_type)){
			if ( !empty($_SESSION['krms_display_type']) ){				
				$display_type=$_SESSION['krms_display_type'];
			} else {		
				$display_type=getOptionA('theme_list_style');
				if (empty($display_type)){
				    $display_type='gridview';	
				}
			}
		}
		
		$_SESSION['krms_display_type']=$display_type;	
								
		if (is_array($res) && count($res)>=1){			
						
			$_SESSION['client_location']=$res['client'];						
			Cookie::setCookie('client_location', json_encode($res['client']) );
			
			$this->render('search-results',array(
			  'data'=>$res,
			  'filter_delivery_type'=>$filter_delivery_type,
			  'filter_cuisine'=>$filter_cuisine,
			  'filter_minimum'=>$filter_minimum,
			  'sort_filter'=>$sort_filter,
			  'display_type'=>$display_type,
			  'restaurant_name'=>$restaurant_name,
			  'current_page_link'=>$current_page_link,
			  'current_page_url'=>$current_page_url,
			  'fc'=>getOptionA('theme_filter_colapse'),
			  'enabled_search_map'=>getOptionA('enabled_search_map')
			));
			$_SESSION['kmrs_search_stmt']=$res['sql'];			
		} else {
			$has_filter=false;
			if (isset($_GET['filter_minimum'])){$has_filter=true;}		
			if (isset($_GET['filter_delivery_type'])){$has_filter=true;}		
			if (isset($_GET['filter_cuisine'])){$has_filter=true;}
			if ($has_filter){
				$this->render('search-results',array(
				  'data'=>$res,
				  'filter_delivery_type'=>$filter_delivery_type,
				  'filter_cuisine'=>$filter_cuisine,
				  'filter_minimum'=>$filter_minimum,
				  'sort_filter'=>$sort_filter,
				  'display_type'=>$display_type,
				  'restaurant_name'=>$restaurant_name,
				  'current_page_url'=>isset($current_page_url)?$current_page_url:'',
				  'fc'=>getOptionA('theme_filter_colapse'),
				  'enabled_search_map'=>getOptionA('enabled_search_map'),
			   ));
			} else $this->render('search-results-nodata');							
		}	
	}
	
	public function actionMenu()
	{		
				
		$data=$_GET;		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}

		$res=FunctionsV3::getMerchantBySlug($data['merchant']);
		
		if (is_array($res) && count($res)>=1){
			if ( $current_merchant !=$res['merchant_id']){							 
				 unset($_SESSION['kr_item']);
			}		
			
			if ( $res['status']=="active"){
								
				/*SEO*/
				$seo_title=Yii::app()->functions->getOptionAdmin('seo_menu');
				$seo_meta=Yii::app()->functions->getOptionAdmin('seo_menu_meta');
				$seo_key=Yii::app()->functions->getOptionAdmin('seo_menu_keywords');
				
				if (!empty($seo_title)){
					$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
					$seo_title=smarty('merchant_name',ucwords($res['restaurant_name']),$seo_title);		    
				    $this->pageTitle=$seo_title;
				    
				    $seo_meta=smarty('merchant_name',ucwords($res['restaurant_name']),$seo_meta);
				    $seo_key=smarty('merchant_name',ucwords($res['restaurant_name']),$seo_key);		    
				    
				    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
				}
				/*END SEO*/
				
				unset($_SESSION['guest_client_id']);
				
				$merchant_id=$res['merchant_id'];				
				
				/*SET TIME*/
				$mt_timezone=Yii::app()->functions->getOption("merchant_timezone",$merchant_id);				
		    	if (!empty($mt_timezone)){       	 	
		    		Yii::app()->timeZone=$mt_timezone;
		    	}		   		
		    	
		    	
		    	$distance_type='';
		    	$distance='';
		    	$merchant_delivery_distance='';
		    	$delivery_fee=0;
		    			    			    	
		    	/*double check if session has value else use cookie*/		    	
		    	FunctionsV3::cookieLocation();
					    		    	
		    	if (isset($_SESSION['client_location'])){
		    		
		    		/*get the distance from client address to merchant Address*/             
	                 $distance_type=FunctionsV3::getMerchantDistanceType($merchant_id); 
	                 $distance_type_orig=$distance_type;
	                 
		             $distance=FunctionsV3::getDistanceBetweenPlot(
		                $_SESSION['client_location']['lat'],
		                $_SESSION['client_location']['long'],
		                $res['latitude'],$res['lontitude'],$distance_type
		             );           
		             		            		 
		             $distance_type_raw = $distance_type=="M"?"miles":"kilometers";            		            
		             $distance_type=$distance_type=="M"?t("miles"):t("kilometers");
		             $distance_type_orig = $distance_type;
		             
		              if(!empty(FunctionsV3::$distance_type_result)){
		             	$distance_type_raw=FunctionsV3::$distance_type_result;
		             	$distance_type=t(FunctionsV3::$distance_type_result);
		             }
		             
		             $merchant_delivery_distance=getOption($merchant_id,'merchant_delivery_miles');             
		             		             
		             $delivery_fee=FunctionsV3::getMerchantDeliveryFee(
		                          $merchant_id,
		                          $res['delivery_charges'],
		                          $distance,
		                          $distance_type_raw);
		    		
		    	}			
		    			    		
		    	
		    	/*SESSION REF*/
		    	$_SESSION['kr_merchant_id']=$merchant_id;
                $_SESSION['kr_merchant_slug']=$data['merchant'];
		    	$_SESSION['shipping_fee']=$delivery_fee;		
		    			    	
		    	/*CHECK IF BOOKING IS ENABLED*/
		    	$booking_enabled=true;		    		
		    	if (getOption($merchant_id,'merchant_table_booking')=="yes"){
		    		$booking_enabled=false;
		    	}			
		    	if ( getOptionA('merchant_tbl_book_disabled')){
		    		$booking_enabled=false;
		    	}
		    	
		    	/*CHECK IF MERCHANT HAS PROMO*/
		    	$promo['enabled']=1;
		    	if($offer=FunctionsV3::getOffersByMerchant($merchant_id,2)){		    	   
		    	   $promo['offer']=$offer;
		    	   $promo['enabled']=2;
		    	}		    			
		    	if ( $voucher=FunctionsV3::merchantActiveVoucher($merchant_id)){		    
		    		$promo['voucher']=$voucher;
		    		$promo['enabled']=2;
		    	}
		    	$free_delivery_above_price=getOption($merchant_id,'free_delivery_above_price');
		    	if ($free_delivery_above_price>0){
		    	    $promo['free_delivery']=$free_delivery_above_price;
		    		$promo['enabled']=2;
		    	}
		    	
		    	$photo_enabled=getOption($merchant_id,'gallery_disabled')=="yes"?false:true;
		    	if ( getOptionA('theme_photos_tab')==2){
		    		$photo_enabled=false;
		    	}
						    
				$this->render('menu' ,array(
				   'data'=>$res,
				   'merchant_id'=>$merchant_id,
				   'distance_type'=>$distance_type,
				   'distance_type_orig'=>$distance_type_orig,
				   'distance'=>$distance,
				   'merchant_delivery_distance'=>$merchant_delivery_distance,
				   'delivery_fee'=>$delivery_fee,
				   'disabled_addcart'=>getOption($merchant_id,'merchant_disabled_ordering'),
				   'merchant_website'=>getOption($merchant_id,'merchant_extenal'),
				   'photo_enabled'=>$photo_enabled,
				   'booking_enabled'=>$booking_enabled,
				   'promo'=>$promo,
				   'tc'=>getOptionA('theme_menu_colapse'),
				   'theme_promo_tab'=>getOptionA('theme_promo_tab'),
				   'theme_hours_tab'=>getOptionA('theme_hours_tab'),
				   'theme_reviews_tab'=>getOptionA('theme_reviews_tab'),
				   'theme_map_tab'=>getOptionA('theme_map_tab'),
				   'theme_info_tab'=>getOptionA('theme_info_tab'),
				   'theme_photos_tab'=>getOptionA('theme_photos_tab')
				));	
								
			}  else  $this->render('error',array(
		       'message'=>t("Sorry but this merchant is no longer available")
		    ));
			
		} else $this->render('error',array(
		  'message'=>t("merchant is not available")
		));
	}
	
	public function actionCheckout()
	{
		if ( Yii::app()->functions->isClientLogin()){	       
 	       $this->redirect(Yii::app()->createUrl('/store/PaymentOption')); 
 	       die();
        }
        
        $cs = Yii::app()->getClientScript();
		$baseUrl = Yii::app()->baseUrl; 
		$cs->registerScriptFile($baseUrl."/assets/js/fblogin.js?ver=1"); 
		    
		if (Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->createUrl('/store')); 
			die();
		}

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
		
		$fb=1;
		$fb_app_id=getOptionA('fb_app_id');
		$fb_flag=getOptionA('fb_flag');
		
		if ( $fb_flag=="" && $fb_app_id<>""){
			$fb=2;
		}
		
		$this->render('checkout',array(
		   'terms_customer'=>getOptionA('website_terms_customer'),
		   'terms_customer_url'=>Yii::app()->functions->prettyLink(getOptionA('website_terms_customer_url')),
		   'disabled_guest_checkout'=>getOptionA('website_disabled_guest_checkout'),
		   'enabled_mobile_verification'=>getOptionA('website_enabled_mobile_verification'),
		   'fb_flag'=>$fb,
		   'google_login_enabled'=>getOptionA('google_login_enabled'),
		   'captcha_customer_login'=>getOptionA('captcha_customer_login'),
		   'captcha_customer_signup'=>getOptionA('captcha_customer_signup')
		));
	}
	
	public function actionPaymentOption()
	{	
		
		/*POINTS PROGRAM*/
		if (Yii::app()->hasModule('pointsprogram')){
		   PointsProgram::includeFrontEndFiles();	   
		} 
		
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
		$this->render('payment-option',array(
		  'website_enabled_map_address'=>getOptionA('website_enabled_map_address'),
		  'address_book'=>Yii::app()->functions->showAddressBook()
		));
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
		if (Yii::app()->functions->isClientLogin()){		   
		   $this->render('profile',array(
		     'tabs'=>isset($_GET['tab'])?$_GET['tab']:'',
		     'disabled_cc'=>getOptionA('disabled_cc_management'),
		     'info'=>Yii::app()->functions->getClientInfo( Yii::app()->functions->getClientId()),
		     'avatar'=>FunctionsV3::getAvatar( Yii::app()->functions->getClientId() )
		   ));
		} else $this->render('404-page',array(
		   'header'=>true
		));
	}
	
	/*public function actionCards()
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
	}*/
	
	public function actionhowItWork()
	{
		$this->render('dynamic-page');
	}
	
	public function actionforgotPassword()
	{
		if ($res=Yii::app()->functions->getLostPassToken($_GET['token']) ){
			$this->render('forgot-pass');
		} else $this->render('error',array('message'=>t("ERROR: Invalid token.")));
	}
	
	public function actionPage()
	{
		$_GET=array_flip($_GET);   
        $slug=$_GET[''];
        if ($data=yii::app()->functions->getCustomPageBySlug($slug)){
        	
            /*SET SEO META*/
			if (!empty($data['seo_title'])){
			     $this->pageTitle=ucwords($data['seo_title']);
			     Yii::app()->clientScript->registerMetaTag($data['seo_title'], 'title'); 
			}
			if (!empty($data['meta_description'])){   
			     Yii::app()->clientScript->registerMetaTag($data['meta_description'], 'description'); 
			}
			if (!empty($data['meta_keywords'])){   
			     Yii::app()->clientScript->registerMetaTag($data['meta_keywords'], 'keywords'); 
			}
        	
        	$this->render('custom-page',array(
        	  'data'=>$data
        	));
        } else {
        	$this->render('404-page',array('header'=>true));
        }
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
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('browse',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}
		
        /*update merchant if expired and sponsored*/
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();
		
		if (!isset($_GET['tab'])){
			$_GET['tab']='';
		}
		switch ($_GET['tab']){			
			case 2:
			  $tabs=2;
		      $list=Yii::app()->functions->getAllMerchantNewest();		
		      break;
		      
		    case 3:
			  $tabs=3;
			  $list=Yii::app()->functions->getFeaturedMerchant();	      
		      break;  
		    
		    case "4":
		       break;  
		    	  
			default:
			  $tabs=1;
			  $list=Yii::app()->functions->getAllMerchant();		
			  break;
		}

		$country_list=Yii::app()->functions->CountryList();
		$country=getOptionA('merchant_default_country');  
		if (array_key_exists($country,(array)$country_list)){
			$country_name = $country_list[$country];
		} else $country_name="United states";
						
    	if ($lat_res=Yii::app()->functions->geodecodeAddress($country_name)){    		
    		$lat_res=array(
    		  'lat'=>$lat_res['lat'],
    		  'lng'=>$lat_res['long'],
    		);
    	} else {
    		$lat_res=array();
    	} 
    	
    	$cs = Yii::app()->getClientScript();
    	$cs->registerScript(
		  'country_coordinates',
		  'var country_coordinates = '.json_encode($lat_res).'
		  ',
		  CClientScript::POS_HEAD
		);
					
		$this->render('browse-resto',array(
		  'list'=>$list,
		  'tabs'=>$tabs
		));
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
				$datas[]=array(				  				
				  'name'=>$val['restaurant_name']
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
		GROUP BY street		
		ORDER BY restaurant_name ASC		
		LIMIT 0,20
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$datas[]=array(				  				
				  'name'=>$val['street']
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
				$datas[]=array(				  				
				  'name'=>$val['cuisine_name']
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
				$datas[]=array(				  				
				  'name'=>$val['item_name']
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
                   header('Location: '.Yii::app()->request->baseUrl."/store/merchantsignup/Do/step4/token/$my_token"); 
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
								
								header('Location: '.websiteUrl()."/store/merchantsignup/Do/thankyou2/token/".$data['token']); 
						    } else $msg=t("Token not found");	
					     }
						break;
						
					case "cancel":	
					    if ( $trans_info['transaction_type']=="order"){
					    	header('Location: '.websiteUrl()."/store/"); 
					    } else {
					        header('Location: '.websiteUrl()."/store/merchantsignup/Do/step3/token/".$data['token']); 
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
		if (Yii::app()->hasModule('pointsprogram')){
		   PointsProgram::includeFrontEndFiles();	
		}    
		
		$this->render('payment-option',
		  array(
		     'is_guest_checkout'=>true,
		     'website_enabled_map_address'=>getOptionA('website_enabled_map_address'),
		     'address_book'=>Yii::app()->functions->showAddressBook()
		));
	}
	
	public function actionMerchantSignupSelection()
	{
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('resto_signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}	

		if ( Yii::app()->functions->getOptionAdmin("merchant_disabled_registration")=="yes"){
			//$this->render('error',array('message'=>t("Sorry but merchant registration is disabled by admin")));
			$this->render('404-page',array('header'=>true));
		} else $this->render('merchant-signup-selection',array(
		  'percent'=>getOptionA('admin_commision_percent'),
		  'commision_type'=>getOptionA('admin_commision_type'),
		  'currency'=>adminCurrencySymbol()
		));		
	}
	
	public function actionMerchantSignupinfo()
	{
		$this->render('merchant-signup-info',array(
		  'terms_merchant'=>getOptionA('website_terms_merchant'),
		  'terms_merchant_url'=>getOptionA('website_terms_merchant_url'),
		  'kapcha_enabled'=>getOptionA('captcha_merchant_signup')
		));
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
		/** Register all scripts here*/
		ScriptManager::RegisterAllJSFile();
		ScriptManager::registerAllCSSFiles();
		$this->render('404-page',array(
		  'header'=>true
		));
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
				  'name'=>$full
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
		
	public function actionItem()
	{
		$data=Yii::app()->functions->getItemById($_GET['item_id']);
		$this->layout='mobile_tpl';
		$this->render('item',array(
		   'title'=>"test title",
		   'data'=>$data,
		   'this_data'=>isset($_GET)?$_GET:''
		));
	}
	
	public function actionTy()
	{
		$this->render('ty',array(
		  'verify'=>isset($_GET['verify'])?true:false
		));
	}	
	
	public function actionEmailVerification()
	{
		
		if ( Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->request->baseUrl."/store/home");
		    Yii::app()->end();
		}
		
		$continue=true; $msg='';
		
		if(!isset($_GET['id'])){
			$_GET['id']='';
		}
		if( $res=Yii::app()->functions->getClientInfo($_GET['id'])){	
			if ( $res['status']=="active"){
				$continue=false;
				$msg=t("Your account is already verified");
			}
		} else {
			$continue=false;
			$msg=t("Sorry but we cannot find what you are looking for.");
		}
		
		if ($continue){
		   $this->render('email-verification',array(
		     'data'=>$res
		   ));
		} else $this->render('error',array('message'=>$msg));
	}
	
	public function actionMyPoints()
	{		
		/*POINTS PROGRAM*/
		PointsProgram::includeFrontEndFiles();

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
	