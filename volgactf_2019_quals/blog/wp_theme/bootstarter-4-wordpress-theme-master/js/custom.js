$(document).ready(function(){
    //show_window_width_for_development_init(); // comment on production
    is_breakpoint_init(); // keep on production
    detect_touch_device();
    enable_swipe_for_carousel();
    tuning();
});

var windows_width = $(window).width();
var resize_timer;
$(window).on('resize', function(){
    clearTimeout(resize_timer);
    resize_timer = setTimeout(function(){
    tuning();

    if ($(window).width()==windows_width) return;
    windows_width = $(window).width();
    horizontal_tuning(); // launched only if there is an horizontal resize

    }, 0);
});

function tuning(){
    main_content_full_height_pushes_footer_at_the_bottom();
    show_window_width_for_development(); // leave this function always last in order to detect script errors
}

function horizontal_tuning() { // launched only if there is an horizontal resize
    show_window_width_for_development(); // leave this function always last in order to detect script errors
}

// "show window for development" functions
function show_window_width_for_development_init() {
    $('body').append('<div id="window_width" style="position:fixed;bottom:0;right:0;z-index:9999;font-size:0.75rem;background:#fff;"></div>');
}
function show_window_width_for_development() {
    $('#window_width').html(get_breakpoint().toUpperCase()+'['+$(window).width()+'px]');
}

// "breakpoint detection" functions
function is_breakpoint_init(){
    $('body').append('<div class="device-xs d-block d-sm-none"></div><div class="device-sm d-none d-sm-block d-md-none"></div><div class="device-md d-none d-md-block d-lg-none"></div><div class="device-lg d-none d-lg-block d-xl-none"></div><div class="device-xl d-none d-xl-block"></div>');
}
function is_breakpoint(alias){
    return $('.device-' + alias).is(':visible');
}
function get_breakpoint(){
    if( is_breakpoint('xs') ) return 'xs';
    if( is_breakpoint('sm') ) return 'sm';
    if( is_breakpoint('md') ) return 'md';
    if( is_breakpoint('lg') ) return 'lg';
    if( is_breakpoint('xl') ) return 'xl';
}

function main_content_full_height_pushes_footer_at_the_bottom(){
    var wpadminbar = $('#wpadminbar').outerHeight(true);
    if (!wpadminbar) { wpadminbar = 0; }
    //if( is_breakpoint('xs') ) {
        $('.site-main').css( 'min-height', '0');
    //} else {
    //    $('.site-main').css( 'min-height', $(window).height()-( $('.site-header').outerHeight(true)+$('.site-footer').outerHeight(true) ) );
    //}

}

function detect_touch_device(){ // according to Modernizr
    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
        $('html').addClass('touch');
    } else {
        $('html').addClass('no-touch');
    }
}

function enable_swipe_for_carousel(){
    var touchStartX = null;
    $('.carousel').each(function () {
        var $carousel = $(this);
        $(this).on('touchstart', function (event) {
            var e = event.originalEvent;
            if (e.touches.length == 1) {
                var touch = e.touches[0];
                touchStartX = touch.pageX;
            }
        }).on('touchmove', function (event) {
            var e = event.originalEvent;
            if (touchStartX != null) {
                var touchCurrentX = e.changedTouches[0].pageX;
                if ((touchCurrentX - touchStartX) > 30) {
                    touchStartX = null;
                    $carousel.carousel('prev');
                } else if ((touchStartX - touchCurrentX) > 30) {
                    touchStartX = null;
                    $carousel.carousel('next');
                }
            }
        }).on('touchend', function () {
            touchStartX = null;
        });
    });
}
