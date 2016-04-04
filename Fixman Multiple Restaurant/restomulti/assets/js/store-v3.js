var smap; /*global map variable*/
var otables;

jQuery(document).ready(function() {		
	
	if ( $(".tab-byaddress").exists()){
	    $(".tab-byaddress").fadeIn("slow");
	}
	
	/*DESKTOP MENU*/
	
	if ( $(".search-menu").exists() )
	{
		var selected=$(".search-menu li:first-child");
		var class_name=selected.find("a").attr("data-tab");
		$(".forms-search").hide();
		$("."+class_name).fadeIn("slow");	
	}
	
	$( document ).on( "click", ".search-menu a", function() {
		var tab = $(this).data("tab");		
		$(".search-menu a").removeClass("selected");
		$(this).addClass("selected");		
		
		$(".forms-search").hide();
		$("."+tab).fadeIn("slow");		
		
		switch (tab)
		{
			case "tab-byaddress":			
			$(".home-search-text").html( $("#find_restaurant_by_address").val() );
			break;
			
			case "tab-byname":
			$(".home-search-text").html( js_lang.find_restaurant_by_name );
			break;
			
			case "tab-bystreet":
			$(".home-search-text").html( js_lang.find_restaurant_by_streetname );
			break;
			
			case "tab-bycuisine":
			$(".home-search-text").html( js_lang.find_restaurant_by_cuisine );
			break;
			
			case "tab-byfood":
			$(".home-search-text").html( js_lang.find_restaurant_by_food );
			break;
		}
		
	});
	
	/*MOBILE MENU*/
	$( document ).on( "click", ".mobile-search-menu a", function() {
		var tab = $(this).data("tab");		

		$(".mobile-search-menu a").removeClass("selected");
		$(this).addClass("selected");		
		
		$(".forms-search").hide();
		$("."+tab).fadeIn("slow");		
		
		switch (tab)
		{
			case "tab-byaddress":			
			$(".home-search-text").html( $("#find_restaurant_by_address").val() );
			break;
			
			case "tab-byname":
			$(".home-search-text").html( js_lang.find_restaurant_by_name );
			break;
			
			case "tab-bystreet":
			$(".home-search-text").html( js_lang.find_restaurant_by_streetname );
			break;
			
			case "tab-bycuisine":
			$(".home-search-text").html( js_lang.find_restaurant_by_cuisine );
			break;
			
			case "tab-byfood":
			$(".home-search-text").html( js_lang.find_restaurant_by_food );
			break;
		}
		
	});
	
	
	/*RATING STARS*/
	if ( $(".rating-stars").exists() ){
	   initRating();
	}
	
	if ( $(".raty-stars").exists() ){
		$('.raty-stars').raty({ 
		   readOnly: false, 		
		   hints:'',
		   path: sites_url+'/assets/vendor/raty/images',
		   click: function (score, evt) {
		   	   $("#initial_review_rating").val(score);
		   }
        });    
	}

	/*FILTER BOX*/
	$( document ).on( "click", ".filter-box a", function() {
		var parent=$(this).parent();
		var t=$(this);
		var i=t.find("i");		
		if ( i.hasClass("ion-ios-arrow-thin-right")){
			i.removeClass("ion-ios-arrow-thin-right");
			i.addClass("ion-ios-arrow-thin-down");
		} else {
			i.addClass("ion-ios-arrow-thin-right");
			i.removeClass("ion-ios-arrow-thin-down");
		}
		var parent2 = parent.find("ul").slideToggle( "fast", function() {
			 parent2.removeClass("hide");
        });
	});
				
    if ( $(".infinite-container").exists()) { 	
		var infinite = new Waypoint.Infinite({
	       element: $('.infinite-container')[0],       
	       onBeforePageLoad : function() {
	       	  dump('onBeforePageLoad');
	       	  $(".search-result-loader").show();
	       },
	       onAfterPageLoad : function() {
	       	  dump('onAfterPageLoad');
	       	  $(".search-result-loader").hide();
	       	  initRating();
	       	  removeFreeDelivery();
	       	  if ( $("#restuarant-list").exists() ){
	    	       plotMap();
	          }           
	       }
	    }); 
   }
   
   $( document ).on( "click", ".display-type", function() {   	   
   	   $("#display_type").val( $(this).data("type") );   	   
   	   research_merchant(); 
   });    
        
   $('.filter_promo').on('ifChecked', function(event){      
      $(".non-free").fadeOut("fast");
   });
   
   $('.filter_promo').on('ifUnchecked', function(event){       
       $(".non-free").fadeIn("fast");
   });        

   /*SEARCH MAP TOOGLE*/  
   $( document ).on( "click", ".search-view-map, #mobile-viewmap-handle", function() {   	   
   	   if ( $(".search-map-results").hasClass("down") ){
   	   	 $(".search-map-results").slideUp( 'slow', function(){ 
   	      	   $(".search-map-results").removeClass("down")
   	      });
   	   } else {
   	      $(".search-map-results").slideDown( 'slow', function(){ 
   	      	   $(".search-map-results").addClass("down");
   	      	   dump('load map');   	 
   	      	   map = new GMaps({
					div: '.search-map-results',
					lat: $("#clien_lat").val(),
					lng: $("#clien_long").val(),
					scrollwheel: false ,
					styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}],
				    markerClusterer: function(map) {
                        return new MarkerClusterer(map);
                    }
			   });      	   
   	      	   callAjax('loadAllRestoMap','');  	      	   
   	      }); /*end slidedown*/
   	   }
   });
   

    /*TABS*/
    $("ul#tabs li").click(function(e){
    	if ( $(this).hasClass("noclick") ){
    		return;    		
    	}
        if (!$(this).hasClass("active")) {
            var tabNum = $(this).index();
            var nthChild = tabNum+1;
            $("ul#tabs li.active").removeClass("active");
            $(this).addClass("active");
            $("ul#tab li.active").removeClass("active");
            $("ul#tab li:nth-child("+nthChild+")").addClass("active");
        }
    });
   /*END TABS*/   

   /*SET MENU STICKY*/
   var disabled_cart_sticky=$("#disabled_cart_sticky").val();
   if ( $(".menu-right-content").exists() ){	   
   	   dump(disabled_cart_sticky);
   	   if (disabled_cart_sticky!=2){
		   jQuery('.menu-right-content, .category-list').theiaStickySidebar({      
		      additionalMarginTop: 8
		   }); 
   	   }
   }
   if ( $(".sticky-div").exists() ){	   	  
   	   if (disabled_cart_sticky!=2){ 
		   jQuery('.sticky-div').theiaStickySidebar({      
		      additionalMarginTop: 8
		   }); 
   	   }
   }  
    
   /*MENU 1*/  
   $( document ).on( "click", ".menu-1 .menu-cat a", function() {
		var parent=$(this).parent();		
		var t=$(this);
		var i=t.find("i");		
		if ( i.hasClass("ion-ios-arrow-thin-right")){
			i.removeClass("ion-ios-arrow-thin-right");
			i.addClass("ion-ios-arrow-thin-down");
		} else {
			i.addClass("ion-ios-arrow-thin-right");
			i.removeClass("ion-ios-arrow-thin-down");
		}

		var parent2 = parent.find(".items-row").slideToggle( "fast", function() {
			 parent2.removeClass("hide");
        });
	});
	
	/*READ MORE*/
	initReadMore();
				
	$( document ).on( "click", ".view-reviews", function() {	
		if ( $(".merchant-review-wrap").html()=="" ){
		    load_reviews();			
		    initReadMore();
		}
	});	
	
	$( document ).on( "click", ".write-review-new", function() {		
		$(".review-input-wrap").slideToggle("fast");
	});
	
	
	$( document ).on( "click", ".view-merchant-map", function() {	
		 	
		 $(".direction_output").css({"display":"none"});	
		 
		 var lat=$("#merchant_map_latitude").val();
		 var lng=$("#merchant_map_longtitude").val();	
		 	 
		 if (empty(lat)){
		 	 uk_msg(js_lang.trans_9);
		 	 $(".direction-action").hide();
		 	 return;
		 }
		 if (empty(lng)){
		 	 uk_msg(js_lang.trans_9);
		 	 $(".direction-action").hide();
		 	 return;
		 }		 		 		 
		 
		 $(".direction-action").show();
		 		 		 
		 smap = new GMaps({
			div: '#merchant-map',
			lat: lat,
			lng: lng,
			scrollwheel: false ,
			styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}]
		 });      	  		 
		 
		 var resto_info='';	
		 if ( !empty(merchant_information)){
			 resto_info+='<div class="marker-wrap">';
		   	   resto_info+='<div class="row">';
			   	   resto_info+='<div class="col-md-4 ">';
			   	   resto_info+='<img class="logo-small" src="'+merchant_information.merchant_logo+'" >';
			   	   resto_info+='</div>';
			   	   resto_info+='<div class="col-md-8 ">';
				   	   resto_info+='<h3 class="orange-text">'+merchant_information.restaurant_name+'</h3>'; 
				   	   resto_info+='<p class="small">'+merchant_information.merchant_address+'</p>';				   	   
				   resto_info+='</div>';
		   	   resto_info+='</div>';
	   	    resto_info+='<div>';  
		 } else {
		 	resto_info='';
		 }		 
		 smap.addMarker({
			lat: lat,
			lng: lng,
			title: $("#restaurant_name").val(),
			icon : map_marker ,
			infoWindow: {
			  content: resto_info
			}
		});			
	});
	
	/*MERCHANT PHOTOS*/	
	$( document ).on( "click", ".view-merchant-photos", function() {	
		if ( $("#photos").exists() ){
		   $("#photos").justifiedGallery();
		}
	});	
	
	if( $('.section-payment-option').exists()) {	
       load_cc_list();
    }
            
    $('.payment_option').on('ifChecked', function(event){   	      	
    	var seleted_payment=$(this).val();
    	dump(seleted_payment);
    	switch (seleted_payment)
    	{
    		case "ocr":    		
    		$(".credit_card_wrap").show();
    		$(".change_wrap").hide();
    		$(".payment-provider-wrap").hide();
    		break;
    		
    		case "cod":
    		$(".credit_card_wrap").hide();
    		$(".change_wrap").show();
    		$(".payment-provider-wrap").hide();
    		break;
    		
    		case "pyr":
    		$(".payment-provider-wrap").show();
    		$(".credit_card_wrap").hide();
    		$(".change_wrap").hide();
    		break;
    		
    		default:
    		$(".credit_card_wrap").hide();
    		$(".change_wrap").hide();
    		$(".payment-provider-wrap").hide();
    		break;
    	}
    });  


    if ($("#contact-map").exists()){
    	dump(website_location);    	
    	map = new GMaps({
			div: '#contact-map',
			lat: website_location.map_latitude ,
			lng: website_location.map_longitude ,
			scrollwheel: false ,
			disableDefaultUI: true,
			styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1} ]}]
	    });      	    
	    map.addMarker({
			lat: website_location.map_latitude,
			lng: website_location.map_longitude ,			
			icon : map_marker			
		}); 	    	    
    }
    
    if ( $("#restuarant-list").exists() ){
    	plotMap();
    }
    
    if ( $(".section-merchant-payment").exists() ){
    	load_cc_list_merchant();
    }
    
    $( document ).on( "change", "#change_package", function() {	
		var package_id=$(this).val();
		window.location.href=$("#change_package_url").val()+package_id;
	});	
	
	/*CAPCHA*/
	setTimeout('onloadMyCallback()', 2100);
			
	if ( $(".section-address-book").exists() ){
		if ( $("#table_list_info").exists() ){
		} else {
			table();
		}
	}
				
	$( document ).on( "click", ".row_remove", function() {
		var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	var table = $(this).data("table");
		    var whereid = $(this).data("whereid");
		    var id = $(this).data("id");
		    rowRemove(id, table, whereid, $(this) );		
        }		
	});
		
	if ( $(".otable").exists() ){
		initOtable();
	}
	
	if( $('#uploadavatar').exists() ) {    	
       createUploader('uploadavatar','uploadAvatar');
    }    
      
    
    if ( $(".search_resto_name").exists() ){    	
    	iniRestoSearch('search_resto_name','AutoResto');  
    	iniRestoSearch('street_name','AutoStreetName');  
    	iniRestoSearch('cuisine','AutoCategory');  
    	iniRestoSearch('foodname','AutoFoodName'); 
    }
    if ( $(".search-by-postcode").exists() ){
    	dump('d2x');
    	iniRestoSearch('zipcode','AutoZipcode'); 
    }
    
    $( document ).on( "click", ".full-maps", function() {    	
    	dump(country_coordinates);     	
    	map = new GMaps({
			div: '#full-map',
			lat: country_coordinates.lat ,
			lng: country_coordinates.lng ,
			scrollwheel: false ,
			styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}],
			zoom: 6,
		    markerClusterer: function(map) {
                return new MarkerClusterer(map);
            }
	    });      	   
    	
    	callAjax("loadAllMerchantMap")
    });    
    
    $( document ).on( "click", ".view-full-map", function() {        		   
        $(".full-map-wrapper").toggleClass("full-map");
        if ( $(".full-map-wrapper").hasClass("full-map") ) {
        	$(this).html(js_lang.close_fullscreen);
        	$(".view-full-map").removeClass("green-button");
        	$(".view-full-map").addClass("orange-button");
        } else {
        	$(this).html(js_lang.view_fullscreen);     	
        	$(".view-full-map").addClass("green-button");
        	$(".view-full-map").removeClass("orange-button");
        }
    });    
    
    $( document ).on( "click", ".menu-nav-mobile a", function() { 
       $(".menu-top-menu").slideToggle("fast");
    });	
    
  
    $( document ).on( "click", "#mobile-filter-handle", function() { 
    	 
         toogleModalFilter("#mobile-search-filter");
         
         $('.filter_by').on('ifChecked', function(event){
            research_merchant();       
         });
         $('.filter_by').on('ifUnchecked', function(event){
            research_merchant();   
         }); 
         $('.filter_by_radio').on('ifChecked', function(event){  
	       $(".filter_minimum_clear").show();   
	       research_merchant();   
	     });
	     $('.filter_promo').on('ifChecked', function(event){      
		      $(".non-free").fadeOut("fast");
		 });
	     $('.filter_promo').on('ifUnchecked', function(event){       
	        $(".non-free").fadeIn("fast");
	     });    
    });
    
    $( document ).on( "click", ".cart-mobile-handle", function() { 
         toogleModalFilter("#menu-right-content");
    });
    
    /*MOBILE SINGLE PAGE FOR FOOD ITEM*/
    if ( $("#mobile-view-food-item").exists()){
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
    }
    
    $( document ).on( "change", ".language-options", function() {
    	if ( $(this).val() != ""){
    		redirect=home_url+"/setlanguage/Id/"+ $(this).val();
    		dump(redirect);
    		window.location.href=redirect;
    	}
    });
    
   $( document ).on( "click", ".view-receipt-front", function() {    	       	 	
   	   var params="order_id="+ $(this).data("id")+"&post_type=get";
       fancyBoxFront('viewReceipt',params);
   });	
   
   /*COOKIE LAW*/
   if ( $(".cookie-wrap").exists() ){
   	   var kr_cookie_law =$.cookie('kr_cookie_law');	
   	   dump("kr_cookie_law:"+kr_cookie_law);
   	   if (empty(kr_cookie_law)){
   	   	  $(".cookie-wrap").fadeIn("fast");
   	   }
   }
   $( document ).on( "click", ".accept-cookie", function() { 
   	   $(".cookie-wrap").fadeOut("slow");
   	   $.cookie('kr_cookie_law', '2', { expires: 500, path: '/' }); 
   });
   
   $( document ).on( "click", ".cookie-close", function() { 
   	   $(".cookie-wrap").fadeOut("slow");
   });
   
   $( document ).on( "click", ".resend-email-code", function() { 
        callAjax('resendEmailCode','client_id='+$("#client_id").val());
   });
   
                   
}); /*end docu*/

