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
						<form action="{{ action('ICNOW\Mini\MiniController@cancleOrderStore') }}" method="post" class="form-horizontal" >
							{!! csrf_field() !!}
							<input type="hidden" name="order_id" value="{{$orderCustomerMain->id}}">
							<div class="text-s1">กรุณาระบุเหตุผล</div>
								<div class="form-check">
									<ul>
										<li>
											<div class="squaredTwo">
										      <input type="radio" value="เมินิมีสินค้าไม่เพียงพอ" id="reason-1" name="cancle_case"/>
										      <label for="reason-1"></label>
										    </div>
										</li>
										<li>
											<label for="reason-1">มินิมีสินค้าไม่เพียงพอ</label >
										</li>
									</ul>
								</div>
								<div class="form-check">
									<ul>
										<li>
											<div class="squaredTwo">
										      <input type="radio" value="ศูนย์จัดจำหน่ายมีสินค้าไม่เพียงพอ" id="reason-2" name="cancle_case"/>
										      <label for="reason-2"></label>
										    </div>
										</li>
										<li>
											<label for="reason-2">ศูนย์จัดจำหน่ายมีสินค้าไม่เพียงพอ</label >
										</li>
									</ul>
								</div>
								<div class="form-check">
									<ul>
										<li>
											<div class="squaredTwo">
										      <input type="radio" value="วอลล์แมนประจำเส้นทางลาออกอยู่ระหว่างหาคนใหม่" id="reason-3" name="cancle_case"/>
										      <label for="reason-3"></label>
										    </div>
										</li>
										<li>
											<label for="reason-3">วอลล์แมนประจำเส้นทางลาออกอยู่ระหว่างหาคนใหม่</label >
										</li>
									</ul>
								</div>
								<div class="form-check">
									<ul>
										<li>
											<div class="squaredTwo">
										      <input type="radio" value="วอลล์แมนประจำเส้นทางอยู่ระหว่างลาหยุดงาน" id="reason-4" name="cancle_case"/>
										      <label for="reason-4"></label>
										    </div>
										</li>
										<li>
											<label for="reason-4">วอลล์แมนประจำเส้นทางอยู่ระหว่างลาหยุดงาน</label >
										</li>
									</ul>
								</div>
								<div class="form-check">
									<ul>
										<li>
											<div class="squaredTwo">
										      <input type="radio" value="วอลล์แมนประจำเส้นทางติดนัดหมายลูกค้าอื่นก่อนหน้า" id="reason-5" name="cancle_case"/>
										      <label for="reason-5"></label>
										    </div>
										</li>
										<li>
											<label for="reason-5">วอลล์แมนประจำเส้นทางติดนัดหมายลูกค้าอื่นก่อนหน้า</label >
										</li>
									</ul>
								</div>
								<!-- <div class="form-check">
								    <div class="squaredTwo">
								      <input type="checkbox" value="None" id="reason-2" name="check"  />
								      <label for="reason-2"></label>z
								    </div>
								    <label for="reason-2">เหตุผล 2</label >
								</div>
								<div class="form-check">
								    <div class="squaredTwo">
								      <input type="checkbox" value="None" id="reason-3" name="check"  />
								      <label for="reason-3"></label>
								    </div>
								    <label for="reason-3">เหตุผล 3</label >
								</div>
								<div class="form-check">
								    <div class="squaredTwo">
								      <input type="checkbox" value="None" id="reason-4" name="check"  />
								      <label for="reason-4"></label>
								    </div>
								    <label for="reason-4">เหตุผล 4</label >
								</div> -->

								<textarea class="form-control" rows="5" placeholder="พิมพ์ข้อความ" name="cancle_comment"></textarea>

								<button class="btn btn-confirm-data">ยืนยันข้อมูล</button>

						</form>
					</div>
				</div>
			</div>
			<div class="footer"  id="footer">
				<img src="/icnow/mini/img/footer.png">
			</div>
		</div>


		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>

		<script type="text/javascript">
			$('input.checklimit-2').on('change', function(evt) {
	           if($('input.checklimit-2:checked').length > 1) {
	               this.checked = false;
	           }
	        });
		</script>


	</body>
</html>