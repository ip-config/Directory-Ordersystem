<?php 
class FunctionsV3
{
	static $message;
	static $distance_type_result;
	
	public static function getPerPage()
	{
		return 4;		
	}
	
	public static function q($data)
	{
		return Yii::app()->db->quoteValue($data);
	}
	
    public static function prettyPrice($amount='')
	{
		if(!empty($amount)){
			return displayPrice(getCurrencyCode(),prettyFormat($amount));
		}
		return '-';
	}			
	
	public static function getDesktopLogo()
	{
		$upload_path=self::uploadPath();
		$logo=getOptionA('website_logo');
		if (!empty($logo)){
			if (file_exists($upload_path."/$logo")){
				return uploadURL()."/$logo";
			}
		} 
		return assetsURL()."/images/logo-desktop.png";
	}
	
	public static function getMobileLogo()
	{
		$upload_path=self::uploadPath();
		$logo=getOptionA('mobilelogo');
		if (!empty($logo)){
			if (file_exists($upload_path."/$logo")){
				return uploadURL()."/$logo";
			}
		} 
		return assetsURL()."/images/logo-mobile.png";
	}	
	
	public static function getFooterAddress()
	{
		$theme_custom_footer=getOptionA('theme_custom_footer');
		if (!empty($theme_custom_footer)){
			echo $theme_custom_footer;
			return ;
		}
		
		$website_address=getOptionA('website_address');
		$website_contact_phone=getOptionA('website_contact_phone');
		$website_contact_email=getOptionA('website_contact_email');
		$htm="<p>";
		if (!empty($website_address)){
			$htm.=$website_address." ".yii::app()->functions->adminCountry().'<br/>';
		}
		if (!empty($website_contact_phone)){
			$htm.=t("Call Us")." $website_contact_phone <br/>";
		}
		if (!empty($website_contact_email)){
			$htm.="$website_contact_email";
		}
		$htm.="</p>";	
		echo $htm;
	}
	
    public static function getMenu($class="menu")
    {
    	$top_menu_activated=self::getTopMenuActivated();
    
    	$top_menu[]=array('tag'=>"signup",'label'=>''.Yii::t("default","Home"),
                'url'=>array('/store/home'));
                                              
        $top_menu[]=array('tag'=>"browse",
                'visible'=>in_array('browse',(array)$top_menu_activated)?true:false,
                'label'=>''.Yii::t("default","Browse Restaurant"),
                'url'=>array('/store/browse'));
                        
        $enabled_commission=getOptionA('admin_commission_enabled');		
		$signup_link="/store/merchantsignup";
		if ($enabled_commission=="yes"){
		   $signup_link="/store/merchantsignupselection";	
		}         
		
		$client_signup=in_array('signup',(array)$top_menu_activated)?true:false;
		if ($client_signup){
			$client_signup=Yii::app()->functions->isClientLogin()?false:true;
		}
								
		if ( getOptionA('merchant_disabled_registration')!="yes"){
		    $top_menu[]=array('tag'=>"resto_signup",
		        'visible'=>in_array('resto_signup',(array)$top_menu_activated)?true:false,
		        'label'=>''.Yii::t("default","Restaurant Signup"),
                'url'=>array($signup_link));
		}       
                
        $top_menu[]=array('tag'=>"contact",
                'visible'=>in_array('contact',(array)$top_menu_activated)?true:false,
                'label'=>''.Yii::t("default","Contact"),
                'url'=>array('/store/contact'));                             
                                       
       $top_menu[]=array('visible'=>$client_signup,
                'tag'=>"signup",'label'=>''.Yii::t("default","Login & Signup"),
                'url'=>array('/store/signup'));   
                       
        if ( Yii::app()->functions->isClientLogin()){
        	$top_menu[]=array(
        	  'url'=>array('/store/profile'),
        	  'label'=>'<i class="ion-android-contact"></i> '.ucwords(Yii::app()->functions->getClientName()),
        	  'itemOptions'=>array('class'=>'green-button')      	  
        	);
        	
        	$top_menu[]=array(
        	  'url'=>array('/store/logout'),
        	  'label'=>t("Sign Out"),
        	  'itemOptions'=>array('class'=>'logout-menu orange-button')
        	);
        }
        
        /*LANGUAGE BAR TOP*/
        $language_selection=true;
        $theme_lang_pos=getOptionA('theme_lang_pos');
        $show_language=getOptionA('show_language');
        if($show_language==1){
        	$language_selection=false;
        }
        if ( $theme_lang_pos=="bottom" || $theme_lang_pos==""){
        	$language_selection=false;
        }
        
        if ($language_selection){
           $language_info=Yii::app()->functions->languageInfo( isset($_COOKIE['kr_lang_id'])?$_COOKIE['kr_lang_id']:'' );
           $flag="<img src=\"".assetsURL()."/images/flags/us.png" ."\">";
           $text_lang=$flag.t("English");
           if($language_info){
           	   $flag_png=strtolower($language_info['country_code']).".png";
           	   $flag="<img src=\"".assetsURL()."/images/flags/$flag_png" ."\">";
           	   $text_lang=$flag.$language_info['language_code'];
           }
           $top_menu[]=array('visible'=>$language_selection,
                'tag'=>"signup",'label'=>$text_lang,
                'itemOptions'=>array('class'=>"language-selection"),
                'url'=>"javascript:;");   
        } 
       /*LANGUAGE BAR TOP*/         
               
        return array(  		    
		    'id'=>$class,
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>$top_menu                      
         );        
    }
    
    public static function displayServicesList($service='')
    {    
    	$htm='<ul class="services-type">';
    	switch ($service) {
    		case 1:
    			$htm.='<li>'.t("Delivery").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			$htm.='<li>'.t("Pickup").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break;    			
    	
    		case 2:
    			$htm.='<li>'.t("Delivery").' <i class="green-color ion-android-checkmark-circle"></i></li>';    				
    			break; 
    		case 3:
    			$htm.='<li>'.t("Pickup").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break; 
    			
    		default:
    			break;
    	}
    	$htm.='</ul>';
    	return $htm;
    }
    
