<?php
/*******************************************
@author : bastikikang 
@author email: basti@codemywebapps.com
@author website : http://codemywebapps.com
*******************************************/

/* ********************************************************
 *   Karenderia Multiple Restaurant 
 *   11 October 14 Version 1.0.0 initial release
 *   Last Update : 14 october 2014 Version 1.0.1
 *   Last Update : 12 november 2014 Version 1.0.2
 *   Last Update : 27 november 2014 Version 1.0.2a
 *   Last Update : 8 December 2014 Version 1.0.3
 *   Last Update : 26 December 2014 Version 1.0.4
 *   Last Update : 03 march 2015 Version 1.0.5 
 *   Last Update : 20 march 2015 Version 1.0.6 
 *   Last Update : 25 march 2015 Version 1.0.7
 *   Last Update : 05 May 2015 Version 1.0.8
 *   Last Update : 11 May 2015 Version 1.0.9
 *   Last Update : 29 May 2015 Version 2.0
 *   Last Update : 19 June 2015 Version 2.1
 *   Last Update : 25 July 2015 Version 2.2
 *   Last Update : 30 July 2015 Version 2.2.1
 *   Last Update : 17 Aug 2015 Version 2.3
 *   Last Update : 17 October 2015 Version 2.4
 *   Last Update : 24 October 2015 Version 2.5
 *   Last Update : 31 October 2015 Version 2.6
 *   Last Update : 19 March 2016 Version 3.0
 *   Last Update : 31 March 2016 Version 3.1
 ***********************************************************/

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_ENABLE_EXCEPTION_HANDLER', false);
ini_set("display_errors",false);

// include Yii bootstrap file
require_once(dirname(__FILE__).'/yiiframework/yii.php');
$config=dirname(__FILE__).'/protected/config/main.php';

// create a Web application instance and run
Yii::createWebApplication($config)->run();