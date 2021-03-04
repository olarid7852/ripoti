// TOGGLE REPORT REPLY TEXT AREA
$(function () {
    $('#reply-report').click(function () {
        $('.report-reply').css('display', 'block');
    });
});
//DELETE ICON
$(function () {
    $('#delete-icon').click(function () {
        $('#report-text').val('');
            return false;
    })
})
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

let replyReportForm = $('#reply-report-form');
$('#form-report-reply').click(function(){
    replyReportForm.attr('action', 'reply-report');
});

$('#twitter-report-reply').click(function(){
    replyReportForm.attr('action', '/twitter/send-twitter-reply');
});

$('#email-report-reply').click(function(){
    replyReportForm.attr('action', 'send-email-reply');
});