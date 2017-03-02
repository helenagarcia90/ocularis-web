(function( $ ){
   $.fn.aliasMaker = function(origin, genAlias) {
      
	   
	   return this.each(function(){

			
		   $(origin).data('maker', $(this));
		   $(this).data('origin', $(origin));
		   $(this).data('generateAlias',genAlias);
		   
			$(origin).on("keyup",function(){
			
				if(!$(this).data('maker').data('generateAlias'))
					return;
				$.fn.aliasMaker.generate($(this), $(this).data('maker'));
		
			});
				
				
			$(this).on("keypress",function(event) {

				
				if( !( (event.which >= 48 &&  event.which<= 57) || (event.which >= 97 &&  event.which<= 122)  || (event.which >= 65 &&  event.which<= 90)  || event.which == 45 || event.which == 46 || event.which == 95 || event.which == 126) ) 
					event.preventDefault();
						
			});
						

			$(this).on("change",function()
			{
				if($(this).val() == "")
					$(this).data('generateAlias',true);
				else		
					$(this).data('generateAlias',false);
						
			});
				
		
			
	   		$(this).on("paste", function (e) {
			  	e.preventDefault();
				return false;
			});
			
	   		$("#"+$(this).attr("id")+"_regen").data('maker', $(this)).data('origin',$(origin));
	   		
			$("#"+$(this).attr("id")+"_regen").on("click",function()
			{
				
				$.fn.aliasMaker.generate( $(this).data('origin'), $(this).data('maker'));
				$(this).data('maker').data('generateAlias',true);
				
			});
		   
		   
		   
	   });
	   
	  
   }; 
   
   
   $.fn.aliasMaker.generate = function($origin, $destination)
	{
		
		text = $origin.val();

		text = text.toLowerCase();
		
		text = text.replace(/\W{1}/g, function (m) {
				    map = {'ñ':'n','ç':'c','á':'a','à':'a','â':'a','ä':'a','é':'e','è':'e','ê':'e','ë':'e','í':'i','ì':'i','ï':'i','î':'i','ó':'o','ò':'o','ô':'o','ö':'o','ú':'u','ù':'u','û':'u','ü':'u'};

					if(map[m] != undefined)
						return map[m];
					else
						return m;

				});

		text = text.replace(/[^\w\._~]/g, "-");
		text = text.replace(/\-+/g, "-");
		text = text.replace(/\-$/, "");
		text = text.replace(/^\-/, "");						    		
				
		$destination.val(text);
	}
   
})( jQuery );