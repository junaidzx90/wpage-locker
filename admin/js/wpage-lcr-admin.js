(function( $ ) {
	'use strict';

	$('.lockedLink.nolink').on('click', function (e) {
		e.preventDefault();
		$('.popupwrap').remove();
		$(this).parent().append('<div class="popupwrap">Not enough clicks.<div class="triangle-top"></div></div></div>');

		setTimeout(() => {
			$('.popupwrap').remove();
		}, 1500);
	});
})( jQuery );
