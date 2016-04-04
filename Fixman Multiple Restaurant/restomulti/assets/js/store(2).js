/*version 2.4*/

var $ = jQuery.noConflict();
var hl_end_content_pos;
var locations;
var global_plot_marker=[];

jQuery.fn.exists = function(){return this.length>0;}

jQuery(document).ready(function() {	
	hl_end_content_pos = parseFloat($('.footer-wrap').position().top)+50;		
	
	var website_date_picker_format="yy-mm-dd";
    if ( $("#website_date_picker_format").exists()){
    	website_date_picker_format= $("#website_date_picker_format").val();
    }
    dump(website_date_picker_format);
    
    jQuery(".j_date").datepicker({ 
    	//dateFormat: 'yy-mm-dd' ,
    	dateFormat: website_date_picker_format,        
        altFormat: "yy-mm-dd",       
    	changeMonth: true,
    	changeYear: true ,	   
	    minDate: 0,
	    prevText: js_lang.datep_1,
		nextText: js_lang.datep_2,
		currentText: js_lang.datep_3,
		monthNames: [js_lang.January,js_lang.February,js_lang.March,js_lang.April,js_lang.May,js_lang.June,
		js_lang.July,js_lang.August,js_lang.September,js_lang.October,js_lang.November,js_lang.December],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		js_lang.Jul, js_lang.Aug, js_lang.Sep, js_lang.Oct, js_lang.Nov, js_lang.Dec],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: [js_lang.Su,js_lang.Mo,js_lang.Tu,js_lang.We,js_lang.Th,js_lang.Fr,js_lang.Sa],						
		isRTL: false,
		onSelect : function( element, object ) {
			var original_id=$(this).data("id");
			dump(original_id);
			var altFormat = $(this).datepicker('option', 'altFormat');
			var currentDate = $(this).datepicker('getDate');
			var formatedDate = $.datepicker.formatDate(altFormat, currentDate);
			dump(formatedDate);
			$("#"+original_id).val(formatedDate);
		}
	});	
	jQuery(".j_date2").datepicker({ 
    	//dateFormat: 'yy-mm-dd' ,
    	dateFormat: website_date_picker_format,        
        altFormat: "yy-mm-dd",       
    	changeMonth: true,
    	changeYear: true ,	   
	    //minDate: 0,
	    prevText: js_lang.datep_1,
		nextText: js_lang.datep_2,
		currentText: js_lang.datep_3,
		monthNames: [js_lang.January,js_lang.February,js_lang.March,js_lang.April,js_lang.May,js_lang.June,
		js_lang.July,js_lang.August,js_lang.September,js_lang.October,js_lang.November,js_lang.December],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		js_lang.Jul, js_lang.Aug, js_lang.Sep, js_lang.Oct, js_lang.Nov, js_lang.Dec],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: [js_lang.Su,js_lang.Mo,js_lang.Tu,js_lang.We,js_lang.Th,js_lang.Fr,js_lang.Sa],						
		isRTL: false,
		onSelect : function( element, object ) {
			var original_id=$(this).data("id");
			dump(original_id);
			var altFormat = $(this).datepicker('option', 'altFormat');
			var currentDate = $(this).datepicker('getDate');
			var formatedDate = $.datepicker.formatDate(altFormat, currentDate);
			dump(formatedDate);
			$("#"+original_id).val(formatedDate);
		}
	});	
	
	/** update 2.4 */
	var booking_mindate=1;	
	if ( $("#accept_booking_sameday").exists() ){
		if ( $("#accept_booking_sameday").val()==2){		
			booking_mindate=0;
		}
	}	
	/** update 2.4 */
	
	jQuery(".date_booking").datepicker({ 
		//dateFormat: 'yy-mm-dd' ,
		dateFormat: website_date_picker_format,        
        altFormat: "yy-mm-dd",       
		changeMonth: true,
		changeYear: true ,	   
	    minDate: booking_mindate,
	    prevText: js_lang.datep_1,
		nextText: js_lang.datep_2,
		currentText: js_lang.datep_3,
		monthNames: [js_lang.January,js_lang.February,js_lang.March,js_lang.April,js_lang.May,js_lang.June,
		js_lang.July,js_lang.August,js_lang.September,js_lang.October,js_lang.November,js_lang.December],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		js_lang.Jul, js_lang.Aug, js_lang.Sep, js_lang.Oct, js_lang.Nov, js_lang.Dec],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: [js_lang.Su,js_lang.Mo,js_lang.Tu,js_lang.We,js_lang.Th,js_lang.Fr,js_lang.Sa],						
		isRTL: false,
		onSelect : function( element, object ) {
			var original_id=$(this).data("id");
			dump(original_id);
			var altFormat = $(this).datepicker('option', 'altFormat');
			var currentDate = $(this).datepicker('getDate');
			var formatedDate = $.datepicker.formatDate(altFormat, currentDate);
			dump(formatedDate);
			$("#"+original_id).val(formatedDate);
		}
	});	
	
	
	var show_period=false;
	if ( $("#website_time_picker_format").exists() ){		
		if ( $("#website_time_picker_format").val()=="12"){
			show_period=true;
		}
	}
	
	jQuery('.timepick').timepicker({        
        showPeriod: show_period,
		hourText: js_lang.Hour,       
		minuteText: js_lang.Minute,  
		amPmText: [js_lang.AM, js_lang.PM],
    });	      
    
    jQuery('#booking_time').timepicker({
        /*showPeriod: true,
        showLeadingZero: true*/
    });	      

          
});	

$(window).scroll(function() {   
	
	if ( hl_get_scroll_position() ) {		
		$(".back-top").show();
	} else {		
		$(".back-top").fadeOut("slow");
	}
});

function hl_get_scroll_position()
{					
	var total_scrol_height=$(window).scrollTop() + $(window).height();				
	
	if( $(window).scrollTop() + $(window).height() == $(document).height()) {       		
	}			
	if ( total_scrol_height >= hl_end_content_pos){
		return true;
	}	
	return false;
}

function clear_elements(ele) {	
    $("#"+ele).find(':input').each(function() {						    	
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;            
            
        }
   });
   
   $(".preview").remove();
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit();
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms-search',    
    onError : function() {      
    },
    onSuccess : function() {           
      return true;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#form-signup',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('form-signup');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-creditcard',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-creditcard');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-resume-signup',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-resume-signup');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-book',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-book');
      return false;
    }  
});

/*$.validate({ 	
    form : '#frm-fooditem',    
    onError : function() {      
    },
    onSuccess : function() {           
      alert('d2');
      form_submit('frm-fooditem');
      return false;
    }  
});*/

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-delivery',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-delivery');
      return false;
    }  
});

function busy(e)
{
    if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        
    
    if (e) {
    	$("body").before("<div class=\"preloader\"></div>");
    } else $(".preloader").remove();
    
}

function scroll(id){
   if( $('#'+id).is(':visible') ) {	
      $('html,body').animate({scrollTop: $("#"+id).offset().top-100},'slow');
   }
}

function scroll_class(id){
   if( $('.'+id).is(':visible') ) {	
      $('html,body').animate({scrollTop: $("."+id).offset().top-100},'slow');
   }
}

function toogle(id , bool , caption)
{
    $('#'+id).attr("disabled", bool );
    $("#"+id).val(caption);
}

function rm_notices()
{
	$(".uk-alert").remove();		    
}

