<?php
class ApiController extends CController
{	
	public $data;
	public $code=2;
	public $msg='';
	public $details='';
	
	public function __construct()
	{
		$this->data=$_GET;
		
		$website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
	    if (!empty($website_timezone)){
	 	   Yii::app()->timeZone=$website_timezone;
	    }		 
	}
	
	public function beforeAction(CAction $action)
	{				
		return true;
	}	
	
	public function actionIndex(){
		//throw new CHttpException(404,'The specified url cannot be found.');
	}		
	
	private function q($data='')
	{
		return Yii::app()->db->quoteValue($data);
	}
	
	private function t($message='')
	{
		return Yii::t("default",$message);
	}
		
    private function output()
    {
	   $resp=array(
	     'code'=>$this->code,
	     'msg'=>$this->msg,
	     'details'=>$this->details,
	     'request'=>json_encode($this->data)		  
	   );		   
	   if (isset($this->data['debug'])){
	   	   dump($resp);
	   }
	   
	   if (!isset($_GET['callback'])){
  	   	   $_GET['callback']='';
	   }    
	   
	   if (isset($_GET['json']) && $_GET['json']==TRUE){
	   	   echo CJSON::encode($resp);
	   } else echo $_GET['callback'] . '('.CJSON::encode($resp).')';		    	   	   	  
	   Yii::app()->end();
    }	
    
    public function actionLogin()
    {
        $Validator=new Validator;
		$req=array(
		  'username'=>$this->t("username is required"),
		  'password'=>$this->t("password is required")		  
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::login($this->data['username'],md5($this->data['password']))){				
				if ($res['status']=="active" || $res['status']=="expired"){
					
					/*get device information and update*/
					if (isset($this->data['merchant_device_id'])){
						if ( $resp=merchantApp::getDeviceInfo($this->data['merchant_device_id'])){							
							$record_id=$resp['id'];
							
							$params['merchant_id']=$res['merchant_id'];		
							$params['user_type']=$res['user_type'];
							$params['date_modified']=date('c');
							$params['status']='active';
							
							if ( $res['user_type']=="admin"){
							    $params['merchant_user_id']=0;
							} else {								
								$params['merchant_user_id']=$res['merchant_user_id'];
							}											
							$DbExt=new DbExt;
							$DbExt->updateData('{{mobile_device_merchant}}',$params,'id',$record_id);
							
							/*now update all device previous use by user*/
							if ( $res['user_type']=="admin"){								
								$stmt_update="UPDATE
								{{mobile_device_merchant}}																
								SET status='inactive'
								WHERE
								merchant_id =".merchantApp::q($res['merchant_id'])."
								AND
								user_type ='admin'
								AND id NOT IN ('$record_id')
								";
								$DbExt->qry($stmt_update);
							} else {								
								$stmt_update="UPDATE
								{{mobile_device_merchant}}																
								SET status='inactive'
								WHERE
								merchant_user_id  =".merchantApp::q($res['merchant_user_id'])."
								AND
								user_type ='user'
								AND id NOT IN ('$record_id')
								";
								$DbExt->qry($stmt_update);
							}												
						}
					}
					
					$this->msg=$this->t("Successul");
					$this->code=1;
					$this->details=array(
					  'token'=>$res['token'],
					  'info'=>array(
					    'username'=>$res['username'],
					    'restaurant_name'=>$res['restaurant_name'],					    
					    'contact_email'=>$res['contact_email'],
					    'user_type'=>$res['user_type'],
					    'merchant_id'=>$res['merchant_id']
					  )
					);
				} else $this->msg=$this->t("Login Failed. You account status is")." ".$res['status'];
			} else $this->msg=$this->t("either username or password is invalid");
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();
    }
    
    public function actionGetTodaysOrder()
    {    	
    	
    	$Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    	
			    $DbExt=new DbExt;	
				$stmt="
				SELECT a.*,
				(
				select concat(first_name,' ',last_name)
				from 
				{{client}}
				where
				client_id=a.client_id
				limit 0,1				
				) as customer_name
				
				FROM
				{{order}} a
				WHERE
				merchant_id=".$this->q($res['merchant_id'])."				
				AND
				date_created LIKE '".date("Y-m-d")."%'						
				AND 
				status NOT IN ('initial_order')					
				ORDER BY date_created DESC
				LIMIT 0,100
				";				
				if ( $res=$DbExt->rst($stmt)){					
					$this->code=1; $this->msg="OK";					
					foreach ($res as $val) {							
						$data[]=array(						  
						  'order_id'=>$val['order_id'],
						  'viewed'=>$val['viewed'],
						  'status'=>t($val['status']),
						  'status_raw'=>$val['status'],
						  'trans_type'=>t($val['trans_type']),
						  'trans_type_raw'=>$val['trans_type'],
						  'total_w_tax'=>$val['total_w_tax'],						  
						  'total_w_tax_prety'=>merchantApp::prettyPrice($val['total_w_tax']),
						  'transaction_date'=>Yii::app()->functions->FormatDateTime($val['date_created'],true),
						  'transaction_time'=>Yii::app()->functions->timeFormat($val['date_created'],true),
						  'delivery_time'=>Yii::app()->functions->timeFormat($val['delivery_time'],true),
						  'delivery_asap'=>$val['delivery_asap']==1?t("ASAP"):'',
						  'delivery_date'=>Yii::app()->functions->FormatDateTime($val['delivery_date'],false),
						  'customer_name'=>!empty($val['customer_name'])?$val['customer_name']:$this->t('No name')
						);
					}					
					$this->code=1;
					$this->msg="OK";
					$this->details=$data;
				} else $this->msg=$this->t("no current orders");
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();    	    
    }
    
