(function ($) {
    let form = $('#edit-modal-form'),
        editBtn = $('.edit-violation'),
        editFirstNameField = $('[name="UsersTypesForm[first_name]"]'),
        editLastNameField = $('[name="UsersTypesForm[last_name]"]'),
        roleField = $('[name="UsersTypesForm[role_key]"]'),
        userAuthIdField = $('[name="UsersTypesForm[user_auth_id]"]'),
        emailField = $('[name="UserCredential[email]"]'),
        invalidFeedback = $('.invalid-feedback');
    editBtn.on('click', function () {
        editFirstNameField.val($(this).data('first_name'));
        editLastNameField.val($(this).data('last_name'));
        userAuthIdField.val($(this).data('user_auth_id'));
        roleField.val($(this).data('role'));
        emailField.val($(this).data('email'));
        form.attr('action', $(this).data('url'));
    });
    invalidFeedback.bind('DOMSubtreeModified', function () {
        $(this).addClass('d-block');
    });
})(jQuery);