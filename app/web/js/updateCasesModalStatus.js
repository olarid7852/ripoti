(function ($) {
    let form = $('#reminder-modal-form'),
        editBtn = $('.edit-violation'),
        nameField = $('[name="CasesTypesForm[fullname]"]'),
        emailField = $('[name="CasesTypesForm[email]"]'),
        caseSummaryField = $('[name="CasesTypesForm[case_summary]"]'),
        invalidFeedback = $('.invalid-feedback');

    editBtn.on('click', function () {
        nameField.val($(this).data('full_name'));
        emailField.val($(this).data('email'));
        caseSummaryField.val($(this).data('case_summary'));
        form.attr('action', $(this).data('url'));
    });

    invalidFeedback.bind('DOMSubtreeModified', function () {
        $(this).addClass('d-block');
    });
})(jQuery);