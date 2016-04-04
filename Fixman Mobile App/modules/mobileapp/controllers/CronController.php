<?php
class CronController extends CController
{
	
	public function actionIndex()
	{
		
	}
	
	public function actionProcesspush()
	{
		$iOSPush=new iOSPush;
		$DbExt=new DbExt; 

		$ios_push_mode=Yii::app()->functions->getOptionAdmin('ios_push_mode');
		$ios_passphrase=Yii::app()->functions->getOptionAdmin('ios_passphrase');
		$ios_push_dev_cer=Yii::app()->functions->getOptionAdmin('ios_push_dev_cer');
		$ios_push_prod_cer=Yii::app()->functions->getOptionAdmin('ios_push_prod_cer');
							
		$api_key=Yii::app()->functions->getOptionAdmin('mobile_android_push_key');		
		$msg_count=1;		
				
		$stmt="SELECT * FROM
		{{mobile_push_logs}}
		WHERE
		status='pending'
		ORDER BY id ASC
		LIMIT 0,10
		";
		if($res=$DbExt->rst($stmt)){		   
		   foreach ($res as $val) {		
		   	  $status='';
		   	  $record_id=$val['id'];		   	  
		   	  
		   	  $message=array(		 
				 'title'=>$val['push_title'],
				 'message'=>$val['push_message'],
				 'count'=>$msg_count,
				 'additionalData'=>array(
				   'push_type'=>$val['push_type']		   		 
				 )
			   );			   			   			   
			   
			   if ( strtolower($val['device_platform'])=="ios"){			   	   
			   	   /*send push using ios*/
			   	   $iOSPush->pass_prase=$ios_passphrase;
			   	   $iOSPush->dev_certificate=$ios_push_dev_cer;
			   	   $iOSPush->prod_certificate=$ios_push_prod_cer;
			   	   		   	
			   	   $ios_push_mode=$ios_push_mode=="development"?false:true;
			   	   
			   	   if ($resp=$iOSPush->push($val['push_message'],$val['device_id'],$ios_push_mode)){
			   	   	   $status="process";
			   	   } else $status=$iOSPush->get_msg();
			   	   
			   } else {
			   	   /*send push using android*/
			   	   dump($message);
				   if (!empty($api_key)){
			   	       $resp=AddonMobileApp::sendPush($val['device_platform'],$api_key,$val['device_id'],$message);
			   	       if (AddonMobileApp::isArray($resp)){
			   	       	   dump($resp);
			   	       	   if( $resp['success']>0){			   	       	   	   
			   	       	   	   $status="process";
			   	       	   } else {		   	       	   	   
			   	       	   	   $status=$resp['results'][0]['error'];
			   	       	   }
			   	       } else $status="uknown push response";
				   } else $status="Invalid API Key";
			   }
			   			   
			   $params_update=array(
			     'status'=>empty($status)?"uknown status":$status,
			     'date_process'=>date('c'),
			     'json_response'=>json_encode($resp)
			    );
			   dump($params_update);
			   $DbExt->updateData('{{mobile_push_logs}}',$params_update,'id',$record_id);			   			   
		   }
		}  else echo "No records to process<br/>";
	} 		
	
	public function actionProcessBroadcast()
	{
		$DbExt=new DbExt; 
	    $stmt="
	    SELECT * FROM
	    {{mobile_broadcast}}
	    WHERE
	    status='pending'
	    ORDER BY broadcast_id ASC
	    LIMIT 0,1	    
	    ";
	    if ( $res=$DbExt->rst($stmt)){
	    	$res=$res[0];	    	
	    	$broadcast_id=$res['broadcast_id'];
	    	
	    	$and='';
	    	switch ($res['device_platform']) {
	    		case "1":	    			
	    		   $and=" AND device_platform ='Android'";
	    			break;
	    	
	    		case "2":	
	    		  $and=" AND device_platform ='iOS'";
	    		   break;  
	    		   
	    		default:
	    			break;
	    	}
	    	$stmt2="
	    	SELECT * FROM
	    	{{mobile_registered_view}}
	    	WHERE
	    	enabled_push='1'
	    	$and   	
	    	";
	    	if ($res2=$DbExt->rst($stmt2)){
	    		foreach ($res2 as $val) {	    			
	    			$params=array(
	    			  'client_id'=>$val['client_id'],
	    			  'client_name'=>!empty($val['client_name'])?$val['client_name']:'no name',
	    			  'device_platform'=>$val['device_platform'],
	    			  'device_id'=>$val['device_id'],
	    			  'push_title'=>$res['push_title'],
	    			  'push_message'=>$res['push_message'],
	    			  'push_type'=>'campaign',
	    			  'date_created'=>date('c'),
	    			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    			  'broadcast_id'=>$res['broadcast_id']
	    			);
	    			dump($params);
	    			$DbExt->insertData("{{mobile_push_logs}}",$params);
	    		}
	    		dump("Finish");
	    	}
	    	
	    	$params_update=array('status'=>"process");
	    	$DbExt->updateData('{{mobile_broadcast}}',$params_update,'broadcast_id',$broadcast_id);
	    	
	    } else echo 'No records to process';
	}
	
}/* end class*/