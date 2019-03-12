<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paymant</title>
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt:400,700">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/sumoselect.min.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/slinky.min.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/resources/css/main.css">
</head>

<body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-static navbar-default header-bar">
        <div class="container-fluid">
            <button type="button" class="navbar-toggle toggle-right" data-toggle="sidebar" data-target=".sidebar-right">
                <div id="nav-icon1">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <img class="logo-default" src="/temp-ecommerce/payment-v2/resources/img/logo-default.svg" alt="LOGO">
            <!-- <button type="button" name="cart" class="cart-btn">
                <img class="icon-cart" src="/temp-ecommerce/payment-v2/resources/img/cart-ic.png" alt="icon cart">
                <span class="badge">999</span>
            </button> -->
        </div>
    </div>
    <div class="sidebar sidebar-right sidebar-animate" id="menu">
        <ul class="nav navbar-stacked">
            <li>
                <a href="#" class="main-menu">Home</a>
            </li>
            <li>
                <a href="#" class="main-menu">Categories</a>
                <ul>
                    <li>
                        <a href="#">Categories 1</a>
                        <ul>
                            <li>
                                <a href="#">Product 1</a>
                            </li>
                            <li>
                                <a href="#">Product 2</a>
                            </li>
                            <li>
                                <a href="#">Product 3</a>
                            </li>
                            <li>
                                <a href="#">Product 4</a>
                            </li>
                            <li>
                                <a href="#">Product 5</a>
                            </li>
                            <li>
                                <a href="#">Product 6</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Categories 2</a>
                        <ul>
                            <li>
                                <a href="#">Product 1</a>
                            </li>
                            <li>
                                <a href="#">Product 2</a>
                            </li>
                            <li>
                                <a href="#">Product 3</a>
                            </li>
                            <li>
                                <a href="#">Product 4</a>
                            </li>
                            <li>
                                <a href="#">Product 5</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Categories 3</a>
                        <ul>
                            <li>
                                <a href="#">Product 1</a>
                            </li>
                            <li>
                                <a href="#">Product 2</a>
                            </li>
                            <li>
                                <a href="#">Product 3</a>
                            </li>
                            <li>
                                <a href="#">Product 4</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Categories 4</a>
                        <ul>
                            <li>
                                <a href="#">Product 1</a>
                            </li>
                            <li>
                                <a href="#">Product 2</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Categories 5</a>
                        <ul>
                            <li>
                                <a href="#">Product 1</a>
                            </li>
                            <li>
                                <a href="#">Product 2</a>
                            </li>
                            <li>
                                <a href="#">Product 3</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <hr>
            <li>
                <a href="#">คะแนนสะสม</a>
            </li>
            <li>
                <a href="#">ตะกร้าสินค้า</a>
            </li>
            <li>
                <a href="#">รายการสินค้าที่ชอบ</a>
            </li>
            <li>
                <a href="#">รายการสั่งซื้อ</a>
            </li>
            <li>
                <a href="#">ข้อมูลส่วนตัว</a>
            </li>
            <li>
                <a href="#">ติดต่อเรา</a>
            </li>
        </ul>
        <div class="menu-footer">
            <p>® 2017 Unilever Food Solutions | All rights reserved</p>
        </div>
    </div>
    <div class="main-content">
        <div class="payment-bg"></div>
        <div class="payment-tab">
            <label>กรุณาเลือกช่องทางการชำระเงิน</label>
        </div>
        <div class="payment">
            <a class="payment-btn" href="/ecommerce-customer-payment-detail/{{$order->id}}">โอนเงินผ่านธนาคาร</a>
            <a class="payment-btn" href="/ecommerce-customer-confirm-cod/{{$order->id}}">ชำระเงินเมื่อได้รับสินค้า</a>
            <!-- <button class="button btn-more" type="button">ชำระเงินเมื่อได้รับสินค้า</button> -->
            <!-- <button class="button btn-more" type="button">Rabbit LINE Pay</button>
            <button class="button btn-more" type="button">Visa/Master Card</button> -->
        </div>
    </div>


    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/popper.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/jquery.sumoselect.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/sidebar.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/slinky.min.js"></script>
    <script type="text/javascript">
        // MENU TOGGLE 
        $('#nav-icon1,#nav-icon2,#nav-icon3,#nav-icon4').click(function () {
            $(this).toggleClass('open');
        });

        //Menu sidebar
        const slinky = $('#menu').slinky()
    </script>
</body>

</html>