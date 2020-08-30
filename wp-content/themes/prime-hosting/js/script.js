
(function ($) {
	$('.blog_2').slick({
		infinite: true,
		slidesToShow: 2,
		slidesToScroll: 2,
		responsive: [

			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

	$().ready(function () {
		$('.slick-carousel').slick({
			arrows: true,
			centerPadding: "0px",
			dots: true,
			slidesToShow: 1,
			infinite: false,
			accessibility: true
		});
	});

	// Move focus to the first active slide after navigating forwards. 
	// Without this, the focus is placed inside the comment area.
	$('.slick-next').click(function () {
		event.preventDefault();
		$(".slick-active").focus();
	});


})(jQuery);


(function ($) {
	// Page loader js
	$('#preloader').delay('10').fadeOut(2000);
	setTimeout(page_anim_remove_preloader, '11000');

	function page_anim_remove_preloader() {
		$('#preloader').remove();
	}

}) (jQuery);

/*====================================
		Scroll to top
=====================================*/

(function ($) {
	$(document).ready(function () {
		$('.go-top').hide(0)

		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('.go-top').fadeIn(200);
			} else {
				$('.go-top').fadeOut(300);
			}
		});
		$('.go-top').click(function () {
			event.preventDefault();

			$('html , body').animate({ scrollTop: 0 }, 1000);
		});
	});
}) (jQuery);


//Keep focus within the secondary menu, WooCommerce cart and search field.
(function () {
	document.addEventListener('keydown', function (event) {
		var modal, selectors, elements, menuType, bottomMenu,
			activeEl, lastEl, firstEl, tabKey, shiftKey,

		selectors = 'input, a, button';

		modal = document.querySelectorAll("#collapseExample_info_01, #cart1, #search_box");
		
		for (var i = 0; i < modal.length; i++) {

			if (modal[i].classList.contains( 'show' ) ) {

				modalwrap = document.querySelectorAll(".cart-wrap, .search-wrap, .secondary-menu-wrap");

				for (var i = 0; i < modalwrap.length; i++) {
					elements = modalwrap[i].querySelectorAll(selectors);
					elements = Array.prototype.slice.call(elements);

					lastEl = elements[elements.length - 1];
					firstEl = elements[0];
					activeEl = document.activeElement;

					tabKey = event.keyCode === 9;
					shiftKey = event.shiftKey;

					if (!shiftKey && tabKey && lastEl === activeEl) {
						event.preventDefault();
						firstEl.focus();
					}

					if (shiftKey && tabKey && firstEl === activeEl) {
						event.preventDefault();
						lastEl.focus();
					}
				}
			}
		}
	})
})();

// Set focus inside the search input field.
(function ($) {
	$('#search_box').on('shown.bs.collapse', function () {
		$("#search_input").focus();
	})
})(jQuery);
