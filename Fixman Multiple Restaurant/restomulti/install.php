<?php 
ini_set("display_errors",false);
session_start();
$_SESSION['test']='123';
$host=dirname($_SERVER['REQUEST_URI']);
$host=$host=="/"?"":$host;
$current_dir=dirname(__FILE__);
$current_dir_folder=basename(__DIR__) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Karinderia Web Installer</title>
<link href="<?php echo $host;?>/assets/css/store.css?ver=1.0" rel="stylesheet" />


<link rel="shortcut icon" href="/favicon.ico" />

<!--UIKIT-->
<link href="<?php echo $host; ?>/assets/vendor/uikit/css/uikit.almost-flat.min.css" rel="stylesheet" />
<link href="<?php echo $host; ?>/assets/vendor/uikit/css/addons/uikit.addons.min.css" rel="stylesheet" />
<link href="<?php echo $host; ?>/assets/vendor/uikit/css/addons/uikit.gradient.addons.min.css" rel="stylesheet" />
<!--UIKIT-->

</head>
<body>

<?php 
$steps=1;
if (isset($_GET['step'])){
	$steps=$_GET['step'];
}
?>

<div class="sub-header merchant-step-section" >
  <div class="main">
    <ul class="order-steps" style="margin-left:150px;">
      <li class="<?php echo $steps==""?"active current":'';?>">
        <a href="<?php echo $host?>/install.php">Checking Prerequisite</a>  
        <div class="line"></div>
      </li>
            
      <li class="<?php echo $steps==2?"active current":'';?>">
        <a href="javascript:;" class="inactive">Database</a>  
        <div class="line"></div>
      </li>
      
      <li class="<?php echo $steps==3?"active current":'';?>">
        <a href="javascript:;" class="inactive">Information</a>  
        <div class="line"></div>
      </li>

    </ul>
  </div>
</div>  

<div class="header-wrap">
 <div class="main">
 <div class="uk-grid">
        <div class="uk-width-1-3">
          <div class="section-top section-to-menu-user">                         
          </div>
        </div>
        <div class="uk-width-1-3">
           <div class="logo-wrap">			       
			<a href="/restomulti">
			<img title="karinderia" alt="karinderia" src="<?php echo $host; ?>/assets/images/store-logo.png">
			</a>			
           </div>
        </div>
        <div class="uk-width-1-3">
          <div class="section-top section-right">                               
          </div>                              
        </div>
     </div>
 </div>
</div> <!--header-wrap-->

</body>

