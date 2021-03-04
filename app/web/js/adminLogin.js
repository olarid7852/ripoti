// TOGGLE PASSWORD VISIBILITY
$(".icon").click(function () {
    $(this).toggleClass("fa-eye-slash fa-eye");
    let input = $($(this).attr("data-toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

// REMEMBER-ME CHECKBOX
let check = $('.field-loginform-rememberme');
$(check).find('.custom-control').removeClass('custom-control');
$(check).find('.custom-checkbox').css({
    'padding' : '0 10px 0 10px',
    'color' : '#279a47',
    'cursor' : 'pointer',
});

