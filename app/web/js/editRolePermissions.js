(function ($) {
    let editBtn = $('.edit_role'),
        roleLabel = $('[name="Role[label]"]'),
        statusField = $('[name="Role[status]"]');
    editBtn.on('click', function () {
        $('[name="Role[permissions][]"]').prop('checked', false);
        roleLabel.val($(this).data('label'));
        statusField.val($(this).data('status'));
        $('#new_role_modal').find('[name="Role[permissions][]"]').addClass('check');
        let permissions = $(this).data('permissions[]');
        $('.check').each(function () {
            let val = $(this).val();
              if (permissions.includes(parseInt(val))) {
                $(this).prop('checked', true);
            }
        });
    });
    // SELECT ALL FUNCTION
    let selectAllItems = "#select-all";
    let checkboxItem = ":checkbox";
    $(selectAllItems).click(function () {
        if (this.checked) {
            $(checkboxItem).each(function () {
                this.checked = true;
            });
        } else {
            $(checkboxItem).each(function () {
                this.checked = false;
            });
        }
    });
})(jQuery);
