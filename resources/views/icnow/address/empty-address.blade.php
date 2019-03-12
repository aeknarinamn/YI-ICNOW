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
</head>

<body>
    <div>
        @include('icnow.layout.header')
        <section>
            <div class="section-title" style="padding-top: 12px;">โปรดระบุที่อยู่สำหรับการจัดส่ง</div>
            <div class="address-empty">
                คุณยังไม่มีที่อยู่สำหรับจัดส่ง
                <br/> กรุณาเพิ่มที่อยู่ใหม่ค่ะ
            </div>
            <div class="address-button">
                <a href="/address-add" class="image-btn">
                    <img src="/icnow/resources/images/btn-add-new-address.png" alt="Button">
                </a>
            </div>
        </section>
        <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20">
                    <a href="/shopping-cart" class="image-btn">
                        <img src="/icnow/resources/images/btn-order-more.png" alt="Button">
                    </a>
                </div>
                <!-- <div class="footer-btn-column is-68 is-lg-80 text-right" style="padding-right: 40px">
                    <a href="#" class="link-to">ดำเนินการต่อ</a>
                </div> -->
            </div>
        </footer>
    </div>



    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
</body>

</html>