function form_submit(formid)
{		
	rm_notices();
	if ( formid ) {
		var form_id=formid;
	} else {
		var form_id=$("form").attr("id");    
	}    	
    
	var btn=$('#'+form_id+' input[type="submit"]');   	
    var btn_cap=btn.val();
    btn.attr("disabled", true );
    btn.val(js_lang.processing);
    busy(true);    
    
    var params=$("#"+form_id).serialize();	
    
    var action=$('#'+form_id).find("#action").val(); 
    if ( action == "placeOrder" ){
    	params+="&cc_id="+$(".cc_id:checked").val();
    }    	
    
    if ( action == "merchantPayment" ){
    	params+="&cc_id="+$(".cc_id:checked").val();
    }    	

    if ( action=="addReviews"){
    	if ( $("#initial_review_rating").val()=="" ){
    		uk_msg(js_lang.trans_1);    		
    		busy(false);  
        	btn.attr("disabled", false );
        	btn.val(btn_cap);        	
    		return;
    	}    	
    }   
                   
    switch(action)
    {    	
    	case "clientLogin":
    	case "clientLoginModal":
    	case "merchantSignUp":    	
    	case "merchantSignUp2":
    	if ( $("#RecaptchaField1").exists() ){
    		var googleResponse = $("#RecaptchaField1").find(".g-recaptcha-response").val();    		
    		if (!googleResponse) {
    			busy(false); 
	    		uk_msg(js_lang.trans_52);                 
	            btn.attr("disabled", false );
		        btn.val(btn_cap);
		        busy(false);        	    	
		    	return;
    		}
    	}
    	break;
    	    	
    	case "clientRegistrationModal":
    	case "clientRegistration":
    	if ( $("#RecaptchaField2").exists() ){
    		var googleResponse = $("#RecaptchaField2").find(".g-recaptcha-response").val();    		
    		dump(googleResponse);
    		if (!googleResponse) {
    			busy(false); 
	    		uk_msg(js_lang.trans_52);                 
	            btn.attr("disabled", false );
		        btn.val(btn_cap);
		        busy(false);        	    	
		    	return;
    		}
    	}
    	break;
    }
        
    dump(action);
    
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){ 
        	busy(false);  
        	btn.attr("disabled", false );
        	btn.val(btn_cap);        	
        	//scroll(form_id);
        	if (data.code==1){
        		
        		/*$("#"+form_id).before("<p class=\"uk-alert uk-alert-success\">"+data.msg+"</p>");
        		setTimeout(function () {
                   $(".uk-alert-success").fadeOut();
                }, 4000);  */
        		
        		uk_msg_sucess(data.msg);
        		        		
        		if ( action=="forgotPassword"){
        			if ( $(".checkout-page").exists()){
        				$(".section-forgotpass").hide();
        				$("#username-email").val('');
        				return;
        			}        		
        		}
        		
        		if ( action=="clientRegistration"){
        			if ( $("#verification").exists() ){
        				window.location.href=sites_url+"/store/verification/checkout/true/id/"+data.details;
        				return;
        			}  
        		}
        		
        		if ( $("#redirect").length>=1 ){
        			if (typeof data.details === "undefined" || data.details==null ) {        			    		
        			    window.location.replace(  $("#redirect").val() );
        			} else {
        				window.location.replace(  $("#redirect").val()+"/id/"+data.details );
        			}
        		}
        		
        		if( action=="verifyMobileCode"){        			
        			if ( $("#redirect").exists() ){
        				window.location.href=$("#redirect").val();
        				return;
        			}          			
        			window.location.href=sites_url+"/store";
        			return;
        		}
        		        		
        		if ( action=="clientLogin"){
        			if ( $("#single_page").exists() ){
	        			window.location.href=sites_url+"/store";
	        			return;
        			}
        		}
        		
        		if ( action=="subscribeNewsletter"){
        			$("#subscriber_email").val("");
        		}        	
        		
        		if ( action=="bookATable" || action=="bankDepositVerification" ){
        			clear_elements('frm-book');
        		}
        		if (action=="bankDepositVerification"){
        			clear_elements('forms');
        		}        	
        		
        		if ( action=="addToCart" ){
        			close_fb();
        			load_item_cart();
        		}        		        	
        		
        		if ( action=="addCreditCard"){
        			load_cc_list();
        		}
        		
        		if ( action=="addCreditCardMerchant"){
        			load_cc_list_merchant();
        		}
        		
        		if ( action == "placeOrder" ){
        			console.debug(data.details.payment_type);
        			if ( data.details.payment_type =="pyp"){
        				window.location.replace(sites_url+"/store/paypalInit/id/"+data.details.order_id);
        			} else if(data.details.payment_type =="stp")	{
        				window.location.replace(sites_url+"/store/stripeInit/id/"+data.details.order_id);
        			} else if(data.details.payment_type =="mcd")	{
        				window.location.replace(sites_url+"/store/mercadoInit/id/"+data.details.order_id);	
        			} else if(data.details.payment_type =="pyl")	{
        				window.location.replace(sites_url+"/store/paylineinit/id/"+data.details.order_id);		
        			} else if(data.details.payment_type =="ide")	{
        				window.location.replace(sites_url+"/store/sisowinit/id/"+data.details.order_id);			
        			} else if(data.details.payment_type =="payu")	{
        				window.location.replace(sites_url+"/store/payuinit/id/"+data.details.order_id);			
        			} else if(data.details.payment_type =="pys")	{
        				window.location.replace(sites_url+"/store/payserainit/id/"+data.details.order_id);					
        			} else if(data.details.payment_type =="bcy")	{
        				window.location.replace(sites_url+"/store/bcyinit/id/"+data.details.order_id);					
        			} else if(data.details.payment_type =="epy")	{
        				window.location.replace(sites_url+"/store/epyinit/id/"+data.details.order_id);						
        			} else if(data.details.payment_type =="atz")	{
        				window.location.replace(sites_url+"/store/atzinit/id/"+data.details.order_id);							
        			} else{
        				window.location.replace(sites_url+"/store/receipt/id/"+data.details.order_id);
        			}        			
        		}        		        
        		
        		if ( action=="clientLoginModal"){        			
        			load_top_menu();
        			close_fb();
        		}
        		
        		if ( action=="clientRegistrationModal"){
        			        			
        			if ( $("#verification").exists() ){
        				window.location.href=sites_url+"/store/verification/id/"+data.details;
        				return;
        			}        			
        			
        			if ( $("#single_page").exists() ){
        				window.location.href=sites_url;
        			} else {        		
	        			load_top_menu();
	        			close_fb();
        		    }
        		}
        		        		        		
        		if ( action=="addReviews"){
        			uk_msg(data.msg);
        			load_reviews();
        			load_ratings();
        			$(".write-review").addClass("active");
        			$(".review-content-wrap").slideToggle("fast");
        		}
        		
        		if ( action=="updateReview"){        			
        			load_reviews();
        			close_fb();
        		}
        		
        		if ( action=="paypalCheckoutPayment"){
        			window.location.replace(sites_url+"/store/receipt/id/"+data.details.order_id);
        		}
        		
        		/*merchant signup*/
        		
        		if (action=="merchantSignUp"){
        			window.location.replace(sites_url+"/store/merchantSignup/Do/step3/token/"+data.details);
        			/*if ( $("#merchant_email_verification").val()=="yes" ){
        				window.location.replace(sites_url+"/store/merchantSignup/Do/thankyou/token/"+data.details);
        			} else {
        				window.location.replace(sites_url+"/store/merchantSignup/Do/step3/token/"+data.details);
        			}*/
        		}
        		
        		if ( action=="merchantSignUp2"){
        			window.location.replace(sites_url+"/store/merchantSignup/Do/thankyou2/token/"+data.details);
        		}
        		        		
        		if ( action=="merchantPayment"){        			
        			if ($("#renew").val()==1 && $("#payment_opt:checked").val() =="ccr" ){
        				window.location.replace(sites_url+"/store/renewSuccesful");
        			} else {
	        			/*if ( $("#merchant_email_verification").val()=="yes" ){   	        				
window.location.replace(sites_url+"/store/merchantSignup/Do/thankyou2/token/"+$("#token").val());
	        			} else {        			           			          			   	        			   */
	        			   switch ( $("#payment_opt:checked").val() )
	        			   {
	        			   	   case "pyp":
	        			   	   case "stp":
	        			   	   case "mcd":
	        			   	   case "pyl":
	        			   	   case "ide":
	        			   	   case "payu":
	        			   	   case "obd":
	        			   	   case "pys":
	        			   	   case "bcy":
	        			   	   case "epy":
	        			   	   case "atz":
	        			   	   //window.location.replace(data.details);
	        			   	   window.location.href=data.details;
	        			   	   break;
	        			   	   default:
	        			   	   //window.location.replace(sites_url+"/store/merchantSignup/Do/step4/token/"+data.details);
	        			   	   window.location.href=sites_url+"/store/merchantSignup/Do/step4/token/"+data.details;
	        			   	   break;
	        			   }
	        			//}        			
        			}	
        		}
        		
        		if ( action=="merchantPaymentPaypal"){
        		    if ($("#renew").val()==1){
        				window.location.replace(sites_url+"/store/renewSuccesful");
        		    } else {		
	        			if ( $("#merchant_email_verification").val()=="yes" ){
	        				window.location.replace(sites_url+"/store/merchantSignup/Do/thankyou2/token/"+data.details);
	        			} else {
	        				window.location.replace(sites_url+"/store/merchantSignup/Do/step4/token/"+data.details);
	        			}
        		    }
        		}
        		
        		if (action=="activationMerchant"){
        			window.location.replace(sites_url+"/store/merchantSignup/Do/thankyou1/token/"+data.details);
        		}
        		
        		if ( action=="changePassword"){
        			$(".change-pass-btn").attr("disabled",true);
        			$(".change-pass-btn").css({"opacity":"0.5"});
        		}        		
        		        	
        		if ( action=="merchantResumeSignup"){
        			window.location.replace(data.details);
        			return;
        		}
        		
        		if (action=="setAddress"){
        			//console.debug("setAddress");
        			window.location.reload();
        			return;
        		}
        			
        	} else {
        		//$("#"+form_id).before("<p class=\"uk-alert uk-alert-danger\">"+data.msg+"</p>");
        		/*$("#"+form_id).before("<p class=\"uk-alert uk-alert-danger\">"+data.msg+"</p>");
        		setTimeout(function () {
                   $(".uk-alert-danger").fadeOut();
                }, 4000);        	*/
        		
        		uk_msg(data.msg);
        	}
        	//scroll_class('uk-alert');
        }, 
        error: function(){	        	
        	btn.attr("disabled", false );
        	btn.val(btn_cap);
        	busy(false);        	
        	//$("#"+form_id).before("<p class=\"uk-alert uk-alert-danger\">ERROR:</p>");
        	/*$("#"+form_id).before("<p class=\"uk-alert uk-alert-danger\">ERROR</p>");
    		setTimeout(function () {
               $(".uk-alert-danger").fadeOut();
            }, 4000);        	
        	scroll_class('uk-alert');*/
        	uk_msg("ERROR");
        }		
    });
}

var otable;

