<?php
return array(
	'name'=>'Karinderia Multiple Restaurant',
	
	'defaultController'=>'store',
		
	'import'=>array(
		'application.models.*',
		'application.models.admin.*',
		'application.components.*',
		'application.vendor.*'
	),
	
	'language'=>'default',		
			
	'components'=>array(
		/*'urlManager'=>array(
			'urlFormat'=>'path',			
		),*/
	    'urlManager'=>array(
		    'urlFormat'=>'path',
		    'rules'=>array(
		        '<controller:\w+>/<id:\d+>'=>'<controller>/view',
		        '<controller:\w+>'=>'<controller>/index',
		        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
		        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',		        
		    ),
		    'showScriptName'=>false,
		),
				
		'db'=>array(	        
		    'class'            => 'CDbConnection' ,
			'connectionString' => 'mysql:host={localhost};dbname={db_name}',
			'emulatePrepare'   => true,
			'username'         => '{db_username}',
			'password'         => '{db_password}',
			'charset'          => 'utf8',
			'tablePrefix'      => '{table_prefix}',
	    ),
	    
	    'functions'=> array(
	       'class'=>'Functions'	       
	    ),
	    'validator'=>array(
	       'class'=>'Validator'
	    ),
	    'widgets'=> array(
	       'class'=>'Widgets'
	    ),
	    	    
	    'Smtpmail'=>array(
	        'class'=>'application.extension.smtpmail.PHPMailer',
	        'Host'=>"YOUR HOST",
            'Username'=>'YOUR USERNAME',
            'Password'=>'YOUR PASSWORD',
            'Mailer'=>'smtp',
            'Port'=>587, // change this port according to your mail server
            'SMTPAuth'=>true,   
            'ContentType'=>'UTF-8'
	    ), 
	    
	    'GoogleApis' => array(
	         'class' => 'application.extension.GoogleApis.GoogleApis',
	         'clientId' => '', 
	         'clientSecret' => '',
	         'redirectUri' => '',
	         'developerKey' => '',
	    ),
	),
);

function statusList()
{
	return array(
	 'publish'=>Yii::t("default",'Publish'),
	 'pending'=>Yii::t("default",'Pending for review'),
	 'draft'=>Yii::t("default",'Draft')
	);
}

function clientStatus()
{
	return array(
	  'pending'=>Yii::t("default",'pending for approval'),
	 'active'=>Yii::t("default",'active'),	 
	 'suspended'=>Yii::t("default",'suspended'),
	 'blocked'=>Yii::t("default",'blocked'),
	 'expired'=>Yii::t("default",'expired')
	);
}

function paymentStatus()
{
	return array(
	 'pending'=>Yii::t("default",'pending'),
	 'paid'=>Yii::t("default",'paid'),
	 'draft'=>Yii::t("default",'Draft')
	);
}

function dump($data=''){
    echo '<pre>';print_r($data);echo '</pre>';
}