$(document).ready(
    () => {
        let hit__list = $('.hit__list')
        hit__list.slick(
            {   slidesToShow: 4,
                slidesToScroll: 1,
                dots: true,
                infinite: false,
                arrows: false,
                customPaging : function(slider, i) {
                    return '<a class="slider-dots">';
                },
                responsive: [
                    {
                        breakpoint: 1470,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 1280,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        }
                    },
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