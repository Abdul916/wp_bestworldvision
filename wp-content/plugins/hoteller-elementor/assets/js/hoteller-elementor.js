;function isJson(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}

function isTouchDevice() {
  return 'ontouchstart' in window // works on most browsers 
      || 'onmsgesturechange' in window; // works on ie10
}

function isMobileDevice() {
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
}

jQuery.fn.isInViewport = function() {
    var elementTop = jQuery(this).offset().top;
    var elementBottom = elementTop + jQuery(this).outerHeight();

    var viewportTop = jQuery(window).scrollTop();
    var viewportBottom = viewportTop + jQuery(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
};

( function( $ ) {
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		
		jQuery("img.lazy").each(function() {
			var currentImg = jQuery(this);
			
			jQuery(this).Lazy({
				onFinishedAll: function() {
					currentImg.parent("div.post_img_hover").removeClass("lazy");
					currentImg.parent('.tg_gallery_lightbox').parent("div.gallery_grid_item").removeClass("lazy");
		        }
			});
		});
		
		elementorFrontend.hooks.addAction('frontend/element_ready/global', function( $scope ) {
			
			if(elementorFrontend.isEditMode())
			{
				var elementSettings = {};
				var modelCID 		= $scope.data( 'model-cid' );
					
				var settings 		= elementorFrontend.config.elements.data[ modelCID ];
				if(typeof settings != 'undefined')
				{
					var type 			= settings.attributes.widgetType || settings.attributes.elType,
						settingsKeys 	= elementorFrontend.config.elements.keys[ type ];
			
					if ( ! settingsKeys ) {
						settingsKeys = elementorFrontend.config.elements.keys[type] = [];
			
						jQuery.each( settings.controls, function ( name, control ) {
							if ( control.frontend_available ) {
								settingsKeys.push( name );
							}
						});
					}
			
					jQuery.each( settings.getActiveControls(), function( controlKey ) {
						if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
							elementSettings[ controlKey ] = settings.attributes[ controlKey ];
						}
					} );
		
					var widgetExt = elementSettings;
				}
			}
			else
			{
				//Get widget settings data
				var widgetExtObj = $scope.attr('data-settings');
				
				if(isJson(widgetExtObj) && typeof widgetExtObj != 'undefined')
				{
					var widgetExt = JSON.parse(widgetExtObj);
				}
			}
			
			if(typeof widgetExt != 'undefined')
			{
				//Begin background image parallax scrolling
				if(typeof widgetExt.hoteller_ext_is_background_parallax != 'undefined' && widgetExt.hoteller_ext_is_background_parallax == 'true')
				{
					if(typeof widgetExt.background_background != 'undefined' && widgetExt.background_background == 'classic')
					{
						//If not edit in Elementor for better performance
						if(!elementorFrontend.isEditMode())
						{
							var widgetBg = 	$scope.css('background-image');
							
							//Add support for Elementor background lazy load
							if(widgetBg == 'none') {
								widgetBg = 	$scope.css('--e-bg-lazyload');
							}
							
							widgetBg = widgetBg.replace('url(','').replace(')','').replace(/\"/gi, "");
							
							//parallaxator
							/*$scope.addClass('parallaxator');
							$scope.append('<img class="parallax_child" src="'+widgetBg+'" data-parallaxator-reverse="true"/>');*/
							
							var jarallaxScrollSpeed = 0.5;
							if(typeof widgetExt.hoteller_ext_is_background_parallax_speed.size != 'undefined')
							{
								jarallaxScrollSpeed = parseFloat(widgetExt.hoteller_ext_is_background_parallax_speed.size);
							}
							//console.log(jarallaxScrollSpeed);
							//Jarallax
							$scope.addClass('jarallax');
							$scope.append('<img class="jarallax-img" src="'+widgetBg+'"/>');
							
							$scope.jarallax({
							    speed: jarallaxScrollSpeed
							});
							
							if(!isMobileDevice())
							{
								$scope.css('background-image', 'none');
							}
							
							jQuery(window).resize(function() {
								if(!isMobileDevice())
								{
									$scope.css('background-image', 'none');
								}
								else
								{
									$scope.css('background-image', 'url('+widgetBg+')');
								}
							});
						}
					}
				}
				
				if(typeof widgetExt.hoteller_ext_is_fadeout_animation != 'undefined' && widgetExt.hoteller_ext_is_fadeout_animation == 'true')
				{
					var scrollVelocity = parseFloat(widgetExt.hoteller_ext_is_fadeout_animation_velocity.size);
					var scrollDirection = widgetExt.hoteller_ext_is_fadeout_animation_direction; 

					jQuery(window).scroll(function(i){
						var scrollVar = jQuery(window).scrollTop();
						var scrollPx = -(scrollVelocity*scrollVar);
						
						if(scrollDirection == 'up')
						{
						 scrollPx = -(scrollVelocity*scrollVar);
						}
						else if(scrollDirection == 'down')
						{
						 scrollPx = scrollVelocity*scrollVar;
						}
						else
						{
						    scrollPx = 0;
						}
						
						//console.log(scrollVelocity);
						$scope.find('.elementor-widget-container').css({'transform': "translateY("+scrollPx+"px)" });
						$scope.find('.elementor-widget-container').css({'opacity':( 100-(scrollVar/4) )/100});
					})
				}
					
				
				//Begin scroll animation extensions
				if(typeof widgetExt.hoteller_ext_is_scrollme != 'undefined' && widgetExt.hoteller_ext_is_scrollme == 'true')
				{	
					var scrollArgs = {};
					
					if(typeof widgetExt.hoteller_ext_scrollme_scalex.size != 'undefined' && widgetExt.hoteller_ext_scrollme_scalex.size != 1)
					{
						scrollArgs['scaleX'] = widgetExt.hoteller_ext_scrollme_scalex.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_scaley.size != 'undefined' && widgetExt.hoteller_ext_scrollme_scaley.size != 1)
					{
						scrollArgs['scaleY'] = widgetExt.hoteller_ext_scrollme_scaley.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_scalez.size != 'undefined' && widgetExt.hoteller_ext_scrollme_scalez.size != 1)
					{
						scrollArgs['scaleZ'] = widgetExt.hoteller_ext_scrollme_scalez.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_rotatex.size != 'undefined' && widgetExt.hoteller_ext_scrollme_rotatex.size != 0)
					{
						scrollArgs['rotateX'] = widgetExt.hoteller_ext_scrollme_rotatex.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_rotatey.size != 'undefined' && widgetExt.hoteller_ext_scrollme_rotatey.size != 0)
					{
						scrollArgs['rotateY'] = widgetExt.hoteller_ext_scrollme_rotatey.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_rotatez.size != 'undefined' && widgetExt.hoteller_ext_scrollme_rotatez.size != 0)
					{
						scrollArgs['rotateY'] = widgetExt.hoteller_ext_scrollme_rotatez.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_translatex.size != 'undefined' && widgetExt.hoteller_ext_scrollme_translatex.size != 0)
					{
						scrollArgs['x'] = widgetExt.hoteller_ext_scrollme_translatex.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_translatey.size != 'undefined' && widgetExt.hoteller_ext_scrollme_translatey.size != 0)
					{
						scrollArgs['y'] = widgetExt.hoteller_ext_scrollme_translatey.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_translatez.size != 'undefined' && widgetExt.hoteller_ext_scrollme_translatez.size != 0)
					{
						scrollArgs['z'] = widgetExt.hoteller_ext_scrollme_translatez.size;
					}
					
					if(typeof widgetExt.hoteller_ext_scrollme_smoothness.size != 'undefined')
					{
						scrollArgs['smoothness'] = widgetExt.hoteller_ext_scrollme_smoothness.size;
					}
					
					//scrollArgs['duration'] =  150;
					
					$scope.attr('data-parallax', JSON.stringify(scrollArgs));
					
					if(typeof widgetExt.hoteller_ext_scrollme_disable != 'undefined')
					{
						if(widgetExt.hoteller_ext_scrollme_disable == 'mobile')
						{
							if(parseInt(jQuery(window).width()) < 501)
							{
								$scope.addClass('noanimation');
							}
						}
						
						if(widgetExt.hoteller_ext_scrollme_disable == 'tablet')
						{
							if(parseInt(jQuery(window).width()) < 769)
							{
								$scope.addClass('noanimation');
							}
						}
						
						jQuery(window).resize(function() {
							if(widgetExt.hoteller_ext_scrollme_disable == 'mobile')
							{
								if(isMobileDevice() || parseInt(jQuery(window).width()) < 501)
								{
									$scope.addClass('noanimation');
								}
								else
								{
									$scope.removeClass('noanimation');
								}
							}
							
							if(widgetExt.hoteller_ext_scrollme_disable == 'tablet')
							{
								if(parseInt(jQuery(window).width()) < 769)
								{
									$scope.addClass('noanimation');
								}
								else
								{
									$scope.removeClass('noanimation');
								}
							}
						});
					}
				}
				//End scroll animation extensions
				
				//Begin entrance animation extensions
				if(typeof widgetExt.hoteller_ext_is_smoove != 'undefined' && widgetExt.hoteller_ext_is_smoove == 'true')
				{				
					$scope.addClass('init-smoove');
					
					$scope.smoove({
						min_width : parseInt(widgetExt.hoteller_ext_smoove_disable),
						
						scaleX   : widgetExt.hoteller_ext_smoove_scalex.size,
					    scaleY   : widgetExt.hoteller_ext_smoove_scaley.size,
					    
					    rotateX   : parseInt(widgetExt.hoteller_ext_smoove_rotatex.size)+'deg',
					    rotateY   : parseInt(widgetExt.hoteller_ext_smoove_rotatey.size)+'deg',
					    rotateZ   : parseInt(widgetExt.hoteller_ext_smoove_rotatez.size)+'deg',
					    
					    moveX   : parseInt(widgetExt.hoteller_ext_smoove_translatex.size)+'px',
					    moveY   : parseInt(widgetExt.hoteller_ext_smoove_translatey.size)+'px',
					    moveZ   : parseInt(widgetExt.hoteller_ext_smoove_translatez.size)+'px',
					    
					    skewX   : parseInt(widgetExt.hoteller_ext_smoove_skewx.size)+'deg',
					    skewY   : parseInt(widgetExt.hoteller_ext_smoove_skewy.size)+'deg',
					    
					    perspective :  parseInt(widgetExt.hoteller_ext_smoove_perspective.size),
					    
					    offset : '-10%',
					});
		
					if(typeof widgetExt.hoteller_ext_smoove_duration != 'undefined')
					{
						$scope.css('transition-duration', parseInt(widgetExt.hoteller_ext_smoove_duration)+'ms');
					}
					
					var width = jQuery(window).width();
					if (widgetExt.hoteller_ext_smoove_disable >= width) {
					   if(!$scope.hasClass('smooved'))
					   {
					       $scope.addClass('no-smooved');
					   }
				       
				       return false;
				   }
				   
				   setTimeout(function(){ 
						  window.scrollTo(window.scrollX, window.scrollY - 1);
						  window.scrollTo(window.scrollX, window.scrollY + 1);
					  }, 1000);
				}
				//End entrance animation extensions
				
				
				//Begin mouse parallax extensions
				if(typeof widgetExt.hoteller_ext_is_parallax_mouse != 'undefined' && widgetExt.hoteller_ext_is_parallax_mouse == 'true')
				{	
					var elementID = $scope.attr('data-id');
					$scope.find('.elementor-widget-container').attr('data-depth', parseFloat(widgetExt.hoteller_ext_is_parallax_mouse_depth.size));
					$scope.attr('ID', 'parallax-'+elementID);
		
					var parentElement = document.getElementById('parallax-'+elementID);
					var parallax = new Parallax(parentElement, {
						relativeInput: true
					});
					
					if(elementorFrontend.isEditMode())
					{
						if($scope.width() == 0)
						{
							$scope.css('width', '100%');
						}
						
						if($scope.height() == 0)
						{
							$scope.css('height', '100%');
						}
					}
				}
				//End mouse parallax extensions
				
				
				//Begin infinite animation extensions
				if(typeof widgetExt.hoteller_ext_is_infinite != 'undefined' && widgetExt.hoteller_ext_is_infinite == 'true')
				{
					var animationClass = '';
					var keyframeName = '';
					var animationCSS = '';
					
					if(typeof widgetExt.hoteller_ext_infinite_animation != 'undefined')
					{
						animationClass = widgetExt.hoteller_ext_infinite_animation;
						
						switch(animationClass) {
						  	case 'if_swing1':
						    	keyframeName = 'swing';
						    break;
						    
						    case 'if_swing2':
						    	keyframeName = 'swing2';
						    break;
						    
						    case 'if_wave':
						    	keyframeName = 'wave';
						    break;
						    
						    case 'if_tilt':
						    	keyframeName = 'tilt';
						    break;
						    
						    case 'if_bounce':
						    	keyframeName = 'bounce';
						    break;
						    
						    case 'if_scale':
						    	keyframeName = 'scale';
						    break;
						    
						    case 'if_spin':
						    	keyframeName = 'spin';
						    break;
						}
						
						animationCSS+= keyframeName+' ';
					}
					
					if(typeof widgetExt.hoteller_ext_infinite_duration != 'undefined')
					{
						animationCSS+= widgetExt.hoteller_ext_infinite_duration+'s ';
					}
					
					animationCSS+= 'infinite alternate ';
					
					if(typeof widgetExt.hoteller_ext_infinite_easing != 'undefined')
					{
						animationCSS+= 'cubic-bezier('+widgetExt.hoteller_ext_infinite_easing+')';
					}
					
					$scope.css({
						'animation' : animationCSS,
					});
					$scope.addClass(animationClass);
				}
				//End infinite animation extensions
				
				//Begin link to side menu extensions
				if(typeof widgetExt.hoteller_ext_link_sidemenu != 'undefined' && widgetExt.hoteller_ext_link_sidemenu == 'true')
				{
					$scope.on( 'click', function(e) {
						e.preventDefault();
						
						jQuery('body,html').animate({scrollTop:0},100);
						jQuery('body').addClass('js_nav');
						jQuery('body').addClass('modalview');
						jQuery('#close_mobile_menu').addClass('open');
					});
				}
				
				//Begin link to fullscreen menu extensions
				if(typeof widgetExt.hoteller_ext_link_fullmenu != 'undefined' && widgetExt.hoteller_ext_link_fullmenu == 'true')
				{
					$scope.addClass('fullmenu-button');
					
					$scope.on( 'click', function(e) {
						e.preventDefault();
						
						//jQuery('body,html').animate({scrollTop:0},100);
						jQuery('body').toggleClass('fullmenu-active').trigger('classChange');
						jQuery('.fullmenu-wrapper').toggleClass('fullmenu-wrapper-active');
					});
				}
				
				if(typeof widgetExt.hoteller_ext_link_closed_fullmenu != 'undefined' && widgetExt.hoteller_ext_link_closed_fullmenu == 'true')
				{
					$scope.on( 'click', function(e) {
						e.preventDefault();
						
						jQuery('body').removeClass('fullmenu-active').trigger('classChange');
						jQuery('.fullmenu-wrapper').removeClass('fullmenu-wrapper-active');
					});
				}
				
				//Begin background on change extensions
				if(typeof widgetExt.hoteller_ext_is_background_on_scroll != 'undefined' && widgetExt.hoteller_ext_is_background_on_scroll == 'true')
				{	
					var bodyBackground = jQuery('body').css('background-color');
					var position = jQuery(window).scrollTop();
					
					jQuery(window).on("scroll touchmove", function() {
						clearTimeout($.data(this, 'scrollTimer'));
						$.data(this, 'scrollTimer', setTimeout(function() {
							jQuery('body').attr('data-scrollend', jQuery(window).scrollTop());
						}, 250));
						
						var scroll = jQuery(window).scrollTop();
						var position = jQuery('body').attr('data-scrollend');
						var windowHeight = jQuery(window).height();
						var windowHeightOffset = parseInt(windowHeight/2);
						var elementTop = $scope.position().top - windowHeightOffset;
						var elementBottom = elementTop + $scope.outerHeight(true);
						
						/*console.log('document scroll top: '+jQuery(document).scrollTop());
						console.log('element top: '+elementTop);
						console.log('element bottom: '+elementBottom);*/
						
						if (scroll > position) { 
							//console.log('scroll down');
							
							//Scroll down
							if (jQuery(document).scrollTop() >= elementTop && jQuery(document).scrollTop() <= elementBottom) {
								//jQuery('body').css('background-color', widgetExt.hoteller_ext_background_on_scroll_color);
								jQuery('#wrapper').css('background-color', widgetExt.hoteller_ext_background_on_scroll_color);
							}
							
							if (jQuery(document).scrollTop() > elementBottom) {
								//jQuery('body').css('background-color', bodyBackground);
								jQuery('#wrapper').css('background-color', bodyBackground);
							}
						
						} else {
							/*console.log('scroll up');
							console.log('document scroll top: '+jQuery(document).scrollTop());
							console.log('element top: '+$scope.position().top);
							console.log('element bottom: '+elementBottom);*/
							
							//Scroll up	
							if (jQuery(document).scrollTop() <= elementBottom && jQuery(document).scrollTop() >= elementTop) {
								setTimeout(function(){
									//jQuery('body').css('background-color', widgetExt.hoteller_ext_background_on_scroll_color).stop();
									jQuery('#wrapper').css('background-color', widgetExt.hoteller_ext_background_on_scroll_color).stop();
								}, 100);
							}
							
							if (jQuery(document).scrollTop() < $scope.position().top) {
								//jQuery('body').css('background-color', bodyBackground);
								jQuery('#wrapper').css('background-color', bodyBackground);
							}
						}
					});
				}
				//End background on change extensions
				
				//Begin mobile static position extensions
				if(typeof widgetExt.hoteller_ext_mobile_static != 'undefined' && widgetExt.hoteller_ext_mobile_static == 'true')
				{
					$scope.addClass('mobile-static');
				}
				//End mobile static position extensions
			}
		});
		
		//Start execute javascript for blog posts
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-blog-posts.default', function( $scope ) {
			jQuery(function( $ ) {
				jQuery("img.lazy").each(function() {
					var currentImg = jQuery(this);
					
					jQuery(this).Lazy({
						onFinishedAll: function() {
							currentImg.parent("div.post_img_hover").removeClass("lazy");
				        },
					});
				});
				
				if(!is_touch_device())
				{
					var scaleTilt = 1.05;
					if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
						scaleTilt = 1;
					}
					
					jQuery(".blog-tilt").tilt({
					    scale: scaleTilt,
					    perspective: 2500
					});
				}
				
				jQuery(".layout_masonry").each(function() {
					var grid = jQuery(this);
					
					grid.imagesLoaded().progress( function() {
						grid.masonry({
							itemSelector: ".blog-posts-masonry",
							columnWidth: ".blog-posts-masonry",
							gutter : 45
						});
						
						jQuery(".layout_masonry .blog-posts-masonry").each(function(index) {
						    setTimeout(function() {
						      	jQuery(".layout_masonry .blog-posts-masonry").eq(index).addClass("is-showing");
						    }, 250 * index);
						 });
					});
					
					jQuery(".layout_masonry img.lazy_masonry").each(function() {
						var currentImg = jQuery(this);
						currentImg.parent("div.post_img_hover").removeClass("lazy");
						
						jQuery(this).Lazy({
							onFinishedAll: function() {
								grid.masonry({
									itemSelector: ".blog-posts-masonry",
									columnWidth: ".blog-posts-masonry",
									gutter : 45
								});
					        },
						});
					});
				});
				
				jQuery(".layout_metro_masonry").each(function() {
					var grid = jQuery(this);
					
					grid.imagesLoaded().progress( function() {
						grid.masonry({
							itemSelector: ".blog-posts-metro",
							columnWidth: ".blog-posts-metro",
							gutter : 40
						});
						
						jQuery(".layout_metro_masonry .blog-posts-metro").each(function(index) {
						    setTimeout(function() {
						      	jQuery(".layout_metro_masonry .blog-posts-metro").eq(index).addClass("is-showing");
						    }, 100 * index);
						});
					});
					
					jQuery(".post_metro_left_wrapper img.lazy_masonry, .layout_metro_masonry img.lazy_masonry").each(function() {
						var currentImg = jQuery(this);
						currentImg.parent("div.post_img_hover").removeClass("lazy");
						
						jQuery(this).Lazy({
							onFinishedAll: function() {
								grid.masonry({
									itemSelector: ".blog-posts-metro",
									columnWidth: ".blog-posts-metro",
									gutter : 40
								});
					        },
						});
					});
				});
				
				var menuLayout = jQuery('#pp_menu_layout').val();
				if(menuLayout != 'leftmenu')
				{
					jQuery(".post_metro_left_wrapper").stick_in_parent({ offset_top: 120 });
				}
				else
				{
					jQuery(".post_metro_left_wrapper").stick_in_parent({ offset_top: 40 });
				}
	
				if(jQuery(window).width() < 768 || is_touch_device())
				{
					jQuery(".post_metro_left_wrapper").trigger("sticky_kit:detach");
				}
			});
		} );
		//End execute javascript for blog posts
		
		//Start execute javascript for gallery grid
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-gallery-grid.default', function( $scope ) {
			jQuery("img.lazy").each(function() {
				var currentImg = jQuery(this);
				
				jQuery(this).Lazy({
					onFinishedAll: function() {
						currentImg.parent("div.post_img_hover").removeClass("lazy");
						currentImg.parent('.tg_gallery_lightbox').parent("div.gallery_grid_item").removeClass("lazy");
						currentImg.parent("div.gallery_grid_item").removeClass("lazy");
			        }
				});
			});
			
			jQuery(function( $ ) {
				if(!is_touch_device())
				{
					var scaleTilt = 1.1;
					if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
						scaleTilt = 1;
					}
					jQuery(".gallery-grid-tilt").tilt({
					    scale: scaleTilt,
					    perspective: 2500
					});
				}
			});
		} );
		//End execute javascript for gallery grid
		
		//Start execute javascript for gallery masonry
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-gallery-masonry.default', function( $scope ) {
			jQuery(function( $ ) {
				if(!is_touch_device())
				{
					var scaleTilt = 1.1;
					if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
						scaleTilt = 1;
					}
					jQuery(".gallery-grid-tilt").tilt({
					    scale: scaleTilt,
					    perspective: 2500
					});
				}
				
				jQuery(".gallery_grid_content_wrapper.do_masonry").each(function() {
					var grid = jQuery(this);
					var cols = grid.attr('data-cols');
					
					if(!grid.hasClass('has_no_space'))
					{
						var gutter = 40;
						if(cols > 4)
						{
							gutter = 30;
						}
					}
					else
					{
						gutter = 0;
					}
					
					grid.imagesLoaded().progress( function() {
						grid.masonry({
							itemSelector: ".gallery_grid_item",
							columnWidth: ".gallery_grid_item",
							gutter : gutter
						});
						
						jQuery(".gallery_grid_content_wrapper.do_masonry .gallery_grid_item").each(function(index) {
						    setTimeout(function() {
						      	jQuery(".do_masonry .gallery_grid_item").eq(index).addClass("is-showing");
						    }, 100 * index);
						 });
					});
					
					jQuery(".gallery_grid_content_wrapper.do_masonry img.lazy_masonry").each(function() {
						var currentImg = jQuery(this);
						currentImg.parent("div.post_img_hover").removeClass("lazy");
						
						var cols = grid.attr('data-cols');
						
						if(!grid.hasClass('has_no_space'))
						{
							var gutter = 40;
							if(cols > 4)
							{
								gutter = 30;
							}
						}
						else
						{
							gutter = 0;
						}
						
						jQuery(this).Lazy({
							onFinishedAll: function() {
								grid.masonry({
									itemSelector: ".gallery_grid_item",
									columnWidth: ".gallery_grid_item",
									gutter : gutter
								});
					        },
						});
					});
				});
			});
		} );
		//End execute javascript for gallery masonry
		
		//Start execute javascript for gallery justified
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-gallery-justified.default', function( $scope ) {
			jQuery(function( $ ) {
				if(!is_touch_device())
				{
					var scaleTilt = 1.1;
					if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
						scaleTilt = 1;
					}
					jQuery(".gallery-grid-tilt").tilt({
					    scale: scaleTilt,
					    perspective: 2500
					});
				}
				
				jQuery("img.lazy").each(function() {
					var currentImg = jQuery(this);
					
					jQuery(this).Lazy({
						onFinishedAll: function() {
							currentImg.parent("div.post_img_hover").removeClass("lazy");
				        }
					});
				});
				
				jQuery(".gallery_grid_content_wrapper.do_justified").each(function() {
					var grid = jQuery(this);
					var rowHeight = grid.attr('data-row_height');
					var margin = grid.attr('data-margin');
					var justifyLastRow = grid.attr('data-justify_last_row');
					var justifyLastRowStr = 'nojustify';
					if(justifyLastRow == 'yes')
					{
						justifyLastRowStr = 'justify';
					}
					
					grid.imagesLoaded().always( function() {
						grid.justifiedGallery({
							rowHeight:  rowHeight,
							margins: margin,
							lastRow: justifyLastRowStr
						});
					});
				});
			});
		} );
		//End execute javascript for gallery justified
		
		//Start execute javascript for gallery horizontal
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-gallery-horizontal.default', function( $scope ) {
			jQuery(".tg_horizontal_gallery_wrapper").each(function() {
				var $carousel = jQuery(this);
				var timer = $carousel.attr('data-autoplay');
				if(timer == 0)
				{
					timer = false;
				}
				
				var loop = $carousel.attr('data-loop');
				var navigation = $carousel.attr('data-navigation');
				if(navigation == 0)
				{
					navigation = false;
				}
				
				var pagination = $carousel.attr('data-pagination');
				if(pagination == 0)
				{
					pagination = false;
				}
				
				$carousel.flickity({
					percentPosition: false,
					imagesLoaded: true,
					selectedAttraction: 0.01,
					friction: 0.2,
					lazyLoad: 5,
					pauseAutoPlayOnHover: true,
					autoPlay: parseInt(timer),
					contain: true,
					prevNextButtons: navigation,
					pageDots: pagination
				});
				
				var parallax = $carousel.attr('data-parallax');
				if(parallax == 1)
				{
					var $imgs = $carousel.find('.tg_horizontal_gallery_cell img');
	
					var docStyle = document.documentElement.style;
					var transformProp = typeof docStyle.transform == 'string' ?
					  'transform' : 'WebkitTransform';
	
					var flkty = $carousel.data('flickity');
					
					$carousel.on( 'scroll.flickity', function() {
					  flkty.slides.forEach( function( slide, i ) {
					    var img = $imgs[i];
					    var x = ( slide.target + flkty.x ) * -1/3;
					    img.style[ transformProp ] = 'translateX(' + x  + 'px)';
					  });
					});
				}
				
				var fullscreen = $carousel.attr('data-fullscreen');
				if(typeof fullscreen != 'undefined' && fullscreen != 0)
				{
					jQuery('body').addClass('elementor-fullscreen');
					
					//Get menu element height
					var menuHeight = parseInt(jQuery('#wrapper').css('paddingTop'));
					var documentHeight = jQuery(window).innerHeight();
					var sliderHeight = parseInt(documentHeight - menuHeight);
					
					$carousel.find('.tg_horizontal_gallery_cell').css('height', sliderHeight+'px');
					$carousel.find('.tg_horizontal_gallery_cell_img').css('height', sliderHeight+'px');
					$carousel.flickity('resize');
					
					jQuery( window ).resize(function() {
						var menuHeight = parseInt(jQuery('#wrapper').css('paddingTop'));
						var documentHeight = jQuery(window).innerHeight();
						var sliderHeight = parseInt(documentHeight - menuHeight);
						
						$carousel.find('.tg_horizontal_gallery_cell').css('height', sliderHeight+'px');
						$carousel.find('.tg_horizontal_gallery_cell_img').css('height', sliderHeight+'px');
						$carousel.flickity('resize');
					});
				}
			});
		} );
		//End execute javascript for gallery horizontal
		
		//Start execute javascript for slider horizontal
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-slider-horizontal.default', function( $scope ) {
			jQuery(".tg_horizontal_slider_wrapper").each(function() {
				var $carousel = jQuery(this);
				var timer = $carousel.attr('data-autoplay');
				if(timer == 0)
				{
					timer = false;
				}
				
				var loop = $carousel.attr('data-loop');
				var navigation = $carousel.attr('data-navigation');
				if(navigation == 0)
				{
					navigation = false;
				}
				
				var pagination = $carousel.attr('data-pagination');
				if(pagination == 0)
				{
					pagination = false;
				}
				
				$carousel.flickity({
					percentPosition: false,
					imagesLoaded: true,
					pauseAutoPlayOnHover: true,
					autoPlay: parseInt(timer),
					contain: true,
					prevNextButtons: navigation,
					pageDots: pagination
				});
				
				var fullscreen = $carousel.attr('data-fullscreen');
				if(fullscreen != 0)
				{
					jQuery('body').addClass('elementor-fullscreen');
					
					//Get menu element height
					var menuHeight = parseInt(jQuery('#wrapper').css('paddingTop'));
					var documentHeight = jQuery(window).innerHeight();
					var sliderHeight = parseInt(documentHeight - menuHeight);
					
					$carousel.find('.tg_horizontal_slider_cell').css('height', sliderHeight+'px');
					$carousel.flickity('resize');
					
					jQuery( window ).resize(function() {
						var menuHeight = parseInt(jQuery('#wrapper').css('paddingTop'));
						var documentHeight = jQuery(window).innerHeight();
						var sliderHeight = parseInt(documentHeight - menuHeight);
						
						$carousel.find('.tg_horizontal_slider_cell').css('height', sliderHeight+'px');
						$carousel.flickity('resize');
					});
				}
			});
		} );
		//End execute javascript for slider horizontal
		
		//Start execute javascript for album grid
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-album-grid.default', function( $scope ) {
			jQuery(function( $ ) {
				var tiltSettings = [
				{},
				{
					movement: {
						imgWrapper : {
							translation : {x: 10, y: 10, z: 30},
							rotation : {x: 0, y: -10, z: 0},
							reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
						},
						lines : {
							translation : {x: 10, y: 10, z: [0,70]},
							rotation : {x: 0, y: 0, z: -2},
							reverseAnimation : {duration : 2000, easing : 'easeOutExpo'}
						},
						caption : {
							rotation : {x: 0, y: 0, z: 2},
							reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
						},
						overlay : {
							translation : {x: 10, y: -10, z: 0},
							rotation : {x: 0, y: 0, z: 2},
							reverseAnimation : {duration : 2000, easing : 'easeOutExpo'}
						},
						shine : {
							translation : {x: 100, y: 100, z: 0},
							reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
						}
					}
				},
				{
					movement: {
						imgWrapper : {
							rotation : {x: -5, y: 10, z: 0},
							reverseAnimation : {duration : 900, easing : 'easeOutCubic'}
						},
						caption : {
							translation : {x: 30, y: 30, z: [0,40]},
							rotation : {x: [0,15], y: 0, z: 0},
							reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
						},
						overlay : {
							translation : {x: 10, y: 10, z: [0,20]},
							reverseAnimation : {duration : 1000, easing : 'easeOutExpo'}
						},
						shine : {
							translation : {x: 100, y: 100, z: 0},
							reverseAnimation : {duration : 900, easing : 'easeOutCubic'}
						}
					}
				},
				{
					movement: {
						imgWrapper : {
							rotation : {x: -5, y: 10, z: 0},
							reverseAnimation : {duration : 50, easing : 'easeOutQuad'}
						},
						caption : {
							translation : {x: 20, y: 20, z: 0},
							reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
						},
						overlay : {
							translation : {x: 5, y: -5, z: 0},
							rotation : {x: 0, y: 0, z: 6},
							reverseAnimation : {duration : 1000, easing : 'easeOutQuad'}
						},
						shine : {
							translation : {x: 50, y: 50, z: 0},
							reverseAnimation : {duration : 50, easing : 'easeOutQuad'}
						}
					}
				},
				{
					movement: {
						imgWrapper : {
							translation : {x: 0, y: -8, z: 0},
							rotation : {x: 3, y: 3, z: 0},
							reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
						},
						lines : {
							translation : {x: 15, y: 15, z: [0,15]},
							reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
						},
						overlay : {
							translation : {x: 0, y: 8, z: 0},
							reverseAnimation : {duration : 600, easing : 'easeOutExpo'}
						},
						caption : {
							translation : {x: 10, y: -15, z: 0},
							reverseAnimation : {duration : 900, easing : 'easeOutExpo'}
						},
						shine : {
							translation : {x: 50, y: 50, z: 0},
							reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
						}
					}
				},
				{
					movement: {
						lines : {
							translation : {x: -5, y: 5, z: 0},
							reverseAnimation : {duration : 1000, easing : 'easeOutExpo'}
						},
						caption : {
							translation : {x: 15, y: 15, z: 0},
							rotation : {x: 0, y: 0, z: 3},
							reverseAnimation : {duration : 1500, easing : 'easeOutElastic', elasticity : 700}
						},
						overlay : {
							translation : {x: 15, y: -15, z: 0},
							reverseAnimation : {duration : 500,easing : 'easeOutExpo'}
						},
						shine : {
							translation : {x: 50, y: 50, z: 0},
							reverseAnimation : {duration : 500, easing : 'easeOutExpo'}
						}
					}
				},
				{
					movement: {
						imgWrapper : {
							translation : {x: 5, y: 5, z: 0},
							reverseAnimation : {duration : 800, easing : 'easeOutQuart'}
						},
						caption : {
							translation : {x: 10, y: 10, z: [0,50]},
							reverseAnimation : {duration : 1000, easing : 'easeOutQuart'}
						},
						shine : {
							translation : {x: 50, y: 50, z: 0},
							reverseAnimation : {duration : 800, easing : 'easeOutQuart'}
						}
					}
				},
				{
					movement: {
						lines : {
							translation : {x: 40, y: 40, z: 0},
							reverseAnimation : {duration : 1500, easing : 'easeOutElastic'}
						},
						caption : {
							translation : {x: 20, y: 20, z: 0},
							rotation : {x: 0, y: 0, z: -5},
							reverseAnimation : {duration : 1000, easing : 'easeOutExpo'}
						},
						overlay : {
							translation : {x: -30, y: -30, z: 0},
							rotation : {x: 0, y: 0, z: 3},
							reverseAnimation : {duration : 750, easing : 'easeOutExpo'}
						},
						shine : {
							translation : {x: 100, y: 100, z: 0},
							reverseAnimation : {duration : 750, easing : 'easeOutExpo'}
						}
					}
				}];
	
				function init() {
					var idx = 0;
					[].slice.call(document.querySelectorAll('a.tilter')).forEach(function(el, pos) { 
						idx = pos%2 === 0 ? idx+1 : idx;
						new TiltFx(el, tiltSettings[idx-1]);
					});
				}
	
				init();
			});
		} );
		//End execute javascript for distortion grid
		
		//Start execute javascript for slider horizontal
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-distortion-grid.default', function( $scope ) {
			Array.from(document.querySelectorAll('.distortion_grid_item-img')).forEach((el) => {
				const imgs = Array.from(el.querySelectorAll('img'));
				new hoverEffect({
					parent: el,
					intensity: el.dataset.intensity || undefined,
					speedIn: el.dataset.speedin || undefined,
					speedOut: el.dataset.speedout || undefined,
					easing: el.dataset.easing || undefined,
					hover: el.dataset.hover || undefined,
					image1: imgs[0].getAttribute('src'),
					image2: imgs[1].getAttribute('src'),
					displacementImage: el.dataset.displacement
				});
			});
		} );
		//End execute javascript for distortion grid
		
		//Start execute javascript for slider property clip
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-slider-property-clip.default', function( $scope ) {
			jQuery(".tg_slider_property_clip_wrapper").each(function() {
				var slider = jQuery(this).find(".slider"),
					slides = slider.find('li'),
					nav = slider.find('nav');
			
				slides.eq(0).addClass('current');
			
				nav.children('a').eq(0).addClass('current_dot');
			
				nav.on('click', 'a', function(event) {
					event.preventDefault();
					$(this).addClass('current_dot').siblings().removeClass('current_dot');
					slides.eq($(this).index()).addClass('current').removeClass('prev').siblings().removeClass('current');
					slides.eq($(this).index()).prevAll().addClass('prev');
					slides.eq($(this).index()).nextAll().removeClass('prev');
				});
			});
		} );
		//End execute javascript for slider property clip
		
		//Start execute javascript for slider zoom
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-slider-zoom.default', function( $scope ) {
			
			jQuery(".slider_zoom_wrapper").each(function() {
				var sliderObj = jQuery(this);
				
				var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();
	
				function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
				
				var $window = jQuery(window);
				var $body = jQuery('body');
				
				var Slideshow = function () {
			  	function Slideshow() {
			    	var _this = this;
				
			    	var userOptions = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
				
			    	_classCallCheck(this, Slideshow);
				
					var timer = sliderObj.attr('data-autoplay');
					var autoplay = true;
					
					if(timer == 0)
					{
						timer = false;
						autoplay = false;
					}
					
					var pagination = sliderObj.attr('data-pagination');
					if(pagination == 0)
					{
						var pagination = false;
					}
					else
					{
						var pagination = true;
					}
				
			    	var defaultOptions = {
			      	$el: sliderObj,
			      	showArrows: false,
			      	showPagination: false,
			      	duration: timer,
			      	autoplay: autoplay
			    	};
				
			    	var options = Object.assign({}, defaultOptions, userOptions);
				
			    	this.$el = options.$el;
			    	this.maxSlide = this.$el.find($('.js-slider-home-slide')).length;
			    	this.showArrows = this.maxSlide > 1 ? options.showArrows : false;
			    	this.showPagination = pagination;
			    	this.currentSlide = 1;
			    	this.isAnimating = false;
			    	this.animationDuration = 1200;
			    	this.autoplaySpeed = options.duration;
			    	this.interval;
			    	this.$controls = this.$el.find('.js-slider-home-button');
			    	this.autoplay = this.maxSlide > 1 ? options.autoplay : false;
				
			    	this.$el.on('click', '.js-slider-home-next', function (event) {
			      	return _this.nextSlide();
			    	});
			    	this.$el.on('click', '.js-slider-home-prev', function (event) {
			      	return _this.prevSlide();
			    	});
			    	this.$el.on('click', '.js-pagination-item', function (event) {
			      	if (!_this.isAnimating) {
			        	_this.preventClick();
			        	_this.goToSlide(event.target.dataset.slide);
			      	}
			    	});
				
			    	this.init();
			  	}
				
			  	_createClass(Slideshow, [{
			    	key: 'init',
			    	value: function init() {
			      	this.goToSlide(1);
				
			      	if (this.autoplay) {
			        	this.startAutoplay();
			      	}
				
			      	if (this.showPagination) {
			        	var paginationNumber = this.maxSlide;
			        	var pagination = '<div class="pagination"><div class="container">';
				
			        	for (var i = 0; i < this.maxSlide; i++) {
			          	var item = '<span class="pagination__item js-pagination-item ' + (i === 0 ? 'is-current' : '') + '" data-slide=' + (i + 1) + '>' + (i + 1) + '</span>';
			          	pagination = pagination + item;
			        	}
				
			        	pagination = pagination + '</div></div>';
				
			        	this.$el.append(pagination);
			      	}
			    	}
			  	}, {
			    	key: 'preventClick',
			    	value: function preventClick() {
			      	var _this2 = this;
				
			      	this.isAnimating = true;
			      	this.$controls.prop('disabled', true);
			      	clearInterval(this.interval);
				
			      	setTimeout(function () {
			        	_this2.isAnimating = false;
			        	_this2.$controls.prop('disabled', false);
			        	if (_this2.autoplay) {
			          	_this2.startAutoplay();
			        	}
			      	}, this.animationDuration);
			    	}
			  	}, {
			    	key: 'goToSlide',
			    	value: function goToSlide(index) {
			      	this.currentSlide = parseInt(index);
				
			      	if (this.currentSlide > this.maxSlide) {
			        	this.currentSlide = 1;
			      	}
				
			      	if (this.currentSlide === 0) {
			        	this.currentSlide = this.maxSlide;
			      	}
				
			      	var newCurrent = this.$el.find('.js-slider-home-slide[data-slide="' + this.currentSlide + '"]');
			      	var newPrev = this.currentSlide === 1 ? this.$el.find('.js-slider-home-slide').last() : newCurrent.prev('.js-slider-home-slide');
			      	var newNext = this.currentSlide === this.maxSlide ? this.$el.find('.js-slider-home-slide').first() : newCurrent.next('.js-slider-home-slide');
				
			      	this.$el.find('.js-slider-home-slide').removeClass('is-prev is-next is-current');
			      	this.$el.find('.js-pagination-item').removeClass('is-current');
				
			      	if (this.maxSlide > 1) {
			        	newPrev.addClass('is-prev');
			        	newNext.addClass('is-next');
			      	}
				
			      	newCurrent.addClass('is-current');
			      	this.$el.find('.js-pagination-item[data-slide="' + this.currentSlide + '"]').addClass('is-current');
			    	}
			  	}, {
			    	key: 'nextSlide',
			    	value: function nextSlide() {
			      	this.preventClick();
			      	this.goToSlide(this.currentSlide + 1);
			    	}
			  	}, {
			    	key: 'prevSlide',
			    	value: function prevSlide() {
			      	this.preventClick();
			      	this.goToSlide(this.currentSlide - 1);
			    	}
			  	}, {
			    	key: 'startAutoplay',
			    	value: function startAutoplay() {
			      	var _this3 = this;
				
			      	this.interval = setInterval(function () {
			        	if (!_this3.isAnimating) {
			          	_this3.nextSlide();
			        	}
			      	}, this.autoplaySpeed);
			    	}
			  	}, {
			    	key: 'destroy',
			    	value: function destroy() {
			      	this.$el.off();
			    	}
			  	}]);
				
			  	return Slideshow;
				}();
				
				(function () {
			  	var loaded = false;
			  	var maxLoad = 3000;
				
			  	function load() {
			    	var options = {
			      	showPagination: true
			    	};
				
			    	var slideShow = new Slideshow(options);
			  	}
				
			  	function addLoadClass() {
			    	$body.addClass('is-loaded');
				
			    	setTimeout(function () {
			      	$body.addClass('is-animated');
			    	}, 600);
			  	}
				
			  	$window.on('load', function () {
			    	if (!loaded) {
			      	loaded = true;
			      	load();
			    	}
			  	});
				
			  	setTimeout(function () {
			    	if (!loaded) {
			      	loaded = true;
			      	load();
			    	}
			  	}, maxLoad);
				
			  	addLoadClass();
				})();
			});
		} );
		//End execute javascript for slider zoom
		
		//Start execute javascript for slider parallax
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-slider-parallax.default', function( $scope ) {
			
			jQuery(".slider_parallax_wrapper").each(function() {
				var slideshow=jQuery(this);
				var timer = slideshow.attr('data-autoplay');
				var autoplay = true;
				
				if(timer == 0)
				{
					timer = false;
					autoplay = false;
				}
				
				var pagination = slideshow.attr('data-pagination');
				if(pagination == 0)
				{
					var pagination = false;
				}
				else
				{
					var pagination = true;
				}
				
				var navigation = slideshow.attr('data-navigation');
				if(navigation == 0)
				{
					var navigation = false;
				}
				else
				{
					var navigation = true;
				}
				
				var slideshowDuration = timer;
				
				function slideshowSwitch(slideshow,index,auto){
				  if(slideshow.data('wait')) return;
				
				  var slides = slideshow.find('.slide');
				  var pages = slideshow.find('.pagination');
				  var activeSlide = slides.filter('.is-active');
				  var activeSlideImage = activeSlide.find('.image-container');
				  var newSlide = slides.eq(index);
				  var newSlideImage = newSlide.find('.image-container');
				  var newSlideContent = newSlide.find('.slide-content');
				  var newSlideElements=newSlide.find('.caption > *');
				  if(newSlide.is(activeSlide))return;
				
				  newSlide.addClass('is-new');
				  var timeout=slideshow.data('timeout');
				  clearTimeout(timeout);
				  slideshow.data('wait',true);
				  var transition=slideshow.attr('data-transition');
				  if(transition=='fade'){
					newSlide.css({
					  display:'block',
					  zIndex:2
					});
					newSlideImage.css({
					  opacity:0
					});
				
					TweenMax.to(newSlideImage,1,{
					  alpha:1,
					  onComplete:function(){
						newSlide.addClass('is-active').removeClass('is-new');
						activeSlide.removeClass('is-active');
						newSlide.css({display:'',zIndex:''});
						newSlideImage.css({opacity:''});
						slideshow.find('.pagination').trigger('check');
						slideshow.data('wait',false);
						if(auto){
						  timeout=setTimeout(function(){
							slideshowNext(slideshow,false,true);
						  },slideshowDuration);
						  slideshow.data('timeout',timeout);}}});
				  } else {
					if(newSlide.index()>activeSlide.index()){
					  var newSlideRight=0;
					  var newSlideLeft='auto';
					  var newSlideImageRight=-slideshow.width()/8;
					  var newSlideImageLeft='auto';
					  var newSlideImageToRight=0;
					  var newSlideImageToLeft='auto';
					  var newSlideContentLeft='auto';
					  var newSlideContentRight=0;
					  var activeSlideImageLeft=-slideshow.width()/4;
					} else {
					  var newSlideRight='';
					  var newSlideLeft=0;
					  var newSlideImageRight='auto';
					  var newSlideImageLeft=-slideshow.width()/8;
					  var newSlideImageToRight='';
					  var newSlideImageToLeft=0;
					  var newSlideContentLeft=0;
					  var newSlideContentRight='auto';
					  var activeSlideImageLeft=slideshow.width()/4;
					}
				
					newSlide.css({
					  display:'block',
					  width:0,
					  right:newSlideRight,
					  left:newSlideLeft
					  ,zIndex:2
					});
				
					newSlideImage.css({
					  width:slideshow.width(),
					  right:newSlideImageRight,
					  left:newSlideImageLeft
					});
				
					newSlideContent.css({
					  width:slideshow.width(),
					  left:newSlideContentLeft,
					  right:newSlideContentRight
					});
				
					activeSlideImage.css({
					  left:0
					});
				
					TweenMax.set(newSlideElements,{y:20,force3D:true});
					TweenMax.to(activeSlideImage,1,{
					  left:activeSlideImageLeft,
					  ease:Power3.easeInOut
					});
				
					TweenMax.to(newSlide,1,{
					  width:slideshow.width(),
					  ease:Power3.easeInOut
					});
				
					TweenMax.to(newSlideImage,1,{
					  right:newSlideImageToRight,
					  left:newSlideImageToLeft,
					  ease:Power3.easeInOut
					});
				
					TweenMax.staggerFromTo(newSlideElements,0.8,{alpha:0,y:60},{alpha:1,y:0,ease:Power3.easeOut,force3D:true,delay:0.6},0.1,function(){
					  newSlide.addClass('is-active').removeClass('is-new');
					  activeSlide.removeClass('is-active');
					  newSlide.css({
						display:'',
						width:'',
						left:'',
						zIndex:''
					  });
				
					  newSlideImage.css({
						width:'',
						right:'',
						left:''
					  });
				
					  newSlideContent.css({
						width:'',
						left:''
					  });
				
					  newSlideElements.css({
						opacity:'',
						transform:''
					  });
				
					  activeSlideImage.css({
						left:''
					  });
				
					  slideshow.find('.pagination').trigger('check');
					  slideshow.data('wait',false);
					  if(auto){
						timeout=setTimeout(function(){
						  slideshowNext(slideshow,false,true);
						},slideshowDuration);
						slideshow.data('timeout',timeout);
					  }
					});
				  }
				}
				
				function slideshowNext(slideshow,previous,auto){
				  var slides=slideshow.find('.slide');
				  var activeSlide=slides.filter('.is-active');
				  var newSlide=null;
				  if(previous){
					newSlide=activeSlide.prev('.slide');
					if(newSlide.length === 0) {
					  newSlide=slides.last();
					}
				  } else {
					newSlide=activeSlide.next('.slide');
					if(newSlide.length==0)
					  newSlide=slides.filter('.slide').first();
				  }
				
				  slideshowSwitch(slideshow,newSlide.index(),auto);
				}
				
				function homeSlideshowParallax(){
				  var scrollTop=jQuery(window).scrollTop();
				  if(scrollTop>windowHeight) return;
				  var inner=slideshow.find('.slideshow-inner');
				  var newHeight=windowHeight-(scrollTop/2);
				  var newTop=scrollTop*0.8;
				
				  inner.css({
					transform:'translateY('+newTop+'px)',height:newHeight
				  });
				}
				
				jQuery(document).ready(function() {
				 jQuery('.slider_parallax_wrapper .slide').addClass('is-loaded');
				
				 jQuery('.slider_parallax_wrapper .arrows .arrow').on('click',function(){
				  slideshowNext(jQuery(this).closest('.slider_parallax_wrapper'),jQuery(this).hasClass('prev'));
				});
				
				 jQuery('.slider_parallax_wrapper .pagination .item').on('click',function(){
				  slideshowSwitch(jQuery(this).closest('.slider_parallax_wrapper'),jQuery(this).index());
				});
				
				 jQuery('.slider_parallax_wrapper .pagination').on('check',function(){
				  var slideshow=jQuery(this).closest('.slider_parallax_wrapper');
				  var pages=jQuery(this).find('.item');
				  var index=slideshow.find('.slider_parallax_slides .is-active').index();
				  pages.removeClass('is-active');
				  pages.eq(index).addClass('is-active');
				});
				
				if(autoplay)
				{
					var timeout=setTimeout(function(){
					  slideshowNext(slideshow,false,true);
					},slideshowDuration);
					
					slideshow.data('timeout',timeout);
				}
				});
			});
		} );
		//End execute javascript for slider parallax
		
		
		//Start execute javascript for navigation menu
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-navigation-menu.default', function( $scope ) {
			jQuery('.tg_navigation_wrapper .nav li.menu-item').hover(function()
			{
				jQuery(this).children('ul:first').addClass('visible');
				jQuery(this).children('ul:first').addClass('hover');
			},
			function()
			{	
				jQuery(this).children('ul:first').removeClass('visible');
				jQuery(this).children('ul:first').removeClass('hover');
			});
			
			jQuery('.tg_navigation_wrapper .nav li.menu-item').children('ul:first.hover').hover(function()
			{
				jQuery(this).stop().addClass('visible');
			},
			function()
			{	
				jQuery(this).stop().removeClass('visible');
			});
		} );
		//End execute javascript for navigation menu
		
		//Start execute javascript for mouse driven vertical carousel
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-mouse-driven-vertical-carousel.default', function( $scope ) {
			
			class VerticalMouseDrivenCarousel {
				constructor(options = {}) {
					const _defaults = {
						carousel: ".tg_mouse_driven_vertical_carousel_wrapper .js-carousel",
						bgImg: ".js-carousel-bg-img",
						list: ".js-carousel-list",
						listItem: ".js-carousel-list-item"
					};
			
					this.posY = 0;
			
					this.defaults = Object.assign({}, _defaults, options);
			
					this.initCursor();
					this.init();
					this.bgImgController();
				}
			
				//region getters
				getBgImgs() {
					return document.querySelectorAll(this.defaults.bgImg);
				}
			
				getListItems() {
					return document.querySelectorAll(this.defaults.listItem);
				}
			
				getList() {
					return document.querySelector(this.defaults.list);
				}
			
				getCarousel() {
					return document.querySelector(this.defaults.carousel);
				}
			
				init() {
					TweenMax.set(this.getBgImgs(), {
						autoAlpha: 0,
						scale: 1.05
					});
			
					TweenMax.set(this.getBgImgs()[0], {
						autoAlpha: 1,
						scale: 1
					});
			
					this.listItems = this.getListItems().length - 1;
					
					this.listOpacityController(0);
				}
			
				initCursor() {
					//Init only on desktop device
					if(jQuery(window).width()>1024)
					{
						const listHeight = this.getList().clientHeight;
						const carouselHeight = this.getCarousel().clientHeight;
						const carouselPos = this.getCarousel().getBoundingClientRect();
						const carouselPosY = parseInt(carouselPos.top);
						
						this.getCarousel().addEventListener(
							"mousemove",
							event => {
								this.posY = parseInt(event.pageY - carouselPosY) - this.getCarousel().offsetTop;
								let offset = -this.posY / carouselHeight * listHeight;
				
								TweenMax.to(this.getList(), 0.3, {
									y: offset,
									ease: Power4.easeOut
								});
							},
							false
						);
					}
				}
			
				bgImgController() {
					for (const link of this.getListItems()) {
						link.addEventListener("mouseenter", ev => {
							let currentId = ev.currentTarget.dataset.itemId;
			
							this.listOpacityController(currentId);
			
							TweenMax.to(ev.currentTarget, 0.3, {
								autoAlpha: 1
							});
			
							TweenMax.to(".is-visible", 0.2, {
								autoAlpha: 0,
								scale: 1.05
							});
			
							if (!this.getBgImgs()[currentId].classList.contains("is-visible")) {
								this.getBgImgs()[currentId].classList.add("is-visible");
							}
			
							TweenMax.to(this.getBgImgs()[currentId], 0.6, {
								autoAlpha: 1,
								scale: 1
							});
						});
					}
				}
			
				listOpacityController(id) {
					id = parseInt(id);
					let aboveCurrent = this.listItems - id;
					let belowCurrent = parseInt(id);
			
					if (aboveCurrent > 0) {
						for (let i = 1; i <= aboveCurrent; i++) {
							let opacity = 0.5 / i;
							let offset = 5 * i;
							TweenMax.to(this.getListItems()[id + i], 0.5, {
								autoAlpha: opacity,
								x: offset,
								ease: Power3.easeOut
							});
						}
					}
			
					if (belowCurrent > 0) {
						for (let i = 0; i <= belowCurrent; i++) {
							let opacity = 0.5 / i;
							let offset = 5 * i;
							TweenMax.to(this.getListItems()[id - i], 0.5, {
								autoAlpha: opacity,
								x: offset,
								ease: Power3.easeOut
							});
						}
					}
				}
			}
			
			new VerticalMouseDrivenCarousel();
			
		} );
		//End execute javascript for mouse driven vertical carousel
		
		//Start execute javascript for synchronized carousel slider
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-slider-synchronized-carousel.default', function( $scope ) {
			
			jQuery(".tg_synchronized_carousel_slider_wrapper:not(.activated)").each(function() {
				var sliderID = jQuery(this).attr('id');
				var slidersContainer = document.querySelector("#"+sliderID);
				var countSlide = jQuery(this).attr('data-countslide');
				
				// Initializing the numbers slider
			    var msNumbers = new MomentumSlider({
			        el: slidersContainer,
			        cssClass: "ms--numbers",
			        range: [1, countSlide],
			        rangeContent: function (i) {
			            return "0" + i;
			        },
			        style: {
			            transform: [{scale: [0.4, 1]}],
			            opacity: [0, 1]
			        },
			        interactive: false
			    });
			    
			    var titles = JSON.parse(jQuery(this).attr('data-slidetitles'));
			    
			    var msTitles = new MomentumSlider({
			        el: slidersContainer,
			        cssClass: "ms--titles",
			        range: [0, parseInt(countSlide-1)],
			        rangeContent: function (i) {
			            return "<h3>"+ titles[i] +"</h3>";
			        },
			        vertical: true,
			        reverse: true,
			        style: {
			            opacity: [0, 1]
			        },
			        interactive: false
			    });
			    
			    var buttonTitles = JSON.parse(jQuery(this).attr('data-slidebuttontitles'));
			    var buttonUrls = JSON.parse(jQuery(this).attr('data-slidebuttonurls'));
			    
			    // Initializing the links slider
			    var msLinks = new MomentumSlider({
			        el: slidersContainer,
			        cssClass: "ms--links",
			        range: [0, parseInt(countSlide-1)],
			        rangeContent: function (i) {
			            return "<a href=\""+buttonUrls[i]+"\" class=\"ms-slide__link\">"+buttonTitles[i]+"</a>";
			        },
			        vertical: true,
			        interactive: false
			    });
			    
			    // Get pagination items
			    var paginationID = jQuery(this).attr('data-pagination');
			    var pagination = document.querySelector("#"+paginationID);
			    var paginationItems = [].slice.call(pagination.children);
			    
			    var images = JSON.parse(jQuery(this).attr('data-slideimages'));
			    
			    // Initializing the images slider
			    var msImages = new MomentumSlider({
			        // Element to append the slider
			        el: slidersContainer,
			        // CSS class to reference the slider
			        cssClass: "ms--images",
			        // Generate the 4 slides required
			        range: [0, parseInt(countSlide-1)],
			        rangeContent: function (i) {
			            return "<div class=\"ms-slide__image-container\"><div class=\"ms-slide__image\" style=\"background-image: url('"+images[i]+"')\"></div></div>";
			        },
			        // Syncronize the other sliders
			        sync: [msNumbers, msTitles, msLinks],
			        // Styles to interpolate as we move the slider
			        style: {
			            ".ms-slide__image": {
			                transform: [{scale: [1.5, 1]}]
			            }
			        },
			        // Update pagination if slider change
			        change: function(newIndex, oldIndex) {
			            if (typeof oldIndex !== "undefined") {
			                paginationItems[oldIndex].classList.remove("pagination__item--active");
			            }
			            paginationItems[newIndex].classList.add("pagination__item--active");
			        }
			    });
			    
			    pagination.addEventListener("click", function(e) {
			        if (e.target.matches(".pagination__button")) {
			            var index = paginationItems.indexOf(e.target.parentNode);
			            msImages.select(index);
			        }
			    });
				
				jQuery(this).addClass('activated');
			});
			
		} );
		//End execute javascript for synchronized carousel slider
		
		//Start execute javascript for flip box
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-flip-box.default', function( $scope ) {
			
			var countSquare = jQuery('.square').length;
		
			//For each Square found add BG image
			for (i = 0; i < countSquare; i++) {
			    var firstImage = jQuery('.square').eq([i]);
			    var secondImage = jQuery('.square2').eq([i]);
			
			    var getImage = firstImage.attr('data-image');
			    var getImage2 = secondImage.attr('data-image');
			
			    firstImage.css('background-image', 'url(' + getImage + ')');
			    secondImage.css('background-image', 'url(' + getImage2 + ')');
			}
			
			jQuery('.tg_flip_box_wrapper').on('click', function() {
				 jQuery(this).trigger("mouseover");
			});

			
		} );
		//End execute javascript for flip box
		
		//Start execute javascript for animated text
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-animated-text.default', function( $scope ) {
			
			jQuery(".themegoods-animated-text").each(function() {
				var textContent = jQuery(this).first();
				var delimiterTypeOri = jQuery(this).attr('data-delimiter');
				var delimiterType = jQuery(this).attr('data-delimiter');
				var transitionSpeed = parseInt(jQuery(this).attr('data-transition'));
				var transitionDelay = parseInt(jQuery(this).attr('data-transition-delay'));
				var transitionDuration = parseInt(jQuery(this).attr('data-transition-duration'));
				
				if(delimiterType == 'sentence') {
					delimiterType = 'word'
				}
				
				var animatedText = textContent.blast({
				    delimiter: delimiterType,
				    aria: false,
				});
				
				//If overflow hidden
				if(jQuery(this).hasClass('overflow-hidden')){
					animatedText.each(function(i) {
						var textEachSpan = jQuery(this);
						var initialText = textEachSpan.text();
						//console.log(textEachSpan.html());
						textEachSpan.html('<span>'+initialText+'</span>');
						
						//console.log(initialText);
					});
				}
				
				if(textContent.isInViewport()) {
					animatedText.each(function(i) {
						var delaySpeed = parseInt(transitionDelay + (i * transitionSpeed));
						//console.log(delimiterType);
						if(delimiterTypeOri == 'sentence') {
							delaySpeed = parseInt(transitionDelay + transitionSpeed);
						}
						
					  	jQuery(this).queue(function (next) { 
							jQuery(this).css({'transition-delay': delaySpeed+'ms', 'transition-duration': transitionDuration+'ms', 'transform': 'translateX(0px) translateY(0px) translateZ(0px)', 'opacity': 1});  
						})
					});
				}
				
				jQuery(window).on('resize scroll', function() {
					if(textContent.isInViewport()) {
						animatedText.each(function(i) {
							var delaySpeed = parseInt(transitionDelay + (i * transitionSpeed));
							if(delimiterTypeOri == 'sentence') {
								delaySpeed = parseInt(transitionDelay + transitionSpeed);
							}
							
						  	jQuery(this).queue(function (next) { 
								jQuery(this).css({'transition-delay': delaySpeed+'ms', 'transition-duration': transitionDuration+'ms', 'transform': 'translateX(0px) translateY(0px) translateZ(0px)', 'opacity': 1});  
							})
						});
					}
				});
			});
			
		} );
		//End execute javascript for animated text
		
		//Start execute javascript for animated headline
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-animated-headline.default', function( $scope ) {
			
			jQuery(".themegoods-animated-headline").each(function() {
				var animationType = jQuery(this).attr('data-animation');

				jQuery(this).animatedHeadline({
					animationType: animationType
				});
			});
			
		} );
		//End execute javascript for animated headline
		
		//Start execute javascript for background menu effect
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-background-menu-effect.default', function( $scope ) {
			
			jQuery(".themegoods-background-menu-wrapper").each(function() {
				var menuList = jQuery(this).find('.themegoods-background-menu li.themegoods-background-menu__item a');
				
				menuList.each(function() {
					jQuery(this).mouseenter(function() {
						
						jQuery(this).parent('li').addClass('mouseover');
						jQuery(this).parent('li').removeClass('mouseleave');
						
					}).mouseleave(function() {
						
						jQuery(this).parent('li').addClass('mouseleave');
						jQuery(this).parent('li').removeClass('mouseover');
					});
				});
			});
			
		} );
		//End execute javascript for background menu effect
		
		//Start execute javascript for service grid
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-service-grid.default', function( $scope ) {
			
			jQuery(".service-grid-wrapper").mouseover(function() {
				
				var dataHoverY = jQuery(this).attr('data-hoverY');
			    jQuery(this).find('.header-wrap').css('transform', 'translateY(-'+dataHoverY+'px)');
			    
			}).mouseleave(function() {
				
			    jQuery(this).find('.header-wrap').css('transform', 'translateY(0px)');
			});
			
			jQuery(".service-grid-wrapper").each(function() {
			    var hoverContent = jQuery(this).find('.hover-content');
			    var hoverMoveY = parseInt(hoverContent.height()-20);
			    jQuery(this).attr('data-hoverY', hoverMoveY);
			});
			
			jQuery(window).resize(function() {
				jQuery(".service-grid-wrapper").each(function() {
				    var hoverContent = jQuery(this).find('.hover-content');
				    var hoverMoveY = parseInt(hoverContent.height()-20);
				    jQuery(this).attr('data-hoverY', hoverMoveY);
				});
			});
			
		} );
		//End execute javascript for service grid
		
		//Start execute javascript for service carousel
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-service-carousel.default', function( $scope ) {
			jQuery(".service-carousel-wrapper .owl-carousel").each(function() {
				var autoPlay = jQuery(this).attr('data-autoplay');
				if (typeof autoPlay == "undefined"){
					autoPlay = false;
				}
				if(autoPlay == 1)
				{
					autoPlay = true;
				}
				else
				{
					autoPlay = false;
				}
				
				var timer = parseInt(jQuery(this).attr('data-timer'));
				if (typeof timer == "undefined"){
					timer = 8000;
				}
				
				var slidePadding = parseInt(jQuery(this).attr('data-stage-padding'));
				if (typeof slidePadding == "undefined"){
					slidePadding = 70;
				}
				
				var slideMargin = parseInt(jQuery(this).attr('data-margin'));
				if (typeof slideMargin == "undefined"){
					slideMargin = 40;
				}
				
				var items = parseInt(jQuery(this).attr('data-items'));
				if (typeof items == "undefined"){
					items = 4;
				}
				
				var pagination = jQuery(this).attr('data-pagination');
				if (typeof pagination == "undefined"){
					pagination = true;
				}
				if(pagination == 1)
				{
					pagination = true;
				}
				else
				{
					pagination = false;
				}
				//console.log(pagination);
				var serviceCarousel = jQuery(this).owlCarousel({
					stagePadding: parseInt(slidePadding),
					loop: false,
					center: false,
					items: parseInt(items),
					margin: parseInt(slideMargin),
					autoHeight : true,
					autoplay: autoPlay,
					dots: pagination,
					autoplayTimeout: timer,
					smartSpeed: 450,
					responsive: {
					  0: {
						items: 1
					  },
					  768: {
						items: 2
					  },
					  1170: {
						items: parseInt(items)
					  },
					  1600: {
						items: parseInt(items+1)
					  },
					  2000: {
						items: parseInt(items+2)
					  },
					  2400: {
						items: parseInt(items+3)
					  },
					}
				});
				
				setTimeout(function(){
					serviceCarousel.trigger('refresh.owl.carousel');
				}, 1000);
			});
		});
		//End execute javascript for service carousel
		
		//Start execute javascript for testimonials carousel
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-testimonial-carousel.default', function( $scope ) {
			jQuery(".testimonials-carousel-wrapper .owl-carousel").each(function() {
				var autoPlay = jQuery(this).attr('data-autoplay');
				if (typeof autoPlay == "undefined"){
					autoPlay = false;
				}
				if(autoPlay == 1)
				{
					autoPlay = true;
				}
				else
				{
					autoPlay = false;
				}
				
				var timer = jQuery(this).attr('data-timer');
				if (typeof timer == "undefined"){
					timer = 8000;
				}
				
				var pagination = jQuery(this).attr('data-pagination');
				if (typeof pagination == "undefined"){
					pagination = true;
				}
				if(pagination == 1)
				{
					pagination = true;
				}
				else
				{
					pagination = false;
				}
				
				jQuery(this).owlCarousel({
		            loop: false,
		            center: true,
		            items: 3,
		            margin: 0,
		            autoplay: autoPlay,
		            dots:pagination,
		            autoplayTimeout: timer,
		            smartSpeed: 450,
		            responsive: {
		              0: {
		                items: 1
		              },
		              768: {
		                items: 2
		              },
		              1170: {
		                items: 3
		              }
		            }
		        });
			});
		});
		//End execute javascript for testimonials carousel
		
		//Start execute javascript for accommodation carousel
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-accommodation-carousel.default', function( $scope ) {
			jQuery(".accommodation-carousel-wrapper .owl-carousel").each(function() {
				var autoPlay = jQuery(this).attr('data-autoplay');
				if (typeof autoPlay == "undefined"){
					autoPlay = false;
				}
				if(autoPlay == 1)
				{
					autoPlay = true;
				}
				else
				{
					autoPlay = false;
				}
				
				var timer = jQuery(this).attr('data-timer');
				if (typeof timer == "undefined"){
					timer = 8000;
				}
				
				var pagination = jQuery(this).attr('data-pagination');
				if (typeof pagination == "undefined"){
					pagination = true;
				}
				if(pagination == 1)
				{
					pagination = true;
				}
				else
				{
					pagination = false;
				}
				
				var items = jQuery(this).attr('data-items');
				if (typeof pagination == "undefined"){
					items = 2;
				}
				
				//console.log(pagination);
				jQuery(this).owlCarousel({
					stagePadding: 70,
		            loop: false,
		            center: false,
		            items: parseInt(items),
		            margin: 40,
		            autoplay: autoPlay,
		            dots:pagination,
		            autoplayTimeout: timer,
		            smartSpeed: 450,
		            responsive: {
		              0: {
		                items: 1
		              },
		              768: {
		                items: 2
		              },
		              1170: {
		                items: parseInt(items)
		              }
		            }
		        });
			});
		});
		//End execute javascript for accommodation carousel
		
		//Start execute javascript for Elementor image
		elementorFrontend.hooks.addAction( 'frontend/element_ready/image.default', function( $scope ) {
			
			if(elementorFrontend.isEditMode())
			{
				var elementSettings = {};
				var modelCID 		= $scope.data( 'model-cid' );
					
				var settings 		= elementorFrontend.config.elements.data[ modelCID ];
				if(typeof settings != 'undefined')
				{
					var type 			= settings.attributes.widgetType || settings.attributes.elType,
						settingsKeys 	= elementorFrontend.config.elements.keys[ type ];
			
					if ( ! settingsKeys ) {
						settingsKeys = elementorFrontend.config.elements.keys[type] = [];
			
						jQuery.each( settings.controls, function ( name, control ) {
							if ( control.frontend_available ) {
								settingsKeys.push( name );
							}
						});
					}
			
					jQuery.each( settings.getActiveControls(), function( controlKey ) {
						if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
							elementSettings[ controlKey ] = settings.attributes[ controlKey ];
						}
					} );
		
					var widgetExt = elementSettings;
				}
			}
			else
			{
				//Get widget settings data
				var widgetExtObj = $scope.attr('data-settings');
				
				if(typeof widgetExtObj != 'undefined')
				{
					var widgetExt = JSON.parse(widgetExtObj);
				}
			}
			
			if(typeof widgetExt != 'undefined')
			{
				//Begin background image parallax scrolling
				if(widgetExt.hoteller_image_is_animation == 'true')
				{
					$scope.addClass('themegoods-image-animation-'+widgetExt.hoteller_image_animation_effect);
					
					$scope.smoove({
						offset : '30%'
					});
				}
			}
		} );
		//End execute javascript for Elementor image
		
		//Start execute javascript for testimonials slider
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-testimonial-slider.default', function( $scope ) {
			jQuery(".testimonials-slider-wrapper .owl-carousel").each(function() {
				var autoPlay = jQuery(this).attr('data-autoplay');
				if (typeof autoPlay == "undefined"){
					autoPlay = false;
				}
				if(autoPlay == 1)
				{
					autoPlay = true;
				}
				else
				{
					autoPlay = false;
				}
				
				var timer = jQuery(this).attr('data-timer');
				if (typeof timer == "undefined"){
					timer = 8000;
				}
				
				jQuery(this).owlCarousel({
					loop: false,
					center: true,
					margin: 0,
					nav:true,
					autoplay: autoPlay,
					autoplayTimeout: timer,
					smartSpeed: 300,
					navText: [ '<span class="arrow-left"></span>', '<span class="arrow-right"></span>' ],
					responsive:{
						0:{
							items:1
						},
						600:{
							items:1
						},
						800:{
							items:1
						},
						1024:{
							items:1
						}
					}
				});
			});
		});
		//End execute javascript for testimonials slider
		
		//Start execute javascript for search
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-search.default', function( $scope ) {
			
			jQuery(".hoteller-search-icon").each(function() {
				var iconInput = jQuery(this).find('a');
				
				iconInput.bind('click', function () {
					var openDiv = jQuery(this).attr('data-open');
					jQuery('#'+openDiv).addClass('active');
					
					var isTouch = ('ontouchstart' in document.documentElement);
					if ( isTouch ) {
						jQuery('#'+openDiv).find('.hoteller-search-inner').addClass('touch');
					}
					
					jQuery('body').addClass('elementor-no-overflow');
					jQuery('body').addClass('elementor-search-activate');
					
					setTimeout(function(){ 
						jQuery('#'+openDiv).find('.tg_search_form.autocomplete_form').find('input#s').trigger('focus');
					}, 300);
				});
			});
			
			jQuery(".tg_search_form.autocomplete_form").each(function() {
				var wrapper = jQuery(this).attr('data-open');
				var formInput = jQuery(this).find('input[name="s"]');
				var resultDiv = jQuery(this).attr('data-result');
				var isTouch = ('ontouchstart' in document.documentElement);
		
				if ( !isTouch ) {
					formInput.on('input', function() {
						if(jQuery(this).val().length > 1)
						{
							jQuery.ajax({
								url: tgAjax.ajaxurl,
								type:'POST',
								data:'action=hoteller_ajax_search_result&'+jQuery(this).serialize(),
								success:function(results) {
									jQuery("#"+resultDiv).html(results);
									
									if(results != '')
									{
										jQuery("#"+resultDiv).addClass('visible');
										jQuery("#"+resultDiv).show();
										jQuery("#"+resultDiv).css('z-index', 99);
										
										jQuery("#"+resultDiv+ " ul li a").mousedown(function() {
											jQuery("#"+resultDiv).addClass('visible');
											jQuery("#"+resultDiv).attr('data-mousedown', 'true');
											
											location.href = jQuery(this).attr('href');
										});
									}
									else
									{
										jQuery("#"+resultDiv).hide();
									}
								}
							});
						}
						else
						{
							jQuery("#"+resultDiv).html('');
						}
					});
				}
				
				formInput.bind('click', function () {
					jQuery("#"+resultDiv).addClass('visible');
					jQuery("#"+resultDiv).attr('data-mousedown', 'true');
					
					/*var isTouch = ('ontouchstart' in document.documentElement);
					if ( isTouch ) {*/
						//jQuery('#'+wrapper).addClass('touch');
					//}
				});
				
				formInput.bind('focus', function () {
					jQuery("#"+resultDiv).addClass('visible');
					formInput.addClass('visible');
				});
				
				formInput.bind('blur', function () {
					jQuery("#"+resultDiv).removeClass('visible');
					formInput.removeClass('visible');
				});
				
				jQuery("#"+wrapper).bind('click', function () {
					if(!formInput.hasClass('visible'))
					{
						if(jQuery("#"+resultDiv).attr('data-mousedown') != 'true')
						{
							jQuery("#"+resultDiv).removeClass('visible');
						}
						jQuery('#'+wrapper).removeClass('active');
						jQuery('body').removeClass('elementor-no-overflow');
						jQuery('body').removeClass('elementor-search-activate');
					}
				});
			});
			
		} );
		//End execute javascript for search
		
		//Start execute javascript for portfolio timeline
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hoteller-portfolio-timeline.default', function( $scope ) {
			var timelines = jQuery('.cd-horizontal-timeline'),
			eventsMinDistance = 120;
		
		(timelines.length > 0) && initTimeline(timelines);
		
		function initTimeline(timelines) {
			timelines.each(function(){
				var timeline = jQuery(this),
					timelineComponents = {};
				//cache timeline components 
				timelineComponents['timelineWrapper'] = timeline.find('.events-wrapper');
				timelineComponents['eventsWrapper'] = timelineComponents['timelineWrapper'].children('.events');
				timelineComponents['fillingLine'] = timelineComponents['eventsWrapper'].children('.filling-line');
				timelineComponents['timelineEvents'] = timelineComponents['eventsWrapper'].find('a');
				timelineComponents['timelineDates'] = parseDate(timelineComponents['timelineEvents']);
				timelineComponents['eventsMinLapse'] = minLapse(timelineComponents['timelineDates']);
				timelineComponents['timelineNavigation'] = timeline.find('.cd-timeline-navigation');
				timelineComponents['eventsContent'] = timeline.children('.events-content');
		
				//assign a left postion to the single events along the timeline
				setDatePosition(timelineComponents, eventsMinDistance);
				//assign a width to the timeline
				var timelineTotWidth = setTimelineWidth(timelineComponents, eventsMinDistance);
				//the timeline has been initialize - show it
				timeline.addClass('loaded');
		
				//detect click on the next arrow
				timelineComponents['timelineNavigation'].on('click', '.next', function(event){
					event.preventDefault();
					updateSlide(timelineComponents, timelineTotWidth, 'next');
				});
				//detect click on the prev arrow
				timelineComponents['timelineNavigation'].on('click', '.prev', function(event){
					event.preventDefault();
					updateSlide(timelineComponents, timelineTotWidth, 'prev');
				});
				//detect click on the a single event - show new event content
				timelineComponents['eventsWrapper'].on('click', 'a', function(event){
					event.preventDefault();
					timelineComponents['timelineEvents'].removeClass('selected');
					jQuery(this).addClass('selected');
					updateOlderEvents(jQuery(this));
					updateFilling(jQuery(this), timelineComponents['fillingLine'], timelineTotWidth);
					updateVisibleContent(jQuery(this), timelineComponents['eventsContent']);
				});
		
				//on swipe, show next/prev event content
				timelineComponents['eventsContent'].on('swipeleft', function(){
					var mq = checkMQ();
					( mq == 'mobile' ) && showNewContent(timelineComponents, timelineTotWidth, 'next');
				});
				timelineComponents['eventsContent'].on('swiperight', function(){
					var mq = checkMQ();
					( mq == 'mobile' ) && showNewContent(timelineComponents, timelineTotWidth, 'prev');
				});
		
				//keyboard navigation
				jQuery(document).keyup(function(event){
					if(event.which=='37' && elementInViewport(timeline.get(0)) ) {
						showNewContent(timelineComponents, timelineTotWidth, 'prev');
					} else if( event.which=='39' && elementInViewport(timeline.get(0))) {
						showNewContent(timelineComponents, timelineTotWidth, 'next');
					}
				});
			});
		}
		
		function updateSlide(timelineComponents, timelineTotWidth, string) {
			//retrieve translateX value of timelineComponents['eventsWrapper']
			var translateValue = getTranslateValue(timelineComponents['eventsWrapper']),
				wrapperWidth = Number(timelineComponents['timelineWrapper'].css('width').replace('px', ''));
			//translate the timeline to the left('next')/right('prev') 
			(string == 'next') 
				? translateTimeline(timelineComponents, translateValue - wrapperWidth + eventsMinDistance, wrapperWidth - timelineTotWidth)
				: translateTimeline(timelineComponents, translateValue + wrapperWidth - eventsMinDistance);
		}
		
		function showNewContent(timelineComponents, timelineTotWidth, string) {
			//go from one event to the next/previous one
			var visibleContent =  timelineComponents['eventsContent'].find('.selected'),
				newContent = ( string == 'next' ) ? visibleContent.next() : visibleContent.prev();
		
			if ( newContent.length > 0 ) { //if there's a next/prev event - show it
				var selectedDate = timelineComponents['eventsWrapper'].find('.selected'),
					newEvent = ( string == 'next' ) ? selectedDate.parent('li').next('li').children('a') : selectedDate.parent('li').prev('li').children('a');
				
				updateFilling(newEvent, timelineComponents['fillingLine'], timelineTotWidth);
				updateVisibleContent(newEvent, timelineComponents['eventsContent']);
				newEvent.addClass('selected');
				selectedDate.removeClass('selected');
				updateOlderEvents(newEvent);
				updateTimelinePosition(string, newEvent, timelineComponents, timelineTotWidth);
			}
		}
		
		function updateTimelinePosition(string, event, timelineComponents, timelineTotWidth) {
			//translate timeline to the left/right according to the position of the selected event
			var eventStyle = window.getComputedStyle(event.get(0), null),
				eventLeft = Number(eventStyle.getPropertyValue("left").replace('px', '')),
				timelineWidth = Number(timelineComponents['timelineWrapper'].css('width').replace('px', '')),
				timelineTotWidth = Number(timelineComponents['eventsWrapper'].css('width').replace('px', ''));
			var timelineTranslate = getTranslateValue(timelineComponents['eventsWrapper']);
		
			if( (string == 'next' && eventLeft > timelineWidth - timelineTranslate) || (string == 'prev' && eventLeft < - timelineTranslate) ) {
				translateTimeline(timelineComponents, - eventLeft + timelineWidth/2, timelineWidth - timelineTotWidth);
			}
		}
		
		function translateTimeline(timelineComponents, value, totWidth) {
			var eventsWrapper = timelineComponents['eventsWrapper'].get(0);
			value = (value > 0) ? 0 : value; //only negative translate value
			value = ( !(typeof totWidth === 'undefined') &&  value < totWidth ) ? totWidth : value; //do not translate more than timeline width
			setTransformValue(eventsWrapper, 'translateX', value+'px');
			//update navigation arrows visibility
			(value == 0 ) ? timelineComponents['timelineNavigation'].find('.prev').addClass('inactive') : timelineComponents['timelineNavigation'].find('.prev').removeClass('inactive');
			(value == totWidth ) ? timelineComponents['timelineNavigation'].find('.next').addClass('inactive') : timelineComponents['timelineNavigation'].find('.next').removeClass('inactive');
		}
		
		function updateFilling(selectedEvent, filling, totWidth) {
			//change .filling-line length according to the selected event
			var eventStyle = window.getComputedStyle(selectedEvent.get(0), null),
				eventLeft = eventStyle.getPropertyValue("left"),
				eventWidth = eventStyle.getPropertyValue("width");
			eventLeft = Number(eventLeft.replace('px', '')) + Number(eventWidth.replace('px', ''))/2;
			var scaleValue = eventLeft/totWidth;
			setTransformValue(filling.get(0), 'scaleX', scaleValue);
		}
		
		function setDatePosition(timelineComponents, min) {
			for (i = 0; i < timelineComponents['timelineDates'].length; i++) { 
				var distance = daydiff(timelineComponents['timelineDates'][0], timelineComponents['timelineDates'][i]),
					distanceNorm = Math.round(distance/timelineComponents['eventsMinLapse']) + 2;
				timelineComponents['timelineEvents'].eq(i).css('left', distanceNorm*min+'px');
			}
		}
		
		function setTimelineWidth(timelineComponents, width) {
			var timeSpan = daydiff(timelineComponents['timelineDates'][0], timelineComponents['timelineDates'][timelineComponents['timelineDates'].length-1]),
				timeSpanNorm = timeSpan/timelineComponents['eventsMinLapse'],
				timeSpanNorm = Math.round(timeSpanNorm) + 4,
				totalWidth = timeSpanNorm*width;
			timelineComponents['eventsWrapper'].css('width', totalWidth+'px');
			updateFilling(timelineComponents['timelineEvents'].eq(0), timelineComponents['fillingLine'], totalWidth);
		
			return totalWidth;
		}
		
		function updateVisibleContent(event, eventsContent) {
			var eventDate = event.data('date'),
				visibleContent = eventsContent.find('.selected'),
				selectedContent = eventsContent.find('[data-date="'+ eventDate +'"]'),
				selectedContentHeight = selectedContent.height();
		
			if (selectedContent.index() > visibleContent.index()) {
				var classEnetering = 'selected enter-right',
					classLeaving = 'leave-left';
			} else {
				var classEnetering = 'selected enter-left',
					classLeaving = 'leave-right';
			}
		
			selectedContent.attr('class', classEnetering);
			visibleContent.attr('class', classLeaving).one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(){
				visibleContent.removeClass('leave-right leave-left');
				selectedContent.removeClass('enter-left enter-right');
			});
			eventsContent.css('height', selectedContentHeight+'px');
		}
		
		function updateOlderEvents(event) {
			event.parent('li').prevAll('li').children('a').addClass('older-event').end().end().nextAll('li').children('a').removeClass('older-event');
		}
		
		function getTranslateValue(timeline) {
			var timelineStyle = window.getComputedStyle(timeline.get(0), null),
				timelineTranslate = timelineStyle.getPropertyValue("-webkit-transform") ||
					 timelineStyle.getPropertyValue("-moz-transform") ||
					 timelineStyle.getPropertyValue("-ms-transform") ||
					 timelineStyle.getPropertyValue("-o-transform") ||
					 timelineStyle.getPropertyValue("transform");
		
			if( timelineTranslate.indexOf('(') >=0 ) {
				var timelineTranslate = timelineTranslate.split('(')[1];
				timelineTranslate = timelineTranslate.split(')')[0];
				timelineTranslate = timelineTranslate.split(',');
				var translateValue = timelineTranslate[4];
			} else {
				var translateValue = 0;
			}
		
			return Number(translateValue);
		}
		
		function setTransformValue(element, property, value) {
			element.style["-webkit-transform"] = property+"("+value+")";
			element.style["-moz-transform"] = property+"("+value+")";
			element.style["-ms-transform"] = property+"("+value+")";
			element.style["-o-transform"] = property+"("+value+")";
			element.style["transform"] = property+"("+value+")";
		}
		
		//based on http://stackoverflow.com/questions/542938/how-do-i-get-the-number-of-days-between-two-dates-in-javascript
		function parseDate(events) {
			var dateArrays = [];
			events.each(function(){
				var dateComp = jQuery(this).data('date').split('/'),
					newDate = new Date(dateComp[2], dateComp[1]-1, dateComp[0]);
				dateArrays.push(newDate);
			});
			return dateArrays;
		}
		
		function parseDate2(events) {
			var dateArrays = [];
			events.each(function(){
				var singleDate = jQuery(this),
					dateComp = singleDate.data('date').split('T');
				if( dateComp.length > 1 ) { //both DD/MM/YEAR and time are provided
					var dayComp = dateComp[0].split('/'),
						timeComp = dateComp[1].split(':');
				} else if( dateComp[0].indexOf(':') >=0 ) { //only time is provide
					var dayComp = ["2000", "0", "0"],
						timeComp = dateComp[0].split(':');
				} else { //only DD/MM/YEAR
					var dayComp = dateComp[0].split('/'),
						timeComp = ["0", "0"];
				}
				var	newDate = new Date(dayComp[2], dayComp[1]-1, dayComp[0], timeComp[0], timeComp[1]);
				dateArrays.push(newDate);
			});
			return dateArrays;
		}
		
		function daydiff(first, second) {
			return Math.round((second-first));
		}
		
		function minLapse(dates) {
			//determine the minimum distance among events
			var dateDistances = [];
			for (i = 1; i < dates.length; i++) { 
				var distance = daydiff(dates[i-1], dates[i]);
				dateDistances.push(distance);
			}
			return Math.min.apply(null, dateDistances);
		}
		
		/*
			How to tell if a DOM element is visible in the current viewport?
			http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
		*/
		function elementInViewport(el) {
			var top = el.offsetTop;
			var left = el.offsetLeft;
			var width = el.offsetWidth;
			var height = el.offsetHeight;
		
			while(el.offsetParent) {
				el = el.offsetParent;
				top += el.offsetTop;
				left += el.offsetLeft;
			}
		
			return (
				top < (window.pageYOffset + window.innerHeight) &&
				left < (window.pageXOffset + window.innerWidth) &&
				(top + height) > window.pageYOffset &&
				(left + width) > window.pageXOffset
			);
		}
		
		function checkMQ() {
			//check if mobile or desktop device
			return window.getComputedStyle(document.querySelector('.cd-horizontal-timeline'), '::before').getPropertyValue('content').replace(/'/g, "").replace(/"/g, "");
		}
		} );
		//End execute javascript for portfolio timeline vertical
		
	} );
	
} )( jQuery );