jQuery(document).ready(function() {	
	 if( $('#table_list').is(':visible') ) {    	
    	table();    	
    } 
        
    if( $('.chosen').is(':visible') ) {     
     //$(".chosen").chosen(); 
       $(".chosen").chosen({
       	  allow_single_deselect:true,
       	  no_results_text: js_lang.trans_33,
          placeholder_text_single: js_lang.trans_32, 
          placeholder_text_multiple: js_lang.trans_32
       });     
    } 
    
    if( $('.icheck').is(':visible') ) { 
	     $('.icheck').iCheck({
	       checkboxClass: 'icheckbox_minimal',
	       radioClass: 'iradio_flat'
	     });
    }
    
    if( $('#bar-rating').is(':visible') ) {	
    	
	    $('#bar-rating').barrating('show', {
	    	initialRating: $("#initial_rating").val(),
	        showValues:false,
	        showSelectedRating:true,        
	        onSelect:function(value, text) {	            
	        	if( $('#review_content').is(':visible') ) {		        	
	        		$("#initial_review_rating").val(value)
	        	} else {	        		
	        		$("#initial_review_rating").val(value)
	        		//add_rating(value, $("#merchant_id").val() );
	        	}		        	           
	        }
	    });
	            
	    var x=1;
        $( ".br-widget a" ).each(function( index ) {
        	$(this).addClass("level-"+x++);
        });
    }
           
    $( document ).on( "click", ".menu-category", function() {
    	var i=$(this).find("i");
    	if (i.hasClass("fa-chevron-up")){
    		i.removeClass("fa-chevron-up");
    		i.addClass("fa-chevron-down");
    	} else {
    		i.addClass("fa-chevron-up");
    		i.removeClass("fa-chevron-down");
    	}
        var parent=$(this).parent();
    	var ul=parent.find("ul");    	
    	ul.slideToggle("fast");
    });
    
    $( document ).on( "click", ".menu-item", function() {
    	
    	if ( !$(".order-list-wrap").exists()){
    		return;
    	} 
    	
    	/** check if the item is available*/
    	if ( $(this).hasClass("item_not_available")){
    		uk_msg(js_lang.trans_51);
    		return;
    	}    
    	    	
    	if ( $("#merchant_close_store").exists() ){	
    		if  (  $("#merchant_close_store").val()=="yes"){
    			var close_msg=$("#merchant_close_msg").val();
		        uk_msg(close_msg);
		        return;
    		}
    	}	
    	
    	/** auto add item if food is single */
    	var id=$(this).attr("rel");
    	
    	var single=$(this).data("single");    	
    	if ( single==2){    
    		if ( $("#website_disbaled_auto_cart").val()!="yes"){		
	    		single_food_item_add(id, $(this).data("price"), $(this).data("size") );	
	    		return;
    		}
    	}
    	    		       
    	var params="action=viewFoodItem&currentController=store&item_id="+id+"&tbl=viewFoodItem";
    	open_fancy_box(params);
    });
    
    $( document ).on( "click", ".edit_item", function() {
    	var id=$(this).attr("rel");
    	var row=$(this).data("row");
    	var params="action=viewFoodItem&currentController=store&item_id="+id+"&tbl=viewFoodItem&row="+row;
    	open_fancy_box(params);
    });
    
    $(".view-item-wrap").niceScroll( {
    	cursorcolor:"#E57871",
    	cursorwidth:"7px",
    	autohidemode:"leave"  
    });
    
   $(".view-item-wrap").mouseover(function() {
      $(".view-item-wrap").getNiceScroll().resize();
   });
   
   //$('.numeric_only').keyup(function () {     
   $( document ).on( "keyup", ".numeric_only", function() {
      this.value = this.value.replace(/[^0-9\.]/g,'');
   });	 
      
   $( document ).on( "click", ".special-instruction", function() {
   	  $(".notes-wrap").slideToggle("fast");
   });
      
   $( document ).on( "click", ".qty-plus", function() {
   	  qty=parseFloat( $("#qty").val())+1;
    	////console.debug(qty);
    	if (isNaN(qty)){
    	    qty=1;
    	}
    	$("#qty").val( qty );
   });
   
   $( document ).on( "click", ".qty-minus", function() {
   	    var qty=$("#qty").val()-1;
    	if (qty<=0){
    		qty=1;
    	}
    	$("#qty").val(  qty );
   });
   
   $( document ).on( "click", ".qty-addon-plus", function() {
   	   var parent=$(this).parent();   	   
   	   var child=parent.find(".addon_qty");   	   
   	   var qty=parseFloat(child.val())+1;   	   
   	   if (isNaN(qty)){
    	    qty=1;
       }
       child.val( qty );
   });
   
   $( document ).on( "click", ".qty-addon-minus", function() {
   	   var parent=$(this).parent();   	   
   	   var child=parent.find(".addon_qty");   	      
   	   var qty=parseFloat(child.val())-1;
       if (qty<=0){
    		qty=1;
       }
       child.val( qty );
   });
      
   $( document ).on( "click", ".sub_item_name", function() {   	   
   	   var addon_type=$(this).attr("rel");
   	   
   	   if ( addon_type=="multiple"){
	   	   var parent=$(this).parent().parent();	   	    
		   var child=parent.find(".quantity-wrap-small");
	   	   if ($(this).is(":checked")){   	   	  
		   	    child.show();
	   	   } else {
	   	   	    child.hide();
	   	   }   
   	   }
   	      	   
   	   if ( addon_type=="custom"){   	   	 
   	   	  var data_id=$(this).data("id");
   	   	  var data_option=$(this).data("option");   	   	  
   	   	  var total_multi_selected=$(".sub_item_name_"+data_id+":checked").length;   	   	  
   	   	  if ( data_option == ""){
   	   	  	  return;
   	   	  }   	   
   	   	  if ( total_multi_selected > data_option  ){   	   	  	  
	       	  uk_msg(js_lang.trans_2+" "+" "+ data_option +" "+js_lang.trans_3);  	  
   	   	  	  $(this).attr("checked",false);
   	   	  }   	   
   	   }   
   });
   
   
   $( document ).on( "click", ".add_to_cart", function() {   	   
   	   //var price=$("#price:checked").length;
   	   var price=$(".item_price:checked").length;   	   
   	   console.debug(price);
   	   if (price<=0){
   	   	   if ( !$("#two_flavors").exists()) {
   	   	      uk_msg(js_lang.trans_29);
   	   	      $("#price_wrap").focus();
   	   	      return;
   	   	   }
   	   }  
   	   
   	   /** two flavors */
   	   if ( $("#two_flavors").exists()) {   	   	  
   	   	  var sub_item=$(".sub_item:checked").length;   	   	  
   	   	  dump(sub_item);
   	   	  if ( sub_item<2){
   	   	  	  uk_msg(js_lang.trans_46);   	   	      
   	   	      return;
   	   	  }   	   
   	   }
   	   
   	   /** check if addon is required */
   	   if ( $(".require_addon").exists()){
   	   	   
   	   	   $(".req_addon_msg").remove();
   	   	   
   	   	   var addon_err='';
   	   	   $('.require_addon').each(function () {
   	   	   	   if ( $(this).val()==2 ) {
	   	   	   	   var required_addon_id=$(this).data("id");
	   	   	   	   var required_addon_name=$(this).data("name");
	   	   	   	   dump(required_addon_id);
	   	   	   	   var required_addon_selected=$(".sub_item_name_"+required_addon_id+":checked").length; 
	   	   	   	   dump(required_addon_selected);
	   	   	   	   if ( required_addon_selected <=0){
	   	   	   	   	   addon_err=js_lang.trans_47+" - "+required_addon_name +"<br/>";
	   	   	   	   	   $(".require_addon_"+required_addon_id).after(
	   	   	   	   	   "<p class=\"req_addon_msg uk-text-danger uk-text-small\">"+addon_err+"</p>").fadeIn("slow");
	   	   	   	   }   	   	   	   
   	   	   	   }
   	   	   });
   	   	   
   	   	   if ( addon_err!=""){
   	   	   	   //uk_msg(addon_err);
   	   	   	   return;
   	   	   }   	   
   	   }   	   
   	   
   	   form_submit('frm-fooditem');
   });
   
   if( $('.item-order-wrap').is(':visible') ) {	
      load_item_cart();
   }
      
   $( document ).on( "click", ".delete_item", function() { 
   	   var ans=confirm(js_lang.trans_4); 
   	   if (ans){      
   	       var row=$(this).data("row");   	   
   	       delete_item(row);
   	   }
   });
      
   $( document ).on( "click", ".edit_item", function() { 
   	   var row=$(this).data("row");
   	   ////console.debug(row);
   });
      
   /************** CHECK OUT ***************/
   
   $( document ).on( "click", ".checkout", function() {    	  
   	   	
   	 var subtotal= parseFloat($("#subtotal_order").val());
   	 
   	 /*check if delivery/pickup date is empty*/   	 
   	
   	 if ( $("#delivery_type").val()=="delivery"){
	   	  if ( $("#minimum_order").length>=1){   	  	  
	   	  	  var minimum= parseFloat($("#minimum_order").val());	   	  	  
	   	  	  if (isNaN(subtotal)){
	   	  	  	  subtotal=0;
	   	  	  }   	     	  	  
	   	  	  if (isNaN(minimum)){
	   	  	  	  minimum=0;
	   	  	  }   	     	  	  
	   	  	  if ( minimum>subtotal){   	  	  	
	              uk_msg(js_lang.trans_5+" "+ $("#minimum_order_pretty").val());
	   	  	  	  return;
	   	  	  }      	  	  
	   	  	  
	          if ( $("#merchant_maximum_order").exists() ){
	    	      console.debug("max");
	    	      var merchant_maximum_order= parseFloat($("#merchant_maximum_order").val());    	      
	    	      if ( subtotal>merchant_maximum_order){
	    	      	  uk_msg(js_lang.trans_31+" "+ $("#merchant_maximum_order_pretty").val());
	   	  	  	      return;
	    	      }              	      
	          }   	  	     	  	  
	   	  }  
   	 }
   	 
   	 if ( $("#delivery_type").val()=="pickup"){   	     
   	 	   	 	 
   	     if ( $("#merchant_minimum_order_pickup").exists()){
   	     	  var minimum= parseFloat($("#merchant_minimum_order_pickup").val());	   	  	  
	   	  	  if (isNaN(subtotal)){
	   	  	  	  subtotal=0;
	   	  	  }   	     	  	  
	   	  	  if (isNaN(minimum)){
	   	  	  	  minimum=0;
	   	  	  }   	     	  	  
	   	  	  if ( minimum>subtotal){   	  	  	
	              uk_msg(js_lang.trans_5+" "+ $("#merchant_minimum_order_pickup_pretty").val());
	   	  	  	  return;
	   	  	  }      	  	  
   	     }   	 
   	     
   	     if ( $("#merchant_maximum_order_pickup").exists() ){
   	     	  var merchant_maximum_order= parseFloat($("#merchant_maximum_order_pickup").val());    	      
    	      if ( subtotal>merchant_maximum_order){
    	      	  uk_msg(js_lang.trans_31+" "+ $("#merchant_maximum_order_pickup_pretty").val());
   	  	  	      return;
    	      }              	      
   	     }   	 
   	 }   
   	  
   	  if ( $("#delivery_type").val()=="delivery" ){   	  	  
	   	  if ( $("#is_ok_delivered").val()==2){	   	  	 
	   	  	 uk_msg(js_lang.trans_15+" "+$("#merchant_delivery_miles").val() + " "+$("#unit_distance").val());   	  
	   	  	 return;
	   	  }   
	   	  if ( $("#delivery_date").val()==""){
   	  	 	uk_msg(js_lang.trans_43);  	  	 
   	  	 	$("#delivery_date").focus();
   	  	 	return;
   	  	 }   	  
   	  	 
   	  	 if ( $("#merchant_required_delivery_time").exists()){
   	  	 	if ( $("#merchant_required_delivery_time").val()=="yes"){   	  	 		   	  	 	
   	  	 		if ( $("#delivery_time").val()==""){
   	  	 			var delivery_asap=$("#delivery_asap:checked").val();
   	  	 			dump(delivery_asap);
   	  	 			if ( delivery_asap!=1){
			   	  	 	uk_msg(js_lang.trans_44);  	  	 
			   	  	 	$("#delivery_time").focus();
			   	  	 	return;
   	  	 			}
		   	  	 }   	  
   	  	 	}   	  	 
   	  	 }   	  
   	  	 
   	  } else {   	  	 
   	  	 if ( $("#delivery_date").val()==""){
   	  	 	uk_msg(js_lang.trans_42);  	  	 
   	  	 	$("#delivery_date").focus();
   	  	 	return;
   	  	 }   	  
   	  	 if ( $("#delivery_time").val()==""){
   	  	 	uk_msg(js_lang.trans_41);  	  	 
   	  	 	$("#delivery_time").focus();
   	  	 	return;
   	  	 }   	  
   	  }   	  
   	  
   	  var params="delivery_type="+$("#delivery_type").val()+"&delivery_date="+$("#delivery_date").val();
   	  params+="&delivery_time="+$("#delivery_time").val();
   	  params+="&delivery_asap="+$("#delivery_asap:checked").val();
   	  params+="&merchant_id="+$("#merchant_id").val();
   	  
   	    busy(true);
	    $.ajax({    
	    type: "POST",
	    url: ajax_url,
	    data: "action=setDeliveryOptions&currentController=store&tbl=setDeliveryOptions&"+params,
	    dataType: 'json',       
	    success: function(data){ 
	    	busy(false);      		
	    	if (data.code==1) {
	    	   //window.location.replace(sites_url+"/store/checkout/");
	    	window.location.href=sites_url+"/store/checkout/";
	    	} else {
	    		uk_msg(data.msg);
	    	}	    
	    }, 
	    error: function(){	        	    	
	    	busy(false); 
	    }		
	    });   	     	  
   });
   
   $('#delivery_asap').on('ifChecked', function(event){
      $("#delivery_time").val('');
   });
   
   $('.payment_opt').on('ifChecked', function(event){   	   
       $(".credit_card_wrap").slideToggle("fast");
   });
   
   $('.payment_opt').on('ifUnchecked', function(event){
       $(".credit_card_wrap").slideToggle("fast");
   });
   
   
   $( document ).on( "click", ".cc-add", function() {    	  
   	   $(".cc-add-wrap").slideToggle("fast");
   });
   
   if( $('.payment-option-page').is(':visible') ) {	
       load_cc_list();
   }
   
   if( $('.merchant-payment-option').is(':visible') ) {	
       load_cc_list_merchant();
   }
   
   /************ PLACE ORDER ***************/
   
   $( document ).on( "click", ".place_order", function() {    	  
   	   	   	   
   	   if ( $("#minimum_order").length>=1){   	  	  
   	  	  var minimum= parseFloat($("#minimum_order").val());
   	  	  var subtotal= parseFloat($("#subtotal_order").val());
   	  	  if (isNaN(subtotal)){
   	  	  	  subtotal=0;
   	  	  }   	     	  	  
   	  	  if (isNaN(minimum)){
   	  	  	  minimum=0;
   	  	  }   	     	  	  
   	  	  if ( minimum>subtotal){   	  	  	
              uk_msg(js_lang.trans_5+" "+ $("#minimum_order_pretty").val());
   	  	  	  return;
   	  	  }      	  	  
   	   }  
   	      	    	   
   	     
   	   /** if checkout is guest type */
   	   if ( $("#is_guest_checkout").exists() ){   	   	   
   	   	   if ( $("#map_address_toogle").exists()){   	   	   	   	   	  
   	          if ( $("#map_address_toogle").val()==2 ){
   	          	  $("#street").removeAttr("data-validation");
   	          	  $("#city").removeAttr("data-validation");
   	          	  $("#state").removeAttr("data-validation");
   	          }
   	   	   }  
   	   }   
   	
   	   var payment_type=$(".payment_option:checked").val();
   	   
   	   if ( $(".payment_option:checked").length<=0 ){
   	   	   uk_msg(js_lang.trans_6);
   	   	   return;
   	   }
   	   
   	   
   	   if ( $("#contact_phone").exists() ){
	   	   var p = $("#contact_phone").val();   	      	      	   
	   	   if ( p.length <=6){
	   	   	   uk_msg(js_lang.trans_7);
	   	   	   $("#contact_phone").focus();
	   	   	   return;
	   	   }   
   	   }
   	      	      	   
   	   if ( payment_type =="ccr"){
   	   	   ////console.debug( $(".cc_id:checked").val() );   	   
   	   	   if ( $(".cc_id:checked").length<=0 ){
   	   	   	   uk_msg(js_lang.trans_8);   	   	  
   	   	   	   return;
   	   	   }   	   
   	   }        	   
   	      	   
   	   if ( payment_type=="pyr"){
   	   	   var selected_payment_provider_name=$("#payment_provider_name:checked").length;   	   	   
   	   	   if ( selected_payment_provider_name<=0){
   	   	   	   uk_msg(js_lang.trans_40);   	   	   
   	   	   	   return;
   	   	   }   	   
   	   }   
   	   
   	   /** check if client move the marker */
   	   if ( $("#map_address_toogle").exists()){   	   	
   	      if ( $("#map_address_toogle").val()==2 ){
	   	   	   if ( $("#map_address_lat").val()==""){
	   	   	   	  uk_msg(js_lang.trans_48);    	   	   
	   	   	   	  return;
	   	   	   }   	   
   	      }
   	   }   
   	   
   	   /*check google recaptcha*/
   	   if ( $("#RecaptchaField1").exists() ){
   	   	   var google_resp = $("#RecaptchaField1").find(".g-recaptcha-response").val();    		
   	   	   dump(google_resp);
   	   	   if (!google_resp) {
   	   	   	   uk_msg(js_lang.trans_52); 
   	   	   	   return;
   	   	   }
   	   }    
   	      	   
   	   /*check if merchant sms oder verification is required*/
   	   if ( $("#order_sms_code").exists()){
   	   	    if ( $("#order_sms_code").val()=="" ){
   	   	    	uk_msg(js_lang.trans_53);    	  
   	   	    	$("#order_sms_code").focus();  
   	   	    	return;
   	   	    } else {
   	   	    	$("#client_order_sms_code").val( $("#order_sms_code").val() );
   	   	    	$("#client_order_session").val( $(".send-order-sms-code").data("session"));
   	   	    } 	      	   
   	   }   
   	   
   	   $("#frm-delivery").submit();   	   
   	   //form_submit('frm-delivery');   	   
   });
   
   /*ANIMATE*/
   $('.animated').appear(function(){             
      var element = $(this);
      var animation = element.data('animation');
      var animationDelay = element.data('delay');      
      if (animationDelay) {
        setTimeout(function(){
          element.addClass( animation + " visible" );
          element.removeClass('hiding');
          if (element.hasClass('counter')) {          
          }
        }, animationDelay);
      }else {
        element.addClass( animation + " visible" );
        element.removeClass('hiding');
        if (element.hasClass('counter')) {         
        }
      }    
    },{accY: -1});
      
    /*LOOP THRU STEPS*/
    $( ".order-steps li" ).each(function( index ) {
    	var current=$(this);    	
    	var link= current.find("a");
    	if ( current.hasClass("active") ){  
    	} else {    	    
    	    link.attr("href","javascript:;")    	   
    	    link.addClass("inactive");
    	}    
    });
        
    
   $('.filter_by').on('ifChecked', function(event){
       research_merchant();       
   });
   
   $('.filter_by').on('ifUnchecked', function(event){
       research_merchant();   
   });     
      
   $('.filter_by_radio').on('ifChecked', function(event){   	
       ////console.debug('d2');
       $(".filter_minimum_clear").show();   
       research_merchant();   
   });     
      
   $( document ).on( "click", ".button_filter", function() {
   	   $(".frm_search_name_clear").show();
   	   research_merchant(); 
   });
   
   $( document ).on( "click", ".sort_filter", function() {
   	   var sort_filter=$(this).data("id");   	   
   	   var data_text=$(this).data("text");   	      	   
   	   $("#sort_filter").val(sort_filter);
   	   //$(".sortby_text").text(sort_filter);
   	   $(".sortby_text").text(data_text);
   	   research_merchant(sort_filter);
   });
    
   
   $( document ).on( "click", ".login-link", function() {    	     	  
   	  $(".section1").hide();
   	  $(".section2").fadeIn();
   });	
   
   
   $( document ).on( "click", ".signup-link", function() {    	     	     	  
   	  $(".section3").fadeIn();
   	  $(".section2").hide();
   	  $(".section1").hide();
   });	
      
   $( document ).on( "click", ".back-link", function() {    	  
   	  $(".section1").fadeIn();
   	  $(".section2").hide();
   	  $(".section3").hide();
   	  $(".section-forgotpass").hide();
   });	
   
   $( document ).on( "click", ".fc-close", function() {    	  
      close_fb();
   });	     
      
   $( document ).on( "click", ".remove-rating", function() {    	  
       var params="action=removeRating&currentController=store&merchant_id="+$("#merchant_id").val();
		busy(true);
	    $.ajax({    
	    type: "POST",
	    url: ajax_url,
	    data: params,
	    dataType: 'json',       
	    success: function(data){ 
	    	busy(false);      
	    	if (data.code==1){	  	    		
	    		load_ratings();
	    		$('#bar-rating').barrating('clear'); 
	    		$(".br-widget a").removeClass("br-selected");
	    		$(".rating_handle").addClass("hide");
	    	} else {
	    		uk_msg(data.msg);
	    	}	    
	    }, 
	    error: function(){	        	    	
	    	busy(false); 
	    }		
	    });
   });	     
   
  
   $( document ).on( "click", ".write-review", function() {    	  
   	   if ( $(this).hasClass("active") ){
   	   	   ////console.debug('d2');
   	   	   $(".review-content-wrap").slideToggle("fast");
   	   } else {   
   	       $(".review-content-wrap").slideToggle("fast");
   	   }
   });	
   
   if( $('.menu-wrapper').is(':visible') ) {	     	   
   	   load_reviews();
   }
   
   /* if ( $(".menu-wrapper").is(":visible") ) {      	   	    
   	    var locations;
    	locations=[[$("#map_title").val(),$("#merchant_map_latitude").val(),$("#merchant_map_longtitude").val(),16]];    	
    	initializeMarker(locations);      
   } */

   $( document ).on( "click", ".map-li", function() {    	     	  
   	  var locations;
   	  if ( $("#google_map_wrap").text()=="" ){
   	  	 
   	  	 if (typeof $("#merchant_map_latitude").val() === "undefined" || $("#merchant_map_latitude").val()==null || $("#merchant_map_latitude").val()=="" ) {  
   	  	 	$("#google_map_wrap").html("<p class=\"uk-text-muted\">"+js_lang.trans_9+"</p>");
   	  	 	return;
   	  	 }	
         locations=[[$("#map_title").val(),$("#merchant_map_latitude").val(),$("#merchant_map_longtitude").val(),16]];
         initializeMarker(locations);      
   	  }
   });
         
   $( document ).on( "click", ".uk-tab-responsive a", function() {    	     	  
   	  var locations;
   	  if ( $("#google_map_wrap").text()=="" ){
   	  	 
   	  	 if (typeof $("#merchant_map_latitude").val() === "undefined" || $("#merchant_map_latitude").val()==null || $("#merchant_map_latitude").val()=="" ) {  
   	  	 	$("#google_map_wrap").html("<p class=\"uk-text-muted\">"+js_lang.trans_9+"</p>");
   	  	 	return;
   	  	 }	
         locations=[[$("#map_title").val(),$("#merchant_map_latitude").val(),$("#merchant_map_longtitude").val(),16]];
         initializeMarker(locations);      
   	  }
   });
    
   $( document ).on( "click", ".top_sigin", function() {    	  
   	   var params="action=loginModal&tbl=loginModal&do-action=sigin&currentController=store";
       open_fancy_box(params);
   });
      
   $( document ).on( "click", ".top_signup", function() {    	  
   	   var params="action=loginModal&tbl=loginModal&do-action=sigin&currentController=store";
       open_fancy_box(params);
   });
   
   $( document ).on( "click", ".edit-review", function() {    	  
   	   var id=$(this).data("id");
   	   var params="action=editReview&currentController=store&tbl=editReview&id="+id+"&web_session_id="+$("#web_session_id").val();
       open_fancy_box(params);
   });	

     
   $( document ).on( "click", ".delete-review", function() {    	  
   	   var id=$(this).data("id");
   	   var q=confirm(js_lang.trans_10);
   	   if (q){
   	   	   delete_review(id);
   	   }   
   });

     
   $( document ).on( "click", ".print_element", function() {    	       	 	
       //$('.receipt-main-wrap').print();
       $('.receipt-main-wrap').printElement();
   });	
   
   $( document ).on( "click", ".view-receipt", function() {    	       	 	
   	   var params="action=viewReceipt&currentController=store&tbl=viewReceipt&id="+ $(this).data("id");
       open_fancy_box(params);
   });	
   
   $( document ).on( "click", ".add-to-cart", function() {    	       	 	
      var id=$(this).data("id");
      var a=confirm(js_lang.trans_11);
      if (a){
      	 add_to_order(id);      
      }
   });	
      
   $( document ).on( "click", ".search-box-wrap	 a", function() {    	       	 	
   	  if ( $(this).hasClass("filter_minimum_clear") ){
   	  	 return;
   	  }   
   	  if ( $(this).hasClass("frm_search_name_clear") ){
   	  	 return;
   	  }   
   	  var i=$(this).find(".fa");
   	  if ( i.hasClass("fa-caret-up") ){
   	  	  i.removeClass("fa-caret-up");
   	  	  i.addClass("fa-caret-down");
   	  } else {
   	  	  i.removeClass("fa-caret-down");
   	  	  i.addClass("fa-caret-up");
   	  }   
     	 
   	  var parent=$(this).parent();   	  
   	  var child=parent.find(".uk-list");
   	  child.slideToggle("fast");
   });	
   
   $( document ).on( "click", ".next_step_free_payment", function() {    	       	 	
   	  next_step_free_payment($(this).data("token"));
   });	   
   
   $( document ).on( "click", ".row_del", function() {
        var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	row_delete( $(this).attr("rev"),$("#tbl").val(), $(this));        	        
        }
    });
    
    $( document ).on( "click", ".filter_minimum_clear", function() {    	
    	$(".filter_minimum").attr("checked",false);
    	$('.filter_minimum').iCheck('update'); 
    	$(this).hide();
    	research_merchant();  
    });            
       
    $( document ).on( "click", ".frm_search_name_clear", function() {    	
    	$(".filter_name").val('');
    	$(this).hide();
    	research_merchant();  
    });
            
    /*contact map*/
    if ( $("#map_latitude").length>=1 ){
    	if (typeof $("#map_latitude").val() === "undefined" || $("#map_longitude").val()==null  ) {  
   	  	 	$("#google_map_wrap").html("<p class=\"uk-text-muted\">Map not available</p>");
   	  	 	return;
   	  	 }	
         locations=[[$("#map_title").val(),$("#map_latitude").val(),$("#map_longitude").val(),16]];         
         initializeMarker(locations);      
    }
    
   
    $( document ).on( "click", ".fb-link-login", function() {    	
    	////console.debug('d2');
    	checkLoginState();
    });
    
    $( document ).on( "click", ".forgot-pass-link", function() {    	    
    	  $(".section-forgotpass").fadeIn();
    	  $(".section3").hide();
   	      $(".section2").hide();
   	      $(".section1").hide();
    });
    
    next_bg();
        
    $( document ).on( "click", ".resume-app-link", function() {    	    
    	$(".frm-resume-signup").slideToggle("fast");
    });	
        
    $( document ).on( "click", ".resend-activation-code", function() {    	    
    	resend_activation_code( $("#token").val() );
    });	
    
    
    if ( $("#merchant_header").length>=1 ){
    	var merchant_header=upload_url+"/"+$("#merchant_header").val();
    	//console.debug(merchant_header);
    	$('#menu-with-bg').css('background-image', 'url(' + merchant_header + ')');
    }
    
    $( document ).on( "click", ".apply_voucher", function() {
    	if ( $("#voucher_code").val()!="" ){
    		apply_voucher();
    	} else {
    		uk_msg(js_lang.trans_22);
    	}        
    });    
    
    
    if( $('#restaurant-mini-list').is(':visible') ) {	    	
    	var t=$("#tab-left-content li:first-child").find(".links");      	
    	//geocode_address(t.data("id"));
    	var lat=t.data("lat");
    	var lng=t.data("long");    	
    	if (isNaN(lat) && isNaN(lng)){
    	    geocode_address(t.data("id"));
    	} else {
    		//locations=[['test',lat,lng,18]];    		
    		locations=[['',lat,lng,18]];
            geocode_address2(locations);      
    	}    
    }	
        
    $( document ).on( "click", ".view-map", function() {     	
    	var lat=$(this).data("lat");
    	var lng=$(this).data("long");    	
    	if (isNaN(lat) && isNaN(lng)){
    	    geocode_address($(this).data("id"));
    	} else {
    		var merchantname=$(this).data("merchantname");   	
    		locations=[[merchantname,lat,lng,18]];
            geocode_address2(locations);      
    	}    
    });	            
            
}); /*END DOCU*/