function fancyBoxFront(action,params)
  {  	  	  	  	
  	dump(params);
	var URL=front_ajax+"/"+action+"/?"+params;
		$.fancybox({        
		maxWidth:800,
		closeBtn : false,          
		autoSize : true,
		padding :0,
		margin :2,
		modal:false,
		type : 'ajax',
		href : URL,
		openEffect :'elastic',
		closeEffect :'elastic',
		helpers: {
		    overlay: {
		      locked: false
		    }
		 }
	});   
}

$('#mobile-search-filter').on('hidden.bs.modal', function (e) {
   $("#mobile-search-filter").removeClass("fade");
   $("#mobile-search-filter").removeClass("modal");
   $(".modal-close-btn").hide();
});


$('#menu-right-content').on('hidden.bs.modal', function (e) {
   $("#menu-right-content").removeClass("fade");
   $("#menu-right-content").removeClass("modal");
   $(".modal-close-btn").hide();
});

function toogleModalFilter(id)
{

   if ( id=="#menu-right-content"){
   	   $(id).css("overflow",'');
   	   $(id).css("position",'');
   }
	
   if ( $(id).hasClass("modal") ){
   	   $(id).removeClass("fade");
       $(id).removeClass("modal");
   	   $(id).modal('hide');
   	   $(".modal-close-btn").hide();
   } else {  	   
       $('.icheck').iCheck({
	       checkboxClass: 'icheckbox_minimal',
	       radioClass: 'iradio_flat'
	   });
   	   $(id).addClass("fade");
   	   $(id).addClass("modal");
   	   $(id).modal('show');
   	   $(".modal-close-btn").show();
   	   
   	   if ( id=="#menu-right-content"){
   	   	  load_item_cart();
   	   }
   	   
   }
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-addressbook',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-addressbook');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '.krms-forms',    
    onError : function() {      
    },
    onSuccess : function() {     
      var params=$(".krms-forms").serialize();
      var action=$(".krms-forms").find("#action").val();
      callAjax(action,params, $(".krms-forms-btn") );
      return false;
    }  
});

