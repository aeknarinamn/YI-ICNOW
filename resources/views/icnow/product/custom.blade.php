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
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/product-detail.css">
    <style type="text/css">
        .set-position {
            width: 80%;
            max-width: 500px;
            padding-left: 30px;
            padding-right: 10px;
        }
        .f-right{
            float: right;
        }
        .product-group .product-group-item-label .text {
            margin-left: 10px;
            width: 80%;
        }

        .product-group .product-group-item-label .text span{
            float: right;
        }
    </style>
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <input type="hidden" id="count-party-set" value="{{$partySetCount}}">
        <form id="form-submit">
            <input type="hidden" name="product_id" value="{{$product->id}}">
            <input type="hidden" name="section_id" value="{{$product->section_id}}">
            <input type="hidden" name="line_user_id" value="{{$lineUserProfile->id}}">
            <input type="hidden" name="total_price" id="total_price" value="0">
            <input type="hidden" name="price_min" id="price_min" value="{{$product->price}}">
            <section class="product-detail-wrap">
                <div class="section-title">{{$product->product_name}}</div>
                <div class="product-detail">
                    <div class="is-half">
                        <img src="{{$mainImage->img_url}}" alt="">
                    </div>
                    <div class="is-half">
                        <div class="cart-product">
                            <input type="hidden" id="main-price-data" value="{{number_format($product->price,2)}}">
                            <div class="product-price">
                                <div class="product-price-label">วิธีเลือก : เลือกสินค้าแยกตามชนิดให้ครบ {{number_format($product->price,2)}} บาท แล้วกดใส่ตะกร้า</div>
                            </div>
                            <div class="cart-product" style="margin-top : 35%;"><div class="product-discount">รวมราคา <label id="data-price-total-main">0</label> บาท</div></div>
                        </div>
                    </div>
                </div>
                <div class="product-order-description">
                    <div class="product-order-des-inner-wrap">
                        <?php $count = 1; $countAll = 1; ?>
                        @foreach($productPartySets as $productPartySet)
                            <input type="hidden" name="items[{{$count}}][group_id]" value="{{$productPartySet->id}}">
                            <input type="hidden" name="items[{{$count}}][group_name]" value="{{$productPartySet->group_name}}">
                            <input type="hidden" name="items[{{$count}}][unit]" value="{{$productPartySet->unit}}">
                            <input type="hidden" name="items[{{$count}}][max_item]" id="items-value-max-{{$count}}" value="{{$productPartySet->volumn}}">
                            <input type="hidden" name="items[{{$count}}][choose_item]" id="items-value-choose-{{$count}}" value="0">
                            <input type="hidden" id="group-original-quantity-{{$count}}" value="{{$productPartySet->volumn}}">
                            <div class="product-group">
                                <div class="product-group-title ">
                                     <div class="set-position" id="box-{{$count}}">
                                        <label id="box-{{$count}}-position">+</label> {{$productPartySet->group_name}} 
                                        <div class="f-right">
                                            ( <span><label id="group-choose-{{$count}}">0</label></span> {{$productPartySet->unit}})
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    $countMaxValue = $productPartySet->volumn;
                                    $maxCount = $productPartySet->productCustomItems->count();
                                    $genCount = 1;
                                    $checkCount = 1;
                                ?>
                                <div style="display: none" class="box-{{$count}}">
                                @foreach($productPartySet->productCustomItems as $productPartySetItem)
                                    <?php 
                                        $randValue = $productPartySetItem->default_unit;
                                        $genCount++;
                                    ?>
                                    <input type="hidden" name="items[{{$count}}][group_items][{{$productPartySetItem->id}}][item_name]" value="{{$productPartySetItem->value}}">
                                    <input type="hidden" name="items[{{$count}}][group_items][{{$productPartySetItem->id}}][price]" value="{{$productPartySetItem->price}}">
                                    <input type="hidden" mainId="item-main-default-data-{{$countAll}}" value="{{$productPartySetItem->default_unit}}">
                                    <input type="hidden" mainId="item-main-price-data-{{$countAll}}" value="{{$productPartySetItem->price}}">
                                    <div class="product-group-item @if($checkCount == 1) pt-50 @endif">
                                        <div class="product-group-item-input">
                                            <div class="input-wrap">
                                                <!-- <button type="button" class="decrease-btn" btn-main-id="{{$count}}">-</button> -->
                                                <div class="decrease-btn" btn-main-id="{{$count}}">-</div>
                                                <input type="text" class="input" name="items[{{$count}}][group_items][{{$productPartySetItem->id}}][item_value]" id="item-quantity-{{$count}}" mainId="item-main-quantity-data-{{$countAll}}" price="{{$productPartySetItem->price}}" placeholder="ระบุจำนวน" onchange="inputItemQuantity({{$count}},{{$productPartySetItem->id}})" value="{{$randValue}}">
                                                <div class="increase-btn" btn-main-id="{{$count}}"> +</div>
                                                <!-- <button type="button" class="increase-btn" btn-main-id="{{$count}}"> +</button> -->
                                            </div>
                                        </div>
                                        <div class="product-group-item-label">
                                            <div style="width: 35%">
                                                <img src="{{$productPartySetItem->img_url}}">
                                            </div>
                                            <div class="text" style="margin-top: 10px; width: 100%">
                                                <p >{{$productPartySetItem->value}}</p>
                                            </div>
                                            <div class="text text-right" style="margin-top: 10px">
                                                <p >{{$productPartySetItem->price}} บ.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $countAll++; $checkCount++; ?>
                                @endforeach
                                </div>
                            </div>
                            <div class="line"></div>
                            <br/>
                            <?php $count++; ?>
                        @endforeach
                        <div class="cart-product" style="margin-left: 10px"><div class="product-discount">รวมราคา <label id="data-price-total">0</label> บาท</div></div>
                    </div>
                </div>
            </section>
        </form>
        <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20">
                    <a href="/home-page" class="image-btn">
                        < กลับ
                    </a>
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right">
                    <div class="add-to-cart-wrap">
                        <button type="button" class="add-to-cart-btn" id="cartBtn">
                            <img src="/icnow/resources/images/btn-add-cart.png" alt="Button">
                        </button>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div id="cartModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ตะกร้าของคุณ</div>
            <div class="modal-detail">เพิ่ม {{$product->product_name}}
                <br/>ลงในตะกร้าแล้ว</div>
            <div class="modal-button">
                <a href="/home-page" class="image-btn">
                    <img src="/icnow/resources/images/btn-continue-shopping.png" alt="Button">
                </a>
                <a href="/shopping-cart" class="image-btn">
                    <img src="/icnow/resources/images/btn-goto-cart.png" alt="Button">
                </a>
            </div>
        </div>
    </div>
    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ขออภัย</div>
            <div class="modal-detail">
                <!-- <p>ท่านกรอกข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลอีกครั้ง</p> -->
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
    <script type="text/javascript">
        function closeModal(){
            $('#alertModal').hide();
        }


        function setCookie(cname, cvalue, exdays) {
          var d = new Date();
          d.setTime(d.getTime() + (exdays*24*60*60*1000));
          var expires = "expires="+ d.toUTCString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        
        function getCookie(cname) {
          var name = cname + "=";
          var decodedCookie = decodeURIComponent(document.cookie);
          var ca = decodedCookie.split(';');
          for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "";
        }
        $(".set-position").click(function(evt){
            var showId = '.'+$(this).attr("id");
            var click_name = 'click'+$(this).attr("id");
            var click = getCookie(click_name);
            if(click != ''){
                if(click == 1){
                    $('#'+$(this).attr("id")+'-position').text('+');
                    $(showId).hide();
                    setCookie(click_name,0,1);
                }else{
                    $('#'+$(this).attr("id")+'-position').text('-');
                    $(showId).show();
                    setCookie(click_name,1,1);
                }
            }else{
                $('#'+$(this).attr("id")+'-position').text('-');
                setCookie(click_name,1,1);
                $(showId).show();
            }

        });


        function saveShoppingCart()
        {
            var modal = document.getElementById('waiting-modal');
            modal.style.display = "block";
            $isCheck = 1;
            var msgError = ""; 
            var totalPrice = $('#total_price').val();
            var priceMin = $('#price_min').val();
            if(parseInt(totalPrice) < parseInt(priceMin)){
                msgError += "<p>ท่านเลือกซื้อสินค้าราคาต่ำกว่าเกณฑ์ที่ตั้งไว้</p>";
                $isCheck = 0;
            }
            // var input_list = $("input[id^='items-value-max']");
            // for(var i = 0; i < input_list.length; i++) {
            //     var index = i+1;
            //     var valueMax = input_list[i].value;
            //     var input_list_2 = $("input[id^='item-quantity-"+index+"']");
            //     var count = 0;
            //     for(var j = 0; j < input_list_2.length; j++) {
            //         var value = input_list_2[j].value;
            //         if(value == ""){
            //             // $isCheck = 0;
            //             value = 0;
            //         }
            //         count = count + parseInt(value);
            //     }
            //     if(valueMax < count){
            //         msgError += "<p>ท่านกรอกจำนวนเกินสำหรับเซ็ทที่ "+index+"</p>";
            //         $isCheck = 0;
            //     }
            //     if(valueMax > count){
            //         msgError += "<p>ท่านกรอกจำนวนไม่ครบสำหรับเซ็ทที่"+index+"</p>";
            //         $isCheck = 0;
            //     }
            // }

            if($isCheck == 0){
                modal.style.display = "none";
                $('#alert-error-data').empty();
                $('#alert-error-data').append(msgError);
                var modal = document.getElementById('alertModal');
                modal.style.display = "block";
            }else{
                var formData = $("#form-submit").serialize();
                $.ajax(
                {

                    type:'post',
                    url:'/shopping-cart-add-custom',
                    data:formData,
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
                        //-----------------------comment on 2018-11-02-----------------------------
                        // var modal = document.getElementById('cartModal');
                        // modal.style.display = "block";
                        //-----------------------comment on 2018-11-02-----------------------------
                        window.location.replace("/shopping-cart");
                         // alert(result);
                    }
                });

                // modal.style.display = "block";
            }
        }

        function changeQuantity(){ 
            var quantity = $('#quantity').val();
            var input_list = $("input[mainId^='item-main-quantity-data']");
            var priceData = $('#main-price-data').val();
            var newPrice = parseInt(priceData) * quantity;
            $('#data-price').text(newPrice.toFixed(2));
            for(var i = 1; i <= input_list.length; i++) {
                var value = $('input[mainId=item-main-quantity-data-'+i+']').val();
                var defaultData = $('input[mainId=item-main-default-data-'+i+']').val();
                if(value == ""){
                    // $isCheck = 0;
                    value = 0;
                }
                value = parseInt(value)+parseInt(defaultData);
                // input_list[i].value = value;
                // alert($('input[mainId=item-main-quantity-data-'+i+']').val());
                $('input[mainId=item-main-quantity-data-'+i+']').val(value);
                // $("input[mainId^='item-main-quantity-data-'+"+i+"]").val(value);
            }
            var countPartySet = $('#count-party-set').val();
            for (i = 1; i <= countPartySet; i++) { 
                var groupQuantity = $('#group-original-quantity-'+i).val();
                var newGroupQuantity = groupQuantity*quantity;
                $('#group-'+i).text(newGroupQuantity);
                $('#items-value-max-'+i).val(newGroupQuantity);
            }
            sumGroupData();
            // console.log(quantity);
        }

        function changeQuantityMinus(){ 
            var quantity = $('#quantity').val();
            var input_list = $("input[mainId^='item-main-quantity-data']");
            var priceData = $('#main-price-data').val();
            var newPrice = parseInt(priceData) * quantity;
            $('#data-price').text(newPrice.toFixed(2));
            for(var i = 1; i <= input_list.length; i++) {
                var value = $('input[mainId=item-main-quantity-data-'+i+']').val();
                var defaultData = $('input[mainId=item-main-default-data-'+i+']').val();
                if(value == ""){
                    // $isCheck = 0;
                    value = 0;
                }
                value = parseInt(value)-parseInt(defaultData);
                if(value < 0){
                    value = 0;
                }
                // input_list[i].value = value;
                // alert($('input[mainId=item-main-quantity-data-'+i+']').val());
                $('input[mainId=item-main-quantity-data-'+i+']').val(value);
                // $("input[mainId^='item-main-quantity-data-'+"+i+"]").val(value);
            }
            var countPartySet = $('#count-party-set').val();
            for (i = 1; i <= countPartySet; i++) { 
                var groupQuantity = $('#group-original-quantity-'+i).val();
                var newGroupQuantity = groupQuantity*quantity;
                $('#group-'+i).text(newGroupQuantity);
                $('#items-value-max-'+i).val(newGroupQuantity);
            }
            sumGroupData();
            // console.log(quantity);
        }

        function inputItemQuantity(mainId,itemId){
            var count = 0;
            var input_list = $("input[id^='item-quantity-"+mainId+"']");
            // console.log(input_list.length);
            for(var i = 0; i < input_list.length; i++) {
                var value = input_list[i].value;
                if(value == ""){
                    value = 0;
                }
                // either way should get you the value
                // console.debug(input_list[i].value, input_list[i].getAttribute('value'));
                // console.log(input_list[i].value);
                count = count + parseInt(value);
            }
            // console.log(count);
            //--------------------comment 2018-11-02------------------------
            // $('#sum-group-'+mainId).text(count);
            //--------------------comment 2018-11-02------------------------
            $('#group-choose-'+mainId).text(count);
            $('#items-value-choose-'+mainId).val(count);
            sumTotal();
        }

        function sumTotal(){
            var count = 0;
            var priceAll = 0;
            var input_list = $("input[id^='item-quantity']");
            for(var i = 0; i < input_list.length; i++) {
                var value = input_list[i].value;
                var price = input_list[i].getAttribute("price");
                if(value == ""){
                    value = 0;
                }
                if(price == ""){
                    price = 0;
                }
                count = count + parseInt(value);
                priceAll = priceAll + (parseInt(price) * parseInt(value));
            }
            $('#sum-total-all').text(count);
            $('#data-price-total').text(priceAll);
            $('#data-price-total-main').text(priceAll);
            $('#total_price').val(priceAll);
            // console.log(count);
        }

        function minusClick(){
            var oldQty = $("#quantity").val();
            var qty = $("#quantity").val();
            qty = parseInt(qty);

            var qty_new = qty-1;
            if(qty_new <= 0 ){
                qty_new = 1;
            }
            $("#quantity").val(qty_new);
            if(oldQty - 1 > 0){
                changeQuantityMinus();
            }
        }

        function plusClick(){
            var qty = $("#quantity").val();
            qty = parseInt(qty);

            var qty_new = qty+1;
            
            $("#quantity").val(qty_new);
            changeQuantity();
        }

        function decrease(mainId,itemId){
            var $qty=$(this).closest('.input-number-wrap').find('.input-number');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 0) {
                $qty.val(currentVal - 1);
            }

            var $qty=$(this).closest('.input-wrap').find('.input');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 0) {
                $qty.val(currentVal - 1);
            }

            inputItemQuantity(mainId,itemId);
        }

        function increase(mainId,itemId){
            $('.increase-btn').on('click',function(){
                var $qty=$(this).closest('.input-number-wrap').find('.input-number');
                var currentVal = parseInt($qty.val());
                if (!isNaN(currentVal)) {
                    $qty.val(currentVal + 1);
                }
            });

            var $qty=$(this).closest('.input-wrap').find('.input');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }

            inputItemQuantity(mainId,itemId);
        }

        function sumGroupData(){
            var countPartySet = $('#count-party-set').val();
            for (i = 1; i <= countPartySet; i++) { 
                inputItemQuantity(i,i);
            }
        }

        $(document).ready(function () {
            sumGroupData();
            sumTotal();
            var modal = document.getElementById('cartModal');
            var btn = document.getElementById("cartBtn");
            var background = document.getElementById("backgroundModal");
            btn.onclick = function () {
                saveShoppingCart();
                // modal.style.display = "block";
            }
            window.onclick = function (event) {
                if (event.target == background) {
                    modal.style.display = "none";
                }
            }

            $('.increase-btn').on('click',function(){
                var mainId = $(this).attr( "btn-main-id" );
                var itemId = 0;
                var $qty=$(this).closest('.input-number-wrap').find('.input-number');
                var currentVal = parseInt($qty.val());
                var valueMax = parseInt($("#items-value-max-"+mainId).val());
                var checkValueData = document.getElementById('group-choose-'+mainId).textContent;
                var nextValue = parseInt(checkValueData) + 1;
                // if(nextValue <= valueMax){
                    if (!isNaN(currentVal)) {
                        $qty.val(currentVal + 1);
                    }
                    inputItemQuantity(mainId,itemId);
                // }
            });
            $('.decrease-btn').on('click',function(){
                var mainId = $(this).attr( "btn-main-id" );
                var itemId = 0;
                var $qty=$(this).closest('.input-number-wrap').find('.input-number');
                var currentVal = parseInt($qty.val());
                if (!isNaN(currentVal) && currentVal > 0) {
                    $qty.val(currentVal - 1);
                }
                inputItemQuantity(mainId,itemId);
            });


            $('.increase-btn').on('click',function(){
                var mainId = $(this).attr( "btn-main-id" );
                var itemId = 0;
                var $qty=$(this).closest('.input-wrap').find('.input');
                var currentVal = parseInt($qty.val());
                var valueMax = parseInt($("#items-value-max-"+mainId).val());
                var checkValueData = document.getElementById('group-choose-'+mainId).textContent;
                var nextValue = parseInt(checkValueData) + 1;
                // if(nextValue <= valueMax){
                    if (!isNaN(currentVal)) {
                        $qty.val(currentVal + 1);
                    }
                    inputItemQuantity(mainId,itemId);
                // }
            });
            $('.decrease-btn').on('click',function(){
                var mainId = $(this).attr( "btn-main-id" );
                var itemId = 0;
                var $qty=$(this).closest('.input-wrap').find('.input');
                var currentVal = parseInt($qty.val());
                if (!isNaN(currentVal) && currentVal > 0) {
                    $qty.val(currentVal - 1);
                }
                inputItemQuantity(mainId,itemId);
            });
        });
    </script>
</body>

</html>