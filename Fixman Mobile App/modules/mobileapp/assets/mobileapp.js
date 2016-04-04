jQuery.fn.exists = function(){return this.length>0;}

var data_table;

function dump(data)
{
	console.debug(data);
}

jQuery(document).ready(function() {	

	
	if( $(".chosen").exists() ) {
       $(".chosen").chosen({
       	  allow_single_deselect:true,
       	  width: '100%'
       });     
    } 
    
   if ( $("#upload-file").exists()) {
	   var uploader = new ss.SimpleUpload({
	       button: 'upload-file', // HTML element used as upload button
	       url: ajaxurl+"/upload", // URL of server-side upload handler
	       name: 'uploadfile', // Parameter name of the uploaded file
	       responseType: 'json',
	       allowedExtensions: ['png', 'jpeg','jpg'],
		   maxSize: 11024, // kilobytes
		   onExtError: function(filename,extension ){
			   nAlert("Invalid File extennsion","warning");
		   },
		   onSizeError: function (filename,fileSize){ 
			   nAlert("Invalid File size","warning");  
		   },       
		   onSubmit: function(filename, extension) {      	            
		   	  busy(true);
		   },	
		   onComplete: function(filename, response) {	   	  
		   	  dump(response);
		   	  busy(false);
		   	  if (response.code==1){	   	  	 	   	  	 
		   	  	 nAlert(response.msg,"success");
		   	  	 $("#mobile_default_image_not_available").val(filename);
		   	  	 $(".my-thumb").attr("src",response.details);
		   	  } else {
		   	  	 nAlert(response.msg,"warning");	   	  	 
		   	  }
		   }
	    });    
   }
   
   if ( $("#table_list").exists() ) {
       initTable();
   }
           	
});/* end docu*/

function busy(e)
{
    if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        
    
    if (e) {
    	$("body").before("<div class=\"preloader\"></div>");
    } else $(".preloader").remove();
    
}

function initTable()
{		
	var params=$("#frm_table").serialize();	
	
	data_table = $('#table_list').dataTable({		
		   "iDisplayLength": 20,
	       "bProcessing": true, 	       
	       "bServerSide": true,
	       //"sAjaxSource": ajaxurl+"/"+ $("#action").val()+"/?currentController=admin",	       
	       "sAjaxSource": ajaxurl+"/"+ $("#action").val()+"/?currentController=admin&"+params,	       
	       "aaSorting": [[ 0, "DESC" ]],	       
           "sPaginationType": "full_numbers",   
           //"bFilter":false,            
           "bLengthChange": false,
	       "oLanguage":{	       	 
	       	 "sProcessing": "<p>Processing.. <i class=\"fa fa-spinner fa-spin\"></i></p>"
	       },	       
	       "fnInitComplete": function(oSettings, json) {	       	  		      
		   }		
	});
	   		
}

function nAlert(msg,alert_type)
{
	var n = noty({
		 text: msg,
		 type        : alert_type ,		 
		 theme       : 'relax',
		 layout      : 'topCenter',		 
		 timeout:2000,
		 animation: {
	        open: 'animated fadeInDown', // Animate.css class names
	        close: 'animated fadeOut', // Animate.css class names	        
	    }
	});
	
}

jQuery(document).ready(function() {	
	
   if ( $("#upload-file").exists()) {
	   var uploader = new ss.SimpleUpload({
	       button: 'upload-certificate-dev', // HTML element used as upload button
	       url: ajaxurl+"/uploadCertificate", // URL of server-side upload handler
	       name: 'uploadfile', // Parameter name of the uploaded file
	       responseType: 'json',
	       allowedExtensions: ['pem'],
		   maxSize: 11024, // kilobytes
		   onExtError: function(filename,extension ){
			   nAlert("Invalid File extennsion","warning");
		   },
		   onSizeError: function (filename,fileSize){ 
			   nAlert("Invalid File size","warning");  
		   },       
		   onSubmit: function(filename, extension) {      	            
		   	  busy(true);
		   },	
		   onComplete: function(filename, response) {	   	  
		   	  dump(response);
		   	  busy(false);
		   	  if (response.code==1){	   	  	 	   	  	 
		   	  	 nAlert(response.msg,"success");
		   	  	 $("#ios_push_dev_cer").val(filename);		   	  	 
		   	  } else {
		   	  	 nAlert(response.msg,"warning");	   	  	 
		   	  }
		   }
	    });    
   }	
   
   if ( $("#upload-certificate-prod").exists()) {
	   var uploader = new ss.SimpleUpload({
	       button: 'upload-certificate-prod', // HTML element used as upload button
	       url: ajaxurl+"/uploadCertificate", // URL of server-side upload handler
	       name: 'uploadfile', // Parameter name of the uploaded file
	       responseType: 'json',
	       allowedExtensions: ['pem'],
		   maxSize: 11024, // kilobytes
		   onExtError: function(filename,extension ){
			   nAlert("Invalid File extennsion","warning");
		   },
		   onSizeError: function (filename,fileSize){ 
			   nAlert("Invalid File size","warning");  
		   },       
		   onSubmit: function(filename, extension) {      	            
		   	  busy(true);
		   },	
		   onComplete: function(filename, response) {	   	  
		   	  dump(response);
		   	  busy(false);
		   	  if (response.code==1){	   	  	 	   	  	 
		   	  	 nAlert(response.msg,"success");
		   	  	 $("#ios_push_prod_cer").val(filename);		   	  	 
		   	  } else {
		   	  	 nAlert(response.msg,"warning");	   	  	 
		   	  }
		   }
	    });    
   }	
   
   
   $('.nav-tabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
   });
   
   if ( $("#translation-save-wrap").exists() ){
       $("#translation-save-wrap").sticky({topSpacing:0});
   }
      
   $('.export-language').click(function (e) {   	   
   	   dump(ajaxurl);
   	   openExportWindow(100,100,ajaxurl+"/exportlang");
   });
   
   if ( $("#import-language").exists()) {
	   var uploader = new ss.SimpleUpload({
	       button: 'import-language', // HTML element used as upload button
	       url: ajaxurl+"/importLang", // URL of server-side upload handler
	       name: 'uploadfile', // Parameter name of the uploaded file
	       responseType: 'json',
	       allowedExtensions: ['json'],
		   maxSize: 11024, // kilobytes
		   onExtError: function(filename,extension ){
			   nAlert("Invalid File extennsion","warning");
		   },
		   onSizeError: function (filename,fileSize){ 
			   nAlert("Invalid File size","warning");  
		   },       
		   onSubmit: function(filename, extension) {      	            
		   	  busy(true);
		   },	
		   onComplete: function(filename, response) {	   	  
		   	  dump(response);	
		   	  busy(false);
		   	  if (response.code==1){	   	  	 	   	  	 
		   	  	 nAlert(response.msg,"success");
		   	  	 window.location.refresh();   	  	 
		   	  } else {
		   	  	 nAlert(response.msg,"warning");	   	  	 
		   	  }	   	  
		   }
	    });    
   }	   
	
}); /*end doc*/

function openExportWindow(h, w, url) {
  leftOffset = (screen.width/2) - w/2;
  topOffset = (screen.height/2) - h/2;
  window.open(url, this.target, 'left=' + leftOffset + ',top=' + topOffset + ',width=' + w + ',height=' + h + ',resizable,scrollbars=yes');
}