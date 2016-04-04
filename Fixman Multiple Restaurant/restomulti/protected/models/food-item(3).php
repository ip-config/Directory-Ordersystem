<?php
$row='';
$item_data='';
$price_select='';
$size_select='';
if (array_key_exists("row",(array)$this->data)){
	$row=$this->data['row'];	
	$item_data=$_SESSION['kr_item'][$row];
	//dump($item_data);
	$price=Yii::app()->functions->explodeData($item_data['price']);
	if (is_array($price) && count($price)>=1){
		$price_select=isset($price[0])?$price[0]:'';
		$size_select=isset($price[1])?$price[1]:'';
	}
	$row++;
}


$data=Yii::app()->functions->getItemById($this->data['item_id']);
//dump($data);
$disabled_website_ordering=Yii::app()->functions->getOptionAdmin('disabled_website_ordering');
$hide_foodprice=Yii::app()->functions->getOptionAdmin('website_hide_foodprice');
echo CHtml::hiddenField('hide_foodprice',$hide_foodprice);
?>

<?php if (is_array($data) && count($data)>=1):?>
<?php 
$data=$data[0];

?>


<form class="frm-fooditem" id="frm-fooditem" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addToCart')?>
<?php echo CHtml::hiddenField('item_id',$this->data['item_id'])?>
<?php echo CHtml::hiddenField('row',isset($row)?$row:"")?>
<?php echo CHtml::hiddenField('merchant_id',isset($data['merchant_id'])?$data['merchant_id']:'')?>


<?php echo CHtml::hiddenField('discount',isset($data['discount'])?$data['discount']:"" )?>
<?php echo CHtml::hiddenField('currentController','store')?>

<?php 
//dump($data);
/** two flavores */
if ($data['two_flavors']==2){
	$data['prices'][0]=array(
	  'price'=>0,
	  'size'=>''
	);
	//$data['prices']=='';
	echo CHtml::hiddenField('two_flavors',$data['two_flavors']);
}
//dump($data);
?>

