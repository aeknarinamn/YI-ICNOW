<?php 
    $count = 0;
    $lineUserId = $_COOKIE['line-user-id'];
    $shoppingCart = \YellowProject\ICNOW\ShoppingCart\ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
    if($shoppingCart){
        $shoppingCartItems = \YellowProject\ICNOW\ShoppingCart\ShoppingCartItem::where('shopping_cart_id',$shoppingCart->id)->get();
        $count = $shoppingCartItems->count();
    }
?>
<header>
    <div class="logo-image-wrap">
        <a href="/home-page">
            <img src="/icnow/resources/images/walls-logo.png" alt="Wall's Logo">
        </a>
    </div>
    <div class="nav-wrap">
        <ul class="nav">
            <li>
                <a href="/profile-page" class="nav-link">ข้อมูลส่วนตัว</a>
            </li>
            <li>
                <a href="/how-to-buy-page" class="nav-link">วิธีการสั่งซื้อ</a>
            </li>
            <li>
                <a href="/contact" class="nav-link">ติดต่อเรา</a>
            </li>
            <li>
                <a href="/shopping-cart">
                    <img class="icon-cart" src="/icnow/resources/images/icon-cart.png" alt="">
                    <em class="noti">{{$count}}</em>
                </a>
            </li>
        </ul>
    </div>
</header>