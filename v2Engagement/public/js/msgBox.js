/****************************************************************************************************************************************************************
*																																								*
*																																								*
*																	Main Application msgBox Plugin																*
*																																								*
*																																								*
******************************************************************************************************************************************************************/
var msgBox = function () {
	var selector = '.msgBox';
	var defaults = {
		message: '',
		title: "Please Wait",
		progressBar: true,
		progressBarVal: 100,
		autoClose:0,
		closeable:false,
		overlay: true
	};
	
	var _open = function(opts)
	{
		var options = $.extend({}, defaults, opts);
		
		if(options.closeable)
		{
			$('.close', selector).show();
		}else{
                    
			$('.close2', selector).hide();
		}
		
		$('.modal-header .modal-title', selector).html(options.title);
		
		if(options.progressBar)
		{
			$('.modal-body .progress', selector).show();
		}else{
			$('.modal-body .progress', selector).hide();
		}
		$('.modal-body .message', selector).html(options.message);
		$(selector).modal('show');
		//return this;
		
	}
	
	return {
		open: _open,
		close: function(){
			$(selector).modal('hide');
			return this;
		}
	};
}();