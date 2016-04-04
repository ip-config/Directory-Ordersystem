<?php
class Ajax extends AjaxAdmin 
{
	
	public function commissionSettings()
	{		
		/*if (isset($this->data['admin_disabled_membership'])){
			if (!isset($this->data['admin_commission_enabled'])){
				$this->msg=t("Sorry but you cannot disabled membership if commision is disabled");
				return ;
			}
		}*/
		
		/*if (!isset($this->data['admin_commission_enabled']) && !isset($this->data['admin_disabled_membership'])){
			$this->msg=t("Sorry but you cannot disabled both membership and commision");
			return ;
		}*/
		
		Yii::app()->functions->updateOptionAdmin("admin_commission_enabled",
    	isset($this->data['admin_commission_enabled'])?$this->data['admin_commission_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_disabled_membership",
    	isset($this->data['admin_disabled_membership'])?$this->data['admin_disabled_membership']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_commision_percent",
    	isset($this->data['admin_commision_percent'])?$this->data['admin_commision_percent']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_vat_no",
    	isset($this->data['admin_vat_no'])?$this->data['admin_vat_no']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_vat_percent",
    	isset($this->data['admin_vat_percent'])?$this->data['admin_vat_percent']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("total_commission_status",
    	isset($this->data['total_commission_status'])?json_encode($this->data['total_commission_status']):'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_commision_ontop",
    	isset($this->data['admin_commision_ontop'])?$this->data['admin_commision_ontop']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_commision_type",
    	isset($this->data['admin_commision_type'])?$this->data['admin_commision_type']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_include_merchant_cod",
    	isset($this->data['admin_include_merchant_cod'])?$this->data['admin_include_merchant_cod']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_exclude_cod_balance",
    	isset($this->data['admin_exclude_cod_balance'])?$this->data['admin_exclude_cod_balance']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function merchantCommission()
	{
		 		    
		  
	    	$and='';  
	    	$and_date='';	    	
	    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
	    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
	    		$and=" AND a.date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
	    		        '".$this->data['end_date']." 23:59:00'
	    		 ";	    		
	    		$and_date=" AND date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
	    		        '".$this->data['end_date']." 23:59:00'
	    		 ";
	    		}
	    	}
	    	
	    	if ( $this->data['query']=="last15"){
		    	$start_date=date("Y-m-d", strtotime ('-15 days'));
				$end_date=date("Y-m-d");
				
				$and =" AND a.date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				$and_date =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
	    	} elseif ( $this->data['query']=="last30"){
	    		
	    		$start_date=date("Y-m-d", strtotime ('-30 days'));
				$end_date=date("Y-m-d");
				
				$and =" AND a.date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				$and_date =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
	    	} elseif ( $this->data['query']=="month"){
	    		
	    		$query_date = $this->data['query_date'];			
				$start_date=date('Y-m-01', strtotime($query_date));
				$end_date=date('Y-m-t', strtotime($query_date));
				
				$and =" AND a.date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				$and_date =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
	    	}
	    	
	    	$order_status_id='';
	    	$or='';
	    	if (isset($this->data['stats_id'])){
		    	if (is_array($this->data['stats_id']) && count($this->data['stats_id'])>=1){
		    		foreach ($this->data['stats_id'] as $stats_id) {		    			
		    			$order_status_id.="'$stats_id',";
		    		}
		    		if ( !empty($order_status_id)){
		    			$order_status_id=substr($order_status_id,0,-1);
		    		}		    	
		    	}	    
	    	}
	    	
	    	if ( !empty($order_status_id)){	    		
	    		$where= " WHERE a.status IN ($order_status_id)";
	    		$and_date.=" AND status IN ($order_status_id)";
	    	} else {
	    		$where= " WHERE a.status NOT IN ('".initialStatus()."')";
	    		$and_date.="AND status NOT IN ('".initialStatus()."')";
	    	}
	    		    	
	    	 	    	
	    	
	    	if ( $this->data['merchant_id']>=1){
	    		$and.=" AND a.merchant_id='".$this->data['merchant_id']."' ";
	    	}
	    	
	    	if (isset($this->data['payment_type'])){
	    		if ( $this->data['payment_type']==2){ // cash
	    			$and_date.=" AND payment_type IN ('cod','pyr','ccr','ocr') ";
	    			$and.=" AND payment_type IN ('cod','pyr','ccr','ocr') ";
	    		} else if ($this->data['payment_type']==3) { // card
	    			$and_date.=" AND payment_type NOT IN ('cod','pyr','ccr','ocr') ";
	    			$and.=" AND payment_type NOT IN ('cod','pyr','ccr','ocr') ";
	    		}	    	
	    	}	
	    	
	    	$DbExt=new DbExt;    	
	    	
	    	$stmt="SELECT a.*,b.is_commission,
	    	(
	    	select restaurant_name 
	    	from
	    	{{merchant}}
	    	where merchant_id = a.merchant_id 
	    	) as merchant_name,
	    	
	    	(
	    	select sum(total_w_tax) 
	    	from
	    	{{order}}
	    	where merchant_id = a.merchant_id 	  
	    	$and_date  		 
	    	) as total_order,
	    	
	    	(
	    	select sum(total_commission)
	    	from
	    	{{order}}
	    	where merchant_id = a.merchant_id 	 
	    	$and_date   		 
	    	) as total_commission
	    	
	    	FROM
	    	{{order}} a	  	    
	    	left join {{merchant}} b
			On
			a.merchant_id=b.merchant_id
	    	  	  
	    	$where
	    	$and	    	
	    	AND b.is_commission='2'
	    	
	    	GROUP BY merchant_id
	    	ORDER BY order_id DESC
	    	LIMIT 0,2000
	    	";
	    	
	    	if (isset($_GET['debug'])){
	    		dump($this->data);	    	
	    	    dump($stmt);
	    	}
	    	$_SESSION['kr_export_stmt']=$stmt;	    	
	    			    
	    	if ( $res=$DbExt->rst($stmt)){	 
	    		if (isset($_GET['debug'])){   		
	    		   dump($res);
	    		}
	    		
	    		$total_commission=0;
	    		foreach ($res as $val) {	    		
	    			$link=websiteUrl()."/admin/merchantcommissiondetails";	    			
	    			$link.="/mtid/".$val['merchant_id'];	 
	    			$link.="/where/".$where;	 
	    			$link.="/and/".$and;
/*$action="<a class=\"view-details\" data-id=\"$val[merchant_id]\" href=\"javascript:;\" data-where=\"$where\" data-and=\"$and\">".
Yii::t("default","Details").
"</a>";*/	    			

                    $total_commission+=$val['total_commission'];
$action="<a href=\"$link\" >".Yii::t("default","Details")."</a>";
	    			$date=prettyDate($val['date_created'],true);
	    			$date=Yii::app()->functions->translateDate($date);
	    				    				    				    			
	    			$feed_data['aaData'][]=array(
	    			  $val['merchant_id'],
	    			  $val['merchant_name'],
	    			  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_order'])),
	    			  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_commission'])),
	    			  $action	    			  
	    		    );
	    		}	    		
	    		
	    		$feed_data['total_commission']=displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_commission));
	    		$this->otableOutput($feed_data);
	    	}	   
	    	$this->otableNodata();					
	}
	
