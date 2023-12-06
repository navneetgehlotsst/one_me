/*-----------------------------------------------------------------------------------
/* Styles Switcher
-----------------------------------------------------------------------------------*/

window.console = window.console || (function(){
	var c = {}; c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(){};
	return c;
})();


jQuery(document).ready(function($) {

//Theme Option
	$(".dark-version, .dark-ltr-version" ).click(function(){
		$("#theme-opt").attr("href", "css/style-dark.css" );
		return false;
	});

	$(".light-version, .ltr-version" ).click(function(){
		$("#theme-opt").attr("href", "css/osahan.css" );
		return false;
	});

	$("#style-switcher .bottom a.settings").click(function(e){
		e.preventDefault();
		var div = $("#style-switcher");
		if (div.css("right") === "-189px") {
			$("#style-switcher").animate({
				right: "0px"
			}); 
		} else {
			$("#style-switcher").animate({
				right: "-189px"
			});
		}
	})
	
	$("#style-switcher").animate({
		right: "-189px"
	});
});