function table()
{			
	var action=$("#action").val();	
	dump(action);
	var params=$("#frm_table_list").serialize();
			
	var sInfo=js_lang.trans_12;	
	
	if ( action=="ClientCCList"){	
		params+="&action=ClientCCList";
		sInfo=js_lang.trans_13;
		sEmptyTable=js_lang.tablet_1;
		
	} else if( action=="addressBook"){
		params+="&action=addressBook";
		sEmptyTable=js_lang.tablet_1;
		
	} else {		
		sInfo=js_lang.trans_13;
		sEmptyTable=js_lang.tablet_1;
	}
		
	if ( action=="searchArea"){
		 epp_table = $('#table_list').dataTable({
		       "bProcessing": true, 
		       "bServerSide": true,	    
		       "bFilter":false,
		       "bLengthChange":false,
		       "sAjaxSource": ajax_url+"?"+params,	       		       
		       "bDeferRender": true,
		       "iDisplayLength": 10,
		       "pagingType": "full_numbers",
		       "oLanguage":{
		       	 "sInfo": sInfo,
		       	 "sInfoEmpty":  js_lang.tablet_3,
		       	 "sEmptyTable": sEmptyTable,
		       	 "sProcessing": "<p>"+js_lang.tablet_7+" <i class=\"fa fa-spinner fa-spin\"></i></p>",
		       	 "oPaginate": {
				        "sFirst":    js_lang.tablet_10,
				        "sLast":     js_lang.tablet_11,
				        "sNext":     js_lang.tablet_12,
				        "sPrevious": js_lang.tablet_13
				  }
		       },
		       "fnInitComplete": function (oSettings, json) {  		       	  		       	  		       	  
	              if ( oSettings._iRecordsTotal<=0){
	              	  $(".ops_notification").show();
	              }
	           },
	           "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {	      	           	
	            	global_plot_marker[iDisplayIndex]=[ucwords(aData[7]),parseFloat(aData[5]),parseFloat(aData[6]),iDisplayIndex,aData[8],aData[9],aData[10] ];
	            		            	
	           },
	           "fnDrawCallback": function( oSettings ) {
	           	  plotMerchantLocationNew(global_plot_marker);
	           }
	    });		
	} else {
	    epp_table = $('#table_list').dataTable({
		       "bProcessing": true, 
		       "bServerSide": false,	    
		       "bFilter":false,
		       "bLengthChange":false,
		       "sAjaxSource": ajax_url+"?"+params,	       
		       //"aaSorting": [[ 0, "desc" ]],
		       "oLanguage":{
		       	 "sInfo": sInfo,
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
		       "fnInitComplete": function (oSettings, json) {                                                   
	              if ( json.iTotalRecords <=0){
	              	 $(".ops_notification").show();
	              } else {
	              	 if ( action=="searchArea"){                     	 	              	 	
	              	 	plotMerchantLocation(json);
	              	 }
	              }
	           }
	    });		
	}
}

