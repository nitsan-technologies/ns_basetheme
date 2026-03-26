if(typeof jQuery != 'undefined') {
    $( document ).ready(function() {

        // Back to top feature
        if ($('#ns_basetheme_back_to_top').length) {
            var scrollTrigger = 100, // px
                backToTop = function () {
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop > scrollTrigger) {
                        $('#ns_basetheme_back_to_top').addClass('show');
                    } else {
                        $('#ns_basetheme_back_to_top').removeClass('show');
                    }
                };
            backToTop();
            $(window).on('scroll', function () {
                backToTop();
            });
            $('#ns_basetheme_back_to_top').on('click', function (e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, 700);
            });
        }
    });
}
