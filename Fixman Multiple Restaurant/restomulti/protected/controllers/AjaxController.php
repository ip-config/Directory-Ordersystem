<?php
if (!isset($_SESSION)) { session_start(); }

class AjaxController extends CController
{
	public $layout='_store';	
	public $code=2;
	public $msg;
	public $details;
	public $data;
	
	public function __construct()
	{
		$this->data=$_POST;	
		if (isset($_GET['post_type'])){
			if ($_GET['post_type']=="get"){
				$this->data=$_GET;	
			}
		}
	}
	
	private function jsonResponse()
	{
		$resp=array('code'=>$this->code,'msg'=>$this->msg,'details'=>$this->details);
		echo CJSON::encode($resp);
		Yii::app()->end();
	}
	
	private function otableNodata()
	{
		if (isset($_GET['sEcho'])){
			$feed_data['sEcho']=$_GET['sEcho'];
		} else $feed_data['sEcho']=1;	   
		     
        $feed_data['iTotalRecords']=0;
        $feed_data['iTotalDisplayRecords']=0;
        $feed_data['aaData']=array();		
        echo json_encode($feed_data);
    	die();
	}

	private function otableOutput($feed_data='')
	{
	  echo json_encode($feed_data);
	  die();
    }    
    
    public function actionLoadAllRestoMap()
    {
    	$data='';
    	$stmt=$_SESSION['kmrs_search_stmt'];
    	if (!empty($stmt)){
    		$pos=strpos($stmt,'LIMIT');    		
    		$stmt=substr($stmt,0,$pos-1);       		
    		$DbExt=new DbExt();
    		$DbExt->qry("SET SQL_BIG_SELECTS=1");
    		if ( $res=$DbExt->rst($stmt)){
    			foreach ($res as $val) {    
    				if (!empty($val['latitude']) && !empty($val['lontitude'])){
	    				$data[]=array(
	    				  'restaurant_name'=>$val['restaurant_name'],
	    				  'restaurant_slug'=>$val['restaurant_slug'],
	    				  'merchant_address'=>$val['merchant_address'],
	    				  'latitude'=>$val['latitude'],
	    				  'lontitude'=>$val['lontitude'],
	    				  'logo'=>FunctionsV3::getMerchantLogo($val['merchant_id']),
	    				  'link'=>Yii::app()->createUrl('store/menu/merchant/'.$val['restaurant_slug'])
	    				);
    				}
    			}    			
    			$this->code=1;
    			$this->msg="OK";
    			$this->details=$data;
    		} else $this->msg=t("no records");
    	} else $this->msg=t("invalid statement query");
    	$this->jsonResponse();
    }
    
    public function actionloadAllMerchantMap()
    {    	
    	$datas='';
    	if ( $data=Yii::app()->functions->getAllMerchant(true)){
    		foreach ($data['list'] as $val) {
    			if (!empty($val['latitude']) && !empty($val['lontitude'])){
    				$datas[]=array(
    				  'restaurant_name'=>$val['restaurant_name'],
    				  'restaurant_slug'=>$val['restaurant_slug'],
    				  'merchant_address'=>$val['merchant_address'],
    				  'latitude'=>$val['latitude'],
    				  'lontitude'=>$val['lontitude'],
    				  'logo'=>FunctionsV3::getMerchantLogo($val['merchant_id']),
    				  'link'=>Yii::app()->createUrl('store/menu/merchant/'.$val['restaurant_slug'])
    				);
				}
    		}
    		$this->code=1;
			$this->msg="OK";
			$this->details=$datas;
    	} else $this->msg=t("no records");
    	$this->jsonResponse();
    }
    
