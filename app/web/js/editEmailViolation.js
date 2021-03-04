(function ($) {
    let form = $('#edit-form'),
        editBtn = $('.edit-violation'),
        violationField = $('[name="ReportTypesForm.violation[names]"]'),
        invalidFeedback = $('.invalid-feedback');
    editBtn.on('click', function () {
        violationField.val($(this).data('violation_type_id'));
        form.attr('action', $(this).data('url'));
    });
    invalidFeedback.bind('DOMSubtreeModified', function () {
        $(this).addClass('d-block');
    });
})(jQuery);