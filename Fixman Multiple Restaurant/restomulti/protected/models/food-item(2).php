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
?>

<?php if (is_array($data) && count($data)>=1):?>
<?php $data=$data[0];?>

<!--<link href="/restomulti/assets/vendor/uikit/css/uikit.almost-flat.min.css" rel="stylesheet" />
<link href="/restomulti/assets/vendor/uikit/css/addons/uikit.addons.min.css" rel="stylesheet" />
<link href="/restomulti/assets/vendor/uikit/css/addons/uikit.gradient.addons.min.css" rel="stylesheet" />
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<link href="/restomulti/assets/css/store.css?ver=1.0" rel="stylesheet" />-->

<form class="frm-fooditem" id="frm-fooditem" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addToCart')?>
<?php echo CHtml::hiddenField('item_id',$this->data['item_id'])?>
<?php echo CHtml::hiddenField('row',isset($row)?$row:"")?>
<?php echo CHtml::hiddenField('merchant_id',isset($data['merchant_id'])?$data['merchant_id']:'')?>


<?php echo CHtml::hiddenField('discount',isset($data['discount'])?$data['discount']:"" )?>
<?php echo CHtml::hiddenField('currentController','store')?>

<div class="view-item-wrap">
  
  <div class="uk-grid">
	  <div class="uk-width-1-2" style="width:120px;">
	    <?php if ( !empty($data['photo'])):?>
	    <img src="<?php echo baseUrl()."/upload/".$data['photo']?>" alt="<?php echo $data['item_name']?>" title="<?php echo $data['item_name']?>" class="uk-thumbnail uk-thumbnail-small">
	    <?php else :?>
	    <?php endif;?>
	  </div>
	  <div class="uk-width-1-2">
	  <h1 class="uk-h2"><?php echo $data['item_name']?></h1>
	  <p class="uk-text-muted"><?php echo $data['item_description']?></p>
	  </div>
  </div> <!--uk-grid-->
  
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
            'value'=>$price['price']."|".$price['size']
          ))?>
          <?php echo $price['size']?>
        </span>
        <?php else :?>
        <span class="size">
        <?php echo CHtml::radioButton('price',
            count($price['price'])==1?true:false
            ,array(
            'value'=>$price['price']
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
       <?php echo $val;?>
       </span>
     </div>
    </li>
    <?php endforeach;?>
    <div class="clear"></div>
  </div>
  <?php endif;?>
  <?php endif;?>
  
  <?php if (isset($data['addon_item'])):?>
  <?php if (is_array($data['addon_item']) && count($data['addon_item'])>=1):?>
  <?php foreach ($data['addon_item'] as $val): //dump($val);?>
  <div class="section section-addon">
    <h3 class="uk-h3"><?php echo $val['subcat_name']?></h3> 
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
            $sub_item_name="sub_item[$subcat_id][]";
            
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
            
            if ( $val['multi_option']=="custom" || $val['multi_option']=="multiple"): 
                            
	            echo CHtml::checkBox($sub_item_name,
	            in_array($sub_item_id,(array)$sub_addon_selected_id)?true:false
	            ,array(
	              'value'=>$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name'],
	              'data-id'=>$val['subcat_id'],
	              'data-option'=>$val['multi_option_val'],
	              'rel'=>$val['multi_option'],
	              'class'=>'sub_item_name sub_item_name_'.$val['subcat_id']
	            ));
            else :            	            
	            echo CHtml::radioButton($sub_item_name,
	            in_array($sub_item_id,(array)$sub_addon_selected_id)?true:false
	            ,array(
	              'value'=>$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name'],
	              //'value'=>$val_addon['sub_item_id']."|".$val_addon['sub_item_name']
	            ));
            endif;
            ?>
            <?php echo $val_addon['sub_item_name']?>
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
		      <?php echo CHtml::textField("addon_qty[$subcat_id][]",$qty_selected,array(
		      'class'=>"uk-form-width-mini numeric_only left addon_qty",      
		      ))?>
		      <a href="javascript:;" class="qty-addon-plus"><i class="fa fa-plus"></i></a>		      
		      <div class="clear"></div>
		    </div>
            
            <?php endif;?>
          </div>
          <div class="uk-width-1-3" style="text-indent:100px;">
            <?php echo !empty($val_addon['price'])?getCurrencyCode().$val_addon['price']:"-";?>
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
  
  <div class="action-wrap">
   <div class="inner right">
   <input type="submit" value="<?php echo empty($row)?Yii::t("default","add to cart"):Yii::t("default","update cart");?>" class="add_to_cart uk-button uk-button-success">
   <a href="javascript:close_fb();" class="uk-button uk-button-danger"><?php echo Yii::t("default","Close")?></a>
   </div>
   <div class="clear"></div>
  </div> <!--action-wrap-->
  
</div> <!--view-item-wrap-->
</form>
<?php else :?>
<p class="uk-alert uk-alert-danger"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
<?php endif;?>
<!--<script type="text/javascript">
jQuery(document).ready(function() {	
	if ( $("#notes").val()!="" ){
		$(".notes-wrap").slideToggle("fast");
	}
});	
</script>-->