<div class="page-right-sidebar">
  <div class="main">

  <div class="uk-width-1-1">
  <?php if ($steps==1):?> <!--step1-->
  <h2>Checking requirements..</h2>
  
  <?php $file_to_create=$current_dir."/protected/config/main.php";?>
  <?php if (file_exists($file_to_create)):?>
  <p class="uk-text-danger">main.php file detected. Either you create the main.php manually or you have already install the application. </p>
  <p class="uk-text-danger">If you proceed this file will deleted and replace by new configuration.</p>
  <p class="uk-text-danger"><?php echo $file_to_create;?></p>
  <?php //die();?>
  <?php endif;?>
  
  <?php 
  $failed=0;
  $config_file=$current_dir."/protected/config/";  
  if (is_writable($config_file)){
  	  echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">$config_file is writable [OK]</div>";
  } else {
  	  $failed++;
  	  echo "<p class=\"uk-alert uk-alert-danger\">$config_file must be writable</p>";
  }
  
  /*if (is_writable($current_dir)){
  	  echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">$current_dir is writable [OK]</div>";
  } else {
  	  $failed++;
  	  echo "<p class=\"uk-alert uk-alert-danger\">$config_file must be writable</p>";
  }*/
  
  
  if (defined('PDO::ATTR_DRIVER_NAME')) {      
      echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">PDO installed [OK]</div>";
  } else {
  	  $failed++;
  	 echo "<p class=\"uk-alert uk-alert-danger\">PDO is not installed</p>";
  }
    
  if ( function_exists( 'mail' ) ) {      
      echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">mail() is available [OK]</div>";
  } else {
  	  $failed++;
  	  echo "<p class=\"uk-alert uk-alert-danger\">mail() has been disabled</p>";
  }
    

  if (!empty($_SESSION['test'])){
  	 echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">Session [OK]</div>";
  } else {
  	 $failed++;
  	 echo "<p class=\"uk-alert uk-alert-danger\">Session not supported</p>";
  }
  
  /*echo 'Curl: ', function_exists('curl_version') ? 'Enabled' : 'Disabled' ;
  echo 'file_get_contents: ', @file_get_contents(__FILE__) ? 'Enabled' : 'Disabled';*/
  
  if ( function_exists('curl_version') ){
  	 echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">CURL is enabled [OK]</div>";
  } else {
  	 $failed++;
  	 echo "<p class=\"uk-alert uk-alert-danger\">CURL is disabled</p>";
  }
  
  if ( @file_get_contents(__FILE__) ){
  	echo "<div class=\"uk-text-success uk-alert uk-alert-succes\">file_get_contents is enabled [OK]</div>";
  } else {
  	$failed++;
  	echo "<p class=\"uk-alert uk-alert-danger\">file_get_contents is disabled</p>";
  }

  if ( $failed<=0){
  	  ?>
  	  <h3>Everything seems to be ok. Proceed to next steps</h3>
  	  <a class="uk-button uk-button-success uk-width-1-4" href="<?php echo $host;?>/install.php?step=2">Next</a>
  	  <?php
  } else {
  	  ?>
  	  <h3>There seems to be error in checking your server. Please fixed the following issue and try again.</h3>
  	  <?php
  }   
  ?>
  
  <?php elseif ($steps==2):?> <!--step2-->
  
  <?php 
  $data=$_POST;  
  ?>
  
  <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'):?>
  <?php   
  
  $htaccess='';
  /*dump($current_dir_folder);
  dump($host);
  die();*/  
  if ( $host=="htdocs" || $host=="public_html" || $host==""){
    $htaccess="
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>";  	
  } else {  
	$htaccess="
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /$current_dir_folder/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /$current_dir_folder/index.php [L]
</IfModule>";
  }
  
  /*set session database access*/  
  $_SESSION['kr_install_data']=$data;
  
  $table_prefix=isset($data['db_table_prefix'])?$data['db_table_prefix']:'mt_';
  
  if (empty($table_prefix)){
  	 $table_prefix='mt_';
  }
    
  require_once $current_dir."/protected/config/table_structure.php";  
  require_once $current_dir."/install/Db.php"; 
    
  
  echo "<p class=\"uk-icon-chevron-right\"> Connecting to database..</p>";
  $db = new Db( 'mysql', $data['db_host'], $data['db_name'], $data['db_username'],$data['db_pass'] );  
  echo "<div>Connection [ok]</div>";
  echo "<p  class=\"uk-icon-chevron-right\"> Creating Table..</p>";
  if (is_array($tbl) && count($tbl)>=1){
  	  foreach ($tbl as $tbl_slq) {  
  	  	 //try {
  	  	    $db->raw($tbl_slq);	
  	  	 /*}catch (Exception $e){
  	  	 	echo "<div class=\"uk-text-danger\">$e</div>";
  	  	 }  	  	 */
  	  }
  } else echo "<p class=\"uk-text-danger\">ERROR: No table query to execute.</p>";
  echo "<div>Creating table [done]</div>";
  
  
  echo "<p class=\"uk-icon-chevron-right\"> Creating .htaccess..</p>";  
  if ( file_exists($current_dir."/.htaccess")){
  	 echo "<div class=\"uk-text-danger\">FAILED: .htaccess already exist</div>";
  	 
  	 echo "<p>IF creating htaccess failed. you can just create this file in your server.</p>";
  	 echo "<p>$current_dir/.htaccess</p>";
     echo "<pre>$htaccess</pre>";
  	 
  } else {
  	 createFile($current_dir."/.htaccess",$htaccess);
  	 echo "<div>Creating .htaccess [done]</div>";
  }   
  
  echo "<p class=\"uk-icon-chevron-right\"> Creating main.php(config) file..</p>";
  $config_file=file_get_contents($current_dir."/install/Config.php");
  $config_file=str_replace("{localhost}",$data['db_host'],$config_file);
  $config_file=str_replace("{db_name}",$data['db_name'],$config_file);
  $config_file=str_replace("{db_username}",$data['db_username'],$config_file);
  $config_file=str_replace("{db_password}",$data['db_pass'],$config_file);
  $config_file=str_replace("{table_prefix}",$table_prefix,$config_file);  
  $file_to_create=$current_dir."/protected/config/main.php";
  /*if (file_exists($file_to_create)){
  	 echo "<div class=\"uk-text-danger\">FAILED: main.php already exist</div>";
  } else {
  	  createFile($file_to_create,$config_file);
  	  echo "<div>Creating main.php [done]</div>";
  }*/
  if (file_exists($file_to_create)){
  	  @unlink($file_to_create);  	  
  }
  createFile($file_to_create,$config_file);
 echo "<div>Creating main.php [done]</div>";
  ?>

  <div style="height:50px;"></div>
  <a class="uk-button uk-button-success uk-width-1-4" href="<?php echo $host;?>/install.php?step=3">Next</a> 
  
  <?php else :?>
   
  <h2>Database</h2>
  
  <form class="uk-form uk-form-horizontal forms" id="forms" method="POST">
  
   <div class="uk-form-row">                           
   <label class="uk-form-label">DB Host</label>
   <input type="text" name="db_host" class="uk-form-width-large" value="<?php echo isset($data['db_host'])?$data['db_host']:''?>">
   </div>             
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">DB Name</label>
   <input type="text" name="db_name" class="uk-form-width-large" value="<?php echo isset($data['db_name'])?$data['db_name']:''?>">
   </div>             
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">DB Username</label>
   <input type="text" name="db_username" class="uk-form-width-large" value="<?php echo isset($data['db_username'])?$data['db_username']:''?>" >
   </div>             
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">DB Password</label>
   <input type="text" name="db_pass" class="uk-form-width-large" value="<?php echo isset($data['db_pass'])?$data['db_pass']:''?>" >
   </div>            
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">Table Prefix</label>
   <input type="text" name="db_table_prefix" class="uk-form-width-large" placeholder="mt_" value="<?php echo isset($data['db_table_prefix'])?$data['db_table_prefix']:''?>" >
   </div>              
	
	<div class="uk-form-row">
	<label class="uk-form-label"></label>
	<input type="submit" value="Next" class="uk-button uk-form-width-medium uk-button-success">
	</div>   	
	
  </form>
  <?php endif;?>
  
  
  <?php elseif ($steps==3):?> <!--step3-->
  
  <?php 
  require_once $current_dir."/install/forms.php";
  require_once $current_dir."/install/validator.php";
  $country_list=require_once $current_dir."/protected/components/CountryCode.php";    
  $forms=new forms;
  $currency_list=array(
    'AUD'=>"AUD-&#36;",
    'CAD'=>"CAD-&#36;",
    'CNY'=>"CNY-&yen;",
    'EUR'=>"EUR-&euro;",
    'HKD'=>"HKD-&#36;",
    'JPY'=>"JPY-&yen;",
    'MXN'=>"MXN-&#36;",
    'USD'=>"USD-&#36;",
    'NZD'=>"NZD-&#36;"
  );
  $currency_list=array_flip($currency_list);
  ?>
  
  <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'):?>
  <?php 
  $err='';
  $data=$_POST;  
  $req=array(
    'website_title'=>"Website title is required",
    'admin_country_set'=>"Country is required",
    'website_address'=>"Address is required",
    'website_contact_phone'=>"Phone is required",
    'website_contact_email'=>"Email is required",
    'admin_currency_set'=>"Currency is required",
    'username'=>"Username is required",
    'password'=>"Password is required"
  );
  $validator=new validator;
  $validator->required($req,$data);
  if ( $validator->validate()){  	  
  	  $admin_currency_set=explode("-",$data['admin_currency_set']);  	    	  
  	  
  	  $db_con=$_SESSION['kr_install_data'];      
      $table_prefix=isset($db_con['db_table_prefix'])?$db_con['db_table_prefix']:'';
      $table_prefix=!empty($table_prefix)?$table_prefix:"mt_";
      
  	  require_once $current_dir."/protected/config/table_structure.php";  
      require_once $current_dir."/install/Db.php";        
      
      echo "<p class=\"uk-icon-chevron-right\"> Connecting to database..</p>";
      $db = new Db( 'mysql', $db_con['db_host'], $db_con['db_name'], $db_con['db_username'],$db_con['db_pass'] );  
      echo "<div>Connection [ok]</div>";  	  
      echo "<p class=\"uk-icon-chevron-right\"> Saving information ...</p>";
      
      $params=array(
       'website_title'=>$data['website_title'],
       'admin_country_set'=>$data['admin_country_set'],
       'website_address'=>$data['website_address'],
       'website_contact_phone'=>$data['website_contact_phone'],
       'website_contact_email'=>$data['website_contact_email'],
       'admin_currency_set'=>$admin_currency_set[0]
      );   
      $truncate="TRUNCATE TABLE ".$table_prefix."option";   
      $db->raw($truncate);
      try {
      	  //$db->create( 'Table', array( 'field' => 'data' ) ); 
      	  foreach ($params as $key=>$val) {
      	  	$params=array(
      	  	  'option_name'=>$key,
      	  	  'option_value'=>$val
      	  	);
      	  	$db->create( $table_prefix."option", $params); 
      	  }
      	  echo "<div> [done]</div>";  	  
      } catch (Exception $e){
      	echo "<div class=\"uk-text-danger\">$e</div>";
      }            
      
      /*insert currency data*/      
      $truncate="TRUNCATE TABLE ".$table_prefix."currency";   
      $db->raw($truncate);
      if (is_array($curr_data) && count($curr_data)>=1){
      	  echo "<p class=\"uk-icon-chevron-right\"> Inserting Currency ...</p>";      
      	  foreach ($curr_data as $curr) {      	  	
      	  	$db->create( $table_prefix."currency", $curr); 
      	  }
      	  echo "<div> [done]</div>";  	  
      }
      
      /*insert cuisine*/
      $truncate="TRUNCATE TABLE ".$table_prefix."cuisine";   
      $db->raw($truncate);
      if (is_array($cuisine) && count($cuisine)>=1){
      	  echo "<p class=\"uk-icon-chevron-right\"> Inserting Cuisine ...</p>";      
      	  foreach ($cuisine as $cusine_data) {      	  	
      	  	$db->create( $table_prefix."cuisine", $cusine_data); 
      	  }
      	  echo "<div> [done]</div>";  	  
      }
            
      /*insert rating*/
      $truncate="TRUNCATE TABLE ".$table_prefix."rating_meaning";   
      $db->raw($truncate);
      if (is_array($rating) && count($rating)>=1){
      	  echo "<p class=\"uk-icon-chevron-right\"> Inserting Rating ...</p>";      
      	  foreach ($rating as $rating_data) {      	  	
      	  	$db->create( $table_prefix."rating_meaning", $rating_data); 
      	  }
      	  echo "<div> [done]</div>";
      }
      
      $user_all_access='["autologin","dashboard","merchant","sponsoredMerchantList","packages","Cuisine","dishes","OrderStatus","settings","commisionsettings","voucher","merchantcommission","withdrawal","incomingwithdrawal","withdrawalsettings","emailsettings","emailtpl","customPage","Ratings","ContactSettings","SocialSettings","ManageCurrency","ManageLanguage","Seo","analytics","customerlist","subscriberlist","reviews","bankdeposit","paymentgatewaysettings","paymentgateway","paypalSettings","stripeSettings","mercadopagoSettings","sisowsettings","payumonenysettings","obdsettings","payserasettings","payondelivery","barclay","epaybg","authorize","sms","smsSettings","smsPackage","smstransaction","smslogs","fax","faxtransaction","faxpackage","faxlogs","faxsettings","reports","rptMerchantReg","rptMerchantPayment","rptMerchanteSales","rptmerchantsalesummary","rptbookingsummary","userList"]
';
                              
      /*insert user account*/
      $params_user=array( 
        'username'=>$data['username'],
        'password'=>md5($data['password']),
        'date_created'=>date('c'),
        'ip_address'=>$_SERVER['REMOTE_ADDR'],
        'user_access'=>$user_all_access
      );
      echo "<p class=\"uk-icon-chevron-right\"> Inserting user ...</p>";      
      $truncate="TRUNCATE TABLE ".$table_prefix."admin_user";   
      $db->raw($truncate);
      $db->create( $table_prefix."admin_user", $params_user); 
      echo "<div> [done]</div>";
      
      /*order status*/
      $truncate="TRUNCATE TABLE ".$table_prefix."order_status";   
      $db->raw($truncate);
      if (is_array($order_stats) && count($order_stats)>=1){
      	  echo "<p class=\"uk-icon-chevron-right\"> Inserting Order Status ...</p>";      
      	  foreach ($order_stats as $order_stats_data) {      	  	
      	  	$db->create( $table_prefix."order_status", $order_stats_data); 
      	  }
      	  echo "<div> [done]</div>";
      }
      
      
      
      if ( $current_dir_folder=="htdocs" || $current_dir_folder=="public_html"){
      	  $admin_url="http://".$_SERVER['HTTP_HOST'];
      } else $admin_url="http://".$_SERVER['HTTP_HOST']."$host"      
      ?>
      <h2 class="uk-text-success">Installation complete</h2>
      <p>Access the admin panel <a href="<?php echo $admin_url."/admin";?>">here</a> or simply visit your website admin url address <span class="uk-text-success"><?php echo $admin_url;?>/admin</span></p>
      
      <p>Wesbite URL : <span class="uk-text-success"><?php echo $admin_url?></span> </p>
      <p>Merchant URL : <span class="uk-text-success"><?php echo $admin_url?>/merchant</span> </p>
      <?php
  	  die();
  } else $err=$validator->getErrorAsHTML();
  ?>  
  <?php endif;?>  
   <h2>Website information</h2>
   
   <?php if (!empty($err)):?>
   <div class="uk-text-danger">
   <?php echo $err;?>
   </div>
   <?php endif;?>
  
  <form class="uk-form uk-form-horizontal forms" id="forms" method="POST">
  
   <div class="uk-form-row">                           
   <label class="uk-form-label">Website name</label>
   <input type="text" name="website_title" class="uk-form-width-large" value="<?php echo isset($data['website_title'])?$data['website_title']:""?>" >
   </div>      

   <div class="uk-form-row">                           
   <label class="uk-form-label">Country</label>
   <?php 
   echo $forms->dropDownList('admin_country_set','',$country_list);
   ?>
   </div>               
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">Address</label>
   <input type="text" name="website_address" class="uk-form-width-large" value="<?php echo isset($data['website_address'])?$data['website_address']:""?>" >
   </div>      
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">Contact Phone Number</label>
   <input type="text" name="website_contact_phone" class="uk-form-width-large" value="<?php echo isset($data['website_contact_phone'])?$data['website_contact_phone']:""?>" >
   </div>      
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">Contact Email</label>
   <input type="text" name="website_contact_email" class="uk-form-width-large" value="<?php echo isset($data['website_contact_email'])?$data['website_contact_email']:""?>" >
   </div>      
   
   <div class="uk-form-row">                           
   <label class="uk-form-label">Currency Code</label>
   <?php echo $forms->dropDownList('admin_currency_set','',$currency_list)?>
   <p class="uk-text-muted">if your currency is not listed you can select or add this later on the backend.</p>
   </div>      
   
   <h2>Admin User</h2>
   <div class="uk-form-row">                           
   <label class="uk-form-label">Username</label>
   <input type="text" name="username" class="uk-form-width-large" value="<?php echo isset($data['username'])?$data['username']:""?>" >
   </div>      
   <div class="uk-form-row">                           
   <label class="uk-form-label">Password</label>
   <input type="password" name="password" class="uk-form-width-large" value="<?php echo isset($data['password'])?$data['password']:""?>" >
   </div>      
      
	<div class="uk-form-row">
	<label class="uk-form-label"></label>
	<input type="submit" value="Next" class="uk-button uk-form-width-medium uk-button-success">
	</div>   	
   
   </form>      
  
  <?php endif;?>  
  </div>
  </div> <!--main-->
</div> <!--page-->
  
<?php 
function dump($data='')
{
	echo '<pre>'; print_r($data); echo "</pre>";
}

function createFile($filename_path,$content='')
{
	$myfile = fopen($filename_path, "w") or die("Unable to open file!".$filename_path);    
    fwrite($myfile, $content);        
    fclose($myfile);
}
?>

<script src="//code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>  
<!--UIKIT-->
<script src="<?php echo $host; ?>/assets/vendor/uikit/js/uikit.js"></script>
<script src="<?php echo $host; ?>/assets/vendor/uikit/js/addons/notify.min.js"></script>
<script src="<?php echo $host; ?>/assets/vendor/uikit/js/addons/sticky.min.js"></script>
<script src="<?php echo $host; ?>/assets/vendor/uikit/js/addons/sortable.min.js"></script>
<!--UIKIT-->

</html>