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
		<div class="logo">
			<img src="/eplus/img/logo.png">
		</div>
		<div class="store-id banner">
			<div class="font-text text03">สวัสดีค่ะ/ครับ ร้าน {{ $customerDataMapping->banner_master }}</div>
			<div class="font-text text03">Banner Code : {{ $customerDataMapping->banner_code }}</div>
			<div class="font-text text03">Banner Name : {{ $customerDataMapping->banner_e_plus }}</div>
			<div class="text-regis font-text otp">ขอบคุณสำหรับการลงทะเบียน</div>
		</div>
		<button type="button" onclick="redirectToLine()" class="btn font-bold"> กลับไปหน้าแชท </button>
	</div>
</body>
</html>
<script type="text/javascript">
	function redirectToLine() {
		window.location.href = "https://line.me/R/ti/p/%40test_usf";
	}
</script>