function plotMap()
{
	dump('plotMap');
	$( ".browse-list-map.active" ).each(function( index ) {
							
		var lat=$(this).data("lat");
		var lng=$(this).data("long");
		
		map = new GMaps({
			div: this,
			lat: lat ,
			lng: lng ,
			scrollwheel: false ,			
			styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1} ]}]
	    });      

	    map.addMarker({
			lat: lat,
			lng: lng ,			
			icon : map_marker			
		}); 	    

		$(this).removeClass("active"); 
			     			
	});
}

function initReadMore()
{
	if ( $(".read-more").exists() ){				
	    $('.read-more').readmore({
	    	moreLink:'<a class="small" href="javascript:;">'+js_lang.read_more+'</a>',
	    	lessLink:'<a class="small" href="javascript:;">'+js_lang.close+'</a>'
	    });
	}
}

function initRating()
{
	$('.rating-stars').raty({ 
		readOnly: true, 
		score: function() {
             return $(this).attr('data-score');
       },
		path: sites_url+'/assets/vendor/raty/images',
		hints:''
    });    
}

function removeFreeDelivery()
{
	var filter_promo=$(".filter_promo:checked").val();	
	if ( filter_promo=="free-delivery"){		
		$(".non-free").fadeOut("fast");
	}
}