<div class="view-item-wrap">
  
  <div class="uk-grid">
	  <div class="uk-width-1-2" style="width:120px;">
	    <?php if ( !empty($data['photo'])):?>
	    <img src="<?php echo baseUrl()."/upload/".$data['photo']?>" alt="<?php echo $data['item_name']?>" title="<?php echo $data['item_name']?>" class="uk-thumbnail uk-thumbnail-small">
	    <?php else :?>
	    <?php endif;?>
	  </div>
	  <div class="uk-width-1-2">
	  <h1 class="uk-h2"><?php echo qTranslate($data['item_name'],'item_name',$data)?></h1>
	  <?php //echo Widgets::displaySpicyIcon($data['spicydish'],'',$data['merchant_id']);?> 
	  <?php echo Widgets::displaySpicyIconNew($data['dish']);?> 
	  <p class="uk-text-muted"><?php echo qTranslate($data['item_description'],'item_description',$data)?></p>
	  </div>
  </div> <!--uk-grid-->
   
  <?php if (getOption($data['merchant_id'],'disabled_food_gallery')!=2):?>    
  <?php $gallery_photo=!empty($data['gallery_photo'])?json_decode($data['gallery_photo']):false; ?>
  <?php if (is_array($gallery_photo) && count($gallery_photo)>=1):?>
  <div class="section">
    <h3 class="uk-h3"><?php echo Yii::t("default","Gallery")?></h3>    
    <ul class="food-gallery-wrap">
      <?php foreach ($gallery_photo as $gal_val):?>
      <li>
      <a href="<?php echo websiteUrl()."/upload/$gal_val"?>">
        <div class="mini-pic" style="background:url('<?php echo websiteUrl()."/upload/$gal_val"?>')"></div>
        <img style="display:none;" src="<?php echo websiteUrl()."/upload/$gal_val"?>" alt="" title="">
      </a>
      </li>
      <?php endforeach;?>
      <div class="clear"></div>
    </ul>
  </div>  
  <?php endif;?>
  <?php endif;?>
    
  <?php if (is_array($data['prices']) && count($data['prices'])>=1):?>  
  <div class="section">
    <h3 class="uk-h3"><?php echo Yii::t("default","Price")?></h3>    
    <?php foreach ($data['prices'] as $price):?>
    <?php $price['price']=Yii::app()->functions->unPrettyPrice($price['price'])?>
    <li>
      <div class="price">        
        <?php if ( !empty($price['size'])):?>
        <span class="size">          
          <?php echo CHtml::radioButton('price',
          $size_select==$price['size']?true:false
          ,array(
            'value'=>$price['price']."|".$price['size'],
            'class'=>"price_cls item_price"
          ))?>
          <?php echo qTranslate($price['size'],'size',$price)?>
        </span>
        <?php else :?>
        <span class="size">
        <?php echo CHtml::radioButton('price',
            count($price['price'])==1?true:false
            ,array(
            'value'=>$price['price'],
            'class'=>"item_price"
          ))?>
        </span> 
        <?php endif;?>
        <?php if (isset($price['price'])):?>        
	        <?php if (is_numeric($data['discount'])):?>
	          <span class="price normal-price">
	          <?php echo displayPrice(getCurrencyCode(),Yii::app()->functions->prettyFormat($price['price']))?>
	          </span>
	          <span class="price sale-price"><?php 
	          echo displayPrice(getCurrencyCode(),Yii::app()->functions->prettyFormat(($price['price']-$data['discount'])))?>
	          </span>
	        <?php else :?>
	          <span class="price">
	            <?php echo displayPrice(getCurrencyCode(),Yii::app()->functions->prettyFormat($price['price']))?>
	          </span>
	        <?php endif;?>
        <?php endif;?>
      </div>
    </li>    
    <?php endforeach;?>
    <div class="clear"></div>
  </div> <!--section-->
  <?php endif;?>
  
  <?php if (is_array($data['prices']) && count($data['prices'])>=1):?>
  <div class="section">
    <?php if ( $disabled_website_ordering==""):?>
    <h3 class="uk-h3"><?php echo Yii::t("default","Quantity")?></h3>    
    <div class="quantity-wrap">
      <a href="javascript:;" class="qty-minus" ><i class="fa fa-minus"></i></a>
      <?php echo CHtml::textField('qty',
      isset($item_data['qty'])?$item_data['qty']:1
      ,array(
      'class'=>"uk-form-width-mini numeric_only left qty",      
      ))?>
      <a href="javascript:;" class="qty-plus"><i class="fa fa-plus"></i></a>
      <a href="javascript:;" class="special-instruction"><?php echo Yii::t("default","Special Instructions")?></a>
      <div class="clear"></div>
    </div>
    <?php endif;?>
  </div> <!--section-->
  
  <div class="notes-wrap">
  <div class="uk-form-row">
  <?php echo CHtml::textArea('notes',
  isset($item_data['notes'])?$item_data['notes']:""
  ,array(
   'class'=>'uk-width-1-1',
   'placeholder'=>Yii::t("default","Special Instructions")
  ))?>
  </div>
  </div> <!--END notes-wrap-->
  
  <?php else :?>
  
  <?php endif;?>
    
  
  <?php if (isset($data['cooking_ref'])):?>
  <?php if (is_array($data['cooking_ref']) && count($data['cooking_ref'])>=1):?>
  <div class="section">
    <h3 class="uk-h3"><?php echo Yii::t("default","Cooking Preference")?></h3>    
    <?php foreach ($data['cooking_ref'] as $val):?>
    <?php $item_data['cooking_ref']=isset($item_data['cooking_ref'])?$item_data['cooking_ref']:''; ?>
    <li>
     <div class="price" id="price_wrap">
       <span class="size">
       <?php echo CHtml::radioButton('cooking_ref',
       $item_data['cooking_ref']==$val?true:false
       ,array(
         'value'=>$val
       ))?>&nbsp;             
       <?php echo qTranslate($val,'cooking_ref',$data);?>
       </span>
     </div>
    </li>
    <?php endforeach;?>
    <div class="clear"></div>
  </div>
  <?php endif;?>
  <?php endif;?>
  
  
  <!--Ingredients-->  
  <?php if (isset($data['ingredients'])):?>
  <?php if (is_array($data['ingredients']) && count($data['ingredients'])>=1):?>
  <div class="section">
    <h3 class="uk-h3"><?php echo Yii::t("default","Ingredients")?></h3>    
    <?php foreach ($data['ingredients'] as $val):?>
    <?php $item_data['ingredients_id']=isset($item_data['ingredients_id'])?$item_data['ingredients_id']:''; ?>
    <li>
     <div class="price" id="price_wrap">
       <span class="size">
       <?php echo CHtml::checkbox('ingredients[]',
       in_array($val,(array)$item_data['ingredients'])?true:false
       ,array(
         'value'=>$val
       ))?>&nbsp;             
       <?php echo $val;?>
       </span>
     </div>
    </li>
    <?php endforeach;?>
    <div class="clear"></div>
  </div>
  <?php endif;?>
  <?php endif;?>  
  <!--END Ingredients-->
    
  
  <?php if (isset($data['addon_item'])):?>
  <?php if (is_array($data['addon_item']) && count($data['addon_item'])>=1):?>
  <?php foreach ($data['addon_item'] as $val): //dump($val);?>
  <div class="section section-addon">
  
    <?php echo CHtml::hiddenField('require_addon_'.$val['subcat_id'],$val['require_addons'],array(
     'class'=>"require_addon require_addon_".$val['subcat_id'],
     'data-id'=>$val['subcat_id'],
     'data-name'=>strtoupper($val['subcat_name'])
    ))?>
    
    <h3 class="uk-h3"><?php echo qTranslate($val['subcat_name'],'subcat_name',$val)?></h3> 
    <?php if (is_array($val['sub_item']) && count($val['sub_item'])>=1):?>
    <ul class="uk-list uk-list-striped">
    <?php $x=0;?>
    <?php foreach ($val['sub_item'] as $val_addon):?>    
      <li>        
        <div class="uk-grid">
          <div class="uk-width-1-3">          
            <?php 
            $subcat_id=$val['subcat_id'];
            $sub_item_id=$val_addon['sub_item_id'];
            $multi_option_val=$val['multi_option'];
            
            /** fixed select only one addon*/
            if ( $val['multi_option']=="custom" || $val['multi_option']=="multiple"){
            	$sub_item_name="sub_item[$subcat_id][$x]";
            } else $sub_item_name="sub_item[$subcat_id][]"; 
            
            $sub_addon_selected='';
            $sub_addon_selected_id='';
                        
            $item_data['sub_item']=isset($item_data['sub_item'])?$item_data['sub_item']:'';
            if (array_key_exists($subcat_id,(array)$item_data['sub_item'])){
            	$sub_addon_selected=$item_data['sub_item'][$subcat_id];
            	if (is_array($sub_addon_selected) && count($sub_addon_selected)>=1){
	            	foreach ($sub_addon_selected as $val_addon_selected) {
	            		$val_addon_selected=Yii::app()->functions->explodeData($val_addon_selected);
	            		if (is_array($val_addon_selected)){
	            		    $sub_addon_selected_id[]=$val_addon_selected[0];
	            		}
	            	}
            	}
            }
            
            /** two flavors */
            /*if ($data['two_flavors']==2){            	
            	$val['multi_option']='one';
            }*/
            
            if ( $val['multi_option']=="custom" || $val['multi_option']=="multiple"): 
                            
	            echo CHtml::checkBox($sub_item_name,
	            in_array($sub_item_id,(array)$sub_addon_selected_id)?true:false
	            ,array(
	              'value'=>$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name']."|".$val['two_flavor_position'],
	              'data-id'=>$val['subcat_id'],
	              'data-option'=>$val['multi_option_val'],
	              'rel'=>$val['multi_option'],
	              'class'=>'sub_item_name sub_item_name_'.$val['subcat_id']
	            ));
            else :            	            
                //dump($val['two_flavor_position']);
	            echo CHtml::radioButton($sub_item_name,
	            in_array($sub_item_id,(array)$sub_addon_selected_id)?true:false
	            ,array(
	              'value'=>$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name']."|".$val['two_flavor_position'],
	              //'class'=>"sub_item"
	              'class'=>'sub_item sub_item_name_'.$val['subcat_id']
	              //'value'=>$val_addon['sub_item_id']."|".$val_addon['sub_item_name']
	            ));
            endif;
            ?>
            <?php echo qTranslate($val_addon['sub_item_name'],'sub_item_name',$val_addon)?>
          </div>
          <div class="uk-width-1-3">
            <?php if ($val['multi_option']=="multiple"):?>
            
            <?php             
            $qty_selected=1;
            if (array_key_exists($subcat_id,(array)$item_data['addon_qty'])){            	            
            	$qty_selected=$item_data['addon_qty'][$subcat_id][$x];
            }            
            ?>
            
            <div class="quantity-wrap quantity-wrap-small" >
		      <a href="javascript:;" class="qty-addon-minus" ><i class="fa fa-minus"></i></a>
		      <?php echo CHtml::textField("addon_qty[$subcat_id][$x]",$qty_selected,array(
		      'class'=>"uk-form-width-mini numeric_only left addon_qty",      
		      ))?>
		      <a href="javascript:;" class="qty-addon-plus"><i class="fa fa-plus"></i></a>		      
		      <div class="clear"></div>
		    </div>
            
            <?php endif;?>
          </div>
          <div class="uk-width-1-3" style="text-indent:100px;">
            <!--<span class="hide-food-price"><?php echo !empty($val_addon['price'])?getCurrencyCode().$val_addon['price']:"-";?></span>-->
            <span class="hide-food-price"><?php echo !empty($val_addon['price'])?displayPrice(getCurrencyCode(),$val_addon['price']):"-";?></span>
          </div>
        </div>
      </li>
      <?php $x++;?>
    <?php endforeach;?>
    </ul>
    <div class="clear"></div>
    <?php endif;?>
  </div>
  <?php endforeach;?>
  <div class="clear"></div>
  <?php endif;?>
  <?php endif;?>
  
    
  <?php if ($disabled_website_ordering==""):?>
  <div class="action-wrap">
   <div class="inner right">
   <input type="submit" value="<?php echo empty($row)?Yii::t("default","add to cart"):Yii::t("default","update cart");?>" class="add_to_cart uk-button uk-button-success">
   <a href="javascript:close_fb();" class="uk-button uk-button-danger"><?php echo Yii::t("default","Close")?></a>
   </div>
   <div class="clear"></div>
  </div> <!--action-wrap-->
  <?php endif;?>
  
