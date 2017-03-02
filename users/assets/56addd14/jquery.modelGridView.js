(function($) {

	methods = {

		init : function(options) {
			
			return this.each(function(){
			
					var grid = $(this);
					var id = grid.attr("id");
					
					$('.tooltipped', grid).tooltip();
		
					// handle sort
					grid.on('click',
							'table thead a.sort-link, a.ajax-link, .pager > a',
							function(e) {
		
								$(this).data("lastUrl",$(this).attr("href"))
								
								e.preventDefault();
		
								$("#"+id).modelGridView('showLoad');
		
								jQuery.ajax({
									'url' : $(this).attr("href"),
									'dataType' : 'html',
									'data' : {gridId : id},
									'success' : function(data) {
										lastUrl = $(this).attr("href");
										$("#"+id).modelGridView('refresh', data);
									},
									'error' : function(xhr, status) {
										alert(status);
									},
									'cache' : false
								});
		
							});
		
					// handle filters
					grid.on('change', '.filters input, .filters select',
							function() {	
		
								$("#"+id).modelGridView('showLoad');
		
								data = $("#"+id).find('.filters input, .filters select')
										.serialize();
		
								data += '&gridId='+id;
									
								jQuery.ajax({
									'url' : options.ajaxUrl,
									'data' : data,
									'dataType' : 'html',
									'success' : function(data) {
										$("#"+id).modelGridView('refresh', data);
									},
									'error' : function(xhr, status) {
										alert(status);
									},
									'cache' : false
								});
		
							});

			});
			
		},

		showLoad : function() {
			$('.loader', $(this)).fadeIn('fast');
		},

		hideLoad : function() {
			$('.loader', $(this)).fadeOut('fast');
		},

		refresh : function(data) {
			var $data = $('<div>' + data + '</div>');

			$('#' + $(this).attr('id') + '_table', $(this)).replaceWith(
					$('#' + $(this).attr('id') + '_table', $data));
			
			$('.pager', $(this)).replaceWith($('.pager', $data));

			$('.btn-heading', $(this)).replaceWith($('.btn-heading', $data));

			if($('.panel-heading .count', $(this)).html() != $('.panel-heading .count', $data).html())
			{
				$('.panel-heading .count', $(this)).fadeOut(
							'fast',
							function() {
								$(this).html($('.panel-heading .count', $data).html())
										.fadeIn('fast');
							});
			}
			
			$('.tooltipped', $(this)).tooltip();
			
			$(this).modelGridView('hideLoad');

		},
		
		reload : function()
		{
			
			$(this).modelGridView('showLoad');
			
			url = $(this).data("lastUrl");
			
			if(url == undefined)
				url = window.location;
			
			data = $(this).find('.filters input, .filters select')
			.serialize();
			grid = $(this);
			jQuery.ajax({
				'url' : url,
				'data' : data,
				'dataType' : 'html',
				'success' : function(data) {
					grid.modelGridView('refresh', data);
				},
				'error' : function(xhr, status) {
					alert(status);
				},
				'cache' : false
			});
			
			
		}
		

	}

	$.fn.modelGridView = function(method) {

		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(
					arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method
					+ ' does not exist on jQuery.modelGridView');
			return false;
		}
	};

})(jQuery);