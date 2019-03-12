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
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/owl.carousel.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/fonts.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/default.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/home.css">
    <style type="text/css">
        .myButton {
            -moz-box-shadow:inset 0px 1px 0px 0px #f5978e;
            -webkit-box-shadow:inset 0px 1px 0px 0px #f5978e;
            box-shadow:inset 0px 1px 0px 0px #f5978e;
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #d12d22), color-stop(1, #c62d1f));
            background:-moz-linear-gradient(top, #d12d22 5%, #c62d1f 100%);
            background:-webkit-linear-gradient(top, #d12d22 5%, #c62d1f 100%);
            background:-o-linear-gradient(top, #d12d22 5%, #c62d1f 100%);
            background:-ms-linear-gradient(top, #d12d22 5%, #c62d1f 100%);
            background:linear-gradient(to bottom, #d12d22 5%, #c62d1f 100%);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#d12d22', endColorstr='#c62d1f',GradientType=0);
            background-color:#d12d22;
            -moz-border-radius:6px;
            -webkit-border-radius:6px;
            border-radius:6px;
            border:1px solid #d02718;
            display:inline-block;
            cursor:pointer;
            color:#ffffff;
            font-family:Arial;
            font-size:15px;
            font-weight:bold;
            padding:6px 24px;
            text-decoration:none;
            text-shadow:0px 1px 0px #810e05;
        }
        .myButton:hover {
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c62d1f), color-stop(1, #d12d22));
            background:-moz-linear-gradient(top, #c62d1f 5%, #d12d22 100%);
            background:-webkit-linear-gradient(top, #c62d1f 5%, #d12d22 100%);
            background:-o-linear-gradient(top, #c62d1f 5%, #d12d22 100%);
            background:-ms-linear-gradient(top, #c62d1f 5%, #d12d22 100%);
            background:linear-gradient(to bottom, #c62d1f 5%, #d12d22 100%);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c62d1f', endColorstr='#d12d22',GradientType=0);
            background-color:#c62d1f;
        }
        .myButton:active {
            position:relative;
            top:1px;
        }
    </style>
</head>

<body>
    <div class="container">
        <input type="hidden" id="delay-time" value="{{$bannerCarousel->delay_time}}">
        @include('icnow.layout.header')
        <section>
            <div class="owl-carousel carousel-wrap">
                @foreach($bannerCarouseImages as $bannerCarouseImage)
                    <div>
                        <img src="{{$bannerCarouseImage->image_url}}" alt="">
                    </div>
                @endforeach
            </div>
            {{-- <div class="show-product-list">
                @foreach($productPartySets as $sectionId => $productSections)
                    <div class="show-product-title">
                        <div class="show-product-title-image">
                            @if($sectionImages->where('icnow_section_id',$sectionId)->count() > 0)
                                <img src="{{$sectionImages->where('icnow_section_id',$sectionId)->first()->img_url}}" alt="">
                                <div class="show-product-subtitle">
                                    <img src="/icnow/resources/images/image-product-subtitle.png" alt="">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="is-row">
                        @foreach($productSections as $product)
                            <div class="is-50">
                                <a href="/product-detail/{{$product->id}}">
                                    <img src="{{$product->img_url}}" alt="">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div> --}}
            <div class="show-product-list">
                <div class="show-product-title">
                    @if($sectionImages->where('icnow_section_id',2)->count() > 0)
                        <div class="show-product-title-image">
                            <img src="{{$sectionImages->where('icnow_section_id',2)->first()->img_url}}" alt="">
                            <div class="show-product-subtitle">
                                <!-- <img src="/icnow/resources/images/image-product-subtitle.png" alt=""> -->
                            </div>
                        </div>
                    @endif
                </div>
                <div class="is-row">
                    @foreach($productDiys as $product)
                        <div class="is-50">
                            <a href="/product-detail/{{$product->id}}">
                                <img src="{{$product->img_url}}" alt="">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="show-product-list">
                <div class="show-product-title">
                    @if($sectionImages->where('icnow_section_id',1)->count() > 0)
                        <div class="show-product-title-image">
                            <img src="{{$sectionImages->where('icnow_section_id',1)->first()->img_url}}" alt="">
                            <div class="show-product-subtitle">
                                <img src="/icnow/resources/images/image-product-subtitle.png" alt="">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="is-row">
                    @foreach($productPartySets as $product)
                        <div class="is-50">
                            <a href="/product-detail/{{$product->id}}">
                                <img src="{{$product->img_url}}" alt="">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="show-product-list">
                <div class="show-product-title">
                    @if($sectionImages->where('icnow_section_id',3)->count() > 0)
                        <div class="show-product-title-image">
                            <img src="{{$sectionImages->where('icnow_section_id',3)->first()->img_url}}" alt="">
                            <div class="show-product-subtitle">
                                <img src="/icnow/resources/images/image-product-subtitle.png" alt="">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="is-row">
                    @foreach($productCustoms as $product)
                        <div class="is-50">
                            <a href="/product-detail/{{$product->id}}">
                                <img src="{{$product->img_url}}" alt="">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <footer>
            <div class="footer-btn">
                <a href="https://www.unileverprivacypolicy.com/thai/policy.aspx" class="link-text">Terms & condition | Privacy policy</a>
            </div>
        </footer>
        <div class="footer-image">
            <img src="/icnow/resources/images/image-home-footer.png" alt="">
        </div>
    </div>

    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">พื้นที่ให้บริการ</div>
            <div class="modal-detail">
                <p>กรุณาเลือกพื้นที่ให้บริการ</p>
                <div id="alert-error-data"></div>
            </div>
            <div class="modal-button text-center">
                <button type="button" class="myButton" onclick="addToCacheData('ชลบุรี')">ชลบุรี</button><br/>
                <button type="button" class="myButton" onclick="addToCacheData('เชียงใหม่')">เชียงใหม่</button><br/>
                <button type="button" class="myButton" onclick="location.href = '/out-service-page';">นอกพื้นที่</button>
            </div>
        </div>
    </div>

    <input type="hidden" id="is_address" value="{{$isAddress}}">
    <input type="hidden" id="line_user_id" value="{{$lineUserId}}">

    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script src="/icnow/vendors/js/owl.carousel.min.js"></script>
    <script type="text/javascript">
        function addToCacheData(province)
        {
            var lineUserId = $('#line_user_id').val();
            $.ajax({
                method: "POST",
                url: "/api/icnow-save-data-cache",
                data: { 
                    value: province,
                    line_user_id: lineUserId,
                    data_id: 1,
                }
            })
            .done(function( msg ) {
                closeModal();
                // window.location.href = "/address-add";
            });
        }
        function closeModal(){
            $('#alertModal').hide();
        }
        $(document).ready(function () {
            var isAddress = $('#is_address').val();
            if(isAddress == 0){
                var modal = document.getElementById('alertModal');
                modal.style.display = "block";
            }
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                items: 1,
                loop: true,
                margin: 10,
                autoplay:true,
                autoplayTimeout:$('#delay-time').val()*1000,
                autoplayHoverPause:true
            });
            $('.play').on('click', function () {
                owl.trigger('play.owl.autoplay', [3000])
            })
            $('.stop').on('click', function () {
                owl.trigger('stop.owl.autoplay')
            })
        });
    </script>
</body>

</html>