/*
 * doing Things Right - Web Stats
 *
 * author Mike Mackintosh <mike@bakeryframework.com>
 */

var starttime = new Date();

;(function( $ ) {

  $.fn.dtrws = function( params ) {
    
    // params
	  var profile = params;
	  
    // Create image
	  var f=new Image(1,1);
	  
    // Web script
	  var url = "/dtrws.php?";
	  
	  // Profile ID
	  url += "profile="+profile
	  
	  // Screen Resolution
	  url += "&resolutionx="+screen.width
	  url += "&resolutiony="+screen.height

	  //
	  url += "&loadtime="+(new Date().getTime() - starttime.getTime())

	  // Flash Version
	  //url += "&flashVer="+ (new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1]);

	  // Domain
	  url += "&domain="+ window.location.host
	  
	  // Protocol
	  url += "&protocol="+ window.location.protocol

	  url += "&pathname="+ window.location.pathname

	  url += "&search="+ window.location.search

	  // Location
	  url += "&location="+ window.location.protocol +
	    '//' + window.location.hostname +
	    window.location.pathname +
	    window.location.search

	  // Page title
	  url += "&title="+ $("title").text()
	  
	  // Referer
	  url += "&refurl="+ document.referrer
	  
	  // 
	  url += "&osVer="+navigator.appVersion
	  
	  url += "&osAgent="+navigator.userAgent

	  
    // Make request
	  f.src=url;
				
  };

})( jQuery );