    public function actionClientCCList()
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
		{{client_cc}}		
		WHERE
		client_id ='".Yii::app()->functions->getClientId()."'	
		ORDER BY cc_id DESC
		";						
		if ($res=$DbExt->rst($stmt)){		
		   foreach ($res as $val) {	
		   	    $edit_url=Yii::app()->createUrl('/store/profile/?tab=4&do=add&id='.$val['cc_id']);
				$action="<div class=\"options\">
	    		<a href=\"$edit_url\" ><i class=\"ion-ios-compose-outline\"></i></a>
	    		<a href=\"javascript:;\" data-table=\"client_cc\" data-whereid=\"cc_id\" class=\"row_remove\" data-id=\"$val[cc_id]\" ><i class=\"ion-ios-trash\"></i></a>
	    		</div>";		   	   
		   	   $feed_data['aaData'][]=array(
		   	      $val['card_name'].$action,
		   	      Yii::app()->functions->maskCardnumber($val['credit_card_number']),
		   	      $val['expiration_month']."-".$val['expiration_yr']
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();			
    }
    
    public function actionUpdateClientCC()
    {
    	if (Yii::app()->functions->isClientLogin()){
    	$client_id=Yii::app()->functions->getClientId();    	    	
	    	$params=array(
	    	  'client_id'=>$client_id,
	    	  'card_name'=>$this->data['card_name'],
	    	  'credit_card_number'=>$this->data['credit_card_number'],
	    	  'expiration_month'=>$this->data['expiration_month'],
	    	  'expiration_yr'=>$this->data['expiration_yr'],
	    	  'billing_address'=>$this->data['billing_address'],
	    	  'cvv'=>$this->data['cvv'],
	    	  'date_created'=>date('c'),
	    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
	    	);
	    	$DbExt=new DbExt;
	    	if (isset($this->data['cc_id'])){
	    		unset($params['date_created']);
	    		$params['date_modified']=date('c');	    		
	    		
	    		$stmt="SELECT * FROM
	    		{{client_cc}}
	    		WHERE
	    		client_id=".FunctionsV3::q($client_id)."
	    		AND
	    		cc_id<>".FunctionsV3::q($this->data['cc_id'])."
	    		AND credit_card_number=".FunctionsV3::q($this->data['credit_card_number'])."
	    		
	    		LIMIT 0,1
	    		";	    		
	    		if ($DbExt->rst($stmt)){
	    			$this->msg=t("Credit card number already exist in you credit card list");
	    			$this->jsonResponse();
	    			return ;
	    		}
	    			    		
	    		if ( $DbExt->updateData("{{client_cc}}",$params,'cc_id',$this->data['cc_id'])){
	    			$this->code=1;
	    			$this->msg=t("Card successfully updated.");
	    		} else $this->msg=t("Error cannot saved information");
	    	} else {
	    		if (!Yii::app()->functions->getCCbyCard($this->data['credit_card_number'],$client_id) ){
		    		if ( $DbExt->insertData("{{client_cc}}",$params)){
		    			$cc_id=Yii::app()->db->getLastInsertID();	    			
		    			$redirect=Yii::app()->createUrl('/store/profile/?tab=4&do=add&id='.$cc_id);
		    			
		    			$this->code=1;
		    			$this->msg=t("Card successfully added");
		    			$this->details=array('redirect'=>$redirect);
		    		} else $this->msg=t("Error cannot saved information");
	    		} else $this->msg=t("Credit card number already exist in you credit card list");
	    	}
    	} else $this->msg=t("ERROR: Your session has expired.");
    	$this->jsonResponse();
    }
    
    public function actionsaveAvatar()
    {    	
    	$DbExt=new DbExt;
    	if (!empty($this->data['filename'])){
    		$params=array(
    		  'avatar'=>$this->data['filename'],
    		  'date_modified'=>date(''),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$client_id=Yii::app()->functions->getClientId();    		
    		if (is_numeric($client_id)){
    			$DbExt->updateData("{{client}}",$params,'client_id',$client_id);
    			$this->msg=t("You have succesfully change your profile picture");
    			$this->code=1;
    		} else $this->msg=t("ERROR: Your session has expired.");
    	} else $this->msg=t("Filename is empty");
    	$this->jsonResponse();
    }
    
    public function actionViewReceipt()
    {
    	/** Register all scripts here*/
    	ScriptManager::registerAllCSSFiles();
		$this->render('/store/receipt-front',array(
		  'data'=>Yii::app()->functions->getOrder2( isset($this->data['order_id'])?$this->data['order_id']:'' )
		));
    }
    
    public function actionResendEmailCode()
    {
    	$client_id=isset($this->data['client_id'])?$this->data['client_id']:'';
    	if( $res=Yii::app()->functions->getClientInfo( $client_id )){	
    		FunctionsV3::sendEmailVerificationCode($res['email_address'],$res['email_verification_code'],$res);
    		$this->code=1;
    		$this->msg=t("We have sent verification code to your email address");
    	} else $this->msg=t("Sorry but we cannot find your information.");
    	$this->jsonResponse();
    }
    	
} /*end class*/    