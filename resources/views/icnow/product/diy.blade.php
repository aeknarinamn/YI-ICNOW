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
        .hidden {
          visibility: hidden;
        }
    </style>
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <form id="form-submit">
            <input type="hidden" name="product_id" value="{{$product->id}}">
            <input type="hidden" name="section_id" value="{{$product->section_id}}">
            <input type="hidden" name="line_user_id" value="{{$lineUserProfile->id}}">
            <input type="hidden" name="is_other_option" id="is_other_option" value="0">
            <input type="hidden" name="max_person_in_party" id="max_person_in_party" value="{{ $productDiyPersons->max('value') }}">
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
                        </div> --}}
                        <input class="input-number" id="quantity" name="quantity" min="0" type="hidden" value="1" />
                        <!-- <div class="product-number">
                            <div class="product-number-label">จำนวน :</div>
                            <div class="input-number-wrap">
                                <button type="button" class="decrease-btn" onclick="minusClick()">
                                    <img src="/icnow/resources/images/icon-decrease.png" alt="">
                                </button>
                                <input class="input-number" id="quantity" name="quantity" min="0" type="number" value="1" />
                                <button type="button" class="increase-btn" onclick="plusClick()">
                                    <img src="/icnow/resources/images/icon-increase.png" alt="">
                                </button>
                            </div>
                        </div> -->
                        <div class="product-number">
                            <div class="product-number-label"><font color="red">*</font> จำนวนคนในปาร์ตี้ :</div>
                            <div class="select-option">
                                <select name="person_in_party" id="person_in_party" class="SlectBox" onchange="checkOtherOption(this.options[this.selectedIndex].getAttribute('optionType'))">
                                    <option selected disabled value="">-- โปรดระบุ --</option>
                                    @foreach($productDiyPersons as $productDiyPerson)
                                        <option value="{{$productDiyPerson->value}}" optionType="normal">{{$productDiyPerson->value}}</option>
                                    @endforeach
                                    @if($productDiyOtherOptions->count() > 0)
                                        @foreach($productDiyOtherOptions as $productDiyOtherOption)
                                            <option value="{{$productDiyOtherOption->value}}" optionType="other">{{$productDiyOtherOption->value}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="input-wrap" style="margin-top: 5px">
                                <input type="number" name="other_option" id="other-option" class="input hidden" placeholder="ระบุจำนวน" style="text-align:center;" min="0">
                            </div>
                        </div>
                        <div class="cart-product">
                            <div class="product-price-label">ราคา :</div>
                            @if($product->special_start_date != "" && $product->special_end_date != "" && $product->special_start_date <= $dateNow && $product->special_end_date >= $dateNow)
                                <div class="product-price">
                                    <div class="product-real-price discount">{{number_format($product->price,2)}} บาท</div>
                                </div>
                                <div class="product-discount">{{number_format($product->special_price,2)}}
                                    <span>บาท<br/>ลด {{number_format((($product->price - $product->special_price)/$product->price)*100,2)}} %</span>
                                </div>
                            @else
                                <div class="product-price">
                                    <div class="product-real-price">{{number_format($product->price,2)}} บาท</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="product-order-description">
                    <div class="product-selec-detail">
                        <div class="product-selec-detail-row">
                            <div class="product-selec-detail-column is-100">
                                <!-- <div class="select-option">
                                    <div class="select-option-title">ประมาณจำนวนคนในปาร์ตี้</div>
                                    <select name="person_in_party" id="person_in_party" class="SlectBox" onchange="checkOtherOption(this.options[this.selectedIndex].getAttribute('optionType'))">
                                        <option selected disabled value="">-- โปรดระบุ --</option>
                                        @foreach($productDiyPersons as $productDiyPerson)
                                            <option value="{{$productDiyPerson->value}}" optionType="normal">{{$productDiyPerson->value}}</option>
                                        @endforeach
                                        @if($productDiyOtherOptions->count() > 0)
                                            @foreach($productDiyOtherOptions as $productDiyOtherOption)
                                                <option value="{{$productDiyOtherOption->value}}" optionType="other">{{$productDiyOtherOption->value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-wrap" style="margin-top: 5px">
                                    <input type="number" name="other_option" id="other-option" class="input hidden" placeholder="ระบุจำนวน" style="text-align:center;" min="0">
                                </div> -->
                                <div class="check-group">
                                    <div class="check-group-tite">เน้นสินค้าจำพวก</div>
                                    <div class="box-pretty">
                                        @foreach($productDiyProductFocuses as $productDiyProductFocus)
                                            <div class="pretty p-image p-plain">
                                                <input type="checkbox" name="product_focus[]" value="{{$productDiyProductFocus->value}}" />
                                                <div class="state">
                                                    <img class="image" src="/icnow/resources/images/icon-check.png">
                                                    <label></label>
                                                    <!-- <label>{{$productDiyProductFocus->value}}</label> -->
                                                </div>
                                                <img src="{{$productDiyProductFocus->img_url}}" class="pretty-image-product">
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- <div class="pretty p-image p-plain">
                                        <input type="checkbox" />
                                        <div class="state">
                                            <img class="image" src="/icnow/resources/images/icon-check.png">
                                            <label>แท่ง</label>
                                        </div>
                                    </div>
                                    <div class="pretty p-image p-plain">
                                        <input type="checkbox" />
                                        <div class="state">
                                            <img class="image" src="/icnow/resources/images/icon-check.png">
                                            <label>โคน</label>
                                        </div>
                                    </div>
                                    <div class="pretty p-image p-plain">
                                        <input type="checkbox" />
                                        <div class="state">
                                            <img class="image" src="/icnow/resources/images/icon-check.png">
                                            <label>ถ้วย</label>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <!-- <div class="product-selec-detail-column is-40"> -->
                                <!-- <div class="check-group"> -->
                                    <!-- <div class="check-group-tite">เน้นสินค้าจำพวก</div>
                                    @foreach($productDiyProductFocuses as $productDiyProductFocus)
                                        <div class="pretty p-image p-plain">
                                            <input type="checkbox" name="product_focus[]" value="{{$productDiyProductFocus->value}}" />
                                            <div class="state">
                                                <img class="image" src="/icnow/resources/images/icon-check.png">
                                                <label>{{$productDiyProductFocus->value}}</label>
                                            </div>
                                        </div>
                                    @endforeach -->


                                    <!-- <div class="pretty p-image p-plain">
                                        <input type="checkbox" />
                                        <div class="state">
                                            <img class="image" src="/icnow/resources/images/icon-check.png">
                                            <label>แท่ง</label>
                                        </div>
                                    </div>
                                    <div class="pretty p-image p-plain">
                                        <input type="checkbox" />
                                        <div class="state">
                                            <img class="image" src="/icnow/resources/images/icon-check.png">
                                            <label>โคน</label>
                                        </div>
                                    </div>
                                    <div class="pretty p-image p-plain">
                                        <input type="checkbox" />
                                        <div class="state">
                                            <img class="image" src="/icnow/resources/images/icon-check.png">
                                            <label>ถ้วย</label>
                                        </div>
                                    </div> -->


                                <!-- </div> -->
                            <!-- </div> -->
                        </div>
                        <div class="product-selec-detail-row">
                            <div class="control textarea-wrap">
                                <label for="description">ระบุสินค้าที่อยากได้ (ถ้ามี)<small><!-- <span id="spnCharLeft">( 0/100 )</span> --></small></label>
                                <textarea class="textarea" type="text" name="comment" id="description" maxlength="100" placeholder='"ตัวอย่าง" ขอ Cornetto เยอะๆ หน่อยค่าาาา'></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>

        <footer>
            <div class="footer-btn">
                <!-- <div class="footer-btn-column is-32 is-lg-20">
                    <a href="/home-page" class="image-btn">
                        <img src="/icnow/resources/images/btn-back.png" alt="Button">
                    </a>
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right">
                    <div class="add-to-cart-wrap">
                        <button type="button" class="add-to-cart-btn" id="cartBtn">
                            ใส่ตะกร้า
                            <img src="/icnow/resources/images/icon-cart.png" alt="">
                        </button>
                    </div>
                </div> -->
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
        function checkOtherOption(value){
            if(value == "normal"){
                $("#other-option").addClass('hidden');
                $("#other-option").val("");
                $("#is_other_option").val(0);
                // alert('normal');
            }else{
                // alert('other option');
                $("#other-option").removeClass('hidden');
                $("#is_other_option").val(1);
            }
        }
        function closeModal(){
            $('#alertModal').hide();
        }
        function saveShoppingCart()
        {
            var modal = document.getElementById('waiting-modal');
            modal.style.display = "block";
            $isCheck = 1;
            var msgError = ""; 
            var quantity = $('#quantity').val();
            var personInParty = $('#person_in_party').val();
            var isOtherOption = $('#is_other_option').val();
            var otherOption = $('#other-option').val();
            var maxPersonInparty = $('#max_person_in_party').val();

            var countCheckBoxHasCheck = $(":checkbox:checked").length;
            if(quantity <= 0){
                msgError += "<p>ท่านไม่สามารถเลือกจำนวนเป็น 0 หรือต่ำกว่าได้</p>";
                $isCheck = 0;
            }
            if(personInParty == "" || personInParty == null){
                msgError += "<p>กรุณาเลือก ประมาณจำนวนคนในปาร์ตี้</p>";
                $isCheck = 0;
            }
            // if(countCheckBoxHasCheck <= 0){
            //     msgError += "<p>กรุณาเลือก เน้นสินค้าจำพวก อย่างน้อย 1 อย่าง</p>";
            //     $isCheck = 0;
            // }
            if(isOtherOption == 1){
                if(otherOption == ""){
                    msgError += "<p>กรุณากรอกข้อมูล "+personInParty+"</p>";
                    $isCheck = 0;
                }else{
                    if(parseInt(otherOption) > parseInt(maxPersonInparty)){
                        msgError += "<p>ท่านไม่สามารถกรอกจำนวนเกินกว่าจำนวนสูงสุดได้</p>";
                        $isCheck = 0;
                    }
                }
            }

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
                    url:'/shopping-cart-add-diy',
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
        function minusClick(){
            var qty = $("#quantity").val();
            qty = parseInt(qty);

            var qty_new = qty-1;
            if(qty_new < 0 ){
                qty_new = 0;
            }
            $("#quantity").val(qty_new);
        }

        function plusClick(){
            var qty = $("#quantity").val();
            qty = parseInt(qty);

            var qty_new = qty+1;
            
            $("#quantity").val(qty_new);
        }
        $(document).ready(function () {
            $('.SlectBox').SumoSelect({
                forceCustomRendering: false
            });

            var maxLimit = 100;
            $('#description').keyup(function () {
                var lengthCount = this.value.length;              
                $('#spnCharLeft').text('( '+lengthCount+'/100 )');
            });

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
        });
    </script>
</body>

</html>