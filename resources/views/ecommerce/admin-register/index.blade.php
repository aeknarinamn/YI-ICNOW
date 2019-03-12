<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Register</title>
    <link rel="stylesheet" type="text/css" href="/admin/vendors/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt:400,700">
    <link rel="stylesheet" type="text/css" href="/admin/vendors/css/sumoselect.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/vendors/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/admin/vendors/css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" type="text/css" href="/admin/vendors/css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="/admin/resources/css/main.css">
    <script language="JavaScript" type="text/javascript">
		function validate() {
			var validName = $('#email').val();
			
			var isSubmit = 1;
			var msgError = ""; 
			if(validName == ""){
				isSubmit = 0;
				msgError += "กรุณากรอก Email \n";
				// document.getElementById('name').style.border = "solid 1px red";
			}else{
				// document.getElementById('name').style.border = "";
			}

			if(isSubmit){
				$('#formRegister').submit();
			}else{
				alert(msgError);
			}
		}
	</script>
</head>

<body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-static navbar-default header-bar">
        <div class="container-fluid">
            <img class="logo-default" src="/admin/resources/img/logo-default.svg" alt="LOGO">
        </div>
    </div>
    <div class="main-content">
        <div class="register-guide">
            <label for="register-guide">กรอกข้อมูลเพื่อลงทะเบียน</label>
        </div>
        <div class="register-bg"></div>
        <form class="form-horizontal" id="formRegister" data-abide action="{{ action('Ecommerce\RegisterAdminController@storeData') }}" method="post">
			{!! csrf_field() !!}
			<input type="hidden" name="line_user_id" id="line_user_id" value="{{ $lineUserProfileId }}">
            <div class="register-form">
                <div class="form-group input-txt">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <button class="button btn-link" type="button" onclick="validate()">ถัดไป<img src="/admin/resources/img/next@3x.png" alt=""></button>
            </div>
        </form>
    </div>


    <script type="text/javascript" src="/admin/vendors/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/admin/vendors/js/popper.min.js"></script>
    <script type="text/javascript" src="/admin/vendors/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/admin/vendors/js/jquery.sumoselect.min.js"></script>
    <script type="text/javascript" src="/admin/vendors/js/sidebar.js"></script>
</body>

</html>