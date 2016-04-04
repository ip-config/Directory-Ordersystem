<?php
/**
 * Update Controller
 *
 */
class UpdateController extends CController
{
	public function actionIndex()
	{
		$prefix=Yii::app()->db->tablePrefix;		
		$table_prefix=$prefix;
		
		$DbExt=new DbExt;
		
		echo "STARTING UPDATE(s)";
		echo "<br/>";		
		$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."languages (
  `lang_id` int(14) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(14) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `source_text` text NOT NULL,
  `is_assign` int(1) NOT NULL DEFAULT '2',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";		
	    echo "Creating Table languages..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("languages") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table languages already exist.<br/>"; 
		  
				
		echo "<br/>";		
		$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."stripe_logs (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `json_result` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";		
	    echo "Creating Table stripe_logs..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("stripe_logs") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table stripe_logs already exist.<br/>"; 
		  				
	    echo "<br/>Updating Table admin_user.<br/>";	        
        $new_field=array(         
          'user_lang'=>"int(14) NOT NULL",
          'email_address'=>"varchar(255) NOT NULL",
          'lost_password_code'=>"varchar(255) NOT NULL"
        );	        
        $this->alterTable('admin_user',$new_field);
        echo "<br/>";
        
        
/*1.0.1*/        
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."sms_broadcast (
  `broadcast_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `send_to` int(14) NOT NULL,
  `list_mobile_number` text CHARACTER SET utf8 NOT NULL,
  `sms_alert_message` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 AUTO_INCREMENT=1;
";		
	    echo "Creating Table sms_broadcast..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("sms_broadcast") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table sms_broadcast already exist.<br/>";         
		
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."sms_broadcast_details (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `broadcast_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_phone` varchar(50) NOT NULL,
  `sms_message` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `gateway_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_executed` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";		
	    echo "Creating Table sms_broadcast_details..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("sms_broadcast_details") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table sms_broadcast_details already exist.<br/>";         		
		
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."sms_package (
  `sms_package_id` int(14) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float(14,4) NOT NULL,
  `promo_price` float(14,4) NOT NULL,
  `sms_limit` int(14) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`sms_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";		
	    echo "Creating Table sms_package..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("sms_package") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table sms_package already exist.<br/>";         				
		
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."sms_package_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `sms_package_id` int(14) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `package_price` float(14,4) NOT NULL,
  `sms_limit` int(14) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) NOT NULL,
  `payment_gateway_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";		
	    echo "Creating Table sms_broadcast_details..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("sms_package_trans") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table sms_package_trans already exist.<br/>";         						
		
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."payment_order (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(10) CHARACTER SET utf8 NOT NULL,
  `payment_reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `order_id` int(14) NOT NULL,
  `raw_response` text CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
";		
	    echo "Creating Table payment_order..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("payment_order") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table payment_order already exist.<br/>";         								
        
		
        echo "<br/>Updating Table merchant.<br/>";	        
        $new_field=array(         
          'user_lang'=>"int(14) NOT NULL",
          'membership_purchase_date'=>"datetime NOT NULL",
          'sort_featured'=>"int(14) NOT NULL",
          'is_commission'=>"int(1) NOT NULL DEFAULT '1'",
          'percent_commision'=>"float(14,5) NOT NULL",
        );	        
        $this->alterTable('merchant',$new_field);
        echo "<br/>";
        
        echo "<br/>Updating Table packages.<br/>";	        
        $new_field=array(         
          'sell_limit'=>"int(14) NOT NULL"     
        );	        
        $this->alterTable('packages',$new_field);
        echo "<br/>";
                