    public static function displayCuisine($cuisine='')
    {    
    	$p='';
    	if ( !empty($cuisine)){
    		$list=Yii::app()->functions->Cuisine(true);
    		$cuisine=json_decode($cuisine,true);    		
    		if (is_array($cuisine) && count($cuisine)>=1){
    			foreach ($cuisine as $val) {    				
    				if (array_key_exists($val,(array)$list)){
    					
    					 if ( Yii::app()->functions->multipleField()==2){
    					 	
	    					$cuisine_id=$val;    				
	    					$cuisine_info=Yii::app()->functions->GetCuisine($cuisine_id);
	    					
	    					$cuisine_json['cuisine_name_trans']=!empty($cuisine_info['cuisine_name_trans'])?
	    					json_decode($cuisine_info['cuisine_name_trans'],true):'';
	    					
    					    $p.= qTranslate($list[$val],'cuisine_name',$cuisine_json)  .", ";
    					 } else {
    					
    					    $p.= $list[$val].", ";
    					 }
    				}
    			}
    			$p=substr($p,0,-2);
    		}
    	}
    	return $p;
    }
    
    public static function minimumDeliveryFee()   
    {
    	$minimum_list=array(
		  '5'=>"< ".displayPrice(baseCurrency(),5),
		  '10'=>"< ".displayPrice(baseCurrency(),10),
		  '15'=>"< ".displayPrice(baseCurrency(),15),
		  '20'=>"< ".displayPrice(baseCurrency(),20),
		);		
		return $minimum_list;
    }
    
    public static function getMerchantLogo($merchant_id='')
    {
    	$upload_path=Yii::getPathOfAlias('webroot')."/upload";         	
    	$merchant_logo=Yii::app()->functions->getOption('merchant_photo',$merchant_id);    	
    	if (!empty($merchant_logo)){
    		if (file_exists($upload_path."/".$merchant_logo)){
    		   $merchant_logo=uploadURL()."/$merchant_logo";    
    		} else $merchant_logo=assetsURL()."/images/default-image-merchant.png";
    	} else $merchant_logo=assetsURL()."/images/default-image-merchant.png";
    	return $merchant_logo;
    }
    
