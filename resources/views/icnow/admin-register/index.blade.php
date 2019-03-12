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
        <form id="form-submit" action="{{ action('ICNOW\View\AdminUserController@adminUserStore') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="line_user_id" value="{{$lineUserProfile->id}}">
            <section style="margin-bottom: 30px">
                <div class="section-title address">โปรดระบุอีเมลล์</div>
                <div class="input-wrap">
                    *Email : <input type="text" name="email" id="email" class="input">
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
                    <a href="#" onclick="submitForm()" class="link-to">บันทึกข้อมูล</a>
                </div>
                <!-- <div class="footer-btn-column is-68 is-lg-80 text-right" style="padding-right: 40px">
                    <button id="continueBtn" class="link-to">บันทึกที่อยู่ใหม่</button>
                </div> -->
            </div>
        </footer>
    </div>
    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ขออภัย</div>
            <div class="modal-detail">ท่านยังไม่ได้กรอกข้อมูลอีเมลล์ของท่าน</div>
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
            var email = $('#email').val();
            
            $isCheck = 1;
            if(email == ""){
                $isCheck = 0;
            }

            if($isCheck == 1){
                $('#form-submit').submit();
            }else{
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