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
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/contact-us.css">
    <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="https://raw.githubusercontent.com/kartik-v/bootstrap-star-rating/master/css/star-rating.min.css">
    <link type="text/css" rel="stylesheet" href="https://raw.githubusercontent.com/kartik-v/bootstrap-star-rating/master/css/star-rating.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/address.css">
    <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
     <style type="text/css">
        .contact-inner-wrap p{line-height: .9;}
        .star-rating {
          line-height:32px;
          font-size:2em;
        }
        .star-rating-2 {
          line-height:32px;
          font-size:2em;
        }
        .star-rating-3 {
          line-height:32px;
          font-size:2em;
        }
        .star-rating-4 {
          line-height:32px;
          font-size:2em;
        }

        .star-rating .fa-star{color: red;}
        .star-rating-2 .fa-star{color: red;}
        .star-rating-3 .fa-star{color: red;}
        .star-rating-4 .fa-star{color: red;}
    </style>
</head>
<body>
    <div class="container">
        <form id="form-submit" action="{{ action('ICNOW\View\CustomerOrderController@submitRating') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" id="user_id" name="user_id" value="">
            <input type="hidden" id="order_id" name="order_id" value="{{$order_id}}">
            <section>
                <div class="contact-wrap">
                    <div class="deliver-date">
                        <div class="deliver-date-title"><font size="5px">คุณภาพสินค้า</font></div>
                        <div class="star-rating">
                            <span class="fa fa-star-o" data-rating="1"></span>
                            <span class="fa fa-star-o" data-rating="2"></span>
                            <span class="fa fa-star-o" data-rating="3"></span>
                            <span class="fa fa-star-o" data-rating="4"></span>
                            <span class="fa fa-star-o" data-rating="5"></span>
                            <input type="hidden" id="rating_1" name="rating_1" class="rating-value" value="0">
                        </div>
                        <div class="deliver-date-title"><font size="5px">ความรวดเร็วในการให้บริการ</font></div>
                        <div class="star-rating-2">
                            <span class="fa fa-star-o" data-rating="1"></span>
                            <span class="fa fa-star-o" data-rating="2"></span>
                            <span class="fa fa-star-o" data-rating="3"></span>
                            <span class="fa fa-star-o" data-rating="4"></span>
                            <span class="fa fa-star-o" data-rating="5"></span>
                            <input type="hidden" id="rating_2" name="rating_2" class="rating-value" value="0">
                        </div>
                        <div class="deliver-date-title"><font size="5px">มารยาทในการให้บริการโดยรวม</font></div>
                        <div class="star-rating-3">
                            <span class="fa fa-star-o" data-rating="1"></span>
                            <span class="fa fa-star-o" data-rating="2"></span>
                            <span class="fa fa-star-o" data-rating="3"></span>
                            <span class="fa fa-star-o" data-rating="4"></span>
                            <span class="fa fa-star-o" data-rating="5"></span>
                            <input type="hidden" id="rating_3" name="rating_3" class="rating-value" value="0">
                        </div>
                        <div class="deliver-date-title"><font size="5px">ความพึงพอใจโดยรวมในบริการ</font></div>
                        <div class="star-rating-4">
                            <span class="fa fa-star-o" data-rating="1"></span>
                            <span class="fa fa-star-o" data-rating="2"></span>
                            <span class="fa fa-star-o" data-rating="3"></span>
                            <span class="fa fa-star-o" data-rating="4"></span>
                            <span class="fa fa-star-o" data-rating="5"></span>
                            <input type="hidden" id="rating_4" name="rating_4" class="rating-value" value="0">
                        </div>
                        <div class="deliver-date-title"><font size="5px">ข้อเสนอแนะเพิ่ม</font></div>
                        <textarea cols="50" rows="4" name="suggestion"></textarea>
                    </div>
                </div>
            </section>
            <footer>
                <div class="footer-btn">
                    <div class="footer-btn-column is-32 is-lg-20">
                        
                    </div>
                    <div class="footer-btn-column is-68 is-lg-80 text-right">
                        <button type="button" class="add-to-cart-btn" id="continueBtn" onclick="submitData()">
                            <img src="/icnow/resources/images/btn-continue.png" style="width: 141px; height: 41px; " alt="Button">
                        </button>
                    </div>
                </div>
            </footer>
        </form>
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

    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        function closeModal(){
            $('#alertModal').hide();
        }

        var $star_rating = $('.star-rating .fa');
        

        var SetRatingStar = function() {
          return $star_rating.each(function() {
            if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
              return $(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
              return $(this).removeClass('fa-star').addClass('fa-star-o');
            }
          });
        };

        $star_rating.on('click', function() {
          $star_rating.siblings('input.rating-value').val($(this).data('rating'));
          return SetRatingStar();
        });

        SetRatingStar();

        var $star_rating_2 = $('.star-rating-2 .fa');

        var SetRatingStar2 = function() {
          return $star_rating_2.each(function() {
            if (parseInt($star_rating_2.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
              return $(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
              return $(this).removeClass('fa-star').addClass('fa-star-o');
            }
          });
        };

        $star_rating_2.on('click', function() {
          $star_rating_2.siblings('input.rating-value').val($(this).data('rating'));
          return SetRatingStar2();
        });

        SetRatingStar2();

        var $star_rating_3 = $('.star-rating-3 .fa');

        var SetRatingStar3 = function() {
          return $star_rating_3.each(function() {
            if (parseInt($star_rating_3.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
              return $(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
              return $(this).removeClass('fa-star').addClass('fa-star-o');
            }
          });
        };

        $star_rating_3.on('click', function() {
          $star_rating_3.siblings('input.rating-value').val($(this).data('rating'));
          return SetRatingStar3();
        });

        SetRatingStar3();

        var $star_rating_4 = $('.star-rating-4 .fa');

        var SetRatingStar4 = function() {
          return $star_rating_4.each(function() {
            if (parseInt($star_rating_4.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
              return $(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
              return $(this).removeClass('fa-star').addClass('fa-star-o');
            }
          });
        };

        $star_rating_4.on('click', function() {
          $star_rating_4.siblings('input.rating-value').val($(this).data('rating'));
          return SetRatingStar4();
        });

        SetRatingStar4();

        function submitData()
        {
            $('#form-submit').submit();
        }

        $(document).ready(function() {
            liff.init(
                data => {
                    // Now you can call LIFF API
                    const userId = data.context.userId;
                    $('#user_id').val(userId);
                },
                err => {
                    // LIFF initialization failed
                }
            );
        });
    </script>
</body>

</html>