<?php
class UpdateController extends CController
{
	public function actionIndex()
	{
		$prefix=Yii::app()->db->tablePrefix;		
		$table_prefix=$prefix;
		
		$DbExt=new DbExt;
		
		echo "Updating order table<br/>";
		$new_field=array( 
		   'request_from'=>"varchar(10) NOT NULL DEFAULT 'web'",
            'mobile_cart_details'=>"text NOT NULL"
		);
		$this->alterTable('order',$new_field);		
		
		$stmt="
		CREATE TABLE IF NOT EXISTS ".$table_prefix."mobile_registered (
		  `id` int(14) NOT NULL AUTO_INCREMENT,
		  `client_id` int(14) NOT NULL,
		  `device_platform` varchar(255) NOT NULL DEFAULT 'Android',
		  `device_id` text NOT NULL,
		  `enabled_push` int(1) NOT NULL DEFAULT '1',
		  `country_code_set` varchar(3) NOT NULL,
		  `date_created` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `client_id` (`client_id`),
          KEY `enabled_push` (`enabled_push`),
          KEY `device_platform` (`device_platform`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		";		
		echo "Creating Table mobile_registered..<br/>";	
		$DbExt->qry($stmt);
		echo "(Done)<br/>";    
		
		$stmt="
		CREATE TABLE IF NOT EXISTS ".$table_prefix."mobile_push_logs (
		  `id` int(14) NOT NULL AUTO_INCREMENT,
		  `client_id` int(14) NOT NULL,
		  `client_name` varchar(255) NOT NULL,
		  `device_platform` varchar(100) NOT NULL,
		  `device_id` text NOT NULL,
		  `push_title` varchar(255) NOT NULL,
		  `push_message` varchar(255) NOT NULL,
		  `push_type` varchar(100) NOT NULL DEFAULT 'order',
		  `status` varchar(255) NOT NULL DEFAULT 'pending',
		  `json_response` text NOT NULL,
		  `date_created` datetime NOT NULL,
		  `date_process` datetime NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `device_platform` (`device_platform`),
          KEY `push_type` (`push_type`),
          KEY `status` (`status`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";		
		echo "Creating Table mobile_push_logs..<br/>";	
		$DbExt->qry($stmt);		
		echo "(Done)<br/>";      
		
		$stmt="
		CREATE OR REPLACE VIEW ".$table_prefix."mobile_registered_view as
		select a.*,
		concat(b.first_name,' ',b.last_name) as client_name
		FROM
		".$table_prefix."mobile_registered a
		left join ".$table_prefix."client b
		ON
		a.client_id=b.client_id		
		";
		echo "Creating View mobile_registered_view..<br/>";	
		$DbExt->qry($stmt);		
		echo "(Done)<br/>";     
			
		$stmt="		
		CREATE TABLE IF NOT EXISTS ".$table_prefix."mobile_temp_email (
		  `id` int(14) NOT NULL AUTO_INCREMENT,
		  `order_id` int(14) NOT NULL,
		  `client_email` varchar(255) NOT NULL,
		  `receipt_sender` varchar(255) NOT NULL,
		  `receipt_subject` varchar(255) NOT NULL,
		  `tpl` text NOT NULL,
		  `email_type` varchar(100) NOT NULL DEFAULT 'client',
		  `merchant_id` int(14) NOT NULL,
		  `client_id` int(14) NOT NULL,
		  `client_name` varchar(255) NOT NULL,
		  `gateway` varchar(255) NOT NULL,
		  `status` varchar(100) NOT NULL DEFAULT 'pending',
		  UNIQUE KEY `id` (`id`),
		  KEY `order_id` (`order_id`),
		  KEY `status` (`status`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
		";
		echo "Creating Table mobile_temp_email..<br/>";	
		$DbExt->qry($stmt);		
		echo "(Done)<br/>";
							
		$stmt="				
		CREATE TABLE IF NOT EXISTS ".$table_prefix."mobile_broadcast (
		  `broadcast_id` int(14) NOT NULL AUTO_INCREMENT,
		  `push_title` varchar(255) NOT NULL,
		  `push_message` varchar(255) NOT NULL,
		  `device_platform` int(14) NOT NULL DEFAULT '1',
		  `status` varchar(255) NOT NULL DEFAULT 'pending',
		  `date_created` datetime NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		  PRIMARY KEY (`broadcast_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		echo "Creating Table mobile_broadcast..<br/>";	
		$DbExt->qry($stmt);		
		echo "(Done)<br/>";
			
		echo "Updating table mobile_push_logs<br/>";
		$new_field=array( 
		   'broadcast_id'=>"int(14) NOT NULL"
		);
		$this->alterTable('mobile_push_logs',$new_field);		
		
		echo "(FINISH)<br/>";    
		
	} /*end index*/
	
	public function addIndex($table='',$index_name='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		
		$table=$prefix.$table;
		
		$stmt="
		SHOW INDEX FROM $table
		";		
		$found=false;
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {				
				if ( $val['Key_name']==$index_name){
					$found=true;
					break;
				}
			}
		} 
		
		if ($found==false){
			echo "create index<br>";
			$stmt_index="ALTER TABLE $table ADD INDEX ( $index_name ) ";
			dump($stmt_index);
			$DbExt->qry($stmt_index);
			echo "Creating Index $index_name on $table <br/>";		
            echo "(Done)<br/>";		
		} else echo 'index exist<br>';
	}
	
	public function alterTable($table='',$new_field='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		$existing_field='';
		if ( $res = Yii::app()->functions->checkTableStructure($table)){
			foreach ($res as $val) {								
				$existing_field[$val['Field']]=$val['Field'];
			}			
			foreach ($new_field as $key_new=>$val_new) {				
				if (!in_array($key_new,$existing_field)){
					echo "Creating field $key_new <br/>";
					$stmt_alter="ALTER TABLE ".$prefix."$table ADD $key_new ".$new_field[$key_new];
					dump($stmt_alter);
				    if ($DbExt->qry($stmt_alter)){
					   echo "(Done)<br/>";
				   } else echo "(Failed)<br/>";
				} else echo "Field $key_new already exist<br/>";
			}
		}
	}	
	
} /*end class*/