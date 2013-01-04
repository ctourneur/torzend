/**
 * 
 * @param limit
 */
function setLocation(param, value) {
	console.log(param);
	console.log(value);
	var search = window.location.search;
	var url = window.location.pathname+'?'; 
	
	if(search.length>0)
	{
		
		// Extraction des paramètres
		var params = search.substr(1, search.length-1);
		
		// Parcourt la liste des params
		var paramsArray = params.split('&');
		for(var i=0; i<paramsArray.length; i++)
		{
			var paramArray = paramsArray[i].split('=');
			if(paramArray[0] != param)
			{
				url += paramsArray[i]+'&'; 
			}
		}
	}
	
	// Ajout de la limit
	url += param+'='+value;

	// Redirection
	window.location.href = url;
}