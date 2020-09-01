var _root_url = document.getElementById("helper_fn").getAttribute('data-base-url');
if(_root_url==undefined)_root_url='';

if(typeof base_url !== 'function')
function base_url(url)
{
	if(url==undefined)url='';
	return this._root_url+url;
}

var require = function (urls, callback)
{
	if(typeof urls !== 'object')urls = [urls];

	var counter = 0;
	
	$.each(urls, function(i, url){
		url = base_url('assets/'+url);
		if($('script[src="'+url+'"]').length == 0)
		{
			counter++;
			$.ajax({
				url: url,
				cache: true,
				success: function(response){
					counter--;
					$('<script></script>', {'src':url}).appendTo('body');
					if(counter==0 && typeof callback == 'function'){
						callback();
						callback = null;
					}
				}
			});
		}
	});
	
	if(counter==0 && typeof callback == 'function'){
		callback();
		callback = null;
	}
	
}




var include = function (urls, callback)
{
	if(typeof urls !== 'object')urls = [urls];

	var counter = 0;
	
	$.each(urls, function(i, url){
		url = base_url('assets/'+url);
		if($('link[href="'+url+'"]').length == 0)
		{
			counter++;
			$.ajax({
				url: url,
				cache: true,
				success: function(response){
					counter--;
					$('<link>', {rel:'stylesheet', type:'text/css', 'href':url}).appendTo('head');
					if(counter==0 && typeof callback == 'function'){
						callback();
						callback = null;
					}
				}
			});
		}
	});
	
	if(counter==0 && typeof callback == 'function'){
		callback();
		callback = null;
	}
	
}



var current_url = function()
{
	return window.location;
}

// getURLParameter
//////////////////////////////////////////////////////////////
var _getURLParameter = function (paramName) {
    var searchString = window.location.search.substring(1),
        i, val, params = searchString.split("&");

    for (i = 0; i < params.length; i++) {
        val = params[i].split("=");
        if (val[0] == paramName) {
            return unescape(val[1]);
        }
    }
    return null;
};
//alert( _getURLParameter('base_url') );
