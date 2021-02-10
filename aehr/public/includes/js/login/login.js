function login()
{
    $('#login').click(function () {
        initLogin();
    });
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            initLogin();
        }
    });

    function initLogin()
    {
        var username = $('#username').val();
        var password = $('#password').val();

        if(username == '' && password == '')
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Incomplete Credentials!',
            });
        else if(username == '')
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Username is required!',
            });
        else if(password == '')
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password is required!',
            });
        else
            $.ajax(
                {
                    url: '/login',
                    method: 'post',
                    data: {
                        username: username,
                        password: password
                    },
                    statusCode: {
                        404: function () {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sorry but',
                                text: 'Your account does not exist!',
                            });
                        },
                        401: function () {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sorry but',
                                text: 'Your password is incorrect!',
                            });
                        },
                        200: function (data) {
                            switch (data.role)
                            {
                                case 'admin':

                                    break;
                                case 'encoder':
                                    window.location.href = '/encoder';
                                    break;
                            }
                        }
                    }
                }
            );
    }
}
