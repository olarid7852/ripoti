(function ($) {
    let form = $('#edit-modal-form'),
        editBtn = $('.edit-violation'),
        violationField = $('[name="ViolationTypes[names]"]'),
        statusField = $('[name="ViolationTypes[status]"]'),
        invalidFeedback = $('.invalid-feedback');
    editBtn.on('click', function () {
        violationField.val($(this).data('violation'));
        statusField.val($(this).data('status'));
        form.attr('action', $(this).data('url'));
    });
    invalidFeedback.bind('DOMSubtreeModified', function () {
        $(this).addClass('d-block');
    });
})(jQuery);
