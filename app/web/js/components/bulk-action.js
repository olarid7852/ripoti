(function ($) {
    let bulkActionForm = $('#bulk-action-form'),
        bulkActionBtn = $('#bulk-action-btn'),
        selectAll = $('#select-all'),
        bulkDropdownItem = $('.bulk-dropdown-item'),
        bulkActionModal = $('#bulk-action-modal'),
        bulkActionWrapper = $('.bulk-action-wrapper'),
        bulkSelectCheckbox = $('[name="bulk-select"]'),
        idTemplate = (id) => `<input type="hidden" name="id[]" value="${id}">`;
    function addId(id) {
        bulkActionForm.append(idTemplate(id));
    }
    function removeId(id) {
        $('[name="id[]"]').each(function () {
            if ($(this).val() === id) {
                $(this).remove();
            }
        })
    }
    function noneChecked() {
        let allUnchecked = true;
        bulkSelectCheckbox.each(function () {
            let unchecked = !$(this).prop('checked');
            allUnchecked = allUnchecked && unchecked;
        });
        return allUnchecked;
    }
    $.fn.added = function () {
        bulkActionWrapper.removeClass('d-none');
        const id = $(this).val();
        addId(id)
    };
    $.fn.removed = function () {
        const id = $(this).val();
        removeId(id);
        if (noneChecked()) {
            bulkActionWrapper.addClass('d-none');
        }
    };
    $.fn.exists = function () {
        const id = $(this).val();
        if (id) {
            return true;
        }
        return false;
    };
    bulkSelectCheckbox.on('click', function () {
        if ($(this).prop('checked')) {
            $(this).added();
        } else {
            $(this).removed();
        }
    });
    bulkDropdownItem.on('click', function () {
        const modalBody = bulkActionModal.find('.modal-body');
        bulkActionModal.find('.modal-header h4').html($(this).data('title'));
        modalBody.find('p').html($(this).data('msg'));
        bulkActionForm.attr('action', $(this).data('url'));
        bulkActionForm.find('[name="status"]').val($(this).data('value'));
    });
    selectAll.on('click', function () {
        if ($(this).prop('checked')) {
            bulkSelectCheckbox.each(function () {
                const id = $(this).val();
                $(this).prop('checked', true);
                if (!$(`[name="id[]"][value="${id}"]`).exists()) {
                    $(this).added();
                }
            });
            bulkActionWrapper.removeClass('d-none');
        } else {
            bulkSelectCheckbox.prop('checked', false);
            bulkSelectCheckbox.each(function () {
                $(this).prop('checked', false);
                $(this).removed();
            });
            bulkActionWrapper.addClass('d-none');
        }
    });
})(jQuery);