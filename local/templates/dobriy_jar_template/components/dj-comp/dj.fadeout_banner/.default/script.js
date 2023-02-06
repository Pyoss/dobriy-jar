$(document).ready(
    () => {
        let main_slider = $('.main-slider')
        main_slider.slick(
            {
                dots: true,
                arrows: false,
                customPaging : function(slider, i) {
                    return '<a class="slider-dots">';
                },
            }
        );
    }
)