        /*END 1.0.1*/
        
        
$stmt="CREATE TABLE IF NOT EXISTS ".$prefix."voucher (
  `voucher_id` int(14) NOT NULL AUTO_INCREMENT,
  `voucher_name` varchar(255) NOT NULL,
  `merchant_id` int(14) NOT NULL,
  `number_of_voucher` int(14) NOT NULL,
  `amount` float NOT NULL,
  `voucher_type` varchar(100) NOT NULL DEFAULT 'fixed amount',
  `status` varchar(100) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`voucher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";        
echo "Creating Table voucher..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("voucher") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table voucher already exist.<br/>";         								        
		
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."voucher_list (
  `voucher_id` int(14) NOT NULL,
  `voucher_code` varchar(50) NOT NULL,
  `status` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'unused',
  `client_id` int(14) NOT NULL,
  `date_used` varchar(50) NOT NULL,
  `order_id` int(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";        
echo "Creating Table voucher_list..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("voucher_list") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table voucher_list already exist.<br/>";         								        		
				
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."merchant_user (
  `merchant_user_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_access` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `last_login` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`merchant_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";        
echo "Creating Table merchant_user..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("merchant_user") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table merchant_user already exist.<br/>";         								        				
        		
		
echo "<br/>Updating Table payment_order.<br/>";	        
        $new_field=array(         
          'payment_type'=>"varchar(10) CHARACTER SET utf8 NOT NULL"     
        );	        
        $this->alterTable('payment_order',$new_field);
        echo "<br/>";
        
echo "<br/>Updating Table client.<br/>";	        
        $new_field=array(         
          'status'=>"varchar(100) NOT NULL DEFAULT 'active'" ,
          "token"=>"varchar(255) NOT NULL",  
          "mobile_verification_code"=>"int(14) NOT NULL",  
          "mobile_verification_date"=>"datetime NOT NULL", 
          'custom_field1' => "varchar(255) NOT NULL",
          'custom_field2' => "varchar(255) NOT NULL",
          'avatar' => "varchar(255) NOT NULL",
          'email_verification_code' => "varchar(14) NOT NULL",
        );	        
        $this->alterTable('client',$new_field);
        echo "<br/>";        
        		
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."bookingtable (
  `booking_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `number_guest` int(14) NOT NULL,
  `date_booking` date NOT NULL,
  `booking_time` varchar(50) NOT NULL,
  `booking_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `booking_notes` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

echo "Creating Table bookingtable..<br/>";	
	    if ( !Yii::app()->functions->isTableExist("bookingtable") ){			
			if ($DbExt->qry($stmt)){
		        echo "(Done)<br/>";
            } else echo "(Failed)<br/>";	
		} else echo "Table bookingtable already exist.<br/>";         								        				

$stmt="
create OR REPLACE VIEW ".$prefix."view_ratings as
select 
merchant_id,
SUM(ratings)/COUNT(*) AS ratings
from
".$prefix."rating
group by merchant_id
";

echo "Updating view Table view_ratings..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

	
$stmt="
create OR REPLACE VIEW ".$prefix."view_merchant as

SELECT a.*,
b.option_value as latitude,
c.option_value as lontitude,
d.option_value as delivery_charges,
e.option_value as minimum_order,
f.ratings

FROM
".$prefix."merchant a
left join ".$prefix."option b
ON 
a.merchant_id =b.merchant_id 	
and b.option_name='merchant_latitude'

left join ".$prefix."option c
ON 
a.merchant_id =c.merchant_id 	
and c.option_name='merchant_longtitude'

left join ".$prefix."option d
ON 
a.merchant_id =d.merchant_id 	
and d.option_name='merchant_delivery_charges'

left join ".$prefix."option e
ON 
a.merchant_id =e.merchant_id 	
and e.option_name='merchant_minimum_order'

left join ".$prefix."view_ratings f
ON 
a.merchant_id =f.merchant_id 	
";
echo "Updating view Table merchant..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";		


