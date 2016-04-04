<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$db_ext=new DbExt;
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
$is_merchant=true;

$amount_to_pay=0;
$payment_description=Yii::t("default",'Payment to merchant');

$back_url=Yii::app()->request->baseUrl."/store/PaymentOption";
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{order}}');

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){		
	
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
	$payment_description.=isset($data['merchant_name'])?" ".$data['merchant_name']:'';	
	
	$amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->standardPrettyFormat($data['total_w_tax']):'';
	
	/*dump($payment_description);
	dump($amount_to_pay);*/
	
} else $error=Yii::t("default","Failed. Cannot process payment");  
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using PayUMoney")?></h1>
          <div class="box-grey rounded">	     
          
          <?php if ( !empty($error)):?>
               <p class="text-danger"><?php echo $error;?></p>  
          <?php else :?>
          
          <?php require_once("payu.php")?>
		  <?php 
		  if ( $success==TRUE){
		   	  //dump($_POST);
		   	  $data_post=$_POST;	
		   	  $params_logs=array(
		      'order_id'=>$_GET['id'],
		      'payment_type'=>Yii::app()->functions->paymentCode('payumoney'),
		      'raw_response'=>json_encode($data_post),
		      'date_created'=>date('c'),
		      'ip_address'=>$_SERVER['REMOTE_ADDR'],
		      'payment_reference'=>$data_post['txnid']
		    );
		    $db_ext->insertData("{{payment_order}}",$params_logs);
		    
		    $params_update=array( 'status'=>'paid');	        
		    $db_ext->updateData("{{order}}",$params_update,'order_id',$_GET['id']);
		    
		    /*POINTS PROGRAM*/ 
		    if (Yii::app()->hasModule('pointsprogram')){
			   PointsProgram::updatePoints($_GET['id']);
		    }
		    		    
		    header('Location: '.Yii::app()->createUrl('/store/receipt/id/'.$_GET['id'] ));
		    die();						
		  }
		  ?>
          
          <?php endif;?>            
          
            <div class="top25">
		     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
             <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
            </div>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->
