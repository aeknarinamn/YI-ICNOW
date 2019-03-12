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
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/cart.css">
    <style type="text/css">
        input[type=text]:disabled {
          background: #FFFFFF;
        }
    </style>
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <section class="cart">
            <div class="section-title">ตะกร้าสินค้า</div>
            <div class="cart-product-list">
                <input type="hidden" id="cart-item-id">
                @foreach($datas as $data)
                    @if($data->product_name != "")
                        <div class="cart-product">
                            <div class="is-half">
                                <img src="{{$data->img_url}}" alt="">
                            </div>
                            <div class="is-half">
                                <div class="cart-product-name">{{$data->product_name}}</div>
                                <div class="product-number">
                                    <div class="product-number-label">จำนวน :</div>
                                    <div class="input-number-wrap">
                                        <input class="input-number input-number-new" name="input-number" type="text" value="{{$data->quantity}}" style="color: red;" disabled="disabled" />
                                    </div>
                                </div>
                                <div class="product-price-label">ราคา :</div>
                                @if($data->special_start_date != "" && $data->special_end_date != "" && $data->special_start_date <= $dateNow && $data->special_end_date >= $dateNow)
                                    <div class="product-price">
                                        <div class="product-real-price discount">{{number_format($data->quantity*$data->price,2)}} บาท</div>
                                        <div class="cart-product-delete">
                                            <button type="button" class="delete-btn" onclick="addRemoveCartItemId({{$data->id}})">
                                                <img class="icon-delete" src="/icnow/resources/images/icon-delete.png" alt="Delete Icon"> ลบ
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-discount">{{number_format($data->quantity*$data->special_price,2)}}
                                        <span>บาท<br/>ลด {{number_format((($data->price - $data->special_price)/$data->price)*100,2)}} %</span>
                                    </div>
                                @else
                                    <div class="product-price">
                                        <div class="product-real-price">{{number_format($data->quantity*$data->price,2)}} บาท</div>
                                        <div class="cart-product-delete">
                                            <button type="button" class="delete-btn" onclick="addRemoveCartItemId({{$data->id}})">
                                                <img class="icon-delete" src="/icnow/resources/images/icon-delete.png" alt="Delete Icon"> ลบ
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
                <!-- <div class="cart-product">
                    <div class="is-half">
                        <img src="/icnow/resources/images/product-mock-2.png" alt="">
                    </div>
                    <div class="is-half">
                        <div class="cart-product-name">สุขสุดฟิน</div>
                        <div class="product-number">
                            <div class="product-number-label">จำนวน :</div>
                            <div class="input-number-wrap">
                                <button class="decrease-btn">
                                    <img src="/icnow/resources/images/icon-decrease.png" alt="">
                                </button>
                                <input class="input-number" name="input-number" type="number" value="2" />
                                <button class="increase-btn">
                                    <img src="/icnow/resources/images/icon-increase.png" alt="">
                                </button>
                            </div>
                        </div>
                        <div class="product-price">
                            <div class="product-real-price discount">1,998 บาท</div>
                            <div class="cart-product-delete">
                                <button class="delete-btn">
                                    <img class="icon-delete" src="/icnow/resources/images/icon-delete.png" alt="Delete Icon"> ลบ
                                </button>
                            </div>
                        </div>
                        <div class="product-discount">1,776
                            <span>บาท ลด xx %</span>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="cart-summary-wrap">
                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <div class="cart-summary-column is-70">สินค้าทั้งหมด :</div>
                        <div class="cart-summary-column is-30">{{number_format($datas->sum('quantity'))}}</div>
                    </div>
                    <!-- <div class="cart-summary-row">
                        <div class="cart-summary-column is-70">คูปองส่วนลด :</div>
                        <div class="cart-summary-column is-30">
                            <div class="input-wrap">
                                <input type="text" class="input">
                            </div>
                        </div>
                    </div> -->
                    <div class="cart-summary-row">
                        <div class="cart-summary-column is-70">ยอดสุทธิ :</div>
                        <div class="cart-summary-column is-30">{{number_format($datas->sum('retial_price'))}} บาท</div>
                    </div>
                    <div class="cart-summary-row">
                        <div class="cart-summary-column cart-summary-note">* รวมค่าจัดส่งแล้ว ชำระเงินปลายทาง</div>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20">
                    <a href="/home-page" class="image-btn">
                        < ซื้อเพิ่ม
                    </a>
                    <!-- <a href="/home-page" class="image-btn">
                        <img src="/icnow/resources/images/btn-order-more.png" alt="Button">
                    </a> -->
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right">
                    <button type="button" class="add-to-cart-btn" id="cartBtn" onclick="validate({{$datas->count()}})" >
                        <img src="/icnow/resources/images/btn-continue.png" alt="Button">
                    </button>
                    <!-- <a href="#" onclick="validate({{$datas->count()}})" class="link-to">ดำเนินการต่อ</a> -->
                </div>
            </div>
        </footer>
    </div>

    <div id="confirmDeleteModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ยืนยันการลบสินค้า</div>
            <div class="modal-detail"></div>
            <div class="modal-button">
                <a href="#" onclick="removeCartItem()" class="image-btn" id="btnRemoveItem">
                    <img src="/icnow/resources/images/btn-confirm.png" alt="Button">
                </a>
                <a href="#" onclick="closeModal2()" class="image-btn" id="btnCancel">
                    <img src="/icnow/resources/images/btn-cancel.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ขออภัย</div>
            <div class="modal-detail">ไม่พบสินค้าในตะกร้าของท่าน</div>
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
    <script type="text/javascript">
        function closeModal2(){
            $('#confirmDeleteModal').hide();
        }
        function closeModal(){
            $('#alertModal').hide();
        }

        function validate(count) {
            if(count > 0){
                window.location = "/address";
            }else{
                var modal = document.getElementById('alertModal');
                modal.style.display = "block";
            }
        }
        function addRemoveCartItemId(cartItemId){
            $('#cart-item-id').val(cartItemId);
            var modal = document.getElementById('confirmDeleteModal');
            modal.style.display = "block";
        }

        function removeCartItem()
        {
            $("#btnRemoveItem").removeAttr('onclick');
            var cartItemId = $('#cart-item-id').val();
            $.ajax(
            {
                type:'get',
                url:'/shopping-cart-remove/'+cartItemId,
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
                    window.location = "/shopping-cart";
                    // alert(result);
                }
            });
        }
        $(document).ready(function () {
            
        });
    </script>
</body>

</html>