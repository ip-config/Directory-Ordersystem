<?php
$kr_merchant_slug=isset($_SESSION['kr_merchant_slug'])?$_SESSION['kr_merchant_slug']:'';

if (isset($_SESSION['search_type'])){
	switch ($_SESSION['search_type']) {
		case "kr_search_foodname":			
			$search_key='foodname';
			$search_str= isset($_SESSION['kr_search_foodname'])?$_SESSION['kr_search_foodname']:'';
			break;
			
		case "kr_search_category":			
			$search_key='category';
			$search_str=isset($_SESSION['kr_search_category'])?$_SESSION['kr_search_category']:'';
			break;
			
		case "kr_search_restaurantname":
			$search_str=isset($_SESSION['kr_search_restaurantname'])?$_SESSION['kr_search_restaurantname']:'';
			$search_key='restaurant-name';
			break;	
		
		case "kr_search_streetname":
			$search_str=isset($_SESSION['kr_search_streetname'])?$_SESSION['kr_search_streetname']:'';
			$search_key='street-name';
			break;	

		case "kr_postcode":	
		    $search_str=isset($_SESSION['kr_postcode'])?$_SESSION['kr_postcode']:'';
		    $search_key='zipcode';
			break;	
			
		default:
			$search_str=isset($_SESSION['kr_search_address'])?$_SESSION['kr_search_address']:'';
			$search_key='s';
			break;
	}
}
?>

<?php if ($show_bar):?>
<div class="order-progress-bar">
  <div class="container">
      <div class="row">
      
        <div class="col-md-3 col-xs-3 ">
          <a class="active" href="<?php echo Yii::app()->createUrl('/store')?>"><?php echo t("Search")?></a>  
        </div>
        
        <div class="col-md-3 col-xs-3 ">
           <a class="<?php echo $step>=2?"active":"inactive"; echo $step==2?" current":"";?>" 
           href="<?php echo Yii::app()->createUrl('store/searcharea',array($search_key=>$search_str))?>">
           <?php echo t("Pick Merchant")?></a>
        </div>
        
        <div class="col-md-3 col-xs-3">
        <a class="<?php echo $step>=3?"active":"inactive"; echo $step==3?" current":"";?> "
         href="<?php echo Yii::app()->createUrl('/store/menu/merchant/'.$kr_merchant_slug)?>">
        <?php echo t("Create your order")?></a>
        </div>
        
        <div class="col-md-3 col-xs-3 ">
        <a class="<?php echo $step>=4?"active":"inactive"; echo $step==4?" current":"";?> "
         href="javascript:;"><?php echo t("Checkout")?></a>
        </div>
      
        
      </div><!-- row-->
  </div> <!--container-->
  
   <div class="border progress-dot mytable">    
     <a href="<?php echo Yii::app()->createUrl('/store')?>" class="mycol selected" ><i class="ion-record"></i></a>
     <a href="javascript:;" class="mycol 
     <?php echo $step>=2?"selected":'';?>" ><i class="ion-record"></i></a>
     
     <a href="javascript:;" class="mycol <?php echo $step>=3?"selected":'';?>" ><i class="ion-record"></i></a>
     
     <a href="javascript:;" class="mycol <?php echo $step>=4?"selected":'';?>"><i class="ion-record"></i></a>
     
  </div> <!--end progress-dot-->
  
</div> <!--order-progress-bar-->
<?php endif;?>