<?php
/**
 * CronController Controller
 *
 */
if (!isset($_SESSION)) { session_start(); }

class CronController extends CController
{
	public function actionIndex()
	{
		echo "CONTROLLER INDEX";
	}
	
	public function actionProcessBroacast()
	{
	   define('LOCK_SUFFIX', '.lock');
		
		if(($pid = cronHelper::lock()) !== FALSE) {			
			echo 'cron running';
			
			$this->ProcessBroacast();
			sleep(1); // Cron job code for demonstration
	
			cronHelper::unlock();
	    } else {	    	
	    	echo "CRON LOCK";
	    }
	}
	
	public function ProcessBroacast()
	{
		$cron=new CronFunctions;
		$db_ext=new DbExt;
		$stmt="SELECT * FROM
		{{sms_broadcast}}
		WHERE
		status IN ('pending')
		LIMIT 0,1
		";
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {
				dump($val);
				if ( $val['send_to']==1 ){
					$cron->getAllCustomer($val);
				} elseif ( $val['send_to']==2){
					echo "Merchant customer";
					$cron->getAllCustomerByMerchant($val);
				} else {
					echo "custom mobile";
					$cron->customMobile($val);
				}
				$db_ext->updateData("{{sms_broadcast}}",
				  array('status'=>"process",'date_modified'=>date('c')),
				  'broadcast_id',$val['broadcast_id']);
			}
		} else echo "<p>No records to process</p>";
	}	

	public function actionProcessSMS()
	{
	   define('LOCK_SUFFIX', '.locksms');
		
		if(($pid = cronHelper::lock()) !== FALSE) {			
			echo 'cron running sms';
			
			$this->ProcessSMS();
			sleep(1); // Cron job code for demonstration
	
			cronHelper::unlock();
	    } else {	    	
	    	echo "CRON LOCK";
	    }
	}
		
	public function ProcessSMS()
	{		
		require_once "Twilio.php";
		
		$sms_sender_id=Yii::app()->functions->getOptionAdmin('sms_sender_id');
		$sms_account_id=Yii::app()->functions->getOptionAdmin('sms_account_id');
		$sms_token=Yii::app()->functions->getOptionAdmin('sms_token');
		
		$nexmo_sender_id=Yii::app()->functions->getOptionAdmin('nexmo_sender_id');
		$nexmo_key=Yii::app()->functions->getOptionAdmin('nexmo_key');
		$nexmo_secret=Yii::app()->functions->getOptionAdmin('nexmo_secret');    		
		$nexmo_use_curl=Yii::app()->functions->getOptionAdmin('nexmo_use_curl');
		
		$sms_provider=Yii::app()->functions->getOptionAdmin('sms_provider');  
		
		$clickatel_user      = Yii::app()->functions->getOptionAdmin('clickatel_user');
		$clickatel_password  = Yii::app()->functions->getOptionAdmin('clickatel_password');
		$clickatel_api_id    = Yii::app()->functions->getOptionAdmin('clickatel_api_id');
		$clickatel_use_curl  = Yii::app()->functions->getOptionAdmin('clickatel_use_curl');
		
		$nexmo_use_unicode=Yii::app()->functions->getOptionAdmin('nexmo_use_unicode');
		$clickatel_use_unicode=Yii::app()->functions->getOptionAdmin('clickatel_use_unicode');
		
		$clickatel_sender=Yii::app()->functions->getOptionAdmin('clickatel_sender');
		
		$privatesms_username=Yii::app()->functions->getOptionAdmin('privatesms_username');
        $privatesms_password=Yii::app()->functions->getOptionAdmin('privatesms_password');
        $privatesms_sender=Yii::app()->functions->getOptionAdmin('privatesms_sender');
		
		/*dump($sms_sender_id);
		dump($sms_account_id);
		dump($sms_token);*/
		
		$db_ext=new DbExt;
		$stmt="
		SELECT * FROM
		{{sms_broadcast_details}}
		WHERE
		status IN ('pending')
		ORDER BY id ASC
		LIMIT 0,50
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {
				dump($val);
				
				//$available_credit=Yii::app()->functions->getMerchantSMSCredit(Yii::app()->functions->getMerchantID());	    	
				$available_credit=Yii::app()->functions->getMerchantSMSCredit($val['merchant_id']);	    	
				
				dump($available_credit);
				if ( $available_credit>=1){		
					//if ( !empty($sms_sender_id) && !empty($sms_account_id) && !empty($sms_token)){
						$raw='';$msg='';
																		
						if ($sms_provider=="nexmo"){    																									    					    						    		   
				    		$Nexmo=new Nexmo;
				    		$Nexmo->key=$nexmo_key;
				    		$Nexmo->secret=$nexmo_secret;
				    		$Nexmo->sender=$nexmo_sender_id;    		
				    		$Nexmo->to=$val['contact_phone'];
				    		$Nexmo->message=$val['sms_message'];
				    		$Nexmo->is_curl=$nexmo_use_curl;
				    		
				    		if ( $nexmo_use_unicode==1){
	    			            $Nexmo->unicode=true;
	    		            }	    		
	    			    		            
				    		try {    			
				    			$raw=$resp=$Nexmo->sendSMS();
				    			$msg="process";
				    		} catch (Exception $e){
				    			$msg=$e->getMessage();
				    		}       	
				    		
						} elseif ( $sms_provider=="clickatell" ) {
							
							$Clickatell=new Clickatell;
			    			$Clickatell->user=$clickatel_user;
			    			$Clickatell->password=$clickatel_password;
			    			$Clickatell->api_id=$clickatel_api_id;
			    			$Clickatell->is_curl=$clickatel_use_curl;
			    			$Clickatell->to=$val['contact_phone'];
				    		$Clickatell->message=$val['sms_message'];
				    		
				    		if ( $clickatel_use_unicode==1){
	    			             $Clickatell->unicode=true;
	    		            }
	    		            $Clickatell->sender=$clickatel_sender;
	    		            	    		
				    		try {    			
				    			$raw=$resp=$Clickatell->sendSMS();
				    			$msg="process";
				    		} catch (Exception $e){
				    			$msg=$e->getMessage();
				    		}       		 			    	
							
						} elseif ( $sms_provider=="private") {
							
							$obj = new Sender("103.16.101.52",
							"80",$privatesms_username,$privatesms_password,$privatesms_sender,
							$val['sms_message'], $val['contact_phone'], "0", "1");
							
                            $resp=$obj->Submit();                                     
                            if (preg_match("/1701/i", $resp)) {
			        	        $raw=$resp;
			        	        $msg="process";
			                } else {
			                	$errors['1702']="Invalid URL Error, This means that one of the parameters was not
			provided or left blank";
					        	$errors['1703']="Invalid value in username or password field";
					        	$errors['1704']='Invalid value in "type" field';
					        	$errors['1705']="Invalid Message";
					        	$errors['1706']="Invalid Destination";
					        	$errors['1707']="Invalid Source (Sender)";
					        	$errors['1708']='Invalid value for "dlr" field';
					        	$errors['1709']="User validation failed";
					        	$errors['1710']="Internal Error";
					        	$errors['1025']="Insufficient Credit";
					        	$resp_temp=explode("|",$resp);	
					        	if (is_array($resp_temp) && count($resp_temp)>=1){
					        		$code_error=$resp_temp[0];
					        	} else $code_error=$resp;		
					        	$raw=$resp;					        		        
					        	if (array_key_exists($code_error,$errors)){
					        		$msg=$errors[$code_error];
					        	} else $msg="Undefined response from api.";	
			                }    			
			                 
						} else {
							$twilio=new Twilio;
							$twilio->_debug=false;
							$twilio->sid=$sms_account_id;
							$twilio->auth=$sms_token;
							$twilio->data['From']=$sms_sender_id;
							$twilio->data['To']=$val['contact_phone'];
							$twilio->data['Body']=$val['sms_message'];
							if ($resp=$twilio->sendSMS()){
								$raw=$twilio->getSuccessXML();				
								$msg="process";
							} else $msg=$twilio->getError();	
						}
						
					//} else $msg=Yii::t("default","Admin SMS Settings Invalid");			
				} else $msg=Yii::t("default","No SMS Credit");
				
				$params=array(
				  'status'=>$msg,
				  'gateway_response'=>$raw,
				  'date_executed'=>date('c'),
				  'gateway'=>$sms_provider
				);
				$db_ext->updateData("{{sms_broadcast_details}}",$params,'id',$val['id']);
			}
		} else echo "<p>No records to process</p>";
	}	
	
	public function actionProcessPayout()
	{		
		$db_ext=new DbExt;
		
		$paypal_client_id=yii::app()->functions->getOptionAdmin('wd_paypal_client_id');
		$paypal_client_secret=yii::app()->functions->getOptionAdmin('wd_paypal_client_secret');
		
		$paypal_config=Yii::app()->functions->getPaypalConnectionWithdrawal();
		dump($paypal_config);
		$Paypal=new Paypal($paypal_config);
		$Paypal->debug=true;
		
		$website_title=yii::app()->functions->getOptionAdmin('website_title');
		
		$cron=new CronFunctions;		
		if ( $res=$cron->getPayoutToProcess()){
			if (is_array($res) && count($res)>=1){
				foreach ($res as $val) {
					$withdrawal_id=$val['withdrawal_id'];
					$api_raw_response='';
					$status_msg='';
					dump($val);
					switch ($val['payment_method']){
						case "paypal":
							dump("Process paypal");
							//if (!empty($paypal_client_id) && !empty($paypal_client_secret)){
							if (is_array($paypal_config) && count($paypal_config)>=1){
								if ( $val['account']!=""){
									
									$Paypal->params['RECEIVERTYPE']="EmailAddress";
									$Paypal->params['CURRENCYCODE']="USD";
									$Paypal->params['EMAILSUBJECT']="=You have a payment from ".$website_title;
									
									$Paypal->params['L_EMAIL0']=$val['account'];
									$Paypal->params['L_AMT0']=normalPrettyPrice($val['amount']);
									$Paypal->params['L_UNIQUEID0']=str_pad($val['withdrawal_id'],10,"0");																														
									if ( $pay_resp=$Paypal->payout()){
									    dump($pay_resp);
									    if ( $pay_resp['ACK']=="Success"){
									    	$status_msg='paid';		
									    	$api_raw_response=json_encode($pay_resp);
									    } else {
									    	$api_raw_response=json_encode($pay_resp);
									    	$status_msg=$pay_resp['L_LONGMESSAGE0'];
									    }
									} else $status_msg=$Paypal->getError();
								} else $status_msg=t("Paypal account is empty");
							} else $status_msg=t("Payout settings for paypal not yet set");
							break;
							
						case "bank":
							$status_msg='paid';
							break;	
					}
					
					echo "<h3>Update status</h3>";
					dump($api_raw_response);
					dump($status_msg);
					$params_update=array(
					  'date_process'=>date('c'),
					  'api_raw_response'=>$api_raw_response,
					  'status'=>$status_msg
					);
					dump($params_update);
					if ( $db_ext->updateData("{{withdrawal}}",$params_update,'withdrawal_id',$withdrawal_id)){
						echo "<h2>Update ok</h2>";
					} else echo "<h2>Update Failed</h2>";
					
					if ( $status_msg=="paid"){
						// send email
						$subject=yii::app()->functions->getOptionAdmin('wd_template_process_subject');
						if (empty($subject)){
	                        $subject=t("Your Request for Withdrawal has been Processed");
                        }
                        if ( $merchant_info=Yii::app()->functions->getMerchant($val['merchant_id'])){ 
                        	$merchant_email=$merchant_info['contact_email'];
                        	$tpl=yii::app()->functions->getOptionAdmin('wd_template_process');
                        	$tpl=smarty("merchant-name",$merchant_info['restaurant_name'],$tpl);
			                $tpl=smarty("payout-amount",standardPrettyFormat($val['amount']),$tpl);
			                $tpl=smarty("payment-method",$val['payment_method'],$tpl);
			                $tpl=smarty("acoount",$val['account'],$tpl);
                        	dump($tpl);
                        	if(!empty($tpl)){
                        		sendEmail($merchant_email,'',$subject,$tpl);
                        	}
                        }	
					}
				}
			}
		} else dump("No record to process");
	}
	
	public function actionFax()
	{
		$msg='';
		$send_fax_link='https://www.faxage.com/httpsfax.php';
		
		$db_ext=new DbExt;
		$stmt="SELECT * FROM
		{{fax_broadcast}}
		WHERE
		status='pending'
		LIMIT 0,5
		";
		
		$fax_company=yii::app()->functions->getOptionAdmin("fax_company");
		$fax_username=yii::app()->functions->getOptionAdmin("fax_username");
		$fax_password=yii::app()->functions->getOptionAdmin("fax_password");
		
		dump("company: ".$fax_company);
		dump("username: ".$fax_username);
		dump("password: ".$fax_password);
		$notify_url=websiteUrl()."/cron/faxpostback/";
		
		if ( $res=$db_ext->rst($stmt)){			
			foreach ($res as $val) {
				dump($val);				
				$jobid='';
				$record_id=$val['id'];
				$credit=Yii::app()->functions->getMerchantFaxCredit($val['merchant_id']);	    	
				dump($credit);
				if ($credit>=1){
					$params="username=".$fax_username;
					$params.="&company=".$fax_company;
					$params.="&password=".$fax_password;
					$params.="&recipname=".$val['recipname'];
					$params.="&faxno=".$val['faxno'];
					$params.="&operation=sendfax";
					$params.="&faxurl=".$val['faxurl'];
					$params.="&url_notify=$notify_url";					
					dump($params);
					if ( $response=Yii::app()->functions->Curl($send_fax_link,$params)){
						$msg=$response;
						if (preg_match("/JOBID/i", $response)) {
							$jobid=str_replace("JOBID:",'',$response);
							$jobid=trim($jobid);
						} else $jobid='';
					} else $msg="Invalid response";
				} else $msg=t("Zero credits");
				
				$params_update=array(
				 'status'=>"process",
				 'api_raw_response'=>$msg,
				 'date_process'=>date('c'),
				 'jobid'=>$jobid
				);
				$db_ext->updateData("{{fax_broadcast}}",$params_update,'id',$record_id);
			} /*end foreach*/
		} else $msg="NO records to process";
		
		dump("Result: ".$msg);
	}
	
	public function actionFaxPostBack()
	{
		$data=$_REQUEST;
		dump($data);
		if ( $res=Yii::app()->functions->getFaxJobId($data['jobid'])){
			dump($res);
			$record_id=$res['id'];
			$params=array(
			 'status'=>$data['shortstatus'],
			 'api_raw_response'=>$data['longstatus'],
			 'date_postback'=>date('c')
			);
			dump($params);
			$db_ext=new DbExt;
			$db_ext->updateData("{{fax_broadcast}}",$params,'jobid',$data['jobid']);
		}
	}
	
}/* END CLASS*/