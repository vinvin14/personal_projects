function jfunction(){
        $('#logout').on('click', function(){
            $.confirm({
                title: 'Signout?',
                content: 'Your time is out, you will be automatically logged out in 10 seconds.',
                autoClose: 'logoutUser|10000',
                animation: 'top',
                closeAnimation: 'top',
                animateFromElement: false,
                buttons: {
                    logoutUser: {
                        text: 'Signout myself',
                        action: function(){
                            $.alert('The user was logged out');
                            window.setTimeout(function(){window.location.href = "/encoder/singout";

                            }, 1500);

                        }
                    },
                    cancel: function(){
                        $.alert('Cancelled!');
                    }
                }
            });
        });
        // $('#add-participant').on('click', function(){
        //     $.ajax({
        //         method: 'POST',
        //         url: 'ajaxRequest/register-participant',
        //
        //     });
        // });

}