       echo "<br/>Updating Booking table<br/>";	        
        $new_field=array(         
          'status'=>"varchar(255) NOT NULL DEFAULT 'pending'",
          'viewed'=>"int(1) NOT NULL DEFAULT '1'"
        );	        
        $this->alterTable('bookingtable',$new_field);
        echo "<br/>";    
        
        
$stmt="CREATE TABLE IF NOT EXISTS ".$prefix."bank_deposit (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `branch_code` varchar(100) NOT NULL,
  `date_of_deposit` date NOT NULL,
  `time_of_deposit` varchar(50) NOT NULL,
  `amount` float(14,4) NOT NULL,
  `scanphoto` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` date NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
echo "Updating Table bank_deposit..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";
        

       echo "<br/>Updating Bank deposit table<br/>";	        
        $new_field=array(         
          'transaction_type'=>"varchar(255) NOT NULL DEFAULT 'merchant_signup'",
          "client_id"=>"int(14) NOT NULL",
          "order_id"=>"int(14) NOT NULL"
        );	        
        $this->alterTable('bank_deposit',$new_field);
        echo "<br/>";        
        
        echo "<br/>Updating custom_page table<br/>";	        
        $new_field=array(         
          'open_new_tab'=>"int(11) NOT NULL DEFAULT '1'",
          'is_custom_link'=>"int(2) NOT NULL DEFAULT '1'"
        );	        
        $this->alterTable('custom_page',$new_field);
        echo "<br/>";                
                        
        echo "<br/>Updating order table<br/>";	        
        $new_field=array(         
          'order_change'=>"float(14,4) NOT NULL",
          'payment_provider_name'=>"varchar(255) NOT NULL",
          'discounted_amount'=>"float(14,5) NOT NULL",
          'discount_percentage'=>"float(14,5) NOT NULL",
          'percent_commision'=>"float(14,4) NOT NULL",
          'total_commission'=>"float(14,4) NOT NULL",
          'commision_ontop'=>"int(2) NOT NULL DEFAULT '2'",
          'merchant_earnings'=>"float(14,4) NOT NULL",
          'packaging'=>"float(14,4) NOT NULL",
          'cart_tip_percentage'=>"float(14,4) NOT NULL",
          "cart_tip_value"=>"float(14,4) NOT NULL",
          "card_fee"=>"float(14,4) NOT NULL",
          'donot_apply_tax_delivery'=>"int(1) NOT NULL DEFAULT '1'",
          'order_locked'=>"int(1) NOT NULL DEFAULT '1'",
          'request_from'=>"varchar(10) NOT NULL DEFAULT 'web'",
          'mobile_cart_details'=>"text NOT NULL"
        );	        
        $this->alterTable('order',$new_field);
        echo "<br/>";                 
        
$stmt="CREATE TABLE IF NOT EXISTS ".$prefix."payment_provider (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `payment_name` varchar(255) NOT NULL,
  `payment_logo` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'publish',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
echo "Creating Table _payment_provider..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";      

$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."offers (
  `offers_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `offer_percentage` float(14,4) NOT NULL,
  `offer_price` float(14,4) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`offers_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table _offers..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>"; 

$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."newsletter (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table _newsletter..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>"; 


        echo "<br/>Updating Table sms_broadcast_details.<br/>";	        
        $new_field=array(         
          'gateway'=>"varchar(255) NOT NULL DEFAULT 'twilio'"     
        );
        $this->alterTable('sms_broadcast_details',$new_field);
        echo "<br/>";
        
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."barclay_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `transaction_type` varchar(255) NOT NULL DEFAULT 'signup',
  `date_created` date NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `param1` varchar(255) NOT NULL,
  `param2` text NOT NULL,
  `param3` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table barclay_trans..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";         

$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."ingredients (
  `ingredients_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `ingredients_name` varchar(255) NOT NULL,
  `sequence` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'published',
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`ingredients_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table ingredients..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";         

        $new_field=array(         
          'ingredients'=>"text NOT NULL",
          'spicydish'=>"int(2) NOT NULL DEFAULT '1'",
          "two_flavors"=>"int(2) NOT NULL",
          "two_flavors_position"=>"text NOT NULL",
          "require_addon"=>"text NOT NULL",
          'dish'=>"text NOT NULL",
          'item_name_trans'=>"text NOT NULL",
          'item_description_trans'=>"text NOT NULL",
          'non_taxable'=>"int(1) NOT NULL DEFAULT '1'",
          'not_available'=>"int(1) NOT NULL DEFAULT '1'",
          'gallery_photo'=>"text NOT NULL"
        );	                
        $this->alterTable('item',$new_field);
        echo "<br/>";        
        
        $new_field=array(         
          'ingredients'=>"text NOT NULL" ,
          'non_taxable'=>"int(1) NOT NULL DEFAULT '1'"
        );	        
        $this->alterTable('order_details',$new_field);
        echo "<br/>";        
        
        
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."withdrawal (
  `withdrawal_id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `amount` float(14,4) NOT NULL,
  `current_balance` float(14,4) NOT NULL,
  `balance` float(14,4) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `account` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `swift_code` varchar(100) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `bank_country` varchar(50) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `viewed` int(2) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL,
  `date_to_process` date NOT NULL,
  `date_process` datetime NOT NULL,
  `api_raw_response` text NOT NULL,
  `withdrawal_token` text NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `bank_type` varchar(255) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`withdrawal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table withdrawal..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";              

      $new_field=array(         
          'bank_type'=>"varchar(255) NOT NULL DEFAULT 'default'"          
        );	        
        $this->alterTable('withdrawal',$new_field);
        echo "<br/>";        

        $new_field=array(         
          'contact_email'=>"varchar(255) NOT NULL",
          'session_token'=>"varchar(255) NOT NULL",
        );	        
        $this->alterTable('merchant_user',$new_field);
        echo "<br/>";        
                
        
        $new_field=array(         
          'abn'=>"varchar(255) NOT NULL",
          'session_token'=>"varchar(255) NOT NULL" ,
          'commision_type'=>"varchar(50) NOT NULL DEFAULT 'percentage'"     
        );	        
        $this->alterTable('merchant',$new_field);
        echo "<br/>";      
        
        
$stmt="
CREATE TABLE IF NOT EXISTS ".$prefix."fax_package (
`fax_package_id` int(14) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`description` text NOT NULL,
`price` float(14,4) NOT NULL,
`promo_price` float(14,4) NOT NULL,
`fax_limit` int(14) NOT NULL,
`sequence` int(14) NOT NULL,
`status` varchar(100) NOT NULL,
`date_created` datetime NOT NULL,
`date_modified` datetime NOT NULL,
`ip_address` varchar(100) NOT NULL,
PRIMARY KEY (`fax_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table fax_package..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";                      


$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."fax_package_trans (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `fax_package_id` int(14) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `package_price` float(14,4) NOT NULL,
  `fax_limit` int(14) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) NOT NULL,
  `payment_gateway_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

echo "Creating Table fax_package_trans..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";                      

$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."fax_broadcast (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `faxno` varchar(50) NOT NULL,
  `recipname` varchar(32) NOT NULL,
  `faxurl` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `jobid` varchar(255) NOT NULL,
  `api_raw_response` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_process` datetime NOT NULL,
  `date_postback` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";

