<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Eplus</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/eplus/css/style.css">

</head>
<body>
	<div class="container">
		<form id="action-form" action="{{ action('Eplus\Salesman\OTPController@recieveOTP') }}" method="post">
			{!! csrf_field() !!}
			<input type="hidden" id="line_user_id" name="line_user_id" value="{{ $lineUserProfile->id }}">
			<div class="logo">
				<img src="/eplus/img/logo.png">
			</div>
			<div class="store-id">
				<div class="title font-medium otp">*OTP</div>
				<div class="title font-medium otp" id="otp-label">{{ $otpData->otp }}</div>
				<input type="number" id="otp" name="otp" class="sinput font-text" placeholder="กรุณากรอกรหัส OTP ">
				<input type="hidden" id="otp_ref" name="otp_ref" value="{{ $otpData->otp_ref }}">
				<input type="hidden" id="otp_check_data" name="otp_check_data" value="{{ $otpData->otp }}">
				<div class="font-text npass">
					<a href="#" onclick="newOTP();return false;" class="btn-npass">ขอรหัสใหม่</a>
				</div>
			</div>
			<button onclick="validate();" type="button" class="btn font-bold"> ถัดไป </button>
		</form>
	</div>

	<div class="modal bs-modal-sm  fade m-npass" id="m-npass" tabindex="-1" role="dialog"  aria-hidden="true">
	    <div class="vertical-alignment-helper">
	        <div class="modal-dialog vertical-align-center modal-dialog-point">
	           <div class="modal-content font-text" >
					<div class="modal-body c-m-npass">
						<p class="t1">ขอรหัส OTP ใหม่</p>
						<p class="t2">เรียบร้อยแล้ว</p>
						<button type="button" class="btn font-bold btn-c"> ตกลง </button>
				    </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal bs-modal-sm  fade m-npass" id="otp-not-correct" tabindex="-1" role="dialog"  aria-hidden="true">
	    <div class="vertical-alignment-helper">
	        <div class="modal-dialog vertical-align-center modal-dialog-point">
	           <div class="modal-content font-text" >
					<div class="modal-body c-m-npass">
						<p class="t1">รหัส OTP ไม่ถูกต้อง</p>
						<button type="button" class="btn font-bold btn-c"> ตกลง </button>
				    </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal bs-modal-sm  fade m-npass" id="otp-not-fill-in" tabindex="-1" role="dialog"  aria-hidden="true">
	    <div class="vertical-alignment-helper">
	        <div class="modal-dialog vertical-align-center modal-dialog-point">
	           <div class="modal-content font-text" >
					<div class="modal-body c-m-npass">
						<p class="t1">กรุณากรอกรหัส OTP</p>
						<button type="button" class="btn font-bold btn-c"> ตกลง </button>
				    </div>
	            </div>
	        </div>
	    </div>
	</div>

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
	<script type="text/javascript">
		function validate() {
        	var otp = $('#otp').val();
        	var otpCheckData = $('#otp_check_data').val();
        	var isSubmit = 1;

        	if(otp == ''){
        		isSubmit = 0;
        		$('#otp-not-fill-in').modal('show');
        	}else{
        		if(otp != otpCheckData){
	        		isSubmit = 0;
			  		$('#otp-not-correct').modal('show');
	        	}
        	}
        	

        	if(isSubmit){
            	$('#action-form').submit();
	        }
		}

		function newOTP(){
        	var lineUserId = $('#line_user_id').val();
        	$.ajax({
	      		url: "/eplus-salesman-re-otp/"+lineUserId,
	      		success: function( result ) {
	      			console.log(result.otpData.otp);
	      			$('#otp-label').empty();
	      			$('#otp-label').append(result.otpData.otp);
	      			$('#otp_ref').val(result.otpData.otp_ref);
        			$('#otp_check_data').val(result.otpData.otp);
	      			$('#m-npass').modal('show');
			    }
	        });
		}

		// $( ".btn-npass" ).click(function() {
		//   	$('#m-npass').modal('show');
		// });

		$( ".btn-c" ).click(function() {
		  	$('#m-npass').modal('hide');
		  	$('#otp-not-correct').modal('hide');
		  	$('#otp-not-fill-in').modal('hide');
		});

	</script>
</body>



</html>