    public static function searchByAddress($address='',$page=0,$per_page=5,$getdata='')
    {    	
    	if (empty($address)){
    		return false;
    	}
    	    	    	
    	if ($page>0){
    	    $page=($page-1)*$per_page;
    	}
    	
    	$lat=0;
		$long=0;
		$and='';
		
    	if ($lat_res=Yii::app()->functions->geodecodeAddress($address)){
	        $lat=$lat_res['lat'];
			$long=$lat_res['long'];
    	} 
    	
    	if (empty($lat)){
			$lat=0;
		}		
		if (empty($long)){
			$long=0;
		}					
		    	    	
    	$home_search_unit_type=getOptionA('home_search_unit_type');
		$home_search_radius=getOptionA('home_search_radius');
				
		if (empty($home_search_unit_type)){
			$home_search_unit_type='mi';
		}	
		if (!is_numeric($home_search_radius)){
			$home_search_radius=10;
		}			
    	
		$distance_exp=3959;
		if ($home_search_unit_type=="km"){
			$distance_exp=6371;
		}		
		
		$sort_by =" ORDER BY is_sponsored DESC, distance ASC";		
		$sort_combine=$sort_by;
		
		if (isset($getdata['sort_filter'])){
			if (!empty($getdata['sort_filter'])){
				$sort="ASC";
				if($getdata['sort_filter']=="ratings"){
					$sort="DESC";
				}
				$sort_combine=" ORDER BY ".$getdata['sort_filter']." $sort";
			}
		}
		
		//dump($getdata);		
		if (isset($getdata['filter_delivery_type'])){			
			switch ($getdata['filter_delivery_type']) {
				case 1:
					$and = "AND ( service='1' OR service ='2' OR service='3')";
					break;			
				case 2:
					$and = "AND ( service='1' OR service ='2')";
					break;
				case 3:
					$and = "AND ( service='1' OR service ='3')";
					break;		
				default:
					break;
			}		
		}		
		
		$filter_cuisine='';
		if (isset($_GET['filter_cuisine'])){
			$filter_cuisines=!empty($_GET['filter_cuisine'])?explode(",",$_GET['filter_cuisine']):false;
			if (is_array($filter_cuisines) && count($filter_cuisines)>=1){
				$x=1;
				foreach ($filter_cuisines as $val) {				
					if (!empty($val)){
						if ( $x==1){
							$filter_cuisine.=" LIKE '%\"$val\"%'";
						} else $filter_cuisine.=" OR cuisine LIKE '%\"$val\"%'";
						$x++;
					}
				}				
				if (!empty($filter_cuisine)){
				   $and.=" AND (cuisine $filter_cuisine) ";
				}
			}
		}
		
		$filter_minimum='';
		if (isset($_GET['filter_minimum'])){
			if (is_numeric($_GET['filter_minimum'])){
				$and.=" AND CAST(minimum_order as SIGNED) <='".$_GET['filter_minimum']."' ";
			}		
		}		
		
		if (isset($_GET['restaurant_name'])){
			if (!empty($_GET['restaurant_name'])){
			    $and.=" AND restaurant_name LIKE '".$_GET['restaurant_name']."%'";
			}
		}
		
		$and.="AND status='active' ";
		$and.="  AND is_ready ='2' ";
		    	
    	$stmt="
		SELECT SQL_CALC_FOUND_ROWS a.*, ( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
		* cos( radians( lontitude ) - radians($long) ) 
		+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
		AS distance,
		concat(street,' ',city,' ',state,' ',post_code) as merchant_address
		
		FROM {{view_merchant}} a 
		HAVING distance < $home_search_radius		
		$and
		$sort_combine
		LIMIT $page,$per_page
		";    	
    	
    	if (isset($_GET['debug'])){
    		dump($stmt);
    	}
		
		
		$DbExt=new DbExt();
		$DbExt->qry("SET SQL_BIG_SELECTS=1");		
		if ($res=$DbExt->rst($stmt)){			
			//dump($res);die();
			$stmt_rows="SELECT FOUND_ROWS()";
			$total_found=0;
			if ($rows=$DbExt->rst($stmt_rows)){
				$total_found=$rows[0]['FOUND_ROWS()'];
			}
			return array(
			   'total'=>$total_found,
			   'client'=>array(
			     'lat'=>$lat,
			     'long'=>$long
			   ),
			   'list'=>$res,
			   'sql'=>$stmt
			);
		}
    	return false;
    }
    
    public static function  searchGetFilter($getdata='')
    {
    	$and='';
    	//dump($getdata);
    	    					
		if (isset($getdata['filter_delivery_type'])){			
			switch ($getdata['filter_delivery_type']) {
				case 1:
					$and = "AND ( service='1' OR service ='2' OR service='3')";
					break;			
				case 2:
					$and = "AND ( service='1' OR service ='2')";
					break;
				case 3:
					$and = "AND ( service='1' OR service ='3')";
					break;		
				default:
					break;
			}		
		}		

		$filter_cuisine='';
		if (isset($getdata['filter_cuisine'])){
			$filter_cuisines=!empty($getdata['filter_cuisine'])?explode(",",$getdata['filter_cuisine']):false;
			if (is_array($filter_cuisines) && count($filter_cuisines)>=1){
				$x=1;
				foreach ($filter_cuisines as $val) {				
					if (!empty($val)){
						if ( $x==1){
							$filter_cuisine.=" LIKE '%\"$val\"%'";
						} else $filter_cuisine.=" OR cuisine LIKE '%\"$val\"%'";
						$x++;
					}
				}				
				if (!empty($filter_cuisine)){
				   $and.=" AND (cuisine $filter_cuisine) ";
				}
			}
		}
		
		$filter_minimum='';
		if (isset($getdata['filter_minimum'])){
			if (is_numeric($getdata['filter_minimum'])){
				$and.=" AND CAST(minimum_order as SIGNED) <='".$getdata['filter_minimum']."' ";
			}		
		}		
		
		if (isset($getdata['restaurant_name'])){
			if (!empty($getdata['restaurant_name'])){
			    $and.=" AND restaurant_name LIKE '".$getdata['restaurant_name']."%'";
			}
		}
		
		$and.=" AND status='active' ";
		$and.=" AND is_ready ='2' ";
		return $and;
    }
    
    public static function searchByMerchant($stype='',$address='',$page=0,$per_page=5,$getdata='')
    {        
    	
        if ($page>0){
    	    $page=($page-1)*$per_page;
    	}
    	
        $lat=0;
		$long=0;
		if ( !empty($address)){
	    	if ($lat_res=Yii::app()->functions->geodecodeAddress($address)){	    		
		        $lat=$lat_res['lat'];
				$long=$lat_res['long'];
	    	} 
		}
    	
    	if (empty($lat)){
			$lat=0;
		}		
		if (empty($long)){
			$long=0;
		}					
		    	    	    	        
        $sort_by =" ORDER BY is_sponsored DESC, restaurant_name ASC";		
		$sort_combine=$sort_by;
				
		if (isset($getdata['sort_filter'])){
			if (!empty($getdata['sort_filter'])){
				$sort="ASC";
				if($getdata['sort_filter']=="ratings"){
					$sort="DESC";
				}
				$sort_combine=" ORDER BY ".$getdata['sort_filter']." $sort";
			}
		}
		
		$and=self::searchGetFilter($getdata);
        
		$stmt=''; $query='';
		
		//dump($stype);
		switch ($stype) {
			case "kr_search_restaurantname":
				$merchant_name= isset($getdata['restaurant-name'])?$getdata['restaurant-name']:'';
				$query=" restaurant_name LIKE '%$merchant_name%' ";
				break;
		
			case "kr_search_streetname":	
			   $stree_name= isset($getdata['street-name'])?$getdata['street-name']:'';
			   $query=" street LIKE '%$stree_name%' ";
			   break;
			   
			case "kr_search_category":   				
			   //$query=" cuisine LIKE '%".$cuisine_id."%' ";
			   $query =" 1";
			   break;
			   
			case "kr_search_foodname":   
			   $foodname_str='';
			   if (isset($getdata['foodname'])){
				  if (!empty($getdata['foodname'])){
					  $foodname_str="%".$getdata['foodname']."%";
				  } else $foodname_str='-1';			
			   } else $foodname_str='-1';		   			   
			   $stmt="SELECT SQL_CALC_FOUND_ROWS a.*,
			   concat(street,' ',city,' ',state,' ',post_code) as merchant_address
			   FROM
		       {{view_merchant}} a
		       WHERE
		       merchant_id = (
		         select merchant_id
		         from
		         {{item}}
		         where
		         item_name like ".self::q($foodname_str)."
		         and
		         merchant_id=a.merchant_id
		         limit 0,1
		       )
		       $and
		       $sort_combine
		       LIMIT $page,$per_page
		       ";	
			   break;
			   
			case "kr_postcode":   
			   $post_code='-1';
			   $zipcode=isset($getdata['zipcode'])?$getdata['zipcode']:'';
			   if(!empty($zipcode)){
			   	  $zipcode=explode(" ",$zipcode);
			   	  $post_code=$zipcode[0];
			   }
			   $query=" post_code LIKE '%$post_code%' ";
			   break;
			   
			default:
				break;
		}        
                
		if (empty($stmt)){
	        $stmt="
	        SELECT SQL_CALC_FOUND_ROWS a.*,
	        concat(street,' ',city,' ',state,' ',post_code) as merchant_address
	        FROM {{view_merchant}} a
	        WHERE
	        $query
	        $and
			$sort_combine
			LIMIT $page,$per_page
	        ";
		}
        		
		if(isset($_GET['debug'])){
			dump($stmt);
		}
                
        $DbExt=new DbExt();
		$DbExt->qry("SET SQL_BIG_SELECTS=1");		
		if ($res=$DbExt->rst($stmt)){			
						
			$stmt_rows="SELECT FOUND_ROWS()";
			$total_found=0;
			if ($rows=$DbExt->rst($stmt_rows)){
				$total_found=$rows[0]['FOUND_ROWS()'];
			}
			return array(
			   'total'=>$total_found,
			   'client'=>array(
			     'lat'=>$lat,
			     'long'=>$long
			   ),
			   'list'=>$res,
			   'sql'=>$stmt
			);
		}
    	return false;
    }
    
    public static function merchantOpenTag($merchant_id='')
    {
    	$is_merchant_open = Yii::app()->functions->isMerchantOpen($merchant_id); 
	    $merchant_preorder= Yii::app()->functions->getOption("merchant_preorder",$merchant_id);
	    
	    $now=date('Y-m-d');
		$is_holiday=false;
	        if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)){  
      	   if (in_array($now,(array)$m_holiday)){
      	   	  $is_merchant_open=false;
      	   }
        }
        
