function copylink() {
    var copyText = document.getElementById("url");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    var tooltip = document.getElementById("lcr_tooltip");
    tooltip.innerHTML = "Copied"; //+ copyText.value;
}
    
function outFunc() {
    var tooltip = document.getElementById("lcr_tooltip");
    tooltip.innerHTML = "Copy to clipboard";
}

(function ($) {
	'use strict';

	$('#copy_url').mouseover(function () {
		$('.lcr_tooltip').css('visibility', 'visible').css('opacity', '1');
	});
	$('#copy_url').mouseout(function () {
		$('.lcr_tooltip').css('visibility', 'hidden').css('opacity', '0');
	});

	$('.lockedLink.nolink').on('click', function (e) {
		e.preventDefault();
		$('.popupwrap').remove();
		$(this).parent().append('<div class="popupwrap">Not enough clicks.<div class="triangle-top"></div></div></div>');

		setTimeout(() => {
			$('.popupwrap').remove();
		}, 1500);
	});

})( jQuery );
