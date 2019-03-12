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
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <input type="hidden" id="count-party-set" value="{{$partySetCount}}">
        <form id="form-submit">
            <input type="hidden" name="product_id" value="{{$product->id}}">
            <input type="hidden" name="section_id" value="{{$product->section_id}}">
            <input type="hidden" name="line_user_id" value="{{$lineUserProfile->id}}">
            <section class="product-detail-wrap">
                <div class="section-title">{{$product->product_name}}</div>
                <div class="product-detail">
                    <div class="is-half">
                        <img src="{{$mainImage->img_url}}" alt="">
                    </div>
                    <div class="is-half">
                        {{-- <div class="product-price">
                            <div class="product-price-label">ราคา :</div>
                            @if($product->special_start_date != "" && $product->special_end_date != "" && $product->special_start_date <= $dateNow && $product->special_end_date >= $dateNow)
                                <div class="product-price-discount">{{number_format($product->special_price,2)}} บาท
                                    <span>ลด {{(($product->price - $product->special_price)/$product->price)*100}} %</span>
                                </div>
                            @else
                                <div class="product-real-price">{{number_format($product->price,2)}} บาท</div>
                            @endif
                            <!-- <div class="product-price-discount">888 บาท
                                <span>ลด xx %</span>
                            </div> -->
                        </div> --}}
                        <div class="product-number">
                            <div class="product-number-label">จำนวน :</div>
                            <div class="input-number-wrap">
                                <!-- <button type="button" class="decrease-btn-new" onclick="minusClick()">-</button> -->
                                <div class="decrease-btn-new" onclick="minusClick()">-</div>
                                <!-- <button type="button" class="decrease-btn" onclick="minusClick()">
                                    <img src="/icnow/resources/images/icon-decrease.png" alt="">
                                </button> -->
                                <input class="input-number input-number-new" type="number" value="1" id="quantity" name="quantity" min="1" onchange="changeQuantity()" readonly="" />
                                <!-- <button type="button" class="increase-btn" onclick="plusClick()">
                                    <img src="/icnow/resources/images/icon-increase.png" alt="">
                                </button> -->
                                <div class="increase-btn-new" onclick="plusClick()"> +</div>
                                <!-- <button type="button" class="increase-btn-new" onclick="plusClick()"> +</button> -->
                            </div>
                        </div>
                        <div class="cart-product">
                            @if($product->special_start_date != "" && $product->special_end_date != "" && $product->special_start_date <= $dateNow && $product->special_end_date >= $dateNow)
                                <input type="hidden" id="main-price-data" value="{{number_format($product->special_price,2)}}">
                                <div class="product-price">
                                    <div class="product-price-label">ราคา :</div>
                                    <div class="product-real-price discount">{{number_format($product->special_price,2)}} บาท</div>
                                </div>
                                <div class="product-discount"><label id="data-price">{{number_format($product->special_price,2)}}</label>
                                    <span>บาท<br/>ลด {{number_format((($product->price - $product->special_price)/$product->price)*100,2)}} %</span>
                                </div>
                            @else
                                <input type="hidden" id="main-price-data" value="{{number_format($product->price,2)}}">
                                <div class="product-price">
                                    <div class="product-price-label">ราคา :</div>
                                    <div class="product-real-price"><label id="data-price">{{number_format($product->price,2)}}</label> บาท</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="product-order-description">
                    <div class="product-order-des-inner-wrap">
                        <?php $count = 1; $countAll = 1; ?>
                        @foreach($productPartySets as $productPartySet)
                            <input type="hidden" name="items[{{$count}}][group_id]" value="{{$productPartySet->id}}">
                            <input type="hidden" name="items[{{$count}}][group_name]" value="{{$productPartySet->group_name}}">
                            <input type="hidden" name="items[{{$count}}][max_item]" id="items-value-max-{{$count}}" value="{{$productPartySet->volumn}}">
                            <input type="hidden" name="items[{{$count}}][choose_item]" id="items-value-choose-{{$count}}" value="0">
                            <input type="hidden" id="group-original-quantity-{{$count}}" value="{{$productPartySet->volumn}}">
                            <div class="product-group">
                                <div class="product-group-title ">
                                     <div class="set-position">{{$productPartySet->group_name}} ( <span><label id="group-choose-{{$count}}">0</label></span> /<label id="group-{{$count}}">{{$productPartySet->volumn}}</label> {{$productPartySet->unit}})</div>
                                </div>
                                <?php 
                                    $countMaxValue = $productPartySet->volumn;
                                    $maxCount = $productPartySet->productPartySetItems->count();
                                    $genCount = 1;
                                    $checkCount = 1;
                                ?>
                                @foreach($productPartySet->productPartySetItems as $productPartySetItem)
                                    <?php 
                                        $randValue = $productPartySetItem->default_unit;
                                        $genCount++;
                                    ?>
                                    <input type="hidden" name="items[{{$count}}][group_items][{{$productPartySetItem->id}}][item_name]" value="{{$productPartySetItem->value}}">
                                    <input type="hidden" mainId="item-main-default-data-{{$countAll}}" value="{{$productPartySetItem->default_unit}}">
                                    <div class="product-group-item @if($checkCount == 1) pt-50 @endif">
                                        <div class="product-group-item-input">
                                            <div class="input-wrap">
                                                <!-- <button type="button" class="decrease-btn" btn-main-id="{{$count}}">-</button> -->
                                                <div class="decrease-btn" btn-main-id="{{$count}}">-</div>
                                                <input type="text" class="input" name="items[{{$count}}][group_items][{{$productPartySetItem->id}}][item_value]" id="item-quantity-{{$count}}" mainId="item-main-quantity-data-{{$countAll}}" placeholder="ระบุจำนวน" onchange="inputItemQuantity({{$count}},{{$productPartySetItem->id}})" value="{{$randValue}}">
                                                <div class="increase-btn" btn-main-id="{{$count}}"> +</div>
                                                <!-- <button type="button" class="increase-btn" btn-main-id="{{$count}}"> +</button> -->
                                            </div>
                                        </div>
                                        <div class="product-group-item-label">
                                            <div>
                                                <img src="{{$productPartySetItem->img_url}}">
                                            </div>
                                            <div class="text" style="margin-top: 10px">
                                                <p >{{$productPartySetItem->value}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $countAll++; $checkCount++; ?>
                                @endforeach
                            </div>
                            <div class="line"></div>
                            <?php $count++; ?>
                        @endforeach
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


    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script type="text/javascript">
        function closeModal(){
            $('#alertModal').hide();
        }
        function saveShoppingCart()
        {
            $isCheck = 1;
            var msgError = ""; 
            var quantity = $('#quantity').val();
            if(quantity <= 0){
                msgError += "<p>ท่านไม่สามารถเลือกจำนวนเป็น 0 หรือต่ำกว่าได้</p>";
                $isCheck = 0;
            }
            var input_list = $("input[id^='items-value-max']");
            for(var i = 0; i < input_list.length; i++) {
                var index = i+1;
                var valueMax = input_list[i].value;
                var input_list_2 = $("input[id^='item-quantity-"+index+"']");
                var count = 0;
                for(var j = 0; j < input_list_2.length; j++) {
                    var value = input_list_2[j].value;
                    if(value == ""){
                        // $isCheck = 0;
                        value = 0;
                    }
                    count = count + parseInt(value);
                }
                if(valueMax < count){
                    msgError += "<p>ท่านกรอกจำนวนเกินสำหรับเซ็ทที่ "+index+"</p>";
                    $isCheck = 0;
                }
                if(valueMax > count){
                    msgError += "<p>ท่านกรอกจำนวนไม่ครบสำหรับเซ็ทที่"+index+"</p>";
                    $isCheck = 0;
                }
            }

            if($isCheck == 0){
                $('#alert-error-data').empty();
                $('#alert-error-data').append(msgError);
                var modal = document.getElementById('alertModal');
                modal.style.display = "block";
            }else{
                var formData = $("#form-submit").serialize();
                $.ajax(
                {

                    type:'post',
                    url:'/shopping-cart-add-party-set',
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
            var input_list = $("input[id^='item-quantity']");
            for(var i = 0; i < input_list.length; i++) {
                var value = input_list[i].value;
                if(value == ""){
                    value = 0;
                }
                count = count + parseInt(value);
            }
            $('#sum-total-all').text(count);
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
                if(nextValue <= valueMax){
                    if (!isNaN(currentVal)) {
                        $qty.val(currentVal + 1);
                    }
                    inputItemQuantity(mainId,itemId);
                }
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
                if(nextValue <= valueMax){
                    if (!isNaN(currentVal)) {
                        $qty.val(currentVal + 1);
                    }
                    inputItemQuantity(mainId,itemId);
                }
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