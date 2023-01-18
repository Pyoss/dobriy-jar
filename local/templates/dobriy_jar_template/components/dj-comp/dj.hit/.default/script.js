$(document).ready(
    () => {
        let hit__list = $('.hit__list')
        hit__list.slick(
            {   slidesToShow: 4,
                slidesToScroll: 1,
                dots: true,
                arrows: false,
                customPaging : function(slider, i) {
                    return '<a class="slider-dots">';
                },
                responsive: [
                    {
                        breakpoint: 980,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    }
                ]
            }
        );
    }
)