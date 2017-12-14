require([ 'jquery', 'jquery/ui'], function($){
	$(document).ready(function($) {

		jQuery("li.parent a.level-top, li.parent > a").attr('href','javascript:void(0)');
		
		/* Header Search */
		$(".block-search .block-title").click(function(){
			jQuery('.block-search .block-content').fadeToggle('fast');
			jQuery(".page-header").addClass("no-scroll");
		}); 
		
		$(".search-overlay").click(function(){
			jQuery('.block-search .block-content').fadeOut('fast');							
			jQuery(".page-header").removeClass("no-scroll");
		});

		$(".qty-wrapper > .qty-trigger").click(function() {
			$(this).next().toggle();
		});

		$(".qty-wrapper > .qty-options > .options").click(function() {
			$(this).parent().parent().next().val($(this).attr('data-value'));	
			$(this).parent().hide();
			$(this).parent().prev().html($(this).html());
		});

		$(document).on('mousewheel DOMMouseScroll', '.search-overlay, #search_mini_form, .page-header.no-scroll', function(e) {
    		var scrollTo = null;
			if (e.type == 'mousewheel') {
				scrollTo = (e.originalEvent.wheelDelta * -1);
			}
			else if (e.type == 'DOMMouseScroll') {
				scrollTo = 40 * e.originalEvent.detail;
			}

			if (scrollTo) {
				e.preventDefault();
				$(this).scrollTop(scrollTo + $(this).scrollTop());
			}
		});
		
		/* Header Menu */
		$(".navigation li.level0.parent").mouseenter(function(){
			jQuery('.navigation .level0.submenu').addClass('v-align');			
		});
		
		/*$(".navigation li.level0.parent").each(function(){
			$(this).prepend($('<div></div>').attr('class', "navigation-overlay").hover(function(){
				$(this).hide().next().removeClass('ui-state-active').next().hide();
			}));
		});		*/
		
		/*$(".navigation li.level0.parent").hover(function(){
			jQuery(this).find('.navigation-overlay').show();
		},function(){
			jQuery(this).find('.navigation-overlay').hide();
		});*/	
		
		/*jQuery(window).scroll(function(){
           if (jQuery(this).scrollTop() > 20) {
                jQuery('.navigation-overlay').css("top","0");
            }
            else {
                jQuery('.navigation-overlay').css("top","115px");
            }
        });*/
		
		function setHeight() {
			windowHeight = $(window).innerHeight();
			$('.navigation .level0.submenu').css('height', (windowHeight) - 85);
			$('#banners-slider').css('height', (windowHeight) - 116);
			$('#banners-slider .banner-container').css('height', (windowHeight) - 116);
		    $('.navigation-overlay').css('height', (windowHeight) - 116);
			$('.search-overlay').css('height', (windowHeight) - 116);
			$('.cms-home .section-container').css('height', (windowHeight));
            
        	 if($('.cms-home .section-container .section-video-trigger').length > 0)
                $('.cms-home .section-container .section-video-trigger').css('height', (windowHeight));
		};
		
		setHeight();

		$(window).resize(function() {
			setHeight();
		});	
		
		/* Category banner*/
		
		if($('.category-view').find('.section-container').length > 0) {
			$('.category-view').after('<div class="category-arrow-target"></div>');
			$('.category-view').prepend($('<a class="category-banner-arrow">down</a>'));
		}
					
		/* Category banner ends */
		
//		$('.page-header .panel.wrapper').after('<a class="header-control-pannel" style="display:none;">control</a>');
		
//		$(".header-control-pannel").click(function(){
//			jQuery('.page-header').toggleClass('control-panel');			
//		});
		
		
		/* Bundle product wishlist moves */		
		//$('.product-detail-dinner .product-social-links .action.towishlist').appendTo().insertBefore('.product-detail-dinner .box-tocart .actions .tocart');	

		/* grouped product wishlist moves */		
		
		$('.page-product-grouped .product-social-links .action.towishlist').clone().insertBefore('.page-product-grouped .box-tocart .actions .tocart');	



	

		
		$('.page.messages').insertAfter('.page-header');										
		
		/* Cart page buttons moves */
		$('.cart-container .form-cart .actions.main').appendTo('.cart-container');								
		$('.cart-summary .checkout-methods-items .action.primary.checkout').appendTo('.cart-container .cart.actions');
		
		/* Product detail Tabs atrributes remove */		
		jQuery(".product.info.detailed .data.item.title").removeAttr('data-role role data-collapsible aria-expanded aria-labeledby');
		jQuery(".product.info.detailed .data.item.title .data.switch").attr('href','javascript:void(0)');
		jQuery(".product.info.detailed .data.item.content").removeAttr('data-role');
		
		jQuery('#product\\.info\\.description').show();
		jQuery('#ktpl_catalog_product_tab_features')	.show();
		jQuery('#tab-label-product\\.info\\.description').addClass('active');
		jQuery('#tab-label-ktpl_catalog_product_tab_features').addClass('active');

		$(document).on("click", ".product.info.detailed .data.item.title", function(){                    
			
			$(this).toggleClass("active");
			$(this).next(".data.item.content").slideToggle();			                  
		})
		
		/* Sticky Header*/
        /*jQuery(window).scroll(function(){
           if (jQuery(this).scrollTop() > 10) {
                jQuery('body').addClass('stickyyyy');
            }
            else {
                $('body').removeClass('stickyyyy');
            }

            if ($(this).scrollTop() > 10) {
               $('body').addClass('slideDown');
            }
            else {
               $('body').removeClass('slideDown');
            }

        });*/
		
		/* Header Menu ends */
		
		/* Home top banner down arrow click */		
		$('a.home-banner-arrow').on('click', function(event) {
			var targetcontainer = $('#home-arrow-target');			
			event.preventDefault();
			$('html, body').stop().animate({
				scrollTop: targetcontainer.offset().top
			}, 800);
		});
		
		/* Category top banner down arrow click */		
		$('a.category-banner-arrow').on('click', function(event) {
			var targetcontainer = $('.category-arrow-target');			
			event.preventDefault();
			$('html, body').stop().animate({
				scrollTop: targetcontainer.offset().top
			}, 800);
		});		
	});  

});
