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
        #form-submit{
          padding: 0 15px;
        }
        #form-submit .input-wrap .input {
            height: 35px;
            line-height: 35px;
        }
    </style>
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <form id="form-submit" action="{{ action('ICNOW\View\AddressController@addressDataUpdate') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="address_id" value="{{$customerShippingAddress->id}}">>
            <section style="margin-bottom: 30px">
                <div class="section-title address">โปรดระบุที่อยู่สำหรับการจัดส่ง</div>
                <div class="input-wrap">
                    *ชื่อ : <input type="text" name="first_name" id="first_name" class="input" value="{{$customerShippingAddress->first_name}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *นามสกุล : <input type="text" name="last_name" id="last_name" class="input" value="{{$customerShippingAddress->last_name}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *ที่อยู่ : <input type="text" name="address" id="address" class="input" value="{{$customerShippingAddress->address}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *ตำบล : <input type="text" name="sub_district" id="sub_district" class="input" value="{{$customerShippingAddress->sub_district}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *อำเภอ : <input type="text" name="district" id="district" class="input" value="{{$customerShippingAddress->district}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *จังหวัด : <input type="text" name="province" id="province" class="input" value="{{$customerShippingAddress->province}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *รหัสไปรษณีย์ : <input type="text" name="post_code" id="post_code" class="input" value="{{$customerShippingAddress->post_code}}">
                </div>
                <div class="input-wrap" style="margin-top: 60px">
                    *เบอร์ติดต่อ : <input type="text" name="phone_number" id="phone_number" class="input" value="{{$customerShippingAddress->phone_number}}">
                </div>
                <!-- <div class="address-button" style="margin-top: 60px">
                    <a href="add-address.html" class="image-btn">
                        <img src="/icnow/resources/images/btn-add-new-address.png" alt="Button">
                    </a>
                </div> -->
            </section>
        </form>
        <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20">
                    <!-- <a href="#" class="image-btn">
                        <img src="/icnow/resources/images/btn-order-more.png" alt="Button">
                    </a> -->
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right" style="padding-right: 40px">
                    <a href="#" onclick="submitForm()" class="link-to" id="btnSubmitForm">บันทึกข้อมูล</a>
                </div>
                <!-- <div class="footer-btn-column is-68 is-lg-80 text-right" style="padding-right: 40px">
                    <button id="continueBtn" class="link-to">บันทึกที่อยู่ใหม่</button>
                </div> -->
            </div>
        </footer>
    </div>
    <div id="continueModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ยืนยันการสั่งซื้อ</div>
            <div class="modal-detail"></div>
            <div class="modal-button">
                <a href="../index.html" class="image-btn">
                    <img src="/icnow/resources/images/btn-continue-shopping.png" alt="Button">
                </a>
                <a href="../thank.html" class="image-btn">
                    <img src="/icnow/resources/images/btn-confirm-order.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ขออภัย</div>
            <div class="modal-detail">
                <p>ท่านกรอกข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลอีกครั้ง</p>
                <div id="alert-error-data"></div>
            </div>
            <div class="modal-button">
                <a href="javascript:closeModal()" class="image-btn">
                    <img src="/icnow/resources/images/btn-cancel.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script>
        function closeModal(){
            $('#alertModal').hide();
        }
        function submitForm(){
            var firstName = $('#first_name').val();
            var lastName = $('#last_name').val();
            var address = $('#address').val();
            var subDistrict = $('#sub_district').val();
            var district = $('#district').val();
            var province = $('#province').val();
            var postCode = $('#post_code').val();
            var phoneNumber = $('#phone_number').val();
            var msgError = "";
            $isCheck = 1;
            if(firstName == ""){
                msgError += "<p>กรุณากรอก ชื่อ</p>";
                $isCheck = 0;
            }

            if(lastName == ""){
                msgError += "<p>กรุณากรอก นามสกุล</p>";
                $isCheck = 0;
            }

            if(address == ""){
                msgError += "<p>กรุณากรอก ที่อยู่</p>";
                $isCheck = 0;
            }

            if(subDistrict == ""){
                msgError += "<p>กรุณากรอก ตำบล</p>";
                $isCheck = 0;
            }

            if(district == ""){
                msgError += "<p>กรุณากรอก อำเภอ</p>";
                $isCheck = 0;
            }

            if(province == ""){
                msgError += "<p>กรุณากรอก จังหวัด</p>";
                $isCheck = 0;
            }

            if(postCode == ""){
                msgError += "<p>กรุณากรอก รหัสไปรษณีย์</p>";
                $isCheck = 0;
            }

            if(phoneNumber == ""){
                msgError += "<p>กรุณากรอก เบอร์โทรศัพท์</p>";
                $isCheck = 0;
            }

            if($isCheck == 1){
                $("#btnSubmitForm").removeAttr('onclick');
                $('#form-submit').submit();
            }else{
                $('#alert-error-data').empty();
                $('#alert-error-data').append(msgError);
                var modal = document.getElementById('alertModal');
                modal.style.display = "block";
            }
        }
        $(document).ready(function () {
            $('.SlectBox').SumoSelect();

            var modal = document.getElementById('continueModal');
            var btn = document.getElementById("continueBtn");
            var background = document.getElementById("backgroundModal");
            btn.onclick = function () {
                modal.style.display = "block";
            }
            window.onclick = function (event) {
                if (event.target == background) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
</body>

</html>