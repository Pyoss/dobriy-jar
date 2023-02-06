$(document).ready(
    () => {
        let hit__list = $('.new__list')
        hit__list.slick(
            {
                slidesToShow: 4,
                slidesToScroll: 4,
                dots: true,
                arrows: false,
                customPaging: function (slider, i) {
                    return '<a class="slider-dots">';
                },
                responsive: [
                    {
                        breakpoint: 980,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    }
                ]
            }
        );
    }
)