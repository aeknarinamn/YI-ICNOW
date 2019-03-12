<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Proof Payment</title>
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt:400,700">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/sumoselect.min.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="/temp-ecommerce/payment-v2/vendors/css/slinky.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css">
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
                <img class="icon-cart" src="../resources/img/cart-ic.png" alt="icon cart">
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
    <form id="action-form" action="{{action('Ecommerce\CustomerConfirmPaymentController@uploadPayment')}}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="order_code" value="{{$order->order_id}}">
        <input type="hidden" name="payment_amount" value="{{ $order->total_paid }}">
        <div class="main-content">
            <div class="payment-bg"></div>
            <div class="proof-payment">
                <div class="container">
                    <div class="row">
                        <div class="column col-5 col-md-3">
                            <label for="">รายการสั่งซื้อเลขที่</label>
                            <span>:</span>
                        </div>
                        <div class="column col-7 col-md-9">{{$order->order_id}}</div>
                    </div>
                    <div class="row hr">
                        <div class="column col-5 col-md-3">
                            <label for="">ยอดค้างชำระ</label>
                            <span>:</span>
                        </div>
                        <div class="column col-7 col-md-9">{{ $order->total_paid }} บาท</div>
                    </div>
                    <div class="row align-center">
                        <div class="column col-5 col-md-3">
                            <label for="">ธนาคารที่โอน</label>
                            <span>:</span>
                        </div>
                        <div class="column col-7 col-md-9">
                            <select name="bank_name" class="SlectBox select-dropdown" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                <option>-- ระบุธนาคาร --</option>
                                <option value="บมจ.ธนาคารทหารไทย">บมจ.ธนาคารทหารไทย</option>
                                <option value="บมจ.ธนาคารกสิกรไทย">บมจ.ธนาคารกสิกรไทย</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-center">
                        <div class="column col-5 col-md-3">
                            <label for="">วันที่โอน</label>
                            <span>:</span>
                        </div>
                        <div class="column col-7 col-md-9">
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control" name="transfer_date">
                                <div class="input-group-addon">
                                    <img src="/temp-ecommerce/payment-v2/resources/img/calendar.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-center hr">
                        <div class="column col-5 col-md-3">
                            <label for="">เวลา</label>
                            <span>:</span>
                        </div>
                        <div class="column payment-time col-7 col-md-9">
                            <select name="transfer_time_hour" class="SlectBox select-dropdown" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                            </select>
                            <select name="transfer_time_minute" class="SlectBox select-dropdown" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                                <option value="32">32</option>
                                <option value="33">33</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="46">46</option>
                                <option value="47">47</option>
                                <option value="48">48</option>
                                <option value="49">49</option>
                                <option value="50">50</option>
                                <option value="51">51</option>
                                <option value="52">52</option>
                                <option value="53">53</option>
                                <option value="54">54</option>
                                <option value="55">55</option>
                                <option value="56">56</option>
                                <option value="57">57</option>
                                <option value="58">58</option>
                                <option value="59">59</option>
                                <option value="60">60</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-center">
                        <div class="column col-12">
                            <button class="btn-black" type="button" onclick="clickUploadImage()">
                                <img src="../resources/img/Upload.png" alt=""> อัพโหลดรูป
                            </button>
                        </div>
                    </div>
                    <input class="btn-black" id="file_img" name="file_img" type="file" accept="image/*" onchange="readURL(this);" hidden>
                    <!-- <div class="row align-center">
                        <div class="column col-6">
                            <button class="btn-black" type="submit">
                                <img src="/temp-ecommerce/payment-v2/resources/img/Upload.png" alt=""> อัพโหลดรูป</button>
                        </div>
                        <div class="column col-6">
                            <input class="btn-black" type="file" accept="image/*" capture="filesystem" value="ถ่ายรูป">
                            <button class="btn-black" type="submit">
                                <img src="/temp-ecommerce/payment-v2/resources/img/camera.png" alt=""> ถ่ายรูป</button>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="column col-12 example-img">
                            <img id="blah" src="/temp-ecommerce/payment-v2/resources/img/dummy-img.png" alt="">
                        </div>
                    </div>
                </div>
                <button class="button btn-link" type="submit">แจ้งชำระเงิน
                    <img src="/temp-ecommerce/payment-v2/resources/img/next@3x.png" alt="">
                </button>
            </div>
        </div>
    </form>


    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/popper.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/jquery.sumoselect.min.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/sidebar.js"></script>
    <script type="text/javascript" src="/temp-ecommerce/payment-v2/vendors/js/slinky.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        function clickUploadImage()
        {
            $( "#file_img" ).click();
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(300)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        // MENU TOGGLE 
        $('#nav-icon1,#nav-icon2,#nav-icon3,#nav-icon4').click(function () {
            $(this).toggleClass('open');
        });

        //Menu sidebar
        const slinky = $('#menu').slinky()

        // SELECT BOX
        $('.SlectBox').SumoSelect({
            forceCustomRendering: true
        });

        //DATE
        $('.datepicker').datepicker();
    </script>
</body>

</html>