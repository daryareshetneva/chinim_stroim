/* Скрипт для исправления бага с нумерацией картинок при просмотре */
$('.slick-cloned a').each(function(){
    $(this).attr('data-lightbox', '');
});