function table_reload()
{
	epp_table.fnReloadAjax(); 
}

function table_reload_with_params(new_params)
{
	var params=$("#frm_table_list").serialize();
	epp_table.fnReloadAjax(  ajax_url+"?"+params+new_params ); 
}

function research_merchant()
{
	var filter_delivery_type='';
	var filter_cuisine='';
	var filter_promo='';
	var filter_minimum='';
	var filter_name='';
	
	$('input:checkbox.filter_delivery_type').each(function () {
        var sThisVal = (this.checked ? $(this).val() : "");
        if ( sThisVal !=""){
            filter_delivery_type+=sThisVal+",";
        }
    });
    
    $('input:checkbox.filter_cuisine').each(function () {
        var sThisVal = (this.checked ? $(this).val() : "");
        if ( sThisVal !=""){
            filter_cuisine+=sThisVal+",";
        }
    });
        
    $('input:checkbox.filter_promo').each(function () {
        var sThisVal = (this.checked ? $(this).val() : "");
        if ( sThisVal !=""){
            filter_promo+=sThisVal+",";
        }
    });
    
    
    filter_minimum=$(".filter_minimum:checked").val(); 
    filter_name=$("#filter_name").val();
    if (typeof filter_name === "undefined" || filter_name==null ) {  
    	filter_name='';
    }	
        
           
    var new_params='';
    new_params+="&filter_delivery_type="+filter_delivery_type;
    new_params+="&filter_cuisine="+filter_cuisine;
    new_params+="&filter_promo="+filter_promo;
    new_params+="&filter_minimum="+filter_minimum;
    new_params+="&filter_name="+filter_name;
    
    sort_filter=$("#sort_filter").val();
    if (typeof sort_filter === "undefined" || sort_filter==null ) {  
    } else {    	
    	new_params+="&sort_filter="+sort_filter;
    }	
    
    table_reload_with_params(new_params);
}

function open_fancy_box(params)
  {  	  	  	  	
	var URL=ajax_url+"/?"+params;
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
	closeEffect :'elastic'	
	});   
}

function open_fancy_box2(params)
  {  	  	  	  	
	var URL=ajax_url+"/?"+params;
	$.fancybox({        
	maxWidth:800,
	closeBtn : false,          
	autoSize : true,
	padding :0,
	margin :2,
	modal:true,
	type : 'ajax',
	href : URL,
	openEffect :'elastic',
	closeEffect :'elastic'	
	});   
}

function close_fb()
{
	$.fancybox.close(); 
}

function uk_msg(msg)
{
	$.UIkit.notify({
	   message :msg,
	   timeout :2500,
	   //status:"warning"
	});	    	   	   	  
}

function uk_msg_sucess(msg)
{
	$.UIkit.notify({
	   message :msg,
	   timeout :2500,	   
	   status:'success'
	});	    	   	   	  
}

function load_item_cart()
{	
	var params="action=loadItemCart&currentController=store&merchant_id="+$("#merchant_id").val();
	params+="&delivery_type="+$("#delivery_type").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){
    		$(".item-order-wrap").html(data.details.html);
    		$(".checkout").attr("disabled",false);    		
    		$(".checkout").css({ 'pointer-events' : 'auto' });
    		$(".checkout").addClass("uk-button-success");
    		$(".voucher_wrap").show();
    		clearCartButton(1);
    	} else {
    		$(".item-order-wrap").html('<div class="center">'+data.msg+'</div>');
    		$(".checkout").attr("disabled",true);
    		$(".checkout").css({ 'pointer-events' : 'none' });
    		$(".checkout").removeClass("uk-button-success");
    		$(".voucher_wrap").hide();
    		clearCartButton(2);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function delete_item(row)
{
	var params="action=deleteItem&row="+row;
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){    		
    		load_item_cart();
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function load_cc_list()
{
	var htm='';
	var params="action=loadCreditCardList&currentController=store";
	params+="&is_guest_checkout="+$("#is_guest_checkout").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){    		    		    		    	
    		$.each(data.details, function( index, val ) {
    			$(".uk-list-cc li").remove(); 
    			htm+='<li>';
	              htm+='<div class="uk-grid">';
	                htm+='<div class="uk-width-1-2">'+val.credit_card_number+'</div>';
	                htm+='<div class="uk-width-1-2">&nbsp;<input type="radio" name="cc_id" class="cc_id" value="'+val.cc_id+'"></div>';
	              htm+='</div>';
	            htm+='</li>';
    		});
    		$(".uk-list-cc").append(htm);
    		$(".cc-add-wrap").hide();
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function load_cc_list_merchant()
{
	var htm='';
	var params="action=loadCreditCardListMerchant&currentController=store";
	params+="&merchant_id="+$("#merchant_id").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){    		    		    		    	
    		$.each(data.details, function( index, val ) {
    			$(".uk-list-cc li").remove(); 
    			htm+='<li>';
	              htm+='<div class="uk-grid">';
	                htm+='<div class="uk-width-1-2">'+val.credit_card_number+'</div>';
	                htm+='<div class="uk-width-1-2">&nbsp;<input type="radio" name="cc_id" class="cc_id" value="'+val.mt_id+'"></div>';
	              htm+='</div>';
	            htm+='</li>';
    		});
    		$(".uk-list-cc").append(htm);
    		$(".cc-add-wrap").hide();
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function add_rating(value,merchant_id)
{
    var params="action=addRating&currentController=store&value="+value+"&merchant_id="+merchant_id;
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      		
    	if (data.code==1){    
    		$(".rating_handle").removeClass("hide");
    		load_ratings();
            close_fb();
    	} else if( data.code==3) {
    		uk_msg(data.msg);
    	} else {
    		$('#bar-rating').barrating('clear'); 		
    		var params="action=loginModal&tbl=loginModal&currentController=store&do-action=rating&rating="+value;
    	    open_fancy_box(params);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function load_ratings()
{
	var params="action=loadRatings&currentController=store&merchant_id="+$("#merchant_id").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){    		
    		$(".votes_counter").html(data.details.votes+" Votes");
    		$(".rate-wrap h6").html(data.details.ratings);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}


function load_top_menu()
{
	var params="action=loadTopMenu&currentController=store";
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){
    		$(".section-to-menu-user").append(data.details);
    		$(".top_signup").remove();
    		$(".top_sigin").remove();
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}


function load_reviews()
{
	var params="action=loadReviews&currentController=store&merchant_id="+$("#merchant_id").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){
    	   $(".reviews-wrap").html(data.details);
    	} else {
    	   $(".reviews-wrap").html("<div class=\"uk-text-muted\">"+data.msg+"</div>");
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function delete_review(id)
{
	var params="action=deleteReview&currentController=store&id="+id+"&web_session_id="+$("#web_session_id").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){    	   
            load_reviews();            
            close_fb();
    	} else {
    	   uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function add_to_order(order_id)
{
	var params="action=addToOrder&currentController=store&order_id="+order_id;
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){       			              
    		window.location.replace(sites_url+"/store/menu/merchant/"+data.details.restaurant_slug);
    	} else {
    	   uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function next_step_free_payment(token)
{
	var params="action=merchantFreePayment&currentController=store&token="+token;
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if ( $("#merchant_email_verification").val()=="yes" ){
		   window.location.replace(sites_url+"/store/merchantSignup/Do/thankyou2/token/"+token);
		} else {
		   window.location.replace(sites_url+"/store/merchantSignup/Do/step4/token/"+token);
		}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });	
}

function row_delete(id,tbl,object)
{		
	var form_id=$("form").attr("id");
	rm_notices();	
	busy(true);
	var params="action=rowDelete&tbl="+tbl+"&row_id="+id+"&whereid="+$("#whereid").val();	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);
        	if (data.code==1){       
        		$("#"+form_id).before("<div class=\"uk-alert uk-alert-success\">"+data.msg+"</div>");         		
        		tr=object.closest("tr");
                tr.fadeOut("slow");
        	} else {      
        		$("#"+form_id).before("<div class=\"uk-alert uk-alert-danger\">"+data.msg+"</div>");
        	}        	        	
        }, 
        error: function(){	        	        	
        	busy(false);
        	$("#"+form_id).before("<div class=\"uk-alert uk-alert-danger\">"+js_lang.trans_14+"</div>");
        }		
    });
}

/*=============================================================
START GOOGLE MAP MARKER
=============================================================*/
function initializeMarker(locations){	

    window.map = new google.maps.Map(document.getElementById('google_map_wrap'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false
        //styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 0.5 }]}]
    });
        
    var infowindow = new google.maps.InfoWindow();

    var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
    	    	                
        if ( $("#map_marker").exists() ){
        	var image=upload_url+"/"+$("#map_marker").val(); 
        	marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            map: map ,
	            icon:image
	        });
        } else {
	        marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            map: map           
	        });
        }

        bounds.extend(marker.position);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    map.fitBounds(bounds);

    var listener = google.maps.event.addListener(map, "idle", function () {
        map.setZoom(16);
        google.maps.event.removeListener(listener);
    });
}

function initializeMarkerNew(locations,divname,zoom_value){		
		
	window.map = new google.maps.Map(document.getElementById(divname), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false
    });
        
    var contentString=[];
       
    for (i = 0; i < locations.length; i++) {
        //dump(locations);
    	contentString[i]='<div class="marker-info-wrap">';
    	contentString[i]+=locations[i][6];
    	contentString[i]+="<div>"+js_lang.trans_35+" : "+locations[i][0]+"</div>";
    	contentString[i]+="<div>"+js_lang.trans_36+" : "+locations[i][4]+"</div>";
    	contentString[i]+="<a href=\""+sites_url+"/store/menu/merchant/"+locations[i][5]+"\" >"+js_lang.trans_37+"</a>";
    	contentString[i]+="</div>";
    	
    	
    	var infowindow = new google.maps.InfoWindow({
           content: contentString[i]
        });

        
        var bounds = new google.maps.LatLngBounds();
    
        var image=upload_url+"/"+$("#map_marker").val();        
                
        if ( $("#map_marker").exists() ){
	        marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            map: map,
	            icon: image     
	        });
        } else {
        	marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            map: map	           
	        });
        }

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {   
            	infowindow.setContent(contentString[i]);             
                infowindow.open(map,marker);
            }
        })(marker, i));

        
        bounds.extend(marker.position);
        
    }
    

    dump("zoom=>"+zoom_value);
    if (typeof zoom_value === "undefined" || zoom_value==null ) { 
    	zoom_value=10;
    }
    dump("zoom=>"+zoom_value);
    
    map.fitBounds(bounds);

    var listener = google.maps.event.addListener(map, "idle", function () {
        map.setZoom(zoom_value);
        
        /** focus on the map location */
        if ( focus_lat!=""){        	
        	dump("focus_lat->"+focus_lat);
            dump("focus_lng->"+focus_lng);
            var position = new google.maps.LatLng(focus_lat,focus_lng);
            map.setCenter(position);   
        }                
        
        google.maps.event.removeListener(listener);
    });
	
}
/*=============================================================
END GOOGLE MAP MARKER
=============================================================*/

function fb_register(object)
{
	var fb_params='';
	$.each( object, function( key, value ) {      
      fb_params+=key+"="+value+"&";
    });
		
    rm_notices();	
	busy(true);
	
	var params="action=FBRegister&currentController=store&"+fb_params;
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',        
        success: function(data){         	
        	busy(false);
        	if (data.code==1){
        		load_top_menu();
        		if( $('.checkout-page').is(':visible') ) {	
        			window.location.reload();
        		}	
        		            		    	
        		if ( $("#single_page").exists() ){
        			window.location.href=sites_url+"/store";
        		}		
        		
        		close_fb();
        	} else {
        		uk_msg(data.msg);
        	}
        }, 
        error: function(){	             	
        	busy(false);
        }		
   });
}


/*CHANGE BACKGROUND*/
//var handle_bg=setInterval(next_bg, 5000);
var backgrounds = [
sites_url+"/assets/images/b-1.jpg",
sites_url+"/assets/images/b-2.jpg",
sites_url+"/assets/images/b-3.jpg",
sites_url+"/assets/images/b-4.jpg",
sites_url+"/assets/images/b-5.jpg",
sites_url+"/assets/images/b-6.jpg",
];
      
