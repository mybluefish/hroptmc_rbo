$.fn.getHexColor = function(colorType){
	var rgb = $(this).css(colorType);
//	var ie = !+"\v1";
	
	if(!$.support.msie){
//		alert("NOT IE");
		rgb = rgb.match(/^rgb\((\d+), \s*(\d+), \s*(\d+)\)$/);
	
		function hex(x){
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
	
		rgb = "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}
	
	return rgb;
}