        if ( $is_merchant_open==true){
        	if ( getOption($merchant_id,'merchant_close_store')=="yes"){
        		$is_merchant_open=false;	
        		$merchant_preorder=false;			        		
        	}
        }
        
        if ($is_merchant_open){
        	$tag='<span class="label label-success">'.t("Open").'</span>';
        } else {
        	if ($merchant_preorder){
        		$tag='<span class="label label-info">'.t("Pre-Order").'</span>';
        	} else {
        		$tag='<span class="label label-danger">'.t("Closed").'</span>';
        	}
        }      
        return $tag;  
    }
    
    public static function getFreeDeliveryTag($merchant_id='')
    {
    	$fee=getOption($merchant_id,'free_delivery_above_price');
    	if ($fee>0){
    		return '<span class="label label-default">'. t("Free Delivery On Orders Over")." ". self::prettyPrice($fee).'</span>';
    	}
    	return '';
    }
    
    public static function getDeliveryEstimation($merchant_id='')
    {
    	$delivery_est=Yii::app()->functions->getOption("merchant_delivery_estimation",$merchant_id);
    	if (empty($delivery_est)){
    		return t("not available");
    	}
    	return $delivery_est;
    }
    
    public static function getOffersByMerchant($merchant_id='',$display_type=1)
    {
    	$offer='';
    	if ( $res=Yii::app()->functions->getMerchantOffersActive($merchant_id)){    		
    		if ($display_type==1){
    			$offer=number_format($res['offer_percentage'],0)."% ".t("Off");
    		} else {
    			$offer=number_format($res['offer_percentage'],0)."% ".t("off today on orders over");
    			$offer.=" ".self::prettyPrice($res['offer_price']);
    		}
    		return $offer;
    	}
    	return false;
    }
    
    public static function getDistanceBetweenPlot($lat1, $lon1, $lat2, $lon2, $unit)
    {
    	 /* dump("$lat1,$lon1");
    	  dump("$lat2,$lon2");
    	  dump($unit);*/
    	  
    	  $units_params='';
    	  
    	  switch ($unit) {
    	  	case "M":
    	  	case "Miles":
    	  		$units_params='imperial';
    	  		break;
    	  
    	  	default:
    	  		$units_params='metric';
    	  		break;
    	  }
    	  
    	  $method=getOptionA('google_distance_method');
    	  $use_curl=getOptionA('google_use_curl');
    	  
    	  $protocol = isset($_SERVER["https"]) ? 'https' : 'http';
    	  $key=Yii::app()->functions->getOptionAdmin('google_geo_api_key');
    	  
    	  if ($method=="driving" || $method=="transit"){
    	  	 $url="https://maps.googleapis.com/maps/api/distancematrix/json";
    	  	 $url.="?origins=".urlencode("$lat1,$lon1");
    	  	 $url.="&destinations=".urlencode("$lat2,$lon2");
    	  	 $url.="&mode=".urlencode($method);    	  
    	  	 $url.="&units=".urlencode($units_params);
    	  	 if(!empty($key)){
    	  	 	$url.="&key=".urlencode($key);
    	  	 }
    	  	 
    	  	 if(isset($_GET['debug'])){
    	  	    dump($url);
    	  	 }
    	  	 
    	  	 if ($use_curl==2){
    	  	 	$data = Yii::app()->functions->Curl($url);
    	  	 } else $data = @file_get_contents($url);	
    	  	 $data = json_decode($data);  
    	  	 //dump($data);
    	  	 if (is_object($data)){
    	  	 	if ($data->status=="OK"){ 
    	  	 		$distance_value=$data->rows[0]->elements[0]->distance->text;
    	  	 		if ($units_params=="imperial"){
    	  	 		   $distance_raw=str_replace(array(" ","mi","ft"),"",$distance_value);
    	  	 		   if (preg_match("/ft/i", $distance_value)) {
    	  	 		   	  self::$distance_type_result='ft';
    	  	 		   }
    	  	 		} else {
    	  	 			$distance_raw=str_replace(array(" ","km","mm",'mt'),"",$distance_value);
    	  	 			if (preg_match("/mm/i", $distance_value)) {
    	  	 		   	   self::$distance_type_result='mm';
    	  	 		    }
    	  	 		    if (preg_match("/mt/i", $distance_value)) {
    	  	 		   	   self::$distance_type_result='mt';
    	  	 		    }
    	  	 		}
    	  	 		return $distance_raw;
    	  	 	}	
    	  	 }
    	  	 return false;
    	  }
    	  
    	  if (empty($lat1)){ return false; }
    	  if (empty($lon1)){ return false; }
    	  
    	  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);
		
		  if ($unit == "K") {
		      return ($miles * 1.609344);
		  } else if ($unit == "N") {
		      return ($miles * 0.8684);
		  } else {
		      return $miles;
		  }
    }       
    
    public static function getMerchantDistanceType($merchant_id='')
    {
    	$distance_type=getOption($merchant_id,'merchant_distance_type');
    	$distance_type=strtolower($distance_type);        
        switch ($distance_type) {
        	case "mi":
        		$type="M";
        		break;        
        	case "km":	
        	    $type="K";
        	    break;
        	default:
        		$type="M";
        		break;
        }
        return $type;
    }
    
    public static function getMerchantDeliveryFee($merchant_id='',$fee='',$distance='',$unit='')
    {
    	$distance=!empty($distance)?number_format($distance,3):0;    	
    	$shipping_enabled=getOption($merchant_id,'shipping_enabled');    	
    	$charge=$fee;
    	if ( $shipping_enabled==2){
    		$FunctionsK=new FunctionsK();
    		$charge=$FunctionsK->getDeliveryChargesByDistance(
    		  $merchant_id,
    		  $distance,
    		  $unit,
    		  $fee
    		);
    	}    	
    	
    	if ($unit=="ft" || $unit=="mm" || $unit=="mt"){
    		if ($fee>0){
    		    return $fee;
	    	}
	    	return false;
    	}
    	
    	if ($charge>0){
    		return $charge;
    	}
    	return false;
    }
    
    public static function clearSearchParams($field_to_clear='',$extra_params='')
    {    	
    	$request=$_GET;    
    	$new_request='';
    	if (is_array($request) && count($request)>=1){
    		foreach ($request as $key=>$val) {
    			if ($key!=$field_to_clear){
    				$new_request.="$key=$val&";
    			}    			
    		}
    	}
    	if (!empty($extra_params)){
    		$new_request.=$extra_params;
    	}
    	return Yii::app()->createUrl('store/searcharea?'.$new_request);    	
    }
    
	public static function getMerchantBySlug($slug_id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT *,
		concat(street,' ',city,' ',state,' ',post_code) as merchant_address
		 FROM
		{{view_merchant}}
		WHERE
		restaurant_slug=".q($slug_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}	    
	
	public static function getMerchantById($merchant_id='')
	{
		$DbExt=new DbExt;
		
		$DbExt->qry("SET SQL_BIG_SELECTS=1");
		
		$stmt="SELECT *,
		concat(street,' ',city,' ',state,' ',post_code) as merchant_address
		 FROM
		{{view_merchant}}
		WHERE
		merchant_id=".q($merchant_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}	    	
	
	public static function isMerchantcanCheckout($merchant_id='')
	{
		$msg=''; $code=2; $button=''; $holiday=2;
		$now=date('Y-m-d');
		if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)){      	         	  
      	   if (in_array($now,(array)$m_holiday)){
      	   	  $holiday=1;
      	   	  $msg=getOption($merchant_id,'merchant_close_msg_holiday');   
      	   	  if (empty($msg)){
      	   	  	  $msg=t("Sorry merchant is closed");
      	   	  }
      	   }
        }
        
        if (!yii::app()->functions->validateSellLimit($merchant_id) ){
        	$msg=t("This merchant is not currently accepting orders.");
        }
        
        $is_merchant_open = Yii::app()->functions->isMerchantOpen($merchant_id); 
	    $merchant_preorder= Yii::app()->functions->getOption("merchant_preorder",$merchant_id);
        
	    if ($is_merchant_open){
	    	$code=1; $button=t("Checkout");
	    } else {
	    	if ($merchant_preorder==1){
	    		$code=1; $button=t("Pre-Order");
	    	} else {
	    		if ($holiday==2){
		    		$merchant_close_msg=getOption($merchant_id,'merchant_close_msg');
		    		if (empty($merchant_close_msg)){
		    			$msg=t("Sorry merchant is closed.");
		    		} else $msg=$merchant_close_msg;
	    		}
	    	}
	    }
	    
	    /*check if merchant is close via backend*/
	    if ( getOption($merchant_id,'merchant_close_store')=="yes"){
	    	$code=2; 
	    	$msg=getOption($merchant_id,'merchant_close_msg');
	    	if (empty($msg)){
	    	    $msg=t("This restaurant is closed now. Please check the opening times.");
	    	}
	    }
	    	    
	    return array(
	     'code'=>$code,
	     'msg'=>$msg,
	     'button'=>$button,
	     'holiday'=>$holiday
	    );
	}
	
	public static function getItemFirstPrice($prices='',$discount='')
	{
		if (is_array($prices) && count($prices)>=1){
			$regular_price=$prices[0]['price'];
			if ($discount>0){
				$regular_price=$regular_price-$discount;
			}
			return self::prettyPrice($regular_price);
		}
		return '-';
	}
	
	public static function uploadPath()
	{
		return Yii::getPathOfAlias('webroot')."/upload";
	}
	
	public static function fixedLink($link='')
	{
		if  (!empty($link)){
	  	   if (!preg_match("/http/i", $link)) {
	  	   	   $link="http://".$link;
	  	   }
	  	   return $link;
       }  
       return false;
	}
	
	public static function getFoodDefaultImage($photo='',$small=true)
	{
		$path=self::uploadPath()."/$photo";		
		if (!file_exists($path) || empty($photo)){
			if ( $small){
			    $url_image=websiteUrl()."/assets/images/default-food-image.png";
			} else $url_image=websiteUrl()."/assets/images/default-food-image-large.png";
		} else $url_image=websiteUrl()."/upload/$photo";		
		return $url_image;
	}		
	
    public static function getMerchantOpeningHours($merchant_id='')
	{
        $stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
		$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
		$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);
		
		$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
		$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
		$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
		$stores_open_custom_text=!empty($stores_open_custom_text)?(array)json_decode($stores_open_custom_text):false;
		
		
		$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
		$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;
		
		$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
		$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;		
												
		$open_starts='';
		$open_ends='';
		$open_text='';
		$data='';
				
		if (is_array($stores_open_day) && count($stores_open_day)>=1){
			foreach ($stores_open_day as $val_open) {	
				if (array_key_exists($val_open,(array)$stores_open_starts)){
					$open_starts=timeFormat($stores_open_starts[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_ends)){
					$open_ends=timeFormat($stores_open_ends[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_custom_text)){
					$open_text=$stores_open_custom_text[$val_open];
				}					
				
				$pm_starts=''; $pm_ends=''; $pm_opens='';
				if (array_key_exists($val_open,(array)$stores_open_pm_start)){
					$pm_starts=timeFormat($stores_open_pm_start[$val_open],true);
				}											
				if (array_key_exists($val_open,(array)$stores_open_pm_ends)){
					$pm_ends=timeFormat($stores_open_pm_ends[$val_open],true);
				}												
				
				$full_time='';
				if (!empty($open_starts) && !empty($open_ends)){					
					$full_time=$open_starts." - ".$open_ends."&nbsp;&nbsp;";
				}			
				if (!empty($pm_starts) && !empty($pm_ends)){
					if ( !empty($full_time)){
						$full_time.=" / ";
					}				
					$full_time.="$pm_starts - $pm_ends";
				}												
								
				$data[]=array(
				  'day'=>$val_open,
				  'hours'=>$full_time,
				  'open_text'=>$open_text
				);
				
				$open_starts='';
		        $open_ends='';
		        $open_text='';
			}
			return $data;
		}			
		return false;		
	}	
		
    public static function merchantActiveVoucher($merchant_id='')
    {    	
    	$mtid='"'.$merchant_id.'"';
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{voucher_new}}
			WHERE
			status in ('publish','published')
			AND
			now() <= expiration
			AND ( merchant_id =".self::q($merchant_id)." OR joining_merchant LIKE '%$mtid%' )
			LIMIT 0,10			
		";	 	 
	    //dump($stmt);   
		if ( $res=$DbExt->rst($stmt)){			
			return $res;
		}
		return false;
    }	
    
    public static function sectionHeader($title='')
    {
    	?>
    	<div class="section-label">
	    <a class="section-label-a">
	      <span class="bold">
	      <?php echo t($title)?></span>
	      <b></b>
	    </a>     
	   </div>    
    	<?php
    }
    
    public static function PaymentOptionList()
    {
    	/*
    	Important: you can change the value but not the key
    	like cod ocr pyr etc 
    	*/
    	
    	return array(
    	  'cod'=>t("Cash On delivery"),
    	  'ocr'=>t("Offline Credit Card Payment"),
    	  'pyr'=>t("Pay On Delivery"),
    	  'pyp'=>t("paypal"),
    	  'stp'=>t("stripe"),
    	  'mcd'=>t("mercapado"),
    	  'ide'=>t("sisow"),
    	  'payu'=>t("payumoney"),
    	  'pys'=>t("paysera"),    	  
    	  'bcy'=>t("Barclay"),
    	  'epy'=>t("EpayBg"),
    	  'atz'=>t("Authorize.net"),
    	  'obd'=>t("Offline Bank Deposit")    	  
    	);
    }
    
    public static function getOfflinePaymentList()
    {
    	/*
    	Important: you can change the value but not the key
    	like cod ocr pyr etc 
    	*/
    	
    	return array(
    	   'cod'=>t("Cash On delivery"),
    	   'ocr'=>t("Offline Credit Card Payment"),
    	   'obd'=>t("Offline Bank Deposit") ,
    	   'pyr'=>t("Pay On Delivery"),   	  
    	);
    }
    
    public static function getAdminPaymentList()
    {
    	$payment_list=self::PaymentOptionList();
    	
    	$payment_available='';
    	if (getOptionA('admin_stripe_enabled')=="yes"){
    		$payment_available[]='stp';
    	}    
    	if (getOptionA('admin_enabled_paypal')==""){
    		$payment_available[]='pyp';
    	}    
    	if (getOptionA('admin_enabled_card')==""){
    		$payment_available[]='ocr';
    	}
    	if (getOptionA('admin_mercado_enabled')=="yes"){
    		$payment_available[]='mcd';
    	}
    	if (getOptionA('admin_sisow_enabled')=="yes"){
    		$payment_available[]='ide';
    	}
    	if (getOptionA('admin_payu_enabled')=="yes"){
    		$payment_available[]='payu';
    	}
    	if (getOptionA('admin_bankdeposit_enabled')=="yes"){
    		$payment_available[]='obd';
    	}
    	if (getOptionA('admin_paysera_enabled')=="yes"){
    		$payment_available[]='pys';
    	}
    	if (getOptionA('admin_enabled_barclay')=="yes"){
    		$payment_available[]='bcy';
    	}
    	if (getOptionA('admin_enabled_epaybg')=="yes"){
    		$payment_available[]='epy';
    	}
    	if (getOptionA('admin_enabled_autho')=="yes"){
    		$payment_available[]='atz';
    	}
    	
    	$new_payment_list='';
		if (is_array($payment_list) && count($payment_list)>=1){
			foreach ($payment_list as $key=>$val) {
				if(in_array($key,(array)$payment_available)){
				   $new_payment_list[$key]=$val;
				}
			}
		}
		return $new_payment_list;
    }
    
    public static function getMerchantPaymentList($merchant_id='')
    {
    	$payment_list=self::PaymentOptionList();

        $is_commission=false;
		if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
			//commission
			$is_commission=true;
			$payment_available=Yii::app()->functions->getMerchantListOfPaymentGateway();			
		} else {
			//membership			
			if ( getOption($merchant_id,'merchant_disabled_cod')==""){
				$payment_available[]='cod';
			}
			if ( getOption($merchant_id,'merchant_disabled_ccr')==""){
				$payment_available[]='ocr';
			}
			if ( getOption($merchant_id,'enabled_paypal')=="yes"){
				$payment_available[]='pyp';
			}
			if ( getOption($merchant_id,'stripe_enabled')=="yes"){
				$payment_available[]='stp';
			}
			if ( getOption($merchant_id,'merchant_mercado_enabled')=="yes"){
				$payment_available[]='mcd';
			}
			if ( getOption($merchant_id,'merchant_sisow_enabled')=="yes"){
				$payment_available[]='ide';
			}
			if ( getOption($merchant_id,'merchant_payu_enabled')=="yes"){
				$payment_available[]='payu';
			}
			if ( getOption($merchant_id,'merchant_paysera_enabled')=="yes"){
				$payment_available[]='pys';
			}
			if ( getOption($merchant_id,'merchant_payondeliver_enabled')=="yes"){
				$payment_available[]='pyr';
			}
			if ( getOption($merchant_id,'merchant_enabled_barclay')=="yes"){
				$payment_available[]='bcy';
			}
			if ( getOption($merchant_id,'merchant_enabled_epaybg')=="yes"){
				$payment_available[]='epy';
			}
			if ( getOption($merchant_id,'merchant_enabled_autho')=="yes"){
				$payment_available[]='atz';
			}
			if ( getOption($merchant_id,'merchant_bankdeposit_enabled')=="yes"){
				$payment_available[]='obd';
			}		
			
			$admin_available_payment=Yii::app()->functions->getMerchantListOfPaymentGateway();
			if (is_array($admin_available_payment) && count($admin_available_payment)>=1 ){
				foreach ($payment_available as $key=>$val) {
					if ( !in_array($val, (array) $admin_available_payment)){
						unset($payment_available[$key]);
					}
				}
			} else $payment_available='';
		}		
				
		$new_payment_list='';
		if (is_array($payment_list) && count($payment_list)>=1){
			foreach ($payment_list as $key=>$val) {
				if(in_array($key,(array)$payment_available)){
				   $new_payment_list[$key]=$val;
				}
			}
		}
		
		/*Check Admin individual settings for cod, offline cc, payon delivery*/
		if ( getOption($merchant_id,'merchant_switch_master_cod')==2){
			//cod
			if (array_key_exists('cod',(array)$new_payment_list)){
				unset($new_payment_list['cod']);
			}
		}
		if ( getOption($merchant_id,'merchant_switch_master_ccr')==2){
			//ocr
			if (array_key_exists('ocr',(array)$new_payment_list)){
				unset($new_payment_list['ocr']);
			}
		}
		if ( getOption($merchant_id,'merchant_switch_master_pyr')==2){
			//pyr
			if (array_key_exists('pyr',(array)$new_payment_list)){
				unset($new_payment_list['pyr']);
			}
		}
		
		
		/*check if has payment on delivery = pyr */
		if (array_key_exists('pyr',(array)$new_payment_list)){
			if ($is_commission){
				$provider_list=Yii::app()->functions->getPaymentProviderListActive();         	
			} else {
				$provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
			}			
			if (!is_array($provider_list) && count($provider_list)<=1){				
				unset($new_payment_list['pyr']);
			} 
		}

		return $new_payment_list;
    }
    
    public static function displayCashAvailable($merchant_id='',$echo=true)
    {
    	$payment_list=self::PaymentOptionList();        

        $is_commission=false;
		if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
			//commission
			$is_commission=true;
			$payment_available=Yii::app()->functions->getMerchantListOfPaymentGateway();			
		} else {
			//membership			
			if ( getOption($merchant_id,'merchant_disabled_cod')==""){
				$payment_available[]='cod';
			}
		}
		
		$new_payment_list='';
		if (is_array($payment_list) && count($payment_list)>=1){
			foreach ($payment_list as $key=>$val) {
				if(in_array($key,(array)$payment_available)){
				   $new_payment_list[$key]=$val;
				}
			}
		}
		/*Check Admin individual settings for cod, offline cc, payon delivery*/
		if ( getOption($merchant_id,'merchant_switch_master_cod')==2){
			//cod
			if (array_key_exists('cod',(array)$new_payment_list)){
				unset($new_payment_list['cod']);
			}
		}
		
		/*check if has payment on delivery = pyr */
		if (array_key_exists('pyr',(array)$new_payment_list)){
			if ($is_commission){
				$provider_list=Yii::app()->functions->getPaymentProviderListActive();         	
			} else {
				$provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
			}			
			if (!is_array($provider_list) && count($provider_list)<=1){				
				unset($new_payment_list['pyr']);
			} 
		}
		
		if (array_key_exists('cod',(array)$new_payment_list)){
			if ($echo){				
				echo "<p class=\"cod-text\">".t("Cash on delivery available")."</p>";
			} else return true;
		}
		return false;
    }
    
    public static function cookieLocation()
    {
    	$check_cookie=false;
    	if (!isset($_SESSION['client_location'])){
    		$check_cookie=true;
    	} else {
    		if (empty($_SESSION['client_location'])){
    			$check_cookie=true;
    		}
    	}
    	if ($check_cookie){    		
    		$temp_location=Cookie::getCookie('client_location');    	    
    	    if (!empty($temp_location)){    	    	
    	    	$temp_location=json_decode($temp_location,true);    	    	
    	    	$_SESSION['client_location']=$temp_location;
    	    }
    	}
    }
    
    public static function getMerchantPaymentMembership($merchant_id='',$package_id='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{package_trans}}
    	WHERE
    	merchant_id=".self::q($merchant_id)."
    	AND
    	package_id =".self::q($package_id)."
    	ORDER BY id DESC
    	LIMIT 0,1
    	";    	
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public static function getAvatar($client_id='')
    {
    	if ( $res= Yii::app()->functions->getClientInfo($client_id) ){
    		$file=$res['avatar'];
    	} else $file='avatar.jpg';
    	
    	if (empty($file)){
    		$file='avatar.jpg';
    	}
    	    	    
    	$path=Yii::getPathOfAlias('webroot')."/upload/$file";
    	
    	if ( file_exists($path) ){       		 		    	
    		return uploadURL()."/$file";
    	} else return assetsURL()."/images/avatar.jpg";    	
    }
    
    public static function prettyUrl($url='')
    {
    	if (preg_match("/http/i", $url)) {
    		return $url;
    	}
    	return "http://".$url;
    }
    
    public static function customPageUrl($data='')
    {
    	if (is_array($data) && count($data)>=1){
    		if ( $data['is_custom_link']==1){
    			return Yii::app()->createUrl('/store/page/'.$data['slug_name']);
    		} else {
    			return self::prettyUrl($data['content']);
    		}
    	}
    	return "#";
    }
    
    public static function openAsNewTab($data='')
    {
    	if (is_array($data) && count($data)>=1){
    		if ( $data['open_new_tab']==2){
    			echo " target=\"_blank\" ";
    		}
    	}
    	return false;
    }
    
    public static function getSessionAddress()
    {
    	$kr_search_adrress='';
    	if (isset($_SESSION['kr_search_address'])){	
			$kr_search_adrress=$_SESSION['kr_search_address'];		
		} else {
			$kr_search_adrress=Cookie::getCookie('kr_search_address');
			if (!empty($kr_search_adrress)){
				$_SESSION['kr_search_address']=$kr_search_adrress;
			}
		}
		
		return $kr_search_adrress;
    }
    
    public static function receiptRowTotal($label='',$value='',$class1='',$class2='')
    {
    	$html='';
    	$html.="<div class=\"row\">";
    	$html.="<div class=\"col-md-6 col-xs-6 text-right $class1\">".t($label)."</div>";
    	$html.="<div class=\"col-md-6 col-xs-6 text-right $class2\">$value</div>";
    	$html.="</div>";
    	return $html;
    }
    
    public static function getTopMenuActivated()
    {
		$theme_top_menu=getOptionA('theme_top_menu');
		if(empty($theme_top_menu)){
			$theme_top_menu=array(
			  'browse','resto_signup','contact','signup'
			);
		} else $theme_top_menu=json_decode($theme_top_menu,true);
		
		return $theme_top_menu;
    }
    
    public static function getLanguage()
    {
    	$lang[-999]=t("English");
    	if ($list=Yii::app()->functions->getLanguageList()){
    		foreach ($list as $val) {
    			$lang[$val['lang_id']]=$val['language_code'];
    		}
    	}
    	return $lang;
    }
    
    public static function receiptTableRow($label='',$value='')
    {
    	?>
    	<tr>
         <td><?php echo t($label)?></td>
         <td class="text-right"><?php echo $value?></td>
        </tr>
    	<?php
    }
    
    public static function login($user='',$pass='')
    {
		$stmt="SELECT * FROM
    	{{client}}
    	WHERE
    	email_address=".Yii::app()->db->quoteValue($user)."
    	AND
    	password=".Yii::app()->db->quoteValue(md5($pass))."
    	AND
    	status IN ('active','pending')
    	LIMIT 0,1
    	";
		$DbExt=new DbExt;
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }
    
    public static function sendEmailVerificationCode($to='',$code='',$info='')
    {
    	$tpl=EmailTPL::signupEmailVerification();
    	$tpl=smarty('firstname',isset($info['first_name'])?$info['first_name']:'',$tpl);
    	$tpl=smarty('code',$code,$tpl);
    	if (sendEmail($to,'',t("Your signup verification code"),$tpl)){
    		return true;
    	} 
    	return false;
    }
    
    public static function getMapMarker()
    {
    	$map_marker=getOptionA('map_marker');
    	$upload_path=self::uploadPath();
    	if (!empty($map_marker)){
	    	if (file_exists($upload_path."/$map_marker")){
	    		return uploadURL()."/$map_marker";
	    	}
    	}
    	return assetsURL()."/images/map_pointer_small.png";
    }
    
    public static function reCheckDelivery($merchant_id='',$data='')
    {
    	if($merchant_info=FunctionsV3::getMerchantById($merchant_id)){
    		$distance_type=FunctionsV3::getMerchantDistanceType($merchant_id); 
    		
    		/*$lat=isset($_SESSION['client_location']['lat'])?$_SESSION['client_location']['lat']:'';
    		$lng=isset($_SESSION['client_location']['long'])?$_SESSION['client_location']['long']:'';*/
    		
    		$complete_address=$data['street']." ".$data['city']." ".$data['state']." ".$data['zipcode'];
    		$lat=0;
			$long=0;
			
    		/*if address book was used*/
    		if ( isset($data['address_book_id'])){
	    		if ($address_book=Yii::app()->functions->getAddressBookByID($data['address_book_id'])){
	        		$complete_address=$address_book['street'];	    	
	    	        $complete_address.=" ".$address_book['city'];
	    	        $complete_address.=" ".$address_book['state'];
	    	        $complete_address.=" ".$address_book['zipcode'];
	        	}	    		        
	    	}
	    	
	    	if ($lat_res=Yii::app()->functions->geodecodeAddress($complete_address)){
		        $lat=$lat_res['lat'];
				$lng=$lat_res['long'];
	    	}
	    	
	    	/*if map address was used*/
    		if (isset($data['map_address_toogle'])){
    			if ($data['map_address_toogle']==2){
    				$lat=$data['map_address_lat'];
    				$lng=$data['map_address_lng'];
    			}
    		}
    		
    		/*dump($complete_address);
    		dump($lat);
    		dump($lng);
    		dump($data);*/
    		
    		$distance=FunctionsV3::getDistanceBetweenPlot(
				$lat,
				$lng,
				$merchant_info['latitude'],$merchant_info['lontitude'],$distance_type
			);  
			//dump($distance);
			$distance_type_raw = $distance_type=="M"?"miles":"kilometers";
			$distance_type = $distance_type=="M"?t("miles"):t("kilometers");
			$merchant_delivery_distance=getOption($merchant_id,'merchant_delivery_miles'); 
			
			if(!empty(FunctionsV3::$distance_type_result)){
             	$distance_type_raw=FunctionsV3::$distance_type_result;
            }
			
			if (is_numeric($merchant_delivery_distance)){
				if ( $distance>$merchant_delivery_distance){
					 if($distance_type_raw=="ft" || $distance_type_raw=="mm" || $distance_type_raw=="mt"){
					 	return true;
					 }
		             return false;
                } else {
                	$delivery_fee=self::getMerchantDeliveryFee(
								              $merchant_id,
								              $merchant_info['delivery_charges'],
								              $distance,
								              $distance_type_raw);
                    //dump($delivery_fee);
                    $_SESSION['shipping_fee']=$delivery_fee;
                    return true;								              
                }
			}
    	}
    	return true;
    }
    
    public static function verifyMerchantSlug($slug_name='',$merchant_id='')
    {
    	$DbExt=new DbExt;
    	if ( is_numeric($merchant_id)){
    		$stmt="
    		SELECT count(*) as total FROM
    		{{merchant}}
    		WHERE
    		restaurant_slug=".self::q($slug_name)."
    		AND 
    		merchant_id <> ".self::q($merchant_id)."
    		";
    	} else {
    	    $stmt="
    		SELECT count(*) as total FROM
    		{{merchant}}
    		WHERE
    		restaurant_slug=".self::q($slug_name)."
    		";
    	}
    	//dump($stmt);
    	if ($res=$DbExt->rst($stmt)){
    		if ($res[0]['total']>0){
    		    $slug_name=Yii::app()->functions->seo_friendly_url($slug_name.$res[0]['total']);
    		}
    	} 
    	return $slug_name;
    }
    
    public static function getCuisine()
    {
    	$lists='';
        $DbExt=new DbExt;
		$stmt="SELECT *
		      FROM
		      {{cuisine}}
		      ORDER BY sequence ASC
		";
		$data='';
    	if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {
				$id='"'.$val['cuisine_id'].'"';
				$stmt2="
				SELECT count(*) AS total
				FROM
				{{merchant}}
				WHERE
				cuisine LIKE '%$id%'
				AND status='active'
				AND is_ready ='2'
				";
				$count=$DbExt->rst($stmt2);
				$val['total']=$count[0]['total'];
				$data[]=$val;
			}
			return $data;
		}
		return false;
    }
		
}/* end*/