    public function actionGetPendingOrders()
    {    	    
    	$Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    	
			    $DbExt=new DbExt;	
				$stmt="
				SELECT a.*,
				
				(
				select concat(first_name,' ',last_name)
				from 
				{{client}}
				where
				client_id=a.client_id
				limit 0,1				
				) as customer_name

				FROM
				{{order}} a
				WHERE
				merchant_id=".$this->q($res['merchant_id'])."
				AND
				status IN ('pending')							
				ORDER BY date_created DESC
				LIMIT 0,100
				";
				if ( $res=$DbExt->rst($stmt)){					
					$this->code=1; $this->msg="OK";					
					foreach ($res as $val) {						
						$data[]=array(
						  'order_id'=>$val['order_id'],
						  'viewed'=>$val['viewed'],
						  'status'=>t($val['status']),
						  'status_raw'=>$val['status'],
						  'trans_type'=>t($val['trans_type']),
						  'trans_type_raw'=>$val['trans_type'],
						  'total_w_tax'=>$val['total_w_tax'],						  
						  'total_w_tax_prety'=>merchantApp::prettyPrice($val['total_w_tax']),
						  'transaction_date'=>Yii::app()->functions->FormatDateTime($val['date_created'],true),
						  'transaction_time'=>Yii::app()->functions->timeFormat($val['date_created'],true),
						  'delivery_time'=>Yii::app()->functions->timeFormat($val['delivery_time'],true),
						  'delivery_asap'=>$val['delivery_asap']==1?t("ASAP"):'',
						  'delivery_date'=>Yii::app()->functions->FormatDateTime($val['delivery_date'],false),
						  'customer_name'=>!empty($val['customer_name'])?$val['customer_name']:$this->t('No name')
						);
					}					
					$this->code=1;
					$this->msg="OK";
					$this->details=$data;
				} else $this->msg=$this->t("no pending orders");
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();    	    
    }    
    
    public function actionGetAllOrders()
    {
    	$Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    	
			    $DbExt=new DbExt;
				$stmt="
				SELECT a.*,

				(
				select concat(first_name,' ',last_name)
				from 
				{{client}}
				where
				client_id=a.client_id
				limit 0,1				
				) as customer_name
				
				FROM
				{{order}} a
				WHERE
				merchant_id=".$this->q($res['merchant_id'])."	
				AND status NOT IN ('initial_order')			
				ORDER BY date_created DESC
				LIMIT 0,100
				";			
				if ( $res=$DbExt->rst($stmt)){					
					$this->code=1; $this->msg="OK";					
					foreach ($res as $val) {						
						$data[]=array(
						  'order_id'=>$val['order_id'],
						  'viewed'=>$val['viewed'],
						  'status'=>t($val['status']),
						  'status_raw'=>$val['status'],
						  'trans_type'=>t($val['trans_type']),
						  'trans_type_raw'=>$val['trans_type'],
						  'total_w_tax'=>$val['total_w_tax'],						  
						  'total_w_tax_prety'=>merchantApp::prettyPrice($val['total_w_tax']),
						  'transaction_date'=>Yii::app()->functions->FormatDateTime($val['date_created'],true),
						  'transaction_time'=>Yii::app()->functions->timeFormat($val['date_created'],true),
						  'delivery_time'=>Yii::app()->functions->timeFormat($val['delivery_time'],true),
						  'delivery_asap'=>$val['delivery_asap']==1?t("ASAP"):'',
						  'delivery_date'=>Yii::app()->functions->FormatDateTime($val['delivery_date'],false),
						  'customer_name'=>!empty($val['customer_name'])?$val['customer_name']:$this->t('No name')
						);
					}					
					$this->code=1;
					$this->msg="OK";
					$this->details=$data;
				} else $this->msg=$this->t("no orders found");
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();    	    
    }
    
