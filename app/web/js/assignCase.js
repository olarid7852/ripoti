let requiredInput = $('.requiredInput');
// VALIDATE ADDITIONAL-INFORMATION
$('#assign_btn').click(function (event) {
    requiredInput.each(function () {
        if (!$(this).val()) {
            $(this).next().html('This field is required');
            event.preventDefault();
        }
    });
});
requiredInput.on('change', function () {
    if ($(this).val()) {
        $(this).next().html('');
    }
})

$(function () {
    $('#delete-icon').click(function () {
        $('.reply-text').val('');
        return false;
    })
});