function callAjax(action,params,buttons)
{
	dump(action);
	dump(params);
	busy(true);
	var buttons_text='';	
	
	if (!empty(buttons)){
		buttons_text=buttons.html();
		buttons.html('<i class="fa fa-refresh fa-spin"></i>');
		buttons.css({ 'pointer-events' : 'none' });
	}
	
    $.ajax({    
    type: "POST",
    url: front_ajax+"/"+action,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);    
    	if (!empty(buttons)){
    		buttons.html(buttons_text);
		    buttons.css({ 'pointer-events' : 'auto' });
    	}
    	
    	if (data.code==1){
    		switch (action)
    		{
    			case "loadAllMerchantMap":
    			case "loadAllRestoMap":    		
    			
    			   var last_lat='';
    			   var last_lng='';
    			   var bounds = [];
    			   
    			   $.each(data.details, function( index, val ) {
    			   	   
    			   	   resto_info='';
    			   	       			   	   
    			   	   resto_info+='<div class="marker-wrap">';
	    			   	   resto_info+='<div class="row">';
		    			   	   resto_info+='<div class="col-md-4 ">';
		    			   	   resto_info+='<img class="logo-small" src="'+val.logo+'" >';
		    			   	   resto_info+='</div>';
		    			   	   resto_info+='<div class="col-md-8 ">';
			    			   	   resto_info+='<h3 class="orange-text">'+val.restaurant_name+'</h3>'; 
			    			   	   resto_info+='<p class="small">'+val.merchant_address+'</p>'; 
			    			   	   resto_info+='<a class="orange-button" href="'+val.link+'">'+js_lang.trans_37+'</a>';
			    			   resto_info+='</div>';
	    			   	   resto_info+='</div>';
    			   	   resto_info+='<div>';    		
    			   	   
    			   	   last_lat=val.latitude;
    			   	   last_lng=val.lontitude;
    			   	  
    			   	   var latlng = new google.maps.LatLng( last_lat , last_lng );
    			   	   bounds.push(latlng);
    			   	    
    			   	    map.addMarker({
							lat: val.latitude,
							lng: val.lontitude,
							title: val.restaurant_name,
							icon : map_marker ,
							infoWindow: {
							  content: resto_info
							}
						});		     
						
    			   });    			   
    			
    			   if ( $("#full-map").exists() ){
    			   	   //map.setCenter(last_lat,last_lng);
    			   	   map.fitLatLngBounds(bounds);
    			   }
    			   
    			   if ( $(".search-map-results").exists() ){
    			   	   map.fitLatLngBounds(bounds);
    			   }
    			   
    			break;
    			    			    			    			
    			default:
    			   uk_msg_sucess(data.msg);
    			   if (!empty(data.details)){
    			   	   if (!empty(data.details.redirect)){
    			   	   	   dump(data.details.redirect);
    			   	   	   window.location.href=data.details.redirect;
    			   	   	   return;
    			   	   }
    			   }    			   
    			break;
    		}
    	} else {
    		uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    	if (!empty(buttons)){
    		buttons.html(buttons_text);
		    buttons.css({ 'pointer-events' : 'auto' });
    	}
    }		
    });   	     	  
}

