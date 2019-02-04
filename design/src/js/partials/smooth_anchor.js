/* Скрипт для плавной прокрутки до якорных ссылок */
$(function(){
    $("button > a[href^='#']").click(function(){
        var href = $(this).attr("href");
        var header_height = $("header").height();
        $("html, body").animate({scrollTop: $(header_height) +"px"});
        return false;
    });
});