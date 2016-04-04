<?php 
$search_address=isset($_GET['s'])?$_GET['s']:'';
if (isset($_GET['st'])){
	$search_address=$_GET['st'];
}
$this->renderPartial('/front/search-header',array(
   'search_address'=>$search_address,
   'total'=>0
));?>

<?php 
$this->renderPartial('/front/order-progress-bar',array(
   'step'=>2,
   'show_bar'=>true
));
echo CHtml::hiddenField('current_page_url',isset($current_page_url)?$current_page_url:'');
?>

<div class="sections section-search-results">
  <div class="container center">
     <h3><?php echo Yii::t("default","Oops. We're having trouble finding that address.")?></h3>
     <p><?php echo Yii::t("default","Please enter your address in one of the following formats and try again. Please do NOT enter your apartment or floor number here.")?></p>
    <p class="bold">- <?php echo Yii::t("default","Street address, city, state")?></p>
    <p class="bold">- <?php echo Yii::t("default","Street address, city")?></p>
    <p class="bold">- <?php echo Yii::t("default","Street address, zip code")?></p>
  </div> <!--container--> 
</div> <!--section-search-results-->   