<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>ICNOW</title>
		<meta name="viewport" content="width=device-width, user-scalable=no" />
		<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="/icnow/mini/css/style.css">
		<style type="text/css">
			.myclass:link { color: #FF0000; }
			.myclass:visited { color: #FF0000; }
			.logout{
			    position: absolute;
			    right: 0;
			    top: 0;
			}
			.logout button{
				width: auto !important;
				height: 40px;
				margin-top: 2px;
				margin-right: 10px;
				font-size: 15px;
			}
		</style>
	</head>
	<body>
		<div class="register card1" id="container">
			<div class="header" id="header">
					<img src="/icnow/mini/img/header.png">
					<div class="logo">
						<a href="/mini-page"><img src="/icnow/mini/img/logo.png"></a>
					</div>
					<div class="logout">
						<button class="btn btn-alert btn-back" onclick="location.href = '/mini-logout';">Logout</button>
					</div>
			</div>
			<div class="content" id="body">
				<div class="row ">
    				<div class="col-md-8 col-md-offset-2 ">
						<div class="text-s1 text-success">ได้รับข้อมูลเรียบร้อยแล้ว</div>
					</div>
				</div>
			</div>
			<div class="footer"  id="footer">
				<img src="/icnow/mini/img/footer.png">
			</div>
		</div>

		</div>

		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>

	</body>
</html>