// Toggle Case reply
$(function () {
    $('#reply-case').click(function () {
        $('.cases-reply').css('display', 'block');
    });
});

$(function () {
    $('#delete-icon').click(function () {
        $('.reply-text').val('');
        return false;
    })
});

//VALIDATION
let requiredInput = $('.requiredInput');
$('.all-report-reply').click(function (event) {
    requiredInput.each(function () {
        if (!$(this).val()) {
            $(this).next().html('This field is required');
            event.preventDefault();
        }
    });
});
//Admin-case-reply
$(function () {
    $('#cases-reply').click(function () {
        $('.cases-reply').css('display', 'block');
    });
});

// Withdraw Case
$(function () {
    $('#case_withdraw').click(function () {
        $('.withdrawCase').css('display', 'block');
    });
});

$('#withdraw-case').click(function () {
    let selected = true;
    $('.required').each(function (e) {
        if (!$(this).val()) {
            selected = selected && false;
            $(this).next().html('This field is required');
            e.preventDefault();
        }
    });
});