    public function actionOrderdDetails()
    {        
    	    	    	
        $Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		  'order_id'=>$this->t("order id is required")
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    	
			    if ( $data=Yii::app()->functions->getOrder2($this->data['order_id'])){
			    	//dump($data);
			    	$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
			    	
			    	Yii::app()->functions->displayOrderHTML(
			    	array(
					  'merchant_id'=>$data['merchant_id'],
					  'delivery_type'=>$data['trans_type'],
					  'delivery_charge'=>$data['delivery_charge'],
					  'packaging'=>$data['packaging'],
					  'cart_tip_value'=>$data['cart_tip_value'],
					  'cart_tip_percentage'=>$data['cart_tip_percentage'],
					  'card_fee'=>$data['card_fee']					  
					  ),
					  $json_details,true);
					  
					  if ( Yii::app()->functions->code==1){					  	  
					  	  $data_raw=Yii::app()->functions->details['raw'];						 
					  	  //dump($data_raw);
					  	  
					  	  /*fixed sub item*/
					  	  $new_sub_item='';
					  	  foreach ($data_raw['item'] as $key=>$item) {					  	  	 
					  	  	 if (isset($item['sub_item'])){
					  	  	     if (is_array($item['sub_item']) && count($item['sub_item'])>=1){
					  	  	     	foreach ($item['sub_item'] as $sub_item) {					
					  	  	     		$sub_item['total'] = merchantApp::prettyPrice(
					  	  	     		$sub_item['addon_qty']*$sub_item['addon_price']);
					  	  	     		$new_sub_item[$sub_item['addon_category']][]=$sub_item;
					  	  	     	}
					  	  	     }					  	  	 
					  	  	     $data_raw['item'][$key]['sub_item_new']=$new_sub_item;
					  	  	     unset($new_sub_item);
					  	  	 }					  	  					  	  	 
					  	  }
					  	  
						  $data_raw['total']['subtotal']=merchantApp::prettyPrice($data_raw['total']['subtotal']);
						  $data_raw['total']['subtotal1']=$data['sub_total'];
						  $data_raw['total']['subtotal2']=merchantApp::prettyPrice($data['sub_total']);
						  
						  $data_raw['total']['taxable_total']=merchantApp::prettyPrice($data['taxable_total']);
						  $data_raw['total']['delivery_charges']=merchantApp::prettyPrice($data_raw['total']['delivery_charges']);
						  
						  $data_raw['total']['total']=merchantApp::prettyPrice($data['total_w_tax']);
						  
						  
						  $data_raw['total']['tax_amt']=$data_raw['total']['tax_amt']."%";
						  $data_raw['total']['merchant_packaging_charge']=merchantApp::prettyPrice($data_raw['total']['merchant_packaging_charge']);
						  						 						  
						  if ($data['order_change']>0){
						     $data_raw['total']['order_change']= merchantApp::prettyPrice($data['order_change']);
						  }
						  
						  if ($data['voucher_amount']>0){
						      $data_raw['total']['voucher_amount']=$data['voucher_amount'];
						      $data_raw['total']['voucher_amount1']=merchantApp::prettyPrice($data['voucher_amount']);						      
						  }
						  
						  if ($data['discounted_amount']>0){
						  	 $data_raw['total']['discounted_amount']=$data['discounted_amount'];
						  	 $data_raw['total']['discounted_amount1']=merchantApp::prettyPrice($data['discounted_amount']);
						  	 $data_raw['total']['discount_percentage']=number_format($data['discount_percentage'],0)."%";
						  	 $data_raw['total']['subtotal']=merchantApp::prettyPrice($data['sub_total']+$data['voucher_amount']);						  	 
						  }		
						  
						  /*less points_discount*/						  
						  if (isset($data['points_discount'])){						  	 
						  	 if ( $data['points_discount']>0){						  	 	
						  	 	$data_raw['total']['points_discount']=$data['points_discount'];
						  	 	$data_raw['total']['points_discount1']=merchantApp::prettyPrice($data['points_discount']);						  	 	$data_raw['total']['subtotal']=merchantApp::prettyPrice($data['sub_total']);
						  	 }						  
						  }			
						  						  
						  /*tips*/						  
						  if ( $data['cart_tip_value']>0){						  	  
						  	  $data_raw['total']['cart_tip_value']=$data['cart_tip_value'];
						  	  $data_raw['total']['cart_tip_value']=merchantApp::prettyPrice($data['cart_tip_value']);
						  	  $data_raw['total']['cart_tip_percentage']=number_format($data['cart_tip_percentage'],0)."%";
						  }					  
						  
						  $pos = Yii::app()->functions->getOptionAdmin('admin_currency_position'); 
						  $data_raw['currency_position']=$pos;					  
						  
						  $delivery_date=$data['delivery_date'];
						  						  						  
						  $data_raw['transaction_date']	= Yii::app()->functions->FormatDateTime($data['date_created']);						          $data_raw['delivery_date'] = Yii::app()->functions->FormatDateTime($delivery_date,false);
						  $data_raw['delivery_time'] = $data['delivery_time'];
						  $data_raw['delivery_asap'] = $data['delivery_asap']==1?t("Yes"):"";
						  $data_raw['status']=t($data['status']);
						  $data_raw['status_raw']=$data['status'];
						  $data_raw['trans_type']=t($data['trans_type']);
						  $data_raw['trans_type_raw']=$data['trans_type'];
						  $data_raw['payment_type']=strtoupper($data['payment_type']);
						  $data_raw['viewed']=$data['viewed'];
						  $data_raw['order_id']=$data['order_id'];
						  
						  $data_raw['delivery_instruction']=$data['delivery_instruction'];
						  $data_raw['client_info']=array(
						    'full_name'=>$data['full_name'],
						    'email_address'=>$data['email_address'],
						    'address'=>$data['client_full_address'],
						    'location_name'=>$data['location_name1'],
						    'contact_phone'=>$data['contact_phone']
						  );			
						  						  
						  $this->code=1;
						  $this->msg="OK";				  
						  $this->details=$data_raw;
						  
						  // update the order id to viewed						  
						  $params=array(
						    'viewed'=>2
						  );
						  $DbExt=new DbExt;
						  $DbExt->updateData("{{order}}",$params,'order_id',$this->data['order_id']);
						  
					  } else $this->msg=$this->t("order details not available");
			    } else $this->msg=$this->t("order details not available");
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();    	    	
    }
    
