@extends('layouts.master')
@section('scripts')
    <script src="{{asset('js/login.js')}}"></script>
    @endsection
@section('title', 'AEHR')
@section('content')
    <style>
        body
        {
            /*background-image: linear-gradient(to left, #b92b27, #1565C0);*/
            /*min-width: 800px;*/
            box-sizing: border-box;
            overflow: auto;
        }
        .login-container
        {
            margin-top: 30vh;
        }
        #aehr-logo
        {
            max-width: 100%;
            width: 25vw;
            heigth: 25vh;
        }
        button
        {
            border-radius: 20px 20px 20px 20px !important;
            text-align: center;
        }
        #username, #password
        {
            background: transparent !important;
            color: black !important;
            border:none;
        }
        #username:focus, #password:focus
        {
            background: transparent !important;
        }
        #username::placeholder, #password::placeholder
        {
            color: #9F9F9D;
        }

        /*.bg{*/
            /*opacity:0.3 !important;*/
            /*filter: grayscale(100%);*/
            /*position: fixed;*/
            /*top:0px;*/
            /*height: 100%;*/
            /*width:100%;*/
            /*background-image: url('/includes/images/banaue2.jpg');*/
            /*background-repeat: no-repeat;*/
            /*background-size: cover;*/
            /*z-index: -1;*/
            /*background-color: #1b1e21;*/
        /*}*/

        .input-light1, .input-light2{
            color: black !important;
            background-color: transparent !important;
            border: 0 !important;
            width: 100%;
            border-bottom: 2px gray solid !important;
            border-radius: 0 !important;
            z-index: 100;
            font-size: 1.3rem !important;
            font-family: arial;
        }
        .bot-border, .bot-border2{
            position: relative;
            top: -1.5px;
            border-bottom: 3px orange solid;
            width: 0;
        }
        .input-light1:focus, .input-light2:focus{
            outline:0px !important;
            -webkit-appearance:none;
        }
        #username, #password{
            position: relative;
            top: 30px;
            font-family: 'arial';
            z-index: 1;
            font-size: 1rem;
        }
    </style>

    <div class="container">
        <div class="row login-container">
            <div class="col-lg-6 border-right text-center">
                <img src="{{asset('includes/images/aehr.png')}}" class="pt-5" id="aehr-logo" alt="">
                <h4 class="pt-2 text-light" style="letter-spacing: 1vw; max-font-size: 18px">PHILIPPINES INC.</h4>
            </div>
            <div class="col-lg-6 ">
                <form action="{{route('login2')}}" method="post">
                    @csrf
                    <div class="">
                        <div class="container">
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Woops!</strong>  {{ Session::get('error')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="lead text-light" id="username"><i class="fas fa-edit"></i> Username</label>
                                <input type="text" name="username" id="usernameinput" class="input-light1" required autocomplete="off" value="{{ old('username') }}">
                                <div class="bot-border"></div>
                            </div>
                            <div class="form-group">
                                <label class="lead text-light" id="password"><i class="fa fa-lock"> </i> Password</label>
                                <input type="password" name="password" id="passwordinput" class="input-light2" value="{{ old('password') }}" autocomplete="off" required>
                                <div class="bot-border2"></div>
                            </div>
                        </div>
                        <div class="p-2 mt-4 mx-2">
                            <button type="submit" class="btn btn-success form-control">Sign in</button>

                            <div class="text-center mt-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberme">
                                    <label class="form-check-label " for="rememberme">Remember me</label>
                                </div>
                                <small class="font-italic text-light text-dark">No Account yet? You can request here <a
                                        href="{{route('register')}}"><strong>Sign up</strong></a> </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function (){
            if($(".input-light1").val() !== '')
            {
                $(".bot-border").css({"width" : "100%", "transition" : "1.5s"});
                $("#username").css({"top": "0px", "transition": "0.5s"});
            }
            if($(".input-light2").val() !== '')
            {
                $(".bot-border2").css({"width" : "100%", "transition" : "1.5s"});
                $("#password").css({"top": "0px", "transition": "0.5s"});
            }
            //normal
            $(".input-light1").on("click", function(){
                $(".bot-border").css({"width" : "100%", "transition" : "1.5s"});
                $("#username").css({"top": "0px", "transition": "0.5s"});
            });
            $(".input-light1").on("input", function(){
                $(".bot-border").css({"width" : "100%", "transition" : "1.5s"});
                $("#username").css({"top": "0px", "transition": "0.5s"});
            });
            $(".input-light1").on("blur", function(){
                $(".bot-border").css("width", "0%");
                if( $(".input-light1").val() === ''){
                    $("#username").css({"top": "30px", "transition": "0.5s"});
                }else{
                    $(".bot-border").css("width", "100%");
                }
            });
            $(".input-light2").on("click", function(){
                $(".bot-border2").css({"width" : "100%", "transition" : "1.5s"});
                $("#password").css({"top": "0px", "transition": "0.5s"});
            });
            $(".input-light2").on("input", function(){
                $(".bot-border2").css({"width" : "100%", "transition" : "1.5s"});
                $("#password").css({"top": "0px", "transition": "0.5s"});
            });
            $(".input-light2").on("blur", function(){
                $(".bot-border2").css("width", "0%");
                if( $(".input-light2").val() === ''){
                    $("#password").css({"top": "30px", "transition": "0.5s"});
                }else{
                    $(".bot-border2").css("width", "100%");
                }
            });
            $('#rememberme').on('blur', function (){
                if($('#rememberme').prop('checked'))
                {
                    setCookie('username', $('#usernameinput').val(), 1);
                    setCookie('password', $('#passwordinput').val(), 1);
                }
            });
        });
    </script>
@endsection
