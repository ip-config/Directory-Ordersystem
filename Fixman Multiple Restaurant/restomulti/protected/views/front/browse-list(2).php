
<div class="result-merchant infinite-container" id="restuarant-list">
<?php foreach ($list['list'] as $val):?>
<?php
$merchant_id=$val['merchant_id'];
$ratings=Yii::app()->functions->getRatings($merchant_id);   
$merchant_delivery_distance=getOption($merchant_id,'merchant_delivery_miles');
$distance_type='';

/*fallback*/
if ( empty($val['lat'])){
	if ($lat_res=Yii::app()->functions->geodecodeAddress($val['merchant_address'])){        
		$val['latitude']=$lat_res['lat'];
		$val['lontitude']=$lat_res['long'];
	} 
}
?>
<div class="infinite-item">
   <div class="inner">
   
   <?php if ( $val['is_sponsored']==2):?>
       <div class="ribbon"><span>Sponsored</span></div>
    <?php endif;?>
    
    <?php if ($offer=FunctionsV3::getOffersByMerchant($merchant_id)):?>
       <div class="ribbon-offer"><span><?php echo $offer;?></span></div>
    <?php endif;?>
   
     <div class="row"> 
        <div class="col-md-6 borderx">
        
         <div class="row borderx" style="padding: 10px;padding-bottom:0;">
             <div class="col-md-3 borderx ">
		       <a href="<?php echo Yii::app()->createUrl('store/menu/merchant/'.$val['restaurant_slug'])?>">
		        <img class="logo-small"src="<?php echo FunctionsV3::getMerchantLogo($merchant_id);?>">
		       </a>
		       <div class="top10"><?php echo FunctionsV3::displayServicesList($val['service'])?></div>		               
		       
		       <div class="top10">
		         <?php FunctionsV3::displayCashAvailable($merchant_id)?>
		       </div>
		       
		     </div> <!--col-->
		     <div class="col-md-9 borderx">
		     
		     <div class="mytable">
		         <div class="mycol">
		            <div class="rating-stars" data-score="<?php echo $ratings['ratings']?>"></div>   
		         </div>
		         <div class="mycol">
		            <p><?php echo $ratings['votes']." ".t("Reviews")?></p>
		         </div>
		         <div class="mycol">
		            <?php echo FunctionsV3::merchantOpenTag($merchant_id)?>                
		         </div>		         		         		         
		      </div> <!--mytable-->
	       
		      <h2><?php echo $val['restaurant_name']?></h2>
	          <p class="merchant-address concat-text"><?php echo $val['merchant_address']?></p>   
	          
	          <p class="cuisine bold">
              <?php echo FunctionsV3::displayCuisine($val['cuisine']);?>
              </p>                
		     
              <p><?php echo t("Minimum Order").": ".FunctionsV3::prettyPrice($val['minimum_order'])?></p>
              
              <p><?php echo t("Delivery Est")?>: <?php echo FunctionsV3::getDeliveryEstimation($merchant_id)?></p>
              
              <p>
		        <?php 
		        if (!empty($merchant_delivery_distance)){
		        	echo t("Delivery Distance").": ".$merchant_delivery_distance." $distance_type";
		        } else echo  t("Delivery Distance").": ".t("not available");
		        ?>
		       </p>
		       		       
		        <p class="top15"><?php echo FunctionsV3::getFreeDeliveryTag($merchant_id)?></p>
		        
		        <a href="<?php echo Yii::app()->createUrl('store/menu/merchant/'.$val['restaurant_slug'])?>" 
		        class="orange-button rounded3 medium bottom10 inline-block">
		        <?php echo t("View menu")?>
		        </a>
		                      
		     </div> <!--col-->
         </div> <!--row-->         
         
        </div> <!--col-->
        
        <!--MAP-->
        <div class="col-md-6 with-padleft" style="padding-left:0; border-left:1px solid #C9C7C7;" >
          <div class="browse-list-map active" 
		        data-lat="<?php echo $val['latitude']?>" data-long="<?php echo $val['lontitude']?>">
             
          </div> <!--browse-list-map-->
        </div> <!--col-->
        
     </div> <!--row-->
   </div> <!--inner-->
</div> <!--infinite-item-->


<div class="search-result-loader">
    <i></i>
    <p><?php echo t("Loading more restaurant...")?></p>
 </div> <!--search-result-loader-->

<?php             
if (isset($cuisine_page)){
	$page_link=Yii::app()->createUrl('store/cuisine/'.$category.'/?');
} else $page_link=Yii::app()->createUrl('store/browse/?tab='.$tabs);

 echo CHtml::hiddenField('current_page_url',$page_link);
 require_once('pagination.class.php'); 
 $attributes                 =   array();
 $attributes['wrapper']      =   array('id'=>'pagination','class'=>'pagination');			 
 $options                    =   array();
 $options['attributes']      =   $attributes;
 $options['items_per_page']  =   FunctionsV3::getPerPage();
 $options['maxpages']        =   1;
 $options['jumpers']=false;
 $options['link_url']=$page_link.'&page=##ID##';			
 $pagination =   new pagination( $list['total'] ,((isset($_GET['page'])) ? $_GET['page']:1),$options);		
 $data   =   $pagination->render();
 ?>             

<?php endforeach;?>
</div> <!--result-merchant-->