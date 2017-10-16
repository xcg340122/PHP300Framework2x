jQuery(document).ready(function($) {
   'use strict';

	// var windowHeight = $(window).height();
	// var windowWidth = $(window).width();

	// Nav
	$('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top
	        }, 500);
	        return false;
	      }
	    }
	});

    $(".menu-icon").click(function(e) {
    	if ($('.menu').hasClass("visuallyhidden")) {
			$('.menu').removeClass("visuallyhidden");
			$('.menu-icon').addClass("close");
		} else {
			$('.menu').addClass("visuallyhidden");
			$('.menu-icon').removeClass("close");
		}
    });

    $(".menu li a").click(function(e) {
    	if ($('.menu').hasClass("visuallyhidden")) {
			$('.menu').removeClass("visuallyhidden");
			$('.menu-icon').addClass("close");
		} else {
			$('.menu').addClass("visuallyhidden");
			$('.menu-icon').removeClass("close");
		}
    });
	// Nav

	// Works
	$(".work-item").hover(function(e){
		$(this).children('.work-item-inside').addClass("visuallyshowed");
	}, function(){ 
		$(this).children('.work-item-inside').removeClass("visuallyshowed");
	});
	// Works

	// Skrollr · Mobile NONE
	if(!(/Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i).test(navigator.userAgent || navigator.vendor || window.opera)){
	    skrollr.init({
			beforerender: function(data) {
				return data.curTop > data.lastTop;
			}        
        	
	    });
	}
	// Skrollr

});


$(window).load(function() {

	fixSizes();
	$(".loader").delay(500).fadeOut('slow');
	$("body").css("overflow", "auto");
	$(".home-title").removeClass("jiji");
	$(".menu-icon").removeClass("jiji");
	
});


$(window).resize(function() {
	fixSizes();
});

function fixSizes() {

	var windowHeight = $(window).height();
	var windowWidth = $(window).width();

	$("#home").css('height', windowHeight);
	$(".home-title").each(function() {
		$(this).css('padding-top', ($(this).parent().height() - $(this).height()) / 2);
	});
	$(".work-item-inside-content").each(function() {
		$(this).css('margin-top', ($(this).parent().height() - $(this).height()) / 2);
	});
}