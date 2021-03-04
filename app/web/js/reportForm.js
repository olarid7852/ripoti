$('document').ready(function () {
    // ADD/CUSTOMIZE  CLASS FOR DATE-PICKER
    $('#reportform-occurred_when')
        .addClass('report-input mustFill')
        .attr('placeholder', 'Please pick a date')
        .css({
            'font-size': '1rem',
            'font-weight': '400',
            'padding': '0.375rem 0.75rem',
        });
    // TOGGLE TO ADDITIONAL INFORMATION PAGE
    $('#next_btn').click(function () {
        let selected = true;
        $('.mustFill').each(function () {
            if (!$(this).val()) {
                selected = selected && false;
                $(this).next().html('This field is required');
            }
        });
        if (selected) {
            $('#report-form1').hide();
            $('#additional-report-form').show();
        }
    });
    let requiredField = $('.requiredField');
    // VALIDATE ADDITIONAL-INFORMATION
    $('#submit_btn').click(function (event) {
        let selected = true;
        requiredField.each(function () {
            if (!$(this).val()) {
                selected = selected && false;
                $(this).next().html('This field is required');
                event.preventDefault();
            }
        });
    });
    requiredField.on('change', function () {
        if ($(this).val()) {
            $(this).next().html('');
        }
    })
    // TOGGLE MEANS OF CONTACT
    $('#reportform-contact').change(function () {
        $('.contact').hide();
        $('#' + $(this).val()).show();
    });
    // TOGGLE TO REPORT FORM PAGE
    $('#back_btn').click(function () {
        $('#additional-report-form').hide();
        $('#report-form1').show();
    });
});
