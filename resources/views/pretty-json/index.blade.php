<html>
<head>
    <title>Json Decode</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Quicksand" />
    <link rel="stylesheet" type="text/css" href="/pretty-json/css/pretty-json.css" />

    <!-- lib -->
    <script type="text/javascript" src="/pretty-json/libs/jquery-1.11.1.min.js" ></script>
    <script type="text/javascript" src="/pretty-json/libs/underscore-min.js" ></script>
    <script type="text/javascript" src="/pretty-json/libs/backbone-min.js" ></script>

    <!-- src dev
    <script type="text/javascript" src="/pretty-json-debug.js" ></script>
    -->
    <!-- src build -->
    <script type="text/javascript" src="/pretty-json/build/pretty-json-min.js" ></script>

    <input type="hidden" id="json_data" value="{{$json_data}}">

    <!-- just css for this page example -->
    <style type="text/css">
        body{
            width:700px;
            border-style: none;
            margin-left: auto;
            margin-right: auto;
        }

        textarea{
            width:600px;
            padding:4px 7px;
            border:1px solid #ccc;
            border-radius:4px;
            background:#fff;
            color:#333;
            margin-bottom:7px;
        }
    </style>
</head>

<body>
<script>
    $(document).ready(function() {

        var el = {
            btnAction: $('#action'),
            btnClear: $('#clear'),
            input: $('#input'),
            result: $('#result')
        };

        var demo = $('#json_data').val();

        // el.input.val(JSON.stringify(demo,null,4));

        el.input.val(demo);

        // el.btnAction.on('click', function(){
        var json = el.input.val();

        var data;
        try{ data = JSON.parse(json); }
        catch(e){ 
            alert('not valid JSON');
            return;
        }

        var node = new PrettyJSON.view.Node({ 
            el:el.result,
            data: data,
            dateFormat:"DD/MM/YYYY - HH24:MI:SS"
        });
        // });

        el.btnAction.on('click', function(){
            var json = el.input.val();

            var data;
            try{ data = JSON.parse(json); }
            catch(e){ 
                alert('not valid JSON');
                return;
            }

            var node = new PrettyJSON.view.Node({ 
                el:el.result,
                data: data,
                dateFormat:"DD/MM/YYYY - HH24:MI:SS"
            });
        });

        el.btnClear.on('click', function(){
            el.input.val('');
            el.result.html('');
        });
    });
</script>
<h1>/JsonDecode</h1>
<textarea id="input" rows="12"></textarea>
<br/>
<!-- <button id="action">go</button> -->
<!-- <button id="clear">clear</button> -->
<br/>
<br/>
<span id="result"></span>
</body>
</html>