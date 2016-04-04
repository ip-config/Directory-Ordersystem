<?php
if (!isset($_SESSION)) { session_start(); }

class IndexController extends CController
{
	public $layout='layout';	
	
	public function beforeAction(CAction $action)
	{		
		if (Yii::app()->controller->module->require_login){
			if(!Yii::app()->functions->isAdminLogin()){
			   $this->redirect(Yii::app()->createUrl('/admin/noaccess'));
			   Yii::app()->end();		
			}
		}
		return true;
	}
	
	public function actionIndex(){
		$this->redirect(Yii::app()->createUrl('/mobileapp/index/settings'));
	}		
	
	public function actionSettings()
	{
				
		$country_list=require_once('CountryCode.php');
		$mobile_country_list=getOptionA('mobile_country_list');
		if (!empty($mobile_country_list)){
			$mobile_country_list=json_decode($mobile_country_list);
		} else $mobile_country_list=array();
				
		$default_image=AddonMobileApp::getImage(getOptionA('mobile_default_image_not_available'));
		
		$this->render('settings',array(
		  'country_list'=>$country_list,
		  'default_image_url'=>$default_image,
		  'mobile_country_list'=>$mobile_country_list
		));
	}
	
	public function actionPushLogs()
	{		
		$this->render('pushlogs',array(		  
		));
	}
	
	public function actionregistereddevice()
	{
		$this->render('registered_device',array(		  
		));
	}
	
	public function actionPushHelp()
	{
		$this->render('pushhelp',array(		  
		));
	}
	
	public function actionPush()
	{		
		if ( $res=AddonMobileApp::getDeviceByID($_GET['id'])){
	    $this->render('push_form',array(		  
	      'data'=>$res
		));
		} else $this->render('error',array(
		  'msg'=>t("cannot find records")
		));
	}
	
	public function actionBroadcast()
	{
		$this->render('broadcast-list',array(		  
		));
	}
	
	public function actionBroadcastNew()
	{
		$this->render('broadcast-new',array(		  
		));
	}
	
	public function actionbroadcastdetails()
	{		
		if ( AddonMobileApp::getBroadcast($_GET['id'])){
			$this->render('broadcast-details',array(		  
		    ));
		} else  $this->render('error',array(
		  'msg'=>t("cannot find records")
		));
	}
	
	public function actiontranslation()
	{
		$this->render('translation',array(		  
		));
	}
	
} /*end class*/