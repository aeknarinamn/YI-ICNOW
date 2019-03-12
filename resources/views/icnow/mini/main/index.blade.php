<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>ICNOW</title>
		<meta name="viewport" content="width=device-width, user-scalable=no" />
		<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="/icnow/mini/css/style.css?v2">
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
		<input type="hidden" name="section_id" id="section_id">
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
    				<div class="col-md-12 col-md-offset-0 ">
						<div class="text-s1">จัดการคำสั่งซื้อ</div>
						<div class="row">
			                <div class="input-group stylish-input-group">
			                    <input type="text" class="form-control Search"  placeholder="พิมพ์เพื่อค้นหา" >
			                    <span class="input-group-addon">
			                        <button type="submit">
			                            <span class="glyphicon glyphicon-search"></span>
			                        </button>
			                    </span>
			                </div>
						</div>

						<h5 class="text-s1 mb10">คำสั่งซื้อใหม่</h5>
						<div class="scroll" 2>
			                <table>
			                	<tr>
				                	<th>#</th>
				                	<th>ชื่อ</th>
				                	<th>เบอร์โทร</th>
				                	<th>เลขที่สั่งซื้อ</th>
				                	<th class="control-order">จัดการ</th>
				                	<th>สถานะ</th>
			                	</tr>
			                	<?php $count = 1 ?>
			                	@foreach($newOrderCustomers as $key => $newOrderCustomer)
				                	<tr>
					                	<td>{{$count}}</td>
					                	<td>{{$newOrderCustomer->first_name}}</td>
					                	<td><a href="tel:{{$newOrderCustomer->phone_number}}">{{$newOrderCustomer->phone_number}}</a></td>
					                	<td>{{$newOrderCustomer->order_no}}</td>
					                	<td class="c-red"><a href="/mini-order-detail/{{$newOrderCustomer->id}}" class="myclass">ดูรายละเอียด</a></td>
					                	<td ><span style="background-color: #C3C3C3">{{$newOrderCustomer->status}}</span></td>
				                	</tr>
				                	<?php $count++; ?>
				                @endforeach
			                </table>
		            	</div>

		            	<h5 class="text-s1 mb10">รอการจัดส่ง</h5>
						<div class="scroll" >
			                <table>
			                	<tr>
				                	<th>#</th>
				                	<th>ชื่อ</th>
				                	<th>เบอร์โทร</th>
				                	<th>เลขที่สั่งซื้อ</th>
				                	<th class="control-order">จัดการ</th>
				                	<th>ส่งข้อความยืนยัน</th>
			                	</tr>
			                	<?php $count = 1 ?>
			                	@foreach($waitingDeliveries as $key => $waitingDelivery)
				                	<tr>
					                	<td>{{$count}}</td>
					                	<td>{{$waitingDelivery->first_name}}</td>
					                	<td><a href="tel:{{$waitingDelivery->phone_number}}">{{$waitingDelivery->phone_number}}</a></td>
					                	<td>{{$waitingDelivery->order_no}}</td>
					                	<td class="c-red"><a href="/mini-order-detail-cf/{{$waitingDelivery->id}}?status=wd" class="myclass">ดูรายละเอียด</a></td>
					                	<td >
					                		<button type="button" id="confirm-send" class="confirm-send" onclick="addSection({{$waitingDelivery->id}})">
					                			ยืนยันการจัดส่ง
					                		</button>
					                		<button type="button" id="cancle-order" class="confirm-send" onclick="addSectionCancle({{$waitingDelivery->id}})" style="background-color: red">
					                			ยกเลิกคำสั่งซื้อ
					                		</button>
					                	</td>
				                	</tr>
				                	<?php $count++; ?>
				                @endforeach
			                </table>
						</div>

						<h5 class="text-s1 mb10">จัดส่งเรียบร้อย</h5>
						<div class="scroll" >
			                <table>
			                	<tr>
				                	<th>#</th>
				                	<th>ชื่อ</th>
				                	<th>เบอร์โทร</th>
				                	<th>เลขที่สั่งซื้อ</th>
				                	<th class="control-order">จัดการ</th>
				                	<th>สถานะ</th>
			                	</tr>
			                	<?php $count = 1 ?>
			                	@foreach($completeDeliveries as $key => $completeDelivery)
				                	<tr>
					                	<td>{{$count}}</td>
					                	<td>{{$completeDelivery->first_name}}</td>
					                	<td><a href="tel:{{$completeDelivery->phone_number}}">{{$completeDelivery->phone_number}}</a></td>
					                	<td>{{$completeDelivery->order_no}}</td>
					                	<td class="c-red"><a href="/mini-order-detail-cf/{{$completeDelivery->id}}?status=cd" class="myclass">ดูรายละเอียด</a></td>
					                	<td ><span style="background-color: #9AFCF9">{{$completeDelivery->status}}</span></td>
				                	</tr>
				                	<?php $count++; ?>
				                @endforeach
			                </table>
						</div>

						<h5 class="text-s1 mb10">ยกเลิกรายการสั่งซื้อ</h5>
						<div class="scroll" >
			                <table>
			                	<tr>
				                	<th>#</th>
				                	<th>ชื่อ</th>
				                	<th>เบอร์โทร</th>
				                	<th>เลขที่สั่งซื้อ</th>
				                	<th class="control-order">จัดการ</th>
				                	<th>สถานะ</th>
			                	</tr>
			                	<?php $count = 1 ?>
			                	@foreach($cancleOrders as $key => $cancleOrder)
				                	<tr>
					                	<td>{{$count}}</td>
					                	<td>{{$cancleOrder->first_name}}</td>
					                	<td><a href="tel:{{$cancleOrder->phone_number}}">{{$cancleOrder->phone_number}}</a></td>
					                	<td>{{$cancleOrder->order_no}}</td>
					                	<td class="c-red"><a href="/mini-order-detail-cf/{{$cancleOrder->id}}?status=co" class="myclass">ดูรายละเอียด</a></td>
					                	<td ><span style="background-color: #FEB2C3">{{$cancleOrder->status}}</span></td>
				                	</tr>
				                	<?php $count++; ?>
				                @endforeach
			                </table>
						</div>

						<h5 class="text-s1 mb10">ยกเลิกรายการโดยระบบ</h5>
						<div class="scroll" >
			                <table>
			                	<tr>
				                	<th>#</th>
				                	<th>ชื่อ</th>
				                	<th>เบอร์โทร</th>
				                	<th>เลขที่สั่งซื้อ</th>
				                	<th class="control-order">จัดการ</th>
				                	<th>สถานะ</th>
			                	</tr>
			                	<?php $count = 1 ?>
			                	@foreach($cancleOrderSystems as $key => $cancleOrderSystem)
				                	<tr>
					                	<td>{{$count}}</td>
					                	<td>{{$cancleOrderSystem->first_name}}</td>
					                	<td><a href="tel:{{$cancleOrderSystem->phone_number}}">{{$cancleOrderSystem->phone_number}}</a></td>
					                	<td>{{$cancleOrderSystem->order_no}}</td>
					                	<td class="c-red"><a href="/mini-order-detail-cf/{{$cancleOrderSystem->id}}?status=cos" class="myclass">ดูรายละเอียด</a></td>
					                	<td ><span style="background-color: #FF3F6A">{{$cancleOrderSystem->status}}</span></td>
				                	</tr>
				                	<?php $count++; ?>
				                @endforeach
			                </table>
						</div>


				</div>
			</div>

		</div>
		<div class="footer"  id="footer">
			<img src="/icnow/mini/img/footer.png">
		</div>

		<div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog vertical-align-center" role="document">
				    <div class="modal-content ">
				      <div class="modal-body modal-confirm">
				        	<h4 class="text-3">ยืนยันการส่งข้อความ</h4>
				        	<hr>
							<div class="btn-alert-group">
								<button type="button" class="btn btn-alert btn-alert-confirm mr-b-10" onclick="confirmDeliveryOrder()" id="btnConfirmDeliveryOrder">ยืนยัน</button>
								<button class="btn btn-alert btn-back">กลับ</button>
							</div>
				      </div>
				    </div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="alert-cancle-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog vertical-align-center" role="document">
				    <div class="modal-content ">
				      <div class="modal-body modal-confirm">
				        	<h4 class="text-3">ยืนยันการยกเลิก</h4>
				        	<hr>
							<div class="btn-alert-group">
								<button type="button" class="btn btn-alert btn-alert-confirm mr-b-10" onclick="cancleOrder()" id="btnConfirmDeliveryOrder">ยืนยัน</button>
								<button class="btn btn-alert btn-back">กลับ</button>
							</div>
				      </div>
				    </div>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
		<script type="text/javascript">
			// $('#alert').modal('show');

			// $( "#confirm-send" ).click(function() {
			//   $('#alert').modal('show');
			// });
			$( ".btn-back" ).click(function() {
			  $('#alert').modal('hide');
			  $('#alert-cancle-order').modal('hide');
			});

			// $( "#cancle-order" ).click(function() {
			//   $('#alert-cancle-order').modal('show');
			// });

			function addSection(id){
				$('#alert').modal('show');
				$('#section_id').val(id);
			}

			function addSectionCancle(id){
				$('#alert-cancle-order').modal('show');
				$('#section_id').val(id);
			}

			function confirmDeliveryOrder(){
				// $("#btnConfirmDeliveryOrder").removeAttr('onclick');
				// document.getElementById("btnConfirmDeliveryOrder").disabled = false;
				$('button').prop('disabled', true);
				var id = $('#section_id').val();
				window.location = "/mini-update-status-deliver/"+id;
			}

			function cancleOrder(){
				$('button').prop('disabled', true);
				var id = $('#section_id').val();
				window.location = "/mini-cancle-order/"+id;
			}
		</script>
	</body>
</html>