/****************************************************************************************************************************************************************
*																																								*
*																																								*
*																	Main Application Model Plugin																*
*																																								*
*																																								*
******************************************************************************************************************************************************************/
var appModel = function () {
	var appModelInstance = '';
	var settings = {
		message: '',
		title: "Please Wait",
		progressBar: 100,
		autoClose:0,
		closeable:false,
		overlay: true
	};


	var render = function()
	{
		tmpl = '';
		tmpl = tmpl + '	<div class="modal-dialog">';
		tmpl = tmpl + '		<div class="modal-content">';
		tmpl = tmpl + '			<div class="modal-header">';

		if(settings.closeable)
		{
			tmpl = tmpl + '				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		}


		tmpl = tmpl + '				<h4 class="modal-title" id="appModalLabel">' + settings.title + '</h4>';
		tmpl = tmpl + '				</h4>';
		tmpl = tmpl + '			</div>';
		tmpl = tmpl + '			<div class="modal-body">';

		if(settings.progressBar == false)
		{
			tmpl = tmpl + '				<p>' + settings.message + '</p>';
		}else{
			tmpl = tmpl + '				<div class="progress">';
			tmpl = tmpl + '					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="' + settings.progressBar + '" style="width: 100%">';
			tmpl = tmpl + '					</div>';
			tmpl = tmpl + '				</div>';
			tmpl = tmpl + '				<small>' + settings.message + '</small>';
		}

		tmpl = tmpl + '			</div>';
		tmpl = tmpl + '';
		tmpl = tmpl + '		</div>';
		tmpl = tmpl + '	</div>';



		if(typeof settings.message == 'object')
		{
			var tmpl = $(tmpl);
			$('.modal-body', tmpl).html(settings.message);

		}


		return tmpl;
	}

	var init = function() {

		tmplModal = '';
		tmplModal = tmplModal + '<div id="appModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="appModalLabel" aria-hidden="true">';
		tmplModal = tmplModal + '</div>';

		if(appModelInstance.length == 0)
		{
			appModelInstance = $(tmplModal).appendTo('body');
			$(appModelInstance).on('shown.bs.modal', function(){

					if( typeof ($(this).data('settings')).onOpen == 'function')
					{
						($(this).data('settings')).onOpen(($(this).data('settings')).context);
					}

				}).on('hidden.bs.modal', function(){
					//console.log('closed');
				});


		}
		return appModelInstance;

	};



	var _settings = function(newSettings)
	{
		settings = $.extend({}, settings, newSettings);
		return settings;
	};



	var _open = function(newSettings, callback, context)
	{

		console.log('appModal: Try to open');

		if($(appModelInstance).is(':visible'))
		{
			console.log('appModal: Already open');

			$(appModelInstance).on('hidden.bs.modal', function() {
					console.log('appModal: Closed');
					_open(newSettings, callback, context);
				});
			console.log('appModal: Try to close');
			_close();
			return appModelInstance;

		}


		var that = this;
		setTimeout(function(){
				settings = $.extend({}, settings, newSettings);
				init();

				if(settings.autoClose>0)
				{
					setTimeout(function(){ _close(); }, settings.autoClose);
				}

				$(appModelInstance).data('settings', settings);

				$(appModelInstance).on('shown.bs.modal', function() {
						if(typeof callback == 'function')
						{
							callback(context);
						}
					});

				$(appModelInstance).html(render());
				$(appModelInstance).modal('show');
                                console.log(settings);
				console.log('appModal: opened');
			}, 200);

		return appModelInstance;

	};



	var _close = function()
	{
		//setTimeout(function(){ _close(); }, 250);
		$(appModelInstance).modal('hide');
		this.settings = {
			message: '',
			title: "Please Wait",
			progressBar: 100,
			autoClose:0,
			closeable:false,
			overlay: true
		};
		$(appModelInstance).removeData();

		//$(appModelInstance).remove();
		//$('body').removeClass('modal-open');
		//$('.modal-backdrop').remove();
		return appModelInstance;

	};



	return {
		settings: _settings,
		open: _open,
		close: _close
	};


}();