function next_bg()
{	
	var numLow = 1;
    var numHigh = 6;    
    var adjustedHigh = (parseFloat(numHigh) - parseFloat(numLow)) + 1;    
    var numRand = Math.floor(Math.random()*adjustedHigh) + parseFloat(numLow);        
    var bg_img=backgrounds[numRand];
    ////console.debug(bg_img);
    if (typeof bg_img === "undefined" || bg_img==null ) { 
    } else {	
       $(".banner-wrap").addClass("bg-fadein");
       $(".banner-wrap").css('background', 'url(' + bg_img + ')');       
    }
    
}

function resend_activation_code(token)
{
	$(".resend-activation-code").css({ 'pointer-events' : 'none' });
	var params="action=resendActivationCode&currentController=store&token="+token;
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	$(".resend-activation-code").css({ 'pointer-events' : 'auto' });
    	busy(false);      	
    	if (data.code==1){    		    	
    	}
    	uk_msg(data.msg);
    }, 
    error: function(){	        	    	
    	$(".resend-activation-code").css({ 'pointer-events' : 'auto' });
    	busy(false); 
    }		
    });
}

function apply_voucher()
{
	var action="applyVoucher";
	if ( $(".apply_voucher").text()==js_lang.trans_23 ){
		action="removeVoucher";
	}
	
	if ( action=="removeVoucher"){
		var a=confirm("Are you sure?");
		if (!a){
			return;
		}
	}
	
	var code = $("#voucher_code").val();
	var params="action="+action+"&currentController=store&voucher_code="+code+"&merchant_id="+$("#merchant_id").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){     	
    	busy(false);      	
    	if (data.code==1){    	
    		//console.debug(action);
    		load_item_cart();
    		if ( action=="removeVoucher"){
    			$(".apply_voucher").text(js_lang.trans_24);    		
    			$("#voucher_code").show();
    		} else {    			
    		    $("#voucher_code").hide();
    			$(".apply_voucher").text(js_lang.trans_23);
    		}    		
    	} else {
           uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	    	
    	busy(false); 
    }		
    });	
}

/***************************************
GET CURRENT LOCATION 
***************************************/

jQuery(document).ready(function() {	
	
	if ( $("#google_auto_address").val()=="yes" ){		
	} else {
		if ( $("#google_default_country").val()=="yes" ){			
			$("#s").geocomplete({
			  country: $("#admin_country_set").val()			
		   });	
		} else {			
			$("#s").geocomplete();	
		}
	}
	$("#origin").geocomplete({
		country: $("#admin_country_set").val()
	});
	
	function success_geo(position) {	
		if ( $("#s").val()!=""){
		 	 return;
		 }	
		/*console.debug(position.coords.latitude);
		console.debug(position.coords.longitude);		*/
		//getAddress(position.coords.latitude,position.coords.longitude);
		var lat=position.coords.latitude;
		var lng=position.coords.longitude;
		var latlng = new google.maps.LatLng(lat, lng);		
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'latLng': latlng}, function(results, status) {						
			if (status == google.maps.GeocoderStatus.OK) {
				 //dump(results[0]);
				 if (results[1]) {				
				 	if (typeof results[0].formatted_address === "undefined" || results[0].formatted_address==null ) { 
				 		$("#s").val(results[1].formatted_address);
				 		$(".st").val(results[1].formatted_address);
				 	} else {	     				 						 		
				        $("#s").val(results[0].formatted_address);
				        $(".st").val(results[0].formatted_address);
				 	} 
				 } else {
				 	 uk_msg(js_lang.trans_27);
				 }
			} else {
				uk_msg(js_lang.trans_28 + " " + status);
			}
		});
	}
	
	//if( $('#s').is(':visible') ) {		
	//if ( $("#s").length>0 ){				
	//if ( $(".home-page").length>0 ){				
	if ( $(".banner-wrap").length>0 ){	
		if (navigator.geolocation) {
		   if ( $("#disabled_share_location").val()==""){		   	  
	          navigator.geolocation.getCurrentPosition(success_geo);
		   } 
	    } else {
	        error('Geo Location is not supported');
	    }
	}
	
    function getAddress(lat,lng)
    {
    	var params="action=geoReverse&currentController=store&lat="+lat+"&lng="+lng;
		busy(true);
	    $.ajax({    
	    type: "POST",
	    url: ajax_url,
	    data: params,
	    dataType: 'json',       
	    success: function(data){     	
	    	busy(false);      	
	    	if (data.code==1){    	
	    		if ( $("#s").val()==""){
	    		   $("#s").val(data.details);
	    		}
	    	} else {	           
	    		uk_msg(data.msg);
	    	}
	    }, 
	    error: function(){	        	    	    	
	    	busy(false); 
	    }		
	    });	
    }
        
    $( document ).on( "click", ".get_direction_btn", function() {    	
    	if ( $("#origin").val() ==""){
    		uk_msg(js_lang.trans_25);
    	} else {
    		$(".direction_output").css({"display":"none"});	
    		display_direction();
    	}    	
    });
    
    
    if( $('.rating-wrapper').is(':visible') ) {	
    	if ( $("#from_address ").val()=="" ){
    		if ( $("#customer_ask_address").val()!=2){
    		   var params="action=enterAddress&currentController=store&tbl=enterAddress";
    	       open_fancy_box2(params);    		    	
    		}	
    	}
    }	    
    
    $( document ).on( "click", ".change-address", function() {    	
    	var params="action=enterAddress&currentController=store&tbl=enterAddress";
    	open_fancy_box(params);    		
    });	    
    
           
    if ( $("#sisowbank").exists() ){
    	$("#sisowbank").addClass("uk-form-width-large");
    }
    
    
    if ( $("#payuForm").exists() ){
    	if ( $("#hash").val()=="" ){    		    	
    	} else {
    		$(".uk-button").attr("disabled",true);    		
    		$(".uk-button").css({ 'pointer-events' : 'none' });
    		$("#payuForm").submit();
    	}
    }
    if ( $("#payu_status").exists() ){
    	$(".uk-button").attr("disabled",true);    		
        $(".uk-button").css({ 'pointer-events' : 'none' });
    }    
    
    jQuery.fn.exists = function(){return this.length>0;}
    
    if ( $("#is_merchant_open").exists() ){
    	if ( $("#is_merchant_open").val()==2 ){
    		var merchant_close_msg=$("#merchant_close_msg").val();    		
    		if (typeof merchant_close_msg === "undefined" || merchant_close_msg==null ) { 
    			merchant_close_msg(js_lang.trans_30);
    		} else {
    			//uk_msg(merchant_close_msg);
    			$.UIkit.notify({
				   message :merchant_close_msg,
				   timeout :5500,
				   status:"warning"
				});	    	   	   	  
    		}	
    	}
    }
    
    $( document ).on( "click", "a.share", function(ev) {
		social_popup( $(this).attr("rel") );
		ev.preventDefault();
		return;
	});
		
	$( document ).on( "change", "#delivery_type", function() {    	
		var delivery_type=$(this).val();				
		if ( delivery_type=="pickup"){
			$(".delivery-asap").hide();			
			$("#delivery_time").attr("placeholder",js_lang.trans_38);
			$(".delivery-fee-wrap").hide();	
		} else {
			$(".delivery-asap").show();			
			$("#delivery_time").attr("placeholder",js_lang.trans_39);
			$(".delivery-fee-wrap").show();	
		}
    	load_item_cart();
    });	    
    
    if( jQuery('#photo').is(':visible') ) {    	
       createUploader('photo','photo');
    }     
    
    if ( $("#search-tabs").exists()){
    	$( "#search-tabs" ).show();
        $( "#search-tabs" ).tabs();
    }
        
    if ( $(".fancybox").exists() ){
       $(".fancybox").fancybox();
    }
    
    $('.payment_cod').on('ifChecked', function(event){   	   
       $(".change_wrap").slideToggle("fast");
   });
   
   $('.payment_cod').on('ifUnchecked', function(event){
       $(".change_wrap").slideToggle("fast");
   });
   
   if ( $(".is_holiday").exists()){
   	   uk_msg( $(".is_holiday").val() );
   }  
    
   if ( $(".merchant-gallery-wrap").exists()){
   	    $('.merchant-gallery-wrap').magnificPopup({
          delegate: 'a',
          type: 'image',
          closeOnContentClick: false,
          closeBtnInside: false,
          mainClass: 'mfp-with-zoom mfp-img-mobile',
          image: {
            verticalFit: true,
            titleSrc: function(item) {
              return '';
            }
          },
          gallery: {
            enabled: true
          },
          zoom: {
            enabled: true,
            duration: 300, // don't foget to change the duration also in CSS
            opener: function(element) {
              return element.find('img');
            }
          }
          
        });
   }
   
   $('.payment_pyr').on('ifChecked', function(event){   	   
       $(".payment-provider-wrap").slideToggle("fast");
   });
   
   $('.payment_pyr').on('ifUnchecked', function(event){
       $(".payment-provider-wrap").slideToggle("fast");
   });
           
    isImageLoaded('featured-restaurant-list');
    
    $( document ).on( "click", ".goto-category", function(ev) {
		var class_name= $(this).data("id");		
		dump(class_name);
		scroll_class(class_name);
	});
   
}); /*END DOCU*/

function featuredListing()
{
	if ( $(".bxslider").exists() ){  
		$(".bxslider").show();
		$(".feature-merchant-loader").remove();
    	$('.bxslider').bxSlider({
          minSlides: 8,
		  maxSlides: 9,
		  slideWidth: 150,
		  slideMargin: 10,
		  pager:false,
		  onSliderLoad:function(currentIndex){		  	
		  }		  
    	});
    }
}
function featuredListingMobile()
{	
	if ( $(".bxslider2").exists() ){  		
    	$('.bxslider2').bxSlider({
          minSlides: 2,
		  maxSlides: 3,
		  slideWidth: 150,
		  slideMargin: 10,
		  pager:false,
		  onSliderLoad:function(currentIndex){		  	
		  }		  
    	});
    }
    if ( $(".bxslider3").exists() ){  		
    	$('.bxslider3').bxSlider({
          minSlides: 1,
		  maxSlides: 1,
		  slideWidth: 150,
		  slideMargin: 10,
		  pager:false,
		  onSliderLoad:function(currentIndex){		  	
		  }		  
    	});
    }
}

function isImageLoaded(classname)
{
	  $('.'+classname).imagesLoaded()
	  .always( function( instance ,image ) {
	     //console.log('all images loaded');
	     featuredListing();
	     featuredListingMobile();
	  })
	  .done( function( instance ) {
	     //console.log('all images successfully loaded');	     	     
	  })
	  .fail( function() {
	    //console.log('all images loaded, at least one is broken');	     
	  })
	  .progress( function( instance, image ) {	  	 	  	 
	     var result = image.isLoaded ? 'loaded' : 'broken';	     
	     var $item = $( image.img ).parent();	     
	     //dump($item);
	     $item.removeClass('isloading');	     
	     //console.log( 'image is ' + result + ' for ' + image.img.src );	     
    });	
}

function social_popup(url)
{	
	w=700;
    h=436;
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
       	
	window.open(url, 'sharer','toolbar=0,status=0,width=700,height=436'+', top='+top+', left='+left);	  
}

/*$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    animationLoop: false,
    itemWidth: 100,
    itemMargin: 5
  });
});*/

function display_direction()
{
	
	$(".direction_output").css({"min-height":"300px","display":"block"});	
	 var directionsService = new google.maps.DirectionsService();
     var directionsDisplay = new google.maps.DirectionsRenderer();

     var map = new google.maps.Map(document.getElementById('google_map_wrap'), {
       zoom:7,
       mapTypeId: google.maps.MapTypeId.ROADMAP,
       scrollwheel: false
     });

     directionsDisplay.setMap(map);
     directionsDisplay.setPanel(document.getElementById('direction_output'));

     switch( $("#travel_mode").val() )
     {
     	case "DRIVING":
     	var request = {
	       origin: $("#origin").val(), 
	       destination: $("#resto_address").val(),
	       travelMode: google.maps.DirectionsTravelMode.DRIVING
	     };
     	break;
     	
     	case "WALKING":
     	var request = {
	       origin: $("#origin").val(), 
	       destination: $("#resto_address").val(),
	       travelMode: google.maps.DirectionsTravelMode.WALKING
         };
     	break;
     	
     	case "BICYCLING":
     	var request = {
	       origin: $("#origin").val(), 
	       destination: $("#resto_address").val(),
	       travelMode: google.maps.DirectionsTravelMode.BICYCLING
	     };	     
     	break;
     	
     	case "TRANSIT":
     	var request = {
	       origin: $("#origin").val(), 
	       destination: $("#resto_address").val(),
	       travelMode: google.maps.DirectionsTravelMode.TRANSIT
	     };
     	break;
     }          
     
     directionsService.route(request, function(response, status) {       
       if (status == google.maps.DirectionsStatus.OK) {
         directionsDisplay.setDirections(response);
       } else {
       	  uk_msg(js_lang.trans_26+" "+status)
       	  $(".direction_output").css({"display":"none"});	
       }
     });
}

