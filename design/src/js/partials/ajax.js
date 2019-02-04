/* Скрипты для обработки форм с помощью AJAX */

$(document).ready(function() {
    /* Обработка формы в модальном окне */
    $('#callus_button_modal').click(function () {
        var phoneValue = $('#phoneInputModal').val();
        var nameValue = $('#nameInputModal').val();

        $.ajax ({
            type: 'POST',
            url: config.callUs.request,
            data: {
                name: nameValue,
                phone: phoneValue,
                message: '',
                source: 'modal'
            },
            dataType: 'json',
            success: function(response) {
                if (typeof response.success !== 'undefined') {
                    jQuery('#callus_modal form')[0].reset();
                    $('#nameInputModal').removeClass('warning');
                    $('#phoneInputModal').removeClass('warning');
                    $('#callus_modal').modal('hide');
                    $('#thankyou_overlay').removeClass('invisible');
                    setTimeout(function () {
                        $('#thankyou_overlay').addClass('invisible');
                    }, 15000);


                } else if (typeof response.nameError !== 'undefined') {
                    alert(response.nameError);
                    $('#nameInputModal').addClass('warning');
                    $('#phoneInputModal').removeClass('warning');
                } else if (typeof response.phoneError !== 'undefined') {
                    alert(response.phoneError);
                    $('#phoneInputModal').addClass('warning');
                    $('#nameInputModal').removeClass('warning');
                } else { alert(response.error);}
            },
            error: function(errors) {
                alert('Произошла непридвиненная ошибка. Приносим извинения');
                return false;
            }
        });
        return false;
    });
    /* Обработка формы в промоблоке на главной странице */
    $('#callus_button').click(function () {
        var phoneValue = $('#phoneInput').val();
        var nameValue = $('#nameInput').val();

        $.ajax ({
            type: 'POST',
            url: config.callUs.request,
            data: {
                name: nameValue,
                phone: phoneValue,
                message: '',
                source: 'promoblock'
            },
            dataType: 'json',
            success: function(response) {
                if (typeof response.success !== 'undefined') {
                    jQuery('#callus_form form')[0].reset();
                    $('#nameInput').removeClass('warning');
                    $('#phoneInput').removeClass('warning');
                    $('#thankyou_overlay').removeClass('invisible');
                    setTimeout(function () {
                        $('#thankyou_overlay').addClass('invisible');
                    }, 15000);
                } else if (typeof response.nameError !== 'undefined') {
                    alert(response.nameError);
                    $('#nameInput').addClass('warning');
                    $('#phoneInput').removeClass('warning');
                } else if (typeof response.phoneError !== 'undefined') {
                    alert(response.phoneError);
                    $('#phoneInput').addClass('warning');
                    $('#nameInput').removeClass('warning');
                } else { alert(response.error);}
            },
            error: function(errors) {
                alert('Произошла непридвиненная ошибка. Приносим извинения');
                return false;
            }
        });
        return false;
    });
    /* Обработка формы в call_us_pages */
    $('#callus_pages_button').click(function () {
        var phoneValue = $('#phoneInput').val();
        var nameValue = $('#nameInput').val();

        $.ajax ({
            type: 'POST',
            url: config.callUs.request,
            data: {
                name: nameValue,
                phone: phoneValue,
                message: '',
                source: 'pages'
            },
            dataType: 'json',
            success: function(response) {
                if (typeof response.success !== 'undefined') {
                    jQuery('#callus_form_pages form')[0].reset();
                    $('#nameInput').removeClass('warning');
                    $('#phoneInput').removeClass('warning');
                    $('#thankyou_overlay').removeClass('invisible');
                    setTimeout(function () {
                        $('#thankyou_overlay').addClass('invisible');
                    }, 15000);
                } else if (typeof response.nameError !== 'undefined') {
                    alert(response.nameError);
                    $('#nameInput').addClass('warning');
                    $('#phoneInput').removeClass('warning');
                } else if (typeof response.phoneError !== 'undefined') {
                    alert(response.phoneError);
                    $('#phoneInput').addClass('warning');
                    $('#nameInput').removeClass('warning');
                } else { alert(response.error);}
            },
            error: function(errors) {
                alert('Произошла непридвиненная ошибка. Приносим извинения');
                return false;
            }
        });
        return false;
    });
    /* Обработка формы "Задайте вопрос" */
    $('#question_form_button').click(function () {
        var phoneValue = $('#phoneInput').val();
        var nameValue = $('#nameInput').val();
        var message = $('#questionInput').val();
        $.ajax ({
            type: 'POST',
            url: config.callUs.request,
            data: {
                name: nameValue,
                phone: phoneValue,
                message: message,
                source: 'question'
            },
            dataType: 'json',
            success: function(response) {
                if (typeof response.success !== 'undefined') {
                    jQuery('#question_form form')[0].reset();
                    $('#nameInput').removeClass('warning');
                    $('#phoneInput').removeClass('warning');
                    $('#thankyou_overlay').removeClass('invisible');
                    setTimeout(function () {
                        $('#thankyou_overlay').addClass('invisible');
                    }, 15000);
                } else if (typeof response.nameError !== 'undefined') {
                    alert(response.nameError);
                    $('#nameInput').addClass('warning');
                    $('#phoneInput').removeClass('warning');
                } else if (typeof response.phoneError !== 'undefined') {
                    alert(response.phoneError);
                    $('#phoneInput').addClass('warning');
                    $('#nameInput').removeClass('warning');
                } else { alert(response.error);}
            },
            error: function(errors) {
                alert('Произошла непридвиненная ошибка. Приносим извинения');
                return false;
            }
        });
        return false;
    });
    /* Обработка формы "Оставьте отзыв" */
    $('#give_feedback_button').click(function () {
        var phoneValue = $('#phoneInput').val();
        var nameValue = $('#nameInput').val();
        var message = $('#feedbackInput').val();

        $.ajax ({
            type: 'POST',
            url: config.callUs.request,
            data: {
                name: nameValue,
                phone: phoneValue,
                message: message,
                source: 'feedback'
            },
            dataType: 'json',
            success: function(response) {
                if (typeof response.success !== 'undefined') {
                    jQuery('#give_feedback_form form')[0].reset();
                    $('#nameInput').removeClass('warning');
                    $('#phoneInput').removeClass('warning');
                    $('#thankyou_overlay').removeClass('invisible');
                    setTimeout(function () {
                        $('#thankyou_overlay').addClass('invisible');
                    }, 15000);
                } else if (typeof response.nameError !== 'undefined') {
                    alert(response.nameError);
                    $('#nameInput').addClass('warning');
                    $('#phoneInput').removeClass('warning');
                } else if (typeof response.phoneError !== 'undefined') {
                    alert(response.phoneError);
                    $('#phoneInput').addClass('warning');
                    $('#nameInput').removeClass('warning');
                } else { alert(response.error);}
            },
            error: function(errors) {
                alert('Произошла непридвиненная ошибка. Приносим извинения');
                return false;
            }
        });
        return false;
    });


});