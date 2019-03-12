<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8" />
    <title>Yellow Idea V2</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
    <meta content="" name="author" />

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/simple-line-icons/simple-line-icons.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= asset('assets/global/css/components.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/global/css/plugins.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('assets/pages/css/login.min.css') ?>" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body class=" login">
    <div class="logo">
        <img src="<?= asset('assets/images/yellow_logo.png') ?>" alt="" style="width:130px;"/>
    </div>
    <div class="content">
      @yield('content')
    </div>

    <script src="<?= asset('assets/global/plugins/jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') ?>"type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/js.cookie.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>"type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>" type="text/javascript"></script>
    <!--<script src="<?= asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') ?>" type="text/javascript"></script>-->
    <script src="<?= asset('assets/global/plugins/select2/js/select2.full.min.js') ?>" type="text/javascript"></script>
    <script src="<?= asset('assets/global/scripts/app.min.js') ?>" type="text/javascript"></script>
    <!--<script src="<?= asset('assets/pages/scripts/login.min.js') ?>" type="text/javascript"></script>-->
    <script>
    $(function() {
        $("form input").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                $('button[type=submit]').click();
                return false;
            } else {
                return true;
            }
        });
    });
    </script>
</body>
</html>
