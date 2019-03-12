<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Eplus</title>
	<link rel="stylesheet" type="text/css" href="/eplus/css/style.css">
</head>
<body>
	<div class="container">
		<form id="action-form" action="{{ action('Eplus\Salesman\SalesmanController@storeDataRegister') }}" method="post">
			{!! csrf_field() !!}
			<input type="hidden" name="line_user_id" value="{{ $lineUserProfile->id }}">
			<div class="logo">
				<img src="/eplus/img/logo.png">
			</div>
			<div class="store-id">
				<div class="title font-medium">*รหัสพนักงานขาย</div>

				<input id="fs_salesman_code" name="fs_salesman_code" class="sinput font-text" placeholder="กรุณาระบุ ">
			</div>
			<div class="news font-text">
				<section title=".squaredThree">
				    <div class="squaredThree">
				      <input type="checkbox" value="None" id="squaredThree" name="check"  />
				      <label for="squaredThree"></label>
				    </div>
				</section>
				ยินดีรับข้อมูลและข่าวสารผ่านทางไลน์
			</div>
			<button onclick="validate();" type="button" class="btn font-bold"> ลงทะเบียน </button>
		</form>
	</div>
</body>
</html>
<script type="text/javascript" src="/eplus/js/jquery-3.2.1.min.js"></script>
<script type='text/javascript'>
    function validate() {
        var validFsSalesmanCode = $('#fs_salesman_code').val();

        var isSubmit = 1;
        var msgError = ""; 
        if(validFsSalesmanCode == ""){
            isSubmit = 0;
            msgError += "กรุณากรอก รหัสพนักงานขาย\n";
        }
        
        if(document.getElementById("squaredThree").checked == false){
            isSubmit = 0;
            msgError += "กรุณายืนยันการสมัครและรับรองเงื่อนไข \n";
        }

        if(isSubmit){
            $('#action-form').submit();
        }else{
            alert(msgError);
        }
    }
</script>