function onloadMyCallback()
{				
	//dump('init kapcha');
	if ( $("#kapcha-1").exists()){
	   if ( $("#kapcha-1").html()=="" ){
	        recaptcha1=grecaptcha.render(document.getElementById('kapcha-1'), {'sitekey' : captcha_site_key});    
	   } 
	}
	if ( $("#kapcha-2").exists()){
		if ( $("#kapcha-2").html()=="" ){
	        recaptcha1=grecaptcha.render(document.getElementById('kapcha-2'), {'sitekey' : captcha_site_key});    
		}
	}
}

function initOtable()
{
	dump('otables');	
	otables = $('.otable').dataTable({
	"bProcessing": true, 
	"bServerSide": false,	    
	"bFilter":false,
	"bLengthChange":false,	
	"sAjaxSource": front_ajax+"/" + $("#otable_action").val() ,
	"oLanguage":{
		 "sInfo": js_lang.trans_13 ,
		 "sEmptyTable": sEmptyTable,
		 "sInfoEmpty":  js_lang.tablet_3,
		 "sProcessing": "<p>"+js_lang.tablet_7+" <i class=\"fa fa-spinner fa-spin\"></i></p>",
		 "oPaginate": {
	        "sFirst":    js_lang.tablet_10,
	        "sLast":     js_lang.tablet_11,
	        "sNext":     js_lang.tablet_12,
	        "sPrevious": js_lang.tablet_13
	  }
	},
	"fnInitComplete": function (oSettings, json){ 	  
	}
	});		
}

