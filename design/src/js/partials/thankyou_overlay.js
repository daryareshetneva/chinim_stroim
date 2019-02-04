/* Скрипт для отработки закрытия формы "Спасибо" */
var overlay = $('#thankyou_overlay');
var close_button = $('#thankyou_overlay .close_button');

close_button.click(function () {
    overlay.addClass('invisible');
});