<?php $country=Yii::app()->functions->CountryList();?>
<?php if ( $lang_list=Yii::app()->functions->getLanguageList() ):?>
<div class="language-selection-wrap">
  <a href="javascript:;" class="language-selection-close"><i class="ion-ios-close-empty"></i></a>
  <div class="container-medium">
  
   <div class="col-sm-6 border">
    
   </div> <!--col-->
   <div class="col-sm-6 border">
       <div class="lang-list">
       
         <div class="row head bottom10">
           <div class="col-xs-8 border"><?php echo t("Language")?></div>
           <!--<div class="col-xs-4 border"><?php echo t("Language")?></div>-->
           <div class="col-xs-4 border"></div>
         </div>  <!--row-->
         
          <div class="row body">
           <div class="col-xs-8 border">
             <ul>
              <li>
                <a href="javascript:;" class="highlight lang-selector" data-id="-9999">
                  United States | <?php echo t("English")?>
                </a>
              </li>
              <?php foreach ($lang_list as $val):?>
              <li><a href="javascript:;" class="lang-selector" data-id="<?php echo $val['lang_id']?>">
                <?php echo $country[$val['country_code']]?> | <?php echo $val['language_code']?></a>
              </li>
              <?php endforeach;?>
             </ul>
           </div>
           <!--<div class="col-xs-4 border"><div class="highlight">Espanol</div></div>-->
           <div class="col-xs-4 border">
             <a href="<?php echo Yii::app()->createUrl('/store/setlanguage/Id/-9999')?>" 
class="change-language orange-button rounded"><?php echo t("Change sites")?></a>
           </div>
         </div>  <!--row-->
       
       </div> <!--lang-list-->
   </div> <!--col-->
   
  </div> <!--container-medium-->
</div> <!--language-selection-wrap-->
<?php endif;?>