//SELECT FROM COUNTRIES
(function ($) {
    function sortKeysByValues(obj) {
        let newKeys = [];
        const values = Object.values(obj);
        values.sort();
        values.forEach(function (value) {
            const key = getKeysFromValue(obj, value);
            newKeys.push(key);
        });
        return newKeys;
    }
    function getKeysFromValue(obj, value) {
        const keys = Object.keys(obj);
        const key = keys.filter(function (key) {
            return obj[key] === value
        })[0];
        return key;
    }
    $('.dependent-select-group').each(function (i, select) {
        select = $(select);
        let currentLevel = select.data('level');
        select.change(function () {
            $('.dependent-select-group').each(function (i, select) {
                select = $(select);
                let selectId = select.attr('id');
                if (currentLevel < select.data('level') && select.data('parent') === selectId) {
                    select.empty();
                    $('#' + selectId).hide();
                    $('label[for=' + selectId + ']').hide();
                }
            });
        });
    });
    $('.dependent-select').each(function (i, select) {
        select = $(select);
        let parent = select.data('parent');
        let dataURL = select.data('url');
        let selectId = select.attr('id');
        let prompt = select.data('prompt') || 'Please pick one';
        $('#' + parent).change(function () {
            let parentKeys = $(this).val();
            if (parentKeys === null) {
                select.empty();
                return;
            }
            $.ajax({
                url: dataURL + encodeURI($(this).val()),
                dataType: "json",
                beforeSend: function () {
                    select.empty();
                    select.show();
                    $('label[for=' + selectId + ']').show();
                }
            }).done(function (data) {
                let items = data.data;
                let keys = sortKeysByValues(items);
                select.append(`<option value>${prompt}</option>`);
                keys.forEach(function (key) {
                    select.append('<option value="' + key + '">' + items[key] + '</option>');
                });
                if (keys.length > 0) {
                    select.show();
                    $('label[for=' + selectId + ']').show();
                }
            })
        });
        if(!select.val()) {
            $('#' + selectId).hide();
            $('label[for=' + selectId + ']').hide();
            $('#'+ parent).change();
        }
    });
})(jQuery);
