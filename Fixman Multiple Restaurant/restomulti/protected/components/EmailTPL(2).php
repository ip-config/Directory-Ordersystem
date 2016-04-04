<?php
class EmailTPL extends CApplicationComponent
{
	
	public static function forgotPass($data='',$token='')
	{
      $website_title=Yii ::app()->functions->getOptionAdmin('website_title');
      $url=Yii::app()->getBaseUrl(true)."/store/forgotPassword/?token=".$token;      
	  return <<<HTML
	  <p>Hi $data[first_name]</p>
	  <br/>
	  <p>Click on the link below to change your password.</p>
	  <p><a href="$url">$url</a></p>
	  <p>Thank you.</p>
	  <p>- $website_title</p>
HTML;
	}
	
	public static function merchantActivationCode($data='')
	{
	$website_url=Yii::app()->getBaseUrl(true)."/merchant";
    $website_title=Yii::app()->functions->getOptionAdmin('website_title');
    
    $email_tpl_activation=Yii::app()->functions->getOptionAdmin('email_tpl_activation');    
    if (!empty($email_tpl_activation)){
    	$email_tpl_activation=Yii::app()->functions->smarty("restaurant_name",$data['restaurant_name'],$email_tpl_activation); 
    	$email_tpl_activation=Yii::app()->functions->smarty("activation_key",$data['activation_key'],$email_tpl_activation); 
    	$email_tpl_activation=Yii::app()->functions->smarty("website_title",$website_title,$email_tpl_activation); 
    	$email_tpl_activation=Yii::app()->functions->smarty("website_url",$website_url,$email_tpl_activation); 
    	return $email_tpl_activation;
    }
    
	return <<<HTML
	<p>hi $data[restaurant_name]<br/></p>
	<p>Thank you for joining us!</p>
	<p>Your activation code is $data[activation_key]</p>
	
	<p>Click <a href="$website_url">here</a> to login or visit $website_url</p>
	
	<p>Thank you.</p>
	<p>- $website_title</p>
HTML;
	}
	
	public static function merchantActivationCodePlain()
	{		
	return <<<HTML
	<p>hi {restaurant_name}<br/></p>
	<p>Thank you for joining us!</p>
	<p>Your activation code is {activation_key}</p>
	
	<p>Click <a href="{website_url}">here</a> to login or visit {website_url}</p>
	
	<p>Thank you.</p>
	<p>- {website_title}</p>
HTML;
	}	
	
	public static function merchantForgotPass($data='',$code='')
	{
	  $website_title=Yii::app()->functions->getOptionAdmin('website_title');
	  
	  $email_tpl_forgot=Yii::app()->functions->getOptionAdmin('email_tpl_forgot');	
	  if (!empty($email_tpl_forgot)){
	  	  $email_tpl_forgot=Yii::app()->functions->smarty("restaurant_name",$data['restaurant_name'],$email_tpl_forgot); 
	  	  $email_tpl_forgot=Yii::app()->functions->smarty("verification_code",$code,$email_tpl_forgot); 
	  	  $email_tpl_forgot=Yii::app()->functions->smarty("website_title",$website_title,$email_tpl_forgot); 	  	  
	  	  
	  }
			  
	  return <<<HTML
	  <p>hi $data[restaurant_name]<br/></p>
	  <p>Your verification code is $code</p>
	  <p>Thank you.</p>
	<p>- $website_title</p>
HTML;
	}
	
	public static function merchantForgotPassPlain()
	{	 
	  return <<<HTML
	  <p>hi {restaurant_name}<br/></p>
	  <p>Your verification code is {verification_code}</p>
	  <p>Thank you.</p>
	<p>- {website_title}</p>
HTML;
	}	
	
