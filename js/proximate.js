(function($) {
	function calculateDistance(elem, mouseX, mouseY) {
		return Math.floor(Math.sqrt(Math.pow(mouseX - (elem.offset().left+(elem.width()/2)), 2) + Math.pow(mouseY - (elem.offset().top+(elem.height()/2)), 2)));
	}
	$.fn.proximate = function(options) {
		$inProximityCallBack = options['callback'];	
		var $element = this
		$(document).mousemove(function(e) {
			mX = e.pageX;
			mY = e.pageY;
			var distance = calculateDistance($element, mX, mY);
			$inProximityCallBack(distance, $element);
		})		
	}
})(jQuery)

