<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{cbLang("page_title_forgot")}} : {{$appname}}</title>
    <meta name='generator' content='CMS.com'/>
    <meta name='robots' content='noindex,nofollow'/>
    <link rel="shortcut icon"
          href="{{ CMS::getSetting('favicon')?asset(CMS::getSetting('favicon')):asset('vendor/cms/assets/logo_cms.png') }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{asset('vendor/cms/assets/adminlte/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="{{asset('vendor/cms/assets/adminlte/dist/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <link rel='stylesheet' href='{{asset("vendor/cms/assets/css/main.css")}}'/>
    <link rel='stylesheet' href='{{asset("vendor/cms/assets/css/main.css")}}'/>
    <style type="text/css">
        .login-page, .register-page {
            background: {{ CMS::getSetting("login_background_color")?:'#dddddd'}} url('{{ CMS::getSetting("login_background_image")?asset(CMS::getSetting("login_background_image")):asset('vendor/cms/assets/bg_blur3.jpg') }}');
            color: {{ CMS::getSetting("login_font_color")?:'#ffffff' }}  !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .login-box-body {
            box-shadow: 0px 0px 50px rgba(0, 0, 0, 0.8);
            background: rgba(255, 255, 255, 0.9);
            color: {{ CMS::getSetting("login_font_color")?:'#666666' }}  !important;
        }
    </style>
</head>
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{url('/')}}">
            <img title='{!!($appname == 'CMS')?"<b>Albreis</b>CMS":$appname!!}'
                 src='{{ CMS::getSetting("logo")?asset(CMS::getSetting('logo')):asset('vendor/cms/assets/logo_cms.png') }}'
                 style='max-width: 100%;max-height:170px'/>
        </a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">

        @if ( Session::get('message') != '' )
            <div class='alert alert-warning'>
                {{ Session::get('message') }}
            </div>
        @endif

        <p class="login-box-msg">{{cbLang("forgot_message")}}</p>
        <form action="{{ route('postForgot') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name='email' required placeholder="Email Address"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    {{cbLang("forgot_text_try_again")}} <a href='{{route("getLogin")}}'>{{cbLang("click_here")}}</a>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{cbLang("button_submit")}}</button>
                </div><!-- /.col -->
            </div>
        </form>

        <br/>
        <!--a href="#">I forgot my password</a-->

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('vendor/cms/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.4.1 JS -->
<script src="{{asset('vendor/cms/assets/adminlte/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>

</body>
</html>