function OtableReload()
{
	otables.fnReloadAjax(); 
}

 function rowRemove(id,tbl,whereid,object)
{			
	busy(true);
	var params="action=rowDelete&tbl="+tbl+"&row_id="+id+"&whereid="+whereid;	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);
        	if (data.code==1){               		
        		tr=object.closest("tr");
                tr.fadeOut("slow");
        	} else {      
        		uk_msg(data.msg);
        	}        	        	
        }, 
        error: function(){	        	        	
        	busy(false);
        	uk_msg(js_lang.trans_14);
        }		
    });
}

function uploadAvatar(data)
{
	dump(data);
	if ( data.code==1){
		$(".avatar-wrap").html( '<img src="'+upload_url+"/"+data.details.file +'" class="img-circle" />' );
		callAjax("saveAvatar",'filename='+data.details.file );
	} else {
		uk_msg(data.msg);
	}
}

function iniRestoSearch(fields,actions)
{
	var parent=$("."+fields).parent().parent();	
	var button=parent.find("i");
	
	var options = {
	  url: function(phrase) {
	    return home_url+"/"+actions;
	  },		
	  getValue: function(element) {
	    return element.name;
	  },		
	  ajaxSettings: {
	    dataType: "json",
	    method: "POST",
	    data: {
	      dataType: "json"
	    },
	    beforeSend: function() {	    		    
	    	busy(true);
	    },
	    complete: function(data) {
	    	busy(false);
	    	dump(data);
	    },
	  },		
	  preparePostData: function(data) {
	    data.search = $("."+fields).val();
	    return data;
	  },
      requestDelay: 400
   };      
   $("."+fields).easyAutocomplete(options);
}