	public function merchantCommissionDetails()
	{
		$DbExt=new DbExt;		
		$where=$this->data['where'];
		$and=" AND merchant_id='".$this->data['mtid']."'";
		$and.=$this->data['and'];
		
				
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		$and.=" AND date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
    		        '".$this->data['end_date']." 23:59:00'
    		 ";
    		}
    	}
		
		$stmt="
		SELECT a.*,		
		(
    	select restaurant_name 
    	from
    	{{merchant}}
    	where merchant_id = a.merchant_id 
    	) as merchant_name    	
		FROM
		{{order}} a
		$where
		$and
		ORDER BY order_id DESC
		";
		
		if (isset($_GET['debug'])){
			dump($this->data);	    	
            dump($stmt);
	    }	    	
		$total_order=0;	
	    $total_commission=0;    	
	    
	    $_SESSION['kr_export_stmt']=$stmt;	    
	    	
		if ($res=$DbExt->rst($stmt)){
			foreach ($res as $val) {
				
				$total_order=$total_order+$val['total_order'];
	    		$total_commission=$total_commission+$val['total_commission'];
	    			
				/*$date=prettyDate($val['date_created'],true);
	    	    $date=Yii::app()->functions->translateDate($date);	    	    */
				$date=FormatDateTime($val['date_created']);
	    	    
	    	    $feed_data['total_commission']=displayPrice(adminCurrencySymbol(),
	    	    normalPrettyPrice($total_commission));
	    	    $feed_data['merchant_name']=ucwords($val['merchant_name']);
	    	    
	    	    if ( $val['commision_ontop']==1){
	    	    	$total_w_tax="<a  class=\"view-receipt\" data-id=\"$val[order_id]\" href=\"javascript:;\">".displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['sub_total']))."</a>";
	    	    } else {
	    	    	$total_w_tax="<a  class=\"view-receipt\" data-id=\"$val[order_id]\" href=\"javascript:;\">".displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_w_tax']))."</a>";
	    	    }	    	    
	    	    
	    	    $feed_data['aaData'][]=array(
					$val['order_id'],
					strtoupper($val['payment_type']),
					$total_w_tax,
					normalPrettyPrice($val['percent_commision']),
					displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_commission'])),
					$date
				);
			}	
			$this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function getPackageInformation()
	{				
		if (isset($this->data['package_id'])){
			if ( $this->data['package_id']==0 ){
				$this->code=3;
				return false;
			}
			if ( $res=Yii::app()->functions->getSMSPackagesById($this->data['package_id'])){
				$this->code=1;
				$this->msg=t("Successful");
				$this->details=$res['sms_limit'];
			} else $this->msg=t("Cannot find information");
		} else $this->msg=t("Missing parameters");
	}
	
	public function getCommissionTotal()
	{
		$total_com=displayPrice(adminCurrencySymbol(),
		normalPrettyPrice(Yii::app()->functions->getTotalCommission()));
		
		$total_today=displayPrice(adminCurrencySymbol(),
		normalPrettyPrice(Yii::app()->functions->getTotalCommissionToday()));
				
		$total_last=displayPrice(adminCurrencySymbol(),
		normalPrettyPrice(Yii::app()->functions->getTotalCommissionLast()));
		
		$commission=array(
		  'total_com'=>$total_com,
		  'total_today'=>$total_today,
		  'total_last'=>$total_last
		);
		$this->code=1;
		$this->msg="Ok";
		$this->details=$commission;
	}
	
	public function merchantSignUp2()
	{		
					
        /** check if admin has enabled the google captcha*/    	    	
    	if ( getOptionA('captcha_merchant_signup')==2){
    		if ( GoogleCaptcha::checkCredentials()){
    			if ( !GoogleCaptcha::validateCaptcha()){
    				$this->msg=GoogleCaptcha::$message;
    				return false;
    			}	    		
    		}	    	
    	} 
	    	
		$status=Yii::app()->functions->getOptionAdmin('merchant_sigup_status');			
		$token=md5($this->data['restaurant_name'].date('c'));
		
		$percent=Yii::app()->functions->getOptionAdmin('admin_commision_percent');
		
	    $params=array(
	      'restaurant_name'=>$this->data['restaurant_name'],
	      'restaurant_phone'=>$this->data['restaurant_phone'],
	      'contact_name'=>$this->data['contact_name'],
	      'contact_phone'=>$this->data['contact_phone'],
	      'contact_email'=>$this->data['contact_email'],
	      'street'=>$this->data['street'],
	      'city'=>$this->data['city'],
	      'post_code'=>$this->data['post_code'],
	      'cuisine'=>json_encode($this->data['cuisine']),
	      'username'=>$this->data['username'],
	      'password'=>md5($this->data['password']),
	      'status'=>$status,
	      'date_created'=>date('c'),
	      'ip_address'=>$_SERVER['REMOTE_ADDR'],
	      'activation_token'=>$token,
	      'activation_key'=>Yii::app()->functions->generateRandomKey(5),
	      'restaurant_slug'=>Yii::app()->functions->createSlug($this->data['restaurant_name']),	      
	      'payment_steps'=>3,	      
	      'country_code'=>$this->data['country_code'],
	      'state'=>$this->data['state'],
	      'is_commission'=>2,
	      'percent_commision'=>$percent,	      
	      'abn'=>isset($this->data['abn'])?$this->data['abn']:''
	    );			
	    
	    if ( !Yii::app()->functions->validateUsername($this->data['username']) ){
	    	
	    	if ($respck=Yii::app()->functions->validateMerchantUserFromMerchantUser($params['username'],
	    	    $params['contact_email'])){
	    		$this->msg=$respck;
	    		return ;		    		
	    	}		    
	    		    		    	
		    if ($this->insertData("{{merchant}}",$params)){
		    	$this->code=1;
		    	$this->msg=Yii::t("default","Successful");
		    	$this->details=$token;
		    	
		    	// send email activation key
		    	$tpl=EmailTPL::merchantActivationCode($params);
	            $sender=Yii::app()->functions->getOptionAdmin('website_contact_email');
	            $to=$this->data['contact_email'];		    		  
	            if (!sendEmail($to,$sender,"Merchant Registration",$tpl)){		    	
	            	//$this->details="failed";
	            } //else $this->details="ok mail";
	            			    				    				    	
		    } else $this->msg=Yii::t("default","Sorry but we cannot add your information. Please try again later");
	    } else $this->msg=Yii::t("default","Sorry but your username is alread been taken.");
	}
	
	public function getMerchantBalance()
	{
		$mtid=Yii::app()->functions->getMerchantID();	
		
		$balance=displayPrice(adminCurrencySymbol(),
		normalPrettyPrice(Yii::app()->functions->getMerchantBalance($mtid)));
			
		$this->details=$balance;
		$this->code=1;
		$this->msg="ok";
	}
	
	public function merchantStatement()
	{
		if (isset($_GET['debug'])){
		   dump($this->data);
		}
		$mtid=Yii::app()->functions->getMerchantID();
		
		$orderstats=Yii::app()->functions->getCommissionOrderStats();		
		
		if (isset($_GET['debug'])){
		   dump($orderstats);
		   dump($admin_commision_ontop);
		}
		
		$and='';
		if ( $this->data['query']=="month"){
			$query_date = $this->data['query_date'];			
			$start_date=date('Y-m-01', strtotime($query_date));
			$end_date=date('Y-m-t', strtotime($query_date));
			$and =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
	    		        '".$end_date." 23:59:00'
	    		 ";	    		
		} elseif ( $this->data['query']=="period"){
			
			$start_date=$this->data['start_date'];
			$end_date=$this->data['end_date'];
			
			$and =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
	    		        '".$end_date." 23:59:00'
	    		 ";	    		
		} elseif ( $this->data['query']=="last15"){
			
			$start_date=date("Y-m-d", strtotime ('-15 days'));
			$end_date=date("Y-m-d");
			
			$and =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
	    		        '".$end_date." 23:59:00'
	    		 ";	    		
		} elseif ( $this->data['query']=="last30"){
			
			$start_date=date("Y-m-d", strtotime ('-30 days'));
			$end_date=date("Y-m-d");
			
			$and =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
	    		        '".$end_date." 23:59:00'
	    		 ";	    		
		}	
		
		if (isset($this->data['payment_type'])){
			if ( $this->data['payment_type']==2){ //cash
				$trans_type="AND payment_type IN ('cod','pyr','ccr','ocr')";				
			} elseif ( $this->data['payment_type']==3){ //card			
				$trans_type="AND payment_type NOT IN ('cod','pyr','ccr','ocr')";	
			}		
		}
		/*$trans_type="AND payment_type NOT IN ('cod','pyr','ccr')";
		if (isset($this->data['cash_statement'])){
			$trans_type="AND payment_type IN ('cod','pyr','ccr')";
		}*/
		
	    $stmt="SELECT * FROM
	    {{order}}
	    WHERE
	    merchant_id=".Yii::app()->functions->q($mtid)."
	    AND status in ($orderstats)
	    $and	    
	    $trans_type
	    ORDER BY order_id DESC
	    ";
	    if (isset($_GET['debug'])){
	        dump($stmt);
	    }
	    
	    $_SESSION['kr_export_stmt']=$stmt;
	    
	    $total_amount=0;
	    $total_payable=0;
	    if ( $res=$this->rst($stmt)){
	    	foreach ($res as $val) {	    			    		
	    		//$date=prettyDate($val['date_created']);
	    		/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));
			    $date=Yii::app()->functions->translateDate($date);*/					
	    		$date=FormatDateTime($val['date_created']);
			    
			    $total=$val['total_w_tax'];
			    if ( $val['commision_ontop']==1){
			    	$total=$val['sub_total'];
			    }
			    
			    $total_commission=$val['total_commission'];
			    $amount=$total-$total_commission;
			    
			    $link="<a href=\"javascript:;\" class=\"view-receipt\"  data-id=\"$val[order_id]\"  >".
			    displayPrice(adminCurrencySymbol(),normalPrettyPrice($total))."</a>";
			    			    
	    		$feed_data['aaData'][]=array(
	    		    $val['order_id'],
	    		    strtoupper($val['payment_type']),
	    		    $link,	    		    
	    		    normalPrettyPrice($val['percent_commision']),
	    		    displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_commission)),
	    		    displayPrice(adminCurrencySymbol(),normalPrettyPrice($amount)),
	    		    $date
	    		);	    		
	    			    		
	    		$total_amount+=$amount;
	    		$total_payable+=$total_commission;
	    	}
	    	
	    	$feed_data['total_amount']=displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_amount));
	    	$feed_data['total_payable']=displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_payable));
	    	$this->otableOutput($feed_data);
	    } 
	    $this->otableNodata();
	}
	
	public function removeNotice()
	{
		$mtid=Yii::app()->functions->getMerchantID();		
		Yii::app()->functions->updateOption("merchant_read_notice","1",$mtid);
		$this->code=1;
	}
	
	public function ingredientsList()
	{
	    $slug=$this->data['slug'];
        $stmt="
		SELECT * FROM
		{{ingredients}}
		WHERE			
		merchant_id='".Yii::app()->functions->getMerchantID()."'
		ORDER BY ingredients_id  DESC
		";
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	     	    		
	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[ingredients_id]\" class=\"chk_child\" >";   		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[ingredients_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[ingredients_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
	    		/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));  
	    		$date=Yii::app()->functions->translateDate($date);*/
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		$feed_data['aaData'][]=array(
	    		  $chk,$val['ingredients_name'].$option,
	    		  $date."<div>".$val['status']."</div>"
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function AddIngredients()
	{
	  $params=array(
		  'ingredients_name'=>$this->data['ingredients_name'],
		  'status'=>addslashes($this->data['status']),
		  'date_created'=>date('c'),
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'merchant_id'=>Yii::app()->functions->getMerchantID()
		);								

		if (isset($this->data['ingredients_name_trans'])){
			if (okToDecode()){
				$params['ingredients_name_trans']=json_encode($this->data['ingredients_name_trans'],
				JSON_UNESCAPED_UNICODE);
			} else $params['ingredients_name_trans']=json_encode($this->data['ingredients_name_trans']);
		}
			
		$command = Yii::app()->db->createCommand();
		if (isset($this->data['id']) && is_numeric($this->data['id'])){				
			unset($params['date_created']);
			$params['date_modified']=date('c');				
			$res = $command->update('{{ingredients}}' , $params , 
			'ingredients_id=:ingredients_id' , array(':ingredients_id'=>addslashes($this->data['id'])));
			if ($res){
				$this->code=1;
                $this->msg=Yii::t("default",'ingredients updated');  
			} else $this->msg=Yii::t("default","ERROR: cannot update");
		} else {				
			if ($res=$command->insert('{{ingredients}}',$params)){
				$this->details=Yii::app()->db->getLastInsertID();	
                $this->code=1;
                $this->msg=Yii::t("default",'ingredients added'); 
            } else $this->msg=Yii::t("default",'ERROR. cannot insert data.');
		}	    		  
	}
	
	public function withdrawalSettings()
	{
		/*Yii::app()->functions->updateOptionAdmin("wd_minimum_amount",
	    isset($this->data['wd_minimum_amount'])?$this->data['wd_minimum_amount']:'');*/
		
		Yii::app()->functions->updateOptionAdmin("wd_paypal_minimum",
	    isset($this->data['wd_paypal_minimum'])?$this->data['wd_paypal_minimum']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_bank_minimum",
	    isset($this->data['wd_bank_minimum'])?$this->data['wd_bank_minimum']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_days_process",
	    isset($this->data['wd_days_process'])?$this->data['wd_days_process']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal",
	    isset($this->data['wd_paypal'])?$this->data['wd_paypal']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode",
	    isset($this->data['wd_paypal_mode'])?$this->data['wd_paypal_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode_user",
	    isset($this->data['wd_paypal_mode_user'])?$this->data['wd_paypal_mode_user']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode_pass",
	    isset($this->data['wd_paypal_mode_pass'])?$this->data['wd_paypal_mode_pass']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode_signature",
	    isset($this->data['wd_paypal_mode_signature'])?$this->data['wd_paypal_mode_signature']:'');
	    
	    /*Yii::app()->functions->updateOptionAdmin("wd_paypal_client_id",
	    isset($this->data['wd_paypal_client_id'])?$this->data['wd_paypal_client_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_client_secret",
	    isset($this->data['wd_paypal_client_secret'])?$this->data['wd_paypal_client_secret']:'');*/
	    
	    Yii::app()->functions->updateOptionAdmin("wd_bank_deposit",
	    isset($this->data['wd_bank_deposit'])?$this->data['wd_bank_deposit']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_payout",
	    isset($this->data['wd_template_payout'])?$this->data['wd_template_payout']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_process",
	    isset($this->data['wd_template_process'])?$this->data['wd_template_process']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_enabled_paypal",
	    isset($this->data['wd_enabled_paypal'])?$this->data['wd_enabled_paypal']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_payout_disabled",
	    isset($this->data['wd_payout_disabled'])?$this->data['wd_payout_disabled']:'');
	    	    
	    Yii::app()->functions->updateOptionAdmin("wd_payout_notification",
	    isset($this->data['wd_payout_notification'])?$this->data['wd_payout_notification']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_payout_subject",
	    isset($this->data['wd_template_payout_subject'])?$this->data['wd_template_payout_subject']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_process_subject",
	    isset($this->data['wd_template_process_subject'])?$this->data['wd_template_process_subject']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_bank_fields",
	    isset($this->data['wd_bank_fields'])?$this->data['wd_bank_fields']:'');
	    
	    $this->code=1;
	    $this->msg=t("Successful");
	}
	
	public function requestPayout()
	{
		
		$mtid=Yii::app()->functions->getMerchantID();
		
 		$wd_paypal_minimum=yii::app()->functions->getOptionAdmin('wd_paypal_minimum');
        $wd_bank_minimum=yii::app()->functions->getOptionAdmin('wd_bank_minimum');
        
        $wd_paypal_minimum=standardPrettyFormat($wd_paypal_minimum);
        $wd_bank_minimum=standardPrettyFormat($wd_bank_minimum);
        
        $current_balance=Yii::app()->functions->getMerchantBalance($mtid);
        $this->data['current_balance']=$current_balance;

		$validator=new Validator;
		
		$req=array(
		  'payment_type'=>t("Payment type is required"),
		  'payment_method'=>t("Payment Method is required"),
		);								
		
		if ( $this->data['payment_method']=="paypal"){
			$req2=array(
			 'account'=>t("Email account not valid"),	 
			 'account_confirm'=>t("Confirm email account not valid"),
			);
			$validator->email($req2,$this->data);

			$req['amount']=t("Amount is required");

			if ( $this->data['account']!=$this->data['account_confirm']){
				$validator->msg[]=t("Confirm email does not match");
			}
											    		   
		} elseif ( $this->data['payment_method']=="bank" ){
			
		}				
		
		if ( $this->data['payment_type']=="single"){
			if ( $this->data['minimum_amount']>$this->data['amount']){
			   $validator->msg[]=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$this->data['minimum_amount']);
			}				
			if ( $current_balance<$this->data['amount']){		    	
		       $validator->msg[]=t("Amount is greater than your balance");
		    }
		} elseif ( $this->data['payment_type']=="all-earnings"){						
			$this->data['amount']=$current_balance;
			if ( $this->data['minimum_amount']>$this->data['amount']){
			   $validator->msg[]=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$this->data['minimum_amount']);
			}				
			if ( $this->data['minimum_amount']>$current_balance){
			   $validator->msg[]=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$this->data['minimum_amount']);
			}		
		} else {
			
		}
												
		$validator->required($req,$this->data);
											
		if ( $validator->validate()){
			if ( $this->data['payment_type']=="single"){															
				/*if  ( $wd_paypal_minimum>$this->data['amount']){
					$this->msg=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$wd_paypal_minimum);
				} else {*/
					$resp=Yii::app()->functions->payoutRequest($this->data['payment_method'],$this->data);
					if ($resp){
						$this->details=$resp['id'];
						$this->code=1;
						$this->msg=t("Successful");
																	
					} else $this->msg=t("ERROR: Something went wrong");
				//}			
			} else {
				//echo 'all earning';
				$resp=Yii::app()->functions->payoutRequest($this->data['payment_method'],$this->data);
				if ($resp){
					$this->details=$resp['id'];
					$this->code=1;
					$this->msg=t("Successful");											
			   } else $this->msg=t("ERROR: Something went wrong");
			}
		} else $this->msg=$validator->getErrorAsHTML();
				
		if ( $this->code==1){
			
			/*update all orders paid status to locked*/		
		    FunctionsK::updateAllPaidOrders($mtid);
			
			if ( isset($this->data['default_account_paypal'])){
			   if ( $this->data['default_account_paypal']==2){
			   	    Yii::app()->functions->updateOption("merchant_payout_account",
    	            isset($this->data['account'])?$this->data['account']:'',$mtid);
			   }
            }			
            
            if ( isset($this->data['default_account_bank'])){
            	if ( $this->data['default_account_bank']==2){
            		$bank_info=array(
            		  'swift_code'=>isset($this->data['swift_code'])?$this->data['swift_code']:'',
            		  'bank_account_number'=>isset($this->data['bank_account_number'])?$this->data['bank_account_number']:'' ,
            		  'account_name'=>isset($this->data['account_name'])?$this->data['account_name']:'',
            		  'bank_account_number'=>isset($this->data['bank_account_number'])?$this->data['bank_account_number']:'',
            		  'swift_code'=>isset($this->data['swift_code'])?$this->data['swift_code']:'',
            		  'bank_name'=>isset($this->data['bank_name'])?$this->data['bank_name']:'',
            		  'bank_branch'=>isset($this->data['bank_branch'])?$this->data['bank_branch']:''
            		);
            		Yii::app()->functions->updateOption("merchant_payout_bank_account",
            		json_encode($bank_info),$mtid);
            	}            
            }		
            
            // send email
            $merchant_email='';
			$tpl='';
			
			$wd_days_process=Yii::app()->functions->getOptionAdmin("wd_days_process");		
			if (empty($wd_days_process)){
    		    $wd_days_process=5;
    	    }
			$cancel_date=$wd_days_process-2;
	        $cancel_date=date("F d Y", strtotime (" +$cancel_date days"));
	        $process_date=date("F d Y", strtotime (" +$wd_days_process days"));
	        
			if ( $merchant_info=Yii::app()->functions->getMerchant($mtid)){			
				$merchant_email=$merchant_info['contact_email'];
				$cancel_link=websiteUrl()."/store/cancelwithdrawal/id/".$resp['token'];
				$tpl=yii::app()->functions->getOptionAdmin('wd_template_payout');
			    $tpl=smarty("merchant-name",$merchant_email['restaurant_name'],$tpl);
			    $tpl=smarty("payout-amount",standardPrettyFormat($this->data['amount']),$tpl);
			    $tpl=smarty("payment-method",$this->data['payment_method'],$tpl);
			    $tpl=smarty("account",$this->data['account'],$tpl);
			    $tpl=smarty("cancel-date",$cancel_date,$tpl);
			    $tpl=smarty("cancel-link",$cancel_link,$tpl);
			    $tpl=smarty("process-date",$process_date,$tpl);
			}		
										
			if (!empty($tpl)){
				$wd_template_payout_subject=yii::app()->functions->getOptionAdmin('wd_template_payout_subject');
                if (empty($wd_template_payout_subject)){
	                $wd_template_payout_subject=t("Your Request for Withdrawal was Received");
                }                
				sendEmail($merchant_email,'',$wd_template_payout_subject,$tpl);
			}            
		} 		
	}	

	public function incomingWithdrawals()
	{		
		$show_action=true;
		$and="WHERE status IN ('pending')";		
		if (isset($this->data['w-list'])){
			switch ($this->data['w-list']) {
				case "cancel":
					$and="WHERE status IN ('cancel')";
					$show_action=false;
					break;
				case "reversal":
					$and="WHERE status IN ('reversal')";
					$show_action=false;
					break;
				case "paid":					
					$and="WHERE status IN ('paid')";
					$show_action=true;
					break;
				case "denied":					
					$and="WHERE status IN ('denied')";
					$show_action=false;
					break;
				case "failed":	
				    $and="WHERE status NOT IN ('paid','pending','denied','approved','reversal')";
				    $show_action=false;
				    break;
				case "approved":    
				    $and="WHERE status IN ('approved')";
					$show_action=false;
					break;
				case "all":    
				    $and="";
				    $show_action=false;
				    break;
				default:
					break;
			}
		}
		
		if (isset($this->data['start_date']) && isset($this->data['end_date'])){
			if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
				if (!empty($and)){				
					$and.=" AND date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
	    		        '".$this->data['end_date']." 23:59:00'
	    		    ";
				} else {
					$and.=" WHERE date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
	    		        '".$this->data['end_date']." 23:59:00'
	    		    ";
				}
			}
		}	
		
		if (isset($this->data['merchant_id'])){
			if (!empty($this->data['merchant_id'])){
				if (!empty($and)){
					$and.=" AND merchant_id='".$this->data['merchant_id']."'";
				} else {
					$and=" WHERE merchant_id='".$this->data['merchant_id']."'";
				}
			}
		}	
		
		$slug=$this->data['slug'];
        $stmt="
		SELECT a.*,
		(
		select restaurant_name 
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		 FROM
		{{withdrawal}} a		
		$and
		ORDER BY withdrawal_id DESC
		";
        
        if (isset($_GET['debug'])){
           dump($this->data);
           dump($stmt);
        }
        
        $_SESSION['kr_export_stmt']=$stmt;
        
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	 
	    		$method=t("Paypal to")." ".$val['account'];
	    		if ( $val['payment_method']=="bank"){
	    			$method=t("Bank to")." ".$val['bank_account_number'];
	    		}	    	
	    		
	    		if ( $this->data['w-list']=="paid"){
	    		$action="<a href=\"javascript:;\" class=\"payout_action\" data-id=\"$val[withdrawal_id]\" data-status=\"reversal\">".t("Apply reversal")."</a><br/>";		    		
	    		} else {
	    		$action="<a href=\"javascript:;\" class=\"payout_action\" data-id=\"$val[withdrawal_id]\" data-status=\"approved\">".t("approved")."</a><br/>";
	    		$action.="<a href=\"javascript:;\" class=\"payout_action\" data-id=\"$val[withdrawal_id]\" data-status=\"denied\">".t("denied")."</a>";
	    		}
	    		
	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[cook_id]\" class=\"chk_child\" >";   		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[cook_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[cook_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
	    		/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));  
	    		$date=Yii::app()->functions->translateDate($date);*/
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		/*$date_created=displayDate($val['date_created']);
	    		$date_to_process=displayDate($val['date_to_process']);*/
	    		$date_created=FormatDateTime($val['date_created'],false);
	    		$date_to_process=FormatDateTime($val['date_to_process'],false);
	    		
	    		$bank_info='';
	    		if ( $val['payment_method']=="bank"){
	    			$bank_info="<br/><a href=\"javascript:;\" data-id=\"$val[withdrawal_id]\" class=\"view-bank-info\">".t("View bank info")."</a>";
	    		}	    	
	    		
	    		$feed_data['aaData'][]=array(
	    		  $val['withdrawal_id'],
	    		  $val['merchant_name'],
	    		  $method.$bank_info,
	    		  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['amount'])),
	    		  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['current_balance'])),
	    		  "<span class=\"uk-badge withdrawal-status\">".t($val['status'])."</span>",
	    		  $date_created,
	    		  $date_to_process,	    		  
	    		  $show_action==true?$action:''
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function payoutChangeStatus()
	{	    
	    $validator=new Validator;
	    $req=array(
	     'withdrawal_id'=>t("withdrawal id is required"),
	     'status'=>t("Status is required"),
	    );
	    $validator->required($req,$this->data);
	    if ( $validator->validate()){
	    	$params=array(
	    	  'status'=>$this->data['status'],
	    	  'viewed'=>2
	    	);	    	
	    	$DbExt=new DbExt;
	    	if ( $DbExt->updateData("{{withdrawal}}",$params,'withdrawal_id',$this->data['withdrawal_id'])){
	    		$this->code=1;
	    		$this->msg=t("Successful");
	    	} else $this->msg=t("Failed cannot update records");	    
	    } else $this->msg=$validator->getErrorAsHTML();
	}
	
	public function wdPayoutNotification()
	{	
		$DbExt=new DbExt;
		$stmt="SELECT count(*) as total
		 FROM
		{{withdrawal}}
		WHERE
		status ='pending'
		AND
		viewed='1'
		";
		if ( $res=$DbExt->rst($stmt)){			
			if ( $res[0]['total']>=1){
				$this->code=1;
				$msg=t("There are")." (".$res[0]['total'].") ".t("withdrawals waiting for your approval");
				$this->msg=$msg."<br/><a class=\"white-link\" href=\"".websiteUrl()."/admin/incomingwithdrawal\">".t("Click here to view")."</a>";
				$this->details=$res[0]['total'];
			} else $this->msg="no results";
		} else $this->msg="no results";
	}
	
	public function cancelWithdrawal()
	{
		if ( $res=Yii::app()->functions->getWithdrawalInformationByToken($this->data['id'])){			
			if ($res['status']=="cancel"){				
				$this->msg=t("This withdrawal request has been already cancel");
				return ;
			}		
		}
		
		$DbExt=new DbExt;
		if (isset($this->data['id'])){
			$params=array(
			  'status'=>'cancel',
			  'date_process'=>date('c'),
			  'ip_address'=>date('c')			
			);
			if ($DbExt->updateData("{{withdrawal}}",$params,'withdrawal_token',$this->data['id'])){
				$this->code=1;
				$this->msg=t("Your withdrawal has been cancel");
			} else $this->msg=t("Error cannot cancel withdrawal please contact site admin");
		} else $this->msg=t("Missing id");
	}
	
	public function rptMerchantSalesSummaryReport()
	{
		if(isset($_GET['debug'])){
			dump($this->data);
		}		
		
		unset($_SESSION['rpt_date_range']);
	
		$and='';  
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		   $and=" AND date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
    		        '".$this->data['end_date']." 23:59:00'
    		   ";
    		   $_SESSION['rpt_date_range']=array(
    		     'start_date'=>$this->data['start_date'],
    		     'end_date'=>$this->data['end_date']
    		   );
    		}
    	}
    	
    	$order_status_id='';
	    $or='';
    	if (isset($this->data['stats_id'])){
	    	if (is_array($this->data['stats_id']) && count($this->data['stats_id'])>=1){
	    		foreach ($this->data['stats_id'] as $stats_id) {		    			
	    			$order_status_id.="'$stats_id',";
	    		}
	    		if ( !empty($order_status_id)){
	    			$order_status_id=substr($order_status_id,0,-1);
	    		}		    	
	    	}	    
    	}
    	
    	if ( !empty($order_status_id)){	    		
    		$and.= " AND status IN ($order_status_id)";
    	}	    	 
    	
    	$where='';
    	if (isset($this->data['merchant_id'])){
    		if (!empty($this->data['merchant_id'])){
    			$where=" WHERE merchant_id=".Yii::app()->functions->q($this->data['merchant_id'])." ";
    		}    	
    	}	
    	
		$slug=$this->data['slug'];
        $stmt="
		SELECT a.restaurant_name,
		(
		select sum(total_w_tax)as total
		from 
		{{order}}
		where
		merchant_id=a.merchant_id
		$and
		) as total_sales,
		
		(
		select sum(total_commission)
		from
		{{order}}
		where
		merchant_id=a.merchant_id
		$and
		) as total_commission,
		
		(
		select sum(merchant_earnings)
		from
		{{order}}
		where
		merchant_id=a.merchant_id
		$and
		) as total_earnings
		
		
		FROM
		{{merchant}} a
		$where
		ORDER BY restaurant_name ASC
		";
        if(isset($_GET['debug'])){
        	dump($stmt);
        }
        
       /* (
		select sum(number_guest)
		from
		{{bookingtable}}
		where
		merchant_id=a.merchant_id
		and status='approved'		
		) as total_guest*/
		
        
        $_SESSION['kr_export_stmt']=$stmt;
        
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	     	    			    		
	    		if(isset($_GET['debug'])){
	    		   dump($val);
	    		}
	    		$feed_data['aaData'][]=array(	    		  
	    		   $val['restaurant_name'],
	    		   displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_sales']+0)),
	    		   displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_commission']+0)),
	    		   displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_earnings']+0)),
	    		   //$val['total_guest']
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function testEmail()
	{
		require_once 'test-email.php';
		die();
	}
	
	public function sendTestEmail()
	{		
		if (isset($this->data['email'])){
			$content="This is a test email";
			if ( Yii::app()->functions->sendEmail($this->data['email'],'',$content,$content)){
				$this->code=1;
				$this->msg=t("Successful");
			} else $this->msg=t("Sending of email has failed");		
		}  else $this->msg=t("Email is required");
	}
	
	public function FaxSettings()
	{	
		Yii::app()->functions->updateOptionAdmin("fax_company",
    	isset($this->data['fax_company'])?$this->data['fax_company']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_username",
    	isset($this->data['fax_username'])?$this->data['fax_username']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_password",
    	isset($this->data['fax_password'])?$this->data['fax_password']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_enabled",
    	isset($this->data['fax_enabled'])?$this->data['fax_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_user_admin_credit",
    	isset($this->data['fax_user_admin_credit'])?$this->data['fax_user_admin_credit']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_email_notification",
    	isset($this->data['fax_email_notification'])?$this->data['fax_email_notification']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
		
	public function FaxpackagesList()
	{
	    $slug=$this->data['slug'];
		$stmt="SELECT * FROM
		{{fax_package}}			
		ORDER BY fax_package_id DESC
		";
		if ( $res=$this->rst($stmt)){
			foreach ($res as $val) {	
				/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));  				
				$date=Yii::app()->functions->translateDate($date);*/
				$date=FormatDateTime($val['date_created']);
				$action="<div class=\"options\">
	    		<a href=\"$slug/id/$val[fax_package_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[fax_package_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
				$val['title']=ucwords($val['title']);
				$feed_data['aaData'][]=array(
				  $val['fax_package_id'],
				  $val['title'].$action,
				  Yii::app()->functions->limitDescription($val['description']),
				  Yii::app()->functions->standardPrettyFormat($val['price']),
				  Yii::app()->functions->standardPrettyFormat($val['promo_price']),
				  $val['fax_limit'],
				  $date."<div>".Yii::t("default",$val['status'])."</div>"					  
				);
			}
			$this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function FaxPackageAdd()
	{		   
       $params=array(
         'title'=>$this->data['title'],
         'description'=>$this->data['description'],
         'price'=>$this->data['price'],
         'promo_price'=>$this->data['promo_price'],
         'fax_limit'=>$this->data['fax_limit'],
         'status'=>$this->data['status'],
         'date_created'=>date('c'),
         'ip_address'=>$_SERVER['REMOTE_ADDR']
       );	       
       if (empty($this->data['id'])){	
	    	if ( $this->insertData("{{fax_package}}",$params)){
	    		$this->details=Yii::app()->db->getLastInsertID();
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");		    		
	    	}
	    } else {		    	
	    	unset($params['date_created']);
			$params['date_modified']=date('c');				
			$res = $this->updateData('{{fax_package}}' , $params ,'fax_package_id',$this->data['id']);
			if ($res){
				$this->code=1;
                $this->msg=Yii::t("default",'Package updated.');  
			} else $this->msg=Yii::t("default","ERROR: cannot update");
	    }			
	}	
	
	public function FaxMerchantSettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("fax_merchant_recipient",
    	isset($this->data['fax_merchant_recipient'])?$this->data['fax_merchant_recipient']:''
    	,$merchant_id);
    	
	    Yii::app()->functions->updateOption("fax_merchant_number",
    	isset($this->data['fax_merchant_number'])?$this->data['fax_merchant_number']:''
    	,$merchant_id);
    	
    	Yii::app()->functions->updateOption("fax_merchant_enabled",
    	isset($this->data['fax_merchant_enabled'])?$this->data['fax_merchant_enabled']:''
    	,$merchant_id);
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function initpaymentprovider()
	{
		$params="?method=".$this->data['payment_opt'];
		$params.="&purchase=".$this->data['purchase'];		
		$params.="&return_url=".$this->data['return_url'];
				
		$FunctionsK=new FunctionsK;		
		$merchantinfo=Yii::app()->functions->getMerchantInfo();
				
		switch ($this->data['purchase']) {
			case "fax_package":	
					
			    $resp=$FunctionsK->getFaxPackagesById($this->data['fax_package_id']);			    
			    if (!$resp){
			    	$this->msg=t("Package information not found");
			    	return ;
			    }		
			    
			    if ($resp['promo_price']>=1){
					$package_price.=$resp['promo_price'];
			    } else $package_price=$resp['price'];
			   
			    $credit=$resp['fax_limit'];
			    
			    if ($this->data['payment_opt']=="pyp" || $this->data['payment_opt']=="stp"){
					$params.="&package_id=".$this->data['fax_package_id'];				
					//if ( $resp=$FunctionsK->getFaxPackagesById($this->data['fax_package_id'])){					
					if ($resp){
						$params2='';
						if ($resp['promo_price']>=1){
							$params2.="&price=".$resp['promo_price'];
						} else $params2.="&price=".$resp['price'];
							
						$params2.="&description=".urlencode($resp['title']);		
						$params.="&raw=".base64_encode($params2);
												
						$this->code=1;
						$this->msg=t("Please wait while we redirect you");
						$this->details=websiteUrl()."/merchant/pay/$params";
					} else $this->msg=t("Package information not found");
					
			    } elseif ( $this->data['payment_opt']=="obd"){
			    	
			    	//$merchantinfo=Yii::app()->functions->getMerchantInfo();			    	
			    	if ( is_array($merchantinfo) && count($merchantinfo)>=1){
			    		$merchant_email=$merchantinfo[0]->contact_email;
			    		$ref="FAX_".Yii::app()->functions->generateRandomKey(8);
			 			    		
			    		$params_insert=array(
			    		 'merchant_id'=>Yii::app()->functions->getMerchantID(),
			    		 'fax_package_id'=>$this->data['fax_package_id'],
			    		 'payment_type'=>Yii::app()->functions->paymentCode("bankdeposit"),
			    		 'package_price'=>$package_price,
			    		 'fax_limit'=>$credit,
			    		 'payment_reference'=>$ref,
			    		 'date_created'=>date('c'),
			    		 'ip_address'=>$_SERVER['REMOTE_ADDR']
			    		);		
			    		$bank_info=Yii::app()->functions->getBankDepositInstruction();			    		
			    		$tpl=$bank_info['content'];
			    		$tpl=smarty('amount',displayPrice(baseCurrency(),standardPrettyFormat($package_price)),$tpl);
			    		$tpl=smarty('verify-payment-link',
			    		websiteUrl()."/merchant/faxbankdepositverification/?ref=$ref",$tpl);
			    		
			    		if (sendEmail($merchant_email,$bank_info['sender'],$bank_info['subject'],$tpl)){
			    			if ( $this->insertData("{{fax_package_trans}}",$params_insert)){
			    				$this->details=websiteUrl()."/merchant/faxreceipt/id/".Yii::app()->db->getLastInsertID();
			    				$this->code=1;
$this->msg=t("We have sent bank information instruction to your email")." :$merchant_email";


                                //$FunctionsK=new FunctionsK();
                                $FunctionsK->faxSendNotification((array)$merchantinfo[0],
                                                   $this->data['fax_package_id'],
                                                   Yii::app()->functions->paymentCode("bankdeposit"),
                                                   $package_price);
                                
			    			} else $this->msg=t("Error cannot saved information");
			    		} else $this->msg=t("Failed cannot send bank payment instructions");
			    	} else $this->msg=t("Something went wrong merchant information is empty");
			    } else {
			    	if ($package_price==0){
			    		// Free package
			    		$params_insert=array(
			    		 'merchant_id'=>Yii::app()->functions->getMerchantID(),
			    		 'fax_package_id'=>$this->data['fax_package_id'],
			    		 'payment_type'=>'Free',
			    		 'package_price'=>$package_price,
			    		 'fax_limit'=>$credit,
			    		 'payment_reference'=>'',
			    		 'date_created'=>date('c'),
			    		 'ip_address'=>$_SERVER['REMOTE_ADDR'],
			    		 'status'=>"paid"
			    		);					    		
			    		if ( $this->insertData("{{fax_package_trans}}",$params_insert)){
			    			$this->details=websiteUrl()."/merchant/faxreceipt/id/".Yii::app()->db->getLastInsertID();
			    			$this->code=1;
                            $this->msg=t("Successful");                            
                            $FunctionsK->faxSendNotification((array)$merchantinfo[0],
                                                   $this->data['fax_package_id'],
                                                   "Free",
                                                   $package_price);
			    		} else $this->msg=t("Error cannot saved information");
			    	} else $this->msg=t("No payment options has been selected");
			    }
				break;
		
			default:
				$this->msg=t("No found instructions");
				break;
		}
	}
	
	public function paymentPaypalVerification()
	{		
		$raw=base64_decode(isset($this->data['raw'])?$this->data['raw']:'');
		parse_str($raw,$raw_decode);				
						
		$price='';
		$description='';
		if (is_array($raw_decode) && count($raw_decode)>=1){
			$price=isset($raw_decode['price'])?$raw_decode['price']:'';
			$description=isset($raw_decode['description'])?$raw_decode['description']:'';
		}
		
		$paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();   			
        $paypal=new Paypal($paypal_con);
		
		if ($res_paypal=$paypal->getExpressDetail()){	            	
							
			$paypal->params['PAYERID']=$res_paypal['PAYERID'];
            $paypal->params['AMT']=$res_paypal['AMT'];
            $paypal->params['TOKEN']=$res_paypal['TOKEN'];
            $paypal->params['CURRENCYCODE']=$res_paypal['CURRENCYCODE'];	            	           
		            
            if ($res=$paypal->expressCheckout()){ 
            	            
        	    /*now insert transaction logs*/
				if ( $this->data['purchase']=="fax_package"){
					$payment_code=Yii::app()->functions->paymentCode("paypal");
					
					$FunctionsK=new FunctionsK;
					$info=$FunctionsK->getFaxPackagesById($this->data['package_id']);
										
	                $params=array(
    			      'merchant_id'=>Yii::app()->functions->getMerchantID(),
	    			  'fax_package_id'=>$this->data['package_id'],
	    			  'payment_type'=>$payment_code,
	    			  'package_price'=>$price,
	    			  'fax_limit'=>$info['fax_limit'],
	    			  'date_created'=>date('c'),
	    			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    			  'payment_gateway_response'=>json_encode($res),
	    			  'status'=>"paid"
	    			);	 
	    				    				    		
	    			if ( $this->insertData("{{fax_package_trans}}",$params)){
					   $this->details=websiteUrl()."/merchant/faxreceipt/id/".Yii::app()->db->getLastInsertID();
					   $this->code=1;
					   $this->msg=Yii::t("default","Successful");	

					   
					   $merchantinfo=Yii::app()->functions->getMerchantInfo();			    	
					   $FunctionsK=new FunctionsK();
                       $FunctionsK->faxSendNotification((array)$merchantinfo[0],
                                           $this->data['package_id'],
                                           $payment_code,
                                           $price);
                        
					   							
				    } else $this->msg=Yii::t("default","ERROR: Cannot insert record.");	
				    
				} else $this->msg=t("Uknown transaction");
					            	
            } else $this->msg=$paypal->getError();	
		} else $this->msg=$paypal->getError();	           	
	}
	
	public function faxTransactionList()
	{
	    $slug=$this->data['slug'];
		$stmt="SELECT a.*,
		(
		select restaurant_name
		from
		{{merchant}} 
		where
		merchant_id=a.merchant_id
		limit 0,1
		) merchant_name,
		
		(
		select title
		from
		{{fax_package}}
		where
		fax_package_id=a.fax_package_id
		limit 0,1
		) fax_package_name
		
		 FROM
		{{fax_package_trans}} a
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	
				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
			   
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      ucwords($val['merchant_name']).$action,
		   	      ucwords($val['fax_package_name']),
		   	      standardPrettyFormat($val['package_price']),
		   	      $val['fax_limit'],
		   	      strtoupper($val['payment_type']),
		   	      $date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>"
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}	
	
	public function updateFaxTransaction()
	{						
		$f=new FunctionsK;
		if (empty($this->data['id'])){
			if ( $res=$f->getFaxPackagesById($this->data['fax_package_id'])){
				if ( $res['promo_price']>=1){
					$package_price=$res['promo_price'];
				} else $package_price=$res['price'];
			}
			$params=array(
			  'merchant_id'=>$this->data['merchant_id'],
			  'fax_package_id'=>$this->data['fax_package_id'],
			  'package_price'=>$package_price,
			  'fax_limit'=>$this->data['fax_limit'],
			  'status'=>$this->data['status'],
			  'date_created'=>date('c'),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'payment_type'=>"manual"
			);				
			if ( $this->insertData("{{fax_package_trans}}",$params)){
				$this->details=Yii::app()->db->getLastInsertID();					
				$this->code=1;
				$this->msg=t("Successful");
			} else $this->msg=t("ERROR. cannot insert data.");
		} else {		
			$params=array( 
			  'fax_limit'=>$this->data['fax_limit'],
			  'status'=>$this->data['status'],
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);
			if ( $this->updateData("{{fax_package_trans}}",$params,'id',$this->data['id']) ){
				$this->code=1;
				$this->msg=Yii::t("default","Successful");
			} else $this->msg=Yii::t("default","ERROR: cannot update");		
		}
	}		
	
	public function FaxbankDepositVerification()
	{
		$FunctionsK=new FunctionsK();
		
		if (isset($this->data['photo'])){
				$req=array('ref'=>t("reference number is required"));
			} else {
		        $req=array(
		          'branch_code'=>t("branch code is required"),
		          'date_of_deposit'=>t("date of deposit is required"),
		          'time_of_deposit'=>t("time of deposit is required"),
		          'amount'=>t("amount is required"),
		        );
			}
			$Validator=new Validator;			
			$Validator->required($req,$this->data);
						
			if ($Validator->validate()){
				$DbExt=new DbExt;
				 if ($res=$FunctionsK->getFaxTransactionByRef($this->data['ref'])){			 	
				 	
					$params=array(				
					  'merchant_id'=>Yii::app()->functions->getMerchantID(),
					  'branch_code'=>$this->data['branch_code'],
					  'date_of_deposit'=>$this->data['date_of_deposit'],
					  'time_of_deposit'=>$this->data['time_of_deposit'],
					  'amount'=>$this->data['amount'],
					  'scanphoto'=>isset($this->data['photo'])?$this->data['photo']:'',
					  'date_created'=>date('c'),
					  'ip_address'=>$_SERVER['REMOTE_ADDR'],
					  'transaction_type'=>"fax_purchase"
					);									
					if ($DbExt->insertData("{{bank_deposit}}",$params)){
						$this->code=1;
						$this->msg=Yii::t("default","Thank you. Your information has been receive please wait 1 or 2 days to verify your payment.");
						
						/*send email to admin owner*/
						$from='no-reply@'.$_SERVER['HTTP_HOST'];
	    	            $subject=Yii::t("default","New Bank Deposit");
	    	            $to=Yii::app()->functions->getOptionAdmin('website_contact_email');
	    	            $tpl=EmailTPL::bankDepositedReceive();
	    	            if (!empty($to)){
	    	                Yii::app()->functions->sendEmail($to,$from,$subject,$tpl);
	    	            }
						
					} else $this->msg=Yii::t("default","Something went wrong during processing your request. Please try again later.");
				 } else $this->msg=Yii::t("default","Reference number not found");
			} else $this->msg=$Validator->getErrorAsHTML();
	}
	
	public function faxTransactionLogs()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="
		SELECT * FROM
		{{fax_broadcast}}
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      $val['jobid'],
		   	      $val['faxurl'],
		   	      $val['status'],
		   	      $val['api_raw_response'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function updateMerchantUserProfile()
	{		
		$params=array(
		  'first_name'=>$this->data['first_name'],
		  'last_name'=>$this->data['last_name'],
		  'contact_email'=>$this->data['contact_email'],
		  'username'=>$this->data['username'],
		  'date_modified'=>date('c')
		);
		if (!empty($this->data['password'])){
			$params['password']=md5($this->data['password']);
		}
		
		if ($this->updateData("{{merchant_user}}",$params,'merchant_user_id',$this->data['merchant_user_id'])){
			$this->code=1;
			$this->msg=t("Profile successfully updated");
		} else $this->msg=t("ERROR: cannot update");
	}
	
	public function faxPurchaseTransaction()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="
		SELECT a.*,
		(
		select title
		from
		{{fax_package}}
		where
		fax_package_id=a.fax_package_id
		) as package_name
		 FROM
		{{fax_package_trans}} a
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      strtoupper($val['payment_type']),
		   	      ucfirst($val['package_name']),
		   	      displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['package_price'])),
		   	      $val['fax_limit'],
		   	      $val['status'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function smsPurchaseTransaction()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="
		SELECT a.*,
		(
		select title
		from
		{{sms_package}}
		where
		sms_package_id=a.sms_package_id
		) as package_name
		 FROM
		{{sms_package_trans}} a
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      strtoupper($val['payment_type']),
		   	      ucfirst($val['package_name']),
		   	      displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['package_price'])),
		   	      $val['sms_limit'],
		   	      $val['status'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}


	public function viewBankInfo()
	{
		require_once 'bank-info.php';
		die();
	}
	
	public function shipppingRates()
	{
		//dump($this->data);		
		$mtid=Yii::app()->functions->getMerchantID();	
		Yii::app()->functions->updateOption("shipping_enabled",
    	isset($this->data['shipping_enabled'])?$this->data['shipping_enabled']:'',$mtid);
    	
    	Yii::app()->functions->updateOption("free_delivery_above_price",
    	isset($this->data['free_delivery_above_price'])?$this->data['free_delivery_above_price']:'',$mtid);
    	
    	if (is_array($this->data['distance_from']) && count($this->data['distance_from'])>=1){    		
    		$x=0;
    		$stmt="
    		DELETE FROM
    		{{shipping_rate}}    		
    		WHERE
    		merchant_id=".Yii::app()->functions->q($mtid)."
    		";
    		$this->qry($stmt);
    		foreach ($this->data['distance_from'] as $val) {    			
    			$params=array(
    			  'merchant_id'=>$mtid,
    			  'distance_from'=>$val,
    			  'distance_to'=>$this->data['distance_to'][$x],
    			  'shipping_units'=>$this->data['shipping_units'][$x],
    			  'distance_price'=>$this->data['distance_price'][$x],
    			);    			
    			$this->insertData("{{shipping_rate}}",$params);
    			$x++;
    		}
    	}	
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function bookingSummaryReport()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		$and='';  
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		  $and=" AND date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
    		        '".$this->data['end_date']." 23:59:00'
    		  ";
    		    		  
              $_SESSION['rpt_date_range']=array(
    		     'start_date'=>$this->data['start_date'],
    		     'end_date'=>$this->data['end_date']
    		   );
    		  
    		}
    	}
		
		$slug=$this->data['slug'];
		$stmt="
		SELECT sum(a.number_guest) as total_approved,
		(
		select sum(number_guest)
		from {{bookingtable}}
		where
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		and
		status='denied'
		) as total_denied,
		
		(
		select sum(number_guest)
		from {{bookingtable}}
		where
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		and
		status='pending'
		) as total_pending
		
		FROM
		{{bookingtable}} a
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		AND status='approved'
		$and
		";		
		$_SESSION['kr_export_stmt']=$stmt;
		if (isset($_GET['debug'])){
			dump($stmt);
		}	
		if ($res=$this->rst($stmt)){		   			
		   foreach ($res as $val) {				   	    			   	    							   
		   	   $feed_data['aaData'][]=array(
		   	      $val['total_approved']+0,
		   	      $val['total_denied']+0,
		   	      $val['total_pending']+0
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function merchanBbookingSummaryReport()
	{		
		unset($_SESSION['rpt_date_range']);
		$and='';  
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		   $and=" AND date_created BETWEEN  '".$this->data['start_date']." 00:00:00' AND 
    		        '".$this->data['end_date']." 23:59:00'
    		   ";    		   
               $_SESSION['rpt_date_range']=array(
    		     'start_date'=>$this->data['start_date'],
    		     'end_date'=>$this->data['end_date']
    		   );
    		}
    	}
    	
    	$where='';
    	if (isset($this->data['merchant_id'])){
    		if (!empty($this->data['merchant_id'])){
    			$where=" WHERE merchant_id=".Yii::app()->functions->q($this->data['merchant_id'])." ";
    		}    	
    	}	
    	
    	$stmt="
    	SELECT a.merchant_id,a.restaurant_name as merchant_name,
    	
    	(
    	select sum(number_guest)
    	from
    	{{bookingtable}}
    	where
    	merchant_id=a.merchant_id  
    	and status='approved'  	
    	$and
    	) as total_approved,
    	
    	
    	(
    	select sum(number_guest)
    	from
    	{{bookingtable}}
    	where
    	merchant_id=a.merchant_id  
    	and status='denied'  	
    	$and
    	) as total_denied,
    	
    	
    	(
    	select sum(number_guest)
    	from
    	{{bookingtable}}
    	where
    	merchant_id=a.merchant_id    	
    	and status='pending'
    	$and
    	) as total_pending
    	
    	
    	FROM
    	{{merchant}} a
    	$where
    	GROUP BY merchant_id
    	";
		
		$_SESSION['kr_export_stmt']=$stmt;
		if (isset($_GET['debug'])){
			dump($stmt);
		}	
		if ($res=$this->rst($stmt)){		   					   
		   foreach ($res as $val) {				   	    			   	    							   
		   	   $feed_data['aaData'][]=array(
		   	      ucwords($val['merchant_name']),
		   	      $val['total_approved']+0,
		   	      $val['total_denied']+0,
		   	      $val['total_pending']+0
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function adminFaxTransactionLogs()
	{		
		$slug=$this->data['slug'];
		$stmt="
		SELECT a.*,
		(
		select restaurant_name
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		 FROM
		{{fax_broadcast}} a		
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      ucwords($val['merchant_name']),
		   	      $val['jobid'],
		   	      $val['faxurl'],
		   	      $val['status'],
		   	      $val['api_raw_response'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}	
	
	public function merchantList()
	{
		
		$slug=$this->data['slug'];
		
		$aColumns = array(
		  'merchant_id','restaurant_name','street','city','country_code','contact_phone',
		  'package_id','activation_token','is_commission','status'
		);
		
		$sWhere=''; $sOrder=''; $sLimit='';
		
		$sTable = "{{merchant}}";
		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		$stmt = "
			SELECT SQL_CALC_FOUND_ROWS 
			a.*,
			(
			select title
			from
			{{packages}}
			where
			package_id = a.package_id
			limit 0,1
			) as package_name
			
			FROM $sTable a
			$sWhere
			$sOrder
			$sLimit
		";
		if (isset($_GET['debug'])){
		   dump($stmt);
		}
		if ( $res=$this->rst($stmt)){		
			
			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){
				//dump($res2);
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
			foreach ($res as $val) {	
					/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));
					$date=Yii::app()->functions->translateDate($date);*/
					$date=FormatDateTime($val['date_created']);
					
					$action="<div class=\"options\">
    	    		<a href=\"$slug/id/$val[merchant_id]\" >".Yii::t("default","Edit")."</a>
    	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[merchant_id]\" >".Yii::t("default","Delete")."</a>
    	    		</div>";
					
					$val['package_name']=isset($val['package_name'])?$val['package_name']:'';
					
					if ($val['status']=="expired"){
					   $class='uk-badge-danger';
					} elseif ( $val['status']=="pending"){
						$class='';
					} elseif ($val['status']=="active"){
						$class='uk-badge-success';
					}				
					$membershipdate=FormatDateTime($val['membership_expired'],false);
					$membershipdate=Yii::app()->functions->translateDate($membershipdate);					
					
					$url_login=baseUrl()."/merchant/autologin/id/".$val['merchant_id']."/token/".$val['password'];
					$link_login='<br/><br/>
					<a target="_blank" href="'.$url_login.'"><div class="uk-badge">'.t("AutoLogin").'</div></a>
					';
					
					$aa_access=Yii::app()->functions->AAccess();
					if (!in_array('autologin',(array)$aa_access)){
						$link_login='';						
					}
					
					if (getOptionA('home_search_mode')!="postcode"){
						$feed_data['aaData'][]=array(
						  $val['merchant_id'],stripslashes($val['restaurant_name']).$action,
						  $val['street'],
						  $val['city'],
						  $val['country_code'],
						  $val['restaurant_phone']." / ".$val['contact_phone'],
						  $val['package_name']."<br/>".$membershipdate,
						  $val['activation_key'],
						  membershipType($val['is_commission']),
						  $date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>".$link_login
						);
					} else {
						$feed_data['aaData'][]=array(
						  $val['merchant_id'],stripslashes($val['restaurant_name']).$action,
						  $val['street'],						  
						  $val['post_code'],
						  $val['restaurant_phone']." / ".$val['contact_phone'],
						  $val['package_name']."<br/>".$membershipdate,
						  $val['activation_key'],
						  membershipType($val['is_commission']),
						  $date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>".$link_login
					);
					}
		
				}										
						
			$this->otableOutput($feed_data);	
		}
	    $this->otableNodata();
	}
	
	public function showSMS()
	{
		require_once "show-sms.php";
		die();
	}
	
	public function sendUpdateOrderSMS()
	{				
		$_GET['backend']=true;
		$order=Yii::app()->functions->getOrder2($this->data['order_id']);		
				
		if (isset($this->data['sms_order_change_msg'])){
			if (is_array($order) && count($order)>=1){
				$sms_msg=$this->data['sms_order_change_msg'];				
				$to=$order['contact_phone'];		
				
				$mtid=Yii::app()->functions->getMerchantID();				
				$available_credit=Yii::app()->functions->getMerchantSMSCredit($mtid);	 				
				if ( $available_credit>=1){						
					if ($resp= Yii::app()->functions->sendSMS($to,$sms_msg)){							
						if ( $resp['msg']=='process'){
							$this->code=1;
						    $this->msg=t("SMS sent");
						    $params=array(
						      'merchant_id'=>$mtid,
						      'broadcast_id'=>'999999999',
						      'client_id'=>$order['client_id'],
						      'client_name'=>$order['full_name'],
						      'contact_phone'=>$to,
						      'sms_message'=>$sms_msg,
						      'status'=>'process',
						      'gateway_response'=>$resp['raw'],
						      'date_created'=>date('c'),
						      'date_executed'=>date('c'),
						      'ip_address'=>$_SERVER['REMOTE_ADDR'],
						      'gateway'=>$resp['sms_provider']
						    );						    
						    $this->insertData("{{sms_broadcast_details}}",$params);
						} else $this->msg=$resp['msg'];
					} else $this->msg=$resp['msg'];
				} else $this->msg=t("No SMS Credit");
			} else $this->msg=t("Sory but we cannot find the order information");
		} else $this->msg=t("Message is required");
	}	
	
	public function saveAdminAuthorizeSettings()
	{

		Yii::app()->functions->updateOptionAdmin("admin_enabled_autho",
    	isset($this->data['admin_enabled_autho'])?$this->data['admin_enabled_autho']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_mode_autho",
    	isset($this->data['admin_mode_autho'])?$this->data['admin_mode_autho']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_autho_api_id",
    	isset($this->data['admin_autho_api_id'])?$this->data['admin_autho_api_id']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_autho_key",
    	isset($this->data['admin_autho_key'])?$this->data['admin_autho_key']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function saveMerchantAuthorizeSettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_enabled_autho",
    	isset($this->data['merchant_enabled_autho'])?$this->data['merchant_enabled_autho']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_mode_autho",
    	isset($this->data['merchant_mode_autho'])?$this->data['merchant_mode_autho']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_autho_api_id",
    	isset($this->data['merchant_autho_api_id'])?$this->data['merchant_autho_api_id']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_autho_key",
    	isset($this->data['merchant_autho_key'])?$this->data['merchant_autho_key']:'',$merchant_id);
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function merchantBankDeposit()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_bankdeposit_enabled",
    	isset($this->data['merchant_bankdeposit_enabled'])?$this->data['merchant_bankdeposit_enabled']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_deposit_sender",
    	isset($this->data['merchant_deposit_sender'])?$this->data['merchant_deposit_sender']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_deposit_subject",
    	isset($this->data['merchant_deposit_subject'])?$this->data['merchant_deposit_subject']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_deposit_instructions",
    	isset($this->data['merchant_deposit_instructions'])?$this->data['merchant_deposit_instructions']:'',$merchant_id);
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function ItemBankDepositVerification()
	{		
		if ( $res=Yii::app()->functions->getOrderInfo($this->data['ref'])){			
			$params=array(				
			  'merchant_id'=>$res['merchant_id'],
			  'branch_code'=>$this->data['branch_code'],
			  'date_of_deposit'=>$this->data['date_of_deposit'],
			  'time_of_deposit'=>$this->data['time_of_deposit'],
			  'amount'=>$this->data['amount'],
			  'scanphoto'=>isset($this->data['photo'])?$this->data['photo']:'',
			  'date_created'=>date('c'),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'transaction_type'=>"item_purchase",
			  'client_id'=>$res['client_id'],
			  'order_id'=>$this->data['ref'],
			);				
			
			if ($this->insertData("{{bank_deposit}}",$params)){
				$this->code=1;
				$this->msg=Yii::t("default","Thank you. Your information has been receive please wait 1 or 2 days to verify your payment.");
				
				/*send email to admin owner*/
				if ( $merchant_info=Yii::app()->functions->getMerchant($res['merchant_id'])){					
					$to=$merchant_info['contact_email'];
				} else $to='';
				
				$from='no-reply@'.$_SERVER['HTTP_HOST'];
	            $subject=Yii::t("default","New Bank Deposit");	            
	            $tpl=EmailTPL::bankDepositedReceiveMerchant();
	            
	            if (!empty($to)){
	                Yii::app()->functions->sendEmail($to,$from,$subject,$tpl);
	            }
					
			} else $this->msg=t("Something went wrong during processing your request. Please try again later.");
		} else $this->msg=t("ERROR: Something went wrong");
	}
	
	public function BankDepositListMerchant()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="SELECT a.*,
		(
		select restaurant_name from
		{{merchant}}
		where merchant_id=a.merchant_id
		) as merchant_name,
		
		(
		select concat(first_name,' ',last_name)
		from
		{{client}}
		where
		client_id=a.client_id
		) as client_name
		
		 FROM		 
		{{bank_deposit}} a
		
		WHERE
		merchant_id=".Yii::app()->functions->q($mtid)."
		AND
		transaction_type='item_purchase'
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[cuisine_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[cuisine_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	   
				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);*/
			   $date=FormatDateTime($val['date_created']);
			   
			   if (!empty($val['scanphoto'])){
			      $img=Yii::app()->request->baseUrl."/upload/$val[scanphoto]";
			      $scanphoto="<a href=\"$img\" target=\"_blank\">";
	    		  $scanphoto.="<img class=\"uk-thumbnail uk-thumbnail-mini\" src=\"$img\" >";	
	    		  $scanphoto.="</a>";
			   } else $scanphoto='';
			   
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      ucwords($val['client_name']),		   	      
		   	      $val['branch_code'],
		   	      FormatDateTime($val['date_of_deposit'],false),
		   	      $val['time_of_deposit'],
		   	      Yii::app()->functions->standardPrettyFormat($val['amount']),
		   	      $scanphoto,
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}

	public function testSms()
	{
		require_once 'test-sms.php';
		die();
	}
	
	public function SendTestSMS()
	{		
		if (isset($this->data['mobile'])){
			$text="This is a sms test message";
			if ( $res=Yii::app()->functions->sendSMS($this->data['mobile'],$text) ){								
				if ( $res['msg']=="process"){
					$this->code=1;
					$this->msg=t("Successful");
				} else $this->msg=$res['msg'];
			} else $this->msg=t("Failed");
		} else $this->msg=t("Mobile number is required");
	}
	
	public function verifyMobileCode()
	{		
		if( $res=Yii::app()->functions->getClientInfo($this->data['client_id'])){
			if ( $this->data['code']==$res['mobile_verification_code']){
				$this->code=1;
				$this->msg=t("Successful");
				
				$params=array( 
				  'status'=>"active",
				  'mobile_verification_date'=>date('c')
				);
				$this->updateData("{{client}}",$params,'client_id',$res['client_id']);
				
				Yii::app()->functions->clientAutoLogin($res['email_address'],$res['password'],$res['password']);
				
			} else $this->msg=t("Verification code is invalid");		
		} else $this->msg=t("Sorry but we cannot find your records");
	}
	
	public function resendMobileCode()
	{
		$date_now=date('Y-m-d g:i:s a');				
		if ( isset($_SESSION['resend_code'])){			
			$date_diff=Yii::app()->functions->dateDifference($_SESSION['resend_code'],$date_now);			
			if (is_array($date_diff) && count($date_diff)>=1){
				if ( $date_diff['minutes']<5){
					$remaining=5-$date_diff['minutes'];
					$this->msg=t("Please wait for a minute to receive your code");					
					$this->msg.=" (".$remaining ." "."minutes".")";
					return ;
				}			
			}		
		}	
				
		if ( isset($this->data['id'])){
			if( $res=Yii::app()->functions->getClientInfo($this->data['id'])){				
				$code=$res['mobile_verification_code'];
				$_SESSION['resend_code']=$date_now;
				Yii::app()->functions->sendVerificationCode($res['contact_phone'],$code);
				$this->code=1;
				$this->msg=t("Your verification code has been sent to")." ".$res['contact_phone'];
			} else $this->msg=t("Sorry but we cannot find your records");
		} else $this->msg=t("Missing id");
	}
		
	public function getAllMerchantCoordinates()
	{		
		$admin_country_set=Yii::app()->functions->getOptionAdmin('admin_country_set');
		
		$this->qry("SET SQL_BIG_SELECTS=1");
		
		$stmt="
		SELECT merchant_id,
		restaurant_slug,restaurant_name,latitude,lontitude,
		concat(street,' ',city,' ',state,' ',post_code) as address
		FROM
		{{view_merchant}}
		WHERE
		status in ('active')
		AND latitude <>''
		AND lontitude <>''		
		ORDER BY latitude ASC
		";
		if ( $res=$this->rst($stmt)){
			$list='';
			$x=0;
			foreach ($res as $val) {					
				$photo=Yii::app()->functions->getOption("merchant_photo",$val['merchant_id']);
				if (empty($photo)){
					$photo='thumbnail-medium.png';
				}
				
				$logo='<a href="'.websiteUrl()."/store/menu/merchant/".$val['restaurant_slug'].'">';
                $logo.='<img title="" alt="" src="'.uploadURL()."/$photo".'" class="uk-thumbnail uk-thumbnail-mini">';
				$logo.='</a>';
				
							
				$list[]=array(
				  $val['restaurant_name'],
				  $val['latitude'],
				  $val['lontitude'],
				  $x,
				  $val['address'],
				  $val['restaurant_slug'],
				  $logo,
				);
				$x++;
			}			
			
			$lng='';
			$lng='';
			$country=Yii::app()->functions->getAdminCountrySet();			
			if( $lat_res=Yii::app()->functions->geodecodeAddress($country)){				
				$lat=$lat_res['lat'];
				$lng=$lat_res['long'];
			}		
			
			$this->code=1;
			$this->msg=array(
			  'lat'=>$lat,
			  'lng'=>$lng,
			);
			$this->details=$list;
		} else $this->msg=t("0 restaurant found");
	}
	
	public function findGeo()
	{
		$home_search_unit_type=Yii::app()->functions->getOptionAdmin('home_search_unit_type');
		$home_search_radius=Yii::app()->functions->getOptionAdmin('home_search_radius');
		
		if(!is_numeric($home_search_radius)){
			$home_search_radius=15;
		}	
				
		$distance_exp=3959;
		if ($home_search_unit_type=="km"){
			$distance_exp=6371;
		}		
		
		$lat=isset($this->data['lat'])?$this->data['lat']:0;
		$long=isset($this->data['lng'])?$this->data['lng']:0;
						
		if ($lat_res=Yii::app()->functions->geodecodeAddress($this->data['geo_address'])){			
			$lat=$lat_res['lat'];
			$long=$lat_res['long'];
		}		
						
		if (isset($this->data['geo_address'])){
			$stmt="
			SELECT 
			SQL_CALC_FOUND_ROWS a.*, ( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
			* cos( radians( lontitude ) - radians($long) ) 
			+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
			AS distance								
			
			FROM {{view_merchant}} a 
			HAVING distance < $home_search_radius	
			AND status='active' AND is_ready='2' 		
			";		
			//dump($stmt);
			if ( $res=$this->rst($stmt)){
				$list='';
			    $x=0;
				foreach ($res as $val) {
					$address=$val['street']." ".$val['city']." ".$val['state']." ".$val['post_code'];
					
					
					$photo=Yii::app()->functions->getOption("merchant_photo",$val['merchant_id']);
				    if (empty($photo)){
					   $photo='thumbnail-medium.png';
				    }
				
				    $logo='<a href="'.websiteUrl()."/store/menu/merchant/".$val['restaurant_slug'].'">';
                    $logo.='<img title="" alt="" src="'.uploadURL()."/$photo".'" class="uk-thumbnail uk-thumbnail-mini">';
				    $logo.='</a>';
					
					$list[]=array(
					  $val['restaurant_name'],
					  $val['latitude'],
					  $val['lontitude'],
					  $x,
					  $address,
					  $val['restaurant_slug'],
					  $logo,
					);
				    $x++;
				}				
				$this->code=1;
			    $this->msg=array(
			      'lat'=>$lat,
			      'lng'=>$long
			    );
			    $this->details=$list;
			} else $this->msg=t("No results");
		} else $this->msg=t("Missing parameters");
	}
	
	public function dishList()
	{
		$slug=$this->data['slug'];
        $stmt="
		SELECT * FROM
		{{dishes}}
		WHERE
		status in ('published','publish')
		ORDER BY dish_id  DESC
		";
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	     	    		
	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[dish_id]\" class=\"chk_child\" >";   		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[dish_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[dish_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
	    		
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		$feed_data['aaData'][]=array(
	    		  $val['dish_id'],
	    		  $val['dish_name'].$option,
	    		  '<img class="uk-thumbnail uk-thumbnail-mini" src="'.uploadURL()."/".$val['photo'].'">',
	    		  $date."<div>".$val['status']."</div>"
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function addDish()
	{		
	   $Validator=new Validator;
		$req=array(
		  'dish_name'=>Yii::t("default","Dish name is required"),
		  'spicydish'=>t("Icon is required")
		);		
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			$params=array(
			  'dish_name'=>$this->data['dish_name'],
			  'photo'=>$this->data['spicydish'],
			  'status'=>$this->data['status'],
			  'date_created'=>date('c'),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);			
		   if (empty($this->data['id'])){	
		    	if ( $this->insertData("{{dishes}}",$params)){
		    		    $this->details=Yii::app()->db->getLastInsertID();
			    		$this->code=1;
			    		$this->msg=Yii::t("default","Successful");				    		
			    	}
			    } else {		    	
			    	unset($params['date_created']);
					$params['date_modified']=date('c');				
					$res = $this->updateData('{{dishes}}' , $params ,'dish_id',$this->data['id']);
					if ($res){
						$this->code=1;
		                $this->msg=Yii::t("default",'Dish updated');  
				} else $this->msg=Yii::t("default","ERROR: cannot update");
		    }	
		} else $this->msg=$Validator->getErrorAsHTML();	
	}
	
	public function addVoucherNew()
	{					
		$functionsk=new FunctionsK();		
		
		$merchant_id=Yii::app()->functions->getMerchantID();
		$params=array(
		  'voucher_name'=>$this->data['voucher_name'],
		  'voucher_type'=>$this->data['voucher_type'],
		  'amount'=>$this->data['amount'],
		  'expiration'=>$this->data['expiration'],
		  'status'=>$this->data['status'],
		  'date_created'=>date('c'),
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'merchant_id'=>$merchant_id
		);
		
		/*dump($this->data);
		dump($params);
		die();*/
		
		if (isset($this->data['voucher_owner'])){
			unset($params['merchant_id']);
			$params['voucher_owner']=$this->data['voucher_owner'];
		}
		
		if (isset($this->data['joining_merchant'])){
			$params['joining_merchant']=json_encode($this->data['joining_merchant']);
		}	
		
		if (isset($this->data['used_once'])){
			$params['used_once']=$this->data['used_once'];
		} else 	$params['used_once']='';
						
		if (!empty($this->data['id'])){
			
			if ( $functionsk->checkIFVoucherCodeExisting($this->data['voucher_name'],$this->data['id'])){
				$this->msg=t("Sorry but voucher name already exist!");
				return;
			}		
			
			$params['date_modified']=date('c');
			unset($params['date_created']);
			if ( $this->updateData("{{voucher_new}}",$params,'voucher_id',$this->data['id'])){
				$this->code=1;
	    		$this->msg=t("Successful");
			} else $this->msg=t("Failed cannot update records");	    	
		} else {
			if ( $functionsk->checkIFVoucherCodeExists($this->data['voucher_name'])){
				$this->msg=t("Sorry but voucher name already exist!");
				return;
			}		
	        if ( $this->insertData('{{voucher_new}}',$params)){
	        	$this->details=Yii::app()->db->getLastInsertID();
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");		 
	        } else $this->msg=t("ERROR: Something went wrong");		
		}
	}
	
	public function VoucherListNew()
		{
			$slug=$this->data['slug'];
						
			$and='';
			if (isset($this->data['voucher_owner'])){
				$stmt="
				SELECT a.*,
				(
				select count(*) 
				from
				{{order}}
				where
				voucher_code=a.voucher_name			
				) as total_used
				FROM
				{{voucher_new}} a
				WHERE
				voucher_owner=".Yii::app()->db->quoteValue($this->data['voucher_owner'])."
				ORDER BY voucher_id DESC
				";	   		    		    	
			} else {
				$merchant_id=Yii::app()->functions->getMerchantID();		    
			    $stmt="
				SELECT a.*,
				(
				select count(*) 
				from
				{{order}}
				where
				voucher_code=a.voucher_name			
				) as total_used
				FROM
				{{voucher_new}} a
				WHERE
				merchant_id=".Yii::app()->db->quoteValue($merchant_id)."
				ORDER BY voucher_id DESC
				";	   		    		    	
			}	
						
			$connection=Yii::app()->db;
    	    $rows=$connection->createCommand($stmt)->queryAll();     	        	        	        	    
    	    //dump($rows);
    	    if (is_array($rows) && count($rows)>=1){
    	    	foreach ($rows as $val) {    	    	    		
    	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[voucher_id]\" class=\"chk_child\" >";   		
    	    		$action="<div class=\"options\">
    	    		<a href=\"$slug/id/$val[voucher_id]\" >".Yii::t("default","Edit")."</a>
    	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[voucher_id]\" >".Yii::t("default","Delete")."</a>
    	    		</div>";
    	    		
    	    		
    	    		if ( $val['total_used']>0){
    	    			$used='<a class="voucher-details" href="javascript:;" data-id="'.$val['voucher_name'].'">'.
    	    			$val['total_used'].'</a>';
    	    		} else $used='';  	    	 
    	    		
    	    		if ($val['voucher_type']=="percentage"){
    	    			$amt=normalPrettyPrice($val['amount']). " %";
    	    		} else $amt=normalPrettyPrice($val['amount']);    		
    	    		    	    		
    	    		$date=FormatDateTime($val['date_created']);
    	    		
    	    		$feed_data['aaData'][]=array(
    	    		  $val['voucher_id'],
    	    		  $val['voucher_name'].$action,    	    		  
    	    		  $val['voucher_type'],
    	    		  $amt,    	    		
    	    		  FormatDateTime($val['expiration'],false),
    	    		  $used,
    	    		  $date."<div>".Yii::t("default",$val['status'])."</div>"
    	    		);
    	    	}
    	    	$this->otableOutput($feed_data);
    	    }     	    
    	    $this->otableNodata();	
     }	
     
     public function viewVoucherDetails()
     {
     	require_once "voucher-details.php";
     }
     
     public function addressBook()
     {     	
 	    $slug=createUrl("store/profile/?tab=2");
		$stmt="SELECT id,location_name,country_code,as_default,
		concat(street,' ',city,' ',state,' ',zipcode) as address		
		FROM
		{{address_book}}		
		WHERE
		client_id ='".Yii::app()->functions->getClientId()."'	
		ORDER BY id DESC
		";						
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug&do=add&id=$val[id]\" ><i class=\"ion-ios-compose-outline\"></i></a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[id]\" ><i class=\"ion-ios-trash\"></i></a>
	    		</div>";		   	   
		   	   $feed_data['aaData'][]=array(
		   	      $val['address'].$action,
		   	      $val['location_name'],
		   	      $val['as_default']==2?'<i class="fa fa-check"></i>':'<i class="fa fa-times"></i>'
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();			
     }
     
     public function addAddressBook()
     {     	
     	$params=array(
     	  'client_id'=>Yii::app()->functions->getClientId(),
     	  'street'=>$this->data['street'],
     	  'city'=>$this->data['city'],
     	  'state'=>$this->data['state'],
     	  'zipcode'=>$this->data['zipcode'],
     	  'location_name'=>isset($this->data['location_name'])?$this->data['location_name']:'',
     	  'as_default'=>isset($this->data['as_default'])?$this->data['as_default']:1,
     	  'date_created'=>date('c'),
     	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
     	  'country_code'=>$this->data['country_code']
     	);     	
     	
     	if (!isset($this->data['as_default'])){
     		$this->data['as_default']='';
     	}
     	
     	if ( $this->data['as_default']==2){
     		$sql_up="UPDATE {{address_book}}
     		SET as_default='1' 	     		
     		";
     		$this->qry($sql_up);
     	}     
     	
     	if ( isset($this->data['id'])){
     		unset($params['date_created']);
     		$params['date_modified']=date('c');
     		if ( $this->updateData("{{address_book}}",$params,'id',$this->data['id'])){
     			$this->code=1;
     			$this->msg=Yii::t("default","Successful");		 
     		} else $this->msg=t("ERROR: Something went wrong");	
     	} else {
     	    if ( $this->insertData('{{address_book}}',$params)){
	        	$this->details=Yii::app()->db->getLastInsertID();
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");		 
	        } else $this->msg=t("ERROR: Something went wrong");		
     	}
     }
     
	public function adminCustomerReviews()
	{		
		$slug=$this->data['slug'];
		$stmt="SELECT a.*,
		(
		select concat(first_name,' ',last_name)
		from {{client}}
		where
		client_id=a.client_id
		) client_name,
		
		(
		select restaurant_name
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		
		 FROM
		{{review}} a			
		ORDER BY id DESC
		";						
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	  
				
				if ( $this->data['currentController']=="admin"){						
				} else {					
					if ( Yii::app()->functions->getOptionAdmin('merchant_can_edit_reviews')=="yes"){
						$action='';
					}
				}
				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);	
			   $date=Yii::app()->functions->translateDate($date); */
			   $date=FormatDateTime($val['date_created']);
			   
		   	   $feed_data['aaData'][]=array(
		   	     $val['id'],
		   	      $val['merchant_name'].$action,
		   	      $val['client_name'],
		   	      $val['review'],
		   	      /*$val['order_id'],*/
		   	      $val['rating'],
		   	      $date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>"
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();	
	}     
	
	public function AdminUpdateCustomerReviews()
	{
		$db_ext=new DbExt;			
		if (isset($this->data['id'])){
			$params=array(
			  'review'=>$this->data['review'],
			  'status'=>$this->data['status'],
			  'rating'=>$this->data['rating'],
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);
			if ($db_ext->updateData("{{review}}",$params,'id',$this->data['id'])){
				$this->code=1;
				$this->msg=Yii::t("default","Successful");
			} else $this->msg=Yii::t("default","ERROR: cannot update");
		} else $this->msg="";		
	}	
	
	public function clearCart()
	{
		unset($_SESSION['kr_item']);
		$this->code=1;
		$this->msg="OK";
	}
	
	public function UpdateItemAvailable()
	{		
		if (isset($this->data['item_id'])){
			$params=array('not_available'=>$this->data['checked']==1?2:1);
			$db_ext=new DbExt;
			if ( $db_ext->updateData("{{item}}",$params,'item_id',$this->data['item_id'])){
				$this->code=1;
				$this->msg=t("Successful");
			} else $this->msg=t("ERROR: cannot update records.");
		} else $this->msg=t("Missing parameters");
	}
	
	public function cardPaymentSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_enabled_card",
    	isset($this->data['admin_enabled_card'])?$this->data['admin_enabled_card']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function switchMerchantAccount()
	{		
		if (!isset($this->data['iagree'])){
			$this->msg=t("You must agree to switch your account to commission");
			return ;
		}	
		
		$params=array(
		  'is_commission'=>2,
		  'percent_commision'=>getOptionA('admin_commision_percent'),
		  'commision_type'=>getOptionA('admin_commision_type')
		);		
		$merchant_id=Yii::app()->functions->getMerchantID();		
		$db_ext=new DbExt;
		if ( $db_ext->updateData("{{merchant}}",$params,'merchant_id',$merchant_id)){
			$this->code=1; 
			$this->msg=t("You have successfully switch your account to commission");
			$this->msg.="<br/>";
			$this->msg.=t("Please not you might have to relogin again to see the balance");
			$this->details=websiteUrl()."/merchant/dashboard";
		} else $this->msg=t("ERROR: cannot update");
	}

	public function sendUpdateOrderEmail()
	{
		if (empty($this->data['email_order_change_msg'])){
			$this->msg=t("Email content is required");
			return false;
		}	
		if (empty($this->data['subject'])){
			$this->msg=t("Subject is required");
			return false;
		}	
		if ($res=Yii::app()->functions->getOrder($this->data['order_id'])){
			$client_email=$res['email_address'];			
			$content=$this->data['email_order_change_msg'];
			$subject=$this->data['subject'];
			if (Yii::app()->functions->sendEmail($client_email,'',$subject,$content)){
				$this->code=1;
				$this->msg=t("Email sent");
			} else $this->msg=t('ERROR: Cannot sent email.');
		} else $this->msg=t("Sory but we cannot find the order information");
	}
	
	public function viewOrderHistory()
	{		
		?>
		<div class="view-receipt-pop">
	      <h3><?php echo Yii::t("default",'History')?></h3>
	    
	      <?php if ( $resh=FunctionsK::orderHistory($this->data['id'])):?>                    
               <table class="uk-table uk-table-hover">
                 <thead>
                   <tr>
                    <th class="uk-text-muted"><?php echo t("Date/Time")?></th>
                    <th class="uk-text-muted"><?php echo t("Status")?></th>
                    <th class="uk-text-muted"><?php echo t("Remarks")?></th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php foreach ($resh as $valh):?>
                   <tr style="font-size:12px;">
                     <td><?php                       
                      echo FormatDateTime($valh['date_created'],true);
                      ?></td>
                     <td><?php echo t($valh['status'])?></td>
                     <td><?php echo $valh['remarks']?></td>
                   </tr>
                   <?php endforeach;?>
                 </tbody>
               </table> 
          <?php else :?>                
            <p class="uk-text-danger order-order-history show-history-<?php echo $val['order_id']?>">
              <?php echo t("No history found")?>
            </p>
          <?php endif;?>	 
	    	 
	    </div>
		<?php
		Yii::app()->end();
	}
	
	public function sendOrderSMSCode()
	{		
		$validator=new Validator;
		$req=array(
		  'session'=>t("Session is missing"),
		  'mobile'=>t("Mobile number is required"),
		  'mtid'=>t("Merchant id is missing")
		);
		
		if (empty($this->data['mtid'])){
			$this->msg=t("Merchant id is missing");
			return ;
		}	
		
		$waiting_time_define=getOption($this->data['mtid'],'order_sms_code_waiting');	
		if (!is_numeric($waiting_time_define)){
			$waiting_time_define=5;
		}			
		if (isset($_SESSION['request_order_sms'])){			
			$time_1=date('Y-m-d g:i:s a');			
			$time_2=$_SESSION['request_order_sms'];			
			if (!empty($time_2)){			
				$time_diff=Yii::app()->functions->dateDifference($time_2,$time_1);				
				if (is_array($time_diff) && count($time_diff)>=1){
					if ($time_diff['days']==0 && $time_diff['hours']==0){
						if ($time_diff['minutes']<$waiting_time_define){
							$waiting_time=$waiting_time_define-$time_diff['minutes'];
							$this->msg=t("Spam protection. you cannot request another order sms code in less than")." ".$waiting_time_define." ".t("Minutes");
							$this->msg.="<br/><br/>";
							$this->msg.=t("Please wait in")." ".$waiting_time." ".t("Minutes")."";
							$this->details=$time_diff;
							return ;
						}				
					} else {					
						$this->msg=t("Spam protection. you cannot request another order sms code in less than")." ".$waiting_time_define." ".t("Minutes");
						$waiting_time=$time_diff['hours']." ".t("hour")." ".t("and")." ".$time_diff['minutes']." ".t("Minutes");
						$this->msg.="<br/><br/>";
						$this->msg.=t("Please wait in")." ".$waiting_time;
						$this->details=$time_diff;
						return ;
					}		
				}			
			}
		}		
		
		$validator->required($req,$this->data);
		if ($validator->validate()){
			$sms_balance=Yii::app()->functions->getMerchantSMSCredit($this->data['mtid']);			
			if ( $sms_balance>=1){
				$code=FunctionsK::generateSMSOrderCode($this->data['mobile']);
				$sms_msg=t("Your order sms code is")." ".$code;
				if ( $resp=Yii::app()->functions->sendSMS($this->data['mobile'],$sms_msg)){				    
				    if ($resp['msg']=="process"){
				    	$this->code=1;
				    	$this->msg=t("Your order sms code has been sent to")." ".$this->data['mobile'];
				    	
				    	$this->data['mobile']=str_replace("+","",$this->data['mobile']);
				    	$params=array(
				    	  'mobile'=>trim($this->data['mobile']),
				    	  'code'=>$code,
				    	  'session'=>$this->data['session'],
				    	  'date_created'=>date('c'),
				    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
				    	);
				    	$this->insertData("{{order_sms}}",$params);
				    	$_SESSION['request_order_sms']=date('Y-m-d g:i:s a');
				    					    								    		    
                        $params=array(
			        	  'merchant_id'=>$this->data['mtid'],
			        	  'broadcast_id'=>"999999999",			        	  
			        	  'contact_phone'=>$this->data['mobile'],
			        	  'sms_message'=>$sms_msg,
			        	  'status'=>$resp['msg'],
			        	  'gateway_response'=>$resp['raw'],
			        	  'date_created'=>date('c'),
			        	  'date_executed'=>date('c'),
			        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        	  'gateway'=>$resp['sms_provider']
			        	);	  		        	  
			        	$this->insertData("{{sms_broadcast_details}}",$params);	   
				    	
				    } else $this->msg=t("Sorry but we cannot sms code this time")." ".$resp['msg'];
				} else $this->msg=t("Sorry but we cannot sms code this time");
			} else $this->msg=t("Sorry but this merchant does not have enought sms credit to send sms");		
		} else $this->msg=$Validator->getErrorAsHTML();	
	}
	
	public function ZipCodeList()
	{		
	    $slug=$this->data['slug'];
		$stmt="SELECT * FROM
		{{zipcode}}
		ORDER BY zipcode_id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[zipcode_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[zipcode_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	   
							   
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['zipcode_id'].$action,
		   	      $val['zipcode'],
		   	      Yii::app()->functions->countryCodeToFull($val['country_code']),
		   	      $val['stree_name'],
		   	      $val['city'],
		   	      $val['area'],
		   	      Yii::app()->functions->translateDate($date)."<br/><div class=\"uk-badge\">".$val['status']."</div>"
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();		
	}
	
	public function addZipCode()
	{
		$Validator=new Validator;
		$req=array(
		  'zipcode'=>Yii::t("default","post code is required"),
		  'country_code'=>t("country is required"),
		  'city'=>t("city is required"),
		  'area'=>t("area is required"),
		);				
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			$params=array(
			  'zipcode'=>$this->data['zipcode'],
			  'country_code'=>$this->data['country_code'],
			  'city'=>$this->data['city'],
			  'area'=>$this->data['area'],
			  'status'=>$this->data['status'],			  
			  'date_created'=>date('c'),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'stree_name'=>isset($this->data['stree_name'])?$this->data['stree_name']:''
			);
		   if (empty($this->data['id'])){	
		    	if ( $this->insertData("{{zipcode}}",$params)){
		    		    $this->details=Yii::app()->db->getLastInsertID();
			    		$this->code=1;
			    		$this->msg=Yii::t("default","Successful");				    		
			    	}
			    } else {		    	
			    	unset($params['date_created']);
					$params['date_modified']=date('c');				
					$res = $this->updateData('{{zipcode}}' , $params ,'zipcode_id',$this->data['id']);
					if ($res){
						$this->code=1;
		                $this->msg=Yii::t("default",'zipcode updated');  
				} else $this->msg=Yii::t("default","ERROR: cannot update");
		    }	
		} else $this->msg=$Validator->getErrorAsHTML();		
	}
	
	public function getArea()
	{		
		if (isset($this->data['city'])){
		   $stmt="
		   SELECT DISTINCT area
		   FROM
		   {{zipcode}}
		   WHERE
		   city =".q($this->data['city'])."
		   ORDER BY area ASC
		   ";
		   if ( $res=$this->rst($stmt)){
		   	   $this->code=1;
		   	   $this->msg="OK";
		   	   $this->details=$res;
		   } else $this->msg=t("No results");
		} else $this->msg=t("missing city parameters");
	}
	
	public function verifyEmailCode()
	{
		$client_id=isset($this->data['client_id'])?$this->data['client_id']:'';
		if( $res=Yii::app()->functions->getClientInfo( $client_id )){	
			
		    if ($res['email_verification_code']==trim($this->data['code'])){
		    	$this->code=1;
		    	$this->msg=t("Successful");
		    	
		    	$params=array( 
				  'status'=>"active",
				  'last_login'=>date('c')
				);
				$this->updateData("{{client}}",$params,'client_id',$res['client_id']);
				
				Yii::app()->functions->clientAutoLogin($res['email_address'],$res['password'],$res['password']);
				
		    } else $this->msg=t("Verification code is invalid");
		} else $this->msg=t("Sorry but we cannot find your information.");
    }
			
} /*END CLASS*/