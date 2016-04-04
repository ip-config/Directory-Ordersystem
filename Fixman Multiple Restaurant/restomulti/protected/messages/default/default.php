<?php
$lang_id='';

switch (Yii::app()->controller->id) {
	case "store":		
	    $lang_id=empty($_COOKIE['kr_lang_id'])?"":$_COOKIE['kr_lang_id'];
		break;

	case "admin":	
	  
	   $lang_id=empty($_COOKIE['kr_admin_lang_id'])?"":$_COOKIE['kr_admin_lang_id'];	   
	   if (empty($lang_id)){	   	  
	   	  $lang_id=Yii::app()->functions->getAdminLanguage();
	   }
	   
	   /*default language set for admin*/
	   if (empty($lang_id)){
	   	   $default_lang_id=getOptionA('default_language_backend');
	   	   if (is_numeric($default_lang_id) && !empty($default_lang_id)){
	   	   	   Yii::app()->request->cookies['kr_admin_lang_id'] = new CHttpCookie('kr_admin_lang_id', $default_lang_id);
	   	   	   $lang_id=$default_lang_id;
	   	   }
	   }	   
	   
	   if (isset($_REQUEST['currentController'])){
	   	   if ($_REQUEST['currentController']=="merchant"){
	   	   	   $lang_id=empty($_COOKIE['kr_merchant_lang_id'])?"":$_COOKIE['kr_merchant_lang_id'];	   	    	   
	   	   //} else $lang_id=empty($_COOKIE['kr_lang_id'])?"":$_COOKIE['kr_lang_id'];	   	   
	   	   } else {
	   	   	   if ( $_REQUEST['currentController']=="store"){
	   	   	   	   $lang_id=empty($_COOKIE['kr_lang_id'])?"":$_COOKIE['kr_lang_id'];	   	   
	   	   	   } else $lang_id=empty($_COOKIE['kr_admin_lang_id'])?"":$_COOKIE['kr_admin_lang_id'];	   	   	   	   	   
	   	   }
	   }	   	   	   
	   break;

	case "merchant":   
	    $lang_id=empty($_COOKIE['kr_merchant_lang_id'])?"":$_COOKIE['kr_merchant_lang_id'];	   	    	   
	    if (empty($lang_id)){	   	  
	   	  $lang_id=Yii::app()->functions->getMerchantLanguage();	   	  
	    }	   	  

	        
	    /*default language set for admin*/
	    if (empty($lang_id)){
	   	   $default_lang_id=getOptionA('default_language_backend');
	   	   if (is_numeric($default_lang_id) && !empty($default_lang_id)){
	   	   	   Yii::app()->request->cookies['kr_merchant_lang_id'] = new CHttpCookie('kr_merchant_lang_id', $default_lang_id);
	   	   	   $lang_id=$default_lang_id;
	   	   }
	    }		        	    
	    break;
	    
	default:
		break;
}

if (empty($lang_id)){
	$lang_id=Yii::app()->functions->getOptionAdmin('default_language');
	$_COOKIE['kr_lang_id']=$lang_id;
}

/** add translation for mobile app */
if (isset($_GET['lang_id'])){
	$lang_id=$_GET['lang_id'];
}

//echo "Languages ID:".$lang_id;
$language_pack=Yii::app()->functions->getSourceTranslationFile($lang_id);
//dump($language_pack);
return $language_pack;