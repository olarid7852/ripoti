$(function(){
    $('#modalButton').on('click touchstart', function (){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    });
});

