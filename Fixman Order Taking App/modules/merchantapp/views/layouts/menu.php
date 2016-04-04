<?php
$menu =  array(  		    		    
    'activeCssClass'=>'active', 
    'encodeLabel'=>false,
    'items'=>array(
        array('visible'=>true,'label'=>'<i class="fa fa-cog"></i>&nbsp; '.merchantApp::t("General Settings"),
        'url'=>array('/merchantapp/index/settings'),'linkOptions'=>array()),
               
        array('visible'=>true,'label'=>'<i class="fa fa-user-plus"></i>&nbsp; '.merchantApp::t('Registered Merchant Device'),
        'url'=>array('/merchantapp/index/registereddevice'),'linkOptions'=>array()),
        
        array('visible'=>true,'label'=>'<i class="fa fa-info-circle"></i>&nbsp; '.merchantApp::t('CronJobs'),
        'url'=>array('/merchantapp/index/cronjobs'),'linkOptions'=>array()),
        
        
        array('visible'=>true,'label'=>'<i class="fa fa-comment-o"></i>&nbsp; '.merchantApp::t('Push Logs'),
        'url'=>array('/merchantapp/index/pushlogs'),'linkOptions'=>array()),
               
        array('visible'=>true,'label'=>'<i class="fa fa-database"></i>&nbsp; '.merchantApp::t("Update DB Tables"),
        'url'=>array('/merchantapp/update/'),'linkOptions'=>array('target'=>'_blank')),
        
        array('visible'=>true,'label'=>'<i class="fa fa-flag-checkered"></i>&nbsp; '.merchantApp::t("Mobile Translation"),
        'url'=>array('/merchantapp/index/translation')), 
     )   
);       
?>
<div class="menu">
<?php $this->widget('zii.widgets.CMenu', $menu);?>
</div>