$(document).ready(function(){
    $('#to').blur(function(){
        $('#result').fadeOut(1000);
    });
});

function show(to_from, id) {
    //$('.msg_box').remove();
    $.ajax({
        type: 'POST',
        url: 'inc/msg_box.php',
        data: {to_from: to_from, id: id},
        success: function(data) {
            $('.message').html(data);
            $('#'+id).attr('class', '');
            $('.chat_box').hide();
        }
    });
    //console.log('ajax called');   
}

/*$('.message').load(
    "inc/msg_box.php", 
    {to_from: to_from}, 
    function(responseTxt, statusTxt, xhr) {
        $('.fa-circle').attr('class', '');
    }
);*/