function geocode_address(address)
{
 
	var geocoder;
    var map;
    geocoder = new google.maps.Geocoder(); 
    var mapOptions = {
	   scrollwheel: false,	
	   zoom: 18,
	   //center: latlng,
	   mapTypeId: google.maps.MapTypeId.ROADMAP
	 }
	 map = new google.maps.Map(document.getElementById('maps_side'), mapOptions);
	 
	 geocoder.geocode( { 'address': address}, function(results, status) {	 	   	 
	 	  //console.debug(results[0].geometry.location.k);
	 	  //console.debug(results[0].geometry.location.B);
	 	  if (status == google.maps.GeocoderStatus.OK) {
		      map.setCenter(results[0].geometry.location);
		      
		      if ( $("#map_marker").exists() ){
		      	var image=upload_url+"/"+$("#map_marker").val();    
		      	 var marker = new google.maps.Marker({    	
				     map: map,
				     position: results[0].geometry.location,
				     icon: image    
				  });
		      } else {
				  var marker = new google.maps.Marker({    	
				     map: map,
				     position: results[0].geometry.location
				  });
		      }
	 	  } else {
	 	  	 uk_msg(status);
	 	  }
	 });
}

function geocode_address2(locations)
{	
     window.map = new google.maps.Map(document.getElementById('maps_side'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false       
    });
        
    var infowindow = new google.maps.InfoWindow();

    var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
    	
    	if ( $("#map_marker").exists() ){
	      	var image=upload_url+"/"+$("#map_marker").val();    
	      	marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            map: map ,
	            icon: image         
	        });
	      } else {
	        marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            map: map            
	        });
		 }

        bounds.extend(marker.position);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    map.fitBounds(bounds);

    var listener = google.maps.event.addListener(map, "idle", function () {
        map.setZoom(18);
        google.maps.event.removeListener(listener);
    });
}

function dump(data)
{
	console.debug(data);
}

function photo(data)
{
	var img='';	
	$(".preview").show();
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\"uk-thumbnail uk-thumbnail-mini\" >";
	img+="<input type=\"hidden\" name=\"photo\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rm_preview();\">"+js_lang.removeFeatureImage+"</a></p>";
	$(".image_preview").html(img);

	$("#branch_code").removeAttr("data-validation");
	$("#date_of_deposit").removeAttr("data-validation");
	$("#time_of_deposit").removeAttr("data-validation");
	$("#amount").removeAttr("data-validation");
}

function rm_preview()
{
	$(".image_preview").html('');
	
	$("#branch_code").attr("data-validation",'required');
	$("#date_of_deposit").attr("data-validation",'required');
	$("#time_of_deposit").attr("data-validation",'required');
	$("#amount").attr("data-validation",'required');
}

function plotMerchantLocation(json)
{		
	if ( $(".search-map-wrap").exists() ){
		var s=[];
		var x=0;
		var y=1;		
	    $.each(json.aaData, function( index, val ) {        
	        s[x]=[ucwords(val[7]),parseFloat(val[5]),parseFloat(val[6]),y,val[8],val[9],val[10] ]
	        x++;
	        y++;
	    });        	    	    
	    $(".search-map-wrap").show();	    
	    initializeMarkerNew(s,'search-map-wrap');
	}
}

function ucwords(str) {  
  return (str + '')
    .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
      return $1.toUpperCase();
  });
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-subscribe',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-subscribe');
      return true;
    }  
});

jQuery(document).ready(function() {	
	
	if ( $("#hide_foodprice").exists() ){		
		if ( $("#hide_foodprice").val()=="yes"){
			$(".food-price-wrap").hide();			
			$(".hide-food-price").hide();				
			//$(".right-menu-content").hide();
			//$("#menu-wrap .grid-1").css({"width":"100%"});
		}
	}	
			
	$( document ).on( "click", ".close-receipt", function() {
		close_fb();
	});
			
	if ( $(".mobile_inputs").exists()){
		if ( $("#mobile_country_code").exists()){
			$(".mobile_inputs").intlTelInput({      
		        autoPlaceholder: false,
		        defaultCountry: $("#mobile_country_code").val(),    
		        autoHideDialCode:true,    
		        nationalMode:false,
		        autoFormat:false,
		        utilsScript: sites_url+"/assets/vendor/intel/lib/libphonenumber/build/utils.js"
		     });
		} else {
			 $(".mobile_inputs").intlTelInput({      
		        autoPlaceholder: false,		        
		        autoHideDialCode:true,    
		        nationalMode:false,
		        autoFormat:false,
		        utilsScript: sites_url+"/assets/vendor/intel/lib/libphonenumber/build/utils.js"
		     });
		}
	}
	

	$( document ).on( "click", ".ui-timepicker a", function() {		
		if ( $("#frm-book").exists()){
			$("#booking_time").removeAttr("style");
			var parent=$("#booking_time").parent();		
			parent.find(".form-error").remove();
		}
	});
	
	if ( $("#delivery_type").exists()){
		var delivery_type=$("#delivery_type").val();		
		if ( delivery_type=="pickup"){
			$(".delivery-asap").hide();			
			$("#delivery_time").attr("placeholder",js_lang.trans_38);
			$(".delivery-fee-wrap").hide();	
		} else {
			$(".delivery-asap").show();			
			$("#delivery_time").attr("placeholder",js_lang.trans_39);
			$(".delivery-fee-wrap").show();	
		}
    	//load_item_cart();
	}
	
	if ( $("#merchant_close_store").exists() ){				
		if  (  $("#merchant_close_store").val()=="yes"){
		   var close_msg=$("#merchant_close_msg").val();
		   uk_msg(close_msg);
		   $(".order-list-wrap").after('<p class="uk-alert uk-alert-warning">'+close_msg+'</p>');
		   $(".book-table-button").attr("disabled",true);
		   $(".checkout").hide();
		}
	}
	
}); /*END doc*/

function plotMerchantLocationNew(s)
{	
	if ( $(".search-map-wrap").exists() ){				
		if ( s.length >=1){
			$(".search-map-wrap").show();		
	        initializeMarkerNew(s,'search-map-wrap');
		}
	}
}

function single_food_item_add(item_id,price,size)
{
	/*dump("item_id:"+item_id);
	dump("price:"+price);
	dump("size:"+size);*/
	
	var params='';
	params="merchant_id="+$("#merchant_id").val();
	params+="&item_id="+item_id	
	if ( size==""){
		params+="&price="+price;
	} else {
	    params+="&price="+price+"|"+size;
	}
	params+="&qty=1";		
	params+="&discount=";
	params+="&notes=";
	params+="&row=";	
		
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: "action=addToCart&currentController=store&"+params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      		
    	if (data.code==1) {    	   
    		uk_msg_sucess(data.msg);
    		load_item_cart();
    	} else {
    		uk_msg(data.msg);
    	}	    
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });   	     	  
}


jQuery(document).ready(function() {	
	$( document ).on( "click", ".tips", function() {		
		
		$("#tip_value").val('');
		
		var type=$(this).data("type");
		
		$(".tips").removeClass("active");
		$(this).addClass("active");
			
		if ( type=="tip"){		
		    var tip=$(this).data("tip");			
		    		    						
			var tip_percentage = tip*100;
			$(".tip_percentage").html(tip_percentage+"%");
			
			var cart_subtotal=$("#subtotal_order2").val()
			var tip_raw = tip*cart_subtotal;
			dump(tip_raw.toFixed(2));		
			
			display_tip(tip_percentage,tip_raw.toFixed(2));
		} else {
			//$(".tip_percentage").html(0+"%");			
			$(".tip_percentage").html(0+"%");
			display_tip(0,0);			
			$(".added_tip_wrap").remove();
		}
	});
			
	$( "#tip_value" ).keyup(function() {
		 var tip_raw=parseFloat($(this).val());		 
		 if (isNaN(tip_raw)){
		 	tip_raw=0;
		 }
		 dump(tip_raw);
		 $(".tips").removeClass("active");
		 
		 var cart_subtotal=parseFloat($("#subtotal_order2").val());
		 dump(cart_subtotal);
		 
		 var reverse_percentage= (tip_raw/cart_subtotal)*100;
		 dump(reverse_percentage);
		 $(".tip_percentage").html(reverse_percentage.toFixed()+"%");
		 		 
		 display_tip(reverse_percentage.toFixed(),tip_raw.toFixed(2));
	});
	
	
	if ( $("#default_tip").exists() ){
		var default_tip=$("#default_tip").val();		
		$( ".tips" ).each(function( index ) {
			var tip=$(this).data("tip");
			dump(tip);
			if ( default_tip == tip){				
				
				$(".tips").removeClass("active");
		        $(this).addClass("active");
				
				var tip_percentage = tip*100;
				$(".tip_percentage").html(tip_percentage+"%");
								
				setTimeout(function() { 
					var cart_subtotal=$("#subtotal_order2").val();
					var tip_raw = tip*cart_subtotal;
					dump("->"+tip_raw.toFixed(2));							
					display_tip(tip_percentage,tip_raw.toFixed(2));				
				},2100);
				
				return false;
			}
		});
	}
		
}); /*end docu*/

function display_tip(tip_percentage,amount)
{
	$("#cart_tip_percentage").val(tip_percentage);
	$("#cart_tip_value").val(amount);
	
	var admin_currency_set=$("#admin_currency_set").val();
	
	var sub_total= $("#subtotal_order2").val();
	var subtotal_extra_charge= parseFloat($("#subtotal_extra_charge").val()) + 0;	
	
	if (isNaN(subtotal_extra_charge)){
		subtotal_extra_charge=0;
	}
	if (isNaN(amount)){
		amount=0;
	}
	if (isNaN(subtotal_extra_charge)){
		subtotal_extra_charge=0;
	}	
	
	dump(sub_total);
	dump(amount);
	dump(subtotal_extra_charge);
	
	var cart_total= parseFloat(sub_total) + parseFloat(amount) + parseFloat(subtotal_extra_charge);
	dump(cart_total);
	cart_total=cart_total.toFixed(2);
	
	var cart_total_display='';
	var amount_display='';
	
	if ( $("#admin_currency_position").val()=="right" ){
		cart_total_display=cart_total+" "+admin_currency_set;
		amount_display = amount+" "+admin_currency_set;
	} else {
		cart_total_display=admin_currency_set+" "+cart_total
		amount_display = admin_currency_set+" "+amount;
	}
		
	
	var html='';
	html+='<div class="added_tip_wrap">';
	html+='<div class="a">'+js_lang.trans_45+' ('+tip_percentage+'%)</div>';
	html+='<div class="manage">';
	  html+='<div class="b">'+amount_display+'</div>';
	html+='</div>';
	html+='<div>';
	
	$(".added_tip_wrap").remove();
	$(".cart_total_wrap").before(html);
		
	$(".cart_total").html(cart_total_display);
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms-normal',    
    onError : function() {      
    },
    onSuccess : function() {     
      return true;
    }  
});

/***  SIGNUP AND LOGING PAGE*/
$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-modal-forgotpass',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-modal-forgotpass');
      return false;
    }  
});

jQuery(document).ready(function() {	
	$( document ).on( "click", ".resend-code", function() {		
		busy(true);
	    $.ajax({    
	    type: "POST",
	    url: ajax_url,
	    data: "action=resendMobileCode&currentController=store&tbl=resendMobileCode&id="+$("#client_id").val(),
	    dataType: 'json',       
	    success: function(data){ 
	    	busy(false);      		
	    	if (data.code==1) {	    	   
	    		uk_msg_sucess(data.msg);
	    	} else {
	    		uk_msg(data.msg);
	    	}	    
	    }, 
	    error: function(){	        	    	
	    	busy(false); 
	    }		
	    });   	     	  		
	});	
}); /*end docu*/


