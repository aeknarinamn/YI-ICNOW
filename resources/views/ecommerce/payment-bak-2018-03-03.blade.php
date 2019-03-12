<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SCG</title>
	
	<link rel="stylesheet" href="/temp-ecommerce/confirm-payment/css/bootstrap.min.css">
	<link rel="stylesheet" href="/temp-ecommerce/confirm-payment/css/font-awesome.min.css">
	<link rel="stylesheet" href="/temp-ecommerce/confirm-payment/css/slick.css"/>
	<link rel="stylesheet" href="/temp-ecommerce/confirm-payment/css/jquery.fancybox.min.css"/>
	<link rel="stylesheet" href="/temp-ecommerce/confirm-payment/css/slick-theme.css"/>
	<link rel="stylesheet" href="/temp-ecommerce/confirm-payment/css/style.css">
</head>
<body class="bg-fff">
	<div class="clearfix"></div>
    <div class="bg-ebeced" style="padding-top: 4px;"></div>
    <div class="bg-fff">
	    <div class="container">
	    	<div class="payment-top">
		    	<h3>รายการสั่งซื้อของคุณ {{ $customer->first_name." ".$customer->last_name }}</h3>
		    	<p class="text-muted">
					รหัสการสั่งซื้อของคุณคือ : <span>{{ $order->order_id }}</span><br>
					<!-- สินค้าทั้งหมดจำนวน <span>1</span> ชิ้น<br> -->
					ยอดเงินที่ต้องชำระ <span>{{ number_format($order->total_paid,2) }}</span> บาท
				</p>
		    	<h3>การชำระเงิน</h3>
		    	<p>วิธีการชำระเงินผ่านการโอนเงิน โดยธนาคารดังต่อไปนี้</p>
	    	</div>
	    </div>
    </div>
    <div class="container">
    	<div class="payment-bank">
	    	<table class="table">
	    		<tbody>
	    			<tr>
	    				<td colspan="2"><strong>ชื่อบัญชี  บริษัท เค.โฟร์. เทรดดิ้ง จำกัด</strong></td>
	    			</tr>
	    			<tr>
	    				<td width="55" style="padding-top: 15px;"><a data-toggle="collapse" href="#collapseThree" style="text-decoration: none;"><img src="/temp-ecommerce/confirm-payment/img/bank-3.png" alt="bank"></a></td>
	    				<td style="padding-top: 15px;"><strong>บมจ.ธนาคารกสิกรไทย (KASIKORNBANK) สาขาเซ็นทรัลพลาซ่าชลบุรี เลขที่บัญชี 036-1-61765-7</strong></td>
	    			</tr>
	    			<tr>
	    				<td width="55" style="padding-top: 15px;"><a data-toggle="collapse" href="#collapseThree" style="text-decoration: none;"><img src="/temp-ecommerce/confirm-payment/img/bank-4.jpg" alt="bank"></a></td>
	    				<td style="padding-top: 15px;"><strong>บมจ.ธนาคารทหารไทย (TMB) สาขาบ้านสวน ชลบุรี เลขที่บัญชี 492-1-06205-7</strong></td>
	    			</tr>
	    			<!-- <tr>
	    				<td colspan="2" style="padding-top: 20px;">
	    					หมายเหตุ: <br>
							หากท่านต้องการสอบถาม หรือต้องการข้อมูลเพิ่มเติมเกี่ยวกับขั้นตอนการชำระเงิน กรุณาติดต่อ SCG Contact Center โทร. 02-586 2222 Line: @scg.contact.center
	    				</td>
	    			</tr> -->
	    		</tbody>
	    	</table>
    	</div>
    </div>
	<div class="container">
		<div class="text-center pagi">
			<img src="/temp-ecommerce/confirm-payment/img/car.png" alt="car">
			<span class="car-text">ฟรีค่าจัดส่งทั่วประเทศ</span>
			<h4 class="text-muted issue">สินค้าจะจัดส่งภายใน 3 วันทำการ หลังชำระเงิน</h4>
	    </div>
    </div>
    <footer class="footer">
	  	<div class="btn-group btn-group-justified">
	    	<a href="/ecommerce-payment/{{ $orderDTPayment->payment_code }}" class="btn btn-back"><i class="fa fa-chevron-left"></i> กลับ</a>
	    	<a href="/ecommerce-customer-confirm-payment/{{ $order->id }}" class="btn btn-primary">แจ้งชำระเงิน <img src="/temp-ecommerce/confirm-payment/img/checkout.png" alt="checkout"></a>
	  	</div>
    </footer>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="/temp-ecommerce/confirm-payment/js/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="/temp-ecommerce/confirm-payment/js/slick.min.js"></script>
	<script type="text/javascript" src="/temp-ecommerce/confirm-payment/js/main.js"></script>
</body>
</html>