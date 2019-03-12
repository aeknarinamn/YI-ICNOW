<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IC.NOW</title>
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/bulma.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/sumoselect.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/pretty-checkbox.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/datepicker.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/fonts.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/default.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/address.css">
    <style type="text/css">
          #loadingmsg {
          color: black;
          background: #fff; 
          padding: 10px;
          position: fixed;
          top: 50%;
          left: 50%;
          z-index: 100;
          margin-right: -25%;
          margin-bottom: -25%;
          }
          #loadingover {
          background: black;
          z-index: 99;
          width: 100%;
          height: 100%;
          position: fixed;
          top: 0;
          left: 0;
          -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
          filter: alpha(opacity=80);
          -moz-opacity: 0.8;
          -khtml-opacity: 0.8;
          opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <form id="form-submit" action="{{ action('ICNOW\View\CustomerOrderController@submitOrder') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="line_user_id" value="{{$lineUserProfile->id}}">
            <section>
                <div class="section-title address">ที่อยู่จัดส่ง</div>
                <!-- <div class="address-wrap">
                    <div class="radio-group">
                        @foreach($customerShippingAddresses as $customerShippingAddress)
                            <div class="pretty p-default p-round">
                                <input type="radio" name="address_id" value="{{$customerShippingAddress->id}}" @if($lineUserProfile->address_id == $customerShippingAddress->id) checked="checked" @endif/>
                                <div class="state p-success-o">
                                    <label> {{$customerShippingAddress->first_name}} {{$customerShippingAddress->last_name}}
                                        <p>{{$customerShippingAddress->address}} {{$customerShippingAddress->sub_district}}</p>
                                        <p>{{$customerShippingAddress->district}} {{$customerShippingAddress->province}} {{$customerShippingAddress->post_code}}</p>
                                    </label>
                                </div>
                            </div>
                        @endforeach()
                    </div>
                </div> -->
                <div class="deliver-date">
                    <div class="deliver-date-title"><font color="red">*</font> ชื่อ</div>
                    <div class="input-address">
                        <input type="text" name="first_name" id="first_name" value="{{ $firstname }}" class="input-here">
                    </div>
                    <div class="deliver-date-title"><font color="red">*</font> นามสกุล</div>
                    <div class="input-address">
                        <input type="text" name="last_name" id="last_name" value="{{ $lastName }}" class="input-here">
                    </div>
                    <div class="deliver-date-title"><font color="red">*</font> เบอร์ติดต่อ</div>
                    <div class="input-address">
                        <input type="tel" name="phone_number" id="phone_number" value="{{ $phoneNumber }}" class="input-here">
                    </div>
                    <div class="deliver-date-title"><font color="red">*</font> ที่อยู่</div>
                    <div class="input-address">
                        <textarea name="address" id="address" class="address-here" onclick="getLocation()" placeholder="กดเพื่อระบุที่อยู่" readonly="">{{$address}}</textarea>
                        <!-- <input type="text" name="phone_number" id="phone_number" class="address-here" autocomplete="off" value="{{$address}}" onclick="getLocation()"> -->
                    </div>
                    <div class="deliver-date-title"> รายละเอียดเพิ่มเติม
                    <div class="input-address">
                        <input type="text" name="remark" id="remark" class="input-here">
                    </div>
                    <div class="deliver-date-title"><font color="red">*</font> วันที่จัดส่ง</div>
                    <div class="date-picker">
                        <input type='text' class='datepicker-here' data-language='en' value="{{$dateNow}}" data-date-format="dd/mm/yy" data-position="top left"
                            readonly="true" name="date_of_delivery" id="date_of_delivery">
                    </div>
                    <div class="deliver-date-title"><font color="red">*</font> ช่วงเวลาจัดส่ง</div>
                    <div class="select-option">
                        <select name="time_of_delivery" id="time_of_delivery" class="SlectBox">
                            <option value="">-- ช่วงเวลาจัดส่ง --</option>
                            <option value="09.00 - 10.00">09.00 - 10.00</option>
                            <option value="10.00 - 11.00">10.00 - 11.00</option>
                            <option value="11.00 - 12.00">11.00 - 12.00</option>
                            <option value="12.00 - 13.00">12.00 - 13.00</option>
                            <option value="13.00 - 14.00">13.00 - 14.00</option>
                            <option value="14.00 - 15.00">14.00 - 15.00</option>
                            <option value="15.00 - 16.00">15.00 - 16.00</option>
                            <option value="16.00 - 17.00">16.00 - 17.00</option>
                        </select>
                    </div>
                </div>
                <div class="address-button" style="margin-bottom: 20px; margin-top: 40px">
                    <div class="check-group row">
                            <div class="box-pretty">
                                <div class="pretty p-image p-plain">
                                    <input type="checkbox" name="" value="" checked="checked" />
                                    <div class="state">
                                        <img class="image" src="/icnow/resources/images/icon-check.png">
                                        <label></label>
                                    </div>
                                </div>
                                <a href="https://www.unileverprivacypolicy.com/thai/policy.aspx" target="_blank">เงื่อนไขข้อตกลง</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="address-button" style="margin-bottom: 20px; margin-top: 20px">
                    <div class="check-group row">
                        <div class="pretty p-image p-plain">
                            <input type="checkbox" name="" value="" />
                            <div class="state">
                                <img class="image" src="/icnow/resources/images/icon-check.png">
                                <label></label>
                            </div>
                        </div>
                        <a href="https://www.unileverprivacypolicy.com/thai/policy.aspx" target="_blank">เงื่อนไขข้อตกลง</a>
                    </div>
                    <a href="/address-add" class="image-btn">
                        <img src="/icnow/resources/images/btn-add-new-address.png" alt="Button">
                    </a>
                </div> -->
            </section>
        </form>

        <!-- <div id="alertModal" class="modal">
            <div class="modal-background" id="backgroundModal"></div>
            <div class="modal-content">
                <div class="modal-title">ขออภัย</div>
                <div class="modal-detail">ท่านกรอกข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลอีกครั้ง</div>
                <div class="modal-button">
                    <a href="javascript:closeModal()" class="image-btn">
                        <img src="/icnow/resources/images/btn-cancel.png" alt="Button">
                    </a>
                </div>
            </div>
        </div> -->

        <!-- <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20" style="padding-left: 15px">
                    <a href="/home-page" class="image-btn">
                        <img src="/icnow/resources/images/btn-order-more.png" alt="Button">
                    </a>
                    <button class="link-to" onclick="backAction()">< กลับ</button>
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right" style="padding-right: 20px">
                    <button id="continueBtn" class="link-to">สั่งซื้อ</button>
                </div>
            </div>
        </footer> -->
        <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20">
                    <a href="/home-page" class="image-btn">
                        < กลับ
                    </a>
                    <!-- <a href="/home-page" class="image-btn">
                        <img src="/icnow/resources/images/btn-order-more.png" alt="Button">
                    </a> -->
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right">
                    <button type="button" class="add-to-cart-btn" id="continueBtn">
                        <img src="/icnow/resources/images/btn-buy.png" style="width: 141px; height: 41px; " alt="Button">
                    </button>
                </div>
            </div>
        </footer>
    </div>
    <div id="continueModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ยืนยันการสั่งซื้อ</div>
            <div class="modal-detail"></div>
            <div class="modal-button">
                <a href="#" onclick="submitForm()" class="image-btn" id="btnSubmitForm">
                    <img src="/icnow/resources/images/btn-confirm-order.png" alt="Button">
                </a>
                <a href="/home-page" class="image-btn">
                    <img src="/icnow/resources/images/btn-continue-shopping.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ขออภัย</div>
            <div class="modal-detail">
                <p>โปรดกรอกข้อมูลให้ครบถ้วน</p>
                <br/>
                <div id="alert-error-data"></div>
            </div>
            <div class="modal-button">
                <a href="javascript:closeModal()" class="image-btn">
                    <img src="/icnow/resources/images/btn-cancel.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <div id="waiting-modal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">กรุณารอสักครู่</div>
            <div class="modal-detail">
                <p>ระบบกำลังทำการสั่งสินค้าให้ท่าน</p>
            </div>
        </div>
    </div>



    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script>
        function getLocation(){
            var phoneNumber = $('#phone_number').val();
            var firstName = $('#first_name').val();
            var lastName = $('#last_name').val();
            $.ajax({
                method: "POST",
                url: "/api/icnow-address-add-to-cookie",
                data: { 
                    first_name: firstName,
                    last_name: lastName, 
                    phone_number: phoneNumber, 
                }
            })
            .done(function( msg ) {
                window.location.href = "/address-add";
            });
        }

        function backAction(){
            window.location.href = "/shopping-cart";
        }

        function closeModal(){
            $('#alertModal').hide();
        }
        function submitForm(){
            var modal = document.getElementById('waiting-modal');
            modal.style.display = "block";
            var modal = document.getElementById('continueModal');
            modal.style.display = "none";
            $("#btnSubmitForm").removeAttr('onclick');
            $('#form-submit').submit();
        }
        $(document).ready(function () {
            $('.SlectBox').SumoSelect();

            var modal = document.getElementById('continueModal');
            var btn = document.getElementById("continueBtn");
            var background = document.getElementById("backgroundModal");
            btn.onclick = function () {
                $isCheck = 1;
                var msgError = "";
                var dateOfDelivery = $('#date_of_delivery').val();
                var timeOfDelivery = $('#time_of_delivery').val();
                var firstName = $('#first_name').val();
                var lastName = $('#last_name').val();
                var phoneNumber = $('#phone_number').val();
                var address = $('#address').val();
                var countCheckBoxHasCheck = $(":checkbox:checked").length;
                
                if(firstName == ""){
                    msgError += "<p>กรุณากรอกชื่อ</p>";
                    $isCheck = 0;
                }
                if(lastName == ""){
                    msgError += "<p>กรุณากรอกนามสกุล</p>";
                    $isCheck = 0;
                }
                if(phoneNumber == ""){
                    msgError += "<p>กรุณากรอกเบอร์ติดต่อ</p>";
                    $isCheck = 0;
                }else{
                    if(phoneNumber.length != 10){
                        msgError += "<p>กรุณากรอกเบอร์ติดต่อเป็นตัวเลข 10 หลักเท่านั้น</p>";
                        $isCheck = 0;
                    }
                }
                if(address == ""){
                    msgError += "<p>กรุณากรอกที่อยู่</p>";
                    $isCheck = 0;
                }
                if(dateOfDelivery == ""){
                    msgError += "<p>กรุณาเลือกวันที่สำหรับจัดส่ง</p>";
                    $isCheck = 0;
                }
                if(timeOfDelivery == ""){
                    msgError += "<p>กรุณาเลือกช่วงเวลาสำหรับจัดส่ง</p>";
                    $isCheck = 0;
                }
                if(countCheckBoxHasCheck <= 0){
                    msgError += "<p>กรุณากดยอมรับเงื่อนไขและข้อตกลง</p>";
                    $isCheck = 0;
                }
                // if ($('input[name=address_id]:checked').length <= 0) {
                //     msgError += "<p>กรุณาเลือกที่อยู่สำหรับจัดส่ง</p>";
                //     $isCheck = 0;
                // }

                if($isCheck == 1){
                    var modal = document.getElementById('continueModal');
                    modal.style.display = "block";
                }else{
                    $('#alert-error-data').empty();
                    $('#alert-error-data').append(msgError);
                    var modal = document.getElementById('alertModal');
                    modal.style.display = "block";
                }
            }
            window.onclick = function (event) {
                if (event.target == background) {
                    modal.style.display = "none";
                }
            }
        });

        var actualDate = new Date();
        var newDate = new Date(actualDate.getFullYear(), actualDate.getMonth(), actualDate.getDate()+1);
       
        $(function () {
            $('.datepicker-here').datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: newDate
            });
        });

        var dp = $('.datepicker-here').datepicker().data('datepicker');

        dp.selectDate(newDate);
    </script>
</body>

</html>