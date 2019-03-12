<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>UFS</title>
	
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
		    	<h3>แจ้งยืนยันการชำระเงิน</h3>
		    	<form id="action-form" action="{{action('Ecommerce\CustomerConfirmPaymentController@uploadPayment')}}" method="post" enctype="multipart/form-data">
		    		{!! csrf_field() !!}
			    	<div class="row">
			    		<div class="col-xs-5">
			    			<p class="text-muted">รหัสการสั่งซื้อของคุณ</p>
			    		</div>
			    		<div class="col-xs-7">
	                        <div class="form-group">
				    			<input class="form-control" name="order_code" value="{{$order->order_id}}" disabled="disabled">
				    		</div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-xs-5">
			    			<p class="text-muted">ชำระเข้าธนาคาร</p>
			    		</div>
			    		<div class="col-xs-7">
	                        <div class="form-group">
	                            <select class="form-control" name="bank_name">
	                                <option disabled>ธนาคาร</option>
	                                <option selected>บมจ.ธนาคารไทยพาณิชย์</option>
	                                <option>บมจ.ธนาคารกรุงเทพ</option>
	                                <option>บมจ.ธนาคารกสิกรไทย</option>
	                            </select>
			    			</div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-xs-5">
			    			<p class="text-muted">เวลา</p>
			    		</div>
			    		<div class="col-xs-7">
	                        <div class="form-group">
				    			<div class="input-group time">
			                        <input type="number" class="form-control text-center" value="" name="transfer_time_hour">
			                        <div class="input-group-addon">:</div>
			                        <input type="number" class="form-control text-center" value="" name="transfer_time_minute">
			                        <div class="input-group-addon">น.</div>
			                    </div>
				    		</div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-xs-5">
			    			<p class="text-muted">ยอดเงินที่ชำระ</p>
			    		</div>
			    		<div class="col-xs-7">
	                        <div class="form-group">
				    			<div class="input-group time">
			                        <input type="number" class="form-control" value="{{ $order->total_paid }}" name="payment_amount">
			                        <div class="input-group-addon">บาท</div>
			                    </div>
				    		</div>
			    		</div>
			    	</div>
			    	<h3>หลักฐานการชำระเงิน</h3>
			    	<p class="text-center">
			    		<span>กรุณาแนบหลักฐานการชำระเงิน</span><br>
						<label for="upload-photo"><img src="/temp-ecommerce/confirm-payment/img/upload.jpg" alt="upload"></label>
						<input type="file" name="file_img" id="upload-photo" />
			    	</p>
			    	<input type="hidden" class="form-control" name="order_id" value="{{$order->id}}">
		    	</form>
	    	</div>
	    </div>
    </div>
	<div class="container">
		<div class="text-center pagi">
			<h4 class="text-muted issue">*กดที่ <i class="fa fa-file-image-o"></i> เพื่อทำการอัพโหลดไฟล์หลักฐาน*</h4>
	    </div>
    </div>
    <footer class="footer">
	  	<div class="btn-group btn-group-justified">
	    	<a href="javascript:{}" onclick="document.getElementById('action-form').submit();" class="btn btn-primary">ยืนยันการแจ้งชำระเงิน <img src="/temp-ecommerce/confirm-payment/img/checkout.png" alt="checkout"></a>
	  	</div>
    </footer>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="js/bootstrap.min.js" type="/temp-ecommerce/confirm-payment/text/javascript"></script>
	<script type="text/javascript" src="/temp-ecommerce/confirm-payment/js/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="/temp-ecommerce/confirm-payment/js/slick.min.js"></script>
	<script type="text/javascript" src="/temp-ecommerce/confirm-payment/js/main.js"></script>
</body>
</html>