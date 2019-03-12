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
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/profile.css">
    <style type="text/css" media="screen">
    a {
        color: white;
    }
    a span {
        color: white;
    }   
    </style>
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <input type="hidden" id="address-remove-id">
        <section class="profile-wrap">
            <div class="profile-title">ข้อมูลส่วนตัว</div>
            <div class="profile-info">
                <div class="profile-info-column is-35 is-lg-25">
                    <img class="profile-image" src="{{$lineUserProfile->avatar}}" alt="Profile Image">
                </div>
                <div class="profile-info-column is-65 is-lg-75">
                    <div class="profile-info-text">สวัสดีค่ะ</div>
                    <div class="profile-info-text">คุณ : {{$customerShippingAddress->first_name or ''}}</div>
                    <div class="profile-info-text">นามสกุล : {{$customerShippingAddress->last_name or ''}}</div>
                </div>
            </div>
            <div class="profile-address">
                <div class="profile-address-title">ที่อยู่จัดส่งที่เลือกไว้ :</div>
                <div class="profile-addres-info">{{$customerShippingAddress->first_name or ''}} {{$customerShippingAddress->last_name or ''}}</div>
                <div class="profile-addres-info">{{$customerShippingAddress->address or ''}} {{$customerShippingAddress->sub_district or ''}} {{$customerShippingAddress->district or ''}} {{$customerShippingAddress->province or ''}} {{$customerShippingAddress->post_code or ''}}</div>
            </div>
            <div class="profile-menu">
                <div class="collapsible-wrap">
                    <div class="collapsible-item">
                        <button class="collapsible">ที่อยู่จัดส่ง
                            <img class="icon-arrow" src="/icnow/resources/images/icon-arrow-down-red.png" alt="Icon">
                        </button>
                        <div class="content">
                            <div class="radio-group">
                                @foreach($customerShippingAddressAlls as $customerShippingAddressAll)
                                    <div class="radio-group-inner-wrap">
                                        <div class="pretty p-default p-round">
                                            <input type="radio" name="icon_solid" @if($customerShippingAddress->id == $customerShippingAddressAll->id) checked="checked" @endif/>
                                            <div class="state p-success-o">
                                                <label> {{$customerShippingAddressAll->first_name or ''}} {{$customerShippingAddressAll->last_name or ''}}
                                                    <p>{{$customerShippingAddressAll->address or ''}}</p>
                                                    <p>{{$customerShippingAddressAll->sub_district or ''}} {{$customerShippingAddressAll->district or ''}}</p>
                                                    <p>{{$customerShippingAddressAll->province or ''}} {{$customerShippingAddressAll->post_code or ''}}</p>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="address-adtion">
                                            <!-- <a href="/address-edit/{{$customerShippingAddressAll->id}}" class="edit-address">
                                                <img src="/icnow/resources/images/icon-edit.png" alt="Edit Icon">
                                            </a>
                                            <button type="button" class="delete-address" id="deleteBtn1" onclick="addRemoveAddressId({{$customerShippingAddressAll->id}})">
                                                <img src="/icnow/resources/images/icon-delete-gray.png" alt="">
                                            </button> -->
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="collapsible-item">
                        <button class="collapsible">รายการสั่งซื้อที่ผ่านมา
                            <img class="icon-arrow" src="/icnow/resources/images/icon-arrow-down-red.png" alt="Icon">
                        </button>
                        <div class="content">
                            <div class="historyorder-wrap">
                                @foreach($datas as $data)
                                    <input id="product-name-item-{{$data->id}}" type="hidden" value="{{$data->product_name}}">
                                    <div class="is-row">
                                        <div class="is-50">
                                            <img src="{{$data->img_url}}" alt="">
                                        </div>
                                        <div class="is-50">
                                            <div class="product-name">{{$data->product_name}}</div>
                                            <div class="product-amount">จำนวน
                                                <span>{{$data->quantity}}</span> หน่วย</div>
                                            @if($data->special_price != "")
                                                <div class="product-price">{{number_format($data->quantity*$data->price,2)}} บาท</div>
                                                <div class="product-discount">{{number_format($data->quantity*$data->special_price,2)}}
                                                    <span>บาท ลด {{(($data->price - $data->special_price)/$data->price)*100}} %</span>
                                                </div>
                                            @else
                                                <div>{{number_format($data->quantity*$data->price,2)}} บาท</div>
                                            @endif
                                            <div class="order-again-btn">
                                                <a href="#" onclick="addCartReOrder({{$data->id}})" class="image-btn">
                                                    <img src="/icnow/resources/images/btn-order-again.png" alt="Button">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="footer-text"><a href="https://www.unileverprivacypolicy.com/thai/policy.aspx"> Terms & condition | Privacy policy </a></div>
        </footer>
    </div>
    <div id="confirmDeleteModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ยืนยันลบที่อยู่</div>
            <div class="modal-detail"></div>
            <div class="modal-button">
                <a href="#" onclick="closeModal()" class="image-btn" id="btnCancel">
                    <img src="/icnow/resources/images/btn-cancel.png" alt="Button">
                </a>
                <a href="#" onclick="removeAddress()" class="image-btn">
                    <img src="/icnow/resources/images/btn-confirm.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <div id="cartModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ตะกร้าของคุณ</div>
            <div class="modal-detail">เพิ่ม <div id="add-cart-modal"></div>
                <br/>ลงในตะกร้าแล้ว</div>
            <div class="modal-button">
                <a id="confirmRecent" href="/profile-page" class="image-btn">
                    <img src="/icnow/resources/images/btn-continue-shopping.png" alt="Button">
                </a>
                <a href="/shopping-cart" class="image-btn">
                    <img src="/icnow/resources/images/btn-goto-cart.png" alt="Button">
                </a>
            </div>
        </div>
    </div>


    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var coll = document.getElementsByClassName("collapsible");
            var i;

            for (i = 0; i < coll.length; i++) {
                coll[i].addEventListener("click", function () {
                    this.classList.toggle("active");
                    var content = this.nextElementSibling;
                    if (content.style.display === "block") {
                        content.style.display = "none";
                    } else {
                        content.style.display = "block";
                    }
                });
            }

            var modal = document.getElementById('confirmDeleteModal');
            // var btn1 = document.getElementById("deleteBtn1");
            var btn2 = document.getElementById("deleteBtn2");
            var background = document.getElementById("backgroundModal");
            // var cancel = document.getElementById("btnCancel");
            // btn1.onclick = function () {
            //     modal.style.display = "block";
            // }
            btn2.onclick = function () {
                modal.style.display = "block";
            }
            window.onclick = function (event) {
                if (event.target == background) {
                    modal.style.display = "none";
                }
            }
            // cancel.onclick = function () {
                // modal.style.display = "none";
                // $('#confirmDeleteModal').hide();
            // }
        });

        function closeModal(){
            $('#confirmDeleteModal').hide();
        }

        function addRemoveAddressId(addressId){
            $('#address-remove-id').val(addressId);
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = "block";
        }

        function removeAddress(addressId){
            var addressId = $('#address-remove-id').val();
            $.ajax(
            {
                type:'get',
                url:'/address-remove/'+addressId,
                beforeSend:function()
                {
                    // launchpreloader();
                },
                complete:function()
                {
                    // stopPreloader();
                },
                success:function(result)
                {
                    window.location = "/profile-page";
                    // alert(result);
                }
            });
        }

        function addCartReOrder(itemId){
            var productName = $('#product-name-item-'+itemId).val();
            $.ajax(
            {
                type:'get',
                url:'/profile-page-recent/'+itemId,
                beforeSend:function()
                {
                    // launchpreloader();
                },
                complete:function()
                {
                    // stopPreloader();
                },
                success:function(result)
                {
                    // $('#add-cart-modal').empty();
                    // $('#add-cart-modal').append(""+productName);
                    // var modal = document.getElementById('cartModal');
                    // modal.style.display = "block";
                    window.location.replace("/shopping-cart");
                }
            });
        }
    </script>
</body>

</html>