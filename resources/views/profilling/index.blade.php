<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8>
        <title>Yellow Idea</title>
        <meta http-equiv=X-UA-Compatible content="IE=edge">
        <meta content="width=device-width,initial-scale=1" name=viewport>
        <meta content="Yellow Idea" name=description>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="hostname" content="{{ Request::root() }}">
        <link rel=stylesheet href=<?= asset('static-profilling/bootstrap2.min.css') ?> type=text/css charset=utf-8>
        <link rel=stylesheet href=<?= asset('static-profilling/components1.min.css') ?> type=text/css charset=utf-8>
        <link href="<?= asset('static-profilling/core/app.b00fafc543347315aceb8c7d28cb994d.css') ?>" rel=stylesheet>
        <link href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" rel="icon" type="image/x-icon" />
    </head>
    <body>
        <div id="hid_profilling" style=display:none>{{ $datas }}</div>
        <div id="hid_subdata" style=display:none>{{$subscriberDatas}}</div>
        <input type="hidden" id="hid_dogjun_color" value="#e27826" />
        <div id=app></div>

        <script src="<?= asset('./static-profilling/vendor/jquery-1.11.2.min.js') ?>"></script>
        <script type=text/javascript src="<?= asset('/static-profilling/core/manifest.c5bda70a8691a6c92276.js') ?>"></script>
        <script type=text/javascript src="<?= asset('/static-profilling/core/vendor.068bada7c62ba8fb79b6.js') ?>"></script>
        <script type=text/javascript src="<?= asset('/static-profilling/core/app.4a1753eaa3735cb704e9.js') ?>"></script>
    </body>
</html>