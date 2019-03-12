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
			<div class="font-text text03">สวัสดีค่ะ/ครับ</div>
			<div class="font-text text03">รหัสพนักงานขาย : {{ $salesmanDataMapping->fs_salesman_code }}</div>
			<div class="font-text text03">ชื่อพนักงานขาย : {{ $salesmanDataMapping->salesman_name }}</div>
			<div class="font-text text03">เบอร์โทร : {{ $salesmanDataMapping->salesman_tel }}</div>
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