	public static function salesReceipt($data='',$item_details='')
	{				
		$tr="";
		if (is_array($data) && count($data)>=1){
			foreach ($data as $val) {				
				$tr.="<tr>";
				$tr.="<td>".$val['label']."</td>";
				$tr.="<td>".$val['value']."</td>";
				$tr.="</tr>";
			}
		}
		
		$mid=isset($item_details['total']['mid'])?$item_details['total']['mid']:'';
		//dump($mid);
		
		$tr.="<tr>";
		$tr.="<td colspan=\"2\">&nbsp;</td>";
		$tr.="</tr>";
		if (isset($item_details['item'])){
			if (is_array($item_details['item']) && count($item_details['item'])>=1){
				foreach ($item_details['item'] as $item) {
					//dump($item);
					$notes='';
					$item_total=$item['qty']*$item['discounted_price'];
					if (!empty($item['order_notes'])){
					    $notes="<p>".$item['order_notes']."</p>";
					}
					$cookref='';
					if (!empty($item['cooking_ref'])){
					    $cookref="<p>".$item['cooking_ref']."</p>";
					}
					$size='';
					if (!empty($item['size_words'])){
					    $size="<p>".$item['size_words']."</p>";
					}
					$tr.="<tr>";
				    $tr.="<td>".$item['qty']." ".$item['item_name'].$size.$notes.$cookref."</td>";
				    $tr.="<td>".prettyFormat($item_total,$mid)."</td>";
				    $tr.="</tr>";
				    
				    if (isset($item['sub_item'])){
				    	foreach ($item['sub_item'] as $itemsub) {				    		
				    		$subitem_total=$itemsub['addon_qty']*$itemsub['addon_price'];				    		
				    		$tr.="<tr>";
				            $tr.="<td style=\"text-indent:10px;\">".$itemsub['addon_name']."</td>";
				            $tr.="<td>".prettyFormat($subitem_total,$mid)."</td>";
				            $tr.="</tr>";
				    	}
				    }
				    
				}
			}
		}
		$tr.="<tr>";
		$tr.="<td colspan=\"2\">&nbsp;</td>";
		$tr.="</tr>";
		//dump($item_details['total']);		
		if (isset($item_details['total'])){
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Subtotal").":</td>";
			$tr.="<td>".prettyFormat($item_details['total']['subtotal'],$mid)."</td>";
			$tr.="</tr>";
			
			if (!empty($item_details['total']['delivery_charges'])):
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Delivery Fee").":</td>";
			$tr.="<td>".prettyFormat($item_details['total']['delivery_charges'],$mid)."</td>";
			$tr.="</tr>";
			endif;
			
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Tax")." ".$item_details['total']['tax_amt']."%</td>";
			$tr.="<td>".prettyFormat($item_details['total']['taxable_total'],$mid)."</td>";
			$tr.="</tr>";
			
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Total").":</td>";
			$tr.="<td>".$item_details['total']['curr'].prettyFormat($item_details['total']['total'],$mid)."</td>";
			$tr.="</tr>";
		}
		ob_start();
		?>
		<h3><?php echo Yii::t("default","Order Details")?></h3>
		<table border="0">
		<?php echo $tr;?>		
		</table>
		<?php	
		$receipt = ob_get_contents();
        ob_end_clean();
        return $receipt;
	}
	
	public function receiptTPL()
	{
		return <<<HTML
<p>Dear {customer-name},</p>
<br/><br/>
<p> Thank you for shopping at Karendera. We hope you enjoy your new purchase! Your order number is {receipt-number}. We have included your order receipt and delivery details below:	</p>
<br/>
 {receipt}	
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}
	
	public function bookingApproved()
	{
		return <<<HTML
<p>Dear {customer-name},</p>
<br/><br/>
<p> Thank you. Your booking has been approved.</p>
<p>{message}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}	
	
	public function bookingDenied()
	{
		return <<<HTML
<p>Dear {customer-name},</p>
<br/><br/>
<p> We regret to inform you that your table booking has been denied.</p>
<p>{message}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}		
	
	public function bookingTPL()
	{
		return <<<HTML
<p>Dear admin,</p>
<br/>
<p> New table booking has been receive.</p>
<p>{booking-information}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}			
	

	public function bankDepositTPL()
	{
		return <<<HTML
<p><strong>Deposit Instructions</strong></p>
<br/>
<p>
Please deposit {amount} to :
</p>

<p>
Bank : Your bank name<br/>
Account Number : Your bank account number<br/>
Account Name : Your bank account name<br/>
</p>

<p>When deposit is completed {verify-payment-link}</p>

<br/><br/>
<p> Kind Regards</p>
HTML;
	}	
	
	public function bankDepositedReceive()
	{
		return <<<HTML
<p>hi Admin,</p>
<br/><br/>
<p>There is new submitted offline bank deposited. you can check this via admin panel</p>
<br/>
	
<br/><br/>
HTML;
	}			
	
	public static function adminForgotPassword($newpass='')
	{	 
	  return <<<HTML
	  <p>hi <br/></p>
	  <p>Your password has been reset to : $newpass</p>
	  <p>Thank you.</p>	
HTML;
	}	
	
	public static function merchantChangeStatus()
	{	 
	  return <<<HTML
	  <p>hi {owner_name}<br/></p>
	  <p>your merchant {restaurant_name} has change status to {status}</p>
	  <br/>
	  <p>{website_title}</p>
	  <p>Thank you.</p>	
HTML;
	}		
	
	public function receiptMerchantTPL()
	{
		return <<<HTML
<p>hi admin,</p>
<br/>
<p>There is a new order with the reference number {receipt-number} from customer {customer-name}</p>
<br/>
 {receipt}	
	
<br/><br/>
<p><a href="{confirmation-link}">Click here</a> to accept the order<br/>
or simply visit this link {confirmation-link}
</p>
<br/>
<p> Kind Regards</p>
HTML;
	}	
	
} /*END CLASS*/