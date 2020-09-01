<!doctype html>
<html>
<head>
    <meta http-equiv="Cache-control" content="public">
    <meta charset="utf-8">
    <meta name="viewport" content=" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login</title>

    <link href="{{ asset('assets/css/all.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/jquery-steps.css') }}" rel="stylesheet" type="text/css">
    <!-- custom scrollbar stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.mCustomScrollbar.css') }}">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<!-- email_html-2,  -->
<body class="">

<div class="wrapper">

    <!-- Login Pop Up -->

    <div class="login_popup_outer popup_outer" style="display: block;">
        <div class="center_alignment">
            <div class="pop_up_body">
                <h2>Member Login</h2>

                <form class="login-form" role="form" id="loginForm" method="POST" action="{{url('/login')}}"
                      onsubmit="return validateForm()">

                    @if ( $errors->count() > 0 )
                        <div class="form-group has-error">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <span class="help-block">
                                @foreach( $errors->all() as $message )
                                        <strong> {{ $message }} </strong>
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    @endif

                    {{ csrf_field() }}

                    <label for="">
                        <div>
                            <input id="email" placeholder="User Name/Email" type="email" name="email"
                                   value="{{ old('email') }}">
                            <span id="emailError" style="color: red;display: none">Email required</span>
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif

                    </label>
                    <label for="">
                        <div>
                            <input id="password" placeholder="Password" type="password" name="password">
                            <span id="passwordError" style="color: red;display: none">Password required</span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                             <strong>{{ $errors->first('password') }}</strong>
                           </span>
                        @endif
                    </label>
                    <label for="">
                        <div class="captcha_holder">
                            <div id="recaptcha_register" class="g-recaptcha"
                                 data-sitekey="6LdUpaUUAAAAAPJ-lQlAahQgHH48XKrfp6g-i9GH"></div>
                            <span id="capatchError" style="color: red;display: none">Please select captcha</span>
                        </div>
                    </label>
                    <button class="sub_btn" type="submit" name="button">LOGIN</button>
                    <div class="rem_for_pass clearfix">
                        <a style="margin-top: 30px;" class="forgote_password" href="{{ url('/password/reset') }}">
                            Forgot password? </a>
                        <div class="camp_timing_check_box remember_pass" style="margin-top: 15px;">
                            <input type="checkbox" name="remember"/>
                            <label style="border: none;" for="e2-rec_comp3">Remember me </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
<script type="text/javascript" src="{{ asset('assets/js/jquery-1.10.2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-resizable.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-steps.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script type="text/javascript">
    function validateForm() {
        
        var response = grecaptcha.getResponse();
        console.log('grecaptcha.getResponse', grecaptcha.getResponse());
        var email = document.forms["loginForm"]["email"].value;
        var password = document.forms["loginForm"]["password"].value;
        var flag = true;
        console.log('password', password);
        console.log('email', email);
        if (email == "") {
            flag = false;
            $("#emailError, #email").addClass('not_valid');
        } else {
            $("#emailError, #email").removeClass('not_valid');
        }
        if (password == "") {
            flag = false;
            $("#passwordError, #password").addClass('not_valid');
        } else {
            $("#passwordError, #password").removeClass('not_valid');
        }
        if (response.length == 0) {
            $("#capatchError").addClass('not_valid');
            flag = false;
        } else {
            $("#capatchError").removeClass('not_valid');
        }
        return flag;

    }
</script>

</body>
</html>
