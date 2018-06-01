/**
 * Created by jocelyn on 5/11/18.
 */

$(document).on('click','#signupSubmit', function(e){
    e.preventDefault();
    var pass, pass1, token;
    pass = $('#password').val();
    pass1 = $('#password1').val();
    token = $('#token').val();


    if(pass == '' || pass1 == ''){
        custom_alert('Saisir le mot de passe','Mot de passe');
        return false;
    }

    if( pass != pass1){
        custom_alert('Le mot de passe est incorrect','Mot de passe');
    }else {
        //alert(token);
        var data = {
            password: pass,
            token: token
        };

        $('#loader').addClass('loader');
        $.ajax({
            type: 'POST',
            url: Routing.generate('activeCompte'),
            data: data,
            success: function(res){
               // console.log(res);
                custom_alert(res.message,'DATA');
                $('#loader').removeClass('loader');
            },
            error: function(e){
                alert(e);
            }
        });
    }

    function custom_alert( message, title ) {
        if ( !title )
            title = 'Alert';

        if ( !message )
            message = 'No Message to Display.';

        $('<div></div>').html( message ).dialog({
            title: title,
            resizable: false,
            modal: true,
            buttons: {
                'Ok': function()  {
                    $( this ).dialog( 'close' );
                }
            }
        });
    }

    function basicDialog(){
        $( "#dialog" ).dialog();
    }

})