echo "Creating Table fax_broadcast..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."shipping_rate (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(14) NOT NULL,
  `distance_from` int(14) NOT NULL,
  `distance_to` int(14) NOT NULL,
  `shipping_units` varchar(5) NOT NULL,
  `distance_price` float(14,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

echo "Creating Table shipping_rate..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

       $new_field=array(         
          'spicydish'=>"int(2) NOT NULL DEFAULT '1'",
          'spicydish_notes'=>"text NOT NULL" ,
          'dish'=>"text NOT NULL",
          'category_name_trans'=>"text NOT NULL",
          'category_description_trans'=>"text NOT NULL",
        );	        
        $this->alterTable('category',$new_field);
        echo "<br/>";  
        
        $new_field=array(         
          'session_token'=>"varchar(255) NOT NULL",
          'last_login'=>"datetime NOT NULL",
          'user_access'=>"text NOT NULL"
        );	        
        $this->alterTable('admin_user',$new_field);
        echo "<br/>";  

        
$stmt="
create OR REPLACE VIEW ".$table_prefix."view_order_details as
select a.* ,

(
select merchant_id 	
from
".$table_prefix."order
where
order_id=a.order_id
limit 0,1
) as merchant_id,

(
select status 	
from
".$table_prefix."order
where
order_id=a.order_id
limit 0,1
) as status,

(
select date_created 	
from
".$table_prefix."order
where
order_id=a.order_id
limit 0,1
) as date_created

from
".$table_prefix."order_details a
";
echo "Creating Table view_order_details..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

/**ADD INDEX*/
$this->addIndex('option','merchant_id');
$this->addIndex('rating','merchant_id');
$this->addIndex('rating','client_id');
$this->addIndex('order','merchant_id');
$this->addIndex('order','client_id');


$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_delivery_address (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `contact_phone` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table order_delivery_address..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";


        $new_field=array(         
          'fee'=>"float(14,4) NOT NULL"          
        );	 
        $this->alterTable('package_trans',$new_field);
        echo "<br/>";  
        
        $new_field=array(         
          'contact_phone'=>"varchar(100) NOT NULL" 
        );	 
        $this->alterTable('order_delivery_address',$new_field);
        echo "<br/>";  
        
        $new_field=array(         
          'status'=>"varchar(100) NOT NULL DEFAULT 'publish'",
          'subcategory_name_trans'=>"text NOT NULL",
          'subcategory_description_trans'=>"text NOT NULL",
        );	 
        $this->alterTable('subcategory',$new_field);
        echo "<br/>";       
        
        /**check if has a permission set for admin*/
        $stmt="SELECT * FROM
        {{admin_user}}
        WHERE
        user_access<>''      
        ";        
        if ( !$DbExt->rst($stmt)){
        	$user_all_access='["autologin","dashboard","merchant","sponsoredMerchantList","packages","Cuisine","dishes","OrderStatus","settings","commisionsettings","voucher","merchantcommission","withdrawal","incomingwithdrawal","withdrawalsettings","emailsettings","emailtpl","customPage","Ratings","ContactSettings","SocialSettings","ManageCurrency","ManageLanguage","Seo","analytics","customerlist","subscriberlist","reviews","bankdeposit","paymentgatewaysettings","paymentgateway","paypalSettings","stripeSettings","mercadopagoSettings","sisowsettings","payumonenysettings","obdsettings","payserasettings","payondelivery","barclay","epaybg","authorize","sms","smsSettings","smsPackage","smstransaction","smslogs","fax","faxtransaction","faxpackage","faxlogs","faxsettings","reports","rptMerchantReg","rptMerchantPayment","rptMerchanteSales","rptmerchantsalesummary","rptbookingsummary","userList"]
';
        	$stmt_update_admin="UPDATE {{admin_user}}
        	SET user_access='$user_all_access'
        	";        	
        	$DbExt->qry($stmt_update_admin);
        }
        
        
        $alter_table="
        ALTER TABLE {{currency}} CHANGE 
       `currency_symbol` `currency_symbol` VARCHAR( 100 ) 
        CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ";                                         
        dump($alter_table);
        $DbExt->qry($alter_table);
        
        
        
$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."dishes (
  `dish_id` int(14) NOT NULL AUTO_INCREMENT,
  `dish_name` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`dish_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table dishes..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."voucher_new (
  `voucher_id` int(14) NOT NULL AUTO_INCREMENT,
  `voucher_owner` varchar(255) NOT NULL DEFAULT 'merchant',
  `merchant_id` int(14) NOT NULL,
  `joining_merchant` text NOT NULL,
  `voucher_name` varchar(255) NOT NULL,
  `voucher_type` varchar(255) NOT NULL,
  `amount` float(14,4) NOT NULL,
  `expiration` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `used_once` int(1) NOT NULL DEFAULT '1',  
  PRIMARY KEY (`voucher_id`),
  KEY `voucher_name` (`voucher_name`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";
echo "Creating Table voucher_new..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";
        

$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."address_book (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `client_id` int(14) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `as_default` int(1) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table address_book..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";
        
       $new_field=array(         
          'size_name_trans'=>"text NOT NULL"          
        );	 
        $this->alterTable('size',$new_field);
        echo "<br/>";       
        
        $new_field=array(         
          'ingredients_name_trans'=>"text NOT NULL"          
        );	 
        $this->alterTable('ingredients',$new_field);
        echo "<br/>";     
        
        $new_field=array(         
          'sub_item_name_trans' =>"text NOT NULL",
          'item_description_trans'=>"text NOT NULL"          
        );	 
        $this->alterTable('subcategory_item',$new_field);
        echo "<br/>";     
        
        $new_field=array(         
          'cooking_name_trans'=>"text NOT NULL"          
        );	 
        $this->alterTable('cooking_ref',$new_field);
        echo "<br/>";     
        
        
        $stmt_alter="
        ALTER TABLE ".$table_prefix."languages CHANGE 
        `language_code` `language_code` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 
        ";
        dump($stmt_alter);
        $DbExt->qry($stmt_alter);
        
        
        $new_field=array(         
          'used_once'=>"int(1) NOT NULL DEFAULT '1'"          
        );	 
        $this->alterTable('voucher_new',$new_field);
        echo "<br/>";     
        
        /** new fields for update version 2.4*/
        $new_field=array(         
          'order_id'=>"varchar(14) NOT NULL"          
        );	 
        $this->alterTable('review',$new_field);
        echo "<br/>";     
                
$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_history (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `order_id` int(14) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table order_history..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."order_sms (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(50) NOT NULL,
  `code` int(4) NOT NULL,
  `session` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";
echo "Creating Table order_sms..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";

/*2.6*/
$stmt="
CREATE TABLE IF NOT EXISTS ".$table_prefix."zipcode (
  `zipcode_id` int(14) NOT NULL AUTO_INCREMENT,
  `zipcode` varchar(255) NOT NULL,
  `country_code` varchar(5) NOT NULL,
  `city` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `stree_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`zipcode_id`),
  KEY `country_code` (`country_code`),
  KEY `area` (`area`),
  KEY `stree_name` (`stree_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
echo "Creating Table zipcode..<br/>";	
$DbExt->qry($stmt);
echo "(Done)<br/>";
        

        /** new fields for update version 3.1*/
        $new_field=array(         
          'cuisine_name_trans'=>"text NOT NULL"          
        );	 
        $this->alterTable('cuisine',$new_field);
        echo "<br/>";     
        
		echo "FINISH UPDATE(s)";
	}	
	
	public function addIndex($table='',$index_name='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		
		$table=$prefix.$table;
		
		$stmt="
		SHOW INDEX FROM $table
		";		
		$found=false;
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {				
				if ( $val['Key_name']==$index_name){
					$found=true;
					break;
				}
			}
		} 
		
		if ($found==false){
			echo "create index<br>";
			$stmt_index="ALTER TABLE $table ADD INDEX ( $index_name ) ";
			dump($stmt_index);
			$DbExt->qry($stmt_index);
			echo "Creating Index $index_name on $table <br/>";		
            echo "(Done)<br/>";		
		} else echo 'index exist<br>';
	}
	
	public function alterTable($table='',$new_field='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		$existing_field='';
		if ( $res = Yii::app()->functions->checkTableStructure($table)){
			foreach ($res as $val) {								
				$existing_field[$val['Field']]=$val['Field'];
			}			
			foreach ($new_field as $key_new=>$val_new) {				
				if (!in_array($key_new,$existing_field)){
					echo "Creating field $key_new <br/>";
					$stmt_alter="ALTER TABLE ".$prefix."$table ADD $key_new ".$new_field[$key_new];
					dump($stmt_alter);
				    if ($DbExt->qry($stmt_alter)){
					   echo "(Done)<br/>";
				   } else echo "(Failed)<br/>";
				} else echo "Field $key_new already exist<br/>";
			}
		}
	}
	
} /*END CLASS*/