    public function actionAcceptOrdes()
    {
    	
    	$Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		  'order_id'=>$this->t("order id is required")
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    				    
			    $merchant_id=$res['merchant_id'];
			    $order_id=$this->data['order_id'];			    
			    
			    if ( Yii::app()->functions->isMerchantCommission($merchant_id)){  
	    	    	if ( FunctionsK::validateChangeOrder($order_id)){
	    	    		$this->msg=t("Sorry but you cannot change the order status of this order it has reference already on the withdrawals that you made");
	    	    		$this->output();	    	    		
	    	    	}    	    
    	        }	        
    	        
    	        /*check if merchant can change the status*/
	    	    $can_edit=Yii::app()->functions->getOptionAdmin('merchant_days_can_edit_status');	    	    
	    	    if (is_numeric($can_edit) && !empty($can_edit)){
	    	    	
		    	    $date_now=date('Y-m-d');
		    	    $base_option=getOptionA('merchant_days_can_edit_status_basedon');	
		    	    
		    	    if ( $base_option==2){	    					
						$date_created=date("Y-m-d",
						strtotime($resp['delivery_date']." ".$resp['delivery_time']));		
					} else $date_created=date("Y-m-d",strtotime($resp['date_created']));
					    			
					$date_interval=Yii::app()->functions->dateDifference($date_created,$date_now);					
	    			if (is_array($date_interval) && count($date_interval)>=1){		    				
	    				if ( $date_interval['days']>$can_edit){
	    					$this->msg=t("Sorry but you cannot change the order status anymore. Order is lock by the website admin");
	    					$this->details=json_encode($date_interval);
	    					$this->output();
	    				}		    			
	    			}	    		
	    	    }
    	        
	    	    //$order_status='pending';
	    	    $order_status='accepted';
	    	    
    	        if ( $resp=Yii::app()->functions->verifyOrderIdByOwner($order_id,$merchant_id) ){     	        	
    	        	$params=array( 
    	        	  'status'=>$order_status,
    	        	  'date_modified'=>date('c'),
    	        	  'viewed'=>2
    	        	);    	        	
    	        	
    	        	$DbExt=new DbExt;
    	        	if ($DbExt->updateData('{{order}}',$params,'order_id',$order_id)){
    	        		$this->code=1;
    	        		$this->msg=t("Order ID").":$order_id ".t("has been accepted");
    	        		$this->details=array(
    	        		 'order_id'=>$order_id
    	        		);
    	        		
    	        		/*Now we insert the order history*/	    		
	    				$params_history=array(
	    				  'order_id'=>$order_id,
	    				  'status'=>$order_status,
	    				  'remarks'=>isset($this->data['remarks'])?$this->data['remarks']:'',
	    				  'date_created'=>date('c'),
	    				  'ip_address'=>$_SERVER['REMOTE_ADDR']
	    				);	    				
	    				$DbExt->insertData("{{order_history}}",$params_history);
	    				
	    				/*now we send email and sms*/
	    				merchantApp::sendEmailSMS($order_id);
    	        		
    	        	} else $this->msg=t("ERROR: cannot update order.");    	        	
    	        } else $this->msg=$this->t("This Order does not belong to you");
    	            	        	    
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}  	
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();    	    	
    }
    
    public function actionDeclineOrders()
    {
    	
    	$Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		  'order_id'=>$this->t("order id is required")
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    				    
			    $merchant_id=$res['merchant_id'];
			    $order_id=$this->data['order_id'];		 

			    if ( Yii::app()->functions->isMerchantCommission($merchant_id)){  
	    	    	if ( FunctionsK::validateChangeOrder($order_id)){
	    	    		$this->msg=t("Sorry but you cannot change the order status of this order it has reference already on the withdrawals that you made");
	    	    		$this->output();	    	    		
	    	    	}    	    
    	        }	        
    	        
    	        /*check if merchant can change the status*/
	    	    $can_edit=Yii::app()->functions->getOptionAdmin('merchant_days_can_edit_status');	    	    
	    	    if (is_numeric($can_edit) && !empty($can_edit)){
	    	    	
		    	    $date_now=date('Y-m-d');
		    	    $base_option=getOptionA('merchant_days_can_edit_status_basedon');	
		    	    
		    	    if ( $base_option==2){	    					
						$date_created=date("Y-m-d",
						strtotime($resp['delivery_date']." ".$resp['delivery_time']));		
					} else $date_created=date("Y-m-d",strtotime($resp['date_created']));
					    			
					$date_interval=Yii::app()->functions->dateDifference($date_created,$date_now);					
	    			if (is_array($date_interval) && count($date_interval)>=1){		    				
	    				if ( $date_interval['days']>$can_edit){
	    					$this->msg=t("Sorry but you cannot change the order status anymore. Order is lock by the website admin");
	    					$this->details=json_encode($date_interval);
	    					$this->output();
	    				}		    			
	    			}	    		
	    	    }			   
			    
			    $order_status='decline';
			    
			    if ( $resp=Yii::app()->functions->verifyOrderIdByOwner($order_id,$merchant_id) ){     	        	
    	        	$params=array( 
    	        	  'status'=>$order_status,
    	        	  'date_modified'=>date('c'),
    	        	  'viewed'=>2
    	        	);    	    
    	        
    	        	$DbExt=new DbExt;
    	        	if ($DbExt->updateData('{{order}}',$params,'order_id',$order_id)){
    	        		$this->code=1;
    	        		//$this->msg=t("order has been declined");
    	        		$this->msg=t("Order ID").":$order_id ".t("has been declined");
    	        		$this->details=array(
    	        		 'order_id'=>$order_id
    	        		);
    	        		
    	        		/*Now we insert the order history*/	    		
	    				$params_history=array(
	    				  'order_id'=>$order_id,
	    				  'status'=>$order_status,
	    				  'remarks'=>isset($this->data['remarks'])?$this->data['remarks']:'',
	    				  'date_created'=>date('c'),
	    				  'ip_address'=>$_SERVER['REMOTE_ADDR']
	    				);	    				
	    				$DbExt->insertData("{{order_history}}",$params_history);
	    				
	    				/*now we send email and sms*/
	    				merchantApp::sendEmailSMS($order_id);
    	        		
    	        	} else $this->msg=t("ERROR: cannot update order.");    	        	
    	        	
			    } else $this->msg=$this->t("This Order does not belong to you");
			    
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}  	
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();   
    }
    
    public function actionChangeOrderStatus()
    {
    	$Validator=new Validator;
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		  'order_id'=>$this->t("order id is required"),
		  'status'=>$this->t("order status is required")
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    				    
			    $merchant_id=$res['merchant_id'];
			    $order_id=$this->data['order_id'];		 

			    if ( Yii::app()->functions->isMerchantCommission($merchant_id)){  
	    	    	if ( FunctionsK::validateChangeOrder($order_id)){
	    	    		$this->msg=t("Sorry but you cannot change the order status of this order it has reference already on the withdrawals that you made");
	    	    		$this->output();	    	    		
	    	    	}    	    
    	        }
    	            	        
    	        /*check if merchant can change the status*/
	    	    $can_edit=Yii::app()->functions->getOptionAdmin('merchant_days_can_edit_status');	    	    
	    	    if (is_numeric($can_edit) && !empty($can_edit)){
	    	    	
		    	    $date_now=date('Y-m-d');
		    	    $base_option=getOptionA('merchant_days_can_edit_status_basedon');	
		    	    
		    	    if ( $base_option==2){	    					
						$date_created=date("Y-m-d",
						strtotime($resp['delivery_date']." ".$resp['delivery_time']));		
					} else $date_created=date("Y-m-d",strtotime($resp['date_created']));
					    			
					$date_interval=Yii::app()->functions->dateDifference($date_created,$date_now);					
	    			if (is_array($date_interval) && count($date_interval)>=1){		    				
	    				if ( $date_interval['days']>$can_edit){
	    					$this->msg=t("Sorry but you cannot change the order status anymore. Order is lock by the website admin");
	    					$this->details=json_encode($date_interval);
	    					$this->output();
	    				}		    			
	    			}	    		
	    	    }			   
			    
			    $order_status=$this->data['status'];
			    
			    if ( $resp=Yii::app()->functions->verifyOrderIdByOwner($order_id,$merchant_id) ){     	        	
    	        	$params=array( 
    	        	  'status'=>$order_status,
    	        	  'date_modified'=>date('c'),
    	        	  'viewed'=>2
    	        	);    	    
    	        
    	        	$DbExt=new DbExt;
    	        	if ($DbExt->updateData('{{order}}',$params,'order_id',$order_id)){
    	        		$this->code=1;
    	        		$this->msg=t("order status successfully changed");
    	        		
    	        		/*Now we insert the order history*/	    		
	    				$params_history=array(
	    				  'order_id'=>$order_id,
	    				  'status'=>$order_status,
	    				  'remarks'=>isset($this->data['remarks'])?$this->data['remarks']:'',
	    				  'date_created'=>date('c'),
	    				  'ip_address'=>$_SERVER['REMOTE_ADDR']
	    				);	    				
	    				$DbExt->insertData("{{order_history}}",$params_history);
	    				
	    				/*now we send email and sms*/
	    				merchantApp::sendEmailSMS($order_id);
    	        		
    	        	} else $this->msg=t("ERROR: cannot update order.");    	        	
    	        	
			    } else $this->msg=$this->t("This Order does not belong to you");			    
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}  	
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();   
    }
    
    public function actionForgotPassword()
    {
    	
    	if (isset($this->data['email_address'])){
    		if (empty($this->data['email_address'])){
    			$this->msg=t("email address is required");
    			$this->output();
    		}
    		
    		if ($res=merchantApp::getUserByEmail($this->data['email_address'])){
    		   
    		   $tbl="merchant";
    		   if ( $res['user_type']=="user"){
    		   	   $tbl="merchant_user";
    		   }    		
    		   $params=array('lost_password_code'=> yii::app()->functions->generateCode());	 
    		   
    		   $DbExt=new DbExt;
    		   if ( $DbExt->updateData("{{{$tbl}}}",$params,'merchant_id',$res['merchant_id'])){
    		   	   $this->code=1;
    		   	   $this->msg=t("We have sent verification code in your email.");
    		   	       		   	   
    		   	   $tpl=EmailTPL::merchantForgotPass($res[0],$params['lost_password_code']);
    			   $sender=Yii::app()->functions->getOptionAdmin('website_contact_email');
	               $to=$res['contact_email'];	               
	               if (!sendEmail($to,$sender,t("Merchant Forgot Password"),$tpl)){		    	
	                	$email_stats="failed";
	                } else $email_stats="ok mail";
	                
	                $this->details=array(
	                  'email_stats'=>$email_stats,
	                  'user_type'=>$res['user_type'],
	                  'email_address'=>$this->data['email_address']
	                );
	                
    		   } else $this->msg=t("ERROR: Cannot update");
    		   
    		} else $this->msg=t("sorry but the email address you supplied does not exist in our records");
    		
    	} else $this->msg=t("email address is required");
    	$this->output();   
    }
    
    public function actionChangePasswordWithCode()
    {        
    	
    	
        $Validator=new Validator;
		$req=array(
		  'code'=>$this->t("code is required"),
		  'newpass'=>$this->t("new passwords is required"),		  
		  'user_type'=>t("user type is missing"),
		  'email_address'=>$this->t("email address is required")
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			
			if ( $res=merchantApp::getMerchantByCode($this->data['code'],$this->data['email_address'],
			$this->data['user_type'])){
								
				$params=array(
				  'password'=>md5($this->data['newpass']),
	    		  'date_modified'=>date('c'),
	    	      'ip_address'=>$_SERVER['REMOTE_ADDR']
				);			
								
				$DbExt=new DbExt;
				if ( $this->data['user_type']=="admin"){
					// update merchant table
					if ($DbExt->updateData("{{merchant}}",$params,'merchant_id',$res['merchant_id'])){
						$this->msg=t("You have successfully change your password");
	    				$this->code=1;
					} else $this->msg=t("ERROR: cannot update records.");
				} else {
					// update merchant user table merchant_user_id
					if ($DbExt->updateData("{{merchant_user}}",$params,'merchant_user_id',$res['merchant_user_id'])){
						$this->msg=t("You have successfully change your password");
	    				$this->code=1;
					} else $this->msg=t("ERROR: cannot update records.");
				}				
			} else $this->msg=t("verification code is invalid");
			
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output(); 
    }
    
    public function actionRegisterMobile()
    {    	
    	$DbExt=new DbExt;
		$params['device_id']=isset($this->data['registrationId'])?$this->data['registrationId']:'';
		$params['device_platform']=isset($this->data['device_platform'])?$this->data['device_platform']:'';
		$params['ip_address']=$_SERVER['REMOTE_ADDR'];
				
		$user_type='admin';
		if (!empty($this->data['token'])){
			if ( $info=merchantApp::getUserByToken($this->data['token'])){				
				$user_type=$info['user_type'];
				$params['merchant_id']=$info['merchant_id'];
				$params['user_type']=$user_type;
				if ($user_type=="user"){
				   	$params['merchant_user_id']=$info['merchant_user_id'];
				} else $params['merchant_user_id']=0;
			}
		}					
		if ( $res=merchantApp::getDeviceInfo($this->data['registrationId'])){
			$params['date_modified']=date('c');				
			$DbExt->updateData('{{mobile_device_merchant}}',$params,'id',$res['id']);
			$this->code=1;
			$this->msg="Updated";
		} else {
			$params['date_created']=date('c');
			$DbExt->insertData('{{mobile_device_merchant}}',$params);
			$this->code=1;
			$this->msg="OK";
		}
		$this->output(); 
    }
    
    public function actionStatusList()
    {    	    
    	if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
			    				    				 
			 if (!$order_info = Yii::app()->functions->getOrder($this->data['order_id'])){
			 	$this->msg=t("order records not found");
			 	$this->output(); 
			 }			    
			 
			 if ( $res=merchantApp::orderStatusList($this->data['merchant_id']) ) {  				 	
			 	$this->details=array(
			 	  'status'=>$order_info['status'],
			 	  'status_list'=>$res
			 	);
			 	$this->code=1;
			 	$this->msg="OK";
			 } else $this->msg=t("Status list not available");
        } else {
		    $this->code=3;
		    $this->msg=$this->t("you session has expired or someone login with your account");
		}    
		$this->output(); 
    }
    
	public function actionGetLanguageSelection()
	{
		if ($res=Yii::app()->functions->getLanguageList()){
			$set_lang_id=Yii::app()->functions->getOptionAdmin('set_lang_id');			
			if (preg_match("/-9999/i", $set_lang_id)) {
				$eng[]=array(
				  'lang_id'=>"en",
				  'country_code'=>"US",
				  'language_code'=>"English"
				);
				$res=array_merge($eng,$res);
			}						
			$this->code=1;
			$this->msg="OK";
			$this->details=$res;
		} else $this->msg=$this->t("no language available");
		$this->output();
	}    
	
	public function actionSaveSettings()
	{		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		  'merchant_device_id'=>t("mobile device id is empty please restart the app")
		);
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
				
			    $params=array(
			      'merchant_id'=>$this->data['mtid'],
				  'enabled_push'=>isset($this->data['enabled_push'])?1:2,
				  'date_modified'=>date('c'),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],			  
				);		
				
				$DbExt=new DbExt;
				if ( $resp=merchantApp::getDeviceInfo($this->data['merchant_device_id'])){					
					if ( $DbExt->updateData('{{mobile_device_merchant}}',$params,'id',$resp['id'])){
						$this->msg=$this->t("Setting saved");
						$this->code=1;
					} else $this->msg=$this->t("ERROR: Cannot update");
				} else $this->msg=$this->t("Device id not found please restart the app");
								
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();
	}
    
	public function actionGetSettings()
	{		
		if (isset($this->data['device_id'])){
			if ( $resp=merchantApp::getDeviceInfo($this->data['device_id'])){					
				$this->code=1;
				$this->msg="OK";
				$this->details=$resp;
			} else $this->msg=$this->t("Device id not found please restart the app");
		} else $this->msg=$this->t("Device id not found please restart the app");
		$this->output();
	}
	
	public function actiongeoDecodeAddress()
	{
	
		if (isset($this->data['address'])){
			if ($res=Yii::app()->functions->geodecodeAddress($this->data['address'])){
				$this->code=1;
				$this->msg="OK";
				$res['address']=$this->data['address'];
				$this->details=$res;
			} else $this->msg=t("Error: cannot view location");
		} else $this->msg=$this->t("address is required");
		$this->output();
	}
	
	public function actionOrderHistory()
	{
		if (!isset($this->data['order_id'])){
			$this->msg=$this->t("order is missing");
			$this->output();
		}	
		
		if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    	
			 
			 if ( $res=merchantApp::getOrderHistory($this->data['order_id'])){
			 	  $data='';
			 	  foreach ($res as $val) {
			 	  	$data[]=array(
			 	  	  'id'=>$val['id'],
			 	  	  'status'=>t($val['status']),
			 	  	  'status_raw'=>$val['status'],
			 	  	  'remarks'=>$val['remarks'],
			 	  	  'date_created'=>Yii::app()->functions->FormatDateTime($val['date_created'],true),
			 	  	  'ip_address'=>$val['ip_address']
			 	  	);
			 	  }
			 	  $this->code=1;
			 	  $this->msg="OK";
			 	  $this->details=array(
			 	    'order_id'=>$this->data['order_id'],
			 	    'data'=>$data
			 	  );
			 } else {
			 	$this->msg=$this->t("No history found");			    	
			 	$this->details=$this->data['order_id'];
			 }
         } else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
				$this->details=$this->data['order_id'];
		}
		$this->output();
	}
	
	public function actionsaveProfile()
	{
		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),
		  'password'=>$this->t("password is required"),
		  'cpassword'=>$this->t("confirm password is required")
		);
		
		if (isset($this->data['password']) && isset($this->data['cpassword'])){
			if ( $this->data['password']!=$this->data['cpassword']){
				$Validator->msg[]=$this->t("Confirm password does not match");
			}
		}
		
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){
					    
			    $params=array(
			      'password'=>md5($this->data['password']),
			      'date_modified'=>date('c'),
			      'ip_address'=>$_SERVER['REMOTE_ADDR']
			    );			    
			    
			    $DbExt=new DbExt;	
			    switch ($res['user_type']) {
			    	case "user":
			    		if ( $DbExt->updateData('{{merchant_user}}',$params,'merchant_user_id',$res['merchant_user_id'])){
			    			$this->code=1;
			    			$this->msg=$this->t("Profile saved");
			    		} else $this->msg=$this->t("ERROR: Cannot update profile");
			    		break;
			    			    	
			    	default:
			    		if ( $DbExt->updateData('{{merchant}}',$params,'merchant_id',$res['merchant_id'])){
			    			$this->code=1;
			    			$this->msg=$this->t("Profile saved");
			    		} else $this->msg=$this->t("ERROR: Cannot update profile");
			    		break;
			    }
			} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();	    	
	}
	
	public function actionGetProfile()
	{
		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
			    $this->code=1;
			    $this->msg="OK";
			    $this->details=$res;			    	
	    } else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();	 
	}
	
	public function actionGetLanguageSettings()
	{
		
		$mobile_dictionary=getOptionA('merchant_mobile_dictionary');
		$mobile_dictionary=!empty($mobile_dictionary)?json_decode($mobile_dictionary,true):false;
		if ( $mobile_dictionary!=false){
			$lang=$mobile_dictionary;
		} else $lang='';
		
		$mobile_default_lang='en';
		$default_language=getOptionA('default_language');
		if(!empty($default_language)){
			$mobile_default_lang=$default_language;
		}	
		
		if ( $mobile_default_lang=="en" || $mobile_default_lang=="-9999")
		{
			$this->details=array(
			  'settings'=>array(
			    //'default_lang'=>"ph"		    
			  ),
			  'translation'=>$lang
			);
		} else {
			$this->details=array(
			  'settings'=>array(
			    'default_lang'=>$mobile_default_lang		    
			  ),
			  'translation'=>$lang
			);
		}
		
		$this->code=1;
		$this->output();		
	}
	
	public function actiongetNotification()
	{
		
	    $Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
			   
			   if ( $resp=merchantApp::getMerchantNotification($res['merchant_id'],
			       $res['user_type'],$res['merchant_user_id'])){
			   		
			       	$data='';
			       	foreach ($resp as $val) {			       		
			       		$val['date_created']=Yii::app()->functions->FormatDateTime($val['date_created'],true);
			       		$data[]=$val;
			       	}
			       	
			       	$this->code=1;
			       	$this->msg="OK";
			       	$this->details=$data;
			       	
			    } else $this->msg=$this->t("no notifications");
			   
             } else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();	 			    	
	}
	
	public function actionsearchOrder()
	{
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
			   			    
			    if ( $resp=merchantApp::searchOrderByMerchantId(
			    $this->data['order_id_customername'] , $this->data['mtid'])){
			    	 
			    	$this->code=1; $this->msg="OK";					
					foreach ($resp as $val) {												
						$data[]=array(
						  'order_id'=>$val['order_id'],
						  'viewed'=>$val['viewed'],
						  'status'=>t($val['status']),
						  'status_raw'=>$val['status'],
						  'trans_type'=>t($val['trans_type']),
						  'trans_type_raw'=>$val['trans_type'],
						  'total_w_tax'=>$val['total_w_tax'],						  
						  'total_w_tax_prety'=>merchantApp::prettyPrice($val['total_w_tax']),
						  'transaction_date'=>Yii::app()->functions->FormatDateTime($val['date_created'],true),
						  'transaction_time'=>Yii::app()->functions->timeFormat($val['date_created'],true),
						  'delivery_time'=>Yii::app()->functions->timeFormat($val['delivery_time'],true),
						  'delivery_asap'=>$val['delivery_asap']==1?t("ASAP"):''
						);
					}					
					$this->code=1;
					$this->msg=$this->t("Search Results") ." (".count($data).") ".$this->t("Found records");
					$this->details=$data;
			    	 
			    } else $this->msg=$this->t("no results");
			   
             } else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();	 			 
	}
	
	public function actionPendingBooking()
	{
		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
 			    	
			    if ( $res=merchantApp::getPendingTables($this->data['mtid'])){
			    	$this->code=1;
			    	$this->msg="OK";
			    	$data='';
			    	foreach ($res as $val) {			    		
			    		$val['status_raw']=$val['status'];
			    		$val['status']=$this->t($val['status']);
			    		$val['date_of_booking']=Yii::app()->functions->FormatDateTime($val['date_booking'].
			    		" ".$val['booking_time'],true);
			    		$data[]=$val;
			    	}
			    	$this->details=$data;
			    } else $this->msg=$this->t("no pending booking");
			    
		     } else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();	 			 	    	
	}
	
	public function actionAllBooking()
	{
		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
 			    	
			    if ( $res=merchantApp::getAllBooking($this->data['mtid'])){
			    	$this->code=1;
			    	$this->msg="OK";
			    	$data='';
			    	foreach ($res as $val) {			    		
			    		$val['status_raw']=$val['status'];
			    		$val['status']=$this->t($val['status']);
			    		$val['date_of_booking']=Yii::app()->functions->FormatDateTime($val['date_booking'].
			    		" ".$val['booking_time'],true);
			    		$data[]=$val;
			    	}
			    	$this->details=$data;
			    } else $this->msg=$this->t("no current booking");
			    
		     } else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output();	 			 	    	
	}	
	
	public function actionGetBookingDetails()
	{
		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
			    				    	
			    if ( $res=merchantApp::getBookingDetails($this->data['mtid'],$this->data['booking_id'])){
			    	$res['status_raw']=$res['status'];
			    	$res['date_of_booking']=Yii::app()->functions->FormatDateTime($res['date_booking'].
			    		" ".$res['booking_time'],true);
			    		
			    	$res['transaction_date']=  Yii::app()->functions->FormatDateTime($res['date_created'],true);
			    	$res['date_booking']=  Yii::app()->functions->FormatDateTime($res['date_booking'],false);
			    		
			    	$this->code=1;
			    	$this->msg="OK";
			    	$this->details=array( 
			    	  'booking_id'=>$this->data['booking_id'],			    	  
			    	  'data'=>$res
			    	);
			    	
			    	$params=array(
			    	  'viewed'=>2
			    	);
			    	$DbExt=new DbExt; 
			    	$DbExt->updateData('{{bookingtable}}',$params,'booking_id',$this->data['booking_id']);
			    	
			    } else $this->msg=$this->t("booking details not available");
			    
		} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output(); 			    	
	}
	
	public function actionBookingChangeStats()
	{		
		/*$this->code=1;
		$this->msg="ok";
		$this->output(); 		
		Yii::app()->end();*/
		
		$Validator=new Validator;		
		$req=array(
		  'token'=>$this->t("token is required"),
		  'mtid'=>$this->t("merchant id is required"),
		  'user_type'=>$this->t("user type is required"),		  
		);
						
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			if ( $res=merchantApp::validateToken($this->data['mtid'],
			    $this->data['token'],$this->data['user_type'])){			    			    
			    				 
			   if ( $res=merchantApp::getBookingDetails($this->data['mtid'],$this->data['booking_id'])){   	
				   
				   $params=array(
				     'status'=>$this->data['status'],
				     'date_modified'=>date('c'),
				     'ip_address'=>$_SERVER['REMOTE_ADDR']
				   );
				   
				   $DbExt=new DbExt; 
			       if ($DbExt->updateData('{{bookingtable}}',$params,'booking_id',$this->data['booking_id'])){
			       	   $this->code=1;
			       	   $this->msg= $this->t("Booking id #").$this->data['booking_id'].
			       	   " ".$this->t($this->data['status']);
			       	   			       	   
			       	   switch ($this->data['status']) {
			       	   	case "approved":
			       	   		$subject=getOptionA('tpl_booking_approved_title');
			       	   		$content=getOptionA('tpl_booking_approved_content');
			       	   		break;
			       	   
			       	   	default:
			       	   		$subject=getOptionA('tpl_booking_denied_title');
			       	   		$content=getOptionA('tpl_booking_denied_content');
			       	   		break;
			       	   }
			       	   
			       	   if ( !empty($res['email'])){
				       	   $subject=smarty('merchant_name',$res['restaurant_name'],$subject);
				       	   $subject=smarty('booking_name',$res['booking_name'],$subject);
				       	   $subject=smarty('booking_date',
				       	   Yii::app()->functions->FormatDateTime($res['date_booking'],false),$subject);
				       	   $subject=smarty('booking_time',$res['booking_time'],$subject);
				       	   $subject=smarty('number_of_guest',$res['number_guest'],$subject);
				       	   $subject=smarty('booking_id',$res['booking_id'],$subject);
				       	   $subject=smarty('remarks',$this->data['remarks'],$subject);
				       	   
				       	   $content=smarty('merchant_name',$res['restaurant_name'],$content);
				       	   $content=smarty('booking_name',$res['booking_name'],$content);
				       	   $content=smarty('booking_date',
				       	   Yii::app()->functions->FormatDateTime($res['date_booking'],false),$content);
				       	   $content=smarty('booking_time',$res['booking_time'],$content);
				       	   $content=smarty('number_of_guest',$res['number_guest'],$content);
				       	   $content=smarty('booking_id',$res['booking_id'],$content);
				       	   $content=smarty('remarks',$this->data['remarks'],$content);
				       	   				       	   
				       	   if (!empty($subject) && !empty($content)){				       	   	
				       	   	   sendEmail( trim($res['email']),'',$subject,$content );
				       	   }
			       	   }
			       	   			       	   			       	   			       	   
			       } else $this->msg=t("ERROR: Cannot update");
			    	
			   } else $this->msg=$this->t("booking details not available");
			    
		} else {
				$this->code=3;
				$this->msg=$this->t("you session has expired or someone login with your account");
			}
		} else $this->msg=merchantApp::parseValidatorError($Validator->getError());	    	
		$this->output(); 			    	
	}
	
} /*end class*/
