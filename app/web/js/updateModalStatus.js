(function ($) {
    let statusModal = $('.status-modal'),
        editStatus = $('.edit-status'),
        statusField = statusModal.find('.form-control'),
        invalidFeedback = $('.invalid-feedback');
    editStatus.on('click', function () {
        statusField.val($(this).data('status'));
    });
    invalidFeedback.bind('DOMSubtreeModified', function () {
        $(this).addClass('d-block');
    });
})(jQuery);