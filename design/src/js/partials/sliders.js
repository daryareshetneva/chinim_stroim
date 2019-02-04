/* Скрипт с настройками для слайдеров с помощью slick */
$('#services_slider').slick({
    slidesToShow: 4,
    infinite: true,
    slidesToScroll: 1,
    dots: true,
    speed: 700,
    autoplay: true,
    autoplaySpeed: 8000,
    adaptiveHeight: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 2
            }
        },
        {
            breakpoint: 786,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});
$('#about_slider').slick({
    slidesToShow: 5,
    infinite: true,
    slidesToScroll: 1,
    dots: true,
    speed: 700,
    autoplay: true,
    autoplaySpeed: 8000,
    adaptiveHeight: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 2
            }
        },
        {
            breakpoint: 786,
            settings: {
                slidesToShow: 1,

            }
        }
    ]
});
$('#portfolio_slider').slick({
    slidesToShow: 4,
    infinite: true,
    slidesToScroll: 1,
    dots: true,
    speed: 700,
    autoplay: true,
    autoplaySpeed: 8000,
    adaptiveHeight: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 2
            }
        },
        {
            breakpoint: 786,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});