/** START VERSION 2.1.1 added code*/
jQuery(document).ready(function() {	
	
	if ( $(".full_map_page").exists()){
		getAllMerchantCoordinates();		
		if ( $("#google_default_country").val()=="yes" ){		
		    $("#geo_address").geocomplete({
		    	country: $("#admin_country_set").val(),
		    	details:'form'
		    });  	   
		} else {			
			$("#geo_address").geocomplete({		    	
		    	details:'form'
		    });  	   
		}
	}	
	
	$( document ).on( "click", ".reset-geo", function() {		
		getAllMerchantCoordinates();	
	});
	
	$.validate({ 	
	language : jsLanguageValidator,
	    form : '#form_map',    
	    onError : function() {      
	    },
	    onSuccess : function() {     
	      searchGeoByAddress();
	      return false;
	    }  
	});
	
		
    if ( $(".new-merchant-header").exists() ){
    	var merchant_header=upload_url+"/"+$("#merchant_header_new").val();    
    	$('.new-merchant-header').css('background-image', 'url(' + merchant_header + ')');
    }	
	
});/* end docu*/

function getAllMerchantCoordinates()
{
	var map_zoom=parseInt($("#view_map_default_zoom").val());	
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: "action=getAllMerchantCoordinates&currentController=store&tbl=getAllMerchantCoordinates",
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){     		  
    		focus_lat=data.msg.lat;		
    		focus_lng=data.msg.lng;
    		initializeMarkerNew(data.details,'map_area',map_zoom);
    	} else {
    		uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });   	     	
}

var focus_lat='';
var focus_lng='';

function searchGeoByAddress()
{
	var params_geo="action=findGeo&currentController=store&tbl=findGeo&geo_address="+$("#geo_address").val();
    params_geo+="&lat="+$("#lat").val();
    params_geo+="&lng="+$("#lng").val();	
	
    busy(true);
    
    var map_zoom=parseInt($("#view_map_default_zoom_s").val());	    
    
	$.ajax({    
    type: "POST",
    url: ajax_url,
    data: params_geo,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){        		
    		focus_lat=data.msg.lat;
    		focus_lng=data.msg.lng;    		
    		initializeMarkerNew(data.details,'map_area',map_zoom); 
    	} else {
    		uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });   	     	
}


/*SCROLLING DIV STARTS HERE*/
jQuery(document).ready(function($){
	
	if ( $(".separate-category-menu").exists()){				
		var h=$(".col-category").height();		
		$(".scroll-parent").css({"min-height":h+"px"});
	}	
	
	if ( $(".scroll-parent2").exists()){				
		var h=$(".right-menu-content").height();		
		$(".scroll-parent2").css({"min-height":h+"px"});
	}		
	
	if ( $("#disabled_cart_sticky").val()==""){
	if ( $(".scroll-parent").exists()){						
		
		var $window = $(window);
		var $container = $(".scroll-child");
		var $main = $(".scroll-parent");
		var window_min = 0;
		var window_max = 0;
		var threshold_offset = 50;
	
	
	function set_limits(){
		//max and min container movements
		var max_move = $main.offset().top + $main.height() - $container.height() - 2*parseInt($container.css("top") );
		var min_move = $main.offset().top;
		//save them
		$container.attr("data-min", min_move).attr("data-max",max_move);
		//window thresholds so the movement isn't called when its not needed!
		//you may wish to adjust the freshhold offset
		window_min = min_move - threshold_offset;
		window_max = max_move + $container.height() + threshold_offset;
	}
	
	//sets the limits for the first load
	set_limits();
	
	function window_scroll(){
		//if the window is within the threshold, begin movements
		if( $window.scrollTop() >= window_min && $window.scrollTop() < window_max ){
			//reset the limits (optional)
			set_limits();
			//move the container
			container_move();
		}
	}
	
	$window.bind("scroll", window_scroll);
	
	function container_move(){
		var wst = $window.scrollTop();
		//if the window scroll is within the min and max (the container will be "sticky";
		if( wst >= $container.attr("data-min") && wst <= $container.attr("data-max") ){
			//work out the margin offset
			var margin_top = $window.scrollTop() - $container.attr("data-min");
			//margin it down!
			$container.css("margin-top", margin_top);
			$container.addClass("scroll-active");
		//if the window scroll is below the minimum 
		}else if( wst <= $container.attr("data-min") ){
			//fix the container to the top.
			$container.css("margin-top",0);
			$container.removeClass("scroll-active");
		//if the window scroll is above the maximum 
		}else if( wst > $container.attr("data-max") ){
			//fix the container to the top
			$container.css("margin-top", $container.attr("data-max")-$container.attr("data-min")+"px" );
			$container.addClass("scroll-active");
		}
	}
	//do one container move on load
	container_move();	
	}
	}

	/** START CART*/	
	if ( $("#disabled_cart_sticky").val()==""){
	if ( $(".scroll-parent2").exists()){				
						
		var $window2 = $(window);
		var $container2 = $(".scroll-child2");
		var $main2 = $(".scroll-parent2");
		var window_min2 = 0;
		var window_max2 = 0;
		var threshold_offset2 = 50;
		
	function set_limits2(){
		//max and min container movements
		var max_move = $main2.offset().top + $main2.height() - $container2.height() - 2*parseInt($container2.css("top") );
		var min_move = $main2.offset().top;
		//save them
		$container2.attr("data-min", min_move).attr("data-max",max_move);
		//window thresholds so the movement isn't called when its not needed!
		//you may wish to adjust the freshhold offset
		window_min2 = min_move - threshold_offset2;
		window_max2 = max_move + $container2.height() + threshold_offset2;
	}
	
	//sets the limits for the first load
	set_limits2();
	
	function window_scroll2(){
		//if the window is within the threshold, begin movements
		if( $window2.scrollTop() >= window_min2 && $window2.scrollTop() < window_max2 ){
			//reset the limits (optional)
			set_limits2();
			//move the container
			container_move2();
		}
	}
	
	$window2.bind("scroll", window_scroll2);
	
	function container_move2(){
		var wst = $window2.scrollTop();
		//if the window scroll is within the min and max (the container will be "sticky";
		if( wst >= $container2.attr("data-min") && wst <= $container2.attr("data-max") ){
			//work out the margin offset
			var margin_top = $window2.scrollTop() - $container2.attr("data-min");
			//margin it down!
			$container2.css("margin-top", margin_top);
			$container2.addClass("scroll-active");
		//if the window scroll is below the minimum 
		}else if( wst <= $container2.attr("data-min") ){
			//fix the container to the top.
			$container2.css("margin-top",0);
			$container2.removeClass("scroll-active");
		//if the window scroll is above the maximum 
		}else if( wst > $container2.attr("data-max") ){
			//fix the container to the top
			$container2.css("margin-top", $container2.attr("data-max")-$container2.attr("data-min")+"px" );
			$container2.addClass("scroll-active");
		}
	}
	//do one container move on load
	container_move2();	
	}	
	}
	/** END CART*/
	
	
	$( document ).on( "click", ".back-top-menu", function() {
		scroll_class('opening-hours-wrap');
	});
	
	if ( $("#address_book_id").exists() ){		
		$(".address-block").hide();
		$(".saved_address_block").hide();
				
		$("#street").removeAttr("data-validation");
  	    $("#city").removeAttr("data-validation");
  	    $("#state").removeAttr("data-validation");
	}
		
	$( document ).on( "click", ".edit_address_book", function() {
		$(".address_book_wrap").remove();
		$(".address-block").show();
		$(".saved_address_block").show();
				
		$("#street").attr("data-validation",'required');
  	    $("#city").attr("data-validation",'required');
  	    $("#state").attr("data-validation",'required');
	});
	
	$( document ).on( "click", ".map-address", function() {
		$(this).hide();
		
		$("#street").removeAttr("data-validation");
  	    $("#city").removeAttr("data-validation");
  	    $("#state").removeAttr("data-validation");
   	          	  
		$(".address_book_wrap").hide();
		$(".address-block").hide();
		$("#map_address_toogle").val(2);
		$(".map-address-wrap-inner").show();
		uk_msg_sucess(js_lang.trans_48);
		mapAddress();
	});
			
	$( document ).on( "click", ".paypal_paynow", function() {
		$(this).val(js_lang.processing);
		$(".paypal_paynow").css({ 'pointer-events' : 'none' });
	});
	
	/** set default if payment option is only */
	if ( $(".payment-option-page").exists() ){
		var c=$(".payment_option").length;
		if ( c==1){			
			$(".payment_option").attr("checked",true);
			$('.payment_option').iCheck('update');
						
			if ( $(".payment_option:checked").val()=="cod" ){
				$(".change_wrap").show();
			}		
			if ( $(".payment_option:checked").val()=="ccr" ){
				$(".credit_card_wrap").show();
			}	
		}
	}
	
}); /*end docu*/
/*SCROLLING DIV ENDS HERE*/

var temp_geocoder = new google.maps.Geocoder();

function mapAddress()
{		  
	  
	  $("#temporary_address").geocomplete({
          map: ".map_address",          
          markerOptions: {
            draggable: true
          }      
      });        
      $("#temporary_address").trigger("geocode");          
      
     $("#temporary_address").bind("geocode:dragged", function(event, latLng){    	      
	      $("#map_address_lat").val( latLng.lat() );
	      $("#map_address_lng").val( latLng.lng() );
	      /*codeLatLng(latLng.lat(),latLng.lng());*/
     });
}

/** END VERSION 2.1.1 added code*/


/** START ADDED CODE VERSION 2.3*/
jQuery(document).ready(function() {	
	$( document ).on( "click", ".filter-search-bar", function() {
		$(".filter-options").slideToggle("fast");
	});
	
    $( document ).on( "click", ".forgot-pass-link2", function() {    	    
    	  $(".section-forgotpass").fadeIn();    	  
    });
        	
}); /*end docu*/	
/** END CODE VERSION 2.3*/


/** START ADDED CODE VERSION 2.4*/
jQuery(document).ready(function() {	
	$( document ).on( "click", ".clear-cart", function() {
		clearCart();
	});
	
	if ( $("#disabled_cart_sticky").exists() ){
		if ( $("#disabled_cart_sticky").val()==2){
		    $(".scroll-parent2 .order-list-wrap").removeClass("scroll-child2");
		}
	}
}); /*end docu*/	

function clearCart()
{	
	var ans=confirm(js_lang.trans_4); 
	if (!ans){
		return;
	}
	var params="action=clearCart&currentController=store";	
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);
    	load_item_cart();    	
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function clearCartButton(option)
{	
	if ( option==1){
		$(".clear-cart").show();
	} else {
		$(".clear-cart").hide();
	}
}

var recaptcha1; // for customer signup
var recaptcha2; // for customer login

var KMRSCaptchaCallback = function(){
	if ( !$(".recaptcha").exists()){
		return;
	}
	if (typeof captcha_site_key === "undefined" || captcha_site_key==null || captcha_site_key=="") {   
		return;
	}	
	if ( $("#RecaptchaField1").exists() ){
		dump('RecaptchaField1');
        recaptcha1=grecaptcha.render('RecaptchaField1', {'sitekey' : captcha_site_key});    
	}
	if ( $("#RecaptchaField2").exists() ){
        recaptcha2=grecaptcha.render('RecaptchaField2', {'sitekey' : captcha_site_key});    
	}
};
/** START ADDED CODE VERSION 2.4*/


/** START ADDED CODE VERSION 2.5*/
jQuery(document).ready(function() {	
	$( document ).on( "click", ".view-order-history", function() {
		$(".show-history-"+ $(this).data("id") ).slideToggle("fast");
	});

    $( document ).on( "click", ".send-order-sms-code", function() {    	
    	    	
    	var sosc=$(this);
    	sosc.css({ 'pointer-events' : 'none' });
		var params="action=sendOrderSMSCode&currentController=store&session="+sosc.data("session")+"&mobile="+$("#contact_phone").val()+"&mtid="+$("#merchant_id").val();	
		busy(true);
	    $.ajax({    
	    type: "POST",
	    url: ajax_url,
	    data: params,
	    dataType: 'json',       
	    success: function(data){ 
	    	busy(false);	    	
	    	sosc.css({ 'pointer-events' : 'auto' });
	    	if (data.code==1){
	    		uk_msg_sucess(data.msg);
	    	} else {
	    		uk_msg(data.msg);
	    	}
	    }, 
	    error: function(){	        	    	
	    	busy(false); 
	    	sosc.css({ 'pointer-events' : 'auto' });
	    }		
	    });
	});
}); /*end ready*/

/** END ADDED CODE VERSION 2.5*/


/** START ADDED CODE VERSION 2.6*/
jQuery(document).ready(function() {		
	
	$( document ).on( "change", ".s_city", function() {    	
		var selected=$(this).val();				
		if ( selected!="-1"){
			$(".s_area").val("-1");
			$(".areas").addClass("area-hidden");
			$("."+selected).removeClass("area-hidden");
		} else {
			$(".areas").addClass("area-hidden");
		}
	});	
	
}); /*end ready*/
/** END ADDED CODE VERSION 2.6*/