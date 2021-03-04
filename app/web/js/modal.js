(function ($) {
    let editModalDropdown = $('.edit-modal-dropdown'),
        addBtn = $('.add-btn'),
        editModal = $('#new_role_modal'),
        addForm = $('.add-form');
    addBtn.on('click', function () {
        addForm.trigger('reset');
    });
    editModalDropdown.on('click', function () {
        editModal.find('form').attr('action', $(this).data('url'));
    });
})(jQuery);
//VALIDATE MODAL INPUT-FIELD
$('#modal').submit(function (event) {
    $('.mustFill').each(function () {
        if (!$(this).val()) {
            $(this).next().html('This field is required');
            event.preventDefault();
        }
    });
});
$('.mustFill').on('change', function () {
    if ($(this).val()) {
        $(this).next().html('');
    }
})