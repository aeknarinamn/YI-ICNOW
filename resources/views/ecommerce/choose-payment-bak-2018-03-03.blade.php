<input id="orderId" type="hidden" value="{{ $order->id }}">
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>PAYMENTO</title>

        <!-- Bootstrap -->
        <link href="/temp-ecommerce/css/bootstrap.min.css" rel="stylesheet">
        <link href="/temp-ecommerce/css/style.css" rel="stylesheet">
        <link href="/temp-ecommerce/css/responsive.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            // $( document ).ready(function() {
            //     alert( "ready!" );
            // });
            var orderId = $('#orderId').val();
            $.ajax({
                type: "GET",
                url : "/api/ecommerce-check-order-payment/"+orderId,
                success: function(response){
                    if(response == 1){
                        window.location.href = "/ecommerce-return-thank";
                    }
                }
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="text-center" style="margin: 30px 0 10px;"><img src="/temp-ecommerce/images/card.png" width="150"></div>
            <h1 class="text-center">ช่องทางชำระเงิน</h1>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-fix-1">
                    <p>ชื่อ-สกุล: <span>{{ $customer->first_name." ".$customer->last_name }}</span></p>
                    <p>เลขที่ออเดอร์: <span>{{ $order->order_id }}</span></p>
                    <p style="margin: 0 0 30px;">ยอดเงิน: <span>{{ number_format($order->total_paid,2) }}</span></p>
                    <p class="text-center" style="margin: 0 0 15px;">
                        <a href="/ecommerce-customer-payment-detail/{{$order->id}}" type="button" class="btn btn-default btn-lg">ยืนยันการชำระเงิน</a>
                    </p>
                    <p class="text-center" style="margin: 0 0 15px;">
                        <a href="/ecommerce-customer-confirm-promptpay/{{$order->id}}" type="button" class="btn btn-default btn-lg">
                            <img src="/temp-ecommerce/images/prompt-pay.png" height="39">
                        </a>
                    </p>
                    <p class="text-center" style="margin: 0 0 15px;">
                        <a href="/ecommerce-customer-confirm-cod/{{$order->id}}" type="button" class="btn btn-default btn-lg">ชำระเงินปลายทาง</a>
                    </p>
                    <!-- <p class="text-center" style="margin: 0 0 15px;">
                        <button type="button" class="btn btn-default btn-lg">ชำระเงินแบบ PromptPAY</button>
                    </p>
                    <p class="text-center" style="margin: 0 0 15px;">
                        <button type="button" class="btn btn-default btn-lg">ชำระเงินปลายทาง</button>
                    </p>
                    <!-- <p class="text-center" style="margin: 0 0 15px;">
                        <button type="button" class="btn btn-default btn-lg">
                            <img src="/temp-ecommerce/images/visa.png" height="39">
                        </button>
                    </p>
                    <p class="text-center" style="margin: 0 0 15px;">
                        <button type="button" class="btn btn-default btn-lg">
                            <img src="/temp-ecommerce/images/rabbit.png" height="39">
                        </button>
                    </p> -->
                </div>
            </div>
                    
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/temp-ecommerce/js/bootstrap.min.js"></script>
    </body>
</html>
