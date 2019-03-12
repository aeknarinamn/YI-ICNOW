<!DOCTYPE html>
<html>
    <head>
        <title>This page cannot be displayed.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #636363;
                display: table;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
            	<img src="{{  asset('img/error_page.png')  }}" style="width:200px;" />
                <p>Sorry, unable to locate the server.</p>
                <p>This page cannot be displayed.</p>
            </div>
        </div>
    </body>
</html>
