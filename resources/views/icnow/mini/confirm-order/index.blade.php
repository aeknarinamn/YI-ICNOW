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
						<form class="form-horizontal">
							<div class="text-s1">คำสั่งซื้อ {{$orderCustomer->order_no}}</div>
							<button class="btn btn-confirm-success" disabled="">ยืนยันคำสั่งซื้อเรียบร้อยแล้ว</button>
							<div class="text-s2">รายละเอียดผู้สั่งซื้อ</div>
							  <div class="form-group">
							    <label for="name" class="col-xs-6 control-label">ชื่อ</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="name" placeholder="" value="{{$orderCustomer->first_name}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="surename" class="col-xs-6 control-label">นามสกุล</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="surename" placeholder="" value="{{$orderCustomer->last_name}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="tel" class="col-xs-6 control-label">เบอร์โทรศัพท์</label>
							    <div class="col-xs-6">
							      <input type="tel" class="form-control" id="tel" placeholder="" value="{{$orderCustomer->phone_number}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="address" class="col-xs-6 control-label">ที่อยู่</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="address" placeholder="" value="{{$orderCustomer->address}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="address" class="col-xs-6 control-label">ตำบล</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="address" placeholder="" value="{{$orderCustomer->sub_district}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="address" class="col-xs-6 control-label">อำเภอ</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="address" placeholder="" value="{{$orderCustomer->district}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="address" class="col-xs-6 control-label">จังหวัด</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="address" placeholder="" value="{{$orderCustomer->province}}" disabled="">
							    </div>
							  </div>
							<div class="form-group">
							    <label for="address" class="col-xs-6 control-label">วันที่จัดส่ง</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="address" placeholder="" value="{{$orderCustomer->date_of_delivery}}" disabled="">
							    </div>
							</div>
							<div class="form-group">
							    <label for="address" class="col-xs-6 control-label">เวลาส่ง</label>
							    <div class="col-xs-6">
							      <input type="text" class="form-control" id="address" placeholder="" value="{{$orderCustomer->time_of_delivery}}" disabled="">
							    </div>
							</div>
							<div class="address-map" id="map"></div>

							<hr>
							<h4>รายละเอียดสินค้า</h4>
							@foreach($datas as $data)
								<div class="row">
									<div class="col-sm-12">
										<img src="{{$data['image_url']}}" class="img-product">
										<label style="margin-left: 20px"><font size="5px">{{$data['product_name']}}</font><br/>
										<input class="input-number" name="input-number" type="text" value="{{$data['quantity']}}" disabled="disabled" style="margin-top: 20px; text-align: center;" /></label>
									</div>
								</div>
								@if($data['section_id'] == 1)
									<div class="row">
										<div class="col-xs-12">
											<label>ประมาณจำนวณคนในปาร์ตี้</label>
											<ul>
												<li><input type="text" class="form-control" placeholder="" value="{{$data['details']['person_in_party']}} @if($data['details']['other_option'] == '') คน @endif" disabled=""></li>
											</ul>
											@if($data['details']['other_option'] != "")
												<ul>
													<li><input type="text" class="form-control" placeholder="" value="{{$data['details']['other_option']}} คน" disabled=""></li>
												</ul>
											@endif
											<label>เน้นสินค้าจำพวก</label>
											<ul class="list">
												@foreach($data['details']['product_focus'] as $productFocus)
													<!-- <li> -->
														<div class="box"><img src="{{$productFocus}}" alt=""></div>
													<!-- </li> -->
												@endforeach
											</ul>
										</div>
									</div>
									<textarea class="form-control" rows="4" placeholder="" disabled="">{{$data['details']['comment']}}</textarea>
								@else
									<div class="row">
										<div class="col-xs-12">
											@foreach($data['details']['group_items'] as $group)
												<label>{{$group['group_name']}} ({{$group['choose_item']}}  / {{$group['max_item']}} )</label>
													@foreach($group['items'] as $item)
														@if($item['item_value'] > 0)
															<div class="form-group">
															    <label for="" class="col-xs-5 control-label vl-middle">{{$item['item_name']}}</label>
															    <div class="col-xs-5">
															      <input type="text" class="form-control" value="{{$item['item_value']}}" disabled="">
															    </div>
															    <div class="col-xs-2">
															    	<div class="vl-middle">แท่ง</div>
															    </div>
															</div>
														@endif
													@endforeach
											@endforeach
										</div>
									</div>
								@endif
								<hr>
							@endforeach
							<div class="  row-btn">
		    					<div class="col-xs-6">
		    						<div class="text-3">ยอดสุทธิ :</div>
		    					</div>
		    					<div class="col-xs-6">
		    						<div class="text-3 pd-1">{{number_format($retialPrice,2)}} <span> บาท</span></div>
		    					</div>
	    					</div>

						</form>
					</div>
				</div>
			</div>
			<div class="footer"  id="footer">
				<img src="/icnow/mini/img/footer.png">
			</div>

		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHKwJb9QKjnI9M0KUOVwosdF7JqVXO_Kc&callback=initMap" async
        defer></script>
        <script type="text/javascript">

			function initMap() {

	            var myOptions = {
	              zoom: 9,
	              center: new google.maps.LatLng(15.000682,103.728207),
	            };
	            map = new google.maps.Map(document.getElementById('map'),
	        myOptions);
	            infowindow = new google.maps.InfoWindow({
	                map:map,
	                maxWidth: 200
	            });
	            marker = new  google.maps.Marker({
	                map:map,
	                position: new google.maps.LatLng(15.000682,103.728207),
	                draggalbe:true
	            });
	            var lat = '{{$orderCustomer->lattitude}}';
	            var lng = '{{$orderCustomer->longtitude}}';
	            var latlng = {lat: parseFloat(lat), lng: parseFloat(lng)};
	            var geocoder = new google.maps.Geocoder;
	            geocoder.geocode({'location': latlng}, function(results, status) {
	              if (status === 'OK') {
	                if (results[1]) {             
	                  marker.setPosition(latlng);
	                  var rs = results[1].formatted_address;
	                  $('#pac-input').val(rs);
	                  // alert(rs);
	                  var tmp = rs.split(" ");
	                  // console.log(tmp);
	                  var tumbon_name = tmp[1];
	                  var ampur_name = tmp[3];
	                  var province_name = tmp[4];
	                  var zip_code = tmp[5];

	                  $('#lat').val(lat);
	                  $('#long').val(lng);

	                // $("#tumbon_name").val(tumbon_name);
	                // $("#ampur_name").val(ampur_name);
	                // $("#province_name").val(province_name);
	                // $("#zip_code").val(zip_code);

	                  var contentString = '<a href="https://www.google.com/maps/dir//'+lat+','+lng+'">'+results[1].formatted_address+'</a>';
	                  
	                  infowindow.setContent(contentString);
	                  infowindow.open(map, marker);

	                  // $.get("https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=AIzaSyBB1Rd5CN5S8taXbmNw-_YWGJyJ3CcFZik", function(data, status){
	                  //       console.log(data);
	                  //   });
	                } else {
	                  window.alert('No results found');
	                }
	              } else {
	                window.alert('Geocoder failed due to: ' + status);
	              }
	            });
	        }
		</script>

	</body>
</html>