</div> <!--view-item-wrap-->
</form>
<?php else :?>
<p class="uk-alert uk-alert-danger"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
<?php endif;?>
<script type="text/javascript">
jQuery(document).ready(function() {	
	var hide_foodprice=$("#hide_foodprice").val();	
	if ( hide_foodprice=="yes"){
		$(".hide-food-price").hide();
		$("span.price").hide();		
		$(".view-item-wrap").find(':input').each(function() {			
			$(this).hide();
		});
	}
	

	var price_cls=$(".price_cls:checked").length; 	
	if ( price_cls<=0){
		var x=0
		$( ".price_cls" ).each(function( index ) {
			if ( x==0){
				dump('set check');
				$(this).attr("checked",true);
			}
			x++;
		});
	}
		

if ( $(".food-gallery-wrap").exists()){
	  $('.food-gallery-wrap').magnificPopup({
      delegate: 'a',
      type: 'image',
      closeOnContentClick: false,
      closeBtnInside: false,
      mainClass: 'mfp-with-zoom mfp-img-mobile',
      image: {
        verticalFit: true,
        titleSrc: function(item) {
          return '';
        }
      },
      gallery: {
        enabled: true
      },
      zoom: {
        enabled: true,
        duration: 300, // don't foget to change the duration also in CSS
        opener: function(element) {
          return element.find('img');
        }
      }      
    });
}
   	
});	 /*END READY*/
</script>