/*JQUERY BROWSER*/
var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

jQuery.browser = browser;
/*JQUERY BROWSER*/



jQuery(document).ready(function() {		
	
	$( document ).on( "click", ".language-selection a", function() {
		$(".language-selection-wrap").slideDown("fast");
	});
	$( document ).on( "click", ".language-selection-close", function() {
		$(".language-selection-wrap").fadeOut("slow");
	});
	$( document ).on( "click", ".lang-selector", function() {
		$(".lang-selector").removeClass("highlight");
		$(this).addClass("highlight");
		$(".change-language").attr("href", home_url+"/setlanguage/Id/"+ $(this).data("id")  );
	});
	
	$( document ).on( "click", ".goto-reviews-tab", function() {
	   $(".view-reviews").click();
	   scroll_class('view-reviews');
	});
	
	if ( $("#theme_time_pick").val() == "2"){	
		var is_twelve_period=false;
		if ( $("#website_time_picker_format").exists() ){		
			if ( $("#website_time_picker_format").val()=="12"){
				is_twelve_period=true;
			}
		}
		var time_format='hh:mm p';
		if (!is_twelve_period){
			dump('24 hour');
			time_format='H:mm';
		} 
		$('.timepick').timepicker({
			timeFormat: time_format
		});
	    $('#booking_time').timepicker({
            timeFormat: 'H:mm'
        });	
	}
	
}); /*ready*/